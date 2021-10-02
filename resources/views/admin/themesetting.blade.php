@include('admin/admin-header')
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
    background: #5b5c5c  !important;
    color: #fff !important;
}

</style>
<div class="col-md-12 mar-auto">
<div class="back-strip top-side srch-byr">
<div class="inner-top">
Theme Options
</div>
</div>
 


<div class="searchtags theme_opt_out">
<div style="padding:30px 50px 0">
<div class="col-md-12">
<div class="col-md-9" >
<div class="watemarklogos" style="width:100%">
<div class="tabwatermarks">
<a href="javascript:void(0);" class="tabwatermarkss active" id="theme" logotype="theme">Theme Color</a>
</div>
<div class="tabwatermarks">
<a href="javascript:void(0);" class="tabwatermarkss" id="homepage" logotype="homepage">Icons</a>
</div>
<!--
<div class="tabwatermarks">
<a href="javascript:void(0);" class="tabwatermarkss" id="pricing" logotype="pricing">Pricing</a>
</div>
<div class="tabwatermarks">
<a href="javascript:void(0);" class="tabwatermarkss" id="memberarea" logotype="memberarea">Member Area</a>
</div>
<div class="tabwatermarks">
<a href="javascript:void(0);" class="tabwatermarkss" id="tagcolor" logotype="tagcolor">Tag Color</a>
</div>
-->
<div class="tabwatermarks">
<a href="javascript:void(0);" class="tabwatermarkss" id="custom" logotype="custom">Custom</a>
</div>

</div></div>

</div>
</div>
 
<div class="ful-top gap-sextion theme"  id="product_container">
<div class="col-md-12">
<form  method="POST" enctype="multipart/form-data" action="/admin/themeoption">
{!! csrf_field() !!}
<input type="hidden" name="formname" value="home" />
<input type="hidden" name="IntthemeId" value="{{$allthemeoptions->IntthemeId}}" />
<input type="hidden" name="Intsiteid" value="{{$allthemeoptions->Intsiteid}}" />

 
<div class="form-group">
<label for="backgroundcolor">Primary Color:</label>
    <input type="text" name="primary_color" class="jscolor form-control" id="backgroundcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->primary_color; } ?>">
</div>
<div class="form-group">
<label for="popupcolor">On Primary Text / Icon Color:</label>
    <input type="text" name="primarytext_iconcolor"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->primarytext_iconcolor; } ?>">
</div>
<div class="form-group">
<label for="titlecolor">Secondary Color:</label>
    <input type="text" name="secondary_color"  class="jscolor form-control" id="searchbox" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->secondary_color; } ?>">
</div>
<div class="form-group">
<label for="titlecolor">On Secondary Text / Icon Color:</label>
    <input type="text" name="sectext_iconcolor"  class="jscolor form-control" id="searchbuttonicon" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->sectext_iconcolor; } ?>">
</div>

<div class="form-group">
<label for="anchorcolor">Background Color:</label>
  <input type="text" name="background_color" class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->background_color; } ?>">
</div>
<div class="form-group">
<label for="titlecolor">On Background Text / Icon Color:</label>
    <input type="text" name="bgtext_iconcolor"  class="jscolor form-control" id="searchbutton" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->bgtext_iconcolor; } ?>">
</div>


<div class="form-group">
<label for="checkboxcolor">Error / Required Color:</label>
    <input type="text" name="error_required" class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->error_required; } ?>">
</div>	
<div class="form-group">
<label for="checkboxcolor">Inactive Text Color:</label>
    <input type="text" name="inactive_text" class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->inactive_text; } ?>">
</div>	
<div class="form-group">
<label for="textcolor">Hyperlink Color:</label>
    <input type="text" name="hyperlink"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->hyperlink; } ?>">
</div>
<div class="form-group">
<label for="popupcolor">Basic Plan Color:</label>
    <input type="text" name="basic_plan_color"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->basic_plan_color; } ?>">
</div>	


<div class="form-group">
<label for="titlecolor">Standard Plan Color:</label>
    <input type="text" name="standard_plan_color"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->standard_plan_color; } ?>">
</div>
<div class="form-group">
<label for="anchorcolor">Premium Plan Color:</label>
  <input type="text" name="premium_color" class="jscolor form-control" id="pagnationanchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->premium_color; } ?>">
