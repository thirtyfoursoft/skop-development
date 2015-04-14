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
			<div class="form-three-colum">
				<div class="first-colum">
					First milestone

	<?php }elseif($keyval+1 == $totalMs){ ?>
		<div class="mstoneinfo">
			<div class="form-three-colum">
				<div class="first-colum">
				Final milestone

	<?php }else{ ?>	
			<div class="milestoneinfo">
				<div class="form-three-colum">
					<div class="first-colum">					
					<?php echo "Milestone <span id='miles_".$keyval."'>".($keyval)."</span>:";?>
					<?php }?>
				</div>
		
		<div class="second-colum">
		<?php if($keyval == 0){ ?>
			<input type="text" readonly="readonly" name="firstmilestone" class="input-text" value="<?php echo htmlspecialchars_decode($msval['milestone'], ENT_NOQUOTES);?>">		
		<?php }elseif($keyval+1 == $totalMs){ ?>
			<input type="text" readonly="readonly" name="finalmilestone" class="input-text" id="finalmilestone" value="<?php echo htmlspecialchars_decode($msval['milestone'], ENT_NOQUOTES);?>">		
		<?php }else{ ?>

				<?php if($keyval == 1){  ?>
					<input type="text" name="milestone[]" value="<?php echo htmlspecialchars(stripslashes($msval['milestone']));?>" readonly="readonly" class="input-text">
				<?php } else { ?>
					<input type="text" name="milestone[]" value="<?php echo htmlspecialchars_decode($msval['milestone'],ENT_NOQUOTES);?>" class="input-text">
				<?php } ?>
									
		<?php } ?>
		</div>
	
		<div class="third-colum">
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
				<input type="text" id="datepickerfirst" name="firstmilestonedate" value="<?php echo $data['project_commencement']; ?>" class="input-text" readonly="readonly">
			<?php }elseif($keyval+1 == $totalMs){ ?>
				<input type="text" id="datepickerfinal" name="finalmilestonedate" value="<?php echo $data['project_completion']; ?>" class="input-text" readonly="readonly">
			<?php } else { ?>

				<?php if($keyval == 1){  ?>
					<!--<input type="text" id="datepicker<?php echo $mskey;?>" name="milestonedate[]" value="<?php echo $msdate; ?>" class="" readonly="readonly">-->
					<input type="text" id="datepicker<?php echo $mskey;?>" name="milestonedate[]" value="<?php echo $data['supplier']; ?>" class="input-text" readonly="readonly">
				<?php } else { ?>
					<input type="text" id="datepicker<?php echo $mskey;?>" name="milestonedate[]" value="<?php echo $msdate; ?>" class="pickdate input-text" readonly="readonly">
				<?php if($keyval == 0 || $keyval == 1 || $keyval+1 == $totalMs){ ?>
				<?php }else{?>
				<a href="#" class="removemclass">x</a>
				<?php }?>
							
				<?php } ?>
			
			<?php } ?>
		</div>
	
		<input type="hidden" name="msid[]" value="<?php echo $msval['mid'];?>">
		</div>
	</div>
	<?php

		}
	} else { 	?>
	
				<?php
					$getAllMilestoneName = $obj->getAllMilestoneName();
					
					foreach ( $getAllMilestoneName as $k => $v ) {

						if ($k == 0) { ?>
						
							<div class="mstoneinfo">
								<div class="form-three-colum">
									<div class="first-colum">
										First milestone: 
									</div>
									<div class="second-colum">
										<input type="text" readonly="readonly" name="firstmilestone" value="<?php echo $v->milestone; ?>" class="input-text">
									</div>			
									<div class="third-colum">
									 <input type="text" id="datepickerfirst" name="firstmilestonedate" class="input-text" readonly="readonly" value="<?php echo $data['project_commencement']; ?>">				
									</div>
								</div>
							</div>
				
						<?php } else if ( $v->milestone == 'Project Completion' ) { ?>
						
							<div class="mstoneinfo">
								<div class="form-three-colum">
									<div class="first-colum">Final milestone: </div>
									<div class="second-colum">
										<input type="text" readonly="readonly" id="finalmilestone" name="finalmilestone" value="<?php echo $v->milestone; ?>" class="input-text">
									</div>
									<div class="third-colum">
									 <input type="text" id="datepickerfinal" name="finalmilestonedate"  class="input-text" value="<?php echo $data['project_completion']; ?>" readonly="readonly">	
									</div>
								</div>
							</div>
							
						<?php } else { ?>

							<div class="milestoneinfo">
								<div class="form-three-colum">
									<div class="first-colum">Milestone <span id="miles_<?=$k; ?>"><?php echo $k; ?></span>: </div>		
									<div class="second-colum">
										<input type="text" name="milestone[]" value="<?=$v->milestone; ?>" readonly="readonly" class="input-text">
									</div>
									<div class="third-colum">
									<?php if ($k == 1) { ?>
										 <input type="text" id="datepicker<?=$k; ?>" name="milestonedate[]" class="input-text" readonly="readonly" value="<?php echo $data['supplier']; ?>">				
									 <?php } else { ?>
										 <input type="text" id="datepicker<?=$k; ?>" name="milestonedate[]" class="pickdate input-text" value="">	
										 <a href="#" class="removemclass">x</a>
									 <?php } ?>
									</div>
								</div>
							</div>

						<?php }

					}
				?>
	<? /*
			<div class="mstoneinfo">
				<div class="form-three-colum">
					<div class="first-colum">First milestone: </div>
					<div class="second-colum">
						<input type="text" readonly="readonly" name="firstmilestone" value="Project Commencement" class="input-text">
					</div>
					<div class="third-colum">
					 <input type="text" id="datepickerfirst" name="firstmilestonedate" class="pickdate input-text" value="<?php echo $data['firstmilestonedate'];?>">
					</div>
				</div>
			</div>
			<div class="milestoneinfo">
				<div class="form-three-colum">
					<div class="first-colum">Milestone 1: </div>
					<div class="second-colum">
						<input type="text" name="milestone[]" value="Supplier engaged" class="input-text">
					</div>
					<div class="third-colum">
					 <input type="text" id="datepicker1" name="milestonedate[]" class="pickdate input-text" value="<?php echo $data['milestonedate'][0];?>">
 					<a href="#" class="removemclass">x</a>
					</div>
				</div>
			</div>
			<div class="milestoneinfo">
				<div class="form-three-colum">
					<div class="first-colum">Milestone 2: </div>

					<div class="second-colum">
						<input type="text" name="milestone[]" value="Scoping and planning complete" class="input-text">
					</div>

					<div class="third-colum">
						<input type="text" id="datepicker2" name="milestonedate[]" class="pickdate input-text" value="<?php echo $data['milestonedate'][1];?>">
						<a href="#" class="removemclass">x</a>
					</div>
				</div>
			</div>

			<div class="milestoneinfo">
				<div class="form-three-colum">
					<div class="first-colum">Milestone 3: </div>

					<div class="second-colum">
					<input type="text" name="milestone[]" value="Interface design complete" class="input-text">
					</div>

					<div class="third-colum">
					 <input type="text" id="datepicker3" name="milestonedate[]" class="pickdate input-text"	value="<?php echo $data['milestonedate'][2];?>">
					 <a href="#" class="removemclass">x</a>
					</div>
				</div>
			</div>
			
			
			<div class="milestoneinfo">
				<div class="form-three-colum">
					<div class="first-colum">Milestone 4: </div>		
			
					<div class="second-colum">
					<input type="text" name="milestone[]" value="Development complete" class="input-text">
					</div>
			
					<div class="third-colum">
					 <input type="text" id="datepicker4" name="milestonedate[]" class="pickdate input-text"	value="<?php echo $data['milestonedate'][3];?>">
					 <a href="#" class="removemclass">x</a>
					</div>
				</div>
			</div>
			
			<div class="milestoneinfo">
				<div class="form-three-colum">
					<div class="first-colum">Milestone 5: </div>	
			
					<div class="second-colum">
					<input type="text" name="milestone[]" value="User testing complete" class="input-text">
					</div>
			
					<div class="third-colum">
					 <input type="text" id="datepicker5" name="milestonedate[]" class="pickdate input-text"	value="<?php echo $data['milestonedate'][4];?>">
					 <a href="#" class="removemclass">x</a>
					</div>
				</div>
			</div>
			
			<div class="milestoneinfo">
				<div class="form-three-colum">
					<div class="first-colum">Milestone 6: </div>
			
					<div class="second-colum">
						<input type="text" name="milestone[]" value="Training complete and system live" class="input-text">
					</div>
			
					<div class="third-colum">
					 <input type="text" id="datepicker6" name="milestonedate[]" class="pickdate input-text"	value="<?php echo $data['milestonedate'][5];?>">
					 <a href="#" class="removemclass">x</a>
					</div>
				</div>
			</div>
			
			<div class="mstoneinfo">
				<div class="form-three-colum">
					<div class="first-colum">Final milestone: </div>		
					<div class="second-colum">
						<input type="text" readonly="readonly" id="finalmilestone" name="finalmilestone" value="Project Completion" class="input-text"> 
					</div>			
					<div class="third-colum">
					 <input type="text" id="datepicker11" name="finalmilestonedate"  class="pickdate input-text" value="<?php echo $data['finalmilestonedate'];?>">	
					</div>
				</div>
			</div>

			*/ ?>
			<?php }?>
</div>
<input type="hidden" class="mileval" value="<?php echo $milevalue;?>" name="mileval" id="mileval" >
