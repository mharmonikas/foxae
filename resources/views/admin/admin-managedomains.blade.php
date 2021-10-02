@include('admin/admin-header')
<style>
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
th {
  background: #357ca5 none repeat scroll 0 0;
  color: #fff;
  font-size: 15px;
  font-weight: normal;
  padding: 8px 14px;
}
td {
  padding: 8px 23px;
}
.pagination {
  margin-top: 12px;
}
.btn-primarry {
    background-color: #3490dc;
    border-color: #3490dc;
    color: #fff;
}
.btn.btn-primary {
  position: inherit;
}
button.btn.btn-primary.btn-xs {
    height: 22px;
    width: 75%;
    padding: 0;
    font-size: 13px;
    border-radius: 0;
}
.btn.btn-info.btn-sm {
    background: #46b8da;
    color: #fff;
}
</style>
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
				Manage Domains
			
	</div>
	</div>
@if(strstr($access, "1"))
	<div class="col-md-12">
			<a href="/admin/adddomains" class="addnew"><i class="fa fa-plus-square"></i>  Add Domain</a>
			
	</div>
	@endif
	
	<div class="view_data">
		<table class="table-bordered" width="100%">
			<thead style="text-align:center">
				<tr>
					   <th>Site Name</th>
						<th>Site Url</th>
						<th>Title</th>
						<th>Description</th>
						<th>Keyword</th>
						
						<th>Status</th>
						@if(strstr($access, "1"))
						<th>Action</th>
						@endif
						<th>Preview</th>
					
				</tr>
			</thead>
		
	
	<?php $count=1;?>
	
	@foreach($responce as $res)
    <tr class="delete_title_{{ $res->intmanagesiteid }}">    
       <td>{{$res->vchsitename}}</td> 
	    <td>{{$res->txtsiteurl}}</td> 
	    <td>{{$res->vchmetatitle}}</td> 
	    <td>{{$res->vchdescription}}</td> 
	    <td>{{$res->vchkeywords}}</td> 
		<td style="text-align:center">@if($res->status=='L')
		<button type="button" class="btn btn-primary btn-xs @if(strstr($access, "1")) btn-status  deactive-btn @endif" @if(strstr($access, "1"))data-id="{{ $res->intmanagesiteid }}" id="userstatus_{{ $res->intmanagesiteid }}" data-value="Deactive" @endif>Dev Server</button> 
	@else
		<button type="button" class="btn btn-primary btn-xs @if(strstr($access, "1")) btn-status @endif" @if(strstr($access, "1")) data-id="{{ $res->intmanagesiteid }}" id="userstatus_{{ $res->intmanagesiteid }}" data-value="Active" @endif>Live Server</button>
	@endif
	</td>
	@if(strstr($access, "1"))
	   	<td style="text-align:center">	
		
		<a href="/admin/managepages/{{$res->intmanagesiteid }}" class="btn btn-warning btn-sm" title="Legal Documents"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></i>
		</a>
		<a href="/admin/siteplans/{{$res->intmanagesiteid }}" class="btn btn-dark btn-sm" title="Change Plan"><i class="fa fa-usd" aria-hidden="true"></i></i>
		</a>
		<a href="/admin/themeoption/{{$res->intmanagesiteid }}" class="delete trashregristaion btn btn-info btn-sm" title="Change theme color"><i 
        class="fa fa-paint-brush" aria-hidden="true" ></i>
		</a>
		<a href="/admin/updatedomains/{{$res->intmanagesiteid }}" class="delete trashregristaion btn btn-success  btn-sm" title="Edit site"><i 
        class="fa fa-edit" aria-hidden="true" ></i>
		</a>
		<a href="javascript:void(0);"class="otherRemove btn btn-danger delete  btn-sm" id="{{$res->intmanagesiteid }}" data-tocken="{{csrf_token()}}" title="Delete site"><i class="fa fa-trash" aria-hidden="true"></i>
        </a>
		</td>
		@endif
		<td style="text-align:center">	
		<a href="https://{{$res->txtsiteurl}}" target="_blank" class="btn btn-success  btn-sm"  Title="Perview"> <i class="fa fa-link" aria-hidden="true"></i>
        </a> </td>
	 

		<!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> -->
    </tr>
    <?php $count++;?>
