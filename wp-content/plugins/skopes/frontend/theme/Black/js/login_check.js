path=jQuery("#path").val();
$(function(){
	//var status_interval=setInterval(function(){check_status();}, 10000);
});

function onBlur()
{
	//document.body.className = 'blurred';
}

function onFocus(){
	//document.body.className = 'focused';
	check_status();
}

if (/*@cc_on!@*/false) { // check for Internet Explorer
	document.onfocusin = onFocus;
	//document.onfocusout = onBlur;
}
else {
	window.onfocus = onFocus;
	//window.onblur = onBlur;
}

/* check user logged in status*/
function check_status()
{
	if($("#hiddenaction").val()=="SaveProfile")
		chk_url=path+"/savedata.php?action=CheckStatus&page=profile";
	else
		chk_url=path+"/savedata.php?action=CheckStatus&page=tool";

	jQuery.ajax({
	   url: chk_url,
	   }).done(function(data)   {
			if(jQuery.trim(data)=="Logout")
			{
				//alert("called");
				document.location="?act=logout";
			}
	});
}