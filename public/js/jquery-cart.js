$(document).on("click",".delete",function(){
	 // if (confirm('Are you sure you want to remove this?')) {
		var id = $(this).attr('id');
		var videoid = $(this).attr('data-value');
		var token=$('meta[name="csrf-token"]').attr('content');
		  localStorage.setItem("id", videoid);
		$.ajax({
			url: '/wishlist-delete',
			type:"POST",
			async: true,
			dataType: 'json',
			headers: {
				'X-CSRF-TOKEN':token
			},
			data:'id='+id+'&_token='+token,
			success:function(data){
			$(".remove_cart_"+id).remove();
			if(data.value2.totalitems <=0){
				 $(".crat-popup").addClass("hide");
				$(".price-row").css("display", "none");
				$("#alert").addClass("alert-section");

			}else{
				 $(".crat-popup").removeClass("hide");
				 $(".price-row").removeAttr('style');
			}
				$( "#alert" ).html("<tr><th class='msg-remove' colspan='3'><strong>"+data.message+"</strong></th></tr>");
				//$( "#desk-alert" ).html("<tr><th colspan='3'><strong>"+data.message+"</strong></th></tr>");
				//$('#alert').addClass('show').removeClass('hide');

				$( ".crat-price" ).html("<span>In Cart&nbsp;("+data.value2.totalitems+" items): </span><span><b>"+data.value2.cartvalue+"</b></span>");
				$( "#cart-text" ).html("<span>Subtotal&nbsp;("+data.value2.totalitems+" items): </span><span><b>"+data.value2.cartvalue+"</b></span>");
				$('#cartval').val(data.value2.cartcreditvalue);

				$("#cartcount").html(data.value2.carticon+'<span>'+data.value2.totalitems+'</span>');
				 $("#errorMessage").html('<div class=""><strong>REMOVED FROM CART!</strong> </div>');
						myFunction();
				// setInterval(function(){
					// $('#alert').addClass('hide').removeClass('show');
				// }, 5000);
				//window.location = '/cart';
			}
		});
	//  }
});


$(document).on("change",".bg_selection",function(){
		var id = $(this).attr('id');

		var val = $('#'+id).val();
		 $('.apply_changes').attr('bg-id',val);
		//alert(val);
		change_background(val,id,'onchange');

});
$(document).on("click","#undo",function(){
	var token=$('meta[name="csrf-token"]').attr('content');
	var productnid = localStorage.getItem('id');
	var cartstatus = 'Add';
	$.ajax({
			url: '/wishlist',
			type:"POST",
			async: true,
			dataType: 'json',

			headers: {
				'X-CSRF-TOKEN':token
			},
			data:'videoid='+productnid+'&_token='+token+'&cartstatus='+cartstatus,
			success:function(data){

				if(data.response=='1'){
					window.location = '/cart';


				}


			}
		});

});

function allinputchecked(id,imgno){
		if($('#all_check_'+id).prop("checked") == true){
			$(".apply_changes").attr('data-value', 'all');
			$(".all-checkbox").addClass("checked-all");
	}
	if($('#all_check_'+id).prop("checked") == false){
		$(".apply_changes").attr('data-value', '');
		$(".all-checkbox").removeClass("checked-all");
	}


}
function oneinputchecked(id,imgno){
	if($('#one_check_'+id).prop("checked") == true){
			$(".apply_changes").attr('data-value', 'one');
			$(".all-checkbox").addClass("checked-one");
		}
	if($('#one_check_'+id).prop("checked") == false){
		$(".apply_changes").attr('data-value', '');
		$(".all-checkbox").removeClass("checked-one");
	}

}
function later_delete(id){

//	if (confirm('Are you sure you want to remove this?')) {
		///var id = $(this).attr('id');
		var token=$('meta[name="csrf-token"]').attr('content');
		$.ajax({
			url: '/wishlist-delete',
			type:"POST",
			async: true,
			dataType: 'json',
			headers: {
				'X-CSRF-TOKEN':token
			},
			data:'id='+id+'&_token='+token,
			success:function(data){
				window.location = '/cart';
			}
		});
	//  }


}
function changestatus(id,status){
	//  if (confirm('Are you sure you want to remove this?')) {
		//var id = $(this).attr('data-value');
		//alert(id);
		var token=$('meta[name="csrf-token"]').attr('content');
		$.ajax({
			url: '/savetolater',
			type:"POST",
			async: true,
			dataType: 'json',
			headers: {
				'X-CSRF-TOKEN':token
			},
			data:'id='+id+'&_token='+token+'&status='+status,
			success:function(data){
				if(status=='cart'){
						$("#errorMessage").html('<div class=""><strong>ADDED TO CART!</strong> </div>');
							myFunction();
				}else{
					 $("#errorMessage").html('<div class=""><strong>SAVED FOR LATER!</strong> </div>');
						myFunction();
				}
					setTimeout(function(){
					   location.reload();
					}, 500);
				//window.location = '/cart';
			}
		});
	  //}
}




