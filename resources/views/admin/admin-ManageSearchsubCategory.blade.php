@include('admin/admin-header')
<link rel="stylesheet" href="/css/bootstrap-tagsinput.css">
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
.col-md-12.mar-auto {
    padding: 8px 25px;
}
.admin-page-area{
	padding-bottom:70px;
}
.inner-top {
    text-align: center;
}
form.form-inline.from-search {
    width: 50%;
    margin: 0 auto;
    padding: 0;
}
form.form-inline.from-search button.btn.btn-primary.btn-header-button {
    margin: 0 10px;
}
form.form-inline.from-search .form-control {
    border-radius: 0;
    height: 35px;
}
.btn.btn-primary.btn-header-button {
    position: unset !important;
	background: #3c8dbc none repeat scroll 0 0;
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
	<div class="ful-top">
	
	<div class="inner-top">
				Manage Sub Tags
			
	</div>
	
	<form class="form-inline from-search" action="">
	  <div class="form-group">
		<input type="text" class="form-control" name="search" Placeholder="Search" value="{{ $search }}">
	  </div>
	  <div class="form-group">
		<select class="form-control" name="searchcategory">
			<option value="">Select Parent category</option>
			 @foreach($parentcategory as $childcate)
				<option value="{{ $childcate->IntId }}" @if($searchcategory ==  $childcate->IntId) Selected @endif >{{ $childcate->VchCategoryTitle }}</option>	
			@endforeach
		</select>
		
	  </div>
	  <button type="submit" class="btn btn-primary btn-header-button">Search</button>
	</form>	
		
	<button  onclick="window.history.go(-1); return false;" type="button" class="btn btn-primary pull-right btn-header-button">  Back <i class="fa fa-arrow-right" aria-hidden="true"></i></button>	
	<a href="/admin/ManageSearchCategory" class="btn btn-primary pull-right btn-header-button">  Manage Tag</a>	
	<a href="javascript:void(0);" class="btn btn-primary pull-right btn-header-button btn-add-new"><i class="fa fa-plus-square"></i>  Add New</a>

	
	

	<div class="table-responsive">
		<table class="table-bordered" width="100%">
			<thead>
				<tr>
					<th>Sr.No</th>
					<th>Tags</th>
					<th>Parent Tag</th>
					<th>Action</th>
				</tr>
			</thead>
	<?php 
	if(isset($_GET['page'])){
		$pages= $_GET['page']-1;$count = $pages*15;	$count = $count+1;	}else { $count=1;}
	?>
	@foreach($getvideosearch as $key => $data)
    <tr class="delete_title_{{ $data->IntId }}">    
      <td><?php echo $count;?></td> 
	  
        <td id="category{{ $data->IntParent}}">{{$data->CategoryTitle}}</td> 
	    <td>{{$data->VchCategoryTitle}}</td> 
	   	<td data-placement="top" data-toggle="tooltip"  class="otherRemove" >
		
		<!-- <button type="button" id="{{ $data->IntId }}" class="btn btn-danger delete" data-title="Delete" title="Remove this"><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button> -->
		<button type="button"  parentcatid="{{ $data->IntParent}}" id="{{ $data->IntId }}" class="btn btn-Sucsess edit" data-title="Delete" title="Remove this"  ><i class="fa fa-pencil" aria-hidden="true"></i></button>
		</td>
		
    </tr>
    <?php $count++;?>
	@endforeach
		</table>
		{{ $getvideosearch->links() }}
	</div>
	
    </div>
    </div>
    </div>
 

  
  <div class="modal" id="myModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	<form action="" name="form1" style="padding: 0;">
      <div class="modal-header">
        <h5 class="modal-title">Sub Tag</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
	
	
	
	<!--data-role="tagsinput"
	 <input class="form-control" name="categorytitle" id="categorytitle1" Placeholder="Enter Sub-Tag Name" value=""  required>-->
	 <textarea name="categorytitle" id="categorytitle1" class="form-control"  placeholder="Enter Sub-Tag Name"></textarea>
	 <br>
	 <input type="hidden" name="category" id="category" value="">
	 <div class="form-group">
    <select name="parentcat" class="form-control" id="parentcat" required >
	 <option value="">Select Parent category</option>
	<?php foreach($parentcategory as $childcate){ ?>
    <option value="<?php echo $childcate->IntId; ?>"><?php echo $childcate->VchCategoryTitle; ?></option>	
	<?php } ?>
	</select>
    </div>	
	
	
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<input class="btn btn-primarry" type="button" name="submit" id="editchild" value="Save">
      </div>
	  </form>
    </div>
  </div>
</div>
  
  
  
  
  
  
  
  
  
  
</div>
		
@include('admin/admin-footer')
<script src="/js/bootstrap-tagsinput.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){

	 $('.btn-add-new').click(function(){
		 
	$('#myModal').modal('show'); 	 
	$('#category').val(''); 	 
		 
	 });
	
		
			
			$(document).on('click', '.edit', function() { 
			
			
			 var id= $(this).attr('id');
			 var title= $("#category"+id).html();
			 //alert(title);
			 var parentcatid= $(this).attr('parentcatid');
			 $('#category').val(id);
			 $('#parentcat').val(parentcatid);
			 $('#categorytitle1').val(title);
			
//$('.bootstrap-tagsinput input').remove();
			//$('.bootstrap-tagsinput input').val(title);
			//$('.bootstrap-tagsinput input').tagsinput('destroy');
			//$('.bootstrap-tagsinput input').tagsinput('add', 'title');
			//$('.bootstrap-tagsinput input').tagsinput('refresh');
			
			 
			  $('#myModal').modal('show'); 
		});
        $('#editchild').click(function(){
		var categorytitle = $('#categorytitle1').val();	
		var parentcat = $('#parentcat').val();
		if(categorytitle==''){
			
			alert("Please enter Tags");
			return false;
		}
		if(parentcat==''){
			
			alert("Please select sub tag");
			return false;
		}
		var category = $('#category').val();
       		
		 var token= $('meta[name="csrf_token"]').attr('content');
			$.ajax({
				url:'{{ URL::to("/admin/addeditsearchsubcategory") }}',
				type:'POST',
				data:{'categorytitle':categorytitle,'category':category,'parentcat':parentcat,'_token':token},
				success:function(ress){
					window.location.href="";
				}
			});	
			
		});
		//delete data
		$('.delete').click(function(){
			var id= $(this).attr('id');
			 var token= $('meta[name="csrf_token"]').attr('content');
			 var r = confirm("Are you sure to delete ?");
			if (r == true){  
			$.ajax({
				url:'{{ URL::to("/admin/deletesearchcategory") }}',
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