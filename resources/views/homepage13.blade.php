@include('header')
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>

var app = angular.module('myApp', ['ui.bootstrap']);

app.controller('customersCtrl', function ($scope, $http) {


	var client = {
		init: function () {
			var o = this;

			// this will disable dragging of all images
			$("img").mousedown(function (e) {
				e.preventDefault()
			});

			// this will disable right-click on all images
			$("body").on("contextmenu", function (e) {
				return false;
			});
		}
	};
	$scope.showkeyword = false;
	var currentpagepagination = 1;
	$scope.searchkeyword = '';
	$scope.allsearch = '';
	$scope.setPage = function (pageNo) {
		$scope.currentPage = pageNo;
	};
	$scope.pageChanged = function () {
		$('.myloadercontainer').fadeIn();
		var limit = $scope.currentPage - 1;
		var searchkeyword = $('#searchkeyword').val();
		var type = $('.racecategory1').val();
		var showitemperpage = $('.showitemperpage').val();
		limit = limit * showitemperpage;
		currentpagepagination = $scope.currentPage;
		var allfilter = [];
		$('.racecategory').each(function () {
			var categoryid = $(this).attr('category');
			var checktype = $(this).attr("type");
			if (checktype == "checkbox") {
				if ($(this).prop('checked') == true) {
					allfilter.push({
						tagtype: $(this).val(),
						category: categoryid
					});
				}
			} else {
				if ($(this).val() != '') {
					allfilter.push({
						tagtype: $(this).val(),
						category: categoryid
					});
				}
			}
		});

		var myJSON = JSON.stringify(allfilter);
		var showitemperpage = $('.showitemperpage').val();
		$http.get('/getallvideo?_token = <?php echo csrf_token() ?>&type=' + type + '&searchtext=' + searchkeyword + '&startlimit=' + limit + '&showitemperpage=' + showitemperpage + '&category=1&Tagid=' + myJSON).then(successCallback, errorCallback);

	};
	$scope.setItemsPerPage = function (num) {
		$scope.itemsPerPage = num;
		$scope.currentPage = 1; //reset to first page
	}
	var showitemperpage = $('.showitemperpage').val();
	var offest = showitemperpage;
	var count = 0;
	$scope.offest = showitemperpage;
	var scrollstart = true;
	var myallvideo = [];
	var tech = getUrlParameter('s');
	if (tech == undefined) {
		tech = "";
	}
	$http.get('/getallvideo?_token = <?php echo csrf_token() ?>&startlimit=0&showitemperpage=' + showitemperpage + '&searchtext=' + tech).then(successCallback, errorCallback);

	function successCallback(response) {
		$scope.showkeyword = false;
		$('.myloadercontainer').fadeOut(300);
		if (response.data.allvideo == '') {
			var showitemperpage = $('.showitemperpage').val();
			var keyword = $('#searchkeyword').val();
			$http.get('/getallkeywords?_token = <?php echo csrf_token() ?>&startlimit=0&keyword=' + keyword + '&showitemperpage=' + showitemperpage).then(successCallback2, errorCallback2);

		}
		$scope.allvideo = '';
		$scope.allvideo = Object.assign({}, response.data.allvideo);
		var showitemperpage = $('.showitemperpage').val();
		$scope.viewby = showitemperpage;
		$scope.totalItems = response.data.totalvideo;
		$scope.currentPage = currentpagepagination;
		$scope.itemsPerPage = showitemperpage;
		$scope.maxSize = 10;
		count++;

	}

	function successCallback2(response) {
		console.log(response.data);
		if (response.data != '') {
			$scope.showkeyword = true;
			$scope.allkeyword = Object.assign({}, response.data);
		} else {
			$scope.showkeyword = false;

		}
	}

	function successCallback1(response) {
		console.log(response.data);
		$scope.allsearch = Object.assign({}, response.data);

	}

	function errorCallback2(error) {}

	function errorCallback(error) {}

	$scope.searchvideo = function (searchkeyword, event) {

		var type = $('.racecategory1').val();
		var allfilter = [];
		$('.racecategory').each(function () {
			var categoryid = $(this).attr('category');
			var checktype = $(this).attr("type");
			if (checktype == "checkbox") {

				if ($(this).prop('checked') == true) {
					allfilter.push({
						tagtype: $(this).val(),
						category: categoryid
					});
				}
			} else {
				if ($(this).val() != '') {
					allfilter.push({
						tagtype: $(this).val(),
						category: categoryid
					});
				}
			}
		});
		var myJSON = JSON.stringify(allfilter);


		if (searchkeyword != '') {
			var count = 0;
			var keyCode = event.which || event.keyCode;
			if (keyCode === 40 || keyCode === 38) {


			}
			if (keyCode === 13) {
				var showitemperpage = $('.showitemperpage').val();
				$scope.allsearch = '';
				$http.get('/getallvideo?_token = <?php echo csrf_token() ?>&type=' + type + '&category=1&Tagid=' + myJSON + '&showitemperpage=' + showitemperpage + '&searchtext=' + searchkeyword + '&startlimit=0').then(successCallback, errorCallback);
			} else {
				$http.get('/getkeywords?_token = <?php echo csrf_token() ?>&type=' + type + '&category=1&Tagid=' + myJSON + '&searchtext=' + searchkeyword + '&startlimit=0').then(successCallback1, errorCallback);
				var showitemperpage = $('.showitemperpage').val();
				$http.get('/getallvideo?_token = <?php echo csrf_token() ?>&type=' + type + '&category=1&Tagid=' + myJSON + '&showitemperpage=' + showitemperpage + '&searchtext=' + searchkeyword + '&startlimit=0').then(successCallback, errorCallback);
			}
		} else {
			var showitemperpage = $('.showitemperpage').val();
			$scope.allsearch = '';
			$http.get('/getallvideo?_token = <?php echo csrf_token() ?>&type=' + type + '&category=1&Tagid=' + myJSON + '&showitemperpage=' + showitemperpage + '&startlimit=0').then(successCallback, errorCallback);
		}
	};

	$('.racecategory').change(function () {
		var type = $('.racecategory1').val();
		var checktype = $(this).attr("type");
		if (checktype == "checkbox") {
			$('.racecategory').not(this).prop('checked', false);
		}
		var allfilter = [];
		$('.racecategory').each(function () {
			var categoryid = $(this).attr('category');
			var checktype = $(this).attr("type");
			if (checktype == "checkbox") {

				if ($(this).prop('checked') == true) {
					allfilter.push({
						tagtype: $(this).val(),
						category: categoryid
					});
				}
			} else {
				if ($(this).val() != '') {
					allfilter.push({
						tagtype: $(this).val(),
						category: categoryid
					});
				}
			}
		});
		var myJSON = JSON.stringify(allfilter);
		var searchtitle = $('#searchkeyword').val();
		var showitemperpage = $('.showitemperpage').val();
		$http.get('/getallvideo?_token = <?php echo csrf_token() ?>&type=' + type + '&category=1&Tagid=' + myJSON + '&showitemperpage=' + showitemperpage + '&startlimit=0&searchtext=' + searchtitle).then(successCallback, errorCallback);
	});
	$scope.selectautosearch = function (title, sucategory) {

		if (sucategory != null) {
			var searchtitle = title + " " + sucategory;
			$('#searchkeyword').val(title + " " + sucategory);
		} else {
			var searchtitle = title;
			$('#searchkeyword').val(title);
		}
		$scope.allsearch = '';
		var showitemperpage = $('.showitemperpage').val();
		$http.get('/getallvideo?_token = <?php echo csrf_token() ?>&searchtext=' + searchtitle + '&showitemperpage=' + showitemperpage + '&startlimit=0').then(successCallback, errorCallback);
	}
	$scope.showvideo = function (videoname, videopath, type, uploadtype, vchgoogledrivelink, VchTitle, tags) {
		//alert(VchTitle);
		if (uploadtype == 'W') {
			if (type == 'V') {
				
				$("#video-div").css("display", "block");
				$("#image-div").css("display", "none");
				$scope.videoname = 'watermark.mp4';
				$scope.videopath = videopath+'/'+<?php echo $managesite->intmanagesiteid;?>;
				$('.big-image').fadeIn("slow");
				$(".bigimagename").text(VchTitle);
				
						if(tags == ""){
					var taglisting = [];
				}else{
					var taglisting = tags.split(",");
				}
				
			
				var tag = "<span>Related keywords:</span>";
				for(i=0; i<taglisting.length; i++){
					tag += "<a href='/?s="+$.trim(taglisting[i])+"'>"+taglisting[i]+"</a>";
				}
				
				if(taglisting.length == 0){
					tag += "<a>No tags</a>";
				}
				$(".rlt-key").html(tag);
				
				var video = document.getElementById('videoID');
				video.load();
				video.play();
			}
		} else {
			
			if (type == 'V') {
				$scope.videoname = 'watermark.mp4';
				$scope.videopath = videopath+'/'+<?php echo $managesite->intmanagesiteid;?>;
				$('.big-image').fadeIn("slow");
				$(".bigimagename").text(VchTitle);
				if(tags == ""){
			var taglisting = [];
		}else{
			var taglisting = tags.split(",");
		}
		
	
		var tag = "<span>Related keywords:</span>";
		for(i=0; i<taglisting.length; i++){
			tag += "<a href='/?s="+$.trim(taglisting[i])+"'>"+taglisting[i]+"</a>";
		}
		
		if(taglisting.length == 0){
			tag += "<a>No tags</a>";
		}
		$(".rlt-key").html(tag);
				var video = document.getElementById('videoID');
				video.load();
				video.play();
			}
			

			// var newdrivelink = vchgoogledrivelink.split("https://drive.google.com/file/d/");
			// var myvideonameid = newdrivelink[1].split("/preview");

			// $scope.videoname = "https://drive.google.com/uc?authuser=0&id=" + myvideonameid[0] + "&export=download";

			// var video = document.getElementById('fvideoID');
			// video.load();
			// video.play();
			// $('.videoplayermodel1').fadeIn();
			/* $('#iframe').attr('src', vchgoogledrivelink);
	 	setTimeout(function(){
		$('.videoplayermodel1').fadeIn();
	    }, 2000); */

		}
	}
	$(document).on('change', '.showitemperpage', function () {
		var type = $('.racecategory1').val();

		var allfilter = [];
		$('.racecategory').each(function () {
			var categoryid = $(this).attr('category');
			var checktype = $(this).attr("type");
			if (checktype == "checkbox") {

				if ($(this).prop('checked') == true) {
					allfilter.push({
						tagtype: $(this).val(),
						category: categoryid
					});
				}
			} else {
				if ($(this).val() != '') {
					allfilter.push({
						tagtype: $(this).val(),
						category: categoryid
					});
				}
			}
		});
		var myJSON = JSON.stringify(allfilter);
		var searchtitle = $('#searchkeyword').val();
		var showitemperpage = $('.showitemperpage').val();
		$http.get('/getallvideo?_token = <?php echo csrf_token() ?>&type=' + type + '&category=1&Tagid=' + myJSON + '&showitemperpage=' + showitemperpage + '&startlimit=0&searchtext=' + searchtitle).then(successCallback, errorCallback);

	});

	$scope.changetype = function (valuesofdropdown) {
		var type = valuesofdropdown;

		var allfilter = [];
		$('.racecategory').each(function () {
			var categoryid = $(this).attr('category');
			var checktype = $(this).attr("type");
			if (checktype == "checkbox") {

				if ($(this).prop('checked') == true) {
					allfilter.push({
						tagtype: $(this).val(),
						category: categoryid
					});
				}
			} else {
				if ($(this).val() != '') {
					allfilter.push({
						tagtype: $(this).val(),
						category: categoryid
					});
				}
			}
		});
		var myJSON = JSON.stringify(allfilter);
		var searchtitle = $('#searchkeyword').val();
		var showitemperpage = $('.showitemperpage').val();
		$http.get('/getallvideo?_token = <?php echo csrf_token() ?>&type=' + type + '&category=1&Tagid=' + myJSON + '&showitemperpage=' + showitemperpage + '&startlimit=0&searchtext=' + searchtitle).then(successCallback, errorCallback);

	}
	$('.closebutton').click(function () {

		$scope.videoname = '';
		$scope.videopath = '';
		var video = document.getElementById('videoID');
		video.load();

		$('.videoplayermodel').fadeOut();

	});
	$('.closediv1').click(function () {
		$scope.videoname = '';
		var video = document.getElementById('fvideoID');
		video.load();
		$('.videoplayermodel1').fadeOut();

	});
	$('.homepage').on("click", ".group1", function () {
		
		$("#image-div").css("display", "block");
		$("#video-div").css("display", "none");
		document.getElementsByClassName('login_form')[0].style.display = "none";
  		document.getElementsByClassName('register_form')[0].style.display = "none";
  		document.getElementsByClassName('forgot_form')[0].style.display = "none";
		   $("#bigimagesize").removeClass("big-active");
		   $("#bigimagesize").addClass("non-active");
		   
		   $('#zoomCheck').prop('checked', false);
		var img = $(this).attr('data-image');
		var tags = $(this).attr('data-tags');
		var name = $(this).attr('data-name');
		
		$(".bigimagename").text(name);
		
		if(tags == ""){
			var taglisting = [];
		}else{
			var taglisting = tags.split(",");
		}
		
	
		var tag = "<span>Related keywords:</span>";
		for(i=0; i<taglisting.length; i++){
			tag += "<a href='/?s="+$.trim(taglisting[i])+"'>"+taglisting[i]+"</a>";
		}
		
		if(taglisting.length == 0){
			tag += "<a>No tags</a>";
		}
		$(".rlt-key").html(tag);
		
		var res = img.replace("%20", "W3Schools")
		$("#bigimagesize").attr("src",res);
		
		//$(".photo").css("background-image","url('"+res+"')");
		openbigForm();
		// $(this).colorbox({
			// width: "90%",
			// height: "90%",
			// innerWidth: '100%',
			// innerHeight: '100%'
		// });
	});
});
$(document).ready(function () {
	var tech = getUrlParameter('s');
	$("#searchkeyword").val(tech);
	
	$("#bigimagesize").draggable();
	$('#bigimagesize').click(function() {
		$(this).toggleClass('big-active').css({
		'left': '0', 
		'top': '0'
	});
    $(this).toggleClass('non-active');
})
});
;

