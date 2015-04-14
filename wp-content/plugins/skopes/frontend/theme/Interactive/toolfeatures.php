<?php
/*
*   Name: Twisha Patel  Date: 19-8-2013
*
*   Tool funtionality page
*/
$objUser = new clsSkopes(); 

/* if project status is not set then set to 0 */
if(!get_user_meta($loggedin_id,'project_status',true))
	update_user_meta($loggedin_id,'project_status',0);

/* set start date and time for the project */
if(!get_user_meta($loggedin_id,'start_dt_time',true))
	update_user_meta($loggedin_id,'start_dt_time',date('Y-m-d H:i:s'));

/* check tooldata is generated for user or not if not then add default catgeory and its line item and generate it*/
if(!get_user_meta($loggedin_id,'tool_data',true)) {
  	$objUser->SaveDefaultToolData($loggedin_id);
}


if(isset($_REQUEST["finish"]) && $_REQUEST["finish"]=="yes" ) { ?>
		<input type="hidden" name="finish" id="finish" value="yes">
<?php 	}	?>
<div class="container">
	<!-- Start: Head Section -->
	<div class="head-section clearfix">
		<span class="heading4">Functional Area</span>
		<span class="symbol">&gt;</span>
		<span class="heading2" id="catname">Continuing</span>
	</div>
	<!-- End: Head Section -->

	<!-- Start: Content Section -->
	<div class="content-section clearfix">

		<!-- Start: Indicator Section -->
		<div class="indicator-section">
		<!--span class="test"></span-->
			<!-- Start: Hoverblock -->
			<span class="hoverblock">
				<span class="top-left"></span>
				<span class="top-disable"></span>
				<span class="top-right"></span>
				<span class="right"></span>
				<span class="bottom-right"></span>
				<span class="bottom"></span>
				<span class="bottom-left"></span>
				<span class="left-disable"></span>
			</span>
			<!-- End: Hoverblock -->

			<!-- Start: Block Section -->
			<div class="block-section default_category">
				<?php
					$catres = $objUser->GetToolData($loggedin_id);
					/* echo '<pre>';
					print_r($catres);
					echo '</pre>'; */ 
					if($catres)
					{
						$k=0;
						for($i=0;$i<sizeof($catres);$i++)
						{
							if($catres[$i][CATEGORY_IS_CUSTOM]==0)
							{
							/*  echo "<br>cat id - ".$catres[$i][CATEGORY_ID]."status - ".$catres[$i][CATEGORY_STATUS]; */
							 
								$k++;
								if($catres[$i][CATEGORY_STATUS]==0)
									$cls="block-incomplete";
								else if($catres[$i][CATEGORY_STATUS]==1)
									$cls="block-complete";
								else if($catres[$i][CATEGORY_STATUS]==2)
									$cls="block-notrequired";
								else if($catres[$i][CATEGORY_STATUS]==3)
									$cls="block-inscope";

							$status_cnt=""; 
							$not_cnt="";
							$incomplete_cnt="";
							$lineitemcnt=0;
							if($catres[$i][CATEGORY_LINEITEMS])
							{
								foreach($catres[$i][CATEGORY_LINEITEMS] as $lineitem)
								{
									if($lineitem[LINEITEM_STATUS]==1)
										$status_cnt++;
									if($lineitem[LINEITEM_STATUS]==2)
										$not_cnt++;
									if($lineitem[LINEITEM_STATUS]==0)
										$incomplete_cnt++;
									$lineitemcnt++;
								}
							}
							
							// echo "<br>cat id - ".$catres[$i][CATEGORY_ID]."lineid - ".$lineitem[LINEITEM_ID]."linestatus - ".$lineitem[LINEITEM_STATUS]; 
							$display="";
							
							if($incomplete_cnt==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==0)
								$display="?";
							else if($incomplete_cnt==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==3)
								$display="?";
							else if(($incomplete_cnt+$not_cnt)==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==0)
								$display="0";
							else if(($incomplete_cnt+$not_cnt)==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==3)
								$display="0";
							else if($not_cnt==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==1)
								$display="0";
							else if($not_cnt==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==0)
								$display="0";
							else if($not_cnt==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==2)
								$display="0";
							else if($not_cnt==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==3)
								$display="0";
							else if($status_cnt=="" && $catres[$i][CATEGORY_STATUS]==2)
								$display="-";
							else
								$display=$status_cnt;


				?>
							<div index="<?php echo $k; ?>" id="cat<?php echo $k; ?>" class="block <?php echo $cls ?>" catid="<?php echo $catres[$i][CATEGORY_ID]; ?>" catname="<?php echo $objUser->GetCategoryName($catres[$i][CATEGORY_ID]); ?>">
								<div class="block-inner">
									<a href="#">
										<span class="circle-small numtop" id="complete_cnt_<?php echo $catres[$i][CATEGORY_ID]; ?>"><?php echo $display; ?></span>
										<span class="circle-large nummid">of</span>
										<span class="circle-small numbtm" id="total_<?php echo $catres[$i][CATEGORY_ID]; ?>"><?php echo $lineitemcnt; ?></span>
									</a>
								</div>
							</div>
				<?php
							}
						}
					}
				?>
			</div>
			<!-- End: Block Section -->

			<!-- Start: Custom Category -->
			<div class="custom-category">
				<!-- Start: Add Section -->
				<a id="addcategory" class="addsection clearfix" href="#">
					<span class="hgroup clearfix">
						<span class="h2">Add</span>
						<span class="h5">Custom Functional Area?</span>
					</span>
					<span class="icon-box"><i class="icon-plus icon-light icon-large"></i></span>
				</a>
				<!-- End: Add Section -->

				<!-- Start: Block Section -->
				<div class="block-section custom_category">
				<?php
					//$catres=$objUser->GetToolData($loggedin_id);
					if($catres)
					{

						for($j=0;$j<sizeof($catres);$j++)
						{
							if($catres[$j][CATEGORY_IS_CUSTOM]===1)
							{
								$k++;
								if($catres[$j][CATEGORY_STATUS]==0)
									$cls="block-incomplete";
								else if($catres[$j][CATEGORY_STATUS]==1)
									$cls="block-complete";
								else if($catres[$j][CATEGORY_STATUS]==2)
									$cls="block-notrequired";
								else if($catres[$i][CATEGORY_STATUS]==3)
									$cls="block-inscope";

								$status_cnt="";
								$not_cnt="";
								$incomplete_cnt="";
								$lineitemcnt=0;
								if($catres[$j][CATEGORY_LINEITEMS])
								{
									foreach($catres[$j][CATEGORY_LINEITEMS] as $lineitem)
									{
										if($lineitem[LINEITEM_STATUS]==1)
											$status_cnt++;
										if($lineitem[LINEITEM_STATUS]==2)
											$not_cnt++;
										if($lineitem[LINEITEM_STATUS]==0)
											$incomplete_cnt++;

										$lineitemcnt++;
									}
								}
							$display="";
							
							if($incomplete_cnt==$lineitemcnt && $catres[$j][CATEGORY_STATUS]==0)
								$display="?";
							else if(($incomplete_cnt+$not_cnt)==$lineitemcnt && $catres[$j][CATEGORY_STATUS]==0)
								$display="0";
							else if($not_cnt==$lineitemcnt && $catres[$j][CATEGORY_STATUS]==1)
								$display="0";
							else if($not_cnt==$lineitemcnt && $catres[$j][CATEGORY_STATUS]==0)
								$display="0";
							else if($not_cnt==$lineitemcnt && $catres[$j][CATEGORY_STATUS]==2)
								$display="0";
							else if($status_cnt=="" && $catres[$j][CATEGORY_STATUS]==2)
								$display="-";
							else
								$display=$status_cnt;
				?>
									<div index="<?php echo $k; ?>" id="cat<?php echo $k; ?>" class="block <?php echo $cls ?>" catid="<?php echo $catres[$j][CATEGORY_ID]; ?>" catname="<?php echo $objUser->GetCategoryName($catres[$j][CATEGORY_ID]); ?>">
										<div class="block-inner">
											<a href="#">
												<span class="circle-small numtop" id="complete_cnt_<?php echo $catres[$j][CATEGORY_ID]; ?>"><?php echo $display; ?></span>
												<span class="circle-large nummid">of</span>
												<span class="circle-small numbtm" id="total_<?php echo $catres[$j][CATEGORY_ID]; ?>"><?php echo $lineitemcnt; ?></span>
											</a>
										</div>
									</div>
				<?php
							}
						}
					}
				?>
				</div>
				<!-- End: Block Section -->
			</div>
			<!-- End: Custom Category -->
		</div>
		<!-- End: Indicator Section -->
		<?php
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
		?>
		<!-- Start: Contentbox -->
		<div class="contentbox" id="catdesc">
		
			<!-- Start: Box -->
			<div class="box">

			</div>
			<!-- End: Box -->
			<!-- Start: Edit Items -->
			<?php
				// **************************************************************
				// Add file to check user paid for this feature or not 
				include("include/block-stage-one-pdf.php");
				$stage2DocsPdf = getRiskSectionStatus('stage2DocsPdf');
				$planMessage = 'This feature is a premium feature - please visit your <a href="'.home_url().'?act=myaccount">account</a> to add it to your package.';
			?>
			<?php if($stage == 'two'){?>
			<div class="edit-items">
				<div>
				<?php if ($stage2DocsPdf == 0) { ?>
					<a href="javascript:void(1)" class="btn" id="" onclick="showPlanMessageinPopup('<?php echo base64_encode($planMessage); ?>');"><strong>Edit</strong> Features</a>
				<?php } else { ?>
					<a href="javascript:void(1)" class="btn" id="edit_line_item"><strong>Edit</strong> Features</a>
				<?php } ?>
				</div>
				<div id="catStatusValue"></div>
				<div>
				<a href="javascript:void(1)" id="category-complete" class="btn btn-green"><strong>Functional Area</strong> Complete</a>
				</div>
			</div>
			<?php } ?>
			<!-- End: Edit Items -->
		</div>
		<!-- End: Contentbox -->
	</div>
	<!-- End: Content Section -->
