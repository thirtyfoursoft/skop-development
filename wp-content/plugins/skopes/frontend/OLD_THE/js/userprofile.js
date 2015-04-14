jQuery(function(){

	/* display textbox when user select OTHER in position and role */
	jQuery("#position").change(function()
	{
	   if(jQuery.trim(jQuery("#position").val())=="Other")
		   jQuery("#placediv").show();
	   else
	   {
			jQuery("#other_position").val("");
			jQuery("#placediv").hide();
	   }
	});

	/* set value in hidden when user click on daily report btn in user profile */
	jQuery("#daily_report_btn").live("click",function(e)
	{
		e.preventDefault();
		if(jQuery("#daily_report").val()=="0")
		{
			jQuery("#daily_report").val("1");
			jQuery("#daily_report_btn .btn-inner").css("background","#8d63a2");
			jQuery("#daily_report_btn").css("background","linear-gradient(to bottom, rgb(141, 99, 162) 0%, rgb(116, 84, 132) 100%) repeat scroll 0 0 transparent");
		}
		else if(jQuery("#daily_report").val()=="1")
		{
			jQuery("#daily_report").val("0");
			jQuery("#daily_report_btn .btn-inner").css("background","#85C8EE");
			jQuery("#daily_report_btn").css("background","linear-gradient(to bottom, rgb(133, 200, 238) 0%, rgb(81, 133, 166) 100%) repeat scroll 0 0 transparent");
		}
	});

	/* set value in hidden when user click on track report btn in user profile */
	jQuery("#track_report_btn").die("click").live("click",function(e)
	{
		e.preventDefault();
		if(jQuery("#track_report").val()=="0")
		{
			jQuery("#track_report").val("1");
			jQuery("#track_report_btn .btn-inner").css("background","#8d63a2");
			jQuery("#track_report_btn").css("background","linear-gradient(to bottom, rgb(141, 99, 162) 0%, rgb(116, 84, 132) 100%) repeat scroll 0 0 transparent");

		}
		else if(jQuery("#track_report").val()=="1")
		{
			jQuery("#track_report").val("0");
			jQuery("#track_report_btn .btn-inner").css("background","#85C8EE");
			jQuery("#track_report_btn").css("background","linear-gradient(to bottom, rgb(133, 200, 238) 0%, rgb(81, 133, 166) 100%) repeat scroll 0 0 transparent");

		}
	});

	/* set hover effect when form is completed */
	jQuery("#btnsection").live("hover",function()
	{

		if(fnvalidate_profile_hover()==true)
		{
			jQuery("#btnsection").addClass("upf-buttonsection-hover");
			jQuery("#btnsection").removeClass("upf-buttonsection");
		}
		else
		{
			jQuery("#btnsection").addClass("upf-buttonsection");
			jQuery("#btnsection").removeClass("upf-buttonsection-hover");
		}
	});

	jQuery(".upf-cell input[type='text'],.upf-cell textarea").live("keyup",function(){
		jQuery("#btnsection").trigger("mouseenter");
	});

	jQuery(".upf-cell input[type='text'],.upf-cell textarea").live("focus",function(){
		jQuery("#btnsection").trigger("mouseenter");
	});

	jQuery(".upf-cell select").live("change",function(){
		jQuery("#btnsection").trigger("mouseenter");
	});

	/* set mouse out effect on user profile */
	

	/*  validate user profile */
	jQuery("#save_profile").live("click",function(e)
	{
		e.preventDefault();
		if(fnvalidate_profile()==true)
			jQuery("#frmprofile").submit();
		else
			return false;
	});

	jQuery("select").selectbox();
	jQuery("#btnsection").trigger("mouseenter");

});

/** validate email address**/
function validateEmail(sEmail) {
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test(sEmail)) {
        return true;
    }
    else {
        return false;
    }
}

/** check email existance **/
function checkemail(email){
	$.ajax({
			type: "POST",
			url: "/wp-content/plugins/skopes/checkuserdata.php",
			data: {'action': 'chkemail', 'email': email},
			success: function (result) {
				alert(result);
				if(result == 'exist'){
					return false;
				}else{
					return true;
				}	
			}
		});
	
}
function spellcheck(mydata){
	//alert(mydata); //successfully gives the value from the text input
	$.post("/wp-content/plugins/skopes/checkuserdata.php", {email: mydata}, function (data){
		//alert(data); 
		if(data == 'exist'){
			jQuery("#email_error").html("This email is already used by another user. try a different one.");
			jQuery("#email").focus();
			return false;
		}
		else{
			jQuery("#email_error").html("");			
			return true;
		}
	});
}

$("#email").blur(function(){
	var mydata = $("#email").val();
	spellcheck(mydata);
});

	