var getUrlParameter = function getUrlParameter(sParam) {
	var sPageURL = window.location.search.substring(1),
		sURLVariables = sPageURL.split('&'),
		sParameterName,
		i;

	for (i = 0; i < sURLVariables.length; i++) {
		sParameterName = sURLVariables[i].split('=');

		if (sParameterName[0] === sParam) {
			return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
		}
	}
};

function updateURL() {
	if (history.pushState) {

		//alert(search);
		setTimeout(function () {
			var search = document.getElementById('searchkeyword').value;
			var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?s=' + search;
			window.history.pushState({
				path: newurl
			}, '', newurl);
		}, 500);

	}
}

function closelist() {
	$(".searchresult").hide();
}

function disperlist() {
	$(".searchresult").show();
	//$(".fox-list").
	
}
</script>
<style>
#agree{
	display: block;
	 height: 17px;
	
}
</style>
<div class="container">
	<div class="title">
		<h5>@{{totalItems}} Item (s)</h5>
		<form>
			<div class="search">
				<input class="form-control gray" id="searchkeyword" type="text" placeholder="Search"  ng-model="searchkeyword" ng-keyup="searchvideo(searchkeyword,$event)" autocomplete="off"   onkeyup="updateURL()" onClick="disperlist()" value="">
				<ul class="searchresult" style="display:none">
					<li ng-repeat="tpname in allsearch" ng-click="selectautosearch(tpname.VchCategoryTitle,tpname.childcategory);" onClick="updateURL()" class="fox-list" >@{{tpname.VchCategoryTitle}}  @{{tpname.childcategory}}</li>
				</ul>

				<button class="btn btn-outline" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
			</div>
		</form>
	</div>
