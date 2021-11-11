<?php

enable_noExecute();

class DataService
{
    public $conn = null;
    private $isConnected = false;
    private $isTransaction = false;
    private $server, $dbname;
    private $username, $password;

    public function __construct($config, &$conn = null)
    {
        $this->server = $config['dbServer'];
        $this->dbname = $config['dbName'];
        $this->username = $config['dbUser'];
        $this->password = $config['dbPassword'];

        if (isset($conn)) {
            $this->conn = $conn;
            $this->isConnected = true;
            $this->isTransaction = true;
        } else {
            $this->connect();
        }
    }

    public function __destruct()
    {
        if ($this->isConnected === true && $this->isTransaction === false) {
            $this->conn->close();
        }
    }

    private function connect()
    {

        if ($this->isConnected === true) {
            return;
        }

        try {
            $this->conn = new mysqli($this->server, $this->username, $this->password, $this->dbname);
            $this->conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 1);
            $this->conn->options(MYSQLI_CLIENT_COMPRESS, true);

            if ($this->conn->connect_error) {
                $result = new ErrorResult($this->conn->connect_error, 503);
                $result->FlushResult();
            } else {
                $this->isConnected = true;
            }

        } catch (Exception $e) {
            $error = $e->getMessage();
            $result = new ErrorResult($error, 503);
            $result->FlushResult();
        }
    }

    public function Begin()
    {
        $this->connect();
        $this->conn->autocommit(false);
        $this->isTransaction = true;
    }

    public function Commit()
    {
        $this->conn->commit();
        $this->conn->autocommit(true);
        $this->isTransaction = false;
    }

    public function Rollback()
    {
        $this->conn->rollback();
        $this->conn->autocommit(true);
        $this->isTransaction = false;
    }

    public function exec($sql)
    {
        try {

            if (startsWith($sql, 'CALL') === false) {
                $sql = 'CALL ' . $sql;
            } else if (startsWith($sql, 'call') === false) {
                $sql = 'call ' . $sql;
            }

            $this->connect();

            $resultArr = array();

            $result = [];

            if ($this->isTransaction) {
                $result = $this->conn->multi_query($sql);
            } else {
                $result = $this->conn->query($sql);
            }

            if (isset($result->num_rows)) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        $row = array_map('utf8_encode', $row);

                        array_push($resultArr, $row);
                    }
                }
            }

            if ($this->isTransaction) {

          
            }

            $this->ReportError($this->conn, $sql);
        
            return $resultArr;
        } catch (Exception $ex) {
            $this->Rollback();
        }
    }

    private function ReportError($conn, $qry)
    {
        $host = $_SERVER['HTTP_HOST'];
        if (contains($host, 'localhost')) {
            if ($conn->errno) {
                if ($conn->errno != 2014) {
                    $data = ['Message' => $conn->error, 'ErrorNO' => $conn->errno, 'SQLQuery' => $qry];
                    $result = new ErrorResult($data, 500);
                    $result->FlushResult();
                }
            }
        }
    }

    public function ExecStatement($sql, $rowbind=null)
    {
        try {
            $this->connect();

            $resultArr = array();

            $result = [];

            if ($this->isTransaction) {
                $result = $this->conn->multi_query($sql);
            } else {
                $result = $this->conn->query($sql);
            }

            if (isset($result->num_rows)) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $row = array_map('utf8_encode', $row);
                       
                        if (isset($rowbind)){
                            $newObj = $rowbind($row);
                            array_push($resultArr, $newObj);
                        }
                        else{
                            array_push($resultArr, $row);
                        }
                    }
                }
            }
            if (strpos(strtolower($sql), "insert") !== false) {
                $resultArr = $this->conn->insert_id;
            }

            $this->ReportError($this->conn, $sql);
        
            return $resultArr;
        } catch (Exception $ex) {
            $this->Rollback();
        }
    }
}
