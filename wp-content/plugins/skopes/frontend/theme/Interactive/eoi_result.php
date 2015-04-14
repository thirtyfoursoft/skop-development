<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>js/skopes-accordion-main.js"></script>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>js/skopes-accordion-active.js"></script>
<link href="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/css/jquery-ui.css" rel="stylesheet" media="screen" />
<!--<script type="text/javascript" src="jquery/popup-jquery.js"></script>
<script type="text/javascript" src="popup-window.js"></script>-->
<script>
	$(function() {
		$(".accordion-con").accordion({ collapsible: true, active: 'none', heightStyle: "content" });
	});
</script>
<?php
	$is_EOI_vendor = 	get_option("_eoi_vendor");
	if ($is_EOI_vendor != 1 ):
		wp_redirect($page."?act=guide");
		exit;		
	endif;
/**
 * Created by PhpStorm.
 * User: LCubed Intern
 * Date: 5/05/14
 * Time: 10:57 AM
 */

/*
Global Declaration Database Object $wpdb
*/
global $wpdb;
$loggedin_id = get_current_user_id();
?>

<SCRIPT TYPE="text/javascript">
    <!--
    function popup(mylink, windowname) {
        if (! window.focus)return true;
        var href;
        if (typeof(mylink) == 'string')
            href=mylink;
        else
            href=mylink.href;
        window.open(href, windowname, 'resizable=yes, top=300, left=800,width=300,height=170,scrollbars=yes');
        return false;
    }
    //-->
</SCRIPT>

