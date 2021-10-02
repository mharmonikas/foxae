@include('admin/admin-header')
 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"/>
<div class="admin-page-area">
@include('admin/admin-logout')

<style>
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: inherit !important;
    margin-right: 10px;
}
</style>
<div class="">
	<div class="col-md-12 mar-auto">
		  <div class="back-strip top-side srch-byr">
				<div class="inner-top">
				 Add Administration Users
				</div>
		  </div>
                <div class="searchtags theme_opt_out">
						<form method="POST" enctype="multipart/form-data" action="/admin/admincreate">
							@csrf
						       <div class="ful-top gap-sextion" id="product_container">
										<div class="col-md-12">
												<input type="hidden" name="userid" @if(!empty($respone->intAdminID)) value="{{$respone->intAdminID}}" @endif>
												<div class="form-group">
													<label for="site_name">Name</label>
														<input required="" type="text" name="name" class="form-control" id="site_name" @if(!empty($respone->vchName)) value="{{$respone->vchName}}" @endif>
												</div>
												<div class="form-group">
													<label for="site_url">Email</label>
														<input type="text" name="email" required="" class="form-control" id="site_url"  @if(!empty($respone->vchEmail)) value="{{$respone->vchEmail}}" @endif>
												</div>
												<div class="form-group">
													<label for="meta_title">Password</label>
														<input type="text" name="password" class="form-control" id="meta_title" @if(!empty($respone->showpassword)) value="{{$respone->showpassword}}" @endif>
												</div>
												
												
												
												<div class="form-group">
												<input type="hidden" name="permissionid" @if(!empty($permissione)) value="{{$permissione->id}}" @endif>
													<label for="description">Assine Role</label>
													<select class="form-control" name="role" > 
														<option value="">Select Option</option>
														@foreach($roles as $role)
															<option value="{{$role->id}}" @if(!empty($respone->vchRole)) @if($respone->vchRole == $role->id) selected @endif @endif>{{$role->name}}</option>
														@endforeach
													</select>
												</div>
												<div class="form-group">
												
													<label class="radio-inline">Status: </label>
													<label class="radio-inline"><input type="radio" name="status" value="A"  @if(empty($respone->enumStatus))checked @endif  @if(!empty($respone->enumStatus)) @if($respone->enumStatus == 'A') checked @endif @endif >Activate</label>
													<label class="radio-inline"><input type="radio" name="status" value="D" @if(!empty($respone->enumStatus)) @if($respone->enumStatus == 'D') checked @endif @endif>Dactivate</label>
													
												</div>
												<div class="form-group" style="clear:both">
												  <input type="submit" value="Submit" class="btn btn-dafualt">
												</div>	
										</div>
								</div>
						</form>
				</div>
           <div class="clearfix"></div>
       </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
<script>
	$('.datetimepicker2').datetimepicker({
	
	lang:'ch',
	timepicker:false,
	format:'Y/m/d',
	formatDate:'Y/m/d',
	
});

$( ".delete" ).click(function() {
	var id=$(this).attr('id');
	 if (confirm('Are you sure you want to delete the user?')) {
	        $.ajaxSetup({
            url: "/admin/delete-user",
            data: { id: id },
            async: true,
            dataType: 'json',
			headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
    },
          });
        $.post()
        .done(function(data) {
		if(data.response==1){
				window.location = '/admin/manageuser';
			}
        })
        .fail(function(response) {
			
            console.log('failed');
        })
	 }
    });
	
	$( ".status" ).click(function() {
	var id=$(this).attr('id');
	var status=$(this).attr('data-title');
	//alert(status);
	 if (confirm('Are you sure you want to change the status?')) {
	        $.ajaxSetup({
            url: "/admin/change-status",
            data: { id: id, status:status },
            async: true,
            dataType: 'json',
			headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
    },
          });
        $.post()
        .done(function(data) {
		if(data.response==1){
				window.location = '/admin/manageuser';
			}
        })
        .fail(function(response) {
			
            console.log('failed');
        })
	 }
    });

</script>

@include('admin/admin-footer')