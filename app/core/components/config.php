<?php
 class Config implements IConfig
{
    public static function connection(){
            $servername = "localhost:8080";
            $username = "root";
            $password= "";
            $dbname = "ngpmcbus_contact_details";
            
            $dbConn =  mysqli_connect($servername,$username,$password,$dbname);
            if(!$dbConn){

                echo "<h1>connections failed</h1>";
            }
          return $dbConn;
            }
        }
?>