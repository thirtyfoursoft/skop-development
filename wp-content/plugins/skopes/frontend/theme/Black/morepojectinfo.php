<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>js/skopes-accordion-main.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>js/skopes-accordion-active.js"></script>

<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/css/jquery-ui.css" rel="stylesheet" media="screen" />
<script>
	$(function() {
		$("#accordion").accordion({ collapsible: true, active: 'none', heightStyle: "content" }); 
		
	});
</script>
<script language="javascript" type="text/javascript">
function intOnly(i)
{
if(i.value.length>0)
{
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
		<div class="subpage-content">
			<div class="box">
				<h2 class="boxheading"> FURTHER PROJECT DETAILS</h2>
				
			</div>
			
		<!-- Team information section starts here -->
		<div id="accordion" class="k1">
		<?php
				
			// get the selection creteria section status value
			$scstatus = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='selection_creteria_info'";
			$scstatusRes = $wpdb->get_col($scstatus); 
			
			$scStatus =  $scstatusRes[0];
			if($scStatus == 2){
			  $scbg = 'green-complete';
			  $sc_cpltbtn ='green1';
			  $sc_compcls = '<span class="comp-gr font14 progress-title">&nbsp;(Complete)</span>';
			}elseif($scStatus == 0){
			  $scbg = 'txt';
			  $sc_cpltbtn ='grey1';
			  $sc_compcls = '<span class="notstart-wh font14 progress-title">&nbsp;(Not Started) </span>';
			}else{
			  $scbg = 'txt';
			  $sc_cpltbtn ='grey1';
			  $sc_compcls = '<span class="incomp-bl font14 progress-title">(In Progress)</span>';
			}
		?>
			<h3 class="abtprjct-new">Selection criteria
			<?php echo $sc_compcls;?></h3>
			<div id="moreprojectinfo">
			 
		
		<form method="post" class="user-profileform bg-abt" onsubmit="" name="frmselectioncriteria" id="frmselectioncriteria" action="<?php echo plugin_dir_url( __FILE__  ); ?>postitem.php">
		<div id="inner" class="selection_criteria">
		
		   <div class="pi-left">
				<h5>Selection criteria</h5>				
			</div>
			
			<div class="pi-middle">
				
			</div>
			
			<div class="sc-right">
				<h5 style="float:left;">Importance weight(Max 100)</h5> 				
  					<div id="selectionCriteriahelp" class="help-click" style="position: absolute;right: 22px; float: right;"> ?</div>	
					<span class="error" id="authors_error"></span>				
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
				<div class="pi-left">				
					<p><?php echo $cretriaName;?></p>
				</div>
				
				<div class="pi-middle">
					
				</div>
				<?php //autotab(this, document.frmselectioncriteria.imp_weight_); ?>
				<div class="sc-right">				
					<input type="text" name="imp_weight_<?=$key?>" id="imp_weight_id_<?=$key?>" value="<?php echo intval($val);?>" onKeyup="intOnly(this); toUnicode(this,this.value);" onKeydown="intOnly(this);" onKeypress="intOnly(this);" class="impweight" maxlength="3" tabindex="<?php echo $useCtr++; ?>" onblur="intOnly(this);"> 
					<span id="errorimp_weight_<?=$key?>" class="error"></span>				
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
				foreach($SelectionCriteria as $key=>$sc){
				    
				?>
						<div class="pi-left">				
							<p><?php echo $sc['sc_name'];?></p>
						</div>
						
						<div class="pi-middle">
							
						</div>
						
						<div class="sc-right">				
							<input type="text" name="imp_weight_<?=$key+1?>" value="10" class="impweight" maxlength="3" onKeyup="intOnly(this);" onKeydown="intOnly(this);" onKeypress="intOnly(this);" >
							<span id="errorimp_weight_<?=$key+1?>" class="error"></span>				
						</div>
				<?php 
				}
			}
			?>
			<div class="pi-left">				
				<!--<input type="submit" name="saveweight" value="Save" class="saveitem" id="savecretria">-->
			</div>
			
			<div class="pi-middle">
				<div id="result"></div>
			</div> 
			
			<?php if($notStarted == 1) { ?>
			            <div class="sc-right">				
							Total = <span class="imptotal"><?php echo intval(100); ?></span>
							<span id="totalerror" class="error"></span>					
						</div>
			<?php } else { ?>
						<div class="sc-right">				
							Total = <span class="imptotal"><?php if($totalWeightsToShow > 0) { echo $totalWeightsToShow; }   ?></span>
							<span id="totalerror" class="error"></span>				
						</div>
			<?php } ?>			
			</div>

			<div id="selectionCriteriahelptext" class="help-text" style="display:none; width: 95%;margin: 0 0 10px 0; font-family: 'Lato', sans-serif;">
				<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
				<?php echo get_option("help_selectionCriteriahelpText")?>
			</div>
			
              <?php if($notStarted == 1) { ?>			
					<div class="savemswrapper <?=$scbg;?>" id="scbgcmplt">
						<span class="btn-blue1 btn1 mrgn">
						<input type="submit" value="Save progress" name="save_Cretria" class="btn-inner1 fnt d1" ></span>
						<span class="btn-<?=$sc_cpltbtn;?> btn1 mrgn"> 
						<input type="button" value="Complete" name="compelte_Cretria" id="compelte_Cretria2" onclick="javascript: firstTimeValidate();" class="btn-inner1 fnt d1"></span>
					</div>
			   <?php } else { ?>		
		            <div class="savemswrapper <?=$scbg;?>" id="scbgcmplt">
						<span class="btn-blue1 btn1 mrgn">
						<input type="submit" value="Save progress" name="save_Cretria" class="btn-inner1 fnt d1" ></span>
						<span class="btn-<?=$sc_cpltbtn;?> btn1 mrgn"> 
						<input type="button" value="Complete" name="compelte_Cretria" id="compelte_Cretria" class="btn-inner1 fnt d1"></span>
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
					    $('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('incomp-bl').addClass('comp-gr');
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('notstart-wh').addClass('comp-gr');						
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').html('&nbsp;(Complete)');
						$("#scbgcmplt").removeClass("txt").addClass("green-complete");
						$("#compelte_Cretria2").parent().removeClass("btn-grey1").addClass("btn-green1");
										
					}else if(data == 0 || data == 1){
					    $("#totalerror").html('Total of all creatia cannot exceed 100, please set the priority again'); 
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('comp-gr').addClass('incomp-bl');
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');						
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').html('&nbsp;(In Progress)');
						$("#scbgcmplt").removeClass("green-complete").addClass("txt");
						$("#compelte_Cretria2").parent().removeClass("btn-green1").addClass("btn-grey1");
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
					    $('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('incomp-bl').addClass('comp-gr');
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('notstart-wh').addClass('comp-gr');						
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').html('&nbsp;(Complete)');
						$("#scbgcmplt").removeClass("txt").addClass("green-complete");
						$("#compelte_Cretria2").parent().removeClass("btn-grey1").addClass("btn-green1");
										
					}else if(data == 0 || data == 1){
					    $("#totalerror").html('Total of all creatia cannot exceed 100, please set the priority again'); 
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('comp-gr').addClass('incomp-bl');
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');						
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').html('&nbsp;(In Progress)');
						$("#scbgcmplt").removeClass("green-complete").addClass("txt");
						$("#compelte_Cretria2").parent().removeClass("btn-green1").addClass("btn-grey1");
					}
				}
		   });   
	 } 
}

