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
    padding: 8px 10px;
    width: 218px;
    text-align: right;
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
.view_data {
    padding: 3% 20%;
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
				 Plans
			
	</div>
 
	</div>
	<div class="buyer-manage">
	<div class="ful-top">
	
	<div class="col-md-12">
			<a href="/admin/managedomains" class="addnew"><i class='fa fa-arrow-left'></i> Back</a>
			
	</div>
	<div class="view_data">
			<form action="/admin/addplan" method="post">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			  <input type="hidden" name="siteid" value="{{$id}}" >
			  <input type="hidden" name="planid" value="{{$id}}" >
			  <input type="hidden" name="type" value="{{$so}}" >
			  
			  <input type="hidden" name="site_id" value="@if($so == 's'){{$id}}@else{{$response->plan_siteid}}@endif" >
			  
			  <div class="form-group">
				<label for="planname">Plan Name:</label>
				<input type="text" class="form-control" id="planname" name="planname" value="@if(!empty($response)){{$response->plan_name}}@endif">
			  </div>
			  <div class="form-group">
				<label for="plandescription">Plan Description :</label>
				<textarea class="form-control" name="plandescription"  rows="5" id="plandescription">@if(!empty($response)){{$response->plan_description}}@endif</textarea>
			  </div>
			  <div class="form-group">
				  <label for="PlanSubscription">Plan Subscription:</label>
				  <select class="form-control" id="PlanSubscription" name="plansubscription"  >
					<option value="M" @if(!empty($response))@if($response->plan_type == 'M') Selected @endif @endif>Subscription Plan</option>
					<option value="O" @if(!empty($response))@if($response->plan_type == 'O') Selected @endif @endif>Single Case Content Pack</option>
				  </select>
				</div>
				<div class="form-group">
					<label for="planprice">Plan Price:</label>
					<input type="text" class="form-control" id="planprice" name="planprice" value="@if(!empty($response)){{$response->plan_price}}@endif">
				</div>
				<div class="form-group">
					<label for="plandownload">Number of Download:</label>
					<input type="text" class="form-control" id="plandownload" name="plandownload" value="@if(!empty($response)){{$response->plan_download}}@endif">
				</div>
				
				<label class="radio-inline"><input type="radio"  name="planstatus" value="A" @if(!empty($response))@if($response->plan_status == 'A') Checked @endif  @else checked @endif >Activate</label>
				<label class="radio-inline"><input type="radio"  name="planstatus" value="D" @if(!empty($response))@if($response->plan_status == 'D') Checked @endif @endif>Deactivate</label>

				<br>
				<table>
				<tr>
				<th></th>
				<th>Standard</th>
				<th>Premium</th>
				<th>Ultra Premium</th>
				</tr>
				@php 
				$j = 0;
				@endphp
				@foreach($stockresponse as $stockresponses)
				
				<tr>
				<td><b>{{$stockresponses->stock_type}}:</b></td>
				  @for($i = 1; $i <= 3; $i++)
					  @if(!empty($stockrescolwise))
					  @foreach($stockrescolwise[$j] as $stockrescolwises)
						<td><input type="hidden" class="form-control" name="stockid[]" value="{{$stockrescolwises->intid}}">
						<input type="hidden" class="form-control" name="stocktypeid[]" value="{{$stockresponses->stock_type_id}}">
						<input type="hidden" class="form-control" name="contentcatid[]" value="{{$i}}">
						<input type="text" class="form-control" name="stock[]" value="{{$stockrescolwises->stock}}">
						
					@endforeach
					@else
						<td>
						<input type="hidden" class="form-control" name="stocktypeid[]" value="{{$stockresponses->stock_type_id}}">
						<input type="hidden" class="form-control" name="contentcatid[]" value="{{$i}}">
						<input type="text" class="form-control" name="stock[]" value="">
						</td>
					@endif
				@php 
				$j++;
				@endphp
				 @endfor
				</tr>
				@endforeach
				</table>
		
				<br>
			  <button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>
	
    </div>
</div>
    </div>
   </div>  

		
@include('admin/admin-footer')