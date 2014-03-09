<?php
/* 
 * Verify if the user is correctly authentified
 */
session_start();

if(isset ($_SESSION['USERNAME'])){
    echo '{success:true}';
}else {
    echo '{success:false}';
}
?>
