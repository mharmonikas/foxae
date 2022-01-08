//
//    This file is derived from the javascript portion of the drag-zoom example
//    at the web site:
//     https://stackoverflow.com/questions/35252249/move-drag-pan-and-zoom-object-image-or-div-in-pure-js
//




// $(document).on("mousedown",".inner-parts a",function(){
          // window.open("/i/"+$(this).attr('data-seo'),'_newtab');
      // }
// })

function loadImage() {
	//alert('helo');
	$('.myloadercontainer2').css("display", "none");
}
$(".close_icon").on("tap", function() {
	alert('hello');
  closebigForm();
});

$(document).ready(function () {
	$( document ).on( "doubletap", "#bigimagesize", function() {

		//alert('helo');

	  "use strict";
     var $src = $(this).attr("src");
     var image_title = $('.bigimagename-3').text();

        $(".showed").fadeIn();
        $(".img-show img").attr("src", $src);
        $(".image-title").text(image_title);
  });
	   $("span, .overlay").click(function () {
        $(".showed").fadeOut();
    });
});


	$(".btn-refresh").click(function(){
	  $.ajax({
		 type:'GET',
		url:'/refresh_captcha',
		 success:function(data){
			$(".captcha span").html(data.captcha);
		 }
	  });
	});
 $(function() {
		$(document).on("mousedown",".btn-model a",function(){
		   localStorage.setItem("seo_url", $(this).attr('data-seo'));
		});
        $.contextMenu({
            selector: '.btn-model a',
            callback: function(key, options) {
			window.open("/i/"+localStorage.getItem("seo_url"),'_blank');

            },
            items: {
                "edit": { name: "Open in new tab" }
            }
        });

		$(document).on("mousedown",".btn-model",function(){
		   localStorage.setItem("seo_url", $(this).attr('data-seo'));
		});

		$.contextMenu({
            selector: '.btn-model',
            callback: function(key, options) {
			window.open("/i/"+localStorage.getItem("seo_url"),'_blank');

            },
            items: {
                "edit": {name: "Open in new tab" }
            }
        });
});
function download_detail(id){

}

function openForm2(formname2) {
  if(formname2 == 'signup2'){
  		document.getElementsByClassName('login_form2')[0].style.display = "none";
  		document.getElementsByClassName('register_form2')[0].style.display = "block";

  }else if(formname2 == 'signin2'){
  		document.getElementsByClassName('login_form2')[0].style.display = "block";
  		document.getElementsByClassName('register_form2')[0].style.display = "none";
  }

}

function openbigForm() {
  document.getElementById('bigimg').style.display = "block";
}

function closebigForm() {

	$('video').trigger('pause');
  document.getElementById("bigimg").style.display = "none";
  $('.homepage').removeClass('freeze');
}
var videoID = $('#videoID');
function setPlaySpeed(speed) {
  videoID.playbackRate = speed;
}
var videoID2 = document.getElementById("fvideoID");
function setPlaySpeed2(speed) {
  videoID2.playbackRate = speed;
}
$(document).ready(function(){
  $(".info-advance-search").click(function(){

    $(".iconsdsf").toggleClass("show");
	if($(this).find("span").text() == '+'){
		$(this).find("span").text('-');
	}else{
		$(this).find("span").text('+');
	}
  });
});

