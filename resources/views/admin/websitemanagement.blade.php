@include('admin/admin-header')
<div class="admin-page-area">
    @include('admin/admin-logout')
    <div class="">
        <style>
            .addnew {
                background: #3c8dbc none repeat scroll 0 0;
                border-radius: 3px;
                color: #fff;
                font-size: 15px;
                height: 40px;
                line-height: 40px;
                margin-bottom: 11px;
                text-align: center;
                width: 94px;
            }
            .watemarklogos {
                display: inline-flex;
                margin-left: 2px;
            }
            .addnew {
                float: right;
            }
            .watemarklogos {
                display: inline-flex;
                margin-left: 2px;
                margin-bottom: 20px;
            }
            .tabwatermarkss {
                background: #3c8dbc none repeat scroll 0 0;
                border-radius: 3px;
                color: #fff;
                font-size: 15px;
                height: 40px;
                line-height: 40px;
                margin-bottom: 11px;
                text-align: center;
                width: 94px;
                padding: 10px;
                border-radius: 8px;
                margin-right: 2px;
                border-radius: 0 !important;
            }
            table.table-condensed th {
                background: #fff !important;
                color: #000;
            }
            [class^="icon-"], [class*=" icon-"] {
                display: inline-block;
                width: 14px;
                height: 14px;
                *margin-right: .3em;
                line-height: 14px;
                vertical-align: text-top;
                background-image: url(../img/glyphicons-halflings.png);
                background-position: 14px 14px;
                background-repeat: no-repeat;
                margin-top: 1px;
            }
            .icon-arrow-right {
                background-position: -264px -96px;
            }
            .icon-arrow-left {
                background-position: -240px -96px;
            }
            a.tabwatermarkss.active {
                background: #000 !important;
                color: #fff !important;
            }
        </style>
        <div class="col-md-12 mar-auto">
            <div class="back-strip top-side srch-byr">
                <div class="inner-top">
                    Manage Website
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12" style="padding-bottom: 35px;">
                <!--<a href="/admin/websitemanagement/create" class="addnew" style="margin-left:10px; width:200px;">
                   <i class="fa fa-refresh" aria-hidden="true"></i>
                     Refresh watermark Images </a>-->
                @if(strstr($access, "1"))
                    <a href="/admin/websitemanagement/create" class="addnew" >
                        <i class="fa fa-plus-square"></i>  Add New </a>
            </div>
            @endif
            <div style="padding:30px 50px 0">
                <div class="col-md-12">
                    <div class="col-md-9" >
                        <div class="watemarklogos" style="width:100%">
                            <div class="tabwatermarks">
                                <a href="javascript:void(0);" class="tabwatermarkss active" id="large" logotype="prdctlargegogo">Watermark Logo for Large image</a>
                            </div>
                            <div class="tabwatermarks">
                                <a href="javascript:void(0);" class="tabwatermarkss" id="small" logotype="prdctsmalgogo">Watermark Logo for Small image</a>
                            </div>
                            <div class="tabwatermarks">
                                <a href="javascript:void(0);" class="tabwatermarkss" id="video" logotype="prdctsmalgogos">Watermark Logo for  Video</a>
                            </div>
                            <div class="tabbackground">
                                <a href="javascript:void(0);" class="tabwatermarkss" id="background" logotype="prdctbackground">Backgrounds</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <form method="get" style="padding:0">
                            <select style="height: 39px;" name="search" class="form-control" onchange="this.form.submit()">
                                <option value="">Select Domain</option>
                                @foreach($managesites as $managesite)
                                    <option value="{{$managesite->intmanagesiteid}}" @if(!empty($search)) @if($search==$managesite->intmanagesiteid) Selected  @endif @endif >{{$managesite->txtsiteurl}}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="searchtags prdctlargegogo">
                <div class="ful-top gap-sextion"  id="product_container">
                    <div class="col-md-12">
                        <?php
                        if(!$watermark->isEmpty()){
                        ?>
                        <table class="table-bordered" width="80%;">
                            <thead>
                            <tr>
                                <th style="text-align:center;">
                                    Watermark Logo
                                </th>
                                <th style="text-align:center;">
                                    Type
                                </th>
                                <th style="text-align:center;">
                                    Transparency
                                </th>
                                <th style="text-align:center;">
                                    Domain
                                </th>
                                <th style="text-align:center;">
                                    Mark Default
                                </th>
                                @if(strstr($access, "1"))
                                    <th style="text-align:center;">
                                        Actions
                                    </th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($watermark as $mylogo){

                            ?>
                            <tr>
                                <td>
                                    <img src="/upload/watermark/<?php echo $mylogo->vchwatermarklogoname; ?>" width="100px;">
                                </td>
                                <td>
                                    <?php
                                    if($mylogo->vchtype=="L"){
                                        echo "Large Thumbnail";
                                    }else {
                                        echo "Small Thumbnail";
                                    }

                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $mylogo->vchtransparency; ?>
                                </td>
                                <td>
                                    <?php
                                    echo $mylogo->txtsiteurl; ?>
                                </td>
                                <td>
                                    <?php
                                    $mystatus = $mylogo->enumstatus;
                                    if($mystatus=='A'){
                                        $check = "checked";
                                    }else {
                                        $check = "";

                                    }
                                    ?>
                                    <input type="checkbox" id="box-<?php echo $mylogo->Intwatermarklogoid; ?>" class="racecategory racecategorycheckbox" name="markdefault" value="<?php echo $mylogo->Intwatermarklogoid; ?>" <?php echo $check; ?> data-value="L" site-id="<?php echo $mylogo->vchsiteid; ?>" >
                                    <label for="box-<?php echo $mylogo->Intwatermarklogoid; ?>">
                                        Mark default Watermark Logo</label>
                                </td>
                                @if(strstr($access, "1"))
                                    <td style="text-align:center;">
                                        <a href="javascript:void(0);" class="btn btn-danger delete" deleteid="<?php echo $mylogo->Intwatermarklogoid; ?>">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </a>
                                        <a href="/admin/watermarkupdateedit?id=<?php echo $mylogo->Intwatermarklogoid; ?>" class="btn btn-danger edit">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                        <button class="btn btn-secondary cache-images" data-site-id="{{$mylogo->vchsiteid}}">
                                            <i class="fa fa-money" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <?php }else { ?>
                        <div class="watermarklogo">Please add large Watermark logo</div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="searchtags prdctsmalgogo" style="display:none;">
                <div class="ful-top gap-sextion"  id="product_container">
                    <div class="col-md-12">
                        <?php
                        if(!$smallwatermark->isEmpty()){
                        ?>
                        <table class="table-bordered" width="80%;">
                            <thead>
                            <tr>
                                <th style="text-align:center;">
                                    Watermark Logo
                                </th>
                                <th style="text-align:center;">
                                    Type
                                </th>
                                <th style="text-align:center;">
                                    Transparency
                                </th>
                                <th style="text-align:center;">
                                    Domain
                                </th>
                                <th style="text-align:center;">
                                    Mark Default
                                </th>
                                @if(strstr($access, "1"))
                                    <th style="text-align:center;">
                                        Action
                                    </th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($smallwatermark as $mylogo){
                            ?>
                            <tr>
                                <td>
                                    <img src="/upload/watermark/<?php echo $mylogo->vchwatermarklogoname; ?>" width="100px;">
                                </td>
                                <td>
                                    <?php
                                    if($mylogo->vchtype=="L"){
                                        echo "Large Thumbnail";
                                    }else {
                                        echo "Small Thumbnail";
                                    }

                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $mylogo->vchtransparency; ?>
                                </td>
                                <td>
                                    <?php
                                    echo $mylogo->txtsiteurl; ?>
                                </td>
                                <td>
                                    <?php
                                    $mystatus = $mylogo->enumstatus;
                                    if($mystatus=='A'){
                                        $check = "checked";
                                    }else {
                                        $check = "";

                                    }
                                    ?>
                                    <input type="checkbox" id="box-<?php echo $mylogo->Intwatermarklogoid; ?>" class="racecategory racecategorycheckbox" data-value="S" name="markdefault" value="<?php echo $mylogo->Intwatermarklogoid; ?>" <?php echo $check; ?> site-id="<?php echo $mylogo->vchsiteid; ?>">
                                    <label for="box-<?php echo $mylogo->Intwatermarklogoid; ?>">
                                        Mark default Watermark Logo</label>
                                </td>
                                @if(strstr($access, "1"))
                                    <td style="text-align:center;">
                                        <a href="javascript:void(0);" class="btn btn-danger delete" deleteid="<?php echo $mylogo->Intwatermarklogoid; ?>">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </a>
                                        <a href="/admin/watermarkupdateedit?id=<?php echo $mylogo->Intwatermarklogoid; ?>" class="btn btn-danger">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                @endif
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <?php }else { ?>
                        <div class="watermarklogo">Please add small Watermark logo</div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="searchtags prdctsmalgogos" style="display:none;">
                <div class="ful-top gap-sextion"  id="product_container">
                    <div class="col-md-12">
                        <?php
                        if(!$videowatermark->isEmpty()){
                        ?>
                        <table class="table-bordered" width="80%;">
                            <thead>
                            <tr>
                                <th style="text-align:center;">
                                    Watermark Logo
                                </th>
                                <th style="text-align:center;">
                                    Type
                                </th>
                                <th style="text-align:center;">
                                    Transparency
                                </th>
                                <th style="text-align:center;">
                                    Domain
                                </th>
                                <th style="text-align:center;">
                                    Mark Default
                                </th>
                                @if(strstr($access, "1"))
                                    <th style="text-align:center;">
                                        Action
                                    </th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($videowatermark as $mylogo){
                            ?>
                            <tr>
                                <td>
                                    <img src="/upload/watermark/<?php echo $mylogo->vchwatermarklogoname; ?>" width="100px;">
                                </td>
                                <td>
                                    <?php
                                    if($mylogo->vchtype=="V"){
                                        echo "Video Watermark";
                                    }else {
                                        echo "Small Thumbnail";
                                    }

                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $mylogo->vchtransparency; ?>
                                </td>
                                <td>
                                    <?php
                                    echo $mylogo->txtsiteurl; ?>
                                </td>
                                <td>
                                    <?php
                                    $mystatus = $mylogo->enumstatus;
                                    if($mystatus=='A'){
                                        $check = "checked";
                                    }else {
                                        $check = "";

                                    }
                                    ?>
                                    <input type="checkbox" id="box-<?php echo $mylogo->Intwatermarklogoid; ?>" class="racecategory racecategorycheckbox" data-value="V" name="markdefault" value="<?php echo $mylogo->Intwatermarklogoid; ?>" <?php echo $check; ?> site-id="<?php echo $mylogo->vchsiteid; ?>">
                                    <label for="box-<?php echo $mylogo->Intwatermarklogoid; ?>">
                                        Mark default Watermark Logo</label>
                                </td>
                                @if(strstr($access, "1"))
                                    <td style="text-align:center;">
                                        <a href="javascript:void(0);" class="btn btn-danger delete" deleteid="<?php echo $mylogo->Intwatermarklogoid; ?>">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </a>
                                        <a href="/admin/watermarkupdateedit?id=<?php echo $mylogo->Intwatermarklogoid; ?>" class="btn btn-danger">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                @endif
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <?php }else { ?>
                        <div class="watermarklogo">Please add small Watermark logo</div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="searchtags prdctbackground" style="display:none;">
                <div class="ful-top gap-sextion"  id="product_container">
                    <div class="row">
                        <div class="col-md-12">
                            <form  method="POST" enctype="multipart/form-data" action="/admin/savebackground">
                            {!! csrf_field() !!}
                            <!--
                           <div class="form-group">
                           <label for="popupcolor">Domain</label>
                             <select name="siteid" class="form-control">
                           <option value="">Select Domain</option>
                           @foreach($managesites as $managesite)
                                <option value="{{$managesite->intmanagesiteid}}"  >{{$managesite->txtsiteurl}}</option>
                           @endforeach
                                </select>
                                </div>
-->
                                @if(strstr($access, "1"))
                                    <div class="iconsdsf">
                                        <label>Domain</label>
                                        <ul class="main">
                                            <li>
                                                <ul style="margin-top: -10px;">
                                                    @foreach($managesites as $managesite)
                                                        <li>
                                                            <label class="container-checkbox">{{$managesite->txtsiteurl}}
                                                                <input type="checkbox" class="checkbox multisite"  name="multisite[]" value="{{$managesite->intmanagesiteid}}" checked>
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                        </ul>
                                    </div>
                                    <div class="form-group">
                                        <label for="popupcolor">Background Title:</label>
                                        <input type="text" class="form-control" name="background_title" value="" />
                                    </div>
                                    <div class="form-group">
                                        <label for="popupcolor">Background Image:</label>
                                        <input type="hidden" name="bg_image" class="proicon" value=""  id="customvideo">
                                        <input type="file" name="bg_upload" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <!--
                                           <input type="submit" name="resettagcolor" value="Reset to default" class="btn btn-dafualt" id="anchorcolor" onclick="return confirm('Are you sure you want reset default setting?');">
                                           -->
                                        <input type="submit" name="custompage" value="Save" class="btn btn-dafualt" id="anchorcolor">
                                    </div>
                        </div>
                        @endif
                        </form>
                    </div>
                    <div class="ful-top gap-sextion" style="width: 100%;">
                        <div class="col-md-12">
                            <table class="table-bordered table-hover" width="80%;">
                                <thead>
                                <tr>
                                    <th style="text-align:center;">
                                        Background Title
                                    </th>
                                    <th style="text-align:center;">
                                        Background Image
                                    </th>
                                    <th style="text-align:center;">
                                        Domain
                                    </th>
                                    @if(strstr($access, "1"))
                                        <th style="text-align:center;">
                                            Action
                                        </th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!empty($background_list)){
                                foreach($background_list as $bglist){
                                ?>
                                <tr>
                                    <td>
                                        <?php
                                        echo $bglist->background_title; ?>
                                    </td>
                                    <td>
                                        <img src="/images/<?php echo $bglist->background_img; ?>" width="100px;">
                                    </td>
                                    <td>
                                        <?php
                                        echo $bglist->sitename; ?>
                                    </td>
                                    @if(strstr($access, "1"))
                                        <td style="text-align:center;">
                                            <a href="javascript:void(0);" class="btn btn-danger delete-bg" deleteid="<?php echo $bglist->bg_id; ?>">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>
                                            <a href="/admin/watermarkupdateedit?bid=<?php echo $bglist->bg_id; ?>" class="btn btn-success">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                                <?php }}else{
                                ?>
                                <tr>
                                    <td> NO BACKGROUNDS </td>
                                </tr>
                                <?php
                                }

                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="display: block;">
                <button type="button" class="close btn-close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Task Scheduling </h4>
            </div>
            <div class="modal-body">
                <input size="16" type="text" value="<?=date('Y-m-d H:s')?>" placeholder="2012-06-15 14:45" readonly class="form-control form_datetime">
                <input type="hidden" class="videologoid" value=""/>
                <input type="hidden" class="videotype" value=""/>
                <input type="hidden" class="siteid" value=""/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-close btn-submit-video" >Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cacheImagesModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="display: block;">
                <button type="button" class="close btn-close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Schedule Images Caching </h4>
            </div>
            <div class="modal-body">
                <p>When should we start caching images for this domain ?</p>
                <input id="cacheImagesTime" size="16" type="text" value="<?=date('Y-m-d H:s')?>" placeholder="2021-06-15 14:45" readonly class="form-control form_datetime">
            </div>
            <div class="modal-footer">
                <button id="cacheImagesBtn" type="button" class="btn btn-default">Schedule</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.delete').click(function(){
            var result = confirm("Are you sure to delete this ?");
            if (result) {
                var deleteid = $(this).attr('deleteid');
                var token= $('meta[name="csrf_token"]').attr('content');
                $.ajax({
                    url:'{{ URL::to("/admin/deletewatermark") }}',
                    type:'POST',
                    data:{'_token':token,'deleteid':deleteid},
                    success:function(ress){

                        location.reload();

                    }
                });
            }
        });


        $('.delete-bg').click(function(){
            var result = confirm("Are you sure to delete this ?");
            if (result) {
                var deleteid = $(this).attr('deleteid');
                var token= $('meta[name="csrf_token"]').attr('content');
                $.ajax({
                    url:'{{ URL::to("/admin/deletebg") }}',
                    type:'POST',
                    data:{'_token':token,'deleteid':deleteid},
                    success:function(ress){
                        location.reload();
                    }
                });
            }
        });

        $('.tabwatermarkss').click(function(){
            var logotype = $(this).attr('logotype');
            $('.searchtags').css('display','none');
            $('.'+logotype).fadeIn();

        });
        $('.racecategory').click(function(){
            var checkbox = $(this).is(":checked");
            var image = $(this).attr("data-value");
            var siteid = $(this).attr("site-id");
            var checkboxid = $(this).val();
            if(image == 'V'){
                if(checkbox){
                    $('#myModal').modal('show');
                    $(".videologoid").val(checkboxid);
                    $(".videotype").val(image);
                    $(".siteid").val(siteid);

                }
                //$('#myModal').modal({backdrop: 'static', keyboard: false});
            }else{
                markset(checkbox,image,checkboxid,siteid);
            }

        });
    });

    var siteId = null

    $('.cache-images').click(function(event) {
        siteId = parseInt(event.currentTarget.dataset.siteId)
        $('#cacheImagesModal').modal('show');
    })

    $('#cacheImagesBtn').click(function() {
        let token = $('meta[name="csrf_token"]').attr('content')
        let date = $('#cacheImagesTime').val()

        $.ajax({
            url:'/admin/scheduleImageCaching',
            type:'POST',
            data:{_token: token, date: date, domainId: siteId},
            success: function(res){
                console.log('res')
                console.log(res)
                $('#cacheImagesModal').modal('hide');
            }
        });

    })

    $(".btn-submit-video").click(function(){
        markset('yes',$(".videotype").val(),$(".videologoid").val(),$(".siteid").val())
    });
    function markset(checkbox,image,checkboxid,siteid){
        if(checkbox){
            var myconfirm = confirm("Are you sure to mark it default watermark logo");
            if(myconfirm){
                var vtime = $(".form_datetime").val();
                var token= $('meta[name="csrf_token"]').attr('content');
                $.ajax({
                    beforeSend: function(){
                        $(".info-loading-image").css("display","flex");
                        $("body").css("overflow","hidden");
                    },
                    url:'{{ URL::to("/admin/markdefaultlogo") }}',
                    type:'POST',
                    data:{'_token':token,'imagetype':image,'type':'check','checkboxid':checkboxid,'vtime':vtime,'siteid':siteid},
                    success:function(ress){
                        $(".info-loading-image").css("display","none");
                        $("body").css("overflow","scroll");
                        location.reload();
                    }
                });
            } else {
                $(this).prop("checked", false);
            }
        }else {

        }
    }
    $(".btn-close").click(function(){
        location.reload();
    });
</script>
<link href="/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript">
    var date = new Date();
    date.setDate(date.getDate());
    $(".form_datetime").datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        startDate: date,
    });

    $("#large").click(function(){
        $('#small').removeClass('active');
        $('#video').removeClass('active');
        $("tabwatermarkss").removeClass("active");
        $('#background').removeClass('active');
        $(this).removeClass('active').addClass('active');
    });
    $("#small").click(function(){
        $('#large').removeClass('active');
        $('#video').removeClass('active');
        $("tabwatermarkss").removeClass("active");
        $('#background').removeClass('active');
        $(this).removeClass('active').addClass('active');
    });
    $("#video").click(function(){
        $('#large').removeClass('active');
        $('#small').removeClass('active');
        $('#background').removeClass('active');
        //$("tabwatermarkss").removeClass("active");
        $(this).removeClass('active').addClass('active');
    });
    $("#background").click(function(){
        $('#large').removeClass('active');
        $('#small').removeClass('active');
        $('#video').removeClass('active');
        //$("tabwatermarkss").removeClass("active");
        $(this).removeClass('active').addClass('active');
    });


</script>
@include('admin/admin-footer')
