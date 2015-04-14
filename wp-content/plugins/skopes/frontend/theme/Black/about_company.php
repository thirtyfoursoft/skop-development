<?php
	if (!class_exists('userinfo')) {
		include('core/class_userinfo.php');
	}
	$obj = new userinfo();
	$objUser = new clsSkopes();
	$userData = wp_get_current_user();
	
	$getStatus = $objUser->getOrgStatusValue($userData->ID); 
?>
<div class="content-section clearfix">
	<div class="container">
		<div class="subpage-content">
			<div class="box">
				<h2 class="boxheading">ABOUT YOUR ORGANISATION</h2> 
                                 <div class="cdiv">
									<div id="abimg"></div>
									<div id="abmsg"></div> 
								 </div>				
				 <form class="user-profileform" id="frmcompany" method="post" onsubmit="return fnvalidate_company()" enctype="multipart/form-data">
					<input type="hidden" name="hiddenaction" id="hiddenaction" value="SaveCompany" />
					
					<?php 
                          $totalVisibleFields = 0;
					?>
					
					<input type="hidden" name="userID" id="userID" value="<?php echo $userData->ID; ?>" />
					
					<input type="hidden" name="totalVisibleFields" id="totalVisibleFields" value="" />
					
					<input type="submit" name="hbtnsave" id="hbtnsave" style="position: absolute; left: -9999px; width: 1px; height: 1px;" />

					<div class="upf-table">
					<div id="dialog" ></div>
						<?php
							$compLogo = $objUser->getDefaultFieldData('company_question1');
							$compLogo_on_off = $compLogo->status;
							
							if($compLogo_on_off==1)
							{
						?>
						<?php $upload_dir = wp_upload_dir(); ?>
						
						
								<div class="upf-raw">
									<div class="upf-cell column1">
										<label><?php echo stripslashes($compLogo->question);?>:</label>
									</div>
									<div class="upf-cell column2 abt-company">
									
									<?php 
										$getlogoval = stripslashes(get_user_meta($loggedin_id,"com_logo",true));
										if(!empty($getlogoval)){ ?>
										<div id="cmplogoimg">									
											<div class="company-logo-inner">
												<img src="<?php echo $upload_dir['baseurl'].'/companylogos/thumbs/'.$loggedin_id.'/'.$getlogoval; ?>" style="float:left;">
											</div>
											<a href="#" id="del_logo">Delete Logo</a>
											<input type="hidden" value="<?php echo $getlogoval;?>" name="logoval">
										 </div>
									<?php } 
										else{
										?>
										<div id="cmplogoupload">
										<input type="file" value="" name="com_logo" id="com_logo" style="width:318px; float:left;"/>
										<span class="error" id="com_logo_error"></span>
										</div>
										<?php }//end of else ?>
										 <div id="clhelp" class="help-click"> ?</div>
									
										<span class="error" id="company_logo_error"></span>
										
										
										
									</div>
									<div id="clhelptext" class="help-text" style="display:none;"> 
									<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png"><?php echo stripslashes($compLogo->help_text);?></div>
								</div>
						<?php
								
						   }
						   
						   // company name 
							$comp_ques2 = $objUser->getDefaultFieldData('company_question2');
							$comp_ques2_on_off = $comp_ques2->status;
							?>
							   <input type="hidden" name="comp_ques2_on_off" id="comp_ques2_on_off" value="<?php echo $comp_ques2_on_off; ?>" />
							<?php
							if($comp_ques2_on_off==1)
							{
							   $totalVisibleFields = $totalVisibleFields + 1;
						?>
							<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo stripslashes($comp_ques2->question);?>:</label>
								</div>
								<div class="upf-cell column2">
									<input type="text" value="<?php echo htmlspecialchars(stripslashes(get_user_meta($loggedin_id,"comp_ques2",true)));?>" name="comp_ques2" id="comp_ques2" style="width:297px; float:left;"/>
									
									<div id="cahelp" class="help-click"> ?</div>
									
									<span class="error" id="comp_ques2_error"></span>
								</div>
								<div id="cahelptext" class="help-text" style="display:none;"> <img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png"><?php echo stripslashes($comp_ques2->help_text);?></div>
							</div>
						<?php
							}

							// company street address 
							
							$comp_ques3 = $objUser->getDefaultFieldData('company_question3');
							$comp_ques3_on_off = $comp_ques3->status;
							?>
							   <input type="hidden" name="comp_ques3_on_off" id="comp_ques3_on_off" value="<?php echo $comp_ques3_on_off; ?>" />
							<?php
							if($comp_ques3_on_off==1)
							{
							   $totalVisibleFields = $totalVisibleFields + 1;
						?>
								<div class="upf-raw">
									<div class="upf-cell column1">
										<label><?php echo stripslashes($comp_ques3->question);?>:</label>
									</div>
									<div class="upf-cell column2">
										<input type="text" value="<?php echo htmlspecialchars(stripslashes(get_user_meta($loggedin_id,"comp_ques3",true)));?>" name="comp_ques3" id="comp_ques3" style="width:297px; float:left;"/> 
										
										<div id="cchelp" class="help-click"> ?</div>
										
										<span class="error" id="comp_ques3_error"></span>
									</div>
									
									<div id="cchelptext" class="help-text" style="display:none;">
									<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
									<?php echo stripslashes($comp_ques3->help_text);?></div>
								</div>  
								<?php
								}
								
								// company CITY
								
								$comp_ques4 = $objUser->getDefaultFieldData('company_question4');
								$comp_ques4_on_off = $comp_ques4->status;
							    ?>
							   <input type="hidden" name="comp_ques4_on_off" id="comp_ques4_on_off" value="<?php echo $comp_ques4_on_off; ?>" />
							<?php
								if($comp_ques4_on_off==1)
								{   $totalVisibleFields = $totalVisibleFields + 1;
								?>
								<div class="upf-raw">
									<div class="upf-cell column1">
										<label><?php echo stripslashes($comp_ques4->question);?>:</label>
									</div>
									<div class="upf-cell column2">
										<input type="text" value="<?php echo htmlspecialchars(stripslashes(get_user_meta($loggedin_id,"comp_ques4",true)));?>" name="comp_ques4" id="comp_ques4" style="width:297px;float:left;" /> 
										
										<div id="cshelp" class="help-click"> ?</div>
										
										<span class="error" id="comp_ques4_error"></span>
									</div>
									
									<div id="cshelptext" class="help-text" style="display:none;">
									<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
									<?php echo stripslashes($comp_ques4->help_text);?></div>
								</div>
								<?php
								}
								
								// company STATE
								
								$comp_ques5 = $objUser->getDefaultFieldData('company_question5');
								$comp_ques5_on_off = $comp_ques5->status;
							?>
							   <input type="hidden" name="comp_ques5_on_off" id="comp_ques5_on_off" value="<?php echo $comp_ques5_on_off; ?>" />
							<?php
								if($comp_ques5_on_off==1)
								{
								    $totalVisibleFields = $totalVisibleFields + 1;
								?>
								<div class="upf-raw">
									<div class="upf-cell column1">
										<label><?php echo stripslashes($comp_ques5->question);?>:</label>
									</div>
									<div class="upf-cell column2">
										<input type="text" value="<?php echo htmlspecialchars(stripslashes(get_user_meta($loggedin_id,"comp_ques5",true)));?>" name="comp_ques5" id="comp_ques5" style="width:297px;float:left;" />
										
										<div id="czhelp" class="help-click"> ?</div>
										
										<span class="error" id="comp_ques5_error"></span>
									</div>
									
									<div id="czhelptext" class="help-text" style="display:none;">
									<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
									<?php echo stripslashes($comp_ques5->help_text);?></div>
								</div>
						<?php
							}
							
							/// Comapny Zip code
							
							    $comp_ques6 = $objUser->getDefaultFieldData('company_question6');
								$comp_ques6_on_off = $comp_ques6->status;
							?>
							   <input type="hidden" name="comp_ques6_on_off" id="comp_ques6_on_off" value="<?php echo $comp_ques6_on_off; ?>" />
							<?php
								if($comp_ques6_on_off==1)
								{
								   $totalVisibleFields = $totalVisibleFields + 1;
						?>
				
						<div class="upf-raw">
							<div class="upf-cell column1">
								<label><?php echo stripslashes($comp_ques6->question);?>:</label>
							</div>
							<div class="upf-cell column2">
															
								<input type="text" value="<?php echo htmlspecialchars(stripslashes(get_user_meta($loggedin_id,"comp_ques6",true)));?>" name="comp_ques6" id="comp_ques6" style="width:297px;float:left;" />
								<div id="cbhelp" class="help-click"> ?</div>
										
								<span class="error" id="comp_ques6_error"></span>
							</div>
								<div id="cbhelptext" class="help-text" style="display:none;">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
								<?php echo stripslashes($comp_ques6->help_text);?></div>
						</div>
						<?php
						}
						
						//Organisations Mission:
						
							$comp_ques7 = $objUser->getDefaultFieldData('company_question7');							
							$comp_ques7_on_off = $comp_ques7->status;
							?>
							   <input type="hidden" name="comp_ques7_on_off" id="comp_ques7_on_off" value="<?php echo $comp_ques7_on_off; ?>" />
							<?php
							if($comp_ques7_on_off==1)
							{
							   $totalVisibleFields = $totalVisibleFields + 1;
						?>
							<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo stripslashes($comp_ques7->question);?>:</label>
								</div>
								<div class="upf-cell column2">
									<textarea cols="100" rows="2" id="comp_ques7" name="comp_ques7"  style="width:297px;"><?php echo stripslashes(get_user_meta($loggedin_id,"comp_ques7",true));?></textarea>
									
									<div id="ogshelp" class="help-click"> ?</div>
									
									<span class="error" id="ogs_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="ogshelptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
								<?php echo stripslashes($comp_ques7->help_text); ?></div>
							</div>
						<?php
							}
							
							$orgq8 = $objUser->getDefaultFieldData('company_question8');							
							$orgq8_on_off = $orgq8->status;
							?>
							   <input type="hidden" name="orgq8_on_off" id="orgq8_on_off" value="<?php echo $orgq8_on_off; ?>" />
							<?php
							if($orgq8_on_off==1)
							{   
							   $totalVisibleFields = $totalVisibleFields + 1;
							?>
							  <div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo stripslashes($orgq8->question);?>:</label>
								</div>
								<div class="upf-cell column2">
									<textarea cols="100" rows="2" id="org_ques_8" name="org_ques_8"  style="width:297px;"><?php echo stripslashes(get_user_meta($loggedin_id,"org_ques_8",true));?></textarea>
									
									<div id="org8help" class="help-click"> ?</div>
									
									<span class="error" id="org8_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="org8helptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
								<?php echo stripslashes($orgq8->help_text); ?></div>
							</div>
							<?php 
							}	
							
							$orgq9 = $objUser->getDefaultFieldData('company_question9');							
							$orgq9_on_off = $orgq9->status;
							?>
							   <input type="hidden" name="orgq9_on_off" id="orgq9_on_off" value="<?php echo $orgq9_on_off; ?>" />
							<?php
							if($orgq9_on_off==1)
							{
							   $totalVisibleFields = $totalVisibleFields + 1;
							?>
							
								<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo stripslashes($orgq9->question);?>:</label>
								</div>
								<div class="upf-cell column2">								
									<input type="text" id="org_ques_9_state" name="org_ques_9_state" value="<?php echo stripslashes(get_user_meta($loggedin_id,"org_ques_9_state",true))?>" style="width:297px;float:left;" onKeyUp="intOnlyFunction(this);" onKeyDown="intOnlyFunction(this);" onKeyPress="intOnlyFunction(this);">
									<div id="org9help" class="help-click"> ?</div>
									
									<span class="error" id="org9_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="org9helptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
								<?php echo stripslashes($orgq9->help_text); ?></div>
							</div>
							<?php
							}

							$orgq10 = $objUser->getDefaultFieldData('company_question10');							
							$orgq10_on_off = $orgq10->status;
							?>
							   <input type="hidden" name="orgq10_on_off" id="orgq10_on_off" value="<?php echo $orgq10_on_off; ?>" />
							<?php							
							if($orgq10_on_off==1)
							{
							    $totalVisibleFields = $totalVisibleFields + 1;
							?>
								<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo stripslashes($orgq10->question);?>:</label>
								</div>
								<div class="upf-cell column2">
									<!--<textarea cols="100" rows="2" id="org_ques_10_state" name="org_ques_10_state"  style="width:297px;"><?php echo stripslashes(get_user_meta($loggedin_id,"org_ques_10_state",true))?></textarea>-->
									<input type="text" id="org_ques_10_state" name="org_ques_10_state"  style="width:297px;" value="<?php echo stripslashes(get_user_meta($loggedin_id,"org_ques_10_state",true))?>">
																		
									<div id="org10help" class="help-click"> ?</div>
									
									<span class="error" id="org10_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="org10helptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
								<?php echo stripslashes($orgq10->help_text); ?></div>
								 
							</div>
							<?php	} 
							$orgq11 = $objUser->getDefaultFieldData('company_question11');
							$orgq11_on_off = $orgq11->status;
							$multiSelectOption = $orgq11->multiSelectOption;
							$fileds = explode("|",$multiSelectOption);

							$data_dupp = get_user_meta($loggedin_id,"org_ques_11",true);
							
							if (!empty($data_dupp)) {
								$data = array_flip($data_dupp);
							} else {
								$data = array();
							}
							?>
							<input type="hidden" name="orgq11_on_off" id="orgq11_on_off" value="<?php echo $orgq11_on_off; ?>" />
						<?php
							if($orgq11_on_off==1) {
							    $totalVisibleFields = $totalVisibleFields + 1;
							?>
								<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo stripslashes($orgq11->question);?>:</label>
								</div>
								<div class="upf-cell column2">
									<input type="hidden" name="org_ques_11" id="org_ques_11" value="" style="width: auto;margin-right: 5px;">								
									<?php foreach ( $fileds as $k => $filed ) { ?>

										<?php if (array_key_exists( trim($filed), $data )) { ?>
											<label style="float: left; width:auto;margin-right: 5px;"><input type="checkbox" name="org_ques_11[]" id="org_ques_11" value="<?php	echo trim($filed); ?>" style="width: auto;margin-right: 5px;" checked><?php	echo trim($filed); ?></label>
										<?php } else { ?>

											<label style="float: left; width:auto;margin-right: 5px;"><input type="checkbox" name="org_ques_11[]" id="org_ques_11" value="<?php	echo trim($filed); ?>" style="width: auto;margin-right: 5px;"><?php	echo trim($filed); ?></label>
										<?php } ?>

									<?php } ?>
									<div id="org11help" class="help-click"> ?</div>
									<span class="error" id="org11_error"></span>

								</div>
								<div style="display:none;" class="help-text" id="org11helptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
								<?php echo stripslashes($orgq11->help_text); ?></div>
							</div>
						<?php	}  ?>
						<?php
							 $totalCompanyQues = $obj->cquestioncount();
							 $userCompanyQues = $obj->usercquestioncount($loggedin_id);
							
							 $status = $obj->checkUserCompanyInfo($loggedin_id);
							 $hover = ($totalCompanyQues == $userCompanyQues ?'green-complete':'txt');

							 $btn = ($totalCompanyQues == $userCompanyQues ?'green1':'blue1');
							?>
									<?php
									   if( (isset($_COOKIE['org_save_status_'.$userData->ID]) and $_COOKIE['org_save_status_'.$userData->ID] != '') or ($getStatus != "") )
                                        {
                                            $org_save_status = explode(",", $_COOKIE['org_save_status_'.$userData->ID]);
 										    if( ($org_save_status[0] == 'Done' and $org_save_status[1] == $userData->ID) or ($getStatus == "filled") )
											{  ?>
											   <style>
											      .btn-blue1 .btn-inner1 {
													background: none repeat scroll 0 0 #98ec8f;
													border: 0 none;
													border-radius: 3px;
													box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset;
													display: inline-block;
													margin: 1px;
													padding: 2px 20px;
													text-shadow: 0 1px 0 rgba(0, 0, 0, 0.4);
												}
											     .btn-blue1:hover .btn-inner1, .btn-blue1:focus .btn-inner1 {
													background: none repeat scroll 0 0 #61c458;
													box-shadow: 0 1px 5px rgba(57, 130, 173, 0.6) inset;
												}
												.btn-blue1:hover,
												.btn-blue1:focus {
												text-decoration: none;
													background-position: 0;
													-webkit-transition: none;
													 -moz-transition:  none;
													   -o-transition:  none;
														  transition:  none; 
														  *border: 0; 

													-webkit-box-shadow:none;
													   -moz-box-shadow:none;
															box-shadow:none;
													  border-color: none;
													  border-color: none;
													  border-bottom-color: none;
													  background: none;
												}
												.btn-blue1 .btn-inner1 {
													background: none repeat scroll 0 0 #7bd572;
													border: 0 none;
													border-radius: 3px;
													box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset;
													display: inline-block;
													margin: 1px;
													padding: 2px 20px;
													text-shadow: 0 1px 0 rgba(0, 0, 0, 0.4);
												}
											.btn-blue1 {
												background: linear-gradient(to bottom, #7bd572 0%, #7bd572 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);
												border: 0 none;
												border-radius: 3px;
												box-shadow: none;
												color: #fff;
												cursor: pointer;
												display: inline-block;
												filter: none !important;
												font-size: 14px;
												line-height: 28px;
												margin-bottom: 0;
												padding: 0;
												text-align: center;
												text-shadow: 0 1px 0 rgba(0, 0, 0, 0.4);
												vertical-align: middle;
											}
											   </style>

												<div class="upf-buttonsection company-mrgn" id="btnsection" style="background: #98ec8f">
												<a class="btn-green1 btn1 mrgn" href="#" id="save_company"><span class="btn-inner1 fnt d1"><strong>OK!</strong> Save Updates</span></a>
												</div>
											<?php
											}
											else
											{  ?>
												<div class="upf-buttonsection company-mrgn <?php echo $hover;?>" id="btnsection">
												<a class="btn-<?php echo $btn;?> btn1 mrgn" href="#" id="save_company"><span class="btn-inner1 fnt d1"><strong>OK!</strong> Save Updates</span></a> 
												</div>
											<?php
											}
										}
										else
										{  ?>
											 <div class="upf-buttonsection company-mrgn <?php echo $hover;?>" id="btnsection">
											 <a class="btn-<?php echo $btn;?> btn1 mrgn" href="#" id="save_company"><span class="btn-inner1 fnt d1"><strong>OK!</strong> Save Updates</span></a>
											 </div>
									<?php
										}
									?>
																</div>
																<script>
																	 document.getElementById('totalVisibleFields').value = <?php echo $totalVisibleFields; ?>;
																</script>
															 </form>
														</div>
													</div>
												</div>
											</div>
											<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/usercompany.js"></script>
