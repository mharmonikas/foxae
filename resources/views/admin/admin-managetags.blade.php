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
   
	<div class="col-md-12 mar-auto">
	<div class="back-strip top-side srch-byr">
	<div class="inner-top">
				Manage Tags
			
	</div>
 
	</div>
	<div class="buyer-manage">
	<div class="ful-top">
	
	<div class="col-md-12">
			<a href="javascript:void(0);" class="addnew"><i class="fa fa-plus-square"></i> Add New</a>
			
	</div>
	<div class="view_data">
		<table class="table-bordered" width="100%">
			<thead>
				<tr>
					
						<th>Sr.No</th>
						<th>Tag_Title</th>
						<th>Action</th>
						<th>Edit</th>
						<th>Delete</th>


					
				</tr>
			</thead>
		
	
	<?php $count=1;?>
	
	@foreach($allmastertags as $key => $data)
    <tr class="delete_title_{{ $data->IntId }}">    
      <td><?php echo $count;?></td>
       <td>{{$data->VchTitle}}</td> 
       <td data-placement="top" data-toggle="tooltip"  id="title__{{ $data->IntId }}"><button title="Deactive" class="btn deactive"  data-title="Edit" data-toggle="modal"><span><i class="fa fa-eye-slash" aria-hidden="true"></i></span></button></td>

<td data-placement="top" data-toggle="tooltip" title="" data-original-title="Edit"><button data-toggle="modal" title="{{$data->VchTitle}}" id="{{ $data->IntId }}" data-target="#myModal" class="btn edit" data-title="Edit"><span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span></button></td>
											
		<td data-placement="top" data-toggle="tooltip"  class="otherRemove" ><button type="button" id="{{ $data->IntId }}" class="btn btn-danger delete" data-title="Delete" title="Remove this"><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button></td>
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
	 <input class="form-control" type="text" name="categorytitle" id="categorytitle1">
	 <input type="hidden" name="category" id="category" value="">
	 <div class="form-group">
    
    </div>	
	<input class="btn btn-primarry" type="button" name="submit" id="editchild" value="Save">
	</form>
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
			$('#categorytitle1').val(title);
			$('#category').val(id);
			/* var id= $(this).attr('id');
			 
			var token= $('meta[name="csrf_token"]').attr('content');
			  
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
		var category = $('#category').val();
      		
		 var token= $('meta[name="csrf_token"]').attr('content');
			  
			$.ajax({
				url:'{{ URL::to("/admin/addeditmastertags") }}',
				type:'POST',
				data:{'categorytitle':categorytitle,'category':category,'_token':token},
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
				url:'{{ URL::to("/admin/deletemastertag") }}',
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