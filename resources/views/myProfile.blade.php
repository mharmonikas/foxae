@include('header')
<div class="container-fuild container-fuild-maintain">    
	<div class="row row-fuild-maintain">
		@include('sidebar')
		<div class="col-md-8 bg-color">
			<div class="profile-container">
			<h1> My Profile </h1>
			<div class="table-responsive">
				<table class="table">
					<tr>
						<td style="border-top: none;">Name </td>
						<td style="border-top: none;">{{ $profiledetail->vchfirst_name }}</td>
					</tr>
					<tr>
						<td>Email </td>
						<td>{{ $profiledetail->vchemail }}</td>
					</tr>
					<tr>
						<td>Last Login </td>
						<td>{{ $profiledetail->lastlogin }}</td>
					</tr>
					<!--
					<tr>
						<td>Account Verify  </td>
						<td>@if($profiledetail->verifystatus == 0) <p style="color:red">Not Verified</p> @else <p style="color:green">Verify</p>  @endif</td>
					</tr>-->
					<tr>
						<td>Current Credit Amount</td>
						<td>@if(!empty($availablecount)){{$availablecount}}@else{{'0'}}@endif Credits</td>
					</tr>
				</table>
				</div>
			</div>
		</div>

	</div>
</div>
@include('footer')