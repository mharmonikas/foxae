@include('admin/admin-header')
<div class="admin-page-area">
@include('admin/admin-logout')
<div class="">
<style>
.uploadedimage {
	width: 250px;
}
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
.addnew {
	float: right;
}
</style>
<div class="col-md-12 mar-auto">
<div class="back-strip top-side srch-byr">
<div class="inner-top">
Theme Options
</div>
</div>


<div class="searchtags theme_opt_out">
<form  method="POST" enctype="multipart/form-data" action="/admin/themeoption">
{!! csrf_field() !!}
 
<div class="ful-top gap-sextion"  id="product_container">
<div class="col-md-12">
<input type="hidden" name="IntthemeId" value="{{$allthemeoptions->IntthemeId}}" />
<input type="hidden" name="Intsiteid" value="{{$allthemeoptions->Intsiteid}}" />
<input type="hidden" name="vchlogo" class="vchlogo" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->Vchthemelogo; } ?>"  id="vchlogo">
<div class="">
<label for="uploadlogo">Theme Upload Logo:</label>
    <input type="file" name="uploadlogo" class="form-control" id="uploadlogo">
</div>
<div class="uploadedimage" >
	 <img id="blah" src="/images/<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->Vchthemelogo;  } ?>" alt="your image" style="width:100%" height="100px" />
	 </div>
<div class="form-group" style="width: 49%;float: left !important;position: inherit;">
<label for="backgroundcolor">Logo Height:</label>
    <input type="text" name="height" class="form-control"  value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->height; } ?>">
</div>
<div class="form-group" style="width: 49%;float: left;margin-left: 2%;">
<label for="backgroundcolor">Logo Width:</label>
    <input type="text" name="width" class="form-control"  value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->width; } ?>">
</div>	
 
<div class="form-group">
<label for="backgroundcolor">Background Color:</label>
    <input type="text" name="backgroundcolor" class="jscolor form-control" id="backgroundcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchwebsitebackgroundcolor; } ?>">
</div>
<div class="form-group">
<label for="titlecolor">Search Box Background Color:</label>
    <input type="text" name="searchbox"  class="jscolor form-control" id="searchbox" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->searchbox; } ?>">
</div>
<div class="form-group">
<label for="titlecolor">Search Button:</label>
    <input type="text" name="searchbutton"  class="jscolor form-control" id="searchbutton" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->searchbutton; } ?>">
</div>
<div class="form-group">
<label for="titlecolor">Search Button Icon:</label>
    <input type="text" name="searchbuttonicon"  class="jscolor form-control" id="searchbuttonicon" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->searchbuttonicon; } ?>">
</div>

<div class="form-group">
<label for="checkboxcolor">Checkbox Color:</label>
    <input type="text" name="checkboxcolor" class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchcheckboxcolor; } ?>">
</div>	
<div class="form-group">
<label for="checkboxcolor">Checkmark Color:</label>
    <input type="text" name="checkmakcolor" class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->checkmakcolor; } ?>">
</div>	
<div class="form-group">
<label for="textcolor">Label Color:</label>
    <input type="text" name="labelcolor"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchlabelcolor; } ?>">
</div>

<div class="form-group">
<label for="anchorcolor">Anchor Color:</label>
  <input type="text" name="anchorcolor" class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchanchorcolor; } ?>">
</div>
<div class="form-group">
<label for="anchorcolor">Pagination Anchor background Color:</label>
  <input type="text" name="pagnationanchorcolor" class="jscolor form-control" id="pagnationanchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->pagnationanchorcolor; } ?>">
</div>		

<div class="form-group" style="display:none";>
<label for="textcolor">Text Color:</label>
    <input type="text" name="textcolor" class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchtextcolor; } ?>">
</div>

<div class="form-group">
<label for="titlecolor">Title Color:</label>
    <input type="text" name="titlecolor"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchtitlecolor; } ?>">
</div>
<div class="form-group">
<label for="titlecolor">Box Shadow Color:</label>
    <input type="text" name="boxshadow"  class="jscolor form-control" id="boxshadow" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->boxshadow; } ?>">
</div>
<div class="form-group">
<label for="popupcolor">Popup background Color:</label>
    <input type="text" name="popupcolor"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchpopupbackgroundcolor; } ?>">
</div>	
<input type="hidden" name="vchicon" class="vchicon" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchvideoicon; } ?>"  id="vchicon">

<div class="">
<label for="uploadlogo">Video play icon:</label>
    <input type="file" name="uploadicon" class="form-control" id="uploadicon">
</div>
<div class="uploadedimage" >
	 <img id="blah" src="/images/<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchvideoicon;} ?>" alt="your image" style="width:100%" height="100px" />
	 </div>
<input type="hidden" name="proicon" class="proicon" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchprofileicon; } ?>"  id="proicon">
 <div class="">
<label for="uploadlogo">Profile icon:</label>
    <input type="file" name="profileicon" class="form-control" id="profileicon">
</div>
<div class="uploadedimage" >
	 <img id="proicon" src="/images/<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchprofileicon;} ?>" alt="your image" style="width:100%" height="100px" />
	 </div>
<div class="form-group">
<input type="submit" name="resettodefault" value="Reset to default" class="btn btn-dafualt" id="anchorcolor" onclick="return confirm('Are you sure you want reset default setting?');">
    <input type="submit" name="mypopupcolor" value="Save Setting" class="btn btn-dafualt" id="anchorcolor">
</div>	
</div>
</div>
</form>
</div>
<div class="clearfix"></div>
</div>  
</div>
</div>
<div class="space100"></div>
<script src="/js/jscolor.js"></script>
<script>
 function readURL(input) {

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
		$('.uploadedimage').css('display','block');
      $('#blah').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}


$(document).ready(function(){
$("#uploadlogo").change(function() {
  readURL(this);
});

$('.delete').click(function(){	
var result = confirm("Are you sure to delete this ?");
if (result) {
		 var token= $('meta[name="csrf_token"]').attr('content');
   	$.ajax({
				url:'{{ URL::to("/admin/deletewatermark") }}',
				type:'POST',
				data:{'_token':token},
				success:function(ress){
					
					

				}
			});
}	
});	
});
</script>
@include('admin/admin-footer')