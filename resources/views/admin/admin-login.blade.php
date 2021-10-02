<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <link href="{{ asset('/css/app.css') }}" rel="stylesheet"> 
  <link href="{{ asset('/css/admin-custom1.css') }}" rel="stylesheet"> 
</head>
<style>
body, html {height: 100%;}
body {background: url(public/images/bg.jpg); width: 100%; background-size: 100% 125%; position: relative;}
.logo img {
  width: 100%;
}
.login_user {
  background: #000 none repeat scroll 0 0 !important;
  border-radius: 4px;
  display: block;
  margin-top: 10px !important;
  padding: 0 !important;
}
.bg_login {
  background: #000000 none repeat scroll 0 0;
    max-width: 420px;
    padding: 30px;
    width: 100%;
}
li.login_button button {
  background: #e56c3d none repeat scroll 0 0;
}

li.login_button a {
  color: #e56c3d;
}
.login_pd {
  padding-top: 80px;
}
.login_user label {
  color: #fff;
}



 .containercheckbox {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.containercheckbox input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.containercheckbox:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.containercheckbox input:checked ~ .checkmark {
  background-color: #e56c3d;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.containercheckbox input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.containercheckbox .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}











</style>
<body>
<form role="form" method="POST" action="{{ url('/admin/') }}" >
<input type="hidden" name="_token" value="{{ csrf_token() }}">	
<div class="container">
        	<div class="row">
             <div class="col-md-12 col-sm-12">
             <div class="login_pd">
             	<div class="bg_login">
                						
					<div class="logo">
					<a href="{{ url('/') }}"><img src="{{ asset('images/logo.jpg') }}"></a>
					</div>
									
    		   <div class="login_user">	

				<div class="col-sm-12">
					<?php if(!empty($msg)){ ?>
					<div class="alert alert-danger alert-dismissable">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
						<?=$msg?>
					</div>
					 <?php } ?>
					 
					 @if($errors->any())
						
							@foreach($errors->all() as $error)								
								<div class="alert alert-danger alert-dismissable">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
									<strong>{{ $error }}</strong>
								</div>	
							@endforeach
						
					@endif 
					</div>	

			   
					<ul>
						<li><label class="control-label label_text">Email</label>
						 <input type="text" class="form-control" name="vchEmail" value="<?php if(isset($_COOKIE['vchEmailAdmin'])){ echo $_COOKIE['vchEmailAdmin'];}?>" required ></li>
						 <li>
						 <label class="control-label label_text">Password</label>
						 <input type="password" class="form-control" name="vchPassword" value="<?php if(isset($_COOKIE['vchPasswordAdmin'])){ echo $_COOKIE['vchPasswordAdmin'];}?>"  required ></li>
						 <li>
									 
						 <label class="containercheckbox">Keep me logged in
  <input type="checkbox" name="rememberme" value="Y" <?php if(isset($_COOKIE['remembermeAdmin']) && $_COOKIE['remembermeAdmin']=='Y'){ echo 'checked';}?> >
  <span class="checkmark"></span>
</label>
						 
						 
						 
						 </li>
						 
						 
						   
						 
						 
						 
						 
						 
						 
						 
						 
						 
						 
						 
						<li class="login_button">  
							<button type="submit" class="btn_login">Login</button>									
							<a href="{{ url('/') }}/admin/forgotpassword" class="forgot_pass">Forgot Password?</a>
						</li>
					</ul>
                        
            	</div>
                </div>
                </div>
                </div>
                
                </div>
             </div>   

</form>		
<script src="{{ asset('public/js/jquery.js') }}"></script>
<script src="{{ asset('public/js/bootstrap.min.js') }}"	></script>
</body>
</html>