<?php
	/* Check the EOI vendor is enable/disable from admin  */
	$is_EOI_vendor = 	get_option("_eoi_vendor");
	if ($is_EOI_vendor != 1 ):
		wp_redirect(get_site_url());
		exit();
	endif;

	$currentUser = wp_get_current_user();
	if ( isset($_GET) && ($_GET['VD'] != '') ) {
		$vendorData = unserialize(base64_decode($_GET['VD']));
		$vandorEmail = $vendorData['email'];
		$inivtationCode = $vendorData['inivtationCode'];

		if ( $vandorEmail != trim($currentUser->user_email) ) {
			wp_redirect(get_site_url());
			exit();
		}
	}

	global $wpdb;
	$loggedin_id = get_current_user_id();
?>
<div class="content-section clearfix">
    <div class="container">
        <div class="subpage-content">
            <div class="box">
                <div class="user-profileform">
				<h2 class="boxheading">YOUR EOI RESPONSE</h2>
				<?php if (isset($_GET) && $_GET['msg'] =='updated') {
					echo '<div class="success" style="padding:15px;"> Form has been connected successfully ..!</div>';
				} elseif (isset($_GET) && $_GET['msg'] =='error') {
					echo '<div class="warning" style="padding:15px;color: red;">Code is either invalid or used! </div>';
				} ?>
                <form class="eoi_form" id="eoi_access" method="post" onsubmit="return eoi_access()"><!--onsubmit uses for the validity-->
                    <input type="hidden" name="hiddenaction" id="hiddenaction" value="eoi_access"/>
                    <input type="submit" name="hbtnsave" id="hbtnsave" style="position: absolute; left: -9999px; width: 1px; height: 1px;"/>

                    <div class="upf-table">
						<div class="upf-raw">
							<div class="upf-cell column1">
								<label>Please Enter Your Invitation Code:</label>
							</div>
							<div class="upf-cell column2" style="width: 48%;">
                                <input type="text" id="invitation_code" name="invitation_code" style="width: 347px" value="<?=$inivtationCode; ?>">
                                <input type="hidden" id="invitation_email" name="invitation_email" style="width: 347px" value="<?=$vandorEmail; ?>">
							</div>
						</div>
						<div class="submit-buttons txt b1" style="text-align:center;" id="">
							<span class="btn-blue1 btn1 mrgn">
								 <input type="submit" name="submit" value="submit" class="btn-inner1 fnt d1" style="color: #fff; height: 34px;">
							</span>
						</div>
                    </div>
                </form>
                <label>
                    <?php
                    /*
                    Print "Your EOI Response List" and show the list.
                    */
                    ?>
                    <br />
                    <h3 style="margin-left:40px;">Your EOI Response List</h3>
                    <p style="margin-left:40px;">Please choose which EOI Response Form you want to access.<br /></p>
                </label>
                <table style="margin-left:40px;width:720px" class="form-table">
                    <tr>
                        <th>Company Name</th>
                        <th>Project Name</b></th>
                        <th>Leader Name</b></th>
                        <th>EOI Response Form Link</b></th>
                    </tr>
                    <?php
                    /*
                     Get all the EOI response form this vendor could access
                    */
                    $eoi_access_ids = $wpdb->get_results(
                        "SELECT eoi_id, project_holder_id
                         FROM wp_eoiresponse
                         WHERE user_id = $loggedin_id"
                    );

                    foreach ($eoi_access_ids as $eoi_access_id) {
                        $eoi_id = $eoi_access_id ->eoi_id;
                        $project_holder_id = $eoi_access_id ->project_holder_id;
                    ?>
                     <tr>
                     <!--  get company's name  -->
                     <td><?php echo get_user_meta($project_holder_id, "comp_ques2", true); ?></td>
                     <!--  get project's name  -->
                     <td><?php echo get_user_meta($project_holder_id,"pro_ques5",true); ?></b></td>
                     <!--  get leader's name  -->
                      <td><?php   $leader_name = $wpdb->get_var( $wpdb->prepare(
                         "
                         SELECT member_name
                         FROM wp_specdoc_userteaminfo
                         WHERE userid = %d
                         AND member_type = %s
                         ",
                          $project_holder_id, "tl") );
                          echo $leader_name; ?></b></td>

                         <!--  EOI Response Form Link -->
                         <td><a href="?act=res_form&id=<?php echo $eoi_id ?>" id="<?php echo $eoi_id ?>" class="btn btn-mini btn-blue" style="display: block; padding: 7px 14px; width: 100px;">EOI Response Form</a></b></td>
                     </tr>
                    <?php } ?>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$("#eoi_access").validate({
		rules: {
			invitation_code:	"required",
		},

		submitHandler: function(form) {
			form.submit();
		}
	});
});
//--></script>
