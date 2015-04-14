/**
	** Validation Functions 
**/

/********************* BENEFIT SECTION STARTS******************/

jQuery(function(){
	$('.help-click').click(function() {
		var id = $(this).attr('id');

		if($( "#"+id+'text' ).css('display') == 'block') {
			$('.help-text').slideUp("slow");
		} else {
			$('.help-text').slideUp("slow");
			$( "#"+id+'text' ).slideToggle( "slow");
		}
	});
})


function autotab(current,to){
    if (current.getAttribute && 
      current.value.length==current.getAttribute("maxlength")) {
        to.focus() 
        }
} 

function toUnicode(elmnt,content){
    if (content.length==elmnt.maxLength){
      next=elmnt.tabIndex
      if (next<document.forms[0].elements.length){
        document.forms[0].elements[next].focus()
      }
  }
}

function fnvalidate_benefits(){
	
	var benefits = document.getElementsByClassName('benefits');
	var regex = /^[A-Za-z]+( [A-Za-z]+)*$/;/*/^[a-zA-Z ]*$/;*/
	
	for (var i = 0; i < benefits.length; i++){
		
		var id = benefits[i].id;		
		
		if(benefits[i].value === ''){
		 $("#error_"+id).html('Enter Benefit'); 
		 $('#ui-accordion-accordion-header-2 span:nth-child(2)').removeClass('comp-gr').addClass('incomp-bl');
						$('#ui-accordion-accordion-header-2 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');						
						$('#ui-accordion-accordion-header-2   span:nth-child(2)').html('&nbsp;(In Progress)');
						$("#abcmpltbg").removeClass("green-complete").addClass("txt");
						$("#complete_benefit").parent().removeClass("btn-green1").addClass("btn-grey1");
		 return false;
		}
		/*else if (!regex.test(benefits[i].value)) {
            $("#"+id).focus();
			$("#error_"+id).html('Please enter only characters.');
$('#ui-accordion-accordion-header-2 span:nth-child(2)').removeClass('comp-gr').addClass('incomp-bl');
						$('#ui-accordion-accordion-header-2 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');						
						$('#ui-accordion-accordion-header-2   span:nth-child(2)').html('&nbsp;(In Progress)');
						$("#abcmpltbg").removeClass("green-complete").addClass("txt");
						$("#complete_benefit").parent().removeClass("btn-green1").addClass("btn-grey1");			
			return false;
        }*/
		else{
		  $("#error_"+id).html('');  
		}
	}
   return true;
   
}

var BenefitsWrapper1   = $("#BenefitsWrapper1"); //Input boxes wrapper ID
	var AddBenefitButton1  = $("#AddMoreBenefit1"); //Add benefit button ID
	if(BenefitsWrapper1.find('div.pi-left').size() >0){
		var y1 = BenefitsWrapper1.find('div.pi-left').size(); 
	}else{
		var y1 = 1;
	}
	//initlal text box count
	var BenefitMaxInputs = 8;

	var AnFieldCount = y1; //to keep track of text box added

	$(AddBenefitButton1).click(function (e)  //on add input button click
	{
        if(y1 <= BenefitMaxInputs) //max input box allowed
        {
            AnFieldCount++; //text box added increment
            //add input box
            //$(BenefitsWrapper1).append('<div><input type="text" name="anbenefits[]" id="anbenefit_'+AnFieldCount+'" value="" class="benefits"/><span class=" sherror error" id="error_anbenefit_'+AnFieldCount+'"></span><a href="#" class="removeabclass" onclick="decrement();">&times;</a></div>');
            $(BenefitsWrapper1).append('<div><div class="pi-left" style="margin-left: 3px;float: left;">Benefit '+AnFieldCount+'</div><div class="pi-middle"><input type="text" name="anbenefits[]" id="anbenefit_'+AnFieldCount+'" value="" class="benefits"/><span class=" sherror error" id="error_anbenefit_'+AnFieldCount+'"></span><a href="#" class="removeabclass" onclick="decrement();">&times;</a></div></div>');
            y1++; //text box increment
        }
	return false;
	});
	
	var BenefitsWrapper    = $("#BenefitsWrapper"); //Input boxes wrapper ID
	var AddBenefitButton   = $("#AddMoreBenefit"); //Add benefit button ID
	var y = BenefitsWrapper.find('div.pi-left').size(); //initlal text box count

	$(AddBenefitButton).click(function (e)  //on add input button click
	{
        if(y <= BenefitMaxInputs) //max input box allowed
        {
            AnFieldCount++; //text box added increment
            //add input box
            //$(BenefitsWrapper).append('<div><input type="text" name="anbenefits[]" id="anbenefit_'+AnFieldCount+'" value="" class="benefits"/><span class="error sherror" id="error_anbenefit_'+AnFieldCount+'"></span><a href="#" class="removeabclass" onclick="decrement();">&times;</a></div>');
            $(BenefitsWrapper).append('<div><div class="pi-left" style="margin-left: 3px;float: left;">Benefit '+AnFieldCount+'</div><div class="pi-middle"><input type="text" name="anbenefits[]" id="anbenefit_'+AnFieldCount+'" value="" class="benefits"/><span class="error sherror" id="error_anbenefit_'+AnFieldCount+'"></span><a href="#" class="removeabclass" onclick="decrement();">&times;</a></div></div>');
            y++; //text box increment
        }
		return false;
	});

	$("body").on("click",".removeabclass", function(e){ //user click on remove text

			if( y > 1 ) {
				$(this).parent('div').parent('div').remove(); //remove text box
				y--; //decrement textbox
			}
			
			if( y1 > 1 ) {
				$(this).parent('div').parent('div').remove(); //remove text box
				y1--; //decrement textbox
			}
			return false;
		});
		
