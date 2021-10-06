<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
      <meta charset="utf-8">
       <meta name="viewport" content="width=device-width, initial-scale=1">
       <meta name="csrf-token" content="{{ csrf_token() }}">
	   <script src="js/app.js"></script>
	     <script src="js/jquery.colorbox-min.js"></script>
		  <link rel="stylesheet" href="/css/colorbox.css">
	 	<script src="js/angular.js"></script>
	   <script src="js/main.js"></script>

	   <script src="js/bootstrapui.js"></script>
	   <link rel="stylesheet" href="/css/app.css">
        <link rel="stylesheet" href="{{ asset('/css/fontendcustomise.css') }}">
	 <style>
	 .videoplayermodel {
  background-color: #fff !important;
  box-shadow: 10px 10px;
  display: none;
  left: 0;
  position: absolute;
  top: 0;
  width: 100%;
}
 .videoplayermodel1 {
  background-color: #fff !important;
  box-shadow: 10px 10px;
  display:none;

  left: 0;
  position: absolute;
  top: 0;
  width: 100%;
}
.divmaincontent {
  height: 100%;
  position: fixed;
  width: 100%;
}
.mainconatinerdiv {
  position: relative;
}
.closediv {
  background-color: #fff !important;
  border-radius: 15%;
  position: absolute;
  right: 17%;
  text-align: center;
  top: 0;
  width: 2%;
}
#videoID {
  height: 90%;
  margin-top: 10px;
  width: 100%;
}
.closediv {
  background-color: #fff !important;
  border-radius: 15%;
  position: absolute;
  right: 17%;
  text-align: center;
  top: 0;
  width: 2%;
}
.closediv1 {
    background-color: #fff !important;
    border-radius: 15%;
    position: absolute;
    right: 2%;
    text-align: center;

    width: 2%;
    bottom: 8%;
}
.divmaincontent {
  height: 100%;
  margin-bottom: 50px;
  margin-top: 10px;
  position: fixed;
  width: 100%;
}

#videoID {
	width: 100%;
	height: 100%;
}
/*Checkboxes styles*/
input[type="checkbox"] { display: none; }

input[type="checkbox"] + label {
  display: block;
  position: relative;
  padding-left: 30px;
  margin-bottom: 20px;
  font: 14px/20px 'Open Sans', Arial, sans-serif;
  color: #ddd;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
}
.myloadercontainer {
    position: absolute;
    z-index: 99999;
    width: 100%;
    height: 100%;
   // background-color: rgba(255,255,255,0.2);
}
.searchresult {
	background: #e56c3d;
	list-style: none;
	border-radius: 3px;
	position: absolute;
	z-index: 999;

}
.innercontentmodel {
  height: 100%;
  position: fixed;
  width: 100%;
   background-color: rgba(100, 100, 100, 0.5);
}
#videoID {
  height: 90%;
  margin-top: 10px;
  width: 100%;
}
loaderview1 {
    position: fixed !important;
    top: 50%;
    right: 40%;
}
input[type="checkbox"] + label:last-child { margin-bottom: 0; }

input[type="checkbox"] + label:before {
  content: '';
  display: block;
  width: 20px;
  height: 20px;
  border: 2px solid #e76b3a;
  position: absolute;
  left: 0;
  top: 0;
  opacity: .6;
  -webkit-transition: all .12s, border-color .08s;
  transition: all .12s, border-color .08s;
}

