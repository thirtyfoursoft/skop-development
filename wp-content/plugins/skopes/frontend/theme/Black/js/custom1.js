/* When user click on Yes for include category - stage1*/
	
jQuery(document).on('click', '#include_yes1', function () {
	
	if(jQuery(".block-section .active").hasClass("block-incomplete") || jQuery(".block-section .active").hasClass("block-notrequired"))
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
				   url:  path+"/savedata.php?action=IncludeCategory&catid="+escape(catid)+"&cat_status=0",
				   }).done(function(data) {
					 jQuery(".block-section .active").removeClass("block-notrequired");
					 jQuery(".block-section .active").removeClass("block-complete");
					 jQuery(".block-section .active").addClass("block-incomplete");
					 
				});

			}else{
				jQuery.ajax({
				   url:  path+"/savedata.php?action=IncludeCategory&catid="+escape(catid)+"&cat_status=3",
				   }).done(function(data) {
					 jQuery(".block-section .active").removeClass("block-incomplete");
					 jQuery(".block-section .active").removeClass("block-notrequired");
					 jQuery(".block-section .active").addClass("block-inscope");
				});
			}
			
			jQuery(".edit-items").fadeIn(1000);
			jQuery("#include_yes1").addClass("active");
			jQuery("#include_no").addClass("deactivated");
			jQuery("#include_no").removeClass("active");
			if(jQuery.isNumeric($(".block-section .active .block-inner span#complete_cnt_"+catid).html()))
			{}else{
				$(".block-section .active .block-inner span#complete_cnt_"+catid).html("0");
			}
			
			
			/** display message on click of success**/
			/*jQuery(".overlay").fadeIn(500);
			jQuery("#big_popup").hide();
			jQuery("#close_popup").hide();
			jQuery("#small_popup").html("");
			jQuery("#popup").fadeIn(500);
			jQuery(".popupbox").addClass("smallpopup");
			jQuery("#small_popup").load(path+"/msg.php?type=catinscope",function(data){
				jQuery("#small_popup").show();
				jQuery("#close_popup").show();
			});*/
		
		}
	}
});

/** when user click on functonal area link on report page **/

jQuery(document).on('click', '#fun_area', function (e) {
  e.preventDefault();
  //alert(path);
  jQuery.ajax({
	   url:  path+"/savedata.php?action=checkCategoryStatus",
	   }).done(function(data) {
		 if(data>0){
		 /** display message on click of success**/
			jQuery(".overlay").fadeIn(500);
			jQuery("#big_popup").hide();
			jQuery("#close_popup").hide();
			jQuery("#small_popup").html("");
			jQuery("#popup").fadeIn(500); 
			jQuery(".popupbox").addClass("smallpopup");
			jQuery("#small_popup").load(path+"/msg.php?type=setstatus",function(data){
				jQuery("#small_popup").show();
				jQuery("#close_popup").show();
			}); 
			
		 }
	});
});	


jQuery(document).on('click', '.mainpage', function (e) {
  var link = $(this).attr('href');
  
});


;


