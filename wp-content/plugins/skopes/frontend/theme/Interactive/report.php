<?php
	/*
	*   Name: Twisha Patel Date: 19-10-2013
	*   Report generation HTML for PDF report for skop.es
	*/

	$objUser = new clsSkopes();
	$loggedin_id=get_current_user_id();
?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8" />
	<title>Digital Project Scoping Tool - Report</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
	<meta name="description" content="" />
	<meta name="author" content="" />

	<!-- stylesheet -->
	<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/css/report.css" rel="stylesheet" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/css/jquery.easy-pie-chart.css" media="screen">

	<!--[if lt IE 9]>
		<script src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/html5.js" type="text/javascript"></script>
		  <script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/PIE.js"></script>
	<![endif]-->


	<!-- favicon and touch icons -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/apple-touch-icon-144.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/apple-touch-icon-114.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/apple-touch-icon-72.png" />
	<link rel="apple-touch-icon-precomposed" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/apple-touch-icon.png" />
	<link rel="shortcut icon" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/favicon.png" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/favicon.ico" />
	<script type="text/javascript">
		var initPieChart = function() {
			$('.percentage').easyPieChart({
				animate: 1,
				scaleColor: false,
				lineCap: 'butt',
				lineWidth: 7,
				trackColor : "#E0F1FB",
				barColor : "#B38DC8",
				size :70,
				onStep: function(value) {
					/*this.$el.find('span').text(~~value);*/
				}
			});
		}
	</script>
