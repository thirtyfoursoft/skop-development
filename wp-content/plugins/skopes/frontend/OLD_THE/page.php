 <?php
	/*
	*   Name: Twisha Patel  Date: 14th Aug 2013
	*
	*   Login and register page funtionality
	*
	*/

	$objUser = new clsSkopes();
	$page = get_page_link(home_url());
	$loggedin_id = get_current_user_id();

	if ( is_user_logged_in() ) { 
		include('include/vendor-screen-access.php');
	}

	/* if get activation key the check and activate uer account */
	if( isset($_GET["key"]) && $_GET["key"] != "" ) {
		if( $objUser->ActivateUser($_GET["key"]) ) {
			wp_redirect($page."?msg=activated");
			exit;
		} else {
			wp_safe_redirect($page."?msg=erractivate",301);
			exit;
		}
	}
 
	/* register user / login / forgot password */
	if(isset($_POST["hiddenaction"]))
	{
		$k = $_POST["hiddenaction"];
		switch ($k) {
		case "RegisterMe":

		if( strlen($_POST["password"]) < 6 ) {
		  wp_redirect($page."?type=plength");
		}
			elseif($_POST["companyname"]!="" && $_POST["firstname"]!="" && $_POST["lastname"]!="" && $_POST["email"]!="" && $_POST["password"]!="" && $_POST["cpassword"]!="" ) {

				if (isset($_POST['invitaionCode']) && $_POST['invitaionCode'] != '' ) {
					$isValidCombination = $objUser->isValidCombinationOfEmailNcode( $_POST['email'], $_POST['invitaionCode'] );

					$vendorData = unserialize(base64_decode($_GET['VD']));

					if ( $isValidCombination == 0 || $_POST['email'] != $vendorData['email'] || $_POST['invitaionCode'] != $vendorData['inivtationCode'] ) { // Condition for manage the vendors registration forms.
						$cuurentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
						$cuurentURL = rtrim($cuurentURL,"/");
						$url = $cuurentURL.'&msg=wrongcoombination';
						wp_redirect($url);
						exit();
					}
				}
				
				if(is_int($userid = $objUser->SaveUser($_POST))) {

					$headers = "From:  ".get_bloginfo('name')."<".get_bloginfo('admin_email').">"."\r\n";
					$headers .= "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";

					$message = "";
					if(get_option("confirm_email_sub"))
						$sub = get_option("confirm_email_sub");
					else
						$sub = "Activate your account on ".get_bloginfo('url');

					$userinfo = get_userdata( $userid );

					$act_key = get_user_meta($userid,'activation_key',true);

					$message = "<div style='font:Arial;color: #080e1a;'><b>Dear ".$userinfo->display_name.",</b><br><br>";
					if(get_option("confirm_email_content")) {
						$message.=get_option("confirm_email_content");
					} else {
						$message .= "Thank you for registering on <b>".get_bloginfo('name')."</b><br><br>&nbsp;";
					}

					$message .= "Click <a href='".$page."?key=".$act_key."'><b>Here</b></a> to activate your account<br><br>";

					$message .= "<b>Thanks & Regards,<br>";
					$message .= get_bloginfo('name')."</b></div>";
					wp_mail($userinfo->user_email,$sub,$message,$headers);
					wp_redirect($page."?type=register_success");
					exit;
				} else {
				   $_GET["msg"]="invalid";
				   $errmsg=$userid;
				}
			}
			break;
		case "Login":
			if($_POST["email"]!="" && $_POST["password"]!="")
			{
				if($_POST['rememberme'])
					$_POST['rememberme']=1;
				else
					$_POST['rememberme']=0;

				if($objUser->CheckUserLogin($_POST["email"],$_POST["password"],$_POST['rememberme'])) {
					if($_POST['rememberme']==1) {
						setcookie("email_cookie",$_POST["email"],time()+3600*24*60);
						setcookie("pass_cookie",$_POST["password"],time()+3600*24*60);
						setcookie("rememberme_cookie",$_POST["rememberme"],time()+3600*24*60);
					} else {
					   setcookie("email_cookie","",time()-3600);
					   setcookie("pass_cookie","",time()-3600);
					   setcookie("rememberme_cookie","",time()-3600);
					}
					
					$cuurentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					$cuurentURL = rtrim($cuurentURL,"/");
					wp_redirect($cuurentURL);
					//wp_redirect($page);
					exit;
				} else {
					wp_redirect($page."?type=fail");
					exit;
				}
			}
			break;
			case 'ForgotPassword':
				if($_POST["email"]!="")
			{
				if($objUser->CheckEmail($_POST["email"])) {
					$userinfo=$objUser->getUserDetail($_POST["email"]);
					$pwd = $objUser->GenerateNewPassword($userinfo->ID);
					$sub = "Your new password on ".get_bloginfo('name');
					$message="";
					$message = "<div style='font:Arial;color: #080e1a;'><b>Dear ".$userinfo->display_name.",</b><br><br>";
					$message .= "Your New Password on <b>".get_bloginfo('name')."</b><br><br>";
					$message .= "<b>Password : ".$pwd." </b><br><br>";
					$message .= "Click <a href='".$page."'><b>Here</b></a> to login<br><br>";
					$message .= "<b>Thanks & Regards,<br>";
					$message .= get_bloginfo('name')."</b></div>";
					$headers = "From:  ".get_bloginfo('name')."<".get_bloginfo('admin_email').">"."\r\n";
					$headers .= "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
					wp_mail($userinfo->user_email,$sub,$message,$headers);
					wp_redirect($page."?msg=sent");
					exit;
				} else {
					wp_redirect($page."?type=invalidemail");
					exit;
				}
			}
			break;
			case 'SaveProfile':
				unset($_POST["hiddenaction"]);
				if($objUser->SaveUserProfile($_POST,$loggedin_id)) {
					wp_redirect($page);
					exit;
				}
			break;
			case 'SaveProject':
				unset($_POST["hiddenaction"]);			
				if($objUser->SaveUserProject($_POST,$loggedin_id)) {
					wp_redirect($page);
					exit;
				} 
			break;
			case 'SaveCompany':
				if(!empty($_FILES['com_logo']['name'])){
				// upload company logo
				$uploadfiles = $_FILES['com_logo'];

				$filename = $filetmp = $uploadfiles['name'];
				$filetmp = $uploadfiles['tmp_name'];		   
				$filetype = wp_check_filetype( basename( $filename ), null );			
				$filetitle = preg_replace('/\.[^.]+$/', '', basename( $filename ) );
				$filename = $filetitle . '.' . $filetype['ext'];
				$upload_dir = wp_upload_dir();			
				$upload_dirpath = $upload_dir['basedir'] .'/companylogos/'.$loggedin_id;
				if (!is_dir($upload_dirpath)) {
					mkdir($upload_dirpath, 0777, true);
				}
				$filedest = $upload_dirpath . '/' . $filename;

				 move_uploaded_file($filetmp, $filedest); // fiel uploading done
				 $objUser->createThumbnail($filedest,$loggedin_id);

				 $_POST["com_logo"] = $filename;
				 unset($_POST["logoval"]);
				} else {
				 $_POST["com_logo"] = $_POST["logoval"];
				}

				unset($_POST["hiddenaction"]);			
				if($objUser->SaveUserCompany($_POST,$loggedin_id)){
					wp_redirect($page.'?act=aboutcompany');
					exit;
				}
			break;
			case 'saveshlist':
				unset($_POST["hiddenaction"]);			
				if($objUser->saveStakeholders($_POST,$loggedin_id))	{
					wp_redirect($page);
					exit;
				}
			
			break;
			case 'saveanbenefitlist':
				unset($_POST["hiddenaction"]);			
				if($objUser->saveAnticipatedBenefits($_POST,$loggedin_id)) {
					wp_redirect($page);
					exit;
				}
			break;

	        case "eoi_response":
		        unset($_POST["hiddenaction"]);
		        $eoi_id = $_POST["eoi_res_id"];
		        $eoi_submit = $_POST["eoi_submit"];
				$objUser->SaveUserEoi($_POST, $eoi_id,$eoi_submit );
				
				$cuurentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$cuurentURL = rtrim($cuurentURL,"/");
				$url = $cuurentURL.'&msg=updated';
				wp_redirect($url);
				exit();

		    break;
		
			case "eoi_email":

				unset($_POST["hiddenaction"]);
				$data = $_POST['vendor_email'];
				if (!empty($data)) {

					$invalidEmails = array();
					foreach ( $data as $vendorEmail ) {

						$isEmailValid = $objUser->checkEmailIAlreadyUsedByAnyAnotherUser($vendorEmail);

						if ($isEmailValid == 'valid') {
					
							$inviteCode = $objUser->GetInviteCode();
							$objUser->SaveVendorEmail( $vendorEmail, $loggedin_id, $inviteCode );
							$message = $objUser->sendInviteCodeEmail( $loggedin_id, $inviteCode, $vendorEmail );

						} else {
							$invalidEmails[] = $vendorEmail;
						}
					}
					$cuurentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					$cuurentURL = rtrim($cuurentURL,"/");

					if (!empty($invalidEmails)) {
						$message = implode(",",$invalidEmails);
						$message = base64_encode($message);
						$url = $cuurentURL.'&ine='.$message;
					} else {
						$url = $page.'&msg=updated';
					}

		            wp_redirect($url);
		            exit();
				}
			break;

		    case "eoi_access":
		        unset($_POST["hiddenaction"]);
			
		        $query = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "eoiresponse WHERE invite_code = '" . $_POST['invitation_code'] . "' AND is_used = 0");

		        if ($query > 0) {
		        
					$objUser->ActiveEOI($_POST, $loggedin_id);
					$url = $page.'?act=eoi_res&msg=updated';
					wp_redirect($url);
					exit();
					
		        } else {
					//$url = $cuurentURL.'&msg=error';
					$url = $page.'?act=eoi_res&msg=error';
					wp_redirect($url);
					exit();
		        }

		    break;

		}
	}
  
