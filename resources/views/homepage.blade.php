@include('header')
<style>
    li.inner-parts.ng-scope:hover a.btn-favorites {
        padding: 7px 15px;
        border-radius: 7px;
        color: #fff !important;
        font-size: 17px;
        font-weight: 700;
        bottom: 0px;
        position: absolute;
        display: flex;
        margin-left: 38%;
        margin-top: 8%;
    }
    ul.video-parts li a i.fa.fa-heart-o, ul.video-parts li a i.fa.fa-heart {
        background: #0000;
        color: #e27d06;
    }
</style>
<link rel="stylesheet" href="/css/fox.css?v=0.0.1">
<script src="js/fox.jquery.js?v=0.1.97"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<div class="container">
    <input type="hidden" id="currentsiteid" value="<?php echo $managesite->intmanagesiteid;?>">
    <div class="title" id="myHeader">
        <h5>@{{totalItems}} Item (s)</h5>
        <form>
            <div class="search">
                <input class="form-control gray" id="searchkeyword" type="text"  ng-model="searchkeyword" ng-keyup="searchvideo(searchkeyword,$event)" autocomplete="off"   onkeyup="updateURL()" onClick="disperlist()" value="">
                <ul class="searchresult" style="display:none">
                    <li ng-repeat="tpname in allsearch" ng-click="selectautosearch(tpname.VchCategoryTitle,tpname.childcategory);" onClick="updateURL()" class="fox-list" >@{{tpname.VchCategoryTitle}}  @{{tpname.childcategory}}</li>
                </ul>
                <button class="btn btn-outline" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                <div class="searchplaceholder">Search </div>
            </div>
        </form>
    </div>