function openNav() {
  document.getElementById("mySidenav").style.width = "350px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

$(document).on("click",".btn-download",function(event){
	event.preventDefault();

	var pack=$("#package-detail").val();
	var value=$("#package-detail").attr('data-value');
	var uniqueid=$("#uniqueid").val();
	if(uniqueid == ""){

		openForm('signin');
		document.body.scrollTop = 0;
					document.documentElement.scrollTop = 0;
		return false;
	}
	if(value == "" || value ==0){
		openNav();
		return false;
	}
	if(pack=='yes'){
	if (confirm('Are you sure you want to use your credit and download this? Available credits is '+value)) {
	$("#productid").val($(this).attr('data-val'));
	downloadinfo('no');
	}
	}else{
		openNav()
	}
});

$(document).on("click",".btn-redownload",function(){
	$("#productid").val($(this).attr('data-val'));
	downloadinfo('no');

});

$("#download-image").click(function(){

	var downloadstatus=$("#downloadstatus").val();
	if(downloadstatus == 1){
		downloadinfo('yes');
		return false;
	}
	var pack=$("#package-detail").val();

	var value=$("#package-detail").attr('data-value');

	if(value == "" || value ==0){
		openNav();
		return false;
	}
	if(pack=='yes'){
		if (confirm('Are you sure you want to use your credit and download this? Available credits is '+value)) {
			downloadinfo('yes');
		}
	}else{
		openNav()
	}
});

$(".unsubscribe").click(function(){
//if (confirm('Are you sure you want to cancel the subscription.')) {
var value=$(this).attr('data-value');
var token=$('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: '/unsubscribe-pack',
		type:"POST",
		async: true,
        dataType: 'json',
		headers: {
			'X-CSRF-TOKEN':token
		},
		data:'packid='+value+'&_token='+token,
		success:function(data){
			  $("#unsubscribe_"+data.packid).remove();
			  $('#subscripition_'+data.packid).val('Subscription Cancel');
			   location.reload();
			}
		});
	//}
});
function downloadinfo(status){
	var agree = $("#agree").val();
	if(status == 'yes'){ if($("#agree").is(':checked')){ }else{ $("#content-agree").css("color","red"); setTimeout(function(){ $("#content-agree").css("color","black");  }, 3000); return false; } }
	var productnid = $("#productid").val();

	var token=$('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: '/download',
		type:"POST",
		async: true,
        dataType: 'json',
		headers: {
			'X-CSRF-TOKEN':token
		},
		data:'id='+productnid+'&_token='+token,
		success:function(data){

			if(data.response == 'done'){
				$('#package-detail').val(data.val);

				$("#download_"+data.id).html('<i class="fa fa-check-circle" aria-hidden="true"></i>');
				$("#download_"+data.id).removeClass('btn-download');
				$("#download_"+data.id).addClass('btn-redownload');
				//.
				//$("#img-credits").text();
				if(data.credit != 0){
					if(data.download == 'new'){
						//var resultcre = $("#img-credits").text().split(' ');
						//if ($.isNumeric(parseInt(resultcre[0]))) {
							//$("#img-credits").text((parseInt(resultcre[0]) - parseInt(data.credit))+" Credits");
							if(data.val=='yes'){
								$('#package-detail').attr('data-value',data.pack);
								$('#credit-count').text(data.pack);
								$("#availablecredit").html('<div class=""><strong>Credits : '+data.pack+'</strong> </div>');
							}else{
								$("#errorMessage").html('<div class=""><strong>Not enough credit.</strong> </div>');
								myFunction();
							}
						//}
					}
				}
					if(data.download == 'new'){
						if(data.val=='yes'){
							var str = data.image;
							download(str);
						}
					}else if(data.download == 'old'){
						var str = data.image;
							download(str);
					}
				}else if(data.response == 'login'){
					openForm('signin')
					document.body.scrollTop = 0;
					document.documentElement.scrollTop = 0;
			}else{
				openNav()
			}
		}
	});
}


function download(str) {
  var a = document.createElement("a");
  a.href = "/fileTodownload/"+str;
  a.setAttribute("target", "_blank");
  a.click();
}

