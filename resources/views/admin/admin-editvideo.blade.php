@include('admin/admin-header')
<style>
.recentlyuploaded{
	width:100%
	display:block;
}
.recentlyuploaded img {
	width: 100%;
}
.addcategory.btn.btn-primary {
	top: 10px !important;
}
.recentlyuploaded li{
	float: left;
	width: 23%;
	margin-right: 10px;
	margin-top: 10px;
	list-style: none;
}
.iconsdsf ul {
	display: inline-flex;
	list-style: none;
}
input#updatebutton {
    margin-top: 20px;
}
.videoimages{

	width: 300px;
height: 100%;
}

.videotype {
    margin-top: 20px;
}

.space100 {
	height: 100px;
}
.selectedli.active{
	border: solid 4px #e46b3c;
 }
 .progress {
    border-radius: 0;
   // display: none;
}
.defauls_prog {
  padding: 17px 13px;
  width: 691px;
}
.defauls_prog .progress {
  border-radius: 5px;
  }
.btn-dafualt {
    background-color: #3490dc;
    border-color: #3490dc;
    color: #fff;
}


        .btn-file {
            position: relative;
            overflow: hidden;
        }

        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }
        .img-zone {
            background-color: #F2FFF9;
            border: 5px dashed #4cae4c;
            border-radius: 5px;
            padding: 20px; margin: 15px 0;
        }
.img-zone h2 {
            margin-top: 0;
        }

.progress, #img-preview {
    margin-top: 15px;
 }
 .list_div {
    display: block;
    max-height: 257px;
    overflow-y: scroll;
}
 .list_div ul {
    display: block;
    padding: 0;
}
.list_div ul li {
    display: block;
    margin: 10px 0;
    background: #f3f3f3;
    padding: 4px 8px; height: auto;
    border: 1px solid #ddd;
    border-radius: 4px;
}
.lit_txt {
    display: block;
    font-size: 14px;
}
.progress .progress-bar-success {
    display: inline-block;
	font-size: 10px;
    border-radius: 10px;
}
.lit_txt span {
    display: inline-block;
    background: #4cae4c;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 4px;
    margin: 0 0 0 5px;
    color: #fff;
}
.progress-bar-warning {
    display: inline-block;
    background-color: #f0ad4e;
    font-size: 10px;
    border-radius: 10px;
}
#progressBar {
    margin: 7px 0 3px;
}

.searchtags {
    background: #f5f5f5;
    background: #f5f5f5;
    border: 1px solid #ccc;
    padding: 15px;
    border-radius: 4px;
}
/* The container-checkbox */
.container-checkbox {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 15px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.container-checkbox input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
      position: absolute;
    top: 0;
    left: 0;
    height: 19px;
    width: 19px;
    background-color: #fff;
    border: 2px solid #e66b3e;
}
.upld-vd-sec {
    background: #f5f5f5;
    border: 1px solid #ccc;
    display: inline-block;
    width: 100%;
    padding: 15px;
    border-radius: 4px;
}
/* On mouse-over, add a grey background color */
.container-checkbox:hover input ~ .checkmark {
  background-color: #fff;
}