if ( is_user_logged_in() )
{
	if(get_user_meta($loggedin_id,'userprofile',true)==1)
	{
		if(isset($_REQUEST["act"]))
		{

		  $i = $_REQUEST["act"];
			switch ($i) {
			case "project_pinch":

				$product_key = 'stage1word';
				include("include/block-stage-one-pdf.php");
				$objUser->saveDownloadHistory( $loggedin_id, 'Project_Rationale.docx' );
				include("wordtemplate/files/codefiles/project_pinch.php");
				break;
			case "eoi":

				$product_key = 'stage1word';
				include("include/block-stage-one-pdf.php");
				$objUser->saveDownloadHistory( $loggedin_id, 'Expression_of_Interest.docx' );
				include("wordtemplate/files/codefiles/EOI_Report.php");
				break;
			case "eoi_response":

				$product_key = 'stage1word';
				include("include/block-stage-one-pdf.php");
				$objUser->saveDownloadHistory( $loggedin_id, 'EOI_Response.docx' );
				include("wordtemplate/files/codefiles/EOI_Response.php");
				break;
			case "project_detail":

				$product_key = 'stage2DocsWord';
				include("include/block-stage-one-pdf.php");
				$objUser->saveDownloadHistory( $loggedin_id, 'Charter_Scope.docx' );
				include("wordtemplate/files/codefiles/project_detail.php");
				break;
			case "rfq":

				$product_key = 'stage2DocsWord';
				include("include/block-stage-one-pdf.php");
				$objUser->saveDownloadHistory( $loggedin_id, 'Request_for_Quotation.docx' );
				include("wordtemplate/files/codefiles/RFQ.php");
				break;
			case "rfq_response":

				$product_key = 'stage2DocsWord';
				include("include/block-stage-one-pdf.php");
				$objUser->saveDownloadHistory( $loggedin_id, 'RFQ_Response.docx' );
				include("wordtemplate/files/codefiles/RFQ_Response.php");
				break;
			case "project_pinchpdf":

				$product_key = 'stage1pdf';
				include("include/block-stage-one-pdf.php");
				$objUser->saveDownloadHistory( $loggedin_id, 'Project_Rationale.pdf' );
				include("wordtemplate/files/codefiles/project_pinchpdf.php");
				break;
			case "eoipdf":

				$product_key = 'stage1pdf';
				include("include/block-stage-one-pdf.php");
				$objUser->saveDownloadHistory( $loggedin_id, 'Expression_of_Interest.pdf' );
				include("wordtemplate/files/codefiles/eoipdf.php");			
				break;
				
			case "eoi_responsepdf":

				$product_key = 'stage1pdf';
				include("include/block-stage-one-pdf.php");
				$objUser->saveDownloadHistory( $loggedin_id, 'EOI_Response.pdf' );
				include("wordtemplate/files/codefiles/eoi_responsepdf.php");			
				break;
				
			case "project_detailpdf":

				$product_key = 'stage2DocsPdf';
				include("include/block-stage-one-pdf.php");
				$objUser->saveDownloadHistory( $loggedin_id, 'Charter_Scope.pdf' );
				include("wordtemplate/files/codefiles/project_detailpdf.php");			
				break;
				
			case "rfq_pdf":

				$product_key = 'stage2DocsPdf';
				include("include/block-stage-one-pdf.php");
				$objUser->saveDownloadHistory( $loggedin_id, 'Request_for_Quotation.pdf' );
				include("wordtemplate/files/codefiles/rfq_pdf.php");
				break;
			case "rfq_responsepdf":

				$product_key = 'stage2DocsPdf';
				include("include/block-stage-one-pdf.php");
				$objUser->saveDownloadHistory( $loggedin_id, 'RFQ_Response.pdf' );
				include("wordtemplate/files/codefiles/rfq_responsepdf.php");
				break;
				
			}
		}
	}  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Skop.es - Digital Project Scoping Tool</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
	<meta name="description" content="" />
	<meta name="author" content="" />

	<!-- stylesheet -->
	<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/css/bootstrap.css" rel="stylesheet" media="screen" />
	<?php 
		$choosenTheme = get_option("selected_theme");
	if( $choosenTheme == 1 ) { ?>
		<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/css/style.css" rel="stylesheet" media="screen" />
	<?php } else { ?>
		<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/css/whiteLabel/style.css" rel="stylesheet" media="screen" />
	<?php } ?>
	<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/css/morestyle.css" rel="stylesheet" media="screen" />
	<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/css/selectbox.css" rel="stylesheet" media="screen" />
	<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/css/bootstrap-responsive.css" rel="stylesheet" media="screen" />
	<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/css/font-awesome.css" rel="stylesheet" media="screen" />
	<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/css/jquery.mCustomScrollbar.css" rel="stylesheet" media="screen" />
	
	<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/jquery-1.11.0.js"></script>
	<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/jquery-migrate-1.2.1.js"></script>
	
	<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/custom.js"></script>
	<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/custom1.js"></script>
	
	<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/jquery.placeholder.js"></script>
	<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/jquery.mCustomScrollbar.min.js"></script>
	
	<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/html5.js"></script>
	<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/selectbox.js"></script>

	<!-- favicon and touch icons -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/apple-touch-icon-144.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/apple-touch-icon-114.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/apple-touch-icon-72.png" />
	<link rel="apple-touch-icon-precomposed" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/apple-touch-icon.png" />
	<link rel="shortcut icon" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/favicon.png" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/favicon.ico" /> 

	<!--  Add css & js files only for confirm box on pop up that will come only on  FUNCTIONAL AREA page -->
	<?php if( isset($_REQUEST['act']) and $_REQUEST['act'] == 'features' ) { ?>
	    <script src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/jquery.confirm.js"></script>
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
		<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>

	<?php } ?>
</head>
<?php
	if ($i == 'aboutproject' || $i == 'moreinfo') {
		$id = 'doScrollBar';
	}
?>
<body <?php body_class( $class ); ?> id="<?=$id; ?>">
	<input type="hidden" id="path" value="<?php echo RC_TC_PLUGIN_URL ?>frontend"/>
	
<?php 
include(RC_TC_BASE_DIR."/frontend/core/class_userinfo.php");
$obj = new userinfo();
$totalCompanyQues = $obj->cquestioncount();
//$userCompanyQues = $obj->usercquestioncount($loggedin_id);
$userCompanyQues = $obj->usercquestioncount_custom($loggedin_id);
$totalProjectQues = $obj->projectpagecount();
$userProjectQues = $obj->projectpageusercount($loggedin_id);
$userProjectMore = $obj->projectmoreCount($loggedin_id);
$userFaDisplay = $objUser->checkFunaStatus($loggedin_id);
$userFaCount = $objUser->checkFunaStatuscount($loggedin_id);

$result1 = $obj->getuserstage($totalCompanyQues,$userCompanyQues,$totalProjectQues,$userProjectQues,$userFaCount);
$stage = ($result1==3 ? 'two': 'one');

update_user_meta( $loggedin_id, 'skope_user_stage', $stage );

?>
<input type="hidden" name="popupIDT" id="popupID2" value="0" />
<div class="overlay"></div>
<div class="popup_overlay"></div>
<div class="popup-section registration" id="popup1">
	<div class="popupbox">
		<a class="closebtn" href="#" id="close_popup1"></a>
		<!-- Start: Box -->
		<div class="box" id="big_popup1"></div>
		<div class="box" id="small_popup1"></div>
		<!-- End: Box -->
	</div>
</div>

	<?php	if( $stage == "one") {  ?>
	          <!-- Start: Popup  -->
				<div class="overlay"></div>
				<div class="popup_overlay"></div>
				<div class="popup-section registration" id="popup">
					<div class="popupbox">
						<a class="closebtn" href="#" id="close_popup"></a>
						<!-- Start: Box -->
						<div class="box" id="big_popup"></div>
						<div class="box" id="small_popup"></div>
						<!-- End: Box -->
					</div>
				</div>
			  <!-- End: Popup -->
			  <script>
				   jQuery( document ).ready(function() {
						  jQuery(".overlay").fadeOut(500);
					});
			  </script>
    <?php	}	else {	?>
		 <!-- Start: Popup  -->
		<div class="overlay"></div>
		<div class="popup_overlay"></div>
		<div class="popup-section registration" id="popup">
			<div class="popupbox">
 
				<?php 	/* *** *  Use conditon for FUNCTIONAL AREA page as we have to put the confirm box on close of that popup *******/
					if( isset($_REQUEST['act']) and $_REQUEST['act'] == 'features' ) { ?>
					<a class="closebtn" href="#" id="close_features_popup" ></a>
				<?php } else { ?>
					<a class="closebtn" href="#" id="close_popup"></a>
				<?php } ?>
				<!-- Start: Box -->

				<div class="box" id="big_popup"></div>
				<div class="box" id="small_popup"></div>
				<!-- End: Box -->
			</div>
		</div>
		<!-- End: Popup -->
		
<?php	} 	?>
		<section id="wrapper" <?php if ( !is_user_logged_in() ) { ?>class="login-page"<?php } else { if(isset($_REQUEST["act"])) { if($_REQUEST["act"]=="myprofile") {?>class="profile-page"<?php } else{ ?>class="ccpage"<?php } } } ?>>

		<div class="loader" style="display:none;"><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/loader.gif" alt="" /></div>
		<div class="popup_loader" style="display:none;"><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/loader.gif" alt="" /></div>

		<?php include("header.php");	?>

		<!-- Start: Middle Section -->
		<section class="middle-section">
		<?php
			$allUrls = array(
				'myprofile'		=> 'userprofile.php',
				'dashboard'		=> 'dashboard.php',
				'getreport'		=> 'generate_report.php',
				'aboutproject'		=> 'about_project.php',
				'features'		=> 'toolfeatures.php',
				'aboutcompany'		=> 'about_company.php',
				'moreinfo'		=> 'morepojectinfo.php',
				'guide'		=> 'guide_interface.php',
				'myaccount'		=> 'myaccount.php',
				'checkout'		=> 'checkout.php',
				'success'		=> 'success.php',
				'change-password'		=> 'change-password.php',
				'eoi_email'		=> 'eoi_email.php',
				'eoi_res'		=> 'eoi_access_control.php'
			);

			if (is_user_logged_in()) {

				if( get_user_meta($loggedin_id,'userprofile',true)==1 ) {
                   
					if(isset($_REQUEST["act"]) && array_key_exists( $_REQUEST["act"], $allUrls ) ) {
						include( $allUrls[$_REQUEST["act"]] );

					} elseif ( isset($_REQUEST["act"]) && $_REQUEST["act"] == 'res_form' ) {

		                $eoi_select_ids = $wpdb->get_results(
		                    "SELECT eoi_id
		                     FROM wp_eoiresponse
		                     WHERE user_id = $loggedin_id"

		                );
		                $eoi_mark = 0;
		                foreach ($eoi_select_ids as $eoi_select_id) {

		                    $page_eoi_id = $eoi_select_id -> eoi_id;
				             if ($_REQUEST["id"] == "$page_eoi_id") {
				             	$eoi_res_id = $page_eoi_id;
				                include("eoi_res.php");
				                 $eoi_mark = 1;
				             }
		                }

		                if ($eoi_mark == 0) {
							wp_redirect($page."?act=guide");
							exit;
		                }

					} else {
						include("guide_interface.php");
					}

				} else {
					include("guide_interface.php");
				?>
					<script type="text/javascript">jQuery(document).ready(function(){jQuery("#wrapper").addClass("profile-page")});</script>
				<?php
				}

			} else {
				include("login_register.php");
			}
		?>
		</section>
		<!-- End: Middle Section -->

		<?php include("footer.php");?>

	</section>

	<?php	if(is_user_logged_in()) {	?>
	
		<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/login_check.js"></script>
		
	<?php	}	?>
</body>
</html>