$(document).on("click",".btn-wishlist",function(event){
	event.stopImmediatePropagation();
	var uniqueid=$("#uniqueid").val();
	// if(uniqueid == ""){

		// openForm('signin');
		// document.body.scrollTop = 0;
		// document.documentElement.scrollTop = 0;
		// return false;
	// }
	  var x = document.getElementById("errorMessage");
	    x.className = x.className.replace("show", "hide");
		$("#errorMessage").empty();
	var wid = $(this).attr('data-value');
	var pid = $(this).attr('id');
	var vproductid  = pid.split('_');
	//alert(pid);
	$("#productid").val($(this).attr('data-value'));
		var productnid = $("#productid").val();
//alert(productnid);
		var token=$('meta[name="csrf-token"]').attr('content');
		var datastatus = $(this).attr('data-status');
	var changestatus = changehtml = addtocart_status = cartstatus = "";
	if(datastatus == 'in-cart'){
		changestatus = 'out-cart';
		cartstatus = 'Add';
		changehtml = 'Remove';
		changehtml1 = "<a id='"+pid+"' class='btn-wishlist' data-value='"+productnid+"'  data-status='"+changestatus+"'><svg width='33' height='33' viewBox='0 0 25 25' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M0.739746 1.90625C0.333496 2.3125 0.333496 2.96875 0.739746 3.375L4.57308 7.20833L6.87516 12.0625L5.46891 14.6146C5.271 14.9583 5.17725 15.375 5.21891 15.8125C5.32308 16.9167 6.32308 17.7083 7.42725 17.7083H15.0627L16.5002 19.1458C15.9793 19.5208 15.6356 20.1354 15.6356 20.8333C15.6356 21.9792 16.5627 22.9167 17.7085 22.9167C18.4064 22.9167 19.021 22.5729 19.396 22.0417L21.6147 24.2604C22.021 24.6667 22.6772 24.6667 23.0835 24.2604C23.4897 23.8542 23.4897 23.1979 23.0835 22.7917L2.2085 1.90625C1.80225 1.5 1.146 1.5 0.739746 1.90625ZM7.29183 15.625L8.43766 13.5417H10.896L12.9793 15.625H7.29183ZM16.7189 13.4792C17.2814 13.3333 17.7502 12.9687 18.021 12.4687L21.7502 5.70833C22.1356 5.02083 21.6252 4.16667 20.8335 4.16667H7.41683L16.7189 13.4792ZM7.29183 18.75C6.146 18.75 5.21891 19.6875 5.21891 20.8333C5.21891 21.9792 6.146 22.9167 7.29183 22.9167C8.43766 22.9167 9.37516 21.9792 9.37516 20.8333C9.37516 19.6875 8.43766 18.75 7.29183 18.75Z' fill='#5B5C5C'/></svg></a><p>Remove</p>";
		addtocart_status = "ADDED TO CART!";
	}else{
		cartstatus = 'Remove';
		changestatus = 'in-cart';
		changehtml = 'Add to Cart';
		changehtml1 = "<a id='"+pid+"' class='btn-wishlist' data-value='"+productnid+"'  data-status='"+changestatus+"'><svg width='33' height='33' viewBox='0 0 33 33' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M16.5 12.375C17.2563 12.375 17.875 11.7563 17.875 11V8.25H20.625C21.3813 8.25 22 7.63125 22 6.875C22 6.11875 21.3813 5.5 20.625 5.5H17.875V2.75C17.875 1.99375 17.2563 1.375 16.5 1.375C15.7437 1.375 15.125 1.99375 15.125 2.75V5.5H12.375C11.6187 5.5 11 6.11875 11 6.875C11 7.63125 11.6187 8.25 12.375 8.25H15.125V11C15.125 11.7563 15.7437 12.375 16.5 12.375ZM9.625 24.75C8.1125 24.75 6.88875 25.9875 6.88875 27.5C6.88875 29.0125 8.1125 30.25 9.625 30.25C11.1375 30.25 12.375 29.0125 12.375 27.5C12.375 25.9875 11.1375 24.75 9.625 24.75ZM23.375 24.75C21.8625 24.75 20.6388 25.9875 20.6388 27.5C20.6388 29.0125 21.8625 30.25 23.375 30.25C24.8875 30.25 26.125 29.0125 26.125 27.5C26.125 25.9875 24.8875 24.75 23.375 24.75ZM11.1375 17.875H21.3813C22.4125 17.875 23.32 17.3113 23.7875 16.4588L28.435 8.03C28.8062 7.37 28.5588 6.53125 27.8988 6.16C27.2388 5.8025 26.4 6.03625 26.0425 6.69625L21.3813 15.125H11.7287L6.2425 3.53375C6.0225 3.0525 5.5275 2.75 5.005 2.75H2.75C1.99375 2.75 1.375 3.36875 1.375 4.125C1.375 4.88125 1.99375 5.5 2.75 5.5H4.125L9.075 15.9363L7.21875 19.2913C6.215 21.1338 7.535 23.375 9.625 23.375H24.75C25.5063 23.375 26.125 22.7563 26.125 22C26.125 21.2438 25.5063 20.625 24.75 20.625H9.625L11.1375 17.875Z' fill='#5B5C5C'/></svg></a><p>Add to cart</p>";
		addtocart_status = "REMOVED FROM CART!";
	}


		$.ajax({
			url: '/wishlist',
			type:"POST",
			async: true,
			dataType: 'json',

			headers: {
				'X-CSRF-TOKEN':token
			},
			data:'id='+productnid+'&_token='+token+'&cartstatus='+cartstatus,
			success:function(data){

				if(data.response=='1'){

					$("#availablecredit").empty();
					$("#incart-credit").empty();
					$("#cartcount").html(data.cart_icon+'<span>'+data.count+'</span>');
					$("#errorMessage").html('<div class=""><strong>'+addtocart_status+'</strong> </div>');
					if(data.cartvalue!=''){
				$("#availablecredit").html('<span class="title-head">Available: </span><span><b>'+data.availablecount+' Credits</b></span>');
					}

				if(data.count > 0){
						  $(".homepage-popup").addClass("show");
						  $('.homepage-popup').removeClass("hide");
						//$('.homepage').replaceClass('hide','show');
					}else{
						 $(".homepage-popup").addClass("hide");
						  $('.homepage-popup').removeClass("show");
						//$('.homepage').replaceClass('show','hide');
					}
					if(uniqueid == ""){
						var val=data.cartvalue;
						var cartvalue='$'+val.toFixed(2);

					}else{
						if(data.packageid!=''){
							var cartvalue=data.cartvalue+' Credits';
						}else{
							var val=data.cartvalue;
							var cartvalue='$'+val.toFixed(2);
						}
					}

					$("#incart-credit").html('<span class="title-head">In Cart&nbsp;('+data.count+' items): </span><span><b>'+cartvalue+'</b></span>');
					//}
					$("#"+pid).attr('data-status',changestatus);
					$("."+vproductid[1]+"_content").attr('cart-status',changestatus);
					$("#"+pid).html(changehtml);
					$("#add-cartli").html(changehtml1);
						myFunction();

				}else if(data.response=='2'){
					$('#exampleModal').modal('show');


				}


			}
		});
});


