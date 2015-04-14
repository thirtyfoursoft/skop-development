<?php

Class getdocx{
 public function createuserdocx($template_filename,$getfilename, $userid = null){

	$pathurl =  plugin_dir_url(__FILE__); 
	$parentpath = dirname(plugin_dir_path( __FILE__ ));
	$dirpathurl =  dirname(plugin_dir_url(__FILE__)); 
	$dirpath = plugin_dir_path( __FILE__ );
	$templatepath = $parentpath.'/templates';
	$loggedin_id = get_current_user_id();
	
	require_once dirname($parentpath).'/classes/CreateDocx.inc';  
	$docx = new CreateDocx();
	global $wp,$wpdb;

	if ($userid == null) {
	    $userid = get_current_user_id();
	}

	$loggedin_id = $userid;

	$objUser = new clsSkopes();
	
	$upload_dir = wp_upload_dir();
	$wpContentUploadPath = $upload_dir['basedir'].'/companylogos/thumbs/'.$userid;
  
    $upload_dir = wp_upload_dir();
	$logoPath = $upload_dir['basedir'].'/companylogos/thumbs/'.$userid;
	$upload_dir = wp_upload_dir();
	$logoUrlPath = $upload_dir['baseurl'].'/companylogos/thumbs/'.$userid;
	
	$quy = "SELECT * FROM wp_usermeta WHERE user_id ='$userid'"; 
	$myrows = $wpdb->get_results($quy);
	 
	/*  echo "<pre>";
	 print_r($myrows);
	 die; */
	 
	foreach($myrows as $key=>$value){
 
	  /** company information **/  
	 
	  // get company logo
	  
	  if($value->meta_key == 'com_logo'){

		  $com_logo = $value->meta_value; 
		  $companylogo = $logoUrlPath.'/'.$com_logo;
          $companylogoAbs = $wpContentUploadPath.'/'.$com_logo;		  
		  $orgLogo = $com_logo;
	  }
	  
	 //  companyName
	  if($value->meta_key == 'comp_ques2'){
		  $org_ques_2 = nl2br(stripslashes(trim($value->meta_value)));
	  } 	  
	 
	 // street address
	  if($value->meta_key == 'comp_ques3'){
		  $org_ques_3 = nl2br(stripslashes(trim($value->meta_value))); 
	  }
	  
	  //city
	  if($value->meta_key == 'comp_ques4'){
		  $org_ques_4 = nl2br(stripslashes(trim($value->meta_value)));
	  }
	  
	  // state
	   if($value->meta_key == 'comp_ques5'){
		  $org_ques_5 = nl2br(stripslashes(trim($value->meta_value)));
	  }
	  
	  //Postcode / Zip code:
	  
	  if($value->meta_key == 'comp_ques6'){
		  $org_ques_6 = nl2br(stripslashes(trim($value->meta_value)));
	  }
	  
	    //Organisations Mission:
		//echo "Res: ".$value->meta_key; echo "<br>";
	   if(trim($value->meta_key) == 'comp_ques7'){ 
	      // echo "if<br><br>";
		  //$org_ques_7 = nl2br(stripslashes(trim($value->meta_value))); 
		  $org_ques_7 = "<div style='font-family:Century Gothic;font-size:14.5px !important;'>".trim(htmlentities($value->meta_value))."</div>";
		  $final_org_ques_7 = $org_ques_7;
	  }
	  else
	  {   //echo "else hi<br><br>";
	      $org_ques_7 = "<div style='font-family:Century Gothic;font-size:14.5px !important;'>&nbsp;</div>";
	  }
	  
	  // Organisational goals: 
	  if($value->meta_key == 'org_ques_8'){

		 // $org_ques_8 = nl2br(stripslashes($value->meta_value)); 
            $org_ques_8 = "<div style='font-family:Century Gothic;font-size:14.5px !important;'>".trim(htmlentities($value->meta_value))."</div>"; 
			$final_org_ques_8 = $org_ques_8;
	  }
	  else
	  {
	      $org_ques_8 = "<div style='font-family:Century Gothic;font-size:14.5px !important;'>&nbsp;</div>";
	  }
	  
	  //Estimated hourly rate for an internal team member:
	  if($value->meta_key == 'org_ques_9_state'){

		  $org_ques_9 = nl2br(stripslashes($value->meta_value)); 

	  }
	  else
	  {
	      $org_ques_9 = "<div style='font-family:Century Gothic;font-size:14.5px !important;'>&nbsp;</div>";
	  }
	  
	  if($value->meta_key == 'org_ques_10_state'){

		  $org_ques_10 = nl2br(stripslashes($value->meta_value)); 

	  }
	  else
	  {
	      $org_ques_10 = "<div style='font-family:Century Gothic;font-size:14.5px !important;'>&nbsp;</div>";
	  }
	  
	  /** company information ends here **/
	  
	  /** Project information **/
	  
	  
	  //Project document authors:
	  
	  if($value->meta_key == 'authors'){
		  $pro_ques_1 = nl2br(stripslashes($value->meta_value)); 
	  }
	  
	  //Projects contribution to your organisations mission:
	  
	  if($value->meta_key == 'pro_ques2'){
		  $pro_ques_2 = nl2br(stripslashes(trim($value->meta_value)));
	  } 
	  
	  //Project contribution to (medium term) organisational goals:
	  
	  if($value->meta_key == 'pro_ques3'){
		  $pro_ques_3 = nl2br(stripslashes(trim($value->meta_value))); 
	  }
	  
	  //Expected project budget:
	  if($value->meta_key == 'pro_ques4'){
		  $pro_ques_4 = nl2br(stripslashes(trim($value->meta_value))); 
	  }
	  
	  //Your projects name:
	  if($value->meta_key == 'pro_ques5'){
		  $pro_ques_5 = nl2br(stripslashes(trim($value->meta_value))); 
	  }
	  
	  if($value->meta_key == 'pro_ques6'){
		  $pro_ques_6 = nl2br(stripslashes(trim($value->meta_value))); 
	  }
	  
	  if($value->meta_key == 'pro_ques7'){
		  $pro_ques_7 = nl2br(stripslashes(trim($value->meta_value))); 
	  }
	  
	  if($value->meta_key == 'pro_ques8'){
		  $pro_ques_8 = nl2br(stripslashes(trim($value->meta_value))); 
	  }
	  
	  if($value->meta_key == 'pro_ques9'){
		  $pro_ques_9 = nl2br(stripslashes(trim($value->meta_value))); 
	  }
	  
	  if($value->meta_key == 'pro_ques10'){
		  $pro_ques_10 = nl2br(stripslashes(trim($value->meta_value))); 
	  }
	  
	 
	   
	  if($value->meta_key == 'tool_data'){
		  $tool_data = nl2br(stripslashes($value->meta_value)); 
	  } 
	  
  
}
//die;  


$sd = $this->msStartDate($userid);
$startDate = date_create(date('Y-m-d', strtotime($sd))); 
$ed = $this->msEndDate($userid);
$endDate = date_create(date('Y-m-d', strtotime($ed)));
$interval = date_diff($startDate, $endDate);
$numberofdays = $interval->format('%a'); 
$publishDate = date('d F Y');

$catfeaturecount = array();
$catfeatureitem = array();
$catCompleteIds = array();
$catUnknownIds = array();
$catInScopeIds = array();
$catNotReqIds = array();

$teamqry = "SELECT * FROM `wp_specdoc_userteaminfo` WHERE userid ='$userid'";
$teamrows = $wpdb->get_results($teamqry);

$trArray = $this->getteamRoleTable($teamrows);
$teamMembers = $this->getteamNames($teamrows);
$teamMembersList = $this->getteamNamesList($teamrows);


$catres = $objUser->GetToolData($loggedin_id);
//echo $loggedin_id.' d <pre>'; print_r($catInScopeIds); die;
if($catres)
{
	$complete_cnt=0;
	$incomplete_cnt=0;
	$not_cnt=0;
	$inscope = 0;
	$totallineitemcnt=0;
	$complete_line_cnt=0;
	$include_line_cnt=0;
	$roi_line=0;
	$highcnt=0;
	$mediumcnt=0;
	$lowcnt=0;
	$lineroi= 0 ;
	$totalbenefitcount = 0;
	/** check category status
		0 - unknown
		1 - in scope
		2 - not in scope
		3 - in scope
	**/
	
	$totalROI = 0;
	for($i=0;$i<sizeof($catres);$i++)
	{
	
	   //echo "<br>".$catres[$i][CATEGORY_ID]."--".$catres[$i][CATEGORY_STATUS];
		if($catres[$i][CATEGORY_STATUS]==0){
			$catIds[] = $catres[$i][CATEGORY_ID];
			$catUnknownIds[] = $catres[$i][CATEGORY_ID];
			$incomplete_cnt++;
		} 
		else if($catres[$i][CATEGORY_STATUS]==1){
			$catIds[] = $catres[$i][CATEGORY_ID];
			$catCompleteIds[] = $catres[$i][CATEGORY_ID];			
			$complete_cnt++;
		}
		else if($catres[$i][CATEGORY_STATUS]==2){
		    $catNotReqIds[] = $catres[$i][CATEGORY_ID];
			$not_cnt++;
		}
		else if($catres[$i][CATEGORY_STATUS] == 3){
		    $catInScopeIds[] = $catres[$i][CATEGORY_ID];
		    $catIds[] = $catres[$i][CATEGORY_ID];
			$inscope++;
		}
		else {
		    $catInScopeIds[] = $catres[$i][CATEGORY_ID];
		}
				
		// for lineitems/features of a Functional Area(category)
		$totallineitemcnt = $totallineitemcnt + count($catres[$i][CATEGORY_LINEITEMS]);

		if($catres[$i][CATEGORY_LINEITEMS])
		{
			$complete_line_cnt=0;
			$lineroi = 0; 
			foreach($catres[$i][CATEGORY_LINEITEMS] as $lineitem)
			{
				$how_often = $lineitem[LINEITEM_YEAR];
				$cost = $lineitem[LINEITEM_COST];
				$time = $lineitem[LINEITEM_TIME];
				//$hrate = get_usermeta($userid,"hourly_rate",true);
				$hrate=get_usermeta($userid,"org_ques_9_state",true);
				//ROI calculation
				if($objUser->ROICalculation($hrate,$time,$cost,$how_often) > 0)
				{  
				     $totalROI = $totalROI + 1;
				}				
				//total ROI calculation
				$roi_line = $roi_line+$objUser->ROICalculation($hrate,$time,$cost,$how_often);
				$lineroi = $lineroi+$objUser->ROICalculation($hrate,$time,$cost,$how_often); 
				
				if($lineitem[LINEITEM_IS_BENEFIT]==1){
					$totalbenefitcount = $totalbenefitcount+1;
				}
								
					$complete_line_cnt++;
					$catfeaturecount[$catres[$i][CATEGORY_ID]] = $complete_line_cnt;
					
					$catfeaturecountwithroi[$catres[$i][CATEGORY_ID]] = array('count'=>$complete_line_cnt,'roi'=>$lineroi);
					
					if($lineitem[LINEITEM_IMPORTANCE] == $high_val)
						$importance = 'high';
					else if($lineitem[LINEITEM_IMPORTANCE] == $medium_val)
						$importance = 'medium';
					else if($lineitem[LINEITEM_IMPORTANCE] == $low_val)
						$importance = 'low';
						
					$catfeatureitem[$catres[$i][CATEGORY_ID]][] = array('itemid'=>$lineitem[LINEITEM_ID],'importance'=>$importance,'fa_total_ROI'=>$lineroi);
					
					//total include number feature count
					$include_line_cnt++;
				//}

				$low_val = $objUser->getDefaultData('Impotrtance_Low_Value');
				$low_val = unserialize($low_val->text);
				if($low_val<0 || $low_val=="")
					$low_val=0;

				$medium_val = $objUser->getDefaultData('Impotrtance_Medium_Value');
				$medium_val = unserialize($medium_val->text);
				if($medium_val<0 || $medium_val=="")
					$medium_val=1;

				$high_val = $objUser->getDefaultData('Impotrtance_High_Value');
				$high_val = unserialize($high_val->text);
				if($high_val<0 || $high_val=="")
					$high_val=2;

				/*echo "<br>".$lineitem[LINEITEM_ID]."--".$lineitem[LINEITEM_IMPORTANCE];*/
				if($lineitem[LINEITEM_IMPORTANCE] == $high_val)
					$highcnt++;
				else if($lineitem[LINEITEM_IMPORTANCE] == $medium_val)
					$mediumcnt++;
				else if($lineitem[LINEITEM_IMPORTANCE] == $low_val)
					$lowcnt++;
				else
					$lowcnt++;
			} 
			
	}

	
  } 
    // total categories/functional areas
	$totalCats = $catIds;  // 
		
	// in scope categories/functional areas
	$inScopeCats = $inscope;
	
	$inScopePlusCompCats = $inScopeCats + $complete_cnt;
	
	// get category names with feature count and ROI,benefits count
	$catfeatureRes = $this->getCatAndFeaturecount($totalCats,$catfeaturecount);
	
	// get category names with feature count and ROI(tableformat)
	$result = $this->getCatAndFeaturecountTable($totalCats,$catfeaturecountwithroi);
	
	// get category names with feature count and ROI(tableformat)
	//$result1 = $this->getCatAndFeaturecountDetailTable($inScopeCats,$catfeatureitem);
	
	$reshtml = $this->getCatAndFeaturecountDetailHTML($totalCats,$catfeatureitem);
	
	$not_data = $not_cnt*100/sizeof($catres);
	$complete_data = $complete_cnt*100/sizeof($catres);
	$total_complete_data =($complete_cnt*100/sizeof($catres))+$not_data;
	$incomplete_data = $incomplete_cnt*100/sizeof($catres);
	$total_data = $total_complete_data+$incomplete_data;
}// end of if

$settings = array(
    'header' => true
); 
   
$totalFnArea = array_merge($catCompleteIds, $catInScopeIds);

$msArray  = $this->getMilestoneTable($userid);
$msArray2 = $this->getMilestoneTable2($userid);
$evalCritArray = $this->getEvalCriteriaData($userid, $objUser);
$stArray = $this->getStakeholderTable($userid);
$functionalTextTable = $this->getCatTitleAndTextTable($catInScopeIds);

$evalCrit2Array = $this->getEvalCriteriaData2($userid, $objUser);

$roiArray  = $this->getROITable($userid, $totalFnArea, $objUser);

$docx->addTemplate($templatepath.'/'.$template_filename.'.docx');
//$docx->replaceListVariable('LISTVAR', $teamMembers);

//$docx->addTable($Milestones, $paramsTable);
 
$docx->addTemplateVariable($result,'table',$settings);
$docx->addTemplateVariable($msArray,'table',$settings);
$docx->addTemplateVariable($msArray2,'table',$settings);
$docx->addTemplateVariable($roiArray,'table',$settings);
$docx->addTemplateVariable($evalCritArray,'table',$settings);
$docx->addTemplateVariable($evalCrit2Array,'table',$settings);
$docx->addTemplateVariable($stArray,'table',$settings);
$docx->addTemplateVariable($trArray,'table',$settings);
$docx->addTemplateVariable($functionalTextTable,'table',$settings); 

$functionalAreaCount = count($catIds); // total functional area count
//$functionalAreaCount = count($totalCats);
/**
** in scope functional area headings 
**/
//$functionaAreas = $this->getCatTitleAndTextTablefAreas($catInScopeIds); 

$functionaAreas = $this->getCatTitleAndTextTablefAreas($totalFnArea); 
//$functionaAreas = $this->getCatTitleAndTextTablefAreas($catInScopeIds); 
$functionaArea  = $this->getCatHeadings($catInScopeIds);
//$functionaArea  = $this->getCatHeadings($catInScopeIds);

/** 
 ** in scope functional headings with text 
**/
//$functionalText = $this->getCatHeadingsAndText($catInScopeIds,1);
  
$functionalText = $this->getCatHeadingsAndText($totalFnArea,1); 

$fullyScopedfunctionalText = $this->getCatHeadingsAndTextRFQResp($catCompleteIds, 1, $userid, $objUser);

if( is_array($catCompleteIds) and !empty($catCompleteIds) ) { 
 $settings2 = array(
    'header' => false
); 
  // $lineitemsD = $this->lineitemsData($catCompleteIds, $userid, $objUser);  
  // $docx->addTemplateVariable($lineitemsD,'table',$settings2);
   
   $addCompletedFAsinTOC = $this->addCompletedFAsinTOC($catCompleteIds, $userid, $objUser);
   $docx->replaceTemplateVariableByHTML('compled_FAs', 'block', "$addCompletedFAsinTOC");
   
   $lineitemsDataOthers = $this->lineitemsDataAll($catCompleteIds, $userid, $objUser);
   $docx->replaceTemplateVariableByHTML('other_FA_features', 'block', "$lineitemsDataOthers"); 
   
   //$lineitemsD2 = $this->lineitemsDataRFQRes($catCompleteIds, $userid, $objUser);
   //$docx->addTemplateVariable($lineitemsD2,'table',$settings);
   
   $lineitemsDataOthers2 = $this->lineitemsDataAllRFQRes($catCompleteIds, $userid, $objUser);
   $docx->replaceTemplateVariableByHTML('other_FA_features2', 'block', "$lineitemsDataOthers2");
   
   $FA_Scope_Features_Functions = $this->getFAScopeFeaturesFunctions($catCompleteIds, $userid, $objUser);
   $docx->replaceTemplateVariableByHTML('FA_Scope_Features_Functions', 'block', "$FA_Scope_Features_Functions");
   
   $FA_ROI_Benefit_Features_Functions = $this->getFAROIBenefitFeaturesFunctions($catCompleteIds, $userid, $objUser);
   $docx->replaceTemplateVariableByHTML('FA_ROI_Benefit_Features_Functions', 'block', "$FA_ROI_Benefit_Features_Functions");
}
else {  
   /* $docx->replaceTemplateVariableByHTML('compled_FAs', 'block', " "); */
   
   $docx->replaceTemplateVariableByHTML('other_FA_features', 'block', "<b>Complete Specified Functional Areas are not available.</b>"); 
   
   $docx->replaceTemplateVariableByHTML('other_FA_features2', 'block', "<b>Complete Specified Functional Areas are not available.</b>");
   
   $docx->replaceTemplateVariableByHTML('FA_Scope_Features_Functions', 'block', "<b>Complete Specified Functional Areas are not available.</b>");
    
   $docx->replaceTemplateVariableByHTML('FA_ROI_Benefit_Features_Functions', 'block', "<b>Complete Specified Functional Areas are not available.</b>");
} 

//$RFQfunctionalText = $this->getCatHeadingsAndText($catInScopeIds,2);
$RFQfunctionalText = $this->getCatHeadingsAndText($catInScopeIds,2);

//$tabl = $this->getCatHeadingsAndText($catInScopeIds,3);
$tabl = $this->getCatHeadingsAndText($catInScopeIds,3);
//$RFQfunctionalText = $this->getCatTitleAndText($catInScopeIds);

$team_lead_name = $this->getTlName($userid);
$team_lead_email = $this->getTlEmail($userid);
$stBenefits = $this->allStakeholdersCount($userid);

$docx->addTemplateVariable('projectname', "$projectName");

 if(!empty($com_logo)){

	list($width, $height, $type, $attr) = getimagesize($companylogo);

	$max_height = 200;
	$max_width = 200;

	if($height > $max_height){
		$newWidth = ($max_height / $height) * $width;
		$newHeight = $max_height;
		$params = 'width ="$newWidth" height="$newHeight"';
	}else if($width > $max_width){
		$newHeight = ($max_width / $width) * $height;
		$newWidth = $max_width;
		$params = 'width ="$newWidth" height="$newHeight"';
	}else{
		$params = $attr;  
	}   
	
	//echo $companylogo.'  '.$params; 
	//die;
	//  $imageData = file_get_contents($companylogo);	
	/* 
    $docx->embedHTML('<img src="'.$companylogo.'" border="0"></img>', array('downloadImages' => true, 'parseAnchors' => true)); */
	/* $docx->replaceTemplateVariableByHTML('companylogo', 'inline', '<img src="/home/beta/public_html/wp-content/uploads/companylogos/thumbs/202/215739_547711931908117_289676141_n.jpg" border="0"></img>', array('isFile' => true, 'downloadImages' => true)); */
	
	/* $docx->replaceTemplateVariableByHTML('companylogo', 'inline', '<img src="'.$companylogo.'" border="0"></img>');  */
	//echo $companylogo; echo "<br>";
	
	$docx->addTemplateImage('LOGO', $companylogoAbs);
	
	/* $docx->replaceVariableByHTML('companylogo', 'block', 'http://www.2mdc.com/PHPDOCX/example.html', array('isFile' => true, 'parseDivsAsPs' => false, 'downloadImages' => true)); */
	
	}else{
		  $docx->addTemplateVariable('companylogo',''); 
	} 
	

$cityState =  $org_ques_4.", ".$org_ques_5.", ".$org_ques_6;

$docx->addTemplateVariable('org_2', "$org_ques_2"); // companyname
$docx->addTemplateVariable('org_3', "$org_ques_3"); // streetaddress
$docx->addTemplateVariable('org_4', "$org_ques_4"); // city
$docx->addTemplateVariable('org_5', "$org_ques_5"); //state
$docx->addTemplateVariable('org_6', "$org_ques_6"); // zip

//Organisations Mission
$docx->replaceTemplateVariableByHTML('org_7', 'block', "$final_org_ques_7");
//Organisational goals: 

$docx->replaceTemplateVariableByHTML('org_8', 'block', "$final_org_ques_8");
$docx->addTemplateVariable('org_9', "$org_ques_9"); // hourly rate
$docx->addTemplateVariable('org_10', "$org_ques_10");
 
$docx->addTemplateVariable('city_state_zip', "$cityState");

$docx->addTemplateVariable('prog_1', "$pro_ques_1"); //authors
//Projects contribution to your organisations mission
$docx->addTemplateVariable('prog_2', "$pro_ques_2");

//Project contribution to (medium term) organisational goals:
$docx->addTemplateVariable('prog_3', "$pro_ques_3");

//Project contribution to (medium term) organisational goals:
$docx->addTemplateVariable('prog_4',"$pro_ques_4");

//Expected project budget:
$docx->addTemplateVariable('prog_5',"$pro_ques_5");

//Your projects name:
$docx->addTemplateVariable('prog_6',"$pro_ques_6"); 
$docx->addTemplateVariable('prog_7',"$pro_ques_7");
$docx->addTemplateVariable('prog_8',"$pro_ques_8");
$docx->addTemplateVariable('prog_9',"$pro_ques_9");
$docx->addTemplateVariable('prog_10',"$pro_ques_10");

$docx->addTemplateVariable('stakeholderList', "$stakeholderList");
$projEnd = date('d F Y', strtotime($ed));

$docx->addTemplateVariable('publishdate', "$publishDate");
$docx->addTemplateVariable('project_startdate', "$projectStartDate");
$docx->addTemplateVariable('project_enddate', "$projEnd");
$docx->addTemplateVariable('numberofdays', "$numberofdays");

$docx->addTemplateVariable('functionalAreaCount', "$inScopePlusCompCats");
$docx->addTemplateVariable($functionaAreas,'table',$settings);

$docx->replaceTemplateVariableByHTML('functional_area','block', "$functionaArea"); 

$docx->replaceTemplateVariableByHTML('fully_scoped', 'block', "$fullyScopedfunctionalText");


 
if( is_array($catInScopeIds) and !empty($catInScopeIds) ) {  
$docx->replaceTemplateVariableByHTML('functional_text', 'block', "$functionalText");
$docx->replaceTemplateVariableByHTML('functionalAreaDesc', 'block', "$RFQfunctionalText");
$docx->replaceTemplateVariableByHTML('tab','block',$tabl);
}
else
{
   if( is_array($totalFnArea) and !empty($totalFnArea) ) { 
           $docx->replaceTemplateVariableByHTML('functional_text', 'block', "$functionalText");
	}
   else {
           $docx->addTemplateVariable('functional_text', '');
    }   
$docx->addTemplateVariable('functionalAreaDesc', '');
$docx->addTemplateVariable('tab','');
}

//$docx->addTemplateVariable('functional_area',"$functionaArea"); 
//$docx->addTemplateVariable('functional_text',"$functionalText");
if( is_array($totalCats) and !empty($totalCats) ) {
$docx->replaceTemplateVariableByHTML('catfeatureres', 'block',$catfeatureRes);
} 
else
{
   $docx->addTemplateVariable('catfeatureres', '');
}
$docx->addTemplateVariable('totalfeatures', "$include_line_cnt"); 
if( is_array($totalCats) and !empty($totalCats) ) {
$docx->replaceTemplateVariableByHTML('faname', 'block',$reshtml);
}
else
{
$docx->addTemplateVariable('faname', '');
}

$docx->addTemplateVariable('totalbenefitcount', "$totalbenefitcount");
$docx->addTemplateVariable('stBenefits', "$stBenefits");
$docx->addTemplateVariable('ROI_Fe_Count', "$totalROI");
$docx->addTemplateVariable('estroi',"$roi_line");
 
$docx->addTemplateVariable('team_role',"$team_role");
$docx->addTemplateVariable('team_member_effort',"$team_member_effort");
$docx->addTemplateVariable('T_Lead_Name',"$team_lead_name");
$docx->addTemplateVariable('T_leader_contact',"$team_lead_email"); 
//$docx->replaceTemplateVariableByHTML('teamMembers', 'block', $teamMembers); 
$docx->replaceTemplateVariableByHTML('TeamList', 'block', $teamMembersList); 



$msStart = date('d F Y', strtotime($sd));
$msEnd = date('d F Y', strtotime($ed));

$docx->addTemplateVariable('MS_commence_date',"$msStart");
$docx->addTemplateVariable('MS_complete_date',"$msEnd");

/* ********** Make code for Risk Management written by Royal Tyagi *********** */

	$is_risk_management = get_option("risk_management");
	if ($is_risk_management == 1):
	
		$riskForProjectRationale = $this->riskForProjectRationale($userid);
		$docx->replaceTemplateVariableByHTML('riskForProjectRationale','block', $riskForProjectRationale);

		$riskForCharterScope = $this->riskForCharterScope($userid);
		$docx->replaceTemplateVariableByHTML('riskForCharterScope','block', $riskForCharterScope);
		
	endif;
/* ********** End code for Risk Management written by Royal Tyagi *********** */

$file = $getfilename.''.$userid.'_html';
//echo $filename = $dirpathurl.'/docx/'.$file;
$filename = $parentpath.'/docx/'.$file;

return $filename ;
}

/**
** get functional area headings
**/	
function getCatHeadingsBlock($catids){
	global $wp,$wpdb;
    $text ='';
	foreach($catids as $key1=>$val1){
		$qry = "select name from wp_specdoc_categories where category_id=$val1";
		$cat_info = $wpdb->get_row($qry,ARRAY_A );
		 
		// individual columns in the row 
		//$text.= $docx->addParagraph(array(array('text' => $cat_info['name'], 'type' => 'link', 'URL' => '#'.$cat_info["name"], 'color' => '000000')), array('color' => '000000'));
		$text.= "<br>".nl2br(stripslashes($cat_info["name"]));
	}
	$text.= "<br>";
	return $text;
  }
  
  function getCatHeadings($catids){
	global $wp,$wpdb;
    $text ='';
	if( count($catIds) > 0 ) {
		foreach($catids as $key1=>$val1){
			$qry = "select name from wp_specdoc_categories where category_id=$val1";
			$cat_info = $wpdb->get_row($qry,ARRAY_A );
			 
			// individual columns in the row 
			//$text.= $docx->addParagraph(array(array('text' => $cat_info['name'], 'type' => 'link', 'URL' => '#'.$cat_info["name"], 'color' => '000000')), array('color' => '000000'));
			$text.= "<br>".nl2br(stripslashes($cat_info["name"]));
		}
	}	
	$text.= "<br>";
	return $text;
  }
  
  function getFAROIBenefitFeaturesFunctions($catIds, $userid, $objUser){ 
	  global $wp,$wpdb;
	  $fntext = '';
	  $totalitems = count($catIds);
	  
		 if( $totalitems > 0 ) { 
		    $fntext .= "<div>"; 
		     for($i=0; $i<$totalitems; $i++) { 
		        $val = $catIds[$i];
				$catqry = "select name,text from wp_specdoc_categories where category_id=$val";
				$catres = $wpdb->get_row($catqry,ARRAY_A );
				$lineItemsData = array();
				
				$name = stripslashes(str_replace('&', "",trim($catres['name'])));
				$text = stripslashes(str_replace('&', "",trim($catres['text'])));
				$cat_id = trim($val);
				
				$lineItems = $objUser->GetToolData($userid);
				foreach($lineItems as $key=>$val) {
				     if($val[0] == $cat_id)
					 {
					      $lineItemsData[$cat_id] = $val[6];
						  break;
					 }
				}
				
				$totalLineItems = 0;
				$totalLineItemsROIs = 0;
				
				$highcnt=0;
				$mediumcnt=0;
				$lowcnt=0;
				$niceToHaveCnt=0;
				$priorities = '';
				
				$low_val=$objUser->getDefaultData('Impotrtance_Low_Value');
				$low_val=unserialize($low_val->text);
				if($low_val<0 || $low_val=="")
					$low_val=0;

				$medium_val=$objUser->getDefaultData('Impotrtance_Medium_Value');
				$medium_val=unserialize($medium_val->text);
				if($medium_val<0 || $medium_val=="")
					$medium_val=1;

				$high_val=$objUser->getDefaultData('Impotrtance_High_Value');
				$high_val=unserialize($high_val->text);
				if($high_val<0 || $high_val=="")
					$high_val=2; 
			
              $fntext .= "<span width='347' style='color: #4f81bd;font-family:Century Gothic;font-size:16px'> 
								 <a name='OLE_LINK10'>".trim($name)."</a>  
							 </span>";
			
				foreach($lineItemsData as $key=>$val) {
				    $subTotalLineItems     = 0;
					$subTotalLineItemsROIs = 0;
					$roi_line = 0;
					foreach($val as $key2=>$val2) {
					    if($val2[9] > 0)
						{
						    $LINEITEM_ID = $val2[0];
							$LINEITEM_NOTE = $val2[3];
						    $LINEITEM_IS_BENEFIT = $val2[12]; 
						    $subTotalLineItems = $subTotalLineItems + 1;    
							$how_often = $val2[5];
							$cost = $val2[7]; 
							$time = $val2[8]; 
							$hrate = get_usermeta($userid,"org_ques_9_state",true);
							$roi_line = $objUser->ROICalculation($hrate,$time,$cost,$how_often);
							$subTotalLineItemsROIs = $subTotalLineItemsROIs + $roi_line; 
                        							
							if($val2[2]==$high_val)
								$highcnt++;
							else if($val2[2]==$medium_val)
								$mediumcnt++;
							else if($val2[9]==1 and $val2[2]==$low_val)
								 $niceToHaveCnt++;
							else
								$lowcnt++; 
                            
                            if($LINEITEM_IS_BENEFIT > 0)
							{
                                 $priorities = $val2[13];
                            }
                            $prioritiesArray = explode(",", $priorities);  
							$totalROIsForOneRow = $totalLineItemsROIs; 
							$totalLineItemsROIs = 0; 
							$impName = '';  

                            $fntext .= '<table border="1" cellpadding="-3" cellspacing="-3" width="622" style="border-color: black;"><tbody>';
				                
							$fntext .= '<tr>
											<td valign="top" colspan="2">
												<p style="color: black;font-family:Century Gothic;font-size:14.5px"> 
													'.str_replace("\'", "'", $objUser->GetLineItemName($LINEITEM_ID)).'
												</p>
											</td> 
										</tr> 
										<tr>
											<td valign="top" width="347" style="color: black;font-family:Century Gothic;font-size:14.5px">
												<p>
													<strong>Notes:</strong>
												</p>
												<p>
													'.$LINEITEM_NOTE.'
												</p>
											</td>
											<td valign="top" width="215" style="color: black;font-family:Century Gothic;font-size:14.5px">
												<p>
													<strong>Priority</strong>
												</p>
												<p>'; 
										 if($highcnt > 0) 
										      $fntext .= "Mandatory";  
											//$fntext .= $highcnt." mandatory "; 
										 else if($mediumcnt > 0) 
										      $fntext .= "Important"; 
										    //$fntext .= $mediumcnt." important, "; 
										 else if($niceToHaveCnt > 0) 
										      $fntext .= "Nice to have";
											//$fntext .= $niceToHaveCnt." nice to have, "; 
										 else 
											$fntext .= '';  
									      //$fntext .= ' - nice to have, ';	
									$fntext .= '</p>
												<p>
													<strong>ROI</strong>
												</p>
												<p>
													  $'.$roi_line.'/YR
												</p>
												<p>
													<strong>Benefit to:</strong>
												</p>
												<p>';
												if( count($prioritiesArray) > 0 ) {
													foreach($prioritiesArray as $key=>$val) {
													   $fntext .= $val."<br>";
													}	
												}	 
									$fntext .= '</p> 
											</td>
										</tr>';	 
							$fntext .= "</tbody></table><br>";							
						}
					}  
				} 			 
			 }   
			$fntext .= "</div>"; 	
		 }	  
		return $fntext;
  }
  
  function getFAScopeFeaturesFunctions($catIds, $userid, $objUser){ 
	  global $wp,$wpdb;
	  $fntext = '';
	  $totalitems = count($catIds);
	  
		 if( $totalitems > 0 ) { 
		    $fntext .= "<div>"; 
		     for($i=0; $i<$totalitems; $i++) { 
		        $val = $catIds[$i];
				$catqry = "select name,text from wp_specdoc_categories where category_id=$val";
				$catres = $wpdb->get_row($catqry,ARRAY_A );
				$lineItemsData = array();
				 
				$name = stripslashes(str_replace('&', "",trim($catres['name'])));
				$text = stripslashes(str_replace('&', "",trim($catres['text'])));
				$cat_id = trim($val);
				
				$getNotes = $objUser->getNotesForToolData($userid, "category", $cat_id);
				
				$lineItems = $objUser->GetToolData($userid);
				foreach($lineItems as $key=>$val) {
				     if($val[0] == $cat_id)
					 {
					      $lineItemsData[$cat_id] = $val[6];
						  break;
					 }
				}
				
				$totalLineItems = 0;
				$totalLineItemsROIs = 0;
				
				$highcnt=0;
				$mediumcnt=0;
				$lowcnt=0;
				$niceToHaveCnt=0;
				$priorities = '';
				
				$low_val=$objUser->getDefaultData('Impotrtance_Low_Value');
				$low_val=unserialize($low_val->text);
				if($low_val<0 || $low_val=="")
					$low_val=0;

				$medium_val=$objUser->getDefaultData('Impotrtance_Medium_Value');
				$medium_val=unserialize($medium_val->text);
				if($medium_val<0 || $medium_val=="")
					$medium_val=1;

				$high_val=$objUser->getDefaultData('Impotrtance_High_Value');
				$high_val=unserialize($high_val->text);
				if($high_val<0 || $high_val=="")
					$high_val=2; 
				
				foreach($lineItemsData as $key=>$val) {
				    $subTotalLineItems     = 0;
					$subTotalLineItemsROIs = 0;
					$roi_line = 0;
					foreach($val as $key2=>$val2) {
					    if($val2[9] > 0)
						{
						    $LINEITEM_IS_BENEFIT = $val2[12]; 
						    $subTotalLineItems = $subTotalLineItems + 1;    
							$how_often = $val2[5];
							$cost = $val2[7]; 
							$time = $val2[8]; 
							$hrate = get_usermeta($userid,"org_ques_9_state",true);
							$roi_line = $objUser->ROICalculation($hrate,$time,$cost,$how_often);
							$subTotalLineItemsROIs = $subTotalLineItemsROIs + $roi_line; 
                        							
							if($val2[2]==$high_val)
								$highcnt++;
							else if($val2[2]==$medium_val)
								$mediumcnt++;
							else if($val2[9]==1 and $val2[2]==$low_val)
								 $niceToHaveCnt++;
							else
								$lowcnt++; 
                            
                            if($LINEITEM_IS_BENEFIT > 0)
							{
                                 $priorities .= $val2[13];
                            }							
						}
					} 
				    $totalLineItems     = $totalLineItems + $subTotalLineItems; 	
					$totalLineItemsROIs = $totalLineItemsROIs + $subTotalLineItemsROIs;
				}
				 
				$prioritiesArray = explode(",", $priorities); 
				
				$totalROIsForOneRow = $totalLineItemsROIs; 
				$totalLineItemsROIs = 0;
				
				$impName = '';
				
				$getLineItemDataByCatId = $objUser->GetLineItemFromToolData($userid, $cat_id);
			   		   								
				$fntext .= '<table border="1" cellpadding="-3" cellspacing="-3" width="622" style="border-color: black;"><tbody>';
				
				$fntext .= "<tr>
								<td valign='top' colspan='2' style='font-family:Century Gothic;font-size:16px'> 
										<a name='OLE_LINK10'>".trim($name)."</a>  
								</td> 
							</tr>
				            <tr>
								<td valign='top' width='347' style='color: black;font-family:Century Gothic;font-size:14.5px'> 
										<strong>Description</strong>
										<br>
										".nl2br(stripslashes(trim($catres['text'])))." 
								</td>
								<td rowspan='2' valign='top' width='215' style='color: black;font-family:Century Gothic;font-size:14.5px'> 
										<strong>Number of features</strong>
										<br>
										".$totalLineItems."<br>
										<strong>ROI</strong><br>
										$".$totalROIsForOneRow."/YR<br>
										<strong>Priority</strong><br>"; 
										 if($highcnt > 0) 
											$fntext .= $highcnt." mandatory, "; 
										 else 
											$fntext .= ' 0 mandatory, ';  
										 if($mediumcnt > 0) 
											$fntext .= $mediumcnt." important, "; 
										 else 
											$fntext .= ' 0 important, ';  
										 if($niceToHaveCnt > 0) 
											$fntext .= $niceToHaveCnt." nice to have, "; 
										 else 
											$fntext .= ' 0 nice to have, '; 			
						$fntext .= "<br>
										<a><strong>Benefit to:</strong></a><br>";
										if( count($prioritiesArray) > 0 ) {
											foreach($prioritiesArray as $key=>$val) {
											   $fntext .= $val."<br>";
											}	
                                        }										
						$fntext .= "</p>
								</td>
							</tr>
							";
				   $fntext .= '<tr>
								<td valign="top" colspan="2" width="347" style="color: black;font-family:Century Gothic;font-size:14.5px"> 
										<strong>Notes: </strong>
										<br>
										'.$getNotes.'
									</p>
								</td>
							</tr>';  
                 $fntext .= "</tbody></table><br>";					 
				} 
			$fntext .= "</div>"; 	
		 }	  
		return $fntext;
  }
  
  function lineitemsDataAllRFQRes($catIds, $userid, $objUser){ 
	  global $wp,$wpdb;
	  $fntext = '';
	  $totalitems = count($catIds);
	  
		 if( $totalitems > 0 ) { 
		    $fntext .= "<div>"; 
		     for($i=0; $i<$totalitems; $i++) { 
		  // foreach($catIds as $key=>$val){ 
		        $val = $catIds[$i];
				$catqry = "select name,text from wp_specdoc_categories where category_id=$val";
				$catres = $wpdb->get_row($catqry,ARRAY_A );
				
				$name = stripslashes(str_replace('&', "",trim($catres['name'])));
				$text = stripslashes(str_replace('&', "",trim($catres['text'])));
				 $cat_id = trim($val);
			
				$totalLineItems = array();
				$impName = '';
				
				$getLineItemDataByCatId = $objUser->GetLineItemFromToolData($userid, $cat_id);
				
				$fntext .= "<div style=\"color: #4f81bd;font-family:Century Gothic;font-size:17px\">".trim($name)."</div>";
						   
			    $fntext.= "<br><br>"; 
			    $fntext.= "<div style='font-family:Century Gothic;font-size:14.5px !important;'>".nl2br(stripslashes(trim($catres['text'])))."</div>";
			    $fntext.= "<br><br>"; 
				
				$fntext .= "<div style=\"color: #4f81bd;font-family:Century Gothic;font-size:17px\">Functional area Line items</div><br>";
				
				$fntext .= '<table border="1" cellpadding="-3" cellspacing="-3" width="622" style="border-color: black;"><tbody>';
				
				$fntext .= "<tr> 
								<td valign='top' width='193' style='font-family:Century Gothic;font-size:17px'>
									<p>
										Feature name
									</p>
								</td>
								<td valign='top' width='185' style='font-family:Century Gothic;font-size:17px'>
									<p>
										Importance
									</p>
								</td>
								<td valign='top' width='244' style='font-family:Century Gothic;font-size:17px'>
									<p>
										Fully complies
									</p>
								</td>
								<td valign='top' width='244' style='font-family:Century Gothic;font-size:17px'>
									<p>
										Partly complies
									</p>
								</td>
								<td valign='top' width='244' style='font-family:Century Gothic;font-size:17px'>
									<p>
										Work around required
									</p>
								</td>
								<td valign='top' width='244' style='font-family:Century Gothic;font-size:17px'>
									<p>
										Does not comply
									</p>
								</td>
						    </tr>";
						   
				    foreach($getLineItemDataByCatId as $key2=>$val2){
							if($val2[9] == 1)
							{ 
									$IMPORTANCE   = $val2[2];
									$LINEITEM_ID  = $val2[0];
									$LINEITEM_NOTE  = $val2[3];
									$lineItemName = $objUser->GetLineItemName($LINEITEM_ID);
									
									if($IMPORTANCE == 0)
									{
										$impName = "Nice to have";      
									}
									else if($IMPORTANCE == 1)
									{
										$impName = "Important"; 
									}
									else if($IMPORTANCE == 2)
									{
										$impName = "Mandatory"; 
									}
									$totalLineItems['Functional_Area'] = $cat_id;
									$totalLineItems['lineItemName'] = $lineItemName;
									$totalLineItems['IMPORTANCE'] = $impName;
									$finalfullySpecTotalLineItems[] = $totalLineItems; 
							    	
                             
						      $fntext .= "<tr>
							  
							                <td valign='top' width='193' style='font-family:Century Gothic;font-size:14.5px'>
												<p>
													".$lineItemName."
												</p>
											</td>
											<td valign='top' width='185' style='font-family:Century Gothic;font-size:14.5px'>
												<p>
													".$impName."
												</p>
											</td>
											<td valign='top' width='244' style='font-family:Century Gothic;font-size:14.5px'>
												&nbsp;
											</td> 
											<td valign='top' width='244' style='font-family:Century Gothic;font-size:14.5px'>
												&nbsp;
											</td> 
											<td valign='top' width='244' style='font-family:Century Gothic;font-size:14.5px'>
												&nbsp;
											</td> 
											<td valign='top' width='244' style='font-family:Century Gothic;font-size:14.5px'>
												&nbsp;
											</td> 
										</tr>";
							
						   //$fntext[] = array('FA_title' => $name, 'FA_Desc' => $text, 'FA_Fnlineitems' =>$lineItemName, 'FA_lineitemsimp' =>$impName, 'FA_Notes' => $LINEITEM_NOTE);											
							} 
					 }
					 
                    $fntext .= "</tbody></table><br>
					            <p style='color: #4f81bd;font-family:Century Gothic;font-size:16px'>
									Any notes or comments
								</p>
								<table border='1' cellpadding='-3' cellspacing='-3' style='border-color: grey;'>
									<tbody>
										<tr>
											<td valign='top' width='616' style='padding: 20px;'>
											  &nbsp;<br><br>
											</td>
										</tr>
									</tbody>
								</table> <br><br>
					         ";					 
				} 
			$fntext .= "</div>"; 	
		 }	  
		return $fntext; 
  }
  
  
  function addCompletedFAsinTOC($catIds, $userid, $objUser)
  { 
	  global $wp,$wpdb;
	  $fntext = '';
	  $totalitems = count($catIds); 
		 if( $totalitems > 0 ) {
            $fntext	.= "<p>";		 
		     for($i=0; $i<$totalitems; $i++) {  
					$val = $catIds[$i];
					$catqry = "select name,text from wp_specdoc_categories where category_id=$val";
					$catres = $wpdb->get_row($catqry,ARRAY_A );
					
					$name = stripslashes(str_replace('&', "",trim($catres['name'])));
					$text = stripslashes(str_replace('&', "",trim($catres['text'])));
					$cat_id = trim($val); 
					
					$fntext .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-family:Century Gothic;font-size:14.5px;'>".trim($name)."</span><br><br>"; 				 
				}
            $fntext	.= "</p>";			
		 }	  
		return $fntext; 
  }
  
  function lineitemsDataAll($catIds, $userid, $objUser){
	  global $wp,$wpdb;
	  $fntext = '';
	  $totalitems = count($catIds);
	  
		 if( $totalitems > 0 ) { 
		    $fntext .= "<div>"; 
		     for($i=0; $i<$totalitems; $i++) { 
		  // foreach($catIds as $key=>$val){ 
		        $val = $catIds[$i];
				$catqry = "select name,text from wp_specdoc_categories where category_id=$val";
				$catres = $wpdb->get_row($catqry,ARRAY_A );
				
				$name = stripslashes(str_replace('&', "",trim($catres['name'])));
				$text = stripslashes(str_replace('&', "",trim($catres['text'])));
				 $cat_id = trim($val);
			
				$totalLineItems = array();
				$impName = '';
				
				$getLineItemDataByCatId = $objUser->GetLineItemFromToolData($userid, $cat_id);
				
				$fntext .= "<div style=\"color: #4f81bd;font-family:Century Gothic;font-size:17px\">".trim($name)."</div>";
						   
			    $fntext.= "<br><br>"; 
			    $fntext.= "<div style='font-family:Century Gothic;font-size:14.5px !important;'>".nl2br(stripslashes(trim($catres['text'])))."</div>";
			    $fntext.= "<br><br>"; 
				
				$fntext .= "<div style=\"color: #4f81bd;font-family:Century Gothic;font-size:17px\">Features details</div><br>";
				
				$fntext .= '<table border="1" cellpadding="-3" cellspacing="-3" width="622" style="border-color: black;"><tbody>';
				
				$fntext .= "<tr> 
								<td valign='top' width='193' style='font-family:Century Gothic;font-size:17px'>
									<p>
										Feature name
									</p>
								</td>
								<td valign='top' width='185' style='font-family:Century Gothic;font-size:17px'>
									<p>
										Importance
									</p>
								</td>
								<td valign='top' width='244' style='font-family:Century Gothic;font-size:17px'>
									<p>
										Notes
									</p>
								</td>
						    </tr>";
						   
				    foreach($getLineItemDataByCatId as $key2=>$val2){
							if($val2[9] == 1)
							{ 
									$IMPORTANCE   = $val2[2];
									$LINEITEM_ID  = $val2[0];
									$LINEITEM_NOTE  = $val2[3];
									$lineItemName = $objUser->GetLineItemName($LINEITEM_ID);
									
									if($IMPORTANCE == 0)
									{
										$impName = "Nice to have";      
									}
									else if($IMPORTANCE == 1)
									{
										$impName = "Important"; 
									}
									else if($IMPORTANCE == 2)
									{
										$impName = "Mandatory"; 
									}
									$totalLineItems['Functional_Area'] = $cat_id;
									$totalLineItems['lineItemName'] = $lineItemName;
									$totalLineItems['IMPORTANCE'] = $impName;
									$finalfullySpecTotalLineItems[] = $totalLineItems; 
							    	
                             
						      $fntext .= "<tr>
							  
							                <td valign='top' width='193' style='font-family:Century Gothic;font-size:14.5px'>
												<p>
													".$lineItemName."
												</p>
											</td>
											<td valign='top' width='185' style='font-family:Century Gothic;font-size:14.5px'>
												<p>
													".$impName."
												</p>
											</td>
											<td valign='top' width='244' style='font-family:Century Gothic;font-size:14.5px'>
												<p>
													".$LINEITEM_NOTE."
												</p>
											</td> 
										</tr>";
							
						   //$fntext[] = array('FA_title' => $name, 'FA_Desc' => $text, 'FA_Fnlineitems' =>$lineItemName, 'FA_lineitemsimp' =>$impName, 'FA_Notes' => $LINEITEM_NOTE);											
							} 
					 }
					 
                    $fntext .= "</tbody></table><br>";					 
				} 
			$fntext .= "</div>"; 	
		 }	  
		return $fntext;  
   } 

  
  function lineitemsDataRFQRes($catIds, $userid, $objUser){ 
	  global $wp,$wpdb;
	  $fntext = array();
	 
		 if( count($catIds) > 0 ) {
		    $val = $catIds[0];  
		  // foreach($catIds as $key=>$val){
				//$catqry = "select name,text from wp_specdoc_categories where category_id=$val";
				$catqry = "select name,text from wp_specdoc_categories where category_id=$val";
				$catres = $wpdb->get_row($catqry,ARRAY_A );
				
				$name = stripslashes(str_replace('&', "",trim($catres['name'])));
				$text = htmlentities($catres['text']);
				
				 $cat_id = trim($val);
			
				$totalLineItems = array();
				$impName = '';
				
				$getLineItemDataByCatId = $objUser->GetLineItemFromToolData($userid, $cat_id);
				
				    foreach($getLineItemDataByCatId as $key2=>$val2){
							if($val2[9] == 1)
							{ 
									$IMPORTANCE   = $val2[2];
									$LINEITEM_ID  = $val2[0];
									$LINEITEM_NOTE  = $val2[3];
									$lineItemName = $objUser->GetLineItemName($LINEITEM_ID);
									
									if($IMPORTANCE == 0)
									{
										$impName = "Nice to have";      
									}
									else if($IMPORTANCE == 1)
									{
										$impName = "Important"; 
									}
									else if($IMPORTANCE == 2)
									{
										$impName = "Mandatory"; 
									}
									$totalLineItems['Functional_Area'] = $cat_id;
									$totalLineItems['lineItemName'] = $lineItemName;
									$totalLineItems['IMPORTANCE'] = $impName;
									$finalfullySpecTotalLineItems[] = $totalLineItems; 

						   $fntext[] = array('FA_title2' => $name, 'FA_Desc2' => $text, 'Fnlineitems2' =>$lineItemName, 'lineitemsimp2' =>$impName);											
							} 
					 } 
					//} 
		 }			
		return $fntext;  
  }  
  
  function lineitemsData($catIds, $userid, $objUser){
	  global $wp,$wpdb;
	  $fntext = array();
	 
		 if( count($catIds) > 0 ) {
		    $val = $catIds[0];  
		  // foreach($catIds as $key=>$val){
				//$catqry = "select name,text from wp_specdoc_categories where category_id=$val";
				$catqry = "select name,text from wp_specdoc_categories where category_id=$val";
				$catres = $wpdb->get_row($catqry,ARRAY_A );
				
				$name = stripslashes(str_replace('&', "",trim($catres['name'])));
				//$text = stripslashes(str_replace('&', "",trim($catres['text'])));
				$text = nl2br(stripslashes(trim($catres['text'])));
				 $cat_id = trim($val);
			
				$totalLineItems = array();
				$impName = '';
				
				$getLineItemDataByCatId = $objUser->GetLineItemFromToolData($userid, $cat_id);
				
				    foreach($getLineItemDataByCatId as $key2=>$val2){
							if($val2[9] == 1)
							{ 
									$IMPORTANCE   = $val2[2];
									$LINEITEM_ID  = $val2[0];
									$LINEITEM_NOTE  = $val2[3];
									$lineItemName = $objUser->GetLineItemName($LINEITEM_ID);
									
									if($IMPORTANCE == 0)
									{
										$impName = "Nice to have";      
									}
									else if($IMPORTANCE == 1)
									{
										$impName = "Important"; 
									}
									else if($IMPORTANCE == 2)
									{
										$impName = "Mandatory"; 
									}
									$totalLineItems['Functional_Area'] = $cat_id;
									$totalLineItems['lineItemName'] = $lineItemName;
									$totalLineItems['IMPORTANCE'] = $impName;
									$finalfullySpecTotalLineItems[] = $totalLineItems; 

						   $fntext[] = array('FA_title' => $name, 'FA_Desc' => html_entity_decode($text), 'FA_Fnlineitems' =>$lineItemName, 'FA_lineitemsimp' =>$impName, 'FA_Notes' => $LINEITEM_NOTE);											
							} 
					 } 
					//} 
		 }
 		 
		return $fntext;  
   } 
  
  function getCatHeadingsAndTextRFQResp($catIds, $style=1, $userid, $objUser){
	 global $wp,$wpdb;
	  $fntext = "";
	  
	  $finalfullySpecTotalLineItems = array();  
	  
	  switch ($style) {
			case 1:
			if( count($catIds) > 0 ) {
			    $fntext.= "<ul>"; 
				foreach($catIds as $key=>$val){ 
					$qry1 = "select name,text from wp_specdoc_categories where category_id=$val";
					$cat_info1 = $wpdb->get_row($qry1,ARRAY_A );
					$cat_id = trim($val);
					
					$totalLineItems = array();
					$impName = '';
					
					$getLineItemDataByCatId = $objUser->GetLineItemFromToolData($userid, $cat_id); 
					
					//$fntext.= "<b><p style=\"color: #4f81bd;font-family:'Century Gothic';font-size:11px\">".nl2br(trim($cat_info1['name']))."</p></b>";
                    $fntext.= "<li><div style=\"color: #4f81bd;font-family:Century Gothic;font-size:17px\">".trim($cat_info1['name'])."</div>"; 					
					$fntext.= "<br><br>";
					//$fntext.= nl2br(stripslashes(trim($cat_info1['text'])));
					$fntext.= "<div style='font-family:Century Gothic;font-size:14.5px !important;'>".nl2br(stripslashes(trim($cat_info1['text'])))."</div>";
					$fntext.= "</li><br><br>"; 
				} 
                $fntext.= "</ul>"; 				
			}	
			else {
			    $fntext.= "<ul>"; 
				    $fntext.= "<li></li>"; 
			    $fntext.= "</ul>";
			}
				$fntext = str_replace('&', "", $fntext);
				break;
			
			case 2:
			 if( count($catIds) > 0 ) {
				foreach($catIds as $key=>$val){
					$qry1 = "SELECT name,text FROM wp_specdoc_categories WHERE category_id=$val";
					$cat_info1 = $wpdb->get_row($qry1,ARRAY_A );
					
					$fntext.= "Functional area name: ".nl2br(stripslashes($cat_info1['name']))."";	
					$fntext.= "<br><br>";
					$fntext.= "Functional area description: <br><br>";
					$fntext.= nl2br(stripslashes(trim($cat_info1['text'])));
					$fntext.= "<br><br>";
				} 
			 }	
				$fntext = str_replace('&', "", $fntext);
				break;
			case 3:
			     $fntext.= "<table style='border:1px solid #ccc;'>";
				    $fntext.= "<tr style='border:1px solid #ccc;'>";
					$fntext.= "<td style='border:1px solid #ccc;'>";
					$fntext.= '<b>Functional Area</b>';	
					$fntext.= "</td>";
					$fntext.= "<td>";
					$fntext.= '<b>Description</b>';
					$fntext.= "</td>";
					$fntext.= "</tr>"; 
			if( count($catIds) > 0 ) {		
				foreach($catIds as $key=>$val){
					$qry1 = "SELECT name,text FROM wp_specdoc_categories WHERE category_id=$val";
					$cat_info1 = $wpdb->get_row($qry1,ARRAY_A );
					$fntext.= "<tr style='border:1px solid #ccc;'>";
					$fntext.= "<td style='border:1px solid #ccc;'>";
					$fntext.= nl2br(stripslashes($cat_info1['name']));	
					$fntext.= "</td>";
					$fntext.= "<td>";
					$fntext.= nl2br(stripslashes(trim($cat_info1['text'])));
					$fntext.= "</td>";
					$fntext.= "</tr>";
				} 
			}	
				$fntext.= "</table>";
				$fntext = str_replace('&', "", $fntext);
				break;
		}
      //  echo $fntext;
		//die;
	
	return $fntext;
	
   }
   
  
/** 
 ** functional area headings with text 
**/
	function getCatHeadingsAndText($catIds,$style=1){
	 global $wp,$wpdb;
	  $fntext = "";
	  
	  switch ($style) {
			case 1: 
			if( count($catIds) > 0 ) {
			   // $fntext.= "<ul style='list-style: none;'>"; 
				foreach($catIds as $key=>$val){ 
					$qry1 = "select name,text from wp_specdoc_categories where category_id=$val";
					$cat_info1 = $wpdb->get_row($qry1,ARRAY_A ); 
					
					//$fntext.= "<b><p style=\"color: #4f81bd;font-family:'Century Gothic';font-size:11px\">".nl2br(trim($cat_info1['name']))."</p></b>";
                    $fntext.= "<div style=\"color: #4f81bd;font-family:Century Gothic;font-size:17px\">".$cat_info1['name']."</div>"; 					
					$fntext.= "<br><br>";
					//$fntext.= nl2br(stripslashes(trim($cat_info1['text'])));
					$fntext.= "<div style='font-family:Century Gothic;font-size:14.5px !important;'>".nl2br(stripslashes(trim($cat_info1['text'])))."</div>";
					$fntext.= "<br><br><br>";
				}
                //$fntext.= "</ul>"; 		
 				
			}	
				$fntext = str_replace('&', "", $fntext);
				break;
			
			case 2:
			 if( count($catIds) > 0 ) {
				foreach($catIds as $key=>$val){
					$qry1 = "SELECT name,text FROM wp_specdoc_categories WHERE category_id=$val";
					$cat_info1 = $wpdb->get_row($qry1,ARRAY_A );
					
					$fntext.= "Functional area name: ".nl2br(stripslashes($cat_info1['name']))."";	
					$fntext.= "<br><br>";
					$fntext.= "Functional area description: <br><br>";
					$fntext.= nl2br(stripslashes(trim($cat_info1['text'])));
					$fntext.= "<br><br>";
				} 
			 }	
				$fntext = str_replace('&', "", $fntext);
				break;
			case 3:
			     $fntext.= "<table style='border:1px solid #ccc;'>";
				    $fntext.= "<tr style='border:1px solid #ccc;'>";
					$fntext.= "<td style='border:1px solid #ccc;'>";
					$fntext.= '<b>Functional Area</b>';	
					$fntext.= "</td>";
					$fntext.= "<td>";
					$fntext.= '<b>Description</b>';
					$fntext.= "</td>";
					$fntext.= "</tr>"; 
			if( count($catIds) > 0 ) {		
				foreach($catIds as $key=>$val){
					$qry1 = "SELECT name,text FROM wp_specdoc_categories WHERE category_id=$val";
					$cat_info1 = $wpdb->get_row($qry1,ARRAY_A );
					$fntext.= "<tr style='border:1px solid #ccc;'>";
					$fntext.= "<td style='border:1px solid #ccc;'>";
					$fntext.= nl2br(stripslashes($cat_info1['name']));	
					$fntext.= "</td>";
					$fntext.= "<td>";
					$fntext.= nl2br(stripslashes(trim($cat_info1['text'])));
					$fntext.= "</td>";
					$fntext.= "</tr>";
				} 
			}	
				$fntext.= "</table>";
				$fntext = str_replace('&', "", $fntext);
				break;
		}
      //  echo $fntext;
		//die;
	
	return $fntext;
	
   }
   
 /** 
 ** functional area headings with text in table format for EOI Response Doc File
**/  
   function getCatTitleAndTextTablefAreas($catIds){
	 global $wp,$wpdb;
	  $fntext = array();
	  if( count($catIds) > 0 ) {
       foreach($catIds as $key=>$val){
					$catqry = "select name,text from wp_specdoc_categories where category_id=$val";
					$catres = $wpdb->get_row($catqry,ARRAY_A );
					
					$name = stripslashes(str_replace('&', "",trim($catres['name'])));
					$dtext = htmlspecialchars_decode(trim($catres['text']), ENT_QUOTES);
					
					
					$fntext[] = array('functional_areas' => $name);
				} 
	  }			
	return $fntext;
	
   }
   
/** 
 ** functional area headings with text in table format
**/
	function getCatTitleAndTextTable($catIds){
	 global $wp,$wpdb;
	  $fntext = array();
	 // echo '<pre>'; print_r($catIds); die;
	 if( count($catIds) > 0 ) {
       foreach($catIds as $key=>$val){
					$catqry = "select name,text from wp_specdoc_categories where category_id=$val";
					$catres = $wpdb->get_row($catqry,ARRAY_A );
					
					$name = stripslashes(str_replace('&', "",trim($catres['name'])));
					$dtext = trim($catres['text']);
					
					
					$fntext[] = array('FA_name' => $name,'FA_desc' =>$dtext);
				} 
	 }			
	return $fntext;
	
   }
   
   /**
	** get specific functional area headings
	**/	
	
	function getCatHeading($catarray){
	  global $wp,$wpdb;
	  $categoryNames = "";
	   
      foreach($catarray as $key=>$val){	   
		$catnameqry = "SELECT name FROM wp_specdoc_categories WHERE category_id=$key";
		$getCatName = $wpdb->get_row($catnameqry,ARRAY_A );
		$catname = nl2br(stripslashes($getCatName['name']));		
		$categoryNames.= "Functional area name: ".$catname;	
 
	  } 

	 return $categoryNames;
	
   }
   
   // get category names with feature count and ROI,benefits count
   
   function getCatAndFeaturecount($catarray,$catfeaturecount){
    global $wp,$wpdb;
	$fntext = "";
       if( is_array($catarray) and !empty($catarray) )
        {	   
				foreach($catarray as $key=>$val){			
					if(array_key_exists($val, $catfeaturecount)){
					  $featureCount = $catfeaturecount[$val];
					}else{
					  $featureCount = 0;
					}
			
					$catnameqry = "select name from wp_specdoc_categories where category_id=$val";
					$getCatName = $wpdb->get_row($catnameqry,ARRAY_A );
					$catname = nl2br(stripslashes($getCatName['name']));
					
					$fntext.= "Functional area name: ".$catname;	
					$fntext.= "<br><br>";
					$fntext.= "Total features: ".$featureCount;
					$fntext.= "<br><br>";
					$fntext.= "Functional area estimated ROI:";
					$fntext.= "<br><br>";
					$fntext.= "Functional area benefits:";
					$fntext.= "<br><br>"; 
				}
		}
	   else {
              $fntext = "";
        }	   
		return $fntext;
		
   }
   
   // get category names with feature count and ROI(table form)
   
   function getCatAndFeaturecountTable($catarray,$catfeaturecount){
    global $wp,$wpdb;
	/* echo '<pre>';
	print_r($catfeaturecount); */
	
	$arr = array();
		/* foreach($catarray as $key=>$val){			
			if(array_key_exists($val, $catfeaturecount)){
			  $featureCount = $catfeaturecount[$val]['count'];
			  $featureRoi = $catfeaturecount[$val]['roi'];
			}else{
			  $featureCount = 0;
			  $featureRoi = 0;
			}
			
			$catnameqry = "select name from wp_specdoc_categories where category_id=$val";
			$getCatName = $wpdb->get_row($catnameqry,ARRAY_A );
			$catname = nl2br(stripslashes($getCatName['name']));
			
		    $arr[] = array('functional_areaname' => $catname,'featureCount' =>$featureCount,'fa_total_ROI'=>$featureRoi);
         
		} */
		 /* echo '<pre>';
		print_r($arr);
		die();  */
		return $arr;
		
   }
   
	function getCatAndFeaturecountDetailTable($catarray,$catfeatureitem){
    global $wp,$wpdb;
	$arr = array();
	/* echo '<pre>';
	print_r($catarray);
	echo "<br>arrayitems<br>";
	print_r($catfeatureitem); */
		foreach($catarray as $key=>$val){	
			//echo "<br>$key=>$val";
	
			 if(array_key_exists($val, $catfeatureitem)){
			     foreach($catfeatureitem as $catfeatureitemkey=>$catfeatureitemval){	
				   //echo "<br>features:$catfeatureitemkey=>$catfeatureitemval";
				     foreach($catfeatureitemval as $cfkey=>$cfval){
					   //echo "<br>featuresff:$cfkey=>$cfval";
				   

					$lineqry = "SELECT name FROM wp_specdoc_line_items where line_item_id = ".$cfval['itemid'];
					$getLineName = $wpdb->get_row($lineqry,ARRAY_A );
					$LineName = nl2br(stripslashes($getLineName['name']));
					$importance = $cfval['importance'];
					$arr[] = array('feature_name' => $LineName,'feature_imporatnce' =>$importance,'fa_total_ROI'=>0,'fa_benefit'=>0);
					}
				 }
			}else{
				    //$arr[] = array('feature_name' => '','feature_imporatnce' =>'','fa_total_ROI'=>0);
				 }
				
			  
			}
			
		 /* echo "<pre>";
		print_r($arr);
		die(); */ 	
		return $arr;
		
   }
   
   
   function getCatAndFeaturecountDetailHTML($catarray,$catfeatureitem){
      global $wp,$wpdb;
	   $html = "";
	       if( is_array($catarray) and !empty($catarray) )
           {  
				foreach($catarray as $key=>$val){	
					$html .= "<p style='color: #4f81bd'>";
					$html .= $this->getSingleCatHeadings($val);
					$html .= "</p>";	
						  if(array_key_exists($val, $catfeatureitem)){
								$html .= "<table cellspacing='0' cellpadding='0' style='width:500px'>";	
								$html .= "<tr><td>Feature name</td><td>Importance</td><td>Estimated saving</td><td>Benefit to</td></tr>";	
							 foreach($catfeatureitem as $catfeatureitemkey=>$catfeatureitemval){			  
								 foreach($catfeatureitemval as $cfkey=>$cfval){
									$lineqry = "SELECT name FROM wp_specdoc_line_items where line_item_id = ".$cfval['itemid'];
									$getLineName = $wpdb->get_row($lineqry,ARRAY_A );
									$LineName = nl2br(stripslashes($getLineName['name']));
									$importance = $cfval['importance'];
									  $html .="<tr><td>"; 
									  $html .=$LineName."</td><td>".$importance."</td><td>0</td><td>0</td></tr>";
									} 
									//$html.= "<b><p style=\"color: #4f81bd\">".$importance."</b>";	
									//$html.= "<br><br>";
									//$html.= $LineName;
									//$html.= "<br><br>";
									$html .= "</table>";
								 }
								
							}else{  } 
					}
			}
		return $html;
		
   }
   
function getSingleCatHeadings($catid){
	global $wp,$wpdb;   

	$qry = "SELECT name FROM wp_specdoc_categories WHERE category_id=$catid";
	$cat_info = $wpdb->get_row($qry,ARRAY_A );
	return nl2br(stripslashes($cat_info["name"]));
}

	public function createpdfdocx($filename){
		$document = new TransformDoc();	 
		$document->setStrFile($filename);
		$document->generatePDF();
	}
	
	// get team names with role and responsiblity
   
   function getteamRoleTable($team){
    global $wp,$wpdb; 
	 
		foreach($team as $tkey=>$tvalue){
		   $teammember = nl2br(stripslashes(htmlspecialchars_decode($tvalue->member_name, ENT_NOQUOTES)));
		   $contact = nl2br(stripslashes(htmlspecialchars_decode($tvalue->member_email, ENT_NOQUOTES)));
		   $role = nl2br(stripslashes($tvalue->member_responsibility));
		   $resp = nl2br(stripslashes(htmlspecialchars_decode($tvalue->member_responsibility, ENT_NOQUOTES)));
		   
		   $member_estimate_effort = nl2br(stripslashes(htmlspecialchars_decode($tvalue->member_estimate_effort, ENT_NOQUOTES)));
		   
			// $arr[] = array('teamMember' => $teammember,'teamContact' => $contact,'teamRole' =>$role,'Responsibility'=>$resp ,'estEfforts'=>$member_estimate_effort); 
              $arr[] = array('teamMember' => $teammember,'teamContact' => $contact,'teamRole' =>$role,'Responsibility'=>$resp ,'T_Lead_Effort'=>$member_estimate_effort); 			
		}

		return $arr;
		
   }
   
   // get team member names
   
   function getteamNames($team){
    global $wp,$wpdb;
	 
		foreach($team as $tkey=>$tvalue){
		   $teammember = nl2br(stripslashes(htmlspecialchars_decode($tvalue->member_name, ENT_NOQUOTES)));		   
		   
			 $arr[] = array('teamMember' => $teammember);  
		}

		return $arr;
		
   }
   
   
   // get team member names inlist
   
   function getteamNamesList($team){
    global $wp,$wpdb;
	 
	 $list = "<ul style='font-family:Century Gothic;font-size:14.5px !important; list-style: none;'>";
		foreach($team as $tkey=>$tvalue){
		   $teammember = nl2br(stripslashes(htmlspecialchars_decode($tvalue->member_name, ENT_NOQUOTES)));		   
		   
			 $list .= "<li>".$teammember."</li>"; 
		}
      $list .=  "</ul>";
		return $list;
		
   }

   
   // get team lead name
   
   function getTlName($userid){
    global $wp,$wpdb;
	$teamqry = "SELECT member_name FROM `wp_specdoc_userteaminfo` WHERE userid ='$userid' and member_type='tl'";
	$teamrows = $wpdb->get_col($teamqry);

	$tl = nl2br(stripslashes($teamrows[0]));
		
	return $tl;
		
   }
   
   // get team lead email
   
   function getTlEmail($userid){ 
    global $wp,$wpdb;
	$teamqry = "SELECT member_email FROM `wp_specdoc_userteaminfo` WHERE userid ='$userid' and member_type='tl'";
	$teamrows = $wpdb->get_col($teamqry);

	$tl = nl2br(stripslashes($teamrows[0]));
		
	return $tl;
		
   }
   
   // milestone Start date
   function msStartDate($userid){
	global $wp,$wpdb;
	$dateQry = "SELECT milestonedate FROM `wp_specdoc_usermilestones` where milestone = 'Project Commencement' and user_id ='$userid'";
	$sresult = $wpdb->get_col($dateQry);

	$res = $sresult[0];
		
	return $res;

   }
   
   // milestone end date
   function msEndDate($userid){
	global $wp,$wpdb;
	$dateQry = "SELECT milestonedate FROM `wp_specdoc_usermilestones` where milestone = 'Project Completion' and user_id ='$userid'";
	$sresult = $wpdb->get_col($dateQry);

	$res = $sresult[0];
		
	return $res;

   }
   
   // get all milestone names
   function allMilestones($userid){
	global $wp,$wpdb;
	$msQry = "SELECT milestone,milestonedate FROM `wp_specdoc_usermilestones` WHERE  user_id ='$userid'";
	$msRes = $wpdb->get_results($msQry,ARRAY_A);
	
	 return $msRes;
   } 
   
   
   function getROITable($userid, $totalFnArea, $objUser){
		global $wp,$wpdb;
		$arr = array();
		$fntext = ''; 
		 
		foreach($totalFnArea as $key=>$val){ 
			$qry1 = "select name,text from wp_specdoc_categories where category_id=$val";
			$cat_info1 = $wpdb->get_row($qry1,ARRAY_A ); 
			$fntext = trim($cat_info1['name']);
			$cat_id = trim($val);
			
			$totalLineItems = 0;
			$roi_line = 0.0;
			$getLineItemDataByCatId = $objUser->GetLineItemFromToolData($userid, $cat_id);
			foreach($getLineItemDataByCatId as $key2=>$val2){
                if($val2[9] == 1)
				{
				        $totalLineItems = $totalLineItems + 1;
					    $how_often = $val2[5];
						$cost = $val2[7];
						$time = $val2[8]; 
						$hrate = get_usermeta($userid,"org_ques_9_state",true); 
						$roi_line = $roi_line + $objUser->ROICalculation($hrate,$time,$cost,$how_often);
				} 
            }	 
              			
			
			$arr[] = array('FA1_titles' => $fntext, 'FA1_FE_Counts' => $totalLineItems, 'FA1_total_ROIs'=> $roi_line); 
		}  
		return $arr;
   }
   
   // get all milestone and display in table
   function getMilestoneTable($userid){
	global $wp,$wpdb;
	$arr = array();
	
	$gtMilestones = $this->allMilestones($userid);
	
	foreach($gtMilestones as $key=>$val){ 
	    $arr[] = array('MS_Number' => $key+1,'MS_name' => $val['milestone'],'MS_Date'=>date('d F Y', strtotime($val['milestonedate']))); 
	} 
	return $arr;
   }
   
   function getMilestoneTable2($userid){
	global $wp,$wpdb;
	$arr = array();
	
	$gtMilestones = $this->allMilestones($userid);
	foreach($gtMilestones as $key=>$val){ 
	    $arr[] = array('MS_Number_2' => $key+1,'MS_name_2' => $val['milestone'],'MS_Date_2'=>date('d F Y', strtotime($val['milestonedate']))); 
	} 
	return $arr;
   }
   
   
   function getEvalCriteriaData2($userid, $objUser){
	global $wp,$wpdb;
	$arr = array();
	
	$gtSelectionCriteria = $objUser->selectselectionCreteria($userid);   
	$fieldvalue = $gtSelectionCriteria['fieldvalue'];
    $resultarray = unserialize($fieldvalue); 
	   
	foreach($resultarray as $key=>$val){ 
	    $name = $objUser->scNameFromId($key);
	    $cretriaName = $name['sc_name'];
	 
	    $arr[] = array('Eval_P_names' => $cretriaName,'Eval_P_Weights'=>$val); 
	} 
	return $arr;
   }
   
   function getEvalCriteriaData($userid, $objUser){
	global $wp,$wpdb;
	$arr = array();
	
	$gtSelectionCriteria = $objUser->selectselectionCreteria($userid);
	
	$fieldvalue = $gtSelectionCriteria['fieldvalue'];
    $resultarray = unserialize($fieldvalue); 
	  
	foreach($resultarray as $key=>$val){ 
	    $name = $objUser->scNameFromId($key);
	    $cretriaName = $name['sc_name'];
	 
	    //$arr[] = array('Eval_PNos' => $key+1,'Eval_Ps' => $cretriaName,'Eval_P1_Weights'=>$val); 
		$arr[] = array('Eval_Ps' => $cretriaName,'Eval_P1_Weights'=>$val); 
	} 
	return $arr;
   }
   
   
   // get all stakeholder names
   function allStakeholders($userid){
	global $wp,$wpdb;
	$stQry = "SELECT fieldvalue FROM `wp_specdoc_userlistinfo` WHERE  userid ='$userid' AND fieldname='stackholders'";
	$stRes = $wpdb->get_row($stQry,ARRAY_A);
	
	 return $stRes;
   }
   
   // get all stakeholder/benefit count
   function allStakeholdersCount($userid){
	global $wp,$wpdb;
	$getVal=$this->allStakeholders($userid);
	$stArray =  explode(',',$getVal['fieldvalue']);
	$stCount = count($stArray);
	 return $stCount;
   }
   
   // get all stakeholders and display in table
   function getStakeholderTable($userid){
	global $wp,$wpdb;
	$arr = array();
	
	$gtStakeholders = $this->allStakeholders($userid);
	$stArray =  explode(',',$gtStakeholders['fieldvalue']);
	foreach($stArray as $key=>$val){
	    $arr[] = array('stakeholder_Group' => $val);
	}
	return $arr;
   }

/************
	Function for make HTML For Risk For Project Rationale Doc
	Function Written by Royal Tyagi on 9-12-2014

*************/
	
	function riskForProjectRationale( $userid )  {
		global $wpdb;

		$is_risk_management = get_option("risk_management");
		if ($is_risk_management == 1):

			$query = "SELECT * FROM ". $wpdb->prefix ."specdoc_riskManagement Where user_id= '".$userid."' ORDER BY id ASC ";
			$results = $wpdb->get_results($query,ARRAY_A);

			if (!empty($results)) {
				$html = '';
				foreach ( $results as $result ) {

					$html .= '<table width="622" cellpadding="-8" cellspacing="-8" style="font-family:Century Gothic;font-size:14.5px !important; border: 1px solid #a6a6a6; border-collapse: collapse;">';
					$html .= '<tr>';
					$html .=  '<td valign="top" style="padding:5px; outline: none;text-align:left; color:#4f81c3; border: 1px solid #a6a6a6; border-collapse: collapse;">Identified risk</td>';
					$html .= 	'<td valign="top" style="padding:5px; outline: none;text-align:left;color:#4f81c3; border: 1px solid #a6a6a6; border-collapse: collapse;">Risk type</td>';
					$html .=		'<td valign="top" style="padding:5px; outline: none;text-align:left;color:#4f81c3; border: 1px solid #a6a6a6; border-collapse: collapse;">Likelihood of occurrence</td>';
					$html .=	 '</tr>';
					$html .= '<tr>';
					$html .=		'<td valign="top" style="padding:5px; text-align:left; outline: none; border: 1px solid #a6a6a6; border-collapse: collapse;">' . $result['riskName'] . '</td>';
					$html .=		'<td valign="top" style="padding:5px; text-align:left; outline: none; border: 1px solid #a6a6a6; border-collapse: collapse;">' . $result['riskType'] . '</td>';
					$html .=		'<td valign="top" style="padding:5px; text-align:left; outline: none; border: 1px solid #a6a6a6; border-collapse: collapse;">' . $result['LikelihoodOfOccurrence'] . '</td>';
					$html .=		'</tr>';
					$html .=		'<tr>';
					$html .=		'<td valign="top" colspan="3" style="text-align: left; padding:5px; outline: none; color:#4f81c3; border: 1px solid #a6a6a6; border-collapse: collapse;">Risk description</td>';
					$html .=		'</tr>';
					$html .=		'<tr>';
					$html .=		'<td valign="top" colspan="3" style="text-align: left; padding:5px; outline: none; border: 1px solid #a6a6a6; border-collapse: collapse;">' . $result['riskDescription'] . '</td>';
					$html .=		'</tr>';
					$html .=	'</table>';
					$html .=	'<br/>';

				}
			} else {
				$html = '<p></p>';
			}

		else:
			$html = '<p>Risk Management is not enable yet.!</p>';
		endif;

		return $html;
	}
   

	function riskForCharterScope( $userid ) {
		global $wpdb;

		$is_risk_management = get_option("risk_management");
		if ($is_risk_management == 1):

			$query = "SELECT * FROM ". $wpdb->prefix ."specdoc_riskManagement Where user_id= '".$userid."' ORDER BY id ASC ";
			$results = $wpdb->get_results($query,ARRAY_A);

			$html = '';
			if (!empty($results)) {
				foreach ( $results as $result ) {

					$html .= '<h2 style="font-family:Century Gothic;font-size:14.5px !important;">' . $result['riskName'] . '</h2>';
					$html .= '<table width="622" cellpadding="-8" cellspacing="-8" style="font-family:Century Gothic;font-size:14.5px !important; border:1px solid #a6a6a6; border-collapse: collapse;">';
					$html .=		'<tr>';
					$html .=		'<td valign="top" style="padding:5px; outline: none;text-align:left; color:#4f81c3; border: 1px solid #a6a6a6; border-collapse: collapse;"  colspan="2">Person responsible</td>';
					$html .=		'<td valign="top" style="padding:5px; outline: none; border: 1px solid #a6a6a6; border-collapse: collapse; color:#4f81c3;">Risk type</td>';
					$html .=		'</tr>';
					$html .=		'<tr>';
					$html .=		'<td valign="top" style="padding:5px; outline: none; text-align:left; border: 1px solid #a6a6a6; border-collapse: collapse;" colspan="2">' . $result['riskName'] . '</td>';
					$html .=		'<td valign="top" style="padding:5px; outline: none; text-align:left; border: 1px solid #a6a6a6; border-collapse: collapse;">' . $result['riskType'] . '</td>';
					$html .=		'</tr>';
					$html .=		'<tr>';
					$html .=		'<td valign="top" style="padding:5px;border: 1px solid #a6a6a6; border-collapse: collapse; color:#4f81c3;">Likelihood of occurrence</td>';
					$html .=		'<td valign="top" style="padding:5px; border: 1px solid #a6a6a6; border-collapse: collapse; color:#4f81c3;">Significance of impact</td>';
					$html .=		'<td valign="top" style="padding:5px; border: 1px solid #a6a6a6; border-collapse: collapse;color:#4f81c3;">Monitoring cycle</td>';
					$html .=		'</tr>';
					$html .=		'<tr>';
					$html .=		'<td valign="top" style="padding:5px; border: 1px solid #a6a6a6; border-collapse: collapse;">' . $result['LikelihoodOfOccurrence'] . '</td>';
					$html .=		'<td valign="top" style="padding:5px; border: 1px solid #a6a6a6; border-collapse: collapse;">' . $result['impact'] . '</td>';
					$html .=		'<td valign="top" style="padding:5px; border: 1px solid #a6a6a6; border-collapse: collapse;">' . $result['monitoring_period'] . '</td>';
					$html .=		'</tr>';
					$html .=		'<tr>';
					$html .=		'<td valign="top" style="padding:5px; text-align:left; border: 1px solid #a6a6a6; border-collapse: collapse; color:#4f81c3;" colspan="3">Risk description</td>';
					$html .=		'</tr>';
					$html .=		'<tr>';
					$html .=		'<td valign="top" style="padding:5px; border: 1px solid #a6a6a6; border-collapse: collapse;" colspan="3">' . $result['riskDescription'] . '</td>';
					$html .=		'</tr>';
					$html .=		'<tr>';
					$html .=		'<td valign="top" style="padding:5px; text-align:left; border: 1px solid #a6a6a6; border-collapse: collapse; color:#4f81c3;" colspan="3">Mitigating actions</td>';
					$html .=		'</tr>';
					$html .=		'<tr>';
					$html .=		'<td valign="top" style="padding:5px; border: 1px solid #a6a6a6; border-collapse: collapse;" colspan="3">' . $result['mitigations_actions'] . '</td>';
					$html .=		'</tr>';
					$html .=		'<tr>';
					$html .=		'<td valign="top" style="padding:5px; text-align:left; border: 1px solid #a6a6a6; border-collapse: collapse; color:#4f81c3;" colspan="3">Procedure for addressing occurrence</td>';
					$html .=		'</tr>';
					$html .=		'<tr>';
					$html .=		'<td valign="top" style="padding:5px; border: 1px solid #a6a6a6; border-collapse: collapse;" colspan="3">' . $result['preventive_actions'] . '</td>';
					$html .=		'</tr>';
					$html .=		'</table>';
					$html .=		'<br/>';
				}
			} else {
				$html .=		'<p></p>';
			}

		else:
			$html = '<p>Risk Management is not enable yet.!</p>';
		endif;

		return $html;

	}
   
}

?>
