/*
function openForm(formname) {
  document.getElementById("myForm").style.display = "block";
  if(formname == 'signup'){
		$(".btn-refresh").click();
  		document.getElementsByClassName('login_form')[0].style.display = "none";
  		document.getElementsByClassName('register_form')[0].style.display = "block";
  		document.getElementsByClassName('forgot_form')[0].style.display = "none";
  		document.getElementsByClassName('big-image')[0].style.display = "none";
  }else if(formname == 'signin'){
  		document.getElementsByClassName('login_form')[0].style.display = "block";
  		document.getElementsByClassName('register_form')[0].style.display = "none";
  		document.getElementsByClassName('forgot_form')[0].style.display = "none";
		document.getElementsByClassName('big-image')[0].style.display = "none";
  }else if(formname == 'forgot'){
  		document.getElementsByClassName('login_form')[0].style.display = "none";
  		document.getElementsByClassName('register_form')[0].style.display = "none";
  		document.getElementsByClassName('forgot_form')[0].style.display = "block";
		document.getElementsByClassName('big-image')[0].style.display = "none";
  }

}
*/

function closeForm() {
  document.getElementById("myForm").style.display = "none";
}
function closebackdrop(){
	$(".modal-backdrop").addClass("hide");
	
	
}
	
// $(document).on("click",".ng-binding",function () {
   // $("html, body").animate({scrollTop: 0}, 0);
// });


function emailcheck(email){
	var email = email;
	var token=$('meta[name="csrf-token"]').attr('content');
	$.ajax({     
		url: '/checkmail',
		type:"POST", 
		async: true,
        dataType: 'json',		
		headers: {
			'X-CSRF-TOKEN':token
		},        
		data:'email='+email+'&_token='+token,
		success:function(data){ 
			if(data.response == 3){
				$("#errorMessage").html('<div class=""><strong>Email already exist.</strong> </div>');
				myFunction();
				
			}
		}
	}); 	
}


$(document).ready(function(){
	
    $('#registrationForm').submit(function(e){
			var password = $("#signuppassword").val();
            var confirmPassword = $("#signupconfirmpassword").val();
            if (password != confirmPassword) {
                //alert("Passwords do not match.");
				$(".error_msg_2").show();
                return false;
            }

		$.ajaxSetup({
            url: "/registration",
            data: $(this).serialize(),
            async: true,
            dataType: 'json',
          });
        $.post()
        .done(function(data) {
			
			if(data.response==1){
				window.location = '/';
			}if(data.type=='error'){
					$("#errorMessage").html('<div class=""><strong>'+data.text+'</strong> </div>');
					myFunction();
				}
			if(data.response=='3'){
					$("#errorMessage").html('<div class=""><strong>Email already exist</strong> </div>');
					myFunction();
				}
            console.log(data);
        })
        .fail(function(data) {
			$("#errorMessage").html('<div class=""><strong>Invalid Captcha</strong> </div>');
					myFunction();
				
					
				
        })
    });
	$('#registrationForm2').submit(function(e){
			var password = $("#signuppassword2").val();
            var confirmPassword = $("#signupconfirmpassword2").val();
            if (password != confirmPassword) {
                //alert("Passwords do not match.");
				$(".error_msg_2").show();
                return false;
            }

		$.ajaxSetup({
            url: "/registration",
            data: $(this).serialize(),
            async: true,
            dataType: 'json',
          });
        $.post()
        .done(function(data) {
			
			if(data.response==1){
				location.reload();
				//window.location = '/';
			}if(data.type=='error'){
					$("#errorMessage").html('<div class=""><strong>'+data.text+'</strong> </div>');
					myFunction();
				}
			if(data.response=='3'){
					$("#errorMessage").html('<div class=""><strong>Email already exist</strong> </div>');
					myFunction();
				}
            console.log(data);
        })
        .fail(function(data) {
			$("#errorMessage").html('<div class=""><strong>Invalid Captcha</strong> </div>');
					myFunction();
				
					
				
        })
    });

	

/* 		$('#forgotForm').submit(function(e){
        $.ajaxSetup({
            url: "/forgot_password",
            data: $('#forgotForm').serialize(),
            async: true,
            dataType: 'json',
            
        });
        $.post()
        .done(function(data) {
			
			if(data.response==1){
			
				$("#errorMessage").html('<div class=""><strong>Email has been sent on your email. </strong> </div>');
				myFunction();
			}if(data.response==0){
				$("#errorMessage").html('<div class=""><strong>Email id not exist.</strong> </div>');
				myFunction();
			}
        })
        .fail(function(response) {
			
        })
    }); */
	
	
});

