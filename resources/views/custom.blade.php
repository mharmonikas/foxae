@include('header')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
	<script>
	$(document).ready(function(){
		//alert(localStorage.sharelink);
    if(localStorage.sharelink!=undefined){
	$("#exampleFormControlTextarea1").html("Hey! I would like to request a quote for a graphic that is similar to this: "+localStorage.sharelink +  "\n\r(If you think it's necessary, please provide more information)");
	}
	
});



	$(function() {
		
		setTimeout(function() {
		
			$('#msg').fadeOut('fast');
		}, 3000); 
	});	
	
	$(function() {
		
		setTimeout(function() {
		localStorage.removeItem("sharelink");
		
		}, 10000); 
	});
	</script>
<style>
label {
    color: #fff;
    font-size: 19px;
    margin: 0 55px;
}
h4 {
    text-align: left;
    margin: 0px 0px 57px 54px;
    color: #fff;
}
.submit-btn{
	font-size: 18px;
	color: #fff;
    background-color: #ff8f08 ;
}
.form-control {
    border: 1px solid #a5a3a3 !important;
    background-color: #a5a3a3 !important;
    padding: 20px 10px;
    margin-left: 33px;
}
.custom_forms {
    padding: 40px 40px 40px 20%;
}
.custom_forms .col-sm-10 {
    max-width: 54.333333%;
}
.col-sm-10.bt_sec {
    margin-left: 33px;
}


</style>
<div class="custom_section">
	<div class="container">
		<div class="main_customform vidrev">
			<div class="customform_inner">
				
				<div class="customform_service hide-on-desktop">
					<ul>
						<li>
							<a class="svg-setting">
<svg width="51" height="50" viewBox="0 0 51 50" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M40.375 6.25H10.625C8.2875 6.25 6.39625 8.125 6.39625 10.4167L6.375 39.5833C6.375 41.875 8.2875 43.75 10.625 43.75H40.375C42.7125 43.75 44.625 41.875 44.625 39.5833V10.4167C44.625 8.125 42.7125 6.25 40.375 6.25ZM36.125 29.1667H29.75V35.4167C29.75 36.5625 28.7938 37.5 27.625 37.5H23.375C22.2063 37.5 21.25 36.5625 21.25 35.4167V29.1667H14.875C13.7063 29.1667 12.75 28.2292 12.75 27.0833V22.9167C12.75 21.7708 13.7063 20.8333 14.875 20.8333H21.25V14.5833C21.25 13.4375 22.2063 12.5 23.375 12.5H27.625C28.7938 12.5 29.75 13.4375 29.75 14.5833V20.8333H36.125C37.2938 20.8333 38.25 21.7708 38.25 22.9167V27.0833C38.25 28.2292 37.2938 29.1667 36.125 29.1667Z" fill="#5B5C5C"/>
</svg>

							<p>Medical Illustrations and Animations</p></a>
						</li>
						<li>
							<a class="svg-setting"><svg width="50" height="51" viewBox="0 0 50 51" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M39.4167 13.0785C39 11.8563 37.8333 10.9862 36.4583 10.9862H13.5417C12.1667 10.9862 11.0208 11.8563 10.5833 13.0785L6.47917 24.8246C6.33333 25.2596 6.25 25.7153 6.25 26.1918V41.0245C6.25 42.744 7.64583 44.1319 9.375 44.1319C11.1042 44.1319 12.5 42.744 12.5 41.0245V39.9887H37.5V41.0245C37.5 42.7233 38.8958 44.1319 40.625 44.1319C42.3333 44.1319 43.75 42.744 43.75 41.0245V26.1918C43.75 25.7361 43.6667 25.2596 43.5208 24.8246L39.4167 13.0785ZM13.5417 33.7739C11.8125 33.7739 10.4167 32.3859 10.4167 30.6665C10.4167 28.9471 11.8125 27.5591 13.5417 27.5591C15.2708 27.5591 16.6667 28.9471 16.6667 30.6665C16.6667 32.3859 15.2708 33.7739 13.5417 33.7739ZM36.4583 33.7739C34.7292 33.7739 33.3333 32.3859 33.3333 30.6665C33.3333 28.9471 34.7292 27.5591 36.4583 27.5591C38.1875 27.5591 39.5833 28.9471 39.5833 30.6665C39.5833 32.3859 38.1875 33.7739 36.4583 33.7739ZM10.4167 23.4159L13.0625 15.5023C13.3542 14.6737 14.1458 14.0936 15.0417 14.0936H34.9583C35.8542 14.0936 36.6458 14.6737 36.9375 15.5023L39.5833 23.4159H10.4167Z" fill="#5B5C5C"/>
