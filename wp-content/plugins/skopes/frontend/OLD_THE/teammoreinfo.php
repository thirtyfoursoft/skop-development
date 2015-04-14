<?php include '../../../../wp-load.php'; 
session_start();
//include('core/class_skopes.php');
include('core/class_userinfo.php');
$objUser = new clsSkopes();
$data = $_POST; 
$obj = new userinfo();
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
    $objUser->saveSectionStageAfterStage2($loggedin_id, 'FURTHER PROJECT DETAILS', 'Team responsibilities and effort', 'InProgress');
//} 
$objUser->saveSectionStageForGuidePageTickOthers2($loggedin_id, 'FURTHER PROJECT DETAILS', 'Complete inputting project background information', 'InProgress', 'step3',  'Team responsibilities and effort', 'Selection criteria', 'Non-financial benefits');

if( is_array($data) and !empty($data) ) {
        $objUser->saveTeamMoreInfo($data,$loggedin_id);  
		$resultarray = $objUser->selectTeamInfo($loggedin_id);
		$res = $objUser->selectMoreInfoStatus($loggedin_id);
		$i=0; 
?>
<div id="memberinfo">
<?php
$memberresultarray = $objUser->selectTeamMemberInfo($loggedin_id);
$tlresultarray = $objUser->selectTeamLeadInfo($loggedin_id);

				
foreach ($tlresultarray as $tlkey=>$tlval){

	$tmemberId = $tlval['id'];
	$tmemberType = $tlval['member_type'];
	$tmemberName = htmlspecialchars_decode($tlval['member_name'], ENT_NOQUOTES);
	$tmemberResponsiblity = htmlspecialchars_decode($tlval['member_responsibility'], ENT_NOQUOTES);
	$tmemberEffort = $tlval['member_estimate_effort'];
?>
	<div class="tldetails">
		<div class="pi-left">		
			<?php echo ucfirst($tmemberName);?>
			<input type="hidden" name="tlid" value="<?php echo $tmemberId;?>">
		</div>
					
		<div class="pi-middle">
			<div><input type="text" value="<?php echo $tmemberResponsiblity; ?>" id="teamleadresp" name="teamleadresp"><span id="error_teamleadresp" class="error"></span></div>
		</div>
			
		<div class="sc-right">
			<div>
				<input type="text" value="<?php echo $tmemberEffort;?>" id="teamlead_effort" name="teamlead_effort" >
				<span id="error_teamlead_effort" class="error"></span>
			</div>	
		</div>
	</div>
<?php }				
$i=0; ?>


<div id="memberinfo"> 
<?php
foreach ($memberresultarray as $key=>$val){

			$memberId = $val['id'];
			$memberType = $val['member_type'];
			$memberName = htmlspecialchars_decode($val['member_name']);
			$memberResponsiblity = htmlspecialchars_decode($val['member_responsibility'], ENT_NOQUOTES);
			$memberEffort = $val['member_estimate_effort'];

			$memberCounter = ($memberType == 'tl' ? '' : $i++);

		?>		
	<!-- member info section -->
		
		<div class="memberdetails">
			<div class="pi-left">
				<?php echo ucfirst($memberName);?>						
			</div>
			
			<div class="pi-middle">
				<div>
					<input type="text" value="<?php echo $memberResponsiblity;?>" id="memberresp_<?=$i;?>" name="memberresp[<?php echo $memberId;?>]">
					<span id="error_memberresp_<?=$i;?>" class="error"></span>
				</div>
			</div>
	
			<div class="sc-right">
				<div>
					<input type="text" value="<?php echo $memberEffort;?>" id="membereffort_<?=$i;?>" name="membereffort[<?php echo $memberId;?>]" class="memeffort">
						<span id="error_membereffort_<?=$i;?>" class="error"></span>
				</div>
			</div>
			<!-- <a class="removeclass" href="#">×</a> -->
		</div>  
	

<?php
} // end of foreach
?>
<input type="hidden" class="" value="<?php echo $res;?>" name="teammoreval" id="teammoreval" >
</div>
<?php }
else {
?>
           <div id="memberinfo" style="font-size: 12pt; color: red; padding: 10px;">
		         Please add some Team members by clicking on Edit link below
		   </div>
<?php
 } ?>