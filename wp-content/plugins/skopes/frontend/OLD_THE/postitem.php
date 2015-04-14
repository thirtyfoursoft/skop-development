<?php include '../../../../wp-load.php'; 
//echo "<pre>";
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

$objUser->saveSelectionCreteria($data,$loggedin_id);

//if($stage == "two")
//{
    $objUser->saveSectionStageAfterStage2($loggedin_id, 'FURTHER PROJECT DETAILS', 'Selection criteria', 'InProgress');
//} 
$objUser->saveSectionStageForGuidePageTickOthers2($loggedin_id, 'FURTHER PROJECT DETAILS', 'Complete inputting project background information', 'InProgress', 'step3', 'Selection criteria', 'Team responsibilities and effort', 'Non-financial benefits');

$scStatus = $obj->selectScStatus($loggedin_id);
$select = $objUser->selectselectionCreteria($loggedin_id);
$fieldvalue = $select['fieldvalue'];
$resultarray = unserialize($fieldvalue);

function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a > $b) ? -1 : 1;
}


// Sort and print the resulting array
uasort($resultarray, 'cmp');
foreach ($resultarray as $key=>$val){

	//echo $key."=>".$val."<br>";
	$name = $objUser->scNameFromId($key);
	$cretriaName = $name['sc_name'];
	
?>
	<div class="pi-left">				
				<p><?php echo $cretriaName;?></p>
	</div>
	
	<div class="pi-middle">
		
	</div>
	
	<div class="sc-right">				
		<input type="text" name="imp_weight_<?=$key?>" value="<?php echo $val;?>" class="impweight" maxlength="3">
		<span id="errorimp_weight_<?=$key?>" class="error"></span>				
	</div>
<?php
} 
?>
<div class="pi-left">				
				<!--<input type="submit" name="saveweight" value="Save" class="saveitem" id="savecretria">-->
			</div>
			
			<div class="pi-middle">
				<div id="result"></div>
			</div> 
			
			<div class="sc-right">				
				Total = <span class="imptotal"><?php echo array_sum($resultarray);?></span>
				<span id="totalerror" class="error"></span>				
			</div>
<input type="hidden" value="<?php echo $scStatus;?>" name="scval" id="scval" >