var i = '';

/* function downloadcart(){
	  if (confirm('Are you sure you want to download  this list?')) {
		var id = $(this).attr('id');
		var token=$('meta[name="csrf-token"]').attr('content');

		$(".download-process").html('<i class="fa fa-refresh fa-spin"></i> Download');
		$.ajax({
			url: '/downloadcart',
			type:"POST",
			async: true,
			dataType: 'json',
			headers: {
				'X-CSRF-TOKEN':token
			},
			data:'_token='+token,
			success:function(data){
			var urls = [];
			var cartid = [];

			$(".availablecount").html(data.available);
			var response = data.response;
				$(".download-process").html('Download');
				for(i=0; i<response.length; i++){
					 cartid.push(response[i]['cartid']);
					// urls.push('/fileTodownload/'+response[i]['downloadid']);
				 }
				urls.push('/download-zip');
				setInterval(download, 2000, urls);
				var myVar =  setInterval(removetocart, 2200, cartid);
				i = 0;
				$("#cartcount").html('<img src="/public/images/'+data.carticon+'"><span>'+data.cartcount+'</span>');
				$("#availablecredit").html('<div class=""><strong>Credits :'+data.available+'</strong> </div>');
			 }
		});
	  }
} */
function downloadcart(){
	//  if (confirm('Are you sure you want to download  this list?')) {
		var id = $(this).attr('id');
		var cartvalue = parseInt($('#cartval').val());
		var availcreditvalue = parseInt($('#available-credit').val());
        if(availcreditvalue >= cartvalue){
            var token=$('meta[name="csrf-token"]').attr('content');
             var checked = [];
                            $.each($("input[name='checkbox_status']:checked"), function(){
                                checked.push($(this).val());
                                //checked = $(this).val();
                            });
            $(".download-process").html('<i class="fa fa-refresh fa-spin"></i> Download');
            $.ajax({
                url: '/downloadcart',
                type:"POST",
                async: true,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN':token
                },
                data:'check_id='+checked+'&_token='+token+'&cartvalue='+cartvalue,
                success:function(data){
                    if(data.response!=''){
                        if(data==1){
                             $("#errorMessage").html('<div class=""><strong>You don\'t have plan</strong> </div>');
                             myFunction();
                        } else {
                            var urls = [];
                            var cartid = [];
                            var downloadid = '';

                            $("#download_success").removeClass('hide');
                            $("#download_success").addClass('show');

                            setTimeout(function(){
                                $("#download_success").removeClass('show');
                                $("#download_success").addClass('hide');
                            }, 3000);

                            $(".availablecount").html(data.available);
                            let response = data.response;
                            //console.log(response);

                            $(".download-process").html('Download');
                            for(i=0; i<response.length; i++){
                                 cartid.push(response[i]['cartid']);
                                 downloadid += response[i]['downloadid'] + ',';
                            }

                            i = 0;
                            $("#cartcount").html(data.carticon+'<span>'+data.cartcount+'</span>');
                            $("#availablecredit").html('<div class=""><strong>Credits :'+data.available+'</strong> </div>');

                            var cartvalue = $('#cartval').val();
                            var token=$('meta[name="csrf-token"]').attr('content');
                            $.ajax({
                                url: '/fileTodownload1',
                                type: "GET",
                                async: true,
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN':token
                                },
                                // data:'downloadid='+downloadid+'&_token='+token+'&cartvalue='+cartvalue,
                                data: {downloadid: downloadid, _token: token, cartvalue: cartvalue},
                                success:function(data){
                                    if(data != '') {
                                        if(data == '1'){
                                         $("#errorMessage").html('<div class=""><strong>You don\'t have plan</strong> </div>');
                                            myFunction();
                                         } else if(data == '2') {
                                            $("#errorMessage").html('<div class=""><strong>No available credit left</strong> </div>');
                                                myFunction();
                                         } else {
                                            let a = document.createElement("a");
                                            a.setAttribute('href', '/download-zip?='+Math.random());
                                            a.click();

                                            setTimeout(function(){
                                                location.reload();
                                            }, 6000);
                                         }
                                    } else {
                                        $("#less_credit").removeClass('hide');
                                        $("#less_credit").addClass('show');
                                        setTimeout(function(){
                                            $("#less_credit").removeClass('show');
                                            $("#less_credit").addClass('hide');
                                        }, 3000);
                                    }
                                }
                            });
                        }
                    } else {
                        $("#less_credit").removeClass('hide');
                        $("#less_credit").addClass('show');
                        setTimeout(function(){
                            $("#less_credit").removeClass('show');
                            $("#less_credit").addClass('hide');
                        }, 3000);
                    }
                }
            });
		}
        else {
			$("#less_credit").removeClass('hide');
			$("#less_credit").addClass('show');

            setTimeout(function(){
				$("#less_credit").removeClass('show');
				$("#less_credit").addClass('hide');
            }, 3000);
		}

}