</div>
</section >
<section class="navigation-bar" onClick="closelist()">
	<div class="container">
		<div class="advance_s">
		<a class="info-advance-search">Advanced Search <i class="fa fa-plus" aria-hidden="true"></i></a>
		</div>
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
					<li>
					<label class="check-box-container"><?php echo $myallcategorytag[$i];?>
					<input type="checkbox"  class="racecategory" category="<?php echo $allcategory->VchColumnType; ?>"  ng-model="gender<?php echo $myalltagid[$i];  ?>" id="box-<?php echo $myalltagid[$i];  ?>" ng-click="changegeneder(gender<?php echo $myalltagid[$i];  ?>,<?php echo $myalltagid[$i];  ?>)" value="<?php echo $myalltagid[$i];  ?>">
					<span class="checkmark"></span>
					</label>
					</li>
				<?php } ?>		
				@else
					<li>
					<div class="image-icon"> 
					<div class="dropdown dropdownlabel"> 
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
				<label class="dropdownlabel info-label">Select Type</label>
				<select class="racecategory1" ng-model="videotype" ng-change="changetype(videotype)">
					<option value="">select</option>
					<option value="I">Image</option>
					<option value="V">Video</option>
				</select>
				<label class="dropdownlabel info-label show">Number of Show</label>
				<select class="showitemperpage">
					<option value="12">12</option>
					<option value="16">16</option>
					<option value="20">20</option>
					<option value="24">24</option>
					<option value="36">36</option>
					<option value="48" Selected>48</option>
					<option value="72">72</option>
					<option value="100">100</option>
				</select>
			</div>
		</div>

	</div>
