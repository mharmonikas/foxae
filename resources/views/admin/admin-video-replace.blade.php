@include('admin/admin-header')
<style>
.btndiv {
    margin-top: 10px; padding-left: 15px;
}
#vchvideotitle {
	display: none;
}
.recentlyuploaded{
	width:100%
	display:block;
}
.recentlyuploaded img {
	width: 100%;
}
span.btn_txt {
    float: left;
    margin: 0 15px 0 0;
    padding-left: 10px;
    font-size: 24px;
}
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
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
.selectedli.active{
	border: solid 4px #e46b3c;
 }
 h3 {
  display: inline-block;
  padding: 0 41px;
}
.iconsdsf ul {
  display: inline-block;
  list-style: outside none none;
  padding-left: 0;
  width: 100%;
}
form {
  float: left;
  padding: 20px 20px 0;
  width: 100%;
}
.iconsdsf .image-icon {
  margin: 0;
}
.btn.btn-primary {
    float: right;
    position: static;
}
.check-view {
  margin-top:10px;
}
span.btn_txt {
    float: left;
    margin: 0 15px 0 0;
    padding-left: 10px;
}
label.switch {
    margin: 0;
}
.googledrivelink a {
    color: #e56b3d; font-size: 16px;
}
.googledrivelink span {
    display: block; font-size: 16px;
    font-style: italic;
}

</style>
<link href="/css/component-chosen.min.css" rel="stylesheet"/>
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

	   $('.racecategorycheckbox').on('change', function() {
		    $('.racecategorycheckbox').not(this).prop('checked', false);
		});


	});
