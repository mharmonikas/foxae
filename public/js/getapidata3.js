var app = angular.module('myApp', ['ui.bootstrap']);
 app.controller('customersCtrl', function($scope, $http) {
  var currentpagepagination = 1;
	$scope.searchkeyword = '';
	 $scope.allsearch ='';
 $scope.setPage = function (pageNo) {
    $scope.currentPage = pageNo;
 };
  $scope.pageChanged = function() {
	var limit = $scope.currentPage-1;
	var searchkeyword = $scope.searchkeyword;
	limit = limit*10;
	currentpagepagination = $scope.currentPage;
    $http.get('/getallvideo?_token = <?php echo csrf_token() ?>&searchtext='+searchkeyword+'&startlimit='+limit).then(successCallback, errorCallback);
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
  $scope.allvideo ='';
  $scope.allvideo = Object.assign({}, response.data.allvideo);
  $scope.viewby = 10;
  $scope.totalItems = response.data.totalvideo;
  $scope.currentPage = currentpagepagination;
  $scope.itemsPerPage = 10;
  $scope.maxSize = 10;
  count++;
}
function successCallback1(response){
  $scope.allsearch = Object.assign({}, response.data);
  $scope.$apply();
}

function errorCallback(error){
}
$scope.searchvideo = function(searchkeyword) {
  currentpagepagination = 1;
   $http.get('/getallvideo?_token = <?php echo csrf_token() ?>&searchtext='+searchkeyword+'&startlimit=0').then(successCallback, errorCallback);
 $http.get('/getkeywords?_token = <?php echo csrf_token() ?>&searchtext='+searchkeyword+'&startlimit=0').then(successCallback1, errorCallback);

 };
 $scope.changegeneder = function(geneder,value){
if(geneder==true){
 $http.get('/getallvideo?_token = <?php echo csrf_token() ?>&category=1&Tagid='+value+'&startlimit=0').then(successCallback, errorCallback);
 }else {
	 $http.get('/getallvideo?_token = <?php echo csrf_token() ?>&startlimit=0').then(successCallback, errorCallback);
 }
 }
 $('.racecategory').change(function(){
 var racecategory = $(this).val();
if(racecategory!=''){
$http.get('/getallvideo?_token = <?php echo csrf_token() ?>&category=1&Tagid='+racecategory+'&startlimit=0').then(successCallback, errorCallback);
}else {
	 $http.get('/getallvideo?_token = <?php echo csrf_token() ?>&startlimit=0').then(successCallback, errorCallback);
}
 });
  });