</section>
<section class="banner-image" onClick="closelist()">
	<div class="banner-image1">
		<div class="container" style="position:relative;">
			<div class="myloadercontainer">
				<div class="loder_innes">   
					<div class="loaderview1">     
						<img src="{{ asset('images/loader11.gif') }}" alt="img" style="width:300px;height:300px;">
					</div>
				</div> 	
			</div>
		<div class="row">
			<ul class="keyword" ng-if="showkeyword">
				Do You Mean :
				<li ng-repeat="tpname in allkeyword" ng-click="selectautosearch(tpname.title);">@{{tpname.title}}</li>
			</ul>
		</div>
		<div class="row" style="min-height:500px; padding:0 5px;">

			<ul class="video-parts">
				<li class="inner-parts"  ng-repeat="tpname in allvideo" ng-click="showvideo(tpname.VchVideoName,tpname.VchFolderPath,tpname.EnumType,tpname.EnumUploadType,tpname.vchgoogledrivelink,tpname.VchTitle,tpname.videotags)">
					<div class="hover-play-icon" ng-if="tpname.EnumType=='V'"> 
					<img src="{{ asset('images') }}/{{$tblthemesetting->vchvideoicon}}" alt="img"> 
					</div>
					<div class="proper_fit" ng-if="tpname.EnumUploadType=='W'">
						<span class="colorwhite" style="color:#fff;">@{{tpname.VchTitle}}</span>
							<a data-name="@{{tpname.VchTitle}}" data-tags="@{{tpname.videotags}}"  data-image="/showimage/@{{tpname.IntId}}/{{$managesite->intmanagesiteid}}/@{{tpname.VchVideoName}}?=@{{tpname.intsetdefault}}" ng-if="tpname.EnumType=='I'" class="group1">


								<div class="image"  ng-if="tpname.Vchcustomthumbnail!=''">
									<img ng-if="tpname.Vchcustomthumbnail!=''" src="/@{{tpname.VchFolderPath}}/@{{tpname.Vchcustomthumbnail}}" > 
								</div>

								<div class="image" ng-if="tpname.Vchcustomthumbnail==''">
									<img ng-if="tpname.vchcacheimages==''" src="/resize1/showimage/@{{tpname.IntId}}/{{$managesite->intmanagesiteid}}/@{{tpname.VchResizeimage}}/?=@{{tpname.intsetdefault}}" > 

									<img ng-if="tpname.vchcacheimages!=''" src="/resize1/showimage/@{{tpname.IntId}}/{{$managesite->intmanagesiteid}}/@{{tpname.VchResizeimage}}/?=@{{tpname.intsetdefault}}" > 
								</div>

							</a> 

							<a href="@{{tpname.VchFolderPath}}/@{{tpname.VchVideothumbnail}}" ng-if="tpname.EnumType=='V'">
								<div class="image" ng-if="tpname.Vchcustomthumbnail!=''">
								<!--<img ng-if="tpname.Vchcustomthumbnail!=''" src="@{{tpname.VchFolderPath}}/@{{tpname.Vchcustomthumbnail}}" >--> 
									<img ng-if="tpname.Vchcustomthumbnail!=''" src="/resize2/showimage/@{{tpname.IntId}}/{{$managesite->intmanagesiteid}}/@{{tpname.Vchcustomthumbnail}}/?={{rand(10,100)}}" > 
								</div>
								<div class="image" ng-if="tpname.Vchcustomthumbnail==''">
								<!--<img ng-if="tpname.Vchcustomthumbnail==''" src="@{{tpname.VchFolderPath}}/@{{tpname.VchVideothumbnail}}"> -->

									<img ng-if="tpname.Vchcustomthumbnail==''" src="/resize2/showimage/@{{tpname.IntId}}/{{$managesite->intmanagesiteid}}/@{{tpname.VchVideothumbnail}}/?={{rand(10,100)}}"> 
								</div>
							</a>
					</div>
					<!--ng-if="tpname.EnumUploadType=='G'" ng-click="iframevideo(tpname.vchgoogledrivelink);"-->
					<div class="proper_fit" ng-if="tpname.EnumUploadType=='G'" >
						<span class="colorwhite" style="color:#fff;">@{{tpname.VchTitle}}</span>
						<!-- <a href="@{{tpname.VchVideothumbnail}}"  class="group1">-->
						<a href="@{{tpname.VchFolderPath}}/@{{tpname.VchVideothumbnail}}" ng-if="tpname.EnumType=='V'">
							<div class="image" ng-if="tpname.Vchcustomthumbnail!=''">
								<img ng-if="tpname.Vchcustomthumbnail!=''" src="@{{tpname.VchFolderPath}}/@{{tpname.Vchcustomthumbnail}}" >
							</div>
							<div class="image" ng-if="tpname.Vchcustomthumbnail==''">
								<img ng-if="tpname.Vchcustomthumbnail==''" src="@{{tpname.VchVideothumbnail}}"> 
							</div>
						</a>


					</div>

				</li> 
			</ul>
		</div>
		<pagination total-items="totalItems"  ng-change="pageChanged(currentPage)" ng-model="currentPage" max-size="maxSize" class="pagination" boundary-links="true" rotate="false" num-pages="numPages" items-per-page="itemsPerPage"></pagination>

		</div>
	</div>