</head>
<?php
	$tooldata=$objUser->GetToolData($loggedin_id);
	if($tooldata)
	{
		$final_arr=false;
		$i=0;
		foreach($tooldata as $data)
		{

			/* for functional area start */
			$lineitems_data=$objUser->GetLineItemFromToolData($loggedin_id,$data[CATEGORY_ID]);
			if($lineitems_data)
			{
				$lcnt=0;
				$roi_line=0;
				$low_cnt=0;
				$medium_cnt=0;
				$high_cnt=0;
				$existing_cnt=0;
				$replace_cnt=0;
				foreach($lineitems_data as $lineitem)
				{
					/* get roi of each line item */
					$how_often=$lineitem[LINEITEM_YEAR];
					$cost=$lineitem[LINEITEM_COST];
					$time=$lineitem[LINEITEM_TIME];
					$hrate=get_usermeta($loggedin_id,"hourly_rate",true);
					$roi_line=$roi_line+$objUser->ROICalculation($hrate,$time,$cost,$how_often);
					$lcnt++;

					$low_val=$objUser->getDefaultData('Impotrtance_Low_Value');
					$low_val=unserialize($low_val->text);
					$medium_val=$objUser->getDefaultData('Impotrtance_Medium_Value');
					$medium_val=unserialize($medium_val->text);
					$high_val=$objUser->getDefaultData('Impotrtance_High_Value');
					$high_val=unserialize($high_val->text);

					if($lineitem[LINEITEM_IMPORTANCE]==$low_val)
						$low_cnt++;
					else if($lineitem[LINEITEM_IMPORTANCE]==$medium_val)
						$medium_cnt++;
					else if($lineitem[LINEITEM_IMPORTANCE]==$high_val)
						$high_cnt++;
					else
						$low_cnt++;

					if($lineitem[LINEITEM_IS_AUTOMATED]==1)
						$existing_cnt++;
					else
						$replace_cnt++;

				}

				$funtional_arr["catid"]=$data[CATEGORY_ID];
				$funtional_arr["catname"]=$objUser->GetCategoryName($data[CATEGORY_ID]);
				$funtional_arr["catdesc"]=$objUser->GetCategoryData($data[CATEGORY_ID],'text');
				$funtional_arr["catnote"]=$data[CATEGORY_NOTE];
				$funtional_arr["ROI"]=$roi_line;
				$funtional_arr["lineitemcnt"]=$lcnt;
				$funtional_arr["importance_low"]=$low_cnt*100/$lcnt;
				$funtional_arr["importance_medium"]=$medium_cnt*100/$lcnt;
				$funtional_arr["importance_high"]=$high_cnt*100/$lcnt;

				$funtional_arr["low"]=$low_cnt;
				$funtional_arr["medium"]=$medium_cnt;
				$funtional_arr["high"]=$high_cnt;

				/*echo $existing_cnt."------".$replace_cnt;*/
				$per=$existing_cnt*100/($existing_cnt+$replace_cnt);
				$funtional_arr["automated_replace_existing"]=$per;
				$final_arr[]=$funtional_arr;
			}
			/* for functional area end */

			/* for featurelist area start */
			if($lineitems_data)
			{
				foreach($lineitems_data as $feature)
				{
					$how_often=$feature[LINEITEM_YEAR];
					$cost=$feature[LINEITEM_COST];
					$time=$feature[LINEITEM_TIME];
					$hrate=get_usermeta($loggedin_id,"hourly_rate",true);
					$roi=$objUser->ROICalculation($hrate,$time,$cost,$how_often);

					$feature_arr[$i]["lineitem_catid"]=$data[CATEGORY_ID];
					$feature_arr[$i]["lineitem_name"]=$objUser->GetLineItemName($feature[LINEITEM_ID]);
					$feature_arr[$i]["lineitem_ROI"]=$roi;
					$feature_arr[$i]["lineitem_catname"]=$objUser->GetCategoryName($data[CATEGORY_ID]);


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

					if($feature[LINEITEM_IMPORTANCE]==$low_val)
						$feature_arr[$i]["lineitem_importance"]="Low";
					else if($feature[LINEITEM_IMPORTANCE]==$medium_val)
						$feature_arr[$i]["lineitem_importance"]="Medium";
					else if($feature[LINEITEM_IMPORTANCE]==$high_val)
						$feature_arr[$i]["lineitem_importance"]="High";

					if($feature[LINEITEM_IS_AUTOMATED]==1)
						$feature_arr[$i]["lineitem_automated"]="Existing";
					else
						$feature_arr[$i]["lineitem_automated"]="Replacement";

					$feature_arr[$i]["lineitem_desc"]=$objUser->GetLineItemDataFromLineId($feature[LINEITEM_ID],"text");
					$feature_arr[$i]["lineitem_notes"]=$feature[LINEITEM_NOTE];


					$i++;
				}
			}
			/* for featurelist area end */
		}
		usort($final_arr, function($a, $b) {
			return $a['ROI'] - $b['ROI'];
		});
		$final_arr=array_reverse($final_arr);

		usort($feature_arr, function($a, $b) {
			return $a['lineitem_ROI'] - $b['lineitem_ROI'];
		});

		$feature_arr=array_reverse($feature_arr);

		/*usort($feature_arr, function($a, $b) {
			return $a['lineitem_catid'] - $b['lineitem_catid'];
		});*/

	}
