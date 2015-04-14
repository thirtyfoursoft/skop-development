<?php
	include("../../../../wp-config.php");

	$objUser = new clsSkopes();
	$userid=get_current_user_id();

	$catid=$_REQUEST["catid"];
	$lineid=$_REQUEST["lineid"];
?>
<form id="frmlineitem_<?php echo $lineid; ?>" method="post" class="lineitemfrm">
	<input type="hidden" name="lineitem_id" id="lineitem_id_<?php echo $lineid;?>" value="<?php echo $lineid;?>" />
	<input type="hidden" name="lineitem_status" id="lineitem_status_<?php echo $lineid;?>" />
	<input type="hidden" name="lineitem_investment" id="lineitem_investment_<?php echo $lineid;?>" value="1" />
	<input type="hidden" name="lineitem_automated" id="lineitem_automated_<?php echo $lineid;?>" value="1" />
	<input type="hidden" name="lineitem_benefit" id="lineitem_benefit_<?php echo $lineid ;?>" value="0" />
	<input type="hidden" name="lineitem_benefit_for" id="lineitem_benefit_for_<?php echo $lineid; ?>" value="" />
	<div class="sq-rawmain" lineid="<?php echo $lineid;?>" id="lineitem_<?php echo $lineid;?>">
		<div class="sq-raw">
			<div class="sq-head clearfix">
				<input type="text" name="name" id="name_<?php echo $lineid; ?>" value="" placeholder="<Enter name for custom line item>" class="sq-heading lineitemname"  lineid="<?php echo $lineid ?>" catid="<?php echo $catid; ?>"/>
				<div class="confirm-button clearfix">
					<a href="javascript:void(1)" class="button-yes lineitem-yes" id="button_yes_<?php echo $lineid; ?>" lineid="<?php echo $lineid; ?>" catid="<?php echo $catid; ?>">Yes</a>
					<a href="javascript:void(1)" class="button-no lineitem-no"  id="button_no_<?php echo $lineid; ?>" lineid="<?php echo $lineid; ?>" catid="<?php echo $catid; ?>">No</a>
				</div>
			</div>
		</div>
		<div class="sq-editsection"  id="edit_delete_<?php echo $lineid; ?>">
			<a href="javascript:void(1)" lineid="<?php echo $lineid;?>" class="btn-edit editlineitem" style="display:none;">Edit</a>
			<a href="javascript:void(1)" lineid="<?php echo $lineid;?>" catid="<?php echo $catid;?>" class="btn-delete deletelineitem" class="btn-delete">Delete</a>
		</div>
		<div class="sq-body" id="lineitem_detail_<?php echo $lineid; ?>">
			<div class="sq-block clearfix">
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
					$medium_val=unserialize($medium_val->text);
					if($medium_val<0 || $medium_val=="")
						$medium_val=1;

					$high_val=$objUser->getDefaultData('Impotrtance_High_Value');
					$high_val=unserialize($high_val->text);
					if($high_val<0 || $high_val=="")
						$high_val=2;


				?>
				<div class="sq-cols1">
					<p class="rating-head">Importance</p>
					<div class="rating-section">
						<div class="bar-outer"><div class="bar-inner"></div></div>
						<div class="slider-low rating_slide" id="rating_<?php echo $lineid;?>"></div>
						<input type="hidden" name="lineitem_importance" id="lineitem_importance_<?php echo $lineid;?>" value="<?php echo $low_val ?>" />
						<h6 class="text-low" lineid="<?php echo $lineid;?>" rating="<?php echo $low_val ?>"><?php echo $low_text; ?></h6>
						<h6 class="text-medium" lineid="<?php echo $lineid;?>" rating="<?php echo $medium_val ?>"><?php echo $medium_text; ?></h6>
						<h6 class="text-high" lineid="<?php echo $lineid;?>" rating="<?php echo $high_val ?>"><?php echo $high_text; ?></h6>
					</div>
				</div>
				<div class="sq-cols2">
					<textarea placeholder="Details and Notes" name="lineitem_note" id="lineitem_note_<?php echo $lineid;?>"></textarea>
				</div>
				<div class="sq-cols3">
					<div class="listmain" id="row_<?php echo $lineid;?>">
						<div class="listraw clearfix row1"  id="row1_<?php echo $lineid;?>">
							<div class="lr-cols1">
								Calculate ROI<!--small>('No' indicates done manually)</small-->
							</div>
							<div class="lr-cols2">
								<a class="automated_yes yes-active" href="#" id="automated_yes_<?php echo $lineid;?>" lineid="<?php echo $lineid;?>"></a>
							</div>
							<div class="lr-cols3">
								<a class="automated_no no-static" href="#" id="automated_no_<?php echo $lineid;?>" lineid="<?php echo $lineid;?>"></a>
							</div>
						</div>

						<div class="listraw clearfix rowno"  id="rowno_<?php echo $lineid;?>">
							<div class="lr-cols1">
								Is this a benefit?<!--small>('No' indicates done manually)</small-->
							</div>
							<div class="lr-cols2">
								<a class="benefit_yes yes-static <?php echo $bycls;?>" href="#" id="benefit_yes_<?php echo $lineid;?>" lineid="<?php echo $lineid;?>"></a>
							</div>
							<div class="lr-cols3">
								<a class="benefit_no no-static <?php echo $bncls;?>" href="#" id="benefit_no_<?php echo $lineid;?>" lineid="<?php echo $lineid;?>"></a>
							</div>
						</div>
						<div class="listraw clearfix rowno-data"  id="rowno-data_<?php echo $lineid;?>" <?php if($linedata[LINEITEM_IS_benefit]==1){ echo "style='display:block;'"; }?>>
							<div class="lr-cols4 float-left">
								<label class="float-left">Customer</label>
								<a class="benefit_data radiobtn float-right" rel="Customer" href="#" lineid="<?php echo $lineid;?>"></a>
							</div>
							<div class="lr-cols4 float-right">
								<label class="float-left">Employees</label>
								<a class="benefit_data radiobtn float-right" rel="Employees" href="#" lineid="<?php echo $lineid;?>"></a>
							</div>
							<div class="lr-cols4 float-left">
								<label class="float-left">Shareholders</label>
								<a class="benefit_data radiobtn float-right" rel="Shareholders" href="#" lineid="<?php echo $lineid;?>"></a>
							</div>
							<div class="lr-cols4 float-right">
								<label class="float-left">Stakeholders</label>
								<a class="benefit_data radiobtn float-right" rel="Stakeholders" href="#" lineid="<?php echo $lineid;?>"></a>
							</div>
							<div class="lr-cols4 float-left">
								<label class="float-left">Other</label>
								<a class="benefit_data radiobtn float-right" href="#" rel="Other" id="other_<?php echo $lineid; ?>" lineid="<?php echo $lineid;?>"></a>
							</div>
							<div class="lr-cols4 float-right other_data" id="other_data_<?php echo $lineid; ?>">
								<input type="text" name="lineitem_benefit_other" id="lineitem_benefit_other_<?php echo $lineid; ?>" class="width4" placeholder="enter other" value=""  lineid="<?php echo $lineid;?>" />
							</div>
						</div>
						<?php
							$roi_que1_text="How often do you do it?";
							$roi_que1=$objUser->getDefaultData('ROI_Question_1');
							$roi_que1_text=unserialize($roi_que1->text);
						?>
						<div class="listraw clearfix row2" id="row2_<?php echo $lineid;?>">
							<div class="lr-cols1"><?php echo $roi_que1_text; ?><small>Manual action conducted / year</small></div>
							<div class="lr-cols2"><input type="text" class="lr-textbox" name="lineitem_year" id="lineitem_year_<?php echo $lineid; ?>" value="" /></div>
							<div class="lr-cols3">/year</div>
						</div>
						<?php
							$roi_que2_text="Investment required?";
							$roi_que2=$objUser->getDefaultData('ROI_Question_2');
							$roi_que2_text=unserialize($roi_que2->text);
						?>
						<div class="listraw clearfix row3" id="row3_<?php echo $lineid;?>">
							<div class="lr-cols1"><?php echo $roi_que2_text; ?></div>
							<div class="lr-cols2"><a class="yes-active invsetment_yes" id="invsetment_yes_<?php echo $lineid;?>" href="#" lineid="<?php echo $lineid;?>"></a></div>
							<div class="lr-cols3"><a class="no-static invsetment_no" id="invsetment_no_<?php echo $lineid;?>" href="#" lineid="<?php echo $lineid;?>"></a></div>
						</div>
						<div class="listraw-yellow clearfix row4"  id="row4_<?php echo $lineid;?>">
							<div class="lr-cols1">Cost<small>(per action)</small></div>
							<div class="lr-cols2"><input type="text" placeholder="$" value="" class="lr-textbox cost_input" name="lineitem_cost" id="lineitem_cost_<?php echo $lineid; ?>"/></div>
							<div class="lr-cols3"><a class="and-active and" id="and_<?php echo $lineid;?>" href="#" lineid="<?php echo $lineid;?>"></a></div>
							<div class="lr-cols4">Time<small>(per action)</small></div>
							<div class="lr-cols5"><input type="text" placeholder="hrs" value="" class="lr-textbox time_input" name="lineitem_time" id="lineitem_time_<?php echo $lineid; ?>" /></div>
						</div>
						<div id="lineitem_error_<?php echo $lineid; ?>" class="error"></div>
					</div>
				</div>
			</div>
			<div class="sq-block">
				<div class="note-buttonsection">
					<div class="bb-block">
						<div class="bb-cols bb-blue">
							<a href="#" class="btn btn-blue btn-mini save_lineitem" id="save_lineitem_<?php echo $lineid; ?>" lineid="<?php echo $lineid; ?>" catid="<?php echo $catid; ?>">
								<span class="btn-inner">Save this item</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>