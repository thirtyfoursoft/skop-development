var path = '/wp-content/plugins/skopes/frontend/theme/Black';
 
 function openpopup() {
    var browserHeight = $(document).height();
	$('#fogotemail_error').html('');
	$('#show_msg_a').html('');
    document.getElementById('light').style.display = 'block';              
    document.getElementById('fade').style.display  = 'block';
	
	$('#fade').css('height', browserHeight);
}

function base64_decode(data) {

  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    dec = '',
    tmp_arr = [];

  if (!data) {
    return data;
  }

  data += '';

  do {
    // unpack four hexets into three octets using index points in b64
    h1 = b64.indexOf(data.charAt(i++));
    h2 = b64.indexOf(data.charAt(i++));
    h3 = b64.indexOf(data.charAt(i++));
    h4 = b64.indexOf(data.charAt(i++));

    bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

    o1 = bits >> 16 & 0xff;
    o2 = bits >> 8 & 0xff;
    o3 = bits & 0xff;

    if (h3 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1);
    } else if (h4 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1, o2);
    } else {
      tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
    }
  } while (i < data.length);

  dec = tmp_arr.join('');

  return decodeURIComponent(escape(dec.replace(/\0+$/, '')));
}

function base64_encode(data) { 

  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    enc = '',
    tmp_arr = [];

  if (!data) {
    return data;
  }

  data = unescape(encodeURIComponent(data));

  do {
    // pack three octets into four hexets
    o1 = data.charCodeAt(i++);
    o2 = data.charCodeAt(i++);
    o3 = data.charCodeAt(i++);

    bits = o1 << 16 | o2 << 8 | o3;

    h1 = bits >> 18 & 0x3f;
    h2 = bits >> 12 & 0x3f;
    h3 = bits >> 6 & 0x3f;
    h4 = bits & 0x3f;

    // use hexets to index into b64, and append result to encoded string
    tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
  } while (i < data.length);

  enc = tmp_arr.join('');

  var r = data.length % 3;

  return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
}


function showMessageinPopup(messageInHeader)
{ 
    var msgtoshow  = base64_decode(messageInHeader);  
    jQuery(".overlay").fadeIn(500);
	jQuery("#big_popup").hide();
	jQuery("#close_popup").hide();
	jQuery("#small_popup").html("");
	jQuery("#popup").fadeIn(500);
	jQuery(".popupbox").addClass("smallpopup");
	jQuery("#small_popup").load(path+"/msg.php?type=reportGenerationError&msgText="+messageInHeader,function(data){
		jQuery("#small_popup").show();
		jQuery("#close_popup").show();
	});
}

function showPlanMessageinPopup(messageInHeader) { 
    var msgtoshow  = base64_decode(messageInHeader);  
    jQuery(".overlay").fadeIn(500);
	jQuery("#big_popup").hide();
	jQuery("#close_popup").hide();
	jQuery("#small_popup").html("");
	jQuery("#popup").fadeIn(500);
	jQuery(".popupbox").addClass("smallpopup");
	jQuery("#small_popup").load(path+"/msg.php?type=reportPlanError&msgText="+messageInHeader,function(data){
		jQuery("#small_popup").show();
		jQuery("#close_popup").show();
	});
}


function validateEmail(sEmail) {
  var reEmail = /^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!\.)){0,61}[a-zA-Z0-9]?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!$)){0,61}[a-zA-Z0-9]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/; 
  if(!sEmail.match(reEmail)) { 
    return 0;
  } 
  return 1; 
} 

// IE browser solution
$(function() { 
 
    jQuery("#forgot_pwd2").on("click",function(e)
	{
	   e.preventDefault();
		    var forgotEmailID = jQuery('#email_forgot_ID').val();
		 
		   if(forgotEmailID == '')
		   {
		        jQuery('#fogotemail_error').html('Please enter the Email ID of your user account');
				jQuery('#fogotemail_error').focus();
				return false;
		   }
		   if(forgotEmailID != '')
		   {
		        var emailcheck = validateEmail(forgotEmailID);
				if(emailcheck == 0)
				{
				        jQuery('#fogotemail_error').html('Please enter the valid Email ID. For eg: test@example.com');
						jQuery('#fogotemail_error').focus();
						return false;
				}
				else
				{
				        $('#fogotemail_error').html('');
				}
		   } 
			 $.ajax({
				type: 'POST', 
				url:  '/wp-content/plugins/skopes/frontend/theme/Black/my_ajax_functions.php',  
				data: {query: 'forgotPass', forgotEmailID: forgotEmailID},  
				beforeSend: function(){
					$('#show_msg_a').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');
				},
				success: function(data){ 
				     $('#show_msg_a').html('');
					 if(data.trim() == 'not_exists')
					 {
					     $('#show_msg_a').html('');
					     $('#show_msg_a1').html('There is not any user related to your email ID. Please correct the email ID.');
					 }
					 else
					 {
					     $('#show_msg_a').html('');
						 $('#show_msg_a1').html('');
					     window.location = data;
					 }
				}
			}); 
	});
 
    $("#cookieID").click(function() {
	  $("html, body").animate({ scrollTop: 0 }, "slow");
	  return false;
	}); 

	//input, textarea placeholder
	$('input, textarea').placeholder();

	//IE PIE
	if (window.PIE)
	{

		$('.block-section .block, .bottom-btn .btn-blue, .bottom-btn .btn-green, .bottom-btn .btn-purple, .smallpopup .box, .sq-editsection .btn-edit, .sq-editsection .btn-delete, .yes-delete, .yes-delete .inner, .ccpage .btn-delete, .ccpage .label-section label, .ccpage .cc-heading, .user-profileform .btn-blue, .user-profileform .btn-blue .btn-inner, .upf-buttonsection .staticbtn, .upf-buttonsection .innerbtn, .signme-section, .signme-section .register .button, .signme-section .login .button, .signme-section .register .button .button-inner, .signme-section .login .button .button-inner, .signme-section .login .button, .signme-section .register .button, .signme-section .static .button, .signup-form .bb-blue .button, .signup-form .bb-blue .button .button-inner, .legend_blue, .legend_green, legend_grey, .progressbar .bar_normal, .bar_current, .bar_gray, .num_circle, .got_feedback .btn-green, .got_feedback .btn-green .btn-inner, .price_section .price').each(function() {
			PIE.attach(this);
		});

		/* report */
		 $('#header-section, .pagenumber .page-num').each(function() {
			PIE.attach(this);
		});
	}

/* ************** Help Icon and menu icon  ***************** */

	$('.top_menu').click(function() {
		var status = $(this).attr('status');

		if (status == 'help') {

			if ( ($('.bottom-header').css('display') == 'none') && ($('li.last-child .sub-expend').length > 0) ) {

				$('.helpbtn1').toggleClass( "closebtn11");
				$('.bottom-header').slideToggle(500);
				$('li.last-child .sub-expend').show();
			} else {
				$('li.last-child .sub-expend').show();
			}
		} else {

			$(this).toggleClass( "closebtn11");
			$('.bottom-header').slideToggle(500);
			$('li.last-child .sub-expend').hide();

		}
	});

	$('li.last-child .sub-expend, .last-child').mouseleave(function() {
		$('li.last-child .sub-expend').hide();
	});
	
	$('li.last-child').mouseover(function() {
		$('li.last-child .sub-expend').show();
	});
	
	/* Display Legends in popup */
	jQuery("#view_lengends").on("click",function(e)
	{
		e.preventDefault();
		jQuery(".overlay").fadeIn(500);
		jQuery("#small_popup").hide();
		jQuery("#big_popup").html("");
		jQuery("#big_popup").show();
		jQuery("#popup").fadeIn(500);
		jQuery(".popupbox").removeClass("smallpopup");
		jQuery("#big_popup").load(path+"/legends.php","",function(data){

		});

	})

	//Confirm section
	$(".edit-items").hide();
	
	$(".button-yes").on("click",function() {

		if($(this).hasClass("deactivated")==true) {
			return false;
		}

		$(".edit-items").fadeIn(1000);
		$(".button-yes.static").addClass("active");
		$(".button-no.static").addClass("deactivated");
	});

	
	//Loader
	$(".loader").show();
	
	
	var path = '/wp-content/plugins/skopes/frontend/theme/Black';

});

jQuery(window).load(function ()
{
   var path = '/wp-content/plugins/skopes/frontend/theme/Black';
 //alert(path);
	jQuery(".loader").hide();
	
	/* display register message in popup */
	if(jQuery("#popup_msg").val()=="fail" || jQuery("#popup_msg").val()=="register_success" || jQuery("#popup_msg").val()=="invalidemail" || jQuery("#popup_msg").val()=="feedback")
	{
	 //alert(jQuery("#popup_msg").val());
		jQuery("#big_popup").hide();
		jQuery("#close_popup").hide();
		jQuery("#small_popup").html("");
		jQuery(".popupbox").addClass("smallpopup");

		jQuery("#small_popup").load(path+"/msg.php?type="+escape(jQuery("#popup_msg").val()),function(data){
			jQuery("#popup").fadeIn(500);
			jQuery(".overlay").fadeIn(500);
			jQuery("#small_popup").show();
			jQuery("#close_popup").show();
		});
	}

	if(jQuery("#finish").length>0)
	{
		if(jQuery.trim(jQuery("#finish").val())=="yes")
		{
			jQuery.ajax({
				   url:  path+"/savedata.php?action=finish",
				   }).done(function(data) {
					   if(data!="complete")
					   {
							jQuery(".overlay").fadeIn(500);
							jQuery("#big_popup").hide();
							jQuery("#close_popup").hide();
							jQuery("#small_popup").html("");
							jQuery("#popup").fadeIn(500);
							jQuery(".popupbox").addClass("smallpopup");
							jQuery("#small_popup").load(path+"/msg.php?type="+escape(data),function(data){
								jQuery("#small_popup").show();
								jQuery("#close_popup").show();
							});
					   }
					   else
					   {
						   url=window.location.href.split('?')[0]+"?action=report";
						   var win=window.open(url,'_blank');
						   //win.focus();
						   //document.location="?action=report";
					   }
				   });
		}
	}

	if($(".textarea_div textarea").length>0)
	{
		if($(".textarea_div .mCSB_container").length==0)
		{
			var textArea=$(".textarea_div textarea");
			textArea.wrap("<div class='textarea-wrapper' />");
			var textAreaWrapper=textArea.parent(".textarea-wrapper");
			textAreaWrapper.mCustomScrollbar({
				scrollInertia:0,
				advanced:{autoScrollOnFocus:false}
			});
			var hiddenDiv=$(document.createElement("div")),
				content=null;
			hiddenDiv.addClass("hiddendiv");
			$("body").prepend(hiddenDiv);
			textArea.bind("keyup",function(e){
				content=$(this).val();
				var clength=content.length;
				var cursorPosition=textArea.getCursorPosition();
				content="<span>"+content.substr(0,cursorPosition)+"</span>"+content.substr(cursorPosition,content.length);
				content=content.replace(/\n/g,"<br />");
				hiddenDiv.html(content+"<br />");
				$(this).css("height",hiddenDiv.height());
				textAreaWrapper.mCustomScrollbar("update");
				var hiddenDivSpan=hiddenDiv.children("span"),
					hiddenDivSpanOffset=0,
					viewLimitBottom=(parseInt(hiddenDiv.css("min-height")))-hiddenDivSpanOffset,
					viewLimitTop=hiddenDivSpanOffset,
					viewRatio=Math.round(hiddenDivSpan.height()+textAreaWrapper.find(".mCSB_container").position().top);
				if(viewRatio>viewLimitBottom || viewRatio<viewLimitTop){
					if((hiddenDivSpan.height()-hiddenDivSpanOffset)>0){
						textAreaWrapper.mCustomScrollbar("scrollTo",hiddenDivSpan.height()-hiddenDivSpanOffset);
					}else{
						textAreaWrapper.mCustomScrollbar("scrollTo","top");
					}
				}
			});
		}
	}
});

