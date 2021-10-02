@include('admin/admin-header')
@include('admin/admin-logout')


<style>
.custom-table {
    padding: 10px 4% 0 18%;
}
a.btn.btn-primary.pull-right {
    position: initial !important;
}
</style>
<div class="row">
		<div class="col-md-12 mar-auto">
		  <div class="back-strip top-side srch-byr">
				<div class="inner-top">
				 Discocunt List
				</div>
		  </div>    
		</div>
	    <div class="clearfix"></div>
		<div class="col-md-12 mar-auto custom-table">
			<a href="/admin/creatediscount" class="btn btn-primary pull-right">Add Coupon </a>
			<div class="table-responsive">
			  <table class="table">
				<tr>
					<th>Coupon</th>
					<th>Type</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th>Status</th>
					<th>Domain</th>
					<th>Apply For</th>
					<th>Action</th>
				</tr>
				@foreach($response as $res)z
				<tr>
					<td> {{$res->coupon}}</td>
					<td> {{$res->type}}</td>
					<td> {{$res->start_date}}</td>
					<td> {{$res->end_date}}</td>
					<td> @if($res->status == 'A') Activated  @elseif($res->status == 'D') Deactivated  @elseif($res->status == 'E') Expired @endif </td>
					<td> @if($res->domain_id == 'A') All Domain @else {{$res->vchsitename}} @endif</td>
					<td> @if($res->apply_for == 'E') Everyone @elseif($res->apply_for == 'S') Single @endif  </td>
					<td>
						<a href="/admin/discountedit/{{$res->id}}" class="btn btn-success  btn-sm" ><i class="fa fa-edit" aria-hidden="true"></i></a> 
						
						<a href="javascript:void(0);" class=" btn btn-danger delete  btn-sm"  title="Delete site"><i class="fa fa-trash" aria-hidden="true"></i> </a>
					</td>
				</tr>	
				@endforeach
			  </table>
				{{ $response->links() }}
			</div>
		</div>
</div>


@include('admin/admin-footer')