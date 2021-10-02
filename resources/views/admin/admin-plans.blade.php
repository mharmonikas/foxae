@include('admin/admin-header')

<style>
.view_data.credits_info {
    background: #eee;
    margin: 8px 10%;
}
.credits_info .col-md-2 {
    text-align: center;
}
.table tr:nth-child(2n+0) {
    background: rgb(238, 238, 238) none repeat scroll 0 0;
}
.submit-right {
    text-align: right;
}
th {
    background: #eee none repeat scroll 0 0 !important;
    color: #000;
}
.table td, .table th {
    border-top: none;
}
.credits_info p {
    font-size: 20px;
    font-weight: 800;
    text-align: center;
}
</style>

<div class="admin-page-area">
<!-- top navigation -->
         @include('admin/admin-logout')
					<!-- /top navigation -->
       <!-- /top navigation -->
   
	<div class="col-md-12 mar-auto">
	<div class="back-strip top-side srch-byr">
		<div class="inner-top">Manage Plans</div>
	</div>
	<div class="buyer-manage">
	<div class="ful-top">
	
	<!--<div class="col-md-12">
			
			<a href="/admin/managedomains" class="addnew"><i class='fa fa-arrow-left'></i> Back</a>
			<a href="/admin/addplan/s_{{$id}}" class="addnew"><i class="fa fa-plus-square"></i> Add New</a>
			
	</div>-->
<form action="" method="post">	
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="siteid" value="{{ $id }}" >
	<div class=" view_data credits_info">
		<div class="row">	
				<div class="col-md-1"></div>
				<div class="col-md-2"><p>Plan Name</p></div>
				<div class="col-md-2"><p>Credits</p></div>
				<div class="col-md-1"><p>$/Mo</p></div>
				<div class="col-md-5"></div>
				<div class="col-md-1"></div>
		</div>		
			@php $i = 1; @endphp
			@foreach($plans as $plan)
				@if($plan->plan_type == 'M')
			<div class="row" >		
				<input type="hidden" name="plan_id[]" value="{{ $plan->plan_id}}" >
				<input type="hidden" name="plan_type[]" value="M" >
				<div class="col-md-1">Plan {{$i}}</div>
				<div class="col-md-2"> <div class="form-group"><input type="text" class="form-control" name="plan_name[]" value="{{ $plan->plan_title}}"></div></div>
				<div class="col-md-2"> <div class="form-group"><input type="text" class="form-control" name="plan_download[]" value="{{ $plan->plan_download}}"></div></div>
				<div class="col-md-1"><div class="form-group"><input type="text" class="form-control"  name="plan_price[]" value="{{ $plan->plan_price}}" ></div></div>
				<div class="col-md-5">
				
				<div class="form-group"><input type="text" class="form-control"  name="plan_description[]" value="{{ $plan->plan_description}}" ></div>
				</div>
				<div class="col-md-1"> <button class="btn btn-link btn-sm removeCurrent" type="button" data-id="{{ $plan->plan_id }}" > &#10005; </button></div>
			</div>	
				@php $i++; @endphp
				@endif
			@endforeach
			
			<div class=" month-plan" style="text-align: right;">
				<button type="button" class="btn btn-success btn-sm" onclick="addmonthplan();">+ Add More</button>
			</div>
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-2"><p>Credits</p></div>
				<div class="col-md-2"><p>$</p></div>
				<div class="col-md-5"></div>
				<div class="col-md-1"></div>
			</div>
			@php $i = 1; @endphp
			@foreach($plans as $plan)
				@if($plan->plan_type == 'O')
			<div class="row">		
				<input type="hidden" name="plan_id[]" value="{{ $plan->plan_id}}" >
				<input type="hidden" name="plan_type[]" value="O" >
				
				<div class="col-md-2">Plan {{$i}}</div>
				<div class="col-md-2"> <div class="form-group"><input type="text" class="form-control" name="plan_download[]" value="{{ $plan->plan_download}}"></div></div>
				<div class="col-md-2"><div class="form-group"><input type="text" class="form-control"  name="plan_price[]" value="{{ $plan->plan_price}}" ></div></div>
				<div class="col-md-5"><div class="form-group"><input type="text" class="form-control"  name="plan_description[]" value="{{ $plan->plan_description}}" ></div></div>
				<div class="col-md-1"> <button class="btn btn-link btn-sm removeCurrent" type="button" data-id="{{ $plan->plan_id }}" > &#10005; </button></div>
				
			</div>
			
			
				@php $i++; @endphp
				@endif
			@endforeach
			
			<div class=" single-plan" style="text-align: right;">
				<button type="button" class="btn btn-success btn-sm" onclick="addsingleplan();">+ Add More</button>
			</div>
			<div class="row">	
			<div class="col-md-2"><b>Yearly Discount:</b></div>
			<div class="col-md-2"> <div class="form-group"><input type="text" class="form-control" name="yearly_discount" value="{{ $plan->yearly_discount }}"></div></div>
			<div class="col-md-1">%</div>
			</div>
				<table class="table">
				<tr>
				<th style="width: 25%;"></th>
				<th style="text-align: center;">Standard</th>
				<th style="text-align: center;">Premium</th>
				<th style="text-align: center;">Deluxe</th>
				</tr>
				@php 
				$j = 0;
				@endphp
				@foreach($stockresponse as $stockresponses)
				
				<tr>
				<td style="padding: 4% 2%;"><b>{{$stockresponses->stock_type}}:</b></td>
				  @for($i = 1; $i <= 3; $i++)
					  @if(!empty($stockrescolwise))
					  @foreach($stockrescolwise[$j] as $stockrescolwises)
						<td>
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
				<tr>
					<td> Dollar to Credits Conversion Rate : <br>(1 USD TO CREDIT)</td>
					<td>
					<input type="text" class="form-control" id="exampleInputPassword1" name="conversion_rate" placeholder="" value="@if(!empty($conversion_rate)){{$conversion_rate}}@endif"></td>
					<td> Credits (<b>How many credits you get, per 1 dollar</b>)</td>
				</tr>
				</table>
				
			<div class="submit-right">
				<button type="submit" class="btn btn-success"> Submit </button>
			</div>
	
	</div>
