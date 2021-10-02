@include('admin/admin-header')
 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"/>
<div class="admin-page-area">
@include('admin/admin-logout')
<style>

.table-bordered-responsive h2 {
    text-align: center;
    font-size: 29px;
    font-weight: 600;
}
.table-bordered-responsive .table-responsive {
    padding: 2% 1%;
}
.table-bordered-responsive thead tr th {
    background: #fff !important;
    color: #000;
    padding: 10px 7px;
}
.table-bordered-responsive tr td {
    background: #fff !important;
    color: #000;
    padding: 10px 7px;
}
.table-bordered-responsive .table-responsive {
    background: #fff;
}
.table-bordered-responsive button.btn.btn-dafualt {
    margin: 1px 0 0 8px;
}
.table-bordered-responsive .form-control {
    margin: 0 0 0 15px;
    height: 38px;
}
.btn-success:hover {
    color: #fff !important;
}
.btn-dafualt {
    background-color: #e56c3d;
    border-color: #e56c3d;
    color: #fff;
}
th {
  background: #357ca5 none repeat scroll 0 0;
  color: #fff;
  font-size: 15px;
  font-weight: normal;
  padding: 8px 14px;
}

.btn.btn-primary {
    position: unset !important;
   
}

[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: unset;
    
}
</style>
<div class="">
   <div class="col-md-12 mar-auto table-bordered-responsive">
      <h2>Manage User</h2>
      <div class="container">
         @include('flash-message')
         <div class="table-responsive">
            <div class="">
               <form class="form-inline">
                  <div class="form-group"><input type="text" class="form-control" name="search" value="{{$search}}" placeholder="Search"> </div>
                  <div class="form-group">
                     <select class="form-control" name="domain">
                        <option value="">Select Domain</option>
                        @foreach($domains as $dom)<option value="{{$dom->intmanagesiteid}}" @if($domain==$dom->intmanagesiteid) Selected @endif>{{$dom->txtsiteurl}}</option>@endforeach
                     </select>
                  </div>
                  <button type="submit" class="btn btn-dafualt" >Search</button>
               </form>
               <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#exportModal" id="btn">Export CSV</button>
               <button class="btn btn-primary pull-left" data-toggle="modal" data-target="#send-email" id="btn">Send Email</button>
            </div>
            <table class="table table-condensed ">
               <thead>
                  <tr>
                     <th>
                        <div class="checkboxs"> <label><input type="checkbox" value="all" name="check" id="select_all"> Select All</label></div>
                     </th>
                     <th>Name</th>
                     <th>Email </th>
                     <th>Site</th>
                     <th>Last Login</th>
                     <th>Credits</th>
                     <th>Status</th>
                     @if(strstr($access, "1"))
                     <th>Action</th>
                     @endif
                     <th>Email Verification</th>
                     <th>Send Email</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($response as $res) 
                  <tr>
                     <td>
                        <div class="checkboxs"> <label><input type="checkbox" class="checkbox" value="{{$res->vchemail}}" id="{{$res->intuserid}}"></label></div>
                     </td>
                     <td>{{ucfirst($res->vchfirst_name)}}</td>
                     <td>{{$res->vchemail}}</td>
                     <td>{{$res->txtsiteurl}}</td>
                     <td>{{$res->lastlogin}}</td>
                     <td><a style="color:#0000ff;" data-toggle="modal" data-target="#credit_{{$res->package_id}}">{{$res->availablecount}}</a></td>
                     <td>@if( $res->enumstatus=='A')<button type="button" id="{{$res->intuserid}}" class="btn btn-success btn-sm @if(strstr($access, "1")) status @endif" data-title="Active" title="Active"><span>Active</span></button>@else<button type="button" id="{{$res->intuserid}}" class="btn btn-danger btn-sm @if(strstr($access, "1")) status @endif" data-title="Deactive" title="Deactive"><span>Deactive</span></button>@endif</td>
                     @if(strstr($access, "1"))
                     <td><button type="button" id="{{$res->intuserid}}" class="btn btn-danger btn-sm delete" data-title="Delete" title="Remove this"><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button><a href="/admin/managedownload/{{$res->intuserid}}" class="btn btn-success btn-sm" data-title="Download" title="Downloads"><span><i class="fa fa-download" aria-hidden="true"></i></span></a><a href="/admin/managepack/{{$res->intuserid}}" class="btn btn-success btn-sm" data-title="Download" title="Plans"><span><i class="fa fa-product-hunt" aria-hidden="true"></i></span></a><a href="/admin/managepayment/{{$res->intuserid}}" class="btn btn-success btn-sm" data-title="Download" title="Payments"><span><i class="fa fa-money" aria-hidden="true"></i></span></a></td>
                     @endif
                     <td style="text-align: center;">@if( $res->verifystatus=='1')<button type="button" id="{{$res->intuserid}}" class="btn btn-success btn-sm" data-title="Active" title="Email Verified"><span>Yes</span></button>@else<button type="button" id="{{$res->intuserid}}" class="btn btn-danger btn-sm" data-title="Deactive" title="Email not Verified"><span>No</span></button>@endif</td>
					 
					 <td><button type="button" id="{{$res->vchemail}}" data-id="{{$res->intuserid}}" class="btn btn-success btn-sm send-email-single"  data-toggle="modal" data-target="#send-email"><span>Send Email</span></button></td>
                  </tr>
                  @endforeach 
               </tbody>
            </table>
            {{$response->links()}}
         </div>
      </div>
   </div>