function myFunction() {
  var x = document.getElementById("errorMessage");
  var y = document.getElementById("availablecredit");
  var z = document.getElementById("incartcredit");
  // $("#availablecredit").empty();
	 // $("#incartcredit").empty();
  x.className = "show";
 // y.className = "show";
 // z.className = "show";
  setTimeout(function(){ 
  x.className = x.className.replace("show", "hide"); 
  //y.className = y.className.replace("show", "hide"); 
 // z.className = z.className.replace("show", "hide");
	 $("#errorMessage").empty();
	 
  }, 5000);
}

function getCredit(){
var content = $("#ImageType").val();
var stock = $("#ImageCategory").val();
var CurrentPage = $("#CurrentPage").val();
if(CurrentPage == 'i'){
if(content == ""){
   content = 0;
}
if(stock == ""){
   stock = 0;
}
var standard = "";
if(content == 1){
   standard = "standard";
}else if(content == 2){
   standard = "premium";
}else if(content == 3){
   standard = "ultra_premium";
}


$("#image-desc").removeAttr("class").addClass("btn "+standard);

if(content!=''){
				if(content=='1'){
					var imagetype='Standard';
					$("#image-desc").html(imagetype);
				}if(content=='2'){
					var imagetype='Premium';
					$("#image-desc").html(imagetype);
				}if(content=='3'){
					var imagetype='Ultra Premium';
					$("#image-desc").html(imagetype);
				}
				
			}
var token=$('meta[name="csrf-token"]').attr('content');
var productid=$('#productid').val();
$.ajax({     
	url: '/checkstock',
	type:"post",
	headers: {
		'X-CSRF-TOKEN':token
	},    
	dataType: 'json',
	data:'content='+content+'&stock='+stock+'&productid='+productid+'&_token='+token,
	success:function(data){ 
		if(data.response == 'Done'){
						if(data.stock == 0){
							if(data.instock == "alreadydownload"){
								$("#download-image").addClass('btn-green');
								$("#downloadstatus").val(1);
								$("#before_cerdits").removeClass("col-md-6").addClass("col-md-12 availHeight");
								$("#credits-stock").css("display","none");
								$("#after_credits").removeClass("after_bd").css('display',"block");
								$("#credit-count").text(data.available_stock);
							}else{
								$("#downloadstatus").val(0);
								$("#download-image").removeClass('btn-green');
								$("#before_cerdits").removeClass("col-md-6").addClass("col-md-12 availHeight");
								$("#credits-stock").css("display","none");
								$("#after_credits").removeClass("after_bd").css('display',"block");
								$("#credit-count").text(data.available_stock);
							}
							
							
						}else{
							$("#after_credits").removeClass("after_bd").addClass("after_bd");
							$("#before_cerdits").removeClass("col-md-12 availHeight").addClass("col-md-6");
							$("#credit-count").text(data.available_stock);
							$("#img-credits").text(data.stock +" Credits");
							$("#after_credits").css("display","block");
							$("#no_package").css("display","none");
							$("#credits-stock").css("display","block");
							
						}
						
					}
		
	}
}); 
}
}
function XchangePassword() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
	$(".showPassword").html('<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0zm0 0h24v24H0V0zm0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"/><path d="M12 6.5c2.76 0 5 2.24 5 5 0 .51-.1 1-.24 1.46l3.06 3.06c1.39-1.23 2.49-2.77 3.18-4.53C21.27 7.11 17 4 12 4c-1.27 0-2.49.2-3.64.57l2.17 2.17c.47-.14.96-.24 1.47-.24zM2.71 3.16c-.39.39-.39 1.02 0 1.41l1.97 1.97C3.06 7.83 1.77 9.53 1 11.5 2.73 15.89 7 19 12 19c1.52 0 2.97-.3 4.31-.82l2.72 2.72c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L4.13 3.16c-.39-.39-1.03-.39-1.42 0zM12 16.5c-2.76 0-5-2.24-5-5 0-.77.18-1.5.49-2.14l1.57 1.57c-.03.18-.06.37-.06.57 0 1.66 1.34 3 3 3 .2 0 .38-.03.57-.07L14.14 16c-.65.32-1.37.5-2.14.5zm2.97-5.33c-.15-1.4-1.25-2.49-2.64-2.64l2.64 2.64z" fill="#5B5C5C"/></svg>');
  } else {
    x.type = "password";
	$(".showPassword").html('<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 4C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="#5B5C5C"/></svg>');
  }
}
function XPassword() {
  var x = document.getElementById("signuppassword");
  if (x.type === "password") {
    x.type = "text";
	$(".showXpassword").html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
  } else {
    x.type = "password";
	$(".showXpassword").html('<i class="fa fa-eye" aria-hidden="true"></i>');
  }
}
function YPassword() {
  var x = document.getElementById("signuppassword2");
  if (x.type === "password") {
    x.type = "text";
	$(".showPassword_reg").html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
  } else {
    x.type = "password";
	$(".showPassword_reg").html('<i class="fa fa-eye" aria-hidden="true"></i>');
  }
}
function zPassword() {
  var x = document.getElementById("cartsignuppassword");
  if (x.type === "password") {
    x.type = "text";
	$(".showXpassword").html('<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0zm0 0h24v24H0V0zm0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"/><path d="M12 6.5c2.76 0 5 2.24 5 5 0 .51-.1 1-.24 1.46l3.06 3.06c1.39-1.23 2.49-2.77 3.18-4.53C21.27 7.11 17 4 12 4c-1.27 0-2.49.2-3.64.57l2.17 2.17c.47-.14.96-.24 1.47-.24zM2.71 3.16c-.39.39-.39 1.02 0 1.41l1.97 1.97C3.06 7.83 1.77 9.53 1 11.5 2.73 15.89 7 19 12 19c1.52 0 2.97-.3 4.31-.82l2.72 2.72c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L4.13 3.16c-.39-.39-1.03-.39-1.42 0zM12 16.5c-2.76 0-5-2.24-5-5 0-.77.18-1.5.49-2.14l1.57 1.57c-.03.18-.06.37-.06.57 0 1.66 1.34 3 3 3 .2 0 .38-.03.57-.07L14.14 16c-.65.32-1.37.5-2.14.5zm2.97-5.33c-.15-1.4-1.25-2.49-2.64-2.64l2.64 2.64z" fill="#5B5C5C"/></svg>');
  } else {
    x.type = "password";
	$(".showXpassword").html('<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 4C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="#5B5C5C"/></svg>');
  } 
}

