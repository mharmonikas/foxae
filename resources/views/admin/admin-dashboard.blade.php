@include('admin/admin-header')
<style>.small-box {
  background: #357ca5 none repeat scroll 0 0;
  color: #fff;
  font-size: 21px;
  padding: 14px 25px;
}
.small-box a {
  color: #fff;
}
.dashboard_bf {
  padding: 79px 31px;
}</style>
 <script src="{{ asset('public/js/amcharts.js') }}"	></script>
 <script src="{{ asset('public/js/serial.js') }}"	></script>
  <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
   <div class="admin-page-area">

        
<!-- top navigation -->
         @include('admin/admin-logout')
					<!-- /top navigation -->
	 
	   
        <!-- /top navigation -->
		<div class="dashboard_bf">	
		<div class="row">
        <div class="col-lg-4">
          <!-- small box -->
          <div class="small-box bg-aqua cnt-sml-bx">
            <div class="inner">
              <p> Manage Content</p>
			  <a href="/admin/managevideosection" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
			  <i class="fa fa-user"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4">
          <!-- small box -->
          <div class="small-box bg-green cnt-sml-bx">
            <div class="inner">
               <p> Tag Content</p>
			   <a href="/admin/taggedvideo" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
			  <i class="fa fa-tag"></i>
            </div>
            
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4">
          <!-- small box -->
          <div class="small-box bg-yellow cnt-sml-bx">
            <div class="inner">
              <p>Add Content</p>
			  <a href="/admin/uploadvideo" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
			  <i class="fa fa-folder"></i>
            </div>
            
          </div>
        </div>
        <!-- ./col -->
      
        <!-- ./col -->
      </div></div>
	
	</div>
	
		
@include('admin/admin-footer')