</div>		

<div class="form-group">
<label for="titlecolor">Deluxe Plan Color:</label>
    <input type="text" name="deluxe_plan_color"  class="jscolor form-control" id="boxshadow" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->deluxe_plan_color; } ?>">
</div>
<div class="form-group">
<label for="cartnumbercolor">On Plan Text / Icon Color:</label>
    <input type="text" name="plantext_iconcolor"  class="jscolor form-control" id="cartnumbercolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->plantext_iconcolor; } ?>">
</div>	

<div class="form-group">
<label for="popupcolor">Surface Color:</label>
    <input type="text" name="surface"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->surface; } ?>">
</div>	
<div class="form-group">
<label for="popupcolor">On Surface Text / Icon Color:</label>
    <input type="text" name="surfacetext_iconcolor"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->surfacetext_iconcolor; } ?>">
</div>	

<div class="form-group">
<label for="popupcolor">Standard Image Tier Color:</label>
    <input type="text" name="standard_tier_color"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->standard_tier_color; } ?>">
</div>

<div class="form-group">
<label for="cartnumbercolor">Premium Image Tier Color:</label>
    <input type="text" name="premium_tier_color"  class="jscolor form-control" id="cartnumbercolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->premium_tier_color; } ?>">
</div>	 
<div class="form-group">
<label for="cartnumbercolor">Deluxe Image Tier Color:</label>
    <input type="text" name="deluxe_tier_color"  class="jscolor form-control" id="cartnumbercolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->deluxe_tier_color; } ?>">
</div>	
 
<div class="form-group">
<label for="cartnumbercolor">On Primary Shadow Color:</label>
    <input type="text" name="primary_shadow_color"  class="jscolor form-control" id="cartnumbercolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->primary_shadow_color; } ?>">
</div>	
<div class="form-group">
<label for="cartnumbercolor">On Secondary Shadow Color:</label>
    <input type="text" name="secondary_shadow_color"  class="jscolor form-control" id="cartnumbercolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->secondary_shadow_color; } ?>">
</div>	
<div class="form-group">
<label for="cartnumbercolor">On Background Shadow Color:</label>
    <input type="text" name="background_shadow_color"  class="jscolor form-control" id="cartnumbercolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->background_shadow_color; } ?>">
</div>	
<div class="form-group">
<label for="cartnumbercolor">On Surface Shadow Color:</label>
    <input type="text" name="surface_shadow_color"  class="jscolor form-control" id="cartnumbercolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->surface_shadow_color; } ?>">
</div>		 
<div class="form-group">
<input type="submit" name="themereset" value="Reset to default" class="btn btn-dafualt" id="anchorcolor" onclick="return confirm('Are you sure you want reset default setting?');">
    <input type="submit" name="themesave" value="Save Setting" class="btn btn-dafualt" id="anchorcolor">
</div>
</form>	
</div>
</div>

<div class="ful-top gap-sextion homepage"  id="product_container" style="display:none">
<div class="col-md-12">
<form  method="POST" enctype="multipart/form-data" action="/admin/themeoption">
{!! csrf_field() !!}
<input type="hidden" name="formname" value="home" />
<input type="hidden" name="IntthemeId" value="{{$allthemeoptions->IntthemeId}}" />
<input type="hidden" name="Intsiteid" value="{{$allthemeoptions->Intsiteid}}" />
<input type="hidden" name="vchlogo" class="vchlogo" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->Vchthemelogo; } ?>"  id="vchlogo">
<div class="">
<label for="uploadlogo">Theme Upload Logo:</label>
    <input type="file" name="uploadlogo" class="form-control" id="uploadlogo">
</div>
<div class="uploadedimage" >
	 <img id="blah" src="/images/<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->Vchthemelogo;  } ?>" alt="your image" style="width:100%" height="100px" />
	 </div>
<div class="form-group" style="width: 49%;float: left !important;position: inherit;">
<label for="backgroundcolor">Logo Height:</label>
    <input type="text" name="height" class="form-control"  value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->height; } ?>">
</div>
<div class="form-group" style="width: 49%;float: left;margin-left: 2%;">
<label for="backgroundcolor">Logo Width:</label>
    <input type="text" name="width" class="form-control"  value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->width; } ?>">
