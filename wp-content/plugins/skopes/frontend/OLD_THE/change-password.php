<?php
	/*
	*  Written by Roayl Tyagi on 25-Dec-14
	*
	*   My Account page on which user will change the password & purchase the plans.
	*
	*/
?>
<?php
/* <?php wp_set_password( $password, $user_id ) ?>
*/
	session_start();
	include(RC_TC_BASE_DIR."/frontend/core/class-plans.php");
	$skopePlans = new skopePlans();

	if( $_POST['change_pass'] == 'Y' ) {
		global $current_user;

		if (wp_check_password( $_POST['old_pass'], $current_user->user_pass, $current_user->ID)) {
			wp_set_password( $_POST['new_pass'], $current_user->ID );
			$success = 'Your password has been chagned.';

		} else {
			$error = 'Please enter correct current password.';
		}
	}

?>
<div class="content-section clearfix">
	<div class="container">
		<div class="subpage-content">
			<div class="box">
				<h2 class="boxheading">Change password</h2>
				<form method="post" action=""  class="user-profileform account" id="user_chanage_pass">
					<?php if ( $error ) echo '<div class="upf-raw"><div class="upf-cell column1"><span class="error">'. $error .'</span></div><div class="upf-cell column2"></div></div>';
					if ( $success ) echo '<div class="upf-raw"><div class="upf-cell column1"><span class="success">'. $success .'</span></div><div class="upf-cell column2"></div></div>';
					?>
					<div class="upf-raw">
						<div class="upf-cell column1">
							<label>Old Password:</label>
						</div>
						<div class="upf-cell column2">
							<input class="text-input" name="old_pass" type="password" id="old_pass" value="" />
						</div>
					</div>
					<div class="upf-raw">
						<div class="upf-cell column1">
							<label>New Password:</label>
						</div>
						<div class="upf-cell column2">
							<input class="text-input" name="new_pass" type="password" id="new_pass" value="" />
						</div>
					</div>
					<div class="upf-raw">
						<div class="upf-cell column1">
							<label>Verify Password:</label>
						</div>
						<div class="upf-cell column2">
							<input class="text-input" name="re_pass" type="password" id="re_pass" value="" />
						</div>
					</div>
					<div class="upf-raw">
						<div class="upf-cell column1">
						</div>
						<div class="upf-cell column2">
							<input type="submit" name="change_pass"  class="button-primary btn-blue1" id='change_pass' value="Save" style="width: 120px; background: #85c8ee;"/>
							<input type="hidden" name="change_pass" value="Y" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/my-account.js"></script>
