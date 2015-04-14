<?php include '../../../../../../wp-load.php';

session_start();
if (!class_exists('userinfo')) {
	include('core/class_userinfo.php');
}
$objUser = new userinfo();
$data = $_POST; 

$objUser2 = new clsSkopes(); 

$loggedin_id = get_current_user_id();

$totalCompanyQues = $objUser->cquestioncount();
//$userCompanyQues = $obj->usercquestioncount($loggedin_id);
$userCompanyQues = $objUser->usercquestioncount_custom($loggedin_id);
$totalProjectQues = $objUser->projectpagecount();
$userProjectQues = $objUser->projectpageusercount($loggedin_id);
$userProjectMore = $objUser->projectmoreCount($loggedin_id);
$userFaDisplay = $objUser2->checkFunaStatus($loggedin_id);
$userFaCount = $objUser2->checkFunaStatuscount($loggedin_id);

$result1 = $objUser->getuserstage($totalCompanyQues,$userCompanyQues,$totalProjectQues,$userProjectQues,$userFaCount);
$stage = ($result1==3 ? 'two': 'one') ;

//if($stage == "two")
//{
	$objUser2->saveSectionStageAfterStage2($loggedin_id, 'About your project', 'Milestones', 'InProgress');
//} 
$objUser2->saveSectionStageForGuidePageTickOthers($loggedin_id, 'About your project', 'Begin inputting project background information', 'InProgress', 'step2', 'Milestones', 'Stakeholders', 'Team members', 'Project motivation, background and budget');

$savedata = $objUser->saveMilestone($data,$loggedin_id);
echo $msg = ($savedata == 1? '': $savedata);

$obj = new userinfo(); 

$Selectml = $objUser->selectAllMilestones($loggedin_id);
$milevalue =  $obj->selectMilestoneStatus($loggedin_id);

?>
<div id="milestoneinfomain">
<?php
$totalMs = count($Selectml);

	$keysDate = get_user_meta( $loggedin_id, '_keysProjectDate', true);
	if (empty($keysDate)) {

		$data = array(
			'project_commencement' => '',
			'supplier' => '',
			'project_completion' => ''
		);
	} else {
		$data = unserialize($keysDate);
	}

