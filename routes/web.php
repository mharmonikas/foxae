<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'FrontendController@index');
Route::get('/index1', 'FrontendController@index1');
Route::get('/css/theme.php?v=5', 'FrontendController@theme');
Route::get('/updatetitle', 'FrontendController@updatetitle');
Route::get('/getallvideo', 'MyajaxController@index');
Route::get('/getkeywords', 'MyajaxController@getkeywords');
Route::get('/getkeywordsvideo', 'MyajaxController@getkeywordsvideo');
Route::get('/getallkeywords', 'MyajaxController@getallkeywords');

//Clear Cache facade value:
Route::get('/clear-cache', function() {
	 $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
	 $exitCode = Artisan::call('config:clear');
    return '<h1>Cache facade value cleared</h1>';
});
//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});
//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});
/*******************************Home****************************************************/

Route::post('/registration', 'HomeController@submitregistrationdata');
Route::post('/login', 'HomeController@login');
Route::get('/logout', 'HomeController@logout');
Route::post('/forgot_password', 'HomeController@forgot_password');
Route::get('/reset-password/{id}', 'HomeController@resetpassword');
Route::post('/submitResetPassword', 'HomeController@submitResetPassword');
Route::post('/submitnewpassword', 'HomeController@submitnewpassword');
Route::post('/check-oldpassword', 'HomeController@check_oldpassword');
Route::get('/custom', 'HomeController@custom');
Route::get('/pricing1', 'HomeController@pricing1');
Route::get('/pricing', 'HomeController@pricing');
Route::get('/i/{seo}', 'HomeController@imageAnimation');
Route::post('/submitcustom', 'HomeController@submitcustom');
//Route::get('/test', 'FrontendController@test');
Route::get('/test', 'HomeController@download');
Route::get('/myprofile', 'HomeController@myprofile');
Route::get('/change-password', 'HomeController@changepassword');

Route::get('/fileTodownload/{id}', 'HomeController@fileTodownload');
Route::post('/download', 'HomeController@downloadData');
Route::post('/wishlist', 'HomeController@wishlistData');
Route::post('/buynow', 'HomeController@buynow');
Route::post('/buynow2', 'HomeController@buynow2');
Route::get('/checkout', 'HomeController@checkout');
Route::get('/member-download', 'HomeController@downloadlist');
Route::post('/payment', 'HomeController@payment');
Route::get('/member-plans', 'HomeController@memberplans');
Route::get('/purchase-history', 'HomeController@purchasehistory');
Route::post('/checkmail', 'HomeController@checkmail');
Route::get('/wishlist', 'HomeController@wishlist');
Route::post('/wishlist-delete', 'HomeController@deletewishlist');
Route::get('/cart', 'HomeController@cart');
Route::get('/cart2', 'TestController@cart2');
Route::get('/autorenew', 'HomeController@autorenew');
Route::post('/downloadcart', 'HomeController@downloadcart');
Route::get('/refresh_captcha', 'HomeController@refreshCaptcha')->name('refresh_captcha');
Route::post('/unsubscribe-pack', 'HomeController@pack_unsubscribe');
Route::post('/resend_email', 'HomeController@resend_email');
Route::get('/verifyaccount/{id}', 'HomeController@verifyaccount');
Route::post('/favorites', 'HomeController@favoritesData');
Route::get('/favorites', 'HomeController@favorites');
Route::post('/savetolater', 'HomeController@savetolater');
Route::post('/favorites-delete', 'HomeController@deletefavorites');
Route::post('/change-background', 'HomeController@change_background');
Route::get('/download-zip', 'HomeController@downloadZip');
Route::get('/test-function', 'HomeController@testfunction');


Route::get('/imageresize', 'CartController@imageresize');
Route::get('/updatedata', 'CartController@updatedata');


