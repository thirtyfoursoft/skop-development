<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>js/skopes-accordion-main.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>js/skopes-accordion-active.js"></script>

<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/css/jquery-ui.css" rel="stylesheet" media="screen" />
<script>
	$(function() {
		$(".accordion-con").accordion({ collapsible: true, active: 'none', heightStyle: "content" });
	});
</script>
<script language="javascript" type="text/javascript">
function intOnly(i) {
	if(i.value.length>0) {
		i.value = i.value.replace(/[^\d]+/g, '');
	}
}
</script>
<?php
if (!class_exists('userinfo')) {
	include('core/class_userinfo.php');
}
$obj = new userinfo(); 
?>
<input type="hidden" name="totalWeights" id="totalWeights" value="" />
<input type="hidden" name="totalResp" id="totalResp" value="" />
<input type="hidden" name="totalNonFinBen" id="totalNonFinBen" value="" />
<div class="content-section clearfix">
	<div class="container">
		<div class="accordion-main-con">
			<div class="page-headding">
			  <h3 style="text-transform: uppercase;">Advanced Project Set-up</h3>
			</div>
			
		<!-- Team information section starts here -->
		<div id="" class="accordion-con">
		<?php
				
			// get the selection creteria section status value
			$scstatus = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='selection_creteria_info'";
			$scstatusRes = $wpdb->get_col($scstatus); 
			
			$scStatus =  $scstatusRes[0];
			if($scStatus == 2){
			  $sc_cpltbtn ='green';
			  $sc_compcls = '<span class="complete pull-right">Complete</span>';
			}elseif($scStatus == 0){
			  $sc_cpltbtn ='grey1';
			  $sc_compcls = '<span class="notstred pull-right">Not Started</span>';
			}else{
			  $sc_cpltbtn ='grey1';
			  $sc_compcls = '<span class="incomplete pull-right">In Progress</span>';
			}
		?>
			<h5 class=""><span class="blue-arrow pull-right"></span>
			<span class="accordion_title">Selection criteria</span>
			<?php echo $sc_compcls;		?></h5>

			<div id="moreprojectinfo" class="">

			<div class="accordian-inner-container">
		
		<form method="post" class="user-profileform bg-abt" onsubmit="" name="frmselectioncriteria" id="frmselectioncriteria" action="<?php echo plugin_dir_url( __FILE__  ); ?>postitem.php">
		<div id="inner" class="selection_criteria">

			<div class="form-two-colum">
				<div class="first-colum"><b>Selection criteria</b></div>
				<div class="second-colum">
					<b>Importance weight(Max 100)</b>
						<div id="selectionCriteriahelp" class="help-click"></div>
				</div>
			</div>
			
			<div id="sccontentvalue">
			<?php 
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


		// Sort and print the resulting array
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
			</div>

			</div>

			<div id="selectionCriteriahelptext" class="help-text" style="display:none;font-family: 'Lato', sans-serif;">
				<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
				<?php echo get_option("help_selectionCriteriahelpText")?>
			</div>

              <?php if($notStarted == 1) { ?>
					<div class="form-btn">
						<input type="submit" value="SAVE PROGRESS" name="save_Cretria" class="progress-btn">
						<input type="button" value="Complete" name="compelte_Cretria" id="compelte_Cretria2" onclick="javascript: firstTimeValidate();" class="compelete-btn <?=$sc_cpltbtn; ?>">
					</div>

			   <?php } else { ?>
					<div class="form-btn">
						<input type="hidden" name="hiddenaction" value="saveshlist">
						<input type="submit" value="SAVE PROGRESS" name="save_Cretria" class="progress-btn">
						<input type="button" value="Complete" name="compelte_Cretria" id="compelte_Cretria" class="compelete-btn <?=$sc_cpltbtn; ?>">
					</div>

               <?php } ?>
