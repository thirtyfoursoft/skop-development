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

	global $wpdb;

		$tables = array (
			'0' 	=>  'functionalareas',
			'1' 	=>  'teammember',
			'2' 	=>  'eoiresponse'
		);

		foreach ( $tables as $k => $table ) {
			$query = $wpdb->query("DELETE FROM " . $wpdb->prefix.$table. " WHERE eoi_id = '" . $eoi_id . "' ");

			echo $query; 
		}

		wp_redirect(admin_url('admin.php?page=specdoc-eoi-response'));
		exit;

?>
