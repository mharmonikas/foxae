@include('admin/admin-header')
<div class="admin-page-area">
<!-- top navigation -->
       @include('admin/admin-logout')
					<!-- /top navigation -->
			<div class="col-md-4 mar-auto">
			@if($msg !="")
			<div class="alert alert-success {{$errorclass}}" style="margin-top:18px;">
				
				{{$msg}}
			</div>
			@endif
				<div class="fully-covered">
					<div class="back-strip">
						<h2>Admin Change Password</h2>
					</div>

				<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/changepassword/') }}" id="formBuyerLogin">
					<input type="hidden" name="_token" value="{{ csrf_token() }}"> 
                    <div class="form-group">
							<label for="pwd">Old Password:</label>
							<input type="password" class="form-control" id="vcholdPassword" placeholder="Enter Password" name="oldPassword" required>
						</div>					
						<div class="form-group">
							<label for="pwd">New Password:</label>
							<input type="password" class="form-control" id="vchPassword" placeholder="Enter Password" name="Password" required>
						</div>
						<div class="form-group">
						  <label for="pwd">Confirm Password:</label>
						  <input type="password" class="form-control" id="txtConfirmPassword" placeholder="Enter Confirm Password" name="cPassword" required>
						</div>	
						<div class="btn-down">
							<button type="submit" class="btn btn-default act" onclick="return Validate()">Submit</button>
							<a href="{{ url('/admin/dashboard/') }}" class="btn btn-default">Cancel</a>
						</div>
				</form>
			</div>
		</div>
</div>
<style>
.msgerror{
background-color: red;
border-color:red;		
}
</style>
<script>
function Validate() {
        var password = document.getElementById("vchPassword").value;
        var confirmPassword = document.getElementById("txtConfirmPassword").value;
        if (password != confirmPassword) {
            alert("Passwords do not match.");
            return false;
        }
        return true;
    }
</script>
@include('admin/admin-footer')