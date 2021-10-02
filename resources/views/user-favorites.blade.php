@include('header')
<style>
button.btn-delete {
    border-radius: 0;
    padding: .25rem .5rem !important;
}
</style>
<div class="container-fuild container-fuild-maintain">    
	<div class="row row-fuild-maintain">
		@include('sidebar')
		<div class="col-md-8 bg-color">
			<div class="profile-container">
			<h1>My Favorites </h1>
			<div class="table-responsive">
			
				<table class="table fav-table" width="100%">
				@if(count($response)>0)
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
	
						
							@foreach($response as $res)
							  <tr>
								<td>
								@if($res->EnumType == 'I')
								<img height="" width="250px" src="/resize1/showimage/{{$res->IntId}}/{{$siteid}}/{{$res->VchResizeimage}}/?={{$res->intsetdefault}}" > 
								
								@elseif($res->EnumType == 'V')
								<img height="" width="250px" src="/resize2/showimage/{{$res->IntId}}/{{$siteid}}/{{$res->VchVideothumbnail}}/?={{rand(10,100)}}"> 	
								@endif
								
								</td>
						
								<td><a href="/i/{{str_replace(" ","-",$res->VchTitle)}}-{{$res->IntId}}"><b>{{$res->VchTitle}}</b> </a></td>
							
								<td><span >@if($res->EnumType=='I')Image @else Video @endif</span></td>
								
								
							
								<td>
								
								
								<button class="btn btn-danger btn-sm btn-delete" data-title="Remove" id="{{$res->fav_id}}" title="Remove"><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button> 

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
$(document).on("click",".btn-delete",function(){
	  if (confirm('Are you sure you want to remove this?')) {
		var id = $(this).attr('id');
		var token=$('meta[name="csrf-token"]').attr('content');
		$.ajax({     
			url: '/favorites-delete',
			type:"POST", 
			async: true,
			dataType: 'json',		
			headers: {
				'X-CSRF-TOKEN':token
			},        
			data:'id='+id+'&_token='+token,
			success:function(data){ 
				window.location = '/favorites';
			}
		});
	  }		
});


</script>
