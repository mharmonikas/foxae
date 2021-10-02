<?php 
use App\Http\Controllers\MyadminController;
?>
<style>
.deletecontent {
	color: red;
	font-size: 25px;
	margin-left: 25px;
}
.forselctionactive {
	border: solid 4px #e66b3e;
}
.mangvid.removediv img {
    height: 180px;
}
.btn_div {
    display: block;
    text-align: center;
    padding: 0 !important;
    position: absolute;
    bottom: 45px;
    margin: 0 auto;
    left: 0;
    right: 0;
}
span.video-tag {
    background: #e66b3e;
    border: 1px solid #fff;
    background: #e66b3e;
    padding: 2px 5px 2px 5px;
    font-size: 10px;
    margin: 3px 3px;
    color: #fff;
    font-weight: 700;
    border-radius: 2px;
}
.pagnation-form form {
    width: 50%;
}

#product_container .mangvid.removediv {
    min-height: 360px !important;
}
</style>
<h3>All Content</h3>
			<div id="msg"></div>
			<div class="">
			<div class="pagnation-form">
				<form>
					<div class="form-group">
					<label class="dropdownlabel info-label show">Number of Show</label>
					  <select    id="changepid" >
						<option @if($perpage == '50') Selected @endif>50</option>
						<option @if($perpage == '100') Selected @endif>100</option>
						<option @if($perpage == '150') Selected @endif>150</option>
						<option @if($perpage == '200') Selected @endif>200</option>
						<option @if($perpage == '500') Selected @endif @if(empty($perpage)) Selected @endif>500</option>
						<option @if($perpage == '1000') Selected @endif>1000</option>
					  </select>
					</div>
				</form>
			</div>
			@if(strstr($allvideo['access'], "1"))
			<div class="selectall">
<input type="checkbox" class="racecategory" id="boxcheck"> 
  <label for="boxcheck">
  Select All
    </label>
	<a href="javascript:void(0);" class="deletecontent"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
	</div>
	@endif
	</div>
	<div class="clearfix"></div>
		    <div class="row">

			<?php
			if(isset($_GET['page'])){
				$pagenumber = $_GET['page']-1;
				$pagenumber = 15*$pagenumber;
				
			}else {
				$pagenumber = 1;
			}
			
             foreach($allvideo['allvideo'] as $myvideo){
		 ?>
			<div class="col-md-3" id="remove<?php echo $myvideo->IntId;?>">
			<div class="mangvid removediv" id="<?php echo $myvideo->IntId;?>">
			<?php 
			if($myvideo->EnumUploadType=='W'){
			if($myvideo->EnumType=='I'){
			?>
			<!--<img src="/<?php echo $myvideo->VchFolderPath;  ?>/<?php echo $myvideo->VchVideothumbnail;  ?>"/>-->
			<img src="/resize1/showimage/{{$myvideo->IntId}}/{{$allvideo['selectserver']->intmanagesiteid}}/{{$myvideo->VchResizeimage}}/?={{ $myvideo->intsetdefault}}"/>
			<?php }else { ?>
			<video width="100%" height="100%" controls>
            <source src="/<?php echo $myvideo->VchFolderPath; ?>/<?php echo $myvideo->VchVideoName; ?>" type="video/mp4">
              <source src="/<?php echo  $myvideo->VchFolderPath; ?>/<?php echo $myvideo->VchVideoName; ?>" type="video/ogg">
            </video>
			<?php } 
			
			}else { ?>
			@if($myvideo->VchFolderPath == "")
				<iframe src="<?php echo $myvideo->vchgoogledrivelink; ?>" width="100%" height="100%"></iframe>
			@else
				
			
			<video width="100%" height="100%" controls>
            <source src="/<?php echo $myvideo->VchFolderPath; ?>/<?php echo $myvideo->VchVideoName; ?>" type="video/mp4">
              <source src="/<?php echo  $myvideo->VchFolderPath; ?>/<?php echo $myvideo->VchVideoName; ?>" type="video/ogg">
            </video>
			@endif
				 
				
		<?php		
			}
			?>
				<h3><?php echo $myvideo->VchTitle;  ?></h3>
				<p class="sitelist" style="bottom: 0; width: 100%;">{{$myvideo->sitename}}</p>
				<div class="tags">
				
				<?php 
						$groupcategorys = explode(",",$myvideo->group_category);
						foreach($groupcategorys as $gkey => $gvalue){
							echo "<span class='video-tag'>".$gvalue."</span>";
						}
						?>
				
				<?php
				// if(!empty($myvideo->Gendercategory)){
					// echo '<span>'.$myvideo->Gendercategory.'</span>';
					
				// }
				// if(!empty($myvideo->Racecategory)){
					// echo '<span>'.$myvideo->Racecategory.'</span>';
					
				// }if(!empty($myvideo->category)){
					// echo '<span>'.$myvideo->category.'</span>';
					
				// }
				
				 ?>
				 </span>
				 	
				 </div>
				 
				<div class="searchtags">
			<?php	
			// $searchkeyword = '';
			// $getalltags =  MyadminController::getsearchingtags($myvideo->IntId); 
			
			// foreach($getalltags as $results){
			 // $searchkeyword .= '<span>'.$results->VchSearchcategorytitle."</span>";
			// }
			// echo $searchkeyword;
			?>
			
				</div>
				 @if(strstr($allvideo['access'], "1"))
				<div class="btn_div">
				<a href="/admin/editvideo?editvideo=<?php echo $myvideo->IntId;?>"  >Edit</a>
				<a class="replace" href="/admin/replace/<?php echo $myvideo->IntId;?>"  >Replace</a>
				<a href="javascript:void(0);" onclick="deletevideo(<?php echo $myvideo->IntId;?>);" >Delete</a>
				
				
				</div>	
				@endif
				</div>
			</div>
			 <?php 
			 
			 $pagenumber++;
			 } ?>
            <!--</table> -->	 		
				
				</div>
				{{ $allvideo['allvideo']->links() }}
				
<script>
$(document).ready(function(){
	var alldeleted = [];
$('#boxcheck').click(function(){
 if($(this).prop("checked") == true){
 $('.removediv').addClass('forselctionactive'); 
 }else {
	$('.removediv').removeClass('forselctionactive');  
	 
 }
alldeleted = [];
$('.forselctionactive').each(function( index ) {
var removeid = $(this).attr('id');
alldeleted.push(removeid);
});
});	

$('.removediv').click(function(){
var removediv = $(this).attr('id');	
$(this).toggleClass('forselctionactive'); 
alldeleted = [];
$('.forselctionactive').each(function( index ) {
var removeid = $(this).attr('id');
alldeleted.push(removeid);
});	
});

$('.deletecontent').click(function(){
	var jsonString = JSON.stringify(alldeleted);
	for(var i=0;i<alldeleted.length; i++){
		var deletedvideo = alldeleted[i]; 
	 $.ajax({
               type:'Get',
               url:'/admin/managevideosection',
               data:{deletevideoid:alldeleted[i]},
               success:function(data) {
				    $("#msg").fadeIn();
					
					$('#remove'+deletedvideo).remove();
					
                  $("#msg").html("Video successfully deleted");
					
				 window.location.href="";
				  setTimeout(function(){
				  
				   $("#msg").fadeOut();
				   }, 3000);
               }
            });
	}
});
});
</script>				
<script type="text/javascript">
        $(document).ready(function() {
            $('#changepid').on('change', function() {
				var gid = $(this).val();
				$("#perpage").val(gid);
				$("#mysearchtags").submit();
				//alert(gid);
               // this.form.submit();
            });
        });
        </script>