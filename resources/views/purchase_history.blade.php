@include('header')
<div class="container-fuild container-fuild-maintain">
	<div class="row row-fuild-maintain">
		@include('sidebar')
		<div class="col-md-8 bg-color">
			<div class="profile-container">
			<h1> Purchase History </h1>
			<div class="table-responsive">
				<table class="table" width="100%">
			<thead style="text-align:center">
				<tr>
					  <th>Transaction Id</th>
					  <th>Package Name</th>
					  <th>Credit Amount</th>
					  <th>Amount</th>
					  <th>Payment Status</th>
					  <th>Payment Type</th>

					  <th>Date</th>


				</tr>
			</thead>
						@php
						$i=1;

						@endphp

						@if(count($response)>0)
							@foreach($response as $res)
							  <tr>
								<td>{{$res->strip_transactionid}}</td>
								<td>@if($res->strip_package_type=='O'){{'One Time Purchase'}}@elseif($res->strip_package_type=='M'){{ $res->plan_title }}{{'- Monthly'}}@elseif($res->strip_package_type=='Y'){{ $res->plan_title }}{{'- Yearly'}}@endif</td>
								<td>@if(!empty($res->plan_download)){{$res->plan_download}} Credits @endif</td>
								<td>${{ number_format($res->strip_amount, 2) }}</td>
								<td>@if($res->strip_status='succeeded' || $res->strip_status='active'){{'Successful'}}@elseif($res->strip_status='declined'){{'Failed'}} @else {{'Processing'}} @endif</td>
								<td>@if($res->strip_package_type=='M')@if($res->strip_payment_type=='New Payment'){{'Subscription'}}@elseif($res->strip_payment_type=='Renew Payment'){{'Subscription - Renew'}}@endif @elseif($res->strip_package_type=='O'){{'One Time Purchase'}}@elseif($res->strip_package_type=='D'){{'Direct Payment'}} @elseif( $res->strip_package_type=='Y')@if($res->strip_payment_type=='New Payment'){{'Subscription'}}@elseif($res->strip_payment_type=='Renew Payment'){{'Subscription-Renew'}}@endif @endif</td>

								<td> {{ \Carbon\Carbon::parse($res->create_date)->format('d-M-Y')}}</td>

							</tr>
							  @php
						$i++;

						@endphp
							@endforeach
						@else
							 <tr>
								<td colspan='5' style="text-align:center"><h2> No Records Found </h2></td>
							 </tr>
						@endif
		</table>
		</div>
	{{ $response->links('pagination.default') }}
			</div>
		</div>

	</div>
</div>

@include('footer')
