<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
Class getdocx{	
	
 public function createuserdocx($template_filename,$getfilename){
	
	$pathurl =  plugin_dir_url(__FILE__); 
    $parentpath = dirname(plugin_dir_path( __FILE__ ));
	$dirpathurl =  dirname(plugin_dir_url(__FILE__)); 
	$dirpath = plugin_dir_path( __FILE__ );
	$templatepath = $parentpath.'/templates';
	
	
	global $wp,$wpdb;

	$objUser = new clsSkopes();
	$userid = get_current_user_id();
	$resarray = $this->getusermetavalues($userid);
	echo $filename = $this->createdoc($parentpath,$resarray,$template_filename,$userid);
	
	return $filename ;
}

// getting user meta values
   function getusermetavalues($userid){
		
		global $wp,$wpdb; 
		$quy = "SELECT * FROM wp_usermeta WHERE user_id ='$userid'"; 
		$myrows = $wpdb->get_results($quy);

		foreach($myrows as $key=>$value){
 
		// get project name
		  if($value->meta_key == 'projectname'){
			  $projectName = trim($value->meta_value);
		  } 
	  
		  if($value->meta_key == 'project_objectives'){
			  $projectObjectives = trim($value->meta_value);
		  } 

		  if($value->meta_key == 'companyname'){
			  $companyName = trim($value->meta_value);
		  } 
	  
		 // get db_software
		  if($value->meta_key == 'db_software'){
			  $software = trim($value->meta_value);
		  }  
		  
		  if($value->meta_key == 'company_city'){
			  $city = trim($value->meta_value);
		  }
		  
		   if($value->meta_key == 'company_state'){
			  $state = trim($value->meta_value);
		  }
		  
		  if($value->meta_key == 'company_zip'){
			  $zip = trim($value->meta_value);
		  }
		  
		   if($value->meta_key == 'country'){
			  $country = trim($value->meta_value);
		  }

		  if($value->meta_key == 'organisation'){
			  $organisation = trim($value->meta_value); 
		  } 
		  
		  if($value->meta_key == 'start_date'){
			  $projectStartDate = trim($value->meta_value); 
		  } 
		  
		  if($value->meta_key == 'end_date'){
			  $projectEndDate = trim($value->meta_value); 
		  } 
		  
		  if($value->meta_key == 'supplier_engaged'){
			  $supplierEngaged = trim($value->meta_value); 
		  }
		  
		  //Milestone two: Scoping and planning complete
		  if($value->meta_key == 'spc'){
			  $spc = trim($value->meta_value); 
		  }
		  
		  //Milestone three: Interface design complete
		  if($value->meta_key == 'idc'){
			  $idc = trim($value->meta_value); 
		  }
		  
		  //Development complete
		  if($value->meta_key == 'dc'){
			  $dc = trim($value->meta_value); 
		  }
		  
		  //Milestone five: User testing complete::
		  if($value->meta_key == 'utc'){
			  $utc = trim($value->meta_value); 
		  }
		  
		  //Milestone six: training complete and system live
		  if($value->meta_key == 'tcsl'){
			  $tcsl = trim($value->meta_value); 
		  }
		  
		  //project_budget
		  if($value->meta_key == 'project_budget'){
			  $projectBudget = trim($value->meta_value); 
		  }
		  
		  if($value->meta_key == 'authors'){
			  $author = $value->meta_value; 
		  }
		  
		  if($value->meta_key == 'company_address'){
			  $streetAddress = trim($value->meta_value); 
		  }
		  
		  if($value->meta_key == 'org_bg'){

			  $org_bg = $value->meta_value; 

		  }
		  
		  if($value->meta_key == 'Sh_list'){
			  $stakeholderList = trim($value->meta_value); 
		  }
		  
		  if($value->meta_key == 'team_effort'){
			  $team_effort = trim($value->meta_value); 
		  }
		
			// team member effort
		  if($value->meta_key == 'tme'){
			  $team_member_effort = trim($value->meta_value); 
		  }
		  
		  if($value->meta_key == 'team_role'){
			  $team_role = trim($value->meta_value); 
		  }
			
			
		  if($value->meta_key == 'tool_data'){
			  $tool_data = $value->meta_value; 
		  }
		  
		} 
	
		$cityState =  $city.",".$state.",".$zip;
		$startDate = date_create(date('Y-m-d', strtotime($projectStartDate))); 
		$endDate = date_create(date('Y-m-d', strtotime($projectEndDate)));
		$interval = date_diff($startDate, $endDate);
		$numberofdays = $interval->format('%a');
		$publishDate = date('d-m-Y');

		$arr = array('projectName'=>$projectName) ;   
		return $arr;  
		
	}

/**
** get functional area headings
**/	
  function getCatHeadings($catids){
	global $wp,$wpdb;
    
	foreach($catids as $key1=>$val1){
		$qry = "select name from wp_specdoc_categories where category_id=$val";
		$cat_info = $wpdb->get_row($qry,ARRAY_A );
		 
		// individual columns in the row 
		//$text.= $docx->addParagraph(array(array('text' => $cat_info['name'], 'type' => 'link', 'URL' => '#'.$cat_info["name"], 'color' => '000000')), array('color' => '000000'));
		$text.= "<br>".$cat_info["name"];
	}
	return $text;
  }

/** 
 ** functional area headings with text 
**/
	function getCatHeadingsAndText($catids){
	 global $wp,$wpdb;
     foreach($catIds as $key=>$val){
		
		$qry1 = "select name,text from wp_specdoc_categories where category_id=$val1";
		$cat_info1 = $wpdb->get_row($qry1,ARRAY_A );
		// add line break
		//$fntext.= $docx->addBreak('line');
		/* $fntext.= $cat_info1['name'];	
		$fntext.= $docx->addBreak(array('type' => 'line'));
		$fntext.= trim($cat_info1['text']); */
		//$fntext.= $docx->addBreak('line'); 
		$fntext.= "<b><p style=\"color: #4f81bd\">".$cat_info1['name']."</b>";	
		$fntext.= "<br><br>";
		$fntext.= trim($cat_info1['text']);
		$fntext.= "<br><br>";
	} 
	return $fntext;
	
   }
   
   function createdoc($parentpath,$arr,$template_filename,$userid){
     //echo 'path :'.$parentpath,$arr;
		require_once dirname($parentpath).'/classes/CreateDocx.inc';
		$docx = new CreateDocx(); 
		$projectName = $arr['projectName'];
		$docx->addTemplateVariable('projectname', "$projectName");
		/* $docx->addTemplateVariable('companylogo', "companylogo");
		$docx->addTemplateVariable('companyname', "$companyName");
		$docx->addTemplateVariable('org_name', "$companyName");
		$docx->addTemplateVariable('org_background', $org_bg);
		$docx->addTemplateVariable('streetaddress', "$streetAddress");
		$docx->addTemplateVariable('city_state_zip', "$cityState");  

		$docx->addTemplateVariable('authors', "$author");
		$docx->addTemplateVariable('stakeholderList', "$stakeholderList");
		$docx->addTemplateVariable('keyobjectives', "$projectObjectives");
		$docx->addTemplateVariable('projectbudget', "$projectBudget");
		$docx->addTemplateVariable('supplier_engaged',"$supplierEngaged");
		$docx->addTemplateVariable('scope_planning',"$spc");
		$docx->addTemplateVariable('interface_design',"$idc");
		$docx->addTemplateVariable('dev_complete',"$dc");
		$docx->addTemplateVariable('user_testing',"$utc");
		$docx->addTemplateVariable('training_complete',"$tcsl");

		$docx->addTemplateVariable('publishdate', "$publishDate");
		$docx->addTemplateVariable('project_startdate', "$projectStartDate");
		$docx->addTemplateVariable('project_enddate', "$projectEndDate");
		$docx->addTemplateVariable('numberofdays', "$numberofdays");

		$docx->addTemplateVariable('functionalAreaCount', "$functionalAreaCount");
		$docx->replaceTemplateVariableByHTML('functional_area','block', "$functionaArea");   
		$docx->replaceTemplateVariableByHTML('functional_text', 'block', "$functionalText"); 
		//$docx->addTemplateVariable('functional_area',"$functionaArea"); 
		//$docx->addTemplateVariable('functional_text',"$functionalText");
		 
		$docx->addTemplateVariable('team_role',"$team_role");
		$docx->addTemplateVariable('team_member_effort',"$team_member_effort"); */
		
	$objUser = new clsSkopes();	
$catres = $objUser->GetToolData($loggedin_id);
if($catres)
{
	$complete_cnt=0;
	$incomplete_cnt=0;
	$not_cnt=0;
	$totallineitemcnt=0;
	$complete_line_cnt=0;
	$roi_line=0;
	$highcnt=0;
	$mediumcnt=0;
	$lowcnt=0;
	for($i=0;$i<sizeof($catres);$i++)
	{
		if($catres[$i][CATEGORY_STATUS]==0){
			$catIds[] = $catres[$i][CATEGORY_ID];
			$incomplete_cnt++;
		} 
		else if($catres[$i][CATEGORY_STATUS]==1){
			$catIds[] = $catres[$i][CATEGORY_ID];		 
			$complete_cnt++;
			}
		else if($catres[$i][CATEGORY_STATUS]==2){
			$not_cnt++;
		}

		
	}
		
}


$docx->addTemplate($templatepath.'/'.$template_filename.'.docx');

$text = "";

$functionalAreaCount = count($catIds);

/**
** functional area headings
**/
$text.= $this->getCatHeadings($catIds);
$text.= "<br>";  
$functionaArea = $text;

/** 
 ** functional area headings with text 
**/
$fntext = "";
$fntext.= $this->getCatHeadingsAndText($catIds);
$functionalText = str_replace('&', "", $fntext);

$file = $getfilename.''.$userid.'_html';
//echo $filename = $dirpathurl.'/docx/'.$file;
$filename = $parentpath.'/docx/'.$file;
 return $filename;
   }
}

?>