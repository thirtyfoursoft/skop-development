<?php
	if ($_POST['skope-settings-helpContent'] == 'Y') {
		check_admin_referer( "help-text-page" );
		skope_updaate_help_content( $_POST['help'] );
		//$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
		$url_parameters = 'updated=true';
		wp_redirect(admin_url('admin.php?page=help-text&'.$url_parameters));
		exit;

	}

/* ******************
* 
* Function for Save data in db  Help Content

*
******************* */
function skope_updaate_help_content( $data ) {

	foreach ($data as $k => $v ) {
		update_option($k,$v);
	}
}	
?>
<div class="wrap">
	<h2><?php echo 'Skope Settings'; ?></h2>
	
	<?php	if ( 'true' == esc_attr( $_GET['updated'] ) ) echo '<div class="updated" ><p>Your help content has been saved.</p></div>'; 	?>

	<div id="poststuff">
	<form method='post' enctype="multipart/form-data">
		<?php
			echo '<h3>Help content section</h3>';
			echo '<p>You can put the help content of the sections that will show on the front end.</p>';
			echo '<table class="form-table">'; 	
			wp_nonce_field( "help-text-page" );
		?>
			<tr valign="top">
				<th scope="row">Team members section help content </th>
				<td valign="top"><textarea name='help[help_teamMembergText]' id='teamMembergText' cols="50" rows="3"><?php echo stripslashes(trim(get_option("help_teamMembergText"))); ?></textarea></td>
			</tr>
			<tr>
				<th scope="row">Stakeholder section help content</th>
				<td valign="top"><textarea name='help[help_stakeholderText]' id='stakeholderText' cols="50" rows="3"><?php echo stripslashes(trim(get_option("help_stakeholderText")));	?></textarea></td>
			</tr>
			<tr>
				<th scope="row">Key project dates help content</th>
				<td valign="top"><textarea name='help[help_keysDateText]' id='keysDateText' cols="50" rows="3"><?php echo stripslashes(trim(get_option("help_keysDateText")));	?></textarea></td>
			</tr>			
			<tr>
				<th scope="row">Milestone section help content</th>
				<td valign="top"><textarea name='help[help_milestoneshelpText]' id='milestoneshelpText' cols="50" rows="3"><?php echo stripslashes(trim(get_option("help_milestoneshelpText")));	?></textarea></td>
			</tr>
			<tr>
				<th scope="row">Selection criteria help content</th>
				<td valign="top"><textarea name='help[help_selectionCriteriahelpText]' id='selectionCriteriahelpText' cols="50" rows="3"><?php echo stripslashes(trim(get_option("help_selectionCriteriahelpText"))); ?></textarea></td>
			</tr>
			<tr>
				<th scope="row">Team responsibilities and effort help content</th>
				<td valign="top"><textarea name='help[help_teamResponsibilitieshelpText]' id='teamResponsibilitieshelpText' cols="50" rows="3"><?php echo stripslashes(trim(get_option("help_teamResponsibilitieshelpText"))); ?></textarea></td>
			</tr>
			<tr>
				<th scope="row">Non-financial benefits help content</th>
				<td valign="top"><textarea name='help[help_nonfinancialBenefitsText]' id='nonfinancialBenefitsText' cols="50" rows="3"><?php echo stripslashes(trim(get_option("help_nonfinancialBenefitsText"))); ?></textarea></td>
			</tr>
			<tr>
				<th scope="row">Risks Help Conetent</td>
				<td valign="top"><textarea name='help[help_riskManagementhelptext]' id='riskManagementhelptext' cols="50" rows="3"><?php echo stripslashes(trim(get_option("help_riskManagementhelptext"))); ?></textarea></td>
			</tr>
			<tr>
				<th scope="row">Risk Management Help Conetent For more info</td>
				<td valign="top"><textarea name='help[help_riskManagementMorehelptext]' id='riskManagementMorehelptext' cols="50" rows="3"><?php echo stripslashes(trim(get_option("help_riskManagementMorehelptext"))); ?></textarea></td>
			</tr>
			<?php echo '</table>'; ?>
			<p class="submit" style="clear: both;">
				<input type="submit" name="save_setting"  class="button-primary" id='save_setting' value="Update Settings" />
				<input type="hidden" name="skope-settings-helpContent" value="Y" />
			</p>
		</table>
	</form>
	</div>
</div>
