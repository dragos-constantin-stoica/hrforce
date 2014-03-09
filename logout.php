<?php
/* 
 * Logout script
 * Clear session data
 */
session_start();
session_unset();
session_destroy();
unset($_SESSION['USERNAME']);
echo '{ success: true}';
?>
