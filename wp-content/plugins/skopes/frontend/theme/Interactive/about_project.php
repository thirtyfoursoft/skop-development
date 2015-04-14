<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>js/skopes-accordion-main.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>js/skopes-accordion-active.js"></script>
<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/css/jquery-ui.css" rel="stylesheet" media="screen" />
<script>
	$(function() {
		$(".accordion-con").accordion({ collapsible: true, active: 'none', heightStyle: "content" });
		
	});
</script>
<?php
if(isset($_GET['team']) && $_GET['team'] != ''){ 
	$active = $_GET['team'];
?>
<script>
	$(function() {		
		$(".accordion-con").accordion('option', 'active' , <?=$active; ?>);
	});
</script>
<?php
}
?>
<?php
if (!class_exists('userinfo')) {
	include('core/class_userinfo.php');
}
$obj = new userinfo(); 
?>
<div class="content-section clearfix">
	<div class="container">
		<div class="accordion-main-con">
			<div class="page-headding">
			  <h3>ABOUT YOUR PROJECT</h3>
			</div>
			
		<!-- Project information section starts here -->
		<div id="" class="accordion-con">
		<?php
			// get the selection creteria section status value
			$prostatusQry = "SELECT section_status FROM wp_specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='projectquesinfo'";
			$prostatusRes = $wpdb->get_col($prostatusQry); 
			$proStatus =  $prostatusRes[0];

			if($proStatus == 2){
			  $procpltbtn ='green';
			  $procompcls = '<span class="complete pull-right">Complete</span>';
			}elseif($proStatus == 0){
			  $procpltbtn ='grey1';
			  $procompcls = '<span class="notstred pull-right">Not Started</span>';
			}else{
			  $procpltbtn ='grey1';
			  $procompcls = '<span class="incomplete pull-right">In Progress</span>';
			} 
		?>

			<h5 class=""><span class="blue-arrow pull-right"></span>
			<span class="accordion_title">Project motivation, background and budget</span>
			<?php echo $procompcls;?></h5>
			<div>
			
			<div class="accordian-inner-container">
			<form method="post"  class="user-profileform bg-abt about-yr-project" name="frmproject" id="frmproject" action="<?php echo plugin_dir_url( __FILE__  ); ?>projectinitalinfo.php" >
			
					<div class="upf-table l1" id="projectmain"> 
				
						<?php
							$pro_ques1 = $objUser->getDefaultFieldData('project_question1');							
							$pro_ques1_on_off = $pro_ques1->status;
							if($pro_ques1_on_off==1) 
							{
						?>
								<div class="form-two-colum">
									<div class="first-colum">
										<label><?php echo stripslashes($pro_ques1->question);?>:</label>
									</div>
									<div class="second-colum">
									<?php  
									$getAuthor = get_user_meta($loggedin_id,"authors",true);

									if($getAuthor==''){
									?>  
										<input type="text" class="input-text" value="<?php echo htmlspecialchars(stripslashes(the_author_meta( 'first_name', $current_user->ID ))); echo ('&nbsp;'); echo htmlspecialchars(stripslashes(the_author_meta('last_name', $current_user->ID)));?>" name="authors" id="authors" />
										 
									<?php } else {
									       $getAuthorC = htmlspecialchars(stripslashes($getAuthor));
									?>	
										<input type="text" class="input-text" value="<?php echo $getAuthorC;?>" name="authors" id="authors" />
									<?php } ?>
										<div id="proques1help" class="help-click"></div>	
										<span class="error" id="authors_error"></span>
										
									</div>									
									<div id="proques1helptext" class="help-text" style="display:none;">
									<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
									<?php echo stripslashes($pro_ques1->help_text);?></div> 
								</div> 
								
						<?php 
							}

							//Your projects name
							$pro_ques5 = $objUser->getDefaultFieldData('project_question5');
							$pro_ques5_on_off = $pro_ques5->status;
							if($pro_ques5_on_off==1)
							{
							     ?>
							<div class="form-two-colum">
								<div class="first-colum">
									<label><?php echo stripslashes($pro_ques5->question);?>:</label>
								</div>
								<div class="second-colum">
								<?php //echo stripslashes(get_user_meta($loggedin_id,"pro_ques5",true));?>
									<input type="text" class="input-text" value="<?php echo htmlspecialchars(stripslashes(get_user_meta($loggedin_id,"pro_ques5",true)));?>" id="pro_ques5" name="pro_ques5" />
									
									<div id="proques5help" class="help-click"></div>
									
									<span class="error" id="pro_ques5_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="proques5helptext">
									<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
									<?php 	echo chunk_split($pro_ques5->help_text, 120, '<br>');  	?>
									<span class="error" id="pro_ques5_error"></span>
								</div>
							</div>
						<?php
							}

							//Projects contribution to your organisations mission:
							
							$pro_ques2 = $objUser->getDefaultFieldData('project_question2');							
							$pro_ques2_on_off2 = $pro_ques2->status;
							if($pro_ques2_on_off2==1)
							{
						?>
								<div class="form-two-colum">
									<div class="first-colum">
										<label><?php echo stripslashes($pro_ques2->question);?>:</label>
									</div>
									<div class="second-colum">
										<?php //echo stripslashes(get_user_meta($loggedin_id,"pro_ques2",true));?>
										<textarea name="pro_ques2" class="input-textarea" id="pro_ques2" cols="10" rows="5"><?php echo htmlspecialchars(stripslashes(get_user_meta($loggedin_id,"pro_ques2",true)));?></textarea>
										<div id="objhelp" class="help-click"></div>	
										<span class="error" id="pro_ques2_error"></span>
									</div>
										<div id="objhelptext" class="help-text" style="display:none;">
										<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
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

						  <div class="form-two-colum">
							<div class="first-colum">
								<label><?php echo stripslashes($pro_ques3->question);?>:</label>
							</div>
							<div class="second-colum">
							<?php //echo stripslashes(get_user_meta($loggedin_id,"pro_ques3",true));?>
								<textarea name="pro_ques3" class="input-textarea" id="pro_ques3" cols="10" rows="5"><?php echo htmlspecialchars(stripslashes(get_user_meta($loggedin_id,"pro_ques3",true)));?></textarea>								
								<div id="pbhelp" class="help-click"></div>
								
								<span class="error" id="pro_ques3_error"></span>
							</div>
							<div class="help-text" id="pbhelptext" style="display:none;">
							<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png"><?php echo stripslashes($pro_ques3->help_text);?></div> 
						 </div>
						<?php
							}
							
							//Expected project budget:

							$pro_ques4 = $objUser->getDefaultFieldData('project_question4');
							$pro_ques4_on_off = $pro_ques4->status;
							
							if($pro_ques4_on_off==1)
							{ 
						?>

								<div class="form-two-colum">
									<div class="first-colum">
										<label><?php echo stripslashes($pro_ques4->question);?>:</label>
									</div>
									<div class="second-colum">
									<?php //echo stripslashes(get_user_meta($loggedin_id,"pro_ques4",true));?>
										<input type="text" class="input-text" value="<?php echo htmlspecialchars(stripslashes(get_user_meta($loggedin_id,"pro_ques4",true)));?>" id="pro_ques4" name="pro_ques4" />
										
										<div id="proques4help" class="help-click"></div>
										
										<span class="error" id="pro_ques4_error"></span>
									</div>
									<div class="help-text" id="proques4helptext" style="display:none;">
									<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png"><?php echo stripslashes($pro_ques4->help_text);?></div>
								</div>
						<?php
							}
							 
							
							$pro_ques6 = $objUser->getDefaultFieldData('project_question6');							
							$pro_ques6_on_off = $pro_ques6->status;
							if($pro_ques6_on_off==1)
							{
						?>
							<div class="form-two-colum">
								<div class="first-colum">
									<label><?php echo stripslashes($pro_ques6->question);?>:</label>
								</div>
								<div class="second-colum">
									<input type="text" class="input-text" value="<?php echo stripslashes(get_user_meta($loggedin_id,"pro_ques6",true));?>" id="pro_ques6" name="pro_ques6" />
									<div id="pro_ques6_help" class="help-click"></div>
									<span class="error" id="pro_ques6_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="pro_ques6_help_text">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
								<?php echo stripslashes($pro_ques6->help_text);?></div>
							</div>

						 <?php
							}
							
							$pro_ques7 = $objUser->getDefaultFieldData('project_question7');							
							$pro_ques7_on_off = $pro_ques7->status;
							
							if($pro_ques7_on_off==1)
							{
						?>
							<div class="form-two-colum">
								<div class="first-colum">
									<label><?php echo stripslashes($pro_ques7->question);?>:</label>
								</div>
								<div class="second-colum">
									<input type="text" class="input-text" value="<?php echo stripslashes(get_user_meta($loggedin_id,"pro_ques7",true));?>" id="pro_ques7" name="pro_ques7" />
									<div id="pro_ques7help" class="help-click"></div>
									<span class="error" id="dc_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="pro_ques7helptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
								<?php echo stripslashes($pro_ques7->help_text);?></div>
							</div>
						 <?php
							}
							
							$pro_ques8 = $objUser->getDefaultFieldData('project_question8');							
							$pro_ques8_on_off = $pro_ques8->status;
							if($pro_ques8_on_off==1)
							{
						?>
							<div class="form-two-colum">
								<div class="first-colum">
									<label><?php echo stripslashes($pro_ques8->question);?>:</label>
								</div>
								<div class="second-colum">
									<input type="text" class="input-text" value="<?php echo stripslashes(get_user_meta($loggedin_id,"pro_ques8",true));?>" id="pro_ques8" name="pro_ques8" />
									<div id="pro_ques8help" class="help-click"></div>
									<span class="error" id="utc_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="pro_ques8helptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
								<?php echo stripslashes($pro_ques8->help_text);?></div>
							</div>
						<?php
							}
							
							$pro_ques9 = $objUser->getDefaultFieldData('project_question9');							
							$pro_ques9_on_off = $pro_ques9->status;
							if($pro_ques9_on_off==1)
							{
							
						?>
							<div class="form-two-colum">
								<div class="first-colum">
									<label><?php echo stripslashes($pro_ques9->question);?>:</label>
								</div>
								<div class="second-colum">
									<input type="text" class="input-text" value="<?php echo stripslashes(get_user_meta($loggedin_id,"pro_ques9",true));?>" id="pro_ques9" name="pro_ques9" />
									<div id="pro_ques9help" class="help-click"></div>
									<span class="error" id="tcsl_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="pro_ques9helptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
								<?php echo stripslashes($pro_ques9->help_text);?></div>
							</div>
						<?php	}	?>
							
						<?php		
						
						$pro_ques10 = $objUser->getDefaultFieldData('project_question10');							
							$pro_ques10_on_off = $pro_ques10->status;
							if($pro_ques10_on_off==1)
							{
						?>

						<div class="form-two-colum">
								<div class="first-colum">
									<label><?php echo stripslashes($pro_ques10->question);?>:</label>
								</div>
								<div class="second-colum">
									<input type="text" class="input-text" value="<?php echo stripslashes(get_user_meta($loggedin_id,"proques10",true)); ?>" id="proques10" name="proques10" />
									<div id="pro_ques10help" class="help-click"></div>
									<span class="error" id="tcs10_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="pro_ques10helptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
								<?php echo stripslashes($pro_ques10->help_text);?></div>
							</div>
						
						<?php  } 	?>
					</div>

					<div class="form-btn">
						<input type="submit" value="SAVE PROGRESS" name="save_project" class="progress-btn">
						<input type="button" id="saveprojectinfo" value="Complete" name="saveprojectinfo" class="compelete-btn <?php echo $procpltbtn;?>">
					</div>
					<div id="projectresultmsg" class ="savemsgtxt" ></div>
					<div id="projectresult" style="text-align:center;"></div>
				</form>
				</div>
			</div>
			<!-- Project information section ENDS here -->
			
			<!-- Team information section STARTS here -->
			
			<?php
				
			// get the selection creteria section status value
			$teamstatusQry = "SELECT section_status FROM wp_specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='teaminialinfo'";
			$tstatusRes = $wpdb->get_col($teamstatusQry); 
			
			$tStatus =  $tstatusRes[0];
			if($tStatus == 2){
			  $teamcpltbtn ='green';
			  $teamcompcls = '<span class="complete pull-right">Complete</span>';
			}elseif($tStatus == 0){
			  $teamcpltbtn ='grey1';
			  $teamcompcls = '<span class="notstred pull-right">Not Started</span>';
			}else{
			  $teamcpltbtn ='grey1';
			  $teamcompcls = '<span class="incomplete pull-right">In Progress</span>';
			} 
		?>
			<h5 class=""><span class="blue-arrow pull-right"></span>
			<span class="accordion_title">Team members</span>
			<?php echo $teamcompcls;?></h5>
					
			<div class="">

		<div class="accordian-inner-container teamMembers">

              <div class="form-three-colum">
                <div class="first-colum" style="text-indent: -9999px;">XYZ</div>
				<div class="second-colum">
					 <b>Name</b>
                  </div>
                <div class="third-colum">
					<b>CONTACT EMAIL</b>
					<div id="temMemnerhelp" class="help-click"></div>
                </div>
              </div>		
			
			<script>
			    function addnewField()
				{
				    var oldV = parseInt(document.getElementById('totalmembersID').value);
					oldV = oldV + 1;
					document.getElementById('totalmembersID').value = oldV; 
				}
				function deletenewField()
				{
				    var oldV = parseInt(document.getElementById('totalmembersID').value);
					oldV = oldV - 1;
					document.getElementById('totalmembersID').value = oldV; 
					
					var latestV = parseInt(document.getElementById('totalmembersID').value);
					var laeVaues = ""; 
					for(var i=1; i<=oldV+1; i++)
					{    
						if($('#memberRowID_'+i).length)
						{ 
						       if(laeVaues == '')
							   {
								   laeVaues = i;     
							   }
							   else
							   {
								   laeVaues += "," + i;
							   }    
						}
					}
					document.getElementById('totalmembersNumbID').value = laeVaues;
				}
			</script>
			<form method="post"  class="bg-abt" name="frmteaminfo" id="frmteaminfo" action="<?php echo plugin_dir_url( __FILE__  ); ?>teaminfo.php" >
			<!-- onsubmit="return validate_teaminfoform()" --> 
			
			<?php $teamresultarray = $objUser->selectTeamInfo($loggedin_id);?>
			<input type="hidden" name="totalmembers" id="totalmembersID" value="" />
			<input type="hidden" name="totalmembersNumb" id="totalmembersNumbID" value="" />
			<input type="hidden" name="lastAddedList" id="lastAddedList" value="" />
			<div id="teammaininfo">
			<div id="teaminitalinfo">	
                  <div id="teamMemAddedID" style="padding: 0px 15px; color: blue;"></div>			
			<?php 
				if(count($teamresultarray)>0){
					$memberresultarray = $objUser->selectTeamMemberInfo($loggedin_id);
					$tlresultarray = $objUser->selectTeamLeadInfo($loggedin_id);
				
					foreach ($tlresultarray as $tlkey=>$tlval){
					
						$tmemberId = $tlval['id'];
						$tmemberType = $tlval['member_type'];
						$tmemberName = htmlspecialchars_decode($tlval['member_name'], ENT_NOQUOTES);
						$tmemberEmail = $tlval['member_email'];
					?>
						<div class="tldetails">

						<div class="form-three-colum">
							<div class="first-colum">Team Lead</div>
							<div class="second-colum">
								 <input type="text" value="<?php echo htmlspecialchars(stripslashes($tmemberName));?>" id="teamleadname" name="teamleadname" class="input-text"><span id="error_teamleadname" class="error"></span>
							  </div>
							<div class="third-colum">
								<input type="text" value="<?php echo htmlspecialchars(stripslashes($tmemberEmail));?>" id="teamlead_email" name="teamlead_email" class="input-text">
								<input type="hidden" value="<?php echo $tmemberId;?>" id="tlid" name="tlid" class="umid" maxlength="100"><span id="error_teamlead_email" class="error"></span>
							</div>
						</div>
					</div>
					
					<?php }
				$i=0; $totalAddedList = ''; $lastAddedList = 0; ?>
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

		          <div class="form-three-colum">
		            <div class="first-colum">New Member</div>
					<div class="second-colum">
						<input type="text" value="<?php echo htmlspecialchars(stripslashes($memberName));?>" id="membername_<?=$i;?>" name="membername[]" maxlength="50" class="input-text"><span id="error_membername_<?=$i;?>" class="error"></span>
		              </div>
		            <div class="third-colum">
						<input type="text" value="<?php echo htmlspecialchars(stripslashes($memberEmail));?>" id="memberemail_<?=$i;?>" name="memberemail[]" class="teamemail input-text" maxlength="100" class="error" style="width:89%;">
						<input type="hidden" value="<?php echo $memberId;?>" id="memberid<?=$i;?>" name="memberid[]" class="umid" maxlength="100">
							<a style="color: #ff0015 !important;font-size: 18px; font-weight: bold; margin-left: 5px;" onclick="removeteam_members('memberdetails_<?=$i;?>', <?php echo $memberId; ?>); deletenewField();" href="javascript:void(0);">x</a>
						<span id="error_memberemail_<?=$i;?>" class="error"></span>
		            </div>
		          </div>

				</div>
				
		<?php
		       $totalFields = $i;
			   if($totalAddedList == '')
			   {
			       $totalAddedList = $i;     
			   }
			   else
			   {
			       $totalAddedList .= "," . $i;
			   } 
			   $lastAddedList = $i;
		} // end of foreach 
		?>
		</div>
		         <script>
				         document.getElementById('totalmembersID').value = <?php echo $totalFields; ?>;
						 document.getElementById('totalmembersNumbID').value = '<?php echo $totalAddedList; ?>'; 
						 document.getElementById('lastAddedList').value = '<?php echo $lastAddedList; ?>';
				 </script>
		<?php } else { ?>

              <div class="form-three-colum">
                <div class="first-colum">Team Lead</div>
				<div class="second-colum">
					 <input type="text" id="teamleadname" name="teamleadname" class="input-text"><span id="error_teamleadname" class="error"></span>
                  </div>
                <div class="third-colum">
					<input type="text" id="teamlead_email" name="teamlead_email" class="input-text">
					<span id="error_teamlead_email" class="error"></span>
                </div>
            </div>
			
			<!-- member info section -->
			<div id="memberinfo">	 
				<div class="memberdetails">

		          <div class="form-three-colum">
		            <div class="first-colum">New Member</div>
					<div class="second-colum">
						 <input type="text" value="" id="membername_1" name="membername[]" class="input-text"><span id="error_membername_1" class="error"></span>
		              </div>
		            <div class="third-colum">
						<input type="text" value="" id="memberemail_1" name="memberemail[]" class="teamemail input-text">
						<span id="error_memberemail_1" class="error"></span>
		            </div>
		        </div>
				</div>  
			</div>
<?php }?>			 
			</div>	

			</div>

			<div id="temMemnerhelptext" class="help-text" style="display:none; font-family: 'Lato', sans-serif;">
				<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
				<?php echo get_option("help_teamMembergText")?>
			</div>

			<div class="teamMember_button">
				<input type="button" id="AddMoreMemberInfo" value="Add Member" class="addnew progress-btn" onclick="addnewField();">
			</div>

			<div class="form-btn">
				<input type="submit" value="SAVE PROGRESS" name="savememberinfo" class="progress-btn">
				<input type="hidden" value="0" id="checkForCompleteClick" name="checkForCompleteClick">
				<input type="button" id="cptlteam" value="Complete" name="cptlteam" class="compelete-btn <?php echo $teamcpltbtn;?>">
			</div>

			<!--<span style="padding: 0 20px; display: block;">Note: <br/> - Each member's email must be unique.
			<br/> - You can only add maximum 6 members. </span>-->
			<div id="teamresultmsg" class ="savemsgtxt" ></div>
			<div id="teamresult" style="text-align:center;"></div>

		    </form>