var path = '/wp-content/plugins/skopes/frontend/theme/Black';
/**
*   Name: Twisha Patel Date: 7-8-2013
*
*   Descripition: Set Frame and enable, disable and move it accoring to direction. open popup, close popup, load category data according to selection, save custom category data
*
**/
NO_ELE=4;
path=jQuery("#path").val();
var undefined = false;
jQuery(function(){

	/* set height for middle section */
	if(jQuery(".middle-section").height()<200)
	{
		//ht=parseInt(jQuery(window).height())-parseInt(jQuery(".footer-section").height());
		//jQuery(".middle-section").css("min-height",ht);
	}

	/* add class to footer ul */
	jQuery(".footer-section .span3").find("ul").addClass("footerlink");

	/*toogle register block */
	jQuery("#register_link").on("click",function(e)
	{
		e.preventDefault();
		jQuery(".signme-section").css("box-shadow","none");
		jQuery(".msgbox").hide();
		jQuery("#login_block").hide();
		jQuery("#register_block").slideToggle(500,function(){
			jQuery(".signme-section").css("box-shadow","box-shadow: 0 1px 3px rgba(255, 255, 255, 0.3);");
		});
		jQuery("#login_link").parent().addClass("static");
		jQuery("#register_link").parent().removeClass("static");

	});

	/* trigger register_link click event to display login block */
	jQuery("#register_link_header").on("click",function(e)
	{
		e.preventDefault();
		jQuery("#register_link").trigger("click");
	});

	/* validate register form */
	jQuery("#register_me").on("click",function(e)
	{
		e.preventDefault();
		if(fnvalidate_register()==true)
			jQuery("#frmregister").submit();
		else
			return false;
	});

	/*toogle login block */
	jQuery("#login_link").on("click",function(e)
	{
		e.preventDefault();
		 jQuery(".signme-section").css("box-shadow","none");
		jQuery(".msgbox").hide();
		jQuery("#register_block").hide();
		jQuery("#login_block").slideToggle(500,function(){
			jQuery(".signme-section").css("box-shadow","box-shadow: 0 1px 3px rgba(255, 255, 255, 0.3);");
		});
		jQuery("#register_link").parent().addClass("static");
		jQuery("#login_link").parent().removeClass("static");
	});

	/* trigger login_link click event to display login block */
	jQuery("#login_link_header").on("click",function(e)
	{
		e.preventDefault();
		jQuery("#login_link").trigger("click");
	});

	/* validate login form */
	jQuery("#login_me").on("click",function(e)
	{
		e.preventDefault();
		if(fnvalidate_login()==true)
			jQuery("#frmlogin").submit();
		else
			return false;
	});

	/* Forgot password Popup */
	jQuery("#forgotpwd_link").on("click",function(e)
	{
		e.preventDefault();
		jQuery(".overlay").fadeIn(500);
		jQuery("#big_popup").hide();
		jQuery("#small_popup").html("");
		jQuery("#small_popup").show();
		jQuery("#popup").fadeIn(500);
		jQuery(".popupbox").addClass("smallpopup");
		jQuery("#small_popup").load(path+"/forgotpwd.php","",function(data){

		});
	});

	/* validate forgot password   */
	jQuery("#forgot_pwd").on("click",function(e)
	{
	   e.preventDefault();
		if(fnvalidate_forgotpwd()==true)
			jQuery("#frmfogotpwd").submit();
		else
			return false;
	});


	/* set active class as default to first element if not set */
	if(jQuery(".default_category .active").length==0)
	{
		if($("#editid").length>0)
			jQuery(".block[catid='"+$("#editid").val()+"']").addClass("active");
		else
			jQuery(".default_category .block").first().addClass("active");
	}

	if(jQuery(".indicator-section .active").length>0)
	{
		jQuery(".hoverblock").css("left",parseInt(jQuery(".block-section .active").position().left)-13);
		jQuery(".hoverblock").css("top",parseInt(jQuery(".block-section .active").position().top)-13);
	}

	/* function to set default attributes as disable for moving direction for default nad custom category */
	_intialize("default_category");
	_intialize("custom_category");

	/* set active category name */
	fnSetCategoryname();

	/* add custom category */
	jQuery("#addcategory").on("click",function(e) { 
		   jQuery(".overlay").fadeIn(500);
			jQuery("#big_popup").hide();
			jQuery("#close_popup").hide();
			jQuery("#small_popup").html("");
			jQuery("#popup").fadeIn(500);
			jQuery(".popupbox").addClass("smallpopup");
			jQuery("#small_popup").load(path+"/msg.php?type=notavailable",function(data){
				jQuery("#small_popup").show();
				jQuery("#close_popup").show();
			});

	});

	jQuery(".bottom").on("mouseenter",function()
	{
		jQuery(".bottom-left").css("height","0px");
		jQuery(".bottom-right").css("height","0px");
	});
	jQuery(".bottom").on("mouseout",function()
	{
		jQuery(".bottom-left").css("height","36px");
		jQuery(".bottom-right").css("height","36px");

	});

	/* move frame on top side */
	/* jQuery(".top").on("click",function()
	{ */
	jQuery(document).on('click', '.top', function () {
		if(jQuery('.block-section .active').attr("top")!="disable")
		{
			parent_cls=jQuery(".block-section .active").parent().attr("class");
			oldInd=parseInt(jQuery(".block-section .active").attr("index"));
			last_count=jQuery(".default_category .block").length%NO_ELE;
			active_ind="";
			if((last_count!=0) )
			{
				if(parent_cls=='block-section custom_category' && ((jQuery(".custom_category .block").length<=NO_ELE) || ( parseInt(jQuery(".block[index="+oldInd+"]").attr('ind'))<NO_ELE ))  )
					active_ind=parseInt(oldInd)-(jQuery(".default_category .block").length%NO_ELE);
				else
					active_ind=parseInt(oldInd)-NO_ELE;
			}
			else
			{
				active_ind=parseInt(oldInd)-NO_ELE;
			}

			jQuery(".block-section .active").removeClass("active");
			id=jQuery(".block[index="+active_ind+"]").attr("id");
			jQuery("#"+id).addClass("active");
			_moveFrame("top",jQuery("#"+id).parent().attr("class"),oldInd);
			_checkMove();

		}
	});

	/* move frame on bottom side */
	jQuery(".bottom").on("click",function()
	{
		jQuery(".bottom").trigger("mouseout");
		if(jQuery('.block-section .active').attr("bottom")!="disable")
		{
			parent_cls=jQuery(".block-section .active").parent().attr("class");
			oldInd=parseInt(jQuery(".block-section .active").attr("index"));
			last_count=jQuery(".default_category .block").length%NO_ELE;
			active_ind="";
			if(last_count!=0 && (oldInd > (jQuery(".default_category .block").length-last_count)) && parent_cls=='block-section default_category')
			//if((jQuery(".default_category .block").length%NO_ELE)!=0 && parent_cls!='block-section default_category')
				active_ind=parseInt(oldInd)+(jQuery(".default_category .block").length%NO_ELE);
			else
				active_ind=parseInt(oldInd)+NO_ELE;

			jQuery(".block-section .active").removeClass("active");

			id=jQuery(".block[index="+active_ind+"]").attr("id");

			jQuery("#"+id).addClass("active");
			_moveFrame("bottom",jQuery("#"+id).parent().attr("class"),oldInd);
			_checkMove();

		}
	});

	/* move frame on left side */
	
	//jQuery(".left").on("click",function(){
	jQuery(document).on('click', '.left', function () {
		if(jQuery('.block-section .active').attr("left")!="disable")
		{
			parent_cls=jQuery(".block-section .active").parent().attr("class");
			active_ind=parseInt(jQuery(".block-section .active").attr("index"))-1;
			jQuery(".block-section .active").removeClass("active");
			id=jQuery(".block[index="+active_ind+"]").attr("id");
			jQuery("#"+id).addClass("active");
			_moveFrame("left",jQuery("#"+id).parent().attr("class"),0); 
			_checkMove();

		}
	});

	/* move frame on right side */
	jQuery(".right").on("click",function()
	{
		if(jQuery('.block-section .active').attr("right")!="disable")
		{
			parent_cls=jQuery(".block-section .active").parent().attr("class");
			active_ind=parseInt(jQuery(".block-section .active").attr("index"))+1;
			jQuery(".block-section .active").removeClass("active");
			id=jQuery(".block[index="+active_ind+"]").attr("id");
			jQuery("#"+id).addClass("active");
			_moveFrame("right",jQuery("#"+id).parent().attr("class"),0);
			_checkMove();
		}
	});

	/*  Move frame on clicked block */
	jQuery(".block-section .block").on("click",function(e)
	{
		e.preventDefault();
		jQuery('.edit-items').hide();
		jQuery("div.block").removeClass("active");
		jQuery("#"+jQuery(this).attr("id")).addClass("active");

		jQuery(".hoverblock").css("left",parseInt(jQuery("#"+jQuery(this).attr("id")).position().left)-13);
		jQuery(".hoverblock").css("top",parseInt(jQuery("#"+jQuery(this).attr("id")).position().top)-13);
		_checkMove();
		/* set active category name */
		fnSetCategoryname();
	});

	/* Save category name when foucs is out from textbox */
	jQuery("#custom_catname").on("focusout",function(e)
	{
		e.preventDefault();

		catname=jQuery("#custom_catname").val();
		catid=jQuery(".block-section .active").attr("catid");
		if(jQuery.trim(catname)=="")
		{
			jQuery("#big_popup").hide();
			jQuery("#close_popup").hide();
			jQuery("#small_popup").html("");
			jQuery(".overlay").fadeIn(500);
			jQuery("#popup").fadeIn(500);
			jQuery(".popupbox").addClass("smallpopup");
			jQuery("#small_popup").load(path+"/msg.php?type=catname_error",function(data){
				jQuery("#small_popup").show();
				jQuery("#close_popup").show();
			});
			//jQuery("#custom_catname").focus();
			//return false;
		}
		else
		{
			jQuery.ajax({
			   url:  path+"/savedata.php?action=SaveCustomCategory&catname="+escape(catname)+"&catid="+catid,
			   }).done(function(data) {
				  
					catdata=data.split("|")
					   jQuery(".block-section .active").attr("catid",catdata[0]);
					   jQuery(".block-section .active").attr("catname",catdata[1]);
					   jQuery(".block-section .active .block-inner .numtop").attr("id","complete_cnt_"+catdata[0]);
					   jQuery(".block-section .active .block-inner .numbtm").attr("id","total_"+catdata[0]);
					   jQuery("#catname").html(catdata[1]);

					   //fnSetCategoryname();
			});
		}
	});

	/* Save category desc when foucs is out from textarea */
	jQuery("#custom_catdesc").on("focusout",function(e)
	{
		e.preventDefault();

		catdesc=jQuery("#custom_catdesc").val();
		if(jQuery.trim(jQuery("#custom_catdesc").val())=="")
		{
			jQuery("#custom_catdesc").focus();
			return false;
		}

		catid=jQuery(".block-section .active").attr("catid");
		if(jQuery.trim(catdesc)=="")
		{
			jQuery(".overlay").fadeIn(500);
			jQuery("#big_popup").hide();
			jQuery("#close_popup").hide();
			jQuery("#small_popup").html("");
			jQuery("#popup").fadeIn(500);
			jQuery(".popupbox").addClass("smallpopup");
			jQuery("#small_popup").load(path+"/msg.php?type=catdesc_error",function(data){
				jQuery("#small_popup").show();
				jQuery("#close_popup").show();
			});
			jQuery("#custom_catdesc").focus();
		}
		else
		{
			jQuery.ajax({
			   url:  path+"/savedata.php?action=SaveCustomCategory&catdesc="+escape(catdesc)+"&catid="+catid,
			   }).done(function(data) {
				   catdata=data.split("|");
				   jQuery(".block-section .active").attr("catid",catdata[0]);
				   jQuery(".block-section .active").attr("catname",catdata[1]);
				   jQuery("#catname").html(catdata[1]);
				   $("#custom_catdesc").mCustomScrollbar("update");
			});
		}
	});
	
	
	/* Add custom category and move frame acoording to position */ 
	jQuery(document).on('click', '#save_custom_cat', function (e) {
		e.preventDefault();
		catname   = escape(jQuery("#custom_catname").val());
		catdesc   = escape(jQuery("#custom_catdesc").val()); 
		catIDUseID = jQuery("#catIDUseID").val();
		actionURL = jQuery("#actionURL").val()+"/skopes/frontend/theme/Black/save_custom_cat.php?catname="+catname+"&catdesc="+catdesc+"&catIDUseID="+catIDUseID; 
		
		jQuery.ajax({
			type: "POST",
			beforeSend: function() {
			   // show indicator loader wrapper
			   jQuery(".loader").show();
			   jQuery(".popup_loader").show();
		    },
		    complete: function() {
			  // hide indicator
			   jQuery(".loader").hide();
			   jQuery(".popup_loader").hide(); 
		    },
			url: actionURL,
			data: { catname: catname, catdesc: catdesc }
			})  
			.done(function( msg ) {
			     if(msg == 0)
				  {
				     jQuery("#errorMsgID").css( "color", "red" ); 
					 jQuery("#errorMsgID").css( "padding", "20px" ); 
					 jQuery("#errorMsgID").html('Name is required field and need to be added.');
				  }
				  else if(msg == 1)
				  {
				     jQuery("#errorMsgID").css( "color", "red" ); 
					 jQuery("#errorMsgID").css( "padding", "20px" ); 
					 jQuery("#errorMsgID").html('Description is required field and need to be added.');
				  }
				  else if(msg == 2)
				  {
				     jQuery("#errorMsgID").css( "color", "red" ); 
					 jQuery("#errorMsgID").css( "padding", "20px" ); 
					 jQuery("#errorMsgID").html('Both fields are required to be added.');
				  }
				  else
				  { 
				     jQuery("#errorMsgID").css( "color", "blue" ); 
					 jQuery("#errorMsgID").css( "padding", "20px" );  
					 jQuery("#errorMsgID").html('You have added successfully the new function.'); 
					 location.reload(); 
				  }
			});
		 
	});

	/* Delete custom category and move frame acoording to position */
	/* jQuery("#delete_custom_cat").on("click",function(e)
	{ */
	jQuery(document).on('click', '#delete_custom_cat', function (e) {
		e.preventDefault();
		catid=jQuery(".block-section .active").attr("catid");
		jQuery(".overlay").fadeIn(500);
		//jQuery("#popup .popupbox .box").html("");

		jQuery("#big_popup").html("");
		jQuery("#big_popup").hide();
		jQuery("#close_popup").hide();
		jQuery("#small_popup").html("");
		jQuery("#small_popup").attr("display","error");
		jQuery(".popupbox").addClass("smallpopup");
		jQuery("#small_popup").load(path+"/msg.php?type=cat_delete&catid="+catid,function(data){
			jQuery("#small_popup").show();
			jQuery("#popup").fadeIn(500);
			jQuery("#close_popup").show();
		});
	});

	/* display when focus is set on custom category name textbox */
	jQuery("#custom_catname").on("focus",function()
	{
	  jQuery("#catname_label").css("visibility","hidden");
	  if(jQuery("#custom_catdesc").val()=="")
		jQuery("#catdesc_label").css("visibility","visible");
	});

	/* display label when focus is set on custom category description textarea */
	jQuery("#custom_catdesc").on("focus",function()
	{
	  jQuery("#catdesc_label").css("visibility","hidden");
	  if(jQuery("#custom_catname").val()=="")
		jQuery("#catname_label").css("visibility","visible");

	});

	/* by default hide esit and complet item area */
	jQuery(".edit-items").hide();

	
	
	/* When user click on Yes for include category in features */
	jQuery(document).on('click', '#include_yes', function () {

		if(jQuery(".block-section .active").hasClass("block-incomplete") || jQuery(".block-section .active").hasClass("block-notrequired") || jQuery(".block-section .active").hasClass("block-inscope"))
		{
			catid = jQuery(".block-section .active").attr("catid");
			
			if(jQuery("#custom_catname"))
			{
				jQuery("#custom_catname").trigger("focusout");
			} 
			if(typeof(catid)!="undefined")
			{ 
				if(jQuery(this).hasClass("deactivated")==true)
				{  
					jQuery.ajax({
					   url:  path+"/savedata.php?action=IncludeCategory&catid="+escape(catid)+"&cat_status=3",
					   }).done(function(data) {					    
						 jQuery(".block-section .active").removeClass("block-notrequired");
						 jQuery(".block-section .active").removeClass("block-complete");
						 jQuery(".block-section .active").addClass("block-inscope");
					});

				}
				
				jQuery(".edit-items").fadeIn(1000);
				jQuery("#include_yes").addClass("active");
				jQuery("#include_no").addClass("deactivated");
				jQuery("#include_no").removeClass("active");
				if(jQuery.isNumeric($(".block-section .active .block-inner span#complete_cnt_"+catid).html()))
				{}else{
					$(".block-section .active .block-inner span#complete_cnt_"+catid).html("0");
				}

				jQuery(".overlay").fadeIn(500);
				jQuery("#small_popup").hide();
				jQuery("#big_popup").html("");
				jQuery("#big_popup").show();
				jQuery("#popup").fadeIn(500);
				jQuery(".popupbox").removeClass("smallpopup");

				jQuery("#big_popup").load(path+"/lineitems.php?type=EditLineItems&catid="+escape(catid),function(data){
					if(jQuery(".lineitemfrm").length==0)
					{
						custom_flag=0;
						jQuery("#add_custom_lineitem").trigger("click");
					}
					else
						jQuery("#lineitem_scroll").mCustomScrollbar();
				});
			}
		} 
	});

	/* When user click on No for include category */
	/* jQuery("#include_no").on("click",function()
	{ */
	jQuery(document).on('click', '#include_no', function () {
		jQuery(".edit-items").hide();
        
		//if(jQuery(".block-section .active").hasClass("block-incomplete"))
		//{
			catid=jQuery(".block-section .active").attr("catid");
			if(catid)
			{   
			    jQuery(".lineitem-no").trigger("click");
			
				jQuery("#include_no").addClass("active");
				jQuery("#include_yes").addClass("deactivated");
				jQuery("#include_yes").removeClass("active");

				jQuery.ajax({
				   url:  path+"/savedata.php?action=IncludeCategory&catid="+escape(catid)+"&cat_status=2",
				   }).done(function(data) {

						catid=jQuery(".block-section .active").attr("catid");
						comp_cnt=jQuery("#complete_cnt_"+catid).html();
					
					   if(comp_cnt >= 0)
						 jQuery("#complete_cnt_"+catid).html("?");  
					   else if(comp_cnt=="?")	
						 jQuery("#complete_cnt_"+catid).html("-");
							 
						 jQuery(".block-section .active").removeClass("block-incomplete");
						 jQuery(".block-section .active").removeClass("block-complete");
						 jQuery(".block-section .active").removeClass("block-inscope");
						 jQuery(".block-section .active").addClass("block-notrequired");
					 });
			//}
		}
	});

	/* show line item popup when clock on edit line item button */
	//jQuery("#edit_line_item").on("click",function()
	jQuery("#edit_line_item").on("click",function() {

		catid=jQuery(".block-section .active").attr("catid");
		catstatus = jQuery('#catStatusValue').html();

		if(jQuery("#custom_catname")) {
			jQuery("#custom_catname").trigger("focusout");
		}

		if(typeof(catid)!="undefined") {
			if(catstatus == 'Not required') {
				/*jQuery.ajax({
				   url:  path+"/savedata.php?action=IncludeCategory&catid="+escape(catid)+"&cat_status=0",
				   }).done(function(data) {
					 jQuery(".block-section .active").removeClass("block-notrequired");
					 jQuery(".block-section .active").removeClass("block-complete");
					 jQuery(".block-section .active").addClass("block-incomplete");
				});*/ 
				jQuery(".overlay").fadeOut(500);
				jQuery("#big_popup").hide();
				jQuery("#popup").fadeOut(500);

			} else if(catstatus == 'Unknown') {
				 jQuery(".overlay").fadeOut(500);
				   jQuery("#big_popup").hide();
				   jQuery("#popup").fadeOut(500);

			} else {
						jQuery(".overlay").fadeIn(500);
						jQuery("#small_popup").hide();
						jQuery("#big_popup").html("");
						jQuery("#big_popup").show();



						jQuery("#popup").fadeIn(500);
						jQuery(".popupbox").removeClass("smallpopup");

						jQuery("#big_popup").load(path+"/lineitems.php?type=EditLineItems&catid="+escape(catid),function(data){
							if(jQuery(".lineitemfrm").length==0) {
								custom_flag=0;
								jQuery("#add_custom_lineitem").trigger("click");
							} else {
								jQuery("#lineitem_scroll").mCustomScrollbar();
							}
						});
		        }
		}
	});

	/* when user click on Yes in line item edit popup */
	/* jQuery(".lineitem-yes").on("click",function(e)
	{ */
	jQuery(document).on('click', '.lineitem-yes', function (e) {
		e.preventDefault();
		var res = checkShStatus(); 
		
		if( res == 2 ) {
			catid = jQuery(this).attr("catid");
			lineid = jQuery(this).attr("lineid");
			statusofLineItem = jQuery(this).attr("statusofLineItem");
			rowDispOrNot = document.getElementById('rowno_'+lineid).style.display;
			 
			if(rowDispOrNot == "")
			{
			      jQuery("#rowno-data_"+lineid).hide();
			}
			
			lineitem_cost = jQuery("#lineitem_cost_"+lineid).val();
			lineitem_time = jQuery("#lineitem_time_"+lineid).val();
			lineitem_eoi  = jQuery("#lineitem_eoi_"+lineid).val();
			lineitem_year = jQuery("#lineitem_year_"+lineid).val();
			
			if(lineitem_cost == "" && lineitem_time == "")
			{
			     if(statusofLineItem > 0)
				 {
				      jQuery("#row4_"+lineid).hide();
				      jQuery("#row5_"+lineid).hide();
				 } 
			}
			else
			{ 
				 $.ajax({
						type: "POST", // <-- get method of form
						url: "/wp-content/plugins/skopes/frontend/theme/Black/compltechk.php", 
						// <-- get action of form
						data: { query: 'getRoiCalC', lineitem_cost: lineitem_cost, lineitem_time: lineitem_time, lineitem_year: lineitem_year },  
						beforeSend: function(){
							/* $('#abimg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');	 */			
						},
						success: function(data){
							jQuery("#lineitem_eoi_"+lineid).val(data);					 
						}
					}); 
			   
				 jQuery("#row4_"+lineid).show();
				 jQuery("#row5_"+lineid).show();
			}

			/* hide edit button section */
			jQuery("#edit_delete_"+lineid+" .btn-edit").hide();
			jQuery("#edit_delete_"+lineid).hide();

			/* slide down the line item detail section */
			jQuery("#lineitem_detail_"+lineid).slideDown(500,function() {
				jQuery("#lineitem_scroll").mCustomScrollbar("update");
				fid="frmlineitem_"+lineid;
				if($("#lineitem_scroll .subquestion-section").height()>=320)
				{
					jQuery("#lineitem_scroll").mCustomScrollbar("scrollTo","#"+fid);
				}
				if(jQuery.trim(jQuery("#name_"+lineid).val())=="")
					jQuery("#name_"+lineid).focus();
			});

		jQuery(this).removeClass("deactivated");
		jQuery(this).addClass("active");
		jQuery(this).next().removeClass("active");
		jQuery(this).next().addClass('deactivated');

			if(jQuery("#lineitem_status_"+lineid).val()!="1")
			{
			}// end of if lineitem_status
		}else{
		  //alert('Please complete your stakeholder list before completing the feature line items');
		     jQuery("#big_popup").hide();
			jQuery("#close_popup").hide();
			jQuery("#small_popup").html("");
			jQuery(".popupbox").addClass("smallpopup");
			jQuery("#small_popup").load(path+"/msg.php?type=sherror",function(data){
				jQuery("#popup").fadeIn(500);
				jQuery(".overlay").fadeIn(500);
				jQuery("#small_popup").show();
				jQuery("#close_popup").show();
			});
		}
	}); // end of lineitem-yes click

	/* when user click on No in line item edit popup */

	jQuery(document).on('click', '.lineitem-no', function (e) {
		e.preventDefault();
		catid=jQuery(this).attr("catid");
		lineid=jQuery(this).attr("lineid");

		/* slide up the line item detail section */
		jQuery("#lineitem_detail_"+lineid).slideUp(500,function() {
			/* hide edit button */
			jQuery("#edit_delete_"+lineid+" .btn-edit").hide();
			jQuery("#edit_delete_"+lineid).hide();
			jQuery("#lineitem_scroll").mCustomScrollbar("update");

			fid="frmlineitem_"+lineid;
			if($("#lineitem_scroll .subquestion-section").height()>=320) {
				jQuery("#lineitem_scroll").mCustomScrollbar("scrollTo","#"+fid);
			}
		});

		jQuery(this).removeClass("deactivated");
		jQuery(this).addClass("active");
		jQuery(this).prev().removeClass("active");
		jQuery(this).prev().addClass('deactivated');
		
		if(jQuery("#lineitem_status_"+lineid).val() == "1") {
			oldstatus = jQuery("#lineitem_status_"+lineid).val();
			jQuery("#lineitem_status_"+lineid).val("0");
			if(catid) {
				jQuery.ajax({
				   url:  path+"/savedata.php?action=UpdateLineItem&catid="+escape(catid),
				   type: "POST",
					async: false,
				   data: jQuery("#frmlineitem_"+lineid).serialize()
				   }).done(function(data) {

						if(oldstatus!=0) {
							comp_cnt=jQuery("#complete_cnt_"+catid).html();

							if(jQuery.isNumeric(comp_cnt)) {

								if((parseInt(comp_cnt)-1)==0)
									jQuery("#complete_cnt_"+catid).html("0");
								else
									jQuery("#complete_cnt_"+catid).html(parseInt(comp_cnt)-1);
							}
							else
								jQuery("#complete_cnt_"+catid).html("?");
						}
				});
			}

		}
	});
	
	//jQuery(document).on('click', '.eoiclass', function (e) {
	$(document).on('change', '.lr-textbox', function(e) {
	   e.preventDefault();
	   var eleid = jQuery(this).attr("id");
	   //alert(eleid);
	    var eleclass = jQuery(this).attr("class");
		//alert(eleclass);
	   var arr = eleid.split('_');
	   var lineid = arr[2]; 
	   
	  // var how_often_doit = $('#'+eleid).parent().parent().find('#lineitem_year_'+lineid).val();
	  var how_often_doit = $('#row2_'+lineid).find('#lineitem_year_'+lineid).val();
	   
	    
	   /* var cost = $('#'+eleid).parent().parent().find('#lineitem_cost_'+lineid).val();	   
	   var time = $('#'+eleid).parent().parent().find('#lineitem_time_'+lineid).val(); */
	   
	   var cost = $('#row4_'+lineid).find('#lineitem_cost_'+lineid).val();	   
	   var time = $('#row4_'+lineid).find('#lineitem_time_'+lineid).val();
	   var hrRate = $('#user_hr').val();
	   
	 
		jQuery.ajax({
			   url:  path+"/savedata.php?action=cal_eoi&hodi="+escape(how_often_doit)+"&cost="+cost+"&time="+time+"&hr_rate="+hrRate,
				type: "POST",
				data: '',
			   }).done(function(data) {	
                   var eloval = data;	
					if(data == 0 || data == '') {
					   eloval = "_";	
					} else {
					  eloval = data;	
					}
					/* $('#eoival_'+lineid).parent().parent().find('#eoival_'+lineid).html('$'+eloval); */
					$('#lineitem_eoi_'+lineid).val(eloval);	 
			});

	});
	

	/* save lineitem data */
	/* jQuery(".save_lineitem").on("click",function(e)
	{ */
	jQuery(document).on('click', '.save_lineitem', function (e) {
		e.preventDefault();
		lineid = jQuery(this).attr("lineid");
		catid = jQuery(this).attr("catid");

		if(fnvalidatelineitem(lineid)!=true)
			return false;

		oldstatus = jQuery("#lineitem_status_"+lineid).val();

		jQuery("#lineitem_status_"+lineid).val("1");
//alert(jQuery("#frmlineitem_"+lineid).serialize());
		jQuery.ajax({
			   url:  path+"/savedata.php?action=SaveLineItemData&catid="+escape(catid),
				type: "POST",
				async: false,
				data: jQuery("#frmlineitem_"+lineid).serialize()
			   }).done(function(data) {
			   //alert(data);
					/* slide down the line item detail section */
					jQuery("#lineitem_detail_"+lineid).slideUp(500,function(){

						jQuery("#button_yes_"+lineid).removeClass("deactivated");
						jQuery("#button_yes_"+lineid).addClass("active");
						jQuery("#button_no_"+lineid).removeClass("active");
						jQuery("#button_no_"+lineid).addClass('deactivated');


						/* display edit button */
						jQuery("#edit_delete_"+lineid+" .btn-edit").show();
						jQuery("#edit_delete_"+lineid).show();


						jQuery("#lineitem_scroll").mCustomScrollbar("update");
						fid="frmlineitem_"+lineid;
						if($("#lineitem_scroll .subquestion-section").height()>=320) {
							jQuery("#lineitem_scroll").mCustomScrollbar("scrollTo","#"+fid);
						}

						if(oldstatus!="1") {
							comp_cnt=jQuery("#complete_cnt_"+catid).html()
							if(jQuery.isNumeric(comp_cnt))
								jQuery("#complete_cnt_"+catid).html(parseInt(comp_cnt)+1);
							else
								jQuery("#complete_cnt_"+catid).html("1");
						}
					});

			});
	});

	/* set custom flag value to stop muliple click */
	custom_flag=0;
	/* add custom line item html */
	/* jQuery("#add_custom_lineitem").on("click",function(e)
	{ */
	jQuery(document).on('click', '#add_custom_lineitem', function (e) {
	        jQuery(".overlay").fadeIn(500);
			jQuery("#big_popup").hide();
			jQuery("#close_popup").hide();
			jQuery("#small_popup").html("");
			jQuery("#popup").fadeIn(500);
			jQuery(".popupbox").addClass("smallpopup");
			jQuery("#small_popup").load(path+"/msg.php?type=notavailable",function(data){
				jQuery("#small_popup").show();
				jQuery("#close_popup").show();
			});
		/* e.preventDefault();
		$(".popup_overlay").show();
		$(".popup_loader").show();
		
		if(custom_flag==0)
		{
			custom_flag = 1;
			jQuery(".subquestion-section").addClass("custom-section");
			catid = jQuery(this).attr("catid");
			jQuery.ajax({
				   url:  path+"/savedata.php?action=SaveCustomLineItem&catid="+catid,
				   }).done(function(data) {
					   //line_data=data.split("|")
					   jQuery.get(path+'/linedata.php?catid='+catid+'&lineid='+data, function(linedata) {
							jQuery(".subquestion-section").append(linedata);
							//jQuery("#name_"+data).focus();
							$(".popup_overlay").hide();
							$(".popup_loader").hide();
							jQuery("#lineitem_scroll").mCustomScrollbar("update");
							if($("#lineitem_scroll .subquestion-section").height()>=320)
							{
								jQuery("#lineitem_scroll").mCustomScrollbar("scrollTo","#name_"+data);
							}
							//jQuery("#lineitem_scroll .mCSB_container").css("top","");
							//jQuery("#lineitem_scroll .mCSB_dragger_bar").css("top",jQuery("#name_"+data).position().top);
							total_cnt=jQuery("#total_"+catid).html();
							if(jQuery.isNumeric(total_cnt))
								jQuery("#total_"+catid).html(parseInt(total_cnt)+1);
							else
								jQuery("#total_"+catid).html("1");

							custom_flag=0;
					   });
					 window.location = "/?act=features";  
				});
		} */
	});

	/* jQuery(".lineitemname").on("focusout",function(e)
	{ */
	jQuery(document).on('focusout', '.lineitemname', function (e) {
		e.preventDefault();
		lineid=jQuery(this).attr("lineid");
		catid=jQuery(this).attr("catid");

	   if(jQuery.trim(jQuery(this).val())=="") {
			jQuery(".overlay").fadeIn(500);
			//jQuery("#popup .popupbox .box").html("");
			//jQuery("#popup").fadeIn(500);
			jQuery("#big_popup").hide();
			jQuery("#close_popup").hide();
			jQuery("#small_popup").html("");
			jQuery("#small_popup").attr("display",lineid);
			jQuery(".popupbox").addClass("smallpopup");
			jQuery("#small_popup").load(path+"/msg.php?type=linename_error",function(data){
				jQuery("#small_popup").show();
				jQuery("#close_popup").show();
			});
	   } else {
		   jQuery.ajax({
				   url:  path+"/savedata.php?action=SaveCustomLineItem&catid="+catid+"&lineid="+lineid+"&name="+escape(jQuery(this).val()),
				   }).done(function(data){
						total_cnt=jQuery("#total_"+catid).html();
						if(jQuery(".lineitemfrm").length!=total_cnt) {
							if(jQuery.isNumeric(total_cnt))
								jQuery("#total_"+catid).html(parseInt(total_cnt)+1);
							else
								jQuery("#total_"+catid).html("1");
						}
				   });
	   }
	});

	/* Slide down line item for edit and hide edit button */
	/* jQuery(".editlineitem").on("click",function(e)
	{ */
	jQuery(document).on('click', '.editlineitem', function (e) {
		e.preventDefault();
		lineid=jQuery(this).attr("lineid");

		jQuery("#edit_delete_"+lineid).hide();
		jQuery("#edit_delete_"+lineid+" .btn-edit").hide();

		lineitem_cost = jQuery("#lineitem_cost_"+lineid).val();
		lineitem_time = jQuery("#lineitem_time_"+lineid).val();
		lineitem_eoi  = jQuery("#lineitem_eoi_"+lineid).val();
		lineitem_year = jQuery("#lineitem_year_"+lineid).val();
		
		if( lineitem_cost == "" && lineitem_time == "" ) {
		     jQuery("#row4_"+lineid).hide();
		     jQuery("#row5_"+lineid).hide();
		} else {
		     $.ajax({
					type: "POST", // <-- get method of form
					url: "/wp-content/plugins/skopes/frontend/theme/Black/compltechk.php", 
					// <-- get action of form
					data: { query: 'getRoiCalC', lineitem_cost: lineitem_cost, lineitem_time: lineitem_time, lineitem_year: lineitem_year },  
					beforeSend: function() {
						/* $('#abimg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');	 */			
					},
					success: function(data) {
						jQuery("#lineitem_eoi_"+lineid).val(data);					 
					}
				}); 
		   
		     jQuery("#row4_"+lineid).show();
		     jQuery("#row5_"+lineid).show();
		}

			jQuery("#lineitem_detail_"+lineid).slideDown(500,function() {
				jQuery("#lineitem_scroll").mCustomScrollbar("update");
				fid="frmlineitem_"+lineid;

				if($("#lineitem_scroll .subquestion-section").height()>=320) {
					jQuery("#lineitem_scroll").mCustomScrollbar("scrollTo","#"+fid);
				}
		});

	});

	/* Delete custom line item popup */
	/* jQuery(".deletelineitem").on("click",function(e)
	{ */
	jQuery(document).on('click', '.deletelineitem', function (e) {
	 
		e.preventDefault();
		lineid=jQuery(this).attr("lineid");		
		catid=jQuery(this).attr("catid");		
		jQuery(".overlay").fadeIn(500);
		//jQuery("#popup .popupbox .box").html("");
		//jQuery("#popup").fadeIn(500);
		jQuery("#big_popup").hide();
		jQuery("#close_popup").hide();
		jQuery("#small_popup").html("");
		jQuery("#small_popup").attr("display","error");
		jQuery(".popupbox").addClass("smallpopup");
		//alert("linename_delete&catid="+catid+"&lineid="+lineid);
		jQuery("#small_popup").load(path+"/msg.php?type=linename_delete&catid="+catid+"&lineid="+lineid,function(data){
		//alert(data);
			jQuery("#small_popup").show();
			jQuery("#close_popup").show();
		});

	});

	/* Delete custom line item */
	/* jQuery(".yes-delete").on("click",function(e) 
	{ */
	jQuery(document).on('click', '.yes-delete', function (e) {
		e.preventDefault();
		jQuery("#small_popup").html("");
		jQuery("#small_popup").hide();
		jQuery(".popupbox").removeClass("smallpopup");

		lineid=jQuery(this).attr("lineid");		
		catid=jQuery(this).attr("catid");
		
		if((jQuery.isNumeric(lineid)) && (jQuery.isNumeric(catid)))
		{
			jQuery("#big_popup").show();
			jQuery.ajax({
				   url:  path+"/savedata.php?action=DeleteLineItemData&catid="+escape(catid)+"&lineid="+lineid,
					type: "POST",
					data: jQuery("#frmlineitem_"+lineid).serialize()
				   }).done(function(data) {
					   jQuery("#frmlineitem_"+lineid).remove();
					   jQuery("#lineitem_scroll").mCustomScrollbar("update");
					   fid=jQuery(".subquestion-section form.lineitemfrm").first().attr("id")
					   if($("#lineitem_scroll .subquestion-section").height()>=320)
					   {
							jQuery("#lineitem_scroll").mCustomScrollbar("scrollTo","#"+fid);
					   }
					   total_cnt=jQuery("#total_"+catid).html()
						if(jQuery.isNumeric(total_cnt))
							jQuery("#total_"+catid).html(parseInt(total_cnt)-1);

						if(jQuery("#lineitem_status_"+lineid).val()==1)
						{
							comp_cnt=jQuery("#complete_cnt_"+catid).html()
							if(jQuery.isNumeric(comp_cnt) && comp_cnt>0)
								jQuery("#complete_cnt_"+catid).html(parseInt(comp_cnt)-1);
							else
								jQuery("#complete_cnt_"+catid).html("1");
						}
				   });

		} else {

			if(catid) {
				jQuery.ajax({
				   url:  path+"/savedata.php?action=DeleteCustomCategory&catid="+catid,
				   }).done(function(data) {
					   //console.log(data);
					   jQuery("#small_popup").hide();
					   jQuery("#big_popup").hide();
					   jQuery(".overlay").fadeOut(500);
					   jQuery("#close_popup").hide();
					   jQuery("#popup").hide();

						/* Set focus according to deleted element */
						if(jQuery(".block-section .active").next().hasClass("block"))
							newid=jQuery(".block-section .active").next().attr("id");
						else if(jQuery(".block-section .active").prev().hasClass("block"))
							newid=jQuery(".block-section .active").prev().attr("id");
						else
							newid=jQuery(".default_category .block").last().attr("id");

						jQuery(".block-section .active").remove();
						jQuery("#"+newid).addClass("active");

						/* remove all disable attributes before set it */
						jQuery(".block").removeAttr("bottom");
						jQuery(".block").removeAttr("top");
						jQuery(".block").removeAttr("left");
						jQuery(".block").removeAttr("right");

						_intialize("default_category");
						_intialize("custom_category");

						jQuery(".hoverblock").css("left",parseInt(jQuery(".block-section .active").position().left)-13);
						jQuery(".hoverblock").css("top",parseInt(jQuery(".block-section .active").position().top)-13);

						fnSetCategoryname();
				   });

			}

		}
	});

	/* mark category complete*/
	/* jQuery("#complete_category_popup").on("click",function(e)
	{ */
	jQuery(document).on('click', '#complete_category_popup', function (e) {
		e.preventDefault();
		/* jQuery("#category-complete").trigger("click");
		jQuery("#close_popup").trigger("click"); */
		jQuery("#category-complete").click();
		jQuery("#popupID2").val(1);
		//jQuery("#close_popup").click();

		$('.popup_overlay, .loader').hide();
		jQuery("#popup").fadeOut(500);
		jQuery(".overlay").fadeOut(500);
	});

	/* Hide investment and year when user click on YES on automated option and set active class to yes and deactive class to no */
	/* jQuery(".automated_yes").on("click",function(e)
	{ */
	jQuery(document).on('click', '.automated_yes', function (e) {
		e.preventDefault();
		jQuery(this).removeClass("yes-deactived");
		jQuery(this).removeClass("yes-static");
		jQuery(this).addClass("yes-active");
		jQuery(this).parent().next().find("a.automated_no").removeClass("no-static");
		jQuery(this).parent().next().find("a.automated_no").removeClass("no-active");
		jQuery(this).parent().next().find("a.automated_no").addClass("no-deactived");
		lineid=jQuery(this).attr("lineid");
		jQuery("#invsetment_yes_"+lineid).addClass("yes-active");
		jQuery("#invsetment_yes_"+lineid).removeClass("yes-deactived");
		jQuery("#invsetment_yes_"+lineid).removeClass("yes-static");
		jQuery("#invsetment_no_"+lineid).addClass("no-deactived");
		jQuery("#invsetment_no_"+lineid).removeClass("no-static");
		jQuery("#invsetment_no_"+lineid).removeClass("no-active");

		jQuery("#benefit_yes_"+lineid).removeClass("yes-deactived");
		jQuery("#benefit_yes_"+lineid).removeClass("yes-active");
		jQuery("#benefit_yes_"+lineid).addClass("yes-static");

		jQuery("#benefit_no_"+lineid).removeClass("no-deactived");
		jQuery("#benefit_no_"+lineid).removeClass("no-active");
		jQuery("#benefit_no_"+lineid).addClass("no-static");

		jQuery("#row2_"+lineid).show();
		jQuery("#row3_"+lineid).show();
		jQuery("#row4_"+lineid).show();
		jQuery("#row5_"+lineid).show();
		jQuery("#rowno_"+lineid).hide();
		jQuery("#rowno-data_"+lineid).hide();

		jQuery("#lineitem_benefit_"+lineid).val("0");
		jQuery("#lineitem_investment_"+lineid).val("1");
		jQuery("#lineitem_automated_"+lineid).val("1");
		/*jQuery("#lineitem_year_"+lineid).val("");
		jQuery("#lineitem_investment_"+lineid).val("0");
		jQuery("#lineitem_cost_"+lineid).val("");
		jQuery("#lineitem_time_"+lineid).val("");*/

	});

	/* Show investment and year when user click on NO on automated option and set active class to no and deactive class to yes */
	
	jQuery(document).on('click', '.automated_no', function (e) {
		e.preventDefault();
		jQuery(this).removeClass("no-deactived");
		jQuery(this).removeClass("no-static");
		jQuery(this).addClass("no-active");
		jQuery(this).parent().prev().find("a.automated_yes").removeClass("yes-static");
		jQuery(this).parent().prev().find("a.automated_yes").removeClass("yes-active");
		jQuery(this).parent().prev().find("a.automated_yes").addClass("yes-deactived");
		lineid=jQuery(this).attr("lineid");
		jQuery("#invsetment_yes_"+lineid).addClass("yes-active");
		jQuery("#invsetment_yes_"+lineid).removeClass("yes-deactived");
		jQuery("#invsetment_yes_"+lineid).removeClass("yes-static");
		jQuery("#invsetment_no_"+lineid).addClass("no-deactived");
		jQuery("#invsetment_no_"+lineid).removeClass("no-static");
		jQuery("#invsetment_no_"+lineid).removeClass("no-active");
		jQuery("#row2_"+lineid).hide();
		jQuery("#row3_"+lineid).hide();
		jQuery("#row4_"+lineid).hide();
		jQuery("#row5_"+lineid).hide();
		jQuery("#rowno-data_"+lineid).hide();
		jQuery("#rowno_"+lineid).show();

		jQuery("#lineitem_automated_"+lineid).val("0");
		jQuery("#lineitem_investment_"+lineid).val("0");
		/* jQuery("#lineitem_cost_"+lineid).val("");
		jQuery("#lineitem_time_"+lineid).val("");	 */	
		jQuery("#lineitem_benefit_"+lineid).val("0");

	});

	/* benefit data is displayed when yes is selected */
	//jQuery('.benefit_yes').on("click",function(e){
	jQuery(document).on('click', '.benefit_yes', function (e) {
		e.preventDefault();
		jQuery(this).removeClass("yes-deactived");
		jQuery(this).removeClass("yes-static");
		jQuery(this).addClass("yes-active");
		jQuery(this).parent().next().find("a.benefit_no").removeClass("no-static");
		jQuery(this).parent().next().find("a.benefit_no").removeClass("no-active");
		jQuery(this).parent().next().find("a.benefit_no").addClass("no-deactived");
		lineid=jQuery(this).attr("lineid");
		jQuery("#rowno-data_"+lineid).show();
		jQuery("#lineitem_benefit_"+lineid).val("1");

	});

	/* hide benefit data when no is selected */
	/* jQuery('.benefit_no').on("click",function(e){ */
	jQuery(document).on('click', '.benefit_no', function (e) {
		e.preventDefault();
		jQuery(this).removeClass("no-deactived");
		jQuery(this).removeClass("no-static");
		jQuery(this).addClass("no-active");
		jQuery(this).parent().prev().find("a.benefit_yes").removeClass("yes-static");
		jQuery(this).parent().prev().find("a.benefit_yes").removeClass("yes-active");
		jQuery(this).parent().prev().find("a.benefit_yes").addClass("yes-deactived");
		lineid=jQuery(this).attr("lineid");
		jQuery("#rowno-data_"+lineid).hide();
		jQuery("#lineitem_benefit_"+lineid).val("2");
		jQuery("#lineitem_benefit_for_"+lineid).val("");
	});

	/* jQuery(".benefit_data").on("click",function(e){ */
	jQuery(document).on('click', '.benefit_data', function (e) {	
		e.preventDefault();
		lineid=jQuery(this).attr("lineid");
		//jQuery(".radiobtn").removeClass("radio-active");
		if(jQuery(this).hasClass("radio-active"))
		{
			jQuery(this).removeClass("radio-active")
			if(jQuery(this).attr("rel")=="Other")
			{
				jQuery("#other_data_"+lineid).hide();
				jQuery("#lineitem_benefit_other_"+lineid).val("");
			}

			if(jQuery("#lineitem_benefit_for_"+lineid).val()!="")
			{
				new_vals="";
				ben_vals= new Array();
				ben_for=jQuery("#lineitem_benefit_for_"+lineid).val();
				ben_vals=ben_for.split(",");
				console.log(ben_vals);
				if(jQuery.inArray(jQuery(this).attr("rel"),ben_vals) !== -1)
				{
					var removeItem = jQuery(this).attr("rel");
					ben_vals.splice( $.inArray(removeItem,ben_vals) ,1 );
				}
				new_vals=ben_vals.join();
				jQuery("#lineitem_benefit_for_"+lineid).val(new_vals);
			}
		}
		else
		{
			jQuery(this).addClass("radio-active");
			if(jQuery(this).attr("rel")=="Other")
				jQuery("#other_data_"+lineid).show();

			if(jQuery("#lineitem_benefit_for_"+lineid).val()=="")
				ben_for=jQuery(this).attr("rel");
			else
				ben_for=jQuery("#lineitem_benefit_for_"+lineid).val()+","+jQuery(this).attr("rel");

			jQuery("#lineitem_benefit_for_"+lineid).val(ben_for);
		}
	});

	/* Show cost and time textbox when user click on YES on invsetment required option and set active class to yes and deactive class to no */
	/* jQuery(".invsetment_yes").on("click",function(e)
	{ */
	jQuery(document).on('click', '.invsetment_yes', function (e) {
		e.preventDefault();
		jQuery(this).removeClass("yes-deactived");
		jQuery(this).removeClass("yes-static");
		jQuery(this).addClass("yes-active");
		jQuery(this).parent().next().find("a.invsetment_no").removeClass("no-static");
		jQuery(this).parent().next().find("a.invsetment_no").removeClass("no-active");
		jQuery(this).parent().next().find("a.invsetment_no").addClass("no-deactived");
		lineid=jQuery(this).attr("lineid");
		jQuery("#row4_"+lineid).show();

		jQuery("#lineitem_investment_"+lineid).val("1");

	});

	/* Hide cost and time textbox when user click on NO on invsetment required option and set active class to no and deactive class to yes */
	/* jQuery(".invsetment_no").on("click",function(e)
	{ */
	jQuery(document).on('click', '.invsetment_no', function (e) {
		e.preventDefault();
		jQuery(this).removeClass("no-deactived");
		jQuery(this).removeClass("no-static");
		jQuery(this).addClass("no-active");
		jQuery(this).parent().prev().find("a.invsetment_yes").removeClass("yes-static");
		jQuery(this).parent().prev().find("a.invsetment_yes").removeClass("yes-active");
		jQuery(this).parent().prev().find("a.invsetment_yes").addClass("yes-deactived");
		lineid=jQuery(this).attr("lineid");
		jQuery("#row4_"+lineid).hide();

		jQuery("#lineitem_investment_"+lineid).val("0");
		//jQuery("#lineitem_cost_"+lineid).val("");
		//jQuery("#lineitem_time_"+lineid).val("");
	});

	/* set readonly in time input if values is in cost input */
	jQuery(".cost_input").on("click",function(e)
	{
		e.preventDefault();
		lineid=jQuery(this).attr("lineid");
		if(jQuery("#lineitem_cost_"+lineid).val()!="")
		{
			if(jQuery("#and_"+lineid).hasClass("and-active"))
				jQuery("#lineitem_time_"+lineid).attr("readonly",false);
			else
				jQuery("#lineitem_time_"+lineid).attr("readonly",true);
		}
		else
			jQuery("#lineitem_time_"+lineid).attr("readonly",false);
	});

	jQuery(".cost_input").on("focus",function(e)
	{
		e.preventDefault();
		jQuery(".cost_input").trigger("click");
	});

	jQuery(".cost_input").on("focusout",function(e)
	{
		e.preventDefault();
		jQuery(".cost_input").trigger("click");
	});

	/* set readonly in cost input if values is in time input */
	jQuery(".time_input").on("click",function(e)
	{
		e.preventDefault();
		lineid=jQuery(this).attr("lineid");

		if(jQuery("#lineitem_time_"+lineid).val()!="")
		{
			if(jQuery("#and_"+lineid).hasClass("and-active"))
				jQuery("#lineitem_cost_"+lineid).attr("readonly",false);
			else
				jQuery("#lineitem_cost_"+lineid).attr("readonly",true);
		}
		else
			jQuery("#lineitem_cost_"+lineid).attr("readonly",false);
	});

	jQuery(".time_input").on("focus",function(e)
	{
		e.preventDefault();
		jQuery(".time_input").trigger("click");
	});

	jQuery(".time_input").on("focusout",function(e)
	{
		e.preventDefault();
		jQuery(".time_input").trigger("click");
	});

	/* set readonly false in cost and time textbox when user click on '&'  */
	/*jQuery(".and").on("click",function(e)
	{
		e.preventDefault();
		lid=jQuery(this).attr("lineid");
		jQuery("#lineitem_cost_"+lid).attr("readonly",false);
		jQuery("#lineitem_time_"+lid).attr("readonly",false);
		jQuery("#lineitem_cost_"+lid).val("");
		jQuery("#lineitem_time_"+lid).val("");

	   if(jQuery("#and_"+lid).hasClass("and-active"))
	   {
			jQuery("#and_"+lid).removeClass("and-active");
			jQuery("#and_"+lid).addClass("and-static");
	   }
	   else
	   {
			jQuery("#and_"+lid).addClass("and-active");
			jQuery("#and_"+lid).removeClass("and-static");
	   }
	})*/
	 

	/* Horizontal rating slide when click on LOW */
	
	jQuery(document).on('click', '.text-low', function () {
		  lineid=jQuery(this).attr("lineid");
		  jQuery("#rating_"+lineid).removeAttr("class");
		  jQuery("#rating_"+lineid).addClass("slider-low rating_slide"); 
		  rating=jQuery(this).attr("rating"); 
		  jQuery("#lineitem_importance_"+lineid).val(rating);
	});

	/* Horizontal rating slide when click on MEDIUM */
	/* jQuery(".text-medium").on("click",function()
	{ */
	jQuery(document).on('click', '.text-medium', function () {
		lineid=jQuery(this).attr("lineid");
		jQuery("#rating_"+lineid).removeAttr("class");
		jQuery("#rating_"+lineid).addClass("slider-medium rating_slide"); 
		rating=jQuery(this).attr("rating"); 
		jQuery("#lineitem_importance_"+lineid).val(rating);
	});

	/* Horizontal rating slide when click on HIGH */

	jQuery(document).on('click', '.text-high', function () {
		lineid=jQuery(this).attr("lineid");
		jQuery("#rating_"+lineid).removeAttr("class");
		jQuery("#rating_"+lineid).addClass("slider-high rating_slide"); 
		rating = jQuery(this).attr("rating"); 		
		jQuery("#lineitem_importance_"+lineid).val(rating);
	});

	/* rating slide when click on horizontal bar move rating according to clicked position */
	jQuery(document).on('click', '.bar-outer', function (e) {	
		var offset = $(this).offset();
		pos=e.clientX - offset.left;
		lineid=jQuery(this).next().attr("id");
		jQuery("#"+lineid).removeAttr("class");
		//rating=jQuery(this).attr("text-high_"); 
		idtoUse = lineid.split("_");
		idV = idtoUse[1]; 
		if(pos<=59.33)
		{
			jQuery("#"+lineid).addClass("slider-low rating_slide");
			rating = jQuery('#text-low_'+idV).attr("rating");  
			jQuery("#lineitem_importance_"+idV).val(rating); 
		}
		else if((pos >59.33) && (pos <= 118.99))
		{
			jQuery("#"+lineid).addClass("slider-medium rating_slide");
			rating = jQuery('#text-medium_'+idV).attr("rating");  
			jQuery("#lineitem_importance_"+idV).val(rating); 
		}
		else if((pos > 118.99) && (pos <= 178))
		{
			jQuery("#"+lineid).addClass("slider-high rating_slide");
			rating = jQuery('#text-high_'+idV).attr("rating"); 
			jQuery("#lineitem_importance_"+idV).val(rating); 
		}
	}); 
	
	/* toggle note section in popup for category */
	jQuery("#note_heading").on("click",function(e)
	{ 
		e.preventDefault();
		jQuery("#lineitem_popup").css("overflow","hidden")
		jQuery("#lineitem_popup").animate({height:'0px'},500);
		jQuery(".note-section").animate({height:'400px'},500);
		jQuery(".note-content").show();

		/*jQuery("#lineitem_scroll").slideUp(500,function(){
			jQuery(".note-content").slideDown(500);
		});*/
		if(jQuery(this).css('display')=="none")
		{
			jQuery(this).show();

		}
		if($(".note-content .textarea-wrapper textarea").length == 0)
		{
			var textArea=$(".note-content textarea");
			textArea.wrap("<div class='textarea-wrapper' />");
			var textAreaWrapper=textArea.parent(".textarea-wrapper");
			textAreaWrapper.mCustomScrollbar({
				scrollInertia:0,
				advanced:{autoScrollOnFocus:false}
			});
			var hiddenDiv=$(document.createElement("div")),
				content=null;
			hiddenDiv.addClass("hiddendiv1");
			$("body").prepend(hiddenDiv);
			textArea.bind("keyup",function(e){
				content=$(this).val();
				var clength=content.length;
				var cursorPosition=textArea.getCursorPosition();
				content="<span>"+content.substr(0,cursorPosition)+"</span>"+content.substr(cursorPosition,content.length);
				content=content.replace(/\n/g,"<br />");
				hiddenDiv.html(content+"<br />");
				$(this).css("height",hiddenDiv.height());
				textAreaWrapper.mCustomScrollbar("update");
				var hiddenDivSpan=hiddenDiv.children("span"),
					hiddenDivSpanOffset=0,
					viewLimitBottom=(parseInt(hiddenDiv.css("min-height")))-hiddenDivSpanOffset,
					viewLimitTop=hiddenDivSpanOffset,
					viewRatio=Math.round(hiddenDivSpan.height()+textAreaWrapper.find(".mCSB_container").position().top);
				if(viewRatio>viewLimitBottom || viewRatio<viewLimitTop){
					if((hiddenDivSpan.height()-hiddenDivSpanOffset)>0){
						textAreaWrapper.mCustomScrollbar("scrollTo",hiddenDivSpan.height()-hiddenDivSpanOffset);
					}else{
						textAreaWrapper.mCustomScrollbar("scrollTo","top");
					}
				}
			});
		}
		else{
			jQuery(".note-content .textarea-wrapper textarea").mCustomScrollbar("update");
		}
	});

	/* toggle note section in popup for category */
	jQuery("#cancel_note").on("click",function(e)
	{
		e.preventDefault();
		jQuery("#lineitem_popup").css("overflow","")
		jQuery(".note-content").hide();
		jQuery(".note-section").animate({height:'20px'},500);
		jQuery("#lineitem_popup").animate({height:'380px'},500);
	});

	/* save category note data to tooldata */
	jQuery("#save_note").on("click",function(e)
	{
		e.preventDefault();
		catid=jQuery(this).attr("catid");
		jQuery.ajax({
			   url:  path+"/savedata.php?action=SaveCategoryNote&catid="+escape(catid),
				type: "POST",
				data: jQuery("#frmnote").serialize()
			   }).done(function(data) {
					//alert(data);
					jQuery("#lineitem_popup").css("overflow","")
					jQuery(".note-content").hide();
					jQuery(".note-section").animate({height:'20px'},500);
					jQuery("#lineitem_popup").animate({height:'380px'},500);
			});
	});

	/* save data and mark category as complete whren use click on category complete button */
	 jQuery("#category-complete").click(function(e)
	{ 
	//jQuery(document).on('click', '#category-complete', function (e) {
		e.preventDefault();
		/*jQuery(".edit-items").fadeOut(500);*/
		eleid=jQuery(".block-section .active").attr("id");		
		parent_cls=jQuery(".block-section .active").parent().attr("class");
		if(parent_cls=="block-section custom_category")
			type="CustomCategory";
		else
			type="DefaultCategory";

		catid=jQuery(".block-section .active").attr("catid");
		if(typeof(catid)!="undefined")
		{
			jQuery.ajax({
			   url:  path+"/savedata.php?act=LineItemStatus&action=IncludeCategory&catid="+escape(catid)+"&cat_status=1",
			   }).done(function(data) {    
				   if(data == 0)
				   { 
                        jQuery(".overlay").fadeIn(500);
						jQuery("#big_popup").hide();
						jQuery("#small_popup").html("");
						jQuery("#small_popup").show();
						jQuery("#popup").fadeIn(500);
						jQuery(".popupbox").addClass("smallpopup");
						jQuery("#small_popup").load(path+"/msg.php?type=linenoselect",function(data){

						});	 	
					   jQuery(".block-section .active").removeClass("block-complete");
					   jQuery(".block-section .active").removeClass("block-notrequired"); 
					   /* block-incomplete */
					   jQuery(".block-section .active").addClass("block-inscope");
				   }				   
				   else if(data == 1)
				   {    
				   
					   jQuery(".block-section .active").removeClass("block-incomplete");					    
					   jQuery(".block-section .active").removeClass("block-notrequired");
					   jQuery(".block-section .active").removeClass("block-inscope");
					   jQuery(".block-section .active").addClass("block-complete");
				   }
				   else if(data == 2)
				   {
					jQuery(".block-section .active").removeClass("block-incomplete");
					jQuery(".block-section .active").removeClass("block-complete");
					jQuery(".block-section .active").addClass("block-notrequired");
					jQuery("#include_yes").removeClass("active");
					jQuery("#include_no").removeClass("deactivated");
					jQuery("#include_yes").addClass("deactivated");
					jQuery("#include_no").addClass("active");
					jQuery(".edit-items").hide();
				   }				   
				   else if(data == 5)
				   {
											
						jQuery(".overlay").fadeIn(500);
						jQuery("#big_popup").hide();
						jQuery("#small_popup").html("");
						jQuery("#small_popup").show();
						jQuery("#popup").fadeIn(500);
						jQuery(".popupbox").addClass("smallpopup");
						jQuery("#small_popup").load(path+"/msg.php?type=linenoselect",function(data){

						});
						
					   jQuery(".block-section .active").removeClass("block-complete");
					   jQuery(".block-section .active").removeClass("block-notrequired");
					   jQuery(".block-section .active").addClass("block-incomplete");
				   }
				   
				   else if(data == 6)
				   { 
						jQuery(".block-section .active").removeClass("block-incomplete");
						jQuery(".block-section .active").removeClass("block-notrequired");
					    jQuery(".block-section .active").addClass("block-complete");
				   }else{
				     
						jQuery(".block-section .active").removeClass("block-complete");
						jQuery(".block-section .active").removeClass("block-notrequired");
						jQuery(".block-section .active").addClass("block-incomplete");
				   }


			   });
		}
	});

	/* close popup */
	jQuery("#close_popup").on("click",function(e){
		e.preventDefault(); 
		if(jQuery("#small_popup").css("display")=="none") {

			jQuery("#big_popup").hide();
			if(jQuery.trim(jQuery("#small_popup").html())!="") {
				jQuery(".popupbox").addClass("smallpopup");
				jQuery("#small_popup").show();
			} else {
				jQuery("#small_popup").hide();
				jQuery("#popup").fadeOut(500);
				jQuery(".overlay").fadeOut(500);
			}

		} else if(jQuery("#big_popup").css("display")=="none") {
			jQuery("#small_popup").hide();
			jQuery("#small_popup").html("");

			if(jQuery("#small_popup").attr("display")) {

				if(jQuery.trim(jQuery("#big_popup").html())!="") {
					jQuery(".popupbox").removeClass("smallpopup");
					jQuery("#big_popup").show();

					if(jQuery.isNumeric(jQuery("#small_popup").attr("display"))) {
						id=jQuery("#small_popup").attr("display")
						jQuery("#name_"+id).focus();
					}
				} else {
					jQuery("#big_popup").hide();
					jQuery("#popup").fadeOut(500);
					jQuery(".overlay").fadeOut(500);
				}
				jQuery("#small_popup").removeAttr("display");

			} else {
				jQuery("#big_popup").html("");
				jQuery("#popup").fadeOut(500);
				jQuery(".overlay").fadeOut(500);
			}

		} else {
			jQuery("#popup").fadeOut(500);
			jQuery(".overlay").fadeOut(500);
		}

		if(jQuery.trim(jQuery("#custom_catname").val())=="") {
			jQuery("#custom_catname").focus();
		}
		
		var close_popup1ex = jQuery("#close_popup1").attr('href');
		var popupID2Value  = jQuery("#popupID2").val();
		//alert(popupID2Value);
		 if(close_popup1ex == "#" && popupID2Value == 0) {
		        /* jQuery(".overlay").fadeIn(500);
				jQuery("#big_popup1").hide();
				jQuery("#small_popup1").html("");
				jQuery("#small_popup1").show();
				jQuery("#popup1").fadeIn(500);
				jQuery(".popupbox").addClass("smallpopup");
				jQuery("#small_popup1").load(path+"/msg.php?type=warningPopupOnClose",function(data){ 
				}); */ 
		 }
	});
	
	jQuery("#close_popup1").on("click",function(e)
	{ 
	    jQuery(".overlay").fadeOut(500);
		jQuery("#big_popup1").hide();
		jQuery("#small_popup1").html("");
		jQuery("#small_popup1").hide();
		jQuery("#popup1").fadeOut(500);   
	});

	/* save tool data from session to databse */

	jQuery(document).on('click', '#save_progress_head', function (e) {	 
		e.preventDefault();
		catid = jQuery(".block-section .active").attr("catid");

		jQuery.ajax({
			   url:  path+"/savedata.php?action=SaveToolData&catid="+escape(catid),
			   }).done(function(data) {
			   
				   if(data!="")
				   {
					   if(data=="0")
					   {
						   jQuery(".block-section .active").removeClass("block-complete");
						   jQuery(".block-section .active").removeClass("block-notrequired");
						   jQuery(".block-section .active").addClass("block-incomplete");
					   }
					   else if(data=="1")
					   {
						   jQuery(".block-section .active").removeClass("block-incomplete");
						   jQuery(".block-section .active").removeClass("block-notrequired");
						   jQuery(".block-section .active").addClass("block-complete");
					   }
					   else if(data=="2")
					   {
						   jQuery(".block-section .active").removeClass("block-incomplete");
						   jQuery(".block-section .active").removeClass("block-complete");
						   jQuery(".block-section .active").addClass("block-notrequired");
					   }
					   else if(data == "5")
					   {

     						//alert('You have to select a line item to complete a functional area.');
						   jQuery(".block-section .active").removeClass("block-complete");
						   jQuery(".block-section .active").removeClass("block-notrequired");
						   jQuery(".block-section .active").addClass("block-incomplete");
					   }
					   
					   else if(data == "6")
					   {
							jQuery(".block-section .active").removeClass("block-incomplete");
							jQuery(".block-section .active").removeClass("block-notrequired");
							jQuery(".block-section .active").addClass("block-complete");
					   }else{
							jQuery(".block-section .active").removeClass("block-complete");
							jQuery(".block-section .active").removeClass("block-notrequired");
							jQuery(".block-section .active").addClass("block-incomplete");
					   }
				   }
				   
					jQuery(".overlay").fadeIn(500);
					jQuery("#popup").fadeIn(500);
					jQuery("#big_popup").hide();
					jQuery("#close_popup").hide();
					jQuery("#big_popup").html("");
					jQuery("#small_popup").html("");
					jQuery(".popupbox").addClass("smallpopup");
					jQuery("#small_popup").load(path+"/msg.php?type=saveprogress",function(data){
						jQuery("#small_popup").show().delay(3000).fadeOut(100,function(){
							jQuery("#small_popup").html("");
							jQuery(".overlay").fadeOut(100);
							jQuery("#popup").hide();
							jQuery("#close_popup").show();
						});

						//jQuery("#close_popup").show();
					});

			   });
	});

	/* trigger save progress when user click on save progress bottom or in popup */

	jQuery(document).on('click', '#save_progress_bottom', function (e) {
		e.preventDefault();
		jQuery("#save_progress_head").trigger("click");
		
	});


/* ******* For save all progress button on functional area popup ***************/
	jQuery(document).on('click', '#save_progress_popup', function (e) {
		e.preventDefault();

		/*jQuery(".edit-items").fadeOut(500);*/
		eleid=jQuery(".block-section .active").attr("id");
		parent_cls=jQuery(".block-section .active").parent().attr("class");
		if(parent_cls=="block-section custom_category")
			type="CustomCategory";
		else
			type="DefaultCategory";

		catid=jQuery(".block-section .active").attr("catid");
		if(typeof(catid)!="undefined") {

			jQuery.ajax({
			   url:  path+"/savedata.php?act=LineItemStatusCustom&action=IncludeCategoryCustom&catid="+escape(catid)+"&cat_status=3",
			   }).done(function(data) {

				   if(data == 0) {
                        jQuery(".overlay").fadeIn(500);
						jQuery("#big_popup").hide();
						jQuery("#small_popup").html("");
						jQuery("#small_popup").show();
						jQuery("#popup").fadeIn(500);
						jQuery(".popupbox").addClass("smallpopup");
						jQuery("#small_popup").load(path+"/msg.php?type=linenoselect",function(data){

						});	 	
					   jQuery(".block-section .active").removeClass("block-complete");
					   jQuery(".block-section .active").removeClass("block-notrequired"); 
					   /* block-incomplete */
					   jQuery(".block-section .active").addClass("block-inscope");

				   } else if(data == 1) {
				   
					   jQuery(".block-section .active").removeClass("block-incomplete");					    
					   jQuery(".block-section .active").removeClass("block-notrequired");
					   jQuery(".block-section .active").removeClass("block-inscope");
					   jQuery(".block-section .active").addClass("block-complete");

				   } else if(data == 2) {

					jQuery(".block-section .active").removeClass("block-incomplete");
					jQuery(".block-section .active").removeClass("block-complete");
					jQuery(".block-section .active").addClass("block-notrequired");
					jQuery("#include_yes").removeClass("active");
					jQuery("#include_no").removeClass("deactivated");
					jQuery("#include_yes").addClass("deactivated");
					jQuery("#include_no").addClass("active");
					jQuery(".edit-items").hide();

				   } else if(data == 5) {
											
						jQuery(".overlay").fadeIn(500);
						jQuery("#big_popup").hide();
						jQuery("#small_popup").html("");
						jQuery("#small_popup").show();
						jQuery("#popup").fadeIn(500);
						jQuery(".popupbox").addClass("smallpopup");
						jQuery("#small_popup").load(path+"/msg.php?type=linenoselect",function(data){

						});
						
					   jQuery(".block-section .active").removeClass("block-complete");
					   jQuery(".block-section .active").removeClass("block-notrequired");
					   jQuery(".block-section .active").addClass("block-inscope");

				   } else if(data == 6) {
						jQuery(".block-section .active").removeClass("block-incomplete");
						jQuery(".block-section .active").removeClass("block-notrequired");
					    jQuery(".block-section .active").addClass("block-complete");

				   } else {

						jQuery(".block-section .active").removeClass("block-complete");
						jQuery(".block-section .active").removeClass("block-notrequired");
						jQuery(".block-section .active").addClass("block-inscope");
				   }
			   });
		}
		jQuery("#popupID2").val(1);
//		jQuery("#close_popup").click();

		jQuery("#popup").fadeOut(500);
		jQuery(".overlay").fadeOut(500);

	});
	
	// restrict access to stage one sections when user in stage 2
	jQuery(document).on('click', '#com_noaccess,#pro_noaccess,#feature_noaccess', function (e) {	
	    e.preventDefault();
		/*jQuery(".overlay").show();
		jQuery("#big_popup").hide();
		jQuery("#close_popup").hide();
		jQuery("#small_popup").html("");
		jQuery(".popupbox").addClass("smallpopup");
		jQuery("#small_popup").load( "/wp-content/plugins/skopes/frontend/theme/Black/msg.php?type=noaccress",function(data){
			jQuery("#popup").fadeIn(500);
			jQuery(".overlay").fadeIn(500);
			jQuery("#small_popup").show(); 
			jQuery("#close_popup").show();
		});  */
	});

	/* finished and show report */
	jQuery("#finished_head").on("click",function(e)
	{
		e.preventDefault();
		 jQuery.ajax({
			   url:  path+"/savedata.php?action=UpdateStatus",
			   }).done(function(data) {
			   
				   /*if(data=="") */
				   document.location="?act=getreport";
					//document.location="?finish=yes";
			}); 
	});

	jQuery("#finished_bottom").on("click",function(e)
	{
		jQuery("#finished_head").trigger("click");
	});

	/* feedback slide up and down on dashboard page */
	jQuery("#give_feedback").on("click",function(e)
	{
	   e.preventDefault();
	   if(jQuery("#frmfeedback").css("display")=="none")
			jQuery("#frmfeedback").slideDown(200,function(){
				jQuery("#feedback").focus();
			});
	   else
			jQuery("#frmfeedback").slideUp(200);

	});

	/* submit feedback by email */
	jQuery("#submit_feedback").on("click",function(e)
	{
	   e.preventDefault();
	   if(jQuery("#feedback").length>0)
	   {
		   if(jQuery.trim(jQuery("#feedback").val())!="")
		   {
			   jQuery.ajax({
					   url:  path+"/savedata.php?action=SubmitFeedback&feedback="+jQuery("#feedback").val(),
					   }).done(function(data) {
						   if(data=="Send")
						   {
							   document.location="?act=dashboard&type=feedback";
						   }
					});
		   }
		   else
				jQuery("#feedback").focus();
	   }
	});

});

