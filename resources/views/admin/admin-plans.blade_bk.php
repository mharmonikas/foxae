@include('admin/admin-header')
<style>
.addnew {
  background: #3c8dbc none repeat scroll 0 0;
  border-radius: 3px;
  color: #fff;
  font-size: 15px;
  height: 40px;
  line-height: 40px;
  margin-bottom: 11px;
  text-align: center;
  width: 94px;
}
th {
  background: #357ca5 none repeat scroll 0 0;
  color: #fff;
  font-size: 15px;
  font-weight: normal;
  padding: 8px 14px;
}
td {
  padding: 8px 23px;
}
.btn-primarry {
    background-color: #3490dc;
    border-color: #3490dc;
    color: #fff;
}
.table-bordered td, .table-bordered th {
    border: 1px solid #dee2e6;
    padding: 10px 10px;
}
</style>

<div class="admin-page-area">
<!-- top navigation -->
         @include('admin/admin-logout')
					<!-- /top navigation -->
       <!-- /top navigation -->
   
	<div class="col-md-12 mar-auto">
	<div class="back-strip top-side srch-byr">
	<div class="inner-top">
				Manage Plans
			
	</div>
 
	</div>
	<div class="buyer-manage">
	<div class="ful-top">
	
	<div class="col-md-12">
			
			<a href="/admin/managedomains" class="addnew"><i class='fa fa-arrow-left'></i> Back</a>
			<a href="/admin/addplan/s_{{$id}}" class="addnew"><i class="fa fa-plus-square"></i> Add New</a>
			
	</div>
	<div class="view_data">
		<table class="table table-bordered" width="100%">
			<thead>
				<tr>
					<th>Plan Name</th>
					<th>Plan Description</th>
					<th>Plan Subscription</th>
					<th>Plan Price</th>
					<th>Number of Download</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach($plans as $plan)
				<tr>
					<td>{{$plan->plan_name}}</td>
					<td>{{$plan->plan_description}}</td>
					<td>@if($plan->plan_type == 'M') Subscription Plan @else Single Case Content Pack @endif</td>
					<td>{{$plan->plan_price}}</td>
					<td>{{$plan->plan_download}}</td>
					<td>@if($plan->plan_status == 'A') <button type="button" class="btn btn-success btn-sm">{{'Activate'}}</button> @else  <button type="button" class="btn btn-danger btn-sm">{{'Deactivate'}}</button> @endif</td>
					<td><a href="/admin/editplan/p_{{$plan->plan_id}}" class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
					
					<a href="/admin/deleteplan/{{$plan->plan_id}}/{{$id}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want delete this?')"><i class="fa fa-trash" aria-hidden="true"></i></a>
					
					
					</td>
				</tr>
				@endforeach
			</tbody>
		
	
	
		</table>
		</div>
	
    </div>
    </div>
    </div>
   </div>  

		
@include('admin/admin-footer')