</section>


<div class="videoplayermodel1">
	<div class="mainconatinerdiv">
		<div class="divmaincontent" style="background: #eeeeeeb0;">
			<div class="video_parts_iner">
				<video id="fvideoID" controls>
					<source src="@{{videoname}}" type="video/mp4">
					<source src="@{{videoname}}" type="video/ogg">
					Your browser does not support the video tag.
				</video>
				<br>
				<div class="speed-scroll">	
					<label> playback speed</label>
					<Select onchange="setPlaySpeed2(this.value)">
						<option value="0.25">0.25</option>
						<option value="0.50">0.50</option>
						<option value="1.0" Selected>1.0</option>
						<option value="1.25">1.25</option>
						<option value="1.50">1.50</option>
						<option value="2.0">2.0</option>
						<option value="3.0">3.0</option>
						<option value="4.0">4.0</option>
						<option value="5.0">5.0</option>
					</select>
				</div>	
				<div class="closediv1">
					<a href="javascript:void(0);" class="closebutton">&#10005;</a>
				</div>
			</div>
		</div>	
	</div>
</div> 

<!--<button class="open-button" onclick="openbigForm()">Open Form</button>-->

<div class="big-image" id="bigimg">


	<div class="row uppr">
	<div class="col-md-8 col-offset-md-4">