input[type="checkbox"]:checked + label:before {
  width: 10px;
  top: -5px;
  left: 5px;
  border-radius: 0;
  opacity: 1;
  border-top-color: transparent;
  border-left-color: transparent;
  -webkit-transform: rotate(45deg);
  transform: rotate(45deg);
}
.video-parts {
	width: 100%;
}
.check-view {
	width: 100px;
}
.image-icon img {
	width: 15px;
}
.dropdown {
	font-weight: normal;
	font-size: 15px;
}
.iconsdsf li:last-child {
	float: none;
}
.iconsdsf ul {
	width: auto;
}
.video {
	display: inline-block;
	float: right;
}
.loaderview1 {
    text-align: center;
    position: fixed;
    top: 50%;
    left: 40%;
    margin: 0 auto;
}
.loader-view {
	position: absolute;
	background: #fff;
	width: 100%;
	height: 700px;
	margin: auto;
	left: 0;
	right: 0;
	text-align: center;
	z-index: 99;
}
.main-container.top-view {
	position: relative;
}
.loader-view img {
	margin: 180px 0;
}
.search .form-control {
	font-size: 18px;
	text-transform: capitalize;
	color: #fff;
	font-weight: normal;
}
.searchresult {
	background: #e56c3d;
	list-style: none;
	border-radius: 3px;
}
.loaderview1 {
    text-align: center;
}
.searchresult li {
	border-bottom: 1px solid rgba(0, 0, 0, 0.2);
	padding: 5px 10px 2px;
}
.searchresult li:last-child{
	border:none;
}
.video-parts .inner-parts {
	display: inline-block;
	width: 23%;  position: relative;
	padding: 10px;
	margin: 10px 11px 15px;
	border-radius: 5px;
	box-shadow: -1px -2px 10px 3px rgba(228,108,61,.4);
}
.hover-play-icon img {
	text-align: center;
	width: 55px;
}
.hover-play-icon {
	position: absolute;
	margin: auto;
	left: 0;
	right: 0;
	top: 0;
	transition: all ease 0.6s;
	-webit-transition: all ease 0.6s;
	opacity: 0;
	text-align: center;
	height: 100%;
}
.hover-play-icon a {
	position: absolute;
	text-align: center;
	margin: auto;
	top: 43%;
	left: 0;
	right: 0;
}
.inner-parts.ng-scope:hover{
	background:#000;
}
.inner-parts.ng-scope:hover .hover-play-icon {
	background: rgba(255,255,255, 0.3);

}
.inner-parts.ng-scope .hover-play-icon {
	opacity: 1;


	border-radius: 5px;
}

<!--- drop-down --->
.cd-dropdown {
	-webkit-perspective: 800px;
	-moz-perspective: 800px;
	-o-perspective: 800px;
	-ms-perspective: 800px;
	perspective: 800px;
}

.cd-dropdown > span {
	-webkit-transform-style: preserve-3d;
	-moz-transform-style: preserve-3d;
	-o-transform-style: preserve-3d;
	-ms-transform-style: preserve-3d;
	transform-style: preserve-3d;

	-webkit-transform-origin: 50% 0%;
	-moz-transform-origin: 50% 0%;
	-o-transform-origin: 50% 0%;
	-ms-transform-origin: 50% 0%;
	transform-origin: 50% 0%;

	-webkit-transition: -webkit-transform .3s;
	-moz-transition: -moz-transform .3s;
	-o-transition: -o-transform .3s;
	-ms-transition: -ms-transform .3s;
	transition: transform .3s;
}

.cd-dropdown > span:active {
	-webkit-transform: rotateX(60deg);
	-moz-transform: rotateX(60deg);
	-o-transform: rotateX(60deg);
	-ms-transform: rotateX(60deg);
	transform: rotateX(60deg);
}

.cd-dropdown > span,
.cd-dropdown ul li:nth-last-child(-n+3) span {
	box-shadow: 0 1px 1px rgba(0,0,0,0.1);
}

.cd-dropdown ul {
	position: absolute;
	top: 0px;
	width: 100%;
}

.cd-dropdown ul li {
	position: absolute;
	width: 200px !important;
}
.cd-dropdown, .cd-select{
	width: 200px !important;
}
.cd-active.cd-dropdown > span {
	color: #f8b161;
}

