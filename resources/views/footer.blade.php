<?php
use App\Http\Controllers\HomeController;
$Plans = HomeController::plans();
$background = HomeController::background();
$getapidetail = HomeController::getapidetail();
$managesite = HomeController::managesite2();
?>

<div id="footer">
    <div class="container">
        <div class="footer">
            <div class="f-left">
                <h3>  Share </h3>
                <ul>
                    <li>
                        <a href="#">
                            <svg width="22" height="22" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21.8337 11C21.8337 5.01996 16.9803 0.166626 11.0003 0.166626C5.02033 0.166626 0.166992 5.01996 0.166992 11C0.166992 16.2433 3.89366 20.6091 8.83366 21.6166V14.25H6.66699V11H8.83366V8.29163C8.83366 6.20079 10.5345 4.49996 12.6253 4.49996H15.3337V7.74996H13.167C12.5712 7.74996 12.0837 8.23746 12.0837 8.83329V11H15.3337V14.25H12.0837V21.7791C17.5545 21.2375 21.8337 16.6225 21.8337 11Z" fill="#5B5C5C"/>
                            </svg>
                            Facebook
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <svg width="20" height="10" viewBox="0 0 20 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 0H12C11.45 0 11 0.45 11 1C11 1.55 11.45 2 12 2H15C16.65 2 18 3.35 18 5C18 6.65 16.65 8 15 8H12C11.45 8 11 8.45 11 9C11 9.55 11.45 10 12 10H15C17.76 10 20 7.76 20 5C20 2.24 17.76 0 15 0ZM6 5C6 5.55 6.45 6 7 6H13C13.55 6 14 5.55 14 5C14 4.45 13.55 4 13 4H7C6.45 4 6 4.45 6 5ZM8 8H5C3.35 8 2 6.65 2 5C2 3.35 3.35 2 5 2H8C8.55 2 9 1.55 9 1C9 0.45 8.55 0 8 0H5C2.24 0 0 2.24 0 5C0 7.76 2.24 10 5 10H8C8.55 10 9 9.55 9 9C9 8.45 8.55 8 8 8Z" fill="#5B5C5C"/>
                            </svg>
                            Copy Link
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <svg width="21" height="17" viewBox="0 0 21 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.77289 16.7344C14.3329 16.7344 18.4541 10.3859 18.4541 4.91406C18.4541 4.72813 18.4541 4.54219 18.4541 4.38281C19.2679 3.79844 19.9504 3.05469 20.5016 2.23125C19.7666 2.55 18.9791 2.78906 18.1391 2.89531C18.9791 2.39062 19.6354 1.56719 19.9504 0.584375C19.1629 1.0625 18.2704 1.40781 17.3516 1.59375C16.5904 0.770313 15.5404 0.265625 14.3591 0.265625C12.0754 0.265625 10.2379 2.125 10.2379 4.43594C10.2379 4.75469 10.2641 5.07344 10.3429 5.39219C6.93039 5.20625 3.91164 3.55938 1.86414 1.03594C1.52289 1.64688 1.31289 2.36406 1.31289 3.10781C1.31289 4.54219 2.04789 5.81719 3.15039 6.56094C2.46789 6.53438 1.83789 6.34844 1.28664 6.02969C1.28664 6.05625 1.28664 6.05625 1.28664 6.08281C1.28664 8.10156 2.70414 9.775 4.59414 10.1469C4.25289 10.2531 3.88539 10.3062 3.51789 10.3062C3.25539 10.3062 2.99289 10.2797 2.75664 10.2266C3.28164 11.8734 4.80414 13.0688 6.58914 13.1219C5.17164 14.2375 3.41289 14.9016 1.49664 14.9016C1.15539 14.9016 0.840391 14.875 0.525391 14.8484C2.31039 16.0438 4.46289 16.7344 6.77289 16.7344Z" fill="#5B5C5C"/>
                            </svg>
                            Twitter
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM19.6 8.25L12.53 12.67C12.21 12.87 11.79 12.87 11.47 12.67L4.4 8.25C4.15 8.09 4 7.82 4 7.53C4 6.86 4.73 6.46 5.3 6.81L12 11L18.7 6.81C19.27 6.46 20 6.86 20 7.53C20 7.82 19.85 8.09 19.6 8.25Z" fill="#5B5C5C"/>
                            </svg>
                            Email
                        </a>
                    </li>
                </ul>
            </div>
            <div class="f-right">
                <h3> Navigate </h3>
                <ul>
                    <li><a href="/">Home </a></li>
                    <li><a href="/support">Support </a></li>
                    <li><a href="/about">About </a></li>
                    <li><a href="/custom">Custom </a></li>
                    <li><a href="/pricing">Pricing </a></li>
                    <li><a href="/cart">Shopping Cart </a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <ul>
                <li><a href="/privacypolicy">Privacy Policy  </a></li>
                <li><a href="/termscondition">Term & Conditions  </a></li>
            </ul>
        </div>
    </div>
