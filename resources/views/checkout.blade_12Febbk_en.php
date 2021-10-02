@include('header')
<script src="js/creditly.js"></script>

<div class="container-fluid"  style="background:#fff;">
<div class="checkout-container">
<h1 class="heading_main">Checkout</h1>
<form class="method_sec creditly-card-form" method="post" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" data-cc-on-file="false" autocomplete="off" >
	<div class="row fluid-row container-checkOut">
	<div class="col-md-7">
	<h2>Billing address</h2>
	<div class="formBox border">
		<div class="billing_first">
		<div class="form-group">
		<label for="text" class="col-sm-12 col-form-label">Country <span>*</span></label>
		<select class="form-control" name="country">
		@foreach($country as $con)
			<option value="{{ $con->name }}" @if($con->id == '226') Selected @endif>{{ $con->name }}</option>
		@endforeach
		</select>
		</div>
			
					<div class="row">
							<div class="col-sm-12">
								<div class="inputBox ">
									<div class="inputText">Address line1 <span>*</span></div>
									<input type="text"  class="input" id="address_line1" name="address_line1">
								</div>
							</div>
					</div>
					<div class="row">
							<div class="col-sm-12">
								<div class="inputBox ">
									<div class="inputText">Address Line 2 <span>*</span></div>
									<input type="text"   class="input" id="address_line2" name="address_line2">
								</div>
							</div>
					</div>
					<div class="row">
							<div class="col-sm-12">
								<div class="inputBox ">
									<div class="inputText">City <span>*</span></div>
									<input type="text"  class="input" id="ciy" name="city">
								</div>
							</div>
					</div>
					<div class="row">
							<div class="col-sm-12">
								<div class="inputBox ">
									<div class="inputText">State / Province / Region <span>*</span></div>
									<input type="text"  class="input" id="state" name="state">
								</div>
							</div>
					</div>
					<div class="row">
							<div class="col-sm-12">
								<div class="inputBox ">
									<div class="inputText">Zip / Postal Code <span>*</span></div>
									<input type="text" class="input" id="zip" name="zip">
								</div>
							</div>
					</div>
	</div>
	</div>
	<h2>Patment method</h2>
	<div class="method border">
	
	<div class="row">
							<div class="col-sm-12">
								<div class="inputBox ">
									<div class="inputText">Name on Card <span>*</span></div>
									<input type="text"  class="input" id="cardname" name="cardname">
								</div>
							</div>
					</div>
	
	<div class="form-group">
	<label for="text" class="col-sm-12 col-form-label">Credit Card Number <span>*</span></label>
		<input type="text" class="form-control credit credit-card-number" name="cardnumber" id="cardnumber" inputmode="numeric" autocomplete="cc-number" autocompletetype="cc-number" x-autocompletetype="cc-number"
                  placeholder="&#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149;">
	<div class="pay_img">	
	<img src="/public/img/payment.png">
	</div>
	</div>
	<div class="form-row">
    <div class="form-group col-md-4">
      <label for="text" class="col-sm-12 col-form-label">Expiration Month <span>*</span></label>
      <input type="text" class="form-control card-expiry-month numeric" name="expirationdate" id="expirationdate" placeholder="MM" maxlength="2">
    
    </div>
	<div class="form-group col-md-4">
      <label for="text" class="col-sm-12 col-form-label">Expiration Year <span>*</span></label>

      <input type="text" class="form-control card-expiry-year numeric" name="expirationYeardate" id="expirationYeardate" placeholder="YYYY" maxlength="4">
    </div>
    <div class="form-group col-md-4" style="margin-bottom: 30px;">
     <label for="text" class="col-sm-12 col-form-label">CVV <span>*</span></label>
      <input type="text" class="form-control security-code" id="cvv" name="cvv" placeholder="123" placeholder="&#149;&#149;&#149;">
	  <div class="pay_img credit_im">	
	<img src="/public/img/credit_card.png">
	</div>
	</div>
  </div>
  
  <div class="card-type"></div>
	</div>	
	</div>	
		
		
		
		<div class="col-md-5">
		<h2>Order summary</h2>
		<div class="border_section">
		<div class="details">
		<div class="order_sec">
		<!--<p><strong>365-day Image On Demond, with 2 Standard License Download</strong></p>
		<p>Standard License<br>Downloads expire within a year of purchase</p>
		</div>
		<div class="order_pay">
		<p><strong>$29</strong></p>
		</div>-->
		
			<p>
		{!! $getplan->plan_name !!}
		
		</p>
		
		</div>
		<div class="order_pay">
		<p><strong>$ {!! $getplan->plan_price !!}</strong></p>
		</div>
		
		</div>
		
		<!--<div class="free_sec">
			<p><span>save 32%</span></p><p><strong class="border"><a href="#">Add</a></strong></p>
		</div>
			
		<div class="free_sec">
			<p>subtotal</p><p><strong>$29</strong></p>
		</div>
		<div class="quest_sec">
		<h4><span>Do you have a coupun code?</span></h4>
		<p>Never run out of downloads. When you run out or when your pack expires. We'll automatically renew your pack. Turn off your auto renewal at any time in your account settings or by contacting our Support Team.</p>
		</div>
		<div class="check0ut">
		<label for="vehicle2"><input type="checkbox" name="vehicle2" value="Boat" id="check_details" checked >Auto renewal</label>
		</div>-->
		<div class="free_sec">
			<p><strong>Ammount due to today</strong></p><p><strong>$ {!! $getplan->plan_price !!}</strong></p>
		</div>
		<div class="buy_button detail">
			<button type="submit" class="submit" id="complete-checkout">complete checkout</button>
		</div>
		<div class="check_com">
		<p><i class="fa fa-lock" aria-hidden="true"></i>Secure checkout. For your convenience Shutterstock will store your encrypted payment information for future orders. Manage you payment information in your Account Details.</p>
		</div>
		
		</div>
		</div>
	
	</div>
		</form>
</div>

<div class="thank-container" style="display:none">
<h1 class="heading_main">Thank You</h1>
		<div class="bd-highlight">
		<div class="check-mark">&#10003;</div>
			<h2>You're all set!</h2>
			<p class="transaction">Transaction Id: <span id="transaction_id"> txn_1GAaopBGINKMPLs3Ix01nF8U</span></p>
			<p>
			Thanks for being awsome,<br>we hope you enjoy your purchase!</p>
		
		  </div>
		  
</div>		
</div>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    $(function() {
      var creditly = Creditly.initialize(
          '.expiration-month-and-year',
          '.credit-card-number',
          '.security-code');

      $(".creditly-card-form .submit").click(function(e) {
        e.preventDefault();
		
		$("#complete-checkout").html('<i class="fa fa-refresh fa-spin"></i>Loading').prop("disabled", true);
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
					$(".thank-container").css('display','block');
					$("#transaction_id").text(data.transaction);
					$(".checkout-container").css('display','none').empty();
					
					 setTimeout(function(){ window.location = "/"; }, 15000);
				}else{
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
	   
    });



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

</script>

@include('footer')