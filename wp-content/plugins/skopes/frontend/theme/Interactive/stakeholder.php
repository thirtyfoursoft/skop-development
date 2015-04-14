<?php 	include '../../../../../../wp-load.php'; 
session_start();
//include('core/class_skopes.php');
//include(RC_TC_BASE_DIR."/frontend/core/class_userinfo.php");
include("core/class_userinfo.php");
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
	$objUser->saveSectionStageAfterStage2($loggedin_id, 'About your project', 'Stakeholders', 'InProgress');
//} 
$objUser->saveSectionStageForGuidePageTickOthers($loggedin_id, 'About your project', 'Begin inputting project background information', 'InProgress', 'step2', 'Stakeholders', 'Team members', 'Project motivation, background and budget', 'Milestones');

$objUser->saveStakeholders($data,$loggedin_id);

$shvalue =  $obj->selectStakeholderStatus($loggedin_id);
$Selectsh = $objUser->selectStakeholders($loggedin_id);	  
$shval = htmlspecialchars_decode($Selectsh['fieldvalue'], ENT_NOQUOTES);
$sharray = explode(',',$shval); 
foreach($sharray as $shak=>$shval){?>

	  <div class="form-two-colum">
		<div class="first-colum">Stakeholders <?=$shak+1;?></div>
		<div class="second-colum">
			<input type="text" value="<?php echo htmlspecialchars(stripslashes($shval));?>" id="stackholder_<?=$shak+1;?>" name="stackholder[]" class="stackholders input-text"><span id="error_stackholder_<?=$shak+1;?>" class="error sherror"></span>
			<?php if ($shak != 0): ?>
				<a class="removeclass" href="#">×</a>
			<?php endif; ?>
		</div>
	  </div>
						  
<?php
}
?>
<input type="hidden" class="shval" value="<?php echo $shvalue;?>" name="shval" id="shval" >
