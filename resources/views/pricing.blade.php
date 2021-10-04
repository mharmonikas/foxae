@include('header')

<style>
    @media only screen and (max-width: 480px){
        #price-plan-from .col-md-6 {
            width: 100%;
        }
    }

    .modal .modal-dialog.checkoutModal {
        max-width: 950px;
    }
    select.form-control {
        background-color: #fde9b3;

    }
    .pricing_total_section a {
        padding-left: 5px;
        cursor: pointer;
        font-size: 18px;
        font-weight: bold;
        margin-top: 0;
        margin-bottom: 1rem;
    }
    .tabcontent {
        display: none;

    }

    .custom-price-apply {
        position: absolute;
        right: 3%;
        z-index: 9;
        margin-top: 4%;
    }

    #apply_coupon_price {
        width: 177px;
        height: 29px;
        background: #f2f2f2;
        border-radius: 6px 0 0 0;
        border: 1px solid #f2f2f2;
        padding: 0 10px;
        box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
        outline: none;
    }

    .custom-button-price {
        margin-left: -5px;
        background: #FF8F09;
        border: 0.25px solid #BDBDBD;
        box-sizing: border-box;
        border-radius: 0 0 6px 0;
        color: #fff;
        height: 29px;
        width: 76px;
        box-shadow: 1px 4px 4px rgb(0 0 0 / 25%);

        font-family: Roboto, serif;
        font-style: normal;
        font-weight: bold;
        font-size: 14px;
        line-height: 16px;
        align-items: center;
        letter-spacing: 1.25px;
        text-transform: uppercase;
    }

    button.custom-button-price:focus {
        box-shadow: 1px 4px 4px rgb(0 0 0 / 25%) !important;
    }

    .origin_price {
        color: #5B5C5C !important;
        text-decoration: line-through;
        margin-bottom: 0;
        font-weight: 600;
    }
    .discount_apply {
        color: #5B5C5C !important;
        margin-bottom: 0;
        font-weight: 600;
    }
