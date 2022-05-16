@include('admin/admin-header')
<style>
.chosen-container .chosen-results .no-results {
    display: list-item;
    padding: .25rem 0 1rem 1.065rem;
    color: #dc3545;
    width: 20%;
}
.recentlyuploaded{
	width:100%
	display:block;
}
.recentlyuploaded img {
	width: 100%;
}
.selectall {
	float: right;
}
.recentlyuploaded li {
    float: left;
    width: 25%;
    margin-right: 0;
    margin-top: 15px;
    list-style: none;
    height: 315px !important;
    padding: 8px 15px;
	background: #f8fafc;
}
li.active p.sitelist{
	bottom: -15px !important;
}
.page-item{

	width:4%!important;
}
.divpagination{
	width:100% !important;
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
  margin-top: 3px;
}
.maincntf {
	position: absolute;
	background-color: #fff !important;
	z-index: 999;
	width: 100%;
	max-height: 200px;
	overflow: auto;
}

.form-group.searchtitles {
	position: relative;
}
.maincntf ul.allpagecontent {
    padding: 0;
    margin: 0;
}
.maincntf li {
    list-style: none;
    font-size: 18px;
    cursor: pointer;
    padding: 10px 15px;
}
.maincntf li:nth-child(even) {
    background: #f8f8f8;
}
.maincntf {
    position: absolute;
    background-color: #fff!important;
    z-index: 999;
    width: 100%;
    max-height: 200px;
    overflow: auto;
    box-shadow: 1px 3px 7px rgba(0,0,0,0.2);
}
.form_search input {
    padding-left: 36px;
    font-size: 18px;
}
.form_search span {
    position: absolute;
    left: 18px;
    top: 15px;
    font-size: 20px;
    color: #444;
}
.form_search {
    background: #3490dc;
    padding: 10px;
    position: relative;
}
.frmal_sd {
    display: flex;
    align-items: center;
}
.frmal_sd b {
    font-size: 17px;
}
.form_inner_parts {
    background: #f8f8f8;
    margin-bottom: 42px;
    position: relative;
    padding: 20px 20px 40px;
    border: 1px solid #eeeeeee6;
}
.cl_12 h3 {
    padding: 0;
}
.cl_12 {
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
}
ul.recentlyuploaded {
    padding: 0;
    margin: 0 -15px;
}
.up_videos {
    padding: 0 20px 141px;
}
.divpagination {
    margin-top: 34px;
}
span.video-tag {
    border: 1px solid #fff;
    background: #fd6d05;
    padding: 2px;
    font-size: 10px;
    margin: 3px 3px;
    color: #fff;
	font-weight: 700;
}
.pagetitle {
    display: flex;
    flex-wrap: wrap;
	align-items: center;
}
li.srchtitles {
    text-align: left;
}
.chosen-choices span {
    color: #222;
}
.search_col_1 .btn-info:hover, .place_tag_col .btn-info:hover {
    color: #fff !important;

}
label.container-radio {
    font-size: 15px;
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

</style>
<link href="/css/component-chosen.min.css" rel="stylesheet"/>
<script>
	$(document).ready(function(){

		$('.selectedli').click(function(event){

			if(event.shiftKey){
                $(this).toggleClass('active2');
                var index1 = $( "#multiple-select li.active" ).last().index();
                var index2 = $( "#multiple-select li.active2" ).last().index();
                var k =0;
				var s = 0;
				if(index2 > 0){
                $( "#multiple-select li" ).each(function(index) {
					if(s == 0){
                        if(k  > index1){
                            $(this).addClass('active');
                        }
                        if(k == index2){
                            s =1;
                        }
					}
                        k++;
                  });
				}
            }else{
            $(this).toggleClass('active').removeClass('active2');
            }

	var allselectedvideo = [];
	 $( "#multiple-select li.active" ).each(function(index) {
		var videoid = $(this).attr("videoid");
//alert(videoid);
		if($.inArray(videoid, allselectedvideo)!== -1){
			allselectedvideo.splice($.inArray(allselectedvideo,videoid) ,1);
		}else {
			allselectedvideo.push(videoid);
		}
	 });
		var allselectedvideostring = allselectedvideo.join(',');
//alert(allselectedvideostring);
		$('#selectedvideo').val(allselectedvideostring);
		$('#videolists').val(allselectedvideostring);
		});


	 $('.myracecategory').on('change', function() {
		    $('.myracecategory').not(this).prop('checked', false);
		});
		 $('.racecategory1').on('change', function() {
		    $('.racecategory1').not(this).prop('checked', false);
		});

		//myracecategory
	});
</script>
<div class="admin-page-area">
<!-- top navigation -->
         @include('admin/admin-logout')
		<!-- /top navigation -->
        <!-- /top navigation -->
   <div class="">
		<div class="col-md-12 mar-auto">
			<div class="ful-top">
				<div class="back-strip top-side srch-byr">
				<?php

				if(isset($_GET['msg'])){
				if($_GET['msg']=='1'){

				?>
					<div class="alert alert-success">
                  Your Tags successfully Saved
                         </div>
				<?php
				}
				?>
				<?php

				if($_GET['msg']=='2'){ ?>
					<div class="alert alert-success">
                  Your Tags successfully Removed
                         </div>
				<?php
				}
				if($_GET['msg']=='3'){  ?>

				<div class="alert alert-success">
                  Your Domain successfully Saved
                         </div>

				<?php
				}
				if($_GET['msg']=='4'){  ?>

				<div class="alert alert-success">
                  Your Domain successfully Removed
                         </div>

				<?php
				}
				}
				?>

					<div class="inner-top">
						Save Tags
					</div>


				</div>
				<div class="searchtags cat_Search_tag">
				<div class="search_col_1">
				 <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">Search<i class="fa fa-plus"></i></button>
				  @if(strstr($alldata['access'], "1"))
					 <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo3">Add/Remove Domains <i class="fa fa-plus"></i></button>
					 <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo2">Add/Remove Tags <i class="fa fa-plus"></i></button>


					 <a href="/admin/ManageSearchCategory" class="btn btn-info" class="btn btn-primary">Manage Tags</a>
					 <a href="/admin/ManageGroups" class="btn btn-info" class="btn btn-primary">Manage Groups</a>
					 <a href="/admin/ManageSearchSubCategory" class="btn btn-info" class="btn btn-primary">Manage Sub Tags</a>
					 <a href="/admin/exportsearchcategory" class="btn btn-info" class="btn btn-primary"> Export Search Tags</a>
				 @endif

  <div id="demo" class="collapse <?php if(isset($_GET['searchtitle'])){ echo 'show'; } ?>">
   <form action ="/admin/taggedvideo" method="Get" id="mysearchtags" name="searchtag" class="searchtag">
				<div class="form_inner_parts">
					<div class="form-group searchtitles">
					<div class="form_search">
					<input class="form-control" placeholder="Please enter Title and Category" type="search" name="searchtitle" id="mysearchtitle" class="formgroup" autocomplete="off" value="{{@$searchtitle}}"><span><i class="fa fa-search" aria-hidden="true"></i>
</span></div>
					<div class="maincntf">
					<ul class="allpagecontent">
					</ul>
					</div>
					</div>

					<div class="iconsdsf">
					<div class="frmal_sd"><b>Gender</b>

<?php $count=1;  ?>
@foreach ($alldata['alltags'] as $allcategory)
<?php
 $myalltagid = explode(',',$allcategory->tagid);
 $myallcategorytag = explode(',',$allcategory->tagTitle);
 $totalitems = count(explode(',',$allcategory->tagTitle));
?>
@if ($allcategory->IntId == 1)
	<?php
for($i=0;$i<$totalitems;$i++){
	  $selected="";

      $tagrelation = $alldata['allvideorelation'];

	  if(!empty($tagrelation)){


	  $columnname = $allcategory->VchColumnType;
	  $genderid = $tagrelation->$columnname;

     if($genderid==$myalltagid[$i]){

	  $selected="checked";
      }


	 }

	 if($VchGenderTagid==$myalltagid[$i]){
		  $selected="Checked";
	 }
 ?>
  <div class="check-view col-md-2 ">
<input type="checkbox" class="myracecategory" name="searchfilteringcategory[<?php echo $allcategory->VchColumnType; ?>]" category="<?php echo $allcategory->VchColumnType; ?>"  ng-model="gender<?php echo $myalltagid[$i];  ?>" id="boxsearch1-<?php echo $myalltagid[$i];  ?>" ng-click="changegeneder(gender<?php echo $myalltagid[$i];  ?>,<?php echo $myalltagid[$i];  ?>)" value="<?php echo $myalltagid[$i];  ?>" <?php echo $selected; ?>>
  <label for="boxsearch1-<?php echo $myalltagid[$i];  ?>">
  <?php echo $myallcategorytag[$i];?>
  </label>
  </div>
<?php } ?>
@else
	<?php

    $tagrelation = $alldata['allvideorelation'];
	 if(!empty($tagrelation)){
	 $genderid = $tagrelation->$columnname;
    $selected="";
    if($myalltagid[$i]==$genderid){
	 $selected="selected";
   }
 }
?>
<?php if($count==1){ ?>
</div>

 <div class="iconsdsf">
 <label>Domains: </label>
 <ul class="main">
							<li>

							<label class="container-checkbox">All Domains
								  <input type="checkbox"  id="select_all" @if($alldata['getdomains'] == $multisite) checked @endif>
								  <span class="checkmark"></span>
								</label>
							</li>

							<ul style="margin-top: -10px;">
							@foreach($alldata['getdomains'] as $getdomain)
								<li>
								<label class="container-checkbox">{{$getdomain->txtsiteurl}}
								  <input type="checkbox" class="checkbox multisite"  name="multisite[]" value="{{$getdomain->intmanagesiteid}}" @if(@in_array($getdomain->intmanagesiteid,$multisite)) checked @endif>
								  <span class="checkmark"></span>
								</label>
							</li>
							@endforeach


							</ul>
						</ul>
						</div>



<ul class="">
<?php }

?>
	   <li class="col-md-6">
     <div class="image-icon">
	 <div class="dropdown">
<label>	 {{$allcategory->VchTitle}}</label>
<select class="racecategory form-control" name="filteringcategory[<?php echo $allcategory->VchColumnType; ?>]" category="<?php echo $allcategory->VchColumnType; ?>">
    <option value="0">All <?php echo $allcategory->VchTitle; ?></option>
   <?php
 for($i=0;$i<$totalitems;$i++){
	 $selected="";
	  $tagrelation = $alldata['allvideorelation'];
	  if(!empty($tagrelation)){
	 $columnname = $allcategory->VchColumnType;
    $genderid = $tagrelation->$columnname;

 if($genderid==$myalltagid[$i]){
	$selected="selected";
 }
}
if(!empty($VchCategoryTagID)){
	if($VchCategoryTagID== $myalltagid[$i]){
		$selected="Selected";
	}
}
if(!empty($VchRaceTagID)){
	if($VchRaceTagID== $myalltagid[$i]){
		$selected="Selected";
	}
}

?>
<option <?php echo $selected; ?> value="<?php echo $myalltagid[$i];  ?>"><?php echo $myallcategorytag[$i];  ?></option>
<?php } ?>
</select>

		</div>
		</div>
		 </li>
		 <?php $count++;
 ?>
@endif
@endforeach
</ul>


 </div>


					<div class="form-group">
                      <button type="submit" class="btn btn-primary">Search</button>
                      </div>
					  </div>
				</form>
  </div>

				</div>
				</div>

				<div class="searchtags secnd_tags">
				<div class="place_tag_col">

  <div id="demo2" class="collapse">
   <form action ="/admin/posttaggedvideo" method="POST" id="mysearchtag" name="searchtag" class="searchtag">
					<div class="form_inner_parts">
							@csrf
						<div class="form-group">
						<?php
						if(isset($_GET['videoid'])){
						$videoid = $_GET['videoid'];
						}else {
							$videoid = '';
						}
						$allsearchtags = array();
						$alltagrelation = $alldata['allsearchvideorelation'];
						foreach($alltagrelation as $alltagsusers){
						array_push($allsearchtags,$alltagsusers->IntCategorid);
						}
					    ?>
							<label>	 Tags </label>
						<input type="hidden" name="selectedvideo" value="<?php echo $videoid; ?>" id="selectedvideo">
							<select id="multiple" name="tags[]" class="form-control form-control-chosen" data-placeholder="Place tags" multiple>

								<?php
									foreach($alldata['searchtags'] as $videouploads){
									$selected='';
									if(in_array($videouploads->IntId,$allsearchtags)){
										$selected="selected";

									}
									?>

									<option <?php echo $selected; ?> value="<?php echo $videouploads->IntId; ?>"><?php echo $videouploads->VchCategoryTitle; ?></option>
								<?php } ?>
							</select>

						</div>

						<label>	 Groups </label>
						<select  name="groupid[]" class="form-control form-control-chosen-2" data-placeholder="Place group" multiple>
							@foreach($alldata['getgrouplists'] as $getgrouplist)
								<option value="{{$getgrouplist->intgroupid}}">{{$getgrouplist->groupname}}</option>
							@endforeach
						</select>



						<div class="iconsdsf">
					<div class="frmal_sd"><b>Gender</b>
<?php $count=1;  ?>
@foreach ($alldata['alltags'] as $allcategory)
<?php
 $myalltagid = explode(',',$allcategory->tagid);
 $myallcategorytag = explode(',',$allcategory->tagTitle);
 $totalitems = count(explode(',',$allcategory->tagTitle));
?>
@if ($allcategory->IntId == 1)
	<?php
for($i=0;$i<$totalitems;$i++){
	  $selected="";

      $tagrelation = $alldata['allvideorelation'];

	  if(!empty($tagrelation)){


	  $columnname = $allcategory->VchColumnType;
	 $genderid = $tagrelation->$columnname;

     if($genderid==$myalltagid[$i]){

	  $selected="checked";
      }
	 }
 ?>
  <div class="check-view col-md-2 ">
<input type="checkbox" class="racecategory1" name="searchfilteringcategory[<?php echo $allcategory->VchColumnType; ?>]" category="<?php echo $allcategory->VchColumnType; ?>"  ng-model="gender<?php echo $myalltagid[$i];  ?>" id="box1-<?php echo $myalltagid[$i];  ?>" ng-click="changegeneder(gender<?php echo $myalltagid[$i];  ?>,<?php echo $myalltagid[$i];  ?>)" value="<?php echo $myalltagid[$i];  ?>" <?php echo $selected; ?>>
  <label for="box1-<?php echo $myalltagid[$i];  ?>">
  <?php echo $myallcategorytag[$i];?>
  </label>
  </div>
<?php } ?>
@else
	<?php

    $tagrelation = $alldata['allvideorelation'];
	 if(!empty($tagrelation)){
	 $genderid = $tagrelation->$columnname;
    $selected="";
    if($myalltagid[$i]==$genderid){
	 $selected="selected";
   }
 }
?>
<?php if($count==1){ ?>
</div>

<ul class="">
<?php } ?>
	   <li class="col-md-6">
     <div class="image-icon">
	 <div class="dropdown">
<label>	 {{$allcategory->VchTitle}}</label>
<select class="racecategory form-control" name="filteringcategory[<?php echo $allcategory->VchColumnType; ?>]" category="<?php echo $allcategory->VchColumnType; ?>">
    <option value="0">All <?php echo $allcategory->VchTitle; ?></option>
   <?php
 for($i=0;$i<$totalitems;$i++){
	 $selected="";
	  $tagrelation = $alldata['allvideorelation'];
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
		 <?php $count++;
 ?>
@endif
@endforeach
</ul>
 </div>
 <div class="iconsdsf">
         <label>Feature Option</label>
         <label class="container-checkbox">
         Feature
         <input type="checkbox" name="feature" class="featurey" value="1">
         <span class="checkmark"></span>
         </label>
         </div>
		 <div class="iconsdsf">
        <label>Content Category</label>
		 <div class="flex-radio">
		<label class="container-radio">
         Standard
         <input type="radio" name="content_category" class="content-category" value="1" required checked>

         </label>

		 <label class="container-radio">
         Premium
         <input type="radio" name="content_category" class="content-category" value="2" required>

         </label>

		 <label class="container-radio">
         Deluxe
         <input type="radio" name="content_category" class="content-category" value="3" required>

         </label>
		 </div>
         </div>
<div class="form-group">
<button type="submit" name="action" value="addtags" class="btn btn-primary">Add Tags</button>
<button type="submit" id="remove" name="action" value="removetags" class="btn btn-primary">Remove Tags</button>
</div>
</div>
</form>
  </div>

    <div id="demo3" class="collapse">
   <form action ="/admin/adddomaintovideo" method="POST" id="myvideodomain" name="searchtag" class="searchtag">
					<div class="form_inner_parts">
							@csrf
							<?php
						if(isset($_GET['videoid'])){
						$videoid = $_GET['videoid'];
						}else {
							$videoid = '';
						} ?>
						<input type="hidden" name="selectedvideo" value="" id="videolists">
					  <div class="iconsdsf">
					 <label>Domains: </label>
					 <ul class="main">
						<li>

							<label class="container-checkbox">All Domains
								  <input type="checkbox"  id="select_all2" @if(($alldata['getdomains']) == ($multisite)) checked @endif>
								  <span class="checkmark"></span>
								</label>
							</li>

							<ul style="margin-top: -10px;">
							@foreach($alldata['getdomains'] as $getdomain)
								<li>
								<label class="container-checkbox">{{$getdomain->txtsiteurl}}
								  <input type="checkbox" class="checkbox2 multisite"  name="multisitename[]" value="{{$getdomain->intmanagesiteid}}" @if(@in_array($getdomain->intmanagesiteid,$multisite)) checked @endif>
								  <span class="checkmark"></span>
								</label>
							</li>
							@endforeach


							</ul>
						</ul>
						</div>

<div class="form-group">
<button type="submit" name="action" value="adddomain" class="btn btn-primary">Add Domain</button>
<button type="submit" id="remove" name="action" value="removedomain" class="btn btn-primary">Remove Domain</button>
</div>
</div>
</form>
  </div>

				</div>
				</div>
			</div>
		</div>
		<div class="cl_12">
			<h3>Recently Uploaded Content</h3>
		<div class="selectall">
<input type="checkbox" class="racecategory" id="boxcheck">
  <label for="boxcheck">
  Select All
    </label>
	</div>


	</div>
	<div class="cl_12">

		<div class="selectall" >
		<form action ="/admin/taggedvideo" method="Get" style="padding:0;">
		<input type="hidden" name="searchtitle" value="@if(!empty($searchtitle)){{$searchtitle}}@endif" >
		@if(!empty($VchGenderTagid))
		<input type="hidden" name="searchfilteringcategory[VchGenderTagid]" value="@if(!empty($VchGenderTagid)){{$VchGenderTagid}}@endif" >
		@endif

		<input type="hidden" name="filteringcategory[VchRaceTagID]" value="
		@if(!empty($VchRaceTagID)){{$VchRaceTagID}}@endif" >


		<input type="hidden" name="filteringcategory[VchCategoryTagID]" value="@if(!empty($VchCategoryTagID)){{$VchCategoryTagID}}@endif" >

	 <label class="dropdownlabel info-label show">Number of Show</label>
		<select class="showitemperpage" name="show" onchange="this.form.submit();">
			<option value="15" @if(!empty($show))@if($show=='15')Selected @endif @endif>15</option>
			<option value="50" @if(!empty($show))@if($show=='50')Selected @endif @endif>50</option>
			<option value="100" @if(!empty($show))@if($show=='100')Selected @endif @endif>100</option>
			<option value="150" @if(!empty($show))@if($show=='150')Selected @endif @endif>150</option>
			<option value="200" @if(!empty($show))@if($show=='200')Selected @endif @endif >200</option>
			<option value="500" @if(!empty($show))@if($show=='500')Selected @endif @else Selected @endif >500</option>
			<option value="1000" @if(!empty($show))@if($show=='1000')Selected @endif @endif >1000</option>
			<option value="all" @if(!empty($show))@if($show=='all')selected @endif @endif >All</option>

		 </select>
	 </form>
	</div>


	</div>
  <div class="up_videos">
			<ul class="recentlyuploaded" id="multiple-select">
					<?php
					// echo "<pre>";
					// print_r($alldata['allvideo']);
					// exit;
					foreach($alldata['allvideo'] as $videouploads){
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
						<!--<img src="/<?php echo $videouploads->VchFolderPath; ?>/<?php echo $videouploads->VchVideoName; ?>" width="100%" height="200px">-->
						<img src="/resize1/showimage/{{$videouploads->videoid}}/{{$managesiteid}}/{{$videouploads->VchResizeimage}}"/>
						<div class="pagetitle">
						<?php echo $videouploads->VchTitle;  ?>


						<?php
						$groupcategorys = explode(",",$videouploads->group_category);
						foreach($groupcategorys as $gkey => $gvalue){
							echo "<span class='video-tag'>".$gvalue."</span>";
						}
						?>

						</div>


						<p class="sitelist">{{$videouploads->sitename}}</p>



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
						<?php
						$groupcategorys = explode(",",$videouploads->group_category);
						foreach($groupcategorys as $gkey => $gvalue){
							echo "<span class='video-tag'>".$gvalue."</span>";
						}
						?>
						</div>
						<?php }
					}else {
						?>
						@if(!empty($videouploads->VchFolderPath))
							<video width="100%" height="190px" controls>
                         <source src="/<?php echo $videouploads->VchFolderPath; ?>/<?php echo $videouploads->VchVideoName; ?>" type="video/mp4">
                        <source src="/<?php echo $videouploads->VchFolderPath; ?>/<?php echo $videouploads->VchVideoName; ?>" type="video/ogg">
                             </video>
						@else


						 <iframe src="<?php echo $videouploads->vchgoogledrivelink; ?>" width="100%" height="190px"></iframe>
						 @endif
						<div class="pagetitle">
						<?php echo $videouploads->VchTitle;  ?>
						<?php
						$groupcategorys = explode(",",$videouploads->group_category);
						foreach($groupcategorys as $gkey => $gvalue){
							echo "<span class='video-tag'>".$gvalue."</span>";
						}
						?>
						</div>
					<?php } ?>

					</li>
					<?php

					} ?>

					<div class="clearfix"></div>

				</ul>

		<div class="divpagination">
		{{ $alldata['allvideo']->links() }}


					</div>
		<div class="clearfix"></div>


				</div>

		</div>

	</div>