/* function to set default attributes as disable for moving direction for default nad custom category */
function _intialize(cls)
{
	if(jQuery("."+cls+" .block").length>0)
	{
		/* set top disable for first 4 indexindex */
		jQuery("."+cls+" .block").first().attr("left","disable");

		/* set last element right position to disable */
		jQuery("."+cls+" .block").last().attr("right","disable");

		/* set top and bottom 4 disable for default category */
		if(cls=="default_category")
		{
			jQuery("."+cls+" .block:lt("+NO_ELE+")").attr("top","disable");
			btm4=jQuery("."+cls+" .block").length-parseInt(NO_ELE)-parseInt(1);
			jQuery("."+cls+" .block:gt("+btm4+")").attr("bottom","disable");
		}
		else /* set top and bottom disable for custom category */
		{
			if(parseInt(jQuery('.custom_category .block').length)>NO_ELE)
				gt=parseInt(jQuery('.custom_category .block').length)-parseInt(NO_ELE);
			else
				gt=0;

			jQuery(".custom_category .block:gt("+parseInt(gt-1)+")").attr("bottom","disable");

			if((jQuery(".default_category .block").length%NO_ELE)!=0)
			{

				for(i=NO_ELE;i>(jQuery(".default_category .block").length%NO_ELE);i--)
				{
					jQuery('#custom_cat'+(jQuery(".default_category .block").length+i)).attr('top','disable');
				}
			}

			last4=NO_ELE;
			if(jQuery(".default_category .block").length%NO_ELE!=0)
				last4=jQuery(".default_category .block").length%NO_ELE;

			ind=parseInt(jQuery(".default_category .block").length)-parseInt(last4);
			if(jQuery(".custom_category .block").length<=NO_ELE)
			{
				newind=ind+parseInt(jQuery(".custom_category .block").length)+1;
				for(i=ind+1;i<newind;i++)
				{
					jQuery(".default_category .block[index="+i+"]").removeAttr("bottom");
					//jQuery(".default_category .block[index="+i+"]").addClass("bottom_"+i);
				}
			}
			else
			{
				for(i=ind+1;i<=jQuery(".default_category .block").length;i++)
				{
					jQuery(".default_category .block[index="+i+"]").removeAttr("bottom");
					//jQuery(".default_category .block[index="+i+"]").addClass("bottom_"+i);
				}
			}
		}

		mycnt=0;
		total=jQuery(".default_category .block").length;
		/* set left and right disable for according to its index for each block */
		jQuery("."+cls+" .block").each(function()
		{
			jQuery(this).attr("ind",jQuery(this).index());
			if((parseInt(jQuery(this).attr("ind"))+1)%NO_ELE==0)
			{
				jQuery(this).attr("right","disable");
				jQuery(this).next().attr("left","disable");
			}
			if(cls=="custom_category")
			{
				jQuery(this).attr("index",parseInt(total)+parseInt(mycnt)+1);
				mycnt++;
			}
		});

		/* function to set class for moving direction according to top,bottom,left and right attribute */
		_checkMove();
	}
}

