<?php 

	set_include_path("../../..");
	require_once 'wp-load.php';
	require_once 'wp-includes/pluggable.php';
	require_once 'wp-includes/registration.php';

	if(!isset($_REQUEST['user_id']))
		return;

	if(!isset($_REQUEST['user_key']))
		return;

	if(md5('hej'.$_REQUEST['user_id']) != $_REQUEST['user_key'])
		return;

	$result = get_user_meta($_REQUEST['user_id'], 'lc_report_word', true);

	if($result == null)
		return;

	$result = base64_decode($result, false);

	if($result == null)
		return;

    header('Content-Type: "application/force-download"');
    header('Content-Length: '.$size);
    header('Content-Disposition: attachment; filename=report.docx');
    header('Content-Transfer-Encoding: binary');

    echo $result;