</form>	
	
	
	
    </div>
    </div>
    </div>
   </div>  

<script>

function addmonthplan(){
	var newplan = '<div class="row"><input type="hidden" name="plan_id[]"  ><input type="hidden" name="plan_type[]" value="M" ><div class="col-md-2"></div><div class="col-md-4"> <div class="form-group"><input type="text" class="form-control" name="plan_download[]" ></div></div><div class="col-md-5"><div class="form-group"><input type="text" class="form-control" name="plan_price[]"  ></div></div><div class="col-md-1"> <button class="btn btn-link btn-sm removeCurrent" type="button" data-id="" > &#10005; </button></div></div>';
	$(".month-plan").before(newplan);
}
function addsingleplan(){
	var newplan = '<div class="row"><input type="hidden" name="plan_id[]"  ><input type="hidden" name="plan_type[]" value="O" ><div class="col-md-2"></div><div class="col-md-4"> <div class="form-group"><input type="text" class="form-control" name="plan_download[]" ></div></div><div class="col-md-5"><div class="form-group"><input type="text" class="form-control" name="plan_price[]"  ></div></div><div class="col-md-1"> <button class="btn btn-link btn-sm removeCurrent" type="button" data-id="" > &#10005; </button></div></div>';
	$(".single-plan").before(newplan);
}

$(document).ready(function(){
    $(document).on("click",".removeCurrent",function(){
      if(confirm("Are you sure you want delete this?")){
		   $(this).parent().parent().remove();
		  var plan = $(this).attr('data-id'); 
		  if(plan != ""){
			var token=$('meta[name="csrf_token"]').attr('content');
			$.ajax({     
				url: '/admin/removeplan',
				type:"post",
				headers: {
					'X-CSRF-TOKEN':token
				},    
				dataType: 'json',
				data:'plan='+plan+'&_token='+token,
				success:function(data){ 
					
					
				}
			});
		  }
		
		}
    });
});
</script>
		
@include('admin/admin-footer')