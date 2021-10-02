<!DOCTYPE html>
<?php
use App\Http\Controllers\MyadminController;
$AdminMenus  = MyadminController::getmenuaccess();
?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf_token" content="{{ csrf_token() }}"> 
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <title> Admin </title>
    <!-- Bootstrap -->
    <link href="/css/app.css?v=<?php echo(rand(100,1000000000)); ?>" rel="stylesheet">
	<link rel="stylesheet" href="/css/featrue.css?v=<?php echo(rand(100,1000000000)); ?>">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700" rel="stylesheet">
    <!-- Font Awesome -->
	 <link href="/css/app.css?v=<?php echo(rand(100,1000000000)); ?>" rel="stylesheet">
	 <link href="/css/cd.css?v=<?php echo(rand(100,1000000000)); ?>" rel="stylesheet">
    <link href="/css/admin.css?v=<?php echo(rand(100,1000000000)); ?>" rel="stylesheet">
    <link href="/css/admin-custom3.css?v=<?php echo(rand(100,1000000000)); ?>" rel="stylesheet">
    <!-- jQuery -->
    <script src="{{ asset('/js/app.js') }}"></script>
 
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
 .progress {
    border-radius: 0;
   // display: none;
}
.defauls_prog {
  padding: 17px 13px;
  width: 691px;
}
.defauls_prog .progress {
  border-radius: 5px;
  }
.btn-dafualt {
    background-color: #3490dc;
    border-color: #3490dc;
    color: #fff;
}
.nav_menu {
    position: relative;
    z-index: 99;
    display: none;
}
        
        .btn-file {
            position: relative;
            overflow: hidden;
        }
        
        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }
        .img-zone {
            background-color: #F2FFF9;
            border: 5px dashed #4cae4c;
            border-radius: 5px;
            padding: 20px; margin: 15px 0;
        }
.img-zone h2 {
            margin-top: 0;
        }
        
.progress, #img-preview {
    margin-top: 15px;
 }
 .list_div {
    display: block;
    max-height: 257px;
    overflow-y: scroll;
}
 .list_div ul {
    display: block;
    padding: 0;
}
.list_div ul li {
    display: block;
    margin: 10px 0;
    background: #f3f3f3;
    padding: 4px 8px; height: auto;
    border: 1px solid #ddd;
    border-radius: 4px;
}
.lit_txt {
    display: block;
    font-size: 14px;
}
.progress .progress-bar-success {
    display: inline-block;
	font-size: 10px;
    border-radius: 10px;
}
.lit_txt span {
    display: inline-block;
    background: #4cae4c;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 4px;
    margin: 0 0 0 5px;
    color: #fff;
}
.progress-bar-warning {
    display: inline-block;
    background-color: #f0ad4e;
    font-size: 10px;
    border-radius: 10px;
}
#progressBar {
    margin: 7px 0 3px;
}

		.profile {
  background: #111 none repeat scroll 0 0;
}
body{
font-family: 'Source Sans Pro', sans-serif;	
font-size:14px;
}
.back-strip {
  background: #ecf0f5 none repeat scroll 0 0;
  float: left;
  margin: auto;
  padding: 9px 0;
  text-align: center;
  width: 100%;
}
.inner-top {
  font-size: 21px;
  font-weight: 600;
}
.recentlyuploaded {
  padding-bottom: 356px;
}
.nav.child_menu > li > a, .nav.side-menu > li > a {
 
  color: #fff;
  font-size: 14px;
  font-weight: 300 !important;
}
 a.md-active {
    background-color: #2ea6d7 !important;
    color: #fff !important;
}
.rec-man.part-img {

        width: 55px;
    height: 52px;
    border-radius: 100%;
}
.last-btm {
    float: left;
    width: 100%;
    padding: 20px 20px;
}
.last-btm a{float:right;}
.box-body {
    width: 100%;
    display: inline-block;
    padding: 20px;
    box-sizing: border-box;
}
.amcharts-chart-div a {
    display: none !important;
}
div#chartdiv {
    width: 100% !important;
}
.box-blue {
	width: 30px;
    height: 15px;
    background-color: #67b7dc;
    float: right;
    margin-top: 30px;
	margin-left: 5px;
}
.box-green {
    width: 30px;
    height: 15px;
    background-color: #fdd400;
    float: right;
    margin-top: 0px;
    margin-left: 5px;
}
.both {
    float: right;
	    width: 20%;
}
.heading {
    font-size: 20px;
	margin-top: 30px;
	margin-left: 35%;
}
div#chartdiv2 {
    width: 100% !important;
}
div#Acceptchartdiv {
    width: 100% !important;
}div#Revenuchartdiv {
    width: 100% !important;
}
.table p a.btn.btn-danger {
    background: #ff5058 !important;
    border: 1px solid #ff5058;
}
.recentlyuploaded li {
    float: left;
    width: 23%;
    margin-right: 10px;
    margin-top: 10px;
    list-style: none;
    border: solid 4px #fff;
}

