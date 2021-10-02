
@include('admin/admin-header')
<div class="admin-page-area">
<!-- top navigation -->
       @include('admin/admin-logout')
					<!-- /top navigation -->
		<div class="buyer-manage">
			
			
			<div class="col-md-12 mar-auto">
				<div class="bottom-form">
					<div class="fully-covered">
						
						<div class="back-strip">
							<h2>Notification</h2>
						</div>
						<div class="form-detail">
							               	
							
							<form  role="form" method="POST" action="{{ url('/admin/notification') }}" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							
							
							
							<div class="col-lg-6 col-md-12 col-md-offset-2" >
								<div class="form-group">
									<label for="concept" class="col-sm-3">Send User</label>
									<div class="col-sm-8">
										<select class="form-control" name="user">
											<option value="buyer">Buyer</option>
											<option value="vendor">Vendor</option>
											<option value="subvendor">Sub Vendor</option>
										</select>
									</div>
								</div>
							</div><div class="col-lg-6 col-md-12 col-md-offset-2" >
								<div class="form-group">
									<label for="concept" class="col-sm-3">Notification</label>
									<div class="col-sm-8">
										<textarea class="form-control" name="notification"></textarea>
									</div>
								</div>
							</div><div class="col-lg-6 col-md-12 col-md-offset-2" >
								<div class="form-group">
									<label for="concept" class="col-sm-3">Send Device</label>
									<div class="col-sm-8">
										<select class="form-control" name="device">
											<option value="AND">Android</option>
											<option value="IOS">IOS</option>
										</select>
									</div>
								</div>
							</div>
							
							<div class="col-lg-12 col-md-12 col-md-offset-6">
								<button type="submit" class="btn btn-default act "> Update </button>
							</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
<script type="text/javascript">
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

</script>

<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
@include('admin/admin-footer')