</div>	
<input type="hidden" name="vchicon" class="vchicon" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchvideoicon; } ?>"  id="vchicon">

<div class="">
<label for="uploadlogo">Video play icon:</label>
    <input type="file" name="uploadicon" class="form-control" id="uploadicon">
</div>
<div class="uploadedimage" >
	 <img id="blah" src="/images/<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchvideoicon;} ?>" alt="your image" style="width:100%" height="100px" />
	 </div>
<input type="hidden" name="proicon" class="proicon" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchprofileicon; } ?>"  id="proicon">
 <div class="">
<label for="uploadlogo">Profile icon:</label>
    <input type="file" name="profileicon" class="form-control" id="profileicon">
</div>
<div class="uploadedimage" >
	 <img id="proicon" src="/images/<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchprofileicon;} ?>" alt="your image" style="width:100%" height="100px" />
	 </div>
	 
	 
	 <input type="hidden" name="gicon" class="gicon" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->gificon; } ?>"  id="gicon">
 <div class="">
<label for="uploadlogo">Homepage Loading GIF:</label>
    <input type="file" name="gificon" class="form-control" id="profileicon">
</div>
<div class="uploadedimage" >
	 <img id="proicon" src="/images/<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->gificon;} ?>" alt="your image" style="width:100%" height="100px" />
	 </div>
	 
	 
 <!--
<div class="form-group">
<label for="backgroundcolor">Background Color:</label>
    <input type="text" name="backgroundcolor" class="jscolor form-control" id="backgroundcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchwebsitebackgroundcolor; } ?>">
</div>
<div class="form-group">
<label for="titlecolor">Search Box Background Color:</label>
    <input type="text" name="searchbox"  class="jscolor form-control" id="searchbox" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->searchbox; } ?>">
</div>
<div class="form-group">
<label for="titlecolor">Search Button:</label>
    <input type="text" name="searchbutton"  class="jscolor form-control" id="searchbutton" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->searchbutton; } ?>">
</div>
<div class="form-group">
<label for="titlecolor">Search Button Icon:</label>
    <input type="text" name="searchbuttonicon"  class="jscolor form-control" id="searchbuttonicon" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->searchbuttonicon; } ?>">
</div>

<div class="form-group">
<label for="checkboxcolor">Checkbox Color:</label>
    <input type="text" name="checkboxcolor" class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchcheckboxcolor; } ?>">
</div>	
<div class="form-group">
<label for="checkboxcolor">Checkmark Color:</label>
    <input type="text" name="checkmakcolor" class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->checkmakcolor; } ?>">
</div>	
<div class="form-group">
<label for="textcolor">Label Color:</label>
    <input type="text" name="labelcolor"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchlabelcolor; } ?>">
</div>

<div class="form-group">
<label for="anchorcolor">Anchor Color:</label>
  <input type="text" name="anchorcolor" class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchanchorcolor; } ?>">
</div>
<div class="form-group">
<label for="anchorcolor">Pagination Anchor background Color:</label>
  <input type="text" name="pagnationanchorcolor" class="jscolor form-control" id="pagnationanchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->pagnationanchorcolor; } ?>">
</div>		

<div class="form-group" style="display:none";>
<label for="textcolor">Text Color:</label>
    <input type="text" name="textcolor" class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchtextcolor; } ?>">
</div>

<div class="form-group">
<label for="titlecolor">Title Color:</label>
    <input type="text" name="titlecolor"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchtitlecolor; } ?>">
</div>
<div class="form-group">
<label for="titlecolor">Box Shadow Color:</label>
    <input type="text" name="boxshadow"  class="jscolor form-control" id="boxshadow" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->boxshadow; } ?>">
</div>
<div class="form-group">
<label for="popupcolor">Popup Background Color:</label>
    <input type="text" name="popupcolor"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->vchpopupbackgroundcolor; } ?>">
</div>
<div class="form-group">
<label for="popupcolor">Button Color:</label>
    <input type="text" name="buttoncolor"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->buttoncolor; } ?>">
</div>	
<div class="form-group">
<label for="popupcolor">Menu Color:</label>
    <input type="text" name="menucolor"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->menucolor; } ?>">
