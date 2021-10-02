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
  width: 94px;
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
				Manage Tags
			
	</div>
	</div>
<!-- <div class="inner-top">
			<a href="javascript:void(0);" class="addnew">Add New</a>
			
	</div> -->
	@if(strstr($access, "1"))
	<div class="col-md-12">
			<a href="javascript:void(0);" class="addnew"><i class="fa fa-plus-square"></i>  Add New</a>
			
	</div>
	@endif
	
	
	<div class="view_data">
		<table class="table-bordered" width="100%">
			<thead>
				<tr>
					
						<th>Sr.No</th>
						<th>Category Title</th>
						<th>Parent Category</th>
						@if(strstr($access, "1"))
						<th>Action</th>
						@endif


					
				</tr>
			</thead>
		
	
	<?php $count=1;?>
	
	@foreach($getvideosearch as $key => $data)
    <tr class="delete_title_{{ $data->Intid }}">    
      <td><?php echo $count;?></td>
       <td>{{$data->vchTitle}}</td> 
	    <td>{{$data->parenttitle}}</td> 
		@if(strstr($access, "1"))
	   	<td data-placement="top" data-toggle="tooltip"  class="otherRemove" ><button type="button" id="{{ $data->Intid }}" class="btn btn-danger delete" data-title="Delete" title="Remove this"  ><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button>
		<button type="button" title="{{$data->vchTitle}}" parentcatid="{{ $data->parentid }}" id="{{ $data->Intid }}" class="btn btn-danger edit" data-title="Delete" title="Remove this"><i class="fa fa-pencil" aria-hidden="true"></i></button>
		
		</td>
		@endif
		<!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> -->
    </tr>
    <?php $count++;?>
@endforeach
		</table>
		{{ $getvideosearch->links() }}
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
  
  <div class="modal" id="myModal" tabindex="-1" role="dialog">
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
	foreach($parentcategory as $categorytitle){ ?>
	<option value="<?php echo $categorytitle->IntId; ?>"><?php echo $categorytitle->VchTitle; ?></option>
	<?php } ?>
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
</div>

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
		$('.delete').click(function(){
			var r = confirm("Are you sure to delete ?");
			if (r == true){ 
			
			var id= $(this).attr('id');
			var token= $('meta[name="csrf_token"]').attr('content');
			$.ajax({
				url:'{{ URL::to("/admin/deleteTagtype") }}',
				type:'POST',
				data:{'id':id,'_token':token},
				success:function(ress){
					$('.delete_title_'+id).remove();

				}
			});
			}
		});
	});
</script>