<?php include '../../../../../../wp-load.php'; 
//echo "<pre>";
session_start();
//include('core/class_skopes.php');
if (!class_exists('userinfo')) {
	include('core/class_userinfo.php');
}
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

			$getlist1 = $objUser->selectselectionCreteria($loggedin_id); 

			if(count($getlist1)>0){
			$notStarted = 0;
			$fieldvalue = $getlist1['fieldvalue']; 
			$resultarray = unserialize($fieldvalue);
			
			function cmp($a, $b) {
				if ($a == $b) {
					return 0;
				}
				return ($a > $b) ? -1 : 1;
			}

uasort($resultarray, 'cmp');
		$totalWeights = "";
		$totalWeightsToShow = 0;
		$useCtr = 1;
		foreach ($resultarray as $key=>$val){
		   if($key != '') {
				 $name = $objUser->scNameFromId($key);
				 $cretriaName = $name['sc_name'];
	
		?>

			<div class="form-two-colum">
				<div class="first-colum"><?php echo $cretriaName;?></div>
				<div class="second-colum">
					<input type="text" name="imp_weight_<?=$key?>" id="imp_weight_id_<?=$key?>" value="<?php echo intval($val);?>" onKeyup="intOnly(this); toUnicode(this,this.value);" onKeydown="intOnly(this);" onKeypress="intOnly(this);" class="impweight input-text" maxlength="3" tabindex="<?php echo $useCtr++; ?>" onblur="intOnly(this);"> 
					<span id="errorimp_weight_<?=$key?>" class="error"></span>	
				</div>
			</div>

			<?php
			       if($totalWeights == '')
					 {
						 $totalWeights .= $key;
					 }
					 else
					 {
						 $totalWeights .= "," .$key;
					 }
                     $totalWeightsToShow = $totalWeightsToShow + $val;				 
			   } 
			}  ?>
			    <script>
			            $("#totalWeights").val('<?php echo $totalWeights; ?>');    
			    </script>    
			<?php		
						
			}else{
				$SelectionCriteria = $objUser->getAllSelectionCriteria();
				$notStarted = 1;
				foreach($SelectionCriteria as $key=>$sc) { 	?>

					<div class="form-two-colum">
						<div class="first-colum"><?php echo $sc['sc_name'];?></div>
						<div class="second-colum">
							<input type="text" name="imp_weight_<?=$key+1?>" value="<?=$sc['percentage']; ?>" class="impweight input-text" maxlength="3" onKeyup="intOnly(this);" onKeydown="intOnly(this);" onKeypress="intOnly(this);" >
							<span id="errorimp_weight_<?=$key+1?>" class="error"></span>
						</div>
					</div>
				<?php 
				}
			}
			?>
			
			<div class="form-two-colum">
				<div class="first-colum" style="text-indent: -9999px;">cc</div>
				<div class="second-colum">
					<div id="result"></div>
					<?php if($notStarted == 1) { ?>
							Total = <span class="imptotal"><?php echo intval(100); ?></span>
							<span id="totalerror" class="error"></span>					

					<?php } else { ?>
							Total = <span class="imptotal"><?php if($totalWeightsToShow > 0) { echo $totalWeightsToShow; }   ?></span>
							<span id="totalerror" class="error"></span>
					<?php } ?>
				</div>
				<input type="hidden" value="<?php echo $scStatus;?>" name="scval" id="scval" >
			</div>

<? /*

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
*/ ?>
