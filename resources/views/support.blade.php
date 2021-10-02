@include('header')
  
<style>
@media only screen and (max-width: 480px){
#price-plan-from .col-md-6 {
    width: 100%;
}
}

.modal .modal-dialog.checkoutModal {
    max-width: 950px;
}
select.form-control {
    background-color: #fde9b3;
   
}
#pricing {
  
    color: #5B5C5C;
}
.pricing_total_section a {
    padding-left: 5px;
   cursor: pointer;
    font-size: 18px;
    font-weight: bold;
	 margin-top: 0;
    margin-bottom: 1rem;
}
.tabcontent {
    display: none;
   
}

.clicked{
	 color: #FF9800 !important;
	
}

.ques-sec {
    font-size: 18px;
    font-weight: bold;
    padding-bottom: 15px;
}
.ans-sec{
    font-size: 18px;
	font-weight: 400;
    
}

.main_customform .customform_inner, .main_customform .customform_video {
    width: 75%;
}

li {
    padding-bottom: 20px;
}
.support-link a {
    color: #0044CC;
}

.support-link{
    list-style: none;
    font-size: 18px;
    padding: 0px;
    margin-left: -23px;
	font-weight: 400;
}

.custom-control {
     margin-top: 40px;
}
.ques-pair {
    margin-top: 40px;
}
.support .main_customform {
    justify-content: center;
   margin-left: 25px;
}
.customform_inner .form-group textarea {
    height: 318px !important;
   
}
#collapse1,#collapse_two,#collapse_three{
    cursor: pointer;
    color: #0044CC;
}
@media only screen and (max-width: 767px){
.main_customform .customform_inner, .main_customform .customform_video {
    width: 100%;
}
}
</style>
<div class="container-fluid" id="pricing">
	<div class="row fluid-row  new_pricing support">
		<!--<h2>Compelling media for every case!</h2>-->
		
		<div class="col-md-12 pricing-container">
			
				
			
			<div class="pricing_main_section buy_section">
				<p class="p-heading">Frequently Asked Question</p>
				<p>
				<a id="collapse1" data-target="#toggle-example">Collapse</a>
				</p>
			</div>
			
		
			<div id="toggle-example" class="" style="display: block;">
				<div class="pricing_main_ques">
				@foreach($faqs as $faq)
					<div class="ques-pair">
						<div class="ques-sec"> {{$faq->question}}</div>
						<div class="ans-sec"> {{$faq->answer}}</div>
					</div>
				@endforeach
				</div>
			</div>
			
		<div class="pricing_main_section">
			<p class="p-heading">Legal</p>
			<a  id="collapse_two" >Collapse</a>
		</div>
			
					
			<div id="collapseExample2" style="display: block;">
				<div class="pricing_main_ques no_border">
					<div class="custom-control custom-checkbox">
					<ul class="support-link">
						<li><a href="/privacypolicy"> Privacy Policy</a></li>
						<li><a href="/termscondition"> Terms & Conditions </a></li>
						<li><a href="/userlicence"> User Licence</a></li>
					</ul>	
					</div>
				</div>
			</div>
			
	
			<div class="pricing_main_section">
			<p class="p-heading">Contact us</p>
			<a  id="collapse_three"  >Collapse</a>
		</div>
			
					
			<div id="collapseExample3" style="display: block;">
				<div class="pricing_main no_border">
					<div class="custom_section">
	<div class="container pa0">
		<div class="main_customform">
			<div class="customform_inner">
				<div class="custom_forms">
					<h4>Did not find what you were looking for? Submit your question, and we will answer as soon as possible! </h4>
					@if(Session::has('msg'))
					<div id="custom_success" class="support_msg"><div class="download-message"><strong>Thank you contacting
