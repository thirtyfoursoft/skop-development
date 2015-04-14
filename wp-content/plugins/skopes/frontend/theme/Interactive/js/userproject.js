/* ***** Code for show help text by clicking help icon on project page Written by Royal Tyagi  ******* */

jQuery(function(){
	$(document).on("click", ".help-click", function() {
		var id = $(this).attr('id');

		if($( "#"+id+'text' ).css('display') == 'block') {
			$('.help-text').slideUp("slow");
		} else {
			$('.help-text').slideUp("slow");
			$( "#"+id+'text' ).slideToggle( "slow");
		}
	});
})


/********************* STACK HOLDER SECTION STARTS******************/

function fnvalidate_stackholder(){
	
	var stackholder = document.getElementsByClassName('stackholders');

	for (var i = 0; i < stackholder.length; i++){
		
		var id = stackholder[i].id;		
		var regex = /^[A-Za-z]+( [A-Za-z]+)*$/;/*/^[a-zA-Z ]*$/;*/
		
		if(stackholder[i].value === ''){
		 $("#"+id).focus();		 
		 $("#error_"+id).html('Enter Stakeholders name'); 
		 return false;
		}else if (!regex.test(stackholder[i].value)) {
            $("#"+id).focus();
			$("#error_"+id).html('Please enter only characters.');  
			return false;
        }
		else{
		  $("#error_"+id).html('');  
		}
	}
   return true;
}


var MaxInputs       = 11; //maximum input boxes allowed 
	var InputsWrapper   = $("#InputsWrapper"); //Input boxes wrapper ID
	var AddButton       = $("#AddMoreFileBox"); //Add button ID

	//var x = InputsWrapper.length; //initlal text box count
	var x = InputsWrapper.find('div.form-two-colum').size();
	var FieldCount=1; //to keep track of text box added

	$(AddButton).click(function (e)  //on add input button click
	{
        if(x <= MaxInputs) //max input box allowed
        {
            FieldCount++; //text box added increment
            //add input box

        //   $(InputsWrapper).append('<div><div class="pi-left">Stakeholders '+ FieldCount+'</div><div class="pi-middle"><input type="text" name="stackholder[]" id="stackholder_'+ FieldCount+'" value=""/><span id="error_stackholder_'+ FieldCount+'" class="error sherror"></span><a href="#" class="removeclass">&times;</a></div></div>');
           	$(InputsWrapper).append('<div class="form-two-colum"><div class="first-colum">Stakeholder Group'+ FieldCount+'</div><div class="second-colum"><input type="text" value="" id="stackholder_'+FieldCount+'" name="stackholder[]" class="stackholders input-text"><span id="error_stackholder_'+FieldCount+'" class="error sherror"></span><a class="removeclass" href="#">×</a></div></div>');
            x++; //text box increment
        }
	return false;
	});

	$("body").on("click",".removeclass", function(e){ //user click on remove text
		if( x > 1 ) {
				$(this).parent('div').parent('div').remove(); //remove text box
				x--; //decrement textbox
		}
	return false;
	})

	// checking value after save
	var InputsWrapper1   = $("#InputsWrapper1"); //Input boxes wrapper ID
	var AddButton1       = $("#AddMoreFileBox1"); //Add button ID

	//var x1 = InputsWrapper1.length; //initlal text box count
	if(InputsWrapper1.find('div.form-two-colum').size() >0){
		var x1 = InputsWrapper1.find('div.form-two-colum').size();
	}else{
		var x1 = 1;
	}
	var ShFieldCount = x1; //to keep track of text box added
	var ShMaxInputs = 11;
	
	$(AddButton1).click(function (e)  //on add input button click
	{
		if(x1 <= ShMaxInputs) //max input box allowed
		{
			ShFieldCount++; //text box added increment

			$(InputsWrapper1).append('<div class="form-two-colum"><div class="first-colum">Stakeholder Group'+ ShFieldCount+'</div><div class="second-colum"><input type="text" value="" id="stackholder_'+ ShFieldCount+'" name="stackholder[]" class="stackholders input-text"><span id="error_stackholder_'+ ShFieldCount+'" class="error sherror"></span><a class="removeclass" href="#">×</a></div></div>');
			x1++; //text box increment
		}
	return false;
	});
	
	$("body").on("click",".removeclass", function(e){ //user click on remove text
	
        if( x1 > 1 ) {
                $(this).parent('div').parent('div').remove(); //remove text box
                x1--; //decrement textbox
        }
		
	return false;
	})

