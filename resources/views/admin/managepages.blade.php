@include('admin/admin-header')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"/>
<div class="admin-page-area">
@include('admin/admin-logout')
<div class="">
<style>
.uploadedimage {
    width: 250px;
    margin-bottom: 40px;
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
	width: 94px;
}
.addnew {
	float: right;
}
.tabwatermarkss {
	background: #3c8dbc none repeat scroll 0 0;
	border-radius: 3px;
	color: #fff;
	font-size: 15px;
	height: 40px;
	line-height: 40px;
	margin-bottom: 11px;
	text-align: center;
	width: 94px;
	padding: 10px;
	border-radius: 8px;
	    margin-right: 2px;
    border-radius: 0 !important;
}

.watemarklogos {
	display: inline-flex;
	margin-left: 2px;
}

.watemarklogos {
	display: inline-flex;
	margin-left: 2px;
	margin-bottom: 20px;
}
a.tabwatermarkss.active {
    background: #000 !important;
    color: #fff !important;
}

.theme_opt_out form {
   width: 100%; 
 
}
.remove-button {
    margin-top: 11px;
    margin-bottom: 13px;
}
.btns {
    margin-top: 55px;
}
.row.detail-sec {
    background-color: #ecf0f5;

}
.theme_opt_out form {
    background: #ecf0f5;
    border: 1px solid #ecf0f5;
  
}
hr {
    margin-top: 0rem;
    margin-bottom: 0rem;
    border: 0;
    border-top: 2px solid;
    width: 1334px;
    margin-left: -9px;
}
</style>
<div class="col-md-12 mar-auto">
<div class="back-strip top-side srch-byr">
<div class="inner-top">
Legal and Support
</div>
</div>
 


<div class="searchtags theme_opt_out">
<div style="padding:30px 50px 0">
<div class="col-md-12">
<div class="col-md-9" >
<div class="watemarklogos" style="width:100%">
<div class="tabwatermarks">
<a href="javascript:void(0);" class="tabwatermarkss active" id="faq" logotype="faq">Q&A</a>
</div>
<div class="tabwatermarks">
<a href="javascript:void(0);" class="tabwatermarkss" id="pendingquestion" logotype="pendingquestion">Pending Question</a>
</div>
<div class="tabwatermarks">
<a href="javascript:void(0);" class="tabwatermarkss" id="documents" logotype="documents">Documents</a>
</div>
<div class="tabwatermarks">
<a href="javascript:void(0);" class="tabwatermarkss" id="archivedquestion" logotype="archivedquestion">Archive</a>
</div>

</div></div>

</div>
</div>
 <div class="ful-top gap-sextion pendingquestion" style="display:none">
 @foreach($contactresponse as $cres)
 <div class="row detail-sec">
 <div class="col-md-5">
 <div class="form-group">
 <label for=""><strong>User Name:</strong></label> {{$cres->vchfirst_name}}
 </div>
 <div class="form-group">
 <label for=""><strong>User Email:</strong></label> {{$cres->vchemail}}
 </div>
 <div class="form-group">
 <label for=""><strong>Date Submited:</strong></label> {{ date('m.d.Y', strtotime($cres->create_date)) }}
 </div>
 <div class="form-group">
 <label for=""><strong>Ticket Number:</strong></label> {{ $cres->ticket_number }}
 </div>
 </div>

<div class="col-md-7">
<form  method="POST" action="/admin/contactrespond_email" >
{!! csrf_field() !!}
<input type="hidden" name="issueid" value="{{$cres->id}}">
<input type="hidden" name="useremail" value="{{$cres->vchemail}}">
<input type="hidden" name="username" value="{{$cres->vchfirst_name}}">
<input type="hidden" name="siteid" value="{{$siteid}}">
  <div class="form-group row">
    <label for="staticEmail" class="col-sm-3 col-form-label"><strong>Date Responded: </strong></label>
    <div class="col-sm-4">
      <input type="text" class="form-control datetimepicker2" name="respond_date" id="" value="{{$cres->respond_date}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword" class="col-sm-3 col-form-label"><strong>Status: </strong></label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="inputPassword" name="respond_status" placeholder="Status" value="{{$cres->respond_status}}">
    </div>
  </div>  
  <div class="form-group row">
    <label for="inputPassword" class="col-sm-3 col-form-label"><strong>Who: </strong></label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="" placeholder="Who" name="respond_person" value="{{$cres->respond_person}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword" class="col-sm-3 col-form-label"><strong> </strong></label>
    <div class="col-sm-4">
    <button  type="submit" class="btn btn-dafualt">Submit</button>
    </div>
  </div>
