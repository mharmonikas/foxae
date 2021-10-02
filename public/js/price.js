$('.open-form').on('click', function() { 
			var uniqueid=$("#uniqueid").val();
			var subs_plan_id=$("#plan-info").val();
			
			if(subs_plan_id==undefined){
				subs_plan_id='';
			}
			var output = $('input[name=packageid]:checked','#price-plan-from').val(); 
          	var type = $('input[name=packageid]:checked','#price-plan-from').attr('data-val'); 
			
			//alert(type);
				if(output == undefined){
					$("#errorMessage").html('<div class=""><strong>Please select one option.</strong> </div>');
						myFunction();		
					return false
				}
			if(uniqueid == ""){
				$("#exampleModal").modal("show");
				$("#login-flow").val("pricing");
				$("#package_id").val(output);
				$("#package_type").val(type);
				
				  return false
			  }
			$(".custom-discount").css("display","none");	  
			var type = $('input[name=packageid]:checked','#price-plan-from').attr('data-val'); 
				var old_pack_id=$("#plan-info").val();
				var old_pack_type=$("#plan-info").attr('data-id');
			 
				var token=$('meta[name="csrf-token"]').attr('content');
				$.ajax({     
					url: '/buynow',
					type:"POST", 
					async: true,
					dataType: 'json',		
					headers: {
						'X-CSRF-TOKEN':token
					},        
					data:'id='+output+'&_token='+token+'&type='+type+'&old_pack_id='+old_pack_id+'&old_pack_type='+old_pack_type,
					success:function(data){
						 console.log(data);
						 var option = [];
						 option.push('<option value="">Select</option>');
						 $.each(data.country, function (i) {
							$.each(data.country[i], function (key, val) {
								var selected="";
								if(data.billing_address!=null){
									if(data.billing_address.billing_country==val){
										selected="Selected";
								
										}
								}else{
									selected='';
									
								}
							option.push('<option value="'+ val +'"'+selected+'>'+ val +'</option>');
							});
						});
						$('#mySelect').html(option.join(''));
						if(data.billing_address!=null){
						$('#address_line1').val(data.billing_address.billing_address_line1);
						$('#address_line2').val(data.billing_address.billing_address_line2);
						$('#city').val(data.billing_address.billing_city);
						$('#state').val(data.billing_address.billing_state);
						$('#zip').val(data.billing_address.billing_zipcode);
						}else{
						$('#address_line1').val('');
						$('#address_line2').val('');
						$('#city').val('');
						$('#state').val('');
						$('#zip').val('');	
							
						}
						if(data.card_details!=null){
						$('#cardname').val(data.card_details.holder_name);
						$('#cardnumber').val(data.card_details.c_number);
						$('#expirationdate').val(data.card_details.exp_month);
						$('#expirationYeardate').val(data.card_details.exp_year);
						
						}else{
						$('#cardname').val('');
						$('#cardnumber').val('');
						$('#expirationdate').val('');
						$('#expirationYeardate').val('');
						
							
						}
						
						if(type=="annual"){
							var price_type='Annual Payment';
							 if (window.matchMedia("(max-width: 767px)").matches){
								 var price_prefix=' Annually';
            
							} else {
            
								var price_prefix=' /Annually';
							}
							
							$("#plan-price").html('<strong>$'+data.annually_price+price_prefix+'</strong>');
							$("#plan-name").text(data.getplan.plan_title+' - '+price_type);
							
						}else if(type=="monthly"){
							var price_type='Monthly Payment';
							 if (window.matchMedia("(max-width: 767px)").matches){
								 var price_prefix='<br>Per Month';
            
							} else {
            
								var price_prefix=' /Month';
							}
							
							$("#plan-price").html('<strong>$'+data.monthly_price+price_prefix+'</strong>');
							$("#plan-name").text(data.getplan.plan_title+' - '+price_type);
							
						}else{
							$("#plan-price").html('<strong>$'+data.onetime_price+'</strong>');
							$("#plan-name").text(data.getplan.plan_name+' - '+'One Time Purchase');
							
						}
							$('#old_packageid').val(old_pack_id);
							$('#old_packagetype').val(old_pack_type);
							
						$(".custom-discount").removeAttr("style");	
						var content = '';
						if(data.discount != ''){
							var icon = '';
							if(data.discount.discount_type == 'P'){
								icon = '%';
							}else if(data.discount.discount_type == 'A'){
								icon = '$';
							}
							
							 content = '<p class="origin_price">Original Price $'+data.discount.original_price+'</p> <p class="discount_apply"> Discount '+data.discount.discount_amount+icon+'</p>';
							 $(".custom-discount").html(content);
						}
						$("#checkoutModal").modal("show");
					}
				})
			  
				
				//$("#price-plan-from").submit();
        }); 
		

	
	
	  $('.custom-control-input').on('change', function() {
		    $('.custom-control-input').not(this).prop('checked', false);  
		});
		

$(".custom-control-input").click(function() {
var check_count=$(".custom-control-input:checked");
var plan_id=$(this).val();
var plan_type=$(this).attr('data-val');
//alert(plan_type);
if(check_count.length>0){
	$('.pricing_main').removeClass('onetime-div');
	 $('.bg_section').removeClass('after_click');
	$('.trans_'+plan_id).addClass('after_click');
	document.getElementById('btn-show').style.display = "block";
	if(plan_type=='Onetime'){
		$('.pricing_main').removeClass('onetime-div');
		$('#onetime_'+plan_id).addClass('onetime-div');
	}else{
		$('#onetime_'+plan_id).removeClass('onetime-div');	
	}
	}else{
		$('.bg_section').removeClass('after_click');
		$('#onetime_'+plan_id).removeClass('onetime-div');
	document.getElementById('btn-show').style.display = "none";
	}
});
	



$( "#collapse_one" ).click(function() {
	if($('#collapseExample').css('display') == 'none')
{
	$("#collapse_one").text("Collapse");
}else{
	
	$("#collapse_one").text("Expand");
	
}

  $( "#collapseExample" ).slideToggle( "slow" );
});

$( "#collapse_two" ).click(function() {
	if($('#collapseExample2').css('display') == 'none')
{
	$("#collapse_two").text("Collapse");
}else{
	
	$("#collapse_two").text("Expand");
	
}

  $( "#collapseExample2" ).slideToggle( "slow" );
});



function trans(plan_id){
	var check_count=$(".custom-control-input:checked");
	if(check_count.length>0){
	 $('.bg_section').removeClass('after_click');
	$('.trans_'+plan_id).addClass('after_click');
	document.getElementById('btn-show').style.display = "block";
	}else{
		$('.bg_section').removeClass('after_click');
	document.getElementById('btn-show').style.display = "none";
	}
	
}

function openCity(evt, cityName) {
	document.getElementById('btn-show').style.display = "none";
	$('.bg_section').removeClass('after_click');
	$('.price-checkbox input').prop('checked', false);
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
	  tablinks[i].className = tablinks[i].className.replace(" inactive", "");
    tablinks[i].className = tablinks[i].className.replace(" clicked", " inactive");
    
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " clicked";
}
