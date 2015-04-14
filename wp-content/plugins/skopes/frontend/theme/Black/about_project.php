<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>js/skopes-accordion-main.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>js/skopes-accordion-active.js"></script>
<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/css/jquery-ui.css" rel="stylesheet" media="screen" />
<script>
	$(function() {
		$("#accordion").accordion({ collapsible: true, active: 'none', heightStyle: "content" });
		
	});
</script>
<?php
if(isset($_GET['team']) && $_GET['team'] != ''){ 
	$active = $_GET['team'];
?>
<script>
	$(function() {		
		$("#accordion").accordion('option', 'active' , <?=$active; ?>);
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
		<div class="subpage-content">
			<div class="box">
				<h2 class="boxheading">About your project</h2>				
			</div>
			
		<!-- Project information section starts here -->
		<div id="accordion" class="k1">
		<?php
				
			// get the selection creteria section status value
			$prostatusQry = "SELECT section_status FROM wp_specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='projectquesinfo'";
			$prostatusRes = $wpdb->get_col($prostatusQry); 
			
			$proStatus =  $prostatusRes[0];
			if($proStatus == 2){
			  $probg = 'green-complete';
			  $procpltbtn ='green1';
			  $procompcls = '<span class="comp-gr font14 progress-title">&nbsp;(Complete)</span>';
			}elseif($proStatus == 0){
			  $probg = 'txt';
			  $procpltbtn ='grey1';
			  $procompcls = '<span class="notstart-wh font14 progress-title">&nbsp;(Not Started) </span>';
			}else{
			  $probg = 'txt';
			  $procpltbtn ='grey1';
			  $procompcls = '<span class="incomp-bl font14 progress-title">(In Progress)</span>';
			} 
		?>

			<h3 class="abtprjct-new">Project motivation, background and budget
			<?php echo $procompcls;?></h3>
			<div>
			
			<div class="accordian_inner_content">
			<form method="post"  class="user-profileform bg-abt about-yr-project" name="frmproject" id="frmproject" action="<?php echo plugin_dir_url( __FILE__  ); ?>projectinitalinfo.php" >
			
					<div class="upf-table l1" id="projectmain"> 
				
						<?php
							$pro_ques1 = $objUser->getDefaultFieldData('project_question1');							
							$pro_ques1_on_off = $pro_ques1->status;
							if($pro_ques1_on_off==1) 
							{
						?>
								<div class="upf-raw">
									<div class="upf-cell column1">
										<label><?php echo stripslashes($pro_ques1->question);?>:</label>
									</div>
									<div class="upf-cell column2">
									<?php  
									$getAuthor = get_user_meta($loggedin_id,"authors",true);
																		
									//if author name is empty then , use current user name as author name
									/*
									<?php echo stripslashes(the_author_meta( 'first_name', $current_user->ID ));echo ('&nbsp;');echo stripslashes(the_author_meta( 'last_name', $current_user->ID ));?>
									
									<?php echo stripslashes($getAuthor);?>
									*/   
									if($getAuthor==''){
									?>  
										<input type="text" value="<?php echo htmlspecialchars(stripslashes(the_author_meta( 'first_name', $current_user->ID ))); echo ('&nbsp;'); echo htmlspecialchars(stripslashes(the_author_meta('last_name', $current_user->ID)));?>" name="authors" id="authors" />
										 
									<?php } else { 
									       $getAuthorC = htmlspecialchars(stripslashes($getAuthor));
									?>	
										<input type="text" value="<?php echo $getAuthorC;?>" name="authors" id="authors" />
									<?php } ?>
										<div id="proques1help" class="help-click"> ?</div>	
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
							<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo stripslashes($pro_ques5->question);?>:</label>
								</div>
								<div class="upf-cell column2">
								<?php //echo stripslashes(get_user_meta($loggedin_id,"pro_ques5",true));?>
									<input type="text" value="<?php echo htmlspecialchars(stripslashes(get_user_meta($loggedin_id,"pro_ques5",true)));?>" id="pro_ques5" name="pro_ques5" />
									
									<div id="proques5help" class="help-click"> ?</div>
									
									<span class="error" id="pro_ques5_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="proques5helptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
								<?php //echo stripslashes($pro_ques5->help_text);
								      echo chunk_split($pro_ques5->help_text, 120, '<br>');  
								?>
								<span class="error" id="pro_ques5_error"></span>
								<?php //echo htmlspecialchars_decode($pro_ques5->help_text, ENT_NOQUOTES); ?></div>
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
										<?php //echo stripslashes(get_user_meta($loggedin_id,"pro_ques2",true));?>
										<textarea name="pro_ques2" id="pro_ques2" cols="10" rows="5"><?php echo htmlspecialchars(stripslashes(get_user_meta($loggedin_id,"pro_ques2",true)));?></textarea>
										<div id="objhelp" class="help-click"> ?</div>	
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

						  <div class="upf-raw">
							<div class="upf-cell column1">
								<label><?php echo stripslashes($pro_ques3->question);?>:</label>
							</div>
							<div class="upf-cell column2">
							<?php //echo stripslashes(get_user_meta($loggedin_id,"pro_ques3",true));?>
								<textarea name="pro_ques3" id="pro_ques3" cols="10" rows="5"><?php echo htmlspecialchars(stripslashes(get_user_meta($loggedin_id,"pro_ques3",true)));?></textarea>								
								<div id="pbhelp" class="help-click"> ?</div>
								
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

								<div class="upf-raw">
									<div class="upf-cell column1">
										<label><?php echo stripslashes($pro_ques4->question);?>:</label>
									</div>
									<div class="upf-cell column2">
									<?php //echo stripslashes(get_user_meta($loggedin_id,"pro_ques4",true));?>
										<input type="text" value="<?php echo htmlspecialchars(stripslashes(get_user_meta($loggedin_id,"pro_ques4",true)));?>" id="pro_ques4" name="pro_ques4" />
										
										<div id="proques4help" class="help-click"> ?</div>
										
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
							<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo stripslashes($pro_ques6->question);?>:</label>
								</div>
								<div class="upf-cell column2">
									<input type="text" value="<?php echo stripslashes(get_user_meta($loggedin_id,"pro_ques6",true));?>" id="pro_ques6" name="pro_ques6" />
									<div id="pro_ques6_help" class="help-click"> ?</div>
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
							<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo stripslashes($pro_ques7->question);?>:</label>
								</div>
								<div class="upf-cell column2">
									<input type="text" value="<?php echo stripslashes(get_user_meta($loggedin_id,"pro_ques7",true));?>" id="pro_ques7" name="pro_ques7" />
									<div id="pro_ques7help" class="help-click"> ?</div>
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
							<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo stripslashes($pro_ques8->question);?>:</label>
								</div>
								<div class="upf-cell column2">
									<input type="text" value="<?php echo stripslashes(get_user_meta($loggedin_id,"pro_ques8",true));?>" id="pro_ques8" name="pro_ques8" />
									<div id="pro_ques8help" class="help-click"> ?</div>
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
							<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo stripslashes($pro_ques9->question);?>:</label>
								</div>
								<div class="upf-cell column2">
									<input type="text" value="<?php echo stripslashes(get_user_meta($loggedin_id,"pro_ques9",true));?>" id="pro_ques9" name="pro_ques9" />
									<div id="pro_ques9help" class="help-click"> ?</div>
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

						<div class="upf-raw">
								<div class="upf-cell column1">
									<label><?php echo stripslashes($pro_ques10->question);?>:</label>
								</div>
								<div class="upf-cell column2">
									<input type="text" value="<?php echo stripslashes(get_user_meta($loggedin_id,"proques10",true)); ?>" id="proques10" name="proques10" />
									<div id="pro_ques10help" class="help-click"> ?</div>
									<span class="error" id="tcs10_error"></span>
								</div>
								<div style="display:none;" class="help-text" id="pro_ques10helptext">
								<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
								<?php echo stripslashes($pro_ques10->help_text);?></div>
							</div>
						
						<?php
						}
						
						// check the count if it is equal then change the color of background and button
						
						?>
						
					</div>
					<div class="submit-buttons <?php echo $probg;?> wdth" style="text-align:center;" id="proid"> 
						<span class="btn-blue1 btn1 mrgn">			
							<input type="submit" value="Save progress" name="save_project" class="btn-inner1 fnt d1">
						</span>
						<span class="btn-<?php echo $procpltbtn;?> btn1 fnt mrgn" >	
							<input type="button" id="saveprojectinfo" value="Complete" name="saveprojectinfo" class="btn-inner1 fnt d1">
						</span>

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
			  $teambg = 'green-complete';
			  $teamcpltbtn ='green1';
			  $teamcompcls = '<span class="comp-gr font14 progress-title">&nbsp;(Complete)</span>';
			}elseif($tStatus == 0){
			  $teambg = 'txt';
			  $teamcpltbtn ='grey1';
			  $teamcompcls = '<span class="notstart-wh font14 progress-title">&nbsp;(Not Started) </span>';
			}else{
			  $teambg = 'txt';
			  $teamcpltbtn ='grey1';
			  $teamcompcls = '<span class="incomp-bl font14 progress-title">(In Progress)</span>';
			} 
		?>
			<h3 class="abtprjct-new">Team members
			<?php echo $teamcompcls;?>
			</h3>
			<div class="box_white marbtm30">

		<div class="accordian_inner_content teamMembers">
			<div class="form-heading bg-abt">
				
				<div class="pi-left">

				</div> 
				
				<div class="pi-middle">
					<h5>Name</h5>
				</div>
			
				<div class="sc-right">										
					<h5 style="width: 78%; float: left;">Contact Email</h5>

					<div id="temMemnerhelp" class="help-click" style="float: right; margin-right: -15px;"> ?</div>	
					<span class="error" id="authors_error"></span>

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
                  <div id="teamMemAddedID" style="padding: 0px; color:  blue;"></div>			
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
							<div class="pi-left">		
								Team Lead		
							</div>
										
							<div class="pi-middle">
							<?php //echo $tmemberName;?>
								<div><input type="text" value="<?php echo htmlspecialchars(stripslashes($tmemberName));?>" id="teamleadname" name="teamleadname"><span id="error_teamleadname" class="error"></span></div>
							</div>
							<!----- onblur="checkvalidemail(this.id,this.value);"  ----->	
							<div class="sc-right">
							<?php //echo $tmemberEmail;?>
								<div><input type="text" value="<?php echo htmlspecialchars(stripslashes($tmemberEmail));?>" id="teamlead_email" name="teamlead_email">
								<input type="hidden" value="<?php echo $tmemberId;?>" id="tlid" name="tlid" class="umid" maxlength="100"><span id="error_teamlead_email" class="error"></span></div>	
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
				<div class="pi-left">
					New Member <!---<span id="memberRowID_<? //=$i;?>"><? //=$i;?></span> -->
				</div>
				
				<div class="pi-middle"> <?php //echo $memberName;?>
					<div><input type="text" value="<?php echo htmlspecialchars(stripslashes($memberName));?>" id="membername_<?=$i;?>" name="membername[]" maxlength="50"><span id="error_membername_<?=$i;?>" class="error"></span></div>
				</div>
			
				<div class="sc-right">
					<div><!---  onblur="checkvalidemail(this.id,this.value);" ---->
					<?php //echo $memberEmail;?>
					<input type="text" value="<?php echo htmlspecialchars(stripslashes($memberEmail));?>" id="memberemail_<?=$i;?>" name="memberemail[]" class="teamemail" maxlength="100">
					<input type="hidden" value="<?php echo $memberId;?>" id="memberid<?=$i;?>" name="memberid[]" class="umid" maxlength="100">
					<span id="error_memberemail_<?=$i;?>" class="error"></span>
					</div>						
					
				</div>
					<a style="color: #ff0015 !important; display: inline-block; font-size: 18px; font-weight: bold; margin-left: 5px; margin-top: 4px; position: absolute;" onclick="removeteam_members('memberdetails_<?=$i;?>', <?php echo $memberId; ?>); deletenewField();" href="javascript:void(0);">x</a>
				
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
		<?php
		}else{
		?>
				<div class="pi-left">
					Team Lead
				</div>
				
				<div class="pi-middle">
					<div><input type="text" id="teamleadname" name="teamleadname"><span id="error_teamleadname" class="error"></span></div>
				</div>
			
				<div class="sc-right">
					<div><input type="text" id="teamlead_email" name="teamlead_email" >
					<span id="error_teamlead_email" class="error"></span></div>
				</div>
			
			<!-- member info section -->
			<div id="memberinfo">	 
				<div class="memberdetails">
				<div class="pi-left">
					New Member
				</div>
				
				<div class="pi-middle">
					<div><input type="text" value="" id="membername_1" name="membername[]"><span id="error_membername_1" class="error"></span></div>
				</div>
			
				<div class="sc-right">
					<div>
						<input type="text" value="" id="memberemail_1" name="memberemail[]" class="teamemail" >
						<span id="error_memberemail_1" class="error"></span>
					</div>						
					
				</div>
				<!--<a class="removeclass" href="#">x</a> -->
				</div>  
			</div>
<?php }?>			 
			</div>	
			
			
			</div>

			<div id="temMemnerhelptext" class="help-text" style="display:none; width: 95%;margin: 0 0 10px 0; font-family: 'Lato', sans-serif;">
				<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
				<?php echo get_option("help_teamMembergText")?>
			</div>

			<div class="teamMember_button">
				<span class="small btn-blue1">					
					<a id="AddMoreMemberInfo" class="addnew" href="#" onclick="addnewField();">Add Member</a>					
				</span>
			</div>
			
			<div class="submit-buttons <?php echo $teambg;?> b1" style="text-align:center;" id="teamcompl">
				<span class="btn-blue1 btn1 mrgn">			
				<input type="submit" value="Save progress" name="savememberinfo" class="btn-inner1 fnt d1">
				</span>
				<span class="btn-<?php echo $teamcpltbtn;?> btn1 fnt mrgn">
				<input type="hidden" value="0" id="checkForCompleteClick" name="checkForCompleteClick">
				<input type="button" value="Complete" id="cptlteam" name="cptlteam" class="btn-inner1 fnt d1"></span>

			</div>
			<div id="teamresultmsg" class ="savemsgtxt" ></div>
			<div id="teamresult" style="text-align:center;"></div>
				<span>Note: <br/> - Each member's email must be unique.
				<br/> - You can only add maximum 6 members. </span>
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
			  $shbg = 'green-complete';
			  $shcpltbtn ='green1';
			  $shcompcls = '<span class="comp-gr font14 progress-title">&nbsp;(Complete)</span>';
			}elseif($shstatus == 0){
			  $shbg = 'txt';
			  $shcpltbtn ='grey1';
			  $shcompcls = '<span class="notstart-wh font14 progress-title">&nbsp;(Not Started) </span>';
			}else{
			  $shbg = 'txt';
			  $shcpltbtn ='grey1';
			  $shcompcls = '<span class="incomp-bl font14 progress-title">(In Progress)</span>';
			}
		?>
		
		<h3 class="abtprjct-new">Stakeholders&nbsp;<?php echo $shcompcls;?></h3>
		<div class="box_white marbtm30">
		<div class="accordian_inner_content stakeholders">

			<div class="form-heading bg-abt">
				<div class="pi-left">
				</div>
				<div class="pi-middle">
					<h5 style="width: 78%; float: left;font-weight: bold;">Stakeholders Name</h5>
					<div id="proquesstakeholder" class="help-click" style="float: right; margin-right: -13px;"> ?</div>
					<span class="error" id="authors_error"></span>
				</div>
			</div>

		<div class="accordian-inner">
		
			<!--<div class="pi-left info-project">-->
			<div class="">
			<!--<p> Max 6 Stakeholder will be added</p>-->
			<form method="post" class="bg-abt" name="frmstackholder" id="frmstackholder" action="<?php echo plugin_dir_url( __FILE__  ); ?>stakeholder.php">
				<!-- onsubmit="return fnvalidate_stackholder()" -->
			   <?php $Selectsh = $objUser->selectStakeholders($loggedin_id);
			  
			    $shval = $Selectsh['fieldvalue'];
				$sharray = explode(',',$shval); 
				
				if(count($sharray) == 0){?>
					<div id="InputsWrapper">
						<div>
							<div class="pi-left">
								Stakeholders 1
							</div>
							<div class="pi-middle">
								<input type="text" value="" id="stackholder_1" name="stackholder[]"><span id="error_stackholder_1" class="error sherror"></span>
							</div>
						</div>
					</div>
					
				<?php } else { ?>
				<div id="InputsWrapper1">
				<?php	foreach($sharray as $shak=>$shval){
					?>
					
						<div>
							<div class="pi-left">
								Stakeholders <?=$shak+1;?>
							</div>

							<div class="pi-middle">
								<input type="text" value="<?php echo htmlspecialchars(stripslashes($shval));?>" id="stackholder_<?=$shak+1;?>" name="stackholder[]" class="stackholders">
								<span id="error_stackholder_<?=$shak+1;?>" class="error sherror"></span>
								<a class="removeclass" href="#">×</a>
							</div>

<? /*
						<?php //echo htmlspecialchars_decode($shval, ENT_NOQUOTES);?>
							<input type="text" value="<?php echo htmlspecialchars(stripslashes($shval));?>" id="stackholder_<?=$shak+1;?>" name="stackholder[]" class="stackholders">						
							<span id="error_stackholder_<?=$shak+1;?>" class="error sherror"></span>
							
							<?php //if($shak>0){ ?>
							<a class="removeclass" href="#">×</a>
							<?php //}?>
*/ ?>
						</div>
					
				<?php
					  
					}
					?>
					</div>
				<?php
				}
			   ?>
			</div>
			
			<div id="proquesstakeholdertext" class="help-text" style="display:none; width: 96%; margin: 0 0 10px 0; font-family: 'Lato', sans-serif;">
				<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png" style="right: 50%;">
					<?php echo get_option("help_stakeholderText")?>
			</div>

			<div class="stakeholder_button">
				<span class="small btn-blue1">
				<?php if(count($sharray) == 0){ ?>
				<a id="AddMoreFileBox" class="" href="#">Add Stakeholder</a>
				<?php }else{ ?>
				<a id="AddMoreFileBox1" class="" href="#">Add Stakeholder</a>
				<?php }?>
				</span>
			</div>
			<div class="submit-buttons <?=$shbg?>" id="compltshbg">
					
				    <input type="hidden" name="hiddenaction" value="saveshlist">
					<span class="btn-blue1 btn1 mrgn">
					<input type="submit" value="Save progress" name="savestackholder" class="saveitem btn-inner1 fnt d1" ></span>
					<span class="btn-<?=$shcpltbtn?> btn1 mrgn">
					<input type="button" value="Complete" name="cmpltStakeholder" id="cmpltStakeholder" class="btn-inner1 fnt d1" ></span>
					
				</div>
				<div class="cdiv">
					<div id="shimag"></div>
					<div id="shmsg"></div>
				</div>
				<span>Note: You can only add a maximum of 6 stakeholder. </span>
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
			  $date_bg = 'green-complete';
			  $datebtn ='green1';
			  $datecompcls = '<span class="comp-gr font14 progress-title">&nbsp;(Complete)</span>';
			}elseif($dateKeysStatus == 0){
			  $date_bg = 'txt';
			  $datebtn ='grey1';
			  $datecompcls = '<span class="notstart-wh font14 progress-title">&nbsp;(Not Started) </span>';
			}else{
			  $date_bg = 'txt';
			  $datebtn ='grey1';
			  $datecompcls = '<span class="incomp-bl font14 progress-title">(In Progress)</span>';
			}			

		?>
		<h3 class="abtprjct-new">Key project dates&nbsp;<?php echo $datecompcls;?></h3>
		<div class="box_white marbtm30">
			<div class="accordian_inner_content stakeholders">
				<div class="form-heading bg-abt">
					<div class="pi-left">
					</div>
					<div class="pi-middle">
						<h5 style="width: 78%; float: left;font-weight: bold;">Key project dates</h5>
						<div id="keyDates" class="help-click" style="float: right; margin-right: -13px;">?</div>
						<span class="error" id="authors_error"></span>
					</div>
				</div>
				<div class="accordian-inner">
					<div class="">
						<form method="post" class="bg-abt" name="keysDate" id="keysDate" action="<?php echo plugin_dir_url( __file__ ); ?>keyDates.php">
							<div id="InputsWrapper">
								<div>
									<div class="pi-left">
										 Project commencement
									</div>
									<div class="pi-middle">
										<input type="text" value="<?php	echo $dateKeys['project_commencement']; ?>" id="project_commencement" name="project_commencement" class="pickdate" readonly="readonly"><span id="error_project_commencement" class="error">
									</div>
								</div>

								<div>
									<div class="pi-left">
										 Supplier / technology selected
									</div>
									<div class="pi-middle">
										<input type="text" value="<?php	echo $dateKeys['supplier']; ?>" id="supplier" name="supplier" class="pickdate" readonly="readonly"><span id="error_supplier" class="error" readonly="readonly">
									</div>
								</div>

								<div>
									<div class="pi-left">
										 Project completion
									</div>
									<div class="pi-middle">
										<input type="text" value="<?php	echo $dateKeys['project_completion']; ?>" id="project_completion" name="project_completion" class="pickdate" readonly="readonly"><span id="error_project_completion" class="error">
									</div>
								</div>
							</div>
						</div>
						<div id="keyDatestext" class="help-text" style="display:none; width: 96%; margin: 0 0 10px 0; font-family: 'Lato', sans-serif;">
							<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL; ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png" style="right: 50%;"><?php echo stripslashes(trim(get_option("help_keysDateText"))); ?>
						</div>
						<div class="submit-buttons <?=$date_bg; ?>" style="text-align: center;"> <input type="hidden" name="hiddenaction" value="savekeysDate">
							<span class="btn-blue1 btn1 mrgn">
							<input type="submit" value="Save progress" name="savekeysprojectdate" class="saveitem btn-inner1 fnt d1"></span>
							<span class="btn-<?=$datebtn; ?> btn1 mrgn"> <input type="button" value="Complete" name="cmpltkeysDate" id="cmpltkeysDate" class="btn-inner1 fnt d1"></span>
						</div>
						<div id="msresultmsg" class ="savemsgtxt"></div>
						<div id="keysDataresult" style="text-align:center;"></div>
					</div>
				</form>
			</div>
		</div>


		<!--  **********   End key dates *********************** -->
		
		<!-- Milestone information section STARTS here -->
		<?php
