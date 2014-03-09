<?php
/**
 * mysqlCNX a class that is used to create objects that handle connections
 * to mySQL database
 * @author dragos
 */
class mysqlCNX {

        private $host = "";
        private $database = "";
        private $username = "";
        private $password = "";

        private $link;
        private $result;

        public $db_check = false;
        public $sql;

        function __construct($host="", $username="", $password="", $database=""){
            if (!empty($host)){ $this->host = $host; }
            if (!empty($username)){ $this->username = $username; }
            if (!empty($password)){ $this->password = $password; }
            if (!empty($database)){ $this->database = $database; }
                $this->link = mysql_connect($this->host,$this->username,$this->password);
                $this->db_check=mysql_select_db($this->database, $this->link);

                return $this->link;  // returns false if connection could not be made.
        }
        function query($sql){
                if (!empty($sql)){
                        $this->sql = $sql;
                        $this->result = mysql_query($sql, $this->link);
                        return $this->result;
                }else{
                        return false;
                }
        }
        function fetch($result=""){
                if (empty($result)){ $result = $this->result; }
                return mysql_fetch_row($result);
        }
        function error(){
            return mysql_errno($this->link) . ": " . mysql_error($this->link);
        }
        function __destruct(){
                mysql_close($this->link);
        }

}
?>