a.site_title img {
    width: 120px !important;
}
.nav.side-menu>li {
    position: relative;
    display: block;
    cursor: pointer;
    width: 100%;
}
.nav-left.scroll-view {
    background: #000000;
}
.profile_info {
    width: 70%;
    float: left;
    padding: 19px 19px;
   
}

.nav.side-menu>li.active>a, .nav.side-menu>li>a:hover, .nav.side-menu>li>a:visited {
    color: #f8fafc!important;
    font-weight: 700;

    background: #f3f3f3;
}

.profile_info h6 {
    color: #fff;
    margin: 0;
    font-weight: 700;
    font-size: 16px;
}
.image-icon {
    margin: 11px 20px;
}
.btn.btn-primary {
	position: absolute;
    right: 16px;
    top: 171px;
}
.nav.side-menu>li.active>a, .nav.side-menu>li>a:hover, .nav.side-menu>li>a:visited {
    color: #f8fafc!important;
    font-weight: 700;
  
    background: #000000;
}
.profile_info .dropdown-menu > li > a {
  display: inline-block;
}
.profile_info {
    position: relative;
    width: 100%;
}
.dropdown-menu.admin_dropdown.show {
  top: 18px !important;
  transform: none !important;
}
.active_menu > a {
  background: #111 none repeat scroll 0 0 !important;
}
.side-menu a:hover {
  background: #111 none repeat scroll 0 0 !important;
}
.info-loading-image {
    height: 100vh;
    justify-content: center;
    align-items: center;
    width: 100%;
    position: fixed;
    z-index: 999999;
    background: #ffffffba;
}
</style>
  </head> 
 <body class="nav-md palce-all">
	<div class="info-loading-image" style="display:none">
		<img src="/loading/loading.gif" class="loding-image">
	</div>
	<div class="all-wrapper">
          <div class="nav-left scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="{{ url('/admin/dashboard/') }}" class="site_title"><img src="{{ asset('images/logo.jpg') }}"></a>
            </div>
            <div class="clearfix"></div>
         
        <div class="profile clearfix"> 
             
            <div class="profile_info">
                <h6><?=Session::get('name')?></h6>
                <div class="dropdown">
					<span id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">		Admin<i class="fa fa-chevron-down" aria-hidden="true"></i>
					</span>
					<div class="dropdown-menu admin_dropdown" aria-labelledby="dropdownMenu2">
						
							<li class="dropdown-item" ><a href="{{ url('/admin/changepassword') }}" >Change Password</a></li>
						<li class="dropdown-item" ><a href="{{ url('/admin/logout') }}" >Logout</a></li>
					</div>
				</div>
				</span>
            </div>
        </div>
            <!-- /menu profile quick info -->

					<!-- sidebar menu -->
			<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
				<div class="menu_section">
					<ul class="nav side-menu">
					
					@foreach($AdminMenus as $AdminMenu)
							@if(strstr($AdminMenu->role, "2"))
								<li ><a href="{{ url($AdminMenu->url) }}" ><i class="{{$AdminMenu->icon}}"></i>{{$AdminMenu->name}} </a>
							@endif	
								
							
					@endforeach
					
						<!--<li ><a href="{{ url('/admin/dashboard/') }}"><i class="fa fa-home"></i>Dashboard </a>
							</li>
							<li><a href="{{ url('/admin/uploadvideo') }}"><i class="fa fa-plus-square "></i>Add Content</a></li>
							<li><a href="{{ url('/admin/taggedvideo/') }}"><i class="fa fa-gear"></i> Tag Content</a>
							</li>
							</li>
						<li><a href="{{ url('/admin/managevideosection/') }}"><i class="fa fa-wrench"></i> Manage Content</a></li>
				
						<li><a href="{{ url('admin/managesubcategorytagstags') }}"><i class="fa fa-gear"></i> Advanced Search Features </a></li> 
						
						<li><a href="{{ url('admin/websitemanagement') }}"><i class="fa fa-gear"></i> Website Management</a></li>  
					
						
						<li>
							<a href="{{ url('/admin/managedomains') }}">
						  <i class="fa fa-globe" aria-hidden="true"></i>Manage Domains</a>
						  
						</li> 
                         
						<li><a href="{{ url('admin/featured') }}"><i class="fa fa-gear"></i>  Manage Feature </a></li>
						
						<li><a href="{{ url('admin/manageuser') }}"><i class="fa fa-user"></i> Manage User</a></li>  
						<li><a href="{{ url('admin/managecustom') }}"><i class="fa fa-user"></i> Manage Custom</a></li>  
						<li><a href="{{ url('admin/manageadminuser') }}"><i class="fa fa-user"></i>Manage Administration</a></li>  -->
					</ul>
				</div>
			</div>
    </div>
      

     