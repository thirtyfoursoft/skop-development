<?php
include("../../../../wp-config.php");
include("core/class_userinfo.php");

$objUser = new clsSkopes();
$obj = new userinfo();

$loggedin_id = get_current_user_id();
$check = $_POST['query'];  
$status = $_POST['status']; 

switch ($check) {
	case "itm1":
	    $obj->updateGuideStatus($loggedin_id,1,$status);		
	break;
	case "itm2":
	    $obj->updateGuideStatus($loggedin_id,2,$status);		
	break;
	case "itm3":
	    $obj->updateGuideStatus($loggedin_id,3,$status);		
	break;
	case "itm4":
	    $obj->updateGuideStatus($loggedin_id,4,$status);		
	break;
	case "itm5":
	    $obj->updateGuideStatus($loggedin_id,5,$status);		
	break;
	case "itm6":
	    $obj->updateGuideStatus($loggedin_id,6,$status);		
	break;
	case "itm7":
	    $obj->updateGuideStatus($loggedin_id,7,$status);		
	break;
	case "itm8":
	    $obj->updateGuideStatus($loggedin_id,8,$status);		
	break;	
	case "itm9":
	    $obj->updateGuideStatus($loggedin_id,9,$status);		
	break;
	case "itm10":
	    $obj->updateGuideStatus($loggedin_id,10,$status);		
	break;	
	case "itm11":
	    $obj->updateGuideStatus($loggedin_id,11,$status);		
	break;
	case "itm12":
	    $obj->updateGuideStatus($loggedin_id,12,$status);		
	break;
	case "itm13":
	    $obj->updateGuideStatus($loggedin_id,13,$status);		
	break;
	case "itm14":
	    $obj->updateGuideStatus($loggedin_id,14,$status);		
	break;
	case "itm15":
	    $obj->updateGuideStatus($loggedin_id,15,$status);		
	break;
	case "itm16":
	    $obj->updateGuideStatus($loggedin_id,16,$status);		
	break;
	case "itm17":
	    $obj->updateGuideStatus($loggedin_id,17,$status);		
	break;
	case "itm18":
	    $obj->updateGuideStatus($loggedin_id,18,$status);	
	case "itm19":
	    $obj->updateGuideStatus($loggedin_id,19,$status);		
    case "itm20":
	    $obj->updateGuideStatus($loggedin_id,20,$status);				
	break;
	  
}
?>