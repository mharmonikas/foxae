@include('header')
<div class="container-fuild container-fuild-maintain">    
	<div class="row row-fuild-maintain">
		@include('sidebar')
		<div class="col-md-8 bg-color">
			<div class="profile-container">
			<h1> Download History </h1>
			<div class="table-responsive">
				<table class="table download-table" width="100%">
				@if(count($response)>0)
			<thead style="text-align:center">
				<tr>
					<th></th>
					<th>Name</th>
					<th>Type</th>
					<th>Download Date</th>
					<th></th>
					
				</tr>
			</thead>
						@php
						$i=1;
						
						@endphp
	
						
							@foreach($response as $res)
							  <tr>
								<td>
								@if($res->EnumType=='I')<img src="/resize1/showimage/{{ $res->IntId }}/{{$siteid}}/{{ $res->VchResizeimage}}/?={{ $res->intsetdefault}}" height="" width="250px">
								@else <img src="/resize2/showimage/{{$res->IntId}}/{{$siteid}}/{{$res->VchVideothumbnail}}/?=16" height="" width="250px">
								@endif</td>
						
								<td><a href="/?s={{$res->VchTitle}}">{{$res->VchTitle}} </a></td>
							
								<td>@if($res->EnumType=='I')Image @else Video @endif</td>
								
								<td>{{ \Carbon\Carbon::parse($res->create_at)->format('d-M-Y')}}</td>
							<!--
								<td><a href="/fileTodownload/{{Crypt::encryptString($res->IntId)}}" class="btn btn-success btn-sm" data-title="Download" title="Downloads" target="_blank"><span><i class="fa fa-download" aria-hidden="true"></i></span></a>  </td>
								-->
								
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

