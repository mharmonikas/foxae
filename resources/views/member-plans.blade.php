@include('header')
<div class="container-fuild container-fuild-maintain">    
	<div class="row row-fuild-maintain">
		@include('sidebar')
		<div class="col-md-8 bg-color">
			<div class="profile-container">
			<h1> Plans History </h1>
			<div class="table-responsive">
				<table class="table" width="100%">
			<thead style="text-align:center">
				<tr>
					  <th>Plan</th>
					  <th>Credits per Month</th>
					  <th>Payment Plan</th>
					  <th>Amount</th>
					  <th>Date Purchased</th>
					  <th>Next Payment</th>
					  <th>Expiration Date</th>
					
					
				</tr>
			</thead>
			
				
						@php
						$i=1;
						$package_id='';
						$package_subs='';
						@endphp
						
						@if(count($response)>0)
							@foreach($response as $res)
					
								@if($res->package_subscription == 'Y')
										@if(empty($package_id))
									@php
										$package_id=$res->package_id;
										$package_subs=$res->package_subscription;
									@endphp
								@endif
							@endif
							  <tr>
								<td>{{ $res->plan_title }}</td>
								<td>{{ $res->package_credit }}</td>
								<td>@if($res->strip_package_type == 'M') Monthly @elseif($res->strip_package_type == 'O')One Time Purchase @elseif($res->strip_package_type == 'Y')Yearly @endif</td>
								<td>${{number_format($res->strip_amount, 2)}}</td>
								<td>{{ \Carbon\Carbon::parse($res->package_startdate)->format('d-M-Y')}}</td>
								<td>
								@if($res->package_subscription != 'C')
								{{ \Carbon\Carbon::parse($res->package_expiredate)->format('d-M-Y')}}
								@endif
								</td>
								<td>
								@if($res->package_subscription == 'C')
								{{ \Carbon\Carbon::parse($res->package_expiredate)->format('d-M-Y')}}
								@endif
								</td>
							</tr> 
						
							  @php
						$i++;
						
						@endphp
						
							@endforeach  
								@if(!empty($package_id))
							<tr>
								<!--<td class="subscripition" id="subscripition_{{ $res->package_id }}">
								@if($res->package_subscription == 'Y')<a id="unsubscribe_{{$res->package_id}}" data-value="{{ $res->package_id }}" class="btn btn-primary unsubscribe btn-setting" >Unsubscribe</a> @elseif($res->package_subscription == 'C') <b style="color:red"> Subscription Expired </b>@endif</td>-->
								
								@if($package_subs == 'Y'  )
								<td  class="subscripition" id="subscripition_{{ $package_id }}"><a id="unsubscribe_{{$package_id}}" data-value="{{ $package_id }}" class="btn btn-primary unsubscribe btn-setting" >Unsubscribe</a></td>
								@endif
								<td colspan="4" ><a class="btn btn-primary btn-setting" onclick="location.href='/pricing';">Change Plan</a></td>
							</tr>
							@endif
			
			@else 
							 <tr>
								<td colspan='5' style="text-align:center"><h4> You don't have an active plan.<a class="hyperlink-setting" href="/pricing"> Have you considered subscribing?</a></h4></td>
							 </tr>
						@endif
		</table>
		</div>

			</div>
		</div>

	</div>
</div>

@include('footer')