our support team! </strong><hr><p>Your message has been received and<br> forwarded to the team. </p> </div></div>
					@endif
					
					@if(Session::has('errormsg'))
					<div id="custom_success" class=""><div class="download-message"><strong>Invalid Captcha!</strong><hr>
					</div></div>
					@endif
					 <form class="form-horizontal" id="contact-form" action="/contactus" method="post"  autocomplete="off">
						@csrf
					   <input type="hidden" name="userid" value="{{$userid}}">
					   <input type="hidden" name="siteid" value="{{$siteid}}">
					   <input type="hidden" name="useremail" value="@if(!empty($user)){{$user->vchemail}}@endif">
					   <input type="hidden" name="username" value="@if(!empty($user)){{$user->vchfirst_name}}@endif">
					   <div class="form-group">
						 
							<textarea class="form-control" id="exampleFormControlTextarea1" placeholder="Describe your issue here (2000 characters max)" rows="8" name="contactquery" maxlength="2000" required></textarea>
							  <!---<div id="the-count">
								<span id="current">0</span>
								<span id="maximum">/ 2000</span>
							</div>---->
							<div class="captcha-button">
									@if($managesite->intmanagesiteid == '1' )
							<div class="g-recaptcha" data-sitekey="6LflkxcaAAAAAMl1ol0RKdVnnJQ_bJxYqa7XkgUW"></div>
						@endif
						@if($managesite->intmanagesiteid == '17' )
							<div class="g-recaptcha" data-sitekey="6Lc5WyUaAAAAAGUdw0GGKWDdzzdPbFsuDgXYJxkj"></div>
						@endif
						@if($managesite->intmanagesiteid == '22' )
							<div class="g-recaptcha" data-sitekey="6Le8WyUaAAAAABqocCL7twGPdV3kJ5SIV9yNSXXp"></div>
						@endif
							<div class="bt_sec">    
							 <button type="submit" class="btn btn-default submit-btn btn-setting contact-submit">Submit</button>
						</div>
					</div>
				</div>
						
					</form>
				</div>
			
			</div>
			
		</div>				
	</div>

				</div>
			</div>
	
			
			
		</div>	
	</div>
</div>
</div>
</div>




<script src="js/creditly.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="js/jquery.checkout.js?v=1.3.1"></script>
<script>
setTimeout(function(){ 
          $("#custom_success").addClass("hide");
        }, 3000);
		
		
$('textarea').keyup(function() {
    
  var characterCount = $(this).val().length,
      current = $('#current'),
      maximum = $('#maximum'),
      theCount = $('#the-count');
    
  current.text(characterCount);
 
  
  /*This isn't entirely necessary, just playin around*/
  if (characterCount < 900) {
    current.css('color', '#666');
  }
  if (characterCount > 900 && characterCount < 1000) {
    current.css('color', '#6d5555');
  }
  if (characterCount > 1000 && characterCount < 1300) {
    current.css('color', '#793535');
  }
  if (characterCount > 1300 && characterCount < 1500) {
    current.css('color', '#841c1c');
  }
  if (characterCount > 1500 && characterCount < 1800) {
    current.css('color', '#8f0001');
  }
  
  if (characterCount >= 1800) {
    maximum.css('color', '#8f0001');
    current.css('color', '#8f0001');
    theCount.css('font-weight','bold');
  } else {
    maximum.css('color','#666');
    theCount.css('font-weight','normal');
  }
  
      
});

// $(document).ready(function(){
    // $("#toggle-btn").click(function(){
      // $("#toggle-example").collapse('toggle'); // toggle collapse
    // });
// });
$( "#collapse1" ).click(function() {
	if($('#toggle-example').css('display') == 'none')
{
	$("#collapse1").text("Collapse");
}else{
	
	$("#collapse1").text("Expand");
	
}

  $( "#toggle-example" ).slideToggle( "slow" );
});


// $('.bg_section').click(function() {
  // $('.bg_section').removeClass('after_click');
   // $(this).addClass('after_click');
// });
//$(document).ready(function(){
//	$(".custom-control-input").click(function () {
//     $('.bg_section').removeClass('after_click');
	 //$('.bg_section').addClass('after_click');
//	 if ($(this).is(":checked")) {
//    $(this).find('pricing_main').addClass('after_click');
 // }