</form>

</div>
<div class="col-md-12">
<label for=""><strong>User Submited issue: </strong><p>{{$cres->query}}</p></label>
</div>
 <div class="input-group-append pull-right remove-button">
 <form  method="POST" action="/admin/issue_archived" >
	{!! csrf_field() !!}
	<input type="hidden" name="issueid" value="{{$cres->id}}">
	<input type="hidden" name="issue_archived" value="Y">
	<input type="hidden" name="siteid" value="{{$siteid}}">
	<button  type="submit" class="btn btn-dafualt">Archived</button>
</form>
</div>
</div>
<hr>
@endforeach
</div>
 
 <div class="ful-top gap-sextion archivedquestion" style="display:none">
 @foreach($archivedresponse as $cres)
 <div class="row detail-sec">
 <div class="col-md-5">
 <div class="form-group">
 <label for=""><strong>User Name:</strong></label> {{$cres->vchfirst_name}}
 </div>
 <div class="form-group">
 <label for=""><strong>User Email:</strong></label> {{$cres->vchemail}}
 </div>
 <div class="form-group">
 <label for=""><strong>Date Submited:</strong></label>{{ date('m.d.Y', strtotime($cres->create_date)) }}
 </div>
 </div>

<div class="col-md-7">
<form  method="POST" >
{!! csrf_field() !!}
<input type="hidden" name="issueid" value="{{$cres->id}}">
<input type="hidden" name="useremail" value="{{$cres->vchemail}}">
  <div class="form-group row">
    <label for="staticEmail" class="col-sm-3 col-form-label"><strong>Date Responded: </strong></label>
    <div class="col-sm-4">
      <input type="text" class="form-control datetimepicker2" id="" value="{{$cres->respond_date}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword" class="col-sm-3 col-form-label"><strong>Status: </strong></label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="inputPassword" placeholder="Status" value="{{$cres->respond_status}}">
    </div>
  </div>  
  <div class="form-group row">
    <label for="inputPassword" class="col-sm-3 col-form-label"><strong>Who: </strong></label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="" placeholder="Who" value="{{$cres->respond_person}}">
    </div>
  </div>

</form>

</div>
<div class="col-md-12">
<label for=""><strong>User Submited issue: </strong><p>{{$cres->query}}</p></label>
</div>

</div>
<hr>
@endforeach
</div> 
 
 
<div class="ful-top gap-sextion faq"  id="product_container" style="display:block">
<div class="col-md-12">
<form  method="POST" enctype="multipart/form-data" action="/admin/managefaq">
{!! csrf_field() !!}
<input type="hidden" name="siteid" value="{{$siteid}}">
<input type="hidden" id="rowno"  value="{{count($response)}}">
<div class="">
@php
$i = 1;
@endphp
 @foreach($response as $res)
 <div class="" id="inputFormRow">
 <input type="hidden" name="faqid[]"  value="{{$res->id}}">
<div class="form-group">
<label for="question{{$i}}">Question {{$i}}:</label>
    <input type="text" name="question[]" class="form-control" id="question{{$i}}" value="{{$res->question}}">
</div>
<div class="form-group">
<label for="answer{{$i}}">Answer {{$i}}:</label>
<textarea name="answer[]" class="form-control" id="answer{{$i}}" rows="4" >{{$res->answer}}</textarea>
    <div class="input-group-append pull-right remove-button">
          <button id="removeRow" type="button" class="btn btn-danger removeRow" data-id="{{$res->id}}">Remove</button>
        </div>
</div>
</div>
@php
$i++;
@endphp
@endforeach

<div id="newRow"></div>
<div class="form-group btns">
 <button id="addRow" type="button" class="btn btn-dafualt">+ Add More</button>
 <button  type="submit" class="btn btn-dafualt pull-right">Submit</button>
</div>
</div>
</form>	
</div>
</div>

<div class="ful-top gap-sextion documents" style="display:none">
<div class="col-md-12">
<form  method="POST" enctype="multipart/form-data" action="/admin/managedocuments">
{!! csrf_field() !!}
<input type="hidden" name="id" value="@if(!empty($legaldocsresponse)){{$legaldocsresponse->id}}@endif">
<input type="hidden" name="siteid" value="{{$siteid}}">
<div class="">
<div class="form-group">
<label for="terms&condition">Terms & Conditions:</label>
    <input type="text" name="termscondition" class="form-control" id="terms&condition" value="@if(!empty($legaldocsresponse)){{$legaldocsresponse->termscondition}}@endif">
