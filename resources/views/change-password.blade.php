@include('header')

 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
   <script>
    function validatePassword() {
        var validator = $("#password-changed").validate({
            rules: {
                password: "required",
                confirmpassword: {
                    equalTo: "#password",
				},
				oldpassword: {
				  required: true,
				  remote: {
				  url: '/check-oldpassword',
				  type: 'POST',
				   headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				   },  
				 },
            },
            messages: {
                password: " Enter Password",
                confirmpassword: " Enter Confirm Password Same as Password",
				oldpassword: "Password you entered is wrong.",
            }
        });
       
    }
 
    </script>
<style>
.error {
    color: #e50707;
}
</style>

<div class="container-fuild container-fuild-maintain">    
	<div class="row row-fuild-maintain">
		@include('sidebar')
		<div class="col-md-8 bg-color">
			<div class="profile-container"> 
			<h1> Change Password </h1>
				  <form class="form-horizontal changepassword-form" action="/submitnewpassword" method="post" id="password-changed">
				  @csrf
				  <input type="hidden" name="userid" value="@if(!empty($id)){{ $id }}@endif">
					<div class="form-group">
					  <label class="control-label col-sm-12" for="oldpassword">Old Password:</label>
					  <div class="col-sm-12">
						<input type="password" class="form-control" id="oldpassword" placeholder="Enter Old Password" name="oldpassword"  required>
					  </div>
					</div>
					
					<div class="form-group">
					  <label class="control-label col-sm-12" for="password">New Password:</label>
					  <div class="col-sm-12">
						<input type="password" class="form-control" id="password" placeholder="Enter Password" name="password"  required>
					  </div>
					</div>
					<div class="form-group">
					  <label class="control-label col-sm-12" for="pwd">Confirm Password:</label>
					  <div class="col-sm-12">          
						<input type="password" class="form-control" id="pwd" placeholder="Re-enter Password" name="confirmpassword" required >
					  </div>
					</div> 
				 
					<div class="form-group">        
					  <div class="col-sm-offset-3 col-sm-3">
						<button type="submit" class="btn btn-primary btn-setting" onClick="validatePassword();">Submit</button>
					  </div>
					</div>
				  </form>
			</div>
		</div>

	</div>
</div>

@include('footer')