$(document).on("click",".btn-favorites",function(){
	var uniqueid=$("#uniqueid").val();
	if(uniqueid == ""){
	$('#exampleModal').modal('show');

		// openForm('signin');
		// document.body.scrollTop = 0;
					// document.documentElement.scrollTop = 0;
		return false;
	}

	var wid = $(this).attr('data-value');
	var pid = $(this).attr('id');
	$("#productid").val($(this).attr('data-value'));
		var productnid = $("#productid").val();
		var token=$('meta[name="csrf-token"]').attr('content');
		var datastatus = $(this).attr('data-status');
	var changestatus = changehtml = addtocart_status = cartstatus =  carttitle = "";
	if(datastatus == 'out-favorites'){
		changestatus = 'in-favorites';
		cartstatus = 'Add';
		changehtml = "<svg width='33' height='33' viewBox='0 0 28 28' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M15.5749 23.4848C14.6883 24.2898 13.3233 24.2898 12.4366 23.4731L12.3083 23.3565C6.18327 17.8148 2.1816 14.1865 2.33327 9.65979C2.40327 7.67645 3.41827 5.77479 5.06327 4.65479C8.14327 2.55479 11.9466 3.53479 13.9999 5.93812C16.0533 3.53479 19.8566 2.54312 22.9366 4.65479C24.5816 5.77479 25.5966 7.67645 25.6666 9.65979C25.8299 14.1865 21.8166 17.8148 15.6916 23.3798L15.5749 23.4848Z' fill='#FF8F09'/></svg>";
		addtocart_status = "Added to favourites successfully";
		carttitle = "Delete from Collection";
	}else{
		cartstatus = 'Remove';
		changestatus = 'out-favorites';
		changehtml = "<svg width='33' height='33' viewBox='0 0 33 33' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M27.0323 5.48629C23.4023 3.01129 18.9198 4.1663 16.4998 6.9988C14.0798 4.1663 9.5973 2.99754 5.9673 5.48629C4.0423 6.80629 2.8323 9.03379 2.7498 11.385C2.5573 16.72 7.28731 20.9963 14.5061 27.555L14.6436 27.6788C15.6886 28.6275 17.2973 28.6275 18.3423 27.665L18.4936 27.5275C25.7123 20.9825 30.4286 16.7063 30.2498 11.3713C30.1673 9.0338 28.9573 6.80629 27.0323 5.48629ZM16.6373 25.5063L16.4998 25.6438L16.3623 25.5063C9.8173 19.58 5.4998 15.6613 5.4998 11.6875C5.4998 8.93754 7.5623 6.87504 10.3123 6.87504C12.4298 6.87504 14.4923 8.2363 15.2211 10.12H17.7923C18.5073 8.2363 20.5698 6.87504 22.6873 6.87504C25.4373 6.87504 27.4998 8.93754 27.4998 11.6875C27.4998 15.6613 23.1823 19.58 16.6373 25.5063Z' fill='#5B5C5C'/></svg>";
		addtocart_status = "Removed from favorites successfully";
		carttitle = "Add to Collection";
	}


		$.ajax({
			url: '/favorites',
			type:"POST",
			async: true,
			dataType: 'json',
			headers: {
				'X-CSRF-TOKEN':token
			},
			data:'id='+productnid+'&_token='+token+'&cartstatus='+cartstatus,
			success:function(data){
				if(data.response=='1'){

					$("#errorMessage").html('<div class=""><strong>'+addtocart_status+'</strong> </div>');
					$("#"+pid).attr('data-status',changestatus);
					$("#"+pid).attr('title',carttitle);
					$("#"+pid).html(changehtml);
					myFunction();
				}else if(data.response=='2'){
					$('#exampleModal').modal('show');


				}
			}
		});
});