//BENEFIt FORM SUBMISSION
	
	$('#frmanbenefit').on('submit', function(e){

        // prevent native form submission here
         e.preventDefault();
		/* if(fnvalidate_benefits()==false ){
		 		 return false;
		}else{  */
			
        // now do whatever you want here
        $.ajax({
            type: $(this).attr('method'), // <-- get method of form
            url:  $(this).attr('action'), // <-- get action of form
            data: $(this).serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
            beforeSend: function(){
                $('#abimg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');				
            },
            success: function(data){
                $('#BenefitsWrapper1').html(data);
				$('#abimg').hide();				
				$('#abmsg').html('Information Saved');
				$('#abmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
				$('#abimg').html('');
				var abcompleteval = $('#frmanbenefit').find('#abval').val();
					
				if(abcompleteval == 1){
					$('#ui-accordion-accordion-header-2 span:nth-child(2)').removeClass('comp-gr').addClass('incomp-bl');
						$('#ui-accordion-accordion-header-2 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');						
						$('#ui-accordion-accordion-header-2   span:nth-child(2)').html('&nbsp;(In Progress)');
						$("#abcmpltbg").removeClass("green-complete").addClass("txt");
						$("#complete_benefit").parent().removeClass("btn-green1").addClass("btn-grey1"); 
				}
            }
        });
	 // }
    });		

// on click of button complete of benefit section
	
	$('#complete_benefit').on('click', function(event){
		event.preventDefault();
		
		var totalNonFinBen = $("#totalNonFinBen").val(); 
		var totalNonFinBenValues = 0;
		
		for(i=1; i<=totalNonFinBen; i++)
		{   
			var RespVal = $('#anbenefit_'+i).val();
			if(RespVal != '') {
			   totalNonFinBenValues    = totalNonFinBenValues + 1; 
			}    		
		}    
	
		 if(fnvalidate_benefits()==false ){
		 		 return false;
		}else{ 
		           //$("#frmanbenefit").submit();
				   
						 $.ajax({
						type: $("#frmanbenefit").attr('method'), // <-- get method of form
						url:  $("#frmanbenefit").attr('action'), // <-- get action of form
						data: $("#frmanbenefit").serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file



						beforeSend: function(){
						    $('#abimg').show();
							$('#abimg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');				
						},
						success: function(data){
							//$('#BenefitsWrapper1').html(data);
							$('#abimg').hide();				
							$('#abmsg').html('Information Saved');
							$('#abmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
							$('#abimg').html('');
							var abcompleteval = $('#frmanbenefit').find('#abval').val();
							 
							 
							 $.ajax({ 

									type: "POST",
									url: "/wp-content/plugins/skopes/frontend/theme/Black/compltechk.php",
									data: { query: 'benefitinfo', totalNonFinBen: totalNonFinBen, totalNonFinBenValues: totalNonFinBenValues },
									beforeSend: function(){
										$('#abimg').show();
										$('#abimg').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');	 			
									},
									success: function(data){	
										 $('#abimg').hide();
										$('#abmsg').html('Information Saved');
										$('#abmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
										$('#abimg').html(''); 
										
										if(data == 2){ 
											$('#ui-accordion-accordion-header-2 span:nth-child(2)').removeClass('incomp-bl').addClass('comp-gr');
											$('#ui-accordion-accordion-header-2 span:nth-child(2)').removeClass('notstart-wh').addClass('comp-gr');						
											$('#ui-accordion-accordion-header-2 span:nth-child(2)').html('&nbsp;(Complete)');
											$("#abcmpltbg").removeClass("txt").addClass("green-complete");
											$("#complete_benefit").parent().removeClass("btn-grey1").addClass("btn-green1");
															
										}
										else if(data == 1){
											$('#ui-accordion-accordion-header-2 span:nth-child(2)').removeClass('comp-gr').addClass('incomp-bl');
											$('#ui-accordion-accordion-header-2 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');						
											$('#ui-accordion-accordion-header-2   span:nth-child(2)').html('&nbsp;(In Progress)');
											$("#abcmpltbg").removeClass("green-complete").addClass("txt");
											$("#complete_benefit").parent().removeClass("btn-green1").addClass("btn-grey1");
										}  
									}
							   });
						}
					});	   
				    
		
		  }// end of else
});

/********************* BENEFIT SECTION ENDS******************/

/********************* SELECTION CRETERIA SECTION STARTS******************/
function chckimpweight(){
	var stackholder1   = $("#stackholder_1").val(); //first Input boxes value
	if(stackholder1 == ""){
		$("#error_stackholder_1").html('Enter the name of stackholder');
		$("#stackholder_1").focus();
		return false;
	}
}

function countimptotal(){
  var sumimpweight=0;
	$('.impweight').each(function(i, obj) {   
		sumimpweight = sumimpweight+ parseInt($(this).val());   
	});
	return sumimpweight;
}



function checkselectioncretotal(){
	var total = $('.imptotal').html();
	 if(total<100 ){
	   $("#totalerror").html('Total of all creatia must be 100, please set the priority again'); 
		return false;
	 }
	 return true;
}


function checkselectioncre(){

/**
	** check the importance total after the value change
**/
$('.impweight').blur(function(){

    var elename = $(this).attr("name");
	var eleval = $(this).val(); 
	var intRegex = /^\d+$/;
	
	if(eleval == ''){
		$(this).val(0);
	}else if(intRegex.test(eleval)) {
		
	    if(eleval>100){		
			$("#error"+elename).html('Please enter value less then 100');
			$(this).focus();
			return false;
		}else{
			var total = countimptotal();
			$('.imptotal').html(total);
			if(total>100){	 
				$("#totalerror").html('Total of all creatia cannot exceed 100, please set the priority again'); 
				return false;
			}else if(total == 'NaN' || total == '' ){
				$("#totalerror").html('Please enter only digits'); 
				return false;
			}else{
				$("#totalerror").html(''); 
				$("#error"+elename).html('');
			}
			
		}
	}else{
	    $("#error"+elename).html('Please enter only numbers');
		$(this).focus();
		return false;
	  
	}
	
	
 });
 return true;
} 

function checkselectioncreonsubmit(){
}
function validate_alphabets(){
	$("#btnValidate").live("click", function () {
        var regex = /^[a-zA-Z ]*$/;
        if (regex.test($("#txtState").val())) {
            alert("Valid");
        } else {
            alert("Invalid");
        }
    });
}


// on click of complte button of selection creteria section
$('#compelte_Cretria').on('click', function(event){
	event.preventDefault();
	var imptotal = $(".imptotal").text();	
	if(checkselectioncretotal() == false){	
	 //return false;
	}
	/*else if(imptotal >100){	
	  $("#totalerror").html('Total of all creatia cannot exceed 100, please set the priority again'); 
	 return false;
	}*/ 
	var totalWeights = $("#totalWeights").val(); 
	
	var totalWeightsValues = totalWeights.split(","); 
	var totalCountWeightValues = 0;
	
	for(i=0; i<totalWeightsValues.length; i++)
	{  
	    var weight = totalWeightsValues[i];
		var WeightVal = $('#imp_weight_id_'+weight).val();
	    totalCountWeightValues = totalCountWeightValues + parseInt(WeightVal);
	}  
	
	if(isNaN(totalCountWeightValues))
	{
	    totalCountWeightValues = 100;
	}
	
	//alert(totalCountWeightValues); return false;
	
	if(checkselectioncre()== false){
	   //return false; 
		$("#totalerror").html('');		
		$.ajax({ 
				type: "POST",
				url: "/wp-content/plugins/skopes/frontend/theme/Black/compltechk.php", 
				data: { query: 'screteriainfo', totalCountWeightValues: totalCountWeightValues, imptotal: imptotal, data: $('#frmselectioncriteria').serialize() },
				beforeSend: function(){
					$('#result').show();
					$('#result').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');	 			
				},
				success: function(data){	
					$('#result').hide();
					$('#shmsg').html('Information Saved');
					$('#shmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
					$('#result').html(''); 
					var totalWeights = $("#totalWeights").val(); 
	                var totalWeightsValues = totalWeights.split(",");
					for(i=0; i<totalWeightsValues.length; i++)
					{ 
						$('#errorimp_weight_'+i).html(''); 
					}
					if(data == 2){ 
					    $('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('incomp-bl').addClass('comp-gr');
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('notstart-wh').addClass('comp-gr');						
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').html('&nbsp;(Complete)');
						$("#scbgcmplt").removeClass("txt").addClass("green-complete");
						$("#compelte_Cretria").parent().removeClass("btn-grey1").addClass("btn-green1");
										
					}else if(data == 0 || data == 1){
					    $("#totalerror").html('Total of all creatia cannot exceed 100, please set the priority again'); 
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('comp-gr').addClass('incomp-bl');
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');						
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').html('&nbsp;(In Progress)');
						$("#scbgcmplt").removeClass("green-complete").addClass("txt");
						$("#compelte_Cretria").parent().removeClass("btn-green1").addClass("btn-grey1");
					}
				}
		   });
	}else{	
		$("#totalerror").html('');		
		$.ajax({ 
				type: "POST",
				url: "/wp-content/plugins/skopes/frontend/theme/Black/compltechk.php", 
				data: { query: 'screteriainfo', totalCountWeightValues: totalCountWeightValues, imptotal: imptotal, data: $('#frmselectioncriteria').serialize() },
				beforeSend: function(){
					$('#result').show();
					$('#result').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');	 			
				},
				success: function(data){	
					$('#result').hide();
					$('#shmsg').html('Information Saved');
					$('#shmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
					$('#result').html(''); 
					var totalWeights = $("#totalWeights").val(); 
	                var totalWeightsValues = totalWeights.split(",");
					for(i=0; i<totalWeightsValues.length; i++)
					{ 
						$('#errorimp_weight_'+i).html(''); 
					}
					if(data == 2){ 
					    $('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('incomp-bl').addClass('comp-gr');
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('notstart-wh').addClass('comp-gr');						
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').html('&nbsp;(Complete)');
						$("#scbgcmplt").removeClass("txt").addClass("green-complete");
						$("#compelte_Cretria").parent().removeClass("btn-grey1").addClass("btn-green1");
										
					}else if(data == 0 || data == 1){
					    $("#totalerror").html('Total of all creatia cannot exceed 100, please set the priority again'); 
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('comp-gr').addClass('incomp-bl');
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');						
						$('#ui-accordion-accordion-header-0 span:nth-child(2)').html('&nbsp;(In Progress)');
						$("#scbgcmplt").removeClass("green-complete").addClass("txt");
						$("#compelte_Cretria").parent().removeClass("btn-green1").addClass("btn-grey1");
					}
				}
		   });
		   
		  } // end of else
	});
	
 //SELECTION CRITERIA FORM SUBMISSION
	$('#frmselectioncriteria').on('submit', function(e){

        // prevent native form submission here
        e.preventDefault();
		/* var imptotal = $(".imptotal").text();
		if(checkselectioncretotal() == false){
		 return false;
		}else if(imptotal >100 ){
		  $("#totalerror").html('Total of all creatia cannot exceed 100, please set the priority again'); 
		 return false;
		} else{
			$("#totalerror").html(''); */
			$("#totalerror").html('');
        // now do whatever you want here
        $.ajax({
            type: $(this).attr('method'), // <-- get method of form
            url: $(this).attr('action'), // <-- get action of form
            data: $(this).serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
            beforeSend: function(){
			
				//$('#savecretria').hide();
			$('#savecretria').prop('disabled', true);	
                $('#result').show(); 			
                $('#result').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');				
            },
            success: function(data){
                //$('#sccontentvalue').html(data);
				$('#result').hide();
				//$('#savecretria').show();	
				$('#savecretria').prop('disabled', false);
				
				//$('#sccontentvalue').html(data);
				$('#result').hide();
				$('#scmsg').html('Information Saved');
				$('#scmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
				$('#result').html('');
				var scompleteval = $('#frmselectioncriteria').find('#scval').val();
					
				//if(scompleteval == 1){ 
					$('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('comp-gr').addClass('incomp-bl');
					$('#ui-accordion-accordion-header-0 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');						
					$('#ui-accordion-accordion-header-0 span:nth-child(2)').html('&nbsp;(In Progress)');
					$("#scbgcmplt").removeClass("green-complete").addClass("txt");
					$("#compelte_Cretria").parent().removeClass("btn-green1").addClass("btn-grey1");
				//}
            }// end of success
        });
	  //}
    });
/********************* SELECTION CRETERIA SECTION ENDS******************/

/********************* TEAM MEMBER SECTION STARTS ******************/

/**  
		**save the team more info 
	**/
	$('#frmteammoreinfo').on('submit', function(e){
      
        // prevent native form submission here
        e.preventDefault();
		
		/* if(validateteaminfoform()==false ){
		 		 return false;
		}else{ */
		//if(memvalchk() == false){
		//  return false;
		//}else{
			
        // now do whatever you want here
        $.ajax({
            type: $(this).attr('method'), // <-- get method of form
            url: $(this).attr('action'), // <-- get action of form
            data: $(this).serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
            beforeSend: function(){
			    $('#teammoreresult').show();
                $('#teammoreresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');				
            },
            success: function(data){
                $('#teammoreinfo').html(data);
				$('#teammoreresult').hide();
				$('#tmresultmsg').html('Information Saved');
				$('#tmresultmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
				$('#teammoreresult').html('');
				var mcompleteval = $('#frmteammoreinfo').find('#teammoreval').val();
				
				if(mcompleteval == 1){
					$('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('comp-gr');
					//$('#ui-accordion-accordion-header-1 span:nth-child(2)').addClass('incomp-bl'); 	
                    $('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');					
					$('#ui-accordion-accordion-header-1 span:nth-child(2)').html('&nbsp;(In Progress)');
					$("#teamcplt").removeClass("green-complete").addClass("txt");
					$("#compltemembermoreinfo").parent().removeClass("btn-green1").addClass("btn-grey1");
				}
            }
        });
	 // }
    });

	
function memvalchk()
{
  var res = true;
  var memres = document.getElementsByClassName('memresp');
  var memeft = document.getElementsByClassName('memeffort');
  
  if($('#teamleadresp').val()=='')
	{
	  $("#error_teamleadresp").html('Enter team lead responsibility.'); 
	  $("#teamleadresp").focus();
	    $('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('comp-gr');
		$('#ui-accordion-accordion-header-1 span:nth-child(2)').addClass('incomp-bl');
        $('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');		
		$('#ui-accordion-accordion-header-1 span:nth-child(2)').html('&nbsp;(In Progress)');
		$("#teamcplt").removeClass("green-complete").addClass("txt");
		$("#compltemembermoreinfo").parent().removeClass("btn-green1").addClass("btn-grey1");
	  return false;
	}
	else if($('#teamleadresp').val().length>100){		 
		$("#error_teamleadresp").html('Only 100 character allowed.'); 
		$("#teamleadresp").focus();
		$('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('comp-gr');
		$('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');
				$('#ui-accordion-accordion-header-1 span:nth-child(2)').addClass('incomp-bl');	
                $('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl'); 				
				$('#ui-accordion-accordion-header-1 span:nth-child(2)').html('&nbsp;(In Progress)');
				$("#teamcplt").removeClass("green-complete").addClass("txt");
				$("#compltemembermoreinfo").parent().removeClass("btn-green1").addClass("btn-grey1");
		return false;
	}else{
	  $("#error_teamleadresp").html(''); 
	}

   if($('#teamlead_effort').val()=='' || $('#teamlead_effort').val()==0)
	{
	  $("#error_teamlead_effort").html('Enter team lead effort.'); 
	  $("#teamlead_effort").focus();
	  $('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('comp-gr');
	  $('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');
				$('#ui-accordion-accordion-header-1 span:nth-child(2)').addClass('incomp-bl');	
                $('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');				
				$('#ui-accordion-accordion-header-1 span:nth-child(2)').html('&nbsp;(In Progress)');
				$("#teamcplt").removeClass("green-complete").addClass("txt");
				$("#compltemembermoreinfo").parent().removeClass("btn-green1").addClass("btn-grey1");
	  return false;	
	}
	else if(!$.isNumeric($('#teamlead_effort').val())){
	  $("#error_teamlead_effort").html('Please enter numeric value.'); 
	  $("#teamlead_effort").focus();
	  $('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('comp-gr');
	  $('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');
				$('#ui-accordion-accordion-header-1 span:nth-child(2)').addClass('incomp-bl');	
                $('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl'); 				
				$('#ui-accordion-accordion-header-1 span:nth-child(2)').html('&nbsp;(In Progress)');
				$("#teamcplt").removeClass("green-complete").addClass("txt");
				$("#compltemembermoreinfo").parent().removeClass("btn-green1").addClass("btn-grey1");
	  return false;	
	}
	else{
	  $("#error_teamlead_effort").html(''); 
	} 	
		
	for(var i = 0; i < memres.length; i++){
		
		var id = memres[i].id;	// get id of the field	
//alert(memres[i].value);		
		if(memres[i].value === '' ){
			$("#error_"+id).html('Enter member responsibility.'); 
			res = false;
			$('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('comp-gr');
			$('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');
				$('#ui-accordion-accordion-header-1 span:nth-child(2)').addClass('incomp-bl');		
                $('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');				
				$('#ui-accordion-accordion-header-1 span:nth-child(2)').html('&nbsp;(In Progress)');
				$("#teamcplt").removeClass("green-complete").addClass("txt");
				$("#compltemembermoreinfo").parent().removeClass("btn-green1").addClass("btn-grey1");
			break;
		 } 
		else if(memres[i].value.length>100){
		
			$("#error_"+id).html('Only 100 character allowed.'); 
			res = false;
			$('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('comp-gr');
			$('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');
				$('#ui-accordion-accordion-header-1 span:nth-child(2)').addClass('incomp-bl');	
                $('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');				
				$('#ui-accordion-accordion-header-1 span:nth-child(2)').html('&nbsp;(In Progress)');
				$("#teamcplt").removeClass("green-complete").addClass("txt");
				$("#compltemembermoreinfo").parent().removeClass("btn-green1").addClass("btn-grey1");
			break;
		}else{
		  $("#error_"+id).html(''); 
		} 
	}
	
	for(var i = 0; i < memeft.length; i++){
		
		var id = memeft[i].id;	// get id of the field		
		if(memeft[i].value === '' || memeft[i].value ==0){
			$("#error_"+id).html('Enter member effort.'); 
			$(this).focus();
			res = false;
			$('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('comp-gr');
			$('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');
				$('#ui-accordion-accordion-header-1 span:nth-child(2)').addClass('incomp-bl');	
                $('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');				
				$('#ui-accordion-accordion-header-1 span:nth-child(2)').html('&nbsp;(In Progress)');
				$("#teamcplt").removeClass("green-complete").addClass("txt");
				$("#compltemembermoreinfo").parent().removeClass("btn-green1").addClass("btn-grey1");
			break;
		 } else if(!$.isNumeric(memeft[i].value)){
		    $("#error_"+id).html('Please enter numeric value.'); 
			$(this).focus();
			res = false;
			$('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('comp-gr');
			$('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');
				$('#ui-accordion-accordion-header-1 span:nth-child(2)').addClass('incomp-bl');	
                $('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');				
				$('#ui-accordion-accordion-header-1 span:nth-child(2)').html('&nbsp;(In Progress)');
				$("#teamcplt").removeClass("green-complete").addClass("txt");
				$("#compltemembermoreinfo").parent().removeClass("btn-green1").addClass("btn-grey1");
			break;
		 }
		else{
		  $("#error_"+id).html(''); 
		} 
	}
	
	if(res == false){
	  return false;
	  res = true;
	}
}

$(document).ready(function() 
{
   var imptotal = $(".imptotal").text();	
	
	/*else if(imptotal >100){	
	  $("#totalerror").html('Total of all creatia cannot exceed 100, please set the priority again'); 
	 return false;
	} */
	var totalWeights = $("#totalWeights").val(); 
	
	var totalWeightsValues = totalWeights.split(","); 
	var totalCountWeightValues = 0;
	
	for(i=0; i<totalWeightsValues.length; i++)
	{  
	    var weight = totalWeightsValues[i];
		var WeightVal = $('#imp_weight_id_'+weight).val();
	    totalCountWeightValues = totalCountWeightValues + parseInt(WeightVal);
	}
	
	if(isNaN(totalCountWeightValues))
	{
	     $(".imptotal").text(100);
	}
	else
	{
	     $(".imptotal").text(totalCountWeightValues);
	} 

$('#compltemembermoreinfo').on('click', function(event){
	event.preventDefault();
	
	var totalResp = $("#totalResp").val(); 
	if(totalResp == '') { 
	   alert('Please add some Team members by clicking on Edit link below');
	    return false;
	}	
	var totalRespValues = totalResp.split(","); 
	var totalCountRespValues = 0;
	var totalCountEffortsValues = 0;  
	var teamlead_effortV = $("#teamlead_effort").val(); 
	var teamleadrespV    = $("#teamleadresp").val();
	var totalResponsesF = totalRespValues.length + 1;
	
	if(teamleadrespV != '')
	{
	      totalCountRespValues    = totalCountRespValues + 1;    
	}
	for(i=0; i<totalRespValues.length; i++)
	{  
	    var Resp = totalRespValues[i];
		var RespVal = $('#memberresp_'+Resp).val();
		if(RespVal != '') {
	       totalCountRespValues    = totalCountRespValues + 1; 
		}    		
	}  
	
	if(teamlead_effortV != '')
	{ 
		 totalCountEffortsValues = totalCountEffortsValues + 1;   
	}
	for(i=0; i<totalRespValues.length; i++)
	{  
	    var Resp = totalRespValues[i];
		var RespVal = $('#membereffort_'+Resp).val();
		if(RespVal != '') { 
		   totalCountEffortsValues = totalCountEffortsValues + 1;
		}    		
	}  
 
	if(memvalchk() == false){  
		  return false;
	}else{
	
	    if(memvalchk() == false){
		  return false;
		}else{  
				
			$.ajax({
            type: $('#frmteammoreinfo').attr('method'), // <-- get method of form
            url: $('#frmteammoreinfo').attr('action'), // <-- get action of form
            data: $('#frmteammoreinfo').serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
            beforeSend: function(){
			    $('#teammoreresult').show();
                $('#teammoreresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');				
            },
            success: function(data){ 
				$('#teammoreresult').hide();
				$('#tmresultmsg').html('Information Saved');
				$('#tmresultmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
				$('#teammoreresult').html('');
				var mcompleteval = $('#frmteammoreinfo').find('#teammoreval').val();
				   
				    $.ajax({ 
								type: "POST",
								url: "/wp-content/plugins/skopes/frontend/theme/Black/compltechk.php",
								data: { query: 'memberinfo', totalCountRespValues: totalCountRespValues, totalCountEffortsValues: totalCountEffortsValues, totalResp: totalResponsesF },
								beforeSend: function(){
									$('#teammoreresult').show();
									$('#teammoreresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');	 			
								},
								success: function(data){	
										
									//$('#projectmain').html(data); 
									$('#teammoreresult').hide();
									$('#tmresultmsg').html('Information Saved');
									$('#tmresultmsg').hide().fadeIn(800).delay(3000).fadeOut(800);
									$('#teammoreresult').html('');
									var pcompleteval = $('#frmteammoreinfo').find('#teammoreval').val();
									
									if(data == 2){
										$('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('incomp-bl').addClass('comp-gr');
										$('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('comp-gr');
										$('#ui-accordion-accordion-header-1 span:nth-child(2)').html('&nbsp;(Complete)');
										$("#teamcplt").removeClass("txt").addClass("green-complete");
										$("#compltemembermoreinfo").parent().removeClass("btn-grey1").addClass("btn-green1");
										/* if($('#teamcplt').hasClass('txt')){
										   $(#teamcplt).removeClass('txt');
										   $(#teamcplt).addClass('green-complete');
										}	 */	 				
									}else if(data == 1){ 
										$('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('comp-gr').addClass('incomp-bl');
										$('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('notstart-wh').addClass('incomp-bl');					
										$('#ui-accordion-accordion-header-1 span:nth-child(2)').html('&nbsp;(In Progress)');
										$("#teamcplt").removeClass("green-complete").addClass("txt");
										$("#compltemembermoreinfo").parent().removeClass("btn-green1").addClass("btn-grey1");
									}
									
									/* $('#ui-accordion-accordion-header-1 span:nth-child(2)').removeClass('incomp-bl');
										$('#ui-accordion-accordion-header-1 span:nth-child(2)').addClass('comp-gr');
										$('#ui-accordion-accordion-header-1 span:nth-child(2)').html('&nbsp;(Complete)');
										$("#teamcplt").removeClass("txt").addClass("green-complete");
										$("#compltemembermoreinfo").parent().removeClass("btn-grey1").addClass("btn-green1"); */
								}
								 });
            }
          });	
				
				
			
      }
   
  }// end of else
});

});
/********************* TEAM MEMBER  SECTION ENDS ******************/
function validate_form(){


}// end of validaton function

checkselectioncre();
$(document).ready(function() {
   validate_form();
   checkselectioncre();
  
});


/* **********************  JS For Milestones info written By Roayl Tyagi  ************************* */

$(document).on("click", ".pickdate", function() {
	var id = $(this).attr('id');

	var $current = $(this); //the element you have
	var $elems = $('.pickdate'); //the collection of elements
	var $previous = $elems.eq($elems.index($current) - 1);
	
	$("#"+id).datepicker({changeYear: true,dateFormat: 'dd-mm-yy',changeMonth: true, yearRange : 'c:c+15',defaultDate: $previous.val()}).datepicker('show');
	
});

/*
$(document).on("click", ".pickdate", function(){
	 var id = $(this).attr('id');	 
	 $("#"+id).datepicker({changeYear: true,dateFormat: 'dd-mm-yy',changeMonth: true, yearRange : 'c-10:c+15',defaultDate: ''}).datepicker('show');
});
*/

$(document).ready(function() { 
	$('#AddMoreMilestone').live("click", addMilestoneFields);
	$('.removemclass').live("click", removemilestones);
});


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
}

function removemilestones(){

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

$('#frm_milestone').on('submit', function(e){

    e.preventDefault();
	if(fnvalidate_milestone()==false ){
		return false;
	}else{
		// now do whatever you want here
		$.ajax({
		    type: $(this).attr('method'), // <-- get method of form
		    url: $(this).attr('action'), // <-- get action of form
		    data: $(this).serialize(), // <-- serialize all fields into a string that is ready to be posted to your PHP file
		    beforeSend: function(){ 
				$('#milestoneresult').show('');
		        $('#milestoneresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');				
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
					
					}
		    }
		});
    }
});


function fnvalidate_project(e){

	if(fnvalidate_milestone()==false ){
		 return false; 
	}
	return true;

}

function fnvalidate_milestone(){
  
  var startDate = $("#datepickerfirst").val();
  var endDate = $("#datepicker11").val();
  breakOut = false; 
  pastDate = false;

	var d = new Date();

	var month = d.getMonth()+1;
	var day = d.getDate();

	var output = ((''+day).length<2 ? '0' : '') + day + '-' +
		((''+month).length<2 ? '0' : '') + month + '-' +
		d.getFullYear();
 
 if(startDate == ""){
 
   $("#error_dates").html('Enter start date from Key project dates section.');
   $("#datepickerfirst").focus();
    return false;
 } 
 else if(endDate == ""){
	$("#error_dates").html('Enter end date from Key project dates section.');
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
		} else if( $.datepicker.parseDate('dd-mm-yy',$(this).val()) <= $.datepicker.parseDate('dd-mm-yy',output) ) {
			pastDate = true;
			 $(this).focus();
			 return false;			
			
		} else {
		  $("#error_dates").html('');
	   }
  });
   if(breakOut) {
    $("#error_dates").html('Enter milestone date in between start and end date.');
    breakOut = false;
    return false;
    
  }

  if (pastDate) {
    $("#error_dates").html('Date should not be in past.');
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
                $('#milestoneresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');				
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
								url: "/wp-content/plugins/skopes/frontend/theme/Black/compltechk.php",
								data: { query: 'milestoneinfo' },
								beforeSend: function(){
									$('#milestoneresult').show();
									$('#milestoneresult').html('<img src="/wp-content/plugins/skopes/frontend/theme/Black/images/loader.gif" />');	 			
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