function download(urls) {
  var url = urls.pop();

	if(url != undefined){
	  var a = document.createElement("a");
	  a.setAttribute('href', url);
	//  a.setAttribute('download', '');
	 // a.setAttribute('target', '_blank');
	  a.click();
	}

}

function removetocart(cartid){
	var cartid = cartid.pop();

	if(cartid != undefined){
		$(".remove_cart_"+cartid).remove();
	}

	else if(cartid == undefined){
		if(i == 0){

		}
		// clearInterval(myVar);
		i++;
	}
}


function chck_uncheckcart(cartid){
	if($('#checkbox_'+cartid).prop("checked") == true){
                var token=$('meta[name="csrf-token"]').attr('content');
				 var checked = [];
						$.each($("input[name='checkbox_status']:checked"), function(){
							checked.push($(this).val());
							//checked = $(this).val();
						});
					$.ajax({
						url: '/chck_uncheckcart',
						type:"POST",
						async: true,
						dataType: 'json',
						headers: {
							'X-CSRF-TOKEN':token
						},
						data:'id='+checked+'&_token='+token,
						success:function(data){
							$( ".crat-price" ).html("<span>In Cart&nbsp;("+data.totalitems+" items): </span><span><b>"+data.cartvalue+"</b></span>");
							$( "#cart-text" ).html("<span>Subtotal&nbsp;("+data.totalitems+" items): </span><span><b>"+data.cartvalue+"</b></span>");
							$( "#incartcredit" ).html("<div><strong>In Cart: "+data.cartvalue+"</strong> </div>");
							$("#cartcount").html(data.carticon+'<span>'+data.totalitems+'</span>');
							$('#cartval').val(data.cartcreditvalue);
						}
					});
            }
            else if($('#checkbox_'+cartid).prop("checked") == false){
                var token=$('meta[name="csrf-token"]').attr('content');
				 var checked = [];
						$.each($("input[name='checkbox_status']:checked"), function(){
								//checked = $(this).val();
								checked.push($(this).val());
						});
						//alert("My favourite sports are: " + favorite.join(", "));
						//alert(checked);
					$.ajax({
						url: '/chck_uncheckcart',
						type:"POST",
						async: true,
						dataType: 'json',
						headers: {
							'X-CSRF-TOKEN':token
						},
						data:'id='+checked+'&_token='+token,
						success:function(data){
							$( ".crat-price" ).html("<span>In Cart&nbsp;("+data.totalitems+" items): </span><span><b>"+data.cartvalue+"</b></span>");
							$( "#cart-text" ).html("<span>Subtotal&nbsp;("+data.totalitems+" items): </span><span><b>"+data.cartvalue+"</b></span>");
							$( "#incartcredit" ).html("<div><strong>In Cart: "+data.cartvalue+"</strong> </div>");
							$("#cartcount").html(data.carticon+'<span>'+data.totalitems+'</span>');
							$('#cartval').val(data.cartcreditvalue);
						}
					});
            }


}
function changebackground(img,id){
	var token=$('meta[name="csrf-token"]').attr('content');
	var src=$('#image-url_'+id).val();
	var img=$('.apply_changes').attr('bg-id');

	$(".apply_changes").attr('id', img);
	var checkinput = $(".apply_changes").attr('data-value');
	if(checkinput==''){
	 $('#error').text('Please Check atleast one option to apply the background');
	   setTimeout(function () {
                     $('#error').text(' ');
                 }, 2500);

}
	//$("#loader-spin").css("display", "block");
	if(checkinput!='' && img!=''){
		if(checkinput=='all'){
			  $('#modal-body').html('<p>Do you want to apply this background to <strong>ALL IMAGES</strong> in cart?</p>');
			  $('#modal-footer').html('<button type="button" class="btn btn-default" id="ok-btn">Yes</button><button type="button" class="btn btn-default" id="no-btn">No</button><button type="button" class="btn btn-default" id="cancel-btn">Cancel</button>');
			 $('#custom-modal').modal('show');
			  $('#cancel-btn').on('click', function() {
                  $('#custom-modal').modal('hide');
                 });
				 $('#no-btn').on('click', function() {
                  $('#custom-modal').modal('hide');
                 });
				 $('#ok-btn').on('click', function() {
					  $('#custom-modal').modal('hide');
					$.ajax({
					url: '/allcart_background',
					type:"GET",
					dataType: 'json',
					async: false,
					 beforeSend: function(){
						//$('#loader-spin').css("visibility", "visible");
						//$('#loader-spin').removeClass('hidden');

					},
					headers: {
						'X-CSRF-TOKEN':token
					},
					data:'_token='+token+'&img='+img,
					success:function(data){
					var k = 0;
					$.each(data.response, function (i) {
						$.each(data.response[i], function (key, val) {
						$("#bigimagesize_"+key).attr("src",'/'+val);
						$("#alldetail_"+key).attr("data-image",'/'+val);
				});
			});
					$("#errorMessage").html('<div class=""><strong>Apply background to ALL IMAGES in cart successfully</strong> </div>');
						myFunction();
					setTimeout(function(){location.reload();}, 2000);

					},
					complete: function(){
						//$('#loader-spin').css("visibility", "hidden");
						//$('#loader-spin').addClass('hidden');
						//$("#loader-spin").css("display", "none")
					}
				});
	});
	}else{
		 $('#modal-body').html('<p>Do you want to apply this background to this image <strong>ONLY</strong>?</p>');
			 $('#custom-modal').modal('show');
			  $('#cancel-btn').on('click', function() {
                  $('#custom-modal').modal('hide');

                 });
				  $('#no-btn').on('click', function() {
                  $('#custom-modal').modal('hide');
                 });
				 $('#ok-btn').on('click', function() {
						 $('#custom-modal').modal('hide');

					$.ajax({
						url: '/cart-background',
						type:"GET",
						async: false,
							dataType: 'json',
						 beforeSend: function(){
							//$('#loader-spin').css("visibility", "visible");
							//$('#loader-spin').removeClass('hidden');

						},
						headers: {
							'X-CSRF-TOKEN':token
						},
						data:'src='+src+'&_token='+token+'&img='+img+'&id='+id,
						success:function(data){

							///imagick/colorImage.png
							if(data){
								$("#bigimagesize_"+id).attr("src",'/'+data.url);
								$("#alldetail_"+id).attr("data-image",'/'+data.url);
								$("#errorMessage").html('<div class=""><strong>Apply background to this image ONLY successfully</strong> </div>');
							myFunction();
							setTimeout(function(){location.reload();}, 2000);
							}
						},
						complete: function(){
							//$('#loader-spin').css("visibility", "hidden");
							//$('#loader-spin').addClass('hidden');
							//$("#loader-spin").css("display", "none")
						}
					});
	});

	}
	}
}

