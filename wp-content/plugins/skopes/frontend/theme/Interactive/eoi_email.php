<?php
	/* Check the EOI vendor is enable/disable from admin  */
	$is_EOI_vendor = 	get_option("_eoi_vendor");
	if ($is_EOI_vendor != 1 ):
		wp_redirect(get_site_url());
		exit();
	endif;
?>

<?php
/* ********
* 	Code for create tables for vendors in database

****** */
	global $wpdb;
	global $specdoc_db_version;

	$table_name = $wpdb->prefix."eoiresponse";

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

		$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
			  `eoi_id` int(255) NOT NULL AUTO_INCREMENT,
			  `user_id` int(255) NOT NULL,
			  `vendor_email` varchar(255) NOT NULL,
			  `project_holder_id` int(255) NOT NULL,
			  `invite_code` varchar(255) NOT NULL,
			  `org_name` varchar(255) NOT NULL,
			  `your_name` varchar(255) NOT NULL,
			  `your_system_name` varchar(255) NOT NULL,
			  `about_your_org` text,
			  `timeline_low` int(255) NOT NULL,
			  `timeline_high` int(255) NOT NULL,
			  `fee_low` int(255) NOT NULL,
			  `fee_high` int(255) NOT NULL,
			  `add_notes_cred` text,
			  `eoi_complete` int(255) NOT NULL DEFAULT '0',
			  `complete_date` varchar(255) NOT NULL,
			  `is_used` tinyint(2) NOT NULL,
			  PRIMARY KEY (`eoi_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
	}

	$table_name = $wpdb->prefix."functionalareas";

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) { 

		$sql = "CREATE TABLE IF NOT EXISTS `wp_functionalareas` (
			  `functional_areas_id` int(255) NOT NULL AUTO_INCREMENT,
			  `eoi_id` int(255) NOT NULL,
			  `func_name` varchar(255) NOT NULL,
			  `func_category_id` int(255) NOT NULL,
			  `complies_status` varchar(255) NOT NULL,
			  `notes` text,
			  `func_area_inscope` int(255) NOT NULL,
			  PRIMARY KEY (`functional_areas_id`),
			  KEY `eoi_id` (`eoi_id`),
			  KEY `func_category_id` (`func_category_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
	}

	$table_name = $wpdb->prefix."teammember";

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

		$sql = "CREATE TABLE IF NOT EXISTS `wp_teammember` (
			  `teamtable_id` int(255) NOT NULL AUTO_INCREMENT,
			  `eoi_id` int(255) NOT NULL,
			  `team_mem_name` varchar(255) NOT NULL,
			  `position` varchar(255) DEFAULT NULL,
			  `project_role` varchar(255) DEFAULT NULL,
			  `contact_details` text,
			  `comments` text,
			  PRIMARY KEY (`teamtable_id`),
			  KEY `eoi_id` (`eoi_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
?>
<div class="content-section clearfix">
    <div class="container">
        <div class="subpage-content">
            <div class="box">
            <div class="user-profileform">
               <h2 class="boxheading">SENDING E-MAIL</h2>
				<?php if (isset($_GET) && $_GET['msg'] =='updated') {
					echo '<div class="success" style="padding:15px;"> Emails sent successfully to all email address ..!</div>';
				} elseif (isset($_GET) && $_GET['ine'] !=''){ 
					echo '<div class="warning" style="padding:15px;color: red;">'. base64_decode($_GET['ine']) .' email address has already regirsted in skope as normal uses please use any another email adress. </div>';
				} ?>               
                <form class="eoi_email" id="eoi_email_form" method="post" onsubmit="" action="">
                    <input type="hidden" name="hiddenaction" id="hiddenaction" value="eoi_email"/>
                    <input type="submit" name="hbtnsave" id="hbtnsave" style="position: absolute; left: -9999px; width: 1px; height: 1px;"/>
                    
					 <h3 style="margin-left:10px;">Please Enter Vendors' E-mail Address</h3>
					 <div id="vendor_email_duplicate" class="error" style="padding: 0 20px 10px;text-align: center; width: 100%; display:none;">All email address should be unique.</div>
					<div class="upf-table">
					<?php for( $i =1; $i <=3; $i++ ) { ?>
						<div class="upf-raw">
							<div class="upf-cell column1">
								<label>Vendor's E-mail Address:</label>
							</div>
							<div class="upf-cell column2" style="width: 48%;">
		                        <input class="vendor_email" id="vendor_email_<?=$i; ?>" type="text" name="vendor_email[]" style="width: 347px">
							</div>
						</div>
					<?php } ?>
						<div class="teamMember_button">
							<a id="addVendorEmail" class="progress-btn addnew" href="#" style="float: right;">Add Email</a>
						</div>
						<div class="submit-buttons txt b1" style="text-align:center;" id="">
							<span class="btn-blue1 btn1 mrgn">
								 <input type="submit" name="submitEmails" value="Submit Vendors' E-mail Address" class="btn-inner1 fnt d1" style="color: #fff; height: 34px;">
							</span>
						</div>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/js/validate.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	$("#eoi_email_form").validate({
		rules: {
			"vendor_email[]": {
				required: true,
				email: true
			}
		},
		messages: {
			"vendor_email[]": {
				required: "E-mail required",
				email: "Invalid E-mail"
			}
		},

		submitHandler: function(form) {

			$('#vendor_email_duplicate').hide();
			var allEmails = [];
			$("input.vendor_email").each(function(){
				allEmails.push($(this).val());
			});

			var recipientsArray = allEmails.sort(); 

			var reportRecipientsDuplicate = [];
			for (var i = 0; i < recipientsArray.length - 1; i++) {
				if (recipientsArray[i + 1] == recipientsArray[i]) {
					reportRecipientsDuplicate.push(recipientsArray[i]);
				}
			}

			if (reportRecipientsDuplicate.length === 0) {
				form.submit();

			} else {
				$('#vendor_email_duplicate').show();
			}

		}
	});

    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".teamMember_button"); //Fields wrapper
    var add_button      = $("#addVendorEmail"); //Add button ID

    var x = 0; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            var y = parseInt(x)+parseInt(3);
           $( '<div class="upf-raw"><div class="upf-cell column1"><label>Vendor\'s E-mail Address:</label></div><div class="upf-cell column2" style="width: 48%;"><input class="vendor_email" type="text" name="vendor_email[]" id="vendor_email_'+y+'" style="width: 347px"><a class="removeclass" href="#">×</a></div></div>' ).insertBefore(wrapper);
        }
   });

	$('.upf-table').on("click",".removeclass", function(e){ //user click on remove text
	    e.preventDefault(); $(this).parent('div').parent('div').remove(); x--;
	})
    
});
</script>
