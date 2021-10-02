@include('admin/admin-header')

 <script src="{{ asset('public/js/amcharts.js') }}"	></script>
 <script src="{{ asset('public/js/serial.js') }}"	></script>
<div class="admin-page-area">
<!-- top navigation -->
         @include('admin/admin-logout')
					<!-- /top navigation -->
       <!-- /top navigation -->
   <div class="buyer-manage">
	<div class="col-md-12 mar-auto">
	<div class="ful-top">
	<div class="back-strip top-side srch-byr">
	<div class="inner-top">
				Export Search Tags
	</div>
	</div>
	<form action ="/admin/exportsearchcategory" method="POST" name="searchtag" class="searchtag">
	 @csrf
   <!-- <div class="form-group">
	<label>Please Select Export Document Type</label>
	<Select name="exporttype">
	<option value="">Select Your export Type</option>
	<option value="1">CSV</option>
	<option value="2">Excel</option>
	</select>
	</div> -->
	 <div class="form-group">
	<button type="submit" class="btn btn-primary">Export</button>
	</div>
	</form>
    </div>
    </div>
    </div>
   </div>  
	</div>
	</div>
	
		
@include('admin/admin-footer')