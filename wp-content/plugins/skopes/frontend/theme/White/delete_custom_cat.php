<?php ob_start();
include("../../../../wp-config.php");
include("core/class_userinfo.php"); 	
$obj = new userinfo();
$catid = urldecode($_REQUEST['catid']);
$obj->deleteCategory($catid); 
$urlToRedirect = $_SERVER['HTTP_REFERER'];
ob_end_clean();
header("Location: $urlToRedirect");
?>