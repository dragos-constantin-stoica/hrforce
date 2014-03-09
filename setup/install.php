<?php
/*
 * 
 */
include_once '../include/mysqlCNX.php';

$sqlFileToExecute = '../config/db.sql';
$db_host = $_POST['hostName'];
$db_name = $_POST['databaseName'];
$db_user = $_POST['userName'];
$db_pass = $_POST['userPassword'];
//write db login data to config file
$file = fopen("../config/config.php", "w+");
$str = "<?php
\$db_host = '$db_host';\n
\$db_name = '$db_name';\n
\$db_user = '$db_user';\n
\$db_pass = '$db_pass';\n
?>";
$put = fwrite($file, "$str");
if ($put) {
    @chmod("../config/config.php", 0755);
}
fclose($file);

$dbCNX = new mysqlCNX($db_host, $db_user, $db_pass, $db_name);
if (!$dbCNX->db_check) {
    echo '({ success: false, errors: { reason: "Server connection failed. Contact System Administrator." }})';
} else {

    // read the sql file
    $f = fopen($sqlFileToExecute, "r+");
    $sqlFile = fread($f, filesize($sqlFileToExecute));
    $sqlArray = explode(';', $sqlFile);
    foreach ($sqlArray as $stmt) {
        if (strlen($stmt) > 3 && substr(ltrim($stmt), 0, 2) != '/*') {
            $result = $dbCNX->query($stmt);
            if (!$result) {
                echo '({ success: false, errors: { reason: "' . $dbCNX->error() . ' on statement:' . $stmt . '" }})';
                break;
            }
        }
    }
    fclose($f);

    $dbCNX->query("INSERT INTO `USERS` (`USERNAME`, `PASSWORD`,`TYPE`) VALUES('" . $_POST['adminName'] . "', '" . $_POST['adminPassword'] . "','1');");
    echo '({success: true})';
}
?>
