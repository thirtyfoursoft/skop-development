<?php
	include(RC_TC_BASE_DIR."/frontend/core/class_userinfo.php");
	$obj = new userinfo();
?>
<?php
/* check tooldata is generated for user or not if not then add default catgeory and its line item and generate it*/
if(!get_user_meta($loggedin_id,'tool_data',true))

//$objUser->SaveDefaultToolData($loggedin_id);

$loggedin_id = get_current_user_id();
$catres = $objUser->GetToolData($loggedin_id); 
 
//echo "<pre>";	print_r($catres);	echo "</pre>";
if($catres)
{
	$in_skoop = 0;
	$complete_cnt=0;
	$incomplete_cnt=0;
	$not_cnt=0;
	$totallineitemcnt=0;
	$complete_line_cnt=0;
	$roi_line=0;
	$highcnt=0;
	$mediumcnt=0;
	$lowcnt=0;
	$niceToHaveCnt=0;
	for($i=0;$i<sizeof($catres);$i++)
	{
	   //echo $catres[$i][CATEGORY_STATUS];	 
	   //echo '<br>';
		if( $catres[$i][CATEGORY_STATUS]==0 )
			$incomplete_cnt++;
		else if( $catres[$i][CATEGORY_STATUS]==1 )
		{ $complete_cnt++; }
			//$complete_cnt++;
		else if( $catres[$i][CATEGORY_STATUS]==2 ) {
			$not_cnt++; 
		}
        else if( $catres[$i][CATEGORY_STATUS]==3 ) {
            //$complete_cnt++;
			$in_skoop++;
		} 		

		$totallineitemcnt = $totallineitemcnt+count($catres[$i][CATEGORY_LINEITEMS]);
 
		if($catres[$i][CATEGORY_LINEITEMS])
		{

			foreach($catres[$i][CATEGORY_LINEITEMS] as $lineitem)
			{
				$how_often=$lineitem[LINEITEM_YEAR];
				$cost=$lineitem[LINEITEM_COST];
				$time=$lineitem[LINEITEM_TIME];
				//$hrate=get_usermeta($loggedin_id,"hourly_rate",true);
				//$hrate = 44;
				$hrate=get_usermeta($loggedin_id,"org_ques_9_state",true);
				$roi_line=$roi_line+$objUser->ROICalculation($hrate,$time,$cost,$how_often);
				//echo $hrate." - > ".$time." - > ".$cost." - > ".$how_often.'<br>';
				if($lineitem[LINEITEM_STATUS]==1)
				{
					$complete_line_cnt++;
				}

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

				//echo "<br>".$high_val." -- ".$lineitem[LINEITEM_IMPORTANCE];
				
				if($lineitem[LINEITEM_IMPORTANCE]==$high_val)
					$highcnt++;
				else if($lineitem[LINEITEM_IMPORTANCE]==$medium_val)
					$mediumcnt++;
				else if($lineitem[LINEITEM_STATUS]==1 and $lineitem[LINEITEM_IMPORTANCE]==$low_val)
					 $niceToHaveCnt++;
				else
					$lowcnt++;
			}
		}
	} 
	  
	$not_data = $not_cnt*100/sizeof($catres);
	$complete_data = $complete_cnt*100/sizeof($catres);
	$incomplete_data = $incomplete_cnt*100/sizeof($catres);

	$total_complete_data = ($complete_cnt*100/sizeof($catres))+$not_data;
	$total_data=$total_complete_data+$incomplete_data;

/*
echo "black =".$not_data."<br/>";
echo "green =".$complete_data."<br/>";
echo "blue =".$incomplete_data."<br/>";
*/
}
if(isset($_GET["type"])) { ?>
	<input type="hidden" name="popup_msg" id="popup_msg"  value="<?php if($_GET["type"]=="feedback"){ echo "feedback"; } ?>" />
<?php  }  ?>
<div class="container">
	<!-- Start: Content Section -->
	<div class="content-section darkbox clearfix">
		<div class="box_white marbtm30">
			<h2>Project Dashboard and Progress</h2>
			<p>
				<?php
					$project_progress=$objUser->getDefaultData('Project_Dashboard_and_Progress');
					echo $project_progress = stripslashes(unserialize($project_progress->text));
				?>
			</p>
			<!-- Start:progressbar_section -->
			<div class="progressbar_section clearfix">
				<div class="progressbar">
					<div class="bar_normal">
						<?php
							$fnt="";
							if(sprintf("%.1f",$complete_data)<5)
								$fnt="font-size:7px;";
							else if(sprintf("%.1f",$complete_data)>=5 && sprintf("%.1f",$complete_data)<15)
								$fnt="font-size:10px;";
							

							$nleft= -43+$not_data;

							if($nleft<0)
								$n_setleft="left:".sprintf("%.1f",$nleft)."px";

							$cleft=-43+$total_complete_data;
							if($cleft<0)
								$c_setleft="left:".sprintf("%.1f",$cleft)."px";
						?>
						<span class="bar_gray1" style="width:<?php echo sprintf("%.1f",$not_data);?>%;<?php echo $n_setleft; ?>;opacity: 0.7;"><!--span class="bar_txt"><?php echo sprintf("%.1f",$not_data);?></span--></span>
						<?php
							if($total_complete_data > 0 ) {
						?>

						<?php } ?>
						<?php if($complete_data > 0 ) { ?><span class="bar_current1" style="width:<?php echo sprintf("%.1f",$complete_data);?>%;<?php echo $fnt; ?>;<?php echo $c_setleft; ?>"><span class="bar_txt"><?php if($complete_data>0){ echo sprintf("%.1f",$complete_data)."%"; }?></span></span><?php } ?>
						
					</div>
					<div class="legend-data">
						<!--<div class="legend-meaning"><span class="bar-incomplete"></span>&nbsp;&nbsp;Not yet specified Functional Area &nbsp; <?php if($incomplete_data>0){ echo "<b>".sprintf("%.1f",$incomplete_data)."%</b>"; } ?></div>-->
						<div class="legend-meaning"><span class="bar-incomplete"></span>&nbsp;&nbsp;Not yet specified Functional Area &nbsp; <?php echo "<b>".sprintf("%.1f",$incomplete_data)."%</b>"; ?></div>
						<div class="legend-meaning"><span class="bar-complete"></span>&nbsp;&nbsp;Fully specified Functional Area &nbsp; <?php echo "<b>".sprintf("%.1f",$complete_data)."%</b>";  ?></div>
						<div class="legend-meaning"><span class="bar-notrequired"></span>&nbsp;&nbsp;Not Required Functional Area &nbsp; <?php  echo "<b>".sprintf("%.1f",$not_data)."%</b>"; ?></div>
					</div>
				</div>
				<div class="progress_right">
					<div class="pr_raw">
						<span class="num_circle"><?php echo $in_skoop;  //echo $complete_cnt; ?></span>
						<span class="num_txt">
							Functional Area of <?php echo sizeof($catres); ?> included in scope
						</span>
					</div>
					<div class="pr_raw">
						<span class="num_circle"><?php echo $complete_line_cnt; ?></span>
						<span class="num_txt">
							Features  of <?php echo $totallineitemcnt; ?> included in scope
							<br><small><?php if($highcnt > 0) echo $highcnt; else echo ' 0 '; ?> mandatory, <?php if($mediumcnt > 0) echo $mediumcnt; else echo ' 0 '; ?> important, <?php if($niceToHaveCnt > 0) echo $niceToHaveCnt; else echo ' 0 '; ?> nice to have</small>
						</span>
					</div>
					<div class="pr_raw">
						<span class="num_circle">$</span>
						<span class="num_txt">
							<?php echo $roi_line; ?> Estimated savings per year
						</span>
					</div>
				</div>
			</div>
			<!-- End:progressbar_section -->
		</div>
		<div class="cols2 clearfix">
			<div class="cols_left">
				<div class="wbox listbox">
					<div class="headbg">
						<span></span>
						<span>Title</span>
						<span>Status</span>
						<span>ROI</span>
					</div>
					<?php
						if($catres)
						{
							for($i=0;$i<sizeof($catres);$i++)
							{

								$k++;
								if($catres[$i][CATEGORY_STATUS]==0)
								{
									$cls="block-incomplete";
									//$status="Unknown";
									$status="Not Yet Specified";
								}
								else if($catres[$i][CATEGORY_STATUS]==1)
								{
									$cls="block-complete";
									//$status="Complete";
									$status="Fully Specified";
								}
								else if($catres[$i][CATEGORY_STATUS]==2)
								{
									$cls="block-notrequired";
									$status="Not Required";
								}
								else if($catres[$i][CATEGORY_STATUS]==3)
								{
									$cls="block-inscope";
									$status="In Scope";
								}

								$status_cnt="";
								$not_cnt="";
								$incomplete_cnt="";
								$lineitemcnt=0;
								if($catres[$i][CATEGORY_LINEITEMS])
								{
									$roi_line=0;
									foreach($catres[$i][CATEGORY_LINEITEMS] as $lineitem)
									{
										$how_often=$lineitem[LINEITEM_YEAR];
										$cost=$lineitem[LINEITEM_COST];
										$time=$lineitem[LINEITEM_TIME];
										//$hrate=get_usermeta($loggedin_id,"hourly_rate",true); 
										$hrate=get_usermeta($loggedin_id,"org_ques_9_state",true); 
										$roi_line=$roi_line+$objUser->ROICalculation($hrate,$time,$cost,$how_often);

										 if($lineitem[LINEITEM_STATUS]==1)
											$status_cnt++;
										if($lineitem[LINEITEM_STATUS]==2)
											$not_cnt++;
										if($lineitem[LINEITEM_STATUS]==0)
											$incomplete_cnt++;
										$lineitemcnt++;

									}
								}
								
								// if category is not required the ROI will display $0/YR
								
								
								$display="";
								if($incomplete_cnt==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==0)
									$display="?";
								if($incomplete_cnt==$lineitemcnt && $catres[$i][CATEGORY_STATUS]==3)
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
									
									
								$category_id = $catres[$i][CATEGORY_ID];
								
								
								 
								if($catres[$i][CATEGORY_STATUS] == 0)/** if Functional area is incomplete**/
								{
									$incompleteFA[$category_id] = array('display' =>$display,'lineitemcnt'=>$lineitemcnt,'status'=>$status,'roi_line'=>$roi_line,'cls'=>$cls);
								}
								else if($catres[$i][CATEGORY_STATUS] == 3) /** if Functional area is in scope**/
								{
									$inscopeFA[$category_id] = array('display' =>$display,'lineitemcnt'=>$lineitemcnt,'status'=>$status,'roi_line'=>$roi_line,'cls'=>$cls);
								}
								else if($catres[$i][CATEGORY_STATUS] == 1) /** if Functional area is in scope means clicked yes **/
								{
									$completeFA[$category_id] = array('display' =>$display,'lineitemcnt'=>$lineitemcnt,'status'=>$status,'roi_line'=>$roi_line,'cls'=>$cls);
								}
								else if($catres[$i][CATEGORY_STATUS] == 2) /** if Functional area is not required**/
								{
									$notrequiredFA[$category_id] = array('display' =>$display,'lineitemcnt'=>$lineitemcnt,'status'=>$status,'roi_line'=>$roi_line,'cls'=>$cls);
								}
								
					?>
					
					<?php
							} 
						}
					?>
					
					<?php
					 /** display incompete functional areas **/
					 if(count($incompleteFA)!=0)
					 echo $objUser->displayFAlines($incompleteFA);

					 /** display inscope functional areas **/
					 if(count($inscopeFA)!=0)
					 echo $objUser->displayFAlines($inscopeFA);

					 /** display complete functional areas **/
					 if(count($completeFA)!=0)
					 echo $objUser->displayFAlines($completeFA);
					 
					  /** display notrequired functional areas **/
					 if(count($notrequiredFA)!=0)
					 echo $objUser->displayFAlines($notrequiredFA);
					?>
			</div>
			<div class="dash_stat">
			<?php
			
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

			$is_risk_management = get_option("risk_management");
			if ($is_risk_management == 1 ) { 
				$totalMore = 5;
			} else {
				$totalMore = 4;
			}
			?>	
			<section class="info-text">
		<div class="container clearfix">
			
			<p>O:<?php echo $totalCompanyQues."/".$userCompanyQues;?>,&nbsp; P: <?php echo $totalProjectQues."/".$userProjectQues;?> ,&nbsp; FP:<?=$totalMore; ?>/<?php echo $userProjectMore;?>,&nbsp; FA: <?php echo $userFaDisplay;?> ,&nbsp; Stage <?php echo $stage;?> </p>
			
		</div>
	</section>
			</div>
			</div>
			<div class="cols_right">

				<div class="box_white">
					<h2 class="blue_light">Peer insights & suggestions</h2>
					<p>
						<?php
							$compare=$objUser->getDefaultData('How_does_project_compare?');
							echo $compare= stripslashes(unserialize($compare->text));
						?>
					</p>
				</div>
				<?php
						$res=$objUser->GetTop5('category');
						 
						
						if($res)
						{
							usort($res, function($a, $b) {
								return $a['roi'] - $b['roi'];
							});

							$res = array_reverse($res);

				?>
							<div class="list3col">
								<div class="headbg">
									<span>Functional Area</span>
									<span>POP.</span>
									<span>ROI AVerage</span>
								</div>
								<?php
									$k=0;

									foreach($res as $key=>$val)
									{
										$pop=0;
										$normal=0;
										if($k==5)
											break;


									$pop = round($val['cnt']*5/$val['total']);
									$normal = 5-$pop;
								?>
								<div class="lraw">
									<div class="l3_cols1">
										<?php echo stripslashes($objUser->GetCategoryName($val['catid'])); ?>
									</div>
									<div class="l3_cols2">
										<div class="status">
											<?php
												for($i=0;$i<$normal;$i++)
												{
											?>
													<span class="st st_normal"></span>
											<?php
												}
											?>
											<?php
												for($i=0;$i<$pop;$i++)
												{
											?>
													<span class="st st_active"></span>
											<?php
												}
											?>
										</div>
									</div>
									<div class="l3_cols3">
										<div class="price_section">
											<span class="dollar">$</span>
											<?php /** calculate the ROI average for a category 
													Divide total category ROI with total number of projects
													*/
											?> 
											<span class="price ztop">$<?php echo round($val['roi']/$val['total']);?>/YR</span>
										</div>
									</div>
								</div>
								<?php
										$k++;
									}
								?>
							</div>
							<?php
									}
									$res=$objUser->GetTop5('lineitem');
									/*usort($res, function($a, $b) {
										return $a['cnt'] - $b['cnt'];
									});*/


									if($res)
									{
										usort($res, function($a, $b) {
											return $a['roi'] - $b['roi'];
										});

										$res=array_reverse($res);

							?>

								<div class="list3col marbtm20">
									<div class="headbg">
										<span>Features</span>
										<span>POP.</span>
										<span>ROI AVerage</span>
									</div>

									<?php
										$k=0;

										foreach($res as $key=>$val)
										{
											$pop=0;
											$normal=0;
											if($k==5)
												break;


										$pop=round($val['cnt']*5/$val['total']);
										$normal=5-$pop;
									?>
									<div class="lraw">
										<div class="l3_cols1">
											<?php echo stripslashes($objUser->GetLineItemName($val['lineid'])); ?>
										</div>
										<div class="l3_cols2">
											<div class="status">
												<?php
													for($i=0;$i<$normal;$i++)
													{
												?>
														<span class="st st_normal"></span>
												<?php
													}
												?>
												<?php
													for($i=0;$i<$pop;$i++)
													{
												?>
														<span class="st st_active"></span>
												<?php
													}
												?>
											</div>
										</div>
										<div class="l3_cols3">
											<div class="price_section">
												<span class="dollar">$</span>
												<?php /** Display average of each line item **/?>
												<span class="price ztop">$<?php echo round($val['roi']/$val['total']);?>/YR</span>
											</div>
										</div>
									</div>
									<?php
											$k++;
										}
									?>
								</div>
						<?php
								}
						?>

				<div class="box_white marbtm20">
					<h2 class="blue_light">Notices</h2>
					<?php
						$notice1=$objUser->getDefaultData('Dashboard_Notice_1');
						echo $notice1= stripslashes(unserialize($notice1->text));
					?>

					<div class="message warning">
						<?php
							$warning=$objUser->getDefaultData('Dashboard_Warning');
							echo $warning= stripslashes(unserialize($warning->text));
						?>
					</div>

					<?php
						$notice2=$objUser->getDefaultData('Dashboard_Notice_2');
						echo $notice2= stripslashes(unserialize($notice2->text));
					?>
				</div>

				<div class="box_white">
					<h2 class="blue_light marbtm10">Got Feedback?</h2>

					<?php
						if(get_user_meta($loggedin_id,'feedback',true)!=1)
						{
					?>
					<div class="feedback-heading">
						<a herf="javascript:void(1)" id="give_feedback">Click here to give your feedback</a>
					</div>
					<form id="frmfeedback" method="post" class="form-feedback">
						<textarea name="feedback" id="feedback" rows="5" cols="40" class="dashboard-feedback" placeholder="< Enter your feedback >"></textarea>
					</form>
					<?php
						}
						else
						{
					?>
					<div class="feedback-heading">
						You have already submitted your feedback.
					</div>
					<?php
						}
					?>
				</div>

				<div class="got_feedback">
					<a class="btn btn-green" href="#" id="submit_feedback">
						<span class="btn-inner">
							<strong>Submit</strong> Feedback
						</span>
					</a>
				</div>

			</div>
		</div>
	</div>
	<!-- End: Content Section -->
</div>
