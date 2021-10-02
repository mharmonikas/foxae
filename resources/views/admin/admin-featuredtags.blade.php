<?php 
use App\Http\Controllers\MyadminController;
?>
@include('admin/admin-header')
<style>
p.sitelist {
       bottom: 0.6px !important;
   
}

.search-site{
    margin: 15px;
    width: 220px;
    float: right;
}
</style>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $( function() {
    $( "#sortable" ).sortable({
		stop: function(event, div) {
			var i =1;
			var selectedData = new Array();
			var selectedid = new Array();
			$(".sortval").each(function( index ) {
				$( this ).val(i);
				 selectedid.push({value:$( this ).val(),id:$( this ).attr('data-id')});
			i++;
			});
			   updateOrder(selectedid);
		}
});

    function updateOrder(data) {
        $.ajax({
            url:"/admin/changeorder",
            type:'get',
            data:{position: JSON.stringify(data)},
            success:function(){
             location.reload();
            }
        })
    }
	
	

  });
  function removefeature(id){
	var retVal = confirm("Are you sure you want to remove feature?");
      if (retVal == true) {
            $.ajax({
            url:"/admin/removefeature",
            type:'get',
            data:{id: id},
            success:function(){
               location.reload();
				}
			})
		}
		
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
						All Feature Content
					</div>
				</div>
			<div class="ful-top gap-sextion"  id="product_container">
			
			
	<div class="clearfix"></div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="search-site">
			  <form method="get" style="padding:0">
					<select name="search" class="form-control" onchange="this.form.submit()">
						<option value="">Select Domain</option>
						
						@foreach($allvideo['managesites'] as $managesite)
						<option value="{{$managesite->intmanagesiteid}}" @if(!empty($allvideo['search'])) @if($allvideo['search']==$managesite->intmanagesiteid) Selected  @endif @endif >{{$managesite->txtsiteurl}}</option>
						@endforeach
					</select>
			</form>
		</div>
	</div>
</div>
		    <div class="row" id="sortable">
				
			<?php
			$i=1;
			foreach($allvideo['allvideo'] as $myvideo){
		 ?>
			<div class=" ui-state-default" id="remove<?php echo $myvideo->IntId;?>">
			<input type="hidden" class="sortval" data-id="<?php echo $myvideo->IntId;?>" value="<?php echo $i; ?>">
			<div class="mangvid removediv" id="<?php echo $myvideo->IntId;?>">
			<?php 
			if($myvideo->EnumUploadType=='W'){
			if($myvideo->EnumType=='I'){
			?>
			<!--<img src="/<?php echo $myvideo->VchFolderPath;  ?>/<?php echo $myvideo->VchVideothumbnail;  ?>"/>-->
			<div class="watermark-img">
			<img src="/resize1/showimage/{{$myvideo->IntId}}/{{$myvideo->VchResizeimage}}/?={{ $myvideo->intsetdefault}}"/>
			</div>
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
			
		@if(strstr($allvideo['access'], "1"))
					<div class="btn_div">
				
				<a href="javascript:void(0);" onclick="removefeature(<?php echo $myvideo->IntId;?>);" ><i class="fa fa-times" aria-hidden="true"></i></a>
				</div>
				
				@endif
				</div>
				<p class="sitelist">{{$myvideo->sitename}}</p>
			</div>
			<?php }?>
            <!--</table> -->	 		
				
				</div>
			</div>
		</div>
	</div>  
</div>

	
@include('admin/admin-footer')