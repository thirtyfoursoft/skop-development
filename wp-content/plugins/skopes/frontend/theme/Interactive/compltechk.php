<?php
	include '../../../../../../wp-load.php';
	if (!class_exists('userinfo')) {
		include('core/class_userinfo.php');
	}

$objUser = new clsSkopes();
$obj = new userinfo();
$loggedin_id = get_current_user_id();

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


$check = $_POST['query'];  

switch ($check) {
	case "memberinfo":
	    $teamMembertotalCount = $obj->memberTotalCount($loggedin_id);
		$teamrestotalCount = $obj->checkResValue($loggedin_id);	
		
		$totalCountRespValues    = $_REQUEST['totalCountRespValues'];
		$totalCountEffortsValues = $_REQUEST['totalCountEffortsValues'];
		$totalResp               = $_REQUEST['totalResp'];
		
		//echo $totalResp .'=='. $totalCountRespValues .'=='. $totalResp .'=='. $totalCountEffortsValues;
		//die;
		//if($stage == "two")
		//{ 
			$objUser->saveSectionStageAfterStage2($loggedin_id, 'FURTHER PROJECT DETAILS', 'Team responsibilities and effort', 'Complete');
		//}
		$objUser->saveSectionStageForGuidePageTickOthers2($loggedin_id, 'FURTHER PROJECT DETAILS', 'Complete inputting project background information', 'Complete', 'step3',  'Team responsibilities and effort', 'Selection criteria', 'Non-financial benefits');
		
		 if ($totalResp == $totalCountRespValues and $totalResp == $totalCountEffortsValues){
			$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='2' Where userid='$loggedin_id' AND section_name = 'membermoreinfo' ";
		}else{
			$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$loggedin_id' AND section_name = 'membermoreinfo' ";
		} 
		
		$wpdb->query($qry); 
		
		// get the section status value
		$selectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='membermoreinfo'";
		$morestatus = $wpdb->get_col($selectqry); 
		
		echo $morestatus[0];
		break;
	case "projectquesinfo":	
	
		$totalProjectQues = $obj->proquestioncount();
		$userProjectQues = $obj->userproquestioncount($loggedin_id);
		
		$postData = $_REQUEST['postData'];
		$postArr  = explode("&", $postData);
		$postSplitFinalArr = array();
		
		foreach($postArr as $key=>$val)
		{
		     $postSpliData  = explode("=", $val);
			 $postSplitFinalArr[$postSpliData[0]] = urldecode($postSpliData[1]);
		}
		//if($stage == "two")
		//{ 
			$objUser->saveSectionStageAfterStage2($loggedin_id, 'About your project', 'Project motivation, background and budget', 'Complete');
		//}
		$objUser->saveSectionStageForGuidePageTickOthers($loggedin_id, 'About your project', 'Begin inputting project background information', 'Complete', 'step2', 'Project motivation, background and budget', 'Team members', 'Stakeholders', 'Milestones');
		
		$objUser->SaveUserProject($postSplitFinalArr,$loggedin_id);
		
		$totalChecked = $_REQUEST['totalChecked'];
		// $totalProjectQues == $userProjectQues
		 if ($totalProjectQues == $totalChecked){
			$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='2' Where userid='$loggedin_id' AND section_name = 'projectquesinfo' ";
		}else{
			$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$loggedin_id' AND section_name = 'projectquesinfo' ";
		}
		
		$wpdb->query($qry); 
		
		// get the section status value
		$pselectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='projectquesinfo'";
		$pstatus = $wpdb->get_col($pselectqry); 
		
		echo $pstatus[0];
		
		break;
		
		case "teaminfo":	
			$totalTeamMembers = $obj->userTeamCount($loggedin_id);
			$filledTeamMembers = $obj->userTeamInfoCount($loggedin_id);
           
		    //if($stage == "two")
			//{ 
				$objUser->saveSectionStageAfterStage2($loggedin_id, 'About your project', 'Team members', 'Complete');
			//}
			$objUser->saveSectionStageForGuidePageTickOthers($loggedin_id, 'About your project', 'Begin inputting project background information', 'Complete', 'step2', 'Team members', 'Project motivation, background and budget', 'Stakeholders', 'Milestones');
			
			if ($totalTeamMembers == $filledTeamMembers){
				$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='2' Where userid='$loggedin_id' AND section_name = 'teaminialinfo' ";
			}else{
				$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$loggedin_id' AND section_name = 'teaminialinfo' ";
			}
			
			$wpdb->query($qry); 
			
			// get the section status value
			$tselectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='teaminialinfo'";
			$tstatus = $wpdb->get_col($tselectqry); 
			
			echo $tstatus[0];
		break;
		case "getRoiCalC":
		     $clsSkopesObj = new clsSkopes(); 
		     $hrate = get_usermeta($loggedin_id,"org_ques_9_state",true);
			 $lineitem_cost       = $_REQUEST['lineitem_cost'];
			 $lineitem_time       = $_REQUEST['lineitem_time'];
			 $lineitem_year       = $_REQUEST['lineitem_year'];
			 $roi_line = $clsSkopesObj->ROICalculation($hrate,$lineitem_time,$lineitem_cost,$lineitem_year);
			 echo $roi_line;
		break;
		case "ForgotPassword":
		    
		break;
		case "milestoneinfo":
			$totalMilestones = $obj->titleFilledMilestonesCount($loggedin_id);
			$filledMilestones = $obj->dateFilledMilestonesCount($loggedin_id);
			
			//if($stage == "two")
			//{ 
				$objUser->saveSectionStageAfterStage2($loggedin_id, 'About your project', 'Milestones', 'Complete');
			//}
			$objUser->saveSectionStageForGuidePageTickOthers($loggedin_id, 'About your project', 'Begin inputting project background information', 'Complete', 'step2', 'Milestones', 'Stakeholders', 'Team members', 'Project motivation, background and budget');
			
			if ($totalMilestones == $filledMilestones){
				$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='2' Where userid='$loggedin_id' AND section_name = 'milestonestatus' ";
			}else{
				$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$loggedin_id' AND section_name = 'milestonestatus' ";
			}
			
			$wpdb->query($qry); 
			
			// get the section status value
			$mselectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='milestonestatus'";
			$mstatus = $wpdb->get_col($mselectqry); 
			
			echo $mstatus[0];
		break;
	  case "benefitinfo":
		$totalABcount = $obj->userABtotalcount($loggedin_id);
		$userABcount = $obj->userABcount($loggedin_id);
		
		$totalNonFinBen       = $_REQUEST['totalNonFinBen'];
		$totalNonFinBenValues = $_REQUEST['totalNonFinBenValues'];
		//if($stage == "two")
		//{ 
			$objUser->saveSectionStageAfterStage2($loggedin_id, 'FURTHER PROJECT DETAILS', 'Non-financial benefits', 'Complete');
		//}
		$objUser->saveSectionStageForGuidePageTickOthers2($loggedin_id, 'FURTHER PROJECT DETAILS', 'Complete inputting project background information', 'Complete', 'step3', 'Non-financial benefits', 'Selection criteria', 'Team responsibilities and effort');
		
		if ($totalNonFinBen == $totalNonFinBenValues){
			$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='2' Where userid='$loggedin_id' AND section_name = 'benefitinfo' ";
		}else{
			$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$loggedin_id' AND section_name = 'benefitinfo' ";
		}

		$wpdb->query($qry); 



		// get the section status value
		$abselectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='benefitinfo'";
		$abstatus = $wpdb->get_col($abselectqry); 

		echo $abstatus[0];
	   break;
	   
	    case "stakehldrinfo":
		$totalSHcount = $obj->userSHtotalcount($loggedin_id);
		$userSHcount = $obj->userSHcount($loggedin_id);
		//if($stage == "two")
		//{ 
			$objUser->saveSectionStageAfterStage2($loggedin_id, 'About your project', 'Stakeholders', 'Complete');
		//}
		$objUser->saveSectionStageForGuidePageTickOthers($loggedin_id, 'About your project', 'Begin inputting project background information', 'Complete', 'step2', 'Stakeholders', 'Team members', 'Project motivation, background and budget', 'Milestones');
		
		if ($totalSHcount == $userSHcount){
			$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='2' Where userid='$loggedin_id' AND section_name = 'stakeholderinfo' ";
		}else{
			$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$loggedin_id' AND section_name = 'stakeholderinfo' ";
		}

		$wpdb->query($qry); 

		// get the section status value
		$shselectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='stakeholderinfo'";
		$shstatus = $wpdb->get_col($shselectqry); 

		echo $shstatus[0];
	   break;
	   
	   case "screteriainfo":
	   //selection_creteria
	    global $wpdb; 
		$select = $objUser->selectselectionCreteria($loggedin_id); 
		$fieldvalue = $select['fieldvalue'];
		$resultarray = unserialize($fieldvalue);
		$totalCountWeightValues = $_REQUEST['totalCountWeightValues']; 
		$imptotal               = $_REQUEST['imptotal'];
		 
		//if($stage == "two")
		//{ 
			$objUser->saveSectionStageAfterStage2($loggedin_id, 'FURTHER PROJECT DETAILS', 'Selection criteria', 'Complete');
		//} 
		$objUser->saveSectionStageForGuidePageTickOthers2($loggedin_id, 'FURTHER PROJECT DETAILS', 'Complete inputting project background information', 'Complete', 'step3', 'Selection criteria', 'Team responsibilities and effort', 'Non-financial benefits');

		$data               = $_REQUEST['data'];
		$dataarray = explode("&",$data);
		$finalArray = array();
		foreach($dataarray as $key=>$val)
		{
		      $valData = explode("=",$val);
			  $finalArray[$valData[0]] = $valData[1];
		} 
		
		foreach($finalArray as $key=>$value){
		  $getnum = explode('imp_weight_',$key);
		  
		  $fieldid = $getnum[1];
		  $selectionCreteria[$fieldid] = $value;
		} 
		$scvalues = serialize($selectionCreteria); 
		
		$getlist1 = $objUser->selectselectionCreteria($loggedin_id);
		 
		if(count($getlist1)>0){ 
			$query = "UPDATE ".$wpdb->prefix."specdoc_userlistinfo SET fieldvalue='".mysql_real_escape_string($scvalues)."' WHERE fieldname='selection_creteria' AND userid='$loggedin_id'"; 
		} else{					 
			$query = "INSERT INTO ".$wpdb->prefix."specdoc_userlistinfo (userid,fieldname,fieldvalue) values(".$loggedin_id.",'selection_creteria','".mysql_real_escape_string($scvalues)."')";
		} 
			
		 $wpdb->query($query);
		
		if(imptotal >100){
		     echo 0;
		}
		else { 
				//if (array_sum($resultarray) == 100){
				if ($totalCountWeightValues == 100){
					$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='2' Where userid='$loggedin_id' AND section_name = 'selection_creteria_info' ";
				}else{
					$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$loggedin_id' AND section_name = 'selection_creteria_info' ";
				}

				$wpdb->query($qry); 

				// get the section status value
				$shselectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='selection_creteria_info'";
				$shstatus = $wpdb->get_col($shselectqry); 

				echo $shstatus[0]; 
		}		
	   break;

	   case "keysProjectDate":
	   
			$objUser->saveSectionStageAfterStage2($loggedin_id, 'About your project', 'keysProjectDate', 'Complete');

			$objUser->saveSectionStageForGuidePageTickOthers($loggedin_id, 'About your project', 'Begin inputting project background information', 'Complete', 'step2', 'keysProjectDate', 'Stakeholders', 'Team members', 'Project motivation, background and budget');

			$qry = $wpdb->query("update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='2' Where userid='$loggedin_id' AND section_name = 'keysProjectDate' ");
			
			// get the section status value
			$mselectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='keysProjectDate'";
			$mstatus = $wpdb->get_col($mselectqry); 
			
			echo $mstatus[0];			
			
	   break;

	   case 'orgBasicInfo':
			$objUser->saveSectionStageAfterStage2($loggedin_id, 'ABOUT YOUR ORGANISATION', 'orgBasicInfo', 'Complete');	
			$updateqry = $wpdb->query("update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='2' Where userid='$loggedin_id' AND section_name = 'orgBasicInfo'");

			$query = $wpdb->get_col("SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='orgBasicInfo'");
			$orgBasicInfo = $query[0];
			
			echo $orgBasicInfo;

	   break;

	   case 'orgContactInfo':

			$objUser->saveSectionStageAfterStage2($loggedin_id, 'ABOUT YOUR ORGANISATION', 'orgContactInfo', 'Complete');	
			$updateqry = $wpdb->query("update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='2' Where userid='$loggedin_id' AND section_name = 'orgContactInfo'");

			$query = $wpdb->get_col("SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='orgContactInfo'");
			$orgBasicInfo = $query[0];
			
			echo $orgBasicInfo;
			
	   break;

	   case 'orgMissionInfo':

			$objUser->saveSectionStageAfterStage2($loggedin_id, 'ABOUT YOUR ORGANISATION', 'orgMissionInfo', 'Complete');	
			$updateqry = $wpdb->query("update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='2' Where userid='$loggedin_id' AND section_name = 'orgMissionInfo'");

			$query = $wpdb->get_col("SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='orgMissionInfo'");
			$orgMissionInfo = $query[0];
			
			echo $orgMissionInfo;
			
	   break;
}
?>