<script>
function firstTimeValidate()
{
	var imptotal = $(".imptotal").text();	
	if(checkselectioncretotal() == false){
	 //return false;
	}
	/*else if(imptotal >100){	
	  $("#totalerror").html('Total of all creatia cannot exceed 100, please set the priority again'); 
	 return false;
	}*/ 
	var totalWeights = $("#totalWeights").val(); 
	
	var totalWeightsValues = totalWeights.split(","); 
	var totalCountWeightValues = 0;
	
	for(i=0; i<totalWeightsValues.length; i++)
	{  
	    var weight = totalWeightsValues[i];
		var WeightVal = $('#imp_weight_id_'+weight).val();
	    totalCountWeightValues = totalCountWeightValues + parseInt(WeightVal);
	}  
	
	if(isNaN(totalCountWeightValues))
	{
	    totalCountWeightValues = 100;
	} 
	totalCountWeightValues = imptotal;
	 
	if(imptotal > 100 ){ 
	   $("#totalerror").html('Total of all creatia must be 100, please set the priority again');  
	   //return false; 
		//$("#totalerror").html('');		
		$.ajax({ 
				type: "POST",
				url: "/wp-content/plugins/skopes/frontend/theme/<?=$choosenTheme; ?>/compltechk.php", 
				data: { query: 'screteriainfo', totalCountWeightValues: totalCountWeightValues, imptotal: imptotal, data: $('#frmselectioncriteria').serialize() },
				beforeSend: function(){
					$('#result').show();
					$('#result').html('<img src="/wp-content/plugins/skopes/frontend/theme/<?=$choosenTheme; ?>/images/loader.gif" />');	 			
				},
				success: function(data){	
					$('#result').hide();
					$('#shmsg').html('Information Saved');
					$('#shmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
					$('#result').html(''); 
					var totalWeights = $("#totalWeights").val(); 
	                var totalWeightsValues = totalWeights.split(",");
					for(i=0; i<totalWeightsValues.length; i++)
					{ 
						$('#errorimp_weight_'+i).html(''); 
					}
					if(data == 2){ 
						$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred incomplete').addClass('complete'); 
						$('#ui-accordion-1-header-0 span:nth-child(4)').html('Complete');
						$("#compelte_Cretria2").removeClass("grey1").addClass("green");
										
					}else if(data == 0 || data == 1){
					    $("#totalerror").html('Total of all creatia cannot exceed 100, please set the priority again'); 
						$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete'); 
						$('#ui-accordion-1-header-0 span:nth-child(4)').html('In Progress');
						$("#compelte_Cretria2").removeClass("green").addClass("grey1");
					}
				}
		   });
	 }
	 else
	 { 
		$("#totalerror").html('');		
		$.ajax({ 
				type: "POST",
				url: "/wp-content/plugins/skopes/frontend/theme/<?=$choosenTheme; ?>/compltechk.php", 
				data: { query: 'screteriainfo', totalCountWeightValues: totalCountWeightValues, imptotal: imptotal, data: $('#frmselectioncriteria').serialize() },
				beforeSend: function(){
					$('#result').show();
					$('#result').html('<img src="/wp-content/plugins/skopes/frontend/theme/<?=$choosenTheme; ?>/images/loader.gif" />');	 			
				},
				success: function(data){	
					$('#result').hide();
					$('#shmsg').html('Information Saved');
					$('#shmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
					$('#result').html(''); 
					var totalWeights = $("#totalWeights").val(); 
	                var totalWeightsValues = totalWeights.split(",");
					for(i=0; i<totalWeightsValues.length; i++)
					{ 
						$('#errorimp_weight_'+i).html(''); 
					}
					if(data == 2){ 

						$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred incomplete').addClass('complete'); 
						$('#ui-accordion-1-header-0 span:nth-child(4)').html('Complete');
						$("#compelte_Cretria2").removeClass("grey1").addClass("green");
										
					}else if(data == 0 || data == 1){
					    $("#totalerror").html('Total of all creatia cannot exceed 100, please set the priority again'); 
						$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete'); 
						$('#ui-accordion-1-header-0 span:nth-child(4)').html('In Progress');
						$("#compelte_Cretria2").removeClass("green").addClass("grey1");
					}
				}
		   });   
	 } 
}

</script>			   
			</div>	
			</div>
			</form>
		</div>
			
			<?php 
				
			/* $teamMembertotalCount = $obj->memberTotalCount($loggedin_id);
			$teamrestotalCount = $obj->checkResValue($loggedin_id);
			 */
			// get the team section status value
			$selectqry = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='membermoreinfo'";
			$morestatus = $wpdb->get_col($selectqry); 
			
			$mstatus =  $morestatus[0];
			if($mstatus == 2){
			  $teamcpltbtn ='green';
			  $teamcompcls = '<span class="complete pull-right">Complete</span>';
			}else if($mstatus == 0){
			  $teamcpltbtn ='grey1';
			  $teamcompcls = '<span class="notstred pull-right">Not Started</span>';
			}else{
			  $teamcpltbtn ='grey1';
			  $teamcompcls = '<span class="incomplete pull-right">In Progress</span>';
			}?>

			<h5 class=""><span class="blue-arrow pull-right"></span>
			<span class="accordion_title">Team responsibilities and effort</span>
			<?php echo $teamcompcls;?></h5>
			
	<div class="" id="team_responsibilities">
		<div class="accordian-inner-container">
			<form method="post" class="bg-abt" name="frmteammoreinfo" id="frmteammoreinfo" action="<?php echo plugin_dir_url( __FILE__  ); ?>teammoreinfo.php">
			<!-- onsubmit="return fnvalidate_teaminfo()" -->

			<div class="form-three-colum">
				<div class="first-colum" style="text-indent: -9999px;">XYZ</div>
				<div class="second-colum">
					 <b>Responsibility</b>
				  </div>
				<div class="third-colum">
					<b>Estimated effort (hrs) per week </b>
					<div id="teamResponsibilitieshelp" class="help-click"></div>
				</div>
			</div>

			<?php $teamresultarray = $objUser->selectTeamInfo($loggedin_id);?>
			<div id="teammoreinfo">
		
			<?php 
				if(count($teamresultarray)>0){
					$memberresultarray = $objUser->selectTeamMemberInfo($loggedin_id);
					$tlresultarray = $objUser->selectTeamLeadInfo($loggedin_id);
				
					foreach ($tlresultarray as $tlkey=>$tlval){
					
						$tmemberId = $tlval['id'];
						$tmemberType = $tlval['member_type'];
						$tmemberName = htmlspecialchars_decode($tlval['member_name']);
						$tmemberResponsiblity = htmlspecialchars_decode($tlval['member_responsibility'],ENT_NOQUOTES | ENT_HTML5);
						$tmemberEffort = $tlval['member_estimate_effort'];
					?>

						<div class="form-three-colum">
							<div class="first-colum">
								<?php echo htmlspecialchars(stripslashes(ucfirst($tmemberName)));?>
								<input type="hidden" name="tlid" value="<?php echo $tmemberId;?>" class="input-text">
							</div>
							<div class="second-colum">
								<input type="text" value="<?php echo htmlspecialchars(stripslashes($tmemberResponsiblity));?>" id="teamleadresp" name="teamleadresp" class="input-text">
								<span id="error_teamleadresp" class="error"></span>
							  </div>
							<div class="third-colum">
									<input type="text" value="<?php echo htmlspecialchars(stripslashes($tmemberEffort));?>" id="teamlead_effort" name="teamlead_effort" class="input-text">
									<span id="error_teamlead_effort" class="error"></span>
							</div>
						</div>					

					<?php }				
				$i=0; ?>
				<div id="memberinfo"> 
				<?php 
				 $totalResp = '';
				foreach ($memberresultarray as $key=>$val) {

					$memberId = $val['id'];
					$memberType = $val['member_type'];
					$memberResponsiblity = htmlspecialchars_decode($val['member_responsibility'],ENT_NOQUOTES | ENT_HTML5);
					
					$memberEffort = (is_null($val['member_estimate_effort'])? '' : $val['member_estimate_effort'] );
					$memberName = $val['member_name'];
					// htmlspecialchars_decode($val['member_name'], ENT_NOQUOTES| ENT_HTML5);
					$memberCounter = ($memberType == 'tl' ? '' : $i++);

				?>		
			<!-- member info section -->
				
				<div class="memberdetails">

					<div class="form-three-colum">
						<div class="first-colum"><?php echo htmlspecialchars(stripslashes(ucfirst($memberName)));?></div>
						<div class="second-colum">
							<input type="text" class="memresp input-text" value="<?php echo htmlspecialchars(stripslashes($memberResponsiblity));?>" id="memberresp_<?=$i;?>" name="memberresp[<?php echo $memberId;?>]">
							<span id="error_memberresp_<?=$i;?>" class="error"></span>
						  </div>
						<div class="third-colum">
							<input type="text" class="memeffort input-text" value="<?php echo htmlspecialchars(stripslashes($memberEffort));?>" id="membereffort_<?=$i;?>" name="membereffort[<?php echo $memberId;?>]">
							<span id="error_membereffort_<?=$i;?>" class="error"></span>
						</div>
					</div>

				</div>
		
		<?php
             if($totalResp == '') {
				 $totalResp .= $i;
			 } else {
				 $totalResp .= "," .$i;
			 }
		} // end of foreach
		?>
	         <script>
		            $("#totalResp").val('<?php echo $totalResp; ?>');    
		    </script>
		</div>
		<?php
		}?>			
			</div>

			<div id="teamResponsibilitieshelptext" class="help-text" style="display:none; font-family: 'Lato', sans-serif;">
				<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
				<?php echo get_option("help_teamResponsibilitieshelpText")?>
			</div>

			<div class="teamMember_button">				
				<a href="<?php echo site_url().'?act=aboutproject&team=1#ui-accordion-accordion-header-1'?>" class="progress-btn addnew" style="float: right;">Edit team members</a>
			</div>

			<div class="form-btn">
				<input type="submit" value="SAVE PROGRESS" name="save_project" class="progress-btn">
				<input type="button" value="Complete" name="compltemembermoreinfo" id="compltemembermoreinfo" class="compelete-btn <?php echo $teamcpltbtn;?>">
			</div>
			
			<div class="cdiv">
				<div id="teammoreresult" style="text-align:center;"></div>
				<div id="tmresultmsg" style="color:green; text-align:center;"></div>
			</div>
			
			</div>
		    </form>
		</div>
		
		<?php
			// get the benefit section status value
			$benefitstatus = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='benefitinfo'";
			$bstatusRes = $wpdb->get_col($benefitstatus); 
			
			$bstatus =  $bstatusRes[0]; 
			if($bstatus == 2){
			  $benefitcpltbtn ='green';
			  $benefitcompcls = '<span class="complete pull-right">Complete</span>';
			}elseif($bstatus == 0){
			  $benefitcpltbtn ='grey1';
			  $benefitcompcls = '<span class="notstred pull-right">Not Started</span>';
			}else{
			  $benefitcpltbtn ='grey1';
			  $benefitcompcls = '<span class="incomplete pull-right">In Progress</span>';
			}
		?>
			<h5 class=""><span class="blue-arrow pull-right"></span>
			 <span class="accordion_title">Non-financial benefits</span>
			<?php echo $benefitcompcls;?></h5>
		
			<div class="" >
			
			<div class="accordian-inner-container stakeholders" id="benifit">
				<div class="form-two-colum">
					<div class="first-colum" style="text-indent: -9999px;">Stakeholders Name</div>
					<div class="second-colum">
						<b>Benefit</b>
						<div id="nonfinancialBenefits" class="help-click"></div>
					</div>
				</div>
				  			
			<form method="post" class="bg-abt" name="frmanbenefit" id="frmanbenefit" action="<?php echo plugin_dir_url( __FILE__  ); ?>abenefit.php">
			<!-- onsubmit="return fnvalidate_benefit()" -->
			   <?php $Selectab = $objUser->selectAnticipatedBenefits($loggedin_id);
			  
			    $abval = $Selectab['fieldvalue'];
				$abarray = explode(',',$abval); 
				?>
				<script>
				     $("#totalNonFinBen").val('<?php echo count($abarray); ?>');    
				</script>    
				<?php
				if(count($abarray) == 0){?>
					<div id="BenefitsWrapper">

						<div class="form-two-colum">
							<div class="first-colum">Benefit 1</div>
							<div class="second-colum">
								<input type="text" value="" id="anbenefit_1" name="anbenefits[]" class="input-text">
								<a class="removeabclass" href="#" onclick="decrement();">×</a>
								<span id="error_anbenefit_1" class="error sherror"></span>
							</div>
						</div>
					
					</div>
					
				<?php }else{?>

				<div id="BenefitsWrapper1">
				<?php	foreach($abarray as $abkey=>$abvalue){ ?>
					<?php //echo htmlspecialchars_decode($abvalue, ENT_NOQUOTES);?>

						<div class="form-two-colum">
							<div class="first-colum">Benefit <?=$abkey+1;?></div>
							<div class="second-colum">
								<input type="text" value="<?php echo htmlspecialchars(stripslashes($abvalue));?>" id="anbenefit_<?=$abkey+1;?>" name="anbenefits[]" class="benefits input-text">					
								<span id="error_anbenefit_<?=$abkey+1;?>" class="error sherror"></span>
								<a class="removeabclass" href="#" onclick="decrement();">×</a>
							</div>
						</div>

				<?php
					}
					?>
					</div>
				<?php	} ?>
				<script>
					 function increment() {
						 var totalNonFinBenCurrent = parseInt($("#totalNonFinBen").val()) + 1;
						 $("#totalNonFinBen").val(totalNonFinBenCurrent);
					 }
			 
					 function decrement() {
						 var totalNonFinBenCurrent = parseInt($("#totalNonFinBen").val()) - 1;
						 $("#totalNonFinBen").val(totalNonFinBenCurrent);
					 }
				</script>

				<div class="teamMember_button">
						<?php if(count($abarray) == 0){ ?>
							<a id="AddMoreBenefit" class="intro progress-btn addnew" href="#" onclick="increment();" style="float: right;">Add Benefit</a>
						<?php }else{ ?>
							<a id="AddMoreBenefit1" class="intro progress-btn addnew" href="#" onclick="increment();" style="float: right;">Add Benefit</a>
						<?php }?>
				</div>
				
			<div id="nonfinancialBenefitstext" class="help-text" style="display:none; font-family: 'Lato', sans-serif;">
				<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
				<?php echo get_option("help_nonfinancialBenefitsText")?>
			</div>
			
			<div class="form-btn">
				<input type="hidden" name="hiddenaction" value="saveshlist">
				<input type="submit" value="SAVE PROGRESS" name="savebenefitlist" class="progress-btn">
				<input type="button" value="Complete" name="complete_benefit" id="complete_benefit" class="compelete-btn <?=$benefitcpltbtn; ?>">
			</div>
			
			<span style="padding: 0 20px;">Note: You can add a maximum of 20 benefits.</span>
			<div class="cdiv">
				<div id="abimg" style="text-align:center;"></div>
				<div id="abmsg" style="color:green; text-align:center;"></div> 
			</div>
		</div>
	</form>
</div>

	<!--  **************  Start milestone section here Written by Royal Tyagi *****************  -->

		<?php
		    // get milestone status from database
			$msStatus = $obj->selectMilestoneStatus($loggedin_id);

			if($msStatus == 2){
			  $milestonecpltbtn ='green';
			  $milestonecompcls = '<span class="complete pull-right">Complete</span>';
			}elseif($msStatus == 0){
			  $milestonecpltbtn ='grey1';
			  $milestonecompcls = '<span class="notstred pull-right">Not Started</span>';
			}else{
			  $milestonecpltbtn ='grey1';
			  $milestonecompcls = '<span class="incomplete pull-right">In Progress</span>';
			}

			$Selectml = $obj->selectAllMilestones($loggedin_id);
		?>

		<h5 class=""><span class="blue-arrow pull-right"></span>
		<span class="accordion_title">Milestones</span>
		<?php echo $milestonecompcls;?></h5>
		
			<div class="abt_project"> 
	<div class="accordian-inner-container milestone">

			<form method="post"  class="bg-abt" name="frm_milestone" id="frm_milestone" action="<?php echo plugin_dir_url( __FILE__  ); ?>milestone.php">

				<div class="form-three-colum">
		            <div class="first-colum" style="text-indent: -9999px;">XYZ</div>
					<div class="second-colum">
						 <b>Milestone Name</b>
		              </div>
		            <div class="third-colum">
						<b>DATE</b>
						<div id="milestoneshelp" class="help-click"></div>
		            </div>
		        </div>
              			
			<div class="milestone-error"> <span id="error_dates" class="error"></span></div>
			<div id="milestonemain">

			<div id="milestoneinfomain">
			<?php

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
								
			    $totalMs = count($Selectml);
				if($totalMs>0){
					foreach($Selectml as $mskey=>$msval) { ?>

						<?php $keyval = $mskey;?>
						<?php if($keyval == 0){ ?>
						<div class="mstoneinfo">
							<div class="form-three-colum">
								<div class="first-colum">
									First milestone

						<?php }elseif($keyval+1 == $totalMs) { ?>
						<div class="mstoneinfo">
							<div class="form-three-colum">
								<div class="first-colum">
								Final milestone

						<?php } else { ?>
						<div class="milestoneinfo">
							<div class="form-three-colum">
								<div class="first-colum">					
								<?php echo "Milestone <span id='miles_".$keyval."'>".($keyval)."</span>:";?>
								<?php }?>
							</div>

						<div class="second-colum">
						<?php if($keyval == 0){ ?>
							<input type="text" readonly="readonly" class="input-text" name="firstmilestone" value="<?php echo htmlspecialchars(stripslashes($msval['milestone']));?>">						
						<?php }elseif($keyval+1 == $totalMs){ ?>
							<input type="text" readonly="readonly" class="input-text" name="finalmilestone" id="finalmilestone" value="<?php echo htmlspecialchars_decode($msval['milestone'], ENT_NOQUOTES);?>">						
						<?php }else{ ?>

							<?php if($keyval == 1){  ?>
								<input type="text" name="milestone[]" class="input-text" value="<?php echo htmlspecialchars(stripslashes($msval['milestone']));?>" readonly="readonly">
							<?php } else { ?>
								<input type="text" name="milestone[]" class="input-text" value="<?php echo htmlspecialchars(stripslashes($msval['milestone']));?>">
							<?php } ?>
							
						<?php }?>
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
						<?php }else{ ?>

							<?php if($keyval == 1){  ?>
								<input type="text" id="datepicker<?php echo $mskey;?>" name="milestonedate[]" value="<?php echo $data['supplier']; ?>" class="input-text" readonly="readonly">
							<?php } else { ?>
								<input type="text" id="datepicker<?php echo $mskey;?>" name="milestonedate[]" value="<?php echo $msdate; ?>" class="pickdate input-text" readonly="readonly">	
								<?php if($keyval == 0 || $keyval == 1 || $keyval+1 == $totalMs) { ?>
								<?php  } else { ?>
									<a href="#" class="removemclass">x</a>
								<?php } ?>
							<?php } ?>

						<?php }?>						 
						</div>
						
						<input type="hidden" name="msid[]" value="<?php echo $msval['mid'];?>">

					</div>
					</div>
			<?php
					}
				} else {
				?>
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
										 <input type="text" id="datepicker<?=$k; ?>" name="milestonedate[]" class="input-text pickdate" value="">	
										 <a href="#" class="removemclass">x</a>
									 <?php } ?>
									</div>
								</div>
							</div>

						<?php }
					}
				?>
			<?php } ?>

			</div>
			</div>

			<div id="milestoneshelptext" class="help-text" style="display:none; font-family: 'Lato', sans-serif;">
				<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png" style="right: 50%;">
				<?php echo get_option("help_milestoneshelpText")?>
			</div>

			<div class="teamMember_button">
				<a id="AddMoreMilestone" class="addnew progress-btn" href="#" style="float: right;">Add Milestone</a>
			</div>

			<div class="form-btn">
				<input type="hidden" name="hiddenaction" value="saveshlist">
				<input type="submit" value="SAVE PROGRESS" name="save_milestone" class="progress-btn">
				<input type="button" value="Complete" name="complte_milestone" id="complte_milestone" class="compelete-btn <?=$milestonecpltbtn; ?>">
			</div>

			<div id="msresultmsg" class ="savemsgtxt" style="color:green; text-align:center;"></div>
			<div id="milestoneresult" style="text-align:center;"></div>

			<span style="padding: 0 20px; display: block;">Note: <br/>You can only add a maximun of 20 milestones. </span>
			</form>
	</div>
		</div>

	<!--  **************  End milestone section here Written by Royal Tyagi *****************  -->
	<!--   ************* Start code for new section for Risk Management Written by Royal Tyagi ****************** -->
	<?php 

		include("include/block-stage-one-pdf.php");
		$riskStaus = getRiskSectionStatus('risk_section');
		
		$is_risk_management = get_option("risk_management");
		if ( $is_risk_management == 1 && $riskStaus ==1 ):

			// get the selection creteria section status value
			$query = "SELECT section_status FROM wp_specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='riskManagementMoreInfo'";
			$riskStatus = $wpdb->get_col($query);

			$riskStat =  $riskStatus[0];
			if($riskStat == 2) {
			  $procpltbtn ='green';
			  $riskManagementCompSt = '<span class="complete pull-right">Complete</span>';
			} elseif ($riskStat == 1) {
			  $procpltbtn ='grey1';
			  $riskManagementCompSt = '<span class="incomplete pull-right">In Progress</span>';

			} else {
			  $procpltbtn ='grey1';
			  $riskManagementCompSt = '<span class="notstred pull-right">Not Started</span>';
			}

			$risk_row = 0;
			$impact = array(
				'critical'		=> 'Critical',
				'hight'	=> 'High',
				'medium'	=> 'Medium',
				'low'	=> 'Low'
			);

			$query = "SELECT id,member_name,member_email,member_responsibility,member_estimate_effort,member_type From ".$wpdb->prefix."specdoc_userteaminfo Where userid= '".$loggedin_id."' AND (member_type='member' OR member_type='tl')";
			$owner = $wpdb->get_results($query,ARRAY_A);

			$allData = $obj->getAllRiskManagementBlock($loggedin_id);
	?>
	
		<h5 class=""><span class="blue-arrow pull-right"></span>
		<span class="accordion_title">Risk Management</span>
		<?php echo $riskManagementCompSt;	?></h5>
		<div class="" id="riskManagementMoreInfo" class="riskManagementMoreInfo">
			<div class="accordian-inner-container">

			<div class="form-three-colum">
				<div class="first-colum" style="text-indent: -9999px;">XYZ</div>
				<div class="second-colum" style="text-indent: -9999px;">
					 <b>dfd</b>
				  </div>
				<div class="third-colum">
					<b style="text-indent: -9999px;"></b>
					<div id="riskManagementMoreinfohelp" class="help-click"></div>
				</div>
			</div>

			<form method="post" class="bg-abt" name="formRiskMoreInfo" id="formRiskMoreInfo" action="<?php echo plugin_dir_url( __FILE__  ); ?>riskMoreInfo.php">
				<?php foreach ( $allData as $data ) { ?>
					<div class="riskManagementMoreInfo_section">

						<div class="form-three-colum">
							<div class="first-colum"><b>Name</b></div>
							<div class="second-colum" >
								 <b>LIKELIHOOD</b>
							  </div>
							<div class="third-colum">
								<b>Type</b>
							</div>
						</div>

						<div id="riskManagementMoreInfo_Content">
							<div class="form-three-colum">
								<div class="first-colum">
									<?php echo $data['riskName']; ?>
								</div>
								<div class="second-colum">
									<?php echo $data['LikelihoodOfOccurrence']; ?>
								</div>
								<div class="third-colum">
									<?php echo $data['riskType']; ?>
								</div>
							</div>
						</div>

						<div class="form-three-colum">
							<div class="first-colum"><b>Impact</b></div>
							<div class="second-colum" >
								 <b>Owner</b>
							  </div>
							<div class="third-colum">
								<b>Monitoring Period(Weeks)</b>
							</div>
						</div>

						<div id="riskManagementMoreInfo_Content">
							<div class="form-three-colum">
								<input type="hidden" value="<?php echo $data['id']; ?>" name="riskManagement[<?=$risk_row; ?>][id]" class="required" />
								<div class="first-colum">
									<select name="riskManagement[<?=$risk_row; ?>][impact]">
										<?php foreach ( $impact as $kev => $vel ) { ?>
											<?php if($kev == $data['impact'] ) { ?>
												<option value="<?php echo $kev; ?>" selected="selected"><?php echo $vel; ?></option>
											<?php } else { ?>
												<option value="<?php echo $kev; ?>"><?php echo $vel; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>

								<div class="second-colum">
									<select name="riskManagement[<?=$risk_row; ?>][owner]">
										<?php foreach ( $owner as $own ) { ?>
											<?php if($own['member_name'] == $data['owner'] ) { ?>
												<option value="<?php echo $own['member_name']; ?>" selected="selected"><?php echo $own['member_name']; ?></option>
											<?php } else { ?>
												<option value="<?php echo $own['member_name']; ?>"><?php echo $own['member_name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>

								<div class="third-colum">
									<input type="number" value="<?php echo $data['monitoring_period']; ?>" name="riskManagement[<?=$risk_row; ?>][monitoring_period]" min="0" required="required" class="input-text" />
								</div>
								<div class="full-colum">
									<b>Preventive actions</b>
									<input type="text" value="<?php echo $data['preventive_actions']; ?>" name="riskManagement[<?=$risk_row; ?>][preventive_actions]" class="required input-text" />
								</div>
								<div class="full-colum">
									<b>Mitigations actions</b>
									<input type="text" value="<?php echo $data['mitigations_actions']; ?>" name="riskManagement[<?=$risk_row; ?>][mitigations_actions]" class="required input-text" />
								</div>
						</div>
					</div>		
					</div><!-- End riskManagementMoreInfo_section  -->
				<?php	$risk_row++; ?>
				<?php } ?>

				<div id="riskManagementMoreinfohelptext" class="help-text" style="display:none;font-family: 'Lato', sans-serif;">
					<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
					<?php echo get_option("help_riskManagementMorehelptext")?>
				</div>

				<div class="teamMember_button">
					<a href="<?php echo site_url().'?act=aboutproject&team=4'?>" class="progress-btn addnew" style="float: right;">Edit risks</a>
				</div>

				<div class="form-btn">
					<input type="submit" value="SAVE PROGRESS" name="save_riskManagement" class="progress-btn">
					<input type="hidden" value="0" id="checkForRiskManagementMoreInfo" name="checkForRiskManagementMoreInfo">	
					<input type="button" value="Complete" name="complteRiskManagementMoreInfo" id="complteRiskManagementMoreInfo" class="compelete-btn <?php echo $procpltbtn;?>">
				</div>

				<div class="cdiv">
					<div id="complteRiskManagementMoreInfoResult" style="text-align:center;"></div>
					<div id="complteRiskManagementMoreInfoMsg" style="text-align:center; color:green;"></div>
				</div>

			</form>
		</div>
		</div>

	<?php endif; ?>
	<!--   ************* End new section for Risk Management  ***************************** -->
</div>

</div>
		<!-- end of accordian -->
		
		
	
		</div>
	</div>
	
</div>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/moreprojectinfo.js"></script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$("#formRiskMoreInfo").validate({
		 submitHandler: function(form) {
		 	//form.submit();

			$.ajax({
				type: $('#formRiskMoreInfo').attr('method'),
				url: $('#formRiskMoreInfo').attr('action'),
				data: $('#formRiskMoreInfo').serialize(),
				beforeSend: function() {
					$('#complteRiskManagementMoreInfoResult').show('');
					$('#complteRiskManagementMoreInfoResult').html('<img src="/wp-content/plugins/skopes/frontend/theme/<?=$choosenTheme; ?>/images/loader.gif">');				
				},
				success: function(data) {
					$('#complteRiskManagementMoreInfoResult').hide();
					$('#complteRiskManagementMoreInfoMsg').html('Information Saved');
					$('#complteRiskManagementMoreInfoMsg').hide().fadeIn(800).delay(3000).fadeOut(800);
					$('#complteRiskManagementMoreInfoResult').html('');

					if(data == 1) {
						$('#ui-accordion-1-header-4 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete'); 
						$('#ui-accordion-1-header-4 span:nth-child(4)').html('In Progress');
						$("#complteRiskManagementMoreInfo").removeClass("green").addClass("grey1");

					} else {

						$('#ui-accordion-1-header-4 span:nth-child(4)').removeClass('notstred incomplete').addClass('complete'); 
						$('#ui-accordion-1-header-4 span:nth-child(4)').html('Complete');
						$("#complteRiskManagementMoreInfo").removeClass("grey1").addClass("green");
					}
				}
			});
			 return false;
		}
	});

		$('#complteRiskManagementMoreInfo').on('click', function(event) {
		$('#checkForRiskManagementMoreInfo').val('1');
		$("#formRiskMoreInfo").submit();
		$('#checkForRiskManagementMoreInfo').val('0');
	});
});

//--></script>
