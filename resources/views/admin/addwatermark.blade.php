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
.addnew {
	float: right;
}
.from-Transparency {
    width: 50%;
    margin: 0 auto;
    float: unset;
}
</style>
<div class="col-md-12 mar-auto">
<div class="back-strip top-side srch-byr">
<div class="inner-top">
Add WaterMark  Image
</div>
</div>



<div class="col-md-12">
<form class="form-horizontal from-Transparency" enctype="multipart/form-data" method="POST" action="/admin/watermarkupdate" id="">
{!! csrf_field() !!}

  <div class="form-group">
  <label>Please Enter Transparency 1 to 10 (1 is min and 10 is max)</label>
  <input type="text" class="form-control" name="transparentlogo" id="transparentlogo">
  </div>
  <div class="form-group">
  <label>Logo For</label>
  <select name="settransparency" class="form-control">
   <option value="L" selected>Large</option>
   <option value="S">Small</option>
     <option value="V">Video</option>
  </select>
  </div>

  <div class="form-group">
  <label>Domains</label>
		  <select name="multisite" class="form-control">
			<option value="" > Select Domain</option>
			@foreach($managesites as $managesite)
			<option value="{{$managesite->intmanagesiteid}}" > {{$managesite->vchsitename}}</option>
			@endforeach
		  </select>
        
  
  </div>
  <div class="form-group">
 <label>Please Upload Logo</label>
  <input type="file" class="" name="fileToUpload" id="fileToUpload">
  </div>
  <!--<div class="form-group">-->
  <input type="hidden" class="racecategory racecategorycheckbox" name="default"   id="box-1"  value=""> 
  <!--<label for="box-1">
  Set Default watermarklogo  </label>
  </div>-->
  <div class="form-group">
  <input type="submit" class="btn btn-default" value="Upload Image" name="submit">
    </div>
</form>
</div>


</div>  
</div>
</div>
<div class="clearfix"></div>
<script>
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
   });
</script>
@include('admin/admin-footer')