if($totalMs>0){
foreach($Selectml as $mskey=>$msval){ ?>
	<?php 

	$keyval = $mskey;?> 
	<?php if($keyval == 0){ ?>	

		<div class="mstoneinfo">
		<div class="pi-left">
		First milestone

	<?php }elseif($keyval+1 == $totalMs){ ?>
		<div class="mstoneinfo">
		<div class="pi-left">
		Final milestone

	<?php }else{ ?>	
		<div class="milestoneinfo">
		<div class="pi-left">						
		<?php echo "Milestone ".($keyval).":";?>
	<?php }?>
		</div>
		
		<div class="pi-middle">
		<?php if($keyval == 0){ ?>
			<input type="text" readonly="readonly" name="firstmilestone" value="<?php echo htmlspecialchars_decode($msval['milestone'], ENT_NOQUOTES);?>">		
		<?php }elseif($keyval+1 == $totalMs){ ?>
			<input type="text" readonly="readonly" name="finalmilestone" id="finalmilestone" value="<?php echo htmlspecialchars_decode($msval['milestone'], ENT_NOQUOTES);?>">		
		<?php }else{ ?>

				<?php if($keyval == 1){  ?>
					<input type="text" name="milestone[]" value="<?php echo htmlspecialchars(stripslashes($msval['milestone']));?>" readonly="readonly">
				<?php } else { ?>
					<input type="text" name="milestone[]" value="<?php echo htmlspecialchars_decode($msval['milestone'],ENT_NOQUOTES);?>">
				<?php } ?>
									
		<?php } ?>
		</div>
	
		<div class="sc-right">
		<?php 
		if($msval['milestonedate'] == '0000-00-00'){		  
		   $msdate = '';
		}else if (empty($msval['milestonedate'])) {
			$msdate = '';
		}else {
		   $msdate = date("d-m-Y", strtotime($msval['milestonedate']));
		}
		?>
			<?php if($keyval == 0){ ?>
				<!--<input type="text" id="datepickerfirst" name="firstmilestonedate" value="<?php echo $msdate; ?>" class="pickdate">-->
				<input type="text" id="datepickerfirst" name="firstmilestonedate" value="<?php echo $data['project_commencement']; ?>" class="" readonly="readonly">
			<?php }elseif($keyval+1 == $totalMs){ ?>
				<!--<input type="text" id="datepicker11" name="finalmilestonedate" value="<?php echo $msdate; ?>" class="pickdate">-->
				<input type="text" id="datepicker11" name="finalmilestonedate" value="<?php echo $data['project_completion']; ?>" class="" readonly="readonly">
			<?php } else { ?>

				<?php if($keyval == 1){  ?>
					<!--<input type="text" id="datepicker<?php echo $mskey;?>" name="milestonedate[]" value="<?php echo $msdate; ?>" class="" readonly="readonly">-->
					<input type="text" id="datepicker<?php echo $mskey;?>" name="milestonedate[]" value="<?php echo $data['supplier']; ?>" class="" readonly="readonly">
				<?php } else { ?>
					<input type="text" id="datepicker<?php echo $mskey;?>" name="milestonedate[]" value="<?php echo $msdate; ?>" class="pickdate" readonly="readonly">
				<?php } ?>
			
			<?php } ?>
		</div>
		
		
		<input type="hidden" name="msid[]" value="<?php echo $msval['mid'];?>">
		<?php if($keyval == 0 || $keyval == 1 || $keyval+1 == $totalMs){ ?>
		<?php }else{?>
		<a href="#" class="removemclass">x</a>
		<?php }?>
	</div>
	<?php

		}
	}
	else {
			?>
			<div class="mstoneinfo">
				<div class="pi-left">First milestone: </div>
				<div class="pi-middle">
					<input type="text" readonly="readonly" name="firstmilestone" value="Project Commencement" >
				</div>
				<div class="sc-right">
				 <input type="text" id="datepickerfirst" name="firstmilestonedate" class="pickdate" value="<?php echo $data['firstmilestonedate'];?>">
				</div>

			</div>
			<div class="milestoneinfo">
				<div class="pi-left">Milestone 1: </div>
				<div class="pi-middle">
					<input type="text" name="milestone[]" value="Supplier engaged" >
				</div>
				<div class="sc-right">
				 <input type="text" id="datepicker1" name="milestonedate[]" class="pickdate"value="<?php echo $data['milestonedate'][0];?>">
				</div>

				<a href="#" class="removemclass">x</a>
			</div>
			<div class="milestoneinfo">
				<div class="pi-left">Milestone 2: </div>

				<div class="pi-middle">
					<input type="text" name="milestone[]" value="Scoping and planning complete" >
				</div>

				<div class="sc-right">
					<input type="text" id="datepicker2" name="milestonedate[]" class="pickdate"value="<?php echo $data['milestonedate'][1];?>">
				</div>

				<a href="#" class="removemclass">x</a>
			</div>

			<div class="milestoneinfo">
				<div class="pi-left">Milestone 3: </div>

				<div class="pi-middle">
				<input type="text" name="milestone[]" value="Interface design complete" >
				</div>

				<div class="sc-right">
				 <input type="text" id="datepicker3" name="milestonedate[]" class="pickdate"value="<?php echo $data['milestonedate'][2];?>">
				</div>

				<a href="#" class="removemclass">x</a>
			</div>
			
			
			<div class="milestoneinfo">
				<div class="pi-left">Milestone 4: </div>		
			
				<div class="pi-middle">
				<input type="text" name="milestone[]" value="Development complete" >
				</div>
			
				<div class="sc-right">
				 <input type="text" id="datepicker4" name="milestonedate[]" class="pickdate"value="<?php echo $data['milestonedate'][3];?>">				
				</div>
				
				<a href="#" class="removemclass">x</a>
			</div>
			
			<div class="milestoneinfo">
				<div class="pi-left">Milestone 5: </div>	
			
				<div class="pi-middle">
				<input type="text" name="milestone[]" value="User testing complete" >
				</div>
			
				<div class="sc-right">
				 <input type="text" id="datepicker5" name="milestonedate[]" class="pickdate"value="<?php echo $data['milestonedate'][4];?>">				
				</div>
				
				<a href="#" class="removemclass">x</a>
			</div>
			
			<div class="milestoneinfo">
				<div class="pi-left">Milestone 6: </div>
			
			
				<div class="pi-middle">
					<input type="text" name="milestone[]" value="Training complete and system live" >
				</div>
			
				<div class="sc-right">
				 <input type="text" id="datepicker6" name="milestonedate[]" class="pickdate"value="<?php echo $data['milestonedate'][5];?>">				
				</div>
				
				<a href="#" class="removemclass">x</a>
			</div>
			
			<div class="mstoneinfo">
				<div class="pi-left">Final milestone: </div>		
				<div class="pi-middle">
					<input type="text" readonly="readonly" id="finalmilestone" name="finalmilestone" value="Project Completion" > 
				</div>			
				<div class="sc-right">
				 <input type="text" id="datepicker11" name="finalmilestonedate"  class="pickdate" value="<?php echo $data['finalmilestonedate'];?>">	
				 
				</div>
				
				
			</div>
			<?php }?>
</div>
<input type="hidden" class="mileval" value="<?php echo $milevalue;?>" name="mileval" id="mileval" >