.cd-active.cd-dropdown > span,
.cd-active.cd-dropdown ul li span {
	box-shadow: 0 1px 1px rgba(0,0,0,0.1);
}

.cd-active.cd-dropdown ul li span {
	-webkit-transition: all 0.2s linear 0s;
	-moz-transition: all 0.2s linear 0s;
	-ms-transition: all 0.2s linear 0s;
	-o-transition: all 0.2s linear 0s;
	transition: all 0.2s linear 0s;
}

.cd-active.cd-dropdown ul li span:hover {
	background: #f8b161;
	color: #fff;
}
.btn.btn-danger {
	background: #e56c3d;
}
.modal-body img {
	width: 100% !important;
}
.cd-dropdown ul li span {
	padding: 0 15px 0 15px !important;
	font-size: 16px !important; background: #e56c3d; color: #fff;
}
.cd-dropdown > span {
	font-size: 16px;
	background: #fff; color: #444;
	padding: 0 46px 0 15px;
}


.hover-play-icon img {
	position: absolute;
	text-align: center;
	margin: auto;
	top: 43%;
	left: 0;
	right: 0;
}
@media only screen and (max-width: 1024px) and (min-width: 600px) {
 .video-parts .inner-parts {
  border-radius: 5px;
  box-shadow: -1px -2px 10px 3px rgba(228, 108, 61, 0.4);
  display: inline-block;
  margin: 10px 11px 15px;
  padding: 10px;
  position: relative;
  width: 45%;
}
.video-parts {
  display: contents;
  width: 100%;
}
}
@media only screen and (max-width: 1200px) and (min-width: 1025px) {
 .video-parts .inner-parts {
  border-radius: 5px;
  box-shadow: -1px -2px 10px 3px rgba(228, 108, 61, 0.4);
  display: inline-block;
  margin: 10px 11px 15px;
  padding: 10px;
  position: relative;
  width: 31%;
}
.video-parts {
  display: contents;
  width: 100%;
}
}
@media only screen and (max-width: 600px) {
 .video-parts .inner-parts {
  border-radius: 5px;
  box-shadow: -1px -2px 10px 3px rgba(228, 108, 61, 0.4);
  display: inline-block;
  margin: 10px 11px 15px;
  padding: 10px;
  position: relative;
  width: 100%;
}
.video-parts {
  display: contents;
  width: 100%;
}
}
/*-----28-12-2018--------*/
span.colorwhite.ng-binding {
    font-size: 12px;
    line-height: 15px;
    font-weight: 900;
}
.proper_fit {
    text-align: center;
}
.closediv {
    right: 7.2%;
    top:9px;
}
.hover-play-icon img {
     top: 35%;
}
#videoID {
    height: 100%;
    background: black;
    margin: 0;
    padding-top: 8px;
}
.divmaincontent {
     margin-top: 0;
}
.video-parts .inner-parts {
    min-height: 225px;
   vertical-align: top;
}
.btn.btn-outline {
    top: 0;
    left: 19px;
    padding: 5px 12px;
}
.form-control.gray {
    border-color: #fff;
}
@media only screen and (max-width: 991px) {
.dropdown {
     margin-top: 7px;
}
.btn.btn-outline {
   left: 0px;
}
.search {
    padding-left: 46px;
}
}

@media only screen and (max-width: 767px) {
.iconsdsf ul {
    width: 100%;
}
.iconsdsf .image-icon {
    padding-right: 0;
}
.iconsdsf ul li:nth-child(3), .iconsdsf ul li:last-child {
    display: block;
}
.iconsdsf ul li:nth-child(3) select, .iconsdsf ul li:last-child select {
    width: 100%; height: 34px; font-size: 14px; text-transform: capitalize;
}
.iconsdsf .video {
    display: block;
    float: none;
     padding: 10px 0 20px;
}
.iconsdsf .video select {
    width: 100%; height: 34px; font-size: 14px; text-transform: capitalize;
}
}
.keyword {
 color: #db673c;
    list-style-type: none;
}
video#fvideoID {
    width: 100%;
    height: 100%;
}
.keyword  li{
	display:inline-block;
	padding:0 5px;
}