</div>

<div class="form-group">
<label for="privacypolicy">Privacy Policy:</label>
    <input type="text" name="privacypolicy" class="form-control" id="privacypolicy" value="@if(!empty($legaldocsresponse)){{$legaldocsresponse->privacypolicy}}@endif">
</div>
<div class="form-group">
<label for="userlicence">User licence:</label>
    <input type="text" name="userlicence" class="form-control" id="userlicence" value="@if(!empty($legaldocsresponse)){{$legaldocsresponse->userlicence}}@endif">
</div>
<div class="form-group">
<label for="about">About:</label>
    <input type="text" name="about" class="form-control" id="about" value="@if(!empty($legaldocsresponse)){{$legaldocsresponse->about}}@endif">
</div>

<div class="form-group">

     <button  type="submit" class="btn btn-dafualt pull-right">Submit</button>
</div>
</div>
</form>	
</div>
</div>


</div>

</div>  
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">
        // add row
        $("#addRow").click(function () {
			var rowno=$("#rowno").val();
			var row=parseInt(rowno) + parseInt(1);
            var html = '';
            html += '<div id="inputFormRow">';
            html += '<div class="form-group">';
            html += '<label for="question'+row+'">Question '+row+':</label>';
            html += ' <input type="text" name="question[]"  class="form-control" id="question'+row+'" value="">';
            html += '</div>';
			html += '<div class="form-group">';
            html += '<label for="answer'+row+'">Answer '+row+':</label>';
            html += ' <textarea name="answer[]" class="form-control" id="answer'+row+'" rows="4" ></textarea>';
            html += '<div class="input-group-append pull-right remove-button">';
            html += '<button id="removeRow" type="button" class="btn btn-danger">Remove</button>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            $('#newRow').append(html);
			$("#rowno").val(row);
        });

        // remove row
        $(document).on('click', '#removeRow', function () {
			var rowno=$("#rowno").val();
			var row=parseInt(rowno) - parseInt(1);
            $(this).closest('#inputFormRow').remove();
			$("#rowno").val(row);
        });
		
	$('.datetimepicker2').datetimepicker({
	
	lang:'ch',
	timepicker:false,
	format:'Y/m/d',
	formatDate:'Y/m/d',
	minDate:'-1970/01/02', // yesterday is minimum date
	maxDate:'+1970/01/02' // and tommorow is maximum date calendar
});
    </script>
<script>
 function readURL(input) {

  if (input.files && input.files[0]) { 
    var reader = new FileReader();

    reader.onload = function(e) {
		$('.uploadedimage').css('display','block');
      $('#blah').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}




$('.tabwatermarkss').click(function(){
var logotype = $(this).attr('logotype');	
	$('.gap-sextion').css('display','none');
	$('.'+logotype).fadeIn();
	
});

$("#faq").click(function(){
	 $('#pendingquestion').removeClass('active');
	 $('#documents').removeClass('active');
		$("archivedquestion").removeClass("active");
      $(this).removeClass('active').addClass('active');
	  });
	  $("#pendingquestion").click(function(){
	 $('#faq').removeClass('active');
	 $('#documents').removeClass('active');
	$("#archivedquestion").removeClass("active");
      $(this).removeClass('active').addClass('active');
	  }); 
	 $("#documents").click(function(){
			$('#faq').removeClass('active');
			$('#pendingquestion').removeClass('active');
			$("#archivedquestion").removeClass("active");
      $(this).removeClass('active').addClass('active');
	  });
	  $("#archivedquestion").click(function(){
			$('#faq').removeClass('active');
			$('#pendingquestion').removeClass('active');
			$("#documents").removeClass("active");
      $(this).removeClass('active').addClass('active');
	  });
</script>

<script type="text/javascript">
$(".removeRow").click(function (e) {
	   $(this).closest('#inputFormRow').remove();
	var id = $(this).data('id');
	var token=$('meta[name="csrf_token"]').attr('content');
	
    $.ajax({
        url: "/admin/Deleteqa",
       headers: {
			'X-CSRF-TOKEN':token
					}, 
        type: "Post",
        dataType: "Json",
		data:'id='+id+'&_token='+token,
        success: function(result) {
            
        },
        
    });
});
</script>
@include('admin/admin-footer')