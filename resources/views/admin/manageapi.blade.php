@include('admin/admin-header')
 
<div class="admin-page-area">
@include('admin/admin-logout')

<style>
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: inherit !important;
    margin-right: 10px;
}
</style>
<div class="">
	<div class="col-md-12 mar-auto">
		  <div class="back-strip top-side srch-byr">
				<div class="inner-top">
				 Manage API 
				</div>
		  </div>
                <div class="searchtags theme_opt_out">
						<form method="POST" enctype="multipart/form-data" action="/admin/update_api">
							@csrf
						       <div class="ful-top gap-sextion" id="product_container">
										<div class="col-md-12">
												<input type="hidden" name="apiid" @if(!empty($api->id)) value="{{$api->id}}" @endif>
												<div class="form-group">
													<label for="site_name">Key</label>
														<input required="" type="text" name="stripe_key" class="form-control" id="site_key" @if(!empty($api->stripe_key)) value="{{$api->stripe_key}}" @endif>
												</div>
												<div class="form-group">
													<label for="site_url">Secret</label>
														<input type="text" name="stripe_secret" required="" class="form-control" id="site_url"  @if(!empty($api->stripe_secret)) value="{{$api->stripe_secret}}" @endif>
												</div>
											
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




@include('admin/admin-footer')