<?php 	include '../../../../../../wp-load.php'; 

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

	$objUser2->saveSectionStageAfterStage2($loggedin_id, 'About your project', 'riskManagement', 'InProgress');

	$objUser2->saveSectionStageForGuidePageTickOthers($loggedin_id, 'About your project', 'Begin inputting project background information', 'InProgress', 'step2', 'riskManagement', 'Stakeholders', 'Team members', 'Project motivation, background and budget');

	$savedata = $objUser->addRiskManagement( $data, $loggedin_id );
	echo $msg = ($savedata == 1? '': $savedata);


	if ($data['checkForCompleteRiskManagement'] == 1) {
			$qry = "UPDATE ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='2' WHERE userid='$loggedin_id' AND section_name = 'riskManagement' ";
			$objUser2->saveSectionStageAfterStage2($loggedin_id, 'About your project', 'riskManagement', 'Complete');

			$objUser2->saveSectionStageForGuidePageTickOthers($loggedin_id, 'About your project', 'Begin inputting project background information', 'Complete', 'step2', 'riskManagement', 'Stakeholders', 'Team members', 'Project motivation, background and budget');
				
	} else {
			$qry = "UPDATE ".$wpdb->prefix."specdoc_userstatusinfo SET section_status ='1' WHERE userid='$loggedin_id' AND section_name = 'riskManagement' ";
	}

	$wpdb->query($qry);

	$query = "SELECT section_status FROM wp_specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='riskManagement'";
	$riskStatus = $wpdb->get_col($query);
	
	$riskStat =  $riskStatus[0];

//	include(RC_TC_BASE_DIR."/frontend/core/class_userinfo.php");
	$obj = new userinfo();

	$getAllRiskManagementBlock = $objUser->getAllRiskManagementBlock($loggedin_id);

	if (!empty( $getAllRiskManagementBlock )) {
		$LikelihoodOfOccurrence = array (
			'low'	=> 'Low <20%',
			'medium'	=> 'Medium (>20%)',
			'high'	=> 'High (>50%)'
		);

		$riskType = array (
			'legal'	=> 'Legal',
			'operational'	=> 'Operational',
			'timeline'	=> 'Timeline',
			'cost'	=> 'Cost',
			'service impact'	=> 'Service Impact',
			'deliverables'	=> 'Deliverables'
		);

		foreach ($getAllRiskManagementBlock as $k => $v) { ?>
			<div class="riskBlock" id="riskBlock_<?=$k; ?>">
				<?php if ( $k > 0 ) { ?>
					<a style="color: #ff0015 !important; display: inline-block; font-size: 18px; font-weight: bold; right: 30px; margin-top: 0px; position: absolute; float: right;"  href="javascript:void(0);" id="removeRiskBlock" onclick="removeRiskBlock('<?=$k; ?>',<?=$v['id']; ?>);">x</a>
				<?php } ?>
				<input type="hidden" class="riskStatus" value="<?php echo $v['id'];?>" name="risk[<?=$k; ?>][id]">
				<div class="pi-left"><input type="text" value="<?php echo $v['riskName']; ?>" name="risk[<?=$k; ?>][riskName]" class="required" aria-required="true"></div> 
				<div class="pi-middle">
					<select name="risk[<?=$k; ?>][LikelihoodOfOccurrence]" class="required" aria-required="true">
							<?php foreach ( $LikelihoodOfOccurrence as $kev => $vel ) {
									if ($kev == $v['LikelihoodOfOccurrence']) { ?>
									<option value="<?php echo $kev; ?>" selected="selected"><?php echo $vel; ?></option>
								<?php } else { ?>
									<option value="<?php echo $kev; ?>"><?php echo $vel; ?></option>
								<?php } ?>
							<?php } ?>
					</select>
				</div>
				<div class="sc-right">
					<select name="risk[<?=$k; ?>][riskType]" class="required" aria-required="true">
							<?php foreach ( $riskType as $kev => $vel ) {
									if ($kev == $v['riskType']) { ?>
									<option value="<?php echo $kev; ?>" selected="selected"><?php echo $vel; ?></option>
								<?php } else { ?>
									<option value="<?php echo $kev; ?>"><?php echo $vel; ?></option>
								<?php } ?>
							<?php } ?>
					</select>
				</div>
				<div class="sc-full">
					<textarea name="risk[<?=$k; ?>][riskDescription]" rows="4" cols="50" placeholder="Description" class="required" aria-required="true"><?php echo $v['riskDescription']; ?></textarea>
				</div>
			</div>
	<?php
		}
	}
	?>
	<input type="hidden" class="riskStatus" value="<?php echo $riskStat;?>" name="riskStatus" id="riskStatus" >
