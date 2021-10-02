@include('header')

<style>
.profile-container thead tr:first-child {
    background-color: #f7f7f7;
}
</style>
<div class="container-fluid fluid-row"  style="background:#fff;">
	<div class="container">
		<div class="cart-section">
			<h1 class="heading_main">Cart</h1>
			<div class="row">
				<div class="col-md-7">
					<div class="responsive_mob">
					<!--<h3>Cart Items:</h3>-->
						<table class="table" width="100%">
							@php $i=1; @endphp
							@if(count($response)>0)
								@foreach($response as $res)
								<tr class="remove_cart_{{$res->id}}">
									<td>@if($res->EnumType=='I')<img src="/resize1/showimage/{{ $res->IntId }}/{{$siteid}}/{{ $res->VchResizeimage}}/?={{ $res->intsetdefault}}" height="80px" width="180px">@else <img src="/resize2/showimage/{{$res->IntId}}/{{$res->siteid}}/{{$res->VchVideothumbnail}}/?=16" height="80px" width="180px"> @endif</td>
									<td><a href="/?s={{$res->VchTitle}}">{{$res->VchTitle}} </a><br>
									<button class="btn btn-danger btn-sm delete" data-title="Remove" id="{{$res->id}}" title="Remove"><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button></td>
								</tr>
								@php $i++; @endphp
								@endforeach  
								<input type="hidden" id="productid" >
							@else 
								<tr>
									<td colspan='5' style="text-align:center"><h2> No Records Found </h2></td>
								 </tr>
							@endif
							</table>
					</div>	
				</div>	
				<div class="col-md-7 desk">
					<!--<h3>Cart Items:</h3>-->
							<div class="profile-container cart_table">
								<div class="table-responsive">
									<table class="table" width="100%">
										<thead style="text-align:center">
											<tr>
												<th style="width: 5px;"></th>
												<th style="width: 20px;"></th>
												<th>Name</th>
												<th>Image Type</th>
												<th>Type</th>
											</tr>
										</thead>
										@php $i=1; @endphp
										@if(count($response)>0)
											@foreach($response as $res)
											  <tr class="remove_cart_{{$res->id}}">
												<td>
												<button class="btn btn-danger btn-sm delete" data-title="Remove" id="{{$res->id}}" title="Remove"><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button> 
												</td>
												
												<td>@if($res->EnumType=='I')<img src="/resize1/showimage/{{ $res->IntId }}/{{$siteid}}/{{ $res->VchResizeimage}}/?={{ $res->intsetdefault}}" height="60px" width="100px" class="cart-img-size">@else <img src="/resize2/showimage/{{$res->IntId}}/{{$res->siteid}}/{{$res->VchVideothumbnail}}/?=16" height="60px" width="100px"> @endif</td>
												
												<td class="cart-text-center"><a href="/?s={{$res->VchTitle}}">{{$res->VchTitle}} </a></td>
												<td> 
												@if($res->content_category == 1)
													Standard
												@elseif($res->content_category == 2)
													Premium
												@elseif($res->content_category == 3)	
													Ultra Premium
												@endif	
												</td>
												<td>@if($res->EnumType=='I')Image @else Video @endif</td>
											</tr>
											  @php $i++; @endphp
										@endforeach  
											<input type="hidden" id="productid" >
										@else 
											 <tr>
												<td colspan='5' style="text-align:center"><h2> No Records Found </h2></td>
											 </tr>
										@endif
								</table>
							</div>
						</div>
					</div>
					<div class="col-md-5 summary">
						<!--<h3>Package Detail:</h3>-->
						<div class="border_section">
							<div class="details">
								<div class="order_sec">
									<p>@if(!empty($package)) {!!$package->package_name!!}  @else You don't have any package. @endif</p>
								</div>
								<div class="order_pay">
									<p><strong> @if(!empty($package))Remaining <span class="availablecount">{{ $package->package_count - $package->package_download }} </span> @endif</strong></p>
								</div>
							</div>
							<div class="buy_button detail">
								<button type="submit" class="submit download-process" id="complete-checkout"  onclick="downloadcart()">Download</button>
							</div>
						</div>
					</div> 
				</div>
			</div>
	</div>
</div>	
@include('footer')
<script>
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
				window.location = '/cart';
			}
		});
	  }		
});
var i = '';

function downloadcart(){
	  if (confirm('Are you sure you want to download  this list?')) {
		var id = $(this).attr('id');
		var token=$('meta[name="csrf-token"]').attr('content');
		
		$(".download-process").html('<i class="fa fa-refresh fa-spin"></i> Download');
		$.ajax({     
			url: '/downloadcart',
			type:"POST", 
			async: true,
			dataType: 'json',		
			headers: {
				'X-CSRF-TOKEN':token
			},        
			data:'_token='+token,
			success:function(data){ 
			var urls = [];
			var cartid = [];
			
			$(".availablecount").html(data.available);
			var response = data.response;
				$(".download-process").html('Download');
				for(i=0; i<response.length; i++){
					cartid.push(response[i]['cartid']);
					urls.push('/fileTodownload/'+response[i]['downloadid']);
				}
				setInterval(download, 2000, urls);
				var myVar =  setInterval(removetocart, 2200, cartid);
				i = 0;
				$("#cartcount").html('<img src="/public/images/cart.png"><span>'+data.cartcount+'</span>');
			}
		});
	  }		
}
function download(urls) {
  var url = urls.pop();
	if(url != undefined){
	  var a = document.createElement("a");
	  a.setAttribute('href', url);
	  a.setAttribute('download', '');
	  a.setAttribute('target', '_blank');
	  a.click();
	}
}
	
function removetocart(cartid){
	var cartid = cartid.pop();

	if(cartid != undefined){
		$(".remove_cart_"+cartid).remove();
	}else if(cartid == undefined){
		if(i == 0){
			$("#errorMessage").html('<div class=""><strong>Not enough credit.</strong> </div>');
			myFunction();
		}
		// clearInterval(myVar);
		i++;
	}
}
</script>