$(".cart-login").click(function(){
	openForm('signin')
	 document.body.scrollTop = 0;
	document.documentElement.scrollTop = 0;

});
$(".resend").click(function(){
var token=$('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: '/resend_email',
		type:"POST",
		async: true,
		headers: {
			'X-CSRF-TOKEN':token
		},
		data:'_token='+token,
		success:function(data){
				$("#errorMessage").html('<div class=""><strong>Email has been sent in your email. If you not receive email please check in spam folder  </strong> </div>');
				myFunction();
			}
		});

});
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});

// $(".cnrflash").click(function(){
	// if($(this))
// });

 function openForm3(formname) {

  if(formname == 'signup'){
		$(".btn-refresh").click();
		$("#exampleModal").modal("hide");
		$("#signupModal").modal("show");
		$("#forgotModal").modal("hide");
  		// document.getElementById('signinModal').style.display = "none";
  		// document.getElementById('signupModal').style.display = "block";
		//document.getElementsById('forgot_form').style.display = "none";
  }else if(formname == 'signin'){
	  $("#exampleModal").modal("show");
		$("#signupModal").modal("hide");
		$("#forgotModal").modal("hide");
  		// document.getElementById('signinModal').style.display = "block";
  		// document.getElementById('signupModal').style.display = "none";
  		//document.getElementsById('forgot_form').style.display = "none";

  }else if(formname == 'forgot'){
  		$("#exampleModal").modal("hide");
		$("#signupModal").modal("hide");
		$("#forgotModal").modal("show");

  }

}

