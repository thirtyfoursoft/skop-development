<?php

	$user_id = $_GET['user_id'];

	$user_info = get_userdata($user_id);

	if ($user_info->roles[0] != 'administrator') {

		wp_delete_user( $user_id );
		$url_parameters = 'update=del';
		wp_redirect(admin_url('admin.php?page=users-and-payments&'.$url_parameters));
		exit;
				
	} else {
	
		$url_parameters = '';
		wp_redirect(admin_url('admin.php?page=users-and-payments&'.$url_parameters));
		exit;
		
	}
?>
