<?php
// TREEGRID PE COMPANY STRUCTURE
// Quick and Dirty solution
// 

include 'config/config.php';
include_once 'include/mysqlCNX.php';

// $parent is the parent of the children we want to see
// $level is increased when we go deeper into the tree,
//        used to display a nice indented tree
function display_children($parent, $branch) {
   // retrieve all children of $parent
   global $dbCNX;
   $result = $dbCNX->query("SELECT ID, TITLE, TYPE FROM ORGANISATION WHERE PARENT_ID=".$parent." ORDER BY LFT_NODE;");

   // display each child
   while ($row = mysql_fetch_array($result)) {
       $count_result = $dbCNX->query("SELECT COUNT(ID) as CHILDREN FROM ORGANISATION WHERE PARENT_ID=".$row['ID']." ORDER BY LFT_NODE;");
       $count = mysql_fetch_assoc($count_result);
       if($count['CHILDREN']>0){
           // call this function again to display this
           // child's children
           array_push($branch,
                   array('text' => $row['TITLE'], 'children' => display_children($row['ID'], array())));
       }else{
           // this is a terminal node
           // leaf
           array_push($branch, array('text' => $row['TITLE'], 'leaf' => 'true'));
       }
   }
   return $branch;
}


$dbCNX = new mysqlCNX($db_host, $db_user, $db_pass, $db_name);
if(!$dbCNX->db_check){
	echo '({ success: false, errors: { reason: "Server connection failed. Contact System Administrator." }})';
}

$organization_tree = array();
$organization_tree = display_children(-1, $organization_tree);
echo json_encode($organization_tree);
?>