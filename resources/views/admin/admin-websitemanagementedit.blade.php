@include('admin/admin-header')
<div class="admin-page-area">
@include('admin/admin-logout')
<style>
.from-Transparency {
    width: 50%;
    margin: 0 auto;
    float: unset;
}
</style>
<div class="">

<div class="col-md-12 mar-auto">
<div class="back-strip top-side srch-byr">
<div class="inner-top">
Manage Website
</div>
</div>
<div class="clearfix"></div>

<div class="searchtags">
<div class="ful-top gap-sextion"  id="product_container">
@if(!empty($watermark))
<div class="col-md-12">
<form class="form-horizontal from-Transparency" enctype="multipart/form-data" method="POST" action="/admin/watermarkupdateedit" id="">
{!! csrf_field() !!}
 <input type="hidden" name="imagetype"  value="<?php echo $watermark->vchtype;?>" >
  <input type="hidden" name="siteid"  value="<?php echo $watermark->vchsiteid;?>" >
  <input type="hidden" name="checkboxid"  value="<?php echo $watermark->Intwatermarklogoid;?>" >
  <input type="hidden" name="type"  value="check" >
<div class="form-group">
    <label for="pwd">Transparency:</label>
  <input type="text"  class="form-control" name="transparency" value="<?php echo $watermark->vchtransparency;  ?>">
</div> 
<div class="form-group">
    <label for="pwd">Choose File:</label>
  <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" >
  <input type="hidden" name="oldimageupload"  value="<?php echo $watermark->vchwatermarklogoname;?>" >
 
</div> 

		 
 <div class="form-group">
  <label>Domains</label>
  
		  <select name="multisite" class="form-control">
			<option value="" > Select Domain</option>
			@foreach($managesites as $managesite)
			<option value="{{$managesite->intmanagesiteid}}" @if($managesite->intmanagesiteid== $watermark->vchsiteid) Selected  @endif> {{$managesite->vchsitename}}</option>
			@endforeach
		  </select>
  </div>
<div class="formgroupimage">
<?php 
if(!empty($watermark)){ ?>
<img src="/upload/watermark/<?php echo $watermark->vchwatermarklogoname;  ?>"/ style="height: 115px;">
<?php 	
}
?>
<input type="hidden" name="logoid" value="<?=$_REQUEST['id']?>" />
 </div> 
  <input type="submit" class="btn btn-default" value="Upload" name="submit">
</form>

</div>
@endif
@if(!empty($backgrounds))
	<div class="col-md-12">
		<form  method="POST" enctype="multipart/form-data" action="/admin/savebackground">
		<input type="hidden" name="bg_id" value="{{$backgrounds->bg_id}}">
		
{!! csrf_field() !!}


         <div class="iconsdsf">
         <label>Domain</label>
         <ul class="main">
         <li>
        
         <ul style="margin-top: -10px;">  
        	@foreach($managesites as $managesite)
         <li>
		 					  <?php 
							 
								$selected="";
								$arr=explode(',',$backgrounds->siteid);
								$genderid = $managesite->intmanagesiteid;
								if (in_array($genderid, $arr)){
								$selected="checked"; 
							 }
						
	 
	 
		 ?>
         <label class="container-checkbox">{{$managesite->txtsiteurl}}
         <input type="checkbox" class="checkbox multisite"  name="multisite[]" value="{{$managesite->intmanagesiteid}}" <?php echo $selected; ?>>
         <span class="checkmark"></span>
         </label>
         </li>
         @endforeach	
         </ul>
         </ul> 
         </div>

<div class="form-group">
<label for="popupcolor">Background Title:</label>
    <input type="text" class="form-control" name="background_title" value="{{$backgrounds->background_title}}" />
</div>	


<div class="form-group">
<label for="popupcolor">Background Image:</label>
    <input type="hidden" name="bg_image" class="proicon" value="{{$backgrounds->background_img}}"  id="customvideo">
	 <input type="file" name="bg_upload" class="form-control" >
</div>	

<div class="form-group">
<img src="/images/{{$backgrounds->background_img}}" height="200px" width="200px">
</div>	



<div class="form-group">
<!--
<input type="submit" name="resettagcolor" value="Reset to default" class="btn btn-dafualt" id="anchorcolor" onclick="return confirm('Are you sure you want reset default setting?');">
-->
    <input type="submit" name="custompage" value="Save" class="btn btn-dafualt" id="anchorcolor">
</div>	

</div>
</form>
	</div>
@endif

</div>
</div>
</div>  
</div>
</div>
<script>
$(document).ready(function(){

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
	   
	    
   });
</script>
@include('admin/admin-footer')