<?php
	/*
	*   Name: Twisha Patel  Date: 21-8-2013
	*
	*   Save custom category, Delete custom categoty functionality
	*/

	include("../../../../wp-config.php");
	include(RC_TC_BASE_DIR."/frontend/core/class_userinfo.php"); 
	
	$objUser = new clsSkopes();
	$userid = get_current_user_id();
	
	$obj = new userinfo();
	
	/* check user is logged in or not*/
	if(isset($_REQUEST["action"]))
	{
		if($_REQUEST["action"]=="CheckStatus")
		{
			if($_REQUEST["page"]=="profile")
			{
				if(!is_user_logged_in())
				{
					echo "Logout";
					exit;
				}
			}
			else if($_REQUEST["page"]=="tool")
			{
				/*if(!is_user_logged_in() || $_SESSION["tool_data"]=="" || !isset($_SESSION["tool_data"]))
				{
					echo "Logout";
					exit;
				}*/
			}
		}
	}

	if(is_user_logged_in())
	{
		if(isset($_REQUEST["action"]))
		{
			if($_REQUEST["action"]=="SaveCustomCategory")
			{
				if($_REQUEST["catid"]=="undefined")
					$catid="";
				else
					$catid=$_REQUEST["catid"];

				if($_REQUEST["catname"]!="")
					echo $custom_cat_id=$objUser->SaveCustomCategory($_REQUEST["catname"],"name",$userid,$catid);
				if($_REQUEST["catdesc"]!="")
					echo $custom_cat_id=$objUser->SaveCustomCategory($_REQUEST["catdesc"],"text",$userid,$catid);

				 //echo $custom_cat_id;
			}
			else if($_REQUEST["action"]=="DeleteCustomCategory")
			{
				if($_REQUEST["catid"]!="")
				{
					$objUser->DeletCustomCategory($userid,$_REQUEST["catid"]);
				}
			}
			else if($_REQUEST["action"]=="UpdateLineItem" || $_REQUEST["action"]=="SaveLineItemData")
			{
			    //echo "<pre>"; print_r($_REQUEST); echo "</pre>";
				//die;
				if($_REQUEST["catid"]!=""){
					$objUser->UpdateToolData($userid,"lineitem",$_REQUEST);
					
					/* if($res = $objUser->CheckLineItems($userid,$_REQUEST["catid"]))
						{
						 echo $res;
							if($res == "1"){
								$_REQUEST["cat_status"] = 1;							
							}else if($res == "2"){	
								$_REQUEST["cat_status"] = 2;
							}else if($res=="4"){
								$_REQUEST["cat_status"] = 0;
								$res = 0;
							}else if($res == "5"){
								$_REQUEST["cat_status"] = 0;
							}					
							else if($res == "6"){		
								$_REQUEST["cat_status"] = 1;
							}
							else {
								$_REQUEST["cat_status"] = 0;								
							}
							
							$objUser->UpdateToolData($userid,"category",$_REQUEST);						
							echo $res;
						} */
						
				}

			}
			
			else if($_REQUEST["action"]=="IncludeCategoryCustom")
			{
				if($_REQUEST["catid"]!="")
				{
					if($_REQUEST["act"]=="LineItemStatusCustom")
					{  
						if($res = $objUser->CheckLineItems($userid,$_REQUEST["catid"]))
						{ 
							$_REQUEST["cat_status"] = 3;
							
							 $objUser->UpdateToolData($userid,"category",$_REQUEST);						
							echo 3;
						}  
					}
					else{ 
						/* $getLineItems = $objUser->GetLineItemFromToolData($userid,$_REQUEST["catid"]);
						
						
						foreach($getLineItems as $key=>$value){
						   $data["lineitem_id"] = $value[LINEITEM_ID];//line item id
						   if($value[LINEITEM_STATUS] == 1) {//line item id
						    $data["lineitem_status"] = 0 ;
							}
						}		 */				
						
						$objUser->UpdateToolData($userid,"category",$_REQUEST);
						//print_r($_SESSION["tool_data"]);
						
					}
					 update_user_meta($userid,"tool_data",$_SESSION["tool_data"]);
				}
			}
			
			else if($_REQUEST["action"]=="IncludeCategory")
			{
				if($_REQUEST["catid"]!="")
				{
					if($_REQUEST["act"]=="LineItemStatus")
					{  
						if($res = $objUser->CheckLineItems($userid,$_REQUEST["catid"]))
						{ 
							if($res == "1"){
								$_REQUEST["cat_status"] = 1;							
							}else if($res == "2"){	
								$_REQUEST["cat_status"] = 2;
							}else if($res=="4"){
								$_REQUEST["cat_status"] = 3;
								$res = 0;
							}else if($res == "5"){
								$_REQUEST["cat_status"] = 0;
							}					
							else if($res == "6"){		
								$_REQUEST["cat_status"] = 1;
							}
							else {
								$_REQUEST["cat_status"] = 0;								
							}
							
							 $objUser->UpdateToolData($userid,"category",$_REQUEST);						
							echo $res;
						}  
					}
					else{ 
						/* $getLineItems = $objUser->GetLineItemFromToolData($userid,$_REQUEST["catid"]);
						
						
						foreach($getLineItems as $key=>$value){
						   $data["lineitem_id"] = $value[LINEITEM_ID];//line item id
						   if($value[LINEITEM_STATUS] == 1) {//line item id
						    $data["lineitem_status"] = 0 ;
							}
						}		 */				
						
						$objUser->UpdateToolData($userid,"category",$_REQUEST);
						//print_r($_SESSION["tool_data"]);
						
					}
					 update_user_meta($userid,"tool_data",$_SESSION["tool_data"]);
				}
			}
			else if($_REQUEST["action"]=="SaveCategoryNote")
			{
				if($_REQUEST["catid"]!="")
				{
					//$objUser->UpdateToolData($userid,"category",$_REQUEST);
					$objUser->UpdateNotesForToolData($userid, "category", $_REQUEST);
				}
			}
			else if($_REQUEST["action"]=="SaveCustomLineItem")
			{
				if($_REQUEST["catid"]!="")
				{
					echo $objUser->SaveCustomLineItem($_REQUEST["name"],$userid,$_REQUEST["catid"],$_REQUEST["lineid"]);

				}
			}
			else if($_REQUEST["action"]=="DeleteLineItemData")
			{
				if($_REQUEST["catid"]!="" && $_REQUEST["lineid"]!="")
					$objUser->DeleteCustomLineItem($userid,$_REQUEST["catid"],$_REQUEST["lineid"]);
			}
			else if($_REQUEST["action"]=="SaveToolData")
			{
				if($_REQUEST["catid"]!="")
				{
					if($data=$objUser->UpdateCatStatus($userid,$_REQUEST["catid"]))
					{
						if($data=="4")
							$data=0;

						echo $data;
					}
				}
				$objUser->SaveToolData($userid);
			}
			else if($_REQUEST["action"]=="SaveToolDataCustom")
			{
				if($_REQUEST["catid"]!="")
				{
					if($data=$objUser->UpdateCatStatusCustom($userid,$_REQUEST["catid"]))
					{
						if($data=="4")
							$data=0;

						echo $data;
					}
				}
				$objUser->SaveToolData($userid);
			}
			else if($_REQUEST["action"]=="UpdateStatus")
			{
				$objUser->UpdateStatus($userid);
			}
			else if($_REQUEST["action"]=="checkCategoryStatus"){
			    echo $objUser->checkFaStatus($userid);
			}
			else if($_REQUEST["action"]=="finish")
			{
				//$objUser->SaveToolData($userid);
				$status=$objUser->CheckStatus($userid);
				$objUser->SaveToolData($userid);
				if($status=="complete")
				{
					update_user_meta($userid,'finish_dt_time',date('Y-m-d H:i:s'));
					update_user_meta($userid,'project_status',1);
				}
				echo $status;
			}
			else if($_REQUEST["action"]=="SubmitFeedback")
			{
				$message = "";

				$sub = "Feedback on ".get_bloginfo('url');

				$userinfo = get_userdata( $userid );

				$message = "<div style='font:Arial;color: #080e1a;'><b>Dear admin,</b><br><br>";
				$message .= "You have got feedback on ".get_bloginfo('url')." from <b>".$userinfo->user_email."</b><br>";
				$message .= "<b>Feedback :</b> ".$_REQUEST["feedback"]."<br><br>";

				$message .= "<b>Thanks & Regards,<br>";
				$message .= get_bloginfo('name')."</b></div>";

				$adminemail=$objUser->getDefaultData('Administrator_Email');
				$adminemail=unserialize($adminemail->text);

				if($adminemail=="")
					$adminemail=get_bloginfo('admin_email');
				$headers = "From:  ".get_bloginfo('name')."<".$adminemail.">"."\r\n";
				$headers .= "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
				$ans=wp_mail($adminemail,$sub,$message,$headers);
				if($ans)
				{
					update_user_meta($userid,"feedback","1");
					echo "Send";
				}
				exit;

			}
			elseif($_REQUEST["action"]=="shstatus"){
			   echo $status=$obj->selectStakeholderStatus($userid);
			}
			
			elseif($_REQUEST["action"]=="cal_eoi"){
			 $hourlyRate = $_REQUEST["hr_rate"];
			 $how_often_doit = ($_REQUEST["hodi"] == '' ? 0 : $_REQUEST["hodi"]);
			 $cost = ($_REQUEST["cost"] == '' ? 0 : $_REQUEST["cost"]);
			 $time = ($_REQUEST["time"] == '' ? 0 : $_REQUEST["time"]);			 
			   echo $status=$objUser->ROICalculation($hourlyRate,$time,$cost,$how_often_doit);
			}
			elseif($_REQUEST["action"]=="deletelogo"){
			   echo $status=$obj->delLogo($userid);
			}
			
		}
	}
?>
