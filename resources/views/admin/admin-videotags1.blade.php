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
    margin-top: 10px;
    list-style: none;
    max-height: 280px;
    padding: 0 15px;
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
.chosen-choices span {
    color: #222;
}

</style>
<link href="/css/component-chosen.min.css" rel="stylesheet"/>
<script>
 $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            }else{
                getData(page);
            }
        }
    });
$(document).ready(function()
{
	
	$("#mysearchtags").submit(function(e){
   var formdata = $(this).serialize(); // here $(this) refere to the form its submitting
    $.ajax({
        type: 'GET',
        url: "{{ url('/') }}"+'/admin/taggedvideo1',
		 datatype: "html",
        data: formdata, // here $(this) refers to the ajax object not form
        success: function (data) {
            $("#product_container").empty().html(data);
               $('html, body').animate({
        scrollTop: $("#product_container").offset().top
    }, 2000);
        },
    });
    e.preventDefault(); 
});
	
	
	
	
	
	
	
	
	
	
	
	
	
     $(document).on('click', '.pagination a',function(event)
    {
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
		var href = $(this).attr("href");
		
        event.preventDefault();
        var myurl = $(this).attr('href');
       var page=$(this).attr('href').split('page=')[1];
       getData(href);
    });
});
function getData(page){
        $.ajax(
        {
            url: page,
            type: "get",
            datatype: "html",
            // beforeSend: function()
            // {
            //     you can show your loader 
            // }
        })
        .done(function(data)
        {
            console.log(data);
            
            $("#product_container").empty().html(data);
           $('html, body').animate({
        scrollTop: $("#product_container").offset().top
    }, 2000);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError)
        {
              //alert('No response from server');
        });
}


	$(document).ready(function(){
		var allselectedvideo = [];	
			//$('.selectedli').click(function(){
		$(document).on('click', '.selectedli',function(){
			
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
	
	 $('.racecategory').on('change', function() {
		    $('.racecategory').not(this).prop('checked', false);  
		});
	
	
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
					<div class="inner-top">
						Save Tags
					</div>
					<a href="/admin/ManageSearchCategory" class="btn btn-primary">Manage Tags</a>
				</div>
				<div class="searchtags">
				<form action ="/admin/taggedvideo1" method="Get" id="mysearchtags" name="searchtag" class="searchtag">
				<div class="form_inner_parts">
					<div class="form-group searchtitles">
					<div class="form_search"><input class="form-control" placeholder="Please enter Title and Category" type="search" name="searchtitle" id="mysearchtitle" class="formgroup" autocomplete="off"><span><i class="fa fa-search" aria-hidden="true"></i>
</span></div>
					<div class="maincntf">
					<ul class="allpagecontent">
					</ul>
					</div>
					</div>
					
					<div class="iconsdsf">
					<div class="frmal_sd"><b>Gender</b>
<?php $count=1;  ?>
@foreach ($allvideo['alltags'] as $allcategory)
<?php 
 $myalltagid = explode(',',$allcategory->tagid);
 $myallcategorytag = explode(',',$allcategory->tagTitle);
 $totalitems = count(explode(',',$allcategory->tagTitle));
?>
@if ($allcategory->IntId == 1)
	<?php 
for($i=0;$i<$totalitems;$i++){
	  $selected="";
	
      $tagrelation = $allvideo['allvideorelation'];
	
	  if(!empty($tagrelation)){
		  
		 
	  $columnname = $allcategory->VchColumnType;	 
	 $genderid = $tagrelation->$columnname;
   
     if($genderid==$myalltagid[$i]){
	 
	  $selected="checked"; 
      }
	 }	  
 ?>
  <div class="check-view col-md-1 "> 
<input type="checkbox" class="racecategory" name="searchfilteringcategory[<?php echo $allcategory->VchColumnType; ?>]" category="<?php echo $allcategory->VchColumnType; ?>"  ng-model="gender<?php echo $myalltagid[$i];  ?>" id="box1-<?php echo $myalltagid[$i];  ?>" ng-click="changegeneder(gender<?php echo $myalltagid[$i];  ?>,<?php echo $myalltagid[$i];  ?>)" value="<?php echo $myalltagid[$i];  ?>" <?php echo $selected; ?>> 
  <label for="box1-<?php echo $myalltagid[$i];  ?>">
  <?php echo $myallcategorytag[$i];?>
  </label>
  </div>
<?php } ?>		
@else
	<?php 
  
    $tagrelation = $allvideo['allvideorelation'];
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
<ul class="row">
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
	  $tagrelation = $allvideo['allvideorelation'];
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

					<div class="form-group">
                      <button type="submit" class="btn btn-primary">Search</button>
                      </div>
					  </div>
				</form>
				</div>	
				
				<div class="searchtags secnd_tags">
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
						$alltagrelation = $allvideo['allsearchvideorelation'];
						foreach($alltagrelation as $alltagsusers){
						array_push($allsearchtags,$alltagsusers->IntCategorid);
						}
					    ?>
						<input type="hidden" name="selectedvideo" value="<?php echo $videoid; ?>" id="selectedvideo">
							<select id="multiple" name="tags[]" class="form-control form-control-chosen" data-placeholder="Place tags" multiple>
								<option></option>
								<?php 
									foreach($allvideo['searchtags'] as $videouploads){
									$selected='';
									if(in_array($videouploads->IntId,$allsearchtags)){
										$selected="selected";
										
									}
									?>
                                     									
									<option <?php echo $selected; ?> value="<?php echo $videouploads->IntId; ?>"><?php echo $videouploads->VchCategoryTitle; ?></option>
								<?php } ?>
							</select>
						</div>
					
						
						
						<div class="iconsdsf">
					<div class="frmal_sd"><b>Gender</b>
<?php $count=1;  ?>
@foreach ($allvideo['alltags'] as $allcategory)
<?php 
 $myalltagid = explode(',',$allcategory->tagid);
 $myallcategorytag = explode(',',$allcategory->tagTitle);
 $totalitems = count(explode(',',$allcategory->tagTitle));
?>
@if ($allcategory->IntId == 1)
	<?php 
for($i=0;$i<$totalitems;$i++){
	  $selected="";
	
      $tagrelation = $allvideo['allvideorelation'];
	
	  if(!empty($tagrelation)){
		  
		 
	  $columnname = $allcategory->VchColumnType;	 
	 $genderid = $tagrelation->$columnname;
   
     if($genderid==$myalltagid[$i]){
	 
	  $selected="checked"; 
      }
	 }	  
 ?>
  <div class="check-view col-md-1 "> 
<input type="checkbox" class="racecategory" name="searchfilteringcategory[<?php echo $allcategory->VchColumnType; ?>]" category="<?php echo $allcategory->VchColumnType; ?>"  ng-model="gender<?php echo $myalltagid[$i];  ?>" id="box1-<?php echo $myalltagid[$i];  ?>" ng-click="changegeneder(gender<?php echo $myalltagid[$i];  ?>,<?php echo $myalltagid[$i];  ?>)" value="<?php echo $myalltagid[$i];  ?>" <?php echo $selected; ?>> 
  <label for="box1-<?php echo $myalltagid[$i];  ?>">
  <?php echo $myallcategorytag[$i];?>
  </label>
  </div>
<?php } ?>		
@else
	<?php 
  
    $tagrelation = $allvideo['allvideorelation'];
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
<ul class="row">
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
	  $tagrelation = $allvideo['allvideorelation'];
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
<div class="form-group">
<button type="submit" class="btn btn-primary">Save</button>
</div>
</div>						
</form>
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
  <div class="up_videos">
  <div id="product_container">
			  @include('admin.admin-videotagslist')
  </div>			  
		<div class="clearfix"></div>
					
	
				</div>
				
		</div>
		
	</div>  
</div>
	
	 
<script src="/js/choosen5.js?v=2">
</script>
<script type="text/javascript">
 //addcategory
 $(document).ready(function(){
  $("#mysearchtitle").keyup(function(){
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
 
 
 $('#boxcheck').click(function(){
if ($(this).prop('checked')==true){ 
var allselectedvideo = [];	
$('#selectedvideo').val('');	
 $('.selectedli').removeClass("active"); 
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
	 
	 
 });
}else {
	allselectedvideo = [];	
	$('#selectedvideo').val('');	
 $('.selectedli').removeClass("active"); 
	
}
	 
 });
 
 
 
 
 
 
 
 });
 $('.searchtags').on('click','.addcategory',function(){
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