<?php

	$FAData = $objUser->checkFunaStatusCustom($loggedin_id);

	$totalFA          = $FAData[1]['totalFA'];
	$totalInscope     = $FAData[2]['totalInscope'];
	$totalNotrequired = $FAData[3]['totalNotrequired'];
	$totalunknown     = $FAData[0]['unknown'];
	$totalComplete    = $FAData[4]['totalComplete'];

/*	echo "totalFA=".$totalFA;
	echo "totalInscope=".$totalInscope;
	echo "totalNotrequired=".$totalNotrequired;
	echo "totalunknown=".$totalunknown;
	echo "totalComplete=".$totalComplete;	
*/
	if ( $totalFA === $totalNotrequired+$totalComplete) {
		$class = "green-complete";
	} else {
		$class = "txt";
	}
?>

	<!--	<div class="submit-buttons <?=$class; ?>" style="text-align:center; margin-top: 8%;" id="">
			<span class="small btn-blue1" style="margin-right: 35px;">
				<a id="backToguide" class="saveFeatures" href="#">Save progress</a>
			</span>
			<span class="small btn-blue1">
				<a id="backToguideComplete" class="completeFeatures" href="#">Complete</a>
			</span>
		</div>-->

</div>
<?php 

if(isset($_REQUEST["edit"]))
{ 
	if($_REQUEST["edit"]!="")
	{
	?>
		<input type="hidden" name="editid" id="editid" value="<?php echo $objUser->k_decrypt($_REQUEST["edit"]); ?>">
		<!-- open the pop up in case of edit -->
		<script type="text/javascript">
			$(document).ready(function() {

				jQuery("#edit_line_item").on("click",function()
					{					
						//catid = jQuery(".block-section .active").attr("catid");
					
						catid =  '<?php echo $objUser->k_decrypt($_REQUEST["edit"]); ?>';
						catstatus =  '<?php echo trim($_REQUEST["status"]); ?>';
				        jQuery('#catStatusValue').html(catstatus);  
						if(jQuery("#custom_catname"))
						{
							jQuery("#custom_catname").trigger("focusout");
						}
						if(typeof(catid)!="undefined")
						{ 
							//if(jQuery(this).hasClass("deactivated")==true).
							if(catstatus == 'Not required')
							{   
								/*jQuery.ajax({
								   url:  path+"/savedata.php?action=IncludeCategory&catid="+escape(catid)+"&cat_status=0",
								   }).done(function(data) {
									 jQuery(".block-section .active").removeClass("block-notrequired");
									 jQuery(".block-section .active").removeClass("block-complete");
									 jQuery(".block-section .active").addClass("block-incomplete");
								});
                                jQuery(".overlay").fadeOut(500);*/
								   jQuery(".overlay").fadeOut(500);
								   jQuery("#big_popup").hide();
								   jQuery("#popup").fadeOut(500);
							}
							else if(catstatus == 'Unknown')
							{
							     jQuery(".overlay").fadeOut(500);
								   jQuery("#big_popup").hide();
								   jQuery("#popup").fadeOut(500);
							}
                            else {  
									jQuery(".overlay").fadeIn(500);
									jQuery("#small_popup").hide();
									jQuery("#big_popup").html("");
									jQuery("#big_popup").show();
									jQuery("#popup").fadeIn(500);
									jQuery(".popupbox").removeClass("smallpopup");

									jQuery("#big_popup").load(path+"/lineitems.php?type=EditLineItems&catid="+escape(catid),function(data){
									if(jQuery(".lineitemfrm").length==0)
									{
										custom_flag=0;
										jQuery("#add_custom_lineitem").trigger("click");
									}
									else
										jQuery("#lineitem_scroll").mCustomScrollbar();
								});
							}	
					}
				})
			  
			  $('#edit_line_item').trigger('click');
		});
		
		</script>
	<?php
	}
}
?>