</div>			
		</div>
		<!-- Team information section ENDS here -->
		
		<!-- Stake holder starts here -->
		<?php
				
			// get the stake holder section status value
			$stakholderstatus = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='stakeholderinfo'";
			$shstatusRes = $wpdb->get_col($stakholderstatus); 
			
			$shstatus =  $shstatusRes[0];
			if($shstatus == 2){
			  $shcpltbtn ='green';
			  $shcompcls = '<span class="complete pull-right">Complete</span>';
			}elseif($shstatus == 0){
			  $shcpltbtn ='grey1';
			  $shcompcls = '<span class="notstred pull-right">Not Started</span>';
			}else{
			  $shcpltbtn ='grey1';
			  $shcompcls = '<span class="incomplete pull-right">In Progress</span>';
			}
		?>

		<h5 class=""><span class="blue-arrow pull-right"></span>
		<span class="accordion_title">Stakeholder Groups</span>
		<?php echo $shcompcls;?></h5>

		<div class="">
		<div class="accordian-inner-container stakeholders">

		  <div class="form-two-colum">
			<div class="first-colum" style="text-indent: -9999px;">Stakeholders Name</div>
			<div class="second-colum">
				<b>Stakeholders Name</b>
					<div id="proquesstakeholder" class="help-click"></div>
			</div>
		  </div>

		<div class="accordian-inner">
		
			<div class="">
			<form method="post" class="bg-abt" name="frmstackholder" id="frmstackholder" action="<?php echo plugin_dir_url( __FILE__  ); ?>stakeholder.php">
				<!-- onsubmit="return fnvalidate_stackholder()" -->
			   <?php $Selectsh = $objUser->selectStakeholders($loggedin_id);
			  
			    $shval = $Selectsh['fieldvalue'];
				$sharray = explode(',',$shval); 
				
				if(count($sharray) == 0){ ?>
					<div id="InputsWrapper">

						  <div class="form-two-colum">
							<div class="first-colum">Stakeholder Group 1</div>
							<div class="second-colum">
								<input type="text" value="" id="stackholder_1" name="stackholder[]" class="input-text"><span id="error_stackholder_1" class="error sherror"></span>
							</div>
						  </div>
					</div>
					
				<?php } else { ?>
				<div id="InputsWrapper1">
				<?php	foreach($sharray as $shak=>$shval) {	?>

						  <div class="form-two-colum">
							<div class="first-colum">Stakeholder Group <?=$shak+1;?></div>
							<div class="second-colum">
								<input type="text" value="<?php echo htmlspecialchars(stripslashes($shval));?>" id="stackholder_<?=$shak+1;?>" name="stackholder[]" class="stackholders input-text"><span id="error_stackholder_<?=$shak+1;?>" class="error sherror"></span>
								<?php if ($shak != 0): ?>
									<a class="removeclass" href="#">×</a>
								<?php endif; ?>
							</div>
						  </div>
				<?php	}	?>
					</div>
				<?php	}	 ?>
			</div>
			
			<div id="proquesstakeholdertext" class="help-text" style="display:none;font-family: 'Lato', sans-serif;">
				<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png" style="right: 50%;">
					<?php echo get_option("help_stakeholderText")?>
			</div>

			<div class="stakeholder_button">
				<?php if(count($sharray) == 0){ ?>
					<input type="button" id="AddMoreFileBox" value="Add Stakeholder" class="addnew progress-btn">
				<?php }else{ ?>
					<input type="button" id="AddMoreFileBox1" value="Add Stakeholder" class="addnew progress-btn">
				<?php }?>
			</div>

			<div class="form-btn">
				<input type="hidden" name="hiddenaction" value="saveshlist">
				<input type="submit" value="SAVE PROGRESS" name="savestackholder" class="progress-btn">
				<input type="button" value="Complete" name="cmpltStakeholder" id="cmpltStakeholder" class="compelete-btn <?=$shcpltbtn; ?>">
			</div>
			<span style="padding: 0 20px;">Note: You can only add a maximum of 12 stakeholder. </span>			

				<div class="cdiv">
					<div id="shimag"></div>
					<div id="shmsg"></div>
				</div>
				</div>
			</form>
		</div>