</style>
<div class="container-fluid" id="pricing" >
    <div class="row fluid-row  new_pricing">
        <!--<h2>Compelling media for every case!</h2>-->

        <div class="col-md-12 pricing-container">
            <form id="price-plan-from">
                @if(!empty($onetime_response))
                    <div class="@if($msg=='1')hide @endif main-onetime-div" >
                        <div class="pricing_main_section buy_section">
                            <p class="p-heading">Buy More Credits</p>
                            <p>
                                <a id="collapse_one" data-target="#collapseExample">Collapse</a>
                            </p>
                        </div>


                        <div id="collapseExample" class="" style="display: block;">

                            @foreach($onetime_response as $res)

                                <div class="pricing_main tap_anywhere" id="onetime_{{$res->plan_id}}" data-id="{{$res->plan_id}}">
                                    <p class="msg-line">Tap anywhere on the plan to choose</p>
                                    <div class="price-checkbox checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck_{{$res->plan_id}}" name="packageid" value="{{$res->plan_id}}" data-val="Onetime">
                                        <span class="checkmarked"></span>
                                    </div>
                                    <div class="pricing_title">
                                        <div class="custom-control custom-checkbox mb-3">
                                            <label class="custom-control-label" for="customCheck">{{ $res->plan_name }}</label>
                                        </div>
                                    </div>
                                    <div class="download_section">

                                        <p><span>Download up to <strong>{{ $res->plan_description }}</strong></span>
                                        </p>
                                    </div>
                                    <div class="payment_section">
                                        <p class="price">Price</p>

                                        <p>
                                        <h2><sup>$</sup>{{number_format($res->plan_price, 2)}}</h2><span>One time Payment</span>
                                        </p>
                                    </div>
                                </div>

                            @endforeach

                        </div>

                        <div class="pricing_main_section">
                            <p class="p-heading">Change Plans</p>
                            <a  id="collapse_two"  data-target="#collapseExample2">Collapse</a>
                        </div>
                    </div>
                @endif
                @if(!empty($monthly_response))
                    <div id="collapseExample2" class="" style="display: block;">
                        <div class="pricing_total_section buy_section">
                            <a class="tablinks clicked" onclick="openCity(event, 'Yearly')">Yearly Save {{$yarly_dis}}%</a>
                            <p><span> |</span>
                                <a class="tablinks inactive" onclick="openCity(event, 'Monthly')"> Monthly</a></p>
                        </div>
                        <div id="Yearly" class="tabcontent" style="display: block;">
                            @php  $j=0;    @endphp
                            @foreach($monthly_response as $res)
                                @php
                                    $discount = 0;

                                    $price1= ($res->plan_price * 12);
                                    $price2= ($res->plan_price * 12 * ($res->yearly_discount / 100));
                                    $price=$price1-$price2;
                                    $price = $price - $discount;
                                @endphp

                                @if($res->plan_id==$current_packageid && $package_type=='Y')
                                    <input type="hidden" id="plan-info" value="{{$current_packageid}}" data-id="{{$package_type}}">
                                @endif


                                <div class="pricing_main bg_section trans_{{$res->plan_id}} @if($res->plan_title=='Standard Plan'){{'standard'}}@elseif($res->plan_title=='Premium Plan'){{'premium'}}@elseif($res->plan_title=='Deluxe Plan'){{'deluxe'}}@else{{'basic'}}@endif @if($res->plan_id==$current_packageid && $package_type=='Y') active_package @endif" id="{{'Y-'}}{{$res->plan_id}}" data-id="{{$res->plan_id}}">

                                    <div class="pricing_titles">
                                        <p>{{$res->plan_title}} @if($res->plan_id==$current_packageid && $package_type=='Y')<span class="active-span">- ACTIVE PLAN</span> @endif</p>
                                    </div>


                                    <div class="download_sections @if($res->plan_id==$current_packageid && $package_type=='Y'){{'show'}}@else{{'hide'}}@endif" id="{{'yearly-active-'}}{{$res->plan_id}}">
                                        <p>ACTIVE PLAN</p>
                                    </div>

                                    @if($j=='0')
                                        <div class="payment_sections">
                                            <p>Price</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="pricing_main no_border tap_anywhere" data-id="{{$res->plan_id}}">
                                    <p class="msg-line">Tap anywhere on the plan to choose</p>
                                    <div class="price-checkbox checkbox">
                                        <input type="checkbox" class="custom-control-input custom-control-input-{{$res->plan_id}} @if($res->plan_id==$current_packageid && $package_type=='Y') hide-btn @endif {{'Y'}}{{'-'}}{{$res->plan_id}}" id="customCheck_{{$res->plan_id}}" name="packageid" value="{{$res->plan_id}}" data-val="annual" onclick="trans({{$res->plan_id}})" @if($res->plan_id==$current_packageid && $package_type=='Y') disabled @endif>
                                        <span class="checkmarked {{'Y'}}{{'-'}}{{$res->plan_id}} @if($res->plan_id==$current_packageid && $package_type=='Y'){{'hide-btn'}}@endif"></span>
                                    </div>
                                    <div class="pricing_title">
                                        <div class="custom-control custom-checkbox mb-3">

                                            <label class="custom-control-label" for="customCheck">{{ $res->plan_name }}<br><small>Per Month</small></label>
                                        </div>
                                    </div>
                                    <div class="download_section">

                                        <p><span>Download up to <strong>{{ $res->plan_description }}</strong></span>
                                    </div>
                                    <div class="payment_section">
                                        <p class="price">Price</p>
                                        <p>
                                        <h2><sup>$</sup>{{number_format($price, 2)}}</h2><p><span>Total ${{number_format($price/12, 2)}}/month</span></p><span>Billed annually</span>
                                        @if($discountText = data_get($res, 'discountText'))
                                            <p>
                                                <b>{!! nl2br(str_repeat(' ', 30) . $discountText) !!}</b>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                @php
                                    $j++;

                                @endphp
                            @endforeach
                        </div>
                    </div>

                    <div id="Monthly" class="tabcontent" style="display: none;">
                        @php  $j=0;    @endphp
                        @foreach($monthly_response as $res)
                            @php
                                $price1= ($res->plan_price * 12);
                                $price2= ($res->plan_price * 12 * ($res->yearly_discount / 100));
                                $price=$price1-$price2;
                            @endphp

                            @if($res->plan_id==$current_packageid && $package_type=='M')
                                <input type="hidden" id="plan-info" value="{{$current_packageid}}" data-id="{{$package_type}}">
                            @endif

                            <div class="pricing_main bg_section trans_{{$res->plan_id}} @if($res->plan_title=='Standard Plan'){{'standard'}}@elseif($res->plan_title=='Premium Plan'){{'premium'}}@elseif($res->plan_title=='Deluxe Plan'){{'deluxe'}}@else{{'basic'}}@endif @if($res->plan_id==$current_packageid && $package_type=='M') @if($package_status=='A' && $package_subscription=='Y') active_package @endif @endif " id="{{'M'}}{{'-'}}{{$res->plan_id}}">

                                <div class="pricing_titles">
                                    <p>{{$res->plan_title}} @if($res->plan_id==$current_packageid && $package_type=='M')<span class="active-span">- ACTIVE PLAN</span> @endif</p>

                                </div>

                                <div class="download_sections @if($res->plan_id==$current_packageid && $package_type=='M')@if($package_status=='A' && $package_subscription=='Y'){{'show'}}@else{{'hide'}}@endif @endif" id="{{'monthly-active-'}}{{$res->plan_id}}" >
                                    <p>ACTIVE PLAN</p>
                                </div>

                                @if($j=='0')
                                    <div class="payment_sections">
                                        <p>Price</p>
                                    </div>
                                @endif
                            </div>

                            <div class="pricing_main no_border tap_anywhere" data-id="{{$res->plan_id}}">
                                <p class="msg-line">Tap anywhere on the plan to choose</p>
                                <div class="price-checkbox checkbox">
                                    <input type="checkbox" class="custom-control-input  custom-control-input-{{$res->plan_id}} @if($res->plan_id==$current_packageid && $package_type=='M') hide-btn @endif {{'M'}}{{'-'}}{{$res->plan_id}}" id="customCheck_{{$res->plan_id}}" name="packageid" data-val="monthly" value="{{$res->plan_id}}" @if($res->plan_id==$current_packageid && $package_type=='M') disabled @endif>
                                    <span class="checkmarked {{'M'}}{{'-'}}{{$res->plan_id}} @if($res->plan_id==$current_packageid && $package_type=='M'){{'hide-btn'}}@endif"></span>
                                </div>
                                <div class="pricing_title">
                                    <div class="custom-control custom-checkbox mb-3">

                                        <label class="custom-control-label" for="customCheck">{{ $res->plan_name }}<br><small>Per Month</small></label>
                                    </div>
                                </div>
                                <div class="download_section">

                                    <p><span>Download up to <strong>{{ $res->plan_description }}</strong></span>
                                </div>
                                <div class="payment_section">
                                    <p class="price">Price</p>
                                    <p>
                                    <h2><sup>$</sup>{{number_format($res->plan_price, 2)}}</h2><p><span>per month</span></p>
                                    </p>
                                </div>
                            </div>
                            @php
                                $j++;

                            @endphp
                        @endforeach
                    </div>
                @endif

                <div class="custom-price-apply">
                    <input type="text" name="apply_coupon" id="apply_coupon_price" placeholder="Discount Code" value="">
                    <input type="hidden" name="apply_type" id="apply_type" value="2">
                    <button class="custom-button-price" type="submit"> APPLY</button>

                    <p class="custom-warning"></p>
                </div>
                <div class="pricing_last_section">
                    <p>Choose the payment plan that best suits your needs!</p>
                    <p>Early cancellation fee may apply</p>
                </div>
                <div class="pricing_button" id="btn-show" style="display: none;">
                    <a href="javascript:void(0)" class="btn-setting open-form">CONFIRM YOUR SELECTION</a>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="js/price.js?v=0.0.5"></script>

<script>
    $(document).on("click",".custom-button-price",function(){
        var uniqueid=$("#uniqueid").val();

        if(uniqueid == ""){
            $("#exampleModal").modal("show");
            return false
        }
        var couponcode = $('#apply_coupon_price').val();
        var place = $('#apply_type').val();
        var surl = window.location.origin;
        var token=$('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/applycoupon/price',
            type:"POST",
            async: true,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN':token
            },
            data:'couponcode='+couponcode+'&place='+place+'&_token='+token+'&surl='+surl,
            success:function(data){
                if(data.status == '201'){
                    $("#errorMessage").html('<div><strong>INVALID COUPON DETAILS</strong></div>');
                    myFunction();
                } else if(data.status == '200'){
                    $("#errorMessage").html('<div><strong>COUPON APPLIED SUCCESSFULLY</strong></div>');
                    myFunction();

                    location.reload();
                }
            }
        });
    });
</script>

@include('footer')
