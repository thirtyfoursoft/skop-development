<?php 
	include '../../../../../../wp-load.php';
	session_start();
	//include_once('core/class_skopes.php');
	include('core/class_userinfo.php');
	$objUser = new clsSkopes();
	$obj = new userinfo();
	$data = $_POST; 
	$loggedin_id = get_current_user_id();		//echo '<pre>';	// print_r($data);	//echo '</pre>'; 	//die; 
	//echo $objUser->saveTeamInfo($data,$loggedin_id);
	
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
		$objUser->saveSectionStageAfterStage2($loggedin_id, 'About your project', 'Team members', 'InProgress');
	//} 
	$objUser->saveSectionStageForGuidePageTickOthers($loggedin_id, 'About your project', 'Begin inputting project background information', 'InProgress', 'step2', 'Team members', 'Project motivation, background and budget', 'Stakeholders', 'Milestones');
	
    echo $objUser->saveTeamInfoCustom($data,$loggedin_id); 	
	$resultarray = $objUser->selectTeamInfo($loggedin_id,1);
	$teamfinalval = $obj->selectTeamInitialStatus($loggedin_id);

	$i=0;
?>
<script>			    function addnewField()				{				    var oldV = parseInt(document.getElementById('totalmembersID').value);					oldV = oldV + 1;					document.getElementById('totalmembersID').value = oldV; 				}				function deletenewField()				{				    var oldV = parseInt(document.getElementById('totalmembersID').value);					oldV = oldV - 1;					document.getElementById('totalmembersID').value = oldV; 										var latestV = parseInt(document.getElementById('totalmembersID').value);					var laeVaues = ""; 					for(var i=1; i<=oldV+1; i++)					{    						if($('#memberRowID_'+i).length)						{ 						       if(laeVaues == '')							   {								   laeVaues = i;     							   }							   else							   {								   laeVaues += "," + i;							   }    						}					}					document.getElementById('totalmembersNumbID').value = laeVaues;				}			</script>
<div id="teaminitalinfo">
<div id="teamMemAddedID" style="padding: 10px; color:  blue;"></div>	
<?php
$memberresultarray = $objUser->selectTeamMemberInfo($loggedin_id);
$tlresultarray = $objUser->selectTeamLeadInfo($loggedin_id);

foreach ($tlresultarray as $tlkey=>$tlval){
			
	$tmemberId = $tlval['id'];
	$tmemberType = $tlval['member_type'];
	$tmemberName = htmlspecialchars_decode($tlval['member_name'], ENT_NOQUOTES);
	$tmemberEmail = $tlval['member_email'];
?>
	<div class="tldetails">
		<div class="pi-left">		
			Team Lead		
		</div>
			<?php //echo $tmemberName;?>					
		<div class="pi-middle">
			<div><input type="text" value="<?php echo htmlspecialchars(stripslashes($tmemberName));?>" id="teamleadname" name="teamleadname" maxlength="50"><span id="error_teamleadname" class="error"></span></div>
		</div>
		<!---  onblur="checkvalidemail(this.id,this.value);" --->	
		<div class="sc-right"> <?php //echo $tmemberEmail;?>
			<div><input type="text" value="<?php echo htmlspecialchars(stripslashes($tmemberEmail));?>" id="teamlead_email" name="teamlead_email" maxlength="100">
			<input type="hidden" value="<?php echo $tmemberId;?>" id="tlid" name="tlid" class="umid" maxlength="100">
			<span id="error_teamlead_email" class="error"></span></div>	
		</div>
	</div>
	<?php }				
	$i=0; ?>
	<div id="memberinfo"> 
		<?php
		foreach ($memberresultarray as $key=>$val){

			$memberId = $val['id'];
			$memberType = $val['member_type'];
			$memberName = htmlspecialchars_decode($val['member_name'], ENT_NOQUOTES);
			$memberEmail = $val['member_email'];

			$memberCounter = ($memberType == 'tl' ? '' : $i++);

		?>		
	<!-- member info section -->
		
		<div class="memberdetails" id="memberdetails_<?=$i;?>">
		<div class="pi-left"> 
			New Member <? //=$i;?> 
		</div>
		
		<div class="pi-middle"><?php //echo $memberName;?>
			<div><input type="text" value="<?php echo htmlspecialchars(stripslashes($memberName));?>" id="membername_<?=$i;?>" name="membername[]" maxlength="50"><span id="error_membername_<?=$i;?>" class="error"></span></div>
		</div>
	   <!---  onblur="checkvalidemail(this.id,this.value);" --->
		<div class="sc-right"><?php //echo $memberEmail;?>
			<div><input type="text" class="teamemail" value="<?php echo htmlspecialchars(stripslashes($memberEmail));?>" id="memberemail_<?=$i;?>" name="memberemail[]" maxlength="100">
			<input type="hidden" value="<?php echo $memberId;?>" id="memberid<?=$i;?>" name="memberid[]" class="umid" maxlength="100">
			<span id="error_memberemail_<?=$i;?>" class="error"></span></div>
									
			
		</div>
		<?php		//if($i>1){ ?>
				<!--<a class="removeclass" href="#">×</a>--->				<a class="removeclass" id="removeclassID" onclick="removeteam_members('memberdetails_<?=$i;?>', <?php echo $memberId; ?>); deletenewField();" href="javascript:void(0);">x</a>
		<?php //}?>
		</div>  
	

<?php
} // end of foreach
?>
</div>
<?php 

	
?>

<input type="hidden" class="teamval" value="<?php echo $teamfinalval;?>" name="teamval" id="teamval" >
