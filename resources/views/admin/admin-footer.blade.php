   
    <div class=" container-fluid copyright_bg">
    	<p>Copyright <?php echo date('Y');?>  | All Rights Reserved | Powered By </p>
    </div>
     </div>
<!-- Datepicker -->
		


	


    <!-- FastClick -->
   
	
	<script>
	setInterval(function(){
$('.alert').hide();
 }, 3000);
  $("#myUL li a").click(function() {
      $("#myUL li a").removeClass("md-active");
      $(this).addClass("md-active");
    });
	function goBack() {
    window.history.back();
}

var selector = '.side-menu li';
    var url = window.location.href;
    var target = url.split('/');
     $(selector).each(function(){
        if($(this).find('a').attr('href')===url){
          $(selector).removeClass('active_menu');
          $(this).removeClass('active_menu').addClass('active_menu');
        }
     });
 </script>


  </body>
</html>