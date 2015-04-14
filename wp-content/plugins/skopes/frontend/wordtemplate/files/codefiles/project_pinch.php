<?php //session_save_path(dirname($_SERVER['DOCUMENT_ROOT']).'/public_html/tmp');
ob_start(); 	  

$accessFrom = $_SERVER["HTTP_REFERER"];
$pathurl =  plugin_dir_url(__FILE__); 
$parentpath = dirname(plugin_dir_path( __FILE__ ));
$dirpathurl =  dirname(plugin_dir_url(__FILE__)); 
$dirpath = plugin_dir_path( __FILE__ );
$templatepath = $parentpath.'/templates';

require_once $parentpath.'/codefiles/function.php'; 
require_once dirname($parentpath).'/classes/CreateDocx.inc';
$userid = get_current_user_id();
$docx = new CreateDocx(); 
$newdocx = new getdocx();

$template_filename ='Project_pitch_template';
$result_filename ='Project_pitch_result';

$docxfile = 'Project_pitch_result'.$userid.'_html.docx';
$existing_file = $parentpath.'/docx/'.$docxfile; 
 
if(file_exists($existing_file)) {
    unlink($existing_file);
} 

$filename = $newdocx->createuserdocx($template_filename,$result_filename);
//echo $filename; die();
//$docx->createDocxAndDownload($filename); 
$docx->createDocx($filename);
$downloadFileName = base64_encode('Project_Rationale.docx');
 
$finalFile = base64_encode($filename); 
$url = site_url();
//$file = $url.'/?act=getreport&file='.$finalFile.'&downloadFileName='.$downloadFileName; 
$file = $accessFrom.'&file='.$finalFile.'&downloadFileName='.$downloadFileName; 
ob_end_clean();
header("Location: $file");
?>		
