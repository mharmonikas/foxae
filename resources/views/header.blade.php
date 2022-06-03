<?php
use App\Http\Controllers\HomeController;
$managesite = HomeController::managesite();
$tblthemesetting = HomeController::tblthemesetting();
$userdetail = HomeController::userinfo();
$cartcount = HomeController::cartcount();
$availablecreditcount = HomeController::availablecreditcount();
$incartcredit = HomeController::incartcredit();
?>
@if($managesite->status != 'L' )
<!doctype html><html lang="{{str_replace('_', '-', app()->getLocale())}}"> <head> <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1"> <title></title> <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css"> <style>html, body{background-color: #fff; color: #636b6f; font-family: 'Nunito', sans-serif; font-weight: 200; height: 100vh; margin: 0;}.full-height{height: 100vh;}.flex-center{align-items: center; display: flex; justify-content: center;}.position-ref{position: relative;}.top-right{position: absolute; right: 10px; top: 18px;}.content{text-align: center;}.title{font-size: 84px;}.links > a{color: #636b6f; padding: 0 25px; font-size: 13px; font-weight: 600; letter-spacing: .1rem; text-decoration: none; text-transform: uppercase;}.m-b-md{margin-bottom: 30px;}</style> </head> <body> <div class="flex-center position-ref full-height"> <div class="content"> <div class="title m-b-md"> Site is unavailable </div></div></div></body></html>

@php
exit();
@endphp
@endif

@if(!data_get($managesite, 'intmanagesiteid'))
<!doctype html><html lang="{{str_replace('_', '-', app()->getLocale())}}"> <head> <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1"> <title></title> <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css"> <style>html, body{background-color: #fff; color: #636b6f; font-family: 'Nunito', sans-serif; font-weight: 200; height: 100vh; margin: 0;}.full-height{height: 100vh;}.flex-center{align-items: center; display: flex; justify-content: center;}.position-ref{position: relative;}.top-right{position: absolute; right: 10px; top: 18px;}.content{text-align: center;}.title{font-size: 84px;}.links > a{color: #636b6f; padding: 0 25px; font-size: 13px; font-weight: 600; letter-spacing: .1rem; text-decoration: none; text-transform: uppercase;}.m-b-md{margin-bottom: 30px;}</style> </head> <body> <div class="flex-center position-ref full-height"> <div class="content"> <div class="title m-b-md"> Error 404 - Page not found</div></div></div></body></html>
@php
exit();
@endphp

@endif

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<meta charset="utf-8">
		<title>{{$managesite->vchmetatitle}}</title>
		<meta name="description" content="{{$managesite->vchdescription}}">
		<meta name="keywords" content="{{$managesite->vchkeywords}}">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<link rel="stylesheet" href="/css/theme{{$managesite->intmanagesiteid}}.css?v=<?php echo(rand(1,10000000)); ?>">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="/css/app.css?v=<?php echo(rand(1,10000000)); ?>">
		<link rel="stylesheet" href="{{ asset('/css/fontendcustomise.css') }}?v=<?php echo(rand(1,10000000)); ?>">
        <link rel="stylesheet" href="{{ asset('/css/style-front.css') }}?v=<?php echo(rand(1,10000000)); ?>">
		<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
		<script src="/js/app.js"></script>
		<script src="/js/angular.js"></script>
		<script src="/js/main.js?v=1"></script>
		<script src="/js/bootstrapui.js"></script>
		<script src="/js/pager.js?v=<?php echo(rand(1,10000000)); ?>"></script>
</head>
<style>
.close-loginform{
	display:none;
}
.show-logininfo{
	display:block;
}
.verfiy-email {
    background: #e27d06;
    color: #fff;
    width: 100%;
    text-align: center;
    padding: 5px 0;
    z-index: 99999999999;
}
.verfiy-email a.resend {
    color: #2196f3;
    font-weight: 900;
    text-decoration: underline;
}
.error_msg {
    color: red;
    font-size: 17px;
    padding: 10px 5px;
}
.error_msg_2 {
    color: red;
    font-size: 15px;
    padding: 0;
    margin-top: -20px;
}
.showPassword{
	right: 0;
    position: absolute;
    margin-top: -34px;
    margin-right: 20px;
    background: #fff;
    border: none;
    cursor: pointer;
	font-size: 20px;
}
.showPassword_reg{
	right: 0;
    position: absolute;
    margin-top: -58px;
    margin-right: 4.2%;
    background: #fff;
    border: none;
    cursor: pointer;
	font-size: 20px;
}
.loginshowXpassword, .showXpassword{
	right: 0;
    position: absolute;
    margin-top: -64px;
    margin-right: 37%;
    background: #fff;
    border: none;
    cursor: pointer;
	font-size: 20px;
}

.homepage-popup{
	right: 5%;
    top: 83%;
	z-index: 99999;
}

</style>
<script>
	<?php
	    if(empty($userdetail)){
	?>
		var stylestatus ="yes";
	<?php
	}else{
	?>
	    var stylestatus ="no";
	<?php
	    }
	?>
</script>
<body class="homepage" ng-app="myApp" ng-controller="customersCtrl">
    <input type="hidden" id="uniqueid" @if(!empty(Session::get('userid'))) value="{{Session::get('userid')}}" @endif >

    <div class="verfiy-email" @if(!empty($userdetail))@if($userdetail->verifystatus == 0) @else style="display:none;" @endif @else style="display:none;" @endif>A verification email has just been sent to your email address. Please follow the instructions in the email and verify your account.</br> If you didn't receive an email, click here to <a href="" class="resend"> re-send it.</a>  </div>

 	<div class="main-container top-view">
<section class="main">
<div class="container">
<nav class="navbar navbar-expand-md navbar-dark">
  <!-- Brand -->
  <a class="navbar-brand" href="/"><img src="{{ asset('images/') }}/{{$tblthemesetting->Vchthemelogo}}" alt="logo"></a>

  <!-- Toggler/collapsibe Button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav hide-on-mobile">
	<li class="nav-item">
		<ul class="sub-nav">
		  <li class="nav-item">
			<a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="/">Home</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link {{ Request::is('custom') ? 'active' : '' }}" href="/custom">Custom</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link {{ Request::is('about') ? 'active' : '' }}" href="/about">About</a>
		  </li>
		   <li class="nav-item">
			<a class="nav-link {{ Request::is('support') ? 'active' : '' }}" href="/support">Support</a>
		  </li>
		</ul>
	</li>
	  <li class="nav-item">
		<ul class="sub-nav">
		  <li class="nav-item">
			<a class="nav-link {{ Request::is('pricing') ? 'active' : '' }}" href="/pricing">Pricing</a>
		  </li>

		  <li class="nav-item cart">
			<a class="nav-link {{ Request::is('cart') ? 'active' : 'svg-icon' }}" href="/cart"  id="cartcount">
			<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 3c0 .55.45 1 1 1h1l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h11c.55 0 1-.45 1-1s-.45-1-1-1H7l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.67-1.43c-.16-.35-.52-.57-.9-.57H2c-.55 0-1 .45-1 1zm16 15c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path></svg><span>{{$cartcount}}</span>


			<!--<img src="{{ asset('images/') }}/{{ Request::is('cart') ? $tblthemesetting->activecartlogo : $tblthemesetting->carticon }}"><span>{{$cartcount}}</span>-->

			</a>
		  </li>



		 @if(empty($userdetail))

		  <li class="nav-item @if(!empty($userdetail))close-loginform @else show-loginform @endif" id="log-in">
			<a type="button" class="btn btn-primary nav-link" id='login-btn' data-toggle="modal" data-target="#exampleModal">Log in</a>
		  </li>
			@endif
			<!--<li class="nav-item">
			<a class="nav-link" href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i></span></a>
		  </li>-->

		  <li class="nav-item dropdown login-in @if(empty($userdetail))close-loginform @else show-loginform @endif"  id="user-info">
			<a class="nav-link login-details dropdown-toggle" href="#"  role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ><span class="username-info">Welcome, <br> {{ucfirst(@$userdetail->vchfirst_name)}}</span> <img class="user-logo" src="{{ asset('images/') }}/{{$tblthemesetting->vchprofileicon}}"></a>

			<div class="dropdown-menu" aria-labelledby="navbarDropdown" style="margin-left: 55%;">
			  <a class="dropdown-item" href="/myprofile">My Profile</a>
			  <a class="dropdown-item" href="/member-download">Downloads</a>
			  <a class="dropdown-item" href="/member-plans">Active Plans</a>
			  <a class="dropdown-item" href="/purchase-history">Purchase history</a>
			  <a class="dropdown-item" href="/favorites">My favorites</a>
				<a class="dropdown-item" href="/change-password">Change Password</a>
			  <div class="dropdown-divider"></div>
			  <a class="dropdown-item" href="/logout">Logout</a>
			</div>
		 </li>
	 </ul>
	</li>


    </ul>



	<!------------------------------------mobile-menu start------------------------------------------->
	<ul class="navbar-nav hide-on-desktop">
	<li class="nav-item">
		<ul class="sub-nav">
		@if(empty($userdetail))

		  <li class="nav-item @if(!empty($userdetail))close-loginform @else show-loginform @endif" id="log-in">
			<a type="button" class="btn btn-primary nav-link" id='login-btn' data-toggle="modal" data-target="#exampleModal">Log in</a>
		  </li>
			@endif
			<!--<li class="nav-item">
			<a class="nav-link" href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i></span></a>
		  </li>-->

		  <li class="nav-item dropdown login-in @if(empty($userdetail))close-loginform @else show-loginform @endif"  id="user-info">
			<a class="nav-link login-details dropdown-toggle" href="#"  role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ><span class="username-info">Welcome, <br> {{ucfirst(@$userdetail->vchfirst_name)}}</span> <img class="user-logo" src="{{ asset('images/') }}/{{$tblthemesetting->vchprofileicon}}"></a>

			<div class="dropdown-menu" aria-labelledby="navbarDropdown" style="margin-left: 55%;">
			  <a class="dropdown-item" href="/myprofile">My Profile</a>
			  <a class="dropdown-item" href="/member-download">Downloads</a>
			  <a class="dropdown-item" href="/member-plans">Active Plans</a>
			  <a class="dropdown-item" href="/purchase-history">Purchase history</a>
			  <a class="dropdown-item" href="/favorites">My favorites</a>
				<a class="dropdown-item" href="/change-password">Change Password</a>
			  <div class="dropdown-divider"></div>
			  <a class="dropdown-item" href="/logout">Logout</a>
			</div>
		 </li>
		  <li class="nav-item">
			<a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="/">Home</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link {{ Request::is('custom') ? 'active' : '' }}" href="/custom">Custom</a>
		  </li>
		   <li class="nav-item">
			<a class="nav-link {{ Request::is('support') ? 'active' : '' }}" href="/support">Support</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link {{ Request::is('pricing') ? 'active' : '' }}" href="/pricing">Pricing</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link {{ Request::is('about') ? 'active' : '' }}" href="/about">About</a>
		  </li>
		</ul>
	</li>
	  <li class="nav-item">
		<ul class="sub-nav">

		  <li class="nav-item cart">
			<a class="nav-link {{ Request::is('cart') ? 'active' : 'svg-icon' }}" href="/cart"  id="cartcount">
			<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 3c0 .55.45 1 1 1h1l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h11c.55 0 1-.45 1-1s-.45-1-1-1H7l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.67-1.43c-.16-.35-.52-.57-.9-.57H2c-.55 0-1 .45-1 1zm16 15c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path></svg><span>{{$cartcount}}</span>


			<!--<img src="{{ asset('images/') }}/{{ Request::is('cart') ? $tblthemesetting->activecartlogo : $tblthemesetting->carticon }}"><span>{{$cartcount}}</span>-->

			</a>
		  </li>




	 </ul>
	</li>


    </ul>
	<!------------------------------------mobile-menu start------------------------------------------->



  </div>
</nav>
</div>
<!--------------------------------REGISTER,LOGIN,FORGOT FORM-------------------------------->
<!--@if(empty($userdetail))
<div class="form-popup" id="myForm">
<div class="main-popup">

	<div class="login_form " style="display: none;">
		<form class="form-container form-bg"  autocomplete="off">


			@csrf

			<h3>Log in
				<span class="close_icon" onclick="closeForm()">&#10005;</span>
			</h3>

			<div class="form-group">
				<input type="email" class="form-control" id="loginemail"  placeholder="Email or username" name="email" requried>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" id="loginpassword" placeholder="Password" name="password" requried>
					</div>
					<button type="submit" class="btn btn-primary org btn-setting">Log in</button>
					<a class="nav-link" href="#" onclick="openForm('forgot')">Forgot your password?
					</span>
				</a>
				<div class="botm">
					<p>Don't have free account yet?</p>
					<button type="button" class="btn btn-primary trans" onclick="openForm('signup')">Create your account</button>
				</div>
			</form>
		</div>
		<div class="register_form">
			<form class="form-container form-bg" id="registrationForm" autocomplete="off">
				@csrf

				<h3>Sign up
					<span class="close_icon" onclick="closeForm()">&#10005;</span>
				</h3>
				<div class="form-group">
					<input type="text" class="form-control" id="signupname"  placeholder="Full Name" name="first_name" required>
					</div>

						<div class="form-group">
							<input type="email" pattern="[a-zA-Z0-9.!#$%&amp;’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)+" title="valid@email.com"
								placeholder="Email" class="form-control" id="signupemail" name="email"  required autocomplete="off">
							</div>
							<div class="form-group">
								<input type="password" class="form-control" id="signuppassword" placeholder="Password" name="password" required>
								<span onclick="XPassword()" class="showXpassword"><i class="fa fa-eye" aria-hidden="true"></i></span>
								</div>
							<div class="form-group">
								<input type="password" class="form-control" id="signupconfirmpassword" placeholder="Confirm Password" name="password" required>
								<div class="error_msg_2" style="display:none;">Passwords do not match</div>
							</div>
					<div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
						<div class="captcha">
                          <span>{!! captcha_img() !!}</span>
                          <button type="button" class="btn btn-link btn-refresh"><i class="fa fa-refresh"></i></button>
                          </div>
						 <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha">
						</div>
								<button type="submit" class="btn btn-primary org btn-setting">Sign Up</button>
								<div class="botm">
									<p>Already have an account?</p>
									<button type="button" class="btn btn-primary trans" onclick="openForm('signin')">Log in</button>
								</div>
							</form>
						</div>
						<div class="forgot_form">
							<form class="form-container form-bg" id="" autocomplete="off">
								@csrf

								<h3>Forgot
									<span class="close_icon" onclick="closeForm()">&#10005;</span>
								</h3>
								<div class="form-group">
									<input type="email" class="form-control" id="forgotemail"  placeholder="Email or username" name="email">
									</div>
									<button type="submit" class="btn btn-primary org">Forgot Password</button>
									<div class="botm">
										<button type="button" class="btn btn-primary trans" onclick="openForm('signin')">Log in</button>
									</div>
								</form>
							</div>
						</div>

					</div>
				@endif	-->
<!--------------------------------REGISTER,LOGIN,FORGOT FORM-------------------------------->
				<div id="errorMessage" class="hide"></div>
					<div id="download_success" class="hide"><div class="download-message"><strong>Thank you for downloading!</strong><hr><p>Your download will begin shortly....</p> </div></div>


					<div id="subscribe_success" class="hide"><div class="download-message"><strong>Thank you for subscribing!</strong><hr><p>You will be redirected shortly....</p> </div></div>

					<div id="less_credit" class="hide"><div class="download-message"><strong>Not Enough Credits!</strong><hr><p>Consider buying more credits or upgrading your plan</p> </div></div>

			<!--
					<div class="credit-info {{ Request::is('/') ? 'show' : 'hide' }}">
						<div id="incartcredit" class="show">
						@if(!empty($incartcredit))
						<div class=""><strong>In Cart : {{$incartcredit}}  </strong> </div>
						@endif

						</div>
						<div id="availablecredit" class="show">
						@if(!empty($availablecreditcount))
						<div class=""><strong>Credits :{{$availablecreditcount}}</strong> </div>
						@endif
						</div>

					</div>
		-->
	@if (Request::path() == '/')

		<div class="crat-popup homepage-popup @if($incartcredit > 0)show @else hide @endif">
						@if(!empty($userdetail))
						@if(!empty($availablecreditcount))

							<div class="crat-price1" id="availablecredit">
							<span class="title-head">Available: </span><span><b>{{$availablecreditcount}} Credits</b></span>
							</div>
						@endif
						@endif
							<div class="crat-price" id="incart-credit">
								<span class="title-head">In Cart&nbsp;({{$cartcount}} items): </span>
								<span>
                                    <b>
                                        @if(empty($userdetail) || empty($availablecreditcount))
                                            $
                                            @if($incartcredit>0)
                                                {{number_format($incartcredit, 2)}}
                                            @else
                                                0
                                            @endif
                                        @else
                                            @if($incartcredit>0)
                                                {{$incartcredit}}
                                            @else
                                                0
                                            @endif
                                            Credits
                                        @endif
                                    </b>
                                </span>
							</div>
							<button class="btn btn-default" onclick="location.href='/cart';">GO TO CART</button>

						</div>
				@endif

	<!-------------------login popup------------------------->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog login-modal" role="document">
    <div class="modal-content">

		  <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Log in </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		</div>
	 <div class="modal-body">
	   <div id="login-error">

		</div>
        <div class="login_form " >

		<form class="form-container form-bg loginForm3" id="loginForm" autocomplete="off" onsubmit="mainlogin();" >
		<input type="hidden" name="login_flow" id="login-flow" value="">
		<input type="hidden" name="package_id" id="package_id" value="">
		<input type="hidden" name="package_type" id="package_type" value="">
		@csrf
			<div class="form-group">
				<input type="email" class="form-control" id="cart-loginemail" autocomplete="false" placeholder="Email or username" name="email" requried>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" id="cart-loginpassword" autocomplete="new-password" placeholder="Password" name="password" requried>
					<span onclick="loginzPassword()" class="loginshowXpassword"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 4C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="#5B5C5C"/></svg></span>

					</div>
					<button type="submit" class="btn btn-primary org btn-setting">Log in</button>
					<a class="nav-link hyperlink-setting" onclick="openForm3('forgot')">Forgot your password?
					</span>
				</a>
				<div class="botm">
					<p>Don't have free account yet?</p>
					<button type="button" class="btn btn-primary org btn-setting" onclick="openForm3('signup')">Create your account</button>
				</div>
			</form>
		</div>
      </div>

    </div>
  </div>
</div>


<!-------------End login popup------>
<!------------------------- login clone------------------------------------------->


<!------------------------- login clone------------------------------------------->

	<!-------------------Forgot popup------------------------->
	<div class="modal fade" id="forgotModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog login-modal" role="document">
    <div class="modal-content">
		<div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Forgot Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closebackdrop();">
          <span aria-hidden="true">&times;</span>
        </button>
		</div>
	 <div class="modal-body">
	   <div id="forgot-error">

		</div>
        <div class="login_form " >

						<form class="form-container form-bg" id="forgotForm" autocomplete="off" onsubmit="forgot_form();">
								@csrf

								<div class="form-group">
									<input type="email" class="form-control" id="forgotemail"  placeholder="Email or username" name="email">
									</div>
									<button type="submit" class="btn btn-primary org btn-setting">Forgot Password</button>
									<div class="botm">
									<p>Remember Password?</p>
									<button type="button" class="btn btn-primary org btn-setting" onclick="openForm3('signin')">Log in</button>
								</div>
								</form>
		</div>
      </div>

    </div>
  </div>
</div>
<!-------------End login popup------>




<!-----------------------------------------------------Register Popup ------------------------->
<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog login-modal signup-modal" role="document">
    <div class="modal-content">
		<div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Sign up </h5>
        <button type="button" onclick="closebackdrop();" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	   <div id="signup-error">

		</div>
        <div class="login_form " >

		<form class="form-container form-bg register_form3" id="cartregister_form" autocomplete="off" onsubmit="register();">
		@csrf
				<!--
				<div class="input-placeholder">
						<input type="text" class="form-control" id="signupname"  placeholder="" name="first_name" required>
						<div class="placeholder">
							First Name <span>*</span>
						</div>
					</div>
					-->
					<div class="name-placeholder">
						<input type="text" class="form-control" id="signupname"  placeholder="" name="first_name" required>
						<div class="nameplaceholder">
							First Name <span>*</span>
						</div>
					</div>


						<div class="email-placeholder">
						<input type="email" pattern="[a-zA-Z0-9.!#$%&amp;’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)+" title="valid@email.com" class="form-control" id="signupemail" placeholder="" name="email" autocomplete="off" required>
						<div class="emailplaceholder">
							Email Address <span>*</span>
						</div>
					</div>

						<div class="pass-placeholder">
						<input type="password" class="form-control passinput" id="cartsignuppassword" placeholder="" name="password" autocomplete="new-password" required>
								<span onclick="zPassword()" class="showXpassword"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 4C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="#5B5C5C"/></svg></span>
						<div class="passwordplaceholder">
							Password <span>*</span>
						</div>
					</div>
					<div class="conpass-placeholder">
						<input type="password" class="form-control" id="cartsignupconfirmpassword"  name="password" required>
						<div class="conpasswordplaceholder">
							Confirm Password <span>*</span>
						</div>
						<div class="error_msg_2" style="display:none;">Passwords do not match</div>
					</div>

						<!--

							<div class="form-group">
								<input type="password" class="form-control" id="cartsignupconfirmpassword" placeholder="Confirm Password *" name="password" required>
								<div class="error_msg_2" style="display:none;">Passwords do not match</div>
							</div>

					<div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
						<div class="captcha">
                          <span>{!! captcha_img() !!}</span>
                          <button type="button" class="btn btn-link btn-refresh"><i class="fa fa-refresh"></i></button>
                          </div>
						 <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha">
						</div>-->
						<p class="head-recap">Please check the box below to proceed.</p>
						@if($managesite->intmanagesiteid == '1' )
							<div class="g-recaptcha" data-sitekey="6LflkxcaAAAAAMl1ol0RKdVnnJQ_bJxYqa7XkgUW"></div>
						@endif
						@if($managesite->intmanagesiteid == '17' )
							<div class="g-recaptcha" data-sitekey="6Lc5WyUaAAAAAGUdw0GGKWDdzzdPbFsuDgXYJxkj"></div>
						@endif
						@if($managesite->intmanagesiteid == '22' )
							<div class="g-recaptcha" data-sitekey="6Le8WyUaAAAAABqocCL7twGPdV3kJ5SIV9yNSXXp"></div>
						@endif
						<div class="form-group">
								<button type="submit" class="btn btn-primary org btn-setting">Sign Up</button>

								<h6>By clicking Sign Up, you read and agree to our <a  class="hyperlink-setting" href="/termscondition">Terms and Conditions</a> and our <a class="hyperlink-setting" href="/privacypolicy">Privacy Policy</a>.</h6>
								</div>
								<div class="botm">
									<p>Already have an account?</p>
									<button type="button" class="btn btn-primary trans" onclick="openForm3('signin')">Log in</button>
								</div>
			</form>
		</div>
      </div>

    </div>
  </div>
</div>
<!------------End register popup------------------------------------------>
