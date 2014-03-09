<?php
/**
 * @class mySQLDB
 * mySQL Database.  Stores records in $_SESSION
 */
class mySQLDB {

    private $dbCNX;
    private $sql_string;

    public function __construct() {
        global $db_host, $db_user, $db_pass, $db_name;
        $this->dbCNX = new mysqlCNX($db_host, $db_user, $db_pass, $db_name);
    }

    public function setSQL($sql){
        $this->sql_string = $sql;
    }

    // database pk
    public function pk() {    
        return $_SESSION['pk'];
    }
    // resultset
    public function rs() {
        global $request;
        return $_SESSION['rs']["$request->controller"];
    }
    
    public function insert() {
        $this->dbCNX->query($this->sql_string);
        $_SESSION['pk'] = mysql_insert_id();
    }

    public function addrs($rec) {
        global $request;
        array_push($_SESSION['rs']["$request->controller"], $rec);
    }

    public function update($idx, $attributes) {
        $this->dbCNX->query($this->sql_string);
        global $request;
        $_SESSION['rs']["$request->controller"][$idx] = $attributes;
    }

    public function destroy($idx) {
        $this->dbCNX->query($this->sql_string);
        global $request;
        return array_shift(array_splice($_SESSION['rs']["$request->controller"], $idx, 1));
    }

    public function getData(){
        $result= $this->dbCNX->query($this->sql_string);
        $resultArray = array();

        while(($resultArray[] = mysql_fetch_assoc($result)) || array_pop($resultArray));
        global $request;
        $_SESSION['rs']["$request->controller"] = $resultArray;
    }

}