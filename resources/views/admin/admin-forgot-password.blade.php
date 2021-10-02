<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <link href="{{ asset('css/app.css') }}" rel="stylesheet"> 
  <link href="{{ asset('css/admin-custom1.css') }}" rel="stylesheet"> 
</head>
<style>
body, html {height: 100%;}
body {background: url(../public/images/bg.jpg); width: 100%; background-size: 100% 125%; position: relative;}
</style>
<body>
<form role="form" method="POST" action="{{ url('/admin/forgotpassword/') }}" >
<input type="hidden" name="_token" value="{{ csrf_token() }}">	
<div class="container">
        	<div class="row">
             <div class="col-md-12 col-sm-12">
             <div class="login_pd">
             	<div class="bg_login">
                						
					<div class="logo">
					<a href="{{ url('/') }}"><img src="/images/logo.jpg"></a>
					</div>
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
    		   <div class="login_user">		
					<ul>
						<li><label class="control-label label_text">Email</label>
						 <input type="text" class="form-control" name="vchEmail" value="<?php if(isset($_COOKIE['vchEmailAdmin'])){ echo $_COOKIE['vchEmailAdmin'];}?>" required ></li>				 
						
						<li class="login_button">  
							<button type="submit" class="btn_login">Submit</button>									
							<a href="{{ url('/') }}/admin" class="forgot_pass">Back</a>
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