/* When the checkbox is checked, add a blue background */
.container-checkbox input:checked ~ .checkmark {
  background-color: #fff;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container-checkbox input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container-checkbox .checkmark:after {
  left: 6px;
    top: 2px;
  width: 5px;
  height: 10px;
  border: solid #000;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}

input.select2-search__field {
	width: 460px !important;

}
.select2-container--default .select2-selection--multiple .select2-selection__choice {

    color: #000000 !important;
}
li.no-results span{
	color:#000 !important;
}

label.container-radio {
    font-size: 13px;
    display: flex;
    align-items: center;
	 padding: 0 10px 0 0;
}
input.content-category {
    margin: 0px 0 0 5px;
}
.flex-radio {
    display: flex;
    align-items: center;
}
   .iconsdsf h5 {
    font-size: 16px;
    font-weight: 600;
    padding: 15px 0px 0;
    margin: 0;
}
</style>
<script>
function thumbnailupload(id){
	var file_data = $('#thumnbfile')[0].files[0];
					var _token = $('meta[name="csrf_token"]').attr('content');
					var form_data = new FormData();
					form_data.append('file',file_data);
					form_data.append('id',id);
					form_data.append("_token", _token);
					$.ajax({
					beforeSend: function(){
   		    	    $(".info-loading-image").css("display","flex");
   		    	    $("body").css("overflow","hidden");
                   },
						url:'{{ URL::to("/admin/saveuploadvideo") }}',
						type:'POST',
						data:form_data,
						contentType:false,
						processData:false,
						success:function(ress1){
							$(".info-loading-image").css("display","none");
						window.location.href="/admin/managevideosection";
					}
			}) ;



}
jQuery(document).ready(function() {
    var allfiles = '';
	count=0;
	var totalupload = 0;
    $('#updatebutton').click(function() {

		$(this).prop('disabled', true);
	var videotypes = $('#videotypes').val();
	if(videotypes=='W'){
	  var startingfile = allfiles[totalupload];
	  if(startingfile!=undefined){
      ajax_upload(allfiles,allfiles.length,startingfile);
	  }else {

			//alert('hello');
		 //formdata.append("feature", feature);
		//var thumfiles = $('#thumnbfile')[0].files[0];
	  	var formdata = $('#forvideo').serialize();
			$.ajax({
				url:'{{ URL::to("/admin/saveuploadvideo") }}',
				type:'POST',
				data:formdata,
				success:function(ress1){
				//window.location.href="/admin/managevideosection";
				if($('#thumnbfile')[0].files[0]!=undefined){
					var obj = JSON.parse(ress1);
					var id = obj.videoid;
					thumbnailupload(id);
			}else{
					window.location.href="/admin/managevideosection";
				}
			}
		});

	  }


	  }else {

		var token= $('meta[name="csrf_token"]').attr('content');
	     var formdata =  $('#forvideo').serialize();
			$.ajax({
				url:'{{ URL::to("/admin/saveuploadvideo") }}',
				type:'POST',
				data:formdata,
				success:function(ress1){
				if($('#thumnbfile')[0].files[0]!=undefined){
					var obj = JSON.parse(ress1);
					var id = obj.videoid;
					thumbnailupload(id);
				}else{
					window.location.href="/admin/managevideosection";
				}
				}
			});

	}
    });
    var img_zone = document.getElementById('img-zone'),
        collect = {
            filereader: typeof FileReader != 'undefined',
            zone: 'draggable' in document.createElement('span'),
            formdata: !!window.FormData
        };

    // Function to show messages
    function ajax_msg(status, msg) {
        var the_msg = '<div class="alert alert-' + (status ? 'success' : 'danger') + '">';
        the_msg += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        the_msg += msg;
        the_msg += '</div>';
        $(the_msg).insertBefore(img_zone);
    }

    // Function to upload image through AJAX
    function ajax_upload(files,totalvideo,startingfile) {
      $('.showvideo'+totalupload).removeClass("waiting");
	 $('.showvideo'+totalupload).addClass("progress");

		  $('#progressBar'+totalupload).css("width","0%");
	    $('#progressBar'+totalupload).html('0% complete');
		$('#progressBar'+totalupload).removeClass('progress-bar-warning');
	    //
		 $('#progressBar'+totalupload).addClass('progress-bar-success');

        //$('.progress-bar span').html('0% complete');
        var formData = new FormData();
         $('.progress').css("display", "block");
            var _token = $('meta[name="csrf_token"]').attr('content');
            var vchvideotitle = $('#vchvideotitle').val();
			  var cont_cat = $("input[name='content_category']:checked").val();
			  var stock_category = $("input[name='stock_category']:checked").val();

			 if($('.featurey').prop("checked") == true){
                var feature = 1;
				}else{
					 var feature = 0;
				}
			if($('.transparent').prop("checked") == true){
					var transparent = 'Y';
				}else{
					var transparent = 'N';
				}
            var formdata = new FormData();
            formdata.append("file1", startingfile);
            formdata.append("_token", _token);
			//videoid
			var videoid = $('#videoid').val();
			 var thumfiles = $('#thumnbfile')[0].files[0];
			formdata.append("videoid", videoid);
            formdata.append("vchvideotitle", vchvideotitle);
			formdata.append("thumbfile", thumfiles);
			formdata.append("feature", feature);
			formdata.append("transparent", transparent);
			formdata.append("cont_cat", cont_cat);
			formdata.append("stock_category", stock_category);
            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.addEventListener("abort", abortHandler, false);
            ajax.open("POST", "/admin/saveuploadvideo",true);
            ajax.send(formdata);

            function progressHandler(event) {
                _("loaded_n_total").innerHTML = "Uploaded " + event.loaded + " bytes of " + event.total;
                var percent = (event.loaded / event.total) * 100;
                _("progressBar").value = Math.round(percent);
               $('#progressBar'+totalupload).css("width", Math.round(percent) + "%");
               $('#progressBar'+totalupload).html(Math.round(percent) + "%");
			     _("status").innerHTML = Math.round(percent) + "% uploaded... please wait";
            }

            function completeHandler(event) {
				$('.showvideo'+totalupload).removeClass('lit_txt');
				$('.showvideo'+totalupload).addClass('sucs');
				 $('.showvideo'+totalupload).html('<div class="lit_txt">'+files[totalupload]['name']+'<span><i class="fa fa-check" aria-hidden="true"></i> Success!</span></div>');
				var formdata = $('#forvideo').serialize();
				$.ajax({
				url:'{{ URL::to("/admin/saveuploadvideo") }}',
				type:'POST',
				data:formdata,
				success:function(ress1){
				window.location.href="/admin/managevideosection";


				}
			});


			}
            function errorHandler(event) {
                _("status").innerHTML = "Upload Failed";
            }

            function abortHandler(event) {
                _("status").innerHTML = "Upload Aborted";
            }
        //}
    }
    // Call AJAX upload function on drag and drop event
    function dragHandle(element) {
        element.ondragover = function() {
            return false;
        };
        element.ondragend = function() {
            return false;
        };
        element.ondrop = function(e) {
            e.preventDefault();
            //ajax_upload(e.dataTransfer.files);
			 var r = confirm("are you sure to replace current video/Images");
		    if(r){
		     allfiles = e.dataTransfer.files;
			$('.progresslist').html('');
			for(var k=0;k<1;k++){
		    $('.progresslist').append('<li class="waiting showvideo'+k+'"> <div class="lit_txt">'+allfiles[k]['name']+'</div><div id="progressBar'+k+'" class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:100%">Waiting</div></li>');
			}
			}else {

				return false;
			}
        }
    }
    if (collect.zone) {
        dragHandle(img_zone);
    } else {
        alert("Drag & Drop isn't supported, use Open File Browser to upload photos.");
    }

    // Call AJAX upload function on image selection using file browser button
    $(document).on('change', '.btn-file :file', function() {

		 var r = confirm("are you sure to replace current video/Images");
		if(r){
        //ajax_upload(this.files);
        allfiles = this.files;

		$('.progresslist').html('');
		for(var k=0;k<1;k++){
		   $('.progresslist').append('<li class="waiting showvideo'+k+'"> <div class="lit_txt">'+allfiles[k]['name']+'</div><div id="progressBar'+k+'" class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:100%">Waiting </div></li>');
			}
	    }else {

			return false;
		}
    });

    // File upload progress event listener
    (function($, window, undefined) {
        var hasOnProgress = ("onprogress" in $.ajaxSettings.xhr());
        if (!hasOnProgress) {
            return;
        }
        var oldXHR = $.ajaxSettings.xhr;
        $.ajaxSettings.xhr = function() {
            var xhr = oldXHR();
            if (xhr instanceof window.XMLHttpRequest) {
                xhr.addEventListener('progress', this.progress, false);
            }

            if (xhr.upload) {
                xhr.upload.addEventListener('progress', this.progress, false);
            }

            return xhr;
        };
    })(jQuery, window);
});
</script>
<div class="admin-page-area multipleupload">
<!-- top navigation -->
         @include('admin/admin-logout')
		<!-- /top navigation -->
        <!-- /top navigation -->
	   <div class="row">

		<div class="col-md-12 mar-auto">
		<div class="back-strip top-side srch-byr">
					<div class="inner-top">
						Edit Content
					</div>
				</div>
		<form action="/admin/saveuploadvideo" method="Post" id="forvideo" enctype="multipart/form-data">
		@csrf
    <div class="row">
	 <div class="col-md-6">
        <label for="fname">Content Title</label>
	<input type="hidden" name="uploadtype" id="videotypes" value="<?php echo $videotags->EnumUploadType; ?>">
	<input type="hidden" name="action" id="action" value="edit">
        <input class="form-control" type="text" id="vchvideotitle" name="vchvideotitle" placeholder="Enter Your Video Title" value="<?php echo $videotags->VchTitle; ?>">
		<input type="hidden" name="videoid" id="videoid" value="<?php echo $_GET['editvideo']; ?>">

		<input type="hidden" name="videoida" id="videoida" value="<?php echo $_GET['editvideo']; ?>">

      <div class="videotype">
	  <?php
	  if($videotags->EnumUploadType=='W'){
	  if($videotags->EnumType=='V'){ ?>

     <video width="320" height="240" controls>
  <source src="/<?php echo $videotags->VchFolderPath; ?>/<?php echo $videotags->VchVideoName; ?>" type="video/mp4">
  <source src="/<?php echo $videotags->VchFolderPath; ?>/<?php echo $videotags->VchVideoName; ?>" type="video/ogg">

</video>


     <?php
	  }else {
	  ?>
	  <img src="/<?php echo $videotags->VchFolderPath; ?>/<?php echo $videotags->VchVideoName; ?>" height="200px;" width="200px"/>

	  <?php
	  }
	  }else {
	  ?>
	  @if(empty($videotags->VchFolderPath))
	  <iframe src="<?php echo $videotags->vchgoogledrivelink; ?>" width="320" height="240"></iframe>
	  @else
		 <video width="320" height="240" controls>
  <source src="/<?php echo $videotags->VchFolderPath; ?>/<?php echo $videotags->VchVideoName; ?>" type="video/mp4">
  <source src="/<?php echo $videotags->VchFolderPath; ?>/<?php echo $videotags->VchVideoName; ?>" type="video/ogg">

</video>
	  @endif



	  <?php }

	   if($videotags->EnumUploadType=='W'){ ?>

	   <div class="col-sm-12 col-sm-offset-2">
			<div class="upld-vd-sec">
                <div  class="img-zone text-center" id="img-zone">
                    <div class="img-drop">
                        <h2><small>Drag &amp; Drop For Replace Video/Photo Here</small></h2>
                        <p><em>- or -</em></p>
                        <h2><i class="glyphicon glyphicon-camera"></i></h2>
                        <span class="btn btn-success btn-file">
                        Click to Open File Browser<input type="file" >
                    </span>
                    </div>
                </div>

				<div class="list_div">
					<ul class="progresslist">
					</ul>
				</div>
				</div>
			   </div>



	   <?php }else { ?>
	   <div class="googledrivelink">
		<div class="col-md-12">


		 <input type="text" class="form-control" name="googlelink" id="googlelink" value="<?php echo $videotags->vchgoogledrivelink; ?>" >

        </div>
			 </div>

	   <?php } ?>
	   				 <div class="col-md-12">
					 <label> Thumbnail</label>
						<input type="file"  id="thumnbfile" name="image1">
						<input type="hidden"  id="" name="image2" value="<?php echo $videotags->Vchcustomthumbnail; ?>">
						<?php
						if(!empty($videotags->Vchcustomthumbnail)){
						?>
						<img src="/<?php echo $videotags->VchFolderPath; ?>/<?php echo $videotags->Vchcustomthumbnail; ?>" height="200px;" width="200px"/>

						<?php
						}

						?>
					  </div>
	  </div>
	  </div>

	  <div class="col-md-6">
	   <div class="searchtags">

							@csrf
						<div class="form-group">
						<?php
						if(isset($_GET['editvideo'])){
						$videoid = $_GET['editvideo'];
						}else {
							$videoid = '';
						}
						$allsearchtags = array();
						$alltagrelation = $allvideo['allsearchvideorelation'];
						foreach($alltagrelation as $alltagsusers){
						array_push($allsearchtags,$alltagsusers->IntCategorid);
						}
					    ?>
							<label>Place Tags</label>
							<select id="multiple" name="tags[]" class="form-control form-control-chosen" data-placeholder="Place tags" multiple>
								<option></option>
								<?php
									foreach($allvideo['searchtags'] as $videouploads){
									$selected='';
									if(in_array($videouploads->IntId,$allsearchtags)){
										$selected="selected";

									}
									?>

									<option <?php echo $selected; ?> value="<?php echo $videouploads->IntId; ?>"><?php echo $videouploads->VchCategoryTitle; ?></option>
								<?php } ?>
							</select>
						</div>



						<div class="iconsdsf">
<ul class="row">
@foreach ($allvideo['alltags'] as $allcategory)
<?php
 $myalltagid = explode(',',$allcategory->tagid);
 $myallcategorytag = explode(',',$allcategory->tagTitle);
 $totalitems = count(explode(',',$allcategory->tagTitle));
?>
@if ($allcategory->IntId == 1)
	<?php
for($i=0;$i<$totalitems;$i++){
	  $selected="";

      $tagrelation = $allvideo['allvideorelation'];

	  if(!empty($tagrelation)){


	  $columnname = $allcategory->VchColumnType;
	 $genderid = $tagrelation->$columnname;

     if($genderid==$myalltagid[$i]){

	  $selected="checked";
      }
	 }
 ?>
  <li class="check-view col-md-6 ">
<input type="checkbox" class="racecategory racecategory1" name="filteringcategory[<?php echo $allcategory->VchColumnType; ?>]" category="<?php echo $allcategory->VchColumnType; ?>"  ng-model="gender<?php echo $myalltagid[$i];  ?>" id="box-<?php echo $myalltagid[$i];  ?>" ng-click="changegeneder(gender<?php echo $myalltagid[$i];  ?>,<?php echo $myalltagid[$i];  ?>)" value="<?php echo $myalltagid[$i];  ?>" <?php echo $selected; ?>>
  <label for="box-<?php echo $myalltagid[$i];  ?>">
  <?php echo $myallcategorytag[$i];?>
  </label>
  </li>
<?php } ?>
@else
	<?php
   $columnname = $allcategory->VchColumnType;

    $tagrelation = $allvideo['allvideorelation'];

	 if(!empty($tagrelation)){
	 $genderid = $tagrelation->$columnname;
    $selected="";

 }
?>
	   <li class="col-md-5">
     <div class="image-icon">
	 <div class="dropdown">
<label>	 {{$allcategory->VchTitle}}</label>
<select class="racecategory form-control" name="filteringcategory[<?php echo $allcategory->VchColumnType; ?>]" category="<?php echo $allcategory->VchColumnType; ?>">
    <option value="0">All <?php echo $allcategory->VchTitle; ?></option>
   <?php
 for($i=0;$i<$totalitems;$i++){
	 $selected="";
	  $tagrelation = $allvideo['allvideorelation'];
	  if(!empty($tagrelation)){
	 $columnname = $allcategory->VchColumnType;
    $genderid = $tagrelation->$columnname;

 if($genderid==$myalltagid[$i]){
	$selected="selected";
 }
}


		 ?>
	 <option <?php echo $selected; ?> value="<?php echo $myalltagid[$i];  ?>"><?php echo $myallcategorytag[$i];  ?></option>
<?php } ?>
</select>

		</div>
		</div>
		 </li>

@endif
@endforeach
</ul>
 </div>

 <div class="iconsdsf">
						<ul class="main">
							<li>

							<label class="container-checkbox">All Domains
								  <input type="checkbox"  id="select_all">
								  <span class="checkmark"></span>
								</label>

							<ul style="margin-top: -10px;">
							@foreach($allvideo['managesites'] as $managesite)
								<li>
							  <?php

								$selected="";
								$arr=explode(',',$videotags->vchsiteid);
								$genderid = $managesite->intmanagesiteid;
								if (in_array($genderid, $arr)){
								$selected="checked";
							 }



		 ?>
								<label class="container-checkbox">{{$managesite->vchsitename}}
								  <input type="checkbox" class="checkbox multisite"  name="multisite[]" value="{{$managesite->intmanagesiteid}}"<?php echo $selected; ?>>
								  <span class="checkmark"></span>
								</label>



								</li>
							@endforeach
							</ul>
						</ul>
						</div>
		 <div class="iconsdsf">
         <label>Feature Option</label>
         <label class="container-checkbox">
         Feature
		 <?php
			$selected='';
		  if($videotags->feature=='1'){
			$selected="checked";
			}

		 ?>
         <input type="checkbox" name="feature" class="featurey" value="@if($videotags->feature=='1'){{$videotags->feature}} @else {{1}} @endif" <?php if($videotags->feature=='1') echo $selected; ?>  >
         <span class="checkmark"></span>
         </label>
         </div>

		 <div class="iconsdsf">
         <h5>Content Category</h5>
		 <div class="flex-radio">
		<label class="container-radio">
         Standard
          <input type="radio" name="content_category" class="content-category" value="1" <?php if($videotags->content_category=='1') echo 'checked'; ?>>

         </label>

		 <label class="container-radio">
         Premium
          <input type="radio" name="content_category" class="content-category" value="2" <?php if($videotags->content_category=='2') echo 'checked'; ?>>

         </label>

		 <label class="container-radio">
         Deluxe
          <input type="radio" name="content_category" class="content-category" value="3" <?php if($videotags->content_category=='3') echo 'checked'; ?>>

         </label>
		 </div>
         </div>
		 @php
		  if($videotags->EnumType=='I'){
				$stock='1';
				$custom='2';
		  }else{
				$stock='3';
				$custom='4';
		  }

		 @endphp
		<div class="iconsdsf">
         <h5>Stock Category</h5>
		 <div class="flex-radio">
			<label class="container-radio">
			Stock
			 <input type="radio" name="stock_category" class="content-category" value="{{$stock}}"
			 @if($videotags->stock_category== $stock){{'checked'}} @elseif($videotags->stock_category== $stock){{'checked'}}
			 @endif >

			 </label>
			 <label class="container-radio">
			Custom
			 <input type="radio" name="stock_category" class="content-category" value="{{$custom}}"
			 @if($videotags->stock_category== $custom){{'checked'}} @elseif($videotags->stock_category== $custom){{'checked'}}
			 @endif >

			 </label>

		 </div>
      </div>

		 <div class="iconsdsf">
         <label>Image Background</label>
         <label class="container-checkbox">
         Transparent
         <input type="checkbox" name="transparent" class="transparent" value="Y" <?php if($videotags->transparent=='Y') echo 'Checked' ?>>
         <span class="checkmark"></span>
         </label>
         </div>





		</div>



	  </div>
	  <div class="col-md-12">
	   <input class="btn btn-dafualt" type="button" id="updatebutton" name="submit" value="Submit" id="uploadimage">
	   </div>
	  </div>
	  </div>
	  </div>




  </form>



<div class="space100"></div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
</script>
<link href="/css/component-chosen.min.css" rel="stylesheet"/>
<script src="/js/choosen7.js?v=1">
</script>
<script type="text/javascript">
 //addcategory
 $('.searchtags').on('click','.addcategory',function(){

	var searchtext = $('.chosen-search-input').val();
	var categorytitle = searchtext;
	var category = '';
    var parentcat = '';
    var token= $('meta[name="csrf_token"]').attr('content');
	$.ajax({
				url:'{{ URL::to("/admin/addeditsearchcategory") }}',
				type:'POST',
				data:{'categorytitle':categorytitle,'category':'','parentcat':'','_token':token},
				success:function(ress){
				window.location.href="";

$('.form-control-chosen').append($("<option/>", {
        value:ress.lastinsertid,
        text: searchtext
    }));
	$(".form-control-chosen option[value='"+ress.lastinsertid+"']").prop("selected", true);
$('.form-control-chosen').trigger("chosen:updated");

				}
			});
	});

	$('.form-control-chosen').chosen({
			allow_single_deselect: false,
		width: '100%'
	});

	// $('.form-control-chosen-search-threshold-100').chosen({
		// allow_single_deselect: false,
			// disable_search_threshold: 100,
		// width: '100%'
	// });
	// $('.form-control-chosen-optgroup').chosen({
		// width: '100%'
	// });
	$(document).on('click', '[title="clickable_optgroup"] .group-result', function() {
		var unselected = $(this).nextUntil('.group-result').not('.result-selected');
			if(unselected.length) {
			unselected.trigger('mouseup');
		} else {
    $(this).nextUntil('.group-result').each(function() {
		$('a.search-choice-close[data-option-array-index="' + $(this).data('option-array-index') + '"]').trigger('click');
			});
		}
	});
</script>

<script>

	$(document).ready(function(){
		var allselectedvideo = [];
			$('.selectedli').click(function(){
		$(this).toggleClass("active");
	var videoid = $(this).attr("videoid");

		if($.inArray(videoid, allselectedvideo)!== -1){
			allselectedvideo.splice($.inArray(allselectedvideo,videoid) ,1);
		}else {
			allselectedvideo.push(videoid);
}
		var allselectedvideostring = allselectedvideo.join(',');

		$('#selectedvideo').val(allselectedvideostring);
		});

	 $('.racecategory1').on('change', function() {
		    $('.racecategory1').not(this).prop('checked', false);
		});
	});
</script>
<script>
function deletevideo(deleteid){
	var result = confirm("Want to delete?");
	if (result) {
     $.ajax({
               type:'Get',
               url:'/admin/managevideosection',
               data:{deletevideoid:deleteid},
               success:function(data) {
				    $("#msg").fadeIn();
					$('#remove'+deleteid).remove();
                  $("#msg").html("Video successfully deleted");

				  setTimeout(function(){

				   $("#msg").fadeOut();
				   }, 3000);
               }
            });
}

}

  $(document).ready(function(){
       $('#select_all').on('click',function(){
           if(this.checked){
               $('.checkbox').each(function(){
                   this.checked = true;
               });
           }else{
                $('.checkbox').each(function(){
                   this.checked = false;
               });
           }
       });

       $('.checkbox').on('click',function(){
           if($('.checkbox:checked').length == $('.checkbox').length){
               $('#select_all').prop('checked',true);
           }else{
               $('#select_all').prop('checked',false);
           }
       });

	   if($('.checkbox:checked').length == $('.checkbox').length){
               $('#select_all').prop('checked',true);
           }else{
               $('#select_all').prop('checked',false);
           }
   });

   $(document).ready(function() {
    $(".taggingSelect2").select2({
      tags: true,
    });
});
</script>
@include('admin/admin-footer')
