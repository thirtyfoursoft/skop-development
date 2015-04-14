<?php 
include("../../../../wp-config.php");
include("core/class_userinfo.php"); 	
$obj = new userinfo();
if( isset($_REQUEST['catname']) and $_REQUEST['catname'] != '' )
{
     $catname = urldecode($_REQUEST['catname']);
} 
else
{
     $catname = '';
} 
$catIDUseID = $_REQUEST['catIDUseID'];
if( isset($_REQUEST['catdesc']) and $_REQUEST['catdesc'] != '' )
{
     $catdesc = urldecode($_REQUEST['catdesc']);
}
else
{
     $catdesc = '';
} 
if($catname == '')
{
   echo 0;
} 
else if($catdesc == '')
{
   echo 1; 
}
else if($catname == '' and $catdesc == '')
{
   echo 2;
}
else
{ 
   if($catIDUseID > 0)
   { 
        $result = $obj->updateCategory($catname, $catdesc, $catIDUseID);
		echo $result; 
   }
   else
   {
		$result = $obj->insertCategory($catname, $catdesc);
		echo $result; 
   }		
}
?>