</div>
@foreach($response as $res)
<div class="modal fade" id="credit_{{$res->package_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add and Remove Credits</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button> 
         </div>
         <form method="post" action="/admin/manage_credits">
            {{csrf_field()}}
            <div class="modal-body">
               <div class="form-group">
                  <div class="col-md-12"><label >Available Credit</label><input type="text" name="current_packagecount" value="{{$res->availablecount}}" class="form-control" placeholder="+/-" readonly></div>
                  <div class="col-md-6"> <label >Operation Perform</label><input type="text" name="sign" class="form-control" placeholder="+/-"></div>
                  <div class="col-md-6"> <label >Credits</label><input type="text" name="credit" class="form-control" placeholder="Credits"><input type="hidden" name="package_id" value="{{$res->package_id}}" ><input type="hidden" name="user_id" value="{{$res->intuserid}}" ><input type="hidden" name="site_id" value="{{$res->site_id}}" ></div>
                  <div class="col-md-12"><label >Expiration Date(How many days)</label><input type="text" name="exp_days" value="" class="form-control" ></div>
               </div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Save changes</button> </div>
         </form>
      </div>
   </div>
</div>
@endforeach 
<div id="exportModal" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> </div>
         <div class="modal-body">
            <form method="POST" action="/admin/exportuserlist" autocomplete="off">
               {!! csrf_field() !!}
               <div class="form-group col-sm-12">
                  <label class="col-sm-6 col-form-label"><strong>Export Start date : </strong></label> 
                  <div class="col-sm-5"> <input type="text" class="form-control datetimepicker2" id="" name="startdate" value=""> </div>
               </div>
               <div class="form-group col-sm-12">
                  <label class="col-sm-6 col-form-label"><strong>Export End date: </strong></label> 
                  <div class="col-sm-5"> <input type="text" class="form-control datetimepicker2" name="enddate" placeholder="" value=""> </div>
               </div>
               <div class="form-group col-sm-12">
                  <label class="col-sm-6 col-form-label"><strong>Select Website: </strong></label> 
                  <div class="col-sm-6">
                     @foreach($domains as $dom)
                     <div class="domain-checkbox"><label class="col-form-label"><strong>{{$dom->txtsiteurl}}</strong></label><input type="checkbox" class="form-control" placeholder="" name="domain[]" value="{{$dom->intmanagesiteid}}"> </div>
                     @endforeach
                  </div>
               </div>
               <div class="form-group col-sm-12">
                  <label class="col-sm-4 col-form-label"><strong></strong></label> 
                  <div class="col-sm-5"> <button type="submit" class="btn btn-default">Export CSV</button> </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>


<div class="modal fade" id="send-email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Send Email</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button> 
         </div>
         <form method="post" action="/sendemail">
            {{csrf_field()}}
            <div class="modal-body">
					<div class="form-group">
						<input type="text" class="form-control" name="to_email" id="to_email" placeholder="To" readonly required>
						<input type="hidden" class="form-control" name="users_id" id="users_id" >
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="to_subject" id="to_subject" placeholder="Subject" required>
					</div>
					<div class="form-group">
					  <textarea class="form-control" rows="5" id="message" name="message" placeholder="Message" required></textarea>
					</div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Send Email</button> </div>
         </form>
      </div>
   </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
<script>
	$('.datetimepicker2').datetimepicker({
	
	lang:'ch',
	timepicker:false,
	format:'Y/m/d',
	formatDate:'Y/m/d',
	
});

$( ".delete" ).click(function() {
	var id=$(this).attr('id');
	 if (confirm('Are you sure you want to delete the user?')) {
	        $.ajaxSetup({
            url: "/admin/delete-user",
            data: { id: id },
            async: true,
            dataType: 'json',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
			},
          });
        $.post()
        .done(function(data) {
		if(data.response==1){
				window.location = '/admin/manageuser';
			}
        })
        .fail(function(response) {
			
            console.log('failed');
        })
	 }
    });
	
	$( ".status" ).click(function() {
	var id=$(this).attr('id');
	var status=$(this).attr('data-title');
	 if (confirm('Are you sure you want to change the status?')) {
	        $.ajaxSetup({
            url: "/admin/change-status",
            data: { id: id, status:status },
            async: true,
            dataType: 'json',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
			},
          });
        $.post()
        .done(function(data) {
		if(data.response==1){
				window.location = '/admin/manageuser';
			}
        })
        .fail(function(response) {
			
            console.log('failed');
        })
	 }
    });
$(document).ready(function(){
    $('#select_all').on('click',function(){
		var checkval = [];
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
			checkval[0] = 'ALL';
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
        }
		$("#to_email").val(checkval.join(','))
		$("#users_id").val('')
    });
    
    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
		
		var checkval = [];
		var selecteduser = [];
        $('.checkbox:checked').each(function(i){
          checkval[i] = $(this).val();
          selecteduser[i] = $(this).attr('id');
        });
		
		$("#to_email").val(checkval.join(','));
		$("#users_id").val(selecteduser.join(','));
    });
	
	
	$('.send-email-single').on('click',function(){
		$("#to_email").val($(this).attr('id'));
		$("#users_id").val($(this).attr('data-id'));
	});
});
</script>

@include('admin/admin-footer')