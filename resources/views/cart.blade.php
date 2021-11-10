@include('header')
<link rel="stylesheet" href="/css/fox.css?v=0.0.1">
<link rel="stylesheet" href="/css/cart.css?v=0.0.2">

<div class="container-fluid fluid-row cart-row">
	<div class="container">
		<div class="cart-section">
			<div class="row">
				<div class="col-md-12 desk @if(count($response)==0)empty-cart @endif" >
					<!--<h3>Cart Items:</h3>-->
					<div class="profile-container cart_table">
							<div class="table-responsive">
							<table class="table heading-table top-th" width="100%">
								<tr>
									<th ><h4 class="heading_main">Shopping Cart</h4></th>
									<th class="cart-hide-on-mobile" colspan="3" style="text-align:right;">Price</th>
								</tr>
								</table>
                            <table class="table heading-table" width="100%" id="alert">

                            </table>
                            <table class="table heading-table @if(count($response)==0)empty-cart @endif" id="cart-area" width="100%" >
									@php $i=1; @endphp

									@if(count($response)>0)
										@foreach($response as $res)
										  <tr class="remove_cart_{{$res->id}} @if($res->transparent!='Y'){{'cart-twobtns'}}@endif" >
											<td class="cart-hide-on-mobile checkbox" style="vertical-align: middle;">
												<input style="margin-top:0;" name="checkbox_status" type="checkbox" class="form-control" value="{{$res->id}}" id="checkbox_{{$res->id}}" onclick="chck_uncheckcart({{$res->id}});" checked>
												 <span class="checkmarked"></span>
												<input type="hidden" id="status_{{$res->id}}" name="status" value=""      >

											</td>


											<td class="cart-img ">
											<h5 class="cart-hide-on-desktop">{{$res->VchTitle}}</h5>

												<div class="btn-model custom-video" id="alldetail_{{$res->IntId}}" data-bg-id="{{$res->background_id}}" data-site-id="{{$siteid}}" data-image-id="{{$res->IntId}}" data-image-name="{{$res->VchVideoName}}" data-name="{{$res->VchTitle}}" data-tags="{{$res->videotags}}"  data-image="@if(empty($res->applied_bg))/showimage/{{$res->IntId}}/{{$siteid}}/{{$res->VchVideoName}}?={{$res->intsetdefault}} @else /showimg/{{$res->userid}}/{{$res->img_name}} @endif" data-id="{{Crypt::encryptString($res->IntId)}}" data-imgtype="{{$res->content_category}}" data-category="{{$res->stock_category}}" data-seo="{{$res->seo_url}}" data-type="{{$res->EnumType}}" data-folder="{{$res->VchFolderPath}}" data-download="" video-id="{{$res->IntId}}" cart-status="out-cart" fav-status="{{$res->favoritesstatus}}" transparent-status="{{$res->transparent}}">
												<div class="cart-hide-on-desktop outter">
											<div class="object-type">
												@if($res->EnumType=='I')Image @else Video @endif
											</div>
											<span class="cart-hide-on-desktop">
														@php
												if(empty($checkloginuser)){
													if($res->stock!=0){
													$price=$res->stock/$res->conversion_rate;
													}else{
													$price=0;
													}
												}else{
													if(empty($packageid)){
													if($res->stock!=0){
														$price=$res->stock/$res->conversion_rate;
													}else{
													$price=0;

													}
													}
												}
											@endphp

											@if(!empty($checkloginuser))
												@if(!empty($packageid))
													@if(!empty($res->stock)){{$res->stock}} Credits @else 0 Credit @endif
												@else
													${{number_format($price, 2)}}
												@endif
										@else ${{number_format($price, 2)}} @endif


											</span>
											</div>

												<div class="cnrflash-for-mobile cart-hide-on-desktop">
												@if($res->content_category=='1')
												<div class="cnrflash">
													<div class="cnrflash-inner first second standard">
														<span class="cnrflash-label">Standard
														   </span>
													</div>
												</div>
												@endif

												@if($res->content_category=='2')
												<div class="cnrflash">
					<div class="cnrflash-inner first second premium">
						<span class="cnrflash-label">Premium
						   </span>
					</div>
				</div>
												@endif

												@if($res->content_category=='3')
												<div class="cnrflash">
					<div class="cnrflash-inner first second ultra_premium">
						<span class="cnrflash-label">Deluxe
						   </span>
					</div>
				</div>
												@endif
												</div>
																@if($res->EnumType=='V')
													<div class="hover-play-icon group1 cart-hide-on-mobile" >
														<img src="{{ asset('images') }}/{{$managesite->vchvideoicon}}" alt="img">
													</div>

													<img src="/resize2/showimage/{{$res->IntId}}/{{$siteid}}/{{$res->VchVideothumbnail}}/?=16" height="60px" width="100px">
												@else
												<img src="/resize1/showimage/{{ $res->IntId }}/{{$siteid}}/{{ $res->VchResizeimage}}/?={{ $res->intsetdefault}}" height="60px" width="100px" class="cart-img-size">
												</div>
												@endif
												</td>


											<td class="cart-text-center">
											<h5 class="cart-hide-on-mobile">{{$res->VchTitle}}</h5>
											<div class="cart-hide-on-mobile outter">
											@if($res->content_category == 1)
												<div class="standard">
													<span class="tag">Standard
													</span>
												</div>
											@elseif($res->content_category == 2)
												<div class="premium">
													<span class="tag">Premium
													</span>
												</div>
											@elseif($res->content_category == 3)
												<div class="ultra_premium">
													<span class="tag">Deluxe
													</span>
												</div>
											@endif
											<div class="object-type">
												@if($res->EnumType=='I')Image @else Video @endif
											</div>
											</div>
											@if(!empty($res->applied_bg))
												<p class="background-effect" id="appliedbg_{{$res->IntId}}">{{$res->applied_bg}} Background</p>
											@else
												@if($res->transparent=='Y')
													<p class="background-effect" id="appliedbg_{{$res->IntId}}">Transparent Background</p>
											@endif
											@endif

											<span class="clr-grey">|</span> <a class="delete text-style" data-title="Remove" id="{{$res->id}}" data-value="{{$res->IntId}}" title="Remove">Remove</a>
											<span class="clr-grey">|</span> <a class="later text-style" onclick="changestatus({{$res->id}},'later')" data-title="Save for Later"  title="Save for Later">Save for Later</a>
											@if($res->EnumType=='I')
												@if($res->transparent=='Y')
											<span class="clr-grey">|</span> <a class="text-style" data-toggle="modal" data-target="#myModal{{ $res->IntId }}">Change Background</a>
												@endif
											@endif
											</td>
											<td class="price-cart cart-hide-on-mobile" style="text-align:right;">
{{--                                                @php--}}
{{--                                                    $coupon = data_get(Session::get('cart-coupon'), 'coupon');--}}
{{--                                                    $discount = false;--}}
{{--                                                    $discountText = '';--}}