@endforeach
		</table>
	
		</div>
	
    </div>
    </div>
    </div>
   </div>  
	


  <!-- <div class="modal fade" id="myModal" role="dialog">

    <div class="modal-dialog">
    <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
      <!-- Modal content-->
      <!--<div class="modal-content">
        
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div> -->
  
  <!--<div class="modal" id="myModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="" name="form1">
	   <div class="form-group">
	 <input type="text" class="form-control" name="categorytitle" id="categorytitle1">
	 <input type="hidden"  name="category" id="category" value="">
	 </div>
	 <div class="form-group">
    <select name="parentcat" class="form-control" id="parentcat">
	 <option value="0">Select Parent category</option>
	<?php 
	//foreach($parentcategory as $categorytitle){ ?>
	<option value="<?//php echo $categorytitle->IntId; ?>"><?php //echo $categorytitle->VchTitle; ?></option>
	<?php //} ?>
	</select>
    </div>	
	<input type="button" class="btn btn-primarry" name="submit" id="editchild" value="Save">
	</form>
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>-->

</div>
@include('admin/admin-footer')
<script type="text/javascript">
	$(document).ready(function(){
	 $('.addnew').click(function(){
	 $('#myModal').modal('show'); 	 
});
		// edit data
		$('.edit').click(function(){
			
			
			 var id= $(this).attr('id');
			 var title= $(this).attr('title');
			 
			 var parentcatid= $(this).attr('parentcatid');
			 $('#category').val(id);
			 $('#parentcat').val(parentcatid);
			  $('#categorytitle1').val(title);
			  $('#myModal').modal('show'); 
			/*var token= $('meta[name="csrf_token"]').attr('content');
			  
			$.ajax({
				url:'{{ URL::to("/admin/editmastertag") }}',
				type:'POST',
				data:{'id':id,'_token':token},
				success:function(ress1){
					alert(ress1);

				}
			}); */

		});
        $('#editchild').click(function(){
		var categorytitle = $('#categorytitle1').val();	
		if(categorytitle==''){
			
			alert("Please enter Category Title");
			return false;
		}
		var category = $('#category').val();
        var parentcat = $('#parentcat').val();		
		 var token= $('meta[name="csrf_token"]').attr('content');
			  
			$.ajax({
				url:'{{ URL::to("/admin/addeditaddsearchtags") }}',
				type:'POST',
				data:{'categorytitle':categorytitle,'category':category,'parentcat':parentcat,'_token':token},
				success:function(ress){
					window.location.href="";
					//$('.delete_title_'+id).remove();

				}
			});	
			
		});
		//delete data
			$(".otherRemove").click(function(){
	if(confirm("Are you sure you want Delete this?")){
		$(this).parent().parent().remove();
		 var id = $(this).attr("id"); 
		 
		 var token = $(this).attr("data-tocken"); 
		$.ajax({     
				url: '{{ URL::to("admin/deletedomains/delete") }}',
				type:"POST",  
				headers: {
				'X-CSRF-TOKEN':token
				},        
				data:'id='+id+'&_token='+token,
				success:function(data){       
					//alert(data);
				}
		})
	}
 });
   $(".btn-status").click(function(){
	  var status = $(this).attr('data-value');
	  var id = $(this).attr("data-id");
	  var token=$('meta[name="csrf_token"]').attr('content');
		$.ajax({     
				url: "/admin/status",
				type:"POST",  
				headers: {
				'X-CSRF-TOKEN':token
				},        
				data:'id='+id+'&status='+status+'&_token='+token,
				success:function(data){       
					if(status == 'Active'){
						$("#userstatus_"+id).html('Dev Server');
						$("#userstatus_"+id).removeClass('deactive-btn');
						$("#userstatus_"+id).attr("data-value","Deactive");
						
					}else{
						$("#userstatus_"+id).html('Live Server');
						$("#userstatus_"+id).addClass('deactive-btn');
						$("#userstatus_"+id).attr("data-value","Active");
					}
				}
		})
 });
 
 });
</script>