</svg>
							<p>Vehicular Illustrations and Animations</p></a>
						</li>
						<li>
							<a class="svg-setting"><svg width="50" height="49" viewBox="0 0 50 49" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M33.5417 14.217C35.8428 14.217 37.7083 12.3984 37.7083 10.155C37.7083 7.91163 35.8428 6.09302 33.5417 6.09302C31.2405 6.09302 29.375 7.91163 29.375 10.155C29.375 12.3984 31.2405 14.217 33.5417 14.217Z" fill="#5B5C5C"/>
<path d="M33.9584 19.7005C34.1667 19.4974 34.1667 19.4974 34.3751 19.4974C34.5834 19.4974 37.5001 20.1067 37.5001 20.1067C37.5001 20.1067 37.7084 20.1067 37.7084 20.3098L42.2917 26.4027C42.7084 27.012 43.3334 27.2151 43.9584 27.2151C44.3751 27.2151 44.7917 27.012 45.2084 26.8089C46.0417 26.1996 46.2501 24.981 45.6251 23.9655L40.6251 17.0602C40.2084 16.8571 39.5834 16.4509 38.9584 16.2478L32.9167 15.0292L28.1251 13.4044C27.9167 13.4044 27.9167 13.4044 27.7084 13.2013L21.2501 12.1858H21.0417L15.2084 7.51455C14.3751 6.90525 12.9167 7.10835 12.2917 7.92075C11.6667 8.73314 11.6667 10.1548 12.7084 10.7641L18.9584 15.6385C19.1667 15.8416 19.5834 16.0447 20.0001 16.0447L24.1667 16.8571C24.5834 16.8571 24.5834 17.2633 24.3751 17.4664L20.4167 21.1222C20.2084 21.3253 20.2084 21.3253 20.0001 21.1222L16.2501 19.9036C15.4167 19.7005 14.5834 19.7005 13.7501 20.3098L6.87506 26.1996C6.04173 27.012 5.8334 28.2306 6.66673 29.043C7.50006 29.8554 8.75006 30.0585 9.5834 29.2461L15.6251 24.1686C15.8334 24.1686 15.8334 23.9655 16.0417 24.1686L20.6251 25.9965C20.8334 26.1996 21.0417 26.6058 20.6251 26.6058L16.4584 29.4492C15.8334 29.8554 15.4167 30.4647 15.4167 31.2771L14.7917 40.6196C14.7917 41.8382 15.6251 42.6506 16.6667 42.8537C16.6667 42.8537 16.6667 42.8537 16.8751 42.8537C17.9167 42.8537 18.9584 42.0413 18.9584 41.0258L19.5834 32.4957C19.5834 32.2926 19.5834 32.2926 19.7917 32.2926C20.8334 31.6833 26.6667 27.8244 27.0834 27.4182" fill="#5B5C5C"/></svg>
							<p>Injury Illustrations and Animations</p></a>
						</li>
						<li>
							<a class="svg-setting"><svg width="51" height="50" viewBox="0 0 51 50" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M41.2885 27.0416C41.3735 26.375 41.4373 25.7083 41.4373 25C41.4373 24.2916 41.3735 23.625 41.2885 22.9583L45.7723 19.5208C46.176 19.2083 46.2823 18.6458 46.0273 18.1875L41.7773 10.9791C41.5223 10.5208 40.9485 10.3541 40.481 10.5208L35.1898 12.6041C34.0848 11.7708 32.8948 11.0833 31.5985 10.5625L30.791 5.04163C30.7273 4.54163 30.281 4.16663 29.7498 4.16663H21.2498C20.7185 4.16663 20.2723 4.54163 20.2085 5.04163L19.401 10.5625C18.1048 11.0833 16.9148 11.7916 15.8098 12.6041L10.5185 10.5208C10.0298 10.3333 9.47728 10.5208 9.22228 10.9791L4.97228 18.1875C4.69603 18.6458 4.82353 19.2083 5.22728 19.5208L9.71103 22.9583C9.62603 23.625 9.56228 24.3125 9.56228 25C9.56228 25.6875 9.62603 26.375 9.71103 27.0416L5.22728 30.4791C4.82353 30.7916 4.71728 31.3541 4.97228 31.8125L9.22228 39.0208C9.47728 39.4791 10.051 39.6458 10.5185 39.4791L15.8098 37.3958C16.9148 38.2291 18.1048 38.9166 19.401 39.4375L20.2085 44.9583C20.2723 45.4583 20.7185 45.8333 21.2498 45.8333H29.7498C30.281 45.8333 30.7273 45.4583 30.791 44.9583L31.5985 39.4375C32.8948 38.9166 34.0848 38.2083 35.1898 37.3958L40.481 39.4791C40.9698 39.6666 41.5223 39.4791 41.7773 39.0208L46.0273 31.8125C46.2823 31.3541 46.176 30.7916 45.7723 30.4791L41.2885 27.0416ZM25.4998 32.2916C21.3985 32.2916 18.0623 29.0208 18.0623 25C18.0623 20.9791 21.3985 17.7083 25.4998 17.7083C29.601 17.7083 32.9373 20.9791 32.9373 25C32.9373 29.0208 29.601 32.2916 25.4998 32.2916Z" fill="#5B5C5C"/>
