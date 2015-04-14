<?php
	// Check db has table or not if not then create it.
	skope_checkAndCreateProductTables();
	$plans = skope_getAllProducts();

	?>
	<form method='post' action="" enctype="multipart/form-data">
		<?php
			echo '<h3>Skope Plans settings</h3>';
			echo '<table class="form-table">';
			wp_nonce_field( "skope-settings-page" );
		?>
			<tr>
				<th scope="row"><label>Plans</label></th>
				<th valign="row" style="width: 100px;"><label>Status</label></th>
				<th valign="row"><label>Price/Fees</label></th>
				<th valign="row"><label>Sort Order</label></th>
			</tr>
		<?php  foreach( $plans as $k => $plan ) { 
				if( $plan['status'] == 1){
					$checked = "checked=checked";
				} else{ 
					$checked = "";
				}
			?>
			<tr>
				<th scope="row"><label><?php 	echo $plan['name'];	?></label></th>
				<td valign="top">
					<div class="onoffswitch">
						<input type="checkbox" name="plan_setting[<?php echo $k; ?>][status]" class="onoffswitch-checkbox" id="plan_setting[<?php echo $k; ?>][status]"  <?php echo $checked; ?>>
						<label class="onoffswitch-label" for= "plan_setting[<?php echo $k; ?>][status]">
						<div class="onoffswitch-inner"></div>
						<div class="onoffswitch-switch"></div>
						</label>
					</div>
				</td>
				<td valign="top"><input type="text" name="plan_setting[<?php echo $k; ?>][price]" value="<?php  echo $plan['price'];  ?>"></td>
				<td valign="top"><input type="number" name="plan_setting[<?php echo $k; ?>][sort_order]" value="<?php  echo $plan['sort_order'];  ?>" min="1" max="20"></td>
				<input type="hidden" name="plan_setting[<?php echo $k; ?>][id]" value="<?php echo $plan['id'];  ?>">
			</tr>
		<?php } ?>
		<?php echo '</table>'; ?>
		<p class="submit" style="clear: both;">
			<input type="submit" name="save_setting"  class="button-primary" id='save_setting' value="Update Settings" />
			<input type="hidden" name="skope-plan-submit" value="Y" />
		</p>
	</form>
