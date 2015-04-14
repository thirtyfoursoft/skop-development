<?php session_save_path(dirname($_SERVER['DOCUMENT_ROOT']).'/public_html/tmp');session_start();
	class clsSkopes {
		/* Insert user in wp_users */
		function SaveUser($UserInfo) {
			global $wpdb;
			$email = $UserInfo["email"];
			$UserInfo["user_activation_key"] = $this->Check_Activation_Key(rand(1111111111,9999999999));

			$userid = wp_create_user($email,$UserInfo["password"],$email);

			if(is_int($userid)) {
				update_user_meta($userid,"activation_key",$UserInfo["user_activation_key"]);
				update_user_meta($userid,"active_status",0);
				update_user_meta($userid,"first_name",$UserInfo["firstname"]);
				update_user_meta($userid,"last_name",$UserInfo["lastname"]);
				update_user_meta($userid,"companyname",$UserInfo["companyname"]);
                update_user_meta($userid,"userprofile",1);

				// Condition for mannage the vendor registrations
				if ( isset($UserInfo['invitaionCode']) && $UserInfo['invitaionCode'] != '' ) {
					$userdata = array();
					$userdata['ID'] = $userid;

					if ($UserInfo['userRole'] == 'vendor') {
						$userdata['role'] = 'vendor';
					}

					wp_update_user($userdata);
				}
                
				return $userid;
			} else {
				return $userid->get_error_message();
			}
		}
		
		function getStageOfUser($objUser, $loggedin_id) {
		        $totalCompanyQues  = $objUser->cquestioncount();
				//$userCompanyQues = $objUser->usercquestioncount($loggedin_id);
				$userCompanyQues   = $objUser->usercquestioncount_custom($loggedin_id);
				$totalProjectQues  = $objUser->projectpagecount();
				$userProjectQues   = $objUser->projectpageusercount($loggedin_id);
				$userProjectMore   = $objUser->projectmoreCount($loggedin_id);
				$userFaDisplay     = $this->checkFunaStatus($loggedin_id);
				$userFaCount       = $this->checkFunaStatuscount($loggedin_id);  
				$result1           = $objUser->getuserstage($totalCompanyQues,$userCompanyQues,$totalProjectQues,$userProjectQues,$userFaCount);
				
				$stage = ($result1==3 ? 'two': 'one');
				return $stage;
		}

		/* Check activation code is already exists or not */
		function Check_Activation_Key($key) {
			global $wpdb;
			$res=$wpdb->get_row("select * from ".$wpdb->prefix."usermeta where meta_key='activation_key' and meta_value='".$key."'");
			if($res==0)
				return $key;
			else
				return $this->Check_Activation_Key(rand(1111111111,9999999999));
		}
		
		function getOrgStatus($userID) {
		     global $wpdb;
		     $res = $wpdb->get_results("select * from ".$wpdb->prefix."organization_filled_status where user_id = $userID");
			 
			 if( is_array($res) and !empty($res) ) {
			    $status = 'have_record';
			 } else {
			    $status = 'no_record';
			 } 
			 return $status;
		}
		
		function getOrgStatusValue($userID) {
		     global $wpdb;
		     $res = $wpdb->get_results("select * from ".$wpdb->prefix."organization_filled_status where user_id = $userID");
			 
			 if( is_array($res) and !empty($res) ) {
			    $status = $res[0]->filled_sttaus;
			 } else {
			    $status = '';
			 } 
			 return $status;
		}
		
		function setOrgStatus($userID, $status, $action) {
		    global $wpdb;
		      if($action == 'insert') {
			       $wpdb->query("INSERT INTO ".$wpdb->prefix."organization_filled_status (`user_id`, `filled_sttaus`) 
				                 VALUES ( ".$userID.", '".$status."')");
					return "INSERT";			 
			  } else {
			       $wpdb->query("UPDATE ".$wpdb->prefix."organization_filled_status SET `filled_sttaus` = '".$status."'
								 WHERE `user_id` = ".$userID." ");
					return "UPDATE";			 
			  }
		}

		/* Activate User */
		function ActivateUser($key) {
			global $wpdb;
			$res = $wpdb->get_row("select * from ".$wpdb->prefix."usermeta where meta_key='activation_key' and meta_value='".$key."'");
			if($res>0) { 
				$userid=$res->user_id;
				$wpdb->query("update ".$wpdb->prefix."usermeta set meta_value='1' where meta_key='active_status' and user_id='".$userid."'");
				/* $wpdb->query("update ".$wpdb->prefix."usermeta set meta_value='1' where meta_key='userprofile' and user_id='".$userid."'"); */
				
				/** insert the status of each section in the database**/
				$wpdb->query("INSERT INTO ".$wpdb->prefix."specdoc_userstatusinfo (`userid`, `section_name`, `section_status`) VALUES ( '".$userid."', 'projectquesinfo', 0),( '".$userid."', 'teaminialinfo', 0),( '".$userid."', 'milestonestatus', 0),
				('".$userid."', 'selection_creteria_info', 0),('".$userid."', 'membermoreinfo', 0),('".$userid."', 'stakeholderinfo', 0),('".$userid."', 'benefitinfo', 0),('".$userid."', 'user_stage', 1);");
				return true;
			}
			else
				return false;
		}

		/* Check user login */
		function CheckUserLogin($email,$pwd,$remember)
		{
			 global $wpdb;
			 $creds = array();
			$creds['user_login'] = $email;
			$creds['user_password'] = $pwd;
			$creds['remember'] = $remember;
			  // echo '<pre>';
			  // print_r($creds);
			$user = wp_signon( $creds, false );
			//echo '<pre>';
			 //  print_r($user);
			//die;
			if ( is_wp_error($user) )
			   return false;
			 else
			 {
				 if($remember==1)
					wp_set_auth_cookie($user->ID);
				return true;
			 }
		}

		/* get value from wp_specdoc_default according to name */
		function getDefaultData($name) {
			global $wpdb;
			$sSQL="select * from ".$wpdb->prefix."specdoc_defaults where name='".$name."'";
			return $wpdb->get_row($sSQL);
		}

		/* Save user profile data in usermeta */
		function SaveUserProfile($profileinfo,$userid)
		{
		    if(get_user_meta($loggedin_id,"userprofile",true)=="0")
			{
			 update_user_meta( $userid, 'userprofile', 1 );
			}
			 /* Update user password. */
			if ( !empty($profileinfo['pass1'] ) && !empty( $profileinfo['pass2'] ) ) {
				if ( $profileinfo['pass1'] == $profileinfo['pass2'] )
				
				{
				        wp_update_user( array( 'ID' => $userid, 'user_pass' =>  $profileinfo['pass1'] ) );
				}
				else {
				     $error[] = __('The passwords you entered do not match. Your password was not updated.', 'profile');
				}	 
			}
			
			 /* Update user information. */
			  if ( !empty( $profileinfo['email'] ) ){
			  
				if (!is_email(esc_attr( $profileinfo['email'] ))){					
					$error[] = __('The Email you entered is not valid. please try again.', 'profile');
				}
				else if(email_exists(esc_attr( $_POST['email'] )) == ''){
					wp_update_user( array ('ID' => $userid, 'user_email' => esc_attr( $profileinfo['email'] )));
				}
				elseif(email_exists(esc_attr( $profileinfo['email'] )) != $userid){
					$error[] = __('This email is already used by another user. try a different one.', 'profile');					 
				}
				else{
				
					wp_update_user( array ('ID' => $userid, 'user_email' => esc_attr( $profileinfo['email'] )));
				}
			}
			
			if ( !empty( $profileinfo['first_name'] ) ){
			 
				update_user_meta( $userid, 'first_name', esc_attr( $profileinfo['first_name'] ) );
			}
			
			if ( !empty( $profileinfo['last_name'] ) ){
			 
				update_user_meta( $userid, 'last_name', esc_attr( $profileinfo['last_name'] ) );
			} 
			
			 if ( !empty( $profileinfo['display_name'] ) )
				wp_update_user(array('ID' => $userid, 'display_name' => esc_attr( $profileinfo['display_name'] ))); 
				
			 if ( !empty( $profileinfo['description'] ) )
				update_user_meta( $userid, 'description', esc_attr( $_POST['description'] ) );
				
			 if ( !empty( $profileinfo['url'] ) )
				wp_update_user( array ('ID' => $userid, 'user_url' => esc_attr( $profileinfo['url'] )));
			
				$this->updateLastEditDate( $userid );
				
			  return true;
		}

		/* check email is exists in database or not */
		function CheckEmail($email) {
			global $wpdb;
			if(email_exists($email))
				return  true;
			else
				return false;
		}

		/* get user detail from email */
		function getUserDetail($email){
			global $wpdb;

			$sSQL="select * from ".$wpdb->prefix."users where user_email='".$email."'";
			$res=$wpdb->get_row($sSQL);
			if($res>0)
				return $res;
			else
				return false;
		}

		/* Generate new password and update to wp_users */
		function GenerateNewPassword($userid) {
			global $wpdb;
			$pwd=rand(1111111111,9999999999);
			$sSQL="update ".$wpdb->prefix."users set user_pass='".md5($pwd)."' where ID='".$userid."'";

			$wpdb->query($sSQL);
			return $pwd;
		}

		/* get category data by id */
		function GetCategoryDataById($catid) {
			global $wpdb;

			$sSQL="select * from ".$wpdb->prefix."specdoc_categories where category_id='".$catid."'";
			$res=$wpdb->get_row($sSQL);
			if($res>0)
				return $res;
			else
				return false;
		}

		/* get category name from category id */
		function GetCategoryName($catid) {
			global $wpdb;

			$sSQL="select name from ".$wpdb->prefix."specdoc_categories where category_id='".$catid."'";
			$res=$wpdb->get_row($sSQL);
			if($res>0)
				return $res->name;
			else
				return false;

		}

		/* get category data acoording to field name from category id */
		function GetCategoryData($catid,$field) {
			global $wpdb;

			$sSQL="select ".$field." as result from ".$wpdb->prefix."specdoc_categories where category_id='".$catid."'";
			$res=$wpdb->get_row($sSQL);
			if($res>0)
				return $res->result;
			else
				return false;
		}

		/* get line item name from line item id*/
		function GetLineItemName($lineid) {
			global $wpdb;

			$sSQL="SELECT name from ".$wpdb->prefix."specdoc_line_items where line_item_id='".$lineid."'";
			$res=$wpdb->get_row($sSQL);
			if($res>0)
				return $res->name;
			else
				return false;
		}

		/* get lineitem data acoording to field name from line id */
		function GetLineItemDataFromLineId($lineid,$field) {
			global $wpdb;

			$sSQL="select ".$field." as result from ".$wpdb->prefix."specdoc_line_items where line_item_id='".$lineid."'";
			$res=$wpdb->get_row($sSQL);
			if($res>0)
				return $res->result;
			else
				return false;
		}

		/* save cutom category data to table and tool data */
		function SaveCustomCategory($val,$type,$userid,$catid="") {
			global $wpdb;
			if(!$catid)
			{
				if($type=="name")
					$wpdb->query("insert into ".$wpdb->prefix."specdoc_categories (name,foruser,enabled) values('".$val."','".$userid."',1)");
				else if($type=="text")
					$wpdb->query("insert into ".$wpdb->prefix."specdoc_categories (text,foruser,enabled) values('".$val."','".$userid."',1)");

				/* add custom category to the tooldata in user meta */
				$data=array();
				$data[CATEGORY_ID]=$wpdb->insert_id;
				$data[CATEGORY_IS_CUSTOM]=1;
				$data[CATEGORY_NOTE]="";
				$data[CATEGORY_STATUS]=0;
				$data[CATEGORY_START_TIME]="";
				$data[CATEGORY_FINISH_TIME]="";

				$tooldata=$_SESSION["tool_data"];
				array_push($tooldata,$data);

				unset($_SESSION["tool_data"]);
				$_SESSION["tool_data"]=$tooldata;


				$catname=$this->GetCategoryName($wpdb->insert_id);

				$this->updateLastEditDate( $userid );

				return $wpdb->insert_id."|".$catname;
			}
			else
			{
				if($type=="name")
					$wpdb->query("update ".$wpdb->prefix."specdoc_categories set name='".$val."' where category_id='".$catid."'");
				else if($type=="text")
					$wpdb->query("update ".$wpdb->prefix."specdoc_categories set text='".$val."' where category_id='".$catid."'");

				$catname=$this->GetCategoryName($catid);
				$this->updateLastEditDate( $userid );
				
				return $catid."|".$catname; 
			}
		}

		/* Delete Custom Category and its related line item */
		function DeletCustomCategory($userid,$catid)
		{
			global $wpdb;
			$tooldata=$_SESSION["tool_data"];
			if(!empty($tooldata))
			{
				for($i=0;$i<sizeof($tooldata);$i++)
				{
					if($catid==$tooldata[$i][CATEGORY_ID])
					{
						$tooldata[$i]="";
						unset($tooldata[$i]);
					}
				}
				unset($_SESSION["tool_data"]);
				$_SESSION["tool_data"]=$this->fix_keys($tooldata);
			}

			if(!in_array($catid,$_SESSION["delete_cat"]))
				$_SESSION["delete_cat"][]=$catid;

			return true;
		}

		/* delete custom line item from table and from tool data */
		function DeleteCustomLineItem($userid,$catid,$lineid)
		{
		   global $wpdb;
		   $tooldata=$_SESSION["tool_data"];
			if(!empty($tooldata))
			{
				for($i=0;$i<sizeof($tooldata);$i++)
				{
					if($catid==$tooldata[$i][CATEGORY_ID])
					{
						for($j=0;$j<sizeof($tooldata[$i][CATEGORY_LINEITEMS]);$j++)
						{
							if($tooldata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_ID]==$lineid)
							{
								unset($tooldata[$i][CATEGORY_LINEITEMS][$j]);
							}
						}
					}
				}
				unset($_SESSION["tool_data"]);
				$_SESSION["tool_data"]=$this->fix_keys($tooldata);

			}
			$wpdb->query("DELETE from ".$wpdb->prefix."specdoc_line_items where line_item_id='".$lineid."'");
			if(!in_array($lineid,$_SESSION["delete_lineitem"]))
				$_SESSION["delete_lineitem"][]=$lineid;
			return true;

		}

		/* save custom line item data and update tool data session */
		function SaveCustomLineItem($val,$userid,$catid,$lineid="")
		{
			global $wpdb;

			$this->updateLastEditDate( $userid );
			if($lineid=="")
			{
				$wpdb->query("insert into ".$wpdb->prefix."specdoc_line_items (category_id,name,foruser,enabled) values(".$catid.",'".$val."','".$userid."',1)");


				$impval=0;
				$impval=$this->getDefaultData('Impotrtance_Low_Value');
				$impval=unserialize($low_val->text);

				$linearr[LINEITEM_ID]=$wpdb->insert_id;
				$linearr[LINEITEM_IS_CUSTOM]=1;
				$linearr[LINEITEM_IMPORTANCE]=$impval;
				$linearr[LINEITEM_NOTE]="";
				$linearr[LINEITEM_IS_AUTOMATED]=1;
				$linearr[LINEITEM_YEAR]="";
				$linearr[LINEITEM_INVESTMENT_REQ]=1;
				$linearr[LINEITEM_COST]="";
				$linearr[LINEITEM_TIME]="";
				$linearr[LINEITEM_STATUS]=0;
				$linearr[LINEITEM_START_TIME]="";
				$linearr[LINEITEM_FINISH_TIME]="";
				$linearr[LINEITEM_IS_BENEFIT]="0";
				$linearr[LINEITEM_BENEFIT_FOR]="";
				$linearr[LINEITEM_BENEFIT_OTHER]="";
				$linearr[LINEITEM_EOI]="";

				$tooldata=$_SESSION["tool_data"];
				for($i=0;$i<sizeof($tooldata);$i++)
				{
					if($tooldata[$i][CATEGORY_ID]==$catid)
					{
						if(!count($tooldata[$i][CATEGORY_LINEITEMS]))
							$tooldata[$i][CATEGORY_LINEITEMS][0]=$linearr;
						else
							$tooldata[$i][CATEGORY_LINEITEMS][count($tooldata[$i][CATEGORY_LINEITEMS])]=$linearr;
					}
				}
				unset($_SESSION["tool_data"]);
				$_SESSION["tool_data"]=$tooldata;
				$lineid=$wpdb->insert_id;
			}
			else
			   $wpdb->query("update ".$wpdb->prefix."specdoc_line_items set name='".$val."' where line_item_id='".$lineid."'");

			 return $lineid;

		}

		/* get line item array by category id */
		function GetLineItemDataByCatId($catid)
		{
			global $wpdb;
			$results=$wpdb->get_results("select * from ".$wpdb->prefix."specdoc_line_items where category_id='".$catid."' and enabled=1");
			return $results;
		}
		
		/* get line item array by category id */
		function GetLineItemDataByCatIdUserId($catid, $userid)
		{
			global $wpdb;
			$results=$wpdb->get_results("select * from ".$wpdb->prefix."specdoc_line_items where category_id='".$catid."' and enabled=1 and foruser = $userid");
			return $results;
		}

		/* get user id from lineitem */
		function GetForUserIdfromLineItem($lineid)
		{
			global $wpdb;
			$linedata=$wpdb->get_row("select if(foruser IS NULL,0,foruser) as foruserid from ".$wpdb->prefix."specdoc_line_items where line_item_id='".$lineid."' and enabled=1 order by line_item_id");
			return $linedata->foruserid;
		}

		/* save data to session on initial stage where no tooldata is there */
		function SaveDefaultToolData($userid)
		{
			global $wpdb;
			$this->updateLastEditDate( $userid );
			
			$finalarr=false;
			unset($_SESSION["tool_data"]);
			$cond="";
			$catdata=$wpdb->get_results("select * from ".$wpdb->prefix."specdoc_categories where enabled=1 and (foruser IS NULL or foruser='".$userid."') order by category_id");

			foreach($catdata as $cat)
			{
				$data[CATEGORY_ID]=$cat->category_id;
				if(!is_null($cat->foruser) || $cat->foruser!="")
					$data[CATEGORY_IS_CUSTOM]=1;        /* 0-default 1-custom */
				else
					$data[CATEGORY_IS_CUSTOM]=0;        /* 0-default 1-custom */
				$data[CATEGORY_NOTE]="";
				$data[CATEGORY_STATUS]=0;           /* 0-incomplete     1-category complete     2-category not required  */
				$data[CATEGORY_START_TIME]="";
				$data[CATEGORY_FINISH_TIME]="";

				$linearr=array();
				$lineitem=false;
				$linedata=$wpdb->get_results("select * from ".$wpdb->prefix."specdoc_line_items where category_id='".$cat->category_id."' and enabled=1 and (foruser IS NULL or foruser='".$userid."')  order by line_item_id");
				foreach($linedata as $line)
				{
					$linearr[LINEITEM_ID]=$line->line_item_id;
					if($line->foruser!="")
						$linearr[LINEITEM_IS_CUSTOM]=1;       /* 0-default 1-custom */
					else
						$linearr[LINEITEM_IS_CUSTOM]=0;       /* 0-default 1-custom */


					$impval=$this->getDefaultData('Impotrtance_Low_Value');
					$impval=unserialize($low_val->text);
					if($impval=="" || $impval<0)
						$impval=0;

					$linearr[LINEITEM_IMPORTANCE]=$impval;      /* 0-low 1-medium 2-high */
					$linearr[LINEITEM_NOTE]="";
					$linearr[LINEITEM_IS_AUTOMATED]=1;      /* 0-No 1-Yes */
					$linearr[LINEITEM_YEAR]="";
					$linearr[LINEITEM_INVESTMENT_REQ]=1;    /* 0-No 1-Yes */
					$linearr[LINEITEM_COST]="";
					$linearr[LINEITEM_TIME]="";
					$linearr[LINEITEM_STATUS]=0;    /* 0-Not requried       1-incude line item */
					$linearr[LINEITEM_START_TIME]="";
					$linearr[LINEITEM_FINISH_TIME]="";
					$linearr[LINEITEM_IS_BENEFIT]="0";
					$linearr[LINEITEM_BENEFIT_FOR]="";
					$linearr[LINEITEM_BENEFIT_OTHER]="";
					$linearr[LINEITEM_EOI]="";
					$lineitem[]=$linearr;
				}
				$data[CATEGORY_LINEITEMS]=$lineitem;
				$finalarr[]=$data;
			}

			update_user_meta($userid,"tool_data",$finalarr);

		}

		/* get tool and database data and generated tooldata */
		function GetToolData($userid)
		{
			 global $wpdb;
			// if(!isset($_SESSION['tool_data']) && count($_SESSION['tool_data'])<1)
			// {
			
				 $_SESSION['tool_data'] = get_user_meta($userid,'tool_data',true);
			// }
	
			 $temp_catarr=array();
			 $final_arr=array();
			 /* create array for category and lineitem from database */
			 $catres=$wpdb->get_results("select * from ".$wpdb->prefix."specdoc_categories where enabled=1 and (foruser IS NULL or foruser='".$userid."') order by category_id");
			 
			 //echo '<pre>'; print_r($catres); die;
			 
			 foreach($catres as $cat)
			 {			    
				$linedata = $wpdb->get_row("select group_concat(line_item_id) as linitems_ids from ".$wpdb->prefix."specdoc_line_items where category_id='".$cat->category_id."' and enabled=1 and (foruser IS NULL or foruser='".$userid."')  order by line_item_id");				 
				
				$db_lineitemarr[$cat->category_id]=explode(",",$linedata->linitems_ids);
				$temparr=array();
				$temparr=$db_lineitemarr[$cat->category_id]; 

				$ses_lineitems=$this->GetLineItemFromToolData($userid,$cat->category_id); 

				if(!is_array($ses_lineitems))
					$ses_lineitems=array();

				for($i=0;$i<sizeof($temparr);$i++)
				{
					$key=$this->recursive_array_search($temparr[$i],$ses_lineitems);
					if($key!=="a")
					{
						$ses_lineitems[$key][LINEITEM_ID]=$temparr[$i];
						$foruser=$this->GetForUserIdfromLineItem($ses_lineitems[$key][LINEITEM_ID]);
						if($foruser==0)
							$ses_lineitems[$key][LINEITEM_IS_CUSTOM]=0;
						else
							$ses_lineitems[$key][LINEITEM_IS_CUSTOM]=1;

						$temparr[$i]=$ses_lineitems[$key];
					}
					else
					{
						$temp=array();
						$impval=$this->getDefaultData('Impotrtance_Low_Value');
						$impval=unserialize($low_val->text);
						if($impval=="" || $impval<0)
							$impval=0;


						$temp[LINEITEM_ID]=$temparr[$i];

						$foruser=$this->GetForUserIdfromLineItem($temp[LINEITEM_ID]);
						if($foruser==0)
							$temp[LINEITEM_IS_CUSTOM]=0;
						else
							$temp[LINEITEM_IS_CUSTOM]=1;

						$temp[LINEITEM_IMPORTANCE]=$impval;
						$temp[LINEITEM_NOTE]="";
						$temp[LINEITEM_IS_AUTOMATED]=1;
						$temp[LINEITEM_YEAR]="";
						$temp[LINEITEM_INVESTMENT_REQ]=1;
						$temp[LINEITEM_COST]="";
						$temp[LINEITEM_TIME]="";
						$temp[LINEITEM_STATUS]=0;
						$temp[LINEITEM_START_TIME]="";
						$temp[LINEITEM_FINISH_TIME]="";
						$temp[LINEITEM_IS_BENEFIT]="0";
						$temp[LINEITEM_BENEFIT_FOR]="";
						$temp[LINEITEM_BENEFIT_OTHER]="";
						$linearr[LINEITEM_EOI]="";
						$temparr[$i]=$temp;
					}
				}

				$ses_catarr=$this->GetCategoryFromToolData($userid,$cat->category_id);
				$temp_catarr=array();
				$ses_catarr[CATEGORY_LINEITEMS]=$temparr; 
				
				
				
				if($cat->category_id==$ses_catarr[CATEGORY_ID])
				{
					if(!is_null($cat->foruser) || $cat->foruser!="")
						$ses_catarr[CATEGORY_IS_CUSTOM]=1;
					else
						$ses_catarr[CATEGORY_IS_CUSTOM]=0;


					$final_arr[]=$ses_catarr;
				}
				else
				{
					$temp_catarr[CATEGORY_ID]=$cat->category_id;
					if(!is_null($cat->foruser) || $cat->foruser!="")
						$temp_catarr[CATEGORY_IS_CUSTOM]=1;
					else
						$temp_catarr[CATEGORY_IS_CUSTOM]=0;

					$temp_catarr[CATEGORY_NOTE]="";
					//$temp_catarr[CATEGORY_STATUS]= 0;
					$temp_catarr[CATEGORY_START_TIME]="";
					$temp_catarr[CATEGORY_FINISH_TIME]="";
					$temp_catarr[CATEGORY_LINEITEMS]=$this->fix_keys($ses_catarr[CATEGORY_LINEITEMS]);
					 $final_arr[]=$temp_catarr;
				}


			 }  
			 
			 $final=$this->fix_keys($final_arr);
			
			 $_SESSION['tool_data']=$final;
			 return $final;
		}

		/* search key from recursiv array */
		function recursive_array_search($needle,$haystack)
		{
			$k="a";
			foreach($haystack as $key=>$value) {
				 if($needle===$value[0])
				 {
					$k=$key;
					break;
				 }
				}
			return $k;
		}

		/* get data from usermeta and set into session */
		function GetToolData_Old($userid)
		{
			global $wpdb;
			if(!$_SESSION["tool_data"])
				$_SESSION["tool_data"]=$this->fix_keys(get_user_meta($userid,'tool_data',true));


			$tooldata=$_SESSION["tool_data"];

			$lineitemarr=false;
			for($i=0;$i<sizeof($tooldata);$i++)
			{
				$catarr[]=$tooldata[$i][CATEGORY_ID];
				for($j=0;$j<sizeof($tooldata[$i][CATEGORY_LINEITEMS]);$j++)
				{
					$lineitemarr[$tooldata[$i][CATEGORY_ID]][]=$tooldata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_ID];
				}
			}


			foreach ($tooldata as $key => $value)
			{
				/* Check lineitem is in tooldata or not if not add that lineitem to tooldata */
				$linearr=array();
				$lineitem=array();
				$flag=0;
				$catid=$tooldata[$key][CATEGORY_ID];
				if($lineitemarr[$catid])
				{
					$lineittems=implode(",",$lineitemarr[$catid]);
					$linedata = $wpdb->get_results("select * from ".$wpdb->prefix."specdoc_line_items where category_id='".$catid."' and enabled=1 and (foruser IS NULL) and  line_item_id not in(".$lineittems.") order by line_item_id");
				}



				else
					$linedata = $wpdb->get_results("select * from ".$wpdb->prefix."specdoc_line_items where category_id='".$catid."' and enabled=1 and (foruser IS NULL) order by line_item_id");

				foreach($linedata as $line)
				{
					$flag++;

					$linearr[LINEITEM_ID]=$line->line_item_id;
					if($line->foruser!="")
						$linearr[LINEITEM_IS_CUSTOM]=1;
					else
						$linearr[LINEITEM_IS_CUSTOM]=0;

					$impval=$this->getDefaultData('Impotrtance_Low_Value');
					$impval=unserialize($low_val->text);
					if($impval=="" || $impval<0)
						$impval=0;

					$linearr[LINEITEM_IMPORTANCE]=$impval;
					$linearr[LINEITEM_NOTE]="";
					$linearr[LINEITEM_IS_AUTOMATED]=1;
					$linearr[LINEITEM_YEAR]="";
					$linearr[LINEITEM_INVESTMENT_REQ]=1;
					$linearr[LINEITEM_COST]="";
					$linearr[LINEITEM_TIME]="";
					$linearr[LINEITEM_STATUS]=0;
					$linearr[LINEITEM_START_TIME]="";
					$linearr[LINEITEM_FINISH_TIME]="";
					$linearr[LINEITEM_IS_BENEFIT]="0";
					$linearr[LINEITEM_BENEFIT_FOR]="";
					$linearr[LINEITEM_BENEFIT_OTHER]="";
					$linearr[LINEITEM_EOI]="";
					$lineitem[]=$linearr;
				 }

				 if(count($lineitem)>0)
				 {
					 if(count($lineitem) && count($tooldata[$key][CATEGORY_LINEITEMS])>0)
						$tooldata[$key][CATEGORY_LINEITEMS]=array_merge($tooldata[$key][CATEGORY_LINEITEMS],$lineitem);
					 else
						$tooldata[$key][CATEGORY_LINEITEMS]=$lineitem;
				 }


				/* get all id of lineitem according to category and check in tooldata category lineitem if not available in lineitem table then unset that lineitem in tool */
				$lineitem_arr = array();
				$lineitemres = $wpdb->get_row("select group_concat(line_item_id) as lineitems_id from ".$wpdb->prefix."specdoc_line_items  where category_id='".$catid."' and enabled=1 and (foruser is NULL or foruser='".$userid."')");
				$lineitem_arr = explode(",",$lineitemres->lineitems_id);

				for($j=0;$j<sizeof($tooldata[$key][CATEGORY_LINEITEMS]);$j++)
				{
					if(!in_array($tooldata[$key][CATEGORY_LINEITEMS][$j][LINEITEM_ID],$lineitem_arr))
					{
						$flag++;
						unset($tooldata[$key][CATEGORY_LINEITEMS][$j]);
					}
				}
				if(count($tooldata[$key][CATEGORY_LINEITEMS])>0)
					$tooldata[$key][CATEGORY_LINEITEMS]=$this->fix_keys($tooldata[$key][CATEGORY_LINEITEMS]);

				/* get all id from category table and check in tooldata if categoryid not available in category table then remove that category from tool data */
				$cat_arr=array();
				$catres=$wpdb->get_row("select group_concat(category_id) as categories_id from ".$wpdb->prefix."specdoc_categories where enabled=1 and (foruser is NULL or foruser='".$userid."')");
				$cat_arr=explode(",",$catres->categories_id);
				if(!in_array($tooldata[$key][CATEGORY_ID],$cat_arr))
				{
					$flag++;
					unset($tooldata[$key]);
				}
			}
			$_SESSION["tool_data"] = $this->fix_keys($tooldata);


			if($flag>0)
				update_user_meta($userid,'tool_data',$_SESSION["tool_data"]);

			/* Check category is in tooldata or not if not add that catrgory to tooldata */
			if($catarr)
			{
				$finalarr=false;
				$categories=implode(",",$catarr);

				$catdata=$wpdb->get_results("select * from ".$wpdb->prefix."specdoc_categories where enabled=1 and (foruser IS NULL) and category_id not in (".$categories.") order by category_id");
				foreach($catdata as $cat)
				{
					$data[CATEGORY_ID]=$cat->category_id;
					if(!is_null($cat->foruser) || $cat->foruser!="")
						$data[CATEGORY_IS_CUSTOM]=1;
					else
						$data[CATEGORY_IS_CUSTOM]=0;

					$data[CATEGORY_NOTE]="";
					$data[CATEGORY_STATUS]=0;
					$data[CATEGORY_START_TIME]="";
					$data[CATEGORY_FINISH_TIME]="";

					$linearr=array();
					$lineitem=false;
					$linedata=$wpdb->get_results("select * from ".$wpdb->prefix."specdoc_line_items where category_id='".$cat->category_id."' and enabled=1 and (foruser IS NULL or foruser='".$userid."')  order by line_item_id");
					foreach($linedata as $line)
					{
						$linearr[LINEITEM_ID]=$line->line_item_id;
						if($line->foruser!="")
							$linearr[LINEITEM_IS_CUSTOM]=1;
						else
							$linearr[LINEITEM_IS_CUSTOM]=0;


						$impval=$this->getDefaultData('Impotrtance_Low_Value');
						$impval=unserialize($low_val->text);
						if($impval=="" || $impval<0)
							$impval=0;

						$linearr[LINEITEM_IMPORTANCE]=$impval;
						$linearr[LINEITEM_NOTE]="";
						$linearr[LINEITEM_IS_AUTOMATED]=1;
						$linearr[LINEITEM_YEAR]="";
						$linearr[LINEITEM_INVESTMENT_REQ]=1;
						$linearr[LINEITEM_COST]="";
						$linearr[LINEITEM_TIME]="";
						$linearr[LINEITEM_STATUS]=0;
						$linearr[LINEITEM_START_TIME]="";
						$linearr[LINEITEM_FINISH_TIME]="";
						$linearr[LINEITEM_IS_BENEFIT]="0";
						$linearr[LINEITEM_BENEFIT_FOR]="";
						$linearr[LINEITEM_BENEFIT_OTHER]="";
						$linearr[LINEITEM_EOI]="";
						$lineitem[]=$linearr;
					 }
					$data[CATEGORY_LINEITEMS]=$lineitem;
					$finalarr[]=$data;

				}
				if($finalarr)
				{
					$arr=array_merge($_SESSION["tool_data"],$finalarr);
					unset($_SESSION["tool_data"]);
					$_SESSION["tool_data"]=$arr;
					update_user_meta($userid,'tool_data',$arr);
				}
			}

			return $_SESSION["tool_data"];
		}

		/* get line item data by lineitem id */
		function GetLineItemDataById($lineid)
		{
			global $wpdb;
			$results=$wpdb->get_results("select * from ".$wpdb->prefix."specdoc_line_items where line_item_id='".$lineid."' and enabled=1");
			return $results;
		}

		/* save tool data */
		function SaveToolData($userid)
		{
			global $wpdb;

			$this->updateLastEditDate( $userid );
			update_user_meta($userid,"tool_data",$_SESSION["tool_data"]);

			if($_SESSION["delete_cat"])
			{
				foreach($_SESSION["delete_cat"] as $deletid)
				{
					$wpdb->query("delete from ".$wpdb->prefix."specdoc_line_items where category_id='".$deletid."'");
					$wpdb->query("delete from ".$wpdb->prefix."specdoc_categories where category_id='".$deletid."'");
				}
			}
			if($_SESSION["delete_lineitem"])
			{
				foreach($_SESSION["delete_lineitem"] as $deletid)
				{
					$wpdb->query("delete from ".$wpdb->prefix."specdoc_line_items where line_item_id='".$deletid."'");
				}
			}
		}
		
		/* get tool data session Notes */
		function getNotesForToolData($userid, $type, $cat_id)
		{		
			global $wpdb; 
			$cat_note = '';
			$olddata = $_SESSION["tool_data"];  
			for($i=0;$i<sizeof($olddata);$i++)
			{   
				if($cat_id == $olddata[$i][CATEGORY_ID])
				{
					if($type=="category")
					{ 
						$cat_note = $olddata[$i][CATEGORY_NOTE]; 
					} 
				}
			}  
			return $cat_note;
		}
		
		/* update tool data session when any changes made */
		function UpdateNotesForToolData($userid, $type, $data)
		{		
			global $wpdb; 
			$olddata = $_SESSION["tool_data"];  
			for($i=0;$i<sizeof($olddata);$i++)
			{   
				if($data["catid"] == $olddata[$i][CATEGORY_ID])
				{
					if($type=="category")
					{ 
						$status = $data["cat_status"];

						if($data["cat_note"]!=""){
							$olddata[$i][CATEGORY_NOTE]=$data["cat_note"];
						}	 	 
					} 
				}
			} 
			unset($_SESSION["tool_data"]);
			$_SESSION["tool_data"] = $olddata;
			update_user_meta($userid,"tool_data",$olddata);
			return true;
		}

		/* update tool data session when any changes made */
		function UpdateToolData($userid,$type,$data)
		{		
			global $wpdb;			
		   
			$olddata = $_SESSION["tool_data"];  
			for($i=0;$i<sizeof($olddata);$i++)
			{   
				if($data["catid"] == $olddata[$i][CATEGORY_ID])
				{
					if($type=="category")
					{

						$status = $data["cat_status"];

						if($data["cat_note"]!=""){
							$olddata[$i][CATEGORY_NOTE]=$data["cat_note"];
						}	
							
						//if($data["cat_status"]!=""){
							$olddata[$i][CATEGORY_STATUS]=$data["cat_status"];
						//}
						
							if($status == 2){
							  // if category is not required the set all the line items values and status to 0
									for($j=0;$j<sizeof($olddata[$i][CATEGORY_LINEITEMS]);$j++)
										{  
											if($olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_STATUS]==1)
											{
											    $olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_STATUS]= 0;
											}
											if(!empty($olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_YEAR]))
											{
											    $olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_YEAR]= "";
											}
											
											    $olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_IS_AUTOMATED] = 1;
												$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_INVESTMENT_REQ] = "";
												$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_IS_BENEFIT] = "";
												$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_BENEFIT_FOR] = "";
												$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_IMPORTANCE]= 0;
												$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_NOTE]= "";
												$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_YEAR]= "";
												$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_COST]= "";
												$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_TIME]= "";
												$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_EOI]= "";
												
										}
							}
							
					}

					if($type=="lineitem")
					{
					
						for($j=0;$j<sizeof($olddata[$i][CATEGORY_LINEITEMS]);$j++)
						{  
							if($olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_ID]==$data["lineitem_id"])
							{
							
								/* if line item sataus is no then set all other values to default */
								//if($data["lineitem_status"]==2)
								if($data["lineitem_status"]==0)
								{ 
									$impval=$this->getDefaultData('Impotrtance_Low_Value');
									$impval=unserialize($low_val->text);
									if($impval=="" || $impval<0)
										$impval=0;

									$data["lineitem_automated"]=1;
									$data["lineitem_note"]="";
									$data["lineitem_investment"]=0;
									$data["lineitem_importance"]=$impval;
									$data["lineitem_year"]="";
									$data["lineitem_cost"]="";
									$data["lineitem_time"]="";
									$data["lineitem_eoi"]="";
								}

								if($data["lineitem_status"]!="")
									$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_STATUS]=$data["lineitem_status"];
								if($data["lineitem_automated"]!="")
									$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_IS_AUTOMATED]=$data["lineitem_automated"];
								if($data["lineitem_investment"]!="")
									$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_INVESTMENT_REQ]=$data["lineitem_investment"];
								if($data["lineitem_benefit"]!="")
									$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_IS_BENEFIT]=$data["lineitem_benefit"];
								if($data["lineitem_benefit_for"]!="")
									$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_BENEFIT_FOR]=$data["lineitem_benefit_for"];
								if($data["lineitem_importance"]!="")
									$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_IMPORTANCE]=$data["lineitem_importance"];

								$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_BENEFIT_OTHER]=$data["lineitem_benefit_other"];

								$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_NOTE]=$data["lineitem_note"];
								$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_YEAR]=$data["lineitem_year"];
								$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_COST]=$data["lineitem_cost"];
								$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_TIME]=$data["lineitem_time"];
								$olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_EOI]=$data["lineitem_eoi"];

							}
						}
					}
				}
			}
			
			unset($_SESSION["tool_data"]);
			$_SESSION["tool_data"] = $olddata;
			update_user_meta($userid,"tool_data",$olddata);
			return true;
		}

		/* get category array from category id from tool data */
		function GetCategoryFromToolData($userid,$catid)
		{
			$tooldata = $_SESSION["tool_data"];
			for($i=0;$i<sizeof($tooldata);$i++)
			{
				if($catid == $tooldata[$i][CATEGORY_ID])
				{
					return $tooldata[$i];
				}
			}
		}

		/* get line item array from category id */
		function GetLineItemFromToolData($userid,$catid)
		{
		   
			$tooldata=$_SESSION["tool_data"];
			for($i=0;$i<sizeof($tooldata);$i++)
			{
				if($catid==$tooldata[$i][CATEGORY_ID])
				{
					return $tooldata[$i][CATEGORY_LINEITEMS];
				}
			}
		}


				
		function CheckLineItems($userid,$catid)
		{
			$tooldata = $_SESSION["tool_data"];	 
                     //echo '<pre>'; print_r($tooldata); echo '</pre>';			
			for($i=0;$i<sizeof($tooldata);$i++)
			{
				if($catid == $tooldata[$i][CATEGORY_ID])
				{
					if($tooldata[$i][CATEGORY_LINEITEMS])
					{
						$total_lineitems = count($tooldata[$i][CATEGORY_LINEITEMS]); // total number of line items in a category
						$cnt=0;
						$nocnt=0;
						$lnocnt=0;
						for($j=0;$j<sizeof($tooldata[$i][CATEGORY_LINEITEMS]);$j++)
						{
						    // check line item status
							if($tooldata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_STATUS] == 1)
							{
								$cnt++;
							}
							else if($tooldata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_STATUS] == 2)
							{
								$lnocnt++;
							}
							else if($tooldata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_STATUS] == 0)
							{
								$lcnt++;
							}
						}

						if($total_lineitems>0 && $lnocnt==$total_lineitems)
						{
							$retval="2";
						}
						else if($total_lineitems>0 && $cnt==$total_lineitems)
						{
							 //echo "<br>3"; 
							$retval="1";
						}
						else if($total_lineitems>0 && $lcnt==$total_lineitems)
						{
							 //echo "<br>--4"; 
							$retval="4";
						}
						else if($total_lineitems>0 && ($cnt+$lnocnt)==$total_lineitems)
						{
						    	//echo "<br>2"; 
							 $retval="1";
						}
						else if($total_lineitems>0 && ($cnt+$lcnt)==$total_lineitems && $tooldata[$i][CATEGORY_STATUS]!=2)
						{
						    	//echo "<br>5"; 
							 $retval="1";
						}
						else if($total_lineitems>0 && $lcnt>0 && $tooldata[$i][CATEGORY_STATUS]!=2)
						{
							//echo "<br>1";
							$retval= "1";   
						}

						return $retval;
					}
					else
						 return "";
				}
			}
			
		}

		/* check for category complete when user click on finished and show report button */
		function CheckStatus($userid)
		{
			global $wpdb;

			$olddata = $_SESSION["tool_data"];
			$cnt = 0;
			for($i=0;$i<sizeof($olddata);$i++)
			{ 
				if($olddata[$i][CATEGORY_STATUS]==0)
				{
				   $cnt++;
				}
			}

			if($cnt>0)
			{
				if($cnt==sizeof($olddata))
					return "incompleteall";
				else
					return "incomplete";
			}
			else
			{

				return "complete";
			}
		}

		/* check for line items stautus and update category */
		function UpdateStatus($userid)
		{
			global $wpdb;

			$olddata=$_SESSION["tool_data"];
			for($i=0;$i<sizeof($olddata);$i++)
			{
				/*if($olddata[$i][CATEGORY_STATUS]==0)
				{*/
					if($olddata[$i][CATEGORY_LINEITEMS])
					{
						$total_lineitems=count($olddata[$i][CATEGORY_LINEITEMS]);
						$lcnt=0;
						$lnocnt=0;
						$cnt=0;
						for($j=0;$j<sizeof($olddata[$i][CATEGORY_LINEITEMS]);$j++)
						{
							if($olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_STATUS]==1)
							{
								$cnt++;
								//$olddata[$i][CATEGORY_STATUS]=1;
								//break;
							}
							else if($olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_STATUS]==2)
							{
								$lnocnt++;
							}
							else if($olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_STATUS]==0)
							{
								$lcnt++;
							}
						}

						if($total_lineitems>0 && $lnocnt==$total_lineitems)
						{
							/*echo "if1<br>";*/
							$olddata[$i][CATEGORY_STATUS]=2;
						}
						else if($total_lineitems>0 && $cnt==$total_lineitems)
						{
							/*echo "if2<br>";*/
							$olddata[$i][CATEGORY_STATUS]=1;
						}
						else if($total_lineitems>0 && ($cnt+$lnocnt)==$total_lineitems)
						{
							/*echo "if3<br>";*/
							$olddata[$i][CATEGORY_STATUS]=1;
						}
						else if($total_lineitems>0 && $lcnt>0 && $olddata[$i][CATEGORY_STATUS]!=2)
						{
							/*echo "if4<br>";*/
							$olddata[$i][CATEGORY_STATUS]=0;
						}
					}
				/*}*/
			}
			unset($_SESSION["tool_data"]);
			$_SESSION["tool_data"]=$olddata;
			return true;
		}
		
		
		function UpdateCatStatusWithoutlineitems($userid,$catid)
		{
			global $wpdb;

			if($catid!="")
			{
				$olddata = $_SESSION["tool_data"];
				$olddata[$i][CATEGORY_STATUS]= 3;
				$retval = 3;
				unset($_SESSION["tool_data"]);
				$_SESSION["tool_data"]=$olddata;
				return $retval;
			}
		}
		
		function UpdateCatStatusCustom($userid,$catid)
		{
			global $wpdb;

			$this->updateLastEditDate( $userid );
			if($catid!="")
			{
				$olddata = $_SESSION["tool_data"];
				for($i=0;$i<sizeof($olddata);$i++)
				{
					if($olddata[$i][CATEGORY_ID]==$catid)
					{
						if($olddata[$i][CATEGORY_LINEITEMS])
						{
							$total_lineitems=count($olddata[$i][CATEGORY_LINEITEMS]);
							$lcnt=0;
							$lnocnt=0;
							$cnt=0;
							for($j=0;$j<sizeof($olddata[$i][CATEGORY_LINEITEMS]);$j++)
							{
								if($olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_STATUS]==1)
								{
									$cnt++;
									//$olddata[$i][CATEGORY_STATUS]=1;
									//break;
								}
								else if($olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_STATUS]==2)
								{
									$lnocnt++;
								}
								else if($olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_STATUS]==0)
								{
									$lcnt++;
								}
							}

						}
					}
				}
				unset($_SESSION["tool_data"]);
				return $retval;
			}
			else
				return false;
		}

		
		function UpdateCatStatus($userid,$catid)
		{
			global $wpdb;
			$this->updateLastEditDate( $userid );
			
			if($catid!="")
			{
				$olddata = $_SESSION["tool_data"];
				for($i=0;$i<sizeof($olddata);$i++)
				{
					if($olddata[$i][CATEGORY_ID]==$catid)
					{
						if($olddata[$i][CATEGORY_LINEITEMS])
						{
							$total_lineitems=count($olddata[$i][CATEGORY_LINEITEMS]);
							$lcnt=0;
							$lnocnt=0;
							$cnt=0;
							for($j=0;$j<sizeof($olddata[$i][CATEGORY_LINEITEMS]);$j++)
							{
								if($olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_STATUS]==1)
								{
									$cnt++;
									//$olddata[$i][CATEGORY_STATUS]=1;
									//break;
								}
								else if($olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_STATUS]==2)
								{
									$lnocnt++;
								}
								else if($olddata[$i][CATEGORY_LINEITEMS][$j][LINEITEM_STATUS]==0)
								{
									$lcnt++;
								}
							}
							
							//if no item is selected then category satus will be 2 (category not required)
							 if($total_lineitems>0 && $lnocnt==$total_lineitems)
							{
								//echo "if1<br>";
								$olddata[$i][CATEGORY_STATUS]=2;
								$retval="2";
							}
							else if($total_lineitems>0 && $cnt==$total_lineitems)
							{  //if all items are selected then include category (category complete)
								//echo "if2<br>";
								$olddata[$i][CATEGORY_STATUS]=1;
								$retval="1";
							}
							else if($total_lineitems>0 && ($cnt+$lnocnt)==$total_lineitems && ( $olddata[$i][CATEGORY_STATUS]!=1 || $olddata[$i][CATEGORY_STATUS]!=2))
							{
							     //if sum of selected and not selected items is equl to 0 and category status = 0 (then category incomplete)
								//echo "if3<br>";
								$olddata[$i][CATEGORY_STATUS]=0;
								$retval="4";
							}
							else if($total_lineitems>0 && $lcnt>0 && $olddata[$i][CATEGORY_STATUS]!=2)
							{
								//echo "if4<br>";
								$olddata[$i][CATEGORY_STATUS]=0;
								$retval="4";
							} 

						}
					}
				}
				unset($_SESSION["tool_data"]);
				$_SESSION["tool_data"]=$olddata;
				return $retval;
			}
			else
				return false;
		}

		/* function to resert index of an array */
		function fix_keys($array)
		{
		  foreach ($array as $k => $val) {
			if (is_array($val))
			  $array[$k] = $this->fix_keys($val); //recurse
		  }
		  return array_values($array);
		}

		/* get calculated roi result */
		function ROICalculation($hourly_rate,$time = 0,$cost = 0,$how_often_doit)
		{
		   //echo "hr : $hourly_rate ,time = $time, cost = $cost ,often = $how_often_doit<br>";  
			if(($time>0 || $time!="") && $cost==0)
				$ans=$hourly_rate*$time;
			else if(($cost>0 || $cost!="") && $time==0)
				$ans=$cost;
			else
				$ans = ($hourly_rate*$time)+$cost; //$ans=$hourly_rate*($time+$cost);

			if($ans)
				return $ans*$how_often_doit;
			else
				return 0;
		}

		/* Encryption function */
		function k_encrypt($value,$serialNumber="")
		{
			global $config;
			return base64_encode(strrev(substr(md5($serialNumber),0,10) . $value));
		}

		/* Description function */
		function k_decrypt($value,$serialNumber="")
		{
			global $config;
			return str_replace(substr(md5($serialNumber),0,10),"",strrev(base64_decode($value)));
		}

		/* Get top cateory or lineitem */
		function GetTop5($type)
		{
			global $wpdb;

			$userdata = $wpdb->get_results("select * from wp_usermeta where meta_key='tool_data'");
			 
			$total=$wpdb->num_rows;
			foreach($userdata as $data)
			{   
			    $user_id = $data->user_id;
				$tooldata=unserialize($data->meta_value);
				
				if($tooldata)
				{
					for($i=0;$i<sizeof($tooldata);$i++)
					{    
						if($tooldata[$i][CATEGORY_STATUS]==1 && $tooldata[$i][CATEGORY_IS_CUSTOM]==0)
						{  
							$newdata[$tooldata[$i][CATEGORY_ID]]['cnt']=$newdata[$tooldata[$i][CATEGORY_ID]]['cnt']+1;
							$roi_line = 0;	
							
							if($tooldata[$i][CATEGORY_LINEITEMS])
							{
								foreach($tooldata[$i][CATEGORY_LINEITEMS] as $lineitem)
								{ 
									$how_often = $lineitem[LINEITEM_YEAR];
									$cost = $lineitem[LINEITEM_COST];
									$time = $lineitem[LINEITEM_TIME];
									//$hrate = get_usermeta($data->user_id,"hourly_rate",true);
									$hrate = get_usermeta($data->user_id,"org_ques_9_state",true);
									
									$roi_line = $roi_line+$this->ROICalculation($hrate,$time,$cost,$how_often);

									if($lineitem[LINEITEM_IS_CUSTOM]==0)
									{
										$featuredata[$user_id][$lineitem[LINEITEM_ID]]['total'] = $total; 
										$featuredata[$user_id][$lineitem[LINEITEM_ID]]['roi'] = $this->ROICalculation($hrate,$time,$cost,$how_often);
										$featuredata[$user_id][$lineitem[LINEITEM_ID]]['cnt'] = $featuredata[$lineitem[LINEITEM_ID]]['cnt']+1;
										$featuredata[$user_id][$lineitem[LINEITEM_ID]]['lineid'] = $lineitem[LINEITEM_ID];
									}

								}
								$newdata[$tooldata[$i][CATEGORY_ID]]['total'] = $total;
								$newdata[$tooldata[$i][CATEGORY_ID]]['roi'] = $newdata[$tooldata[$i][CATEGORY_ID]]['roi']+$roi_line;
								$newdata[$tooldata[$i][CATEGORY_ID]]['catid']=$tooldata[$i][CATEGORY_ID];
							}
						}
					}
				}
			} 
			//add the roi values from different users for different line items
			
			$p = 0;
			foreach($featuredata as $key=>$value){
			   foreach($value as $subkey=>$subval){
			   
				$result[$subkey]['total'] = $subval['total'];
				$p = $subval['roi'];
				$result[$subkey]['roi'] = $p+$result[$subkey]['roi'];
				$result[$subkey]['cnt'] = $subval['cnt'];
				$result[$subkey]['lineid'] = $subval['lineid'];
			   
			  }
			}
						
			
			if($type=='category')
				return $newdata;
			else if($type=='lineitem')
				return $result;//return $featuredata;
		}
		
		/** display functional area lines in dashboard **/
		
		function displayFAlines($catarray){
		    $final = "";
			foreach($catarray as $inkey=>$inval){
			    $homeUrl = home_url();
			    $catName = stripslashes($this->GetCategoryName($inkey)) ;
				$encrypt = $this->k_encrypt($inkey);
				$display = $inval['display']; 
				$status = $inval['status']; 
				$lineitemcnt = $inval['lineitemcnt']; 
				$roi_line = $inval['roi_line'];
				$cls = $inval['cls'];
				
				$final .= "<div class=\"lb_raw\">
									<div class=\"lb_raw_left\">
										<div class=\"block ".$cls."\">
											<div class=\"block-inner\">
												<a href=\"". home_url()."?act=features&edit=$encrypt&status=$status\">
													<span class=\"circle-small numtop\">".$display."</span>
													<span class=\"circle-large nummid\">of</span>
													<span class=\"circle-small numbtm\">$lineitemcnt</span>
												</a>
											</div>
										</div>
									</div>
									<div class=\"lb_raw_right\">
										<div class=\"lb_raw_top\">
											<span class=\"lb_title\">$catName</span>
											<span class=\"lb_status\">". $status ."</span>
											<span class=\"lb_roi\">$$roi_line/YR</span>
										</div>
										<div class=\"lb_raw_btm\">
											<span class=\"lb_title\"></span>
											<div class=\"lb_status\"><a href=\"$homeUrl?act=features&edit=$encrypt&status=$status\" class=\"btn-edit\">Edit</a></div>
											<span class=\"lb_roi\"><span class=\"dollar\">$</span></span>
										</div>
									</div>
							</div>";
							
						}
			
			return $final;
		   } 
		   
		/* get value from wp_specdoc_field_items according to field name */
		function getDefaultFieldData($name)
		{
			global $wpdb;
			$sSQL="SELECT * from ".$wpdb->prefix."specdoc_field_items where fieldname='".$name."'";
			return $wpdb->get_row($sSQL); 
		}
		
		/* Save user project data in usermeta */
		function SaveUserProject($profileinfo,$userid,$page=0)
		{
			global $wpdb; 

			$this->updateLastEditDate( $userid );
			 foreach($profileinfo as $key=>$value)
			 {
				update_user_meta($userid,$key,mysql_real_escape_string($value));
			 }
			 //update_user_meta($userid,"userproject",1);
			
         	 if($page == 0)
             {			 
				 // update the section status
				 $qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$userid' AND section_name = 'projectquesinfo' "; 
				 $wpdb->query($qry); 
			 }
			 
			 return $profileinfo; 
		}
		
		/* Save user company data in usermeta */
		function SaveUserCompany($profileinfo,$userid) {

			$this->updateLastEditDate( $userid );

			foreach($profileinfo as $key=>$value) {
				update_user_meta($userid,$key,$value);
			}

			$answerStartedData = get_user_meta($userid, '_answerStartedData', true);

			if (!$answerStartedData) {
			
				$dt = new DateTime();
				$now =  $dt->format('Y-m-d H:i:s');			
				add_user_meta( $userid, '_answerStartedData', $now );

			}
			 return $profileinfo; 
		}
		
		/** create thumbnail **/
		function createThumbnail($filename,$loggedin_id) {  
         
		 $uploadfiles = $filename;
		 /* $filename = $uploadfiles['name']; 
		 $filetmp = $uploadfiles['tmp_name']; */
		 $filetype = wp_check_filetype( basename( $filename ), null );
		 $filetitle = preg_replace('/\.[^.]+$/', '', basename( $filename ) );
		 $filename = $filetitle . '.' . $filetype['ext'];
		 $upload_dir = wp_upload_dir();			
		  $path_to_image_directory = $upload_dir['basedir'] .'/companylogos/'.$loggedin_id.'/';		 
        //require 'config.php';  
          //echo $path_to_image_directory . $filename;
		$final_width_of_image = 300;  
		//$path_to_image_directory = 'images/';
		$path_to_thumbs_directory = $upload_dir['basedir'] .'/companylogos/thumbs/'.$loggedin_id.'/';	 
		
        if(preg_match('/[.](jpg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $im = imagecreatefromgif($path_to_image_directory . $filename);  
        } else if (preg_match('/[.](png)$/', $filename)) {  
            $im = imagecreatefrompng($path_to_image_directory . $filename);  
        }  
          
        $ox = imagesx($im); //width 
        $oy = imagesy($im);  //heignt
		
		 $max_width = 300;
		 $max_height = 300;
		 
		 $width = $ox;
			$height = $oy;

			# taller
			if ($height > $max_height) {
				$nx = ($max_height / $height) * $width;
				$ny = $max_height;
			}else{
				$ny = $oy;
				$nx = $ox;
			}

			# wider
			if ($width > $max_width) {
				$ny = ($max_width / $width) * $height;
				$nx = $max_width;
			}else{
				$ny = $oy;
				$nx = $ox;
			}
			
		           
        $nm = imagecreatetruecolor($nx, $ny);  
          
        imagecopyresampled($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);  
        
        if(!is_dir($path_to_thumbs_directory)) {  
          if(!mkdir($path_to_thumbs_directory)) {  
               die("There was a problem. Please try again!");  
          } else{
		  mkdir($path_to_thumbs_directory);
		  }		  
        }  
      
        imagejpeg($nm, $path_to_thumbs_directory . $filename);  
        return $filename;
      } 
	  
	  public function getuserdata($user_id){
	  
	  $projectmetafields = array('authors','project_objectives','project_budget');
	 
	     
		 //get_user_meta($user_id, $key, $single);
		$getmeta = get_user_meta($user_id);	
		foreach($projectmetafields as $key=>$val){
			if(!array_key_exists($val, $getmeta)){			  
			    $result = 'project';
			}else{
			    $values = get_user_meta($user_id,"$val", true);
				if(empty($values)){
				  $result = 'project';
				  break;
				}
			}
		}
		
		 $milestones = userinfo::selectAllMilestones($user_id);
		 if(count($milestones) == 0){
		    $result = 'project';
		 }
		 
		$team_members =  $this->selectTeamInfo($user_id);
		if(count($team_members) == 0){
		    $result = 'project';
		 }
		$companymetafields = array('company_address','company_city','company_state','company_zip','start_date','org_bg');
		
		foreach($companymetafields as $ckey=>$cval){
			if(!array_key_exists($cval, $getmeta)){			  
			    $result = 'company';
			}else{
			    $cvalues = get_user_meta($user_id,"$cval", true);
				if(empty($cvalues)){
				  $result = 'company';
				  break;
				}
			}
		}
		
		$functionalArea = $this->checkFaStatus($user_id);
		if($functionalArea > 0){			  
			    $result = 'functionalarea';
			}	
		return $result;
	  }
	   
	  /* get name of all selection criteria */
		function getAllSelectionCriteria()
		{
			global $wpdb;
 
			$sSQL="select * from ".$wpdb->prefix."specdoc_selection_criteria ";
			$res = $wpdb->get_results($sSQL,ARRAY_A);
			return $res;

		}
		
		/* get selection criteria name */
		function scNameFromId($id)
		{
			global $wpdb;
 
			$sSQL="select sc_name from ".$wpdb->prefix."specdoc_selection_criteria WHERE scid=$id";
			$res = $wpdb->get_row($sSQL,ARRAY_A);
			return $res;

		}
		
		/* Save user stakeholder list */
		function saveStakeholders($data,$userid){
			global $wpdb;

			$this->updateLastEditDate( $userid );
			
			//$filteredsharray = array_values( array_filter($data['stackholder']) );
			$filteredsharray = array_values( $data['stackholder'] );
			$shvalues = implode(',',$filteredsharray);
			
			$getlist = $this->selectStakeholders($userid);
		
			
			if(count($getlist)>0){
			
				$query = "UPDATE ".$wpdb->prefix."specdoc_userlistinfo SET fieldvalue='$shvalues' WHERE fieldname='stackholders' AND userid='$userid'";
				
			} else{					 
				$query = "INSERT INTO ".$wpdb->prefix."specdoc_userlistinfo (userid,fieldname,fieldvalue) values(".$userid.",'stackholders','".$shvalues."')";
			} 
			
			$wpdb->query($query);
			
			// updating the status of stakeholderinfo
			
			$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$userid' AND section_name = 'stakeholderinfo' ";
			$wpdb->query($qry);  
		 return true;
		}
		
		/* Select user stakeholder list */
		function selectStakeholders($userid){
			global $wpdb;
			 
			$query = "SELECT id,fieldvalue From ".$wpdb->prefix."specdoc_userlistinfo Where userid= '".$userid."' AND fieldname='stackholders'";
			$result = $wpdb->get_row($query,ARRAY_A);
		 return $result;
		}
		
		
		function saveSectionStageForGuidePageTickOthers2($loggedin_id, $sectName, $guideName, $status, $stepNo, $formSectionName, $otherformsectsName1, $otherformsectsName2){
		     global $wpdb;  

             $query2 = "SELECT status From ".$wpdb->prefix."guideActionInSkop Where userId = ".$loggedin_id." AND formTitle = '".$sectName."' and formSectionName = '".$formSectionName."' ";
			 $result2 = $wpdb->get_results($query2);    
             
             if( is_array($result2) and empty($result2) )
			 { 
			     $query2 = "INSERT INTO ".$wpdb->prefix."guideActionInSkop (step, title, userId, formTitle, status, formSectionName) values('".$stepNo."', '".$guideName."', ".$loggedin_id.", '".$sectName."', '".$status."', '".$formSectionName."')";
			 }
			 else
			 {
			     $query2 = " UPDATE ".$wpdb->prefix."guideActionInSkop SET status = '".$status."' WHERE userId = ".$loggedin_id." and formTitle = '".$sectName."' and formSectionName = '".$formSectionName."' ";
			 }	 
             $wpdb->query($query2);	
			 $totalUpdated = 0;
			 
			 $checkforAllCompleteQ = "SELECT status From ".$wpdb->prefix."guideActionInSkop Where userId = ".$loggedin_id." AND formTitle = '".$sectName."' and formSectionName = '".$formSectionName."' ";
			 $resultforAllCompleteQ = $wpdb->get_results($checkforAllCompleteQ);
			 
			 $checkforAllCompleteQ1 = "SELECT status From ".$wpdb->prefix."guideActionInSkop Where userId = ".$loggedin_id." AND formTitle = '".$sectName."' and formSectionName = '".$otherformsectsName1."' ";
			 $resultforAllCompleteQ1 = $wpdb->get_results($checkforAllCompleteQ1);
			 
			 $checkforAllCompleteQ2 = "SELECT status From ".$wpdb->prefix."guideActionInSkop Where userId = ".$loggedin_id." AND formTitle = '".$sectName."' and formSectionName = '".$otherformsectsName2."' ";
			 $resultforAllCompleteQ2 = $wpdb->get_results($checkforAllCompleteQ2); 
             
			 if( is_array($resultforAllCompleteQ) and empty($resultforAllCompleteQ) )
			 { }
			 else  
			 {   
			        $statusTmp = $resultforAllCompleteQ[0]->status; 
					if($statusTmp == 'Complete')
					{
					     $totalUpdated = $totalUpdated + 1;
					}
			 }
			 
			 if( is_array($resultforAllCompleteQ1) and empty($resultforAllCompleteQ1) )
			 { }
			 else  
			 {   
			        $statusTmp = $resultforAllCompleteQ1[0]->status; 
					if($statusTmp == 'Complete')
					{
					     $totalUpdated = $totalUpdated + 1;
					}
			 }
			 
			 if( is_array($resultforAllCompleteQ2) and empty($resultforAllCompleteQ2) )
			 { }
			 else  
			 {   
			        $statusTmp = $resultforAllCompleteQ2[0]->status; 
					if($statusTmp == 'Complete')
					{
					     $totalUpdated = $totalUpdated + 1;
					}
			 } 
               
			   if($totalUpdated == 3) {
					 $query3 = "SELECT itemId From ".$wpdb->prefix."specdoc_guide_items Where item_name = '".$guideName."' ";
					 $result3 = $wpdb->get_results($query3);
					 $itemIdV = $result3[0]->itemId;  
					 $query5 = "SELECT * From ".$wpdb->prefix."specdoc_guide_user_items Where user_id = ".$loggedin_id." and item_id = ".$itemIdV." ";
					 $result5 = $wpdb->get_results($query5); 
					 if($status == "Complete")
					 {
						  $item_status = 1;
					 }
					 else
					 {
						  $item_status = 0; 
					 } 
					 if( is_array($result5) and empty($result5) )
					 { 
						 $query4 = "INSERT INTO ".$wpdb->prefix."specdoc_guide_user_items (user_id, item_id, item_status) values(".$loggedin_id.", ".$itemIdV.", $item_status)";
					 }
					 else
					 {
						  
						 $query4 = " UPDATE ".$wpdb->prefix."specdoc_guide_user_items SET item_status = $item_status WHERE user_id = ".$loggedin_id." and item_id = ".$itemIdV." ";
					 }	 
					 $wpdb->query($query4);	

			  }
              else {
                     $query3 = "SELECT itemId From ".$wpdb->prefix."specdoc_guide_items Where item_name = '".$guideName."' ";
					 $result3 = $wpdb->get_results($query3);
					 $itemIdV = $result3[0]->itemId;  
					 $query5 = "SELECT * From ".$wpdb->prefix."specdoc_guide_user_items Where user_id = ".$loggedin_id." and item_id = ".$itemIdV." ";
					 $result5 = $wpdb->get_results($query5); 
					 
					 $item_status = 0;
					 
					 if( is_array($result5) and empty($result5) )
					 { 
						 $query4 = "INSERT INTO ".$wpdb->prefix."specdoc_guide_user_items (user_id, item_id, item_status) values(".$loggedin_id.", ".$itemIdV.", $item_status)";
					 }
					 else
					 {
						  
						 $query4 = " UPDATE ".$wpdb->prefix."specdoc_guide_user_items SET item_status = $item_status WHERE user_id = ".$loggedin_id." and item_id = ".$itemIdV." ";
					 }	 
					 $wpdb->query($query4);
              }			  
		}
		
		
		function saveSectionStageForGuidePageTickOthers($loggedin_id, $sectName, $guideName, $status, $stepNo, $formSectionName, $otherformsectsName1, $otherformsectsName2, $otherformsectsName3){
		     global $wpdb;  

			$this->updateLastEditDate( $loggedin_id );

             $query2 = "SELECT status From ".$wpdb->prefix."guideActionInSkop Where userId = ".$loggedin_id." AND formTitle = '".$sectName."' and formSectionName = '".$formSectionName."' ";
			 $result2 = $wpdb->get_results($query2);    
             
             if( is_array($result2) and empty($result2) )
			 { 
			     $query2 = "INSERT INTO ".$wpdb->prefix."guideActionInSkop (step, title, userId, formTitle, status, formSectionName) values('".$stepNo."', '".$guideName."', ".$loggedin_id.", '".$sectName."', '".$status."', '".$formSectionName."')";
			 }
			 else
			 {
			     $query2 = " UPDATE ".$wpdb->prefix."guideActionInSkop SET status = '".$status."' WHERE userId = ".$loggedin_id." and formTitle = '".$sectName."' and formSectionName = '".$formSectionName."' ";
			 }	 
             $wpdb->query($query2);	
			 $totalUpdated = 0;
			 
			 $checkforAllCompleteQ = "SELECT status From ".$wpdb->prefix."guideActionInSkop Where userId = ".$loggedin_id." AND formTitle = '".$sectName."' and formSectionName = '".$formSectionName."' ";
			 $resultforAllCompleteQ = $wpdb->get_results($checkforAllCompleteQ);
			 
			 $checkforAllCompleteQ1 = "SELECT status From ".$wpdb->prefix."guideActionInSkop Where userId = ".$loggedin_id." AND formTitle = '".$sectName."' and formSectionName = '".$otherformsectsName1."' ";
			 $resultforAllCompleteQ1 = $wpdb->get_results($checkforAllCompleteQ1);
			 
			 $checkforAllCompleteQ2 = "SELECT status From ".$wpdb->prefix."guideActionInSkop Where userId = ".$loggedin_id." AND formTitle = '".$sectName."' and formSectionName = '".$otherformsectsName2."' ";
			 $resultforAllCompleteQ2 = $wpdb->get_results($checkforAllCompleteQ2);
			 
			 $checkforAllCompleteQ3 = "SELECT status From ".$wpdb->prefix."guideActionInSkop Where userId = ".$loggedin_id." AND formTitle = '".$sectName."' and formSectionName = '".$otherformsectsName3."' ";
			 $resultforAllCompleteQ3 = $wpdb->get_results($checkforAllCompleteQ3);
             
			 if( is_array($resultforAllCompleteQ) and empty($resultforAllCompleteQ) )
			 { }
			 else
			 {
			        $statusTmp = $resultforAllCompleteQ[0]->status; 
					if($statusTmp == 'Complete')
					{
					     $totalUpdated = $totalUpdated + 1;
					}
			 }
			 
			 if( is_array($resultforAllCompleteQ1) and empty($resultforAllCompleteQ1) )
			 { }
			 else  
			 {   
			        $statusTmp = $resultforAllCompleteQ1[0]->status; 
					if($statusTmp == 'Complete')
					{
					     $totalUpdated = $totalUpdated + 1;
					}
			 }
			 
			 if( is_array($resultforAllCompleteQ2) and empty($resultforAllCompleteQ2) )
			 { }
			 else  
			 {   
			        $statusTmp = $resultforAllCompleteQ2[0]->status; 
					if($statusTmp == 'Complete')
					{
					     $totalUpdated = $totalUpdated + 1;
					}
			 }
			 
			 if( is_array($resultforAllCompleteQ3) and empty($resultforAllCompleteQ3) )
			 { }
			 else  
			 {   
			        $statusTmp = $resultforAllCompleteQ3[0]->status; 
					if($statusTmp == 'Complete')
					{
					     $totalUpdated = $totalUpdated + 1;
					}
			 }
               
			   if($totalUpdated == 4) {
					 $query3 = "SELECT itemId From ".$wpdb->prefix."specdoc_guide_items Where item_name = '".$guideName."' ";
					 $result3 = $wpdb->get_results($query3);
					 $itemIdV = $result3[0]->itemId;  
					 $query5 = "SELECT * From ".$wpdb->prefix."specdoc_guide_user_items Where user_id = ".$loggedin_id." and item_id = ".$itemIdV." ";
					 $result5 = $wpdb->get_results($query5); 
					 if($status == "Complete")
					 {
						  $item_status = 1;
					 }
					 else
					 {
						  $item_status = 0; 
					 } 
					 if( is_array($result5) and empty($result5) )
					 { 
						 $query4 = "INSERT INTO ".$wpdb->prefix."specdoc_guide_user_items (user_id, item_id, item_status) values(".$loggedin_id.", ".$itemIdV.", $item_status)";
					 }
					 else
					 {
						  
						 $query4 = " UPDATE ".$wpdb->prefix."specdoc_guide_user_items SET item_status = $item_status WHERE user_id = ".$loggedin_id." and item_id = ".$itemIdV." ";
					 }	 
					 $wpdb->query($query4);	
			  }
              else {
                     $query3 = "SELECT itemId From ".$wpdb->prefix."specdoc_guide_items Where item_name = '".$guideName."' ";
					 $result3 = $wpdb->get_results($query3);
					 $itemIdV = $result3[0]->itemId;  
					 $query5 = "SELECT * From ".$wpdb->prefix."specdoc_guide_user_items Where user_id = ".$loggedin_id." and item_id = ".$itemIdV." ";
					 $result5 = $wpdb->get_results($query5); 
					 
					 $item_status = 0;
					 
					 if( is_array($result5) and empty($result5) )
					 { 
						 $query4 = "INSERT INTO ".$wpdb->prefix."specdoc_guide_user_items (user_id, item_id, item_status) values(".$loggedin_id.", ".$itemIdV.", $item_status)";
					 }
					 else
					 {
						  
						 $query4 = " UPDATE ".$wpdb->prefix."specdoc_guide_user_items SET item_status = $item_status WHERE user_id = ".$loggedin_id." and item_id = ".$itemIdV." ";
					 }	 
					 $wpdb->query($query4);
              }			  
		}
		 
		function saveSectionStageForGuidePageTick($loggedin_id, $sectName, $guideName, $status, $stepNo){
		     global $wpdb;  

			$this->updateLastEditDate( $loggedin_id );
             $query2 = "SELECT status From ".$wpdb->prefix."guideActionInSkop Where userId = ".$loggedin_id." AND formTitle = '".$sectName."' ";
			 $result2 = $wpdb->get_results($query2);    
             
             if( is_array($result2) and empty($result2) )
			 { 
			     $query2 = "INSERT INTO ".$wpdb->prefix."guideActionInSkop (step, title, userId, formTitle, status) values('".$stepNo."', '".$guideName."', ".$loggedin_id.", '".$sectName."', '".$status."')";
			 }
			 else
			 {
			     $query2 = " UPDATE ".$wpdb->prefix."guideActionInSkop SET status = '".$status."' WHERE userId = ".$loggedin_id." and formTitle = '".$sectName."' ";
			 }	

             $wpdb->query($query2);	


             $query3 = "SELECT itemId From ".$wpdb->prefix."specdoc_guide_items Where item_name = '".$guideName."' ";
			 $result3 = $wpdb->get_results($query3);
             $itemIdV = $result3[0]->itemId; 			 

			 
			 $query5 = "SELECT * From ".$wpdb->prefix."specdoc_guide_user_items Where user_id = ".$loggedin_id." and item_id = ".$itemIdV." ";
			 $result5 = $wpdb->get_results($query5);
			 
			 if($status == "Complete")
			 {
			      $item_status = 1;
			 }
			 else
			 {
			      $item_status = 0; 
			 }
			  
             if( is_array($result5) and empty($result5) )
			 { 
			     $query4 = "INSERT INTO ".$wpdb->prefix."specdoc_guide_user_items (user_id, item_id, item_status) values(".$loggedin_id.", ".$itemIdV.", $item_status)";
			 }
			 else
			 {
			      
			     $query4 = " UPDATE ".$wpdb->prefix."specdoc_guide_user_items SET item_status = $item_status WHERE user_id = ".$loggedin_id." and item_id = ".$itemIdV." ";
			 }	
			 
             $wpdb->query($query4);			 
		}
		
		/* Save Section form status after Stage 2 */
		function saveSectionStageAfterStage2($loggedin_id, $sectName, $formName, $status){
		     global $wpdb; 

			$this->updateLastEditDate( $loggedin_id );			 
             $query = "SELECT status From ".$wpdb->prefix."save_status_at_stage2 Where userId = ".$loggedin_id." AND section_name = '".$sectName."' AND form_name = '".$formName."' ";
			 $result = $wpdb->get_results($query);    
             
             if( is_array($result) and empty($result) )
			 { 
			     $query = "INSERT INTO ".$wpdb->prefix."save_status_at_stage2 (userId, section_name, form_name, status) values(".$loggedin_id.", '".$sectName."', '".$formName."', '".$status."')";
			 }
			 else
			 {
			     $query = " UPDATE ".$wpdb->prefix."save_status_at_stage2 SET status = '".$status."' WHERE userId = ".$loggedin_id." and section_name = '".$sectName."' and form_name = '".$formName."' ";
			 }	
 			 
             $wpdb->query($query);			 
		}
		
		/* Save user selectionCreteria list */
		function saveSelectionCreteria($data,$userid){
			global $wpdb;

			$this->updateLastEditDate( $userid );			
			unset($data['scval']);
			
			foreach($data as $key=>$value){
			  $getnum = explode('imp_weight_',$key);
			  
			  $fieldid = $getnum[1];
			  $selectionCreteria[$fieldid] = $value;
			} 

			$scvalues = serialize($selectionCreteria); 
			$getlist1 = $this->selectselectionCreteria($userid);
				
			if(count($getlist1)>0){
			
				$query = "UPDATE ".$wpdb->prefix."specdoc_userlistinfo SET fieldvalue='".mysql_real_escape_string($scvalues)."' WHERE fieldname='selection_creteria' AND userid='$userid'";
				
			} else{					 
				$query = "INSERT INTO ".$wpdb->prefix."specdoc_userlistinfo (userid,fieldname,fieldvalue) values(".$userid.",'selection_creteria','".mysql_real_escape_string($scvalues)."')";
			} 
			
		    $wpdb->query($query);
			
			// update status of selection creteria
			$qry = "UPDATE ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$userid' AND section_name = 'selection_creteria_info'";
			$wpdb->query($qry); 
		 return true;
		}
		
		
		/* Select user selectionCreteria */
		function selectselectionCreteria($userid){
			global $wpdb;
			 
			$query = "SELECT id,fieldvalue From ".$wpdb->prefix."specdoc_userlistinfo Where userid= '".$userid."' AND fieldname='selection_creteria'";
			$result = $wpdb->get_row($query,ARRAY_A);
		 return $result;
		}
		
		/* Save user Anticipated Benefits */
		function saveAnticipatedBenefits($data,$userid){
		
			global $wpdb;

			$this->updateLastEditDate( $userid );			
			//$filteredbenefitarray = array_values( array_filter($data['anbenefits']) );
			$filteredbenefitarray = array_values( $data['anbenefits'] );
			$shvalues = implode(',',$filteredbenefitarray);
			
			$getlist = $this->selectAnticipatedBenefits($userid);		
			
			if(count($getlist)>0){ 
			
				$query = "UPDATE ".$wpdb->prefix."specdoc_userlistinfo SET fieldvalue='$shvalues' WHERE fieldname='anticipated_benefits' AND userid='$userid'";				
			} else{					 
				$query = "INSERT INTO ".$wpdb->prefix."specdoc_userlistinfo (userid,fieldname,fieldvalue) values(".$userid.",'anticipated_benefits','".$shvalues."')";
			} 
			
			$wpdb->query($query);
			
			$qry = "UPDATE ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$userid' AND section_name = 'benefitinfo'";
			$wpdb->query($qry); 
			
		 return true;
		}
		
		/* Save user stakeholder list */
		function selectAnticipatedBenefits($userid){
			global $wpdb;
			 
			$query = "SELECT id,fieldvalue From ".$wpdb->prefix."specdoc_userlistinfo Where userid= '".$userid."' AND fieldname='anticipated_benefits'";
			$result = $wpdb->get_row($query,ARRAY_A);
		 return $result;
		}
		
		function saveTeamInfoCustom($data,$userid,$page=0){
		     
			global $wpdb;
			 
			$this->updateLastEditDate( $userid );		
						
			$memebersNameArray = $data['membername'];
			$memebersEmailArray = $data['memberemail']; 
			
			$memebersIdArray = $data['memberid'];
			$totalVisibleMem = count($memebersNameArray);
			
			$tlid = $data['tlid'];
			$teamleadname = $data['teamleadname'];
			$tlemail = $data['teamlead_email'];
			$needToDelIDs = array();
			// if user removes all user from the list then delete all the users from database
			
			$query = "SELECT * From ".$wpdb->prefix."specdoc_userteaminfo Where userid= '".$userid."' and member_type = 'member'";
		    $user_data = $wpdb->get_results($query);
			$totalAddedInUsers = count($user_data); 
 		 
			
			// check and enter team lead information
			$tlcount = $this->CheckMemberEmail($userid,$data['teamlead_email'],$tlid);
			$tlname = str_replace("<!","< !",$teamleadname);
			 
			if($tlid>0){		
				
				$tlquery = "UPDATE ".$wpdb->prefix."specdoc_userteaminfo SET member_name = '".mysql_real_escape_string($tlname)."',member_email='".mysql_real_escape_string($tlemail)."' WHERE userid ='".$userid."' AND id='".$tlid."' AND member_type='tl'";
			}else{
				$tlquery = "INSERT INTO ".$wpdb->prefix."specdoc_userteaminfo (userid,	member_name,member_email,member_responsibility,member_estimate_effort,	member_type) values(".$userid.",'".mysql_real_escape_string($tlname)."','".mysql_real_escape_string($data['teamlead_email'])."','','','tl')";				
			}
		
			$wpdb->query($tlquery);	 
			
			if( is_array($memebersEmailArray) and !empty($memebersEmailArray) )
			{
			    $totalmembers = count($memebersEmailArray);
				 
				
			      for($i=0; $i<$totalmembers; $i++){

					      if($memebersIdArray[$i] != '')
						  {
							 $memQuery = "UPDATE ".$wpdb->prefix."specdoc_userteaminfo SET member_name='".mysql_real_escape_string($memebersNameArray[$i])."',member_email='".mysql_real_escape_string($memebersEmailArray[$i])."' WHERE userid='$userid' AND id='".$memebersIdArray[$i]."'"; 
						     $wpdb->query($memQuery); 
						  }
						  else
						  {
						     $memQuery = "INSERT INTO ".$wpdb->prefix."specdoc_userteaminfo (userid,	member_name,member_email,member_responsibility,member_estimate_effort,	member_type) values(".$userid.",'".mysql_real_escape_string($memebersNameArray[$i])."','".mysql_real_escape_string($memebersEmailArray[$i])."','','','member')"; 
						     $wpdb->query($memQuery);
						  } 
                  }				  
			}
			else
			{ }
			//die;
			// check and enter Member information
			$totalMemberArray = $this->selectTeamInfo($userid);

			foreach($totalMemberArray as $tkey=>$tval){
				//echo "<br>$tkey=>$tval";
				$memberId = $tval['id'];
				$memberType = $tval['member_type'];
				$memberName = $tval['member_name'];
				$memberEmail = $tval['member_email'];
				
				if(count($memebersIdArray) !=0){
					foreach($memebersIdArray as $key=>$val){					
						if($memberId == $val ){  
						   $updatequery = "UPDATE ".$wpdb->prefix."specdoc_userteaminfo SET member_name = '".mysql_real_escape_string($memberName)."' , member_email='".mysql_real_escape_string($memberEmail)."' WHERE id='".$memberId."' AND userid='$userid'";
						  $wpdb->query($updatequery);
						
						}else{ 
						  
						}
					}
				}
			}
			  
			 $error = '';
			 
		   

		   if($page == 0) {
			// update status table
			 $updateqry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$userid' AND section_name = 'teaminialinfo' ";
			$wpdb->query($updateqry);  
		   }
		   
		 return $error;     
	 }

		/* Save user Information */
		function saveTeamInfo($data,$userid,$page=0){
			global $wpdb;
			 
			$this->updateLastEditDate( $userid );		
						
			$memebersNameArray = $data['membername'];
			$memebersEmailArray = $data['memberemail']; 
			
			$memebersIdArray = $data['memberid'];
			$totalVisibleMem = count($memebersNameArray);
			
			$tlid = $data['tlid'];
			$teamleadname = $data['teamleadname'];
			$tlemail = $data['teamlead_email'];
			$needToDelIDs = array();
			// if user removes all user from the list then delete all the users from database
			
			$query = "SELECT * From ".$wpdb->prefix."specdoc_userteaminfo Where userid= '".$userid."' and member_type = 'member'";
		    $user_data = $wpdb->get_results($query);
			$totalAddedInUsers = count($user_data); 
			
			// check and enter team lead information
			$tlcount = $this->CheckMemberEmail($userid,$data['teamlead_email'],$tlid);
			$tlname = str_replace("<!","< !",$teamleadname);
			 
			if($tlid>0){		
				
				$tlquery = "UPDATE ".$wpdb->prefix."specdoc_userteaminfo SET member_name = '".mysql_real_escape_string($tlname)."',member_email='".mysql_real_escape_string($tlemail)."' WHERE userid ='".$userid."' AND id='".$tlid."' AND member_type='tl'";
			}else{
				$tlquery = "INSERT INTO ".$wpdb->prefix."specdoc_userteaminfo (userid,	member_name,member_email,member_responsibility,member_estimate_effort,	member_type) values(".$userid.",'".mysql_real_escape_string($tlname)."','".mysql_real_escape_string($data['teamlead_email'])."','','','tl')";				
			}
		
			$wpdb->query($tlquery);	 
			
			if( is_array($memebersEmailArray) and !empty($memebersEmailArray) )
			{
			    $totalmembers = count($memebersEmailArray);
				 
				
			      for($i=0; $i<$totalmembers; $i++){
				     
					  $memCount = $this->CheckMemberEmail($userid,$memebersEmailArray[$i]); 
				      if($memCount > 0)
					  {
					      $query = "SELECT id From ".$wpdb->prefix."specdoc_userteaminfo Where userid= '".$userid."' AND member_email='".$memebersEmailArray[$i]."'";
			              $user_data = $wpdb->get_results($query);
						  
					     $memQuery = "UPDATE ".$wpdb->prefix."specdoc_userteaminfo SET member_name='".mysql_real_escape_string($memebersNameArray[$i])."',member_email='".mysql_real_escape_string($memebersEmailArray[$i])."' WHERE userid='$userid' AND id='".$user_data[0]->id."'"; 
						 $wpdb->query($memQuery);
					  }
					  else
					  {
					       //echo '<br>'; echo $memebersIdArray[$i]; echo 'else <br>';
					      if($memebersIdArray[$i] != '')
						  {
							 $memQuery = "UPDATE ".$wpdb->prefix."specdoc_userteaminfo SET member_name='".mysql_real_escape_string($memebersNameArray[$i])."',member_email='".mysql_real_escape_string($memebersEmailArray[$i])."' WHERE userid='$userid' AND id='".$memebersIdArray[$i]."'"; 
						     $wpdb->query($memQuery); 
						  }
						  else
						  {
						     $memQuery = "INSERT INTO ".$wpdb->prefix."specdoc_userteaminfo (userid,	member_name,member_email,member_responsibility,member_estimate_effort,	member_type) values(".$userid.",'".mysql_real_escape_string($memebersNameArray[$i])."','".mysql_real_escape_string($memebersEmailArray[$i])."','','','member')"; 
						     $wpdb->query($memQuery);
						  } 
                      }						  
                  }				  
			}
			else
			{ }
			//die;
			// check and enter Member information
			$totalMemberArray = $this->selectTeamInfo($userid);

			foreach($totalMemberArray as $tkey=>$tval){
				//echo "<br>$tkey=>$tval";
				$memberId = $tval['id'];
				$memberType = $tval['member_type'];
				$memberName = $tval['member_name'];
				$memberEmail = $tval['member_email'];
				
				if(count($memebersIdArray) !=0){
					foreach($memebersIdArray as $key=>$val){					
						if($memberId == $val ){  
						   $updatequery = "UPDATE ".$wpdb->prefix."specdoc_userteaminfo SET member_name = '".mysql_real_escape_string($memberName)."' , member_email='".mysql_real_escape_string($memberEmail)."' WHERE id='".$memberId."' AND userid='$userid'";
						  $wpdb->query($updatequery);
						
						}else{
						
						  /* $delquery = "DELETE FROM ".$wpdb->prefix."specdoc_userteaminfo WHERE id='".$memberId."' AND userid='$userid'";
						  $wpdb->query($delquery); */
						  
						  
						}
					}
				}
			}
			  
			 $error = '';
	   
		   if($page == 0) {
			// update status table
			 $updateqry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$userid' AND section_name = 'teaminialinfo' ";
			$wpdb->query($updateqry);  
		   }
		   
		 return $error; 
		}
		
		/* Save user TeamInfo */
		function selectTeamInfo($userid){
			global $wpdb;
			 
			$query = "SELECT id,member_name,member_email,member_responsibility,member_estimate_effort,member_type From ".$wpdb->prefix."specdoc_userteaminfo Where userid= '".$userid."'";
			$result = $wpdb->get_results($query,ARRAY_A);
		 return $result;
		}
		
		/* select user more TeamInfo */
		function selectMoreInfoStatus($userid){
			global $wpdb;
			 $wpdb->query($qry); 
		
			// get the section status value
			$selectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$userid' AND section_name='membermoreinfo'";
			$morestatus = $wpdb->get_col($selectqry); 
		    return $morestatus[0];
		}
		
		
		
		/* Check team member exist */
		//function CheckMemberEmail($userid,$memberemail,$memberid){
		function CheckMemberEmail($userid,$memberemail,$memberid=null){
			global $wpdb;
			 
			/* $query = "SELECT COUNT(*) From ".$wpdb->prefix."specdoc_userteaminfo Where userid= '".$userid."' AND member_email='$memberemail' AND id='$memberid'"; */
			
			$query = "SELECT COUNT(*) From ".$wpdb->prefix."specdoc_userteaminfo Where userid= '".$userid."' AND member_email='$memberemail'";
			$user_count = $wpdb->get_var($query);
			
		 return $user_count;
		}
		
		/* Check team member exist */
		function CheckMemberByEmail($logedinuserid,$memberemail){
			global $wpdb;
			 
			$query = "SELECT id From ".$wpdb->prefix."specdoc_userteaminfo Where userid= '".$logedinuserid."' AND member_email='$memberemail' ";
			$result = $wpdb->get_row($query,ARRAY_A);
			
		    return $result['id'];
			/* $user_count = $wpdb->get_var($query);
			
		 return $user_count; */
		}
		
		/* get teamlead info */
		function selectTeamLeadInfo($userid){
			global $wpdb;
			 
			$query = "SELECT id,member_name,member_email,member_responsibility,member_estimate_effort,member_type From ".$wpdb->prefix."specdoc_userteaminfo Where userid= '".$userid."' AND member_type='tl'";
			$result = $wpdb->get_results($query,ARRAY_A);
		 return $result;
		}
		
		/* get Member info */
		function selectTeamMemberInfo($userid){
			global $wpdb;
			 
			$query = "SELECT id,member_name,member_email,member_responsibility,member_estimate_effort,member_type From ".$wpdb->prefix."specdoc_userteaminfo Where userid= '".$userid."' AND member_type='member'";
			$result = $wpdb->get_results($query,ARRAY_A);
		 return $result;
		}
		
		
		/* Save user More Information */
		function saveTeamMoreInfo($data,$userid){
			global $wpdb;			

			$this->updateLastEditDate( $userid );
			//update Team lead information
			$tlquery = "UPDATE ".$wpdb->prefix."specdoc_userteaminfo SET member_estimate_effort = '".$data['teamlead_effort']."',member_responsibility ='".htmlspecialchars($data['teamleadresp'], ENT_QUOTES | ENT_HTML5, 'UTF-8')."' WHERE userid ='".$userid."' AND id='".$data['tlid']."'" ;
			
			$wpdb->query($tlquery);
			
			//update Member information
			$memebersRespArray = $data['memberresp'];
			$memebersEffortArray = $data['membereffort'];
			
			if( is_array($memebersRespArray) and !empty($memebersRespArray) ) { 
				foreach($memebersRespArray as $key=>$val){
					
					$query = "UPDATE ".$wpdb->prefix."specdoc_userteaminfo SET member_estimate_effort = '".$memebersEffortArray[$key]."',member_responsibility ='".htmlspecialchars($val, ENT_QUOTES | ENT_HTML5, 'UTF-8')."' WHERE userid ='".$userid."' AND id='".$key."'" ;
					
					$wpdb->query($query);
				}
			}
			
			$qry = "update ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' Where userid='$userid' AND section_name = 'membermoreinfo' ";
			 $wpdb->query($qry); 
			
			
			return true;
		}
		
		/** checking functional area statuses 
		0 - unknown
		1 - complete
		2 - not required
		3 - in scope
		**/	
	function checkFaStatus($loggedin_id){
	   
		   $catres = $this->GetToolData($loggedin_id);
		   
		   if($catres)
			{
				$k=0;
				$unknown = 0;
				$complete = 0;
				$inscope = 0;
				$notrequired = 0;
				
				
				for($i=0;$i<sizeof($catres);$i++)
				{
					
						$k++;
						if($catres[$i][CATEGORY_STATUS]==0)
							$unknown++;
						else if($catres[$i][CATEGORY_STATUS]==1)
							$complete++;
						else if($catres[$i][CATEGORY_STATUS]==2)
							$notrequired++;
						else if($catres[$i][CATEGORY_STATUS]==3)
							$inscope++;
					
				}
			}
			
		return $unknown;
	}
	
	
	function checkFunaStatusCustom($loggedin_id){
	   
		   $catres = $this->GetToolData($loggedin_id);
		   $FAArray = array();
		   
		   if($catres)
			{
				$k=0;
				$unknown = 0;
				$complete = 0;
				$inscope = 0;
				$notrequired = 0;
				
				
				for($i=0;$i<sizeof($catres);$i++)
				{   
				   //echo '<br>'.$catres[$i][CATEGORY_ID]."--".$catres[$i][CATEGORY_STATUS];
					
						$k++;
						if($catres[$i][CATEGORY_STATUS]==0)
							$unknown++;
						else if($catres[$i][CATEGORY_STATUS]==1)
							$complete++;
						else if($catres[$i][CATEGORY_STATUS]==2)
							$notrequired++;
						else if($catres[$i][CATEGORY_STATUS]==3)
							$inscope++; 
				}
				
				$FAArray[]['unknown'] = $unknown;
				$FAArray[]['totalFA'] = $k;
				$FAArray[]['totalInscope'] = $inscope;
				$FAArray[]['totalNotrequired'] = $notrequired;
				$FAArray[]['totalComplete'] = $complete;
			}
			
		return $FAArray;
	}
		
	/** get functional area statuses 
		0 - unknown
		1 - complete
		2 - not required
		3 - in scope
		**/	
	function checkFunaStatus($loggedin_id){
	   
		   $catres = $this->GetToolData($loggedin_id);
		   
		   if($catres)
			{
				$k=0;
				$unknown = 0;
				$complete = 0;
				$inscope = 0;
				$notrequired = 0;
				
				
				for($i=0;$i<sizeof($catres);$i++)
				{   
				   //echo '<br>'.$catres[$i][CATEGORY_ID]."--".$catres[$i][CATEGORY_STATUS];
					
						$k++;
						if($catres[$i][CATEGORY_STATUS]==0)
							$unknown++;
						else if($catres[$i][CATEGORY_STATUS]==1)
							$complete++;
						else if($catres[$i][CATEGORY_STATUS]==2)
							$notrequired++;
						else if($catres[$i][CATEGORY_STATUS]==3)
							$inscope++;
					
				}
			}
		$string = "t: ".$k."/ i : ".$inscope."/ o : ".$notrequired."/ c :".$complete;
		return $string;
	}
	
		
	
	/** compare functional area total count with sum of different statuses 
		0 - unknown
		1 - complete
		2 - not required
		3 - in scope
		**/	
	function checkFunaStatuscount($loggedin_id){
	   
		   $catres = $this->GetToolData($loggedin_id);
		   
		   if($catres)
			{
				$k=0;
				$unknown = 0;
				$complete = 0;
				$inscope = 0;
				$notrequired = 0;

				for($i=0;$i<sizeof($catres);$i++)
				{
					
						$k++;
						if($catres[$i][CATEGORY_STATUS]==0)
							$unknown++;
						else if($catres[$i][CATEGORY_STATUS]==1)
							$complete++;
						else if($catres[$i][CATEGORY_STATUS]==2)
							$notrequired++;
						else if($catres[$i][CATEGORY_STATUS]==3)
							$inscope++;
					
				}
			}
			
			if($k == ($inscope+$notrequired+$complete)){
				$result = 1;
			}else{
				$result = 0;
			}
		
		return $result;
	}
	
	/** functional area statuses 
		0 - unknown
		1 - complete
		2 - not required
		3 - in scope
		**/	
	/** get the id of functional area whose line item is zero **/
	function getOneFAInscope($loggedin_id){
	   
		   $catres = $this->GetToolData($loggedin_id);
		   $firstFANotComplete = 0;
		   
		   if($catres)
			{
				$k=0;
				$unknown = 0;
				$complete = 0;
				$inscope = 0;
				$notrequired = 0; 
				
				for($i=0;$i<sizeof($catres);$i++)
				{   
				   
						if($catres[$i][CATEGORY_STATUS]==3){ 
							
							$firstFANotComplete = $catres[$i][CATEGORY_ID];
							break;
							if($catres[$i][CATEGORY_LINEITEMS])
								{
									$roi_line=0;
									$status_cnt=""; 
									$not_cnt="";
									$incomplete_cnt="";
									$lineitemcnt=0;
									foreach($catres[$i][CATEGORY_LINEITEMS] as $lineitem)
									{  
										
										 if($lineitem[LINEITEM_STATUS]==1)
											$status_cnt++;
										if($lineitem[LINEITEM_STATUS]==2)
											$not_cnt++;
										if($lineitem[LINEITEM_STATUS]==0)
											$incomplete_cnt++;
										$lineitemcnt++;									  
									}
								}
								 
								/*echo "<br>$status_cnt -not - $not_cnt - incomp: - $incomplete_cnt - line count - $lineitemcnt";
							    $display=""; */
							
							if($incomplete_cnt==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==0)
								$display="?";
							else if($incomplete_cnt==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==3)
								$display="?";
							else if(($incomplete_cnt+$not_cnt)==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==0)
								$display="0";
							else if(($incomplete_cnt+$not_cnt)==$lineitemcnt && $catres[$i][CATEGORY_STATUS]== 3){							 
								$display="0";
							}else if($not_cnt==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==1)
								$display="0";
							else if($not_cnt==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==0)
								$display="0";
							else if($not_cnt==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==2)
								$display="0";
							else if($not_cnt==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==3)
								$display="0";
							else if($status_cnt=="" && $catres[$i][CATEGORY_STATUS]==2)
								$display="-";
							else
								$display = $status_cnt;	
							$cat_id = $catres[$i][CATEGORY_ID];
							 // echo "<br>hi:".$cat_id.'-display-'.$display;
							if($display == "0" || $display== '?'){		
								$k=1;
								//break;
							}
							
						}
			   } 	
	     }
		    if($firstFANotComplete > 0){ 
			      return $firstFANotComplete;		
			 }
            else {
                  return 0;	  
             }	
	}

/* **************
*	Function to store the record of downloaded doc in database, these info will use in admin side to chek the reports under 'user & payment' page
*   Written by Royal Tyagi at 28-Jan-15.
*
************* */

	function saveDownloadHistory ( $userID, $fileName ) {

		global $wpdb;

		$count = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "specdoc_downloadedDoc_records WHERE user_id = '" . $userID . "' AND docName = '" . $fileName . "'");

		if ( $count == 0 ) {

			$sql = $wpdb->query("INSERT INTO " . $wpdb->prefix . "specdoc_downloadedDoc_records SET user_id = '" . $userID . "', docName = '" . $fileName . "', 	firstDownload	 = NOW(), lastDownload = NOW(), count = '1'");

		} else {

			$sql = $wpdb->query("UPDATE " . $wpdb->prefix . "specdoc_downloadedDoc_records SET lastDownload = NOW(), count = count+1 WHERE user_id = '" . $userID . "' AND docName = '" . $fileName . "'");

		}
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

/* **********************
* 	Function for make the Invite code for EOI functionality for verndors
*   Written by Royal Tyagi at 25-Feb-15.
******************** */

	function GetInviteCode(){
	   global $wpdb;
		$length = 15;
		$inviteCode = "";
		$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		for ($p = 0; $p < $length; $p++) {
		    $inviteCode .= $characters[mt_rand(0, strlen($characters))];
		}
		return $inviteCode;
	}

	function sendInviteCodeEmail($loggedin_id, $inviteCode, $vendorEmail) {

		$data = array(
			'email' 	=> 	$vendorEmail,
			'inivtationCode'		=> $inviteCode
		);

		$key = base64_encode(serialize($data));
		$url = get_site_url().'?act=eoi_res&VD='.$key;
	
		$headers = "From:  ".get_bloginfo('name')."<".get_bloginfo('admin_email').">"."\r\n";
		$headers .= "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";

		$subject = 'EOI Response (Invitaion code)';

		$message = "";
		$message .= "<html><body>";
		$message .= "<div style='font:Arial;color: #080e1a;'>Hi,<br>". "\r\n<br>";
		$message .=  get_user_meta($loggedin_id, "first_name", true) . " invited you to fill the EOI Response Form of Project. If you are existing user then do login othterwise you sholld have to do register first.". "\r\n<br>";
		$message .=  "<a href=". $url ." target='_blank'>Click here</a> to do login or registeration". "\r\n<br><br>";
		$message .=  "Here are your invitation code <b>". $inviteCode . "</b>\r\n<br><br><br>";

		$message .= "Thanks & Regards,<br>";
		$message .= get_bloginfo('name')."</div>";
		$message .= "</html></body>";

	    wp_mail($vendorEmail, $subject, $message, $headers);
		    
		//return $message;
	}

	function SaveVendorEmail( $vendorEmail, $userId, $inviteCode ) {

		global $wpdb;

		$query = $wpdb->query("INSERT INTO " . $wpdb->prefix . "eoiresponse SET project_holder_id = '" . $userId . "', vendor_email = '" . $vendorEmail . "', invite_code = '" . $inviteCode . "'");

	}

	function isValidCombinationOfEmailNcode( $vendorEmail, $inviteCode ) {
		global $wpdb;

		$query = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "eoiresponse WHERE vendor_email = '" . $vendorEmail . "' AND invite_code = '" . $inviteCode . "'" );
		return $query;
	}

	function ActiveEOI( $profileinfo, $userid ) {
		global $wpdb;
		$invitation_code = $_POST["invitation_code"];

		/*   get eoi id based on the invitation code  */
		$eoi_access_id = $wpdb->get_var( $wpdb->prepare(
				"
				SELECT eoi_id
				FROM wp_eoiresponse
				WHERE invite_code = %s
				",
			$invitation_code
		) );
		$wpdb->update(
			'wp_eoiresponse',
			array(
				'user_id' => $userid,	// update the user id to the EOI form who could access
				'eoi_complete' => 2
			),
			array(
				'eoi_id' => $eoi_access_id
			),
			array(
				'%d',	// user_id
				'%d'  // eoi_complete
			),
			array(
				'%d'    //eoi_id
			)
		);

		$query = $wpdb->query("UPDATE " . $wpdb->prefix . "eoiresponse SET is_used = 1 WHERE eoi_id='" . $eoi_access_id . "'");

            /*   create new team member when the vendors enter the invitation code  */
             for ($team_member=0;$team_member<5;$team_member++) {
			    $wpdb->insert(
			        'wp_teammember',
			        array(
			            'eoi_id' => $eoi_access_id        //insert eoi_id
			             ),
			        array(
			            '%d'      //eoi_id
			        )
			    );
            }

            /*   create new functional areas when the vendors enter the invitation code  */
             $tool_data = "";
             $project_holder_id = $wpdb->get_var( $wpdb->prepare(
                "
                 SELECT project_holder_id
                 FROM wp_eoiresponse
                 WHERE invite_code = %s
                ",
                $invitation_code
             ) );
            $tool_data = $wpdb->get_var( $wpdb->prepare(
                "
                 SELECT meta_value
                 FROM wp_usermeta
                 WHERE user_id = %d
                 AND meta_key = %s

                ",
                $project_holder_id,
                "tool_data"
            ) );
            $enabled_number = 0;
            $func_areas_tools= getCategories();
            foreach ($func_areas_tools as $func_areas_tool) {
                if ($func_areas_tool->enabled == 1)
                {   $enabled_number++;
                }
            }
            $tool_data_process = strchr($tool_data,"i:0;a:7:");
            $tool_data = explode("}}}",$tool_data_process);
            $tool_data_array = array();
            for ($tool_m=0;$tool_m<$enabled_number;$tool_m++)
            {
                if (strstr($tool_data[$tool_m],'i:3;s:1:"3";',false) == False)
                {
                    $tool_data_array[$tool_m] = 0;
                }
                else {$tool_data_array[$tool_m] = 1;}
            }
             $func_area_m = 0;
             $func_areas_inserts= getCategories();
             foreach ($func_areas_inserts as $func_areas_insert) {
                 if ($func_areas_insert->enabled == 1)
                 {   $func_area_name = $func_areas_insert->name;
                     $func_category_id = $func_areas_insert->category_id;
                     $wpdb->insert(
                           'wp_functionalareas',
                        array(
                            'eoi_id' => $eoi_access_id,        //insert eoi_id
                            'func_name' => $func_area_name,    //insert func_name
                            'func_category_id' => $func_category_id,   //insert func_category_id
                            'func_area_inscope' => $tool_data_array[$func_area_m]   //insert if a user put this functional are in scope
                            ),
                        array(
                             '%d',      //eoi_id
                             '%s',      //func_name
                             '%d',      //func_category_id
                             '%d'       //func_area_inscope
                            )
                     );
                     $func_area_m++;
                 }
             }
             return $profileinfo;

	}

	function SaveUserEoi( $profileinfo, $eoi_id, $status ) {
		global $wpdb;

        if ($status == "Complete") {
            /* Save EOI complete status in database eoiresponse */
            $wpdb->update(
                'wp_eoiresponse',
                array(
                    'eoi_complete' => 1  //insert eoi_complete
                ),
                array('eoi_id' => $eoi_id),        //where eoi id equals to $eoi_id
                array(
                    '%d'     //eoi_complete
                ),
                array('%d')  //$eoi_id
            );
            $complete_date = getdate();
            $day = $complete_date[mday];
            $month = $complete_date[mon];
            $year = $complete_date[year];
            $wpdb->update(
                'wp_eoiresponse',
                array(
                    'complete_date' => $day.'/'.$month.'/'.$year  //insert eoi_complete
                ),
                array('eoi_id' => $eoi_id),        //where eoi id equals to $eoi_id
                array(
                    '%s'     //eoi_complete
                ),
                array('%d')  //$eoi_id
            );
        } else {
            $wpdb->update(
                'wp_eoiresponse',
                array(
                    'eoi_complete' => 2  //insert eoi_complete
                ),
                array('eoi_id' => $eoi_id),        //where eoi id equals to $eoi_id
                array(
                    '%d'     //eoi_complete
                ),
                array('%d')  //$eoi_id
            );

        }

        /* Save EOI data in database eoiresponse */

		$query = $wpdb->query("UPDATE " . $wpdb->prefix . "eoiresponse SET org_name = '" . mysql_real_escape_string($profileinfo["org_name"]) . "', your_name = '" . mysql_real_escape_string($profileinfo["your_name"]) . "', your_system_name = '" . mysql_real_escape_string($profileinfo["your_system_name"]) . "', about_your_org = '" . mysql_real_escape_string($profileinfo["about_your_org"]) . "', timeline_low = '" . (int)$profileinfo["timeline_low"] . "', timeline_high = '" . (int)$profileinfo["timeline_high"] . "', fee_low = '" . (int)$profileinfo["fee_low"] . "', fee_high = '" . (int)$profileinfo["fee_high"] . "', add_notes_cred = '" . $profileinfo["add_notes_cred"] . "' WHERE eoi_id = '" . $eoi_id . "' ");

		 /* Save EOI data in database teammember */
		 
		foreach ( $profileinfo['members'] as $k => $v) {

			$query = $wpdb->query("UPDATE " . $wpdb->prefix . "teammember SET team_mem_name= '". mysql_real_escape_string($v['name']) ."', position = '". mysql_real_escape_string($v['position']) ."', project_role = '". mysql_real_escape_string($v['project_role']) ."', contact_details = '". (int)$v['contact_details'] ."', comments = '". mysql_real_escape_string($v['comments']) ."' WHERE teamtable_id = '" . $k . "'");

		}

		 /* Save EOI data in database functionalareas */

		 foreach( $profileinfo['functions'] as $k => $v ) {

			$query = $wpdb->query("UPDATE " . $wpdb->prefix . "functionalareas SET complies_status = '" . $v['status'] . "', notes = '" . $v['notes'] . "' WHERE functional_areas_id = '" . $k . "' ");

		 }

	}

/*
* 	Function to cross check the email address that any another user ddidn't registered with same email address with another role excepting vendor.
*/	

	function checkEmailIAlreadyUsedByAnyAnotherUser( $email ) {
		global $wpdb;

		$query = $wpdb->get_col("SELECT ID FROM " . $wpdb->prefix . "users WHERE user_email = '" . $email . "'");

		if (!empty($query)) {
			$user = new WP_User( $query[0] );
			$role = $user->roles[0];

			if ( $role != 'vendor' ) {
				$results = 'Invalid';
			} else {
				$results = 'valid';
			}
		} else {
			$results = 'valid';
		}

		return $results;
	}
}
?>