//});
//});
$('.contact-submit').on('click', function() { 
			var uniqueid=$("#uniqueid").val();
			if(uniqueid == ""){
				$("#exampleModal").modal("show");
				//document.body.scrollTop = 0;
				//document.documentElement.scrollTop = 0;
				//abc('signin');
				//openForm3(formname3);
				return false
			}else{
				$("#contact-form").submit();
			}
        }); 
		

	
	
	  $('.custom-control-input').on('change', function() {
		    $('.custom-control-input').not(this).prop('checked', false);  
			
			//document.getElementById('btn-show').style.display = "block";
			
			// var i, tabcontent;
			// tabcontent = document.getElementsByClassName("custom-control-input");
			// for (i = 0; i < tabcontent.length; i++) {
				// document.getElementById('btn-show').style.display = "none";
			// }	
			
		});
		

$(".custom-control-input").click(function() {
var check_count=$(".custom-control-input:checked");
var plan_id=$(this).val();
if(check_count.length>0){
	 $('.bg_section').removeClass('after_click');
	$('.trans_'+plan_id).addClass('after_click');
	document.getElementById('btn-show').style.display = "block";
	}else{
		$('.bg_section').removeClass('after_click');
	document.getElementById('btn-show').style.display = "none";
	}
});
	
$("#collapse_one").click(function() {
	
if($("#collapseExample").hasClass("in")){
	$("#collapse_one").text("Collapse");
}else if($("#collapseExample").hasClass("show")){
	$("#collapse_one").text("Expand");
}
});

$("#collapse_one").click(function() {
	
    $header = $(this);
    //getting the next element
    $content = $header.next();
    //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
    $content.slideToggle(500, function () {
        //execute this after slideToggle is done
        //change text of header based on visibility of content div
        $header.text(function () {
            //change text based on condition
            return $content.is(":visible") ? "Collapse" : "Expand";
        });
    });
});

// $("#collapse_two").click(function() {
// if($("#collapseExample2").attr("aria-expanded")=='false'){
	// $("#collapse_two").text("Expand");
// }else if($("#collapseExample2").attr("aria-expanded")=='true'){
	// $("#collapse_two").text("Collapse");
// }else{
	// $("#collapse_two").text("Expand");
// }
// });



$( "#collapse_two" ).click(function() {
	if($('#collapseExample2').css('display') == 'none')
{
	$("#collapse_two").text("Collapse");
}else{
	
	$("#collapse_two").text("Expand");
	
}

  $( "#collapseExample2" ).slideToggle( "slow" );
});

$("#collapse_three").click(function() {
	
if($('#collapseExample3').css('display') == 'none')
{
	$("#collapse_three").text("Collapse");
}else{
	
	$("#collapse_three").text("Expand");
	
}
 $( "#collapseExample3" ).slideToggle( "slow" );

});

function trans(plan_id){
	var check_count=$(".custom-control-input:checked");
	if(check_count.length>0){
	 $('.bg_section').removeClass('after_click');
	$('.trans_'+plan_id).addClass('after_click');
	document.getElementById('btn-show').style.display = "block";
	}else{
		$('.bg_section').removeClass('after_click');
	document.getElementById('btn-show').style.display = "none";
	}
	
}
/* function openForm3(formname) {
  
  if(formname == 'signup'){
		$(".btn-refresh").click();
		$("#exampleModal").modal("hide");
		$("#signupModal").modal("show");
  		// document.getElementById('signinModal').style.display = "none";
  		// document.getElementById('signupModal').style.display = "block";
		//document.getElementsById('forgot_form').style.display = "none";
  }else if(formname == 'signin'){
	  $("#exampleModal").modal("show");
		$("#signupModal").modal("hide");
  		// document.getElementById('signinModal').style.display = "block";
  		// document.getElementById('signupModal').style.display = "none";
  		//document.getElementsById('forgot_form').style.display = "none";
		
  }else if(formname == 'forgot'){
  		document.getElementById('signinModal').style.display = "none";
  		document.getElementById('signupModal').style.display = "none";
  		document.getElementById('forgot_form').style.display = "block";
		
  }

} */
</script>
<script>
function openCity(evt, cityName) {
	$('.bg_section').removeClass('after_click');
	$('.price-checkbox input').prop('checked', false);
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" clicked", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " clicked";
}

</script>
@include('footer')