</script>			   
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
			  $teambg = 'green-complete';
			  $teamcpltbtn ='green1';
			  $teamcompcls = '<span class="comp-gr font14 progress-title">&nbsp;(Complete)</span>';
			}else if($mstatus == 0){
			  $teambg = 'txt';
			  $teamcpltbtn ='grey1';
			  $teamcompcls = '<span class="notstart-wh font14 progress-title">&nbsp;(Not Started) </span>';
			}else{
			  $teambg = 'txt';
			  $teamcpltbtn ='grey1';
			  $teamcompcls = '<span class="incomp-bl font14 progress-title">(In Progress)</span>';
			}?>

			
	<h3 class="abtprjct-new">Team responsibilities and effort
	<?php echo $teamcompcls;?>
	</h3>
	<div class="box_white marbtm30" id="team_responsibilities">
			<form method="post" class="bg-abt" name="frmteammoreinfo" id="frmteammoreinfo" action="<?php echo plugin_dir_url( __FILE__  ); ?>teammoreinfo.php">
			<!-- onsubmit="return fnvalidate_teaminfo()" -->
			
			<div class="form-heading">
				
				<div class="pi-left">
					
				</div>
				
				<div class="pi-middle">
					<h5>Responsibility</h5>
				</div>
			
				<div class="sc-right">										
					<h5 style="float: left;">Estimated effort (hrs) per week </h5>
  					<div id="teamResponsibilitieshelp" class="help-click" style="position: absolute;right: 22px; float: right;"> ?</div>	
					<span class="error" id="authors_error"></span>
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
						<div class="tldetails">
							<div class="pi-left">		
								<?php //echo ucfirst($tmemberName);?>
								<?php echo htmlspecialchars(stripslashes(ucfirst($tmemberName)));?>
								<input type="hidden" name="tlid" value="<?php echo $tmemberId;?>">
							</div>
										
							<div class="pi-middle">
								<div>
								<?php //echo $tmemberResponsiblity; ?>
								<input type="text" value="<?php echo htmlspecialchars(stripslashes($tmemberResponsiblity));?>" id="teamleadresp" name="teamleadresp">
								<span id="error_teamleadresp" class="error"></span>
								</div>
							</div>
								
							<div class="sc-right">
								<div>
								<?php //echo $tmemberEffort;?>
									<input type="text" value="<?php echo htmlspecialchars(stripslashes($tmemberEffort));?>" id="teamlead_effort" name="teamlead_effort">
									<span id="error_teamlead_effort" class="error"></span>
								</div>	
							</div>
						</div>
					<?php }				
				$i=0; ?>
				<div id="memberinfo"> 
				<?php 
				 $totalResp = '';
				foreach ($memberresultarray as $key=>$val){

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
					<div class="pi-left">
					<?php //echo ucfirst($memberName);?>
						<?php echo htmlspecialchars(stripslashes(ucfirst($memberName)));?>						
					</div>
					
					<div class="pi-middle">
						<div><?php //echo $memberResponsiblity;?>
							<input type="text" class="memresp" value="<?php echo htmlspecialchars(stripslashes($memberResponsiblity));?>" id="memberresp_<?=$i;?>" name="memberresp[<?php echo $memberId;?>]">
							<span id="error_memberresp_<?=$i;?>" class="error"></span>
						</div>
					</div>
			
					<div class="sc-right">
						<div><?php //echo $memberEffort;?>
							<input type="text" class="memeffort" value="<?php echo htmlspecialchars(stripslashes($memberEffort));?>" id="membereffort_<?=$i;?>" name="membereffort[<?php echo $memberId;?>]">
							<span id="error_membereffort_<?=$i;?>" class="error"></span>
						</div>
					</div>
					
				</div>  
			
		
		<?php 
		             if($totalResp == '')
					 {
						 $totalResp .= $i;
					 }
					 else
					 {
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

			<div id="teamResponsibilitieshelptext" class="help-text" style="display:none; width: 95%;margin: 0 0 10px 0; font-family: 'Lato', sans-serif;">
				<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
				<?php echo get_option("help_teamResponsibilitieshelpText")?>
			</div>

			<div class="teamMember_button">
				<span class="small btn-blue1">					
					<a href="<?php echo site_url().'?act=aboutproject&team=1#ui-accordion-accordion-header-1'?>" style="padding: 2px 7px;">Edit team members</a>
				</span>
			</div>

			<div class="submit-buttons <?php echo $teambg;?> wdth" style="text-align:center;" id="teamcplt">
				<span class="btn-blue1 btn1 mrgn">			
				<input type="submit" value="Save progress" name="save_project" class="btn-inner1 fnt d1">
				</span>
				<span class="btn-<?php echo $teamcpltbtn;?> btn1 fnt mrgn">				
				<input type="button" value="Complete" name="compltemembermoreinfo" class="btn-inner1 fnt d1" id="compltemembermoreinfo"></span>

			</div>
			<div class="cdiv">
				<div id="teammoreresult"></div>
				<div id="tmresultmsg"></div>
			</div>
		    </form>
			
		</div>
		
		
		
		<?php
				
			// get the benefit section status value
			$benefitstatus = "SELECT section_status FROM ".$wpdb->prefix."specdoc_userstatusinfo WHERE userid='$loggedin_id' AND section_name='benefitinfo'";
			$bstatusRes = $wpdb->get_col($benefitstatus); 
			
			$bstatus =  $bstatusRes[0]; 
			if($bstatus == 2){
			  $benefitbg = 'green-complete';
			  $benefitcpltbtn ='green1';
			  $benefitcompcls = '<span class="comp-gr font14 progress-title">&nbsp;(Complete)</span>';
			}elseif($bstatus == 0){
			  $benefitbg = 'txt'; 
			  $benefitcpltbtn ='grey1';
			  $benefitcompcls = '<span class="notstart-wh font14 progress-title">&nbsp;(Not Started) </span>';
			}else{
			  $benefitbg = 'txt';
			  $benefitcpltbtn ='grey1';
			  $benefitcompcls = '<span class="incomp-bl font14 progress-title">(In Progress)</span>';
			}
		?>
			<h3 class="abtprjct-new"> Non-financial benefits
				<?php echo $benefitcompcls;?></h3>
			<div class="box_white marbtm30" >
			
			<div class="accordian_inner_content stakeholders" id="benifit">
				<div class="form-heading bg-abt">
					<div class="pi-left">
					</div> 
					<div class="pi-middle">
						<h5 style="width: 78%; float: left;font-weight: bold;">Benefit</h5>
						<div id="nonfinancialBenefits" class="help-click" style="float: right; margin-right: -16px;"> ?</div>	
						<span class="error" id="authors_error"></span>
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
						<div>
							<div class="pi-left">
								Benefit 1
							</div>
							<div class="pi-middle">
								<input type="text" value="" id="anbenefit_1" name="anbenefits[]"><a class="removeabclass" href="#" onclick="decrement();">×</a>
								<span id="error_anbenefit_1" class="error sherror"></span>
							</div>
						</div>
					</div>
					
				<?php }else{?>

				<div id="BenefitsWrapper1">
				<?php	foreach($abarray as $abkey=>$abvalue){ ?>
					<?php //echo htmlspecialchars_decode($abvalue, ENT_NOQUOTES);?>
						<div>												
							<div class="pi-left">
								Benefit <?=$abkey+1;?>
							</div>
							<div class="pi-middle">
								<input type="text" value="<?php echo htmlspecialchars(stripslashes($abvalue));?>" id="anbenefit_<?=$abkey+1;?>" name="anbenefits[]" class="benefits">					
								<span id="error_anbenefit_<?=$abkey+1;?>" class="error sherror"></span>
								<a class="removeabclass" href="#" onclick="decrement();">×</a>
							</div>
						</div>
				<?php
					}
					?>
					</div>
				<?php
				}

			   ?>
		
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

			<div id="nonfinancialBenefitstext" class="help-text" style="display:none; width: 95%;margin: 0 0 10px 0; font-family: 'Lato', sans-serif;">
				<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
				<?php echo get_option("help_nonfinancialBenefitsText")?>
			</div>

				<div class="teamMember_button">
					<span class="small btn-blue1" style="width:115px">		
						<?php if(count($abarray) == 0){ ?>
						<a id="AddMoreBenefit" class="intro" href="#" style="padding: 0 20px;" onclick="increment();">Add Benefit</a>
						<?php }else{ ?>
						<a id="AddMoreBenefit1" class="intro" href="#" style="padding: 0 20px;" onclick="increment();">Add Benefit</a>
						<?php }?>
					</span>
				</div>

			 <div class="submit-buttons <?=$benefitbg?>" id="abcmpltbg">
				    <input type="hidden" name="hiddenaction" value="saveanbenefitlist">
					<span class="btn-blue1 btn1 mrgn">
					<input type="submit" value="Save progress" name="savebenefitlist" class="btn-inner1 fnt d1 saveitem" ></span>
					<span class="btn-<?php echo $benefitcpltbtn;?> btn1 mrgn">
					<input type="button" value="Complete" name="complete_benefit" id="complete_benefit" class="btn-inner1 fnt d1" ></span>
			  </div>
			<span>Note: You can add a maximum of 9 benefits.</span>
			<div class="cdiv">
				<div id="abimg"></div>
				<div id="abmsg"></div> 
			</div>
		</div>
	</form>
</div>

	<!--  **************  Start milestone section here Written by Royal Tyagi *****************  -->


		<?php
		    // get milestone status from database
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
			//echo "<pre>";		print_r($Selectml);	echo "</pre>";
		?>
		
		  <h3 class="abtprjct-new">Milestones
			<?php echo $milestonecompcls;?></h3>
			<div class="box_white marbtm30 abt_project"> 
<div class="accordian_inner_content milestone">						
			<div class="form-heading bg-abt">		
				
				<div class="pi-left"></div>
				
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
							<?php echo "Milestone <span id='miles_".$keyval."'>".($keyval)."</span>:";?>
							<?php }?>
						</div>

						<div class="pi-middle">
						<?php if($keyval == 0){ ?>
							<input type="text" readonly="readonly" name="firstmilestone" value="<?php echo htmlspecialchars(stripslashes($msval['milestone']));?>">						
						<?php }elseif($keyval+1 == $totalMs){ ?>
							<input type="text" readonly="readonly" name="finalmilestone" id="finalmilestone" value="<?php echo htmlspecialchars_decode($msval['milestone'], ENT_NOQUOTES);?>">						
						<?php }else{ ?>

							<?php if($keyval == 1){  ?>
								<input type="text" name="milestone[]" value="<?php echo htmlspecialchars(stripslashes($msval['milestone']));?>" readonly="readonly">
							<?php } else { ?>
								<input type="text" name="milestone[]" value="<?php echo htmlspecialchars(stripslashes($msval['milestone']));?>">
							<?php } ?>
							
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
							<!--<input type="text" id="datepickerfirst" name="firstmilestonedate" value="<?php echo $msdate; ?>" class="" readonly="readonly">-->
							<input type="text" id="datepickerfirst" name="firstmilestonedate" value="<?php echo $data['project_commencement']; ?>" class="" readonly="readonly">
						<?php }elseif($keyval+1 == $totalMs){ ?>

							<!--<input type="text" id="datepicker11" name="finalmilestonedate" value="<?php echo $msdate; ?>" class="" readonly="readonly">	-->
							<input type="text" id="datepicker11" name="finalmilestonedate" value="<?php echo $data['project_completion']; ?>" class="" readonly="readonly">
						<?php }else{ ?>

							<?php if($keyval == 1){  ?>
								<!--<input type="text" id="datepicker<?php echo $mskey;?>" name="milestonedate[]" value="<?php echo $msdate; ?>" class="" readonly="readonly">-->
								<input type="text" id="datepicker<?php echo $mskey;?>" name="milestonedate[]" value="<?php echo $data['supplier']; ?>" class="" readonly="readonly">
							<?php } else { ?>
								<input type="text" id="datepicker<?php echo $mskey;?>" name="milestonedate[]" value="<?php echo $msdate; ?>" class="pickdate" readonly="readonly">	
							<?php } ?>
						<?php }?>						 
						</div>
						
						<input type="hidden" name="msid[]" value="<?php echo $msval['mid'];?>">
						<?php if($keyval == 0 || $keyval == 1 || $keyval+1 == $totalMs){ ?>
						<?php }else{?>
						<a href="#" class="removemclass">x</a>
						<?php }?>
					</div>
			<?php		
					}
				} else {

				?>
				<div class="mstoneinfo">
					<div class="pi-left">First milestone: </div>		
					<div class="pi-middle">
						<input type="text" readonly="readonly" name="firstmilestone" value="Project Commencement" >
					</div>			
					<div class="sc-right">
					 <input type="text" id="datepickerfirst" name="firstmilestonedate" class="" readonly="readonly" value="<?php echo $data['project_commencement']; ?>">				
					</div>
				</div>
				
				<div class="milestoneinfo">
					<div class="pi-left">Milestone <span id='miles_1'>1</span>: </div>		
					<div class="pi-middle">
						<input type="text" name="milestone[]" value="Supplier engaged" readonly="readonly" >
					</div>			
					<div class="sc-right">
					 <input type="text" id="datepicker1" name="milestonedate[]" class="" readonly="readonly" value="<?php echo $data['supplier']; ?>">				
					</div>
					<!--<a href="#" class="removemclass">x</a>-->
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
					 <input type="text" id="datepicker11" name="finalmilestonedate"  class="" value="<?php echo $data['project_completion']; ?>" readonly="readonly">	
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
			  $probg = 'green-complete';
			  $procpltbtn ='green1';
			  $riskManagementCompSt = '<span class="comp-gr font14 progress-title">&nbsp;(Complete)</span>';
			} elseif ($riskStat == 1) {
			  $probg = 'txt';
			  $procpltbtn ='grey1';
			  $riskManagementCompSt = '<span class="incomp-bl font14 progress-title">(In Progress)</span>';

			} else {
			  $probg = 'txt';
			  $procpltbtn ='grey1';
			  $riskManagementCompSt = '<span class="notstart-wh font14 progress-title">&nbsp;(Not Started) </span>';
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
			//echo "<pre>";	print_r($allData);		echo "</pre>";

		
	?>

		<h3 class="abtprjct-new">Risk Management <?php echo $riskManagementCompSt;	?></h3>
		<div class="box_white marbtm30" id="riskManagementMoreInfo" class="riskManagementMoreInfo">
			<div class="riskHelp">
				<div id="riskManagementMoreinfohelp" class="help-click" style="float: right; margin-right: 30px;"> ?</div>
				<span class="error" id="authors_error"></span>
			</div>
			<form method="post" class="bg-abt" name="formRiskMoreInfo" id="formRiskMoreInfo" action="<?php echo plugin_dir_url( __FILE__  ); ?>riskMoreInfo.php">
				<?php foreach ( $allData as $data ) { ?>
					<div class="riskManagementMoreInfo_section">
						<div class="form-heading">
							<div class="pi-left">
								<h5>Name</h5>
							</div>
							<div class="pi-middle">
								<h5>LIKELIHOOD</h5>
							</div>
							<div class="sc-right">
								<h5 style="float: left;">Type</h5>
							</div>
						</div>


						<div id="riskManagementMoreInfo_Content">
								<div class="pi-left">
									<?php echo $data['riskName']; ?>
								</div>
								<div class="pi-middle">
									<?php echo $data['LikelihoodOfOccurrence']; ?>
								</div>
								<div class="sc-right">
									<?php echo $data['riskType']; ?>
								</div>
						</div>

						<div class="form-heading">
							<div class="pi-left">
								<h5>Impact</h5>
							</div>
							<div class="pi-middle">
								<h5>Owner</h5>
							</div>
							<div class="sc-right">
								<h5 style="float: left;">Monitoring Period(Weeks)</h5>
							</div>
						</div>

						<div id="riskManagementMoreInfo_Content">
								<input type="hidden" value="<?php echo $data['id']; ?>" name="riskManagement[<?=$risk_row; ?>][id]" class="required" />
								<div class="pi-left">
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

								<div class="pi-middle">
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

								<div class="sc-right">
									<input type="number" value="<?php echo $data['monitoring_period']; ?>" name="riskManagement[<?=$risk_row; ?>][monitoring_period]" min="0" required="required" />
								</div>
								<div class="sc-full">
									<h5>Preventive actions</h5>
									<input type="text" value="<?php echo $data['preventive_actions']; ?>" name="riskManagement[<?=$risk_row; ?>][preventive_actions]" class="required" />
								</div>
								<div class="sc-full">
									<h5>Mitigations actions</h5>
									<input type="text" value="<?php echo $data['mitigations_actions']; ?>" name="riskManagement[<?=$risk_row; ?>][mitigations_actions]" class="required" />
								</div>
						</div>
					</div><!-- End riskManagementMoreInfo_section  -->
				<?php	$risk_row++; ?>
				<?php } ?>

				<div id="riskManagementMoreinfohelptext" class="help-text" style="display:none; width: 95%;margin: 0 0 10px 0; font-family: 'Lato', sans-serif;">
					<img class="help-text-img" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help-text.png">
					<?php echo get_option("help_riskManagementMorehelptext")?>
				</div>

				<div class="teamMember_button">
					<span class="small btn-blue1" style="width: 75px;">
					<a href="<?php echo site_url().'?act=aboutproject&team=4'?>" style="padding: 2px 7px;">Edit risks</a>
					</span>
				</div>

				<div class="submit-buttons <?php echo $probg;?> wdth" style="text-align:center;" id="riskTemp">
					<span class="btn-blue1 btn1 mrgn">
					<input type="submit" value="Save progress" name="save_riskManagement" class="btn-inner1 fnt d1">
					</span>
					<span class="btn-<?php echo $procpltbtn;?> btn1 fnt mrgn">
					<input type="hidden" value="0" id="checkForRiskManagementMoreInfo" name="checkForRiskManagementMoreInfo">	
					<input type="button" value="Complete" name="complteRiskManagementMoreInfo" class="btn-inner1 fnt d1" id="complteRiskManagementMoreInfo"></span>
				</div>

				<div class="cdiv">
					<div id="complteRiskManagementMoreInfoResult"></div>
					<div id="complteRiskManagementMoreInfoMsg"></div>
				</div>

			</form>
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
						$('#ui-accordion-accordion-header-4 span:nth-child(2)').removeClass('comp-gr').addClass('incomp-bl');
						$('#ui-accordion-accordion-header-4 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');
						$('#ui-accordion-accordion-header-4 span:nth-child(2)').html('&nbsp;(In Progress)');
						$("#riskTemp").removeClass("green-complete").addClass("txt");
						$("#complteRiskManagementMoreInfo").parent().removeClass("btn-green1").addClass("btn-grey1");
					} else {
						$('#ui-accordion-accordion-header-4 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');			
						$('#ui-accordion-accordion-header-4 span:nth-child(2)').removeClass('incomp-bl').addClass('comp-gr');
						$('#ui-accordion-accordion-header-4 span:nth-child(2)').html('&nbsp;(Complete)');
						$("#riskTemp").removeClass("txt").addClass("green-complete");
						$("#complteRiskManagementMoreInfo").parent().removeClass("btn-grey1").addClass("btn-green1");
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