</div>		
		
		<!-- sTAKEHOLDER SECTION ENDS HERE -->

		<! --  **********  Start key dates  ******************* -->

		<?php 
			$keysDate = get_user_meta( $loggedin_id, '_keysProjectDate', true);

			if (empty($keysDate)) {

				$data_dup = array(
					'project_commencement' => '',
					'supplier' => '',
					'project_completion' => ''
				);
				$data = serialize($data_dup);
				add_user_meta( $loggedin_id, '_keysProjectDate', $data);

				global $wpdb;
				$query = $wpdb->query("INSERT INTO " . $wpdb->prefix . "specdoc_userstatusinfo SET section_name = 'keysProjectDate', userid='" . $loggedin_id . "', section_status = '0' ");
				
			}

			$keysDate = get_user_meta( $loggedin_id, '_keysProjectDate', true);
			$dateKeys = unserialize($keysDate); 

			$query = $wpdb->get_col("SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='keysProjectDate'");

			$dateKeysStatus = $query[0];

			if($dateKeysStatus == 2){
			  $datebtn ='green';
			  $datecompcls = '<span class="complete pull-right">Complete</span>';
			}elseif($dateKeysStatus == 0){
			  $datebtn ='grey1';
			  $datecompcls = '<span class="notstred pull-right">Not Started</span>';
			}else{
			  $datebtn ='grey1';
			  $datecompcls = '<span class="incomplete pull-right">In Progress</span>';
			}			
		?>
			<h5><span class="blue-arrow pull-right"></span>
			<span class="accordion_title">Key project dates</span>
			<?php echo $datecompcls;?></h5>		
		<div class="">
			<div class="stakeholders accordian-inner-container">

				  <div class="form-two-colum">
					<div class="first-colum" style="text-indent: -9999px;">Project commencement</div>
					<div class="second-colum">
						<b>Key project dates</b>
						<div id="keyDates" class="help-click"></div>
					</div>
				  </div>
						<form method="post" class="bg-abt" name="keysDate" id="keysDate" action="<?php echo plugin_dir_url( __file__ ); ?>keyDates.php">
							<div id="InputsWrapper">
							
								  <div class="form-two-colum">
									<div class="first-colum">Project commencement</div>
									<div class="second-colum">
										<input type="text" value="<?php	echo $dateKeys['project_commencement']; ?>" id="project_commencement" name="project_commencement" class="pickdate input-text" readonly="readonly"><span id="error_project_commencement" class="error"></span>
									</div>
								  </div>

								  <div class="form-two-colum">
									<div class="first-colum">Supplier / technology selected</div>
									<div class="second-colum">
										<input type="text" value="<?php	echo $dateKeys['supplier']; ?>" id="supplier" name="supplier" class="pickdate input-text" readonly="readonly"><span id="error_supplier" class="error" readonly="readonly"></span>						
									</div>
								  </div>

								  <div class="form-two-colum">
									<div class="first-colum">Project completion</div>
									<div class="second-colum">
										<input type="text" value="<?php	echo $dateKeys['project_completion']; ?>" id="project_completion" name="project_completion" class="pickdate input-text" readonly="readonly"><span id="error_project_completion" class="error"></span>
									</div>
								  </div>

						<div id="keyDatestext" class="help-text" style="display:none;font-family: 'Lato', sans-serif;">
							<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL; ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png" style="right: 50%;"><?php echo stripslashes(trim(get_option("help_keysDateText"))); ?>
						</div>

						<div class="form-btn">
							<input type="hidden" name="hiddenaction" value="savekeysDate">
							<input type="submit" value="SAVE PROGRESS" name="savekeysprojectdate" class="progress-btn">
							<input type="button" value="Complete" name="cmpltkeysDate" id="cmpltkeysDate" class="compelete-btn <?=$datebtn; ?>">
						</div>
						<div id="msresultmsg" class ="savemsgtxt"></div>
						<div id="keysDataresult" style="text-align:center;"></div>
					</div>
				</form>
			</div>
		</div>				
		<!--  **********   End key dates *********************** -->

		<!--  Add new section for Risk Mangement *********** Made By Royal Tyagi -->
