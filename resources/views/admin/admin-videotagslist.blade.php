<ul class="recentlyuploaded">
					<?php 
					foreach($allvideo['allvideo'] as $videouploads){
						if(isset($_GET['videoid'])){
							$class="active";
						}else {
							$class="";
						}
						
					?>
					<li class="selectedli <?php echo $class; ?>" videoid="<?php echo $videouploads->videoid; ?>">
						
						<?php 
						if($videouploads->EnumUploadType=='W'){
						if($videouploads->EnumType=='I'){
						?>
						<div class="activesclass"> 
						<img src="/<?php echo $videouploads->VchFolderPath; ?>/<?php echo $videouploads->VchVideothumbnail; ?>" width="100%" height="200px">
						
						<div class="pagetitle">
						<?php echo $videouploads->VchTitle;  ?>
						</div>
						
						</div>
						<?php 
						}else {
						?>
						
							<video width="100%" height="190px" controls>
                         <source src="/<?php echo $videouploads->VchFolderPath; ?>/<?php echo $videouploads->VchVideoName; ?>" type="video/mp4">
                        <source src="/<?php echo $videouploads->VchFolderPath; ?>/<?php echo $videouploads->VchVideoName; ?>" type="video/ogg">
                             </video>
						<div class="pagetitle">
						<?php echo $videouploads->VchTitle;  ?>
						</div>
						<?php } 
					}else {
						?>	
						 <iframe src="<?php echo $videouploads->vchgoogledrivelink; ?>" width="100%" height="190px"></iframe>
						<div class="pagetitle">
						<?php echo $videouploads->VchTitle;  ?>
						</div>
					<?php } ?>	
						
					</li>
					<?php 
					
					} ?>
<div class="clearfix"></div>
	</ul>
				
		<div class="divpagination">
					{{ $allvideo['allvideo']->appends(Illuminate\Support\Facades\Input::except('page'))->links() }}
					
					</div>