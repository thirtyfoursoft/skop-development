jQuery(function(){
var path = '/wp-content/plugins/skopes/frontend';
	/** toggle form help text**/

	$('.help-click').click(function() {
		id = $(this).attr('id');
		
		if($( "#"+id+'text' ).css('display') == 'block') {
			$('.help-text').slideUp("slow");
		} else {
			$('.help-text').slideUp("slow");
			$( "#"+id+'text' ).slideToggle( "slow");
		}
	});
	
	/*  validate user company */
	jQuery("#save_company").on("click",function(e)
	{ 
		e.preventDefault(); 
		var userID = jQuery('#userID').val();
		var totalfilled = 0;
		var totalVisibleFields = jQuery('#totalVisibleFields').val();
		
		var comp_ques2_on_off = jQuery('#comp_ques2_on_off').val();
		var organi_name = jQuery('#comp_ques2').val();
		
		if(comp_ques2_on_off == 1)
		{
		     if(organi_name != '')
			 {
                  totalfilled = totalfilled + 1;
			 }			 
		}

		var comp_ques3_on_off = jQuery('#comp_ques3_on_off').val();
		var street_addr = jQuery('#comp_ques3').val();
		
		if(comp_ques3_on_off == 1)
		{
		     if(street_addr != '')
			 {
                  totalfilled = totalfilled + 1;
			 }			 
		}
		
		var comp_ques4_on_off = jQuery('#comp_ques4_on_off').val();
		var city = jQuery('#comp_ques4').val();
		
		if(comp_ques4_on_off == 1)
		{
		     if(city != '')
			 {
                  totalfilled = totalfilled + 1;
			 }			 
		}
		
		var comp_ques5_on_off = jQuery('#comp_ques5_on_off').val();
		var state = jQuery('#comp_ques5').val();
		
		if(comp_ques5_on_off == 1)
		{
		     if(state != '')
			 {
                  totalfilled = totalfilled + 1;
			 }			 
		}
		
		var comp_ques6_on_off = jQuery('#comp_ques6_on_off').val();
		var zip_code = jQuery('#comp_ques6').val();
		
		if(comp_ques6_on_off == 1)
		{
		     if(zip_code != '')
			 {
                  totalfilled = totalfilled + 1;
			 }			 
		}
		
		var comp_ques7_on_off = jQuery('#comp_ques7_on_off').val();
		var org_mission = jQuery('#comp_ques7').val();
		
		if(comp_ques7_on_off == 1)
		{
		     if(org_mission != '')
			 {
                  totalfilled = totalfilled + 1;
			 }			 
		}
		
		var orgq8_on_off = jQuery('#orgq8_on_off').val();
		var org_goals = jQuery('#org_ques_8').val();
		
		if(orgq8_on_off == 1)
		{
		     if(org_goals != '')
			 {
                  totalfilled = totalfilled + 1;
			 }			 
		}
		
		var orgq9_on_off = jQuery('#orgq9_on_off').val();
		var hourly_rate = jQuery('#org_ques_9_state').val();
		
		if(orgq9_on_off == 1)
		{
		     if(hourly_rate != '')
			 {
                  totalfilled = totalfilled + 1;
			 }			 
		}
		
		var orgq10_on_off = jQuery('#orgq10_on_off').val();
		var org_ques_10_state = jQuery('#org_ques_10_state').val();
		
		if(orgq10_on_off == 1)
		{
		     if(org_ques_10_state != '')
			 {
                  totalfilled = totalfilled + 1;
			 }
		}

		var orgq11_on_off = jQuery('#orgq11_on_off').val();
		var org_ques_10_state = jQuery('#org_ques_10_state').val();

		if(orgq11_on_off == 1) {

			var selectedOption = $("input[name='org_ques_11[]']:checked").map(function(){
			  return $(this).val();
			}).get();

			if (selectedOption != '') {
				 totalfilled = totalfilled + 1;
			}
		}
		
		if(fnvalidate_company()==true)
		{ 
			   if(totalfilled == totalVisibleFields)
			   {
					 var org_save_status = 'Done,'+userID;
					 createCookie('org_save_status_'+userID,"",-1); 
					 createCookie('org_save_status_'+userID,org_save_status);  
					 
					 $.ajax({
							type: 'POST', 
							url: "/wp-content/plugins/skopes/frontend/org_filled_status.php",
							data: {userID: userID, status: 'filled'}, 
							beforeSend: function(){
								$('#abimg').html('<img src="/wp-content/plugins/skopes/frontend/images/loader.gif" />');				
							},
							success: function(data){
								jQuery("#frmcompany").submit();							
							}
						});
			   }
			   else
			   {
					 var org_save_status = 'NotDone,'+userID;
					 createCookie('org_save_status_'+userID,"",-1); 
					 createCookie('org_save_status_'+userID,org_save_status);
					 
					 $.ajax({
							type: 'POST', 
							url: "/wp-content/plugins/skopes/frontend/org_filled_status.php",
							data: {userID: userID, status: 'unfilled'}, 
							beforeSend: function(){
								$('#abimg').html('<img src="/wp-content/plugins/skopes/frontend/images/loader.gif" />');				
							},
							success: function(data){
								jQuery("#frmcompany").submit();								
							}
						});
			   } 
			   
				//jQuery("#frmcompany").submit();  
		}
		else
			return false; 
	});
	
	// remove existing logo
	jQuery("#del_logo").on('click', function(event){
		event.preventDefault();

		jQuery("#big_popup").hide();
		jQuery("#close_popup").hide();
		jQuery("#small_popup").html("");
		jQuery(".popupbox").addClass("smallpopup");
		jQuery("#small_popup").load(path+"/msg.php?type=del_logo",function(data){
			jQuery("#popup").fadeIn(500);
			jQuery(".overlay").fadeIn(500);
			jQuery("#small_popup").show();
			jQuery("#close_popup").show();
		});		
		
	});
	
	
	jQuery(document).on('click', '.yes-deletelogo', function (e) {
		e.preventDefault();
		jQuery("#small_popup").html("");
		jQuery("#small_popup").hide();
		jQuery("#close_popup").hide();
		jQuery(".popupbox").removeClass("smallpopup");
		
		jQuery.ajax({
			url:  path+"/savedata.php?action=deletelogo",
		}).done(function(data) {		
			jQuery(".loader").show();
			//jQuery(".overlay").fadeIn(500);
			location.reload();
		});

	});

})
/* function to validate user company */
function fnvalidate_company(){
	var float1 = /^\s*(\+|-)?((\d+(\.\d+)?)|(\.\d+))\s*$/;
	var hrRate = $("#org_ques_9_state").val();
	var comLogo = $("#com_logo").val();
	
	if (!float1.test(hrRate) || !$.isNumeric(hrRate)) {
	   $("#org9_error").html('Please enter numeric value.');
       return false;
    }
//	else if(!/(\.png|\.gif|\.jpg|\.jpeg)$/i.test(comLogo)) {
	else if(typeof comLogo != "undefined" && !/(\.png|\.gif|\.jpg|\.jpeg)$/i.test(comLogo)) {
        $("#com_logo_error").html('Please upload only logo image of .png, .jpg, .jpeg and .gif extensions.');
        return false;       
    }
	else{
	    $("#org9_error").html('');
		$("#com_logo_error").html('');
	}	
	
	return true;
}
 
function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}
