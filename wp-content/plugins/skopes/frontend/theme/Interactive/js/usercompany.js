jQuery(function(){
var path = '/wp-content/plugins/skopes/frontend/theme/Interactive';
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
/*
	jQuery("#save_company, #save_company_two").on("click",function(e) {
		e.preventDefault(); 
		var userID = jQuery('#userID').val();
		var totalfilled = 0;
		var totalVisibleFields = jQuery('#totalVisibleFields').val();
		
		var comp_ques2_on_off = jQuery('#comp_ques2_on_off').val();
		var organi_name = jQuery('#comp_ques2').val();
		
		if(comp_ques2_on_off == 1 && organi_name != '') {
			totalfilled = totalfilled + 1;
		}

		var comp_ques3_on_off = jQuery('#comp_ques3_on_off').val();
		var street_addr = jQuery('#comp_ques3').val();
		
		if(comp_ques3_on_off == 1 && street_addr != '') {
			totalfilled = totalfilled + 1;

		}
		
		var comp_ques4_on_off = jQuery('#comp_ques4_on_off').val();
		var city = jQuery('#comp_ques4').val();
		
		if(comp_ques4_on_off == 1 && city != '') {
            totalfilled = totalfilled + 1;
		}
		
		var comp_ques5_on_off = jQuery('#comp_ques5_on_off').val();
		var state = jQuery('#comp_ques5').val();
		
		if(comp_ques5_on_off == 1 && state != '') {
			totalfilled = totalfilled + 1;
		}
		
		var comp_ques6_on_off = jQuery('#comp_ques6_on_off').val();
		var zip_code = jQuery('#comp_ques6').val();
		
		if(comp_ques6_on_off == 1 && zip_code != '') {
			totalfilled = totalfilled + 1;
		}
		
		var comp_ques7_on_off = jQuery('#comp_ques7_on_off').val();
		var org_mission = jQuery('#comp_ques7').val();
		
		if(comp_ques7_on_off == 1 && org_mission != '') {
			totalfilled = totalfilled + 1;
		}
		
		var orgq8_on_off = jQuery('#orgq8_on_off').val();
		var org_goals = jQuery('#org_ques_8').val();
		
		if(orgq8_on_off == 1 && org_goals != '') {
	        totalfilled = totalfilled + 1;
		}
		
		var orgq9_on_off = jQuery('#orgq9_on_off').val();
		var hourly_rate = jQuery('#org_ques_9_state').val();
		
		if(orgq9_on_off == 1 && hourly_rate != '') {
             totalfilled = totalfilled + 1;
		}
		
		var orgq10_on_off = jQuery('#orgq10_on_off').val();
		var org_ques_10_state = jQuery('#org_ques_10_state').val();
		
		if(orgq10_on_off == 1 && org_ques_10_state != '') {
			totalfilled = totalfilled + 1;
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

		var comp_ques12_on_off = jQuery('#comp_ques12_on_off').val();
		var org_mission = jQuery('#comp_ques12').val();
		
		if(comp_ques12_on_off == 1 && org_mission != '') {
			totalfilled = totalfilled + 1;
		}

		var comp_ques13_on_off = jQuery('#comp_ques13_on_off').val();
		var org_mission = jQuery('#comp_ques13').val();
		
		if(comp_ques13_on_off == 1 && org_mission != '') {
			totalfilled = totalfilled + 1;
		}

		var comp_ques14_on_off = jQuery('#comp_ques14_on_off').val();
		var org_mission = jQuery('#comp_ques14').val();
		
		if(comp_ques14_on_off == 1 && org_mission != '') {
			totalfilled = totalfilled + 1;
		}

		var comp_ques15_on_off = jQuery('#comp_ques15_on_off').val();
		var org_mission = jQuery('#comp_ques15').val();
		
		if(comp_ques15_on_off == 1 && org_mission != '') {
			totalfilled = totalfilled + 1;
		}

		var comp_ques16_on_off = jQuery('#comp_ques16_on_off').val();
		var org_mission = jQuery('#comp_ques16').val();
		
		if(comp_ques16_on_off == 1 && org_mission != '') {
			totalfilled = totalfilled + 1;
		}		
		
//		alert('totalfilled='+totalfilled);
//		alert('totalVisibleFields='+totalVisibleFields);
		
		if(fnvalidate_company()==true) {
			   if(totalfilled == totalVisibleFields) {
					 var org_save_status = 'Done,'+userID;
					 createCookie('org_save_status_'+userID,"",-1); 
					 createCookie('org_save_status_'+userID,org_save_status);  
					 
					 $.ajax({
							type: 'POST', 
							url: "/wp-content/plugins/skopes/frontend/theme/Interactive/org_filled_status.php",
							data: {userID: userID, status: 'filled'}, 
							beforeSend: function(){
								$('#abimg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');				
							},
							success: function(data){
								jQuery("#frmcompany").submit();							
							}
						});
			   } else {
					 var org_save_status = 'NotDone,'+userID;
					 createCookie('org_save_status_'+userID,"",-1); 
					 createCookie('org_save_status_'+userID,org_save_status);
					 
					 $.ajax({
							type: 'POST', 
							url: "/wp-content/plugins/skopes/frontend/theme/Interactive/org_filled_status.php",
							data: {userID: userID, status: 'unfilled'}, 
							beforeSend: function(){
								$('#abimg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');				
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
*/
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

	jQuery('#deleted_logo').on('click', function(e) {
		e.preventDefault();
		jQuery('div.bydegaultLogo').hide();
		jQuery('input[name=logoval]').remove();
		jQuery('div.uploadNewOne').show();
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
/*
function fnvalidate_company() {
	var float1 = /^\s*(\+|-)?((\d+(\.\d+)?)|(\.\d+))\s*$/;
	var hrRate = $("#org_ques_9_state").val();
	var comLogo = $("#com_logo").val();
	var logoVal = $('input[name=logoval]').attr('data');

    $("#org9_error, #com_logo_error, #comp_ques12_error, #comp_ques13_error, #comp_ques14_error, #comp_ques15_error").html('');
    
	if (!float1.test(hrRate) || !$.isNumeric(hrRate)) {
	   $("#org9_error").html('Please enter numeric value.');
       return false;
    } else if(typeof comLogo != "undefined" && !/(\.png|\.gif|\.jpg|\.jpeg)$/i.test(comLogo) && (logoVal != 'bydefault' || logoVal == 'undefined') ) {
        $("#com_logo_error").html('Please upload only logo image of .png, .jpg, .jpeg and .gif extensions.');
        return false;       
    } else if ( $('#comp_ques12').val() == '' ) {
        $("#comp_ques12_error").html('This field is required');
        return false;  			
    } else if ( $('#comp_ques13').val() == '' ) {
        $("#comp_ques13_error").html('This field is required');
        return false;  			
    } else if ( $('#comp_ques14').val() == '' ) {
        $("#comp_ques14_error").html('This field is required');
        return false;  			
    } else if ( $('#comp_ques15').val() == '' ) {
        $("#comp_ques15_error").html('This field is required');
        return false;  			
    }
    
	else{
	    $("#org9_error, #com_logo_error, #comp_ques12_error, #comp_ques13_error, #comp_ques14_error, #comp_ques15_error").html('');
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
 */
/* *************************** Start new code *********************************** */

jQuery('#orgBasicInfo').on('submit', function(e) {

	e.preventDefault();
	jQuery("#com_logo_error, #comp_ques2_error, #comp_ques3_error, #comp_ques4_error, #comp_ques5_error").html('');	
	jQuery.ajax({
		type: $(this).attr('method'), // <-- get method of form
		url:  $(this).attr('action'), // <-- get action of form
		data: $(this).serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
		beforeSend: function() {
		    $('#orgbasicmsg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');
		},
		success: function(data) {
			$('#orgbasicmsg').hide();
			$('#orgbasicresult').html('Information Saved');
			$('#orgbasicresult').hide().fadeIn(800).delay(3000).fadeOut(800);
			$('#orgbasicmsg').html('');

			if(data == 1){
				$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete');
				$('#ui-accordion-1-header-0 span:nth-child(4)').html('In Progress');
				$("#saveOrgBasicInfo").removeClass("green").addClass("grey1");
			}

		}
	});
});

jQuery('#saveOrgBasicInfo').on('click', function(event) {
	 event.preventDefault();
	 
	if(fnvalidate_saveOrgBasicInfo()==false ) {
		return false;
	} else {
			jQuery.ajax({
				type: $('#orgBasicInfo').attr('method'), // <-- get method of form
				url:  $('#orgBasicInfo').attr('action'), // <-- get action of form
				data: $('#orgBasicInfo').serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
				beforeSend: function() {
					$('#orgbasicmsg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');
				},
				success: function(data) {
					$('#orgbasicmsg').hide();
					$('#orgbasicresult').html('Information Saved');
					$('#orgbasicresult').hide().fadeIn(800).delay(3000).fadeOut(800);
					$('#orgbasicmsg').html('');

					if(data == 1){

						$.ajax({ 
							type: "POST",
							url: "/wp-content/plugins/skopes/frontend/theme/Interactive/compltechk.php",
							data: { query: 'orgBasicInfo' },
							beforeSend: function(){
								$('#orgbasicmsg').show();
								$('#orgbasicmsg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');	 			
							},
							success: function(data){
								$('#orgbasicmsg').hide();
								$('#orgbasicresult').html('Information Saved');
								$('#orgbasicresult').hide().fadeIn(800).delay(3000).fadeOut(800);
								$('#orgbasicmsg').html('');
									  
								if(data == 2) {

									$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred incomplete').addClass('complete');
									$('#ui-accordion-1-header-0 span:nth-child(4)').html('Complete');
									$("#saveOrgBasicInfo").removeClass("grey1").addClass("green");

								} else if(data == 1) {
									$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete');
									$('#ui-accordion-1-header-0 span:nth-child(4)').html('In Progress');
									$("#saveOrgBasicInfo").removeClass("green").addClass("grey1");
								}
							}
						});							
					}

				}
			});
	}
	 
});

jQuery('#orgContaceAndTeam').on('submit', function(e) {
	e.preventDefault();
	jQuery("#comp_ques12_error, #comp_ques13_error, #comp_ques14_error, #comp_ques15_error, #comp_ques16_error, #org9_error").html('');

	jQuery.ajax({
		type: $(this).attr('method'), // <-- get method of form
		url:  $(this).attr('action'), // <-- get action of form
		data: $(this).serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
		beforeSend: function() {
		    $('#orgcontactmsg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');
		},
		success: function(data) {
			$('#orgcontactmsg').hide();
			$('#orgcontactresult').html('Information Saved');
			$('#orgcontactresult').hide().fadeIn(800).delay(3000).fadeOut(800);
			$('#orgcontactmsg').html('');

			if(data == 1) {
				$('#ui-accordion-1-header-1 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete');
				$('#ui-accordion-1-header-1 span:nth-child(4)').html('In Progress');
				$("#saveOrgContactInfo").removeClass("green").addClass("grey1");
			}

		}
	});	
});

jQuery('#saveOrgContactInfo').on('click', function(event) {
	 event.preventDefault();

		if(fnvalidate_saveOrgContactInfo()==false ) {
			return false;
		} else { 

			jQuery.ajax({
				type: $('#orgContaceAndTeam').attr('method'), // <-- get method of form
				url:  $('#orgContaceAndTeam').attr('action'), // <-- get action of form
				data: $('#orgContaceAndTeam').serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
				beforeSend: function() {
					$('#orgcontactmsg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');
				},
				success: function(data) {
					$('#orgcontactmsg').hide();
					$('#orgcontactresult').html('Information Saved');
					$('#orgcontactresult').hide().fadeIn(800).delay(3000).fadeOut(800);
					$('#orgcontactmsg').html('');

					if(data == 1) {

							$.ajax({ 
								type: "POST",
								url: "/wp-content/plugins/skopes/frontend/theme/Interactive/compltechk.php",
								data: { query: 'orgContactInfo' },
								beforeSend: function(){
									$('#orgcontactmsg').show();
									$('#orgcontactmsg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');	 			
								},
								success: function(data){
									$('#orgcontactmsg').hide();
									$('#orgcontactresult').html('Information Saved');
									$('#orgcontactresult').hide().fadeIn(800).delay(3000).fadeOut(800);
									$('#orgcontactmsg').html('');
										  
									if(data == 2) {

										$('#ui-accordion-1-header-1 span:nth-child(4)').removeClass('notstred incomplete').addClass('complete');
										$('#ui-accordion-1-header-1 span:nth-child(4)').html('Complete');
										$("#saveOrgContactInfo").removeClass("grey1").addClass("green");

									} else if(data == 1) {
										$('#ui-accordion-1-header-1 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete');
										$('#ui-accordion-1-header-1 span:nth-child(4)').html('In Progress');
										$("#saveOrgContactInfo").removeClass("green").addClass("grey1");
									}
								}
							});	
					}

				}
			});
	
		}
});


jQuery('#orgMissionInfo').on('submit', function(e) {
	e.preventDefault();
	jQuery("#ogs_error, #org8_error, #org10_error,#org11_error").html('');	
	jQuery.ajax({
		type: $(this).attr('method'), // <-- get method of form
		url:  $(this).attr('action'), // <-- get action of form
		data: $(this).serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
		beforeSend: function() {
		    $('#orgMissionmsg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');
		},
		success: function(data) {
			$('#orgMissionmsg').hide();
			$('#orgMissionresult').html('Information Saved');
			$('#orgMissionresult').hide().fadeIn(800).delay(3000).fadeOut(800);
			$('#orgMissionmsg').html('');

			if(data == 1) {
				$('#ui-accordion-1-header-2 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete');
				$('#ui-accordion-1-header-2 span:nth-child(4)').html('In Progress');
				$("#saveOrgMissionInfo").removeClass("green").addClass("grey1");
			}

		}
	});
	
});

jQuery('#saveOrgMissionInfo').on('click', function(event) {
		event.preventDefault();

		if(fnvalidate_saveOrgMissionInfo()==false ) {
			return false;
		} else {
			jQuery.ajax({
				type: $('#orgMissionInfo').attr('method'), // <-- get method of form
				url:  $('#orgMissionInfo').attr('action'), // <-- get action of form
				data: $('#orgMissionInfo').serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
				beforeSend: function() {
					$('#orgMissionmsg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');
				},
				success: function(data) {
					$('#orgMissionmsg').hide();
					$('#orgMissionresult').html('Information Saved');
					$('#orgMissionresult').hide().fadeIn(800).delay(3000).fadeOut(800);
					$('#orgMissionmsg').html('');

					if(data == 1) {

						$.ajax({ 
							type: "POST",
							url: "/wp-content/plugins/skopes/frontend/theme/Interactive/compltechk.php",
							data: { query: 'orgMissionInfo' },
							beforeSend: function(){
								$('#orgMissionmsg').show();
								$('#orgMissionmsg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');	 			
							},
							success: function(data){
								$('#orgMissionmsg').hide();
								$('#orgMissionresult').html('Information Saved');
								$('#orgMissionresult').hide().fadeIn(800).delay(3000).fadeOut(800);
								$('#orgMissionmsg').html('');
									  
								if(data == 2) {

									$('#ui-accordion-1-header-2 span:nth-child(4)').removeClass('notstred incomplete').addClass('complete');
									$('#ui-accordion-1-header-2 span:nth-child(4)').html('Complete');
									$("#saveOrgMissionInfo").removeClass("grey1").addClass("green");

								} else if(data == 1) {
									$('#ui-accordion-1-header-2 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete');
									$('#ui-accordion-1-header-2 span:nth-child(4)').html('In Progress');
									$("#saveOrgMissionInfo").removeClass("green").addClass("grey1");
								}
							}
						});							
					}

				}
			});
	
		}
});

function fnvalidate_saveOrgMissionInfo() {

	jQuery("#ogs_error, #org8_error, #org10_error,#org11_error").html('');

	var selectedOption = $("input[name='org_ques_11[]']:checked").map(function(){
	  return $(this).val();
	}).get();
			
	if (jQuery('#comp_ques7').val() == '') {
	    jQuery("#ogs_error").html('This field is required');
	    return false;
	} else if( jQuery('#org_ques_8').val() == '' ) {

	    jQuery("#org8_error").html('This field is required');
	    return false;
	} else if( jQuery('#org_ques_10').val() == '' ) {

	    jQuery("#org10_error").html('This field is required');
	    return false;
	} else if (selectedOption == '') {
		jQuery("#org11_error").html('This field is required');
	    return false;
	} else {
		jQuery("#ogs_error, #org8_error, #org10_error,#org11_error").html('');
    }

	return true;
	
}

function fnvalidate_saveOrgContactInfo() {

	var float1 = /^\s*(\+|-)?((\d+(\.\d+)?)|(\.\d+))\s*$/;
	var hrRate = $("#org_ques_9_state").val();

	jQuery("#comp_ques12_error, #comp_ques13_error, #comp_ques14_error, #comp_ques15_error, #comp_ques16_error, #org9_error").html('');
	
	if (jQuery('#comp_ques12').val() == '') {
	    jQuery("#comp_ques12_error").html('This field is required');
	    return false;
	} else if( jQuery('#comp_ques13').val() == '' ) {

	    jQuery("#comp_ques13_error").html('This field is required');
	    return false;
	} else if( jQuery('#comp_ques14').val() == '' ) {

	    jQuery("#comp_ques14_error").html('This field is required');
	    return false;
	} else if( jQuery('#comp_ques15').val() == '' ) {

	    jQuery("#comp_ques15_error").html('This field is required');
	    return false;
	} else if( jQuery('#comp_ques16').val() == '' ) {

	    jQuery("#comp_ques16_error").html('This field is required');
	    return false;
	} else if (!float1.test(hrRate) || !$.isNumeric(hrRate)) {
	   $("#org9_error").html('Please enter numeric value.');
       return false;
    } else {
		jQuery("#comp_ques12_error, #comp_ques13_error, #comp_ques14_error, #comp_ques15_error, #comp_ques16_error, #org9_error").html('');
    }

	return true;
}

function fnvalidate_saveOrgBasicInfo() {

	var float1 = /^\s*(\+|-)?((\d+(\.\d+)?)|(\.\d+))\s*$/;
	var comLogo = $("#com_logo").val();
	var logoVal = $('input[name=logoval]').attr('data');

	jQuery("#com_logo_error, #comp_ques2_error, #comp_ques3_error, #comp_ques4_error, #comp_ques5_error").html('');
	
	 if(typeof comLogo != "undefined" && !/(\.png|\.gif|\.jpg|\.jpeg)$/i.test(comLogo) && (logoVal != 'bydefault' || logoVal == 'undefined') ) {
	    jQuery("#com_logo_error").html('Please upload only logo image of .png, .jpg, .jpeg and .gif extensions.');
	    return false;
	} else if ( jQuery('#comp_ques2').val() == '' ) {
	    jQuery("#comp_ques2_error").html('This field is required');
	    return false;
	} else if ( jQuery('#comp_ques3').val() == '' ) {
	    jQuery("#comp_ques3_error").html('This field is required');
	    return false;
	} else if ( jQuery('#comp_ques4').val() == '' ) {
	    jQuery("#comp_ques4_error").html('This field is required');
	    return false;
	} else if ( jQuery('#comp_ques5').val() == '' ) {
	    jQuery("#comp_ques5_error").html('This field is required');
	    return false;
	}

	else{
		jQuery("#com_logo_error, #comp_ques2_error, #comp_ques3_error, #comp_ques4_error, #comp_ques5_error").html('');
	}

	return true;
}

var blank="";
function readURL(input) {
	//$("#x").show();
	$("#previewPane").show();  
	//$("#imageuploadType").hide(); 
		if (input.files && input.files[0]) {
			var reader = new FileReader(); 
			reader.onload = function (e) {
				$('#img_prev')
				.attr('src', e.target.result)
				.width(80).height(60);
			};

			reader.readAsDataURL(input.files[0]);
		} else { 
		    var img = input.value; 
			$('#img_prev').attr('src',img).width(80);
			$('#previewPane').css("display", "block !important");
		}
		$("#x").show().css("margin-right","0px");
}

function uploadFiles() {
	var file_data = $('#com_logo').prop('files')[0];
    var form_data = new FormData();                  
    form_data.append('file', file_data);
    form_data.append('formNameNew', 'uploadLogo');
	$.ajax( {
      url: '/wp-content/plugins/skopes/frontend/theme/Interactive/organizations.php',
      type: 'POST',
      data: form_data,
      processData: false,
      contentType: false
    } );
}

$('#com_logo').bind('change', function() {
	     var file = this.files[0]; 
		 filename = file.name;	  
		 var ext = filename.split('.').pop(); 
		 var categories = [ 
				"gif", 
				"jpg", 
				"jpeg", 
				"gif",
				"png",
				"bmp",			
			];
		var found = jQuery.inArray(ext, categories); //alert(found);
		if (found > -1) {
		    readURL(this);
			$("#company_logo_error").html('');
			uploadFiles();
		} else {
			$("#company_logo_error").html("please upload only logo image of .png,.jpg,.jpeg,.gif extensions.").hide().fadeIn(800).delay(2000).fadeOut(800);
		}

});
