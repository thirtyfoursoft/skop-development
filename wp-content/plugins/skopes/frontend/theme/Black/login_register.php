<?php
	/*
	*   Name: Twisha Patel  Date: 14th Aug 2013
	*
	*   Login and register page funtionality
	*
	*/
if(isset($_GET["type"])) { ?>
	<input type="hidden" name="popup_msg" id="popup_msg"  value="<?php if($_GET["type"]=="register_success" ||$_GET["type"]=="fail" || $_GET["type"]=="invalidemail" ){ echo $_GET["type"]; } ?>"/>
<?php
}
?>
<style>
	.black_overlay {
		display: none;
		position: fixed;
		top: 0%;
		left: 0%;
		width: 100%;
		height: 100%;
		background-color: black;
		z-index:1001;
		-moz-opacity: 0.8;
		opacity:.80;
		filter: alpha(opacity=80);
	} 
	
	.white_content {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: white;
    border-color: #1f2838 -moz-use-text-color #1f2838 #1f2838;
    border-image: none;
    border-radius: 2px;
    border-style: solid none solid solid;
    display: none;
    min-height: 110px;
    left: 50%;
    margin-left: -155px;
    padding: 16px;
    position: absolute;
    top: 10%;
    width: 310px;
    z-index: 1002;
}

#light h5 {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 10px;
}

#frmfogotpwd2 > input {
    float: left;
    height: 33px;
}

.yes-delete, .yes-deletelogo {
    background: linear-gradient(to bottom, #9368a7 0%, #3f2b49 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);
    border-radius: 3px;
    clear: both;
    color: #fff;
    display: block;
    float: left;
    margin: 0 auto;
    max-width: 100px;
    padding: 0;
    width: 100%;
}

.yes-delete .inner, .yes-deletelogo .inner {
    background-color: #8c61a8;
    border-radius: 3px;
    color: #fff;
    display: table;
    font-size: 14px;
    line-height: 100%;
    margin: 1px;
    max-width: 60%;
    padding: 6px 20px;
    text-align: center;
    text-transform: uppercase;
    width: 100%;
}
	 .closebtn
	 
	 {
	 right: -27px;
    text-indent: -99999px;
    top: -25px;
	 
	 
	 }
	 
	 #show_msg_a {
    bottom: -61px;
    left: 120px;
    position: absolute;
}
</style>
<?php
/* ******************
* Get the vendor email & activation keys from the URL (This will wroks only for vendors or you can say only for URL that system sent to the vendors).
*/

	$vendor = '';
	if ( isset($_GET) && ($_GET['VD'] != '') ) {
		$vendorData = unserialize(base64_decode($_GET['VD']));

		if (empty($vendorData)) {
			$url = get_site_url().'?msg=invalidUrl';
			wp_redirect($url);
			exit();
		}

		$vendor = 1;
		$vandorEmail = $vendorData['email'];
		$inivtationCode = $vendorData['inivtationCode'];

		$_POST["email"] = $vandorEmail;
	}