{{--                                                    if(empty($checkloginuser)) {--}}
{{--                                                        if($res->stock!=0){--}}
{{--                                                            $price=$res->stock/$res->conversion_rate;--}}
{{--                                                        } else {--}}
{{--                                                            $price=0;--}}
{{--                                                        }--}}
{{--                                                    } --}}
{{--                                                    --}}
{{--                                                    else if(empty($packageid)) {--}}
{{--                                                        if($res->stock != 0) {--}}
{{--                                                            $stock = $res->stock;--}}
{{--                                                            $tiers = $coupon ? explode(',', $coupon->tier) : [];--}}

{{--                                                            if($coupon && in_array($res->content_category, $tiers)) {--}}
{{--                                                                $stock = $coupon->discount_type == 'P' ? $stock - $stock * $coupon->amount / 100 : $stock - $coupon->amount;--}}
{{--                                                                $discount = true;--}}
{{--                                                                $discountText = $coupon->discount_type == 'P' ? 'Coupon '.$coupon->amount.'% off' : sprintf('Coupon $%s off', $coupon->amount);--}}
{{--                                                            }--}}

{{--                                                            $price = $stock/$res->conversion_rate;--}}
{{--                                                        } else {--}}
{{--                                                            $price = 0;--}}
{{--                                                        }--}}
{{--                                                    }--}}
{{--                                                @endphp--}}

											    @if(!empty($checkloginuser))
                                                    @if(!empty($packageid))
                                                        @if(!empty($res->stock)){{$res->stock}} Credits @else 0 Credit @endif
                                                    @else
                                                        ${{number_format($price, 2)}}
                                                    @endif
                                                @else
                                                    ${{number_format($price, 2)}}
                                                @endif

                                                @if($discountText = data_get($res, 'discountText'))
                                                    <b><br>{!! nl2br(str_repeat(' ', 30) . $discountText) !!}</b>
                                                @endif
											</td>
										</tr>
										  @php $i++; @endphp
									@endforeach
									<tr class="price-row cart-hide-on-mobile">

									<td colspan="2">
									<div class="available-credits">
									@if(!empty($checkloginuser))
										<input type="hidden" value="{{ $availablecount }}" id="available-credit">
									@if(!empty($packageid))Available Credits : <b>{{ $availablecount }} Credits   </b>
										<a class="hyperlink-setting" href="/pricing"><p>Not enough credit? Upgrade plan!</p></a>
								@endif

									@endif
									</div>
									</td>
									<td style="text-align:center">
									@if(!empty($checkloginuser))
										@if(!empty($packageid))
											<button class="btn btn-default cart-button" onclick="downloadcart({{$totalitems}})">BUY SELECTED ITEMS</button>
										@else
										<button class="btn btn-default open-form cart-button">PROCEED TO CHECKOUT</button>
										@endif
									@endif
									@if(empty($checkloginuser))
									<button class="btn btn-default cart-button" data-toggle="modal" data-target="#exampleModal">PROCEED TO CHECKOUT</button>
									@endif
									</td>
									<td  style="text-align:right">
									<div id="cart-text">
                                        <span>Subtotal&nbsp;({{$totalitems}} items): </span>
                                        <span>
                                            <b>
                                                @if(!empty($checkloginuser))
                                                    @if(!empty($packageid))
                                                        <input type="hidden" id="cart-value" value="{{$cartvalue}}">
                                                        {{$cartvalue}} Credits
                                                    @else
                                                        ${{number_format($cartvalue, 2)}}
                                                    @endif
                                                @else
                                                    ${{number_format($cartvalue, 2)}}
                                                @endif
                                            </b>
                                        </span>

									@if(empty($checkloginuser))

										@if($cartvalue>0)
											<p><a class="hyperlink-setting" data-toggle="modal" data-target="#exampleModal" >
											Save up to ${{number_format($saveuptoamount, 2)}} by subscribing</a></p>
										@elseif($cartvalue<=0)
											<p><a class="hyperlink-setting" href="/pricing">
											Have you considered subscribing?</a></p>
										@endif
									@else
										@if(empty($packageid))
											<p><a  class="open-form hyperlink-setting">Save up to ${{number_format($saveuptoamount, 2)}} by subscribing</a></p>
										@endif
								@endif
								</div>
									</td>
									</tr>
										<input type="hidden" id="productid" >



                                    @else
										 <tr>
											<td colspan='5' style="text-align:center"><h2>Cart is Empty </h2></td>
										 </tr>
									@endif
							</table>
							<input type="hidden" id="cartval" value="{{$cartvalue}}">

						</div>
					</div>

					@if(empty($checkloginuser))
						<div class="crat-popup">
							<div class="crat-price"><span>In Cart&nbsp;({{$totalitems}} items):</span><span><b>${{number_format($cartvalue, 2)}} </b></span></div>

							<button class="btn btn-default" data-toggle="modal" data-target="#exampleModal" >PROCEED TO CHECKOUT</button>

							@if($cartvalue>0)
								<p>	<a class="hyperlink-setting" data-toggle="modal" data-target="#exampleModal" >Save up to ${{number_format($saveuptoamount, 2)}} by subscribing</a></p>
							@elseif($cartvalue<=0)
								<p><a class="hyperlink-setting" href="/pricing">
								Have you considered subscribing?</a></p>
							@endif

						</div>
					@endif

					<div class="col">
						@if(!empty($checkloginuser))
                            @if(!empty($packageid))
                                <div class="crat-popup">
                                    <input type="hidden" class="" value="">

                                    <div class="crat-price1"><span class="title-head">Available Credits: </span><span><b>@if(!empty($packageid)){{ $availablecount }} Credits @endif</b></span></div>

                                    <div class="crat-price"><span class="title-head">In Cart&nbsp;({{$totalitems}} items): </span><span><b>{{$cartvalue}} Credits </b></span></div>

                                    <button class="btn btn-default" onclick="downloadcart()" >BUY SELECTED ITEMS</button>

                                    <a class="hyperlink-setting" href="/pricing"><p>Not enough credit? Upgrade plan!</p></a>
                                </div>
                            @else
                                <div class="crat-popup">
                                    <div class="crat-price"><span>In Cart&nbsp;({{$totalitems}} items): </span><span><b>${{number_format($cartvalue, 2)}}</b></span></div>

                                    <button class="btn btn-default open-form" >PROCEED TO CHECKOUT</button>

                                    @if($cartvalue>0)
                                        <p>	<a class="open-form hyperlink-setting" >Save up to ${{number_format($saveuptoamount, 2)}} by subscribing</a></p>
                                    @elseif($cartvalue<=0)
                                        <p>	<a class="hyperlink-setting" href="/pricing" >Have you considered subscribing?</a></p>
                                    @endif
                                </div>
                            @endif
						@endif

                        @if(empty($packageid) || !$packageid)
                            <div class="custom-apply">
                                <input type="text" name="apply_coupon" id="apply_coupon" placeholder="Discount Code">
                                <input type="hidden" name="apply_type" id="apply_type" value="1">
                                <button class="custom-button" type="submit" style="cursor: pointer;"> Apply</button>

                                <p class="custom-warning"></p>
                            </div>
                        @endif
					</div>
				</div>
			</div>
		</div>
	</div>

	<!------------------------------------------Save to Later--------------------------------------->
	<div class="container">
		<div class="cart-section save-later">
			<div class="row">
				@if(count($later_response)>0)
					<div class="col-md-12 desk2">
						<!--<h3>Cart Items:</h3>-->
						<div class="profile-container cart_table">
							<div class="table-responsive">
								<table class="table heading-table top-th" width="100%">
									<tr>
										<th ><h4 class="heading_main">Saved for Later</h4></td>
										<th class="cart-hide-on-mobile" colspan="3" style="text-align:right;">Price</td>
									</tr>
								</table>
								<table class="table heading-table save-later-tbl" width="100%">
									<!--
										<thead style="text-align:center">
											<tr>
												<th style="width: 5px;"></th>
												<th style="width: 20px;"></th>
												<th>Name</th>
												<th>Image Type</th>
												<th>Type</th>
											</tr>
										</thead>-->
										@php $i=1; @endphp
										@if(count($later_response)>0)
											@foreach($later_response as $late_res)
											  <tr class="remove_cart_{{$late_res->id}}">
												<!--<td style="vertical-align: middle;">
													<input style="margin-top:0;" type="checkbox" name="videoid[]" class="form-control">
												</td>-->
												<td class="cart-img">
													<h5 class="cart-hide-on-desktop">{{$late_res->VchTitle}}</h5>

													<div class="save-later-img-sec">
														<div class="cart-hide-on-desktop outter">
															<div class="object-type">
																@if($late_res->EnumType=='I')Image @else Video @endif
															</div>
															<span class="cart-hide-on-desktop">
																@php
                                                                    if(empty($checkloginuser)){
                                                                        $price=$late_res->stock/$late_res->conversion_rate;
                                                                    }
															    @endphp

                                                                @if(!empty($checkloginuser))
                                                                    {{$late_res->stock ?? 0}} Credits
                                                                @else
                                                                    $ {{number_format($price, 2)}}
                                                                @endif
															</span>
														</div>

													    <div class="cnrflash-for-mobile cart-hide-on-desktop">
														@if($late_res->content_category=='1')
															<div class="cnrflash">
																<div class="cnrflash-inner first second standard">
																	<span class="cnrflash-label">Standard
																	   </span>
																</div>
															</div>
														@endif

														@if($late_res->content_category=='2')
															<div class="cnrflash">
																<div class="cnrflash-inner first second premium">
																	<span class="cnrflash-label">Premium
																	   </span>
																</div>
															</div>
														@endif

														@if($late_res->content_category=='3')
															<div class="cnrflash">
																<div class="cnrflash-inner first second ultra_premium">
																	<span class="cnrflash-label">Deluxe
																	   </span>
																</div>
															</div>
														@endif

                                                    </div>

													    @if($late_res->EnumType=='I')
                                                            <img src="/resize1/showimage/{{ $late_res->IntId }}/{{$siteid}}/{{ $late_res->VchResizeimage}}/?={{ $late_res->intsetdefault}}" height="60px" width="100px" class="cart-img-size">
                                                        @else
                                                            <img src="/resize2/showimage/{{$late_res->IntId}}/{{$late_res->siteid}}/{{$late_res->VchVideothumbnail}}/?=16" height="60px" width="100px">
                                                        @endif
                                                    </div>
												</td>

												<td class="cart-text-center cart-hide-on-mobile"><h5 class="cart-hide-on-mobile">{{$late_res->VchTitle}}</h5>
												<div class="cart-hide-on-mobile outter">
												@if($late_res->content_category == 1)
													<div class="standard">
														<span class="tag">Standard
														</span>
													</div>
												@elseif($late_res->content_category == 2)
													<div class="premium">
														<span class="tag">Premium
														</span>
													</div>
												@elseif($late_res->content_category == 3)
													<div class="ultra_premium">
														<span class="tag">Deluxe
														</span>
													</div>
												@endif

													<div class="object-type">
													@if($late_res->EnumType=='I')Image @else Video @endif
												</div>
												<div class="cart-hide-on-desktop">
													@php
													if(empty($checkloginuser)){
														$price=$late_res->stock/$late_res->conversion_rate;
													}
												@endphp

												@if(!empty($checkloginuser))
												@if(!empty($late_res->stock)){{$late_res->stock}} Credits @else 0 Credit @endif @else $ {{number_format($price, 2)}} @endif
											</div>
												</div>

												| <a class="text-style" data-title="Remove" title="Remove" onclick="later_delete({{$late_res->id}})" >Remove</a>  | <a class="later text-style" onclick="changestatus({{$late_res->id}},'cart')" data-title="Move to Cart"  title="Move to Cart">Move to Cart</a>
												</td>


												<!--------------------------button for mobile-------------------------------->

												<td class="cart-text-center cart-hide-on-desktop mobile-btn-save">

												<a class="text-style" data-title="Remove" title="Remove" onclick="later_delete({{$late_res->id}})" >Remove</a> <a class="later text-style" onclick="changestatus({{$late_res->id}},'cart')" data-title="Move to Cart"  title="Move to Cart">Move to Cart</a>
												</td>


												<!--------------------------button for mobile end------------------------->


												<td class="price-cart cart-hide-on-mobile"  style="text-align:right;">
												@php
													if(empty($checkloginuser)){
														$price=$late_res->stock/$late_res->conversion_rate;
													}
												@endphp

												@if(!empty($checkloginuser))
												@if(!empty($late_res->stock)){{$late_res->stock}} Credits @else 0 Credit @endif @else $ {{number_format($price, 2)}} @endif
												</td>
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
				@endif
			</div>
		</div>
		<!-------------------END-------->
	</div>
