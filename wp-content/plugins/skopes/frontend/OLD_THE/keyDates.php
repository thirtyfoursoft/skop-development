<?php include '../../../../wp-load.php'; 

session_start();
include('core/class_userinfo.php');
$objUser = new userinfo();
$data = $_POST; 

global $wpdb;
$objUser2 = new clsSkopes(); 
$loggedin_id = get_current_user_id();

$totalCompanyQues = $objUser->cquestioncount();
$userCompanyQues = $objUser->usercquestioncount_custom($loggedin_id);
$totalProjectQues = $objUser->projectpagecount();
$userProjectQues = $objUser->projectpageusercount($loggedin_id);
$userProjectMore = $objUser->projectmoreCount($loggedin_id);
$userFaDisplay = $objUser2->checkFunaStatus($loggedin_id);
$userFaCount = $objUser2->checkFunaStatuscount($loggedin_id);

$result1 = $objUser->getuserstage($totalCompanyQues,$userCompanyQues,$totalProjectQues,$userProjectQues,$userFaCount);
$stage = ($result1==3 ? 'two': 'one') ;

$objUser2->saveSectionStageAfterStage2($loggedin_id, 'About your project', 'keysProjectDate', 'InProgress');

$objUser2->saveSectionStageForGuidePageTickOthers($loggedin_id, 'About your project', 'Begin inputting project background information', 'InProgress', 'step2', 'keysProjectDate', 'Stakeholders', 'Team members', 'Project motivation, background and budget');

$dataNew = serialize($data);
update_user_meta( $loggedin_id, '_keysProjectDate', $dataNew);

$updateqry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$loggedin_id' AND section_name = 'keysProjectDate' ";
$wpdb->query($updateqry);

$query = $wpdb->get_col("SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='keysProjectDate'");

$dateKeysStatus = $query[0];

echo $dateKeysStatus;

?>