Route::get('/fileTodownload1', 'CartController@fileTodownload1');
Route::post('/chck_uncheckcart', 'CartController@chck_uncheckcart');
Route::get('/allcart_background', 'CartController@allcart_background');
Route::get('/cart-background', 'CartController@cart_background');
Route::post('/cartlogin', 'CartController@cartlogin');
Route::post('/cartregister', 'CartController@cartregister');
Route::post('/datadetail', 'HomeController@datadetail');

Route::get('/about', 'HomeController@aboutus');
Route::get('/support', 'HomeController@support');
Route::post('/contactus', 'HomeController@contactus');
Route::get('/termscondition', 'HomeController@termscondition');
Route::get('/privacypolicy', 'HomeController@privacypolicy');
Route::get('/userlicence', 'HomeController@userlicence');
Route::get('/getreceipt', 'HomeController@getreceipt');
Route::get('/demo', 'MyadminController@demo');
Route::get('/showimg/{id}/{imgs}', array('as' => 'id', 'uses'=>'HomeController@showimage1'));

Route::get('/clearcache', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});
/*******************************Home****************************************************/

/*******************************Admin****************************************************/
Route::get('/admin/', 'MyadminController@index');
Route::get('/loadingpage', 'MyadminController@loadingpage');
Route::post('/admin/', 'MyadminController@adminsubmit');
Route::get('/admin/dashboard/', 'MyadminController@dashboard');
Route::get('/admin/logout/', 'MyadminController@logout');
Route::get('/admin/forgotpassword/', 'MyadminController@forgotpassword');
Route::post('/admin/forgotpassword/', 'MyadminController@forgotpasswordsubmit');
Route::get('/admin/mastertag/', 'MyadminController@mastertag');
//Route::post('/admin/mastertag/', 'MyadminController@mastertag');
Route::get('/admin/edit/{id}', 'MyadminController@edit');
Route::post('/admin/edit/', 'MyadminController@edit');
Route::get('/admin/delete/{id}', 'MyadminController@delete');
Route::post('/admin/delete/', 'MyadminController@delete');
Route::get('/admin/exporttags', 'MyadminController@exporttags');
Route::get('/admin/exportsearchcategory', 'MyadminController@exportsearchcategory');
Route::get('/admin/changepassword/', 'MyadminController@changepassword');
Route::get('/admin/taggedvideo/', 'MyadminController@taggedvideo');
Route::get('/admin/taggedvideo1/',array('as'=>'ajax-pagination','uses'=>'MyadminController@taggedvideo1'));
Route::post('/admin/changepassword/', 'MyadminController@changepasswordsubmit');
Route::post('/admin/posttaggedvideo', 'MyadminController@posttaggedvideo');
Route::post('/admin/adddomaintovideo', 'MyadminController@adddomaintovideo');
/* Route::get('/admin/managevideosection', 'MyadminController@managevideosection'); */
Route::get('/admin/managevideosection', array('as'=>'ajax-pagination','uses'=>'MyadminController@managevideosection'));
Route::get('/admin/uploadvideo', 'MyadminController@uploadvideo');
Route::get('/admin/tagsreorder', 'MyadminController@tagsreorder');

Route::get('/admin/featured', 'MyadminController@managefeature');
Route::get('/admin/adddomains', 'MyadminController@adddomains');
Route::post('/admin/adddomains','MyadminController@insdomains');
Route::get('/admin/managedomains','MyadminController@managedomains'); //Manage  subject
Route::get('admin/updatedomains/{id}','MyadminController@adddomains');//Update Modules/
Route::post('/admin/deletedomains/delete', 'MyadminController@deletedomains');
Route::Post('/admin/status','MyadminController@changedomainsstatus');// Delete User
Route::get('/admin/manageuser', 'MyadminController@Manageuser');

Route::get('/admin/testemail', function () {
    return view('email.sendemail');
});

