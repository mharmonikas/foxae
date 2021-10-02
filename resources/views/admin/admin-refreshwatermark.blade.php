@include('admin/admin-header')
<div class="admin-page-area">
@include('admin/admin-logout')
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
<div class="col-md-12">
<div class="totalcontent">
Total Content : <?php print_r($allcontent); ?>
<input type="hidden" name="startlimit" id="startlimit" value="0">
<input type="hidden" name="endlimit" id="endlimit" value="<?php print_r($allcontent); ?>">
</div>
<div class="col-md-12">
<div class="show">

<div class="alert alert-success" role="alert" style="display:none;">
  Please keep patience
</div>

</div>


</div>
<div class="startuploading">
<div class="progress" >
    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:0%" >
      <span class="sr-only">70% Complete</span>
    </div>
	</div>
<!-- <input type="submit" class="btn btn-default" value="Start Refreshing Images" name="submit"> -->
<a href="javascript:void(0);" class="btn btn-default" id="startuploadingimage">Start Uploading</a>
</div>
</div>
</div>
</div>  
</div>
</div>
<script>
$(document).ready(function(){
$('#startuploadingimage').click(function(){	
          var startlimit = $('#startlimit').val();
		  var endlimit = $('#endlimit').val();
		 var token= $('meta[name="csrf_token"]').attr('content');
		 $('.shwmsg').css('display','block');
		 $(this).attr("disabled", true);
   	 ajaxdata(startlimit,endlimit);
	
});	


});
function ajaxdata(startlimit,endlimit){
$.ajax({
				url:'{{ URL::to("/admin/saverefreshwatermark") }}',
				type:'GET',
				data:{'startlimit':startlimit},
				success:function(ress){
					
				startlimit = parseInt(startlimit)+10;
              			
				endlimit = parseInt(endlimit);	
				var totalmit = startlimit*100;
				
				 var width = totalmit/endlimit; 
				 
                $(".progress-bar").css('width',width);
				
				if(ress!=1){
					
					ajaxdata(startlimit,endlimit);
				}
				}
			});
			
}		
</script>
@include('admin/admin-footer')