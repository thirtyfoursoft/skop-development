<?php include '../../../../wp-load.php';  
session_start(); 
include(RC_TC_BASE_DIR."/frontend/core/class_userinfo.php");
$objUser = new clsSkopes();
$obj = new userinfo(); 
if( isset($_REQUEST['userID']) and $_REQUEST['userID'] != '' )
{
    $userID = $_REQUEST['userID'];
}
else
{
    $userID = 0;
}

if($userID > 0)
{
    $_SESSION[$userID]['org_save_status'] = 'done';
}
?>