Route::get('/admin/managetags', 'MyadminController@managetags');
Route::post('/admin/saveuploadvideo', 'MyadminController@saveuploadvideo');
Route::get('/admin/ManageSearchCategory', 'MyadminController@ManageSearchCategory');
Route::get('/admin/ManageSearchSubCategory', 'MyadminController@ManageSearchSubCategory');
Route::post('/admin/deletehospital', 'MyadminController@Deletehospital');
Route::post('/admin/deletemastertag', 'MyadminController@Deletemastertag');
Route::post('/admin/deletesearchcategory', 'MyadminController@deletesearchcategory');
Route::post('/admin/editmastertag', 'AdmindemoController@editmastertag');
Route::post('/admin/addeditsearchcategory', 'MyadminController@addeditsearchcategory');
Route::post('/admin/addeditsearchsubcategory', 'MyadminController@addeditsearchsubcategory');
Route::post('/admin/addeditmastertags', 'MyadminController@addeditmastertags');
Route::get('/admin/managesubcategorytagstags', 'MyadminController@managesubcategorytagstags');
Route::post('/admin/addeditaddsearchtags', 'MyadminController@addeditaddsearchtags');
Route::post('/admin/deleteTagtype', 'MyadminController@deleteTagtype');
Route::get('/resize1/showimage/{id}/{siteid}/{imgs}', array('as' => 'id', 'uses'=>'MyadminController@resizeshowimage'));
Route::get('/resize2/showimage/{id}/{siteid}/{imgs}', array('as' => 'id', 'uses'=>'MyadminController@resizeshowimage2'));
Route::get('/resize1/showimage/{id}/{imgs}', array('as' => 'id', 'uses'=>'MyadminController@resizeshowimage1'));
Route::get('/resize1/cacheimage/{id}/{imgs}', array('as' => 'id', 'uses'=>'MyadminController@myadminshowimage'));
Route::get('/showimage/{id}/{siteid}/{imgs}', array('as' => 'id', 'uses'=>'MyadminController@showimage'));
Route::get('/showimage/{id}/{imgs}', array('as' => 'id', 'uses'=>'MyadminController@showimage1'));
Route::get('/myshowimage/', 'MyadminController@myshowimage');
Route::post('/admin/replacemedia', 'MyadminController@replacemedia');
Route::get('/admin/recoverpassword/{id}', array('as' => 'id', 'uses'=>'MyadminController@recoverpassword'));
Route::get('/admin/replace/{id}', array('as' => 'id', 'uses'=>'MyadminController@replace'));
Route::resource('admin/websitemanagement','WebsitemanagementController');
Route::post('/admin/watermarkupdate', 'MyadminController@watermarkupdate');
Route::post('/admin/scheduleImageCaching', 'MyadminController@scheduleImageCaching');
Route::get('/admin/refreshwatermark', 'MyadminController@refreshwatermark');
Route::get('/admin/saverefreshwatermark', 'MyadminController@saverefreshwatermark');
Route::post('/admin/themeoption', 'MyadminController@savethemeoption');
Route::get('/admin/watermarkupdateedit','MyadminController@watermarkupdateedit');
Route::get('/admin/runtime','MyadminController@runtime');
Route::get('/resizeimages','MyadminController@resizeimages');
Route::get('/waterimages','MyadminController@waterimages');
Route::post('/admin/watermarkupdateedit', 'MyadminController@savewatermarkupdateedit');
Route::post('/admin/deletewatermark','MyadminController@deletewatermark');
Route::post('/admin/deletebg','MyadminController@deletebackground');
Route::post('/admin/recoverpassword', 'MyadminController@recoverpasswordsubmit');
Route::get('/admin/changeorder', 'MyadminController@changeorder');
Route::get('/admin/removefeature', 'MyadminController@removefeature');

