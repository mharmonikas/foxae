@include('admin/admin-header')
@include('admin/admin-logout')


<style>
.custom-table {
    padding: 10px 4% 0 18%;
}
a.btn.btn-primary.pull-right {
    position: initial !important;
}
span.required {
    color: red;
}
.custom-checkbox {
    position: unset !important;
    left: unset !important;
}
label.lable-custom {
    font-size: 20px;
    color: #34495e;
}
</style>
<div class="row">
		<div class="col-md-12 mar-auto">
		  <div class="back-strip top-side srch-byr">
				<div class="inner-top">
				 Create Discocunt 
				</div>
		  </div>    
		</div>
	    <div class="clearfix"></div>
		<div class="col-md-12 mar-auto custom-table">
			<div class="searchtags theme_opt_out">
						<form method="POST" enctype="multipart/form-data" action="/admin/discountadd" style="margin-bottom: 100px;" autocomplete="off">
							@csrf						       
							
							<div class="ful-top gap-sextion" id="product_container">
								<div class="col-md-12">
									<div class="form-group">
										<label for="end_date" class="lable-custom">Type</label>
									</div>
									<div class="form-group">
										<label class="radio-inline">One Time Uses
										  <input type="radio" name="type" value="O"  checked>
										</label>
										<label class="radio-inline">Multiple Uses
										  <input type="radio" name="type" value="M" >
										</label>
										<label class="radio-inline">World Wide
										  <input type="radio" name="type" value="W" >
										</label>
										
									</div>
									<div class="form-group">
										<label for="end_date" class="lable-custom">Place </label>
									</div>	
									<div class="form-group">
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox placecheckboxes" name="place[]" value="1" >Cart</label>
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox placecheckboxes" name="place[]" value="2" >Price</label>
									</div>
									
									<div class="form-group">
										<label for="end_date" class="lable-custom">Tier </label>
									</div>
									<div class="form-group">
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox tiercheckbox" name="tier[]" value="1" >Standard Image</label>
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox tiercheckbox" name="tier[]" value="2" >Premium Image</label>
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox tiercheckbox" name="tier[]" value="3" >Deluxe Image</label>
										
										<br> 
										
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox tiercheckbox" name="tier[]" value="4" >Standard Video</label>
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox tiercheckbox" name="tier[]" value="5" >Premium Video</label>
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox tiercheckbox" name="tier[]" value="6" >Deluxe Video</label>
										
									</div>
									
									<div class="form-group">
										<label for="end_date" class="lable-custom">Content </label>
									</div>	
									<div class="form-group">
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox placecheckboxes" name="content[]" value="1" >Image </label>
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox placecheckboxes" name="content[]" value="2" >Video</label>
									</div>
									
									<div class="form-group">
										<label for="coupon" class="lable-custom">Domain <span class="required">*</span></label>
										<select name="domain_id" class="form-control" required>
											<option value="">Select Option</option>
											<option value="A">All</option>
											@foreach($sitelists as $sitelist)
												<option value="{{$sitelist->intmanagesiteid}}">{{$sitelist->vchsitename}}</option>
											@endforeach
											
										</select>
									</div>
									<div class="form-group">
										<label for="coupon" class="lable-custom">Discount Type <span class="required">*</span></label>
										<select name="discount_type" class="form-control" required>
											<option value="">Select Option</option>
											<option value="P">Percentage(%)</option>
											<option value="A">Price($)</option>
										</select>
									</div>	
									<div class="form-group">
										<label for="amount" class="lable-custom">Amount <span class="required">*</span></label>
										<input type="text" name="amount" class="form-control" id="amount">
									</div>
									<div class="form-group">
										<label for="coupon" class="lable-custom">Coupon <span class="required">*</span></label>
										<input required="" type="text" name="coupon" class="form-control" id="coupon">
									</div>
									<div class="form-group">
										<label for="end_date" class="lable-custom">Expire  Date</label>
										<input type="text" name="end_date" class="form-control" id="end_date">
									</div>
			
									
									
					
									
									
									<div class="form-group">
										<label for="number_of_uses" class="lable-custom">Number of Uses</label>
										<input type="text" name="number_of_uses" class="form-control" id="number_of_uses"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
									</div>
									
									<div class="form-group">
									  <label for="note" class="lable-custom">Note:</label>
									  <textarea class="form-control" name="note" rows="5" id="note"></textarea>
									</div>

									<div class="form-group" style="clear:both">
									  <input type="submit" value="Submit" class="btn btn-dafualt">
									</div>	
								</div>
							</div>
						</form>
				</div>
		</div>
</div>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	function customType(type){
		if(type == 'S'){
			$("#custom-single").show();
			$('#custom-email').prop('required',true);
		}else{
			$("#custom-single").hide();
			$('#custom-email').prop('required',false);
		}
	}
$( function() {
    $( "#end_date" ).datepicker({
		changeMonth: true,
          changeYear: true,
          numberOfMonths: 1,
		  dateFormat: 'yy-mm-dd'
	});
  } );
  
</script>

@include('admin/admin-footer')