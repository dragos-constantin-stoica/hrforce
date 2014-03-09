<?php
/*
 * Login script
 * Connect to database and send JSON response
 */
include 'config/config.php';
include_once 'include/mysqlCNX.php';

$username = '';
$password = '';
if ( isset($_POST['loginUsername'])){
	$username = $_POST['loginUsername'];   // Get this from Ext
}
if ( isset($_POST['loginPassword'])){
	$password = $_POST['loginPassword'];   // Get this from Ext
}
if ( isset($_POST['companyID'])){
	$company = $_POST['companyID'];   // Get this from Ext
}

$dbCNX = new mysqlCNX($db_host, $db_user, $db_pass, $db_name);
if(!$dbCNX->db_check){
	echo '({ success: false, errors: { reason: "Server connection failed. Contact System Administrator." }})';
}

$users= $dbCNX->query("SELECT * FROM USERS WHERE USERNAME='".$username."' AND PASSWORD='".$password."';");
$hradmins= $dbCNX->query("SELECT COUNT(*) FROM HRADMINS WHERE USERNAME='".$username."' AND PASSWORD='".$password."';");

if(!$users or !$hradmins){	
		echo '({ success: false, errors: { reason: "Login failed. Contact System Administrator." }})';
	
}else{
	$user = mysql_fetch_assoc($users);
	$hradmin = mysql_fetch_row($hradmins);	
	$isUser=count($user['ID']);	
	$isHrAdmin=$hradmin[0];
	
	if($isUser==1 or $isHrAdmin==1){
		session_start();
		$_SESSION['USERNAME']=$username;
		$_SESSION['COMPANY']=$company;
		$_SESSION['HRADMIN']=$isHrAdmin;
		$_SESSION['ITADMIN']=$user['TYPE'];
		
			
		
		
		echo '{ success: true}';
		
	}else{
		echo '({ success: false, errors: { reason: "Login failed for user '.$username.' with password:'.$password.'. Try again." }})';
	}
}
mysql_free_result($users);
mysql_free_result($hradmins);
?>

