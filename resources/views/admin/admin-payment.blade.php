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
a:hover {
    color: #0070e9 !important;
}
</style>

<div class="">
	<div class="col-md-12 mar-auto table-bordered-responsive">
		    <h2>Manage Payments</h2>
            <div class="container">
				<div class="table-responsive">
					<div class="col-md-9">
						<form class="form-inline">
							 <div class="form-group">
							
								<input type="text" class="form-control" name="search" value="@if(!empty($search)){{$search}}@endif" placeholder="Search">
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
						 <th>Transaction Id</th>
						  <th>Package Detail</th>
						  <th>Amount</th>
						  <th>Payment Status</th>
						  <th>Package Name</th>
						  <th>Date</th>
						  <th>Next Payment</th>
						  <th></th>
							
						  </tr>
						</thead>
						<tbody>
						@if(count($response)>0)
							@foreach($response as $res)
							  <tr> 
								<td>{{$res->strip_transactionid}}</td>
								<td>{!! $res->package_name !!}</td>
								<td> ${{number_format($res->strip_amount, 2)}} </td>
								<td>{{ $res->strip_status }}</td>
								<td>{{ $res->strip_packagename }}</td>
								<td>{{ \Carbon\Carbon::parse($res->create_at)->format('d-M-Y')}}</td>
								<td>{{ \Carbon\Carbon::parse($res->package_expiredate)->format('d-M-Y')}}</td>
								<td>@if(!empty($res->strip_receipt_url))<a href="{{ $res->strip_receipt_url }}" target="_blank">Receipt</a>@endif</td>
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


@include('admin/admin-footer')