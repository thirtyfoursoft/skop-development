<?php
	$user = new WP_User( $loggedin_id );
	$role = $user->roles[0];

	$vendorAccess = array(
		'eoi_res'		=> 'eoi_access_control.php',
		'res_form'		=> ''
	);

	if ($role == 'vendor') {
	
		if(isset($_REQUEST["act"]) && !array_key_exists( $_REQUEST["act"], $vendorAccess )) {
			wp_redirect($page."?act=eoi_res");
			exit;
		}

	} else {

		if(isset($_REQUEST["act"]) && array_key_exists( $_REQUEST["act"], $vendorAccess )) {
			wp_redirect($page."?act=guide");
			exit;
		}
	}

?>