</svg>
							<p>Specialized Illustrations and Animations</p></a>
						</li>
					</ul>
				</div>
				
				<div class="customform_contect hide-on-desktop">
					<div>Accurately show medical procedures and surgeries with our <br>state of the art animations and images, custom-made for you!</div>
					<div>Reconstruct vehicular accidents and traffic violation using police <br>reports, satellite images and engineering calculations to precisely <br>demostrate the severity of the incident!</div>
					<div>With the help of our fantastic human anatomy models, demonstrate <br>clearly and accurately the severity of sustained injuries</div>
					<div>Need someting completely different? No problem! With enough <br>information, we will recreate almost anything!</div>
				</div>
				
				<div class="custom_forms">
					<h4>Like what you see? Request a quote from our industry leading <br> graphics designers!</h4>
					@if(Session::has('msg'))
					<div id="custom_success" class=""><div class="download-message"><strong>Thank you for your request!</strong><hr><p>The request has been submitted, and you’ll <br>be contacted as soon as possible!</p> </div></div>
					@endif
					@if(Session::has('errormsg'))
					<div id="custom_success" class=""><div class="download-message"><strong>Invalid Captcha!</strong><hr>
					</div></div>
					@endif
					 <form class="form-horizontal" action="/submitcustom" method="post" id="password-changed" autocomplete="off">
						@csrf
					   <input type="hidden" name="vchsiteid" value="{{ $managesite->intmanagesiteid }}"> 
					   <input type="hidden" name="vchfrom" value="{{ $managesite->vchemailfrom }}"> 
					   <div class="form-group">
						  <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required >
							<input type="text" class="form-control" id="Phone" placeholder="Phone Number" pattern="[1-9]{1}[0-9]{9}" title="Enter 10 digit mobile number" name="phone" required >
							<textarea class="form-control" id="exampleFormControlTextarea1" placeholder="Brief Description (1000 characters max)" rows="3" name="description" required></textarea>
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
							 <button type="submit" class="btn btn-default submit-btn btn-setting custom-btn3" onClick="validatePassword();">Request a quote</button>
						</div>
						</div>
						</div>
						
					</form>
				</div>
				<div class="customform_contect hide-on-mobile">
					<div>Accurately show medical procedures and surgeries with our <br>state of the art animations and images, custom-made for you!</div>
					<div>Reconstruct vehicular accidents and traffic violation using police <br>reports, satellite images and engineering calculations to precisely <br>demostrate the severity of the incident!</div>
					<div>With the help of our fantastic human anatomy models, demonstrate <br>clearly and accurately the severity of sustained injuries</div>
					<div>Need someting completely different? No problem! With enough <br>information, we will recreate almost anything!</div>
				</div>
			</div>
			<div class="customform_video">
				<div class="customform_service hide-on-mobile">
					<ul>
						<li>
							<a class="svg-setting">
							<svg width="51" height="50" viewBox="0 0 51 50" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M40.375 6.25H10.625C8.2875 6.25 6.39625 8.125 6.39625 10.4167L6.375 39.5833C6.375 41.875 8.2875 43.75 10.625 43.75H40.375C42.7125 43.75 44.625 41.875 44.625 39.5833V10.4167C44.625 8.125 42.7125 6.25 40.375 6.25ZM36.125 29.1667H29.75V35.4167C29.75 36.5625 28.7938 37.5 27.625 37.5H23.375C22.2063 37.5 21.25 36.5625 21.25 35.4167V29.1667H14.875C13.7063 29.1667 12.75 28.2292 12.75 27.0833V22.9167C12.75 21.7708 13.7063 20.8333 14.875 20.8333H21.25V14.5833C21.25 13.4375 22.2063 12.5 23.375 12.5H27.625C28.7938 12.5 29.75 13.4375 29.75 14.5833V20.8333H36.125C37.2938 20.8333 38.25 21.7708 38.25 22.9167V27.0833C38.25 28.2292 37.2938 29.1667 36.125 29.1667Z" fill="#5B5C5C"/>
