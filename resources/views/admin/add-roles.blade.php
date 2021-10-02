@include('admin/admin-header')
 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"/>
<div class="admin-page-area">
@include('admin/admin-logout')

<style>
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: inherit !important;
    margin-right: 10px;
}
.roles-permission-sub {
    margin-left: 35px;
    margin-bottom: 10px;
}
.roles-permission {
    border-bottom: 1px solid;
    margin-bottom: 15px;
}
.roles-permission label {
    font-size: 18px;
}
.roles-permission-sub label {
    font-weight: 100;
    font-size: 15px !important;
}
</style>
<div class="">
	<div class="col-md-12 mar-auto">
		  <div class="back-strip top-side srch-byr">
				<div class="inner-top">
				 Add Role
				</div>
		  </div>
                <div class="searchtags theme_opt_out">
						<form method="POST" enctype="multipart/form-data" action="/admin/createroles">
							@csrf
						       <div class="ful-top gap-sextion" id="product_container">
										<div class="col-md-12">
												<input type="hidden" name="roleid" @if(!empty($respone->id)) value="{{$respone->id}}" @endif>
												<div class="form-group">
													<label for="site_name">Name</label>
														<input required="" type="text" name="name" class="form-control"  @if(!empty($respone->name)) value="{{$respone->name}}" @endif>
												</div>
												
												
												<div class="form-group">
												<input type="hidden" name="permissionid" >
													<label for="description">Assine Module</label>
													@php $i = 0; @endphp
													@foreach($permissions as $permission)
													
													@php
														$currentarray = "";
														$preid = "";
														if(!empty($permissione)){
															if(!empty($permissione[$i])){
																$currentarray = explode(",",$permissione[$i]['role']);
																$preid = $permissione[$i]['id'];
															}
														} 
													@endphp
													<input type="hidden" name="preid[]" value="{{$preid}}">
													<div class="roles-permission">
													<div class="checkbox">
													  <label><input type="hidden" name="module[]" value="{{$permission->id}}" >{{$permission->name}}</label>
													</div>
														<div class="roles-permission-sub">
															<div class="checkbox">
																<label><input type="checkbox" name="type[{{$i}}][]" value="1" @if(!empty($currentarray)) @if (in_array(1, $currentarray)) checked @endif @endif >Add / Edit / Delete</label>
															</div>
															<div class="checkbox">
																<label><input type="checkbox" name="type[{{$i}}][]" value="2" @if(!empty($currentarray)) @if (in_array(2, $currentarray)) checked @endif @endif >View</label>
															</div>
														</div>
													</div>	
														@php $i++; @endphp
													@endforeach
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