?>
<body onLoad="initPieChart();">
	<input type="hidden" id="path" value="<?php echo RC_TC_PLUGIN_URL ?>frontend"/>
	<!-- 1st Page Start -->
	<section id="wrapper">
		<!-- Start:report-home-->
		<section class="report-home">
			<?php include("report_header.php")?>

			<!-- Start:middle-section -->
			<section class="middle-section">
			</section>
			<!-- End:middle-section -->

			<!-- Start:orange-section -->
			<footer class="orange-section">
				<div class="container">
					<h4 class="uppercase">Scoping specification document</h4>
					<h2><?php echo get_usermeta($loggedin_id,'projectname',true); ?></h2>
					<h4><span>author:</span> <?php echo get_usermeta($loggedin_id,'contactname',true); ?></h4>
				</div>
			</footer>
			<!-- End:orange-section -->
		</section>
		<!-- End:report-home-->
	</section>
	<!-- 1st Page End -->

	<!-- 2nd Page Start -->
	<section id="wrapper">
		<!-- Start:report-home -->
		<section class="table-contents">

			<!-- Start:header-section -->
			<?php include("report_header.php"); ?>
			<!-- End:header-section -->

			<!-- Start:middle-section -->
			<section class="middle-section">

				<!-- Start:heading-section -->
				<hgroup class="heading-section">
					<h1>Table of contents</h1>
				</hgroup>
				<!-- End:heading-section -->

				<!-- Start:content-section -->
				<section class="content-section">
					<div class="container">

						<ul class="index-section">
							<li>
								<div class="clearfix">
									<span class="cols1">
										Introductions
									</span>
									<span class="cols2 text-blue">
										3.
									</span>
								</div>
							</li>
							<li>
								<div class="clearfix">
									<span class="cols1">
										Overview: Functional Areas
									</span>
									<span class="cols2 text-green">
										4.
									</span>
								</div>
							</li>

							<li>
								<div class="clearfix">
									<span class="cols1">
										Functional Areas
									</span>
									<span class="cols2 text-purple">
										5.
									</span>
								</div>
							</li>
							<li>
								<div class="clearfix">
									<span class="cols1">
										Overview: Feature lists
									</span>
									<span class="cols2 text-red">
										6.
									</span>
								</div>
							</li>
							<li>
								<div class="clearfix">
									<span class="cols1">
										Feature Lists
									</span>
									<span class="cols2 text-blue">
										7.
									</span>
								</div>
							</li>
						</ul>

					</div>
				</section>
				<!-- End:content-section -->

			</section>
			<!-- End:middle-section -->

		</section>
		<!-- End:report-home -->
	</section>
	<!-- 2nd Page End -->

	<!-- 3rd Page Start -->
	<section id="wrapper">
		<!-- Start:report-home -->
		<section class="introduction">
			<?php include("report_header.php") ?>
			<!-- Start:middle-section -->
			<section class="middle-section">

				<!-- Start:heading-section -->
				<div class="heading-section">
					<h1>Introductions</h1>
				</div>
				<!-- End:heading-section -->

				<!-- Start:content-section -->
				<section class="content-section">
					<div class="container">
						<div class="large-text">
							<?php
									$intro_text=$objUser->getDefaultData('General_Introduction');
									$intro_text=unserialize($intro_text->text);
									echo $intro_text;
							?>
						</div>
						<div class="colsmain clearfix">
							<aside>
								<div class="block">
									<h2>Profile Details</h2>
									<div class="rawblock">
										<h4>Company name:</h4>
										<p><?php echo get_usermeta($loggedin_id,'companyname',true); ?></p>
									</div>
									<div class="rawblock">
										<h4>Project name:</h4>
										<p><?php echo get_usermeta($loggedin_id,'projectname',true); ?></p>
									</div>
									<div class="rawblock">
										<h4>Project sponsor name:</h4>
										<p><?php echo get_usermeta($loggedin_id,'sponsername',true); ?></p>
									</div>
									<div class="rawblock">
										<h4>Contact name:</h4>
										<p><?php echo get_usermeta($loggedin_id,'contactname',true); ?></p>
										<p>
											<?php
												if(get_usermeta($loggedin_id,'position',true)=="Other")
													echo get_usermeta($loggedin_id,'other_position',true);
												else
													echo get_usermeta($loggedin_id,'position',true);
											?>
											<em>[position / role]</em>
										</p>
										<p>
											<?php
												global $current_user;
												get_currentuserinfo();
												echo $current_user->user_email;
											?><em>[contact email]</em></p>
									</div>
									<div class="rawblock">
										<h4>Primary objective:</h4>
										<p><?php echo get_usermeta($loggedin_id,'objective',true); ?></p>
									</div>
									<div class="rawblock">
										<h4>Company information:</h4>
										<ul>
											<li><?php echo get_usermeta($loggedin_id,'country',true); ?>, <?php echo get_usermeta($loggedin_id,'state',true); ?>, <?php echo get_usermeta($loggedin_id,'city',true); ?>.</li>
											<li><?php echo get_usermeta($loggedin_id,'organisation',true); ?></li>
											<li><?php echo get_usermeta($loggedin_id,'number_of_location',true); ?> locations</li>
											<li><?php echo get_usermeta($loggedin_id,'turnover',true); ?> annual turnover</li>
											<li><?php echo get_usermeta($loggedin_id,'staff',true); ?> staff</li>
										</ul>
									</div>
									<div class="rawblock">
										<h4>Project information:</h4>
										<ul>
											<li>&lt;<?php echo get_usermeta($loggedin_id,'number_of_teamleader',true); ?> team members in project delivery</li>
											<li>&lt;<?php echo get_usermeta($loggedin_id,'project_budget',true); ?> expected project budget</li>
											<li>
												Project delivery:
												<?php
													if(get_usermeta($loggedin_id,'timeline_month',true)>0)
														echo get_usermeta($loggedin_id,'timeline_month',true);

													if(get_usermeta($loggedin_id,'timeline_year',true)>0)
														echo " ".get_usermeta($loggedin_id,'timeline_year',true);
												?>

											</li>
											<li>Training for &lt;<?php echo get_usermeta($loggedin_id,'number_of_team_members',true); ?> team members</li>
											<li><?php echo get_usermeta($loggedin_id,'lunch_info',true); ?></li>
											<!--li>Reporting data to be automatically exported daily</li>
											<li>Change Tracking history required.</li-->
										</ul>
									</div>
									<div class="rawblock">
										<h4>Database information:</h4>
										<ul>
											<li>Database members: <?php echo get_usermeta($loggedin_id,'number_of_members_in_db',true); ?></li>
											<li>Database software: <?php echo get_usermeta($loggedin_id,'db_software',true); ?></li>
											<li>Satisfaction with current software: <?php echo get_usermeta($loggedin_id,'db_happy_info',true); ?></li>
											<li><?php echo get_usermeta($loggedin_id,'explanation',true); ?></li>
										</ul>
									</div>
								</div>
							</aside>
							<aside class="right">
								<div class="block">
									<h2>ROI</h2>
									<div class="rawblock">
										<?php
											$roi_text=$objUser->getDefaultData('General_ROI');
											$roi_text=unserialize($roi_text->text);
											echo $roi_text;
										?>
									</div>
									<div class="warning-section">
										<h2>Warnings &amp; Caveats</h2>
										<?php
											$warning_text=$objUser->getDefaultData('General_Warnings_&_Caveats');
											$warning_text=unserialize($warning_text->text);
											echo $warning_text;
										?>
									</div>

								</div>
							</aside>
						</div>
					</div>
				</section>
				<!-- End:content-section -->
			</section>
			<?php
				$_SESSION["page_title"]="Introductions";
				$_SESSION["page_no"]=3;
				include("report_footer.php")
			?>
			<!-- End:middle-section -->
		</section>
		<!-- End:report-home -->
	</section>
	<!-- 3rd Page End -->

	<!-- 4th Page Start -->
	<section id="wrapper">
		<!-- Start:report-home -->
		<section class="fa">

			<!-- Start:header-section -->
			<?php include("report_header.php"); ?>
			<!-- End:header-section -->

			<!-- Start:middle-section -->
			<section class="middle-section">

				<!-- Start:heading-section -->
				<div class="heading-section">
					<h1>Overview: Functional Areas</h1>
				</div>
				<!-- End:heading-section -->

				<!-- Start:content-section -->
				<section class="content-section">
					<div class="container">
						<div class="fa-overview">
							<div class="fao-table">
								<header class="header clearfix">
									<div class="cols1">ROI Order</div>
									<div class="cols2">Functional Area</div>
									<div class="cols3">Qty. of features</div>
									<div class="cols4">Importance</div>
									<div class="cols5">Replacement vs Existing</div>
									<div class="cols6">Projected ROI</div>
								</header>
								<?php
									$sno=0;
									foreach($final_arr as $res)
									{
										$sno++;
								?>
										<div class="listing">
											<div class="rawlist clearfix">
												<div class="cols1 order"><?php echo $sno; ?></div>
												<div class="cols2 farea"><?php echo $res["catname"] ?></div>
												<div class="cols3 qty"><span class="num"><?php echo $res["lineitemcnt"] ?></span></div>
												<div class="cols4 imp">
													<div class="imp-raw"><span class="imp-label">High</span><span class="bar high" style="width:<?php echo $res["importance_high"]?>%;"><?php if($res["importance_high"]==0){ echo "---"; } ?></span></div>
													<div class="imp-raw"><span class="imp-label">Medium</span><span class="bar medium" style="width:<?php echo $res["importance_medium"]?>%;"><?php if($res["importance_medium"]==0){ echo "---"; } ?></span></div>
													<div class="imp-raw"><span class="imp-label">Low</span><span class="bar low" style="width:<?php echo $res["importance_low"]?>%;"><?php if($res["importance_low"]==0){ echo "---"; } ?></span></div>
												</div>
												<div class="cols5 re">
													<span class="re-left chart">
														<div class="percentage" DATA-PERCENT="<?php echo $res["automated_replace_existing"]; ?>"></div>
													</span>
													<span class="re-right">
														<span class="replacement"><em></em>Replacement</span>
														<span class="existing"><em></em>Existing</span>
													</span>
													<span class="clear"></span>
												</div>
												<div class="cols6 price">$<?php echo $res["ROI"] ?></div>
											</div>
										</div>
								<?php
									}
								?>
							</div>
						</div>

					</div>
				</section>
			</section>
			<!-- End:middle-section -->
			<?php
				$_SESSION["page_title"]="OVERVIEW: Functional Areas";
				$_SESSION["page_no"]=4;
				include("report_footer.php")
			?>
		</section>
		<!-- End:report-home -->
	</section>
	<!-- 4th Page End -->

	<!-- 5th Page Start -->
	<section id="wrapper">
		<!-- Start:report-home -->
		<section class="fa functionalarea">

			<!-- Start:header-section -->
			<?php include("report_header.php"); ?>
			<!-- End:header-section -->

			<!-- Start:middle-section -->
			<section class="middle-section">

				<!-- Start:heading-section -->
				<div class="heading-section">
					<h1>Functional Areas</h1>
				</div>
				<!-- End:heading-section -->

				<!-- Start:content-section -->
				<section class="content-section">
					<div class="container">

						<div class="large-text">
						<?php
							$functional_intro=$objUser->getDefaultData('Functional_Area_Introduction');
							echo $functional_intro=unserialize($functional_intro->text);
						?>
						</div>

						<div class="colsmain clearfix">
							<aside>
							<?php
								$funcnt=0;

								foreach($final_arr as $funres)
								{
									if($funcnt%3==0 && $funcnt!=0)
									   echo "</aside><aside>";
									$funcnt++;
							?>

									<div class="fa-block">
										<header>
											<div class="heading-top clearfix">
												<span class="fa-sr"><?php echo $funcnt;?>.</span><span class="fa-heading"><?php echo $funres["catname"]; ?></span>
											</div>
											<div class="heading-btm clearfix">
												<span><strong>ROI:</strong>  $<?php echo $funres["ROI"]; ?></span>
												<span><strong>Importance:</strong>
													<?php
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

														$ans=($funres["low"]+$funres["medium"]+$funres["high"])/3;
														$ans=round($ans);
														/*echo $ans."==".$low_val."--".$medium_val."--".$high_val;*/
														if($ans<=$low_val)
															$imp="LOW";
														else if($ans>$low_val && $ans<=$medium_val)
															$imp="MEDIUM";
														else if($ans>$medium_val && $ans<=$high_cnt)
															$imp="HIGH";
														else if($ans>$high_cnt)
															$imp="HIGH";

														echo $imp;
														//print_r(array_keys($funres["importance"], max($funres["importance"])));
													?>
												</span>
											</div>
										</header>
										<div class="fa-content">
											<!--p class="subheading">Enim sagittis aliquet ac nec magna. </p-->
											<p class="label">Description</p>
											<p class="inner"><?php if($funres["catdesc"]!="") { echo $funres["catdesc"];} else { echo "No description available."; } ?></p>
											<p class="label">Notes:</p>
											<p class="bgblue"><?php if($funres["catnote"]!="") { echo $funres["catnote"]; } else { echo "No notes available.";}?></p>
											<footer>
												<a id="<?php echo $funres["catid"]; ?>_catid" href="#catid_<?php echo $funres["catid"]; ?>">View features list</a>
											</footer>
										</div>

									</div>

							<?php
									if(($funcnt-1)==count($final_arr))
									   echo "</aside>";

								}
							?>

						</div>
					</div>
				</section>
				<!-- End:content-section -->
			</section>
			<?php
					$_SESSION["page_title"]="Functional Areas";
					$_SESSION["page_no"]=5;
					include("report_footer.php")
				?>
			<!-- End:middle-section -->
		</section>
		<!-- End:report-home -->
	</section>
	<!-- 5th Page End -->

	<!-- 6th Page Start -->
	<section id="wrapper">
		<!-- Start:report-home -->
		<section class="fa">
			<?php include("report_header.php"); ?>

			<!-- Start:middle-section -->
			<section class="middle-section">

				<!-- Start:heading-section -->
				<div class="heading-section">
					<h1>Overview: Feature lists</h1>
				</div>
				<!-- End:heading-section -->

				<!-- Start:content-section -->
				<section class="content-section">
					<div class="container">

						<div class="fa-featurelist">
							<div class="fao-table">
								<header class="header clearfix">
									<div class="cols1">Order</div>
									<div class="cols2">Feature</div>
									<div class="cols3">Functional area</div>
									<div class="cols4">Importance</div>
									<div class="cols5">Status</div>
									<div class="cols6">Projected ROI</div>
								</header>
								<?php
									$sno=0;
									$catArray=array();
									foreach($feature_arr as $feature)
									{
										$catArray[$feature['lineitem_catid']][]=$feature;
										$sno++;
								?>
									<div class="listing">
										<div class="rawlist clearfix">
											<div class="cols1 order"><?php echo $sno; ?>.</div>
											<div class="cols2 ffeature"><?php echo $feature["lineitem_name"]; ?></div>
											<div class="cols3 farea"><?php echo $feature["lineitem_catname"]; ?></div>
											<div class="cols4">
												<?php
													$cls="";
													if($feature["lineitem_importance"]=="High")
														$cls="bar-high";
													else if($feature["lineitem_importance"]=="Medium")
														$cls="bar-medium";
													else if($feature["lineitem_importance"]=="Low")
														$cls="bar-low";
												?>
												<span class="fbar <?php echo $cls; ?>"><?php echo $feature["lineitem_importance"]; ?></span>
											</div>
											<div class="cols5">
												<?php
													$barcls="";
													if($feature["lineitem_automated"]=="Existing")
														$barcls=" bar-existing";
													else if($feature["lineitem_automated"]=="Replacement")
														$barcls=" bar-replacement";
												?>
												<span class="fbar<?php echo $barcls; ?>"><?php echo $feature["lineitem_automated"]?></span>
											</div>
											<div class="cols6 price">$<?php echo $feature["lineitem_ROI"]?></div>
										</div>
									</div>
								<?php
								   }
								?>
							</div>
						</div>
					</div>
				</section>
				<!-- End:content-section -->
			</section>
			<?php
				$_SESSION["page_title"]="OVERVIEW: Feature lists";
				$_SESSION["page_no"]=6;
				include("report_footer.php")
			?>
			<!-- End:middle-section -->
		</section>
		<!-- End:report-home -->
	</section>
	<!-- 6th Page End -->

	<!-- 7th Page Start -->
	<section id="wrapper">
		<!-- Start:report-home -->
		<section class="fa featurelist">

			 <?php include("report_header.php"); ?>

			<!-- Start:middle-section -->
			<section class="middle-section">

				<!-- Start:heading-section -->
				<div class="heading-section">
					<h1>Feature Lists</h1>
				</div>
				<!-- End:heading-section -->

				<!-- Start:content-section -->
				<section class="content-section">
					<div class="container">

						<div class="large-text">
							<?php
								$feature_intro=$objUser->getDefaultData('Feature_List_Introduction');
								$feature_intro=unserialize($feature_intro->text);
								echo $feature_intro;
							?>
						</div>

						<div class="colsmain clearfix">
							<aside>
							<?php

								$featurecnt=0;
								//print_r($catArray);
								foreach($catArray as $arrfeature)
								{

									foreach($arrfeature as $feature_res)
									{
										if($featurecnt%3==0 && $featurecnt!=0)
										   echo "</aside><aside>";

										$featurecnt++;
							?>
										<div class="fa-block" id="catid_<?php echo $feature_res["lineitem_catid"];?>">
											<header>
												<?php
													if($feature_res["lineitem_catname"]!=$oldcatname)
													{
												?>
													<div class="heading-orange"><?php echo $feature_res["lineitem_catname"]; ?></div>
												<?php
													}
													$oldcatname=$feature_res["lineitem_catname"];
												?>
												<div class="heading-top clearfix">
													<span class="fa-sr"><?php echo $featurecnt;?>.</span><span class="fa-heading"><?php echo $feature_res["lineitem_name"]; ?></span>
												</div>
												<div class="heading-btm clearfix">
													<span><strong>ROI:</strong> $<?php echo $feature_res["lineitem_ROI"]; ?></span>
													<span><strong>Importance:</strong> <?php echo $feature_res["lineitem_importance"]; ?></span>
													<span><strong>Status:</strong> <?php echo $feature_res["lineitem_automated"]; ?></span>
												</div>
											</header>
											<div class="fa-content">
												<!--p class="subheading">Enim sagittis aliquet ac nec magna. </p-->
												<p class="label">Description</p>
												<p class="inner"><?php if($feature_res["lineitem_desc"]!="") { echo $feature_res["lineitem_desc"];} else { echo "No description available."; } ?></p>
												<p class="label">Notes:</p>
												<p class="bgblue"><?php if($feature_res["lineitem_notes"]!="") { echo $feature_res["lineitem_notes"];} else { echo "No notes available."; } ?></p>
											</div>
										</div>
							 <?php
										if(($featurecnt-1)==count($feature_arr))
										  echo "</aside>";
									}
								}
							?>
							</aside>
						</div>
					</div>
				</section>
				<!-- End:content-section -->
			</section>
			<!-- End:middle-section -->
			 <?php
				$_SESSION["page_title"]="Feature lists";
				$_SESSION["page_no"]=7;
				include("report_footer.php")
			?>
		</section>
		<!-- End:report-home -->
	</section>
	<!-- 7th Page End -->

<!-- Javascript -->
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/html5.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/jquery-1.8.1.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/jquery.placeholder.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/custom.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/excanvas.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/jquery.easy-pie-chart.js"></script>
<?php
	if(is_user_logged_in())
	{
?>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/login_check.js"></script>
<?php
	}
?>
</body>
</html>

