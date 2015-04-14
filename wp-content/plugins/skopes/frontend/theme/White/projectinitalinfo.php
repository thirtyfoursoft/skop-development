<?php
	include '../../../../../../wp-load.php'; 
	session_start();
	//include('core/class_skopes.php');
	include('core/class_userinfo.php');
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
		$objUser->saveSectionStageAfterStage2($loggedin_id, 'About your project', 'Project motivation, background and budget', 'InProgress');
	//} 
	$objUser->saveSectionStageForGuidePageTickOthers($loggedin_id, 'About your project', 'Begin inputting project background information', 'InProgress', 'step2', 'Project motivation, background and budget', 'Team members', 'Stakeholders', 'Milestones');
	
	$objUser->SaveUserProject($_POST,$loggedin_id);
	$profinalval = $obj->selectProjectQuesStatus($loggedin_id);

							$pro_ques1 = $objUser->getDefaultFieldData('project_question1');							
							$pro_ques1_on_off = $pro_ques1->status;
							if($pro_ques1_on_off==1) 
							{
						?>
								<div class="upf-raw">
									<div class="upf-cell column1">
										<label><?php echo $pro_ques1->question;?>:</label>
									</div>
									<div class="upf-cell column2">
									<?php 
									$getAuthor = get_user_meta($loggedin_id,"authors",true);
																		
									//if author name is empty then , use current user name as author name
									if($getAuthor==''){
									?>
										<input type="text" value="<?php echo the_author_meta( 'first_name', $current_user->ID );echo ('&nbsp;');echo the_author_meta( 'last_name', $current_user->ID );?>" name="authors" id="authors" /> 
										 
									<?php } else { ?>	
										<input type="text" value="<?php echo $getAuthor;?>" name="authors" id="authors" />
									<?php } ?>
										<div class="help-click" id="clickme"> ?</div>
										<span class="error" id="authors_error"></span>
										
									</div>
									<div class="help-text" id="book" style="display:none;">
									<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/help-text.png">
									<?php echo $pro_ques1->help_text;?></div> 
								</div> 
								
						<?php 
							}
                           
						   //Your projects name
						   $pro_ques5 = $objUser->getDefaultFieldData('project_question5');
							$pro_ques5_on_off = $pro_ques5->status;
							if($pro_ques5_on_off==1)
							{
							?>
							<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo $pro_ques5->question;?>:</label>
								</div>
								<div class="upf-cell column2">
									<input type="text" value="<?php echo get_user_meta($loggedin_id,"pro_ques5",true)?>" id="pro_ques5" name="pro_ques5" />
									
									<div id="proques5help" class="help-click"> ?</div>
									
									<span class="error" id="pro_ques5_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="proques5helptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/help-text.png"><?php echo $pro_ques5->help_text;?></div>
							</div>
						<?php 
							}
						   
							//Projects contribution to your organisations mission:
							
							$pro_ques2 = $objUser->getDefaultFieldData('project_question2');							
							$pro_ques2_on_off2 = $pro_ques2->status;
							if($pro_ques2_on_off2==1)
							{
						?>
								<div class="upf-raw">
									<div class="upf-cell column1">
										<label><?php echo stripslashes($pro_ques2->question);?>:</label>
									</div>
									<div class="upf-cell column2">
										
										<textarea name="pro_ques2" id="pro_ques2" cols="10" rows="5"><?php echo get_user_meta($loggedin_id,"pro_ques2",true)?></textarea>
										<div id="objhelp" class="help-click"> ?</div>	
										<span class="error" id="pro_ques2_error"></span>
									</div>
										<div id="objhelptext" class="help-text" style="display:none;">
										<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/help-text.png">
										<?php echo stripslashes($pro_ques2->help_text);?></div>
								</div>
						
							<?php
							}
							
							//Project contribution to (medium term) organisational goals:
							
							$pro_ques3 = $objUser->getDefaultFieldData('project_question3');
							$pro_ques3_on_off = $pro_ques3->status;
							
							if($pro_ques3_on_off==1)
							{
							?>

						  <div class="upf-raw">
							<div class="upf-cell column1">
								<label><?php echo $pro_ques3->question;?>:</label>
							</div>
							<div class="upf-cell column2">
								<input type="text" value="<?php echo get_user_meta($loggedin_id,"pro_ques3",true)?>" id="pro_ques3" name="pro_ques3" />
								
								<div id="pbhelp" class="help-click"> ?</div>
								
								<span class="error" id="pro_ques3_error"></span>
							</div>
							<div class="help-text" id="pbhelptext" style="display:none;">
							<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/help-text.png"><?php echo $pro_ques3->help_text;?></div> 
						 </div>
						<?php
							}
							
							//Expected project budget:

							$pro_ques4 = $objUser->getDefaultFieldData('project_question4');
							$pro_ques4_on_off = $pro_ques4->status;
							
							if($pro_ques4_on_off==1)
							{ 
						?>

								<div class="upf-raw">
									<div class="upf-cell column1">
										<label><?php echo $pro_ques4->question;?>:</label>
									</div>
									<div class="upf-cell column2">
										<input type="text" value="<?php echo get_user_meta($loggedin_id,"pro_ques4",true)?>" id="pro_ques4" name="pro_ques4" />
										
										<div id="msonehelp" class="help-click"> ?</div>
										
										<span class="error" id="pro_ques4_error"></span>
									</div>
									<div class="help-text" id="msonehelptext" style="display:none;">
									<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/help-text.png"><?php echo $pro_ques4->help_text;?></div>
								</div>
						<?php
							} 
							
							$pro_ques6 = $objUser->getDefaultFieldData('project_question6');							
							$pro_ques6_on_off = $pro_ques6->status;
							if($pro_ques6_on_off==1)
							{
						?>
							<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo $pro_ques6->question;?>:</label>
								</div>
								<div class="upf-cell column2">
									<input type="text" value="<?php echo get_user_meta($loggedin_id,"pro_ques6",true)?>" id="pro_ques6" name="pro_ques6" />
									<div id="pro_ques6_help" class="help-click"> ?</div>
									<span class="error" id="pro_ques6_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="pro_ques6_help_text">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/help-text.png">
								<?php echo $pro_ques6->help_text;?></div>
							</div>

						 <?php
							}
							
							$pro_ques7 = $objUser->getDefaultFieldData('project_question7');							
							$pro_ques7_on_off = $pro_ques7->status;
							
							if($pro_ques7_on_off==1)
							{
						?>
							<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo $ms4->question;?>:</label>
								</div>
								<div class="upf-cell column2">
									<input type="text" value="<?php echo get_user_meta($loggedin_id,"pro_ques7",true)?>" id="pro_ques7" name="pro_ques7" />
									<div id="pro_ques7help" class="help-click"> ?</div>
									<span class="error" id="dc_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="pro_ques7helptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/help-text.png">
								<?php echo $pro_ques7->help_text;?></div>
							</div>
						 <?php
							}
							
							$pro_ques8 = $objUser->getDefaultFieldData('project_question8');							
							$pro_ques8_on_off = $pro_ques8->status;
							if($pro_ques8_on_off==1)
							{
						?>
							<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo $pro_ques8->question;?>:</label>
								</div>
								<div class="upf-cell column2">
									<input type="text" value="<?php echo get_user_meta($loggedin_id,"pro_ques8",true)?>" id="pro_ques8" name="pro_ques8" />
									<div id="pro_ques8help" class="help-click"> ?</div>
									<span class="error" id="utc_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="pro_ques8helptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/help-text.png">
								<?php echo $pro_ques8->help_text;?></div>
							</div>
						<?php
							}
							
							$pro_ques9 = $objUser->getDefaultFieldData('project_question9');							
							$pro_ques9_on_off = $pro_ques9->status;
							if($pro_ques9_on_off==1)
							{
							
						?>
							<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo $pro_ques9->question;?>:</label>
								</div>
								<div class="upf-cell column2">
									<input type="text" value="<?php echo get_user_meta($loggedin_id,"pro_ques9",true)?>" id="pro_ques9" name="pro_ques9" />
									<div id="pro_ques9help" class="help-click"> ?</div>
									<span class="error" id="tcsl_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="pro_ques9helptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/help-text.png">
								<?php echo $ms6->help_text;?></div>
							</div>
						<?php	}	?>
							
						<?php		
						
						$pro_ques10 = $objUser->getDefaultFieldData('project_question10');							
							$pro_ques10_on_off = $pro_ques10->status;
							if($pro_ques10_on_off==1)
							{
						?>
						
						<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo $pro_ques10->question;?>:</label>
								</div>
								<div class="upf-cell column2">
									<input type="text" value="<?php echo get_user_meta($loggedin_id,"proques10",true)?>" id="proques10" name="proques10" />
									<div id="pro_ques10help" class="help-click"> ?</div>
									<span class="error" id="tcs10_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="pro_ques10helptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/help-text.png">
								<?php echo $pro_ques10->help_text;?></div>
							</div>
						
						<?php
						}
			
		?>
<input type="hidden" class="projectval" value="<?php echo $profinalval;?>" name="projectval" id="projectval" >