<div class="content-section clearfix">
	<div class="container">
		<div class="accordion-main-con">
			<div class="page-headding">
			  <h3>Vendor EOI Responses and Requirements/Platform Functionality Match</h3>
			</div>
			<div id="" class="accordion-con">
				<h5><span class="blue-arrow pull-right"></span>
					Executive Summary
				</h5>
				
			<?php
				/*     get the data from database wp_teammember    */
				global $wpdb;
				$vendor_company = array();
				$vendor_system = array();
				$vendor_timeline_low = array();
				$vendor_timeline_high = array();
				$vendor_fee_low = array();
				$vendor_fee_high = array();
				$functionality_flag = array();
				$out_of_box = array();
				$may_need_workaround = array();
				$cannot_be_done = array();
				$system_fit_score = array();
				$eoi_complete = array();
				$complete_date = array();
				$m = 0;
				$eoi_ids = $wpdb->get_results(
					"SELECT eoi_id
					 FROM wp_eoiresponse
					 WHERE project_holder_id = $loggedin_id"
				);

				foreach($eoi_ids as $eoi_id) {
					$vendor_eoi_id = $eoi_id->eoi_id;

					/*   get vendor company name based on eoi id  */
					$vendor_company[$m] = $wpdb->get_var( $wpdb->prepare(
						"
						 SELECT org_name
						 FROM wp_eoiresponse
						 WHERE eoi_id = %d
						",
						$vendor_eoi_id
					) );
					/*   get vendor system name based on eoi id  */
					$vendor_system[$m] = $wpdb->get_var( $wpdb->prepare(
						"
						 SELECT your_system_name
						 FROM wp_eoiresponse
						 WHERE eoi_id = %d
						",
						$vendor_eoi_id
					) );
					/*   get vendor timeline based on eoi id  */
					$vendor_timeline_low[$m] = $wpdb->get_var( $wpdb->prepare(
						"
						 SELECT timeline_low
						 FROM wp_eoiresponse
						 WHERE eoi_id = %d
						",
						$vendor_eoi_id
					) );
					$vendor_timeline_high[$m] = $wpdb->get_var( $wpdb->prepare(
						"
						 SELECT timeline_high
						 FROM wp_eoiresponse
						 WHERE eoi_id = %d
						",
						$vendor_eoi_id
					) );
					/*   get vendor fee based on eoi id  */
					$vendor_fee_low[$m] = $wpdb->get_var( $wpdb->prepare(
						"
						 SELECT fee_low
						 FROM wp_eoiresponse
						 WHERE eoi_id = %d
						",
						$vendor_eoi_id
					) );
					$vendor_fee_high[$m] = $wpdb->get_var( $wpdb->prepare(
						"
						 SELECT fee_high
						 FROM wp_eoiresponse
						 WHERE eoi_id = %d
						",
						$vendor_eoi_id
					) );
					$eoi_complete[$m] = $wpdb->get_var( $wpdb->prepare(
						"
						 SELECT eoi_complete
						 FROM wp_eoiresponse
						 WHERE eoi_id = %d
						",
						$vendor_eoi_id
					) );
					$complete_date[$m] = $wpdb->get_var( $wpdb->prepare(
						"
						 SELECT complete_date
						 FROM wp_eoiresponse
						 WHERE eoi_id = %d
						",
						$vendor_eoi_id
					) );
					$complies_statuses = $wpdb->get_results(
						"
						 SELECT complies_status
						 FROM wp_functionalareas
						 WHERE eoi_id = $vendor_eoi_id"
					);
					$completed = 0;
					$partly_completed = 0;
					$uncompleted = 0;

					foreach($complies_statuses as $complies_status) {
						$eoi_complies_status = $complies_status->complies_status;
						if ($eoi_complies_status == "Out of the Box") {
							$completed++;
						} elseif ($eoi_complies_status == "May Need a Workaround") {
							$partly_completed++;
						} elseif ($eoi_complies_status == "Cannot Be Done") {
							$uncompleted++;
						}
					}
					$functionality_flag[$m]=$uncompleted;
					$out_of_box[$m] = $completed;
					$may_need_workaround[$m] = $partly_completed;
					$cannot_be_done[$m] = $uncompleted;
					$system_fit_score[$m] = $completed*3 + $partly_completed;
					$m++;
				}
		
				for ($j=0;$j<$m;$j++) {
					$rank = 1;
					for ($s=0;$s<$m;$s++) {
						if ($system_fit_score[$j]<$system_fit_score[$s]) {
							$rank++;
						}
					}
					$system_fit_rank[$j] = $rank;
				}
			?>

			<div>
				<div class="accordian-inner-container">
					<div id="poststuff">
						<table class="form-table" align="center" border="1">
							<tr>
								<th>Vendor Name</th>
								<th>System Name</th>
								<th>Estimated Timeline</th>
								<th>Estimated Fee Range</th>
								<th>Functionality Flag</th>
								<th>System Fit Rank</th>
								<th>System Fit Score</th>
								<th>Response Date</th>
								<th>Response Status</th>
							</tr>
							<?php
							for ($k=0;$k<$m;$k++) { 
								if ($eoi_complete[$k] != 0) { ?>
								<tr>
									<td><?php echo $vendor_company[$k] ?></td>
									<td><?php echo $vendor_system[$k] ?></td>
									<td><?php echo $vendor_timeline_low[$k] ?>&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo $vendor_timeline_high[$k] ?></td>
									<td><?php echo $vendor_fee_low[$k] ?>&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo $vendor_fee_high[$k] ?></td>
									<td><?php echo $functionality_flag[$k] ?></td>
									<td><?php echo $system_fit_rank[$k] ?></td>
									<td><?php echo "√".$out_of_box[$k]."    ?".$may_need_workaround[$k]."    ×".$cannot_be_done[$k] ?></td>
									<td><?php echo $complete_date[$k] ?></td>
									<td>
										<?php if ($eoi_complete[$k] == 1) {
												echo "Complete";
											} else if ($eoi_complete[$k] == 2) {
												echo "In Progress";
											}
										?>
									</td>
								</tr>
							<?php
								}
							}
							?>
						</table>
					</div>
				</div>
				</div>


				<?php
					$account = 1;
					$complete_statuses = $wpdb->get_results(
						"SELECT *
									FROM wp_eoiresponse
									WHERE project_holder_id = $loggedin_id
						"
					);
					foreach($complete_statuses as $complete_status) {
						if ($complete_status->eoi_complete ==1) {   
							$eoi_id_detail = $complete_status->eoi_id;
							$complies_statuses_details = $wpdb->get_results(
								"
								  SELECT complies_status
								  FROM wp_functionalareas
								  WHERE eoi_id = $eoi_id_detail"
							);
							$completed_detail = 0;
							$partly_completed_detail = 0;
							$uncompleted_detail = 0;
							$total_detail = 0;
							foreach($complies_statuses_details as $complies_statuses_detail) {
								$eoi_complies_detail = $complies_statuses_detail->complies_status;
								if ($eoi_complies_detail == "Out of the Box") {
									$completed_detail++;
								} elseif ($eoi_complies_detail == "May Need a Workaround") {
									$partly_completed_detail++;
								} elseif ($eoi_complies_detail == "Cannot Be Done") {
									$uncompleted_detail++;
								}
								$total_detail++;
							}
							$functionality_flag_detail=$uncompleted_detail;
							$system_fit_score_detail = $completed_detail*3 + $partly_completed_detail;

							$rank_detail = 1;
							for ($s=0;$s<$m;$s++) {
								if ($system_fit_score_detail<$system_fit_score[$s]) {
									$rank_detail++;
								}
							}
						?>
							<h5><span class="blue-arrow pull-right"></span>
								<?php	if ($complete_status->org_name != '') {
										echo $complete_status->org_name;
									} else {
										echo 'Vendor ' . $account . ' Details';
									} ?>
							</h5>
							<div>
								<div class="accordian-inner-container">
				
										<div id="poststuff">
										<table class="form-table" align="center" border="1">
											<tr>
												<th>Vendor Name</th>
												<th>System Name</th>
												<th>Estimated Timeline</th>
												<th>Estimated Fee Range</th>
												<th>Functionality Flag</th>
												<th>System Fit Rank</th>
												<th>System Fit Score</th>
												<th>Response Date</th>
												<th>Response Status</th>
											</tr>
											<tr>
												<td><?php echo $complete_status->org_name ?></td>
												<td><?php echo $complete_status->your_system_name ?></td>
												<td><?php echo $complete_status->timeline_low ?>&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo $complete_status->timeline_high ?></td>
												<td><?php echo $complete_status->fee_low ?>&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo $complete_status->fee_high ?></td>
												<td><?php echo $functionality_flag_detail ?></td>
												<td><?php echo $rank_detail ?></td>
												<td><?php echo "√".$completed_detail."    ?".$partly_completed_detail."    ×".$uncompleted_detail ?></td>
												<td><?php echo $complete_status->complete_date ?></td>
												<td><?php echo "Complete"; ?></td>
											</tr>
										</table>
									</div>
									<br />

									<label><h3 style="padding: 0 10px;">Vendor <?php echo $account ?> Requirements (<?php	echo $complete_status->org_name; ?>)</h3></label>
									<div id="poststuff">
									<table class="form-table" align="center" border="1">
										<tr>
											<th>Functional Area Name</th>
											<th>Compliance Indicator</th>
											<th>Vendor Notes</th>
											<th>Description of Function Area</th>
										</tr>
										<?php
											$eoi_complies_statuses = $wpdb->get_results(
											"
												SELECT complies_status, func_name, notes,func_area_inscope,func_category_id
												FROM wp_functionalareas
												WHERE eoi_id = $eoi_id_detail
											"
										);
										foreach($eoi_complies_statuses as $eoi_complies_status) {   
											if ($eoi_complies_status->func_area_inscope == 1) {
												$eoi_complies = $eoi_complies_status->complies_status;
												$eoi_func_name = $eoi_complies_status->func_name;
												$eoi_func_notes = $eoi_complies_status->notes;
												$eoi_func_id = $eoi_complies_status->func_category_id;
										   ?>
										<tr>
											<td><?php echo $eoi_func_name ?></td>
											<td><?php echo $eoi_complies ?></td>
											<td><?php echo $eoi_func_notes ?></td>
											<td>
												<form>
													<input type="hidden" name="hiddenaction" id="hiddenaction" value="eoi_result"/>
													 <input type="submit" class="compelete-btn green" name="eoi_popup" value="Description of <?php echo $eoi_func_name ?>" style="  padding: 8px 11px;color: #fff;border: 0;">
												</form>
											</td>
										</tr>
										<?php
												 }
											}
										?>
									</table>
								</div>
							</div>
						</div>
					<?php
						$account++;
						}
					}
				?>

			</div><!--  According-con end-->

		</div>
	</div>
</div>
