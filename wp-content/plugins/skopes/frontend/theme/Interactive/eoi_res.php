<style>
#eoi_form input[type="text"] {
    width: 80%;
}
</style>
<?php
	if (isset($_GET) && $_GET['id'] == '') {
		wp_redirect($page."?act=eoi_res");
		exit;
	}
?>
<div class="content-section clearfix">
	<div class="container">
		<div class="subpage-content">
			<div class="box">
				<div class="eoi_response">
					<h2 class="boxheading">YOUR EOI RESPONSE</h2>
					<?php
						global $wpdb;
						$eoi_ids = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "eoiresponse WHERE user_id = '" . $loggedin_id . "' AND eoi_id = '" . $_GET['id'] . "'");
						if ($eoi_ids == 0):
							echo '<label><h3 style="margin-left:40px;">You have NO EOI Response.</h3>';
						else: 
							$eoi_complete = $wpdb->get_var("SELECT eoi_complete FROM " . $wpdb->prefix . "eoiresponse WHERE eoi_id = '" . $_GET['id'] . "' ");
							if ($eoi_complete == 2)
								$complete_colour =  'grey1';
							else
								$complete_colour = 'green1';

							$project_holder_id = $wpdb->get_var("SELECT project_holder_id FROM " . $wpdb->prefix . "eoiresponse WHERE eoi_id = '" . $_GET['id'] . "' ");
							$leader_name = $wpdb->get_var("SELECT member_name FROM " . $wpdb->prefix . "specdoc_userteaminfo WHERE userid = '" . $project_holder_id . "' AND member_type = 'tl'");

							$data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "eoiresponse WHERE project_holder_id = '" . $project_holder_id . "' AND eoi_id = '" . $_GET['id'] . "'");

							$compliesStatus = array(
								'Out of the Box' 	=> 'Out of the Box',
								'May Need a Workaround'		=> 'May Need a Workaround',
								'Cannot Be Done'		=> 'Cannot Be Done'
							);

							$func_areas = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "functionalareas WHERE eoi_id = $eoi_res_id AND func_area_inscope = 1");
						?>
							<form class="eoi_form" id="eoi_form" method="post">
							<?php if (isset($_GET) && $_GET['msg'] !='') {
								echo '<div class="success" style="padding: 15px 15px 0;"> Your information has been saved ..!</div>';
							} ?>
							<input type="hidden" name="hiddenaction" id="hiddenaction" value="eoi_response"/>
							<input type="submit" name="hbtnsave" id="hbtnsave" style="position: absolute; left: -9999px; width: 1px; height: 1px;"/>
							<!--<input name='eoi_complete' type='hidden' id='eoi_complete' value="<?php $eoi_complete ?>" />-->
							<table style="width:96%; margin: 0 auto;" class="form-table">
								<tr>
									<th><h3 style="">Project Name</h3></th>
									<th><h3 style="">Project Leader</h3></th>
									<td><h3 style="">Company Name</h3></td>
								</tr>
								<tr>
									<td style="padding:0"><?php  echo get_user_meta($project_holder_id,"pro_ques5",true); ?></td>
									<td style="padding:0"><?=$leader_name; ?></td>
									<td style="padding:0"><?php echo get_user_meta($project_holder_id, "comp_ques2", true); ?></td>
								</tr>
								<tr>
									<th>Your Organisation</th>
									<th>Your Name</th>
									<th>Your System Name</th>
								</tr>
								<tr>
									<td><input class="eoi_title" style="" type="text" value="<? echo strip_tags(stripslashes($data->org_name)); ?>" id="org_name" name="org_name"></td>
									<td><input class="eoi_title" style="" type="text" value="<? echo strip_tags(stripslashes($data->your_name)); ?>" id="your_name" name="your_name"></td>
									<td><input class="eoi_title" style="" type="text" value="<? echo strip_tags(stripslashes($data->your_system_name)); ?>" id="your_system_name" name="your_system_name"></td>
								</tr>
								<tr>
									<td colspan="3"><h3 style="">About Your Organisation</h3></td>
								</tr>
								<tr>
									<td colspan="3"><p>Please provide a brief overview of your organisation covering its history, historical and current projects, locations and any other information you feel it relevant. This is provided for information only, there will be further opportunity to present your organisations credentials in the next step</p>
									</td>
								</tr>
								<tr>
									<td colspan="3"><textarea style="width: 100%;" id="about_your_org" name="about_your_org"><?=$data->about_your_org; ?></textarea></td>
								</tr>
								<tr>
									<td colspan="3"><h3 style="">Introduce Your Team</h3></td>
								</tr>
								<tr>
									<td colspan="3"><p>Please list your key team members including identifying those that would be involved with the project and their roles</p></td>
								</tr>
								<tr>
									<td colspan="3">
									<table class="form-table" style=" margin: 0 auto; width: 96%;">
										<tr>
											<td><b>Name</b></td>
											<td><b>Position</b></td>
											<td><b>Project Role</b></td>
											<td><b>Contact Details</b></td>
											<td><b>Comments</b></td>
										</tr>
										<?php
											$team_members = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix ."teammember WHERE eoi_id = '" . $_GET['id'] . "'");
										?>
										<?php foreach( $team_members as $team_member ) { ?>
											<tr>
												<td style="padding:0"><input type="text" value="<?php echo $team_member->team_mem_name; ?>" id="name<?=$team_member->teamtable_id; ?>" name="members[<?=$team_member->teamtable_id; ?>][name]"></td>
												<td style="padding:0"><input type="text" value="<?php echo $team_member->position; ?>" id="position<?=$team_member->teamtable_id; ?>" name="members[<?=$team_member->teamtable_id; ?>][position]"></td>
												<td style="padding:0"><input type="text" value="<?php echo $team_member->project_role; ?>" id="project_role<?=$team_member->teamtable_id; ?>" name="members[<?=$team_member->teamtable_id; ?>][project_role]"></td>
												<td style="padding:0"><input type="text" value="<?php echo $team_member->contact_details; ?>" id="contact_details<?=$team_member->teamtable_id; ?>" name="members[<?=$team_member->teamtable_id; ?>][contact_details]"></td>
												<td style="padding:0"><input type="text" value="<?php echo $team_member->comments; ?>" id="comments<?=$team_member->teamtable_id; ?>" name="members[<?=$team_member->teamtable_id; ?>][comments]"></td>
											</tr>
									 	<?php } ?>
									</table>
									</td>
								</tr>
								<tr>
									<td colspan="3"><h3 style="">Functional Areas</h3></td>
								</tr>
								<tr>
									<td colspan="3"><p>Please indicate how many of the functional areas your system can facilitate (and how) using the table below.</p>
									</td>
								</tr>
								<tr>
									<th>Functional Areas<br /></th>
									<th>Complies Status<br /></th>
									<th>Notes<br /></th>
								</tr>
							  <?php foreach ($func_areas as $func_area) { ?>
								<tr>
									<td><?=$func_area->func_name; ?></td>
									<td>
										<select class="btn-select" name="functions[<?=$func_area->functional_areas_id;  ?>][status]" id="<?php echo $func_area->func_category_id ?>">
										<?php foreach($compliesStatus as $k => $v ) { ?>
											<?php if ($func_area->complies_status == $k) { ?>
												<option value="<?=$k; ?>" selected="selected"><?=$v; ?></option>
											<?php } else { ?>
												<option value="<?=$k; ?>"><?=$v; ?></option>
											<?php } ?>
										<?php } ?>
										</select>
									</td>
									<td><input class="func-notes" type="text" id="<?php echo $eoi_res_id.$func_area->func_category_id ?>" name="functions[<?=$func_area->functional_areas_id;  ?>][notes]" value="<?php echo $func_area->notes ?>"></td>
								</tr>							  	
							  <?php } ?>

								<tr>
									<td colspan="3">
										<table class="form-table">
											<tr>
												<td colspan="4"><h3 style="">Estimated Timeline</h3></td>
												<td colspan="4"><h3 style="">Estimated Fee Range</h3></td>
											</tr>
											<tr>
												<td><input class="eoi_timeline" style="" type="text" value="<?=$data->timeline_low; ?>" id="timeline_low" name="timeline_low"></td>
												<td>Weeks&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td><input class="eoi_timeline" type="text" value="<?=$data->timeline_high; ?>" id="timeline_high" name="timeline_high"></td>
												<td>&nbsp;Weeks&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
												<td>$&nbsp;</td>
												<td><input class="eoi_timeline" type="text" value="<?=$data->fee_low; ?>" id="fee_low" name="fee_low"></td>
												<td>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;$&nbsp;</td>
												<td><input class="eoi_timeline" type="text" value="<?=$data->fee_high; ?>" id="fee_high" name="fee_high"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="3"><h3 style="">Additional Notes & Credentials</h3></td>
								</tr>
								<tr>
									<td colspan="3"><p>Please provide any additional information that you feel will best present your case for winning the selection process and delivering our project.</p>
									</td>
								</tr>
								<tr>
									<td colspan="3"><textarea style="width: 100%;" id="add_notes_cred" name="add_notes_cred"><? echo stripslashes($data->add_notes_cred); ?></textarea></td>
								</tr>
								<tr>
									<td colspan="3" style="text-align: center;">
										<input type="hidden" name="eoi_res_id" id="eoi_res_id" value="<?php echo $eoi_res_id ?>">
										<input class="btn" type="submit" name="eoi_submit" value="Save Update">
										<input class="btn btn-<?php echo $complete_colour;?>"  style="padding: 4px 10px;" type="submit" name="eoi_submit" value="Complete">
									</td>
								<tr>
							</table>

					<?php	endif;
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$("#eoi_form").validate({

		rules: {
			org_name:	"required",
			your_name: "required",
			your_system_name: "required",
			about_your_org: "required",
			timeline_low: {
         		required: true,
               number: true
			},
			timeline_high: {
         		required: true,
               number: true
			},
			fee_low: {
         		required: true,
               number: true
			},
			fee_high: {
         		required: true,
               number: true
			},
			add_notes_cred: "required",
		},

		messages: {
			org_name: "Please enter your organization name",
			your_name: "Please enter your name",
			your_system_name: "Please enter your system name",
			about_your_org: "Please enter info about your organization",
		},
	
		submitHandler: function(form) {
			form.submit();
		}
	});
});

//--></script>
