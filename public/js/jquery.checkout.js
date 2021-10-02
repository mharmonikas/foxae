$(document).ready(function(){
      var creditly = Creditly.initialize(
          '.expiration-month-and-year',
          '.credit-card-number',
          '.security-code');

      $(".creditly-card-form .submit").click(function(e) {
            e.preventDefault();

            if(!validateFields()) {
                return false;
            }

            $("#complete-checkout").html('<i class="fa fa-refresh fa-spin"></i><span>Loading</span>').prop("disabled", true);
            var $form = $(".creditly-card-form");
            if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.credit-card-number').val(),
                    cvc: $('.security-code').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
            }
            setTimeout(function(){
            var token=$('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/payment',
                type:"POST",
                async: true,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN':token
                },
                data: $('.creditly-card-form').serialize(),
                success:function(data){
                    if(data.response == 'done'){
                        $("#checkoutModal").modal("hide");
                        //$(".thank-container").css('display','block');
                        //$("#transaction_id").text(data.transaction);
                        //$(".checkout-container").css('display','none').empty();
                        if(data.type=='direct'){
                            downloaddirect();
                        }else{
                            $("#subscribe_success").removeClass('hide');
                            $("#subscribe_success").addClass('show');
                        //myFunction();
                        setTimeout(function(){ window.location = '/'; }, 2000);
                        }
                    }else{
                        if(data.code == 301){
                            $("#errorMessage").html('<div class=""><strong>Your account is not verified. Please verify your account.</strong> </div>');
                            myFunction();
                        }
                        $("#complete-checkout").text('complete checkout').prop("disabled", false);
                    }
                }
            });
            }, 2000);

          });

          $(".numeric").keypress(function (e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                       return false;
                }
            });
          $(".input").focus(function() {
	 		$(this).parent().addClass("focus");
	 	});

        function validateFields() {
            let valid = true

            if($("#address_line1").val() == ""){
                valid = false;
                $("#address_line1").addClass('error-repot');
            }
            if($("#city").val() == ""){
                valid = false;
                $("#address_line1").addClass('error-repot');
            }
            if($("#state").val() == ""){
                valid = false;
                $("#state").addClass('error-repot');
            }
            if($("#zip").val() == ""){
                valid = false;
                $("#zip").addClass('error-repot');
            }if($("#cardname").val() == ""){
                valid = false;
                $("#cardname").addClass('error-repot');
            }if($("#cardnumber").val() == ""){
                valid = false;
                $("#cardnumber").addClass('error-repot');
            }if($("#expirationdate").val() == ""){
                valid = false;
                $("#expirationdate").addClass('error-repot');
            }if($("#expirationYeardate").val() == ""){
                valid = false;
                $("#expirationYeardate").addClass('error-repot');
            }if($("#cvv").val() == ""){
                valid = false;
                $("#cvv").addClass('error-repot');
                console.log($('#cvv'))
            }

            if(!valid) {
                console.log($('#missingFieldsMessage'))
                $('#missingFieldsMessage').css('visibility', 'visible');
            }

            return valid;

            // if(!element.val()) {
            //     element.addClass('error-repot');
            //     $('#missingFieldsMessage').style('display', 'block');
            //     return false
            // }
            //
            // return true
        }
    });

$("input").click(function(){
	$(this).removeClass('error-repot');
})

function downloaddirect(){
var token=$('meta[name="csrf-token"]').attr('content');
 var checked = [];
						$.each($("input[name='checkbox_status']:checked"), function(){
							checked.push($(this).val());
							//checked = $(this).val();
						});
		$(".download-process").html('<i class="fa fa-refresh fa-spin"></i> Download');
		$.ajax({
			url: '/downloadcart',
			type:"POST",
			async: true,
			dataType: 'json',
			headers: {
				'X-CSRF-TOKEN':token
			},
			data:'type='+'direct'+'&_token='+token+'&check_id='+checked,
			success:function(data){
			var urls = [];
			var cartid = [];
			var downloadid = [];
			$("#download_success").removeClass('hide');
			$("#download_success").addClass('show');
			setTimeout(function(){
				$("#download_success").removeClass('show');
				$("#download_success").addClass('hide');
							}, 5000);
			//$(".availablecount").html(data.available);
			var response = data.response;
			console.log(response);

				$(".download-process").html('Download');
				for(i=0; i<response.length; i++){
					 cartid.push(response[i]['cartid']);
					 downloadid.push(response[i]['downloadid']);
					//urls.push('/fileTodownload/'+response[i]['downloadid']);
					//alert(urls.push('/fileTodownload/'+response[i]['downloadid']));
				 }
				$("#checkoutModal").modal("hide");
				//var myVar =  setInterval(removetocart, 2200, cartid);
					i = 0;
				$("#cartcount").html('<img src="/public/images/'+data.carticon+'"><span>'+data.cartcount+'</span>');
				$("#availablecredit").html('<div class=""><strong>Credits :'+data.available+'</strong> </div>');

				//$( ".crat-price" ).html("<span>In Cart("+data.cartcount+" items):</span><span><b>"+data.cartvalue+"</b></span>");
				//$( "#cart-text" ).html("<span>Subtotal("+data.totalitems+" items):</span><span><b>"+data.cartvalue+"</b></span>");


				var token=$('meta[name="csrf-token"]').attr('content');
					$.ajax({
						url: '/fileTodownload1',
						type:"GET",
						async: true,
						dataType: 'json',
						headers: {
							'X-CSRF-TOKEN':token
						},
						data:'type='+'direct'+'&downloadid='+downloadid+'&_token='+token,
						success:function(data){
							 var a = document.createElement("a");
							a.setAttribute('href', '/download-zip?='+Math.random());
							a.click();
							setTimeout(function(){
									location.reload();
							}, 6000);


						}

					});

			 }
		});







}

function stripeResponseHandler(status, response) {
  console.log(response);
	if (response.error) {
	    alert('Invalid Card Details');
		$("#complete-checkout").text('complete checkout').prop("disabled", false);
		return false;
	} else {
		var token = response['id'];
		$(".card-type").html("<input type='hidden' name='stripeToken' value='" + token + "'/>");
	}
}
