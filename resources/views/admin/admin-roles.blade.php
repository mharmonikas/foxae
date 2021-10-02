@include('admin/admin-header')
 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"/>
<div class="admin-page-area">
@include('admin/admin-logout')
<style>

.table-bordered-responsive h2 {
    text-align: center;
    font-size: 29px;
    font-weight: 600;
}
.table-bordered-responsive .table-responsive {
    padding: 2% 1%;
}
.table-bordered-responsive thead tr th {
    background: #fff !important;
    color: #000;
    padding: 10px 7px;
}
.table-bordered-responsive tr td {
    background: #fff !important;
    color: #000;
    padding: 10px 7px;
}
.table-bordered-responsive .table-responsive {
    background: #fff;
}
.table-bordered-responsive button.btn.btn-dafualt {
    margin: 1px 0 0 8px;
}
.table-bordered-responsive .form-control {
    margin: 0 0 0 15px;
    height: 38px;
}
.btn-success:hover {
    color: #fff !important;
}
.btn-dafualt {
    background-color: #e56c3d;
    border-color: #e56c3d;
    color: #fff;
}
th {
  background: #357ca5 none repeat scroll 0 0;
  color: #fff;
  font-size: 15px;
  font-weight: normal;
  padding: 8px 14px;
}

.btn.btn-primary {
    position: unset !important;
   
}

[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: unset;
    
}
</style>

<div class="">
	<div class="col-md-12 mar-auto table-bordered-responsive">
		    <h2>Manage Roles</h2>
            <div class="container">
			@include('flash-message')
				<div class="table-responsive">
					
						
						<div class="row">
							<div class="col-md-6">
								<a href="/admin/manageadminuser"class="btn btn-primary pull-left" style="float: left !important;">Admins</a>
								<a href="/admin/roles"class="btn btn-primary pull-left" style="float: left !important;background: #6e6767 !important;border-color: #eee;">Roles</a>
							</div>
							@if(strstr($access, "1"))
							<div class="col-md-6">
								<a href="/admin/addroles"class="btn btn-primary pull-right">Add Role</a>
							</div>
							@endif
						</div>
					
						
					
					<table class="table table-condensed text-center">
						<thead>
						  <tr>
							<th><b>Role</b></th>
							@if(strstr($access, "1"))
								<th><b>Action</b> </th>
							@endif
						  </tr>
						</thead>
						<tbody>
							@foreach($reponse as $res)
								@if($res->id != 1)
								<tr>
									<td><a href="/admin/manageadminuser?search={{$res->id}}"class="btn btn-link">{{$res->name}}<a></td>
									@if(strstr($access, "1"))
									<td>
										<a href="/admin/updateroles/{{$res->id}}" class="btn btn-success btn-sm" ><i class="fa fa-edit" aria-hidden="true"></i></a>
										<a href="/admin/deleterole/{{$res->id}}" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want delete this?')"><i class="fa fa-trash" aria-hidden="true"></i></a>
									</td>
									@endif
								</tr>
								@endif
							@endforeach
						</tbody>
						</table>
						{{ $reponse->links() }}
						
			</div>
 
			</div>  
		</div>  
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