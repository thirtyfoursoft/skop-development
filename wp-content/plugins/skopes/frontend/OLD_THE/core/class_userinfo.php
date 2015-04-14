<?php
	include_once(RC_TC_BASE_DIR."/frontend/core/class_skopes.php");
	class userinfo
	{
	   function deleteCategory($catid)
	   {
	        global $wpdb;  
			$mlquery = "DELETE FROM ".$wpdb->prefix."specdoc_categories WHERE category_id = $catid"; 
	        $wpdb->query($mlquery);  
	   }
	   
	   function updateCategory($catname, $catdesc, $catIDUseID)
	   {
	        global $wpdb; 
			$user_id = get_current_user_id();
			$this->updateLastEditDate( $user_id );
			
			$mlquery = "UPDATE ".$wpdb->prefix."specdoc_categories 
			            SET name = '". htmlspecialchars($catname, ENT_QUOTES | ENT_HTML5, 'UTF-8')."',
						    text = '". htmlspecialchars($catdesc, ENT_QUOTES | ENT_HTML5, 'UTF-8')."'
			            WHERE category_id = $catIDUseID"; 
	           $wpdb->query($mlquery); 
	       return 'done';
	   }
	
	   function insertCategory($catname, $catdesc)
	   {
	        global $wpdb; 

			$user_id = get_current_user_id();

			$this->updateLastEditDate( $user_id );
			
			$mlquery = "INSERT INTO ".$wpdb->prefix."specdoc_categories (foruser, enabled, name, text) VALUES (".$user_id.", 1, '". htmlspecialchars($catname, ENT_QUOTES | ENT_HTML5, 'UTF-8')."', '". htmlspecialchars($catdesc, ENT_QUOTES | ENT_HTML5, 'UTF-8')."')"; 
	           $wpdb->query($mlquery); 
	       return 'done';
	   }
	
		/**
			check if user have added all the company information except logo
		**/
		function checkUserCompanyInfo($userid){
			 global $wpdb;
			 $count = 0;
			 $resultarray=array();
			 $quy = "SELECT * FROM wp_usermeta WHERE user_id ='$userid'"; 
			 $myrows = $wpdb->get_results($quy);

			 foreach($myrows as $key=>$value){
 
			  if($value->meta_key == 'company_address'){
				  $streetAddress = trim($value->meta_value); 
				  $resultarray['company_address']=$streetAddress;
				  if(!empty($streetAddress))
				   $count++;
			  }
			  
			  /* if($value->meta_key == 'companyname'){
				  $companyName = trim($value->meta_value);
				  $resultarray['companyname']=$companyName;
			  } */  
	  	  
			  if($value->meta_key == 'company_city'){
				  $city = trim($value->meta_value);
				  $resultarray['company_city'] = $city;
				  if(!empty($city))
				   $count++;
			  }
			  
			  if($value->meta_key == 'company_state'){
				  $state = trim($value->meta_value);
				  $resultarray['company_state']=$state;
				  if(!empty($city))
				   $count++;
			  }
			  
			  if($value->meta_key == 'company_zip'){
				  $zip = trim($value->meta_value);
				  $resultarray['company_zip']=$zip;
				  if(!empty($city))
				   $count++;
			  }
	  
			  if($value->meta_key == 'org_bg'){
				  $org_bg = $value->meta_value;
				  $resultarray['org_bg']=$org_bg;
				  if(!empty($org_bg))
				   $count++;
			  }
	  
			  //Organisational goals statement:
			   
			  if($value->meta_key == 'org_goal_state'){
				  $org_goal_state = trim($value->meta_value); 
				  $resultarray['org_goal_state']=$org_goal_state;
				  if(!empty($org_goal_state))
				   $count++;
			  }
			 
 
		}//end of foreach
		
		// $this->usercquestioncount($count);
		 
		if (in_array("", $resultarray)) {
			$result = 0;
		}else{
			$result = 1;
		}
		
		return $result;
		
	}
	
	function removemilestone($milestoneIdArray,$userid){
	  global $wpdb;

	$this->updateLastEditDate( $userid );
	  $totalMilestoneArray = $this->selectAllMilestonesIds($userid);
		
		
		foreach($totalMilestoneArray as $mkey=>$mval) {
		  if(in_array($mval,$milestoneIdArray)) {

		  }
		  else {
		    $mId = $mval;
		    $delquery = "DELETE FROM ".$wpdb->prefix."specdoc_usermilestones WHERE mid='".$mId."' AND user_id='$userid'";
			$wpdb->query($delquery);
		  }
		}
	}

//wp_specdoc_usermilestones
/* Save user Milestone */
	function saveMilestone($data,$userid){
		global $wpdb;

		$this->updateLastEditDate( $userid );
			
		$milestone = (!empty($data['milestone'])? $data['milestone'] : array());
		$milestonedate = (!empty($data['milestonedate'])? $data['milestonedate']: array());
		$elapsed = $data['elapsed'];
		$milestoneId = $data['msid'];

		//adding first milestone in array
		array_unshift($milestone, $data['firstmilestone']);
		array_unshift($milestonedate, $data['firstmilestonedate']);
		//adding last milestone in array
		array_push($milestone, $data['finalmilestone']);
		array_push($milestonedate, $data['finalmilestonedate']);
	   
		if(count($milestoneId)>0){
		 $allElements = $this->removemilestone($milestoneId,$userid);
		}
		foreach($milestone as $key=>$val) {
		  
		  $p = $milestonedate[$key];
		  if(empty($p) || $p == '0000-00-00') {
		    
		    $mldate = $p;
		  } else {
		   $mldate = date("Y-m-d", strtotime($p));
		  }
		  
		  $id = $this->chkMilestoneExist($userid,$milestoneId[$key]);
		   
			$val = str_replace('<!','< !',$val);
		  if($this->chkMilestoneExist($userid,$milestoneId[$key])>0){
			 //$this->chkMilestoneExist($userid,$val)			
			$mlquery = "UPDATE ".$wpdb->prefix."specdoc_usermilestones SET milestone ='". htmlspecialchars($val, ENT_QUOTES | ENT_HTML5, 'UTF-8')."', milestonedate='".$mldate."', elapsed='".$elapsed[$key]."' WHERE user_id='".$userid."' AND mid='".$milestoneId[$key]."'";
			
		  } else {
			$mlquery = "INSERT INTO ".$wpdb->prefix."specdoc_usermilestones (user_id,milestone,milestonedate,elapsed) values(".$userid.",'". htmlspecialchars($val, ENT_QUOTES | ENT_HTML5, 'UTF-8')."','".$mldate."','".$elapsed[$key]."')";
		  }

		  $wpdb->query($mlquery);
		}
		
		// update status table
		//$totalMilestones = $obj->titleFilledMilestonesCount($loggedin_id);
		//$filledMilestones = $obj->dateFilledMilestonesCount($loggedin_id);
		 $updateqry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$userid' AND section_name = 'milestonestatus' ";
		$wpdb->query($updateqry);  
	 return true; 
	}

	/* Save user TeamInfo */
	function selectAllMilestones($userid){
		global $wpdb;
		 
		$query = "SELECT * From ".$wpdb->prefix."specdoc_usermilestones Where user_id= '".$userid."'";
		$result = $wpdb->get_results($query,ARRAY_A);
	 return $result;
	}

	/* Milestone Ids */
	function selectAllMilestonesIds($userid){
		global $wpdb;
		 
		$query = "SELECT mid From ".$wpdb->prefix."specdoc_usermilestones Where user_id= '".$userid."'";
		$result = $wpdb->get_col($query);
		
	 return $result;
	}


	//function chkMilestoneExist($userid,$ms_name){
	function chkMilestoneExist($userid,$ms_id){
		global $wpdb;
		 
		$query = "SELECT count(*) From ".$wpdb->prefix."specdoc_usermilestones Where user_id= '".$userid."' AND mid='".$ms_id."'";
		$result = $wpdb->get_var($query);
		
		return $result;
	}
	
	function existMilestoneId($userid,$ms_name){
		global $wpdb;
		 
		$query = "SELECT mid From ".$wpdb->prefix."specdoc_usermilestones Where user_id= '".$userid."' AND milestone='".$ms_name."'";
		$result = $wpdb->get_col($query);
		
		return $result;
	}
	
	// get milestone titles
	function milestoneTitles($i) {
	 switch ($i) {
		case 1:
			$text = 'Milestone one: Supplier engaged';
			break;
		case 2:
			$text = 'Milestone two: Scoping and planning complete';
			break;
		case 3:
			$text = 'Milestone three: Interface design complete';
			break;
		case 4:
			$text = 'Milestone four: Development complete';
			break;
		case 5:
			$text = 'Milestone five: User testing complete';
			break;
		case 6:
			$text = 'Milestone six: training complete and system live';
			break;
		default:
			$text = 'Milestone'.$i;
			break;
			
	 }

		return $text;
	}
	
	/** check org questions **/
	function cquestioncount(){ 
		global $wpdb;
	  // select count of questions which admin selected to display on frontend
	  $qry = "SELECT count(*) FROM `wp_specdoc_field_items` where status = 1 and type='cq' and id<>1 ";
	  $result = $wpdb->get_var($qry);
		
	  return $result;
	  
	}
	
	function checkcFieldStatus($fieldname){
	  global $wpdb;
	  // select count of questions which admin selected to display on frontend
	  $qry = "SELECT status FROM ".$wpdb->prefix."specdoc_field_items WHERE fieldname='$fieldname'";
	  $result = $wpdb->get_col($qry);
	  $resultval = $result[0];
	  
	  return $resultval;
	}

    /** check org questions filled by user including the logo **/
	function usercquestioncount_custom($userid){
		global $wpdb;
		 $compcount = 0;
		 $resultarray = array();
		 $quy = "SELECT * FROM wp_usermeta WHERE user_id ='$userid'";  
		 $myrows = $wpdb->get_results($quy);
		 
		 foreach($myrows as $key=>$value){
 
			 /*  if(($value->meta_key == 'company_address') && ($this->checkcFieldStatus('company_question2') == 1)){
			   
				  $streetAddress = trim($value->meta_value); 
				  $resultarray['company_address'] = $streetAddress;
				  if(!empty($streetAddress))
				   $compcount++;
				   
			  }
			 
			  if($value->meta_key == 'companyname'){
				  $companyName = trim($value->meta_value);
				  $resultarray['companyname']=$companyName;
			  } */  
			  
			  /* if($value->meta_key == 'com_logo'){
				  $companyLogo = trim($value->meta_value);
				  $resultarray['companylogo']=$companyName;
				   if(!empty($companyLogo))
				   $count++;
			  }
	  	  
			  if(($value->meta_key == 'company_city')&& ($this->checkcFieldStatus('company_question3') == 1)){
				  $city = trim($value->meta_value);
				  $resultarray['company_city'] = $city;
				  if(!empty($city))
				   $compcount++;				   
				    
			  }
			  
			  if(($value->meta_key == 'company_state')&&($this->checkcFieldStatus('company_question4') == 1)){
				  $state = trim($value->meta_value);
				  $resultarray['company_state']=$state;
				  if(!empty($state))
				   $compcount++;
			  }
			  
			  if(($value->meta_key == 'company_zip')&&($this->checkcFieldStatus('company_question5') == 1)){
				  $zip = trim($value->meta_value);
				  $resultarray['company_zip']=$zip;
				  if(!empty($zip))
				   $compcount++;
			  }
	  
			  if(($value->meta_key == 'org_bg')&&($this->checkcFieldStatus('company_question6') == 1)){
				  $org_bg = $value->meta_value;
				  $resultarray['org_bg']=$org_bg;
				  if(!empty($org_bg))
				   $compcount++;
			  }
	  
			  //Organisational goals statement:
			   
			  if(($value->meta_key == 'org_goal_state')&&($this->checkcFieldStatus('company_question7') == 1)){
				  $org_goal_state = trim($value->meta_value); 
				  $resultarray['org_goal_state']=$org_goal_state;
				  if(!empty($org_goal_state))
				   $compcount++;
			  } */
			  
			  if(($value->meta_key == 'comp_ques2')&&($this->checkcFieldStatus('company_question2') == 1)){
				  $comp_ques2 = trim($value->meta_value); 
				  $resultarray['comp_ques2']=$comp_ques2;
				  if(!empty($comp_ques2))
				   $compcount++;
			  }
			  
			  

			  if(($value->meta_key == 'comp_ques3')&&($this->checkcFieldStatus('company_question3') == 1)){
				  $comp_ques3 = trim($value->meta_value); 
				  $resultarray['comp_ques3']=$comp_ques3;
				  if(!empty($comp_ques3))
				   $compcount++;
			  }
			  if(($value->meta_key == 'comp_ques4')&&($this->checkcFieldStatus('company_question4') == 1)){
				  $comp_ques4 = trim($value->meta_value); 
				  $resultarray['comp_ques4']=$comp_ques4;
				  if(!empty($comp_ques4))
				   $compcount++;
			  }
			  if(($value->meta_key == 'comp_ques5')&&($this->checkcFieldStatus('company_question5') == 1)){
				  $comp_ques5 = trim($value->meta_value); 
				  $resultarray['comp_ques5']=$comp_ques5;
				  if(!empty($comp_ques5))
				   $compcount++;
			  }
			  if(($value->meta_key == 'comp_ques6')&&($this->checkcFieldStatus('company_question6') == 1)){
				  $comp_ques6 = trim($value->meta_value); 
				  $resultarray['comp_ques6']=$comp_ques6;
				  if(!empty($comp_ques6))
				   $compcount++;
			  }
			  if(($value->meta_key == 'comp_ques7')&&($this->checkcFieldStatus('company_question7') == 1)){
				  $comp_ques7 = trim($value->meta_value); 
				  $resultarray['comp_ques7']=$comp_ques7;
				  if(!empty($comp_ques7))
				   $compcount++;
			  }
			   
			  
			  if(($value->meta_key == 'org_ques_8')&&($this->checkcFieldStatus('company_question8') == 1)){
				  $org_ques_8 = trim($value->meta_value); 
				  $resultarray['org_ques_8']=$org_ques_8;
				  if(!empty($org_ques_8))
				   $compcount++;
			  }
			  
			   if(($value->meta_key == 'org_ques_9_state')&&($this->checkcFieldStatus('company_question9') == 1)){
				  $org_ques_9 = trim($value->meta_value); 
				  $resultarray['org_ques_9_state']=$org_ques_9;
				  if(!empty($org_ques_9))
				   $compcount++;
			  }
			  
			   if(($value->meta_key == 'org_ques_10_state')&&($this->checkcFieldStatus('company_question10') == 1)){
				  $org_ques_10 = trim($value->meta_value); 
				  $resultarray['org_ques_10_state']=$org_ques_10;
				  if(!empty($org_ques_10))
				   $compcount++;
			  }

			   if(($value->meta_key == 'org_ques_11')&&($this->checkcFieldStatus('company_question11') == 1)){
				  $org_ques_11 = trim($value->meta_value);
				  $resultarray['org_ques_11'] = $org_ques_11;
				  if(!empty($org_ques_11))
				   $compcount++;
			  }

 		}//end of foreach	 
		return  $compcount;
	}
	
	
	/** check org questions filled by user including the logo **/
	function usercquestioncount($userid) {
		global $wpdb;
		 $compcount = 0;
		 $resultarray = array();
		 $quy = "SELECT * FROM wp_usermeta WHERE user_id ='$userid'";  
		 $myrows = $wpdb->get_results($quy);
		 
		 foreach($myrows as $key=>$value){
 
			  if(($value->meta_key == 'company_address') && ($this->checkcFieldStatus('company_question2') == 1)){

				  $streetAddress = trim($value->meta_value);
				  $resultarray['company_address'] = $streetAddress;
				  if(!empty($streetAddress))
				   $compcount++;

			  }
			 
			  /* if($value->meta_key == 'companyname'){
				  $companyName = trim($value->meta_value);
				  $resultarray['companyname']=$companyName;
			  } */  
			  
			  /* if($value->meta_key == 'com_logo'){
				  $companyLogo = trim($value->meta_value);
				  $resultarray['companylogo']=$companyName;
				   if(!empty($companyLogo))
				   $count++;
			  } */
	  	  
			  if(($value->meta_key == 'company_city')&& ($this->checkcFieldStatus('company_question3') == 1)){
				  $city = trim($value->meta_value);
				  $resultarray['company_city'] = $city;
				  if(!empty($city))
				   $compcount++;				   
				    
			  }
			  
			  if(($value->meta_key == 'company_state')&&($this->checkcFieldStatus('company_question4') == 1)){
				  $state = trim($value->meta_value);
				  $resultarray['company_state']=$state;
				  if(!empty($state))
				   $compcount++;
			  }
			  
			  if(($value->meta_key == 'company_zip')&&($this->checkcFieldStatus('company_question5') == 1)){
				  $zip = trim($value->meta_value);
				  $resultarray['company_zip']=$zip;
				  if(!empty($zip))
				   $compcount++;
			  }
	  
			  if(($value->meta_key == 'org_bg')&&($this->checkcFieldStatus('company_question6') == 1)){
				  $org_bg = $value->meta_value;
				  $resultarray['org_bg']=$org_bg;
				  if(!empty($org_bg))
				   $compcount++;
			  }
	  
			  //Organisational goals statement:
			   
			  if(($value->meta_key == 'org_goal_state')&&($this->checkcFieldStatus('company_question7') == 1)){
				  $org_goal_state = trim($value->meta_value); 
				  $resultarray['org_goal_state']=$org_goal_state;
				  if(!empty($org_goal_state))
				   $compcount++;
			  }
			  
			  if(($value->meta_key == 'org_ques_8')&&($this->checkcFieldStatus('company_question8') == 1)){
				  $org_ques_8 = trim($value->meta_value); 
				  $resultarray['org_ques_8']=$org_ques_8;
				  if(!empty($org_ques_8))
				   $compcount++;
			  }
			  
			   if(($value->meta_key == 'org_ques_9_state')&&($this->checkcFieldStatus('company_question9') == 1)){
				  $org_ques_9 = trim($value->meta_value); 
				  $resultarray['org_ques_9_state']=$org_ques_9;
				  if(!empty($org_ques_9))
				   $compcount++;
			  }
			  
			   if(($value->meta_key == 'org_ques_10_state')&&($this->checkcFieldStatus('company_question10') == 1)){
				  $org_ques_10 = trim($value->meta_value); 
				  $resultarray['org_ques_10_state']=$org_ques_10;
				  if(!empty($org_ques_10))
				   $compcount++;
			  }

			   if(($value->meta_key == 'org_ques_11')&&($this->checkcFieldStatus('company_question11') == 1)){
				  $org_ques_11 = trim($value->meta_value); 
				  $resultarray['org_ques_11']=$org_ques_11;
				  if(!empty($org_ques_11))
				   $compcount++;
			  }
			  
 
		}//end of foreach	 
		return  $compcount;
	}
	
	/** check project questions **/
	function proquestioncount(){
	  global $wpdb;
	  // select count of questions which admin selected to display on frontend
	  $qry = "SELECT count(*) FROM `".$wpdb->prefix."specdoc_field_items` where status = 1 and type='pq' 
	  ";
	  $result = $wpdb->get_var($qry);
		
	  return $result;
	  
	}
	
	/** get TEAM lead name entered by user **/
	function checkTlValue($userid){
		global $wpdb;
		$qry = "SELECT member_name FROM ".$wpdb->prefix."specdoc_userteaminfo where userid='$userid'and member_type='tl'"; 
	    $result = $wpdb->get_col($qry);
		
		if(!empty($result[0])){
		 $result = 1;
		}else{
		 $result = 0;
		}
		
	 return $result;
	}
	
	function userproquestioncount_custom($userid){
		global $wpdb;
		 $count = 0;
		 $resultarray=array();
		 $quy = "SELECT * FROM ".$wpdb->prefix."usermeta WHERE user_id ='$userid'"; 
		 $myrows = $wpdb->get_results($quy);

		 foreach($myrows as $key=>$value){
   
			  if(($value->meta_key == 'authors')&&($this->checkcFieldStatus('project_question1') == 1)){
				  $authors = trim($value->meta_value); 
				  $resultarray['authors']=$authors;
				  if(!empty($authors))
				   $count++;
			  }
			  
			  if(($value->meta_key == 'pro_ques2')&&($this->checkcFieldStatus('project_question2') == 1)){
				  $pro_ques2 = trim($value->meta_value);
				  $resultarray['pro_ques2']=$pro_ques2;
				   if(!empty($pro_ques2))
				   $count++;
			  }
			  if(($value->meta_key == 'pro_ques3')&&($this->checkcFieldStatus('project_question3') == 1)){
				  $pro_ques3 = trim($value->meta_value);
				  $resultarray['pro_ques3']=$pro_ques3;
				   if(!empty($pro_ques3))
				   $count++;
			  }
			  if(($value->meta_key == 'pro_ques4')&&($this->checkcFieldStatus('project_question4') == 1)){
				  $pro_ques4 = trim($value->meta_value);
				  $resultarray['pro_ques4']=$pro_ques4;
				   if(!empty($pro_ques4))
				   $count++;
			  }
			  if(($value->meta_key == 'pro_ques5')&&($this->checkcFieldStatus('project_question5') == 1)){
				  $pro_ques5 = trim($value->meta_value);
				  $resultarray['pro_ques5']=$pro_ques5;
				   if(!empty($pro_ques5))
				   $count++;
			  }
			  if(($value->meta_key == 'pro_ques6')&&($this->checkcFieldStatus('project_question6') == 1)){
				  $pro_ques6 = trim($value->meta_value);
				  $resultarray['pro_ques6']=$pro_ques6;
				   if(!empty($pro_ques6))
				   $count++;
			  }
			  if(($value->meta_key == 'pro_ques7')&&($this->checkcFieldStatus('project_question7') == 1)){
				  $pro_ques7 = trim($value->meta_value);
				  $resultarray['pro_ques7']=$pro_ques7;
				   if(!empty($pro_ques7))
				   $count++;
			  } 
			   if(($value->meta_key == 'pro_ques8')&&($this->checkcFieldStatus('project_question8') == 1)){
				  $pro_ques8 = trim($value->meta_value);
				  $resultarray['pro_ques8']=$pro_ques8;
				   if(!empty($pro_ques8))
				   $count++;
			  } 
			   if(($value->meta_key == 'pro_ques9')&&($this->checkcFieldStatus('project_question9') == 1)){
				  $pro_ques9 = trim($value->meta_value);
				  $resultarray['pro_ques9']=$pro_ques9;
				   if(!empty($pro_ques9))
				   $count++;
			  }
			   if(($value->meta_key == 'pro_ques10')&&($this->checkcFieldStatus('project_question10') == 1)){
				  $pro_ques10 = trim($value->meta_value);
				  $resultarray['pro_ques10']=$pro_ques10;
				   if(!empty($pro_ques10))
				   $count++;
			  }

			   if(($value->meta_key == 'pro_ques11')&&($this->checkcFieldStatus('project_question11') == 1)){
				  $pro_ques11 = trim($value->meta_value);
				  $resultarray['pro_ques11']=$pro_ques11;
				   if(!empty($pro_ques11))
				   $count++;
			  }
			  
			  
            /*    
			  if(($value->meta_key == 'project_objectives')&&($this->checkcFieldStatus('project_question2') == 1)){
				  $project_objectives = trim($value->meta_value);
				  $resultarray['project_objectives']=$project_objectives;
				   if(!empty($project_objectives))
				   $count++;
			  }
	  	  
			  if(($value->meta_key == 'project_budget')&&($this->checkcFieldStatus('project_question3') == 1)){
				  $project_budget = trim($value->meta_value);
				  $resultarray['project_budget'] = $project_budget;
				  if(!empty($project_budget))
				   $count++;
			  }	
			
			  if(($value->meta_key == 'supplier_engaged')&&($this->checkcFieldStatus('project_question4') == 1)){
				  $supplier_engaged = trim($value->meta_value);
				  $resultarray['supplier_engaged'] = $supplier_engaged;
				  if(!empty($supplier_engaged))
				   $count++;
			  }	
			  
			  if(($value->meta_key == 'spc')&&($this->checkcFieldStatus('project_question5') == 1)){
				  $spc = trim($value->meta_value);
				  $resultarray['spc'] = $spc;
				  if(!empty($spc))
				   $count++;
			  }
			  
			  if(($value->meta_key == 'idc')&&($this->checkcFieldStatus('project_question6') == 1)){
				  $idc = trim($value->meta_value);
				  $resultarray['idc'] = $idc;
				  
				  if(!empty($idc))
				   $count++;
			  }
			  
			  if(($value->meta_key == 'dc')&&($this->checkcFieldStatus('project_question7') == 1)){
				  $dc = trim($value->meta_value);
				  $resultarray['dc'] = $dc;
				  
				  if(!empty($dc))
				   $count++;
			  }
			  
			  if(($value->meta_key == 'utc')&&($this->checkcFieldStatus('project_question8') == 1)){
				  $utc = trim($value->meta_value);
				  $resultarray['utc'] = $utc;
				  
				  if(!empty($utc))
				   $count++;
			  }
			  
			  if(($value->meta_key == 'tcsl')&&($this->checkcFieldStatus('project_question9') == 1)){
				  $tcsl = trim($value->meta_value);
				  $resultarray['tcsl'] = $tcsl;
				  
				  if(!empty($tcsl))
				   $count++;
			  }
			  
			  if(($value->meta_key == 'proques10')&&($this->checkcFieldStatus('project_question10') == 1)){
				  $proques10 = trim($value->meta_value);
				  $resultarray['proques10'] = $proques10;
				  
				  if(!empty($proques10))
				   $count++;
			  } */

 
		}//end of foreach
		return  $count;
	}
	
	
	/** check project questions filled by user **/
	
	function userproquestioncount($userid){
		global $wpdb;
		 $count = 0;
		 $resultarray=array();
		 $quy = "SELECT * FROM ".$wpdb->prefix."usermeta WHERE user_id ='$userid'"; 
		 $myrows = $wpdb->get_results($quy);

		 foreach($myrows as $key=>$value){
   
			  if(($value->meta_key == 'authors')&&($this->checkcFieldStatus('project_question1') == 1)){
				  $authors = trim($value->meta_value); 
				  $resultarray['authors']=$authors;
				  if(!empty($authors))
				   $count++;
			  }  

			  if(($value->meta_key == 'project_objectives')&&($this->checkcFieldStatus('project_question2') == 1)){
				  $project_objectives = trim($value->meta_value);
				  $resultarray['project_objectives']=$project_objectives;
				   if(!empty($project_objectives))
				   $count++;
			  }
	  	  
			  if(($value->meta_key == 'project_budget')&&($this->checkcFieldStatus('project_question3') == 1)){
				  $project_budget = trim($value->meta_value);
				  $resultarray['project_budget'] = $project_budget;
				  if(!empty($project_budget))
				   $count++;
			  }	
			
			  if(($value->meta_key == 'supplier_engaged')&&($this->checkcFieldStatus('project_question4') == 1)){
				  $supplier_engaged = trim($value->meta_value);
				  $resultarray['supplier_engaged'] = $supplier_engaged;
				  if(!empty($supplier_engaged))
				   $count++;
			  }	
			  
			  if(($value->meta_key == 'spc')&&($this->checkcFieldStatus('project_question5') == 1)){
				  $spc = trim($value->meta_value);
				  $resultarray['spc'] = $spc;
				  if(!empty($spc))
				   $count++;
			  }
			  
			  if(($value->meta_key == 'idc')&&($this->checkcFieldStatus('project_question6') == 1)){
				  $idc = trim($value->meta_value);
				  $resultarray['idc'] = $idc;
				  
				  if(!empty($idc))
				   $count++;
			  }
			  
			  if(($value->meta_key == 'dc')&&($this->checkcFieldStatus('project_question7') == 1)){
				  $dc = trim($value->meta_value);
				  $resultarray['dc'] = $dc;
				  
				  if(!empty($dc))
				   $count++;
			  }
			  
			  if(($value->meta_key == 'utc')&&($this->checkcFieldStatus('project_question8') == 1)){
				  $utc = trim($value->meta_value);
				  $resultarray['utc'] = $utc;
				  
				  if(!empty($utc))
				   $count++;
			  }
			  
			  if(($value->meta_key == 'tcsl')&&($this->checkcFieldStatus('project_question9') == 1)){
				  $tcsl = trim($value->meta_value);
				  $resultarray['tcsl'] = $tcsl;
				  
				  if(!empty($tcsl))
				   $count++;
			  }
			  
			  if(($value->meta_key == 'proques10')&&($this->checkcFieldStatus('project_question10') == 1)){
				  $proques10 = trim($value->meta_value);
				  $resultarray['proques10'] = $proques10;
				  
				  if(!empty($proques10))
				   $count++;
			  }

			  if(($value->meta_key == 'proques11')&&($this->checkcFieldStatus('project_question11') == 1)){
				  $proques11 = trim($value->meta_value);
				  $resultarray['proques10'] = $proques11;
				  
				  if(!empty($proques11))
				   $count++;
			  }

 
		}//end of foreach
		return  $count;
	}
	
	// project page section total count 
	function projectpagecount(){
	
	 //Adding 1 for team area section , 1 for Key project dates  area and 1 for stake holder section
//	 $count = $this->proquestioncount()+3;

		$is_risk_management = get_option("risk_management");
		if ($is_risk_management == 1) {
			 $count = $this->proquestioncount()+4;
		} else {
			 $count = $this->proquestioncount()+3; 
		}

	  return $count;
	  
	}
	
	// project page section user total count 
	function projectpageusercount($userid){
	
	 //Adding 1 for team area section and 1 for milestone area  
	  //$pqcount = $this->userproquestioncount($userid);
	  $pqcount = $this->userproquestioncount_custom($userid);
	  
	  // if Team lead value added by user then add  1 in total count
	  $tlcount = $this->checkTlValue($userid);
	  
	  // if at least one milestone added by user then add  1 in total count
/*	  
      $milestonecount = $this->titleFilledMilestonesCount($userid);
	  $mcount = $milestonecount>0 ? 1:0; 
*/

		// If at least one value added by user in keys date section
		$keysDate = $this->checkkeysDate($userid);

	  // if at least one stakeholder added by user then add  1 in total count
	  $scCount = $this->stakeholderCount($userid);

//	  	$count = $scCount+$pqcount+$tlcount+$keysDate;

	$is_risk_management = get_option("risk_management");
	if ($is_risk_management == 1 ) {

		$risk = $this->riskCount($userid);
		$count = $scCount+$pqcount+$tlcount+$keysDate+$risk;
	} else {
	
		$count = $scCount+$pqcount+$tlcount+$keysDate;
	}

	  return $count;
	  
	}
	
	//check user profile fields
	function GetUserProfileInfo($userid)
	{	 
	    $counter = 0;
		
		$firstName = get_user_meta($userid, 'first_name', true) == ''? '': $counter++;
		$lastName = get_user_meta($userid, 'last_name', true)== ''? '': $counter++;
		$user_info = get_userdata($userid);
		$email = $user_info->user_email == ''? '': $counter++;
		$website = $user_info->user_url== ''? '': $counter++;
		
		return $counter;
	}
	
	/* Milestone Ids count */
	function selectAllMilestonesCount($userid){
		global $wpdb;
		 
		$query = "SELECT count(*) From ".$wpdb->prefix."specdoc_usermilestones Where user_id= '".$userid."'";
		$result = $wpdb->get_var($query);
		
	 return $result;
	}
	
	/* Milestone count with title filled */
	function titleFilledMilestonesCount($userid){
		global $wpdb;
		 
		$query = "SELECT count(*) From ".$wpdb->prefix."specdoc_usermilestones Where user_id= '".$userid."' AND milestone <>''";
		$result = $wpdb->get_var($query);
		
	 return $result;
	}
	
	
	/* Milestone count with title filled and date filled */
	function dateFilledMilestonesCount($userid){
		global $wpdb;
		 
		$query = "SELECT count(*) From ".$wpdb->prefix."specdoc_usermilestones Where user_id= '".$userid."' AND milestonedate <> '0000-00-00' AND milestone <> ''";
		$result = $wpdb->get_var($query);
		
	 return $result;
	}

	/* Keys date */

	function checkkeysDate( $userid ) {

		$count = 0;
		$keysDate = get_user_meta( $userid, '_keysProjectDate', true);
		$dateKeys = unserialize($keysDate);

		if (empty($dateKeys)) {
			$result = 0;
			return $result;
		}

		foreach ( $dateKeys as $dateKey ) {
			if ($dateKey == '') {
				$count++;
			}
		}

		if ($count > 0) {
			$result = 0;
		} else {
			$result = 1;
		}
		return $result;
	}
	
	/* Stakeholder count */
	function stakeholderCount($userid){
		global $wpdb;
		 
		 $query = "SELECT fieldvalue From ".$wpdb->prefix."specdoc_userlistinfo Where userid= '".$userid."' AND fieldname ='stackholders' ";
		$qryresult = $wpdb->get_col($query);
		if(!empty($qryresult)){
			$result = 1;
		}else{
		    $result = 0;
		}
		
	 return $result;
	}

	function riskCount( $userid ) {
		global $wpdb;
		 
		$query = "SELECT count(*) From ".$wpdb->prefix."specdoc_riskManagement Where user_id= '".$userid."'";
		$result = $wpdb->get_col($query);

		if ($result[0] > 0) {
			$return = 1;
		} else {
			$return = 0;
		}
		 return $return;
	}

	function riskCountForMoreInfo( $userid ) {
		global $wpdb;
		
		$query = "SELECT count(*) From ".$wpdb->prefix."specdoc_riskManagement Where user_id= '".$userid."' AND preventive_actions !='' AND mitigations_actions !=''";
		$result = $wpdb->get_col($query);

		if ($result[0] > 0) {
			$return = 1;
		} else {
			$return = 0;
		}

		 return $return;		
	}
	
	/* Benefits count */
	function benefitsCount($userid){
		global $wpdb;
		 
		 $query = "SELECT fieldvalue From ".$wpdb->prefix."specdoc_userlistinfo Where userid= '".$userid."' AND fieldname ='anticipated_benefits' ";
		$qryresult = $wpdb->get_col($query);
		if(!empty($qryresult)){
			$result = 1;
		}else{
		    $result = 0;
		}
		
	 return $result;
	}
	
	/** get TEAM lead responsiblity and estimate effort entered by user **/
	function checkTlResValue($userid){
		global $wpdb;
		$qry = "SELECT member_responsibility  FROM ".$wpdb->prefix."specdoc_userteaminfo where userid='$userid' and member_type='tl'"; 
	    $result = $wpdb->get_col($qry);
		
		if(!empty($result[0])){
		 $result = 1;
		}else{
		 $result = 0;
		}
		
	 return $result;
	}
	
	
	/** get selection cretria entered by user **/
	function checkSelctionCreteria($userid){
		global $wpdb;
		
		$query = "SELECT fieldvalue From ".$wpdb->prefix."specdoc_userlistinfo Where userid= '".$userid."' AND fieldname ='selection_creteria' ";		
		$qryresult = $wpdb->get_col($query);
		
		if(!empty($qryresult)){
			$result = 1;
		}else{
		    $result = 0;
		}
		
	 return $result;
	}
	/** more total count **/
	
	function projectmoreCount($userid){
		global $wpdb;
		 
		 //$scCount = $this->stakeholderCount($userid);
		 $benefitCount = $this->benefitsCount($userid);
		 $TlResCount = $this->checkTlResValue($userid);
		 $SelCount = $this->checkSelctionCreteria($userid);

		$milestonecount = $this->titleFilledMilestonesCount($userid);
		$mcount = $milestonecount>0 ? 1:0;

		$is_risk_management = get_option("risk_management");
		if ($is_risk_management == 1 ) {

			$riskMoreInfo = $this->riskCountForMoreInfo($userid);
			$total =  $benefitCount+$TlResCount+$SelCount+$mcount+$riskMoreInfo;

		} else {
	
			$total =  $benefitCount+$TlResCount+$SelCount+$mcount;
		}
		 
		//$total =  $benefitCount+$TlResCount+$SelCount+$mcount;
	 return $total;
	}

	 function getuserstage($totalCompanyQues,$userCompanyQues,$totalProjectQues,$userProjectQues,$userFA){
		
		 $count = 0;
		 if($totalCompanyQues == $userCompanyQues){
		  $count++;
		 }
		 
		 if($totalProjectQues == $userProjectQues){
		  $count++;
		 }
		 		 
		 $count = $count+$userFA;
		 
		 return $count;
	 
	}
	
	/** get user team total count **/
	
	function userTeamCount($userid){
		global $wpdb;
		 
		 $query = "SELECT count(*) FROM ".$wpdb->prefix."specdoc_userteaminfo where userid = '$userid'";
		 $qryresult = $wpdb->get_var($query);		
		
		return $qryresult;
	}
	
	
	/** get user added team information total count **/
	
	function userTeamInfoCount($userid){
		global $wpdb;
		 
		 $query = "SELECT count(*) FROM ".$wpdb->prefix."specdoc_userteaminfo where userid = '$userid' and member_name<>''";
		 $qryresult = $wpdb->get_var($query);		
		
		return $qryresult;
	}
	
	
	/* user added Milestone count based on dates */
	function selectUserMilestoneCount($userid){
		global $wpdb;
		 
		$query = "SELECT count(*) From ".$wpdb->prefix."specdoc_usermilestones Where user_id= '".$userid."' AND milestone <>'' ";
		$result = $wpdb->get_var($query);
		
	 return $result;
	}
	
	
	/** get member responsiblity entered by user **/
	function checkResValue($userid){
		global $wpdb;
		$qry = "SELECT count(*) FROM ".$wpdb->prefix."specdoc_userteaminfo where userid='$userid' AND member_responsibility <>''"; 
	    $result = $wpdb->get_var($qry);
		
		return $result;
	}
	
	/** get total number of members entered by user **/
	function memberTotalCount($userid){
		global $wpdb;
		$qry = "SELECT count(*) FROM ".$wpdb->prefix."specdoc_userteaminfo where userid='$userid'"; 
	    $result = $wpdb->get_var($qry);
		
	  return $result;
	}
	
		
	/* select User project question status
	params : userid
	return : status value
	*/
	function selectProjectQuesStatus($userid){
		global $wpdb;

		// get the section status value
		$selectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$userid' AND section_name='projectquesinfo'";
		$pstatus = $wpdb->get_col($selectqry); 
		return $pstatus[0];
	}
	
	/* Select User project Team Initial status
	params : userid
	return : status value
	*/
	function selectTeamInitialStatus($userid){
		global $wpdb;

		// get the section status value
		$selectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$userid' AND section_name='teaminialinfo'";
		$tstatus = $wpdb->get_col($selectqry); 
		return $tstatus[0];
	}
	
	/* Select User project Milestone status
	params : userid
	return : status value
	*/
	function selectMilestoneStatus($userid){
		global $wpdb;

		// get the section status value
		$selectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$userid' AND section_name='milestonestatus'";
		$tstatus = $wpdb->get_col($selectqry); 
		return $tstatus[0];
	}
	
	/* Select User project Milestone status
	params : userid
	return : status value
	*/
	function selectStakeholderStatus($userid){
		global $wpdb;

		// get the section status value
		$selectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$userid' AND section_name='stakeholderinfo'";
		$tstatus = $wpdb->get_col($selectqry); 
		return $tstatus[0];
	}
	
	/* Select User project benefit status
	params : userid
	return : status value
	*/
	function selectBenefitStatus($userid){
		global $wpdb;

		// get the section status value
		$selectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$userid' AND section_name='benefitinfo'";
		$tstatus = $wpdb->get_col($selectqry); 
		return $tstatus[0];
	}
	
	/* Select User project Selection creteria status
	params : userid
	return : status value
	*/
	function selectScStatus($userid){
		global $wpdb;

		// get the section status value
		$selectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$userid' AND section_name='selection_creteria_info'";
		$tstatus = $wpdb->get_col($selectqry); 
		return $tstatus[0];
	}
	/** count user added total values **/		
		function userABtotalcount($userid){
			global $wpdb;
			 
			$query = "SELECT fieldvalue From ".$wpdb->prefix."specdoc_userlistinfo Where userid= '".$userid."' AND fieldname='anticipated_benefits'";
			$result = $wpdb->get_row($query,ARRAY_A);			
			$res = explode(',',$result['fieldvalue']);
			$total_count = count($res);
		 return $total_count;
		}
	/** count user added values **/		
		function userABcount($userid){
			global $wpdb;
			 
			$query = "SELECT fieldvalue From ".$wpdb->prefix."specdoc_userlistinfo Where userid= '".$userid."' AND fieldname='anticipated_benefits'";
			$result = $wpdb->get_row($query,ARRAY_A);			
			$res = explode(',',$result['fieldvalue']);
			$total_count = count(array_filter($res));
		 return $total_count;
		}
		
	/** count user stakeholder total values **/		
		function userSHtotalcount($userid){
			global $wpdb;
			 
			$query = "SELECT fieldvalue From ".$wpdb->prefix."specdoc_userlistinfo Where userid= '".$userid."' AND fieldname='stackholders'";
			$result = $wpdb->get_row($query,ARRAY_A);			
			$res = explode(',',$result['fieldvalue']);
			$total_count = count($res);
		 return $total_count;
		}
	/** count user added stakeholder values **/		
		function userSHcount($userid){
			global $wpdb;
			 
			$query = "SELECT fieldvalue From ".$wpdb->prefix."specdoc_userlistinfo Where userid= '".$userid."' AND fieldname='stackholders'";
			$result = $wpdb->get_row($query,ARRAY_A);			
			$res = explode(',',$result['fieldvalue']);
			$total_count = count(array_filter($res));
		 return $total_count;
		}
		
		/** update user guide status **/		
		function updateGuideStatus($userid,$item_id,$itemStatus){
            global $wpdb;

			$this->updateLastEditDate( $userid );
            
		   $slctRes = $this->selectGuideStatus($userid,$item_id);
		   if(!empty($slctRes) ||($slctRes != '') ){
		    
			  //echo 'updte';
			$wpdb->query("UPDATE ".$wpdb->prefix."specdoc_guide_user_items SET item_status = $itemStatus WHERE user_id = $userid AND item_id = '$item_id'");
		     
		   }else{	
		   //echo 'insert';
			  $this->insertGuideStatus($userid,$item_id,$itemStatus);
		   }
		   
		}
		/** insert user guide status **/
		function insertGuideStatus($userid,$item_id,$itemStatus){
		   global $wpdb;

			$this->updateLastEditDate( $userid );
				   
		   $query = $wpdb->insert( 
				$wpdb->prefix."specdoc_guide_user_items", 
				array( 
					'user_id' => $userid, 
					'item_id' => $item_id,
					'item_status' => $itemStatus
				), 
				array( 
					'%d', 
					'%d',
					'%d' 
				) 
			);
		   
		}
		
		/** select user guide status **/
		function selectGuideStatus($userid,$item_id){
		   global $wpdb;
		   $query = "SELECT item_status From ".$wpdb->prefix."specdoc_guide_user_items Where user_id= '".$userid."' AND item_id='$item_id'";
		   $res = $wpdb->get_col($query);
		   $result = $res[0];		  
		   return $result;
		}
		
		/** select stage total **/
		function selectGroupStatus($userid,$groupId){
		   global $wpdb;		   
		    $query = "SELECT SUM(item_status) as stagetotal FROM ".$wpdb->prefix."specdoc_guide_user_items WHERE user_id = '$userid' AND item_id in(SELECT itemId FROM ".$wpdb->prefix."specdoc_guide_items where group_id = $groupId )";
		   $res = $wpdb->get_col($query);		  
		   $result = $res[0];		  
		   return $result;
		}
		
		/** select grup total items **/
		function groupItemsCount($userid,$groupId){		
		   global $wpdb;		   
		   $query = "SELECT count(itemId)as itemcount FROM ".$wpdb->prefix."specdoc_guide_items where group_id=$groupId";
		   $res = $wpdb->get_col($query);		  
		   $result = $res[0];		  
		   return $result;
		 
		}
		
		// delete logo
		function delLogo($userid) {
			 global $wpdb;			 
			  update_user_meta( $userid,"com_logo","");			 
			 return true;
		}
		

/* ****** Method to inseart Risk Management in db  Written by Royal Tyagi *********** */

	function addRiskManagement( $data, $userid) {
		global $wpdb;

		$this->updateLastEditDate( $userid );
		
		$avalibaleRow = array();
		foreach ( $data['risk'] as $record ) {

			if ($record['id'] == '') {

				$query = "INSERT INTO " . $wpdb->prefix . "specdoc_riskManagement SET user_id = '" . $userid .  "', riskName = '" . $record['riskName'] .  "', LikelihoodOfOccurrence = '" . $record['LikelihoodOfOccurrence'] .  "', riskType = '" . $record['riskType'] .  "', riskDescription = '" . $record['riskDescription'] .  "' ";
				$wpdb->query($query);

				$avalibaleRow[] = $wpdb->insert_id;

			} else {

				$query = "UPDATE " . $wpdb->prefix . "specdoc_riskManagement SET user_id = '" . $userid .  "', riskName = '" . $record['riskName'] .  "', LikelihoodOfOccurrence = '" . $record['LikelihoodOfOccurrence'] .  "', riskType = '" . $record['riskType'] .  "', riskDescription = '" . $record['riskDescription'] .  "' WHERE id = '" . $record['id'] . "' ";
				$wpdb->query($query);

				$avalibaleRow[] = $record['id'];
			}

		}
		
		$finalRow = implode(",", $avalibaleRow);
		$query = $wpdb->query("DELETE FROM " . $wpdb->prefix . "specdoc_riskManagement WHERE user_id= '".$userid."' AND id NOT IN($finalRow)");


		$query = "SELECT section_status FROM wp_specdoc_userstatusinfo WHERE userid='$userid' AND section_name='riskManagement'";
		$riskStatus = $wpdb->get_col($query);

		if (empty($riskStatus)) {
			$updateqry = "INSERT INTO ".$wpdb->prefix."specdoc_userstatusinfo SET userid='$userid', section_status ='1', section_name = 'riskManagement' ";
		} else {
			$updateqry = "UPDATE ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$userid' AND section_name = 'riskManagement' ";
		}

		$wpdb->query($updateqry);

		return true;
	}

	function getAllRiskManagementBlock( $userid )  {
		global $wpdb;
		 
		$query = "SELECT * FROM ". $wpdb->prefix ."specdoc_riskManagement Where user_id= '".$userid."' ORDER BY id ASC ";
		$result = $wpdb->get_results($query,ARRAY_A);

		return $result;

	}

	function addRiskManagementMoreInfo( $data, $userid ) {
		global $wpdb;

		$this->updateLastEditDate( $userid );
		
		foreach ( $data['riskManagement'] as $record )  {

			$query = "UPDATE " . $wpdb->prefix . "specdoc_riskManagement SET user_id = '" . $userid .  "', impact = '" . $record['impact'] .  "', owner = '" . $record['owner'] .  "', monitoring_period = '" . $record['monitoring_period'] .  "', preventive_actions = '" . $record['preventive_actions'] .  "', mitigations_actions = '" . $record['mitigations_actions'] .  "' WHERE id = '" . $record['id'] . "' ";
			$wpdb->query($query);

		}

		$query = "SELECT section_status FROM wp_specdoc_userstatusinfo WHERE userid='$userid' AND section_name='riskManagementMoreInfo'";
		$riskStatus = $wpdb->get_col($query);

		if (empty($riskStatus)) {
			$updateqry = "INSERT INTO ".$wpdb->prefix."specdoc_userstatusinfo SET userid='$userid', section_status ='1', section_name = 'riskManagementMoreInfo' ";
		} else {
			$updateqry = "UPDATE ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$userid' AND section_name = 'riskManagementMoreInfo' ";
		}

		$wpdb->query($updateqry);

		return true;

	}


/* **************
*	Function to store the record of downloaded doc in database, these info will use in admin side to chek the reports under 'user & payment' page
*   Written by Royal Tyagi at 28-Jan-15.
*
************* */

	function updateLastEditDate( $userid ) {

		$dt = new DateTime();
		$now =  $dt->format('Y-m-d H:i:s');
		update_user_meta( $userid, '_lastEditDate', $now );
	}


	function getAboutprojectStatus( $userid ) {

		global $wpdb;

		$is_risk_management = get_option("risk_management");
		if ($is_risk_management == 1 ) {

			$result = $wpdb->get_col("SELECT count(*) FROM " . $wpdb->prefix . "save_status_at_stage2 WHERE userId = '" . $userid . "' AND form_name IN ('Project motivation, background and budget', 'Team members', 'Stakeholders', 'keysProjectDate', 'riskManagement') AND status != 'Complete' ");
			
		} else {

			$result = $wpdb->get_col("SELECT count(*) FROM " . $wpdb->prefix . "save_status_at_stage2 WHERE userId = '" . $userid . "' AND form_name IN ('Project motivation, background and budget', 'Team members', 'Stakeholders', 'keysProjectDate') AND status != 'Complete' ");
		}

		return $result[0];
	}


	function getAboutprojectStatusFromuserstatusinfo( $userid ) {

		global $wpdb;
		$is_risk_management = get_option("risk_management");
		if ($is_risk_management == 1 ) {

			$result = $wpdb->get_col("SELECT count(*) FROM " . $wpdb->prefix . "specdoc_userstatusinfo WHERE userid = '" . $userid . "' AND section_name IN ('projectquesinfo', 'teaminialinfo', 'stakeholderinfo', 'keysProjectDate', 'riskManagement') AND section_status != 2 ");
		
		} else {

			$result = $wpdb->get_col("SELECT count(*) FROM " . $wpdb->prefix . "specdoc_userstatusinfo WHERE userid = '" . $userid . "' AND section_name IN ('projectquesinfo', 'teaminialinfo', 'stakeholderinfo', 'keysProjectDate') AND section_status != 2 ");
		}
		
		return $result[0];

	}


	function getMoreInfoStatus( $userid ) {

		global $wpdb;

		$is_risk_management = get_option("risk_management");
		if ($is_risk_management == 1 ) {

			$result = $wpdb->get_col("SELECT count(*) FROM " . $wpdb->prefix . "save_status_at_stage2 WHERE userId = '" . $userid . "' AND form_name IN ('Selection criteria','Team responsibilities and effort', 'Non-financial benefits', 'Milestones', 'riskManagementMoreInfo') AND status != 'Complete' ");
			
		} else {

			$result = $wpdb->get_col("SELECT count(*) FROM " . $wpdb->prefix . "save_status_at_stage2 WHERE userId = '" . $userid . "' AND form_name IN ('Selection criteria','Team responsibilities and effort', 'Non-financial benefits', 'Milestones') AND status != 'Complete' ");
		}

		return $result[0];
	}	

}// end of class
?>