</div>
<div id="mySidenav" class="sidenav">
    <input type="hidden" id="productid" @if(!empty($productid)) value="{{$productid}}" @endif  >
    <div class="side_section">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="side_bar">
            <h4>ready to purchase ?</h4>
            <p>it's simple. Your image start downloading once you choose your plan. Our worry-free standard license covers the most common use of this image.</p>
            <h4 style="font-size:13px">Value-priced annual plans</h4>
            <p>Save with an annual plan. charged monthly.</p>
            <div class="free_sec">
                <p><span>free</span></p>
                <p><strong>Ready To Purchase</strong></p>
            </div>
        </div>
        <form action="/buynow" method="post" id="side-plan-form">
            @csrf
            <ul class="pricing-list-sidebar">
                @foreach($Plans  as $planfirst)
                    @if($planfirst->plan_purchase == 'M')
                        <li class="">
                            <input type="radio" class="plisting" id="f-{{$planfirst->plan_id}}" name="packageid" value="{{$planfirst->plan_id}}" required>
                            <label for="f-{{$planfirst->plan_id}}">
                                <p class="plan-name">{{ $planfirst->plan_name }}</p>
                                <p class="plan-descrption">{{ $planfirst->plan_description }}</p>
                            </label>
                            <span for="f-price" class="plan-price">$ {{$planfirst->plan_price / $planfirst->plan_download}} <br>
               <small>par image</small></span>
                            <div class="check"></div>
                        </li>
                    @endif
                @endforeach
            </ul>
            <p style="clear: both; font-size:13px;">*Billed monthly for one year. Early cancellation fee may apply.</p>
            <ul class="pricing-list-sidebar border_sec">
                <h3>One Time Purchase</h3>
                @foreach($Plans  as $plansecond)
                    @if($plansecond->plan_purchase == 'O')
                        <li style="clear: both" class="">
                            <input type="radio" class="plisting" id="f-{{$plansecond->plan_id}}" name="packageid" value="{{$plansecond->plan_id}}" required>
                            <label for="f-{{$plansecond->plan_id}}">
                                <p class="plan-name">{{ $plansecond->plan_name }}</p>
                                <p class="plan-descrption">{{ $plansecond->plan_description }}</p>
                            </label>
                            <div class="check"></div>
                            <span for="f-price" class="plan-price">$ {{$plansecond->plan_price / $plansecond->plan_download}} <br><small>par image</small></span>
                        </li>
                    @endif
                @endforeach
            </ul>
            <div class="buy_button">
                <button type="submit" class="btn-cart">buy and download</button>
            </div>
        </form>
    </div>