</div>
@foreach($response as $res)
<div id="myModal{{$res->IntId}}" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg changebg-modal">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="background-effect_{{$res->IntId}}">@if(!empty($res->applied_bg)){{strtoupper($res->applied_bg)}} {{'BACKGROUND'}} @elseif($res->transparent=='Y'){{'TRANSPARENT BACKGROUND'}} @endif </h4>
      </div>
      <div class="modal-body">

	  <div class="myloadercontainer2" id="loader">
				<div class="loder_innes">
					<div class="loaderview1">
						<img src="images/{{$managesite->gificon}}" alt="img" style="width:auto !important;height:130px;">
					</div>
				</div>
			</div>
		@if(!empty($res->applied_bg))
			<img src="showimg/{{$res->userid}}/{{$res->img_name}}" class="non-active ui-draggable ui-draggable-handle" id="bigimagesize_{{$res->IntId}}" style="position: relative;">
		@else
			<img src="/showimage/{{$res->IntId}}/{{$siteid}}/{{$res->VchVideoName}}" class="non-active ui-draggable ui-draggable-handle" id="bigimagesize_{{$res->IntId}}" style="position: relative;">
		@endif
		<input id="image-url_{{$res->IntId}}" hidden value="/showimage/{{$res->IntId}}/{{$siteid}}/{{$res->VchVideoName}}">
		<input id="image-name" hidden value="{{$res->IntId}}">
		<input id="wishlist-id" hidden value="{{$res->id}}">
      </div>
      <div class="modal-footer">

		<div class="check-apply">
		<span class='hint' id="error"></span>

		<div class="">
		<input type="checkbox" name="all" class="form-control check_box all-checkbox" id="one_check_{{$res->IntId}}" onclick="oneinputchecked({{$res->IntId}},'one');" checked><span class="checkmarked-button"></span>
		<span class="text-setting">Apply this background to this image  <strong>ONLY</strong></span>
		</div>
		<div><input type="checkbox" name="all" class="form-control check_box all-checkbox" id="all_check_{{$res->IntId}}" onclick="allinputchecked({{$res->IntId}},'all');"><span class="checkmarked-button"></span>
		<span class="text-setting">Apply this background to <strong>ALL IMAGES </strong> in cart</span></div>

		</div>
		<div class="apply-btn">
        <button type="button"  class="btn btn-default apply_changes" id="" bg-id="" data-value="one" onclick="changebackground(this.value,{{$res->IntId}})" >APPLY CHANGES</button>
		</div>
		<div class="background-select">
		<select class="form-control bg_selection" name="changebg" id="{{$res->IntId}}" onchange="change_background(this.value,{{$res->IntId}},'onchange')">
		<option value='' disabled selected hidden>Select Background</option>
		@foreach($backgroundslist as $bglist)
				<option value="{{$bglist->bg_id}}" @if($res->applied_bg==$bglist->background_title) Selected @endif >{{$bglist->background_title}}</option>
		@endforeach
		</select>
		   </div>
      </div>
    </div>
  </div>
