<?php
/**
 * Template Name: Forgot Password Page
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 get_header();
 ?>  

 
	<h5>Forgot Password</h5>
			<div class="content">
				<form id="frmfogotpwd" method="post" onsubmit="return fnvalidate_forgotpwd()">
					<input type="hidden" name="hiddenaction" id="hiddenaction" value="ForgotPassword"/>
					<span class="error" id="fogotemail_error"></span>
					<input type="text" name="email" id="email" value="" placeholder="Email" />
					<a id="forgot_pwd" class="yes-delete" href="javascript: void(0);" onclick="sendforgotP();">
						<span class="inner">Send</span> 
					</a>
				</form>
			</div>

<?php
get_sidebar();
get_footer();