<!--- drop-down --->
<!----Responsive----->
</style>
	 <script>
	 var app = angular.module('myApp', ['ui.bootstrap']);

 app.controller('customersCtrl', function($scope, $http) {

	 $scope.showkeyword = false;
  var currentpagepagination = 1;
	$scope.searchkeyword = '';
	 $scope.allsearch ='';
 $scope.setPage = function (pageNo) {
    $scope.currentPage = pageNo;
 };
  $scope.pageChanged = function() {
	 $('.myloadercontainer').fadeIn();
	var limit = $scope.currentPage-1;
	var searchkeyword = $('#searchkeyword').val();
	var type = $('.racecategory1').val();
	limit = limit*10;
	currentpagepagination = $scope.currentPage;
    var allfilter = [];
$('.racecategory').each(function(){
var categoryid = $(this).attr('category');
 var checktype = $(this).attr("type");
if(checktype=="checkbox"){
if($(this).prop('checked') == true){
allfilter.push({tagtype:$(this).val(),category:categoryid});
}
}else {
if($(this).val()!=''){
allfilter.push({tagtype:$(this).val(),category:categoryid});
}
}
});
var myJSON = JSON.stringify(allfilter);
$http.get('/getallvideo?_token = <?php echo csrf_token() ?>&type='+type+'&searchtext='+searchkeyword+'&startlimit='+limit+'&category=1&Tagid='+myJSON).then(successCallback, errorCallback);

 };
$scope.setItemsPerPage = function(num) {
 $scope.itemsPerPage = num;
 $scope.currentPage = 1; //reset to first page
 }
 var offest = 10;
 var count =0;
 $scope.offest = 10;
 var scrollstart = true;
 var myallvideo = [];
 $http.get('/getallvideo?_token = <?php echo csrf_token() ?>&startlimit=0').then(successCallback, errorCallback);
function successCallback(response){
	 $scope.showkeyword = false;
$('.myloadercontainer').fadeOut(300);
if(response.data.allvideo==''){
	var keyword = $('#searchkeyword').val();
 $http.get('/getallkeywords?_token = <?php echo csrf_token() ?>&startlimit=0&keyword='+keyword).then(successCallback2, errorCallback2);

}
  $scope.allvideo ='';
  $scope.allvideo = Object.assign({}, response.data.allvideo);
  $scope.viewby = 10;
  $scope.totalItems = response.data.totalvideo;
  $scope.currentPage = currentpagepagination;
  $scope.itemsPerPage = 10;
  $scope.maxSize = 10;
  count++;

}
function successCallback2(response){
  console.log(response.data);
  if(response.data!=''){
   $scope.showkeyword = true;
  $scope.allkeyword = Object.assign({}, response.data);
  }else {
	   $scope.showkeyword = false;

  }
 }
function successCallback1(response){
  console.log(response.data);
  $scope.allsearch = Object.assign({}, response.data);

}
function errorCallback2(error){
}
function errorCallback(error){
}

$scope.searchvideo = function(searchkeyword,event) {

	var type = $('.racecategory1').val();
	var allfilter = [];
	$('.racecategory').each(function(){
var categoryid = $(this).attr('category');
 var checktype = $(this).attr("type");
if(checktype=="checkbox"){

if($(this).prop('checked') == true){
allfilter.push({tagtype:$(this).val(),category:categoryid});
}
}else {
if($(this).val()!=''){
allfilter.push({tagtype:$(this).val(),category:categoryid});
}
}
});
var myJSON = JSON.stringify(allfilter);



	if(searchkeyword!=''){
	var keyCode = event.which || event.keyCode;
    if (keyCode === 13) {
		$scope.allsearch = '';
        $http.get('/getallvideo?_token = <?php echo csrf_token() ?>&type='+type+'&category=1&Tagid='+myJSON+'&searchtext='+searchkeyword+'&startlimit=0').then(successCallback, errorCallback);
    }else {
   $http.get('/getkeywords?_token = <?php echo csrf_token() ?>&type='+type+'&category=1&Tagid='+myJSON+'&searchtext='+searchkeyword+'&startlimit=0').then(successCallback1, errorCallback);
   $http.get('/getallvideo?_token = <?php echo csrf_token() ?>&type='+type+'&category=1&Tagid='+myJSON+'&searchtext='+searchkeyword+'&startlimit=0').then(successCallback, errorCallback);
	}
	}else {
		$scope.allsearch = '';
		$http.get('/getallvideo?_token = <?php echo csrf_token() ?>&type='+type+'&category=1&Tagid='+myJSON+'&startlimit=0').then(successCallback, errorCallback);
	}
};

 $('.racecategory').change(function(){
	 var type = $('.racecategory1').val();
	 var checktype = $(this).attr("type");
	 if(checktype=="checkbox"){
	$('.racecategory').not(this).prop('checked', false);
	 }
	 var allfilter = [];
$('.racecategory').each(function(){
var categoryid = $(this).attr('category');
 var checktype = $(this).attr("type");
if(checktype=="checkbox"){

if($(this).prop('checked') == true){
allfilter.push({tagtype:$(this).val(),category:categoryid});
}
}else {
if($(this).val()!=''){
allfilter.push({tagtype:$(this).val(),category:categoryid});
}
}
});
var myJSON = JSON.stringify(allfilter);
var searchtitle = $('#searchkeyword').val();
$http.get('/getallvideo?_token = <?php echo csrf_token() ?>&type='+type+'&category=1&Tagid='+myJSON+'&startlimit=0&searchtext='+searchtitle).then(successCallback, errorCallback);
  });
  $scope.selectautosearch = function(title,sucategory){

	if(sucategory!=null){
	var searchtitle = title+" "+sucategory;
	$('#searchkeyword').val(title+" "+sucategory);
	}else {
		var searchtitle = title;
		$('#searchkeyword').val(title);
	}
	$scope.allsearch = '';
	$http.get('/getallvideo?_token = <?php echo csrf_token() ?>&searchtext='+searchtitle+'&startlimit=0').then(successCallback, errorCallback);
}
  $scope.showvideo=function(videoname,videopath,type,uploadtype,vchgoogledrivelink){
	  if(uploadtype=='W'){
	  if(type=='V'){
	 $scope.videoname =  videoname;
	  $scope.videopath =  videopath;
  	$('.videoplayermodel').fadeIn("slow");
	var video = document.getElementById('videoID');
    video.load();
    video.play();
	  }
	  }else {
		  alert(vchgoogledrivelink);
		  var newdrivelink = vchgoogledrivelink.split("https://drive.google.com/file/d/");
		var myvideonameid = newdrivelink[1].split("/preview");
		alert(myvideonameid[0]);
		 $scope.videoname =  "https://drive.google.com/uc?authuser=0&id="+myvideonameid[0]+"&export=download";

		var video = document.getElementById('fvideoID');
       video.load();
       video.play();
		$('.videoplayermodel1').fadeIn();
		/* $('#iframe').attr('src', vchgoogledrivelink);
	 	setTimeout(function(){
		$('.videoplayermodel1').fadeIn();
	    }, 2000); */

	  }
  }


  $scope.changetype = function(valuesofdropdown){
	 var type = valuesofdropdown;

	  var allfilter = [];
$('.racecategory').each(function(){
var categoryid = $(this).attr('category');
 var checktype = $(this).attr("type");
if(checktype=="checkbox"){

if($(this).prop('checked') == true){
allfilter.push({tagtype:$(this).val(),category:categoryid});
}
}else {
if($(this).val()!=''){
allfilter.push({tagtype:$(this).val(),category:categoryid});
}
}
});
var myJSON = JSON.stringify(allfilter);
var searchtitle = $('#searchkeyword').val();
$http.get('/getallvideo?_token = <?php echo csrf_token() ?>&type='+type+'&category=1&Tagid='+myJSON+'&startlimit=0&searchtext='+searchtitle).then(successCallback, errorCallback);

  }
  $('.closebutton').click(function(){

	 $scope.videoname =  '';
	  $scope.videopath =  '';
   var video = document.getElementById('videoID');
    video.load();

	$('.videoplayermodel').fadeOut();

  });
 $('.closediv1').click(function(){
	$('#iframe').attr('src', '');
	$('.videoplayermodel1').fadeOut();

  });
  $('.homepage').on("click",".group1",function(){
  $(this).colorbox({width:"90%", height:"90%",innerWidth:'100%', innerHeight:'100%'});
  });
  });