<h3 class="bigimagename">Image Name</h3>
</div>
		<div class="col-md-8">
			<div class="image-center">
			
				<div class="imgs-setup" id="image-div">
					<div class="bigimgcontainer">
						<input type="checkbox" id="zoomCheck">
						<label for="zoomCheck">
							<img src="" class="non-active" id="bigimagesize">
						</label>
					</div>
					
				</div>
				<div class="imgs-setup" id="video-div">
				<div class="bigimgcontainer">
					<video id="videoID" controls>
					<source src="/@{{videopath}}/@{{videoname}}" type="video/mp4">
					<source src="/@{{videopath}}/@{{videoname}}" type="video/ogg">
					Your browser does not support the video tag.
				</video>
					</div>
				</div> 
	
			
		
			
				<div class="img-btm ">
					<span>Not quite what you are looking for?</span>
					<a href="/custom">Request Custom Graphics</a>
				</div>
			</div>
		</div>
		<div class="col-md-4 ryt">
		<span class="close_icon" onclick="closebigForm()">&#10005;</span>
		 @if(empty($userdetail))
			<div class="login_form2" >
		<form class="form-container" id="loginForm2" autocomplete="off">
		
		 
			@csrf
			
			<h3>
				Login your account
			</h3>
		
			<div class="form-group">
				<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email or username" name="email" requried>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password" requried>
					</div>
					<button type="submit" class="btn btn-primary org">Log in</button>
				
				<div class="botm">
					<p>Don't have free account yet?</p>
					<button type="submit" class="btn btn-primary trans" onclick="openForm2('signup2')">Create your account</button>
				</div>
			</form>
		</div>
		<div class="register_form2" style="display: none;">
			<form class="form-container" id="registrationForm2" autocomplete="off">
				@csrf

				<h3>Create your free account 
					
				</h3>
				<div class="form-group">
					<input type="text" class="form-control" id="" aria-describedby="emailHelp" placeholder="Full Name" name="first_name" required>
					</div>
					
						<div class="form-group">
							<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email" name="email" required>
							</div>
							<div class="form-group">
								<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password" required>
								</div>
							<div class="form-group">
								<div class="g-recaptcha" data-sitekey="6LeSjNQUAAAAAMx8CjWAsjcnBcQml4Dsvc6NmFHI"></div>
									</div>
								<button type="submit" class="btn btn-primary org">Sign Up</button>
								<div class="botm">
									<p>Already have an account?</p>
									<button type="submit" class="btn btn-primary trans" onclick="openForm2('signin2')">Log in</button>
								</div>
							</form>
						</div>
						@else
						<div class="login-user">
							<p>
							Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
							</p>
							<form class="" id="" autocomplete="off" >
								@csrf
									<div class="checkbox">
										<label>
										<input class="form-control" type="checkbox" id="agree" name="" value="y" required>Agree with Terms and condition</label>
									</div>
									 
										
									
									
							<button type="submit" class="btn btn-primary org">Download</button>
							</form>
						</div>
						@endif
						<div class="rlt-key"></div>
						
		</div>
		
	</div>
  
    

