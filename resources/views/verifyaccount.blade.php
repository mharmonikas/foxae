@include('header')
<style>
.error-repot {
    border-bottom: 1px solid red !important;
}
h1.heading_main {
    padding: 1% 10%;
	    text-align: center;
}
.container-fluid.fluid-row {
    min-height: 88vh;
    height: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2%;
}
</style>


<div class="container-fluid fluid-row account-verified">
<div class="thank-container acc-verify" >
<h1 class="heading_main">Account Verification Successful!</h1>
		<div class="success-img"> 
		<svg width="369" height="369" viewBox="0 0 369 369" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M184.5 30.75C99.63 30.75 30.75 99.63 30.75 184.5C30.75 269.37 99.63 338.25 184.5 338.25C269.37 338.25 338.25 269.37 338.25 184.5C338.25 99.63 269.37 30.75 184.5 30.75ZM260.606 157.286L173.584 244.309C167.587 250.305 157.901 250.305 151.905 244.309L108.394 200.798C102.398 194.801 102.398 185.115 108.394 179.119C114.39 173.122 124.076 173.122 130.073 179.119L162.667 211.714L238.774 135.607C244.77 129.611 254.456 129.611 260.453 135.607C266.603 141.604 266.603 151.29 260.606 157.286Z" fill="#FF8F09"/>
</svg>
			<p class="thank-txt">Thank you for verifying your account we hope you enjoy your stay!</p>
			<p>You will now be automatically redirected to the <a href="/">home page</a>.</p>
		
		  </div>
		  
</div>		
</div>
<script>
setTimeout(function(){ window.location = '/'; }, 2000);
</script>
@include('footer')