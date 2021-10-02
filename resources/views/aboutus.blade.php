@include('header')
<div class="mainabout-pdf">
<div  class="page_height" style="text-align:center;margin-bottom: 60px;">
<h4 class="main-heading">About</h4>
<div class="about-pdf">@if(!empty($manageabout))
<iframe src="{{$manageabout->about}}" frameborder="0" height="800px" width="620px"></iframe>
@endif
</div>
  </div>
  </div>

@include('footer')