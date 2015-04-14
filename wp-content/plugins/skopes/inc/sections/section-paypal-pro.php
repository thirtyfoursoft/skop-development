<?php
	$transaction = array( '1' => 'Sale' );

	include(RC_TC_BASE_DIR."/inc/pp_pro.php");
	$pp_standard = new Skope_Paypal_Pro();
	$data = $pp_standard->setting;
?>
	<form method='post' action="" enctype="multipart/form-data">
	<?php
		echo '<h3>PayPal Pro</h3>';
		echo '<p>PayPal Pro works without sending user to PalPal all the things will be happend on your website.</p>';
		echo '<table class="form-table">';
		wp_nonce_field( "skope-settings-page" );
	?>
		<tr>
			<th scope="row"><label for="pro[pp_pro_status]">Enable/Disable</label></th>
			<input type="hidden" name='pro[pp_pro_status]' value="0"/>
			<td valign="top"><input type="checkbox" name='pro[pp_pro_status]' value="1" <?php echo ($data['pp_pro_status'] == 1) ? 'checked=checked' : ''; ?> /> Enable PayPal Pro</td>
		</tr>
		<tr>
			<th scope="row"><label for="pro[pp_pro_title]">Title</label></th>
			<td valign="top"><input type="text" name='pro[pp_pro_title]' value="<?php	echo $data['pp_pro_title']; ?>" style="width: 25em;"/>
			<br/><p class="description">This controls the title which the user sees during checkout.</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="pro[pp_pro_api_user]">API Username:</label></th>
			<td valign="top"><input type="text" name='pro[pp_pro_api_user]' value="<?php	echo $data['pp_pro_api_user']; ?>" style="width: 25em;"/>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="pro[pp_pro_api_pass]">API Password:</label></th>
			<td valign="top"><input type="text" name='pro[pp_pro_api_pass]' value="<?php	echo $data['pp_pro_api_pass']; ?>" style="width: 25em;"/>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="pro[pp_pro_api_signature]">API Signature:</label></th>
			<td valign="top"><input type="text" name='pro[pp_pro_api_signature]' value="<?php	echo $data['pp_pro_api_signature']; ?>" style="width: 25em;"/>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="pro[pp_pro_sandbox]">Test Mode</label>
				<br/><p class="help">Use the live or testing (sandbox) gateway server to process transactions?</p>
			</th>
			<input type="hidden" name='pro[pp_pro_sandbox]' value="0"/>
			<td valign="top"><input type="radio" name='pro[pp_pro_sandbox]' value="1" <?php echo ($data['pp_pro_sandbox'] == 1) ? 'checked=checked' : ''; ?>/>&nbsp;Yes &nbsp;&nbsp;<input type="radio" name='pro[pp_pro_sandbox]' value="0" <?php echo ($data['pp_pro_sandbox'] == 0) ? 'checked=checked' : ''; ?> />&nbsp;No
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="pro[pp_pro_transaction]">Transaction Method</label></th>
			<td valign="top">
				<select name="pro[pp_pro_transaction]">
					<?php foreach( $transaction as $k => $v ) { ?>
						<!--<?php if ( $data['pp_pro_transaction'] == $k ) { ?>
								<option value="<?php echo $k; ?>" selected="selected"><?php echo $v; ?></option>
							<?php } else { ?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
						<?php } ?>-->
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>						
					<?php } ?>
				</select>
			</td>
		</tr>

	<?php	echo '</table>';   ?>
		<p class="submit" style="clear: both;">
			<input type="submit" name="save_setting"  class="button-primary" id='save_setting' value="Update Settings" />
			<input type="hidden" name="skope-pp-pro-submit" value="Y" />
		</p>
	</form>