</div>
<!-- Login Register or Download popup -->
<div class="big-image" id="bigimg">
    <input type="hidden" id="downloadstatus"  value="0">
    <div class="row uppr bgpopup-color">
        <div class="col-md-12">
            <div class="pop-heading">
                <div class="rlt-key hide-on-mobile"></div>
                <h3 class="bigimagename hide-on-mobile">Image Name</h3>
                <div class="title_close">
                    <span class="close_icon" onclick="closebigForm()">&#10005;</span>
                </div>
            </div>
            <h3 class="bigimagename bigimagename-3 hide-on-desktop">Image Name</h3>
            <p class="bigimagename-p hide-on-desktop">Double-tap on the image to zoom</p>
            <div class="image-center" style="min-height: 400px !important;">
                <div class="myloadercontainer2" id="loader">
                    <div class="loder_innes">
                        <div class="loaderview1">
                            <img src="/images/{{$managesite->gificon}}" alt="img" style="width:auto !important;height:130px;">
                        </div>
                    </div>
                </div>
                <div class="imgs-setup" id="imagepart">
                    <div class="share-middle">
                        <span id="image-desc" class="image-desc"> </span>
                        <!--<img src="/img/share-apple.png" onclick="showCopy()">-->
                    </div>
                    <div class="bigimgcontainer" id="zoom-container" >
                        <!--	<input type="checkbox" id="zoomCheck">
                           <label for="zoomCheck">-->
                        <img src="" id="bigimagesize" onload="loadImage()" ondragstart="return false" >
                        <!--	</label>-->
                    </div>
                    <button class="" id="zoomin">
                        <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="33" viewBox="0 0 24 24" width="33">
                            <g>
                                <g>
                                    <rect fill="none" height="33" width="33" y="0"/>
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M11.5,8.5h-1v-1c0-0.55-0.45-1-1-1s-1,0.45-1,1v1h-1c-0.55,0-1,0.45-1,1c0,0.55,0.45,1,1,1h1v1c0,0.55,0.45,1,1,1 s1-0.45,1-1v-1h1c0.55,0,1-0.45,1-1C12.5,8.95,12.05,8.5,11.5,8.5z"/>
                                    <path d="M14.73,13.31c1.13-1.55,1.63-3.58,0.98-5.74c-0.68-2.23-2.57-3.98-4.85-4.44C6.21,2.2,2.2,6.22,3.14,10.86 c0.46,2.29,2.21,4.18,4.44,4.85c2.16,0.65,4.19,0.15,5.74-0.98l5.56,5.56c0.39,0.39,1.02,0.39,1.41,0l0,0 c0.39-0.39,0.39-1.02,0-1.41L14.73,13.31z M9.5,14C7.01,14,5,11.99,5,9.5S7.01,5,9.5,5S14,7.01,14,9.5S11.99,14,9.5,14z"/>
                                </g>
                            </g>
                        </svg>
                    </button>
                    <button class="hide-btn" id="zoomout">
                        <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="33" viewBox="0 0 24 24" width="33">
                            <g>
                                <g>
                                    <rect fill="none" height="33" width="33" y="0"/>
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M11,8.5H8c-0.55,0-1,0.45-1,1c0,0.55,0.45,1,1,1h3c0.55,0,1-0.45,1-1C12,8.95,11.55,8.5,11,8.5z"/>
                                    <path d="M14.73,13.31c1.13-1.55,1.63-3.58,0.98-5.74c-0.68-2.23-2.57-3.98-4.85-4.44C6.21,2.2,2.2,6.22,3.14,10.86 c0.46,2.29,2.21,4.18,4.44,4.85c2.16,0.65,4.19,0.15,5.74-0.98l5.56,5.56c0.39,0.39,1.02,0.39,1.41,0l0,0 c0.39-0.39,0.39-1.02,0-1.41L14.73,13.31z M9.5,14C7.01,14,5,11.99,5,9.5S7.01,5,9.5,5S14,7.01,14,9.5S11.99,14,9.5,14z"/>
                                </g>
                            </g>
                        </svg>
                    </button>
                </div>
                <div class="imgs-setup" id="videopart">
                    <div class="share-middle">
                        <span id="image-desc2" class="image-desc"> </span>
                        <!--<img src="/img/share-apple.png" >-->
                    </div>
                    <div class="bigvideocontainer" >
                        <video id="newvideo" controls>
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
                <div class="share-link" style="display:none;">
                    <div class="copy-text">
                        <input type="text" value="Hello World" id="myInput" readonly >
                        <div class="tooltips">
                            <button onclick="CopyFunction()" onmouseout="outFunc()">
                                <span class="tooltiptext" id="myTooltip">Copy to clipboard</span>
                                <i class="fa fa-copy"></i>
                            </button>
                        </div>
                        <div class="close-copy" onclick="closeCopy()">
                            <span>&#x2715; </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pop-footer">
                <div class="img-btm hide-on-mobile">
                    <p>Not quite what you are looking for? </p>
                    <a href="/custom">Request Custom Graphics</a>
                </div>
                <div class="rlt-key hide-on-desktop"></div>
                <div class="main-icon">
                    <ul>
                        </li>
                        <li id="add-favli">
                        </li>
                        <li class="popup-dropdown link-list" id="share-list">
                        </li>
                        <li class="popup-dropdown background-list" id="background-list">
                        </li>
                        <li class="popup-dropdown info-list" id="info-list">
                        </li>
                    </ul>
                </div>
                <div class="pop-heading hide-on-desktop">
                    <div class="img-btm ">
                        <p>Not quite what you are looking for? </p>
                        <a href="/custom">Request Custom Graphics</a>
                    </div>
                </div>
                <div class="img-btm login-text" id="login-link">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="showed dzoom">
    <div class="overlay"></div>
    <div class="img-show">
        <span>X</span>
        <!--<span class="image-title"></span>-->
        <img src="">
    </div>
