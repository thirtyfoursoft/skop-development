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
	if (!class_exists('userinfo')) {
		include('core/class_userinfo.php');
	}
	$obj = new userinfo();
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

	$catres = $objUser->GetToolData($loggedin_id);

	$catStatus = array (
		'0' => 'Incomplete',
		'1' => 'Complete',
		'2' => 'Notrequired',
		'3' => 'Inscope'
	);

	$importance = array(
		'0' => 'Nice to have',
		'1' => 'Important',		
		'2' => 'Mandatory'
	);		
	
//	$basicCmt = '<span class="complete pull-right">Complete</span>';

	function GetLineItemFromToolDataNew( $userID, $catID ) {

			$tooldata = get_user_meta($userID,'tool_data',true);
			for($i=0;$i<sizeof($tooldata);$i++) {
				if($catID==$tooldata[$i][CATEGORY_ID]) {
					return $tooldata[$i][CATEGORY_LINEITEMS];
				}
			}
	}

//echo "<pre>";		print_r($catres)	;	echo "</pre>";
?>
<style type="text/css">
table#header tr th { padding: 10px; text-align: left; text-transform: capitalize; background: #e3e3e3; }
table tr td{  padding: 10px;  text-align: left; text-transform: capitalize;}
</style>
<div class="content-section clearfix">
	<div class="container">
		<div class="accordion-main-con">
			<div class="page-headding">
			  <h3>ABOUT YOUR ORGANISATION</h3>
			</div>
			<table id="header">
				<tr>
					<th style="width: 23%;">Functional area title</th>
					<th style="width: 12%;">Status</th>
					<th style="width: 10%;">Total # features</th>
					<th style="width: 10%;">In Scope</th>
					<th style="width: 10%;">Yearly savings estimate</th>
					<th style="width: 10%;">Tally of stakeholder benefits</th>
					<th style="width: 12%;">Max Importance</th>
				</tr>
			</table>

			<div id="" class="accordion-con">
			<?php	
				$total = array();

				foreach ($catres as $k => $v) { ?>
				<?php if($catres[$k][CATEGORY_IS_CUSTOM]==0): ?>
				
				<?php
				
						$allCount = array();
						$allCount['niceToHave'] = $allCount['Important'] = $allCount['mandatory'] = 0;

						if($catres[$k][CATEGORY_LINEITEMS]) {
							foreach($catres[$k][CATEGORY_LINEITEMS] as $lineitem)	{
//echo "**<pre>";		print_r($lineitem);	echo "</pre>";
								$benifit = array();
								
								$how_often = $lineitem[LINEITEM_YEAR];
								$cost = $lineitem[LINEITEM_COST];
								$time = $lineitem[LINEITEM_TIME];
								$hrate = get_usermeta($loggedin_id,"org_ques_9_state",true);
								$allCount['yearlySaving'] += $objUser->ROICalculation( $hrate, $time, $cost, $how_often);

								if($lineitem[LINEITEM_BENEFIT_FOR]!="") {
									$benifit = explode(",",$lineitem[LINEITEM_BENEFIT_FOR]);
								}
								$allCount['stakeholderBenefits'] += count($benifit);

								if ($lineitem[2] == 0) {
									$allCount['niceToHave'] += 1;
								} elseif( $lineitem[2] == 1 ) {
									$allCount['Important'] += 1;
								} elseif ( $lineitem[2] == 2 ) {
									$allCount['mandatory'] += 1;
								}

							}

						}

						$total['yearlySaving'] += $allCount['yearlySaving'];
						$total['lineItems'] += count($v[CATEGORY_LINEITEMS]);
						$total['stakeholderBenefits'] += $allCount['stakeholderBenefits'];
						//echo "**<pre>";		print_r($total);	echo "</pre>";
				?>
						<h5>
							<table>
								<tr>
									<td style="width: 23%;"><?php echo $objUser->GetCategoryName($v[0]); ?></td>
									<td style="width: 12%;"><?php echo $catStatus[$v[3]]; ?></td>
									<td style="width: 10%;"><?php echo count($v[CATEGORY_LINEITEMS]); ?></td>
									<td style="width: 10%;">In Scope</td>
									<td style="width: 10%;"><?php echo '$'.$allCount['yearlySaving']; ?></td>
									<td style="width: 10%;"><?=$allCount['stakeholderBenefits']; ?></td>
									<td style="width: 12%;"><span style="color:red;"><?=$allCount['mandatory']; ?></span>/ <span style="color:red;"><?=$allCount['Important']; ?></span> / <span style="color:green;"><?=$allCount['niceToHave']; ?></span></td>
								</tr>
							</table>
						</h5>

						<div>
							<div class="accordian-inner-container">
								<form>
									Further content should go here.
								</form>
							</div>
						</div>
				<?php endif; ?>
			<?php } ?>
			<div>
				<table id="header">
					<tr>
						<td style="width: 23%; padding: 10px 30px;"><b>Totals</b></td>
						<td style="width: 12%; padding: 10px 30px;">In Progress</td>
						<td style="width: 10%; padding: 10px 30px;"><b><?php echo $total['lineItems']; ?></b></td>
						<td style="width: 10%; padding: 10px 30px;">?</td>
						<td style="width: 10%; padding: 10px 30px;"><?php echo '$'.$total['yearlySaving']; ?></td>
						<td style="width: 10%; padding: 10px 30px;"><?=$total['stakeholderBenefits']; ?></td>
						<td style="width: 12%; padding: 10px 30px;">?</td>
					</tr>
				</table>		

		</div>
	</div>
</div>