</svg>

							<p>Medical Illustrations and Animations</p></a>
						</li>
						<li>
							<a class="svg-setting"><svg width="50" height="51" viewBox="0 0 50 51" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M39.4167 13.0785C39 11.8563 37.8333 10.9862 36.4583 10.9862H13.5417C12.1667 10.9862 11.0208 11.8563 10.5833 13.0785L6.47917 24.8246C6.33333 25.2596 6.25 25.7153 6.25 26.1918V41.0245C6.25 42.744 7.64583 44.1319 9.375 44.1319C11.1042 44.1319 12.5 42.744 12.5 41.0245V39.9887H37.5V41.0245C37.5 42.7233 38.8958 44.1319 40.625 44.1319C42.3333 44.1319 43.75 42.744 43.75 41.0245V26.1918C43.75 25.7361 43.6667 25.2596 43.5208 24.8246L39.4167 13.0785ZM13.5417 33.7739C11.8125 33.7739 10.4167 32.3859 10.4167 30.6665C10.4167 28.9471 11.8125 27.5591 13.5417 27.5591C15.2708 27.5591 16.6667 28.9471 16.6667 30.6665C16.6667 32.3859 15.2708 33.7739 13.5417 33.7739ZM36.4583 33.7739C34.7292 33.7739 33.3333 32.3859 33.3333 30.6665C33.3333 28.9471 34.7292 27.5591 36.4583 27.5591C38.1875 27.5591 39.5833 28.9471 39.5833 30.6665C39.5833 32.3859 38.1875 33.7739 36.4583 33.7739ZM10.4167 23.4159L13.0625 15.5023C13.3542 14.6737 14.1458 14.0936 15.0417 14.0936H34.9583C35.8542 14.0936 36.6458 14.6737 36.9375 15.5023L39.5833 23.4159H10.4167Z" fill="#5B5C5C"/>
</svg>
							<p>Vehicular Illustrations and Animations</p></a>
						</li>
						<li>
							<a class="svg-setting"><svg width="50" height="49" viewBox="0 0 50 49" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M33.5417 14.217C35.8428 14.217 37.7083 12.3984 37.7083 10.155C37.7083 7.91163 35.8428 6.09302 33.5417 6.09302C31.2405 6.09302 29.375 7.91163 29.375 10.155C29.375 12.3984 31.2405 14.217 33.5417 14.217Z" fill="#5B5C5C"/>