</div>
<!---------------------------------price popup---------------------------------------------->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg login-modal signup-modal checkoutModal billing-form" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="checkout-container">
                        <form class="method_sec creditly-card-form" method="post" data-stripe-publishable-key="{{ $getapidetail->stripe_key }}" data-cc-on-file="false" autocomplete="off" >
                            <input type="hidden" id="old_packageid" name="old_packageid" value="">
                            <input type="hidden" id="old_packagetype" name="old_packagetype" value="">
                            <div class="row">
                                <div class="col-md-5">
                                    <h6>Billing Address</h6>
                                    <div class="formBox border">
                                        <div class="billing_first">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="inputBox focus">
                                                        <div class="inputText">Country  <span>*</span></div>
                                                        <select class="input" name="country" id="mySelect" required>
                                                            <option></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="inputBox focus">
                                                        <div class="inputText">Address line 1 <span>*</span></div>
                                                        <input type="text"  class="input" id="address_line1" name="address_line1" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="inputBox focus">
                                                        <div class="inputText">Address Line 2 <span></span></div>
                                                        <input type="text"   class="input" id="address_line2" name="address_line2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="inputBox focus">
                                                        <div class="inputText">City <span>*</span></div>
                                                        <input type="text"  class="input" id="city" name="city" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="inputBox focus">
                                                        <div class="inputText">State / Province / Region <span>*</span></div>
                                                        <input type="text"  class="input" id="state" name="state" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="inputBox focus">
                                                        <div class="inputText">Zip / Postal Code <span>*</span></div>
                                                        <input type="text" class="input" id="zip" name="zip" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7 summary">
                                    <h6>Payment Method</h6>
                                    <div class="method border">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="inputBox focus">
                                                    <div class="inputText">Cardholder Name <span>*</span></div>
                                                    <input type="text"  class="input" id="cardname" name="cardname">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="text" class="col-sm-12 col-form-label">Credit Card Number <span>*</span></label>
                                            <input type="text" class="form-control credit credit-card-number" name="cardnumber" id="cardnumber" inputmode="numeric" autocomplete="cc-number" autocompletetype="cc-number" x-autocompletetype="cc-number" placeholder="&#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149;">
                                            <div class="pay_img">
                                                <img src="/public/img/payment.png">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="text" class="col-sm-12 col-form-label">Expiration Month <span>*</span></label>
                                                <input type="text" class="form-control card-expiry-month numeric" name="expirationdate" id="expirationdate" placeholder="MM" maxlength="2">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="text" class="col-sm-12 col-form-label">Expiration Year <span>*</span></label>
                                                <input type="text" class="form-control card-expiry-year numeric" name="expirationYeardate" id="expirationYeardate" placeholder="YY" maxlength="2">
                                            </div>
                                            <div class="form-group col-md-4" style="margin-bottom: 30px;">
                                                <label for="text" class="col-sm-12 col-form-label">CVV <span>*</span></label>
                                                <input type="text" class="form-control security-code" id="cvv" name="cvv" placeholder="123" placeholder="&#149;&#149;&#149;" required>
                                                <div class="pay_img credit_im">
                                                    <img src="/public/img/credit_card.png">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-type"></div>
                                    </div>
                                    <div class="order-summary">
                                        <div class="order-inner">
                                            <h6>Order Summary</h6>
                                            <div class="details">
                                                <div class="order_sec">
                                                    <!--<p><strong>365-day Image On Demond, with 2 Standard License Download</strong></p>
                                                       <p>Standard License<br>Downloads expire within a year of purchase</p>
                                                       </div>
                                                       <div class="order_pay">
                                                       <p><strong>$29</strong></p>
                                                       </div>-->
                                                    <p id="plan-name">Basic Plan - Monthly Payment</p>
                                                </div>
                                                <div class="order_pay">
                                                    <p id="plan-price"><strong>$0.14 / Month</strong></p>
                                                    <div class="custom-discount" style="display:none">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" >
                                    <div class="check_com">
                                        <p>Secure checkout. For your convenience we will store your encrypted payment information<br> for future orders. Manage your payment information in your Account Details.</p>
                                    </div>
                                    <div class="buy_button detail">
                                        <button type="submit" class="submit btn-cart" id="complete-checkout">Complete Checkout</button>
                                        <!--<button type="submit" class="submit btn-cart" id="complete-checkout"><i class="fa fa-refresh fa-spin"></i><span>Loading</span></button>-->
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="thank-container" style="display:none">
                        <h1 class="heading_main">Thank You</h1>
                        <div class="bd-highlight">
                            <div class="check-mark">&#10003;</div>
                            <h2>You're all set!</h2>
                            <!--<p class="transaction">Transaction Id: <span id="transaction_id"> txn_1GAaopBGINKMPLs3Ix01nF8U</span></p>-->
                            <p>
                                Thanks for being awesome,<br> we hope you enjoy your purchase!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- confirm-----modal-dialog -->