<?php

	include("include/block-stage-one-pdf.php");
	$riskStaus = getRiskSectionStatus('risk_section');

	$is_risk_management = get_option("risk_management");
	if ($is_risk_management == 1 && $riskStaus ==1 ):

		global $wpdb;
		$query = $wpdb->query("SELECT * FROM " . $wpdb->prefix . "specdoc_userstatusinfo WHERE section_name = 'riskManagement' AND userid = '" . $loggedin_id . "'");
		if ($query == 0) {
			$query = $wpdb->query("INSERT INTO " . $wpdb->prefix . "specdoc_userstatusinfo SET section_name = 'riskManagement', userid = '" . $loggedin_id . "', section_status = 0");
		}
	
		// get the selection creteria section status value
		$query = "SELECT section_status FROM wp_specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='riskManagement'";
		$riskStatus = $wpdb->get_col($query);

		$riskStat =  $riskStatus[0];
		if($riskStat == 2) {
		  $procpltbtn ='green';
		  $procompcls = '<span class="complete pull-right">Complete</span>';
		} elseif ($riskStat == 1) {
		  $procpltbtn ='grey1';
		  $procompcls = '<span class="incomplete pull-right">In Progress</span>';		  

		} else {
		  $procpltbtn ='grey1';
		  $procompcls = '<span class="notstred pull-right">Not Started</span>';
		}

		$risk_row = 0;
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

	$getAllRiskManagementBlock = $obj ->getAllRiskManagementBlock($loggedin_id);
