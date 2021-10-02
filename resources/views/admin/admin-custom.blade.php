@include('admin/admin-header')
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
.btn-dafualt {
    background-color: #e56c3d;
    border-color: #e56c3d;
    color: #fff;
}
</style>

<div class="">
	<div class="col-md-12 mar-auto table-bordered-responsive">
		    <h2>Manage Custom</h2>
            <div class="container">
				<div class="table-responsive">
					<div class="">
						<form class="form-inline">
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
							<button type="submit" class="btn btn-dafualt" >Search</button>
						</form>
					</div>
					<table class="table table-condensed ">
						<thead>
						  <tr>
							<th>Email </th>
							<th>Site</th>
							<th>Phone</th>
							<th>Description</th>
							<th>Created Date</th>
							@if(strstr($access, "1"))
							<th>Action</th>
							@endif
						  </tr>
						</thead>
						<tbody>
						@foreach($response as $res)
						  <tr>
							<td>{{$res->email}}</td>
							<td>{{$res->txtsiteurl}}</td>
							<td>{{$res->phone}}</td>
							<td>{{$res->description}}</td>
							<td>{{$res->created_date}}</td>
							@if(strstr($access, "1"))
							<td><button type="button" id="{{ $res->intid }}" class="btn btn-danger btn-sm delete" data-title="Delete" title="Remove this"><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button></td>
							@endif
						  </tr>
						@endforeach  
						</tbody>
					</table>
					
					{{ $response->links() }}
			</div>
 
    </div>  
    </div>  
</div>
<script>

$( ".delete" ).click(function() {
	var id=$(this).attr('id');
	 if (confirm('Are you sure you want to delete this?')) {
	        $.ajaxSetup({
            url: "/admin/delete-custom",
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
			window.location = '/admin/managecustom';
			}
        })
        .fail(function(response) {
			
            console.log('failed');
        })
	 }
    });

</script>

@include('admin/admin-footer')