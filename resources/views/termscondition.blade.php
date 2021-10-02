@include('header')

<div class="page_height" style="text-align:center;margin-bottom: 60px;">
<h4 class="main-heading">Terms & Conditions</h4>
<div class="about-pdf">@if(!empty($manageabout))
<iframe src="{{$manageabout->termscondition}}" frameborder="0" height="800px" width="620px"></iframe>
@endif
  </div> </div>

@include('footer')