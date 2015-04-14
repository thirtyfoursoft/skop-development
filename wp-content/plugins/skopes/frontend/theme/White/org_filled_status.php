<?php
	include '../../../../../../wp-load.php';
	if (!class_exists('userinfo')) {
		include('core/class_userinfo.php');
	}

$objUser = new clsSkopes();
$obj = new userinfo();

$loggedin_id = get_current_user_id();
$userID = $_POST['userID'];  
$status = $_POST['status'];  
$statusUse = $_POST['status'];

$totalCompanyQues = $obj->cquestioncount();
//$userCompanyQues = $obj->usercquestioncount($loggedin_id);
$userCompanyQues = $obj->usercquestioncount_custom($loggedin_id);
$totalProjectQues = $obj->projectpagecount();
$userProjectQues = $obj->projectpageusercount($loggedin_id);
$userProjectMore = $obj->projectmoreCount($loggedin_id);
$userFaDisplay = $objUser->checkFunaStatus($loggedin_id);
$userFaCount = $objUser->checkFunaStatuscount($loggedin_id);

$result1 = $obj->getuserstage($totalCompanyQues,$userCompanyQues,$totalProjectQues,$userProjectQues,$userFaCount);
$stage = ($result1==3 ? 'two': 'one') ; 

$getStatus = $objUser->getOrgStatus($userID);

if($getStatus == "no_record")
{
     echo $status = $objUser->setOrgStatus($userID, $status, 'insert');
}
else
{
     echo $status = $objUser->setOrgStatus($userID, $status, 'update');    
} 
 
//if($stage == "two")
//{
    if($statusUse == "filled")
	{
	      $objUser->saveSectionStageAfterStage2($loggedin_id, 'ABOUT YOUR ORGANISATION', 'ABOUT YOUR ORGANISATION', 'Complete');
		  
		  $objUser->saveSectionStageForGuidePageTick($loggedin_id, 'ABOUT YOUR ORGANISATION', 'Provide background about your organisation', 'Complete', 'step2');
	}
    else
	{
	      $objUser->saveSectionStageAfterStage2($loggedin_id, 'ABOUT YOUR ORGANISATION', 'ABOUT YOUR ORGANISATION', 'InProgress');
		  
		  $objUser->saveSectionStageForGuidePageTick($loggedin_id, 'ABOUT YOUR ORGANISATION', 'Provide background about your organisation', 'InProgress', 'step2');
	}	
//}  
?>
