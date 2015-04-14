/*  JS code show the panle large & small  */

$(document).ready(function() {
	$('.guide-smal').on('click', function() {
		$('.guide-smal').removeClass('guide-half');
		$(this).addClass('guide-half');

	});

	$('.guide-smal .guide_icons').on('click', function() {
		var url = $(this).find('a').attr('href');
		window.location = url;
		return false;
	});
});


/*
var sitepath = "http://beta.skop.es/wp-content/plugins/skopes/frontend/";

	$("#proguide").on("click", function (e) { 
		e.preventDefault();	 
		// getting element current class
		var curclass = $(this).attr('class');
		var tooltourClass = $("#tooltour").attr('class');
		var popfeaClass = $("#popfea").attr('class');
		var changedclass = '';
		// switch classes on click of tick or cross
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			changedclass = $(this).attr('class');			
		    runajax('itm1',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			changedclass = $(this).attr('class');
			runajax('itm1',1,sitepath);
			
		}		
		
		//changelabl($(this).attr('id'),changedclass,tooltourClass);
        changelabl3($(this).attr('id'),changedclass,popfeaClass,tooltourClass);
	});
	
	$("#tooltour").on("click", function (e) { 
		e.preventDefault();	 
		// getting element current class
		var curclass = $(this).attr('class');
		var proguideClass = $("#proguide").attr('class');
		var popfeaClass = $("#popfea").attr('class');
		var tchangedclass = '';
		// switch classes on click of tick or cross
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			tchangedclass = $(this).attr('class');
			runajax('itm2',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			tchangedclass = $(this).attr('class');
			runajax('itm2',1,sitepath);
		}
		
		//changelabl($(this).attr('id'),tchangedclass,proguideClass);
		changelabl3($(this).attr('id'),tchangedclass,popfeaClass,proguideClass);
	});
	
	$("#popfea").on("click", function (e) { 
		e.preventDefault();	 
		// getting element current class
		var curclass = $(this).attr('class');
		var pguideClass = $("#proguide").attr('class');
		var ttourClass = $("#tooltour").attr('class');
		var popfeachangedclass = '';
		// switch classes on click of tick or cross
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			popfeachangedclass = $(this).attr('class');
			runajax('itm19',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			popfeachangedclass = $(this).attr('class');
			runajax('itm19',1,sitepath);
		}
		
		//changelabl($(this).attr('id'),tchangedclass,proguideClass);
		changelabl3($(this).attr('id'),popfeachangedclass,ttourClass,pguideClass);
	});
	
	$("#tch").on("click", function (e) { 
		e.preventDefault();	 
		var curtclass = $(this).attr('class');
		var paDecides = $("#pa_decides").attr('class');
		var tchChngdClas = '';
		
		if(curtclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curtclass);
			tchChngdClas = $(this).attr('class');
			runajax('itm3',0,sitepath);
		} else {
			$(this).removeClass(curtclass).addClass('guide-error');
			tchChngdClas = $(this).attr('class');
			runajax('itm3',1,sitepath);
		}
		
		changelabl($(this).attr('id'),tchChngdClas,paDecides);		
		
	});
	
	$("#pa_decides").on("click", function (e) { 
		e.preventDefault();	 
		var curpclass = $(this).attr('class');
		var tchClass = $("#tch").attr('class');
		var pdChngdClas = '';
		
		if(curpclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curpclass);
			pdChngdClas = $(this).attr('class');
			runajax('itm4',0,sitepath);
		} else {
			$(this).removeClass(curpclass).addClass('guide-error');
			pdChngdClas = $(this).attr('class');
			runajax('itm4',1,sitepath);
		}
		
		changelabl($(this).attr('id'),pdChngdClas,tchClass);	
		
		
	});
	
	$("#proOrg").on("click", function (e) { 
		e.preventDefault();	 
		var curclass = $(this).attr('class');
		var hlClass = $("#high_level").attr('class');
		var pbgClass = $("#probg").attr('class');
		var poChngdClas = '';
		
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			poChngdClas = $(this).attr('class');
			runajax('itm20',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			poChngdClas = $(this).attr('class');
			runajax('itm20',1,sitepath);
		}
		
		changelabl3($(this).attr('id'),poChngdClas,hlClass,pbgClass);		
		
	});
	
	$("#probg").on("click", function (e) { 
		e.preventDefault();	 
		var curclass = $(this).attr('class');
		var hlClass = $("#high_level").attr('class');
		var poClass = $("#proOrg").attr('class');
		var proChngdClas = '';
		
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			proChngdClas = $(this).attr('class');
			runajax('itm5',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			proChngdClas = $(this).attr('class');
			runajax('itm5',1,sitepath);
		}
		
		//changelabl($(this).attr('id'),proChngdClas,hlClass);		
		changelabl3($(this).attr('id'),proChngdClas,hlClass,poClass);		
	});
	
	$("#high_level").on("click", function (e) { 
		e.preventDefault();	 
		var curclass = $(this).attr('class');
		var probgClass = $("#probg").attr('class');
		var poClass = $("#proOrg").attr('class');
		var hlChngdClas = '';
		
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			hlChngdClas = $(this).attr('class');
			runajax('itm6',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			hlChngdClas = $(this).attr('class');
			runajax('itm6',1,sitepath);
		}
		//changelabl($(this).attr('id'),hlChngdClas,probgClass);
		changelabl3($(this).attr('id'),hlChngdClas,probgClass,poClass);
	});
	
	$("#fun_req").on("click", function (e) { 
		e.preventDefault();	 
		var curclass = $(this).attr('class');
		var stapClass = $("#stck_appr").attr('class');
		var frChngdClas = '';
		
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			frChngdClas = $(this).attr('class');
			runajax('itm7',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			frChngdClas = $(this).attr('class');
			runajax('itm7',1,sitepath);
		}
		
		changelabl($(this).attr('id'),frChngdClas,stapClass);
	});
	
	$("#stck_appr").on("click", function (e) { 
		e.preventDefault();	 
		var curclass = $(this).attr('class');
		var frClass = $("#fun_req").attr('class');
		var saChngdClas = '';
		
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			saChngdClas = $(this).attr('class');
			runajax('itm8',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			saChngdClas = $(this).attr('class');
			runajax('itm8',1,sitepath);
		}
		
		changelabl($(this).attr('id'),saChngdClas,frClass);
		
	});

	$("#pro_bg_info").on("click", function (e) { 
		e.preventDefault();	 
		var curclass = $(this).attr('class');
		var prClass = $("#priorities").attr('class');
		var pbiChngdClas = '';
		
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			pbiChngdClas = $(this).attr('class');
			runajax('itm9',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			pbiChngdClas = $(this).attr('class');
			runajax('itm9',1,sitepath);
		}
		
		changelabl($(this).attr('id'),pbiChngdClas,prClass);
	});
	
	$("#priorities").on("click", function (e) { 
		e.preventDefault();	 
		var curclass = $(this).attr('class');
		var pbiClass = $("#pro_bg_info").attr('class');
		var prChngdClas = '';
		
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			prChngdClas = $(this).attr('class');
			runajax('itm10',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			prChngdClas = $(this).attr('class');
			runajax('itm10',1,sitepath);
		}
		
		changelabl($(this).attr('id'),prChngdClas,pbiClass);
	});
	
	$("#Collaborate").on("click", function (e) { 
		e.preventDefault();	 
		var curclass = $(this).attr('class');
		var svClass = $("#sys_vendors").attr('class');
		var evClass = $("#engag_vendor").attr('class');
		var colChngdClas = '';
		
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			colChngdClas = $(this).attr('class');
			runajax('itm11',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			colChngdClas = $(this).attr('class');
			runajax('itm11',1,sitepath);
		}
	
		changelabl3($(this).attr('id'),colChngdClas,svClass,evClass);
	});
	
	$("#sys_vendors").on("click", function (e) { 
		e.preventDefault();	 
		var curclass = $(this).attr('class');
		var colClass = $("#Collaborate").attr('class');
		var evClass = $("#engag_vendor").attr('class');
		var svChngdClas = '';
		
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			svChngdClas = $(this).attr('class');
			runajax('itm12',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			svChngdClas = $(this).attr('class');
			runajax('itm12',1,sitepath);
		}
		
		changelabl3($(this).attr('id'),svChngdClas,colClass,evClass);
	});
	
	$("#engag_vendor").on("click", function (e) { 
		e.preventDefault();	 

		var curclass = $(this).attr('class');
		var colClass = $("#Collaborate").attr('class');
		var svClass = $("#sys_vendors").attr('class');
		var evChngdClas = '';
		
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			evChngdClas = $(this).attr('class');
			runajax('itm13',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			evChngdClas = $(this).attr('class');
			runajax('itm13',1,sitepath);
		}
		
		changelabl3($(this).attr('id'),evChngdClas,colClass,svClass);
	});
	
	$("#scope_date").on("click", function (e) { 
		e.preventDefault();	 
		
		var curclass = $(this).attr('class');
		var pcClass = $("#project_close").attr('class');
		var sdChngdClas = '';
		
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			sdChngdClas = $(this).attr('class');
			runajax('itm14',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			sdChngdClas = $(this).attr('class');
			runajax('itm14',1,sitepath);
		}
		
		changelabl($(this).attr('id'),sdChngdClas,pcClass);
	});
	
	$("#project_close").on("click", function (e) { 
		e.preventDefault();	 
		var curclass = $(this).attr('class');
		var scdClass = $("#scope_date").attr('class');
		var pcChngdClas = '';
		
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			pcChngdClas = $(this).attr('class');
			runajax('itm15',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			pcChngdClas = $(this).attr('class');
			runajax('itm15',1,sitepath);
		}
		
		changelabl($(this).attr('id'),pcChngdClas,scdClass);
	});
	
	$("#fin_fun").on("click", function (e) {
		e.preventDefault();	 
		var curclass = $(this).attr('class');
		var smClass = $("#stu_maner").attr('class');
		var ensClass = $("#engage_sys").attr('class');
		var ffChngdClas = '';
		
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			ffChngdClas = $(this).attr('class');
			runajax('itm16',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			ffChngdClas = $(this).attr('class');
			runajax('itm16',1,sitepath);
		}
		
		changelabl3($(this).attr('id'),ffChngdClas,smClass,ensClass);
	});
	
	$("#stu_maner").on("click", function (e) { 
		e.preventDefault();	 
		var curclass = $(this).attr('class');
		var ffClass = $("#fin_fun").attr('class');
		var ensClass = $("#engage_sys").attr('class');
		var smChngdClas = '';

		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			smChngdClas = $(this).attr('class');
			runajax('itm17',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			smChngdClas = $(this).attr('class');
			runajax('itm17',1,sitepath);
		}
		
		changelabl3($(this).attr('id'),smChngdClas,ffClass,ensClass);
	});
	
	$("#engage_sys").on("click", function (e) { 
		e.preventDefault();	 
		var curclass = $(this).attr('class');
		var ffClass = $("#fin_fun").attr('class');
		var smClass = $("#stu_maner").attr('class');
		var esChngdClas = '';
		
		if(curclass == "guide-error" ) {
			$(this).addClass('guide-cross').removeClass(curclass);
			esChngdClas = $(this).attr('class');
			runajax('itm18',0,sitepath);
		} else {
			$(this).removeClass(curclass).addClass('guide-error');
			esChngdClas = $(this).attr('class');
			runajax('itm18',1,sitepath);
		}
		
		changelabl3($(this).attr('id'),esChngdClas,ffClass,smClass);
	});

//});

function changelabl(id,proChngdClas,hlClass){

	var pbp = $("#"+id).closest('p'); // get parent p with .grey-content
	var pbclsdiv = $(pbp).closest('div').attr("id"); // get parent div id
	var pbh5id = $("#"+pbclsdiv).prev('h5').attr("id"); // get h5 id	
	var cpTotal = $('#'+pbclsdiv).find('.guide-error').length; // count total link 2/2

	if((proChngdClas == "guide-error") &&  (hlClass == "guide-error")) {			
		$("#"+pbh5id).removeClass("btn-blue1").addClass("btn-green1");			
		$("#"+pbh5id).find('span.guide-right').text(cpTotal+'/2');
	} else {		
		$("#"+pbh5id).removeClass("btn-green1").addClass("btn-blue1");
		$("#"+pbh5id).find('span.guide-right').text(cpTotal+'/2'); 
	} 
return true;
}


function changelabl3(id,chngdelm,elm1,elm2){

	var pbp = $("#"+id).closest('p'); // get parent p with .grey-content
	var pbclsdiv = $(pbp).closest('div').attr("id"); // get parent div id
	var pbh5id = $("#"+pbclsdiv).prev('h5').attr("id"); // get h5 id	
	var cpTotal = $('#'+pbclsdiv).find('.guide-error').length; // count total link 2/2

	if((chngdelm == "guide-error") &&  (elm1 == "guide-error") &&  (elm2 == "guide-error")) {			
		$("#"+pbh5id).removeClass("btn-blue1").addClass("btn-green1");			
		$("#"+pbh5id).find('span.guide-right').text(cpTotal+'/3');
	} else {		
		$("#"+pbh5id).removeClass("btn-green1").addClass("btn-blue1");
		$("#"+pbh5id).find('span.guide-right').text(cpTotal+'/3'); 
	} 
return true;
}

function runajax(itemId,status,sitepath){
	$.ajax({ 
			type: "POST", 
			url: sitepath+"guidestatus.php",
			data: { query: itemId,status:status },
			beforeSend: function(){},
			success: function(data){}
	   });
return true;
}

*/
