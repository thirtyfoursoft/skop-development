<?php
	if( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	if (isset($_GET) && $_GET['eoi_id'] != '' ) {
		$eoi_id = $_GET['eoi_id'];
	}

	if ( $eoi_id == '' ) {
		wp_redirect(admin_url('admin.php?page=specdoc-eoi-response'));
		exit;
	}

	if (isset($_POST) && $_POST['skope-eoi-response-submit'] == 'Y') {
		global $wpdb;
		check_admin_referer( "specdoc-eoi-response" );
		$saveData = $_POST['eoiResponse'];

		$sql = $wpdb->query("UPDATE " . $wpdb->prefix . "eoiresponse SET org_name = '" . $saveData['org_name'] . "', your_name = '" . $saveData['your_name'] . "', your_system_name = '" . $saveData['your_system_name'] . "' WHERE eoi_id = '" . $saveData['eoi_id'] . "'");

		$url_parameters = 'updated=true';
		wp_redirect(admin_url('admin.php?action=editEOI&page=specdoc-eoi-response&eoi_id='.$saveData['eoi_id'].'&'.$url_parameters));
		exit;		
	}
?>
	<form method='post' action="" enctype="multipart/form-data">
	<?php
		global $wpdb;
		
		echo '<h3>EOI Response Form</h3>';
		if (isset($_GET['updated']) && $_GET['updated'] == 'true'):
			echo '<div class="updated below-h2"><p>EOI Response has been saved.</p></div>';
		endif;
		echo '<p>You can change the EOI response here.</p>';
		echo '<table class="form-table">';
		wp_nonce_field( "specdoc-eoi-response" );

		$data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "eoiresponse WHERE eoi_id = '" . $eoi_id . "'");
	?>
		<tr>
			<th scope="row"><label for="eoiResponse[org_name]">Title</label></th>
			<td valign="top"><input type="text" name='eoiResponse[org_name]' value="<?php	echo stripslashes($data->org_name); ?>" style="width: 25em;"/>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="eoiResponse[your_name]">Your Name</label></th>
			<td valign="top"><input type="text" name='eoiResponse[your_name]' value="<? echo stripslashes($data->your_name); ?>"/></td>
		</tr>
		<tr>
			<th scope="row"><label for="eoiResponse[your_system_name]">Your System Name</label></th>
			<td valign="top"><input type="text" name='eoiResponse[your_system_name]' value="<? echo stripslashes($data->your_system_name); ?>"/></td>
		</tr>
		<input type="hidden" name='eoiResponse[eoi_id]' value="<? echo $data->eoi_id; ?>"/>
		
	<?php	echo '</table>';   ?>
		<p class="submit" style="clear: both;">
			<input type="submit" name="save_setting"  class="button-primary" id='save_setting' value="Update EOI" />
			<input type="hidden" name="skope-eoi-response-submit" value="Y" />
		</p>
	</form>
<script type="text/javascript">
function ConfirmDelete() {

	var x = confirm("Do you really wants to delete this EOI Response");

	if (x) {
		return true;
	} else {
		return false;
	}

}
</script>