$('#frmstackholder').on('submit', function(e){ 

        // prevent native form submission here
        e.preventDefault();
		/* if(fnvalidate_stackholder()==false ){
		 		 return false; 
		}else{ */
			
        // now do whatever you want here
        $.ajax({
            type: $(this).attr('method'), // <-- get method of form
            url:  $(this).attr('action'), // <-- get action of form
            data: $(this).serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
            beforeSend: function(){
                $('#shimag').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');				
            },
            success: function(data){
                $('#InputsWrapper1').html(data);
				$('#shimag').hide();
				$('#shmsg').html('Information Saved');
				$('#shmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
				$('#shimag').html('');
				var shcompleteval = $('#frmstackholder').find('#shval').val();
					
					if(shcompleteval == 1){

						$('#ui-accordion-1-header-2 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete'); 
						$('#ui-accordion-1-header-2 span:nth-child(4)').html('In Progress');
						$("#cmpltStakeholder").removeClass("green").addClass("grey1");
					}
            }
        });
	  //}
    });	
	
// on click of complte button of stake holder section
$('#cmpltStakeholder').on('click', function(event){
	event.preventDefault();
	if(fnvalidate_stackholder()==false ){
		 		 return false; 
	}else{ 
		//$("#frmstackholder").submit();
		$.ajax({
            type: $("#frmstackholder").attr('method'), // <-- get method of form
            url:  $("#frmstackholder").attr('action'), // <-- get action of form
            data: $("#frmstackholder").serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
            beforeSend: function(){
                $('#shimag').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');				
            },
            success: function(data){
                $('#InputsWrapper1').html(data);
				$('#shimag').hide();
				$('#shmsg').html('Information Saved');
				$('#shmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
				$('#shimag').html('');
				var shcompleteval = $('#frmstackholder').find('#shval').val(); 
            }
        });
		
		$.ajax({ 
				type: "POST",
				url: "/wp-content/plugins/skopes/frontend/theme/Interactive/compltechk.php",
				data: { query: 'stakehldrinfo' },
				beforeSend: function(){
					$('#shimag').show();
					$('#shimag').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');	 			
				},
				success: function(data){	

					if(data == 2){ 

						$('#ui-accordion-1-header-2 span:nth-child(4)').removeClass('notstred incomplete').addClass('complete'); 
						$('#ui-accordion-1-header-2 span:nth-child(4)').html('Complete');
						$("#cmpltStakeholder").removeClass("grey1").addClass("green");
					}
				}
		   });
		  } // end of else
	});
	

/********************* STACK HOLDER SECTION ENDS******************/

/** CREATE NEW MILESTONES **/

function addMilestoneFields(){

	var MilestoneInputs  = 9; //maximum input boxes allowed
	var InputsWrapper    = $("#milestoneinfomain"); //Input boxes wrapper ID
	var AddButton1       = $("#AddMoreMilestone1"); //Add button ID
	
	var y1 = InputsWrapper.find('div.milestoneinfo').size(); //initlal text box count

	//var MFieldCount = (y1>1?y1:1); //to keep track of text box added
	var MFieldCount = (y1>0?y1+1:1);
	if(y1 <= MilestoneInputs) //max input box allowed
        {
            
			//MFieldCount = (y1==0 ? 1 :MFieldCount++); //text box added increment
			//MFieldCount++;
            //add input box
           
			var lastdiv = $( ".mstoneinfo").last();
			$('<div class="milestoneinfo"><div class="pi-left">Milestone <span id="miles_'+MFieldCount+'">'+MFieldCount+'</span>:</div><div class="pi-middle"><input type="text" name="milestone[]"></div><div class="sc-right"><input type="text" id="datepicker'+MFieldCount+'" name="milestonedate[]" class="pickdate"></div><a href="#" class="removemclass">x</a></div>' ).insertBefore(lastdiv);
			
			
			/* $('#datepicker'+MFieldCount).on('click',function(){
				 $(this).datepicker({dateFormat: 'dd-mm-yy'});
			}); */
            y1++; //text box increment
        }
	return false;
	
	/* $("body").on("click",".removeclass", function(e){ //user click on remove text
						
			if( y1 > 1 ) {
				$(this).parent('div').remove(); //remove text box
				y1--; //decrement textbox
			}
		return false;
	}) */
}

function removemilestones(){
	
	/* var AddMemberInfo    = $("#memberinfo");
	var m = AddMemberInfo.find('div.memberdetails').size(); */
	
	
	var InputsWrapper    = $("#milestoneinfomain"); //Input boxes wrapper ID
	var y1 = InputsWrapper.find('div.milestoneinfo').size(); //initlal text box count
	
	//if( y1 > 1 ) {
		$(this).parent('div').remove(); //remove text box
		y1--; //decrement textbox
	//}
	var ele = $("#milestoneinfo").find('span#miles_').size();
	var ictr = 1;
	var totalspans = $(".milestoneinfo span").size();
	
	$(".milestoneinfo span").each(function() {
	     spanID = $(this).attr('id');
		 $("#"+spanID).html(ictr); 
		 ictr = ictr + 1;   
	});
 
	 
	for(var i=1; i<=y1; i++)
	{
	     //alert(i+' - '+$('#miles_'+i).html());
	}
	
	return false;

}


/* function to validate user project information */


/** check valid email id**/
function checkvalidemail(id,emailid){
   
	var uid = $('#'+id).next().attr('id'); 
	
	var userid = $("#"+uid).val();
	var emailRegex = new RegExp(/^([\w\.\-]+)@([\w\-]+)((\.(\w){2,3})+)$/i);
	var valid = emailRegex.test(emailid);
	
	if(emailid == ''){
		$("#error_"+id).html('Enter email id.');
	}else if (!valid) {
		$("#error_"+id).html('Invalid e-mail address');    
		return false;
	} 
	/* else{
		
			
	} */// end of else	
	 return true;
}

/** email id exist**/
function emailexist(id,emailid){
    var path = '/wp-content/plugins/skopes/frontend/theme/Interactive/';
	var fullpath = path+'check_email.php';
	var newEmail= emailid;
	//,uid:userid
	$.post(path+'check_email.php', { email: newEmail}, function(data){
	 
	var userexistingid = data;
	//alert(userid+"---"+userexistingid); 			
	if(userexistingid == ""){
		$("#error_"+id).html('');
	}	
	else if(userexistingid != "" ){
		//alert('ni');
		$("#"+id).focus();
		$("#error_"+id).html('This email id is alredy in use.Please use another.'); 
		return false;			
	} }, 'html');
return true;
}

/** validate team info **/

function validate_teaminfoform_new(totalmembersID){
       
}

function validate_teaminfoform(){
	var teamleadname = $("#teamleadname").val();
	var membername = $("#membername_1").val();
	var teamlead_email = $("#teamlead_email").val();
	var membername = $("#membername_1").val();
	 
	if(teamleadname == "" ){
		$("#error_teamleadname").html('Enter Team lead name'); 
		return false;
	}
	else if(teamleadname.length>51){
		$("#error_teamleadname").html('Only 50 character allowed.'); 
		return false;
	}
	else{
		$("#error_teamleadname").html('');
	}
	
	if(teamlead_email == "" ){
		$("#error_teamlead_email").html('Enter Team lead email'); 
		return false;
	}
	else if(checkvalidemail('teamlead_email',teamlead_email)==false){
		$("#error_teamlead_email").html('Enter valid email id.'); 
		return false;
	}
	else if(teamlead_email.length>100){
		$("#error_teamlead_email").html('Only 100 character allowed.'); 
		return false;
	}else{
		$("#error_teamlead_email").html('');
	}
	
	if(membername == "" ){
		$("#error_membername_1").html('Enter name of Member');  
		return false;
	}else{
		$("#error_membername_1").html('');
	}

	var buttons1 = document.getElementsByClassName('teamemail');

	for(var i = 0; i < buttons1.length; i++){
		
		var id = buttons1[i].id;			
		if(buttons1[i].value === ''){
			$("#error_"+id).html('Enter email of Team member'); 
			return false;
		 } else if(checkvalidemail(id,buttons1[i].value)==false){
			$("#error_"+id).html('Enter valid email id'); 
			return false;
		} /*else if(emailexist(id,buttons1[i].value)==false){
		   ("#error_"+id).html('Email alreay exist'); 
			return false;
		}*/
		else if(buttons1[i].value.length>100){
			$("#error_"+id).html('Only 100 character allowed.'); 
			return false;
		}else{
		  $("#error_"+id).html(''); 
		} 
	}

	return true;
}

/** Save the team inital info  **/

function saveteaminfo(){
	
	$('#frmteaminfo').on('submit', function(e){
      
        // prevent native form submission here
        e.preventDefault();
		
		 //if(validate_teaminfoform()==false){
			//return false;
		//}else{ 
			// now do whatever you want here 
			$.ajax({
				type: $(this).attr('method'), // <-- get method of form
				url: $(this).attr('action'), // <-- get action of form
				data: $(this).serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
				beforeSend: function(){
					$('#teamresult').show();
					$('#teamresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');				
				},
				success: function(data){
					$('#teammaininfo').html(data);
					$('#teamresult').hide();
					$('#teamresultmsg').html('Information Saved');
					$('#teamresultmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
					$('#teamresult').html('');
					var completeval = $('#frmteaminfo').find('#teamval').val();
     
						if(completeval == 1){

							$('#ui-accordion-1-header-1 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete'); 
							$('#ui-accordion-1-header-1 span:nth-child(4)').html('In Progress');
							$("#cptlteam").removeClass("green").addClass("grey1");
						}
				}
		   });
	  //}
    });
}//end of saveteaminfo function

function addteam_members(){
	/** MEMBERS SECTION STARTS **/
	var MemberMaxInputs = 9;
	var AddMemberInfo    = $("#teaminitalinfo"); //Input boxes wrapper ID
	var AddMoreMemberInfoButton   = $("#AddMoreMemberInfo"); //Add benefit button ID
	//var m = AddMemberInfo.find('div.memberdetails').size(); //initlal text box count
	var m = AddMemberInfo.find('div.form-three-colum').size();

	var MemberFieldCount = (m == 0 ? 1 : m);

	//$(AddMoreMemberInfoButton).click(function (e)  //on add input button click
	//{  
        if(m <= MemberMaxInputs) //max input box allowed
        {
            MemberFieldCount++; //text box added increment
            //add input box  

			$(AddMemberInfo).append('<div class="form-three-colum"><div class="first-colum">New Member</div><div class="second-colum"><input type="text" value="" id="membername_'+MemberFieldCount+'" name="membername[]" maxlength="50" class="input-text"><span id="error_membername_'+MemberFieldCount+'" class="error"></span></div><div class="third-colum"><input type="text" value="" onblur="checkvalidemail(this.id,this.value)" id="memberemail_'+MemberFieldCount+'" name="memberemail[]" class="teamemail input-text" maxlength="100" class="error" style="width:90%;"><input type="hidden" value="" id="memberid'+MemberFieldCount+'" name="memberid[]" class="umid" maxlength="100"><a style="color: #ff0015 !important;font-size: 18px; font-weight: bold; margin-left: 5px;" class="removeclass" id="removeclassID" onclick="removeteam_members(\'memberdetails_'+MemberFieldCount+'\', 0); deletenewField();" href="javascript:void(0);">x</a><span id="error_memberemail_'+MemberFieldCount+'" class="error"></span></div></div>');
            m++; //text box increment  
			document.getElementById('totalmembersNumbID').value += ","+MemberFieldCount;
        }
		return false;
	//});

	/** MEMBERS SECTION ENDS **/
	
	
}

function removeteam_members(memberdetailsID, memberId){  
    if(memberId > 0)
	{  
		  var r = confirm("Are you sure you want to delete the member from team");
			if (r == true) {
				$.ajax({
					type: 'POST',
					url: '/wp-content/plugins/skopes/frontend/theme/Interactive/my_ajax_functions.php',
					data: {query: 'delete_team_member', memberId: memberId},
					beforeSend: function() {
						$('#teamMemAddedID').show('');
						$("#savememberinfo").attr("disabled", true);
						$("#cptlteam").attr("disabled", true);
						$('#teamMemAddedID').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');
					},
					success: function(data) {
					     if(data.trim() == 'delete done')
						 {
						      $('#teamMemAddedID').html('Your member has been deleted permanently from team.');
							  $("#cptlteam").removeAttr("disabled");
							  $("#teamMemAddedID").removeAttr("disabled");
						 }
					}
				}); 
				var AddMemberInfo    = $("#teaminitalinfo");
					var m = AddMemberInfo.find('div.memberdetails').size();	
					 
					//if( m > 1 ) {
							$('#'+memberdetailsID).remove(); //remove text box
							m--; //decrement textbox
					//}
			} else { return false;  }
	}
	else
	{   
			 var AddMemberInfo    = $("#teaminitalinfo");
				var m = AddMemberInfo.find('div.memberdetails').size();	
				 
				//if( m > 1 ) {
						$('#'+memberdetailsID).remove(); //remove text box
						m--; //decrement textbox
				//}
	}
	return false; 
}


/**  
	**save the team more info 
**/
	$('#frm_milestone').on('submit', function(e){
      
        // prevent native form submission here
        e.preventDefault();
		
		  /* if(fnvalidate_milestone()==false ){
		 		 return false;
		}else{  */ 
			
        // now do whatever you want here
        $.ajax({
            type: $(this).attr('method'), // <-- get method of form
            url: $(this).attr('action'), // <-- get action of form
            data: $(this).serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
            beforeSend: function(){ 
				$('#milestoneresult').show('');
                $('#milestoneresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');				
            },
            success: function(data){
                //$('#milestoneinfomain').html(data);
				$('#milestonemain').html(data);				
				$('#milestoneresult').hide();
				$('#msresultmsg').html('Information Saved');
				$('#msresultmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
				$('#milestoneresult').html('');
				var mcompleteval = $('#frm_milestone').find('#mileval').val();
					
					if(mcompleteval == 1){
						$('#ui-accordion-accordion-header-3 span:nth-child(2)').removeClass('incomp-bl');						
						$('#ui-accordion-accordion-header-3 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');	
						$('#ui-accordion-accordion-header-3 span:nth-child(2)').addClass('comp-gr');
						$('#ui-accordion-accordion-header-3 span:nth-child(2)').html('&nbsp;(In Progress)');
						$("#mswrap").removeClass("green-complete").addClass("txt");
						$("#complte_milestone").parent().removeClass("btn-green1").addClass("btn-grey1");						
						
					}/* else{
						$('#ui-accordion-accordion-header-2 span:nth-child(2)').removeClass('comp-gr');
						$('#ui-accordion-accordion-header-2 span:nth-child(2)').addClass('incomp-bl');					
						$('#ui-accordion-accordion-header-2 span:nth-child(2)').html('&nbsp;(In Progress)');
					} */
            }
        });
	  //}
    });
	
function fnvalidate_project(e){

	//frm_milestone

	//e.preventDefault();

	if(fnvalidate_milestone()==false ){

			 return false; 

	}

	

	return true;

}

function fnvalidate_project_motivation_bg()
{
       var authors   = $('#authors').val();
	   var pro_ques5 = $('#pro_ques5').val();
	   var pro_ques2 = $('#pro_ques2').val();
	   var pro_ques3 = $('#pro_ques3').val();
	   var pro_ques4 = $('#pro_ques4').val();
	   
	   if(authors == '')
	   {
			$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete'); 	   
			$("#saveprojectinfo").removeClass("green").addClass("grey1");
	       $('#authors_error').html('Please enter the Project document authors:'); 
		   $('#authors_error').focus();
			return false;
	   }
	   else if(pro_ques5 == '')
	   {
			$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete'); 	   
			$("#saveprojectinfo").removeClass("green").addClass("grey1");
	       $('#authors_error').html(''); 
	       $('#pro_ques5_error').html('Please enter your project name:');
		   $('#pro_ques5_error').focus();

			return false;
	   }
	   else if(pro_ques2 == '')
	   {
			$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete'); 	   
			$("#saveprojectinfo").removeClass("green").addClass("grey1");
	       $('#authors_error').html('');
		   $('#pro_ques5_error').html('');
	       $('#pro_ques2_error').html('Please enter the Project document authors:');
		   $('#pro_ques2_error').focus();
			return false;
	   }
	   else if(pro_ques3 == '')
	   {
			$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete'); 	   
			$("#saveprojectinfo").removeClass("green").addClass("grey1");
	       $('#authors_error').html('');
		   $('#pro_ques5_error').html('');
		   $('#pro_ques2_error').html('');
	       $('#pro_ques3_error').html('Please enter the Project document authors:');
		   $('#pro_ques3_error').focus();
			return false;
	   }
	   else if(pro_ques4 == '')
	   {
			$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete'); 	   
			$("#saveprojectinfo").removeClass("green").addClass("grey1");
	       $('#authors_error').html('');
		   $('#pro_ques5_error').html('');
		   $('#pro_ques2_error').html('');
		   $('#pro_ques3_error').html(''); 
	       $('#pro_ques4_error').html('Please enter the Project document authors:');
		   $('#pro_ques4_error').focus();
			return false;
	   }
	   else {
	       $('#authors_error').html('');
		   $('#pro_ques5_error').html('');
		   $('#pro_ques2_error').html('');
		   $('#pro_ques3_error').html(''); 
	       $('#pro_ques4_error').html('');
	       return true;
	   }
}

function saveprojectinfo(){
	$('#frmproject').on('submit', function(e){
        e.preventDefault();
			$.ajax({
				type: $(this).attr('method'), // <-- get method of form
				url: $(this).attr('action'), // <-- get action of form
				data: $(this).serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
				beforeSend: function(){
					$('#projectresult').show();
					$('#projectresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');	 			
				},
				success: function(data){

					$('#projectmain').html(data); 
					$('#projectresult').hide();
					$('#projectresultmsg').html('Information Saved');
					$('#projectresultmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
					$('#projectresult').html('');
					var pcompleteval1 = $('#frmproject').find('#projectval').val();

					$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete'); 
					$('#ui-accordion-1-header-0 span:nth-child(4)').html('In Progress');
					$("#saveprojectinfo").removeClass("green").addClass("grey1");
				}
		   });
    });	
	
}//end of saveteaminfo function

function saveingprojectinfo(){
 $('#saveprojectinfo').on('click', function(event){
	event.preventDefault();
        //$( "#frmproject" ).submit();
		
		  var totalChecked = 0;  	
		  var authors   = $('#authors').val();
		  var pro_ques5 = $('#pro_ques5').val();
		  var pro_ques2 = $('#pro_ques2').val();
		  var pro_ques3 = $('#pro_ques3').val();
		  var pro_ques4 = $('#pro_ques4').val();
			if(authors != '')
			{
				totalChecked = totalChecked + 1;
			}
			if(pro_ques5 != '')
			{
				totalChecked = totalChecked + 1;
			}
			if(pro_ques2 != '')
			{
				totalChecked = totalChecked + 1;
			}
			if(pro_ques3 != '')
			{
				totalChecked = totalChecked + 1;
			}
			if(pro_ques4 != '')
			{
				totalChecked = totalChecked + 1;
			}
				
		   if(fnvalidate_project_motivation_bg()==false){
		   
		         $.ajax({ 
							type: "POST",
							url: "/wp-content/plugins/skopes/frontend/theme/Interactive/compltechk.php",
							data: { query: 'projectquesinfo', postData: $('#frmproject').serialize(), totalChecked: totalChecked },
							beforeSend: function(){
								$('#projectresult').show();
								$('#projectresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');	 			
							},
							success: function(data){	
								$('#projectresult').hide();
								//$('#projectresultmsg').html('Information Saved');
								$('#projectresultmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
								$('#projectresult').html('');

								if(data == 2){ 
								
									$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred incomplete').addClass('complete'); 
									$('#ui-accordion-1-header-0 span:nth-child(4)').html('Complete');
									$("#saveprojectinfo").removeClass("grey1").addClass("green");
													
								}else if(data == 1){

									$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete'); 
									$('#ui-accordion-1-header-0 span:nth-child(4)').html('In Progress');
									$("#saveprojectinfo").removeClass("green").addClass("grey1");
								}
							}
					   });
		   
			return false;
		   }else{
		      
			       $.ajax({ 
							type: "POST",
							url: "/wp-content/plugins/skopes/frontend/theme/Interactive/compltechk.php",
							data: { query: 'projectquesinfo', postData: $('#frmproject').serialize(), totalChecked: totalChecked },
							beforeSend: function(){
								$('#projectresult').show();
								$('#projectresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');	 			
							},
							success: function(data){	
								$('#projectresult').hide();
								$('#projectresultmsg').html('Information Saved');
								$('#projectresultmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
								$('#projectresult').html('');
								if(data == 2){ 

									$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred incomplete').addClass('complete'); 
									$('#ui-accordion-1-header-0 span:nth-child(4)').html('Complete');
									$("#saveprojectinfo").removeClass("grey1").addClass("green");
													
								}else if(data == 1){

									$('#ui-accordion-1-header-0 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete'); 
									$('#ui-accordion-1-header-0 span:nth-child(4)').html('In Progress');
									$("#saveprojectinfo").removeClass("green").addClass("grey1");

								}
							}
					   });
			    }		   
})
 
}

function fnvalidate_milestone(){
  
  var startDate = $("#datepickerfirst").val();
  var endDate = $("#datepicker11").val();
  breakOut = false; 

 
 
 if(startDate == ""){
 
   $("#error_dates").html('Enter start date.');
   $("#datepickerfirst").focus();
    return false;
 } 
 else if(endDate == ""){
	$("#error_dates").html('Enter end date.');
    $("#datepicker11").focus();
    return false;
 }else if($.datepicker.parseDate('dd-mm-yy', startDate) >= $.datepicker.parseDate('dd-mm-yy',endDate)){   
     $("#error_dates").html('End date will be greater then start date.');     
     $("#datepicker11").focus();
    return false;
  }else{
	  $("#error_dates").html('');
   }

      $('.milestoneinfo .pickdate').each(function(i, obj){
		if($.datepicker.parseDate('dd-mm-yy',$(this).val()) <= $.datepicker.parseDate('dd-mm-yy',startDate) || $.datepicker.parseDate('dd-mm-yy',$(this).val()) >= $.datepicker.parseDate('dd-mm-yy',endDate)){		
			 breakOut = true;
			 $(this).focus();
			 return false;
		}
		else{
		  $("#error_dates").html('');
	   }
  });
   if(breakOut) {
    $("#error_dates").html('Enter milestone date in between start and end date.');
    breakOut = false;
    return false;
    
  }
  
  
}

$('#complte_milestone').on('click', function(event){
	event.preventDefault();
	if(fnvalidate_milestone()==false ){
		return false;
	}else{
	   //$( "#frm_milestone" ).submit();
	    
	   $.ajax({
            type: $("#frm_milestone").attr('method'), // <-- get method of form
            url: $("#frm_milestone").attr('action'), // <-- get action of form
            data: $("#frm_milestone").serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
            beforeSend: function(){ 
				$('#milestoneresult').show('');
                $('#milestoneresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');				
            },
            success: function(data){
                //$('#milestoneinfomain').html(data);
				$('#milestonemain').html(data);				
				$('#milestoneresult').hide();
				$('#msresultmsg').html('Information Saved');
				$('#msresultmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
				$('#milestoneresult').html('');
				var mcompleteval = $('#frm_milestone').find('#mileval').val();
				 
				       $.ajax({ 
								type: "POST",
								url: "/wp-content/plugins/skopes/frontend/theme/Interactive/compltechk.php",
								data: { query: 'milestoneinfo' },
								beforeSend: function(){
									$('#milestoneresult').show();
									$('#milestoneresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');	 			
								},
								success: function(data){	
									$('#milestoneresult').hide();
									$('#msresultmsg').html('Information Saved');
									$('#msresultmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
									$('#milestoneresult').html('');
                                          
									if(data == 2){
									
									$('#ui-accordion-accordion-header-3 span:nth-child(2)').removeClass('incomp-bl').addClass('comp-gr');
									$('#ui-accordion-accordion-header-3 span:nth-child(2)').removeClass('notstart-wh').addClass('comp-gr');			
									$('#ui-accordion-accordion-header-3 span:nth-child(2)').html('&nbsp;(Complete)');
									$("#mswrap").removeClass("txt").addClass("green-complete");
									$("#complte_milestone").parent().removeClass("btn-grey1").addClass("btn-green1"); 
														
									}else if(data == 1){
										$('#ui-accordion-accordion-header-3 span:nth-child(2)').removeClass('comp-gr').addClass('incomp-bl');					
										$('#ui-accordion-accordion-header-3 span:nth-child(2)').html('&nbsp;(In Progress)');
										$("#mswrap").removeClass("green-complete").addClass("txt");
										$("#complte_milestone").parent().removeClass("btn-green1").addClass("btn-grey1");
									}
								}
						   });	 
            }
        }); 
	}
});


$('#cptlteam').on('click', function(event){
	event.preventDefault(); 
	var totalmembersID     = $('#totalmembersID').val(); 
	var totalmembersNumbID = $('#totalmembersNumbID').val();
	//alert(totalmembersNumbID); return false;
	if(validate_teaminfoform(totalmembersID)==false){
	   return false;
	}else{
          	$('#checkForCompleteClick').val(1);          
			//$("#frmteaminfo").submit(); 
			
			$.ajax({
				type: $("#frmteaminfo").attr('method'), // <-- get method of form
				url: $("#frmteaminfo").attr('action'), // <-- get action of form
				data: $("#frmteaminfo").serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
				beforeSend: function(){
					$('#teamresult').show();
					$('#teamresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');				
				},
				success: function(data){
					$('#teammaininfo').html(data);
					$('#teamresult').hide();
					$('#teamresultmsg').html('Information Saved');
					$('#teamresultmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
					$('#teamresult').html('');
					var completeval = $('#frmteaminfo').find('#teamval').val();
					
					$.ajax({ 
							type: "POST",
							url: "/wp-content/plugins/skopes/frontend/theme/Interactive/compltechk.php",
							data: { query: 'teaminfo' },
							beforeSend: function(){
								$('#teamresult').show();
								$('#teamresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');	 			
							},
							success: function(data){	
								$('#teamresult').hide();
								$('#teamresultmsg').html('Information Saved');
								$('#teamresultmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
								$('#teamresult').html('');
								$('#checkForCompleteClick').val(0);  
								if(data == 2){ 
									$('#ui-accordion-1-header-1 span:nth-child(4)').removeClass('notstred incomplete').addClass('complete'); 
									$('#ui-accordion-1-header-1 span:nth-child(4)').html('Complete');
									$("#cptlteam").removeClass("grey1").addClass("green");
													
								}else if(data == 1){
									$('#ui-accordion-1-header-1 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete'); 
									$('#ui-accordion-1-header-1 span:nth-child(4)').html('In Progress');
									$("#cptlteam").removeClass("green").addClass("grey1");
								}
							}
					   });
				}
		   });

		}
})
 
$(document).on("click", ".pickdate", function() {
	var id = $(this).attr('id');

	var $current = $(this); //the element you have
	var $elems = $('.pickdate'); //the collection of elements
	var $previous = $elems.eq($elems.index($current) - 1);
	
	$("#"+id).datepicker({changeYear: true,dateFormat: 'dd-mm-yy',changeMonth: true, yearRange : 'c:c+15',defaultDate: $previous.val()}).datepicker('show');

});

   $(document).ready(function() {
	saveteaminfo();	
	//removeteam_members();
	saveprojectinfo();
	saveingprojectinfo();
	
	//$("#datepicker1").datepicker({changeYear: true,dateFormat: 'dd-mm-yy'});	
	//$(document).on("click", "#AddMoreMilestone", addMilestoneFields);
	$('#AddMoreMilestone').live("click", addMilestoneFields);
	$(document).on("click", "#AddMoreMemberInfo", addteam_members);
	//$(document).on("click", ".removeclass", removeteam_members);
	//$(document).on("click", "#removeclassID", removeteam_members);
	//$(document).on("click", ".removemclass", removemilestones);
	$('.removemclass').live("click", removemilestones);
	//$( ".pickdate" ).datepicker({dateFormat: 'dd-mm-yy'});
	  
	
	
	var AddMemberInfo    = $("#memberinfo");
	var m = AddMemberInfo.find('div.memberdetails').size();

});

/*  **************** JS for Keys Date Written by Royal Tyagi ************************ */

$(document).ready(function() {
	$('#keysDate').on('submit', function(e){
		 e.preventDefault();

		if(fnvalidate_keyDates()==false ){
			return false;
		} else {

			$.ajax({
				type: $(this).attr('method'),
				url: $(this).attr('action'),
				data: $(this).serialize(),
				beforeSend: function(){ 
					$('#keysDataresult').show('');


				    $('#keysDataresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');				
				},
				success: function(data){
					$('#keysDataresult').hide();
					$('#msresultmsg').html('Information Saved');
					$('#msresultmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
					$('#keysDataresult').html('');

					if(data == 1){
						$('#ui-accordion-1-header-3 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete');
						$('#ui-accordion-1-header-3 span:nth-child(4)').html('In Progress');
						$("#cmpltkeysDate").removeClass("green").addClass("grey1");
					}
					
				}
			});
		}

	});

$('#cmpltkeysDate').on('click', function(event){
		 event.preventDefault();

		if(fnvalidate_keyDates()==false ){
			return false;
		} else {
		
	   $.ajax({
            type: $("#keysDate").attr('method'),
            url: $("#keysDate").attr('action'),
            data: $("#keysDate").serialize(),
            beforeSend: function(){ 
				$('#keysDataresult').show('');
                $('#keysDataresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');				
            },
            success: function(data){

				$('#keysDataresult').hide();
				$('#msresultmsg').html('Information Saved');
				$('#msresultmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
				$('#keysDataresult').html('');
				 
				$.ajax({ 
					type: "POST",
					url: "/wp-content/plugins/skopes/frontend/theme/Interactive/compltechk.php",
					data: { query: 'keysProjectDate' },
					beforeSend: function(){
						$('#keysDataresult').show();
						$('#keysDataresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Interactive/images/loader.gif" />');	 			
					},
					success: function(data){	
						$('#keysDataresult').hide();
						$('#msresultmsg').html('Information Saved');
						$('#msresultmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
						$('#keysDataresult').html('');
							  
						if(data == 2){
		
							$('#ui-accordion-1-header-3 span:nth-child(4)').removeClass('notstred incomplete').addClass('complete');
							$('#ui-accordion-1-header-3 span:nth-child(4)').html('Complete');
							$("#cmpltkeysDate").removeClass("grey1").addClass("green");

						}else if(data == 1){
							$('#ui-accordion-1-header-3 span:nth-child(4)').removeClass('notstred complete').addClass('incomplete');
							$('#ui-accordion-1-header-3 span:nth-child(4)').html('In Progress');
							$("#cmpltkeysDate").removeClass("green").addClass("grey1");
						}
					}
				});	 
            }
        }); 
	}
});	

	function fnvalidate_keyDates() {
		$('span.error').html('');
		var error = 0;
		var startDate = $('#project_commencement').val();
		var endDate = $('#project_completion').val();
		var supplierDate = $('#supplier').val();

		var d = new Date();

		var month = d.getMonth()+1;
		var day = d.getDate();

		var output = ((''+day).length<2 ? '0' : '') + day + '-' +
			((''+month).length<2 ? '0' : '') + month + '-' +
			d.getFullYear();

		 $('form#keysDate .pickdate').each(function(i, obj){
				var date = $(this).val();
				var id = $(this).attr('id');
				$('#error_'+id).html('');

				if ( date =='' ) {
					$('span#error_'+id).html('Please fill any date');
					error = 1;
				} else {

					if($.datepicker.parseDate('dd-mm-yy',date) < $.datepicker.parseDate('dd-mm-yy',output) ){
						 $('span#error_'+id).html('Date should not be in the past');
						 error = 1;
					}

				}
		 });

		 if (error ==0) {

				if($.datepicker.parseDate('dd-mm-yy',startDate) >= $.datepicker.parseDate('dd-mm-yy',endDate) ){
					 $('span#error_project_completion').html('Project completion date should be greater than Project commencement');
					 return false;
				} else if( $.datepicker.parseDate('dd-mm-yy',supplierDate) < $.datepicker.parseDate('dd-mm-yy',startDate) || $.datepicker.parseDate('dd-mm-yy',supplierDate) > $.datepicker.parseDate('dd-mm-yy',endDate) ){
					 $('span#error_supplier').html('Supplier date should be greater than Project commencement & less than Project completion');
					 return false;
				} else {
					 return true;
				}

		 } else {
			 return false;
		 }

	}
});
