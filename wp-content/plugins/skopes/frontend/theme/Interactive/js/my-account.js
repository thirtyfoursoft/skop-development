$(document).ready(function() {
    $('input[type="checkbox"]').click(function() {
        if($(this).is(":checked")) {
			var total = calculateTotal();

        }
        else if($(this).is(":not(:checked)")) {
			var total = calculateTotal();
        }

		$('td.total_price').text('$'+total);
    });

	function calculateTotal() {
		var price = parseFloat(0.00).toFixed(2);
        $('input[type="checkbox"]').each(function() {
	        if($(this).is(":checked")) {

				price = (parseFloat($(this).attr('data-type')) + parseFloat(price)).toFixed(2);

	        }
		});

		return price;
	}

	$("#user_chanage_pass").validate({
		rules: {
			old_pass: "required",
			new_pass: {
				required: true,
				minlength: 6,
				maxlength: 10,
			},
			re_pass: {
				equalTo: "#new_pass",
				minlength: 6,
				maxlength: 10
			}
		},
		messages:{
			old_pass: "Please enter your current password.",
			new_pass: {
				required: 'Please enter your new password',
				minlength: 'Passsword should be atleast 6 character.',
				maxlength: 'Passsword should not be more than 10 character.',
			},
			re_pass: {
				equalTo: "Password does not match",
			}
		},
		submitHandler: function(form) {
			form.submit();
		}
	});

	$("#skopePlans").validate({
		rules: {
			'price[]':{ required:true }
		},
		messages:{
			'price[]': {
						required: "Please choose atleast one plan"
					}
		},
		errorPlacement: function(error, element) 
		{
			if ( element.is(":checkbox") ) 
			{
			    error.insertBefore( element.parents('.form-table') );
			}
			else
			{ // This is the default behavior
			    error.insertAfter( element );
			}
		},
		submitHandler: function(form) {
			form.submit();
		}

	});


// For checkout page

	$( "#chckout" ).validate({
	  rules: {
		payment_method: {
		  required: true
		},
//		cc_number : 'required'
	  },
		messages:{
			payment_method: "Please choose any payment method.",
//			cc_number : "Please enter credit card number."
		},
		errorPlacement: function(error, element) 
		{
			if ( element.is(":radio") ) 
			{
			    error.insertAfter( element.parents('ul.payment_methods') );
			}
			else
			{ // This is the default behavior
			    error.insertAfter( element );
			}
		},		
		submitHandler: function(form) {
			//form.submit();
			var form_data = $('#chckout').data();
			var lol;
			window.lol = form_data;
			console.log(lol);
			$('.warning, .attention').remove();
			
			$.ajax({
				type:		'POST',
				url:		window.location.pathname+'wp-content/plugins/skopes/frontend/theme/Interactive/processPayment.php',
				dataType: 'json',
				data:		$('#chckout').serialize(),
				beforeSend: function() {
					$('#place_order').attr('disabled', true);
					//$('#place_order').after('<span class="wait">&nbsp;<img src="wp-content/plugins/skopes/images/loading.gif" alt="" /></span>');
					$('#payment_forms').prepend('<div class="attention"><img src="wp-content/plugins/skopes/images/loading.gif" alt="" />&nbsp;Please wait ... </div>');
				},
				complete: function() {
					$('#place_order').attr('disabled', false);
					$('.attention').remove();
				},
				success: function( json ) {
					
					if (json['redirect']) {
						location = json['redirect'];
					} else if(json['error']) {
						$('#payment_forms').prepend('<div class="warning">'+json['error']+'</div>');
					}
				}
			});
			return false;
		}

	});

/*	$('ul.payment_methods label').on('click', function() {
			var Class = $(this).parent().attr('class');
			$('.payment_box').hide('slow');
			$(".payment_box."+Class).show('slow');
	});
*/
	$('input[type=radio]').live('change', function() {

		var paymentMethod = $(this).val();
		$.ajax({
			type:		'POST',
			url:		window.location.pathname+'wp-content/plugins/skopes/frontend/theme/Interactive/processPayment.php?action=loadForm&method='+paymentMethod,
			dataType: 'html',
			beforeSend: function() {
				$('#place_order').attr('disabled', true);
				$('#payment_forms').prepend('<div class="attention"><img src="wp-content/plugins/skopes/images/loading.gif" alt="" />&nbsp;Please wait ... </div>');
			},
			complete: function() {
				$('#place_order').attr('disabled', false);
				$('.attention').remove();
			},
			success: function( json ) {
				$('#payment_forms').html(json);
			}
		});
		
	});


});