</div>	
<div class="form-group">
<label for="popupcolor">Search Bar Text Color:</label>
    <input type="text" name="searchbartextcolor"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->searchbartextcolor; } ?>">
</div>	
<!--<div class="form-group">
<label for="popupcolor">Hover/Popup Tag Color for standard:</label>
    <input type="text" name="hovertagcolor_standard"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->tagcolor_standard; } ?>">
</div>	
<div class="form-group">
<label for="popupcolor">Hover/Popup Tag Color for premium:</label>
    <input type="text" name="hovertagcolor_premium"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->tagcolor_premium; } ?>">
</div>	
<div class="form-group">
<label for="popupcolor">Hover/Popup Tag Color for ultra premium:</label>
    <input type="text" name="hovertagcolor_ultrapremium"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->tagcolor_ultrapremium; } ?>">
</div>	-->


<!--	 
<div class="form-group">
<label for="cartnumbercolor">Shopping cart number color:</label>
    <input type="text" name="cartnumbercolor"  class="jscolor form-control" id="cartnumbercolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->cartnumbercolor; } ?>">
</div>	
<div class="form-group">
<label for="popupcolor">Shopping cart number background color:</label>
    <input type="text" name="cartnumberbgcolor"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->cartnumberbgcolor; } ?>">
</div>
<div class="form-group">
<label for="popupcolor">Shopping cart icon:</label>
    <input type="hidden" name="carticon" class="proicon" value=""  id="carticon">
	 <input type="file" name="uploadcarticon" class="form-control" id="uploadcarticon">
</div>
<div class="uploadedimage" >
	 <img id="proicon" src="/images/" alt="your image" style="width:30%" height="50%" />
	 </div>	 
	 <!--
	<div class="form-group">
<label for="popupcolor">Shopping cart active icon:</label>
    <input type="hidden" name="activecarticon" class="proicon" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->activecartlogo; } ?>"  id="carticon">
	 <input type="file" name="uploadactivecarticon" class="form-control" id="uploadactivecarticon">
</div>	
<div class="uploadedimage" >
	 <img id="proicon" src="/images/<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->activecartlogo;} ?>" alt="your image" style="width:30%" height="50%" />
	 </div>	 
	
	<div class="form-group">
<label for="cartnumbercolor">Cart and Credit Popup Text Color:</label>
    <input type="text" name="popuptextcolor"  class="jscolor form-control" id="cartnumbercolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->popuptextcolor; } ?>">
</div>	 
<div class="form-group">
<label for="cartnumbercolor">Cart and Credit Popup Box Color:</label>
    <input type="text" name="popupboxcolor"  class="jscolor form-control" id="cartnumbercolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->popupboxcolor; } ?>">
</div>	 
 --> 	 
<div class="form-group">
<input type="submit" name="resettodefault" value="Reset to default" class="btn btn-dafualt" id="anchorcolor" onclick="return confirm('Are you sure you want reset default setting?');">
    <input type="submit" name="homesave" value="Save Setting" class="btn btn-dafualt" id="anchorcolor">
</div>
</form>	
</div>
</div>
<!--
<div class="ful-top gap-sextion pricing"  id="product_container" style="display:none">
<div class="col-md-12">
<form  method="POST" enctype="multipart/form-data" name="pricing" action="/admin/themeoption">
{!! csrf_field() !!}
<input type="hidden" name="formname" value="pricing" />
<input type="hidden" name="IntthemeId" value="{{$allthemeoptions->IntthemeId}}" />
<input type="hidden" name="Intsiteid" value="{{$allthemeoptions->Intsiteid}}" />
<div class="form-group">
<label for="backgroundcolor">Price Page Background Color:</label>
    <input type="text" name="pricingbackgroundcolor" class="jscolor form-control" id="backgroundcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->pricingbackgroundcolor; } ?>">
</div>

<div class="form-group">
<label for="titlecolor">Price Page Text Color:</label>
    <input type="text" name="pricingcolor"  class="jscolor form-control" id="searchbox" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->fontcolorpricing; } ?>">
</div>
<div class="form-group">
<label for="backgroundcolor">Side Bar Background Color:</label>
    <input type="text" name="pricingpopupbgcolor" class="jscolor form-control" id="backgroundcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->pricingpopupbgcolor; } ?>">
