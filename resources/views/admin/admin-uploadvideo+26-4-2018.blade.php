@include('admin/admin-header')
<style>
.recentlyuploaded{
	width:100%
	display:block;
}
.recentlyuploaded img {
	width: 100%;
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
.videoimages{
	
	width: 300px;
height: 100%;
}
.space100 {
	height: 100px;
}
.selectedli.active{
	border: solid 4px #e46b3c;
 }
 .progress {
    border-radius: 0;
    display: none;
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
</style>
<script>
/* Script written by Adam Khoury @ DevelopPHP.com */
/* Video Tutorial: http://www.youtube.com/watch?v=EraNFJiY0Eg */

function _(el){
	return document.getElementById(el);
}
function uploadFile(){
	 $('#updatebutton').prop('disabled', true);
	$('.progress').css("display","block");
	var file = _("file1").files[0];
    var _token =  $('meta[name="csrf_token"]').attr('content');
	var vchvideotitle = $('#vchvideotitle').val();
   var formdata = new FormData();
	formdata.append("file1", file);
	formdata.append("_token", _token);
	formdata.append("vchvideotitle", vchvideotitle);
	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);
	ajax.open("POST", "/admin/saveuploadvideo");
	ajax.send(formdata);
}
function progressHandler(event){
	_("loaded_n_total").innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
	var percent = (event.loaded / event.total) * 100;
	_("progressBar").value = Math.round(percent);
	$('#progressBar').css("width",Math.round(percent)+"%");
	$('#progressBar').html(Math.round(percent)+"%");
	_("status").innerHTML = Math.round(percent)+"% uploaded... please wait";
}
function completeHandler(event){
	
  var myvideoid = jQuery.parseJSON(event.target.responseText);
  	$('#updatebutton').prop('disabled', true);
	window.location.href="/admin/taggedvideo?videoid="+myvideoid.videoid;
	
	$('#progressBar').css("width",0+"%");
	$('.progress').css("display","none");
}
function errorHandler(event){
	_("status").innerHTML = "Upload Failed";
}
function abortHandler(event){
	_("status").innerHTML = "Upload Aborted";
}
</script>
<div class="admin-page-area">
<!-- top navigation -->
         @include('admin/admin-logout')
		<!-- /top navigation -->
        <!-- /top navigation -->
   <div class="">
		<div class="col-md-12 mar-auto">
		<div class="back-strip top-side srch-byr">
					<div class="inner-top">
						Adddas Video
					</div>
				</div>
		<form action="/admin/saveuploadvideo" method="Post" enctype="multipart/form-data">
		@csrf
    <div class="row">
	 <div class="col-md-4">
        <label for="fname">Video Title</label>
        <input class="form-control" type="text" id="vchvideotitle" name="vchvideotitle" placeholder="Enter Your Video Title">
      </div>
 
    <div class="col-md-4">
        <label for="country">Upload Video/ Images</label>
        <input   type="file" name="file1" id="file1">
    </div> </div>

	
     <div class="row">
	<div class="defauls_prog">
	<div class="progress">
<div id="progressBar" class="progress-bar progress-bar-success progress-bar-striped 
active" role="progressbar" aria-valuenow="10"
aria-valuemin="0" aria-valuemax="100" style="width:0%">
0%
</div>
</div>
	<h5 id="status"></h5>
  <p id="loaded_n_total"></p></div>
   <div class="row"> <input class="btn btn-dafualt" type="button" id="updatebutton" name="submit" value="Submit" onclick="uploadFile();"></div>
    </div>

  </form>	
</div>
	</div>  
</div>
<div class="space100"></div>
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
</script>		
@include('admin/admin-footer')