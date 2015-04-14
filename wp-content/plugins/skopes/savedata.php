<?php
require_once('../../../wp-load.php');
global $wpdb;
$field= $_POST['field'];
$ques = $_POST[$field.'_question'];
$helptext = $_POST[$field.'_helptext'];
$id = $_POST[$field.'_id'];

$table_name = $wpdb->prefix."specdoc_field_items";

$sql = 'UPDATE '.$table_name.' SET question="'.mysql_escape_string($ques).'", help_text="'.mysql_escape_string($helptext).
	'",status="'.mysql_escape_string($status).'" WHERE id='.$id;
$wpdb->query($sql);
?>