/* function to move frame according to direction */
function _moveFrame(pos,cls,oldInd)
{
	/* by default hide edit and complete functinal area */
	jQuery(".edit-items").hide();

	if(pos=="right")
		jQuery(".hoverblock").animate({left: "+=80px"},{duration:500});
	else if(pos=="left")
		jQuery(".hoverblock").animate({left: "-=80px"},{duration:500});
	else if(pos=="bottom")
	{
		if(cls=="block-section custom_category")
		{
			if(jQuery(".block[index="+oldInd+"]").parent().attr("class")=="block-section custom_category")
				jQuery(".hoverblock").animate({top: "+=80px"},{duration:500});
			else
				jQuery(".hoverblock").animate({top: "+=162px"},{duration:500});
		}
		else
			jQuery(".hoverblock").animate({top: "+=80px"},{duration:500});

	}
	else if(pos=="top")
	{
		if(cls=="block-section default_category")
		{
			if(jQuery(".block[index="+oldInd+"]").parent().attr("class")=="block-section default_category")
				jQuery(".hoverblock").animate({top: "-=80px"},{duration:500});
			else
				jQuery(".hoverblock").animate({top: "-=162px"},{duration:500});
		}
		else
			jQuery(".hoverblock").animate({top: "-=80px"},{duration:500});
	}

	/* set active category name */
	fnSetCategoryname();
}

 /* function to set class for moving direction 
    according to top,bottom,left and right attribute */