$('.open-form').on('click', function() {
			var uniqueid=$("#uniqueid").val();
			var cartval=$("#cartval").val();
			if(uniqueid == ""){
				$("#exampleModal").modal("show");
				return false
			}

           var token=$('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url: '/buynow',
					type:"POST",
					async: true,
					dataType: 'json',
					headers: {
						'X-CSRF-TOKEN':token
					},
					data: 'cartval='+cartval+'&_token='+token,
					success:function(data){
						 var option = [];
						 option.push('<option value="">Select</option>');
						 $.each(data.country, function (i) {
							$.each(data.country[i], function (key, val) {
								var selected="";
								if(data.billing_address!=null){
									if(data.billing_address.billing_country==val){
										selected="Selected";

										}
										option.push('<option value="'+ val +'"'+selected+'>'+ val +'</option>');
								}else{
									selected='';
								option.push('<option value="'+ val +'"'+selected+'>'+ val +'</option>');
								}

							});
						});
						$('#mySelect').html(option.join(''));
						if(data.wcount>1){
							var items='items';
							}else{
							var items='item';
						}
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
						$("#plan-price").html('<strong>$'+parseFloat(cartval).toFixed(2)+'</strong>');
						$("#plan-name").text('Subtotal ('+data.wcount +' '+items+'):');
						$("#checkoutModal").modal("show");
					}
				})


				//$("#price-plan-from").submit();
        });

		  $('.all-checkbox').on('change', function() {
		    $('.all-checkbox').not(this).prop('checked', false);
		});