/*
			$msStatus = $obj->selectMilestoneStatus($loggedin_id);
			
			if($msStatus == 2){
			  $milestonebg = 'green-complete';
			  $milestonecpltbtn ='green1';
			  $milestonecompcls = '<span class="comp-gr font14 progress-title">&nbsp;(Complete)</span>';
			}elseif($msStatus == 0){
			  $milestonebg = 'txt';
			  $milestonecpltbtn ='grey1';
			  $milestonecompcls = '<span class="notstart-wh font14 progress-title">&nbsp;(Not Started) </span>';
			}else{
			  $milestonebg = 'txt';
			  $milestonecpltbtn ='grey1';
			  $milestonecompcls = '<span class="incomp-bl font14 progress-title">(In Progress)</span>';
			} 
			
			
			$Selectml = $obj->selectAllMilestones($loggedin_id);
		?>
		
		  <h3 class="abtprjct-new">Milestones
			<?php echo $milestonecompcls;?></h3>
			<div class="box_white marbtm30 abt_project"> 
<div class="accordian_inner_content milestone">						
			<div class="form-heading bg-abt">		
				
				<div class="pi-left">					

				</div>
				
				<div class="pi-middle">
					<h5>Milestone Name</h5>
				</div>
			
			
				<div class="sc-right">										
					<h5 style="width: 68%; float: left;">Date</h5>
					<div id="milestoneshelp" class="help-click" style="float: right; position: absolute;right: 24px;"> ?</div>	
					<span class="error" id="authors_error"></span>
				</div>
				
				
				
			</div>
			<form method="post"  class="bg-abt" name="frm_milestone" id="frm_milestone" action="<?php echo plugin_dir_url( __FILE__  ); ?>milestone.php">
			<div class="milestone-error"> <span id="error_dates" class="error"></span>
			 </div>
			<div id="milestonemain">
			
			
			<div id="milestoneinfomain">
	
			<?php
			    $totalMs = count($Selectml);
				if($totalMs>0){

					foreach($Selectml as $mskey=>$msval){
			?>

						<?php $keyval = $mskey;?>
						<?php if($keyval == 0){ ?>

							<div class="mstoneinfo">
								<div class="pi-left">
								First milestone

						<?php }elseif($keyval+1 == $totalMs){ ?>
							<div class="mstoneinfo">
								<div class="pi-left">
							Final milestone

						<?php } else { ?>
						<div class="milestoneinfo">
							<div class="pi-left">						
							<?php echo "Milestone <span id='miles_".$keyval."'>".($keyval+1)."</span>:";?>
							<?php }?>
						</div>

						<div class="pi-middle">
						<?php //echo htmlspecialchars_decode($msval['milestone'], ENT_NOQUOTES);?>
						<?php if($keyval == 0){ ?>
						<input type="text" readonly="readonly" name="firstmilestone" value="<?php echo htmlspecialchars(stripslashes($msval['milestone']));?>">						
						<?php }elseif($keyval+1 == $totalMs){ ?>
							<input type="text" readonly="readonly" name="finalmilestone" id="finalmilestone" value="<?php echo htmlspecialchars_decode($msval['milestone'], ENT_NOQUOTES);?>">						
						<?php }else{ ?>
						<?php //echo htmlspecialchars_decode($msval['milestone'], ENT_NOQUOTES);?>
						<input type="text" name="milestone[]" value="<?php echo htmlspecialchars(stripslashes($msval['milestone']));?>">
						<?php }?>
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
							<input type="text" id="datepickerfirst" name="firstmilestonedate" value="<?php echo $msdate; ?>" class="pickdate" readonly="readonly">	
						<?php }elseif($keyval+1 == $totalMs){ ?>

							<input type="text" id="datepicker11" name="finalmilestonedate" value="<?php echo $msdate; ?>" class="pickdate">	
						<?php }else{ ?>
							<input type="text" id="datepicker<?php echo $mskey;?>" name="milestonedate[]" value="<?php echo $msdate; ?>" class="pickdate" readonly="readonly">	
						<?php }?>						 
						</div>
						
						<input type="hidden" name="msid[]" value="<?php echo $msval['mid'];?>">
						<?php if($keyval == 0 || $keyval+1 == $totalMs){ ?>
						<?php }else{?>
						<a href="#" class="removemclass">x</a>
						<?php }?>
					</div>
			<?php		
					}
				}else{
			?>
			<div class="mstoneinfo">
				<div class="pi-left">First milestone: </div>		
				<div class="pi-middle">
					<input type="text" readonly="readonly" name="firstmilestone" value="Project Commencement" >
				</div>			
				<div class="sc-right">
				 <input type="text" id="datepickerfirst" name="firstmilestonedate" class="pickdate" readonly="readonly">				
				</div>
				
				
			</div>
			<div class="milestoneinfo">
				<div class="pi-left">Milestone <span id='miles_1'>1</span>: </div>		
				<div class="pi-middle">
					<input type="text" name="milestone[]" value="Supplier engaged" >
				</div>			
				<div class="sc-right">
				 <input type="text" id="datepicker1" name="milestonedate[]" class="pickdate" readonly="readonly">				
				</div>
				
				<a href="#" class="removemclass">x</a>
			</div>
			<div class="milestoneinfo">
				<div class="pi-left">Milestone <span id='miles_2'>2</span>: </div>
				
				<div class="pi-middle">
				<input type="text" name="milestone[]" value="Scoping and planning complete" >
				</div>
			
				<div class="sc-right">
				 <input type="text" id="datepicker2" name="milestonedate[]" class="pickdate">				
				</div>
				
				<a href="#" class="removemclass">x</a>
			</div>
			
			<div class="milestoneinfo">
				<div class="pi-left">Milestone <span id='miles_3'>3</span>: </div>
				
				<div class="pi-middle">
				<input type="text" name="milestone[]" value="Interface design complete" >
				</div>
			
				<div class="sc-right">
				 <input type="text" id="datepicker3" name="milestonedate[]" class="pickdate">				
				</div>
				
				<a href="#" class="removemclass">x</a>
			</div>
			
			
			<div class="milestoneinfo">
				<div class="pi-left">Milestone <span id='miles_4'>4</span>: </div>		
			
				<div class="pi-middle">
				<input type="text" name="milestone[]" value="Development complete" >
				</div>
			
				<div class="sc-right">
				 <input type="text" id="datepicker4" name="milestonedate[]" class="pickdate">				
				</div>
				
				<a href="#" class="removemclass">x</a>
			</div>
			
			<div class="milestoneinfo">
				<div class="pi-left">Milestone <span id='miles_5'>5</span>: </div>	
			
				<div class="pi-middle">
				<input type="text" name="milestone[]" value="User testing complete" >
				</div>
			
				<div class="sc-right">
				 <input type="text" id="datepicker5" name="milestonedate[]" class="pickdate">				
				</div>
				
				<a href="#" class="removemclass">x</a>
			</div>
			
			<div class="milestoneinfo">
				<div class="pi-left">Milestone <span id='miles_6'>6</span>: </div>
			
			
				<div class="pi-middle">
					<input type="text" name="milestone[]" value="Training complete and system live" >
				</div>
			
				<div class="sc-right">
				 <input type="text" id="datepicker6" name="milestonedate[]" class="pickdate">				
				</div>
				
				<a href="#" class="removemclass">x</a>
			</div>
			
			<div class="mstoneinfo">
				<div class="pi-left">Final milestone: </div>		
				<div class="pi-middle">
					<input type="text" readonly="readonly" id="finalmilestone" name="finalmilestone" value="Project Completion" > 
				</div>			
				<div class="sc-right">
				 <input type="text" id="datepicker11" name="finalmilestonedate"  class="pickdate">	
				 
				</div>
				
				
			</div>
			<?php }?>
		
			</div>
	
			</div>

			<div id="milestoneshelptext" class="help-text" style="display:none; width: 96%; margin: 0 0 10px 0; font-family: 'Lato', sans-serif;">
				<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png" style="right: 50%;">
				<?php echo get_option("help_milestoneshelpText")?>
			</div>

			<div class="teamMember_button">
				<span class="small btn-blue1">					
					<a id="AddMoreMilestone" class="addnew" href="#">Add Milestone</a>
				</span>
			</div>

			<div class="savemswrapper <?php echo $milestonebg;?> b1" style="text-align:center; clear: both;" id='mswrap'>
				<span class="btn-blue1 btn1 mrgn">
				<input type="submit" value="Save progress" name="save_milestone" class="btn-inner1 fnt d1" ></span>					
				<span class="btn-<?php echo $milestonecpltbtn;?> btn1 mrgn">
				<input type="button" value="Complete" name="complte_milestone" id="complte_milestone" class="btn-inner1 fnt d1" ></span>					
			</div>
			<div id="msresultmsg" class ="savemsgtxt"></div>
			<div id="milestoneresult" style="text-align:center;"></div>
			
			<span>Note: <br/>You can only add a maximun of 10 milestones. </span>
			</form>
	</div>				
		</div>
*/ ?>		
		<!-- Milestone information section ENDS here -->	

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
		  $probg = 'green-complete';
		  $procpltbtn ='green1';
		  $procompcls = '<span class="comp-gr font14 progress-title">&nbsp;(Complete)</span>';
		} elseif ($riskStat == 1) {
		  $probg = 'txt';
		  $procpltbtn ='grey1';
		  $procompcls = '<span class="incomp-bl font14 progress-title">(In Progress)</span>';

		} else {
		  $probg = 'txt';
		  $procpltbtn ='grey1';
		  $procompcls = '<span class="notstart-wh font14 progress-title">&nbsp;(Not Started) </span>';
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
		<h3 class="abtprjct-new">Risks <?php echo $procompcls;  ?></h3>

		<div class="riskManagement">
			<div class="form-heading bg-abt">
				<div class="pi-left"><h5>Risk Name</h5></div>

				<div class="pi-middle">
					<h5>Likelihood of occurrence</h5>
				</div>

				<div class="sc-right">
					<h5 style="float: left;">Type of Risk</h5>
					<div id="riskManagementhelp" class="help-click" style="float: right; margin-right: -15px;"> ?</div>
					<span class="error" id="authors_error"></span>
				</div>

			</div>

			<form method="post"  class="bg-abt" name="formRiskManagement" id="formRiskManagement" action="<?php echo plugin_dir_url( __FILE__  ); ?>riskManagement.php" >
				<div id="riskManagementInfo">
				<?php if (!empty( $getAllRiskManagementBlock)) { ?>
					<?php foreach ($getAllRiskManagementBlock as $k => $v) { ?>

						<div class="riskBlock" id="riskBlock_<?=$risk_row; ?>">
							<?php if ( $risk_row > 0 ) { ?>
								<a style="color: #ff0015 !important; display: inline-block; font-size: 18px; font-weight: bold; right: 30px; margin-top: 0px; position: absolute; float: right;"  href="javascript:void(0);" id="removeRiskBlock" onclick="removeRiskBlock('<?=$risk_row; ?>' , <?=$v['id']; ?>);">x</a>
							<?php } ?>
							<input type="hidden" class="riskStatus" value="<?php echo $v['id'];?>" name="risk[<?=$k; ?>][id]">
							<div class="pi-left"><input type="text" value="<?php echo $v['riskName']; ?>" name="risk[<?=$risk_row; ?>][riskName]" class="required" maxlength="64"/></div> 
							<div class="pi-middle">
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
							<div class="sc-right">
								<select name="risk[<?=$risk_row; ?>][riskType]" class="required">
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
								<textarea name="risk[<?=$risk_row; ?>][riskDescription]" rows="4" cols="50" placeholder="Description" class="required"><?php echo $v['riskDescription']; ?></textarea>
							</div>
						</div><!-- #riskBlock End -->
						
					<?php $risk_row++;
					 } ?>
				<?php } else { ?>

					<div class="riskBlock">
     					<input type="hidden" class="riskStatus" value="" name="risk[<?=$k; ?>][id]">
						<div class="pi-left"><input type="text" value="" name="risk[<?=$risk_row; ?>][riskName]" class="required" /></div> 
						<div class="pi-middle">
							<select name="risk[<?=$risk_row; ?>][LikelihoodOfOccurrence]" class="required">
								<?php foreach ( $LikelihoodOfOccurrence as $k => $v ) { ?>
									<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="sc-right">
							<select name="risk[<?=$risk_row; ?>][riskType]" class="required">
								<?php foreach ( $riskType as $k => $v ) { ?>
									<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="sc-full">
							<textarea name="risk[<?=$risk_row; ?>][riskDescription]" rows="4" cols="50" placeholder="Description" class="required"></textarea>
						</div>
					</div><!-- #riskBlock End -->
					<?php	$risk_row++; ?>
				<?php } ?>

				</div><!-- #riskManagementInfo End -->

			<div id="riskManagementhelptext" class="help-text" style="display:none; width: 95%;margin: 0 0 10px 0; font-family: 'Lato', sans-serif;">
				<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
				<?php echo get_option("help_riskManagementhelptext")?>
			</div>

			<div class="teamMember_button">
				<span class="small btn-blue1" style="width: 109px;">					
					<a id="AddMoreMemberInfo" class="addnew" href="#" onclick="addnewRiskBlock();">Add Risk</a>					
				</span>
			</div>

			<div class="submit-buttons <?php echo $probg; ?> b1" style="text-align:center;" id="riskTemp">
				<span class="btn-blue1 btn1 mrgn">			
				<input type="submit" value="Save progress" name="savememberinfo" class="btn-inner1 fnt d1">
				</span>
				<span class="btn-<?php echo $procpltbtn;	?> btn1 fnt mrgn">
				<input type="hidden" value="0" id="checkForCompleteRiskManagement" name="checkForCompleteRiskManagement">
				<input type="button" value="Complete" id="complteRiskManagement" name="complteRiskManagement" class="btn-inner1 fnt d1"></span>
			</div>

			<div id="riskResultMsg" class ="savemsgtxt" ></div>
			<div id="riskResult" style="text-align:center;"></div>
				<span>Note:
				<br/> - You can only add a maximum of 10 risks. </span>

			</form>


		</div><!--  End riskManagement div -->

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

		html= '<div class="riskBlock" id="riskBlock_'+risk_row+'">';
		html += '<a style="color: #ff0015 !important; display: inline-block; font-size: 18px; font-weight: bold; right: 30px; margin-top: 0px; position: absolute; float: right;"  href="javascript:void(0);" id="removeRiskBlock" onclick="removeRiskBlock('+risk_row+' ,0);">x</a>';
		html +=	'<div class="pi-left"><input type="text" value="" name="risk['+risk_row+'][riskName]" class="required"></div> ';
		html +=	'<div class="pi-middle">';
		html +=		'<select name="risk['+risk_row+'][LikelihoodOfOccurrence]" class="required">';
		<?php foreach ( $LikelihoodOfOccurrence as $k => $v ) { ?>
			html +=	'<option value="<?php echo $k; ?>"><?php echo $v; ?></option>';
		<?php } ?>
		html +=		'</select>';
		html +=	'</div>';
		html +=	'<div class="sc-right">';
		html +=		'<select name="risk['+risk_row+'][riskType]" class="required">';
		<?php foreach ( $riskType as $k => $v ) { ?>
			html +=	'<option value="<?php echo $k; ?>"><?php echo $v; ?></option>';
		<?php } ?>
		html +=		'</select>';
		html +=	'</div>';
		html +=	'<div class="sc-full">';
		html +=		'<textarea name="risk['+risk_row+'][riskDescription]" rows="4" cols="50" placeholder="Description" class="required"></textarea>';
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
						$('#ui-accordion-accordion-header-4 span:nth-child(2)').removeClass('comp-gr').addClass('incomp-bl');
						$('#ui-accordion-accordion-header-4 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');
						$('#ui-accordion-accordion-header-4 span:nth-child(2)').html('&nbsp;(In Progress)');
						$("#riskTemp").removeClass("green-complete").addClass("txt");
						$("#complteRiskManagement").parent().removeClass("btn-green1").addClass("btn-grey1");
					} else {
						$('#ui-accordion-accordion-header-4 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');					
						$('#ui-accordion-accordion-header-4 span:nth-child(2)').removeClass('incomp-bl').addClass('comp-gr');
						$('#ui-accordion-accordion-header-4 span:nth-child(2)').html('&nbsp;(Complete)');
						$("#riskTemp").removeClass("txt").addClass("green-complete");
						$("#complteRiskManagement").parent().removeClass("btn-grey1").addClass("btn-green1");
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
