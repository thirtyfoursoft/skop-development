<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
$pathurl =  plugin_dir_url(__FILE__); 
$parentpath = dirname(plugin_dir_path( __FILE__ ));
$dirpathurl =  dirname(plugin_dir_url(__FILE__)); 
$dirpath = plugin_dir_path( __FILE__ );
$templatepath = $parentpath.'/templates'; 
$accessFrom = $_SERVER["HTTP_REFERER"];

require_once dirname($parentpath).'/classes/CreateDocx.inc';
require_once $parentpath.'/codefiles/function.php';
$docx = new CreateDocx();
$newdocx = new getdocx();
$template_filename ='RFQ_template';
$result_filename ='RFQ_result';

$docxfile = 'RFQ_result'.$userid.'_html.docx';
$existing_file = $parentpath.'/docx/'.$docxfile;  $downloadFileName = base64_encode('Request_for_Quotation.docx');
if(file_exists($existing_file)) {
    unlink($existing_file);
} 

$filename = $newdocx->createuserdocx($template_filename,$result_filename);
$docx->createDocx($filename);

$finalFile = base64_encode($filename);  
$url = site_url(); 
//$file = $url.'/?act=getreport&file='.$finalFile.'&downloadFileName='.$downloadFileName; 
$file = $accessFrom.'&file='.$finalFile.'&downloadFileName='.$downloadFileName; 
ob_end_clean();
header("Location: $file");
?>