function _checkMove()
{
	if(jQuery(".block-section .active").attr("top"))
	{
		jQuery(".hoverblock").find("span.top").addClass("top-disable");
		jQuery(".hoverblock").find("span.top-disable").removeClass("top");
	}
	else
	{
		jQuery(".hoverblock").find("span.top-disable").addClass("top");
		jQuery(".hoverblock").find("span.top").removeClass("top-disable");
	}

	if(jQuery(".block-section .active").attr("left"))
	{
		jQuery(".hoverblock").find("span.left").addClass("left-disable");
		jQuery(".hoverblock").find("span.left-disable").removeClass("left");
	}
	else
	{
		jQuery(".hoverblock").find("span.left-disable").addClass("left");
		jQuery(".hoverblock").find("span.left").removeClass("left-disable");
	}

	if(jQuery(".block-section .active").attr("right"))
	{
		jQuery(".hoverblock").find("span.right").addClass("right-disable");
		jQuery(".hoverblock").find("span.right-disable").removeClass("right");
	}
	else
	{
		jQuery(".hoverblock").find("span.right-disable").addClass("right");
		jQuery(".hoverblock").find("span.right").removeClass("right-disable");
	}

	if(jQuery(".block-section .active").attr("bottom"))
	{
		jQuery(".hoverblock").find("span.bottom").addClass("bottom-disable");
		jQuery(".hoverblock").find("span.bottom-disable").removeClass("bottom");
	}
	else
	{
		jQuery(".hoverblock").find("span.bottom-disable").addClass("bottom");
		jQuery(".hoverblock").find("span.bottom").removeClass("bottom-disable");
	}
}