</script>

 </head>
    <body class="homepage" ng-app="myApp" ng-controller="customersCtrl">


 	<div class="main-container top-view">
<section class="main">
			<div class="container logo">
			<img src="{{ asset('images/logo.jpg') }}" alt="logo">
			</div>
			<div class="container">
			<div class="title">
			<h5>@{{totalItems}} Item (s)</h5>
			<form>
		<div class="search">
      <input class="form-control gray" id="searchkeyword" type="text" placeholder="Search"  ng-model="searchkeyword" ng-keyup="searchvideo(searchkeyword,$event)">
	  <ul class="searchresult">
	  <li ng-repeat="tpname in allsearch" ng-click="selectautosearch(tpname.VchCategoryTitle,tpname.childcategory);">@{{tpname.VchCategoryTitle}}  @{{tpname.childcategory}}</li>
	  </ul>

      <button class="btn btn-outline" type="submit"><img src="{{ asset('images/search.png') }}" alt="image">
      </button>
	    </div>
		</form>
</div>
</div>
</section>
<section class="navigation-bar">
<div class="container">
<div class="iconsdsf">
<ul>
@foreach ($alltags as $allcategory)
<?php
 $myalltagid = explode(',',$allcategory->tagid);
 $myallcategorytag = explode(',',$allcategory->tagTitle);
 $totalitems = count(explode(',',$allcategory->tagTitle));