</div>


 <script src='https://www.google.com/recaptcha/api.js' async defer></script>
<script>


function openForm2(formname2) {
  //document.getElementById("myForm").style.display = "block";
  if(formname2 == 'signup2'){
  		document.getElementsByClassName('login_form2')[0].style.display = "none";
  		document.getElementsByClassName('register_form2')[0].style.display = "block";

  }else if(formname2 == 'signin2'){
  		document.getElementsByClassName('login_form2')[0].style.display = "block";
  		document.getElementsByClassName('register_form2')[0].style.display = "none";
  }

}

function openbigForm() {
  document.getElementById("bigimg").style.display = "block";
}

function closebigForm() {
	
  document.getElementById("bigimg").style.display = "none";
}


//var videoID = document.getElementById("videoID");
var videoID = $('#videoID');
function setPlaySpeed(speed) { 
  videoID.playbackRate = speed;
} 
var videoID2 = document.getElementById("fvideoID");
function setPlaySpeed2(speed) { 
  videoID2.playbackRate = speed;
}
$(document).ready(function(){
  $(".info-advance-search").click(function(){
	
    $(".iconsdsf").toggleClass("show");
	if($(this).find("i").attr('class') == 'fa fa-plus'){
		$(this).find("i").attr('class','fa fa-minus'); 
	}else{
		$(this).find("i").attr('class','fa fa-plus'); 
	}
  });
});

 
</script>
@include('footer')