</div>
<div class="form-group">
<label for="titlecolor">Side Bar Text Color:</label>
    <input type="text" name="pricingpopupcolor"  class="jscolor form-control" id="searchbox" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->pricingpopupcolor; } ?>">
</div>

<div class="form-group">
<input type="submit" name="pricingreset" value="Reset to default" class="btn btn-dafualt" id="anchorcolor" onclick="return confirm('Are you sure you want reset default setting?');">
    <input type="submit" name="pricingsave" value="Save Setting" class="btn btn-dafualt" id="anchorcolor">
</div>	
</div>
</form>
</div>

<div class="ful-top gap-sextion memberarea"  id="product_container" style="display:none">
<div class="col-md-12">
<form  method="POST" enctype="multipart/form-data" action="/admin/themeoption">
{!! csrf_field() !!}
<input type="hidden" name="formname" value="member" />
<input type="hidden" name="IntthemeId" value="{{$allthemeoptions->IntthemeId}}" />
<input type="hidden" name="Intsiteid" value="{{$allthemeoptions->Intsiteid}}" />
<div class="form-group">
<label for="backgroundcolor">Background Color:</label>
    <input type="text" name="memberbackgroundcolor" class="jscolor form-control" id="backgroundcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->backgroundcolormember; } ?>">
</div>

<div class="form-group">
<label for="titlecolor">Text Color:</label>
    <input type="text" name="membercolor"  class="jscolor form-control" id="searchbox" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->fontcolormember; } ?>">
</div>
<div class="form-group">
<label for="titlecolor">Border Color:</label>
    <input type="text" name="bordercolor"  class="jscolor form-control" id="searchbox" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->bordercolor; } ?>">
</div>
<div class="form-group">
<label for="backgroundcolor">Side Bar Background Color:</label>
    <input type="text" name="sidebarbackgroundcolor" class="jscolor form-control" id="backgroundcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->sidebarbackgroundcolor; } ?>">
</div>
<div class="form-group">
<label for="titlecolor">Side Bar Text Color:</label>
    <input type="text" name="sidebarcolor"  class="jscolor form-control" id="searchbox" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->sidebarcolor; } ?>">
</div>
<div class="form-group">
<label for="titlecolor">Side Bar Active Tab Color:</label>
    <input type="text" name="activecolor"  class="jscolor form-control" id="searchbox" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->activemember; } ?>">
</div>

<div class="form-group">
<input type="submit" name="resetmember" value="Reset to default" class="btn btn-dafualt" id="anchorcolor" onclick="return confirm('Are you sure you want reset default setting?');">
    <input type="submit" name="membersave" value="Save Setting" class="btn btn-dafualt" id="anchorcolor">
</div>	
</div>
</form>
</div>

<div class="ful-top gap-sextion tagcolor"  id="product_container" style="display:none">
<div class="col-md-12">
<form  method="POST" enctype="multipart/form-data" action="/admin/themeoption">
{!! csrf_field() !!}
<input type="hidden" name="formname" value="member" />
<input type="hidden" name="IntthemeId" value="{{$allthemeoptions->IntthemeId}}" />
<input type="hidden" name="Intsiteid" value="{{$allthemeoptions->Intsiteid}}" />
<div class="form-group">
<label for="popupcolor">Standard:</label>
    <input type="text" name="hovertagcolor_standard"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->tagcolor_standard; } ?>">
</div>	
<div class="form-group">
<label for="popupcolor">Premium:</label>
    <input type="text" name="hovertagcolor_premium"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->tagcolor_premium; } ?>">
</div>	
<div class="form-group">
<label for="popupcolor">Deluxe:</label>
    <input type="text" name="hovertagcolor_ultrapremium"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->tagcolor_ultrapremium; } ?>">
</div>
<div class="form-group">
<label for="popupcolor">Image mouseover color:</label>
    <input type="text" name="imagemouseover_color"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->imagemouseover_color; } ?>">
</div>	

<div class="form-group">
<label for="popupcolor">Button text color:</label>
    <input type="text" name="button_color"  class="jscolor form-control" id="anchorcolor" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->cartbutton_color; } ?>">
</div>	


