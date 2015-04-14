<?php
	include '../../../../wp-load.php'; 
	session_start();
	//include('core/class_skopes.php');
	include('core/class_userinfo.php');
	$objUser = new clsSkopes();
	$data = $_POST; 
	$obj = new userinfo();
	$loggedin_id = get_current_user_id();

	$totalCompanyQues = $obj->cquestioncount();
	$userCompanyQues = $obj->usercquestioncount_custom($loggedin_id);
	$totalProjectQues = $obj->projectpagecount();
	$userProjectQues = $obj->projectpageusercount($loggedin_id);
	$userProjectMore = $obj->projectmoreCount($loggedin_id);
	$userFaDisplay = $objUser->checkFunaStatus($loggedin_id);
	$userFaCount = $objUser->checkFunaStatuscount($loggedin_id);

	$result1 = $obj->getuserstage($totalCompanyQues,$userCompanyQues,$totalProjectQues,$userProjectQues,$userFaCount);
	$stage = ($result1==3 ? 'two': 'one') ;

    $objUser->saveSectionStageAfterStage2($loggedin_id, 'FURTHER PROJECT DETAILS', 'riskManagementMoreInfo', 'InProgress');

	$objUser->saveSectionStageForGuidePageTickOthers2($loggedin_id, 'FURTHER PROJECT DETAILS', 'Complete inputting project background information', 'InProgress', 'step3',  'Team responsibilities and effort', 'Selection criteria', 'Non-financial benefits', 'riskManagementMoreInfo');

	if(!empty($data)) {
		$savedata = $obj->addRiskManagementMoreInfo( $data, $loggedin_id );
		//echo $msg = ($savedata == 1? '': $savedata);
	}

	if ($data['checkForRiskManagementMoreInfo'] == 1) {
			$qry = "UPDATE ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='2' WHERE userid='$loggedin_id' AND section_name = 'riskManagementMoreInfo' ";

		    $objUser->saveSectionStageAfterStage2($loggedin_id, 'FURTHER PROJECT DETAILS', 'riskManagementMoreInfo', 'Complete');

		$objUser->saveSectionStageForGuidePageTickOthers2($loggedin_id, 'FURTHER PROJECT DETAILS', 'Complete inputting project background information', 'Complete', 'step3',  'Team responsibilities and effort', 'Selection criteria', 'Non-financial benefits', 'riskManagementMoreInfo');
			    			
	} else {
			$qry = "UPDATE ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' WHERE userid='$loggedin_id' AND section_name = 'riskManagementMoreInfo' ";
	}

	$wpdb->query($qry);

	$query = "SELECT section_status FROM wp_specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='riskManagementMoreInfo'";
	$riskStatus = $wpdb->get_col($query);
	
	$riskStat =  $riskStatus[0];

	echo $riskStat;

?>
