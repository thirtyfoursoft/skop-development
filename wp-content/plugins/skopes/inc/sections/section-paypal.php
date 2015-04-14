<?php
		$transaction = array( '1' => 'Sale' );

		include(RC_TC_BASE_DIR."/inc/pp_standard.php");
		$pp_standard = new Skope_Paypal();
		$data = $pp_standard->setting;
	?>

	<form method='post' action="" enctype="multipart/form-data">
	<?php
		echo '<h3>PayPal standard</h3>';
		echo '<p>PayPal standard works by sending the user to PayPal to enter their payment information.</p>';
		echo '<table class="form-table">';
		wp_nonce_field( "skope-settings-page" );
	?>
		<tr>
			<th scope="row"><label for="standard[pp_standard_status]">Enable/Disable</label></th>
			<input type="hidden" name='standard[pp_standard_status]' value="0"/>
			<td valign="top"><input type="checkbox" name='standard[pp_standard_status]' value="1" <?php echo ($data['pp_standard_status'] == 1) ? 'checked=checked' : ''; ?>/> Enable PayPal standard</td>
		</tr>
		<tr>
			<th scope="row"><label for="standard[pp_standard_title]">Title</label></th>
			<td valign="top"><input type="text" name='standard[pp_standard_title]' value="<?php	echo $data['pp_standard_title']; ?>" style="width: 25em;"/>
			<br/><p class="description">This controls the title which the user sees during checkout.</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="standard[pp_standard_email]">PayPal Email</label></th>
			<td valign="top"><input type="text" name='standard[pp_standard_email]' value="<?php	echo $data['pp_standard_email']; ?>" style="width: 25em;"/>
			<br/><p class="description">Please enter your PayPal email address; this is needed in order to take payment.</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="standard[pp_standard_sandbox]">PayPal sandbox</label></th>
			<input type="hidden" name='standard[pp_standard_sandbox]' value="0"/>
			<td valign="top"><input type="checkbox" name='standard[pp_standard_sandbox]' value="1" <?php echo (1 == $data['pp_standard_sandbox']) ? 'checked="checked"' : ''; ?>/>
			<br/><p class="description"><?php echo sprintf( __( 'PayPal sandbox can be used to test payments. Sign up for a developer account <a href="%s" target="_blank">here</a>.', 'woocommerce' ), 'https://developer.paypal.com/' ) ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="standard[pp_standard_debug]">Debug Log</label></th>
			<input type="hidden" name='standard[pp_standard_debug]' value="0"/>
			<td valign="top"><input type="checkbox" name='standard[pp_standard_debug]' value="1" <?php echo (1 == $data['pp_standard_debug']) ? 'checked="checked"' : ''; ?>/>
			<br/><p class="description">Log PayPal events, such as IPN requests, inside log file.</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="standard[pp_standard_transaction]">Transaction Method</label></th>
			<td valign="top">
				<select name="standard[pp_standard_transaction]">
					<?php foreach( $transaction as $k => $v ) { ?>
						<?php if ( $data['pp_standard_transaction'] == $k ) { ?>
								<option value="<?php echo $k; ?>" selected="selected"><?php echo $v; ?></option>
							<?php } else { ?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</td>
		</tr>

	<?php	echo '</table>';   ?>
		<p class="submit" style="clear: both;">
			<input type="submit" name="save_setting"  class="button-primary" id='save_setting' value="Update Settings" />
			<input type="hidden" name="skope-pp-standard-submit" value="Y" />
		</p>
	</form>
