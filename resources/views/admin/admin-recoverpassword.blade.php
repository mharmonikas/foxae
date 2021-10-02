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
<form role="form" id="formid" method="POST" action="{{ url('/admin/recoverpassword/') }}" >
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
					<input type="hidden" name="changepassword" id="changepassword" value="<?php echo $adminid; ?>">
						<li><label class="control-label label_text">Password</label>
					<input type="text" class="form-control" id="vchPassword" name="vchPassword" value="" required></li>
				    <li><label class="control-label label_text">Confirm Password</label>
					<input type="text" class="form-control" id="vchcPassword" name="vchcPassword" value="" required ></li>						
						
						<li class="login_button">  
							<button type="submit" class="btn_login">Submit</button>									
							
						</li>
					</ul>
                        
            	</div>
                </div>
                </div>
                </div>
                
                </div>
             </div>   

</form>	
<script src="/js/app.js"></script>	
<script>
$(document).ready(function(){
$('#formid').submit(function(){
var vchcPassword = $('#vchcPassword').val();
var vchPassword = $('#vchPassword').val();	
if(vchcPassword!=vchPassword){
alert("Your Password do not matched");
return false;	
	
}
});	
});
</script>
</body>
</html>