@include('admin/admin-header')
<div class="admin-page-area">
@include('admin/admin-logout')
<div class="">
<style>
.uploadedimage {
	width: 250px;
}
.addnew {
	background: #3c8dbc none repeat scroll 0 0;
	border-radius: 3px;
	color: #fff;
	font-size: 15px;
	height: 40px;
	line-height: 40px;
	margin-bottom: 11px;
	text-align: center;
	width: 105px;
}
.addnew {
	float: right;
}
</style>
<div class="col-md-12 mar-auto">
		  <div class="back-strip top-side srch-byr">
				<div class="inner-top">
				@if(@$response->intmanagesiteid == "") Add @else Update @endif Domains
				</div>
		  </div>
                <div class="searchtags theme_opt_out">
						<form  method="POST" enctype="multipart/form-data" action="/admin/adddomains" enctype="multipart/form-data">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						 <!--@csrf-->
						 
						       <div class="ful-top gap-sextion"  id="product_container">
										<div class="col-md-12">
												<div class="form-group">
													<label for="site_name">Site Name:</label>
														<input required type="text" name="site_name" class="form-control" id="site_name" value="{{@$response->vchsitename}}">
												</div>
												<div class="form-group">
													<label for="site_url">Site Url:</label>
														<input type="text" name="site_url" required class="form-control" id="site_url" value="{{@$response->txtsiteurl}}">
												</div>
												<div class="form-group">
													<label for="meta_title">Meta Title:</label>
														<input type="text" name="meta_title" class="form-control" id="meta_title" value="{{@$response->vchmetatitle}}">
												</div>
												<div class="form-group">
													<label for="description">Description</label>
														<input type="text" name="description" class="form-control" id="description" value="{{@$response->vchdescription}}">
												</div>
												<div class="form-group">
													<label for="Keywords">Keywords:</label>
														<input type="text" name="keywords" class="form-control" id="keywords" value="{{@$response->vchkeywords}}">
												</div>
												<div class="form-group">
													<label for="EmailFrom">Email From:</label>
														<input type="text" name="emailfrom" class="form-control" id="EmailFrom" value="{{@$response->vchemailfrom}}">
												</div>
												<div class="form-group">
													<label for="EmailTo">Email To:</label>
														<input type="text" name="emailto" class="form-control" id="EmailTo" value="{{@$response->vchemailto}}">
												</div>
												@if(@$response->intmanagesiteid == "")
												<div class="form-group">
													<label for="Keywords">Small Watermark:</label>
														<input type="file" name="smalllogo" class="form-control" accept="image/*" >
												</div>
												<div class="form-group">
													<label for="Keywords">Large Watermark:</label>
														<input type="file" name="largelogo" class="form-control" accept="image/*" >
												</div>
												<div class="form-group">
													<label for="Keywords">Video Watermark:</label>
														<input type="file" name="videologo" class="form-control" accept="image/*" >
												</div>
												@endif
												<div class="form-group">
													<label class="radio-inline">Live Server
													  <input type="radio" name="status" Value="L" @if(@$response->status == 'L') Checked @endif>
													  <span class="checkmark"></span>
													</label>
													<label class="radio-inline">Dev Server
													  <input type="radio"  name="status" Value="D" @if(@$response->status == 'D') Checked @endif>
													  <span class="checkmark"></span>
													</label>
												</div>
												   <input type="hidden" name="intmanagesiteid" value="{{ @$response->intmanagesiteid }}">

												<div class="form-group" style="clear:both">
												  <input type="submit" value="Submit" class="btn btn-dafualt">
												</div>	
										</div>
								</div>
						</form>
				</div>
           <div class="clearfix"></div>
       </div>  
    </div>
</div>

<style>
/* The radio-inline */
.radio-inline {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 16px;
      font-weight: 500;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  width: 25%;
    float: left;
}

/* Hide the browser's default radio button */
.radio-inline input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom radio button */
span.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #fff;
  border: 2px solid #e66b3e;
  border-radius: 50% !important;
}

/* On mouse-over, add a grey background color */
.radio-inline:hover input ~ .checkmark {
  background-color: #fff;
}

/* When the radio button is checked, add a blue background */
.radio-inline input:checked ~ .checkmark {
  background-color: #fff;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.radio-inline input:checked ~ .checkmark:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.radio-inline .checkmark:after {
 	    top: 6px;
    left: 5.8px;
    width: 10px;
    height: 10px;
	border-radius: 50%;
	background: #e66b3e;
}
</style>

<div class="space100"></div>
<script src="/js/jscolor.js"></script>

@include('admin/admin-footer')