function loginzPassword() {
  var x = document.getElementById("cart-loginpassword");
  if (x.type === "password") {
    x.type = "text";
	$(".loginshowXpassword").html('<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0zm0 0h24v24H0V0zm0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"/><path d="M12 6.5c2.76 0 5 2.24 5 5 0 .51-.1 1-.24 1.46l3.06 3.06c1.39-1.23 2.49-2.77 3.18-4.53C21.27 7.11 17 4 12 4c-1.27 0-2.49.2-3.64.57l2.17 2.17c.47-.14.96-.24 1.47-.24zM2.71 3.16c-.39.39-.39 1.02 0 1.41l1.97 1.97C3.06 7.83 1.77 9.53 1 11.5 2.73 15.89 7 19 12 19c1.52 0 2.97-.3 4.31-.82l2.72 2.72c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L4.13 3.16c-.39-.39-1.03-.39-1.42 0zM12 16.5c-2.76 0-5-2.24-5-5 0-.77.18-1.5.49-2.14l1.57 1.57c-.03.18-.06.37-.06.57 0 1.66 1.34 3 3 3 .2 0 .38-.03.57-.07L14.14 16c-.65.32-1.37.5-2.14.5zm2.97-5.33c-.15-1.4-1.25-2.49-2.64-2.64l2.64 2.64z" fill="#5B5C5C"/></svg>');
  } else {
    x.type = "password";
	$(".loginshowXpassword").html('<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 4C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="#5B5C5C"/></svg>');
  } 
}

	function mainlogin(){
		
		 $.ajaxSetup({
            url: "/login",
            data: $('#loginForm').serialize(),
            async: true,
            dataType: 'json',
            
        });
        $.post()
        .done(function(data) {
			if(data.response == 1){
				if(data.pricing_flow==''){
				location.reload(); 
			
				}else{
				$('#exampleModal').modal('hide'); 
				$('#sign-in').remove();
				$('#log-in').remove();
				$("#login-form").addClass("close-loginform");
				$(".modal-backdrop").addClass("hide");
				
				$('#login-user').removeClass('close-loginform'); 
				$('#login-user').addClass('show-logininfo'); 
				$('#user-info').removeClass('close-loginform');
				$('#user-info').addClass('show-logininfo');
				$('#log-in').removeClass('show-logininfo');
				$('#log-in').addClass('close-loginform');
				$('#sign-in').removeClass('show-logininfo');
				$('#sign-in').addClass('close-loginform');
				$('#package-detail').val(data.val);
				if(data.package_type=='Y'){
					$('#Y-'+data.current_packageid).addClass(' active_package');
					$('.Y-'+data.current_packageid).addClass(' hide-btn');
					$('.Y-'+data.current_packageid).attr("disabled", true);
					$('#yearly-active-'+data.current_packageid).removeClass("hide");
					$('#yearly-active-'+data.current_packageid).addClass("show");
					
				}if(data.package_type=='M'){
					$('#M-'+data.current_packageid).addClass(' active_package');
					$('.M-'+data.current_packageid).addClass(' hide-btn');
					$('.M-'+data.current_packageid).attr("disabled", true);
					$('#monthly-active-'+data.current_packageid).removeClass("hide");
					$('#monthly-active-'+data.current_packageid).addClass("show");
				}
				
				if(data.val=='yes'){
				$('#package-detail').attr('data-value',data.pack);
				}
				$("#uniqueid").val(data.id); 
				$(".login-details").html('<span class="username-info">Welcome, '+data.name+'</span><img class="user-logo" src="/images/'+data.logo+'">');
				$("#cartcount").html(data.carticon+'<span>'+data.count+'</span>');
				 $("#cartcount").attr("href", '/cart');
				 $('#cartcount').removeClass('cart-login');
				  if(data.cartvalue>0){
				 $('#availablecredit').html('<div class=""><strong>Credits : '+data.availablecredit+'  </strong> </div>');
				  }
				 if(data.cartvalue>0){
				 $('#incartcredit').html('<div class=""><strong>In Cart : '+data.cartvalue+'  </strong> </div>');
				 }
				 if(data.verifystatus == 0){
					 $(".verfiy-email").toggle();
					 $("html, body").animate({ scrollTop: 0 }, "slow");
				 }
				//window.location = '/';
				//alert(data.availablecredit);
					$("#credit-count").text(data.availablecredit);
				getCredit();
				
					var option = [];
						 option.push('<option value="">Select</option>');
						 $.each(data.country, function (i) {
							$.each(data.country[i], function (key, val) {
								var selected="";
								if(data.billing_address!=null){
									if(data.billing_address.billing_country==val){
										selected="Selected";
								
										}
								}else{
									selected='';
									
								}
							option.push('<option value="'+ val +'"'+selected+'>'+ val +'</option>');
							});
						});
						$('#mySelect').html(option.join(''));
						if(data.billing_address!=null){
						$('#address_line1').val(data.billing_address.billing_address_line1);
						$('#address_line2').val(data.billing_address.billing_address_line2);
						$('#city').val(data.billing_address.billing_city);
						$('#state').val(data.billing_address.billing_state);
						$('#zip').val(data.billing_address.billing_zipcode);
						}else{
						$('#address_line1').val('');
						$('#address_line2').val('');
						$('#city').val('');
						$('#state').val('');
						$('#zip').val('');	
							
						}
						if(data.card_details!=null){
						$('#cardname').val(data.card_details.holder_name);
						$('#cardnumber').val(data.card_details.c_number);
						$('#expirationdate').val(data.card_details.exp_month);
						$('#expirationYeardate').val(data.card_details.exp_year);
						
						}else{
						$('#cardname').val('');
						$('#cardnumber').val('');
						$('#expirationdate').val('');
						$('#expirationYeardate').val('');
						
							
						}
						
						if(data.type=="annual"){
						
							var price_type='Annual Payment';
							var price_prefix=' /Annually';
							$("#plan-price").html('<strong>$'+data.annually_price+price_prefix+'</strong>');
							$("#plan-name").text(data.getplan.plan_title+' - '+price_type);
							
						}else if(data.type=="monthly"){
							
							var price_type='Monthly Payment';
							var price_prefix=' /Month';
							$("#plan-price").html('<strong>$'+data.monthly_price+price_prefix+'</strong>');
							$("#plan-name").text(data.getplan.plan_title+' - '+price_type);
							
						}else{
							$("#plan-price").html('<strong>$'+data.onetime_price+'</strong>');
							$("#plan-name").text(data.getplan.plan_name+' - '+'One Time Purchase');
							
						}
						
							
					if(data.buyid==''){
							$("#checkoutModal").modal("show");
				}else{
				
					$("#checkoutModal").modal("show");
					 $('#old_packageid').val(data.current_packageid);
				
							
					} 
				}
			}if(data.response == 2){
				//$("#errorMessage").html('<div class=""><strong>Invalid login credentials.</strong> </div>');
				$("#login-error").html('<div class="alert alert-danger"><strong>Invalid login credentials.</strong></div>');
				myFunction();
			}if(data.response==0){
				$("#login-error").html('<div class="alert alert-danger"><strong>Your account has been deactivated.</strong></div>');
				myFunction();
			}
        })
        .fail(function(response) {
			
			$("#login-error").html('<div class="alert alert-danger"><strong>Invalid login credentials.</strong></div>');
			
        })
		
    }

