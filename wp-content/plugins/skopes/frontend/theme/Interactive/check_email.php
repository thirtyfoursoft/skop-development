<?php include '../../../../wp-load.php'; 
session_start();
include('core/class_skopes.php');
$objUser = new clsSkopes();
$data = $_POST;
$loggedin_id = get_current_user_id();

//echo $objUser->CheckMemberByEmail($loggedin_id,$data['email'],$data['uid']);

echo $objUser->CheckMemberByEmail($loggedin_id,$data['email']);