<path d="M33.9584 19.7005C34.1667 19.4974 34.1667 19.4974 34.3751 19.4974C34.5834 19.4974 37.5001 20.1067 37.5001 20.1067C37.5001 20.1067 37.7084 20.1067 37.7084 20.3098L42.2917 26.4027C42.7084 27.012 43.3334 27.2151 43.9584 27.2151C44.3751 27.2151 44.7917 27.012 45.2084 26.8089C46.0417 26.1996 46.2501 24.981 45.6251 23.9655L40.6251 17.0602C40.2084 16.8571 39.5834 16.4509 38.9584 16.2478L32.9167 15.0292L28.1251 13.4044C27.9167 13.4044 27.9167 13.4044 27.7084 13.2013L21.2501 12.1858H21.0417L15.2084 7.51455C14.3751 6.90525 12.9167 7.10835 12.2917 7.92075C11.6667 8.73314 11.6667 10.1548 12.7084 10.7641L18.9584 15.6385C19.1667 15.8416 19.5834 16.0447 20.0001 16.0447L24.1667 16.8571C24.5834 16.8571 24.5834 17.2633 24.3751 17.4664L20.4167 21.1222C20.2084 21.3253 20.2084 21.3253 20.0001 21.1222L16.2501 19.9036C15.4167 19.7005 14.5834 19.7005 13.7501 20.3098L6.87506 26.1996C6.04173 27.012 5.8334 28.2306 6.66673 29.043C7.50006 29.8554 8.75006 30.0585 9.5834 29.2461L15.6251 24.1686C15.8334 24.1686 15.8334 23.9655 16.0417 24.1686L20.6251 25.9965C20.8334 26.1996 21.0417 26.6058 20.6251 26.6058L16.4584 29.4492C15.8334 29.8554 15.4167 30.4647 15.4167 31.2771L14.7917 40.6196C14.7917 41.8382 15.6251 42.6506 16.6667 42.8537C16.6667 42.8537 16.6667 42.8537 16.8751 42.8537C17.9167 42.8537 18.9584 42.0413 18.9584 41.0258L19.5834 32.4957C19.5834 32.2926 19.5834 32.2926 19.7917 32.2926C20.8334 31.6833 26.6667 27.8244 27.0834 27.4182" fill="#5B5C5C"/></svg>
							<p>Injury Illustrations and Animations</p></a>
						</li>
						<li>
							<a class="svg-setting"><svg width="51" height="50" viewBox="0 0 51 50" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M41.2885 27.0416C41.3735 26.375 41.4373 25.7083 41.4373 25C41.4373 24.2916 41.3735 23.625 41.2885 22.9583L45.7723 19.5208C46.176 19.2083 46.2823 18.6458 46.0273 18.1875L41.7773 10.9791C41.5223 10.5208 40.9485 10.3541 40.481 10.5208L35.1898 12.6041C34.0848 11.7708 32.8948 11.0833 31.5985 10.5625L30.791 5.04163C30.7273 4.54163 30.281 4.16663 29.7498 4.16663H21.2498C20.7185 4.16663 20.2723 4.54163 20.2085 5.04163L19.401 10.5625C18.1048 11.0833 16.9148 11.7916 15.8098 12.6041L10.5185 10.5208C10.0298 10.3333 9.47728 10.5208 9.22228 10.9791L4.97228 18.1875C4.69603 18.6458 4.82353 19.2083 5.22728 19.5208L9.71103 22.9583C9.62603 23.625 9.56228 24.3125 9.56228 25C9.56228 25.6875 9.62603 26.375 9.71103 27.0416L5.22728 30.4791C4.82353 30.7916 4.71728 31.3541 4.97228 31.8125L9.22228 39.0208C9.47728 39.4791 10.051 39.6458 10.5185 39.4791L15.8098 37.3958C16.9148 38.2291 18.1048 38.9166 19.401 39.4375L20.2085 44.9583C20.2723 45.4583 20.7185 45.8333 21.2498 45.8333H29.7498C30.281 45.8333 30.7273 45.4583 30.791 44.9583L31.5985 39.4375C32.8948 38.9166 34.0848 38.2083 35.1898 37.3958L40.481 39.4791C40.9698 39.6666 41.5223 39.4791 41.7773 39.0208L46.0273 31.8125C46.2823 31.3541 46.176 30.7916 45.7723 30.4791L41.2885 27.0416ZM25.4998 32.2916C21.3985 32.2916 18.0623 29.0208 18.0623 25C18.0623 20.9791 21.3985 17.7083 25.4998 17.7083C29.601 17.7083 32.9373 20.9791 32.9373 25C32.9373 29.0208 29.601 32.2916 25.4998 32.2916Z" fill="#5B5C5C"/>
</svg>
							<p>Specialized Illustrations and Animations</p></a>
						</li>
					</ul>
				</div>
				<div class="customf_video">
					<h4>Custom Animations Demonstration</h4>
					<div class="video-sec">
					<video width="100%" height="100%" controls autoplay>
					  <source src="images/{{$tblthemesetting->cutomvideo}}" type="video/mp4">
					  <source src="images/{{$tblthemesetting->cutomvideo}}" type="video/ogg">
					</video>
					</div>
				</div>
			</div>
		</div>				
	</div>
</div>
</body>
</html>
<style>
.panel-heading.note-toolbar {
    display: none;
}
.note-editor.note-airframe .note-statusbar .note-resizebar, .note-editor.note-frame .note-statusbar .note-resizebar {
   
    display: none !important;
    
}
.note-editable {
    background-color: #f7f7f7 !important;
   
}
.note-editor.note-airframe .note-editing-area .note-editable, .note-editor.note-frame .note-editing-area .note-editable {
    box-shadow: 0px 4px 4px rgb(0 0 0 / 25%);
  
    margin-bottom: 19px;
}
</style>
<script>
	//localStorage.clear();
	$(document).ready(function() {
  $('.summernote').summernote();
});
    setTimeout(function(){ 
          $("#custom_success").addClass("hide");
        }, 3000);
  
</script>
@include('footer')