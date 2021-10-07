@include('header')
<?php
use App\Http\Controllers\HomeController;
$Plans = HomeController::plans();
$background = HomeController::background();

?>
<link rel="stylesheet" href="/css/fox.css?v=0.0.1">
<style>

.bigimgcontainer img {
    cursor: zoom-in;
}

@media only screen and (max-width: 600px){
.loaderview1 {
    width: 30%;
}
.imgs-setup button {
	display:none;
}
}


</style>

<?php
$explodeurl = explode("/",$_SERVER['REQUEST_URI']);
?>



<div class="container-fluid image-setting" id="">
<input type="hidden" id="downloadstatus"  value="0">
	<div class="row fluid-row">

		<!-- Login Register or Download popup -->
<div >
	<div class="row uppr bgpopup-color">
	<div class="col-md-12 col-offset-md-4 title_close">
			<h3 class="bigimagename mob hide-on-mobile">{{$response->VchTitle }}</h3>
		</div>
		<div class="feat-p pricing_main_section buy_section col-md-12">
				<p class="p-heading">{{$response->VchTitle }}</p>
				<p class="dtap-p hide-on-desktop">Double-tap on the image to zoom</p>
			</div>
		<div class="col-md-9 col-sm-12 feat-lft">
		<!--<h3 class="bigimagename desk">{{$response->VchTitle }}</h3>-->
			<div class="image-center">
				 <div class="myloadercontainer2" id="loader">
				<div class="loder_innes">
					<div class="loaderview1">
						<img src="/images/{{$managesite->gificon}}" alt="img"  style="width:auto !important;height:130px;">
					</div>
				</div>
			</div>
			@if($response->EnumType == 'I')
			<div class="imgs-setup" id="imagepart">
				<div class="share-middle">
                    @if($response->content_category=='1')
                        @if($response->stock_category=='1')

                        <span id="image-desc" class="image-desc standard">
                            Standard
                        </span>
                        @elseif($response->stock_category=='2')
                            <span id="image-desc" class="image-desc custom">
                            Custom
                        </span>
                        @endif
                    @elseif($response->content_category=='2')

                    @if($response->stock_category=='1')

                        <span id="image-desc" class="image-desc permium">
                            Premium </span>
                        @elseif($response->stock_category=='2')
                            <span id="image-desc" class="image-desc custom">
                            Custom
                        </span>
                    @endif

                    @elseif($response->content_category=='3')
                        @if($response->stock_category=='1')

                        <span id="image-desc" class="image-desc ultra_premium">
                            Deluxe </span>
                        @elseif($response->stock_category=='2')
                            <span id="image-desc" class="image-desc custom">
                            Custom
                        </span>
                        @endif
                    @endif
					<!--<img src="/img/share-apple.png" onclick="showCopy()">-->
				</div>
					<div class="bigimgcontainer" id="zoom-container" >
						@if($applied_bg=='')
							<img src="/showimage/{{$response->IntId }}/{{$managesite->intmanagesiteid}}/{{$response->VchVideoName }}?={{$response->intsetdefault }}" class="loading" id="bigimagesize"  ondragstart="return false">
						@else
							<img src="/showimg/{{$userid}}/{{$imgname }}" class="loading" id="bigimagesize"  ondragstart="return false" >
						@endif
					</div>

					<button class="" id="zoomin"><svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="33" viewBox="0 0 24 24" width="33"><g><g><rect fill="none" height="33" width="33" y="0"/></g></g><g><g><path d="M11.5,8.5h-1v-1c0-0.55-0.45-1-1-1s-1,0.45-1,1v1h-1c-0.55,0-1,0.45-1,1c0,0.55,0.45,1,1,1h1v1c0,0.55,0.45,1,1,1 s1-0.45,1-1v-1h1c0.55,0,1-0.45,1-1C12.5,8.95,12.05,8.5,11.5,8.5z"/><path d="M14.73,13.31c1.13-1.55,1.63-3.58,0.98-5.74c-0.68-2.23-2.57-3.98-4.85-4.44C6.21,2.2,2.2,6.22,3.14,10.86 c0.46,2.29,2.21,4.18,4.44,4.85c2.16,0.65,4.19,0.15,5.74-0.98l5.56,5.56c0.39,0.39,1.02,0.39,1.41,0l0,0 c0.39-0.39,0.39-1.02,0-1.41L14.73,13.31z M9.5,14C7.01,14,5,11.99,5,9.5S7.01,5,9.5,5S14,7.01,14,9.5S11.99,14,9.5,14z"/></g></g></svg></button>
					<button class="hide-btn" id="zoomout"><svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="33" viewBox="0 0 24 24" width="33"><g><g><rect fill="none" height="33" width="33" y="0"/></g></g><g><g><path d="M11,8.5H8c-0.55,0-1,0.45-1,1c0,0.55,0.45,1,1,1h3c0.55,0,1-0.45,1-1C12,8.95,11.55,8.5,11,8.5z"/><path d="M14.73,13.31c1.13-1.55,1.63-3.58,0.98-5.74c-0.68-2.23-2.57-3.98-4.85-4.44C6.21,2.2,2.2,6.22,3.14,10.86 c0.46,2.29,2.21,4.18,4.44,4.85c2.16,0.65,4.19,0.15,5.74-0.98l5.56,5.56c0.39,0.39,1.02,0.39,1.41,0l0,0 c0.39-0.39,0.39-1.02,0-1.41L14.73,13.31z M9.5,14C7.01,14,5,11.99,5,9.5S7.01,5,9.5,5S14,7.01,14,9.5S11.99,14,9.5,14z"/></g></g></svg></button>
				</div>
			@elseif($response->EnumType == 'V')
				<div class="imgs-setup" id="videopart">
					<div class="bigvideocontainer" >
						<video id="newvideo" controls>
							<source src="/{{$response->VchFolderPath }}/{{$managesite->intmanagesiteid}}/watermark.mp4" type="video/mp4">
						</video>
					</div>
				</div>
			@endif
			<div class="share-link" style="display:none;">
					<div class="copy-text">
					<input type="text" value="{{Request::fullUrl()}}" id="myInput" readonly >
						<div class="tooltips">
						<button onclick="CopyFunction()" onmouseout="outFunc()">
						  <span class="tooltiptext" id="myTooltip">Copy to clipboard</span>
						  <i class="fa fa-copy"></i>
						  </button>
						</div>
						<div class="close-copy" onclick="closeCopy()">
							<span>&#x2715; </span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-12 ryt feat-ryt">

			<div class="feat-top">
			<div class="login-user" id="login-user">
			<ul>
				<li><strong>Type : </strong> {{$data_type}}</li>
				<li><strong>Resolution : </strong>{{$diemension}}</li>
				<li><strong>Skin Tone : </strong> {{$skintone}}</li>
				<li><strong>Category : </strong> {{$category}}</li>
				<li><strong>Gender : </strong> {{$gender}}</li>
				</ul>

			</div>

			<div class="rlt-key"><span>Similar Searches:</span>
			@php
				$relatedkeyword = explode(",",$response->videotags);
			@endphp
			@foreach($relatedkeyword as $key=>$value)
			@if($value!='')
				<a href="/?s={{$value}}">{{$value}}</a>
			@else
				<span>No similar searches</span>
			@endif
			@endforeach

			</div>



			</div>
				<div class="feat-bottom">
				@if(empty($userdetail))
				<div class="img-btm login-text" id="login-link"><p>Don't have free account yet?</p><a data-toggle="modal" data-target="#signupModal">Create your free account</a><button class="btn btn-default" data-toggle="modal" data-target="#exampleModal">Log In</button></div>
				@endif
				<div class="img-btm ">
					<p>Not quite what you are looking for?</p>
					<a href="/custom">Request Custom Graphics</a>
				</div>

				<div class="main-icon">
					<ul>
				@if($response->stock_category=='1')
						<li id="add-cartli">

						@if($cartstatus=='in-cart')
						<a id="addToCart_{{$id}}" class="btn-wishlist" data-value="{{$productid}}" data-status="{{$cartstatus}}"><svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.5 12.375C17.2563 12.375 17.875 11.7563 17.875 11V8.25H20.625C21.3813 8.25 22 7.63125 22 6.875C22 6.11875 21.3813 5.5 20.625 5.5H17.875V2.75C17.875 1.99375 17.2563 1.375 16.5 1.375C15.7437 1.375 15.125 1.99375 15.125 2.75V5.5H12.375C11.6187 5.5 11 6.11875 11 6.875C11 7.63125 11.6187 8.25 12.375 8.25H15.125V11C15.125 11.7563 15.7437 12.375 16.5 12.375ZM9.625 24.75C8.1125 24.75 6.88875 25.9875 6.88875 27.5C6.88875 29.0125 8.1125 30.25 9.625 30.25C11.1375 30.25 12.375 29.0125 12.375 27.5C12.375 25.9875 11.1375 24.75 9.625 24.75ZM23.375 24.75C21.8625 24.75 20.6388 25.9875 20.6388 27.5C20.6388 29.0125 21.8625 30.25 23.375 30.25C24.8875 30.25 26.125 29.0125 26.125 27.5C26.125 25.9875 24.8875 24.75 23.375 24.75ZM11.1375 17.875H21.3813C22.4125 17.875 23.32 17.3113 23.7875 16.4588L28.435 8.03C28.8062 7.37 28.5588 6.53125 27.8988 6.16C27.2388 5.8025 26.4 6.03625 26.0425 6.69625L21.3813 15.125H11.7287L6.2425 3.53375C6.0225 3.0525 5.5275 2.75 5.005 2.75H2.75C1.99375 2.75 1.375 3.36875 1.375 4.125C1.375 4.88125 1.99375 5.5 2.75 5.5H4.125L9.075 15.9363L7.21875 19.2913C6.215 21.1338 7.535 23.375 9.625 23.375H24.75C25.5063 23.375 26.125 22.7563 26.125 22C26.125 21.2438 25.5063 20.625 24.75 20.625H9.625L11.1375 17.875Z" fill="#5B5C5C"></path></svg></a><p>Add to cart</p>
						@else
							<a id="addToCart_{{$id}}" class="btn-wishlist" data-value="{{$productid}}" data-status="{{$cartstatus}}"><svg width='33' height='33' viewBox='0 0 25 25' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M0.739746 1.90625C0.333496 2.3125 0.333496 2.96875 0.739746 3.375L4.57308 7.20833L6.87516 12.0625L5.46891 14.6146C5.271 14.9583 5.17725 15.375 5.21891 15.8125C5.32308 16.9167 6.32308 17.7083 7.42725 17.7083H15.0627L16.5002 19.1458C15.9793 19.5208 15.6356 20.1354 15.6356 20.8333C15.6356 21.9792 16.5627 22.9167 17.7085 22.9167C18.4064 22.9167 19.021 22.5729 19.396 22.0417L21.6147 24.2604C22.021 24.6667 22.6772 24.6667 23.0835 24.2604C23.4897 23.8542 23.4897 23.1979 23.0835 22.7917L2.2085 1.90625C1.80225 1.5 1.146 1.5 0.739746 1.90625ZM7.29183 15.625L8.43766 13.5417H10.896L12.9793 15.625H7.29183ZM16.7189 13.4792C17.2814 13.3333 17.7502 12.9687 18.021 12.4687L21.7502 5.70833C22.1356 5.02083 21.6252 4.16667 20.8335 4.16667H7.41683L16.7189 13.4792ZM7.29183 18.75C6.146 18.75 5.21891 19.6875 5.21891 20.8333C5.21891 21.9792 6.146 22.9167 7.29183 22.9167C8.43766 22.9167 9.37516 21.9792 9.37516 20.8333C9.37516 19.6875 8.43766 18.75 7.29183 18.75Z' fill='#5B5C5C'/></svg></a><p>Remove</p>

						@endif
						</li>
						@endif

						<li id="add-favli">

						@if($favoritesstatus=='out-favorites')
						<a id="favorites_{{$id}}" class="btn-favorites" data-value="{{$productid}}" data-status="{{$favoritesstatus}}"><svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M27.0323 5.48629C23.4023 3.01129 18.9198 4.1663 16.4998 6.9988C14.0798 4.1663 9.5973 2.99754 5.9673 5.48629C4.0423 6.80629 2.8323 9.03379 2.7498 11.385C2.5573 16.72 7.28731 20.9963 14.5061 27.555L14.6436 27.6788C15.6886 28.6275 17.2973 28.6275 18.3423 27.665L18.4936 27.5275C25.7123 20.9825 30.4286 16.7063 30.2498 11.3713C30.1673 9.0338 28.9573 6.80629 27.0323 5.48629ZM16.6373 25.5063L16.4998 25.6438L16.3623 25.5063C9.8173 19.58 5.4998 15.6613 5.4998 11.6875C5.4998 8.93754 7.5623 6.87504 10.3123 6.87504C12.4298 6.87504 14.4923 8.2363 15.2211 10.12H17.7923C18.5073 8.2363 20.5698 6.87504 22.6873 6.87504C25.4373 6.87504 27.4998 8.93754 27.4998 11.6875C27.4998 15.6613 23.1823 19.58 16.6373 25.5063Z" fill="#5B5C5C"></path></svg></a><p>Favourite</p>
						@else
							<a id="favorites_{{$id}}" class="btn-favorites" data-value="{{$productid}}" data-status="{{$favoritesstatus}}"><svg width='33' height='33' viewBox='0 0 28 28' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M15.5749 23.4848C14.6883 24.2898 13.3233 24.2898 12.4366 23.4731L12.3083 23.3565C6.18327 17.8148 2.1816 14.1865 2.33327 9.65979C2.40327 7.67645 3.41827 5.77479 5.06327 4.65479C8.14327 2.55479 11.9466 3.53479 13.9999 5.93812C16.0533 3.53479 19.8566 2.54312 22.9366 4.65479C24.5816 5.77479 25.5966 7.67645 25.6666 9.65979C25.8299 14.1865 21.8166 17.8148 15.6916 23.3798L15.5749 23.4848Z' fill='#FF8F09'/></svg></a><p>Remove</p>
						@endif

						</li>
						<li class="popup-dropdown link-list">
							<a id="sharenavbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.75 22.11C23.705 22.11 22.77 22.5225 22.055 23.1688L12.2512 17.4625C12.32 17.1463 12.375 16.83 12.375 16.5C12.375 16.17 12.32 15.8538 12.2512 15.5375L21.945 9.88625C22.6875 10.5738 23.6637 11 24.75 11C27.0325 11 28.875 9.1575 28.875 6.875C28.875 4.5925 27.0325 2.75 24.75 2.75C22.4675 2.75 20.625 4.5925 20.625 6.875C20.625 7.205 20.68 7.52125 20.7488 7.8375L11.055 13.4888C10.3125 12.8013 9.33625 12.375 8.25 12.375C5.9675 12.375 4.125 14.2175 4.125 16.5C4.125 18.7825 5.9675 20.625 8.25 20.625C9.33625 20.625 10.3125 20.1988 11.055 19.5113L20.845 25.2313C20.7762 25.52 20.735 25.8225 20.735 26.125C20.735 28.3388 22.5362 30.14 24.75 30.14C26.9638 30.14 28.765 28.3388 28.765 26.125C28.765 23.9113 26.9638 22.11 24.75 22.11Z" fill="#5B5C5C"/></svg></a>
							<!--<img class="img-icon" src="/images/share.png">
							<img class="hovr-icon" src="/images/share-c.png">-->
							<p>Share</p>
							        <div class="dropdown-menu" aria-labelledby="sharenavbarDropdown">
									<!--href="https://www.facebook.com/sharer/sharer.php?u=YourPageLink.com&display=popup"-->
										<a class="dropdown-item" ><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21.8337 11C21.8337 5.01996 16.9803 0.166626 11.0003 0.166626C5.02033 0.166626 0.166992 5.01996 0.166992 11C0.166992 16.2433 3.89366 20.6091 8.83366 21.6166V14.25H6.66699V11H8.83366V8.29163C8.83366 6.20079 10.5345 4.49996 12.6253 4.49996H15.3337V7.74996H13.167C12.5712 7.74996 12.0837 8.23746 12.0837 8.83329V11H15.3337V14.25H12.0837V21.7791C17.5545 21.2375 21.8337 16.6225 21.8337 11Z" fill="#5B5C5C"/></svg>Facebook</a>
										<a class="dropdown-item"  onclick="showCopy()"><svg width="20" height="10" viewBox="0 0 20 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 0H12C11.45 0 11 0.45 11 1C11 1.55 11.45 2 12 2H15C16.65 2 18 3.35 18 5C18 6.65 16.65 8 15 8H12C11.45 8 11 8.45 11 9C11 9.55 11.45 10 12 10H15C17.76 10 20 7.76 20 5C20 2.24 17.76 0 15 0ZM6 5C6 5.55 6.45 6 7 6H13C13.55 6 14 5.55 14 5C14 4.45 13.55 4 13 4H7C6.45 4 6 4.45 6 5ZM8 8H5C3.35 8 2 6.65 2 5C2 3.35 3.35 2 5 2H8C8.55 2 9 1.55 9 1C9 0.45 8.55 0 8 0H5C2.24 0 0 2.24 0 5C0 7.76 2.24 10 5 10H8C8.55 10 9 9.55 9 9C9 8.45 8.55 8 8 8Z" fill="#5B5C5C"></path></svg>Copy Link</a>
										<a class="dropdown-item" ><svg width="21" height="17" viewBox="0 0 21 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.77289 16.7344C14.3329 16.7344 18.4541 10.3859 18.4541 4.91406C18.4541 4.72813 18.4541 4.54219 18.4541 4.38281C19.2679 3.79844 19.9504 3.05469 20.5016 2.23125C19.7666 2.55 18.9791 2.78906 18.1391 2.89531C18.9791 2.39062 19.6354 1.56719 19.9504 0.584375C19.1629 1.0625 18.2704 1.40781 17.3516 1.59375C16.5904 0.770313 15.5404 0.265625 14.3591 0.265625C12.0754 0.265625 10.2379 2.125 10.2379 4.43594C10.2379 4.75469 10.2641 5.07344 10.3429 5.39219C6.93039 5.20625 3.91164 3.55938 1.86414 1.03594C1.52289 1.64688 1.31289 2.36406 1.31289 3.10781C1.31289 4.54219 2.04789 5.81719 3.15039 6.56094C2.46789 6.53438 1.83789 6.34844 1.28664 6.02969C1.28664 6.05625 1.28664 6.05625 1.28664 6.08281C1.28664 8.10156 2.70414 9.775 4.59414 10.1469C4.25289 10.2531 3.88539 10.3062 3.51789 10.3062C3.25539 10.3062 2.99289 10.2797 2.75664 10.2266C3.28164 11.8734 4.80414 13.0688 6.58914 13.1219C5.17164 14.2375 3.41289 14.9016 1.49664 14.9016C1.15539 14.9016 0.840391 14.875 0.525391 14.8484C2.31039 16.0438 4.46289 16.7344 6.77289 16.7344Z" fill="#5B5C5C"/></svg>Twitter</a>
										<a class="dropdown-item"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM19.6 8.25L12.53 12.67C12.21 12.87 11.79 12.87 11.47 12.67L4.4 8.25C4.15 8.09 4 7.82 4 7.53C4 6.86 4.73 6.46 5.3 6.81L12 11L18.7 6.81C19.27 6.46 20 6.86 20 7.53C20 7.82 19.85 8.09 19.6 8.25Z" fill="#5B5C5C"/></svg>Email</a>
									</div>
						</li>
						@if($response->stock_category =='1')
						@if($tranparent=='Y')
						<li class="popup-dropdown background-list">
							<a id="backgroundnavbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 6.875C5.5 6.11875 6.11875 5.5 6.875 5.5H13.75C14.5063 5.5 15.125 4.88125 15.125 4.125C15.125 3.36875 14.5063 2.75 13.75 2.75H5.5C3.9875 2.75 2.75 3.9875 2.75 5.5V13.75C2.75 14.5063 3.36875 15.125 4.125 15.125C4.88125 15.125 5.5 14.5063 5.5 13.75V6.875ZM13.2138 18.5488L9.14375 23.6363C8.78625 24.09 9.1025 24.75 9.68 24.75H23.375C23.9388 24.75 24.2688 24.1038 23.925 23.65L21.175 19.9788C20.9 19.6075 20.35 19.6075 20.075 19.9788L17.8338 22.9762L14.2863 18.5488C14.0113 18.205 13.4888 18.205 13.2138 18.5488ZM23.375 11.6875C23.375 10.5463 22.4538 9.625 21.3125 9.625C20.1713 9.625 19.25 10.5463 19.25 11.6875C19.25 12.8288 20.1713 13.75 21.3125 13.75C22.4538 13.75 23.375 12.8288 23.375 11.6875ZM27.5 2.75H19.25C18.4938 2.75 17.875 3.36875 17.875 4.125C17.875 4.88125 18.4938 5.5 19.25 5.5H26.125C26.8813 5.5 27.5 6.11875 27.5 6.875V13.75C27.5 14.5063 28.1188 15.125 28.875 15.125C29.6313 15.125 30.25 14.5063 30.25 13.75V5.5C30.25 3.9875 29.0125 2.75 27.5 2.75ZM27.5 26.125C27.5 26.8813 26.8813 27.5 26.125 27.5H19.25C18.4938 27.5 17.875 28.1188 17.875 28.875C17.875 29.6313 18.4938 30.25 19.25 30.25H27.5C29.0125 30.25 30.25 29.0125 30.25 27.5V19.25C30.25 18.4938 29.6313 17.875 28.875 17.875C28.1188 17.875 27.5 18.4938 27.5 19.25V26.125ZM4.125 17.875C3.36875 17.875 2.75 18.4938 2.75 19.25V27.5C2.75 29.0125 3.9875 30.25 5.5 30.25H13.75C14.5063 30.25 15.125 29.6313 15.125 28.875C15.125 28.1188 14.5063 27.5 13.75 27.5H6.875C6.11875 27.5 5.5 26.8813 5.5 26.125V19.25C5.5 18.4938 4.88125 17.875 4.125 17.875Z" fill="#5B5C5C"/></svg></a>
							<!--
							<img class="img-icon" src="/images/wallpaper.png">
							<img class="hovr-icon" src="/images/wallpaper-c.png">-->
							<p>Background</p>
							 <div class="dropdown-menu" aria-labelledby="backgroundnavbarDropdown">
							 @foreach($background as $bgs)
										<a class="dropdown-item change-background" data-value="{{$id}}" onclick="change_background({{$bgs->bg_id}},'','onclick')">{{$bgs->background_title}}</a>
									@endforeach
									</div>
						</li>
						@endif
					@endif
						@if($response->stock_category =='2')
						<li class="popup-dropdown info-list" id="info-list"><a href="/custom"><svg width="33" height="33" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.0003 3.16675C10.2603 3.16675 3.16699 10.2601 3.16699 19.0001C3.16699 27.7401 10.2603 34.8334 19.0003 34.8334C27.7403 34.8334 34.8337 27.7401 34.8337 19.0001C34.8337 10.2601 27.7403 3.16675 19.0003 3.16675ZM20.5837 30.0834H17.417V26.9167H20.5837V30.0834ZM23.8612 17.8126L22.4362 19.2692C21.6445 20.0767 21.0745 20.8051 20.7895 21.9451C20.6628 22.4517 20.5837 23.0217 20.5837 23.7501H17.417V22.9584C17.417 22.2301 17.5437 21.5334 17.7653 20.8842C18.082 19.9659 18.6045 19.1426 19.2695 18.4776L21.2328 16.4826C21.9612 15.7859 22.3095 14.7409 22.1037 13.6326C21.8978 12.4926 21.0112 11.5267 19.9028 11.2101C18.1453 10.7192 16.5145 11.7167 15.992 13.2209C15.802 13.8067 15.3112 14.2501 14.6937 14.2501H14.2187C13.3003 14.2501 12.667 13.3634 12.9203 12.4767C13.6012 10.1492 15.5803 8.37592 18.0345 7.99592C20.4412 7.61592 22.737 8.86675 24.162 10.8459C26.0303 13.4267 25.4762 16.1976 23.8612 17.8126Z" fill="#5B5C5C"></path></svg></a><p>Request a quote</p></li>
						@endif
					</ul>
				</div>
				</div>
		</div>
	</div>
