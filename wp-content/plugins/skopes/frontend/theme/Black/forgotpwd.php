<?php
  /*
  *     Name: Twisha Patel  Date: 17-8-2013
  *     Forgot password page in popup
  *
        <a id="forgot_pwd" class="yes-delete" href="javascript: void(0);" onclick="test();">
			<span class="inner">Send</span> 
		</a>
		 onsubmit="return fnvalidate_forgotpwd()"
  */
?>
<h5>Forgot Password</h5>
<div class="content">
	<form id="frmfogotpwd2" method="post">
		<input type="hidden" name="hiddenaction" id="hiddenaction" value="ForgotPassword"/>
		<span class="error" id="fogotemail_error" style="float: left; margin-top: 5px; margin-bottom:5px"></span>
		<input type="text" name="email" id="email_forgot_ID" value="" placeholder="Email Address" />
		<a id="forgot_pwd2" class="yes-delete" href="javascript: void(0);">
			<span class="inner">Send</span> 
		</a>
		<div class="error" id="show_msg_a" style="float: left; margin-top: 10px; display: block"></div>
		<div class="error" id="show_msg_a1" style="float: left; margin-top: 10px; display: block"></div>
	</form>
</div>
