<?php
	$option_themes = array('Black'	=> 'Black & Gray', 'White'	=> 'White Lable', 'Interactive' => 'Interactive ');
	$risk_management = array( '0' => 'Disable', '1'	 => 'Enable');

	$is_risk_management = get_option("risk_management");
	$is_EOI_vendor = 	get_option("_eoi_vendor");
	$selected_theme = get_option("selected_theme");
	$logo_img = get_option("my_logo_img");
	?>

	<form method='post' action="">
		<?php
			echo '<h3>Skope Basic settings</h3>';
			echo '<table class="form-table">'; 	
			wp_nonce_field( "skope-settings-page" );
		?>
		<input type='hidden' name='hidden_action' id='hidden_action' value="SaveMessages"/>
			<tr valign="top">
				<th scope="row"><label for="my_logo_img">Upload Logo Image</label></th>
				<td>
					<label for="upload_image">
						<input id="upload_image" type="text" size="30" name="upload_logo" value="" />
						<input id="my_logo_img" type="hidden" size="30" name="my_logo_img" value="<?php echo $logo_img; ?>" />
						<input id="upload_image_button" type="button" value="Upload Image" />
						<br />Logo should be 120X50.<br/><br/>
						<img src="<?php echo $logo_img; ?>" id="preview_img">
				</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="selected_theme">Select Any Theme</label></th>
				<td valign="top">
					<select name="selected_theme" id="selected_theme">
						<?php foreach($option_themes as	$k => $v ) {
							if ($selected_theme == $k ) { ?>
								<option value="<?php echo $k; ?>" selected="selected"><?php	echo $v; ?></option>
							<?php } else { ?>
								<option value="<?php echo $k; ?>"><?php	echo $v; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="risk_management"> Risk Management </label></th>
				<td valign="top">
					<select name="risk_management" id="risk_management">
						<?php foreach($risk_management as $k => $v ) {
							if ($is_risk_management == $k ) { ?>
								<option value="<?php echo $k; ?>" selected="selected"><?php	echo $v; ?></option>
							<?php } else { ?>
								<option value="<?php echo $k; ?>"><?php	echo $v; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>
			</tr>

			<tr>
				<th scope="row"><label for="eoi_vendor"> EOI Vendors </label></th>
				<td valign="top">
					<select name="eoi_vendor" id="eoi_vendor">
						<?php foreach($risk_management as $k => $v ) {
							if ($is_EOI_vendor == $k ) { ?>
								<option value="<?php echo $k; ?>" selected="selected"><?php	echo $v; ?></option>
							<?php } else { ?>
								<option value="<?php echo $k; ?>"><?php	echo $v; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>
			</tr>
			
			<tr>
				<th scope="row"><label for="skop_slogan">Skop Slogan</label></th>
				<td valign="top"><input type ="text" name="skop_slogan" value="<?php echo get_option('skop_slogan')?>" style="width: 50%;"/></td>
			</tr>
			<tr><td colspan="2"><h4 style="color:#21759b;font-size:14px;"><u>Lightbox Messages</u></h4></td></tr>
			<tr>
				<th scope="row"><label for="thankyou_msg">Thank you for regiseter message</label></th>
				<td valign="top"><textarea name='thankyou_msg' id='thankyou_msg' cols="50" rows="3"><?php echo get_option("thankyou_msg")?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="login_fail_msg">Login faild message</label></th>
				<td valign="top"><textarea name='login_fail_msg' id='login_fail_msg' cols="50" rows="3"><?php echo get_option("login_fail_msg")?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="invalid_email_msg">Invalid Email for forgot password error message</label></th>
				<td valign="top"><textarea name='invalid_email_msg' id='invalid_email_msg' cols="50" rows="3"><?php echo get_option("invalid_email_msg")?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="cat_name_msg">Functional area name error message</label></th>
				<td valign="top"><textarea name='cat_name_msg' id='cat_name_msg' cols="50" rows="3"><?php echo get_option("cat_name_msg")?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="cat_desc_msg">Functional area descriprion error message</label></th>
				<td valign="top"><textarea name='cat_desc_msg' id='cat_desc_msg' cols="50" rows="3"><?php echo get_option("cat_desc_msg")?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="cat_del_msg">Functional area delete confirmation message</label></th>
				<td valign="top"><textarea name='cat_del_msg' id='cat_del_msg' cols="50" rows="3"><?php echo get_option("cat_del_msg")?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="lineiten_name_msg">Feature name error message</label></th>
				<td valign="top"><textarea name='lineiten_name_msg' id='lineiten_name_msg' cols="50" rows="3"><?php echo get_option("lineiten_name_msg")?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="lineitem_del_msg">Feature delete confirmation message</label></th>
				<td valign="top"><textarea name='lineitem_del_msg' id='lineitem_del_msg' cols="50" rows="3"><?php echo get_option("lineitem_del_msg")?></textarea></td>
			</tr>
			<tr><td colspan="2"><h4 style="color:#21759b;font-size:14px;"><u>Activation Email Subject and Content</u></h4></td></tr>
			<tr>
				<th scope="row"><label for="confirm_email_sub">Activation confirmation email subject</label></th>
				<td valign="top"><textarea name='confirm_email_sub' id='confirm_email_sub' cols="50" rows="4"><?php echo get_option("confirm_email_sub")?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="confirm_email_content">Activation confirmation email content</label></th>
				<td valign="top"><textarea name='confirm_email_content' id='confirm_email_content' cols="50" rows="4"><?php echo get_option("confirm_email_content")?></textarea></td>
			</tr>
			<?php echo '</table>'; ?>
			<p class="submit" style="clear: both;">
				<input type="submit" name="save_setting"  class="button-primary" id='save_setting' value="Update Settings" />
				<input type="hidden" name="skope-settings-submit" value="Y" />
			</p>
	</form>