<div class="form-group">
<input type="submit" name="resettagcolor" value="Reset to default" class="btn btn-dafualt" id="anchorcolor" onclick="return confirm('Are you sure you want reset default setting?');">
    <input type="submit" name="tagcolorsave" value="Save Setting" class="btn btn-dafualt" id="anchorcolor">
</div>	
</div>
</form>
</div>
-->
<div class="ful-top gap-sextion custom"  id="product_container" style="display:none">
<div class="col-md-12">
<form  method="POST" enctype="multipart/form-data" action="/admin/themeoption">
{!! csrf_field() !!}
<input type="hidden" name="formname" value="custom" />
<input type="hidden" name="IntthemeId" value="{{$allthemeoptions->IntthemeId}}" />
<input type="hidden" name="Intsiteid" value="{{$allthemeoptions->Intsiteid}}" />
<div class="form-group">
<label for="popupcolor">Video:</label>
    <input type="hidden" name="custmvideo" class="proicon" value="<?php if(!empty($allthemeoptions)) { echo $allthemeoptions->cutomvideo; } ?>"  id="customvideo">
	 <input type="file" name="customvideo" class="form-control" id="uploadcarticon">
</div>	



<div class="form-group">
<!--
<input type="submit" name="resettagcolor" value="Reset to default" class="btn btn-dafualt" id="anchorcolor" onclick="return confirm('Are you sure you want reset default setting?');">
-->
    <input type="submit" name="custompage" value="Save Setting" class="btn btn-dafualt" id="anchorcolor">
</div>	
</div>
</form>
</div>

</div>
<div class="clearfix"></div>
</div>  
</div>
</div>
<div class="space100"></div>
<script src="/js/jscolor.js"></script>
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


$(document).ready(function(){
$("#uploadlogo").change(function() {
  readURL(this);
});

$('.delete').click(function(){	
var result = confirm("Are you sure to delete this ?");
if (result) {
		 var token= $('meta[name="csrf_token"]').attr('content');
   	$.ajax({
				url:'{{ URL::to("/admin/deletewatermark") }}',
				type:'POST',
				data:{'_token':token},
				success:function(ress){
					
					

				}
			});
}	
});	
});

$('.tabwatermarkss').click(function(){
var logotype = $(this).attr('logotype');	
	$('.gap-sextion').css('display','none');
	$('.'+logotype).fadeIn();
	
});

$("#homepage").click(function(){
	 $('#memberarea').removeClass('active');
	 $('#pricing').removeClass('active');
		$("tagcolor").removeClass("active");
		$("custom").removeClass("active");
		$("#theme").removeClass("active");
      $(this).removeClass('active').addClass('active');
	  });
	  $("#memberarea").click(function(){
	 $('#homepage').removeClass('active');
	 $('#pricing').removeClass('active');
	$("#tagcolor").removeClass("active");
	$("#custom").removeClass("active");
	$("#theme").removeClass("active");
      $(this).removeClass('active').addClass('active');
	  }); 
	 $("#pricing").click(function(){
			$('#homepage').removeClass('active');
			$('#memberarea').removeClass('active');
			$("#tagcolor").removeClass("active");
			$("#custom").removeClass("active");
			$("#theme").removeClass("active");
      $(this).removeClass('active').addClass('active');
	  });
	  $("#tagcolor").click(function(){
			$('#homepage').removeClass('active');
			$('#memberarea').removeClass('active');
			$("#pricing").removeClass("active");
			$("#custom").removeClass("active");
			$("#theme").removeClass("active");
      $(this).removeClass('active').addClass('active');
	  }); 

	  $("#custom").click(function(){
			$('#homepage').removeClass('active');
			$('#memberarea').removeClass('active');
			$("#pricing").removeClass("active");
			$("#tagcolor").removeClass("active");
			$("#theme").removeClass("active");
			$(this).removeClass('active').addClass('active');
	  });  
	  
	  $("#theme").click(function(){
			$('#homepage').removeClass('active');
			$('#memberarea').removeClass('active');
			$("#pricing").removeClass("active");
			$("#tagcolor").removeClass("active");
			$("#custom").removeClass("active");
			$(this).removeClass('active').addClass('active');
	  });
</script>
@include('admin/admin-footer')