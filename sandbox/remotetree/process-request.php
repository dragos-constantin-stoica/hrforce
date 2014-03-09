<?php

// include classess
include '../../config/config.php';
include_once '../../include/mysqlCNX.php';
include_once '../../include/tree.php';

// create db connection
$dbCNX = new mysqlCNX($db_host, $db_user, $db_pass, $db_name);
if(!$dbCNX->db_check){
	echo '({ success: false, errors: { reason: "Server connection failed. Contact System Administrator." }})';
        exit;
}

$config = $_POST;
$config["db"] = $dbCNX;
$mytree = new Tree($config);
echo json_encode($mytree->dispatchCMD());


function moveTreeNode($otree) {
	$error = $otree->moveNode();
	echo "string" === gettype($error) 
		? '{"success":false,"error":"' . $nodeID . '"}'
		: '{"success":true}'
	;
} // eo function moveTreeNode


 
?>
