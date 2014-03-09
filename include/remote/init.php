<?php
    session_start();
    require('../config/config.php');
    require('mysqlCNX.php');
    // base framework
    require(dirname(__FILE__).'/lib/mysql_db.php');
    require(dirname(__FILE__).'/lib/application_controller.php');
    require(dirname(__FILE__).'/lib/model.php');
    require(dirname(__FILE__).'/lib/request.php');
    require(dirname(__FILE__).'/lib/response.php');

    // require /models (Should iterate app/models and auto-include all files there)
    require(dirname(__FILE__).'/app/models/user.php');
    require(dirname(__FILE__).'/app/models/itadmin.php');
    require(dirname(__FILE__).'/app/models/hradmin.php');
    require(dirname(__FILE__).'/app/models/company.php');

    // Fake a database connection using _SESSION
    $dbh = new mySQLDB();