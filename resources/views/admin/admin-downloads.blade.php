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
a {
    color: #0070e9;
}
a:hover {
    color: #fff !important;
}
.btn-dafualt {
    background-color: #e56c3d;
    border-color: #e56c3d;
    color: #fff;
}
</style>

<div class="">
	<div class="col-md-12 mar-auto table-bordered-responsive">
		    <h2>Manage Downloads</h2>
            <div class="container">
				<div class="table-responsive">
					<div class="col-md-9">
						<form class="form-inline">
							 <div class="form-group">
							
								<input type="text" class="form-control" name="search" value="@if(!empty($search)){{$search}}@endif" placeholder="Search">
							  </div>
							<div class="form-group">
				
								<select class="form-control" name="domain">
									<option value="">Select Domain</option>
									@foreach($domains as $dom)
										<option value="{{$dom->intmanagesiteid}}" @if($domain == $dom->intmanagesiteid) Selected @endif>{{$dom->txtsiteurl}}</option>
									@endforeach
									
								</select>
							</div>	
							<div class="form-group">
				
								<select class="form-control" name="type">
									<option value="">Select Type</option>
									<option value="I" @if($imgtype == 'I') Selected @endif>Image</option>
									<option value="V" @if($imgtype == 'V') Selected @endif>Video</option>
									
									
								</select>
							</div>							
							<button type="submit" class="btn btn-dafualt" >Search</button>
						</form>
					</div>
					<div class="col-md-3">
					<a href="/admin/manageuser" type="button" class="btn btn-dafualt pull-right" >Back</a>
					</div>
					<table class="table table-condensed ">
						<thead>
						  <tr>
							<th>Name</th>
							<th>Site</th>
							<th>Type</th>
							<th>Image/Video</th>
							<th>Download Date</th>
							
						  </tr>
						</thead>
						<tbody>
						@if(count($response)>0)
							@foreach($response as $res)
							  <tr>
								<td>{{$res->VchTitle}}</td>
								<td>{{$res->txtsiteurl}}</td>
								<td>@if($res->EnumType=='I')Image @else Video @endif</td>
								<td>@if($res->EnumType=='I')<img src="/resize1/showimage/{{$res->video_id}}/{{$res->site_id}}/{{ $res->VchResizeimage }}/?=506057403" height="80px" width="80px">@else<img src="/resize2/showimage/{{$res->video_id}}/{{$res->site_id}}/{{$res->VchVideothumbnail}}/?=16" height="80px" width="80px"> @endif</td>
								
								
								<td>{{$res->create_at}}</td>
							
								
								
								
							  </tr>
							@endforeach  
						@else 
							 <tr>
								<td colspan='5' style="text-align:center"><h2> No Records Found </h2></td>
							 </tr>
						@endif
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