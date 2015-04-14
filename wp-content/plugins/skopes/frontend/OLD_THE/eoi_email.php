<?php
	/* Check the EOI vendor is enable/disable from admin  */
	$is_EOI_vendor = 	get_option("_eoi_vendor");
	if ($is_EOI_vendor != 1 ):
		wp_redirect(get_site_url());
		exit();
	endif;
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
							<span class="small btn-blue1" style="width: 117px; margin-right: 25px;margin-top: 15px;">					
								<a id="addVendorEmail" class="addnew" href="#">Add Email</a>
							</span>
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
<script type="text/javascript" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/js/validate.js"></script>
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
