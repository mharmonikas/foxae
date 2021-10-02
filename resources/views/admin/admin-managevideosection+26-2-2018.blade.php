@include('admin/admin-header')
<style>
.recentlyuploaded{
	width:100%
	display:block;
}
.recentlyuploaded img {
	width: 100%;
}
.recentlyuploaded li{
	float: left;
	width: 23%;
	margin-right: 10px;
	margin-top: 10px;
	list-style: none;
}
.iconsdsf ul {
	display: inline-flex;
	list-style: none;
}
.videoimages{
	
	width: 300px;
height: 100%;
}
.space100 {
	height: 100px;
}
.selectedli.active{
	border: solid 4px #e46b3c;
 }
 .ful-top.gap-sextion {
  padding: 50px;
}
.gap-sextion table td {
  border: 1px solid #222;
  padding: 12px;
}
.gap-sextion table {
  width: 100%;
}
.gap-sextion .videoimages {
  height: auto;
  width: 180px;
}
.pagination {
  margin-top: 20px;
  text-align: center;
}
h3 {
    padding: 12px 0;
}
.bg_parts {
  background: #3c8dbc none repeat scroll 0 0;
  color: #fff;
  font-size: 15px;
  font-weight: 600;
}
table p {
  overflow-wrap: break-word;
  width: 63%;
}
.ndfHFb-c4YZDc-i5oIFb.ndfHFb-c4YZDc-e1YmVc .ndfHFb-c4YZDc-Wrql6b {
	background: rgba(0,0,0,0.75);
	height: 40px;
	top: 12px;
	left: auto;
	padding: 0;
	display: none !important;
}
.mangvid {
    border: 1px solid #ddd;
    margin: 0 0 30px;
}
.mangvid img {max-width: 100%;}
.mangvid h3 {
    display: block;
    text-align: center;
    font-size: 16px;
    margin: 0;
    font-weight: 600;
    padding: 6px 0;
    background: #000;
    color: #fff;
}
.btn_div {
    display: block;
    text-align: center;
    padding: 20px 0 10px;
}
.btn_div a {
    display: inline-block;
    background: #d01616;
    color: #fff;
    padding: 4px 9px;
    font-size: 12px;
    border-radius: 4px;
    font-weight: 500;
}
.btn_div a:first-child {
    background: #087921;
}
.tags {
    display: block;
    padding: 10px 10px 5px;
}
.searchtags {
    display: block;
    padding: 0 10px;
}
.tags span, .searchtags span {
    display: inline-block;
    margin: 0 3px 0 0;
    background: #e66b3e;
    padding: 3px 6px;
    color: #fff;
	    margin-top: 2px;
}
</style>

<div class="admin-page-area">
<!-- top navigation -->
         @include('admin/admin-logout')
		<!-- /top navigation -->
        <!-- /top navigation -->
   <div class="">
		<div class="col-md-12 mar-auto">
		<div class="back-strip top-side srch-byr">
					<div class="inner-top">
						Manage Content
					</div>
				</div>
			<div class="ful-top gap-sextion"  id="product_container">
			
 @include('admin.admin-managevideo')
			
			</div>
		</div>
	</div>  
</div>
<div class="space100"></div>
<script>
/*  $(document).on('click', '.pagination a',function(event)
    {
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
		var href = $(this).attr("href");
		
        event.preventDefault();
        var myurl = $(this).attr('href');
       var page=$(this).attr('href').split('page=')[1];
       getData(href);
    });

function getData(page){
        $.ajax(
        {
            url: page,
            type: "get",
            datatype: "html",
            // beforeSend: function()
            // {
            //     you can show your loader 
            // }
        })
        .done(function(data)
        {
            console.log(data);
            
            $("#product_container").empty().html(data);
           $('html, body').animate({
        scrollTop: $("#product_container").offset().top
    }, 2000);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError)
        {
              //alert('No response from server');
        });
} */












function deletevideo(deleteid){
	var result = confirm("Want to delete?");
	if (result) {
		$("#msg").fadeIn();
					$('#remove'+deleteid).remove();
                  $("#msg").html("Video successfully deleted");
     $.ajax({
               type:'Get',
               url:'/admin/managevideosection',
               data:{deletevideoid:deleteid},
               success:function(data) {
				    $("#msg").fadeIn();
					$('#remove'+deleteid).remove();
                  $("#msg").html("Video successfully deleted");
				 // window.location.href="";
				  setTimeout(function(){
				  
				   $("#msg").fadeOut();
				   }, 3000);
               }
            });
}
	
}
</script>		
@include('admin/admin-footer')