?>
<div class="content-section clearfix">
	<div class="container">
		<div class="signme-section">
			<?php
				/* Display activation/activation error message */
				$allMsg = array(
					'activated'		=> 'Activation successful!',
					'erractivate'		=> 'Activation Failed!',
					'invalid'		=> 'Email already exists!',
					'sent'		=> 'Your password has been sent on your email, please check your inbox.',
					'invalidemail'		=> 'Email address does not exists on our system, please check you email address.',
					'plength'		=> 'Password must be 6 character long.',
					'invalidUrl'	=> 'Invalid URL please try again',
					'wrongcoombination'	=> 'Please use valid email & invitaion code'
				);

				if( isset($_GET["msg"]) && array_key_exists( $_GET["msg"], $allMsg ) ) {
					echo '<div class="msgbox">'. $allMsg[$_GET["msg"]] .'</div>';
				}
			 ?>
			<div class="register bb-redlight" <?php if($_GET["msg"]=="activated" || $_GET["msg"]=="erractivate") { ?>style="display:none;"<?php }?>>
				<h2>Sign me up!</h2>
				<a href="#" class="button" id="register_link">
					<span class="button-inner">Register</span>
				</a>
				<div class="clear"></div>
			</div>
			<div class="register-content" id="register_block" <?php if($_POST["hiddenaction"]=="RegisterMe") { ?>style="display:block;"<?php }?>>
				<form class="signup-form" method="post" id="frmregister" onsubmit="return fnvalidate_register()">
				<input type="hidden" id="path" value="<?php echo RC_TC_PLUGIN_URL ?>frontend"/>
					<input type="hidden" name="hiddenaction" id="hiddenaction" value="RegisterMe" />
					<input type="submit" name="hbtnregister" id="hbtnregister" style="position: absolute; left: -9999px; width: 1px; height: 1px;" />
					<div>
						<span class="error" id="firstname_error"></span>
						<input type="text" placeholder="First name:" value="<?php echo $_POST["firstname"]; ?>" id="firstname" name="firstname">
					</div>
					<div>
						<span class="error" id="lastname_error"></span>
						<input type="text" placeholder="Last name:" value="<?php echo $_POST["lastname"]; ?>" id="lastname" name="lastname">
					</div>
					<div>
						<span class="error" id="companyname_error"></span>
						<input type="text" placeholder="Company name:" value="<?php echo $_POST["companyname"]; ?>" id="companyname" name="companyname">
					</div>					
					<div> 
						<span class="error" id="email_error"></span>
						<input type="text" placeholder="Email:" value="<?php echo $_POST["email"]; ?>" id="email" name="email">
					</div>
					<div>
						<span class="error" id="password_error"></span>
						<input type="password" placeholder="Password:" value="<?php echo $_POST["password"]; ?>" id="password" name="password" size="100">
					</div>
					<div>
						<span class="error" id="cpassword_error"></span>
						<input type="password" placeholder="Confirm Password:" value="<?php echo $_POST["password"]; ?>" id="cpassword" name="cpassword" size="100">
					</div>
					<?php if ($vendor != ''): ?>
						<div>
							<span class="error" id="invitaionCode_error"></span>
							<input type="text" placeholder="Invitaion Code" value="<?=$inivtationCode; ?>" id="invitaionCode" name="invitaionCode" size="100">
							<input type="hidden" value="vendor" name="userRole" size="100">							
						</div>
					<?php endif; ?>
					<div class="bb-blue">
						<a href="#" class="button" id="register_me">
							<span class="button-inner"><strong>Ok!</strong> Register me</span>
						</a>
					</div>
				</form>
			</div>

			<div class="login bb-purplelight">
				<h2>Sign me in!</h2>
				<a href="#" class="button" id="login_link"><span class="button-inner">Login</span></a>
				<div class="clear"></div>
			</div>
			<div class="register-content"  id="login_block" <?php if($_GET["msg"]=="fail") { ?>style="display:block;"<?php }?>>
				<form class="signup-form" method="post" id="frmlogin" onsubmit="return fnvalidate_login()">
					<input type="hidden" name="hiddenaction" id="hiddenaction" value="Login" />
					<input type="submit" name="hbtnlogin" id="hbtnlogin" style="position: absolute; left: -9999px; width: 1px; height: 1px;" />
					<div>
						<span class="error" id="login_email_error"></span>
						<input type="text" placeholder="Email:" id="login_email" name="email" <?php if(isset($_COOKIE["email_cookie"])){ echo "value='".$_COOKIE["email_cookie"]."'"; }?>>
					</div>
					<div class="marbtm5">
						<span class="error" id="login_password_error"></span>
						<input type="password" size="100" placeholder="Password:" id="login_password" name="password" <?php if(isset($_COOKIE["pass_cookie"])){ echo "value='".$_COOKIE["pass_cookie"]."'"; }?>>
					</div>
					<div class="clearfix">
						<div class="sf-left">
							<input type="checkbox" name="rememberme" id="rememberme" <?php if(isset($_COOKIE["pass_cookie"])){ echo "checked='checked'"; }?>><label>Remember Me</label>
						</div>
						<div class="sf-right">
						    <!--<a href="#" id="forgotpwd_link">Forgot Your Password?</a>-->
							<a href="javascript: void(0);" id="cookieID" onclick="openpopup();">Forgot Your Password?</a>
						</div>  
					</div>
					<div class="bb-blue">
						<a href="#" class="button" id="login_me"><span class="button-inner width188"><strong>GO!</strong></span></a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="light" class="white_content"> 
 <div style="position:relative"><?php
       $plugins_url = plugins_url();
       include('forgotpwd.php');
   ?>   
   <br>
   <a id="close_popup" href="javascript: void(0);" onclick="javascript: document.getElementById('light').style.display = 'none'; document.getElementById('fade').style.display  = 'none';" class="closebtn" style="display: block;">Close</a> 
   </div>
</div>
<div id="fade" class="black_overlay"></div>
