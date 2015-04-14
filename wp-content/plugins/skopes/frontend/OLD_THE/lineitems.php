<?php
/*
*   Name: Twisha Patel  Date: 23-8-2013
*
*   Lineitem list in popup
*/
	include("../../../../wp-config.php");

	$objUser = new clsSkopes();
	$userid = get_current_user_id();
?>
<script>
    function savePopup()
	{  
	    catid=jQuery(this).attr("catid");
		jQuery.ajax({
			   url:  path+"/savedata.php?action=SaveCategoryNote&catid="+escape(catid),
				type: "POST",
				data: jQuery("#frmnote").serialize()
			   }).done(function(data) {
					//alert(data);
					jQuery("#lineitem_popup").css("overflow","")
					jQuery(".note-content").hide();
					jQuery(".note-section").animate({height:'20px'},500);
					jQuery("#lineitem_popup").animate({height:'380px'},500);
			});
	}
    function cancelPopup()
	{
	    jQuery("#lineitem_popup").css("overflow","")
		jQuery(".note-content").hide();
		jQuery(".note-section").animate({height:'20px'},500);
		jQuery("#lineitem_popup").animate({height:'380px'},500); 
	}
	
    function openNotes()
	{ 
		jQuery("#lineitem_popup").css("overflow","hidden")
		jQuery("#lineitem_popup").animate({height:'0px'},500);
		jQuery(".note-section").animate({height:'400px'},500);
		jQuery(".note-content").show(); 
		/*jQuery("#lineitem_scroll").slideUp(500,function(){
			jQuery(".note-content").slideDown(500);
		});*/
		if(jQuery(this).css('display')=="none")
		{
			jQuery(this).show();

		}
		if($(".note-content .textarea-wrapper textarea").length == 0)
		{
			var textArea=$(".note-content textarea");
			textArea.wrap("<div class='textarea-wrapper' />");
			var textAreaWrapper=textArea.parent(".textarea-wrapper");
			textAreaWrapper.mCustomScrollbar({
				scrollInertia:0,
				advanced:{autoScrollOnFocus:false}
			});
			var hiddenDiv=$(document.createElement("div")),
				content=null;
			hiddenDiv.addClass("hiddendiv1");
			$("body").prepend(hiddenDiv);
			textArea.bind("keyup",function(e){
				content=$(this).val();
				var clength=content.length;
				var cursorPosition=textArea.getCursorPosition();
				content="<span>"+content.substr(0,cursorPosition)+"</span>"+content.substr(cursorPosition,content.length);
				content=content.replace(/\n/g,"<br />");
				hiddenDiv.html(content+"<br />");
				$(this).css("height",hiddenDiv.height());
				textAreaWrapper.mCustomScrollbar("update");
				var hiddenDivSpan=hiddenDiv.children("span"),
					hiddenDivSpanOffset=0,
					viewLimitBottom=(parseInt(hiddenDiv.css("min-height")))-hiddenDivSpanOffset,
					viewLimitTop=hiddenDivSpanOffset,
					viewRatio=Math.round(hiddenDivSpan.height()+textAreaWrapper.find(".mCSB_container").position().top);
				if(viewRatio>viewLimitBottom || viewRatio<viewLimitTop){
					if((hiddenDivSpan.height()-hiddenDivSpanOffset)>0){
						textAreaWrapper.mCustomScrollbar("scrollTo",hiddenDivSpan.height()-hiddenDivSpanOffset);
					}else{
						textAreaWrapper.mCustomScrollbar("scrollTo","top");
					}
				}
			});
		}
		else{
			jQuery(".note-content .textarea-wrapper textarea").mCustomScrollbar("update");
		}
	}
	function makeNo(lineItemId)
	{ alert(lineItemId);
	     document.getElementById('rowno-data_'+lineItemId).style.display = 'none';
	}
    function intOnly(i)
	{
		if(i.value.length>0)
		{
		     i.value = i.value.replace(/[^\d]+/g, '');
		}
	} 
	function floatOnly(i)
	{
		if(i.value.length>0)
		{
		     i.value = i.value.replace(/[^\d.]+/g, '');
		}
	}
    function showHideInvestment(mode, lineItemId)
	{
		  if(mode == "yes")
		  {
			  document.getElementById('row5_'+lineItemId).style.display = '';
		  }
		  else
		  {
			  document.getElementById('row5_'+lineItemId).style.display = 'none';
		  }
	}
	
	function showHideAllBeneBoxes(mode, lineItemId)
	{
	     if(mode == "no")
		 {
		     document.getElementById('benefit_id_'+lineItemId).checked = false;
			 document.getElementById('lineitem_benefit_other_'+lineItemId).value = ''; 
		 }
		 else
		 {   
		     document.getElementById('lineitem_year_'+lineItemId).value = '';
			 document.getElementById('lineitem_cost_'+lineItemId).value = '';
			 document.getElementById('lineitem_time_'+lineItemId).value = '';
			 document.getElementById('lineitem_eoi_'+lineItemId).value = '';
		 }
	}
	
	function showHideAllROIBoxes(mode, lineItemId)
	{ 
		 if(mode == "no")
		 {
		     document.getElementById('lineitem_year_'+lineItemId).value = '';
			 document.getElementById('lineitem_cost_'+lineItemId).value = '';
			 document.getElementById('lineitem_time_'+lineItemId).value = '';
			 document.getElementById('lineitem_eoi_'+lineItemId).value = '';
		 }
		else
		 {
		     document.getElementById('benefit_id_'+lineItemId).checked = false;
			 document.getElementById('lineitem_benefit_other_'+lineItemId).value = ''; 
		 }		 
	}
