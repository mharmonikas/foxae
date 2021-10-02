@include('header')

<div class="container-fuild container-fuild-maintain">    
	<div class="row row-fuild-maintain">
		
		<div class="col-md-12">
			<div class="profile-container reset-password"> 
			<h1> Change Password </h1>
				  <form class="form-horizontal resetpassword-form" id="resetpassword-form" action="/submitResetPassword" method="post" >
				  @csrf
				  <input type="hidden" name="userid" value="@if(!empty($id)){{ $id }}@endif">
					
					
					<div class="form-group">
					  <label class="control-label col-sm-12" for="password">New Password:</label>
					  <div class="col-sm-12">
						<input type="password" class="form-control" id="password" placeholder="Enter Password" name="password"  required>
						<span onclick="XchangePassword()" class="showPassword"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 4C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="#5B5C5C"/></svg></span>
					  </div>
					</div>
					<div class="form-group">
					  <label class="control-label col-sm-12" for="txtConfirmPassword">Confirm Password:</label>
					  <div class="col-sm-12">          
						<input type="password" class="form-control" id="txtConfirmPassword" placeholder="Re-enter Password" name="confirmpassword" required >
						
						<div class="error_msg" style="display:none;">Passwords do not match</div>
					  </div>
					</div> 
				 
					<div class="form-group">        
					  <div class="col-sm-offset-3 col-sm-3">
						<button type="button" class="btn btn-primary btn-setting" onClick='submitDetailsForm()' >Submit</button>
					  </div>
					</div>
				  </form>
			</div>
		</div>

	</div>
</div>

@include('footer')
<script type="text/javascript">
function submitDetailsForm() {
	var password = $("#password").val();
            var confirmPassword = $("#txtConfirmPassword").val();
            if (password != confirmPassword) {
                //alert("Passwords do not match.");
				$(".error_msg").show();
                return false;
            }
            //return true;
       $("#validatedForm").submit();
}

</script>