</div>
</section >
<section class="navigation-bar" onClick="closelist()">
    <div class="container">
        <div class="advance_s">
            <a class="info-advance-search">Advanced Search (<span>+</span>)</a>
        </div>
        <div class="iconsdsf">
            <ul>
                @foreach ($alltags as $allcategory)
                    <?php
                    $myalltagid = explode(',',$allcategory->tagid);
                    $myallcategorytag = explode(',',$allcategory->tagTitle);
                    $totalitems = count(explode(',',$allcategory->tagTitle));
                    ?>
                    @if ($allcategory->IntId == 1)
                        <?php
                        for($i=0;$i<$totalitems;$i++){ ?>
                        <li>
                            <label class="check-box-container"><?php echo $myallcategorytag[$i];?>
                                <input type="checkbox"  class="racecategory" category="<?php echo $allcategory->VchColumnType; ?>"  ng-model="gender<?php echo $myalltagid[$i];  ?>" id="box-<?php echo $myalltagid[$i];  ?>" ng-click="changegeneder(gender<?php echo $myalltagid[$i];  ?>,<?php echo $myalltagid[$i];  ?>)" value="<?php echo $myalltagid[$i];  ?>">
                                <span class="checkmark"></span>
                            </label>
                        </li>
                        <?php } ?>
                    @else
                        <li>
                            <div class="image-icon">
                                <div class="dropdown dropdownlabel">
                                    <select class="racecategory" category="<?php echo $allcategory->VchColumnType; ?>">
                                        <option value="">Select Your <?php echo $allcategory->VchTitle; ?></option>
                                        <?php
                                        for($i=0;$i<$totalitems;$i++){
                                        ?>
                                        <option value="<?php echo $myalltagid[$i];  ?>"><?php echo $myallcategorytag[$i];  ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
            <div class="video">
                <label class="dropdownlabel info-label mobile-hide-label">Select Type</label>
                <select class="racecategory1" ng-model="videotype" ng-change="changetype(videotype)">
                    <option value="">Select Type</option>
                    <option value="I">Image</option>
                    <option value="V">Video</option>
                </select>
                <label class="dropdownlabel info-label show mobile-hide-label">Number of Show</label>
                <select class="showitemperpage mobile-hide-label">
                    <option value="12">12</option>
                    <option value="16">16</option>
                    <option value="20">20</option>
                    <option value="24">24</option>
                    <option value="36">36</option>
                    <option value="48" Selected>48</option>
                    <option value="72">72</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>
</section>
<section class="banner-image" onClick="closelist()">
    <div class="banner-image1">
        <div class="fluid-container" style="position:relative;">
            <div class="myloadercontainer">
                <div class="loder_innes">
                    <div class="loaderview1">
                        <img src="images/{{$tblthemesetting->gificon}}" alt="img" style="width:auto;height:130px;">
                    </div>
                </div>
            </div>
            <div class="row suggesstion-row">
                <ul class="keyword" ng-if="showkeyword">
                    Did You Mean:
                    <li ng-repeat="tpname in allkeyword" ng-click="selectautosearch(tpname.title);">@{{tpname.title}}<span>,</span></li>
                </ul>
            </div>
            <div class="row content" style="min-height:500px; padding:0 5px;margin:0 !important;">
                <input type="hidden" value="@if(!empty($package)){{'yes'}}@else{{'no'}} @endif" data-value="@if(!empty($package)){{$package->package_count-$package->package_download}}@endif" id="package-detail">
                <input type="hidden" value="" ng-if="tpname.downloadstatus=='out-download'" id="download">
                <input type="hidden" value="" ng-if="tpname.downloadstatus=='in-download'" id="redownload">
                <ul class="video-parts">
                    <li class="inner-parts" ng-repeat="tpname in allvideo">
                        <div class="btn-model @{{tpname.IntId}}_content" ng-if="tpname.applied_bg==''" data-name="@{{tpname.VchTitle}}" data-tags="@{{tpname.videotags}}"  data-image="/showimage/@{{tpname.IntId}}/{{$managesite->intmanagesiteid}}/@{{tpname.VchVideoName}}?=@{{tpname.intsetdefault}}" data-id="@{{tpname.productid}}" data-imgtype="@{{tpname.content_category}}" data-category="@{{tpname.stock_category}}" data-seo="@{{tpname.seo_url}}" data-type="@{{tpname.EnumType}}" data-folder="@{{tpname.VchFolderPath}}" data-download="@{{tpname.downloadstatus}}" cart-status="@{{tpname.cartstatus}}"fav-status="@{{tpname.favoritesstatus}}" video-id="@{{tpname.IntId}}" transparent-status="@{{tpname.transparent}}" >
                            <div class="hover-play-icon group1" ng-if="tpname.EnumType=='V'"   >
                                <img src="{{ asset('images') }}/{{$tblthemesetting->vchvideoicon}}" alt="img">
                            </div>
                            <div class="proper_fit" ng-if="tpname.EnumUploadType=='W'">
                                <div class="cnrflash" ng-if="tpname.content_category=='1'">
                                    <div class="cnrflash-inner first second standard" ng-if="tpname.stock_category=='1'">
                                        <span  class="cnrflash-label">Standard</span>
                                    </div>
                                    <div class="cnrflash-inner first second custom" ng-if="tpname.stock_category=='2'">
                                        <span  class="cnrflash-label">Custom</span>
                                    </div>
                                </div>
                                <div class="cnrflash" ng-if="tpname.content_category=='2'">
                                    <div class="cnrflash-inner first second premium" ng-if="tpname.stock_category=='1'">
                                        <span  class="cnrflash-label">Premium</span>
                                    </div>
                                    <div class="cnrflash-inner first second custom" ng-if="tpname.stock_category=='2'">
                                        <span  class="cnrflash-label">Custom</span>
                                    </div>
                                </div>
                                <div class="cnrflash " ng-if="tpname.content_category=='3'">
                                    <div class="cnrflash-inner first second ultra_premium" ng-if="tpname.stock_category=='1'">
                                        <span  class="cnrflash-label">Deluxe</span>
                                    </div>
                                    <div class="cnrflash-inner first second custom" ng-if="tpname.stock_category=='2'">
                                        <span  class="cnrflash-label">Custom</span>
                                    </div>
                                </div>
                                <span class="colorwhite" style="color:#fff;">@{{tpname.VchTitle}}</span>
                                <a ng-if="tpname.EnumType=='I'" class="group1">
                                    <div class="image" ng-if="tpname.Vchcustomthumbnail!=''">
                                        <img ng-if="tpname.Vchcustomthumbnail!=''" src="/@{{tpname.VchFolderPath}}/@{{tpname.Vchcustomthumbnail}}" >
                                        {{--                                    <img ng-if="tpname.Vchcustomthumbnail!=''" src="@{{tpname.vchcacheimages}}">--}}
                                    </div>
                                    <div class="image" ng-if="tpname.Vchcustomthumbnail==''">
                                        <img ng-if="tpname.vchcacheimages==''" src="/resize1/showimage/@{{tpname.IntId}}/{{$managesite->intmanagesiteid}}/@{{tpname.VchResizeimage}}/?=@{{tpname.intsetdefault}}" >
                                        {{--                                    <img ng-if="tpname.vchcacheimages==''" src="@{{tpname.vchcacheimages}}">--}}
                                        <img ng-if="tpname.vchcacheimages!=''" src="/resize1/showimage/@{{tpname.IntId}}/{{$managesite->intmanagesiteid}}/@{{tpname.VchResizeimage}}/?=@{{tpname.intsetdefault}}" class="content-image" >
                                        {{--                                    <img ng-if="tpname.vchcacheimages!=''" src="@{{tpname.vchcacheimages}}">--}}
                                    </div>
                                </a>
                                <a href="@{{tpname.VchFolderPath}}/@{{tpname.VchVideothumbnail}}" ng-if="tpname.EnumType=='V'" >
                                    <div class="image" ng-if="tpname.Vchcustomthumbnail!=''" >
                                        <!--<img ng-if="tpname.Vchcustomthumbnail!=''" src="@{{tpname.VchFolderPath}}/@{{tpname.Vchcustomthumbnail}}" >-->
                                        <img ng-if="tpname.Vchcustomthumbnail!=''" src="/resize2/showimage/@{{tpname.IntId}}/{{$managesite->intmanagesiteid}}/@{{tpname.Vchcustomthumbnail}}/?={{rand(10,100)}}" >
                                    </div>
                                    <div class="image" ng-if="tpname.Vchcustomthumbnail==''">
                                        <!--<img ng-if="tpname.Vchcustomthumbnail==''" src="@{{tpname.VchFolderPath}}/@{{tpname.VchVideothumbnail}}"> -->
                                        <img ng-if="tpname.Vchcustomthumbnail==''" src="/resize2/showimage/@{{tpname.IntId}}/{{$managesite->intmanagesiteid}}/@{{tpname.VchVideothumbnail}}/?={{rand(10,100)}}">
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="btn-model" ng-if="tpname.applied_bg!= ''" data-name="@{{tpname.VchTitle}}" data-tags="@{{tpname.videotags}}"  data-image="showimg/@{{tpname.userid}}/@{{tpname.imgname}}" data-id="@{{tpname.productid}}" data-imgtype="@{{tpname.content_category}}" data-category="@{{tpname.stock_category}}" data-seo="@{{tpname.seo_url}}" data-type="@{{tpname.EnumType}}" data-folder="@{{tpname.VchFolderPath}}" data-download="@{{tpname.downloadstatus}}" cart-status="@{{tpname.cartstatus}}"fav-status="@{{tpname.favoritesstatus}}" video-id="@{{tpname.IntId}}" transparent-status="@{{tpname.transparent}}" applied-bg="@{{tpname.applied_bg}}">
                            <div class="hover-play-icon group1" ng-if="tpname.EnumType=='V'"   >
                                <img src="{{ asset('images') }}/{{$tblthemesetting->vchvideoicon}}" alt="img">
                            </div>
                            <div class="proper_fit" ng-if="tpname.EnumUploadType=='W'">
                                <div class="cnrflash" ng-if="tpname.content_category=='1'">
                                    <div class="cnrflash-inner first second standard" ng-if="tpname.stock_category=='1'">
                                        <span  class="cnrflash-label">Standard</span>
                                    </div>
                                    <div class="cnrflash-inner first second custom" ng-if="tpname.stock_category=='2'">
                                        <span  class="cnrflash-label">Custom</span>
                                    </div>
                                </div>
                                <div class="cnrflash" ng-if="tpname.content_category=='2'">
                                    <div class="cnrflash-inner first second premium" ng-if="tpname.stock_category=='1'">
                                        <span  class="cnrflash-label">Premium</span>
                                    </div>
                                    <div class="cnrflash-inner first second custom" ng-if="tpname.stock_category=='2'">
                                        <span  class="cnrflash-label">Custom</span>
                                    </div>
                                </div>
                                <div class="cnrflash " ng-if="tpname.content_category=='3'">
                                    <div class="cnrflash-inner first second ultra_premium" ng-if="tpname.stock_category=='1'">
                                        <span  class="cnrflash-label">Deluxe</span>
                                    </div>
                                    <div class="cnrflash-inner first second custom" ng-if="tpname.stock_category=='2'">
                                        <span  class="cnrflash-label">Custom</span>
                                    </div>
                                </div>
                                <span class="colorwhite" style="color:#fff;">@{{tpname.VchTitle}}</span>
                                <a ng-if="tpname.EnumType=='I'" class="group1">
                                    <div class="image"  ng-if="tpname.Vchcustomthumbnail!=''">
                                        <img ng-if="tpname.Vchcustomthumbnail!=''" src="/@{{tpname.VchFolderPath}}/@{{tpname.Vchcustomthumbnail}}" >
                                    </div>
                                    <div class="image" ng-if="tpname.Vchcustomthumbnail==''">
                                        <img ng-if="tpname.vchcacheimages==''" src="/resize1/showimage/@{{tpname.IntId}}/{{$managesite->intmanagesiteid}}/@{{tpname.VchResizeimage}}/?=@{{tpname.intsetdefault}}" >
                                        <img ng-if="tpname.vchcacheimages!=''" src="/resize1/showimage/@{{tpname.IntId}}/{{$managesite->intmanagesiteid}}/@{{tpname.VchResizeimage}}/?=@{{tpname.intsetdefault}}" class="content-image" >
                                    </div>
                                </a>
                                <a href="@{{tpname.VchFolderPath}}/@{{tpname.VchVideothumbnail}}" ng-if="tpname.EnumType=='V'" >
                                    <div class="image" ng-if="tpname.Vchcustomthumbnail!=''" >
                                        <!--<img ng-if="tpname.Vchcustomthumbnail!=''" src="@{{tpname.VchFolderPath}}/@{{tpname.Vchcustomthumbnail}}" >-->
                                        <img ng-if="tpname.Vchcustomthumbnail!=''" src="/resize2/showimage/@{{tpname.IntId}}/{{$managesite->intmanagesiteid}}/@{{tpname.Vchcustomthumbnail}}/?={{rand(10,100)}}" >
                                    </div>
                                    <div class="image" ng-if="tpname.Vchcustomthumbnail==''">
                                        <!--<img ng-if="tpname.Vchcustomthumbnail==''" src="@{{tpname.VchFolderPath}}/@{{tpname.VchVideothumbnail}}"> -->
                                        <img ng-if="tpname.Vchcustomthumbnail==''" src="/resize2/showimage/@{{tpname.IntId}}/{{$managesite->intmanagesiteid}}/@{{tpname.VchVideothumbnail}}/?={{rand(10,100)}}">
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!--
                           <a href="javascript:void(0)" id="download_@{{tpname.IntId}}" class="btn-download btn-setting" data-val="@{{tpname.productid}}">Download</a>

                           <a href="javascript:void(0)" ng-if="tpname.downloadstatus=='in-download'" id="redownload_@{{tpname.IntId}}" class="cart-btn btn-redownload btn-setting in-download" data-val="@{{tpname.productid}}">Download</a>

                           <a  href="javascript:void(0)" id="favorites_@{{tpname.IntId}}" data-value="@{{tpname.productid}}" class="btn-favorites" data-toggle="tooltip" data-status="@{{tpname.favoritesstatus}}" data-placement="right" title="Add to Collection" > <i class="@{{tpname.favoriteshtml}}" aria-hidden="true"></i> </a>
                           <a ng-if="tpname.downloadstatus=='out-download'" href="javascript:void(0)" id="addToCart_@{{tpname.IntId}}" class="btn-wishlist btn-setting" data-value="@{{tpname.productid}}"  data-status="@{{tpname.cartstatus}}"><i class="@{{tpname.carthtml}}" aria-hidden="true"></i></a>
                           <a ng-if="tpname.downloadstatus=='in-download'" href="javascript:void(0)" id="download_@{{tpname.IntId}}" class="cart-btn btn-download btn-setting" data-val="@{{tpname.productid}}">Download</a>
                           -->
                        <a ng-if="tpname.stock_category=='1'" href="javascript:void(0)" id="addToCart_@{{tpname.IntId}}" class="cart-btn btn-wishlist btn-setting" data-value="@{{tpname.productid}}"  data-status="@{{tpname.cartstatus}}">@{{tpname.carthtml}}</a>
                        <a ng-if="tpname.stock_category=='2'" href="javascript:void(0)" id="addToCart_@{{tpname.IntId}}" class="cart-btn btn-wishlist btn-setting stock-btn" data-value="@{{tpname.productid}}"  data-status="@{{tpname.cartstatus}}">@{{tpname.carthtml}}</a>
                    </li>
                </ul>
            </div>
            <pagination total-items="totalItems"  ng-change="pageChanged(currentPage)" ng-model="currentPage" max-size="maxSize" class="pagination" boundary-links="true" rotate="false" num-pages="numPages" items-per-page="itemsPerPage"></pagination>
        </div>
    </div>
</section>
<!-- Login Register or Download popup -->
@include('footer')
<script type="text/javascript">
    /* $(document).ready(function(){

    $( document ).on( "doubletap", "#bigimagesize", function() {
    // alert('doubletap');

    });
    }); */
    window.onscroll = function() {my_Functionss()};

    var header = document.getElementById("myHeader");
    var sticky = 250;



    function my_Functionss() {
        //alert(window.pageYOffset);
        if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }
    }

    <?php if(!empty(Session::has('changepassword'))){ ?>
    $(document).ready(function(){
        $("#errorMessage").html('<div class=""><strong>Password changed successfully</strong> </div>');
        myFunction();
    });
    <?php } ?>

    $('.searchplaceholder').click(function() {
        $(this).siblings('input').focus();
    });
    $('.form-control').focus(function() {
        $(this).siblings('.searchplaceholder').hide();
    });
    $('.form-control').blur(function() {
        var $this = $(this);
        //alert($this);
        if ($this.val().length == 0)
            $(this).siblings('.searchplaceholder').show();
    });
    $('.form-control').blur();
    /*
    $(document).ready(function(){
        var $this = $('#searchkeyword').val;
        //alert($this);
      if ($this.val().length == 0)
        $(this).siblings('.searchplaceholder').show();
    }); */
</script>
