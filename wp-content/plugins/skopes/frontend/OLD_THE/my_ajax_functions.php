<?php
include("../../../../wp-config.php");
include("core/class_userinfo.php");
$objUser = new clsSkopes();
$obj = new userinfo();
global $wpdb;
$loggedin_id = get_current_user_id();
$query = $_POST['query'];
switch($query) {
     case 'delete_team_member':
             $memberId = $_POST['memberId'];
             $wpdb->query("DELETE FROM ".$wpdb->prefix."specdoc_userteaminfo WHERE `id` = ".$memberId." ");
           echo 'delete done';
     break;

     case 'delete_risk_block':
             $id = $_POST['blockID'];
             $wpdb->query("DELETE FROM ".$wpdb->prefix."specdoc_riskManagement WHERE `id` = ".$id." ");
           echo 'delete done';
     break;
	 
	 case 'forgotPass':
             $forgotEmailID = $_POST['forgotEmailID'];  
             $checkforEmail = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."users WHERE user_email = '".$forgotEmailID."' ");  
			 $page = home_url();  
			 if( is_array($checkforEmail) and !empty($checkforEmail) ) {
			        $pwd = $objUser->GenerateNewPassword($checkforEmail[0]->ID);
					$sub = "Your new password on ".get_bloginfo('name');
					$message=""; 
					$message = "<div style='font:Arial;color: #080e1a;'><b>Dear ".$checkforEmail[0]->display_name.",</b><br><br>";
					$message .= "Your New Password on <b>".get_bloginfo('name')."</b><br><br>"; 
					$message .= "<b>Password : ".$pwd." </b><br><br>";
					$message .= "Click <a href='".$page."'><b>Here</b></a> to login<br><br>";
					$message .= "<b>Thanks & Regards,<br>"; 
					$message .= get_bloginfo('name')."</b></div>";
					$headers = "From:  ".get_bloginfo('name')."<".get_bloginfo('admin_email').">"."\r\n"; 
					$headers .= "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";

					wp_mail($checkforEmail[0]->user_email,$sub,$message,$headers);
	                echo $page."?msg=sent";

/*					if( wp_mail($checkforEmail[0]->user_email,$sub,$message,$headers) ) {
		                echo $page."?msg=sent";
					} else {
		                echo $page."?msg=notsent";
					}
*/

			 }
			 else
			 {
			    echo 'not_exists';
			 }
     break;

	case 'updateStaticPage': 
			$userID = $_POST['userID'];
			$slug = $_POST['slug'];

			update_user_meta( $userID, $slug, '1' );
			
			echo "success";
	break;
} 
?>