</div>


	</div>
</div>

<script>
$(document).ready(function(){
	getCredit();
})


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

$(document).ready(function () {

var zoomer = (function () {
    var img_ele = null,
      x_cursor = 0,
      y_cursor = 0,
      x_img_ele = 0,
      y_img_ele = 0,
      orig_width = document.getElementById('bigimagesize').getBoundingClientRect().width,
      orig_height = document.getElementById('bigimagesize').getBoundingClientRect().height,
      current_top = 0,
      current_left = 0,
      zoom_factor = 1.0;


    return {
        zoom: function (zoomincrement) {
			// alert(orig_width);
			// var w = window,
      // d = document,
      // e = d.documentElement,
      // g = d.getElementsByTagName('body')[0],
      // x = w.innerWidth || e.clientWidth || g.clientWidth,
      // y = w.innerHeight|| e.clientHeight|| g.clientHeight;
//alert(x);
    // var result = (y*66)/100;
	orig_width = $("#bigimagesize").width();
	orig_height = $("#bigimagesize").height();
	//alert(orig_height);
	//orig_width= (x*100)/100;
	//orig_width=1175;
	//alert(result);
            img_ele = document.getElementById('bigimagesize');
            zoom_factor = zoom_factor + zoomincrement;
			//alert(zoom_factor);
            if (zoom_factor <= 1.0)
            {
                zoom_factor = 1.0;
                img_ele.style.top =  '0px';
                img_ele.style.left = '0px';
            }
            var pre_width = img_ele.getBoundingClientRect().width, pre_height = img_ele.getBoundingClientRect().height;
            console.log('prewidth='+img_ele.getBoundingClientRect().width+'; pre_height ='+img_ele.getBoundingClientRect().height);
        //  img_ele.style.width = (pre_width * zoomincrement) + 'px';
        //  img_ele.style.height = (pre_height * zoomincrement) + 'px';
		//alert(orig_width);
            var new_width = (img_ele.getBoundingClientRect().width * zoom_factor);
            var new_heigth = (orig_height * zoom_factor);

                console.log('postwidth='+img_ele.style.width+'; postheight ='+img_ele.style.height);

            if (current_left < (orig_width - new_width))
            {
                current_left = (orig_width - new_width);
            }
            if (current_top < (orig_height - new_heigth))
            {
                current_top = (orig_height - new_heigth);
            }
            img_ele.style.left = current_left + 'px';
            img_ele.style.top = current_top + 'px';
            img_ele.style.width = new_width + 'px';
            img_ele.style.height = new_heigth + 'px';

            img_ele = null;
        },

        start_drag: function () {
          if (zoom_factor <= 1.0)
          {
             return;
          }
          img_ele = this;
          x_img_ele = window.event.clientX - document.getElementById('bigimagesize').offsetLeft;

          y_img_ele = window.event.clientY - document.getElementById('bigimagesize').offsetTop;
          console.log('img='+img_ele.toString()+'; x_img_ele='+x_img_ele+'; y_img_ele='+y_img_ele+';')
          console.log('offLeft='+document.getElementById('bigimagesize').offsetLeft+'; offTop='+document.getElementById('bigimagesize').offsetTop)
        },

        stop_drag: function () {
          if (img_ele !== null) {
            if (zoom_factor <= 1.0)
            {
              img_ele.style.left = '0px';
              img_ele.style.top =  '0px';
            }
            console.log(img_ele.style.left+' - '+img_ele.style.top);
            }
          img_ele = null;
        },

        while_drag: function () {
            if (img_ele !== null)
            {
				//alert(orig_width);
                var x_cursor = window.event.clientX;
                var y_cursor = window.event.clientY;
                var new_left = (x_cursor - x_img_ele);
                if (new_left > 0)
                {
                    new_left = 0;
                }
                if (new_left < (orig_width - img_ele.width))
                {

                    new_left = (orig_width - img_ele.width);
                }
                var new_top = ( y_cursor - y_img_ele);
                if (new_top > 0)
                {
                    new_top = 0;
                }
                if (new_top < (orig_height - img_ele.height))
                {
                    new_top = (orig_height - img_ele.height);
                }
                current_left = new_left;
                img_ele.style.left = new_left + 'px';
                current_top = new_top;
                img_ele.style.top = new_top + 'px';

                //console.log(img_ele.style.left+' - '+img_ele.style.top);
            }
        }
    };
} ());

 document.getElementById('zoomout').addEventListener('click', function() {
 $('#bigimagesize').removeAttr('style');
 $( "#zoomout" ).addClass( "hide-btn" );
 $( "#zoomin" ).removeClass( "hide-btn" );
 $("#bigimagesize").css("position", "unset");
});
document.getElementById('zoomin').addEventListener('click', function() {

  zoomer.zoom(0.60);

  $( "#zoomin" ).addClass( "hide-btn" );
   $( "#zoomout" ).removeClass( "hide-btn" );
   	$("#bigimagesize").css("position", "relative");
});


document.getElementById('bigimagesize').addEventListener('mousedown', zoomer.start_drag);
//document.getElementById('bigimagesize').addEventListener('touchstart', zoomer.start_drag);
//document.getElementById('bigimagesize').addEventListener('touchmove', zoomer.while_drag);
document.getElementById('imagepart').addEventListener('mousemove', zoomer.while_drag);
document.getElementById('imagepart').addEventListener('mouseup', zoomer.stop_drag);
document.getElementById('imagepart').addEventListener('mouseout', zoomer.stop_drag);



});


$('#loader').show();

// main image loaded ?
$('#bigimagesize').on('load', function(){
  // hide/remove the loading image
  $('#loader').hide();
});

setTimeout(function(){
 $('#loader').hide();
  }, 4000);
</script>
@include('footer')