</script>
<?php

	if(isset($_REQUEST["type"]))
	{
		if($_REQUEST["type"]=="EditLineItems")
		{
			if($_REQUEST["catid"]!="")
			{
				$catdata=$objUser->GetCategoryDataById($_REQUEST["catid"]);
?>
				<h2 class="boxheading"><?php echo $catdata->name; ?></h2>
				<div class="scrollbox" id="lineitem_popup">
				
					<div class="scrollbar-section" id="lineitem_scroll">
						<!-- Start: Subquestion Section -->
						<div class="subquestion-section">
							<?php
								$lineitemdata=$objUser->GetLineItemFromToolData($userid,$_REQUEST["catid"]);
								// seprating and combining line items to display the default one first then the items added by user
								if($lineitemdata)
								{
								    foreach($lineitemdata as $linedata)
									{
									 
									  if($linedata[LINEITEM_IS_CUSTOM]==0)
									  {
									   $array1[] = $linedata;
									  }else{
									   $array2[] = $linedata;
									   }
									}
									if(count($array1)!=0 && count($array2)!=0){
										$lineItemsArray = array_merge($array1, $array2);
									}else{
									    $lineItemsArray = $lineitemdata;
									}
								 
									foreach($lineItemsArray as $linedata)
									{
										$cls1="";
										$cls2="";
										//echo "Res: ".$linedata[LINEITEM_STATUS]."<br>";
										if($linedata[LINEITEM_STATUS]==1)
										{
											$cls1="active";
											$cls2="deactivated";
										}
										//else if($linedata[LINEITEM_STATUS]==2)
										else if($linedata[LINEITEM_STATUS]==0)
										{
											$cls2="active";
											$cls1="deactivated";
										}
										/*  echo '<pre>';
										print_r($linedata);
										 echo '</pre>'; */
							?>
									<form id="frmlineitem_<?php echo $linedata[LINEITEM_ID];?>" method="post" class="lineitemfrm">
									    <?php $hrlyRate =  get_user_meta($userid,'org_ques_9_state', true);  ?>			
										<input type="hidden" name="user_hr" id ="user_hr" value="<?=$hrlyRate?>">
										<input type="hidden" name="lineitem_id" id="lineitem_id_<?php echo $linedata[LINEITEM_ID];?>" value="<?php echo $linedata[LINEITEM_ID];?>" />
										<input type="hidden" name="lineitem_status" id="lineitem_status_<?php echo $linedata[LINEITEM_ID];?>" value="<?php echo $linedata[LINEITEM_STATUS];?>" />
										<input type="hidden" name="lineitem_investment" id="lineitem_investment_<?php echo $linedata[LINEITEM_ID];?>" value="<?php if($linedata[LINEITEM_INVESTMENT_REQ]!=""){ echo $linedata[LINEITEM_INVESTMENT_REQ];} else { echo "1"; }  ?>" />
										<input type="hidden" name="lineitem_automated" id="lineitem_automated_<?php echo $linedata[LINEITEM_ID];?>" value="<?php if($linedata[LINEITEM_IS_AUTOMATED]!="") { echo $linedata[LINEITEM_IS_AUTOMATED]; } else { echo "1"; } ?>" />
										<input type="hidden" name="lineitem_benefit" id="lineitem_benefit_<?php echo $linedata[LINEITEM_ID];?>" value="<?php if($linedata[LINEITEM_IS_BENEFIT]!="") { echo $linedata[LINEITEM_IS_BENEFIT]; } else { echo "0"; } ?>" />
										<input type="hidden" name="lineitem_benefit_for" id="lineitem_benefit_for_<?php echo $linedata[LINEITEM_ID];?>" value="<?php if($linedata[LINEITEM_BENEFIT_FOR]!="") { echo $linedata[LINEITEM_BENEFIT_FOR]; } else { echo ""; } ?>" />
										<div class="sq-rawmain" lineid="<?php echo $linedata[LINEITEM_ID];?>" id="lineitem_<?php echo $linedata[LINEITEM_ID];?>">
											<div class="sq-raw">
												<div class="sq-head clearfix">
													<?php
													   $lineItemName = stripslashes($objUser->GetLineItemName($linedata[LINEITEM_ID]));

														if($linedata[LINEITEM_IS_CUSTOM]==0) {
													?>
													<h6><?php echo $lineItemName; ?></h6>
													
													<?php 	} else {	?>
														<input type="text" name="name" id="name_<?php echo $linedata[LINEITEM_ID]; ?>" value="<?php echo nl2br($lineItemName); ?>" placeholder="<Enter name for custom line item>" class="sq-heading lineitemname" lineid="<?php echo $linedata[LINEITEM_ID]; ?>"  catid="<?php echo $_REQUEST["catid"]; ?>" />
														<script type="text/javascript">
															jQuery('.subquestion-section').addClass("custom-section");
														</script>
													<?php
														}
													?>
													<div class="confirm-button clearfix">
														<a href="javascript:void(1)"  id="button_yes_<?php echo $linedata[LINEITEM_ID]; ?>" class="button-yes lineitem-yes <?php echo $cls1; ?>" lineid="<?php echo $linedata[LINEITEM_ID]; ?>" catid="<?php echo $_REQUEST["catid"]; ?>" statusofLineItem="<?php echo $linedata[LINEITEM_STATUS]; ?>">Yes</a>
														<a href="javascript:void(1)"  id="button_no_<?php echo $linedata[LINEITEM_ID]; ?>" class="button-no lineitem-no <?php echo $cls2; ?>" lineid="<?php echo $linedata[LINEITEM_ID]; ?>" catid="<?php echo $_REQUEST["catid"]; ?>">No</a>
													</div>
												</div>
											</div>

											<div class="sq-editsection" id="edit_delete_<?php echo $linedata[LINEITEM_ID] ?>" <?php if( $linedata[LINEITEM_STATUS]==0 && $linedata[LINEITEM_IS_CUSTOM]==0 ){?>style="display:none;"<?php } ?>>
													<a href="javascript:void(1)"  lineid="<?php echo $linedata[LINEITEM_ID];?>"  class="btn-edit editlineitem" <?php if( $linedata[LINEITEM_STATUS]==0){?>style="display:none;"<?php } ?>>Edit</a>
													<?php
														if($linedata[LINEITEM_IS_CUSTOM]==1)
														{
													?>
														<a href="javascript:void(1)" lineid="<?php echo $linedata[LINEITEM_ID];?>" catid="<?php echo $_REQUEST["catid"];?>" class="btn-delete deletelineitem">Delete</a>
													<?php
														}
													?>
											</div>

											<div class="sq-body" id="lineitem_detail_<?php echo $linedata[LINEITEM_ID]; ?>">
												<div class="sq-block clearfix">
													<div class="sq-cols1">
														<p class="rating-head">Importance</p>
														<div class="rating-section">
															<?php
																$low_text="Low";
																$low_text=$objUser->getDefaultData('Impotrtance_Low_Title');
																$low_text=unserialize($low_text->text);

																$medium_text="Medium";
																$medium_text=$objUser->getDefaultData('Impotrtance_Medium_Title');
																$medium_text=unserialize($medium_text->text);

																$high_text="High";
																$high_text=$objUser->getDefaultData('Impotrtance_High_Title');
																$high_text=unserialize($high_text->text);

																$low_val=$objUser->getDefaultData('Impotrtance_Low_Value');
																$low_val=unserialize($low_val->text);
																if($low_val<0 || $low_val=="")
																	$low_val=0;

																$medium_val=$objUser->getDefaultData('Impotrtance_Medium_Value');
																$medium_val= unserialize($medium_val->text);
																if($medium_val<0 || $medium_val=="")
																	$medium_val=1;

																$high_val=$objUser->getDefaultData('Impotrtance_High_Value');
																$high_val=unserialize($high_val->text);
																
																if($high_val<0 || $high_val=="")
																	$high_val=2;

																$imp="slider-low";
																if($linedata[LINEITEM_IMPORTANCE]==$low_val)
																	$imp="slider-low";
																else if($linedata[LINEITEM_IMPORTANCE]==$medium_val)
																	$imp="slider-medium";
																else if($linedata[LINEITEM_IMPORTANCE]==$high_val)
																	$imp="slider-high";

															?>
															<div class="bar-outer"><div class="bar-inner"></div></div>
															<div class="<?php echo $imp; ?> rating_slide" id="rating_<?php echo $linedata[LINEITEM_ID];?>"></div>
															<input type="hidden" name="lineitem_importance" id="lineitem_importance_<?php echo $linedata[LINEITEM_ID];?>" value="<?php echo $linedata[LINEITEM_IMPORTANCE]; ?>" />
															<h6 class="text-low" id="text-low_<?php echo $linedata[LINEITEM_ID];?>" lineid="<?php echo $linedata[LINEITEM_ID];?>" rating="<?php echo $low_val ?>"><?php echo $low_text; ?></h6>
															<h6 class="text-medium" id="text-medium_<?php echo $linedata[LINEITEM_ID];?>" lineid="<?php echo $linedata[LINEITEM_ID];?>" rating="<?php echo $medium_val ?>"><?php echo $medium_text; ?></h6>
															<h6 class="text-high" id="text-high_<?php echo $linedata[LINEITEM_ID];?>" lineid="<?php echo $linedata[LINEITEM_ID];?>" rating="<?php echo $high_val ?>"><?php echo $high_text; ?></h6>
														</div>
													</div> 
													<?php //echo stripslashes($linedata[LINEITEM_NOTE]);?>
													
													<div class="sq-cols2">
														<textarea placeholder="<?php echo $objUser->GetLineItemDataFromLineId( $linedata[LINEITEM_ID], 'text' ); ?>" name="lineitem_note" id="lineitem_note_<?php echo $linedata[LINEITEM_ID];?>" style="width: 249px; height: 144px;"><?php echo htmlspecialchars(stripslashes($linedata[LINEITEM_NOTE]));?></textarea>
													</div>
													<div class="sq-cols3">
														<div class="listmain" id="row_<?php echo $linedata[LINEITEM_ID];?>">
															<div class="listraw clearfix row1"  id="row1_<?php echo $linedata[LINEITEM_ID];?>">
																<div class="lr-cols1">Calculate ROI?</div>
																<?php
																	$yescls="";
																	$nocls="";
																	
																	//if($linedata[LINEITEM_IS_AUTOMATED]==1)
																	if($linedata[LINEITEM_IS_BENEFIT]==1)
																	{
																	  $yescls="yes-deactived";
																	  $nocls="no-active";
																		
																	}
																	else /* if($linedata[LINEITEM_IS_BENEFIT]==2) */
																	{
																		$yescls="yes-active";
																		$nocls="no-deactived";
																	}
																?>
																<div class="lr-cols2">
																	<a class="<?php echo $yescls; ?> automated_yes" href="#" id="automated_yes_<?php echo $linedata[LINEITEM_ID];?>" lineid="<?php echo $linedata[LINEITEM_ID];?>"></a>
																</div>
																<div class="lr-cols3">
																	<a class="<?php echo $nocls; ?> automated_no" href="#" id="automated_no_<?php echo $linedata[LINEITEM_ID];?>" lineid="<?php echo $linedata[LINEITEM_ID];?>"  onClick="showHideAllROIBoxes('no', <?php echo $linedata[LINEITEM_ID];?>);"></a>
																</div>
															</div>
															 <?php
																$bycls="";
																$bncls="";
																if($linedata[LINEITEM_IS_BENEFIT]==1)
																{
																   $bycls="yes-active";
																   $bncls="no-deactived";
																}
																else if($linedata[LINEITEM_IS_BENEFIT]==2)
																{
																   $bycls="yes-deactived";
																   $bncls="no-active";
																}
															 ?>
															<div class="listraw clearfix rowno"  id="rowno_<?php echo $linedata[LINEITEM_ID];?>" <?php if($linedata[LINEITEM_IS_AUTOMATED]==0) { echo "style='display:block;'"; } ?>>
																<div class="lr-cols1">
																	Is this a benefit?
																</div>
																<div class="lr-cols2">
	<a class="benefit_yes yes-static <?php echo $bycls;?>" href="#" id="benefit_yes_<?php echo $linedata[LINEITEM_ID];?>" lineid="<?php echo $linedata[LINEITEM_ID];?>"></a>
																</div>
																<div class="lr-cols3">
																	<a class="benefit_no no-static <?php echo $bncls;?>" href="#" id="benefit_no_<?php echo $linedata[LINEITEM_ID];?>" lineid="<?php echo $linedata[LINEITEM_ID];?>"  onClick="showHideAllBeneBoxes('no', <?php echo $linedata[LINEITEM_ID];?>);"></a>
																</div>
															</div>
															<div class="listraw clearfix rowno-data"  id="rowno-data_<?php echo $linedata[LINEITEM_ID];?>" <?php if($linedata[LINEITEM_IS_BENEFIT]==1){ echo "style='display:block;'"; }?>>
																<?php
																	$ben_data=array();
																	if($linedata[LINEITEM_BENEFIT_FOR]!="")
																	{
																		$ben_data=explode(",",$linedata[LINEITEM_BENEFIT_FOR]);
																	}
																?>
																
											<?php 
											$rlist = $objUser->selectStakeholders($userid); 
											
												$list = explode(',',$rlist['fieldvalue']); 
												
												foreach($list as $key=>$val){?>
												<div class="lr-cols4 float-left">
													<label class="float-left"><?php echo $val; ?></label>
													<a id="benefit_id_<?php echo $linedata[LINEITEM_ID];?>" class="benefit_data radiobtn float-right<?php if(in_array("$val",$ben_data)){?> radio-active<?php }?>" rel="<?php echo $val; ?>" href="#" lineid="<?php echo $linedata[LINEITEM_ID];?>"></a>
												</div>
												<?}
											?>

																<div class="lr-cols4 float-left">
																	<label class="float-left">Other</label>
																	<a class="benefit_data radiobtn float-right<?php if(in_array("Other",$ben_data)){?> radio-active<?php }?>" href="#" rel="Other" id="other_<?php echo $linedata[LINEITEM_ID]; ?>" lineid="<?php echo $linedata[LINEITEM_ID];?>"></a>
																</div>
																<div class="lr-cols4 float-right other_data" id="other_data_<?php echo $linedata[LINEITEM_ID]; ?>" <?php  if(in_array("Other",$ben_data)){ echo "style='display:block;'"; } ?>>
																	<input type="text" name="lineitem_benefit_other" id="lineitem_benefit_other_<?php echo $linedata[LINEITEM_ID]; ?>" class="width4" placeholder="enter other" value="<?php echo $linedata[LINEITEM_BENEFIT_OTHER]; ?>"  lineid="<?php echo $linedata[LINEITEM_ID];?>" />
																</div>
															</div>
															<?php
																$roi_que1_text="How often do you do it?";
																$roi_que1=$objUser->getDefaultData('ROI_Question_1');
																$roi_que1_text=unserialize($roi_que1->text);
															?>
															<div class="listraw clearfix<?php if($linedata[LINEITEM_IS_AUTOMATED]==1) { ?> row2<?php } ?>"  id="row2_<?php echo $linedata[LINEITEM_ID];?>"  <?php if($linedata[LINEITEM_IS_AUTOMATED]==0) { echo "style='display:none;'"; } ?>>
																<div class="lr-cols1"><?php echo $roi_que1_text; ?><small>Manual action conducted / year</small></div>
																<div class="lr-cols2"><input type="text" class="lr-textbox" name="lineitem_year" id="lineitem_year_<?php echo $linedata[LINEITEM_ID]; ?>" value="<?php echo $linedata[LINEITEM_YEAR];?>" onKeyup="intOnly(this);" onKeydown="intOnly(this);" onKeypress="intOnly(this);" /></div>
																<div class="lr-cols3">/year</div>
															</div>
															<?php
																$roi_que2_text="Investment required?";
																$roi_que2=$objUser->getDefaultData('ROI_Question_2');
																$roi_que2_text=unserialize($roi_que2->text);
															?>
															<div class="listraw clearfix<?php if($linedata[LINEITEM_IS_AUTOMATED]==1) { ?> row3<?php } ?>"  id="row3_<?php echo $linedata[LINEITEM_ID];?>"  <?php if($linedata[LINEITEM_IS_AUTOMATED]==0) { echo "style='display:none;'"; } ?>>
																<div class="lr-cols1"><?php echo $roi_que2_text; ?></div>
																<?php
																	$invest_yescls="yes-active";
																	$invest_nocls="no-static";
																	if($linedata[LINEITEM_INVESTMENT_REQ]==1)
																	{
																		$invest_yescls="yes-active";
																		$invest_nocls="no-deactived";
																	}
																	else if($linedata[LINEITEM_INVESTMENT_REQ]==0)
																	{
																		$invest_yescls="yes-deactived";
																		$invest_nocls="no-active";
																	}
																?>
																<div class="lr-cols2"><a class="<?php echo $invest_yescls; ?> invsetment_yes" id="invsetment_yes_<?php echo $linedata[LINEITEM_ID];?>" href="#" lineid="<?php echo $linedata[LINEITEM_ID];?>" onClick="showHideInvestment('yes', <?php echo $linedata[LINEITEM_ID];?>);"></a></div>
																<div class="lr-cols3"><a class="<?php echo $invest_nocls; ?> invsetment_no" id="invsetment_no_<?php echo $linedata[LINEITEM_ID];?>" href="#" lineid="<?php echo $linedata[LINEITEM_ID];?>" onClick="showHideInvestment('no', <?php echo $linedata[LINEITEM_ID];?>);"></a></div>
															</div>
															<?php

																$cost_readonly="";
																$time_readonly="";
																if($linedata[LINEITEM_COST]!="" && $linedata[LINEITEM_TIME]=="")
																{
																	$cost_readonly="";
																	$time_readonly="readonly='readonly'";
																}
																else if($linedata[LINEITEM_COST]=="" && $linedata[LINEITEM_TIME]!="")
																{
																	$cost_readonly="readonly='readonly'";
																	$time_readonly="";
																}

?>
		<?php //if($linedata[LINEITEM_INVESTMENT_REQ]==0 || $linedata[LINEITEM_IS_AUTOMATED]==0) { echo "style='display:none;'"; }
		
		?>													
	<div class="listraw-yellow clearfix<?php if($linedata[LINEITEM_INVESTMENT_REQ]!=1 || $linedata[LINEITEM_IS_AUTOMATED]==1) { ?> row4<?php } ?>"  id="row4_<?php echo $linedata[LINEITEM_ID];?>" >
																<div class="lr-cols1">Cost in <br>$'s<small>(per action)x</small></div>
																<div class="lr-cols2"><input type="text" placeholder="$" value="<?php echo $linedata[LINEITEM_COST]; ?>" class="lr-textbox cost_input" name="lineitem_cost" onKeyup="intOnly(this);" onKeydown="intOnly(this);" onKeypress="intOnly(this);" id="lineitem_cost_<?php echo $linedata[LINEITEM_ID]; ?>"     lineid="<?php echo $linedata[LINEITEM_ID];?>" <?php echo $cost_readonly;?> /></div>
																
	<div class="lr-cols3"><a class="and-active and" id="and_<?php echo $linedata[LINEITEM_ID];?>" href="#" lineid="<?php echo $linedata[LINEITEM_ID];?>"></a></div>
																<div class="lr-cols4">Time<small>(per action)</small></div>
																<div class="lr-cols5">
																
																
																
<input type="text" placeholder="hrs" value="<?php echo $linedata[LINEITEM_TIME]; ?>" class="lr-textbox time_input" name="lineitem_time" id="lineitem_time_<?php echo $linedata[LINEITEM_ID]; ?>" onKeyup="intOnly(this);" onKeydown="intOnly(this);" onKeypress="intOnly(this);"  lineid="<?php echo $linedata[LINEITEM_ID];?>"  <?php echo $time_readonly;?>/></div>
															</div>
															<div id="lineitem_error_<?php echo $linedata[LINEITEM_ID]; ?>" class="error"></div>
															<div id="row5_<?php echo $linedata[LINEITEM_ID];?>">Estimated yearly saving:
											<div class="inline-block" id="<?php  echo "eoival_".$linedata[LINEITEM_ID]; ?>">
											<?php
											
											//echo $linedata[LINEITEM_EOI];
											$eoi = $linedata[18] == ''? '_': $linedata[18]; ?> 
											$<input type="text" border="0" style="border-width:0px !important; box-shadow: none !important; border:none; width: 65px !important;" value="<?php echo $eoi; ?>" class="lr-textbox eoi_input" name="lineitem_eoi" id="lineitem_eoi_<?php echo $linedata[LINEITEM_ID]; ?>"  lineid="<?php echo $linedata[LINEITEM_ID];?>"  readonly='readonly'/>
										</div></div>   
														</div>
													</div>
												</div>
												<div class="sq-block">
													<div class="note-buttonsection">
														<div class="bb-block">
															<div class="bb-cols bb-blue">
																<a href="#" class="btn btn-blue btn-mini save_lineitem" id="save_lineitem_<?php echo $linedata[LINEITEM_ID]; ?>" lineid="<?php echo $linedata[LINEITEM_ID]; ?>" catid="<?php echo $_REQUEST["catid"]; ?>">
																	<span class="btn-inner">Save this item</span>
																</a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</form>
							<?php
								}
								}
							?>
						</div>
						<!-- End: Subquestion Section -->
					</div>
				</div>

				<!-- Start: Note Section --> 
				<div class="note-section"> 
						<div class="note-heading">
							<a href="javascript: void(0);" onClick="openNotes();">Notes about <I><?php echo $catdata->name; ?></I> :</a>
						</div>
						<form id="frmnote" method="post">
							<div class="note-content">
								<?php
									$category_data=$objUser->GetCategoryFromToolData($userid,$_REQUEST["catid"]);
								?>
								<textarea placeholder="<Enter notes about <?php echo $catdata->name; ?>>" class="nc-textarea" id="cat_note_<?php echo $_REQUEST["catid"]; ?>" name="cat_note" ><?php echo $category_data[CATEGORY_NOTE]; ?></textarea>
								<!-- Start: Note Button Section -->
								<div class="note-buttonsection">
									<div class="bb-block">
										<div class="bb-cols bb-blue">
											<a class="btn btn-blue btn-mini" href="javascript: void(0);" id="save_note" onclick="savePopup();"catid="<?php echo $_REQUEST["catid"]; ?>"><span class="btn-inner">Save Notes</span></a>
										</div>
										<div class="bb-cols bb-purple">
											<a class="btn btn-purple btn-mini" href="javascript: void(0);" id="cancel_note" onclick="cancelPopup();"><span class="btn-inner">Cancel</span></a>
										</div>
									</div>
								</div>
								<!-- End: Note Button Section -->

							</div>
						</form>
					</div>
				<!-- End: Note Section -->

				<!-- Start: btm-button -->
				<div class="btm-button">
					<div class="bottom-btn">
							<div class="bb-block">
								<div class="bb-cols bb-redlight">
									<a class="btn btn-redlight" href="#" id="add_custom_lineitem" catid="<?php echo $_REQUEST["catid"]; ?>"><span class="btn-inner"><strong>Add</strong> Feature</span></a>
								</div>
								<div class="bb-cols bb-blue">
									<a class="btn btn-blue" href="#" id="save_progress_popup"><span class="btn-inner"><strong>Save</strong> Progress</span></a>
								</div>
								<div class="bb-cols bb-green">
									<a class="btn btn-green" href="#" id="complete_category_popup"><span class="btn-inner"><strong>Functional Area</strong> Complete</span></a>
								</div>
							</div>
						</div>
				</div>
				<!-- End: btm-button -->
<?php
			}
		}
	}
?>