/* function to validate register form */
function fnvalidate_register()
{
    if(jQuery.trim(jQuery("#firstname").val())=="")
	{
		jQuery("#firstname_error").html("Enter first name");
		jQuery("#firstname").focus();
		return false;
	}
	else
		jQuery("#firstname_error").html("");

	if(jQuery.trim(jQuery("#lastname").val())=="")
	{
		jQuery("#lastname_error").html("Enter last name");
		jQuery("#lastname").focus();
		return false;
	}
	else
		jQuery("#lastname_error").html("");
	if(jQuery.trim(jQuery("#companyname").val())=="")
	{
		jQuery("#companyname_error").html("Enter company name");
		jQuery("#companyname").focus();
		return false;
	}
	else
		jQuery("#companyname_error").html("");

	

	if(jQuery.trim(jQuery("#email").val())=="")
	{
		jQuery("#email_error").html("Enter email address");
		jQuery("#email").focus();
		return false;
	}
	else
	{
		jQuery("#email_error").html("");
		var a = jQuery("#email").val();
		var f = /^[a-zA-Z0-9_.-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{1,4}$/;
		if(!f.test(a)){
			$("#email_error").html("Enter correct email address");
			$("#email").focus();
			return false;
		}
	}
	
	var passwordval = jQuery.trim(jQuery("#password").val());
	
	if(passwordval=="")
	{
		jQuery("#password_error").html("Enter password");
		jQuery("#password").focus();
		return false; 
	}
	else if( (passwordval.length > 0) && (passwordval.length < 6)){
		jQuery("#password_error").html("Password must be 6 character long");
		jQuery("#password").focus();
		return false;
	}
	else
		jQuery("#password_error").html("");

	if(jQuery.trim(jQuery("#cpassword").val())=="")
	{
		jQuery("#cpassword_error").html("Enter confirm password");
		jQuery("#cpassword").focus();
		return false;
	}
	else
		jQuery("#cpassword_error").html("");

	if(jQuery.trim(jQuery("#password").val()) != jQuery.trim(jQuery("#cpassword").val()))
	{
		jQuery("#password_error").html("Password and confirm password does not match");
		jQuery("#cpassword").val("");
		jQuery("#cpassword").focus();
		return false;
	}
	else
		jQuery("#password_error").html("");

	return true;
}

/* function to validate login form */
function fnvalidate_login()
{
	if(jQuery.trim(jQuery("#login_email").val())=="")
	{
		jQuery("#login_email_error").html("Enter email address");
		jQuery("#login_email").focus();
		return false;
	}
	else
	{
		jQuery("#login_email_error").html("");
		var a = jQuery("#login_email").val();
		var f = /^[a-zA-Z0-9_.-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{1,4}$/;
		if(!f.test(a)){
			$("#login_email_error").html("Enter correct email address");
			$("#login_email").focus();
			return false;
		}
	}

	if(jQuery.trim(jQuery("#login_password").val())=="")
	{
		jQuery("#login_password_error").html("Enter password");
		jQuery("#login_password").focus();
		return false;
	}
	else
		jQuery("#login_password_error").html("");

	return true;
}

/* function to validate forgot password form */
function fnvalidate_forgotpwd()
{
	if(jQuery.trim(jQuery("#email").val())=="")
	{
		jQuery("#fogotemail_error").html("Enter email address");
		jQuery("#email").focus();
		return false;
	}
	else
	{
		jQuery("#fogotemail_error").html("");
		var a = jQuery("#email").val();
		var f = /^[a-zA-Z0-9_.-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{1,4}$/;
		if(!f.test(a)){
			$("#fogotemail_error").html("Enter correct email address");
			$("#email").focus();
			return false;
		}
	}
	$("#close_popup").hide();
	return true;
}

/* function to set active category name */
function fnSetCategoryname()
{
	active_catname=jQuery(".block-section .active").attr("catname");
	if(!active_catname)
		active_catname="Custom Category";
	jQuery("#catname").fadeOut(0);
	jQuery("#catname").html(active_catname);
	jQuery("#catname").fadeIn(700);

	catid=jQuery(".block-section .active").attr("catid");
	parent_cls=jQuery(".block-section .active").parent().attr("class");
	if(parent_cls=="block-section custom_category")
		type="CustomCategory";
	else
		type="DefaultCategory";

	if(!catid)
		catid="";

	jQuery("#wrapper").addClass("ccpage");
	jQuery("#catdesc .box").html("<h5 class='boxheading'>Loading...</h5>");
	jQuery("#catdesc .box").load(path+"/catdata.php?catid="+catid+"&type="+type,"",function(data){			
			if(jQuery(".block-section .active").hasClass("block-complete"))
			{
				jQuery("#include_no").addClass("deactivated");
				jQuery("#include_no").removeClass("active");
				jQuery("#include_yes").addClass("active");
				jQuery("#include_yes").removeClass("deactivated");
				jQuery(".edit-items").show();
			}
			else if(jQuery(".block-section .active").hasClass("block-notrequired"))
			{
				jQuery("#include_yes").addClass("deactivated");
				jQuery("#include_yes").removeClass("active");
				jQuery("#include_no").addClass("active");
				jQuery("#include_no").removeClass("deactivated");
				jQuery(".edit-items").hide();
			}
			else
			{
				if($(".block-section .active .block-inner span#complete_cnt_"+catid).html()>=0)
				{
					jQuery("#include_no").addClass("deactivated");
					jQuery("#include_no").removeClass("active");
					jQuery("#include_yes").addClass("active");
					jQuery("#include_yes").removeClass("deactivated");
					jQuery(".edit-items").show();
				}
				else
					jQuery(".edit-items").hide();
			}

			if(jQuery("#hiddendiv").length==0)
			{
				var hiddenDiv=$(document.createElement("div"));
				jQuery(".hiddendiv").attr("id","hiddendiv");
				content=null;
			}
			else
			{
				var hiddenDiv=jQuery("#hiddendiv");
				content=$(this).val();
			}

			if(parent_cls=="block-section custom_category")
			{
				var textArea=$(".textarea_div #custom_catdesc");
				textArea.wrap("<div class='textarea-wrapper' />");
				var textAreaWrapper=textArea.parent(".textarea-wrapper");
				textAreaWrapper.mCustomScrollbar({
					scrollInertia:0,
					advanced:{autoScrollOnFocus:false}
				});

				hiddenDiv.addClass("hiddendiv");
				$("body").prepend(hiddenDiv);
				textArea.bind("keyup",function(e)
				{
					content=$(this).val();
					var clength=content.length;
					var cursorPosition=textArea.getCursorPosition();
					content="<span>"+content.substr(0,cursorPosition)+"</span>"+content.substr(cursorPosition,content.length);
					content=content.replace(/\n/g,"<br />");
					hiddenDiv.html(content+"<br />");					
					$(this).css("height",hiddenDiv.height());
					textAreaWrapper.mCustomScrollbar("update");
					var hiddenDivSpan=hiddenDiv.children("span"),
						hiddenDivSpanOffset=0,
						viewLimitBottom=(parseInt(hiddenDiv.css("min-height")))-hiddenDivSpanOffset,
						viewLimitTop=hiddenDivSpanOffset,
						viewRatio=Math.round(hiddenDivSpan.height()+textAreaWrapper.find(".mCSB_container").position().top);
					if(viewRatio>viewLimitBottom || viewRatio<viewLimitTop){
						if((hiddenDivSpan.height()-hiddenDivSpanOffset)>0){
							textAreaWrapper.mCustomScrollbar("scrollTo",hiddenDivSpan.height()-hiddenDivSpanOffset);
						}else{
							textAreaWrapper.mCustomScrollbar("scrollTo","top");
						}
					}
				});
			}
			else
				jQuery("#catdesc_scroll").mCustomScrollbar();

	});

}

function CustomScroll()
{
	var textArea=$("#custom_catdesc");
	textArea.wrap("<div class='textarea-wrapper' />");
	var textAreaWrapper=textArea.parent(".textarea-wrapper");
	textAreaWrapper.mCustomScrollbar({
		scrollInertia:0,
		advanced:{autoScrollOnFocus:false}
	});
	var hiddenDiv=$(document.createElement("div")),
		content=null;
	hiddenDiv.addClass("hiddendiv");
	$("body").prepend(hiddenDiv);
	textArea.bind("keyup",function(e)
	{
		content=$(this).val();
		var clength=content.length;
		var cursorPosition=textArea.getCursorPosition();
		content="<span>"+content.substr(0,cursorPosition)+"</span>"+content.substr(cursorPosition,content.length);
		content=content.replace(/\n/g,"<br />");
		hiddenDiv.html(content+"<br />");
		$(this).css("height",hiddenDiv.height());
		textAreaWrapper.mCustomScrollbar("update");
		var hiddenDivSpan=hiddenDiv.children("span"),
			hiddenDivSpanOffset=0,
			viewLimitBottom=(parseInt(hiddenDiv.css("min-height")))-hiddenDivSpanOffset,
			viewLimitTop=hiddenDivSpanOffset,
			viewRatio=Math.round(hiddenDivSpan.height()+textAreaWrapper.find(".mCSB_container").position().top);
		if(viewRatio>viewLimitBottom || viewRatio<viewLimitTop){
			if((hiddenDivSpan.height()-hiddenDivSpanOffset)>0){
				textAreaWrapper.mCustomScrollbar("scrollTo",hiddenDivSpan.height()-hiddenDivSpanOffset);
			}else{
				textAreaWrapper.mCustomScrollbar("scrollTo","top");
			}
		}
	});
} 

$.fn.getCursorPosition=function()
{
	var el=$(this).get(0),
		pos=0;
	if("selectionStart" in el){
		pos=el.selectionStart;
	}else if("selection" in document){
		el.focus();
		var sel=document.selection.createRange(),
			selLength=document.selection.createRange().text.length;
		sel.moveStart("character",-el.value.length);
		pos=sel.text.length-selLength;
	}
	return pos;
}

/* function to validate lineitem data */
function fnvalidatelineitem(lineid)
{
	jQuery("#lineitem_error_"+lineid).html("");

	if(jQuery("#lineitem_automated_"+lineid).val()=="1")
	{
		if(jQuery("#lineitem_year_"+lineid).val()=="")
		{
			jQuery("#lineitem_error_"+lineid).html("Enter year");
			jQuery("#lineitem_year_"+lineid).focus();
			return false;
		}
		else
			jQuery("#lineitem_error_"+lineid).html("");

		if(jQuery("#lineitem_year_"+lineid).val()!="")
		{
			var a = jQuery("#lineitem_year_"+lineid).val();
			var f = /^[0-9]*$/;
			if(!f.test(a)){
				jQuery("#lineitem_error_"+lineid).html("Enter correct year");
				jQuery("#lineitem_year_"+lineid).focus();
				return false;
			}
		}
		else
			jQuery("#lineitem_error_"+lineid).html("");
	}
	else if(jQuery("#lineitem_automated_"+lineid).val()=="0")
	{
		if(jQuery("#lineitem_benefit_"+lineid).val()=="0")
		{
			jQuery("#lineitem_error_"+lineid).html("Select Yes/No for is this benefit?");
			return false;
		}
		else if(jQuery("#lineitem_benefit_"+lineid).val()=="1")
		{
			if(jQuery("#lineitem_benefit_for_"+lineid).val()=="")
			{
				jQuery("#lineitem_error_"+lineid).html("Select benefit for");
				return false;
			}
			else
			{
				benifit_for=jQuery("#lineitem_benefit_for_"+lineid).val();
				ben_vals=benifit_for.split(",");
				if(jQuery.inArray("Other",ben_vals) !== -1)
				{
					if(jQuery("#lineitem_benefit_other_"+lineid).val()=="")
					{
						jQuery("#lineitem_error_"+lineid).html("Enter value for Other");
						jQuery("#lineitem_benefit_other_"+lineid).focus();
						return false;
					}
				}
			}
		}

	}

	
	if(jQuery("#lineitem_investment_"+lineid).val()==1)
	{
		if(jQuery("#lineitem_cost_"+lineid).val()=="" && jQuery("#lineitem_time_"+lineid).val()=="")
		{
			jQuery("#lineitem_error_"+lineid).html("Enter cost or time");
			jQuery("#lineitem_cost_"+lineid).focus();
			return false;
		}
		else
			jQuery("#lineitem_error_"+lineid).html("");
			
        
		if(jQuery("#lineitem_cost_"+lineid).val()!="")
		{
			var a = jQuery("#lineitem_cost_"+lineid).val();
			var f = /^[0-9]+[0-9]*$/;
			if(!f.test(a)){
				jQuery("#lineitem_error_"+lineid).html("Enter correct cost");
				jQuery("#lineitem_cost_"+lineid).focus();
				return false;
			}
		}
		else
			jQuery("#lineitem_error_"+lineid).html("");

		if(jQuery("#lineitem_time_"+lineid).val()!="")
		{
			var a = jQuery("#lineitem_time_"+lineid).val();
			var f = /^[0-9]+[0-9]*$/;
			if(!f.test(a)){
				jQuery("#lineitem_error_"+lineid).html("Enter correct time");
				jQuery("#lineitem_time_"+lineid).focus();
				return false;
			}
		}
		else
			jQuery("#lineitem_error_"+lineid).html("");
			
		if(jQuery("#lineitem_cost_"+lineid).val()==0 && jQuery("#lineitem_time_"+lineid).val()==0)
		{
			
				jQuery("#lineitem_error_"+lineid).html("Cost or Time must be >0");
				jQuery("#lineitem_cost_"+lineid).focus();
				return false;
			
		}
		else
			jQuery("#lineitem_error_"+lineid).html("");
	}

	return true;
}

function checkShStatus(){
	
	var result="";
    $.ajax({
      url:path+"/savedata.php?action=shstatus",
      async: false,  
      success:function(data) {
         result = data; 
      }
   });
   return result;
}


/*********************** Code Written By Royal Tyagi ****************************/

/* *****************
* Code to put confirm box on the close of popup on functional area page.
******************/

$(document).on('click', '#close_features_popup', function(e){
	e.preventDefault();

	// If condition to check that big pop is open or not because we have another pop on same page but another one in small popup
	if($('#popup').find('#big_popup').css('display') == 'none' ) {

		jQuery("#popup").fadeOut(500);
		jQuery(".overlay").fadeOut(500);
		return false;
	}

	$.confirm({
		title:"Are you really wants to close this PopUp",
		text: "Are you sure you wish to exit without saving your changes?",
		confirm: function(button) {
			$('.lineitemfrm').each(function() {
				$('.popup_overlay, .loader').show();

				if ($(this).find( ".button-yes" ).hasClass( "active" ) ) {
						var editItemId = $(this).find(".button-yes").attr('lineid');
						$('#save_lineitem_'+editItemId).trigger('click');
				} else {
						var editItemId = $(this).find(".button-yes").attr('lineid');
						$('#button_no_'+editItemId).trigger('click');
				}

			});

			$('#save_note').trigger('click');

			$('.popup_overlay, .loader').hide();
			jQuery("#popup").fadeOut(500);
			jQuery(".overlay").fadeOut(500);
		},
		cancel: function(button) {
			jQuery("#popup").fadeOut(500);
			jQuery(".overlay").fadeOut(500);
		},
		confirmButton: "Save all",
		cancelButton: "Exit without saving"
	});
});

/* *****************
* Code for complete the static pages entries to tick Green right on guide page
* Function will be call by ajax from all static pages (Get page slug & user id).
******************/

$('#backToguideComplete').click(function(e) {
	e.preventDefault();
	var slug = $(this).attr('slug');
	var userID = $(this).attr('uid');

	$.ajax({
		type: 'POST',
		url:  '/wp-content/plugins/skopes/frontend/theme/Black/my_ajax_functions.php',
		data: {query: 'updateStaticPage', userID: userID, slug: slug},
		beforeSend: function(){
			$('div#shimag').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');
		},
		success: function(data) {

			if(data == 'success') {
				$('div#shimag').html('');
				$('#proid').removeClass('txt');
				$('#proid').addClass('green-complete');
			}

		}
	});
});