/* function to validate user profile */
function fnvalidate_profile() {

	if(jQuery("#email").length>0)
	{
	  var sEmail = jQuery.trim(jQuery("#email").val());
	  
		if(sEmail == "")
		{
			jQuery("#email_error").html("Enter Email id");
			jQuery("#email").focus();
			return false;
		}
		
		if(!validateEmail(sEmail))
		{
			jQuery("#email_error").html("Please enter valid email address");
			jQuery("#email").focus();
			return false;
		}	
		else
			jQuery("#emailerror").html(""); 

	}

	if(jQuery("#url").val().length>0)
	{
		var myVariable = jQuery("#url").val();

		if(/^(http:\/\/www\.|https:\/\/www\.)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test(myVariable)){
			return true;
		} else {
			jQuery("#url_error").html("Url is not valid.");
			jQuery("#url").focus();
			return false;
		} 
	}
	
	return true;
}


function fnvalidate_profile_hover()
{
	if(jQuery("#objective").length>0)
	{
		if(jQuery.trim(jQuery("#objective").val())=="")
			return false;
	}

	if(jQuery("#sponsername").length>0)
	{
		if(jQuery.trim(jQuery("#sponsername").val())=="")
			return false;
	}

	if(jQuery("#position").length>0)
	{
		if(jQuery.trim(jQuery("#position").val())=="")
			return false;

		if(jQuery.trim(jQuery("#position").val())=="Other")
		{
			if(jQuery("#other_position").length>0)
			{
				if(jQuery.trim(jQuery("#other_position").val())=="")
					return false;
			}
		}
	}

	if(jQuery("#country").length>0)
	{
		if(jQuery.trim(jQuery("#country").val())=="")
			return false;
	}

	if(jQuery("#state").length>0)
	{
		if(jQuery.trim(jQuery("#state").val())=="")
			return false;
	}

	if(jQuery("#city").length>0)
	{
		if(jQuery.trim(jQuery("#city").val())=="")
			return false;
	}

	if(jQuery("#organisation").length>0)
	{
		if(jQuery.trim(jQuery("#organisation").val())=="")
			return false;
	}

	if(jQuery("#number_of_location").length>0)
	{
		if(jQuery.trim(jQuery("#number_of_location").val())=="")
			return false;
	}

	if(jQuery("#turnover").length>0)
	{
		if(jQuery.trim(jQuery("#turnover").val())=="")
			return false;
	}

	if(jQuery("#staff").length>0)
	{
		if(jQuery.trim(jQuery("#staff").val())=="")
			return false;
	}

	if(jQuery("#hourly_rate").length>0)
	{
		if(jQuery.trim(jQuery("#hourly_rate").val())=="")
			return false;
		else
		{
			var a = jQuery("#hourly_rate").val();
			var f = /^[1-9]+[0-9]*$/;
			if(!f.test(a)){
				return false;
			}
		}
	}

	if(jQuery("#number_of_teamleader").length>0)
	{
		if(jQuery.trim(jQuery("#number_of_teamleader").val())=="")
			return false;
		else
		{
			var a = jQuery("#number_of_teamleader").val();
			var f = /^[1-9]+[0-9]*$/;
			if(!f.test(a)){
				return false;
			}
		}
	}

	if(jQuery("#project_budget").length>0)
	{
		if(jQuery.trim(jQuery("#project_budget").val())=="")
			return false;
		else
		{
			var a = jQuery("#project_budget").val();
			var f = /^[1-9]+[0-9]*$/;
			if(!f.test(a)){
				return false;
			}
		}
	}

	if(jQuery("#timeline_month").length>0 || jQuery("#timeline_year").length>0)
	{
		if((jQuery.trim(jQuery("#timeline_month").val())=="") && (jQuery.trim(jQuery("#timeline_year").val())==""))
			return false;
	}

	if(jQuery("#number_of_team_members").length>0)
	{
		if(jQuery.trim(jQuery("#number_of_team_members").val())=="")
			return false;
		else
		{
			var a = jQuery("#number_of_team_members").val();
			var f = /^[1-9]+[0-9]*$/;
			if(!f.test(a)){
				return false;
			}
		}

	}

	if(jQuery("#lunch_info").length>0)
	{
		if(jQuery.trim(jQuery("#lunch_info").val())=="")
			return false;
	}

	if(jQuery("#number_of_members_in_db").length>0)
	{
		if(jQuery.trim(jQuery("#number_of_members_in_db").val())=="")
			return false;
		else
		{
			var a = jQuery("#number_of_members_in_db").val();
			var f = /^[1-9]+[0-9]*$/;
			if(!f.test(a)){
				return false;
			}
		}
	}

	if(jQuery("#number_of_non_members_in_db").length>0)
	{
		if(jQuery.trim(jQuery("#number_of_non_members_in_db").val())=="")
			return false;
		else
		{
			var a = jQuery("#number_of_non_members_in_db").val();
			var f = /^[1-9]+[0-9]*$/;
			if(!f.test(a)){
				return false;
			}
		}
	}

	if(jQuery("#db_software").length>0)
	{
		if(jQuery.trim(jQuery("#db_software").val())=="")
			return false;
	}

	if(jQuery("#db_happy_info").length>0)
	{
		if(jQuery.trim(jQuery("#db_happy_info").val())=="")
			return false;
	}
	if(jQuery("#explanation").length>0)
	{
		if(jQuery.trim(jQuery("#explanation").val())=="")
			return false;
	}

	return true;
}