</script>
<script>
jQuery(document).ready(function() {
    var allfiles = '';
	count=0;
	var totalupload = 0;
    $('#updatebutton').click(function() {
	var confirmation = confirm("Are You sure to replace media with Content");
     if(confirmation){
		var error=false;

		 if($('.changedvideo').prop("checked")==true){
	      var googlelink = $('#googlelink').val();
	   if(googlelink==''){
		$('#googlelink').after("<div class='alert alert-danger' style='margin-top:10px;'>Please Enter Your Google drive link</div>");
	 error = true;
	   }
	 }else {
		if(allfiles==''){
		$('#img-zone').after("<div class='alert alert-danger' style='margin-top:10px;'>Please Select any Content</div>");
		error = true;
		}
	  }
		if(error){
		return false;
		}
		$(this).prop('disabled', true);
    if($('.changedvideo').prop("checked")==true){
		var googlelink = $('#googlelink').val().replace("/view", "/preview");
		googlelink = googlelink.replace("?usp=sharing", "");
		$('#uploadtype').val('G');
		$('#googlelink').val(googlelink);
		var formserailise =  $('#forvideo').serialize();
        ajax_upload1(googlelink,'G',formserailise);
	}
	else {
	$('#uploadtype').val('W');
	var startingfile = allfiles[totalupload];
    ajax_upload(allfiles,allfiles.length,startingfile);
	}
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
	  function ajax_upload1(googlelink,type,formdata) {
	    var token= $('meta[name="csrf_token"]').attr('content');

		$.ajax({
				beforeSend: function(){
		    	    $(".info-loading-image").css("display","flex");
		    	    $("body").css("overflow","hidden");
                },
				url:'{{ URL::to("/admin/replacemedia") }}',
				type:'POST',
				data:formdata,
				success:function(ress1){
				   $(".info-loading-image").css("display","none");
		           $("body").css("overflow","scroll");
                   window.location.href="/admin/managevideosection";
				},
    error: function (jqXHR, exception) {
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
        alert("There are some problem in your Google drive Video.Please recheck and upload it again");
		$('#googlelink').val('');
		$('#updatebutton').prop('disabled', false);
        },
			});

	  }
    // Function to upload image through AJAX
    function ajax_upload(files,totalvideo,startingfile) {
     $('.showvideo'+totalupload).removeClass("waiting");
	 $('.showvideo'+totalupload).addClass("progress");
     $('#progressBar'+totalupload).css("width","0%");
	 $('#progressBar'+totalupload).html('0% complete');
	 $('#progressBar'+totalupload).removeClass('progress-bar-warning');
	 $('#progressBar'+totalupload).addClass('progress-bar-success');
	    //$('.progress-bar span').html('0% complete');
        var formData = $('#forvideo').serialize();
		 $('.progress').css("display", "block");
            var _token = $('meta[name="csrf_token"]').attr('content');
            var vchvideotitle = $('#vchvideotitle').val();

            var formdata = new FormData();
			var videoid = $('#videoid').val();
			formdata.append("file1", startingfile);
            formdata.append("_token", _token);
			 formdata.append("videoid", videoid);
            formdata.append("vchvideotitle", vchvideotitle);
            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.addEventListener("abort", abortHandler, false);
            ajax.open("POST", "/admin/replacemedia",true);
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
				totalupload++;
				//alert(totalupload);
				 window.location.href="/admin/managevideosection";
				var startingfile = files[totalupload];
				if(totalupload<files.length){
				 	var myvideoid = jQuery.parseJSON(event.target.responseText);
					$('#videoid').val(myvideoid.videoid);
			        window.location.href="/admin/managevideosection";

				/* $.ajax({
				url:'{{ URL::to("/admin/saveuploadvideo") }}',
				type:'POST',
				data:formdata,
				success:function(ress1){
					 window.location.href="/admin/managevideosection";

				}
			});	 */

				ajax_upload(files,totalvideo,startingfile)
				}else {
					var myvideoid = jQuery.parseJSON(event.target.responseText);
					$('#videoid').val(myvideoid.videoid);
					var formdata = $('#forvideo').serialize();
				/* $.ajax({
				url:'{{ URL::to("/admin/saveuploadvideo") }}',
				type:'POST',
				data:formdata,
				success:function(ress1){
					  window.location.href="/admin/managevideosection";
					  }
			}); */
				//window.location.href="/admin/taggedvideo";
				}
			}
            function errorHandler(event) {
                //_("status").innerHTML = "Upload Failed";
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
            allfiles = e.dataTransfer.files;
			$('.progresslist').html('');
			for(var k=0;k<allfiles.length;k++){
		    $('.progresslist').append('<li class="waiting showvideo'+k+'"> <div class="lit_txt">'+allfiles[k]['name']+'</div><div id="progressBar'+k+'" class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:100%">Waiting</div></li>');
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
        //ajax_upload(this.files);
        allfiles = this.files;

		$('.progresslist').html('');
		for(var k=0;k<allfiles.length;k++){
		   $('.progresslist').append('<li class="waiting showvideo'+k+'"> <div class="lit_txt">'+allfiles[k]['name']+'</div><div id="progressBar'+k+'" class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:100%">Waiting </div></li>');
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
   <div class="">
		<div class="col-md-12 mar-auto">
		<div class="back-strip top-side srch-byr">
					<div class="inner-top">
						Replace Media Content
					</div>
				</div>
		<form action="/admin/saveuploadvideo" id="forvideo" method="Post" enctype="multipart/form-data">
		@csrf
    <div class="row">
		<?php

		if($allvideo->EnumUploadType=='G'){
			$vidoestatus = "checked";
		}else {

		$vidoestatus = "";
		 } ?>

	<div class="col-md-12">
		<div class="uploadvideo"><span class="btn_txt">Replace Google Drive Link:</span><label class="switch">
  <input class="changedvideo" type="checkbox" value="G" <?php echo $vidoestatus; ?>>
  <span class="slider round"></span>
</label>
</div>
	</div>
	 <div class="col-md-12">
     <div class="col-md-12">
        <input type="hidden" name="videoid" id="videoid" value="<?php echo $allvideo->IntId; ?>" >

        <input class="form-control" type="text" id="vchvideotitle" name="vchvideotitle" placeholder="Enter Your Content Title">
      <input type="hidden" name="uploadtype" id="uploadtype">
	  </div>
	   <?php
	  if($vidoestatus=='checked'){
	 $style="style=display:none;";
	  $style1="style=display:block;";
	  }else {
	 $style1="style=display:none;";
	  $style="style=display:block;";

	  ?>
	  <?php } ?>
<div class="videouploadsection" <?php echo $style; ?>>

    <div class="col-sm-12 col-sm-offset-2">

                <div  class="img-zone text-center" id="img-zone"  >
                    <div class="img-drop">
                        <h2><small>Drag &amp; Drop Files Here</small></h2>
                        <p><em>- or -</em></p>
                        <h2><i class="glyphicon glyphicon-camera"></i></h2>
                        <span class="btn btn-success btn-file">
                        Click to Open File Browser<input type="file" multiple="">
                    </span>
                    </div>
                </div>

				<div class="list_div">
					<ul class="progresslist">

					</ul>
				</div>
			 </div>
		     </div>
			 <div class="googledrivelink" <?php echo $style1; ?>>
			  <div class="col-md-12">


		 <input type="text" class="form-control" name="googlelink" id="googlelink">

        </div>
			 </div>
			</div>
		  <div class="col-md-6">

		  </div>
		 <div class="col-md-12"><div class="btndiv"> <input class="btn btn-dafualt" type="button" id="updatebutton" name="submit" value="Replace Media"></div></div>
	</div>


  </form>
</div>
	</div>
</div>
<div class="space100"></div>
<script src="/js/choosen5.js">
</script>
<script type="text/javascript">
 //addcategory
$(document).ready(function(){
  $('.changedvideo').change(function(){
	var checked  = $(this).val();
	 if($(this).prop("checked") == true){
		 $('.videouploadsection').css("display","none");
		 $('.googledrivelink').fadeIn();

	 }else {
		 $('.googledrivelink').css("display","none");
		 $('.videouploadsection').fadeIn();
		 }

 });
 });
 $('.palce-all').on('click','.addcategory',function(){
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
				 //window.location.href="";

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

	$('.form-control-chosen-search-threshold-100').chosen({
		allow_single_deselect: false,
			disable_search_threshold: 100,
		width: '100%'
	});
	$('.form-control-chosen-optgroup').chosen({
		width: '100%'
	});
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

@include('admin/admin-footer')