?>
		<h5 class=""><span class="blue-arrow pull-right"></span>
		<span class="accordion_title">Risks</span>
		<?php echo $procompcls;?></h5>

	<div class="">
		<div class="riskManagement accordian-inner-container">

			<div class="form-three-colum">
                <div class="first-colum"><b>Risk Name</b></div>
				<div class="second-colum">
					 <b>Likelihood of occurrence</b>
                  </div>
                <div class="third-colum">
					<b>Type of Risk</b>
					<div id="riskManagementhelp" class="help-click"></div>
                </div>
              </div>		

			<form method="post"  class="bg-abt" name="formRiskManagement" id="formRiskManagement" action="<?php echo plugin_dir_url( __FILE__  ); ?>riskManagement.php" >
				<div id="riskManagementInfo">
				<?php if (!empty( $getAllRiskManagementBlock)) { ?>
					<?php foreach ($getAllRiskManagementBlock as $k => $v) { ?>

						<div class="riskBlock form-three-colum" id="riskBlock_<?=$risk_row; ?>">
							<input type="hidden" class="riskStatus" value="<?php echo $v['id'];?>" name="risk[<?=$k; ?>][id]">
							<div class="first-colum"><input type="text" value="<?php echo $v['riskName']; ?>" name="risk[<?=$risk_row; ?>][riskName]" class="required input-text" maxlength="64"/></div> 
							<div class="second-colum">
								<select name="risk[<?=$risk_row; ?>][LikelihoodOfOccurrence]" class="required">
									<?php foreach ( $LikelihoodOfOccurrence as $kev => $vel ) {
											if ($kev == $v['LikelihoodOfOccurrence']) { ?>
											<option value="<?php echo $kev; ?>" selected="selected"><?php echo $vel; ?></option>
										<?php } else { ?>
											<option value="<?php echo $kev; ?>"><?php echo $vel; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
							<div class="third-colum">
								<select name="risk[<?=$risk_row; ?>][riskType]" class="required">
									<?php foreach ( $riskType as $kev => $vel ) {
											if ($kev == $v['riskType']) { ?>
											<option value="<?php echo $kev; ?>" selected="selected"><?php echo $vel; ?></option>
										<?php } else { ?>
											<option value="<?php echo $kev; ?>"><?php echo $vel; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
								<?php if ( $risk_row > 0 ) { ?>
									<a style="color: #ff0015 !important; display: inline-block; font-size: 18px; font-weight: bold; right: 30px; margin-top: 0px; float: right;"  href="javascript:void(0);" id="removeRiskBlock" onclick="removeRiskBlock('<?=$risk_row; ?>' , <?=$v['id']; ?>);">x</a>
								<?php } ?>
							</div>
							<div class="full-colum">
								<textarea name="risk[<?=$risk_row; ?>][riskDescription]" rows="4" cols="50" placeholder="Description" class="required input-textarea"><?php echo $v['riskDescription']; ?></textarea>
							</div>
						</div><!-- #riskBlock End -->
						
					<?php $risk_row++;
					 } ?>
				<?php } else { ?>

					<div class="riskBlock form-three-colum">
     					<input type="hidden" class="riskStatus" value="" name="risk[<?=$k; ?>][id]">
						<div class="first-colum"><input type="text" value="" name="risk[<?=$risk_row; ?>][riskName]" class="required input-text" /></div> 
						<div class="second-colum">
							<select name="risk[<?=$risk_row; ?>][LikelihoodOfOccurrence]" class="required">
								<?php foreach ( $LikelihoodOfOccurrence as $k => $v ) { ?>
									<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="third-colum">
							<select name="risk[<?=$risk_row; ?>][riskType]" class="required">
								<?php foreach ( $riskType as $k => $v ) { ?>
									<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="full-colum">
							<textarea name="risk[<?=$risk_row; ?>][riskDescription]" rows="4" cols="50" placeholder="Description" class="required input-textarea"></textarea>
						</div>
					</div><!-- #riskBlock End -->
					<?php	$risk_row++; ?>
				<?php } ?>

				</div><!-- #riskManagementInfo End -->

			<div id="riskManagementhelptext" class="help-text" style="display:none;font-family: 'Lato', sans-serif;">
				<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
				<?php echo get_option("help_riskManagementhelptext")?>
			</div>

			<div class="teamMember_button">
				<input type="button" id="AddMoreMemberInfo" value="Add Risk" class="addnew progress-btn" onclick="addnewRiskBlock();">
			</div>

			<div class="form-btn">
				<input type="hidden" value="0" id="checkForCompleteRiskManagement" name="checkForCompleteRiskManagement">
				<input type="submit" value="SAVE PROGRESS" name="savememberinfo" class="progress-btn">
				<input type="button" value="Complete" name="complteRiskManagement" id="complteRiskManagement" class="compelete-btn <?php echo $procpltbtn;?>">
			</div>

			<!--<span style="padding: 0 20px; display: block;">Note:
			<br/> - You can only add a maximum of 10 risks. </span>-->

			<div id="riskResultMsg" class ="savemsgtxt" ></div>
			<div id="riskResult" style="text-align:center;"></div>

			</form>

		</div><!--  End riskManagement div -->
	</div>

	<?php endif; ?>

		<!--  End code for section for Risk Mangement  *********** Made By Royal Tyagi -->

</div>
		<!-- end of accordian -->
	
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/userproject.js"></script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>

<script type="text/javascript"><!--
	var risk_row = <?php echo $risk_row; ?>;

    function addnewRiskBlock() {
		var maxRiskBlock = 10;
		var alreadyAdded = $('#riskManagementInfo').find('div.riskBlock').size();

		if (alreadyAdded >= maxRiskBlock ) {
			return false;
		}

		html= '<div class="riskBlock form-three-colum" id="riskBlock_'+risk_row+'">';
		html +=	'<div class="first-colum"><input type="text" value="" name="risk['+risk_row+'][riskName]" class="required input-text"></div> ';
		html +=	'<div class="second-colum">';
		html +=		'<select name="risk['+risk_row+'][LikelihoodOfOccurrence]" class="required">';
		<?php foreach ( $LikelihoodOfOccurrence as $k => $v ) { ?>
			html +=	'<option value="<?php echo $k; ?>"><?php echo $v; ?></option>';
		<?php } ?>
		html +=		'</select>';
		html +=	'</div>';
		html +=	'<div class="third-colum">';
		html +=		'<select name="risk['+risk_row+'][riskType]" class="required">';
		<?php foreach ( $riskType as $k => $v ) { ?>
			html +=	'<option value="<?php echo $k; ?>"><?php echo $v; ?></option>';
		<?php } ?>
		html +=		'</select>';
		html += '<a style="color: #ff0015 !important; display: inline-block; font-size: 18px; font-weight: bold; right: 30px; margin-top: 0px;float: right;"  href="javascript:void(0);" id="removeRiskBlock" onclick="removeRiskBlock('+risk_row+' ,0);">x</a>';		
		html +=	'</div>';
		html +=	'<div class="full-colum">';
		html +=		'<textarea name="risk['+risk_row+'][riskDescription]" rows="4" cols="50" placeholder="Description" class="required input-textarea"></textarea>';
		html +=	'</div>';
		html += '</div>';

		$('#riskManagementInfo').append(html);

		risk_row++;
	}

	$("#formRiskManagement").validate({
		 submitHandler: function(form) {
//		 	form.submit();
		    $.ajax({
		        type: $('#formRiskManagement').attr('method'),
		        url: $('#formRiskManagement').attr('action'),
		        data: $('#formRiskManagement').serialize(),
		        beforeSend: function() {
					$('#riskResult').show('');
		            $('#riskResult').html('<img src="/wp-content/plugins/skopes/frontend/theme/<?=$choosenTheme; ?>/images/loader.gif">');				
		        },
		        success: function(data) {
					$('#riskManagementInfo').html(data);
					$('#riskResult').hide();
					$('#riskResultMsg').html('Information Saved');
					$('#riskResultMsg').hide().fadeIn(800).delay(3000).fadeOut(800);
					$('#riskResult').html('');
					var riskStatus = $('#formRiskManagement').find('#riskStatus').val();

					if(riskStatus == 1) {

						$('#ui-accordion-1-header-4 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete'); 
						$('#ui-accordion-1-header-4 span:nth-child(4)').html('In Progress');
						$("#complteRiskManagement").removeClass("green").addClass("grey1");

					} else {

						$('#ui-accordion-1-header-4 span:nth-child(4)').removeClass('notstred incomplete').addClass('complete'); 
						$('#ui-accordion-1-header-4 span:nth-child(4)').html('Complete');
						$("#complteRiskManagement").removeClass("grey1").addClass("green");

					}
		        }
		    });
            return false;
		 }
	});

	$('#complteRiskManagement').on('click', function(event) {
		$('#checkForCompleteRiskManagement').val('1');
		$("#formRiskManagement").submit();
		$('#checkForCompleteRiskManagement').val('0');
	});

	function removeRiskBlock( blockName ,blockID ) {
		if (blockID > 0) {

			var r = confirm("Are you sure you want to delete the member from team");
			if (r == true) {
				$.ajax({
					type: 'POST',
					url: '/wp-content/plugins/skopes/frontend/theme/<?=$choosenTheme; ?>/my_ajax_functions.php',
					data: {query: 'delete_risk_block', blockID: blockID},
					beforeSend: function() {
						$('#riskResult').show('');
					    $('#riskResult').html('<img src="/wp-content/plugins/skopes/frontend/theme/<?=$choosenTheme; ?>/images/loader.gif">');
					},
					success: function(data) {
					     if(data.trim() == 'delete done') {

							$('#riskResult').hide();
							$('#riskResultMsg').html('Your risk block has been deleted.');
							$('#riskResultMsg').hide().fadeIn(800).delay(3000).fadeOut(800);

							$('#riskBlock_'+blockName).remove();

						 }
					}
				}); 

			} else {
				return false;
			}

		} else {
			$('#riskBlock_'+blockName).remove();
		}
	}

//--></script>