Route::post('/admin/markdefaultlogo', 'MyadminController@markdefaultlogo');
Route::post('/admin/addgroup', 'MyadminController@addgroup');
Route::get('admin/editvideo', 'MyadminController@editvideo');
Route::get('admin/themeoption/{id}', 'MyadminController@themeoption');
Route::get('/recreateImages', 'MyadminController@recreateImages');
Route::get('/changewatermark', 'MyadminController@changewatermark');
Route::post('/admin/delete-user', 'MyadminController@removeuser');
Route::post('/admin/change-status', 'MyadminController@changestatus');
Route::post('/admin/delete-custom', 'MyadminController@removecustom');
Route::get('/admin/managecustom', 'MyadminController@managecustom');
Route::get('/admin/managedownload/{id}', 'MyadminController@managedownload');
Route::get('/admin/managepack/{id}', 'MyadminController@managebuypack');
Route::get('/admin/managepayment/{id}', 'MyadminController@managepayment');
Route::get('/admin/siteplans/{id}', 'MyadminController@siteplans');
Route::post('/admin/siteplans/{id}', 'MyadminController@siteplans');
Route::get('/admin/addplan/{id}', 'MyadminController@addplan');
Route::get('/admin/editplan/{id}', 'MyadminController@addplan');
Route::get('/admin/deleteplan/{id}/{sid}', 'MyadminController@deleteplan');

Route::post('/admin/addplan', 'MyadminController@createplan');
Route::post('/admin/removeplan', 'MyadminController@removeplan');

Route::get('/admin/themesetting/{id}', 'MyadminController@themesetting');
Route::post('/admin/manage_credits', 'MyadminController@managecredits');
Route::post('/checkstock', 'HomeController@checkstock');

Route::get('/admin/managepages/{id}', 'MyadminController@managelegalpages');
Route::post('/admin/managefaq', 'MyadminController@managefaq');
Route::post('/admin/managedocuments', 'MyadminController@managedocuments');
Route::post('/admin/Deleteqa', 'MyadminController@Deleteqa');
Route::post('/admin/contactrespond_email', 'MyadminController@contactrespond_email');
Route::post('/admin/issue_archived', 'MyadminController@issue_archived');
Route::post('/admin/exportuserlist', 'MyadminController@exportuserlist');
Route::post('/admin/savebackground', 'MyadminController@savebackground');
Route::get('/admin/manageadminuser', 'MyadminController@manageAdminUser');
Route::get('/admin/addadmin', 'MyadminController@addadminuser');
Route::get('/admin/updateadmin/{id}', 'MyadminController@addadminuser');
Route::post('/admin/admincreate', 'MyadminController@admincreate');
Route::get('/admin/deleteadminuser/{id}', 'MyadminController@deleteadminuser');
Route::get('/admin/roles', 'MyadminController@adminroles');
Route::get('/admin/manage_api', 'MyadminController@manageapi');
Route::post('/admin/update_api', 'MyadminController@updateapi');
Route::get('/admin/addroles', 'MyadminController@addroles');
Route::get('/admin/updateroles/{id}', 'MyadminController@addroles');
Route::post('/admin/createroles', 'MyadminController@createroles');
Route::get('/admin/deleterole/{id}', 'MyadminController@deleterole');
Route::post('/admin/gettingroleinfo', 'MyadminController@gettingroleinfo');
Route::get('/admin/discountlist', 'MyadminController@discountlist');
Route::get('/admin/creatediscount', 'MyadminController@creatediscount');
Route::post('/admin/discountadd', 'MyadminController@discountadd');

Route::get('/admin/discountedit/{id}', 'MyadminController@discountedit');
Route::post('/admin/discountupdate/{id}', 'MyadminController@discountupdate');
Route::get('/admin/deletecoupon/{id}', 'MyadminController@deleteCoupon');
Route::post('/sendemail', 'MyadminController@sendEmailUser');

Route::post('/applycoupon', 'CouponController@index');
Route::post('/applycoupon/price', 'CouponController@couponPrice');
Route::get('/clearcache', function () {
    Artisan::call('optimize:clear');
});

Route::get('/setUpQueue', function () {
    Artisan::call('queue:work');
    dd('ok');
});

Route::get('/stopQueue', function () {
    Artisan::call('queue:down');
    dd('ok');
});

/*******************************Admin****************************************************/

Route::get('/testitng5', 'MyadminController@testitng');
