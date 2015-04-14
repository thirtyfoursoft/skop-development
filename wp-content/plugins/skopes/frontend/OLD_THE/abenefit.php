<?php include '../../../../wp-load.php'; 
session_start();
//include('core/class_skopes.php');
include(RC_TC_BASE_DIR."/frontend/core/class_userinfo.php");
$objUser = new clsSkopes();
$obj = new userinfo(); 
$data = $_POST; 
$loggedin_id = get_current_user_id();

$totalCompanyQues = $obj->cquestioncount();
//$userCompanyQues = $obj->usercquestioncount($loggedin_id);
$userCompanyQues = $obj->usercquestioncount_custom($loggedin_id);
$totalProjectQues = $obj->projectpagecount();
$userProjectQues = $obj->projectpageusercount($loggedin_id);
$userProjectMore = $obj->projectmoreCount($loggedin_id);
$userFaDisplay = $objUser->checkFunaStatus($loggedin_id);
$userFaCount = $objUser->checkFunaStatuscount($loggedin_id);

$result1 = $obj->getuserstage($totalCompanyQues,$userCompanyQues,$totalProjectQues,$userProjectQues,$userFaCount);
$stage = ($result1==3 ? 'two': 'one') ;

//if($stage == "two")
//{
    $objUser->saveSectionStageAfterStage2($loggedin_id, 'FURTHER PROJECT DETAILS', 'Non-financial benefits', 'InProgress');
//} 
$objUser->saveSectionStageForGuidePageTickOthers2($loggedin_id, 'FURTHER PROJECT DETAILS', 'Complete inputting project background information', 'InProgress', 'step3', 'Non-financial benefits', 'Selection criteria', 'Team responsibilities and effort');

$objUser->saveAnticipatedBenefits($data,$loggedin_id);

$SelectabStatus = $obj->selectBenefitStatus($loggedin_id);
$Selectab = $objUser->selectAnticipatedBenefits($loggedin_id);
$abval = $Selectab['fieldvalue'];
$abarray = explode(',',$abval); 

foreach($abarray as $abkey=>$abvalue){?>
	<div>
		<div class="pi-left">
			Benefit <?=$abkey+1;?>
		</div>
		<div class="pi-middle">
		<input type="text" value="<?php echo htmlspecialchars_decode($abvalue, ENT_NOQUOTES);?>" id="anbenefit_<?=$abkey+1;?>" name="anbenefits[]" class="benefits" maxlength="30">					
		<span id="error_anbenefit_<?=$abkey+1;?>" class="error sherror"></span>		
		<?php //if($abkey>0){ ?>
		<a class="removeabclass" href="#">×</a>
		<?php //}?>
		</div>
	</div>
<?php
}
?>
<input type="hidden" value="<?php echo $SelectabStatus;?>" name="abval" id="abval" >