?>
@if ($allcategory->IntId == 1)
	<?php
        for($i=0;$i<$totalitems;$i++){ ?>

  <li class="check-view">
<input type="checkbox" class="racecategory" category="<?php echo $allcategory->VchColumnType; ?>"  ng-model="gender<?php echo $myalltagid[$i];  ?>" id="box-<?php echo $myalltagid[$i];  ?>" ng-click="changegeneder(gender<?php echo $myalltagid[$i];  ?>,<?php echo $myalltagid[$i];  ?>)" value="<?php echo $myalltagid[$i];  ?>">
  <label for="box-<?php echo $myalltagid[$i];  ?>">
  <?php echo $myallcategorytag[$i];?>
  </label>
  </li>
<?php } ?>
@else
	   <li>
     <div class="image-icon">
	 <div class="dropdown"> {{$allcategory->VchTitle}}
	 <img src="{{ asset('images/dropdown.png') }}" alt="image">
   <select class="racecategory" category="<?php echo $allcategory->VchColumnType; ?>">
    <option value="">Select Your <?php echo $allcategory->VchTitle; ?></option>
   <?php
 for($i=0;$i<$totalitems;$i++){
		 ?>
	 <option value="<?php echo $myalltagid[$i];  ?>"><?php echo $myallcategorytag[$i];  ?></option>
<?php } ?>
	 </select>
		</div>
		</div>
		 </li>






