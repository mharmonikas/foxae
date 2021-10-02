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
 .ful-top.gap-sextion {
  padding: 50px;
}
.gap-sextion table td {
  border: 1px solid #222;
  padding: 12px;
}
.gap-sextion table {
  width: 100%;
}
.gap-sextion .videoimages {
  height: auto;
  width: 180px;
}
.pagination {
  margin-top: 20px;
  text-align: center;
}
h3 {
    padding: 12px 0;
}
.bg_parts {
  background: #3c8dbc none repeat scroll 0 0;
  color: #fff;
  font-size: 15px;
  font-weight: 600;
}
table p {
  overflow-wrap: break-word;
  width: 63%;
}
.ndfHFb-c4YZDc-i5oIFb.ndfHFb-c4YZDc-e1YmVc .ndfHFb-c4YZDc-Wrql6b {
	background: rgba(0,0,0,0.75);
	height: 40px;
	top: 12px;
	left: auto;
	padding: 0;
	display: none !important;
}
.mangvid {
    border: 1px solid #ddd;
    margin: 0 0 30px;
}
.mangvid img {max-width: 100%;}
.mangvid h3 {
    display: block;
    text-align: center;
    font-size: 16px;
    margin: 0;
    font-weight: 600;
    padding: 6px 0;
    background: #000;
    color: #fff;
}
.btn_div {
    display: block;
    text-align: center;
    padding: 20px 0 10px;
}
.btn_div a {
    display: inline-block;
    background: #d01616;
    color: #fff;
    padding: 4px 9px;
    font-size: 12px;
    border-radius: 4px;
    font-weight: 500;
}
.replace {
background: #087921 !important;
 }
.btn_div a:first-child {
    background: #087921;
}
.tags {
    display: block;
    padding: 10px 10px 5px;
}
.searchtags {
    display: block;
    padding: 0 10px;
}
.tags span, .searchtags span {
    display: inline-block;
    margin: 0 3px 0 0;
  <!--background: #e66b3e;-->
    padding: 3px 6px;
    color: #000;
	    margin-top: 2px;
}



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
    left: 10px !important;
    top: 10px !important;
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
div#msg {
    
    text-align: center;
    color: #721c24;
    padding: .75rem 1.25rem;
    border-radius: 2px;
    font-size: 17px;
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
</style>
<div class="admin-page-area">
<!-- top navigation -->
         @include('admin/admin-logout')
		<!-- /top navigation -->
        <!-- /top navigation -->
   <div class="">
		<div class="col-md-12 mar-auto">
		<div class="back-strip top-side srch-byr">
					<div class="inner-top">
						Manage Content
					</div>
					<div class="space-content" style="float:right;">
					<ul style="list-style-type:none">
					<li><b>Total Storage - </b>{{$allvideo['space_total']}} MB</li>
					<li><b>Storage Used - </b>{{$allvideo['used_space']}} MB</li>
					<li><b>Free Storage -</b> {{$allvideo['space_available']}} MB</li>
				 </ul>
					</div>
				</div>
				
			<div class="searchtags">
				<form action ="/admin/managevideosection" method="Get" id="mysearchtags" name="searchtag" class="searchtag">
				<div class="form_inner_parts">
					<div class="form-group searchtitles">
					<div class="form_search">
				    <?php 
					if(isset($_GET['searchtitle'])){
					$searchtitle = $_GET['searchtitle'];
					}else {
						$searchtitle = '';
					}
					  ?>
				<input class="form-control" placeholder="Please enter Title and Category" type="search" name="searchtitle" id="mysearchtitle" class="formgroup" autocomplete="off" value="<?php echo $searchtitle;  ?>"><span style="background:#fff;"><i class="fa fa-search" aria-hidden="true"></i></span></div>
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
	
       if(isset($_REQUEST['filteringcategory'])){
	  $tagrelation = $_REQUEST['filteringcategory'];
	  if(isset($tagrelation['VchGenderTagid'])){
	  $columnname = $allcategory->VchColumnType;	 
	  $genderid = $tagrelation['VchGenderTagid'];
       if($genderid==$myalltagid[$i]){
	  $selected="checked"; 
      }
	 }
	   } 	 
 ?>
  <div class="check-view col-md-1 "> 
<input type="checkbox" class="racecategory" name="filteringcategory[<?php echo $allcategory->VchColumnType; ?>]" category="<?php echo $allcategory->VchColumnType; ?>"  ng-model="gender<?php echo $myalltagid[$i];  ?>" id="box1-<?php echo $myalltagid[$i];  ?>" ng-click="changegeneder(gender<?php echo $myalltagid[$i];  ?>,<?php echo $myalltagid[$i];  ?>)" value="<?php echo $myalltagid[$i];  ?>" <?php echo $selected; ?>> 
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
	 if(isset($_REQUEST['filteringcategory'])){
	  $tagrelation = $_REQUEST['filteringcategory'];
	  
   $columnname = $allcategory->VchColumnType;
	if(isset($tagrelation[$columnname])){
    $genderid = $tagrelation[$columnname];

 if($genderid==$myalltagid[$i]){	 
	$selected="selected"; 
 }
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
 <ul class="main">
							<li>
							
							<label class="container-checkbox">All Domains
								  <input type="checkbox"  id="select_all">
								  <span class="checkmark"></span>
								</label>
							</li>
							
							<ul style="margin-top: -10px;">  
							@foreach($allvideo['getmanagevideodomains'] as $getdomain)
								<li>
								<label class="container-checkbox">{{$getdomain->txtsiteurl}}
								  <input type="checkbox" class="checkbox multisite"  name="multisite[]" value="{{$getdomain->intmanagesiteid}}" @if(@in_array($getdomain->intmanagesiteid,$allvideo['multisite'])) checked @endif>
								  <span class="checkmark"></span>
								</label>
							</li>	
							@endforeach
						
							
							</ul>
						</ul>
						</div>
					<input type="hidden" name="perpage" id="perpage" value="{{$perpage}}" />
					<div class="form-group">
                      <button type="submit" class="btn btn-primary">Search</button>
                      </div>
					  </div>
				</form>
				</div>	

				
			<div class="ful-top gap-sextion"  id="product_container">
			
 @include('admin.admin-managevideo')
			
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
</style>
<div class="space100"></div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$(document).ready(function(){
$( "#mysearchtitle" ).autocomplete({
      source:'/getkeywordsvideo',
  });	
	 $('.racecategory').on('change', function() {
		    $('.racecategory').not(this).prop('checked', false);  
		});
	
/*  $("#mysearchtitle").keyup(function(){
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
 }); */  
 });
 </script>
<script>
/*  $(document).on('click', '.pagination a',function(event)
    {
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
		var href = $(this).attr("href");
		
        event.preventDefault();
        var myurl = $(this).attr('href');
       var page=$(this).attr('href').split('page=')[1];
       getData(href);
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
} */












function deletevideo(deleteid){
	var result = confirm("Want to delete?");
	if (result) {
		$("#msg").fadeIn();
					$('#remove'+deleteid).remove();
                  $("#msg").html("Video successfully deleted");
     $.ajax({
               type:'Get',
               url:'/admin/managevideosection',
               data:{deletevideoid:deleteid},
               success:function(data) {
				    $("#msg").fadeIn();
					$('#remove'+deleteid).remove();
                  $("#msg").html("Video successfully deleted");
				 // window.location.href="";
				  setTimeout(function(){
				  
				   $("#msg").fadeOut();
				   }, 5000);
               }
            });
}
	
}
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