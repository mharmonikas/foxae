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
.roles-permission {
    border-bottom: 1px solid;
}
.roles-permission-sub {
    margin-left: 25px;
    margin-bottom: 13px;
}
span.m-left {
    margin-left: 10px;
}
</style>

<div class="">
	<div class="col-md-12 mar-auto table-bordered-responsive">
		    <h2>Manage Administration User</h2>
            <div class="container">
			@include('flash-message')
				<div class="table-responsive">
					
						<!--<form class="form-inline">
							 <div class="form-group">
							
								<input type="text" class="form-control" name="search" value="{{$search}}" placeholder="Search">
							  </div>
							<div class="form-group">
				
								<select class="form-control" name="domain">
									<option value="">Select Domain</option>
									@foreach($domains as $dom)
										<option value="{{$dom->intmanagesiteid}}" @if($domain == $dom->intmanagesiteid) Selected @endif>{{$dom->txtsiteurl}}</option>
									@endforeach
									
								</select>
							</div>							
							<button type="submit" class="btn btn-dafualt"  >Search</button>
						</form>-->
						
						<div class="row">
							<div class="col-md-6">
								<a href="/admin/manageadminuser"class="btn btn-primary pull-left" style="float: left !important;background: #6e6767 !important;border-color: #eee;">Admins</a>
								<a href="/admin/roles"class="btn btn-primary pull-left" style="float: left !important;">Roles</a>
								<a href="/admin/manage_api"class="btn btn-primary pull-left" style="float: left !important;">API</a>
							</div>
							
							
							<div class="col-md-6">
							@if(strstr($access, "1"))
								<a href="/admin/addadmin"class="btn btn-primary pull-right">Add User</a>
							@endif
							@if(!empty($search))
								<a href="/admin/manageadminuser"class="btn btn-primary pull-left" style="">Reset Filter</a>
							@endif
							
							</div>
							
						</div>
					
						
					
					<table class="table table-condensed text-center">
						<thead>
						  <tr>
							<th>Name</th>
							<th>Email </th>
							<th>Role</th>
							<th>Status</th>
							@if(strstr($access, "1"))
							<th>Action</th>
							@endif
						  </tr>
						</thead>
						<tbody>
							@foreach($response as $res)
							@if($res->intAdminID != 3)
								<tr>
									<td> {{$res->vchName}}</td>
									<td> {{$res->vchEmail}}</td>
									<td ><p class="btn btn-link btn-getting-info" data-id="{{$res->role_id}}"  data-toggle="modal" data-target="#myModal">{{$res->name}}</p></td>
									
									<td>
									@if($res->enumStatus == 'A')
											Activate 
										@elseif($res->vchRole == 'D')
											Dactivate
										@endif
									
									</td>
									@if(strstr($access, "1"))
									<td>
										
											<a href="/admin/updateadmin/{{$res->intAdminID}}" class="btn btn-success btn-sm" ><i class="fa fa-edit" aria-hidden="true"></i></a>
											<a href="/admin/deleteadminuser/{{$res->intAdminID}}" class="btn btn-danger btn-sm"  onclick="return confirm('Are you sure you want delete this?')" ><i class="fa fa-trash" aria-hidden="true"></i></a>
										
									</td>
									@endif
								</tr>
								@endif
							@endforeach
						</tbody>
						</table>
						{{ $response->links() }}
						
			</div>
 
			</div>  
		</div>  
	</div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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


	$( ".btn-getting-info" ).click(function() {
			var id=$(this).attr('data-id');
	        $.ajaxSetup({
            url: "/admin/gettingroleinfo",
            data: { id: id},
            async: true,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
			},
          });
        $.post()
        .done(function(data) {
			$('.modal-body').html(data);
        })
        .fail(function(response) {
			
            console.log('failed');
        })
	
    });

</script>

@include('admin/admin-footer')