<div class="modal fade confirm-modal apply-modal" tabindexrole="dialog" id="custom-modal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <!-- modal-body -->
            <div class="modal-body" id="modal-body"></div>
            <!-- modal-footer -->
            <div class="modal-footer" id="modal-footer">
                <button type="button" class="btn btn-default" id="ok-btn">Yes</button>
                <button type="button" class="btn btn-default" id="no-btn">No</button>
                <button type="button" class="btn btn-default" id="cancel-btn">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade confirm-modal apply-modal" tabindexrole="dialog" id="changeplan-modal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <!-- modal-body -->
            <div class="modal-body">Plan change will occur on the next planned payment.
                Do you want to proceed?
            </div>
            <!-- modal-footer -->
            <div class="modal-footer" id="modal-footer">
                <button type="button" class="btn btn-default" id="plan-ok-btn">Yes</button>
                <button type="button" class="btn btn-default" id="plan-no-btn">No</button>
            </div>
        </div>
    </div>
</div>
<!------------------------------price popup-------------------------------->
<script src='https://www.google.com/recaptcha/api.js' async defer></script>
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
    $(document).ready(function(){
        $('.customform_contect').slick({
            dots: false,
            arrows: false,
            infinite: true,
            speed: 300,
            autoplaySpeed: 7000,
            slidesToShow: 1,
            centerMode: true,
            autoplay: true,
            fade: true,
            cssEase: 'linear',
            adaptiveHeight: false
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/utils/Draggable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.11.5/TweenMax.min.js"></script>
<script src="/js/jquery.doubletap.js"></script>
<script src="/js/creditly.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="/js/jquery.checkout.js?v=1.3.1"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link href="https://swisnl.github.io/jQuery-contextMenu/dist/jquery.contextMenu.css" rel="stylesheet" type="text/css" />
<script src="https://swisnl.github.io/jQuery-contextMenu/dist/jquery.contextMenu.js" type="text/javascript"></script>
<script src="/js/jscolor.js"></script>
<script type="text/javascript" src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js" integrity="sha512-0bEtK0USNd96MnO4XhH8jhv3nyRF0eK87pJke6pkYf3cM0uDIhNJy9ltuzqgypoIFXw3JSuiy04tVk4AjpZdZw==" crossorigin="anonymous"></script>
<script type="text/javascript" src="/js/imgViewer2.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="/js/jqueryFooter.js?v=0.0.1"></script>
</div>
</body>
</html>