@endif
@endforeach
</ul>
 <div class="video">
  <select class="racecategory1" ng-model="videotype" ng-change="changetype(videotype)">
  	 <option value="">select</option>
   <option value="I">Image</option>
	 <option value="V">Video</option>

	 </select>
	 </div>

 </div>

 </div>
</section>
<section class="banner-image">
<div class="banner-image1">
	<div class="container" style="position:relative;">
	<div class="myloadercontainer">
	<div class="loaderview1">
		<img src="{{ asset('images/loader11.gif') }}" alt="img" style="width:300px;height:300px;">
	</div>
	</div>
	<div class="row">

	<ul class="keyword" ng-if="showkeyword">
	Do You Mean :
	<li ng-repeat="tpname in allkeyword" ng-click="selectautosearch(tpname.title);">@{{tpname.title}}</li>
	</ul>
	</div>
	<div class="row" style="min-height:500px;">

		<ul class="video-parts">
		<li class="inner-parts"  ng-repeat="tpname in allvideo" ng-click="showvideo(tpname.VchVideoName,tpname.VchFolderPath,tpname.EnumType,tpname.EnumUploadType,tpname.vchgoogledrivelink)">
		 <div class="hover-play-icon" ng-if="tpname.EnumType=='V'">
			<img src="{{ asset('images/play-icon.png') }}" alt="img">
			</div>
			<div class="proper_fit" ng-if="tpname.EnumUploadType=='W'">
			 <span class="colorwhite" style="color:#fff;">@{{tpname.VchTitle}}</span>
			 <a href="@{{tpname.VchFolderPath}}/@{{tpname.VchVideothumbnail}}" ng-if="tpname.EnumType=='I'" class="group1">
				<img src="@{{tpname.VchFolderPath}}/@{{tpname.VchVideothumbnail}}">
				</a>

				<a href="@{{tpname.VchFolderPath}}/@{{tpname.VchVideothumbnail}}" ng-if="tpname.EnumType=='V'">
				<img src="@{{tpname.VchFolderPath}}/@{{tpname.VchVideothumbnail}}">
				</a>
				</div>

				<div class="proper_fit" ng-if="tpname.EnumUploadType=='G'" ng-click="iframevideo(tpname.vchgoogledrivelink);">
			 <span class="colorwhite" style="color:#fff;">@{{tpname.VchTitle}}</span>
			 <a href="@{{tpname.VchVideothumbnail}}"  class="group1">
				<img src="@{{tpname.VchVideothumbnail}}">
				</a>


				</div>

				</li>
		</ul>
	</div>
 <pagination total-items="totalItems"  ng-change="pageChanged(currentPage)" ng-model="currentPage" max-size="maxSize" class="pagination" boundary-links="true" rotate="false" num-pages="numPages" items-per-page="itemsPerPage"></pagination>

</div>
</div>
</div>
</section>

	<div class="videoplayermodel">
	<div class="mainconatinerdiv">
	<div class="divmaincontent">
 <video id="videoID" controls>
				  <source src="/@{{videopath}}/@{{videoname}}" type="video/mp4">
				 <source src="/@{{videopath}}/@{{videoname}}" type="video/ogg">
			  Your browser does not support the video tag.
				</video>
	<div class="closediv">
	<a href="javascript:void(0);" class="closebutton">X</a>
	</div>
</div>
</div>
</div>
	<div class="videoplayermodel1">
	<div class="mainconatinerdiv">
	<div class="divmaincontent">
	 <video id="fvideoID" controls>
				  <source src="@{{videoname}}" type="video/mp4">
				 <source src="@{{videoname}}" type="video/ogg">
			  Your browser does not support the video tag.
				</video>
  <!--  <iframe id="iframe" src="@{{vchgoogledrivelink}}" width="100%" height="100%">

  </iframe>  -->
	<div class="closediv1">

	<a href="javascript:void(0);" class="closebutton">X</a>
	</div>
</div>
</div>
</div>



</div>
 </body>
</html>