</div>
@endforeach

<script src="js/fox.jquery.js?v=0.1.97"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="js/jquery-cart.js?v=0.0.1"></script>
@include('footer')
<script>
$(document).on("click",".custom-button",function(){
	let loginUser = {!! json_encode(['checkLogin' => $checkloginuser ?? false] )!!};
	if(!loginUser.checkLogin) {
		$("#exampleModal").modal('toggle');

		return;
	}

	var videoid =  [];
	var type = [];
	$( ".custom-video" ).each(function() {
	  videoid.push($( this ).attr("video-id" ));
	  type.push($( this ).attr("data-type" ));
	});
	var couponcode = $('#apply_coupon').val();
	var place = $('#apply_type').val();
	var cvideoid = videoid.join(',');
	var type = type.join(',');
	var token=$('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: '/applycoupon',
		type:"POST",
		async: true,
		dataType: 'json',
		headers: {
			'X-CSRF-TOKEN':token
		},
		data:'couponcode='+couponcode+'&place='+place+'&_token='+token+'&videoid='+cvideoid+'&type='+type,
		success:function(data){
			if(data.status == '201'){
				$("#errorMessage").html('<div><strong>INVALID COUPON DETAILS</strong></div>');
				myFunction();
			}else if(data.status == '200'){
				$("#errorMessage").html('<div><strong>COUPON APPLIED SUCCESSFULLY</strong></div>');
				myFunction();

                location.reload();
            }

		}
	});
});
</script>
