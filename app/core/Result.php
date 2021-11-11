<?php

enable_noExecute();

/**
 * The main result base class, responsible for return JSON data, setting header and flushing to client
 **/
class Result
{
    public $message = null;
    public $statusCode = 0;
    /**
     * @var object
     */
    public $data = null;
    /**
     * @var object
     */
    public $filter = null;
    public $custom = null;
    /**
     * constructor accepting either a message or an error
     */
    public function __construct($data, $code = 0, $msg = null, $filter = null)
    {
        $this->message = $msg;
        $this->statusCode = $code;
        $this->data = $data;
        $this->filter = $filter;
        $this->custom = new stdClass();
        $this->Prepare();
    }
    /**
     * Prepare template Method
     */
    public function Prepare()
    {
        if (!isset($this->data['Status'])) {

            $rows = 0;
            if (is_array($this->data)) {
                if (sizeof($this->data) > 0) {
                    $rows = sizeof($this->data);
                }
            }

            if ($this->data == null) {
                $this->data = [];
            }

            $success = [
                'Metadata' => [
                    'Count' => $rows,
                    'Filter' => $this->filter,
                ],
                'Status' => [
                    'Message' => $this->message,
                    'Code' => $this->statusCode
                ],
                'Data' => $this->data
            ];

            $this->data = $success;
        }
    }

    public function GetResult()
    {

        if ((empty($this->data['Status']['Message']) || !isset($this->data['Status']['Message'])) && !empty($this->message)) {
            $this->data['Status']['Message'] = $this->message;
            $this->data['Status']['Code'] = $this->statusCode;
        }


        $this->data['Custom'] = $this->custom;


        // $json = json_encode($this->data,JSON_NUMERIC_CHECK); 
        // JSON_NUMERIC_CHECK removes trailing zeroes and + in phone numbers
        // Robert:: Removing this for now

        $json = json_encode($this->data);
        if ($json == false) {
            $error = new ErrorResult("An internal server error has occured when trying to process data", 500);
            $error->FlushResult();
        }

        return $json;
    }

    public function FlushResult()
    {
        header('Content-Type: application/json');

        $json = $this->GetResult();

        echo $json;
        ob_end_flush();
        exit;
    }
}
