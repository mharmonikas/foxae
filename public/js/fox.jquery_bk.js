var app = angular.module('myApp', ['ui.bootstrap']);

app.controller('customersCtrl', function ($scope, $http) {


	var client = {
		init: function () {
			var o = this;

			$("img").mousedown(function (e) {
				e.preventDefault()
			});


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
		$scope.currentPage = 1;
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
		//alert('yes');
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
	$scope.showvideo = function (videoname, videopath, type, uploadtype, vchgoogledrivelink) {

		var currentsiteid = $("#currentsiteid").val();
		if (uploadtype == 'W') {
			if (type == 'V') {
				$scope.videoname = 'watermark.mp4';
				$scope.videopath = videopath+'/'+currentsiteid;
				$('.videoplayermodel').fadeIn("slow");
				var video = document.getElementById('videoID');
				video.load();
				video.play();
			}
		} else {

			if (type == 'V') {
				$scope.videoname = 'watermark.mp4';
				$scope.videopath = videopath+'/'+currentsiteid;
				$('.videoplayermodel').fadeIn("slow");
				var video = document.getElementById('videoID');
				video.load();
				video.play();
			}

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
		var currentsiteid = $("#currentsiteid").val();
		var datatype = $(this).attr("data-type");
		var name = $(this).attr('data-name');
		var tags = $(this).attr('data-tags');
		var productid = $(this).attr('data-id');
		if(datatype == "V"){
			var path = "/"+$(this).attr('data-folder')+"/"+currentsiteid+"/watermark.mp4"
			$("#imagepart").css("display","none");
			$("#videopart").css("display","block");
			$("#newvideo").html('<source src="'+path+'"  type="video/mp4">');
			$(".bigimagename").text(name);

		}else if(datatype == "I"){
			$("#imagepart").css("display","block");
			$("#videopart").css("display","none");

			document.getElementsByClassName('login_form')[0].style.display = "none";
			document.getElementsByClassName('register_form')[0].style.display = "none";
			document.getElementsByClassName('forgot_form')[0].style.display = "none";
		   $("#bigimagesize").removeClass("big-active");
		   $("#bigimagesize").addClass("non-active");

		   $('#zoomCheck').prop('checked', false);
			var img = $(this).attr('data-image');



			$(".bigimagename").text(name);



			var res = img.replace("%20", "W3Schools")
			$("#bigimagesize").attr("src",res);
		}

		$("#productid").val(productid);


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
		openbigForm();

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

}
