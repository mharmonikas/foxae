@include('header')
<div class="container-fuild container-fuild-maintain">    
	<div class="row row-fuild-maintain">
		@include('sidebar')
		<div class="col-md-8">
			<div class="profile-container">
			<h1> Wishlist </h1>
			<div class="table-responsive">
				<table class="table" width="100%">
			<thead style="text-align:center">
				<tr>
					<th></th>
					<th>Name</th>
					<th>Type</th>
					
					<th></th>
					
				</tr>
			</thead>
						@php
						$i=1;
						
						@endphp
	
						@if(count($response)>0)
							@foreach($response as $res)
							  <tr>
								<td>@if($res->EnumType=='I')<img src="/resize1/showimage/{{ $res->IntId }}/{{$siteid}}/{{ $res->VchResizeimage}}/?={{ $res->intsetdefault}}" height="80px" width="180px">@else <a href="/?s={{$res->VchTitle}}">{{$res->VchTitle}} </a> @endif</td>
						
								<td><a href="/?s={{$res->VchTitle}}">{{$res->VchTitle}} </a></td>
							
								<td>@if($res->EnumType=='I')Image @else Video @endif</td>
								
							
							
								<td>
								<a href="javascript:void(0)" class="btn btn-success btn-sm download" data-id="{{Crypt::encryptString($res->IntId)}}" data-title="Download" title="Downloads" ><span><i class="fa fa-download" aria-hidden="true"></i></span></a> 
								
								<button class="btn btn-danger btn-sm delete" data-title="Remove" id="{{$res->id}}" title="Remove"><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button> 

								</td>
								
								
							  </tr>
							  @php
						$i++;
						
						@endphp
							@endforeach  
								<input type="hidden" id="productid" >
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
<script>
$(document).on("click",".download",function(){
	//if (confirm('Are you sure you want to use your credit and download this?')) {
$("#productid").val($(this).attr('data-id'));
	downloadinfo('no'); 
	//}
});
$(document).on("click",".delete",function(){
	  if (confirm('Are you sure you want to remove this?')) {
		var id = $(this).attr('id');
		var token=$('meta[name="csrf-token"]').attr('content');
		$.ajax({     
			url: '/wishlist-delete',
			type:"POST", 
			async: true,
			dataType: 'json',		
			headers: {
				'X-CSRF-TOKEN':token
			},        
			data:'id='+id+'&_token='+token,
			success:function(data){ 
				window.location = '/wishlist';
			}
		});
	  }		
});


</script>