function CopyFunction() {
  var copyText = document.getElementById("myInput");
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  document.execCommand("copy");

  var tooltip = document.getElementById("myTooltip");
  tooltip.innerHTML = "Copied: Successfully" ;
}

function outFunc() {
  var tooltip = document.getElementById("myTooltip");
  tooltip.innerHTML = "Copy to clipboard";
}
function closeCopy(){
	$(".share-link").css("display","none");
}
function showCopy(){
	$(".share-link").css("display","block");
}

function change_background(bg,vid,event){
    $('.myloadercontainer2').css("display", "block");
    let changeBackground = $(".change-background")
	var token=$('meta[name="csrf-token"]').attr('content');
    var id = vid!='' ? vid : changeBackground.attr('data-value');
    var src='';
    // $("#backgroundnavbarDropdown").dropdown('toggle');

    let bigimagesize = $("#bigimagesize")
    let imgName = bigimagesize.attr('data-img-name')
    let imgId = bigimagesize.attr('data-img-id')
    let siteId = bigimagesize.attr('data-site-id')

    let url = '/watermarkedImages/' + siteId + '/' + imgId + '/' + bg + '/' + imgName
    bigimagesize.attr('src', url); // Add the image with chosen background

    $.ajax({
        url: '/cart-background',
        type:"GET",
        async: true,
        dataType: 'json',
        beforeSend: function(){},
        headers: {
            'X-CSRF-TOKEN':token
        },
        data:'src='+src+'&_token='+token+'&img='+bg+'&id='+id,
        success:function(data){
            // var appliedbg=data.apllied_bg.toUpperCase();

            // setTimeout(function(){
                // if(event=='onclick'){
                //     $("#errorMessage").html('<div class=""><strong>Background changed to '+data.apllied_bg+'</strong> </div>');
                //     myFunction();
                // }  }, 3500);
                //
                // $('#bigimagesize').on('load', function(){
                //     $(".myloadercontainer2").css("display", "none");
                // });
                //
                // if(data){
                //     $("#bigimagesize").attr("src",'/'+data.url);
                //     $("#alldetail_"+id).attr("data-image",'/'+data.url);
                //
                //     if(id!=''){
                //         $("#bigimagesize_"+id).attr("src",'/'+data.url);
                //     }
                //
                //     $("#background-effect_"+id).text(appliedbg+' BACKGROUND');
                //
                //     $("#appliedbg_"+id).text(data.apllied_bg+' Background');
                // }
        }
    });
}
$('.passwordplaceholder').click(function() {
  $(this).siblings('input').focus();
});
$('.form-control').focus(function() {
  $(this).siblings('.passwordplaceholder').hide();
});
$('.form-control').blur(function() {
  var $this = $(this);
  if ($this.val() != '')
    $(this).siblings('.passwordplaceholder').show();
});
$('.form-control').blur();

$('.conpasswordplaceholder').click(function() {
  $(this).siblings('input').focus();
});
$('#cartsignupconfirmpassword').focus(function() {
  $(this).siblings('.conpasswordplaceholder').hide();
});
$('#cartsignupconfirmpassword').blur(function() {
  var $this = $(this);
  if ($this.val() != '')
    $(this).siblings('.conpasswordplaceholder').show();
});
$('#cartsignupconfirmpassword').blur();

$('.emailplaceholder').click(function() {
  $(this).siblings('input').focus();
});
$('.form-control').focus(function() {
  $(this).siblings('.emailplaceholder').hide();
});
$('.form-control').blur(function() {
  var $this = $(this);
  if ($this.val() != '')
    $(this).siblings('.emailplaceholder').show();
});
$('.form-control').blur();