</div>
	<style>
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
@media(min-width:1350px){
	.recentlyuploaded img {
    width: 100%;
    max-height: calc(200px - 10px);
    min-height: calc(200px - 10px);
}
}
</style>

<script src="/js/choosen7.js?v=1">
</script>
<script type="text/javascript">
 //addcategory
 $(document).ready(function(){
  $("#mysearchtitle").keyup(function(e){

  var keyword = $(this).val();
   $.ajax({
    type: 'GET',
    url: '/getkeywords?type=&category=1&Tagid=[]&searchtext='+keyword+'&startlimit=0',
    dataType: 'json',
    success: function (data) {
	$('.allpagecontent').html('');
    $.each(data, function( index, value ) {
	$('.allpagecontent').append('<li class="srchtitles">'+value.VchCategoryTitle+'</li>');
	 });
    }
});
//alert(e.keyCode);
 if(e.keyCode === 27) {
            $(".allpagecontent").hide();
        }else{

		 $(".allpagecontent").show();
		}
 });
$('.searchtags').on('click','.srchtitles',function(){
	var searchingtext = $(this).text();
    $('#mysearchtitle').val(searchingtext);
	$('.allpagecontent').html('');
});


 $('#mysearchtag').submit(function(){
   var selectedvideo = $('#selectedvideo').val();
   if(selectedvideo==''){
	 alert("Please select atleast one video");
	 return false
	}
 });
 $('#myvideodomain').submit(function(){
   var selectedvideo = $('#selectedvideo').val();
   if(selectedvideo==''){
	 alert("Please select atleast one video");
	 return false
	}
 });

   $("#remove").click(function(){
    if (!confirm("Are you sure you want to remove tags from selected videos.")){
      return false;
    }
  });


 $('#boxcheck').click(function(){
if ($(this).prop('checked')==true){
var allselectedvideo = [];
$('#selectedvideo').val('');
$('#videolists').val('');
 $('.selectedli').removeClass("active").removeClass("active2");
 $('.selectedli').each(function(){

  $(this).addClass("active");
	   var videoid = $(this).attr("videoid");

		if($.inArray(videoid, allselectedvideo)!== -1){
			allselectedvideo.splice($.inArray(allselectedvideo,videoid) ,1);
		}else {
			allselectedvideo.push(videoid);
      }
		var allselectedvideostring = allselectedvideo.join(',');

		$('#selectedvideo').val(allselectedvideostring);

		$('#videolists').val(allselectedvideostring);


 });
}else {
	allselectedvideo = [];
	$('#selectedvideo').val('');
	$('#videolists').val('');
 $('.selectedli').removeClass("active");

}

 });







 });
 $('.searchtags').on('click','.addcategory',function(){
	var searchtext = $('.Place_tags').val();
	var grouptag = $('.Place_group').val();
	var categorytitle = searchtext;

	var category = '';
    var parentcat = '';
    var token= $('meta[name="csrf_token"]').attr('content');
	$.ajax({
				url:'{{ URL::to("/admin/addeditsearchcategory") }}',
				type:'POST',
				data:{'categorytitle':categorytitle,'grouptag':grouptag,'category':category,'parentcat':parentcat,'_token':token},
				//data:{'categorytitle':categorytitle,'categorytitle':grouptag,'grouptag':'','parentcat':'','_token':token},
				success:function(ress){
			if(categorytitle != "" && categorytitle != "Place tags"){
			$.each(ress, function(index, value) {

				   $('.form-control-chosen').append($("<option/>", {
					value:value.lastinsertid,
					 text:value.vchtitle
					}));
				 $(".form-control-chosen option[value='"+value.lastinsertid+"']").prop("selected", true);
				 $('.form-control-chosen').trigger("chosen:updated");
				});
			}else if(categorytitle != "" && categorytitle != "Place group"){
				$.each(ress, function(index, value) {
				 $('.form-control-chosen-2').append($("<option/>", {
					value:value.lastinsertid,
					 text:value.vchtitle
					}));
				 $(".form-control-chosen-2 option[value='"+value.lastinsertid+"']").prop("selected", true);
				 $('.form-control-chosen-2').trigger("chosen:updated");
				});
			}

   }
			});
	});

	$('.form-control-chosen').chosen({
			allow_single_deselect: false,
		width: '100%'
	});
	$('.form-control-chosen-2').chosen({
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



    $('#select_all2').on('click',function(){
        if(this.checked){
            $('.checkbox2').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox2').each(function(){
                this.checked = false;
            });
        }
    });

	$('.checkbox2').on('click',function(){
        if($('.checkbox2:checked').length == $('.checkbox2').length){
            $('#select_all2').prop('checked',true);
        }else{
            $('#select_all2').prop('checked',false);
        }
    });
});


</script>

@include('admin/admin-footer')
