@include('admin/admin-header')
@include('admin/admin-logout')


<style>
.custom-table {
    padding: 10px 4% 0 18%;
}
a.btn.btn-primary.pull-right {
    position: initial !important;
}
.ex-alert {
    padding: 2px 10px;
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
					<th>Place</th>
					<th>Tier</th>
					<th>Expired Date</th>
					<th>Number of uses</th>
					<th>Amount</th>
					<th>Status</th>
					<th>Domain</th>
					<th>Note</th>
					<th>Apply For</th>
					<th>Action</th>
				</tr>
				@foreach($response as $res)
				  @php
					  $type = $res->type == 'O' ? 'One Time Use' : ($res->type == 'M' ? 'Multiple Uses' : 'Website Wide');
					  $place = $res->place == 1 ? 'Cart' : 'Price';

                      $tier = '';
                      $tiers = explode(',', $res->tier);
                      foreach($tiers as $tierString) {
                          if($tier !== '') {
							   $tier.=', ';
						   }

                           if($tierString == 1) {
							  $tier.= 'Standard Image';
						  }
						  if($tierString == 2) {
							  $tier.= 'Premium Image';
						  }
						  if($tierString == 3) {
							  $tier.= 'Deluxe Image';
						  }
						  if($tierString == 4) {
							  $tier.= 'Standard Video';
						  }
						  if($tierString == 5) {
							  $tier.= 'Premium Video';
						  }
						  if($tierString == 6) {
							  $tier.= 'Deluxe Video';
						  }
                      }
				  @endphp

				<tr>
					<td> {{$res->coupon}}</td>
					<td> {{$type}}</td>
					<td> {{$place}}</td>
					<td> {{$tier}}</td>
					<td> {{$res->end_date}}</td>
					<td> {{$res->number_of_uses}}</td>
					<td> {{$res->amount}}</td>
					<td>
                        @php
                         $date_now = date("Y-m-d"); // this format is string comparable
                        @endphp

                        @if ($date_now < $res->end_date)
                            @if($res->status == 'A') <span class="alert-success ex-alert" > Active <span>  @elseif($res->status == 'D') <span class=" alert-danger ex-alert" > Inactive <span> @elseif($res->status == 'E') <span class="alert-warning ex-alert" > Expired  <span> @endif
                        @else
                            <span class="alert-danger ex-alert" > Expired  <span>
                        @endif
					</td>
					<td>@if($res->domain_id == 'A') All Domains @else {{$res->vchsitename}} @endif</td>
					<td>{{$res->note}} </td>
					<td>@if($res->type == 'E') Everyone @elseif($res->type == 'S') Single @endif  </td>
					<td>
						<a href="/admin/discountedit/{{$res->id}}" class="btn btn-success  btn-sm" ><i class="fa fa-edit" aria-hidden="true"></i></a>

						<a href="/admin/deletecoupon/{{$res->id}}" class=" btn btn-danger btn-sm"  onclick="return confirm('Are you sure you want to delete this record?')"><i class="fa fa-trash" aria-hidden="true"></i> </a>
					</td>
				</tr>
				@endforeach
			  </table>
				{{ $response->links() }}
			</div>
		</div>
</div>


@include('admin/admin-footer')
