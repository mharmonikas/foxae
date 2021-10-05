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
				 Edit Discocunt
				</div>
		  </div>
		</div>
	    <div class="clearfix"></div>
		<div class="col-md-12 mar-auto custom-table">
			<div class="searchtags theme_opt_out">
						<form method="POST" enctype="multipart/form-data" action="/admin/discountupdate/{{$response->id}}" style="margin-bottom: 100px;">
							@csrf

							<div class="ful-top gap-sextion" id="product_container">
								<div class="col-md-12">
								<div class="form-group">
										<label for="end_date" class="lable-custom">Type</label>
									</div>
									<div class="form-group">
										<label class="radio-inline">
										  <input type="radio" name="type" value="O"   @if($response->type == 'O') checked @endif>
                                          One Time Use
                                        </label>
										<label class="radio-inline">
										  <input type="radio" name="type" value="M" @if($response->type == 'M') checked @endif>
                                          Multiple Uses
										</label>
										<label class="radio-inline">
										  <input type="radio" name="type" value="W" @if($response->type == 'W') checked @endif>
                                          Website Wide
										</label>

									</div>

									@php
										$place = explode(",", $response->place);
									@endphp
									<div class="form-group">
										<label for="end_date" class="lable-custom">Place</label>
									</div>
									<div class="form-group">
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox placecheckboxes" name="place[]" value="1" @if (in_array("1", $place)) checked @endif >Cart</label>
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox placecheckboxes" name="place[]" value="2" @if (in_array("2", $place)) checked @endif >Price</label>
									</div>

									<div class="form-group">
										<label for="end_date" class="lable-custom">Tier</label>
									</div>
									@php
										$tier = explode(",",$response->tier);
									@endphp
									<div class="form-group">
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox tiercheckbox" name="tier[]" value="1" @if (in_array("1", $tier)) checked @endif >Standard Image</label>
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox tiercheckbox" name="tier[]" value="2" @if (in_array("2", $tier)) checked @endif >Premium Image</label>
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox tiercheckbox" name="tier[]" value="3" @if (in_array("3", $tier)) checked @endif >Deluxe Image</label>

										<br>

										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox tiercheckbox" name="tier[]" value="4" @if (in_array("4", $tier)) checked @endif >Standard Video</label>
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox tiercheckbox" name="tier[]" value="5" @if (in_array("5", $tier)) checked @endif >Premium Video</label>
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox tiercheckbox" name="tier[]" value="6" @if (in_array("6", $tier)) checked @endif >Deluxe Video</label>

									</div>

									<div class="form-group">
										<label for="end_date" class="lable-custom">Content </label>
									</div>
									@php
										$content = explode(",",$response->content);
									@endphp
									<div class="form-group">
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox placecheckboxes" name="content[]" value="1" @if (in_array("1", $content)) checked @endif>Image </label>
										<label class="checkbox-inline"><input type="checkbox" class="custom-checkbox placecheckboxes" name="content[]" value="2"  @if (in_array("2", $content)) checked @endif>Video</label>
									</div>

									<div class="form-group">
										<label for="coupon">Domain <span class="required">*</span></label>
										<select name="domain_id" class="form-control" required>
											<option value="">Select Option</option>
											<option value="A" @if($response->domain_id == 'A') Selected @endif>All</option>
											@foreach($sitelists as $sitelist)
												<option value="{{$sitelist->intmanagesiteid}}" @if($response->domain_id == $sitelist->intmanagesiteid) Selected @endif>{{$sitelist->vchsitename}}</option>
											@endforeach

										</select>
									</div>
									<div class="form-group">
										<label for="coupon">Discount Type <span class="required">*</span></label>
										<select name="discount_type" class="form-control" required>
											<option value="">Select Option</option>
											<option value="P" @if($response->discount_type == 'P') Selected @endif>Percentage(%)</option>
											<option value="A" @if($response->discount_type == 'A') Selected @endif>Price($)</option>
										</select>
									</div>
									<div class="form-group">
										<label for="amount">Amount <span class="required">*</span></label>
										<input type="text" name="amount" class="form-control" id="amount" value="{{$response->amount}}">
									</div>
									<div class="form-group">
										<label for="coupon">Coupon <span class="required">*</span></label>
										<input required="" type="text" name="coupon" class="form-control" id="coupon" value="{{$response->coupon}}">
									</div>
									<div class="form-group">
										<label for="end_date">Expired Date</label>
										<input type="text" name="end_date" class="form-control" id="end_date" value="{{$response->end_date}}">
									</div>

									<div class="form-group">
										<label for="number_of_uses" class="lable-custom">Number of Uses</label>
										<input type="text" name="number_of_uses" class="form-control" id="number_of_uses"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" value="{{$response->number_of_uses}}">
									</div>

									<div class="form-group">
									  <label for="note" class="lable-custom">Note:</label>
									  <textarea class="form-control" rows="5" name="note" id="note">{{$response->note}}</textarea>
									</div>

									<div class="form-group">
										<label for="coupon">Status <span class="required">*</span></label>
										<select name="status" class="form-control" required>
											<option value="A" @if($response->status == 'A') Selected @endif>Active</option>
											<option value="D" @if($response->status == 'D') Selected @endif>Inactive</option>
										</select>
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
			$('#custom-email').val('').prop('required',true);
		}else{
			$("#custom-single").hide();
			$('#custom-email').val('').prop('required',false);
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