function forgot_form(){
        $.ajaxSetup({
            url: "/forgot_password",
            data: $('#forgotForm').serialize(),
            async: true,
            dataType: 'json',
            
        });
        $.post()
        .done(function(data) {
			
			if(data.response==1){
				
			$("#forgot-error").html('<div class="alert alert-success"><strong>Email has been sent on your email.</strong></div>');
				myFunction();
			}if(data.response==0){
				$("#forgot-error").html('<div class="alert alert-danger"><strong>Email id not exist.</strong></div>');
				myFunction();
			}
        })
        .fail(function(response) {
			
        })
}
	
	
		
    //$('#registrationForm').submit(function(e){
		function register(){
			var password = $("#cartsignuppassword").val();
            var confirmPassword = $("#cartsignupconfirmpassword").val();
            if (password != confirmPassword) {
                //alert("Passwords do not match.");
				$(".error_msg_2").show();
                return false;
            }

		$.ajaxSetup({
            url: "/registration",
            data: $('#cartregister_form').serialize(),
            async: true,
            dataType: 'json',
          });
        $.post()
        .done(function(data) {
			//alert(data);
			if(data.response==1){
				window.location = '/';
			}if(data.type=='errors'){
					$("#signup-error").html('<div class="alert alert-danger"><strong>'+data.text+'</strong></div>');
					
				}
			if(data.response=='3'){
				$("#signup-error").html('<div class="alert alert-danger"><strong>Email already exist.</strong></div>');
					
				}
				
		if(data.response=='4'){
				$("#signup-error").html('<div class="alert alert-danger"><strong>Invalid Captcha Response.</strong></div>');
					
				}
           
        })
      
		}
   // });