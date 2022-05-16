<?php
namespace App\Http\Controllers;
use App\Jobs\CreateWatermarkedImageJob;
use App\Jobs\UpdateDomainPreviewImagesJob;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\AdminModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Hash;
use File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as Image;
use Session;
use Mail;
use App\Admin;
use Google_Client;
use Google_Service_Drive;
use Illuminate\Support\Facades\Storage;
class MyadminController extends Controller
{
   public function __construct(AdminModel $AdminModel) {
        $this->AdminModel = $AdminModel;

    }
	public function checklogin(){
		$intAdminID = Session::get('intAdminID');
		 if(empty($intAdminID)){
				return redirect('/admin');
		}
	}
	public function index(){
		if(!app()->isLocal() && $_SERVER['SERVER_NAME'] != "dev.fox-ae.com"){
			return redirect('/');
			exit;
		}
		 $intAdminID = Session::get('intAdminID');
		 if(!empty($intAdminID)){
			  return redirect('/admin/dashboard/');
		}else{
			 return view('admin/admin-login');
		}
	}

	public function adminsubmit(Request $request)
    {
		echo  $this->checklogin();
		$this->validate($request,[
			'vchEmail'=>'required',
			'vchPassword'=>'required',
		]);

		$vchEmail = $request->vchEmail;
		$vchPassword = md5($request->vchPassword);

		$data = DB::table('tblAdminMaster')->where('vchEmail', $vchEmail)->where('vchPassword',$vchPassword)->where('enumStatus', 'A')->first();

		if(!empty($data->intAdminID)){
			if ($request->rememberme=='Y') {
					/* Set cookie to last 1 year */
					setcookie('vchEmailAdmin', $request->vchMobileNumber, time()+60*60*24*365);
					setcookie('vchPasswordAdmin',$request->vchPassword, time()+60*60*24*365);
					setcookie('remembermeAdmin','Y', time()+60*60*24*365);
				} else {
					/* Cookie expires when browser closes */
					unset($_COOKIE['vchEmailAdmin']);
					 setcookie('vchEmailAdmin', '', time() - 3600);
					unset($_COOKIE['vchPasswordAdmin']);
					 setcookie('vchPasswordAdmin', '', time() - 3600);
					unset($_COOKIE['remembermeAdmin']);
					 setcookie('remembermeAdmin', '', time() - 3600);
				}
			Session::put('intAdminID',$data->intAdminID);
			Session::put('name',$data->vchName);
			Session::put('intAdminID',$data->intAdminID);
			Session::put('vchRole',$data->vchRole);
			$res_role = $this->permissionUser();


			return redirect('/loadingpage');

		}else{
			$msg="Invalid login credentials.";
            return view('admin/admin-login',compact('msg'));
		}

    }
	public function loadingpage(){
		$notfind = $this->permissionUser();
				foreach($notfind as $find){
					if (strpos($find['role'], '2') !== false) {
						return redirect($find['url']);
					}
				}
	}
	public function logout()
    {
		 Session::flush();
         return redirect('/admin/');
    }


	public function dashboard()
	{
		echo $this->checklogin();
		//echo $this->RedirectNoPermission(1);
		$access = $this->accessPoint(1);
		return view('admin/admin-dashboard');
    }

	public function forgotpassword()

    {   echo $this->checklogin();
        return view('admin/admin-forgot-password');
    }
	public function recoverpassword($id){
		$decoded_id = base64_decode($id);
		$data = DB::table('tblAdminMaster')->where('intAdminID',$decoded_id)->first();
		$adminid = $decoded_id;
		return view('admin/admin-recoverpassword',compact('adminid'));
	}
	public function recoverpasswordsubmit(){
		$changepasswordid = $_POST['changepassword'];
		$adminid = $changepasswordid;
		$vchPassword = md5($_POST['vchPassword']);

		$data = [
			'vchPassword'=>$vchPassword
		];
		DB::table('tblAdminMaster')->where('intAdminID', $changepasswordid)->update($data);

		$msg ="Password successfully Changed";
		return view('admin/admin-recoverpassword',compact('adminid'),compact('msg'));
	}
	public function mastertag()
    {
		echo $this->checklogin();
		$data = DB::table('tbl_MasterTag')->get();
		return view('admin.adminmastertag',compact('data'));
    }
    public function Deletemastertag(Request $request){
		echo $this->checklogin();
		DB::table('tbl_MasterTag')->where('IntId', $request->id)->delete();
		echo 1;
	}

	public function deletesearchcategory(Request $request){
		echo  $this->checklogin();
		DB::table('tbl_Searchcategory')->where('IntId', $request->id)->delete();
		DB::table('tbl_Searchcategory')->where('IntParent', $request->id)->delete();

		$data = DB::table('tbl_SearchcategoryVideoRelationship')->select('*')->where('IntCategorid',$request->id)->get();
		foreach($data as $datas){
		 $dataarr[]=$datas->IntId;
		 DB::table('tbl_SearchcategoryVideoRelationship')->where('IntId',$datas->IntId)->delete();
		}
	}

	public function edit($id=''){
		echo $this->checklogin();
		$data = DB::table('tbl_MasterTag')->where('IntId',$id)->first();
		return view('admin.edit',compact('data'));
    }
	public function markdefaultlogo(){
		$check = $_POST['type'];
		$vtime = $_POST['vtime'];
		$siteid = $_POST['siteid'];

		if($check=='check'){
			$random = rand(100000000,1000000000);
			$imagetype = $_POST['imagetype'];
			$checkboxid = $_POST['checkboxid'];

			DB::table('tblwatermarklogo')->where('vchtype', '=', $imagetype)->where('vchsiteid','=', $siteid)->update(['enumstatus' =>'D']);
			DB::table('tblwatermarklogo')->where('Intwatermarklogoid', '=', $checkboxid)->where('vchsiteid','=', $siteid)->update(['enumstatus' =>'A']);
		}
		if($imagetype == 'L'){
			define('DIR_CACHE', './image_cache/'.$siteid.'/');
			$files = glob(DIR_CACHE."*");
				foreach($files as $file){ // iterate files
					if(is_file($file))
					unlink($file); // delete file
				}

		}else if($imagetype == 'V'){
			define('DIR_CACHES', './image_cache7/'.$siteid.'/');
			$files = glob(DIR_CACHES."*");
				foreach($files as $file){ // iterate files
					if(is_file($file))
					unlink($file); // delete file
				}
				$data = [
				"schedulingtime"=>$vtime,
				"status"=>'Pending'
				];
				DB::table('scheduling')->where('id', 1)->update($data);
				$Watermark = DB::table('tblwatermarklogo')->where('vchtype','V')->where('vchsiteid','=', $siteid)->where('enumstatus','A')->first();

		}else if($imagetype == 'S'){
			define('DIR_CACHESMALL', './image_cache6/'.$siteid.'/');
			$files = glob(DIR_CACHESMALL."*");
			foreach($files as $file){
				if(is_file($file))
				unlink($file); // delete file
			}
			$getvideodata = DB::table('tbl_Video')->get();
			foreach($getvideodata as $videodata){
				if($videodata->vchcacheimages != ""){
					$dataarray = array('vchcacheimages'=>'','intsetdefault'=>$random);
					DB::table('tbl_Video')->where('IntId', $videodata->IntId)->update($dataarray);
				}
			}
		}
	}
	public function delete($id=''){
		echo $this->checklogin();
		DB::table('tbl_MasterTag')->where('IntId', $id)->delete();
		return redirect('/admin/mastertag');
    }
	public function forgotpasswordsubmit(Request $request){

		$vchEmail = $request->vchEmail;
		$data = DB::table('tblAdminMaster')->where('vchEmail', $vchEmail)->first();
		if(!empty($data->intAdminID)){
			$to=$request->vchEmail;
			$subject = "Forgot Password";
			$message = "Please check below Link";
			$useridss = $data->intAdminID;
			$userid = urlencode(base64_encode($data->intAdminID));
			$message .= "Please check below Link";
			$siteurl = url('/').'/admin/recoverpassword/'.$userid;
			$message .= "Please check below Link".'<a href="'.$siteurl.'">'.'Click Here'.'</a>';
			$this->sendemail($to,$subject,$message);
			$msg="Please check your email.";

			return view('admin/admin-forgot-password',compact('msg'));
		}else{
			$msg="Please fill valid email.";
            return view('admin/admin-forgot-password',compact('msg'));
		}
	}
#FF0F0F

	public function userprofile(Request $request){
		echo  $this->checklogin();
		$msg ="";
		$userid = Session::get('intAdminID');
		if($_POST){
			$upadate = array(
			'vchName'=>$request->name,
			'vchEmail'=>$request->email,
			);
			$this->AdminModel->updateUserprofile($upadate,$userid);
			$msg =1;
			Session::put('name',$request->name);
		}
		$vchProfileImage = $request->file('profileimage');
		if($vchProfileImage!=''){
			$input['imagename'] = 'IMG_'.date('Y-m-d').'_'.time().'.'.$vchProfileImage->getClientOriginalExtension();
			$destinationPath = public_path('uploads/admin-profile/');
			$vchProfileImage->move($destinationPath, $input['imagename']);

			$updatearr = array(
				'vchImage'=>$input['imagename'],
		    );
			Session::put('image',$input['imagename']);
			$this->AdminModel->updateUserprofile($updatearr,$userid);

		}
		$profile =  $this->AdminModel->getuserprofile($userid);
		return view('admin/admin-manage-profile',compact('managebuyerlist','profile','msg'));
    }

	public function changepassword(Request $request){
		echo  $this->checklogin();
		$msg ="";
		$userid = Session::get('intAdminID');
		if($_POST){
			$upadate = array(
			'vchPassword'=>$request->Password,
			);
			$this->AdminModel->updateUserprofile($upadate,$userid);
			$msg =1;
		}
        return view('admin/admin-change-password',compact('msg'));
    }
	public function changepasswordsubmit(Request $request){
		$intBuyerSellerID = Session::get('intAdminID');
		$vchPassword = md5($request->Password);
	    $request->oldPassword;
        $vcholdpassword = md5($request->oldPassword);
		$updatearr = array('vchPassword'=>$vchPassword);
		$data = DB::table('tblAdminMaster')->where('vchPassword',$vcholdpassword)->where('intAdminID', $intBuyerSellerID)->get();
	   if(!empty($data)){
			DB::table('tblAdminMaster')->where('intAdminID', $intBuyerSellerID)->update($updatearr);
			$msg = "Password Successfully Changed";
			$errorclass="";
			return view('admin/admin-change-password',compact('msg'),compact('errorclass'));
		}else {
			$msg = "Please enter Correct Old Password";
			$errorclass="msgerror";
			return view('admin/admin-change-password',compact('msg'),compact('errorclass'));
		}
	}
	public function managepages(Request $request,$cid=''){
		echo  $this->checklogin();
		$msg = '';
		if($_POST){
			$fake = strtolower($request->pagename);
			$fakeurl = str_replace(" ","_",$fake);
			$data = array(
				'vchPagesName'=>$request->pagename,
				'vchMetaTitle'=>$request->metatitle,
				'vchFakeurl'=>$fakeurl,
				'VchMetakeyword'=>$request->metakeyword,
				'vchMetaDesc'=>$request->metadesc,
				'vchPageDesc'=>addslashes($request->pagedesc),
			);
			 if($request->pageid == ""){
				$brand = $this->AdminModel->PageInsert($data);
				$msg = "Create Page Successfully";
			}else{
				$this->AdminModel->updateCmsPages($data,$request->pageid);
				$msg = "Update Page Successfully";
			}
		}
		$cmspages = $this->AdminModel->ManageCmsPages($cid);
		return view('admin/pages/admin-manage-pages', compact('cmspages','msg'));
	}
	public function addpage($cid){
		echo  $this->checklogin();
		$cpages = $this->AdminModel->ManageSingleData($cid);
		return view('admin/pages/admin-add-page',compact('cpages'));
	}
	public function UpdateCmsStatus(Request $request){
		echo  $this->checklogin();
		$id = $request->bid;
		$update = array(
			'enumStatus'=>$request->statususer,
		);
		$response = $this->AdminModel->updateCmsPages($update,$id);
		return $response;
	}


	public function DeleteUser(Request $request){
		echo  $this->checklogin();
		$response = $this->AdminModel->DeleteData($request->id);
		return $response;
	}
	public function DeleteOther(Request $request){
		echo  $this->checklogin();
		$response = $this->AdminModel->otheroptiondelete($request->id,$request->status);
		return $response;
	}
	public function exporttags(){
		echo $this->checklogin();
		return view('admin.admin-exporttags');
	}
	public function taggedvideo1(Request $request){
		echo $this->checklogin();
		$searchtags = DB::table('tbl_Searchcategory')->select('*')->get();

		$allvideo = DB::table('tbl_Video')->select('*',DB::raw('tbl_Video.IntId as videoid'))->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId');

		$allvideorelation = array();
		$allsearchvideorelation = array();
		if(isset($_GET['searchtitle'])){
			$searchtitle = $_GET['searchtitle'];
			$allvideo = $allvideo->leftJoin('tbl_SearchcategoryVideoRelationship as parentserachingcategory', 'tbl_Video.IntId', '=', 'parentserachingcategory.IntVideoID');

			$allvideo = $allvideo->leftJoin('tbl_Searchcategory', 'parentserachingcategory.IntCategorid', '=', 'tbl_Searchcategory.IntParent');

			$allvideo = $allvideo->whereRaw("((parentserachingcategory.VchSearchcategorytitle like '%$searchtitle%')or(tbl_Video.VchTitle like '%$searchtitle%') or (tbl_Searchcategory.VchCategoryTitle like '%$searchtitle%') )");

			if(isset($_GET['filteringcategory'])){
				$filteringcategory = $_GET['filteringcategory'];
				if(isset($filteringcategory['VchCategoryTagID'])){
					$VchCategoryTagID = $filteringcategory['VchCategoryTagID'];
					if($VchCategoryTagID!=0){
						$allvideo = $allvideo->where('tbl_Videotagrelations.VchCategoryTagID', '=', $VchCategoryTagID);
					}
				}
				if(isset($filteringcategory['VchRaceTagID'])){
					$VchCategoryTagID = $filteringcategory['VchRaceTagID'];
					if($filteringcategory['VchRaceTagID']!=0){
						$allvideo = $allvideo->where('tbl_Videotagrelations.VchRaceTagID', '=', $VchCategoryTagID);
					}
				}
				if(isset($filteringcategory['VchGenderTagid'])){
					$VchCategoryTagID = $filteringcategory['VchGenderTagid'];
					$allvideo = $allvideo->where('tbl_Videotagrelations.VchGenderTagid', '=', $VchCategoryTagID);
				}
			}
		}
		$allvideo = $allvideo->orderBy('tbl_Video.IntId', 'DESC');

		if(isset($_GET['videoid'])){
			$videoid = $_GET['videoid'];
			$allvideo = $allvideo->where('IntId',$videoid);
			$allvideorelation = DB::table('tbl_Videotagrelations')->where('VchVideoId',$videoid)->select('*')->first();
			$allsearchvideorelation = DB::table('tbl_SearchcategoryVideoRelationship')->where('IntVideoID',$videoid)->select('*')->get();
		}else {

		}
		$allvideo =$allvideo->groupBy('tbl_Video.IntId')->paginate(15);
		$alltags = DB::table('tbl_MasterTag')->select('tbl_MasterTag.IntId','tbl_MasterTag.VchColumnType','tbl_MasterTag.VchTitle',DB::raw('group_concat(tbl_Tagtype.vchTitle) as tagTitle,group_concat(tbl_Tagtype.IntId) as tagid'))->join('tbl_Tagtype', 'tbl_Tagtype.VchTypeID', '=', 'tbl_MasterTag.IntId')->groupBy('tbl_Tagtype.VchTypeID')->get();
		$alldata = array("searchtags"=>$searchtags,"allvideo"=>$allvideo,'alltags'=>$alltags,'allvideorelation'=>$allvideorelation,'allsearchvideorelation'=>$allsearchvideorelation);

		if($request->ajax()) {
		   return view('admin.admin-videotagslist')->with('allvideo', $alldata);;
		}
		return view('admin.admin-videotags1')->with('allvideo', $alldata);
	}

	public function taggedvideo(Request $request){
		echo $this->checklogin();
		//echo $this->RedirectNoPermission(3);
			$access = $this->accessPoint(3);
		$VchCategoryTagID = "";
		$VchRaceTagID = "";
		$servername = $_SERVER['SERVER_NAME'];
		$selectserver = DB::table('tbl_managesite')->where('txtsiteurl',$servername)->first();
        $managesiteid = !app()->isLocal() ? $selectserver->intmanagesiteid : 1;
		$searchtitle =  $request->searchtitle;

		$VchGenderTagid =  '';
		$multisite =  $request->multisite;
		$searchtags = DB::table('tbl_Searchcategory')->select('*')->get();
		$allvideo = DB::table('tbl_Video')->select('*',DB::raw('tbl_Video.IntId as videoid'),DB::raw('(select  GROUP_CONCAT(tbl_SearchcategoryVideoRelationship.VchSearchcategorytitle) as `ColumnName` from tbl_SearchcategoryVideoRelationship where  tbl_SearchcategoryVideoRelationship.IntVideoID = tbl_Video.IntId) as group_category'))->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId');

		$allvideorelation = array();
		$allsearchvideorelation = array();
		if(isset($_GET['searchtitle'])){
			$searchtitle = $_GET['searchtitle'];
			$subcategory=array();
			$searchtagsinfo = DB::table('tbl_Searchcategory')->where('VchCategoryTitle', $searchtitle)->first();
			if(!empty($searchtagsinfo)){
				$subcategory[] = $searchtagsinfo->VchCategoryTitle;
			}

			if(!empty($searchtagsinfo) && $searchtagsinfo->IntParent==0){
				$searchtagsinfos = DB::table('tbl_Searchcategory')->where('IntParent','=',$searchtagsinfo->IntId)->get();
				foreach($searchtagsinfos as $searchtagsinfos2){
					$subcategory[] = $searchtagsinfos2->VchCategoryTitle;
				}
			}
			//exit;
			$allvideo = $allvideo->leftJoin('tbl_SearchcategoryVideoRelationship as parentserachingcategory', 'tbl_Video.IntId', '=', 'parentserachingcategory.IntVideoID');
			$allvideo = $allvideo->leftJoin('tbl_Searchcategory', 'parentserachingcategory.IntCategorid', '=', 'tbl_Searchcategory.IntParent');
			$allvideo = $allvideo->leftJoin('tbl_SearchgroupVideoRelationship', 'tbl_Video.IntId', '=', 'tbl_SearchgroupVideoRelationship.IntVideoID');
			$msearch ="";
			if(!empty($subcategory)){
				foreach($subcategory as $skey=>$svalue){
					$msearch .= " (parentserachingcategory.VchSearchcategorytitle like '%$svalue%') or ";
				}
			}
			$allvideo = $allvideo->whereRaw("($msearch (tbl_Video.VchTitle like '%$searchtitle%') or (tbl_Searchcategory.VchCategoryTitle like '%$searchtitle%') or (tbl_SearchgroupVideoRelationship.VchSearchgrouptitle like '%$searchtitle%') )");


			if(isset($_GET['filteringcategory'])){
				$filteringcategory = $_GET['filteringcategory'];
				if(isset($filteringcategory['VchCategoryTagID'])){
					$VchCategoryTagID = $filteringcategory['VchCategoryTagID'];
					if($VchCategoryTagID!=0){
						$allvideo = $allvideo->where('tbl_Videotagrelations.VchCategoryTagID','=',$VchCategoryTagID);
					}
				}
				$VchRaceTagID = "";
				if(isset($filteringcategory['VchRaceTagID'])){
					$VchRaceTagID = $filteringcategory['VchRaceTagID'];
					if($filteringcategory['VchRaceTagID']!=0){
						$allvideo = $allvideo->where('tbl_Videotagrelations.VchRaceTagID','=',$VchRaceTagID);
					}
				}

			}
			if(isset($_GET['searchfilteringcategory'])){
				$searchfilteringcategory = $_GET['searchfilteringcategory'];
				if(isset($searchfilteringcategory['VchGenderTagid'])){
					$VchGenderTagid  =  $searchfilteringcategory['VchGenderTagid'];
					$allvideo = $allvideo->where('tbl_Videotagrelations.VchGenderTagid','=',$VchGenderTagid);
				}
			}
			if(isset($_GET['multisite'])){
				$multisites = implode("|",$_GET['multisite']);
				$allvideo = $allvideo->whereRaw('vchsiteid REGEXP "[[:<:]]('.$multisites.')[[:>:]]"');
			}
		}
		$allvideo = $allvideo->orderBy('tbl_Video.IntId', 'DESC');
			if(isset($_GET['videoid'])){
				$videoid = $_GET['videoid'];
				$allvideo = $allvideo->where('IntId',$videoid);
				$allvideorelation = DB::table('tbl_Videotagrelations')->where('VchVideoId',$videoid)->select('*')->first();
				$allsearchvideorelation = DB::table('tbl_SearchcategoryVideoRelationship')->where('IntVideoID',$videoid)->select('*')->get();
			}else {

			}

			if(!empty($_GET['show'])){
				$count = DB::table('tbl_Video')->count();
				if($_GET['show']=='all'){
					$show='all';
					$showres=$count;
				}else{
					$show=$_GET['show'];
					$showres=$show;
				}
			}else{
				$show=500;
				$showres=$show;
			}


			$allvideo =$allvideo->groupBy('tbl_Video.IntId')->paginate($showres)->appends('searchtitle',$searchtitle)->appends('filteringcategory',$request->filteringcategory)->appends('show',$request->show);
			foreach($allvideo as $all){
				//
				if(!empty($all->vchsiteid)){

					$siteid = explode(",",$all->vchsiteid);
					$res = DB::table('tbl_managesite')->select(DB::raw("GROUP_CONCAT(tbl_managesite.txtsiteurl SEPARATOR ', ') as sitename"))->whereIn('intmanagesiteid',$siteid)->first();

					$all->sitename = $res->sitename;
				}else{
					$all->sitename = "";
				}

			}

			$alltags = DB::table('tbl_MasterTag')->select('tbl_MasterTag.IntId','tbl_MasterTag.VchColumnType','tbl_MasterTag.VchTitle',DB::raw('group_concat(tbl_Tagtype.vchTitle ORDER BY sorting_order Asc) as tagTitle,group_concat(tbl_Tagtype.IntId ORDER BY sorting_order Asc) as tagid'))->join('tbl_Tagtype', 'tbl_Tagtype.VchTypeID', '=', 'tbl_MasterTag.IntId')->groupBy('tbl_Tagtype.VchTypeID')->get();
			$getdomains =DB::table('tbl_managesite')->get();
			$getgrouplists = DB::table('tbl_group')->get();

			$alldata = array("searchtags"=>$searchtags,"allvideo"=>$allvideo,'alltags'=>$alltags,'allvideorelation'=>$allvideorelation,'allsearchvideorelation'=>$allsearchvideorelation,'getdomains'=>$getdomains,'getgrouplists'=>$getgrouplists,'access'=>$access);

			return view('admin.admin-videotags',compact('alldata','searchtitle','multisite','VchGenderTagid','show','VchCategoryTagID','VchRaceTagID','managesiteid'));
	}
	public function exportsearchcategory(){
		$filename = "searchtag".date("Y_M_D").".csv";
		$fp = fopen('php://output', 'w');
		$reviews = DB::table('tbl_Searchcategory as parentcategory')->leftjoin('tbl_Searchcategory as childcategory', 'parentcategory.IntParent', '=', 'childcategory.IntId')->select('parentcategory.VchCategoryTitle as VchCategoryTitle',DB::raw('group_concat(childcategory.VchCategoryTitle) as childCategoryTitle'))->groupBy('parentcategory.IntId')->get();

		$columns = array('Category Name','Child Category');

		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$file = fopen('php://output', 'w');
        fputcsv($file, $columns);
        foreach($reviews as $review) {
            fputcsv($file, array($review->VchCategoryTitle,$review->childCategoryTitle));
        }
        fclose($file);
		exit;
	}

	public function adddomaintovideo(Request $request){
		echo $this->checklogin();
		if($_POST['action']=='adddomain'){
			$selectedvideo = $_POST['selectedvideo'];
			$myvideo = explode(',',$selectedvideo);
			for($i=0;$i<count($myvideo);$i++){
				$videoid = $myvideo[$i];
				$allvideodata = DB::table('tbl_Video')->where("IntId",$videoid)->first();
				$multisitename=$request->multisitename;
				$siteid = explode(',',$allvideodata->vchsiteid);
				$sitearr=array_unique(array_merge ($multisitename,$siteid));
				$sitestr=implode(',',$sitearr);
				 DB::table('tbl_Video')->where('IntId',$videoid)->update(['vchsiteid' => $sitestr]);
			}

			return redirect('/admin/taggedvideo?msg=3');
		}
		if($_POST['action']=='removedomain'){
			$selectedvideo = $_POST['selectedvideo'];
			$myvideo = explode(',',$selectedvideo);
			for($i=0;$i<count($myvideo);$i++){
				$videoid = $myvideo[$i];
				$allvideodata = DB::table('tbl_Video')->where("IntId",$videoid)->first();
				$domain = explode(',',$allvideodata->vchsiteid);
				$multisite=$request->multisitename;
				for($k=0;$k<count($multisite);$k++){
				$siteid= $multisite[$k];
				unset( $domain[array_search($siteid, $domain )] );
				//$my_array = array_diff($domain, array($siteid));
				$domain = array_values( $domain );
				}
				$sitestr=implode(',',$domain);
				 DB::table('tbl_Video')->where('IntId',$videoid)->update(['vchsiteid' => $sitestr]);
			}

			return redirect('/admin/taggedvideo?msg=4');

		}

	}
	public function posttaggedvideo(Request $request){
		echo $this->checklogin();
		 if($_POST['action']=='addtags'){

				$videotitle = '';
				$filteringcategory = $_POST['filteringcategory'];
				$selectedvideo = $_POST['selectedvideo'];
				$myvideo = explode(',',$selectedvideo);
				for($i=0;$i<count($myvideo);$i++){
					$videoid = $myvideo[$i];
					$allvideodata = DB::table('tbl_Video')->where("IntId",$videoid)->first();
					$videotitle = $allvideodata->VchTitle;
					$VchVideoName = $allvideodata->VchVideoName;
					$videoext = pathinfo($VchVideoName, PATHINFO_EXTENSION);
					$VchVideothumbnail = $allvideodata->VchVideothumbnail;
					$thumbnailext = pathinfo($VchVideothumbnail, PATHINFO_EXTENSION);
					$VchFolderPath = $allvideodata->VchFolderPath;
					$videotype = $allvideodata->EnumType;
					if(isset($_POST['feature'])!=''){
						DB::table('tbl_Video')->where('IntId', $videoid)->update(['feature'=>'1']);
					}
					if(isset($_POST['content_category'])!=''){
					 $content_cat=$_POST['content_category'];

						DB::table('tbl_Video')->where('IntId', $videoid)->update(['content_category'=> $content_cat]);
					}

					if(isset($_POST['tags'])){
						$tagid= $_POST['tags'];
						for($j=0;$j<count($tagid);$j++){
							$mytagid = $tagid[$j];

							$searchcategory = DB::table('tbl_Searchcategory')->select('VchCategoryTitle')->where("IntId",$mytagid)->first();
							$VchCategoryTitle = $searchcategory->VchCategoryTitle;
							DB::table('tbl_SearchcategoryVideoRelationship')->insert(['IntCategorid'=>$mytagid,'VchSearchcategorytitle' =>$VchCategoryTitle,'IntVideoID'=>$videoid,'domain'=>((!empty($request->multisitename))?implode(",",$request->multisitename):"")]);
						}
					}
					if(isset($_POST['groupid'])){
						$groupid= $_POST['groupid'];
						for($n=0;$n<count($groupid);$n++){
							$mygroupid = $groupid[$n];
							$searchgroup = DB::table('tbl_group')->select('groupname')->where("intgroupid",$mygroupid)->first();
							$groupname = $searchgroup->groupname;
							DB::table('tbl_SearchgroupVideoRelationship')->insert(['Intgroupid'=>$mygroupid,'VchSearchgrouptitle' =>$groupname,'IntVideoID'=>$videoid]);

						}
					}

				if(isset($_POST['searchfilteringcategory'])){
					$videoIntId='';
					$searchfilteringcategory=$_POST['searchfilteringcategory'];
					 if(!empty($searchfilteringcategory['VchGenderTagid'])){
						  $result=$searchfilteringcategory['VchGenderTagid'];
					 }else{
						  $result=0;
					 }
					 $getvideosearch = DB::table('tbl_Videotagrelations')->select('IntId')->where("VchVideoId",$videoid)->first();
						if(!empty($getvideosearch)){
							$videoIntId = $getvideosearch->IntId;
						}else {
							$videoIntId = '';
						}
						if(!empty($videoIntId)){
							 DB::table('tbl_Videotagrelations')->where('IntId', $videoIntId)->update(['VchGenderTagid' => $result]);
						}else{
							DB::table('tbl_Videotagrelations')->insertGetId(['VchGenderTagid' =>$result,'VchVideoId'=>$videoid]);
						}
				}
				if(isset($_POST['filteringcategory'])!=''){
					foreach ($filteringcategory as $key =>$result) {
						$getvideosearch = DB::table('tbl_Videotagrelations')->select('IntId')->where("VchVideoId",$videoid)->first();
						if(!empty($getvideosearch)){
							$videoIntId = $getvideosearch->IntId;
						}else {
							$videoIntId = '';
						}
						if(!empty($result)){
							$searchcategory = DB::table('tbl_Tagtype')->select('vchTitle')->where("Intid",$result)->first();
								if(!empty($videoIntId)){
									DB::table('tbl_Videotagrelations')->where('IntId', $videoIntId)->update([$key => $result]);
								}else {
									DB::table('tbl_Videotagrelations')->insertGetId([$key =>$result,'VchVideoId'=>$videoid]);
								}
								if($key=='VchRaceTagID'){
									$videotitle .= $result;
								}
								if($key=='VchCategoryTagID'){
									$videotitle .= $result;
								}
						}else {
							$searchcategory = DB::table('tbl_Tagtype')->select('vchTitle')->where("Intid",$result)->first();
							if(!empty($videoIntId)){
								DB::table('tbl_Videotagrelations')->where('IntId', $videoIntId)->update([$key => 0]);
							}else {
								DB::table('tbl_Videotagrelations')->insertGetId([$key =>0,'VchVideoId'=>$videoid]);
							}

							if($key=='VchRaceTagID'){
								$videotitle .= $result;
							}
							if($key=='VchCategoryTagID'){
								$videotitle .= $result;
							}

						}
					}
				}

				$videoname = $videotitle.'.'.$videoext;
				if($allvideodata->EnumUploadType=='W'){
					if($videotype=='V'){
						$VchVideothumbnail1 = $videotitle.'.'.$thumbnailext;
					}else {
						$VchVideothumbnail1 = $VchVideothumbnail;
					}
					if($videotype=='I'){
						$VchVideothumbnail1 = $videoname;
					}
						DB::table('tbl_Video')->where('IntId', $videoid)->update(['Enumuploadstatus' => 'N','VchVideoName'=>$videoname,'VchVideothumbnail'=>$VchVideothumbnail1]);
						$fname = public_path().'/'.$VchFolderPath.'/'.$VchVideoName;
						if(file_exists($fname)){
							rename(public_path().'/'.$VchFolderPath.'/'.$VchVideoName, public_path().'/'.$VchFolderPath.'/'.$videoname);
						}
						if($videotype=='V'){
							$tname = public_path().'/'.$VchFolderPath.'/'.$VchVideothumbnail;
							if(file_exists($tname)){
								rename(public_path().'/'.$VchFolderPath.'/'.$VchVideothumbnail, public_path().'/'.$VchFolderPath.'/'.$VchVideothumbnail1);
							}
						}
				}

			}
			return redirect('/admin/taggedvideo?msg=1');
		}
		if($_POST['action']=='removetags'){
			$videotitle = '';
			$filteringcategory = $_POST['filteringcategory'];
			$selectedvideo = $_POST['selectedvideo'];
			$myvideo = explode(',',$selectedvideo);
			for($i=0;$i<count($myvideo);$i++){
				$videoid = $myvideo[$i];
				if(isset($_POST['tags'])){
					$tagid= $_POST['tags'];
					for($j=0;$j<count($tagid);$j++){
						$mytagid = $tagid[$j];
						$searchcategory = DB::table('tbl_SearchcategoryVideoRelationship')->where("IntVideoID",$videoid)->where("IntCategorid",$mytagid)->first();
						if($searchcategory!=''){
							$tagintid=$searchcategory->IntId;
							$results = DB::table('tbl_SearchcategoryVideoRelationship')->where('IntId', $tagintid)->delete();
						}
					}
				}

				if(isset($_POST['groupid'])){
					$groupid= $_POST['groupid'];
					for($n=0;$n<count($groupid);$n++){
							$mygroupid = $groupid[$n];
							$searchgroup = DB::table('tbl_SearchgroupVideoRelationship')->where("IntVideoID",$videoid)->where("intgroupid",$mygroupid)->first();
						if($searchgroup!=''){
							$groupintid=$searchgroup->IntId;
							$results = DB::table('tbl_SearchgroupVideoRelationship')->where('IntId', $groupintid)->delete();
						}
					}
				}

			   if(isset($_POST['searchfilteringcategory'])){
				   if(!empty($_POST['searchfilteringcategory'])){
						$searchfilteringcategory=$_POST['searchfilteringcategory'];
						$VchGenderTagid = $searchfilteringcategory['VchGenderTagid'];
						$getvideosearch = DB::table('tbl_Videotagrelations')->where("VchVideoId",$videoid)->where('VchGenderTagid',$VchGenderTagid)->first();
							if(!empty($getvideosearch)){
								$videoIntId = $getvideosearch->IntId;
								 DB::table('tbl_Videotagrelations')->where('IntId', $videoIntId)->update(['VchGenderTagid' => 0]);
							}
					}
				}

				foreach ($filteringcategory as $key =>$result) {
					$getvideosearch = DB::table('tbl_Videotagrelations')->where("VchVideoId",$videoid)->first();
					if(!empty($getvideosearch)){
						$videoIntId = $getvideosearch->IntId;
						$VchRaceTagID = $getvideosearch->VchRaceTagID;
						$VchCategoryTagID = $getvideosearch->VchCategoryTagID;
						$VchGenderTagid = $getvideosearch->VchGenderTagid;
						if($key=='VchRaceTagID' && $VchRaceTagID==$result){
							 DB::table('tbl_Videotagrelations')->where('IntId', $videoIntId)->update(['VchRaceTagID' => 0]);
						}
						if($key=='VchCategoryTagID' && $VchCategoryTagID==$result){
							DB::table('tbl_Videotagrelations')->where('IntId', $videoIntId)->update(['VchCategoryTagID' => 0]);
						}

					}
				}
			}
		 return redirect('/admin/taggedvideo?msg=2');
		}

	}
public function managevideosection(Request $request){
		echo $this->checklogin();
		$space_available = round(disk_free_space("/var/www/") / 1024 / 1024,4);
		$space_total =  round(disk_total_space("/var/www/") / 1024 / 1024,4);
		$used_space = $space_total - $space_available;
		$access = $this->accessPoint(4);
		$perpage = $request->perpage;
		$servername = $_SERVER['SERVER_NAME'];
		$selectserver = DB::table('tbl_managesite')->where('txtsiteurl',$servername)->first();
		$siteid=!app()->isLocal() ? $selectserver->intmanagesiteid : 1;
		$multisite =  $request->multisite;
			if(isset($_GET['deletevideoid'])){
				if(isset($_GET['multiple'])){

				$myvideoid = json_decode($_GET['deletevideoid']);
				foreach($myvideoid as $deleteid){
					$myvideoall = DB::table('tbl_Video')->where('IntId', $deleteid)->first();
					$myvideoallname = $myvideoall->VchVideoName;
					DB::table('tbl_Video')->where('IntId',$deleteid)->delete();
					DB::table('tbl_Videotagrelations')->where('VchVideoId', $deleteid)->delete();
					DB::table('tbl_SearchcategoryVideoRelationship')->where('IntVideoID', $_GET['deletevideoid'])->delete();

							//The name of the folder.
					$folder = public_path().'/upload/'.'videosearch/'.$deleteid;

					//Get a list of all of the file names in the folder.
					$files = glob($folder . '/*');

					//Loop through the file list.
					foreach($files as $file){
						//Make sure that this is a file and not a directory.
						if(is_file($file)){
							//Use the unlink function to delete the file.
							unlink($file);
						}
					}
				}
			}else{
				$videoid = $_GET['deletevideoid'];
				$myvideoall = DB::table('tbl_Video')->where('IntId', $_GET['deletevideoid'])->first();
				$myvideoallname = $myvideoall->VchVideoName;
				DB::table('tbl_Video')->where('IntId', $_GET['deletevideoid'])->delete();
				DB::table('tbl_Videotagrelations')->where('VchVideoId', $_GET['deletevideoid'])->delete();
				DB::table('tbl_SearchcategoryVideoRelationship')->where('IntVideoID', $_GET['deletevideoid'])->delete();

					//The name of the folder.
				$folder = public_path().'/upload/'.'videosearch/'.$videoid;
				//Get a list of all of the file names in the folder.
				$files = glob($folder . '/*');

				//Loop through the file list.
				foreach($files as $file){
					//Make sure that this is a file and not a directory.
					if(is_file($file)){
						//Use the unlink function to delete the file.
						unlink($file);
					}
				}
				//rmdir($folder);
				File::deleteDirectory($folder);
			}
		}
		$allvideo = DB::table('tbl_Video')->select('tbl_Video.IntId','tbl_Video.intsetdefault','tbl_Video.vchsiteid','tbl_Video.VchResizeimage','tbl_Video.VchTitle','tbl_Video.VchVideothumbnail','tbl_Video.VchVideoName','tbl_Video.vchgoogledrivelink','tbl_Video.EnumType','tbl_Video.EnumUploadType','tbl_Video.VchFolderPath','releationtable.VchGenderTagid',DB::raw('(select tbl_Tagtype.vchTitle from tbl_Tagtype where tbl_Tagtype.Intid = releationtable.VchGenderTagid) as Gendercategory'),DB::raw('(select tbl_Tagtype.vchTitle from tbl_Tagtype where tbl_Tagtype.Intid = releationtable.VchRaceTagID) as Racecategory'),DB::raw('(select tbl_Tagtype.vchTitle from tbl_Tagtype where tbl_Tagtype.Intid = releationtable.VchCategoryTagID) as category'),DB::raw('group_concat(tbl_SearchcategoryVideoRelationship.VchSearchcategorytitle) as VchSearchcategorytitle'),DB::raw('(select  GROUP_CONCAT(tbl_SearchcategoryVideoRelationship.VchSearchcategorytitle) as `ColumnName` from tbl_SearchcategoryVideoRelationship where  tbl_SearchcategoryVideoRelationship.IntVideoID = tbl_Video.IntId) as group_category'))->leftjoin('tbl_Videotagrelations as releationtable', 'releationtable.VchVideoId', '=', 'tbl_Video.IntId')->leftjoin('tbl_SearchcategoryVideoRelationship', 'tbl_SearchcategoryVideoRelationship.IntVideoID', '=', 'tbl_Video.IntId')->leftJoin('tbl_Searchcategory', 'tbl_SearchcategoryVideoRelationship.IntCategorid', '=', 'tbl_Searchcategory.IntParent')->leftJoin('tbl_SearchgroupVideoRelationship', 'tbl_SearchgroupVideoRelationship.IntVideoID', '=', 'tbl_Video.IntId');

		$searchtitle = $request->searchtitle;

	 if(!empty($searchtitle)){
			$searchtagsinfo = DB::table('tbl_Searchcategory')->where('VchCategoryTitle', $searchtitle)->first();
			if(!empty($searchtagsinfo)){
				$subcategory[] = $searchtagsinfo->VchCategoryTitle;
			}

			if(!empty($searchtagsinfo) && $searchtagsinfo->IntParent==0){
				$searchtagsinfos = DB::table('tbl_Searchcategory')->where('IntParent','=',$searchtagsinfo->IntId)->get();
				foreach($searchtagsinfos as $searchtagsinfos2){
					$subcategory[] = $searchtagsinfos2->VchCategoryTitle;
				}
			}
				$msearch ="";
				if(!empty($subcategory)){
					foreach($subcategory as $skey=>$svalue){
						$msearch .= " (tbl_SearchcategoryVideoRelationship.VchSearchcategorytitle like '%$svalue%') or ";
					}
				}
				$allvideo = $allvideo->whereRaw("($msearch (tbl_Video.VchTitle like '%$searchtitle%') or(tbl_Searchcategory.VchCategoryTitle like '%$searchtitle%') or (tbl_SearchgroupVideoRelationship.VchSearchgrouptitle like '%$searchtitle%') )");
	  }


		if(isset($_REQUEST['filteringcategory'])){
			$filteringcategory = $_REQUEST['filteringcategory'];
			if(isset($filteringcategory['VchCategoryTagID'])){
				$VchCategoryTagID = $filteringcategory['VchCategoryTagID'];
				if($VchCategoryTagID!=0){
					$allvideo = $allvideo->where('releationtable.VchCategoryTagID','=',$VchCategoryTagID);
				}
			}

			if(isset($filteringcategory['VchRaceTagID'])){
				$VchCategoryTagID = $filteringcategory['VchRaceTagID'];
				if($filteringcategory['VchRaceTagID']!=0){
					$allvideo = $allvideo->where('releationtable.VchRaceTagID','=',$VchCategoryTagID);
				}
			}
			if(isset($filteringcategory['VchGenderTagid'])){
			  $VchCategoryTagID = $filteringcategory['VchGenderTagid'];
			  $allvideo = $allvideo->where('releationtable.VchGenderTagid','=',$VchCategoryTagID);
			}
		}

		if(isset($_GET['multisite'])){
			$multisites = implode("|",$_GET['multisite']);
			$allvideo = $allvideo->whereRaw('vchsiteid REGEXP "[[:<:]]('.$multisites.')[[:>:]]"');
		}
		if(empty($perpage)){
			$perpage = 500;
		}else{
			$perpage = $perpage;
		}
		$allvideo = $allvideo->orderBy('tbl_Video.IntId', 'desc')->groupBy('tbl_Video.IntId')->paginate($perpage)->appends('perpage',$perpage)->appends('searchtitle',$searchtitle)->appends('multisite',$multisite)->appends('filteringcategory',$request->filteringcategory);

		foreach($allvideo as $all){
			if(!empty($all->vchsiteid)){
				$siteid = explode(",",$all->vchsiteid);
				$res = DB::table('tbl_managesite')->select(DB::raw("GROUP_CONCAT(tbl_managesite.txtsiteurl SEPARATOR ', ') as sitename"))->whereIn('intmanagesiteid',$siteid)->first();

				$all->sitename = $res->sitename;
			}else{
				$all->sitename = "";
			}
		}

		$searchtags = DB::table('tbl_Searchcategory')->select('*')->get();
		$alltags = DB::table('tbl_MasterTag')->select('tbl_MasterTag.IntId','tbl_MasterTag.VchColumnType','tbl_MasterTag.VchTitle',DB::raw('group_concat(tbl_Tagtype.vchTitle ORDER BY sorting_order Asc ) as tagTitle,group_concat(tbl_Tagtype.IntId ORDER BY sorting_order Asc ) as tagid'))->join('tbl_Tagtype', 'tbl_Tagtype.VchTypeID', '=', 'tbl_MasterTag.IntId')->groupBy('tbl_Tagtype.VchTypeID')->get();

		$allvideorelation = array();
		$allsearchvideorelation = array();
		$getmanagevideodomains =DB::table('tbl_managesite')->select('*')->get();

		$alldata = array("searchtags"=>$searchtags,"allvideo"=>$allvideo,'alltags'=>$alltags,'allvideorelation'=>$allvideorelation,'allsearchvideorelation'=>$allsearchvideorelation,'getmanagevideodomains'=>$getmanagevideodomains,'multisite'=>$multisite,'selectserver'=>$selectserver,'access'=>$access,'space_available'=>$space_available,'space_total'=>$space_total,'used_space'=>$used_space);
		if ($request->ajax()) {
		   return view('admin.admin-managevideo',compact('perpage'))->with('allvideo', $alldata);
		}
		return view('admin.admin-managevideosection',compact('perpage'))->with('allvideo', $alldata);
    }

		public function uploadvideo(){
			echo $this->checklogin();
			//echo ($this->RedirectNoPermission(2));

			$access = $this->accessPoint(2);
			$managesites = DB::table('tbl_managesite')->select('*')->get();
			$searchtags = DB::table('tbl_Searchcategory')->select('*')->get();
			$allvideo = DB::table('tbl_Video')->select('*')->orderBy('IntId', 'DESC');
			$stocktype = DB::table('tblstocktype')->get();
			// print_r($stocktype);
			// exit;
			$allvideorelation = array();
			$allsearchvideorelation = array();
			$alltags = DB::table('tbl_MasterTag')->select('tbl_MasterTag.IntId','tbl_MasterTag.VchColumnType','tbl_MasterTag.VchTitle',DB::raw('group_concat(tbl_Tagtype.vchTitle ORDER BY sorting_order Asc) as tagTitle,group_concat(tbl_Tagtype.IntId ORDER BY sorting_order Asc) as tagid'))->join('tbl_Tagtype', 'tbl_Tagtype.VchTypeID', '=', 'tbl_MasterTag.IntId')->groupBy('tbl_Tagtype.VchTypeID')->get();

			$alldata = array("searchtags"=>$searchtags,"allvideo"=>$allvideo,'alltags'=>$alltags,'allvideorelation'=>$allvideorelation,'allsearchvideorelation'=>$allsearchvideorelation,'managesites'=>$managesites,'stocktype'=>$stocktype,'access'=>$access);
		return view('admin.admin-uploadvideo')->with('allvideo', $alldata);
	}
	public function managetags(){
		 echo $this->checklogin();
		$allmastertags = DB::table('tbl_MasterTag')->get();
		return view('admin.admin-managetags')->with('allmastertags', $allmastertags);
	}
public function saveuploadvideo(Request $request){

	echo $this->checklogin();

    $msite = explode(',', $_POST['multisite']);

    $servername = $_SERVER['SERVER_NAME'];
    $selectserver = DB::table('tbl_managesite')->where('txtsiteurl',$servername)->first();


    //$vchvideotitle = $_POST['vchvideotitle'];

    if(!empty($_POST['multisite'])){
    $multisite = $_POST['multisite'];
    }
    if(isset($_POST['uploadtype'])){
    if($_POST['uploadtype']=='G'){
        if(!empty($_POST['googlelink'])){
        $googlelink = $_POST['googlelink'];
        $googlelink2 = $_POST['googlelink'];
        $mygoogledrivevideo = explode(PHP_EOL,$googlelink);

        for($i=0;$i<count($mygoogledrivevideo);$i++){
         $googlelink = $mygoogledrivevideo[$i];
         $getcontent =  file_get_contents($mygoogledrivevideo[$i]);

    preg_match_all('~<\s*meta\s+property="(og:image+)"\s+content="([^"]*)~i', $getcontent, $matches1);
    preg_match_all('~<\s*meta\s+property="(og:title+)"\s+content="([^"]*)~i', $getcontent, $matches2);

    $myvchvideotitle = $matches2[2][0];
    $videoext =explode('.',$myvchvideotitle);
    if(isset($matches1[2][0])){
    $imagelink = $matches1[2][0];
    }else {
        $imagelink = '';
        }


         if(!empty($mygoogledrivevideo[$i])){
        if(isset($_POST['videoid'])){

        $videoIntId = $_POST['videoid'];
          $vchvideotitle= $videoext[0];
          $vchvideotitle = $_POST['vchvideotitle'];


            $filenamechange = str_replace(" ","",$matches2[2][0]);
            putenv('GOOGLE_APPLICATION_CREDENTIALS='.public_path().'/Video Search-2ecb22ecfe7d.json');
            $destinationPath = '/var/www/vhosts/fox-ae.com/dev.fox-ae.com/public/upload/videosearch/'.$videoIntId.'/';
            File::makeDirectory($destinationPath, $mode = 0777, true, true);

            $client = new Google_Client();
            $client->addScope(Google_Service_Drive::DRIVE);
            $client->useApplicationDefaultCredentials();
            $service = new Google_Service_Drive($client);
            $videoexplode =  explode('/',$googlelink2);
            $fileId = $videoexplode[5];
            $response = $service->files->get($fileId, array('alt' => 'media'));
            $content = $response->getBody()->getContents();
            file_put_contents($destinationPath.$filenamechange,$content);

                if (file_exists(public_path().'/upload/videosearch/'.$videoIntId.'/'.$selectserver->intmanagesiteid.'/watermark.mp4')){
                    $file_to_delete = public_path().'/upload/videosearch/'.$videoIntId.'/'.$selectserver->intmanagesiteid.'/watermark.mp4';
                    unlink($file_to_delete);
                }

            //$vchvideotitle= $videoext[0];
            $dataupdate = [
                'VchTitle'=>$vchvideotitle,
                'VchVideoName'=>$filenamechange,
                'vchorginalfile'=>$filenamechange,
                'VchFolderPath' => 'upload/videosearch/'.$videoIntId,
                'vchgoogledrivelink' => '',
                "EnumUploadType"=>'G',
                "EnumType"=>'V',
                'VchVideothumbnail'=>$imagelink
            ];

            //['VchTitle'=>$vchvideotitle,'vchgoogledrivelink' => $googlelink,"EnumUploadType"=>'G','VchVideothumbnail'=>$imagelink]
          DB::table('tbl_Video')->where('IntId', $videoIntId)->update($dataupdate);

        $Watermark = DB::table('tblwatermarklogo')->where('vchtype','V')->where('enumstatus','A')->where('vchsiteid',$selectserver->intmanagesiteid)->first();
        $watermarklogo = public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname;

        shell_exec('ffmpeg -i upload/videosearch/'.$videoIntId.'/'.$filenamechange.' -i '.$watermarklogo.' -filter_complex  "overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2" -codec:a copy upload/videosearch/'.$videoIntId.'/'.$selectserver->intmanagesiteid.'/watermark.mp4');

             $lastinsertid =$_POST['videoid'];


        }else {

        $filenamechange = str_replace(" ","",$matches2[2][0]);
        $vchvideotitle= $videoext[0];
        //'VchFolderPath' => 'upload/videosearch/'.$videoIntId,
        $dataupdate = [
                'VchTitle'=>$vchvideotitle,
                'VchVideoName'=>$filenamechange,
                'vchorginalfile'=>$filenamechange,
                'vchgoogledrivelink' => '',
                "EnumUploadType"=>'G',
                "EnumType"=>'V',
                'VchVideothumbnail'=>$imagelink,
                'vchsiteid'=>((!empty($multisite))?implode(",",$multisite):"")
            ];

        //['VchTitle'=>$vchvideotitle,'vchgoogledrivelink' => $googlelink,"EnumUploadType"=>'G','VchVideothumbnail'=>$imagelink,'vchsiteid'=>$multisite]
        $lastinsertid = DB::table('tbl_Video')->insertGetId($dataupdate);


            putenv('GOOGLE_APPLICATION_CREDENTIALS='.public_path().'/Video Search-2ecb22ecfe7d.json');
            $destinationPath = '/var/www/vhosts/fox-ae.com/dev.fox-ae.com/public/upload/videosearch/'.$lastinsertid.'/';
            File::makeDirectory($destinationPath, $mode = 0777, true, true);

            $client = new Google_Client();
            $client->addScope(Google_Service_Drive::DRIVE);
            $client->useApplicationDefaultCredentials();
            $service = new Google_Service_Drive($client);
            $videoexplode =  explode('/',$googlelink2);
            $fileId = $videoexplode[5];
            $response = $service->files->get($fileId, array('alt' => 'media'));
            $content = $response->getBody()->getContents();
            file_put_contents($destinationPath.$filenamechange,$content);
            $servername = $_SERVER['SERVER_NAME'];
            $selectserver = DB::table('tbl_managesite')->where('txtsiteurl',$servername)->first();

            $Watermark = DB::table('tblwatermarklogo')->where('vchtype','V')->where('enumstatus','A')->where('vchsiteid',$selectserver->intmanagesiteid)->first();
            $watermarklogo = public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname;
            shell_exec('ffmpeg -i upload/videosearch/'.$lastinsertid.'/'.$filenamechange.' -i '.$watermarklogo.' -filter_complex  "overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2" -codec:a copy upload/videosearch/'.$lastinsertid.'/'.$selectserver->intmanagesiteid.'/watermark.mp4');

            $updatefolderpath = [
                'VchFolderPath' => 'upload/videosearch/'.$lastinsertid,
            ];
            DB::table('tbl_Video')->where('IntId', $lastinsertid)->update($updatefolderpath);
        }

            if(!empty($_FILES["file"]['name'])){

            //get provided file information
          $fileName= $_FILES["file"]['name'];
         $fileExtArr  = explode('.',$fileName);//make array of file.name.ext as    array(file,name,ext)
            $fileExt     = strtolower(end($fileExtArr));//get last item of array of user file input
            $fileSize    = $_FILES["file"]['size'];
            $fileTmp     = $_FILES["file"]['tmp_name'];

            $path1 = 'upload/'.'videosearch/'.$lastinsertid.'/'.$fileName;
            if(empty($errors)){
                 move_uploaded_file($fileTmp, $path1);

             DB::table('tbl_Video')->where('IntId', $lastinsertid)->update(['Vchcustomthumbnail'=> $fileName]);

            }


    }
        $filteringcategory = $_POST['filteringcategory'];
        $videoid = $lastinsertid;
        $allvideodata = DB::table('tbl_Video')->where("IntId",$videoid)->first();
        $videotitle = $allvideodata->VchTitle;
        $VchVideoName = $allvideodata->VchVideoName;
        $videoext = pathinfo($VchVideoName, PATHINFO_EXTENSION);
        $VchVideothumbnail = $allvideodata->VchVideothumbnail;
        $thumbnailext = pathinfo($VchVideothumbnail, PATHINFO_EXTENSION);
        $VchFolderPath = $allvideodata->VchFolderPath;
        $videotype = $allvideodata->EnumType;
        if(isset($_POST['tags'])){
        DB::table('tbl_SearchcategoryVideoRelationship')->where('IntVideoID', $videoid)->delete();
        $tagid= $_POST['tags'];
        for($j=0;$j<count($tagid);$j++){
        $mytagid = $tagid[$j];

            $checkexit = DB::table('tbl_Searchcategory')->where("VchCategoryTitle",$tagid[$j])->orWhere("IntId",$tagid[$j])->first();
            if(empty($checkexit)){
                DB::table('tbl_Searchcategory')->insert(
                     array(
                            'VchCategoryTitle'=>$tagid[$j],
                            'VchDescripation'=> "",
                            'IntParent'=> 0
                     )
                );
                $tagnewid = DB::getPdo()->lastInsertId();
            }else{
                $tagnewid = $checkexit->IntId;
            }


        $searchcategory = DB::table('tbl_Searchcategory')->select('VchCategoryTitle')->where("IntId",$tagnewid)->first();
        $VchCategoryTitle = $searchcategory->VchCategoryTitle;
        DB::table('tbl_SearchcategoryVideoRelationship')->insert(['IntCategorid'=>$tagnewid,'VchSearchcategorytitle' =>$VchCategoryTitle,'IntVideoID'=>$videoid]);

        }
        }

       if(isset($filteringcategory['VchGenderTagid'])){
        $videotitle .= "_".$filteringcategory['VchGenderTagid'];

        }else {
            $videotitle .= "_".'0';

        }

     foreach ($filteringcategory as $key =>$result) {


        $getvideosearch = DB::table('tbl_Videotagrelations')->select('IntId')->where("VchVideoId",$videoid)->first();
        if(!empty($getvideosearch)){
            $videoIntId = $getvideosearch->IntId;
        }else {
            $videoIntId = '';
        }
            if(!empty($result)){
            $searchcategory = DB::table('tbl_Tagtype')->select('vchTitle')->where("Intid",$result)->first();
            //$videotitle .= $searchcategory->VchCategoryTitle."_";
            //$videotitle .=$searchcategory->vchTitle;
           if(!empty($videoIntId)){
             DB::table('tbl_Videotagrelations')->where('IntId', $videoIntId)->update([$key => $result]);
         }else {
           DB::table('tbl_Videotagrelations')->insertGetId([$key =>$result,'VchVideoId'=>$videoid]);
        }

         if($key=='VchRaceTagID'){
        $videotitle .= "R".$result;
        }
       if($key=='VchCategoryTagID'){
        $videotitle .= "C".$result;
        }
        }else {
        $searchcategory = DB::table('tbl_Tagtype')->select('vchTitle')->where("Intid",$result)->first();
            //$videotitle .= $searchcategory->VchCategoryTitle."_";
            //$videotitle .=$searchcategory->vchTitle;
           if(!empty($videoIntId)){
             DB::table('tbl_Videotagrelations')->where('IntId', $videoIntId)->update([$key => 0]);
         }else {
           DB::table('tbl_Videotagrelations')->insertGetId([$key =>0,'VchVideoId'=>$videoid]);
        }

         if($key=='VchRaceTagID'){
        $videotitle .= "R".$result;
        }
       if($key=='VchCategoryTagID'){
        $videotitle .= "C".$result;
        }

            }
        }
        $videoname = $videotitle.'.'.$videoext;
        }
        }
    }else{

        $videoIntId = $_POST['videoid'];
        $vchvideotitle = $_POST['vchvideotitle'];

            $dataupdate = [
                'VchTitle'=>$vchvideotitle,
                'vchgoogledrivelink' => '',
                "EnumUploadType"=>'G',
                "EnumType"=>'V',

            ];

            //['VchTitle'=>$vchvideotitle,'vchgoogledrivelink' => $googlelink,"EnumUploadType"=>'G','VchVideothumbnail'=>$imagelink]
          DB::table('tbl_Video')->where('IntId', $videoIntId)->update($dataupdate);

            if(!empty($_FILES["file"]['name'])){
            //get provided file information
          $fileName= $_FILES["file"]['name'];
         $fileExtArr  = explode('.',$fileName);//make array of file.name.ext as    array(file,name,ext)
            $fileExt     = strtolower(end($fileExtArr));//get last item of array of user file input
            $fileSize    = $_FILES["file"]['size'];
            $fileTmp     = $_FILES["file"]['tmp_name'];

            $path1 = 'upload/'.'videosearch/'.$videoIntId.'/'.$fileName;
            if(empty($errors)){
                 move_uploaded_file($fileTmp, $path1);

             DB::table('tbl_Video')->where('IntId', $videoIntId)->update(['Vchcustomthumbnail'=> $fileName]);
             $lastinsertid=$videoIntId;
            }


    }

    }

    }else {
     if(isset($_POST['videoida'])){

        //DB::table('tbl_Video')->where('IntId', $_POST['videoida'])->update(['VchTitle'=>$_POST['vchvideotitle']]);
        $videoid = $_POST['videoida'];
        $lastinsertid = $_POST['videoida'];
        $filteringcategory = $_POST['filteringcategory'];


        //$vchvideotitle= $_POST['vchvideotitle'];
          //DB::table('tbl_Video')->where('IntId', $videoid)->update(['VchTitle'=>$vchvideotitle]);

        $allvideodata = DB::table('tbl_Video')->where("IntId",$videoid)->first();
        $videotitle = $allvideodata->VchTitle;
        $VchVideoName = str_replace(' ', '', $allvideodata->VchVideoName);
        $videoext = pathinfo($VchVideoName, PATHINFO_EXTENSION);
        $VchVideothumbnail = $allvideodata->VchVideothumbnail;
        $thumbnailext = pathinfo($VchVideothumbnail, PATHINFO_EXTENSION);
        $VchFolderPath = $allvideodata->VchFolderPath;
        $videotype = $allvideodata->EnumType;
        DB::table('tbl_SearchcategoryVideoRelationship')->where('IntVideoID', '=', $videoid)->delete();
        if(isset($_POST['tags'])){
        $tagid= $_POST['tags'];
        for($j=0;$j<count($tagid);$j++){
        $mytagid = $tagid[$j];

        $checkexit = DB::table('tbl_Searchcategory')->where("VchCategoryTitle",$tagid[$j])->orWhere("IntId",$tagid[$j])->first();
            if(empty($checkexit)){
                DB::table('tbl_Searchcategory')->insert(
                     array(
                            'VchCategoryTitle'=>$tagid[$j],
                            'VchDescripation'=> "",
                            'IntParent'=> 0
                     )
                );
                $tagnewid = DB::getPdo()->lastInsertId();
            }else{
                $tagnewid = $checkexit->IntId;
            }
        $searchcategory = DB::table('tbl_Searchcategory')->select('VchCategoryTitle')->where("IntId",$tagnewid)->first();
        $VchCategoryTitle = $searchcategory->VchCategoryTitle;
        DB::table('tbl_SearchcategoryVideoRelationship')->insert(['IntCategorid'=>$tagnewid,'VchSearchcategorytitle' =>$VchCategoryTitle,'IntVideoID'=>$videoid]);

        }
        }

       if(isset($filteringcategory['VchGenderTagid'])){
        $videotitle .= "_".$filteringcategory['VchGenderTagid'];

        }else {
            $videotitle .= "_".'0';

        }

     foreach ($filteringcategory as $key =>$result) {


        $getvideosearch = DB::table('tbl_Videotagrelations')->select('IntId')->where("VchVideoId",$videoid)->first();
        if(!empty($getvideosearch)){
            $videoIntId = $getvideosearch->IntId;
        }else {
            $videoIntId = '';
        }
            if(!empty($result)){
            $searchcategory = DB::table('tbl_Tagtype')->select('vchTitle')->where("Intid",$result)->first();
            //$videotitle .= $searchcategory->VchCategoryTitle."_";
            //$videotitle .=$searchcategory->vchTitle;
           if(!empty($videoIntId)){
             DB::table('tbl_Videotagrelations')->where('IntId', $videoIntId)->update([$key => $result]);
         }else {
           DB::table('tbl_Videotagrelations')->insertGetId([$key =>$result,'VchVideoId'=>$videoid]);
        }

         if($key=='VchRaceTagID'){
        $videotitle .= "R".$result;
        }
       if($key=='VchCategoryTagID'){
        $videotitle .= "C".$result;
        }
        }else {
        $searchcategory = DB::table('tbl_Tagtype')->select('vchTitle')->where("Intid",$result)->first();
            //$videotitle .= $searchcategory->VchCategoryTitle."_";
            //$videotitle .=$searchcategory->vchTitle;
           if(!empty($videoIntId)){
             DB::table('tbl_Videotagrelations')->where('IntId', $videoIntId)->update([$key => 0]);
         }else {
           DB::table('tbl_Videotagrelations')->insertGetId([$key =>0,'VchVideoId'=>$videoid]);
        }

         if($key=='VchRaceTagID'){
        $videotitle .= "R".$result;
        }
       if($key=='VchCategoryTagID'){
        $videotitle .= "C".$result;
        }
        }
        }
        $videoname = $videotitle.'.'.$videoext;
        if($videotype=='V'){
        $VchVideothumbnail1 = $videotitle.'.'.$thumbnailext;

        }else {

            $VchVideothumbnail1 = $VchVideothumbnail;

        }
    }
    }
    }

    if(!empty($_POST['videoida'])){

        $videoid = $_POST['videoida'];
        $allvideodata = DB::table('tbl_Video')->where("IntId",$videoid)->first();
        $videotitle = $allvideodata->VchTitle;
        $VchVideoName = str_replace(' ', '', $allvideodata->VchVideoName);
        $videoext = pathinfo($VchVideoName, PATHINFO_EXTENSION);
        $VchVideothumbnail = $allvideodata->VchVideothumbnail;
        $thumbnailext = pathinfo($VchVideothumbnail, PATHINFO_EXTENSION);
        $VchFolderPath = $allvideodata->VchFolderPath;
        $videotype = $allvideodata->EnumType;
         $filteringcategory = $_POST['filteringcategory'];
        DB::table('tbl_SearchcategoryVideoRelationship')->where('IntVideoID', '=', $videoid)->delete();
        if(isset($_POST['tags'])){
        $tagid= $_POST['tags'];
        for($j=0;$j<count($tagid);$j++){
        $mytagid = $tagid[$j];

        $checkexit = DB::table('tbl_Searchcategory')->where("VchCategoryTitle",$tagid[$j])->orWhere("IntId",$tagid[$j])->first();
            if(empty($checkexit)){
                DB::table('tbl_Searchcategory')->insert(
                     array(
                            'VchCategoryTitle'=>$tagid[$j],
                            'VchDescripation'=> "",
                            'IntParent'=> 0
                     )
                );
                $tagnewid = DB::getPdo()->lastInsertId();
            }else{
                $tagnewid = $checkexit->IntId;
            }

        $searchcategory = DB::table('tbl_Searchcategory')->select('VchCategoryTitle')->where("IntId",$tagnewid)->first();
        $VchCategoryTitle = $searchcategory->VchCategoryTitle;
        /* $videotitle .= $searchcategory->VchCategoryTitle."_"; */
        DB::table('tbl_SearchcategoryVideoRelationship')->insert(['IntCategorid'=>$tagnewid,'VchSearchcategorytitle' =>$VchCategoryTitle,'IntVideoID'=>$videoid]);

        }
        }

       if(isset($filteringcategory['VchGenderTagid'])){

        $videotitle .= "_".$filteringcategory['VchGenderTagid'];

        }else {
            $videotitle .= "_".'0';

        }

     foreach ($filteringcategory as $key =>$result) {


        $getvideosearch = DB::table('tbl_Videotagrelations')->select('IntId')->where("VchVideoId",$videoid)->first();
        if(!empty($getvideosearch)){
            $videoIntId = $getvideosearch->IntId;
        }else {
            $videoIntId = '';
        }
            if(!empty($result)){
            $searchcategory = DB::table('tbl_Tagtype')->select('vchTitle')->where("Intid",$result)->first();
            //$videotitle .= $searchcategory->VchCategoryTitle."_";
            //$videotitle .=$searchcategory->vchTitle;
           if(!empty($videoIntId)){
             DB::table('tbl_Videotagrelations')->where('IntId', $videoIntId)->update([$key => $result]);
         }else {
           DB::table('tbl_Videotagrelations')->insertGetId([$key =>$result,'VchVideoId'=>$videoid]);
        }

         if($key=='VchRaceTagID'){
        $videotitle .= "R".$result;
        }
       if($key=='VchCategoryTagID'){
        $videotitle .= "C".$result;
        }
        }else {
        $searchcategory = DB::table('tbl_Tagtype')->select('vchTitle')->where("Intid",$result)->first();
            //$videotitle .= $searchcategory->VchCategoryTitle."_";
            //$videotitle .=$searchcategory->vchTitle;
           if(!empty($videoIntId)){
             DB::table('tbl_Videotagrelations')->where('IntId', $videoIntId)->update([$key => 0]);
         }else {
           DB::table('tbl_Videotagrelations')->insertGetId([$key =>0,'VchVideoId'=>$videoid]);
        }

         if($key=='VchRaceTagID'){
        $videotitle .= "R".$result;
        }
       if($key=='VchCategoryTagID'){
        $videotitle .= "C".$result;
        }

            }
        }

        $videoname = $videotitle.'.'.$videoext;

        if($videotype=='V'){

        $VchVideothumbnail1 = $videotitle.'.'.$thumbnailext;
        }else {
            $VchVideothumbnail1 = $VchVideothumbnail;

        }
        //$videotitle
    if(empty($_REQUEST['feature'])){
    $feature='0';

    }else{
    $feature='1';
    }


    if(empty($_REQUEST['transparent'])){
    $transparent='N';
    }else{
    $transparent='Y';
    }


    $videoname2=str_replace(' ', '', $videoname);

        DB::table('tbl_Video')->where('IntId', $_REQUEST['videoid'])->update(['Enumuploadstatus' => 'N','VchVideoName'=>$videoname2,'VchVideothumbnail'=>$VchVideothumbnail1,'feature'=>$feature,'transparent'=>$transparent,'content_category'=>$_REQUEST['content_category'],'content_category'=>$_REQUEST['content_category'],'stock_category' =>$_REQUEST['stock_category']]);
        rename(public_path().'/'.$VchFolderPath.'/'.$VchVideoName, public_path().'/'.$VchFolderPath.'/'.$videoname2);

        if($videotype=='V' && $allvideodata->EnumUploadType=='W'){
        rename(public_path().'/'.$VchFolderPath.'/'.$VchVideothumbnail, public_path().'/'.$VchFolderPath.'/'.$VchVideothumbnail1);
        }

    }
    if(isset($_POST['id'])){
    $vidid= $_REQUEST['id'];
    if(!empty($_FILES["file"])){
            //get provided file information
          $fileName= $_FILES["file"]['name'];
         $fileExtArr  = explode('.',$fileName);//make array of file.name.ext as    array(file,name,ext)
            $fileExt     = strtolower(end($fileExtArr));//get last item of array of user file input
            $fileSize    = $_FILES["file"]['size'];
            $fileTmp     = $_FILES["file"]['tmp_name'];

            $path1 = 'upload/'.'videosearch/'.$vidid.'/'.$fileName;
            if(empty($errors)){
                 move_uploaded_file($fileTmp, $path1);

             DB::table('tbl_Video')->where('IntId', $vidid)->update(['Vchcustomthumbnail'=> $fileName]);
             $lastinsertid=$vidid;
            }


    }
    }
    if(isset($_FILES["file1"])){
    if(!empty($_FILES["file1"]["name"])){
    $filename = pathinfo($_FILES["file1"]["name"]);

    $multisite = ((@$_POST['multisite'] != "")?$_POST['multisite']:"");
    $vchvideotitle = $filename['filename'];
    }else{
        $vchvideotitle=$_POST['vchvideotitle'];
    }
    }
    if(isset($_POST['action'])){
        if(!empty($_POST['action'])){
        $multisite=implode(',',$_POST['multisite']);
        $vchvideotitle=$_POST['vchvideotitle'];
        $myvideoid = DB::table('tbl_Video')->where('IntId', $_POST['videoid'])->update(['VchTitle' => $vchvideotitle,'vchsiteid' =>$multisite,'stock_category' =>$_REQUEST['stock_category']]);
        $lastinsertid = $_POST['videoid'];
    }else{
        $lastinsertid = DB::table('tbl_Video')->insertGetId(['VchTitle' => $vchvideotitle,'feature' =>$_REQUEST['feature'],'transparent' =>$_REQUEST['transparent'],'content_category' =>$_REQUEST['cont_cat'],'stock_category' =>$_REQUEST['stock_category'],'vchsiteid'=>$multisite]);

        DB::table('tbl_Video')->where('IntId', $lastinsertid)->update(['seo_url'=>$this->stringReplace($vchvideotitle)."-".$lastinsertid]);
    }
    }
    //$structure = '/upload/video/'.$lastinsertid.'/';
    if(!empty($_FILES["file1"])){
    $fileName = $_FILES["file1"]["name"]; // The file name
    $fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
    $fileType = $_FILES["file1"]["type"]; // The type of file it is
    $fileSize = $_FILES["file1"]["size"]; // File size in bytes
    $fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true
    if (!$fileTmpLoc) { // if file not chosen
        echo "ERROR: Please browse for a file before clicking the upload button.";
        exit();
    }
    $path = public_path().'/upload/'.'videosearch/'.$lastinsertid;
    $path1 = 'upload/'.'videosearch/'.$lastinsertid;
    File::makeDirectory($path, $mode = 0777, true, true);
     $temp = explode(".", $fileName);
    $newfilename = 'org'.round(microtime(true)) . '.' . end($temp);
    $resizeimage ='';
     $destinationPath = $path.'/resize';
     File::makeDirectory($path.'/resize', $mode = 0777, true, true);
             $allowedMimeTypes = ['tif','jpg','gif','jpeg','gif','png','bmp','svg+xml'];
            $image = $request->file('file1');
            //$watermarklogo = DB::table('tbl_setting')->where('vchcolumnname','watermark')->first();
             if(in_array(strtolower($image->getClientOriginalExtension()), $allowedMimeTypes)){

            /* if(!empty($watermarklogo)){
            $watermark =  Image::make('upload/watermark/'.$watermarklogo->Vchvalues);
            $img = Image::make($image->getRealPath());
           $resizeimage = time().'.'.$image->getClientOriginalExtension();
            $watermarkSize = $img->width() - 20;

         $watermarkSize = $img->width() / 2;

          $resizePercentage = 70;
         $watermarkSize = round($img->width() * ((100 - $resizePercentage) / 100), 2);

         $watermark->resize($watermarkSize, null, function ($constraint) {
        $constraint->aspectRatio();

          $img->insert($watermark, 'bottom-right', 10, 10);
          $img->resize(300, 300, function ($constraint) {$constraint->aspectRatio();})->save($destinationPath.'/'.$resizeimage);

          });
            $watermark1 =  Image::make('upload/watermark/'.$watermarklogo->Vchvalues);
            $img = Image::make($image->getRealPath());
           $resizeimage1 = "watermark".time().'.'.$image->getClientOriginalExtension();
            $watermarkSize = $img->width() - 20;

         $watermarkSize = $img->width() / 2;

          $resizePercentage = 70;
         $watermarkSize = round($img->width() * ((100 - $resizePercentage) / 100), 2);

         $watermark1->resize($watermarkSize, null, function ($constraint) {
          $constraint->aspectRatio();});
          $img->insert($watermark1, 'bottom-right', 10, 10);
          $img->save($path.'/'.$resizeimage1);

            }else { */

            $img = Image::make($image->getRealPath());
            $resizeimage = time().'.'.$image->getClientOriginalExtension();
            $img->resize(300, 300, function ($constraint) {$constraint->aspectRatio();})->save($destinationPath.'/'.$resizeimage);
            //}



    }
    // print_r($msite);
    // exit;
    // $selectserver = DB::table('tbl_managesite')->whereIn('intmanagesiteid',$msite)->toSql();
    // print_r($selectserver);

    // exit;
    if(move_uploaded_file($fileTmpLoc, "$path/$newfilename")){
      $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    if($ext=='webm'||$ext=='wmv'||$ext=='mkv'||$ext=='m4v'||$ext=='flv' ||$ext=='vob'||$ext=='mp4'){
     $video = public_path().'/upload/'.'videosearch/'.$lastinsertid.'/'.$newfilename;
     $thumbnailimage = round(microtime(true)).'thumbnail.jpg';
    $thumbnail = public_path().'/upload/'.'videosearch/'.$lastinsertid.'/'.$thumbnailimage;
    shell_exec("ffmpeg -i $video -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $thumbnail 2>&1");

    //$servername = $_SERVER['SERVER_NAME'];

    $selectserver = DB::table('tbl_managesite')->whereIn('intmanagesiteid',$msite)->get();
    foreach($selectserver as $selectservers){
     mkdir('upload/videosearch/'.$lastinsertid.'/'.$selectservers->intmanagesiteid, 0777);

    $Watermark = DB::table('tblwatermarklogo')->where('vchtype','V')->where('enumstatus','A')->where('vchsiteid',$selectservers->intmanagesiteid)->first();
    if(!empty($Watermark)){

        $watermarklogo = public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname;
        shell_exec('ffmpeg -i upload/'.'videosearch/'.$lastinsertid.'/'.$newfilename.' -i '.$watermarklogo.' -filter_complex  "overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2" -codec:a copy upload/videosearch/'.$lastinsertid.'/'.$selectservers->intmanagesiteid.'/watermark.mp4');
    }
        }

        if($_REQUEST['stock_category']=='stock'){
            $stock='3';
        }else{
            $stock='4';

        }
     DB::table('tbl_Video')->where('IntId', $lastinsertid)->update(['VchFolderPath'=>$path1,'VchVideoName'=>$newfilename,'VchVideothumbnail'=>$thumbnailimage,'EnumType'=>'V','stock_category' =>$stock]);
    }else {

        if($_REQUEST['stock_category']=='stock'){
            $stock='1';
        }else{
            $stock='2';

        }
        $vchorginalfile = $newfilename;
        if(!empty($resizeimage1)){
        $newfilename = $resizeimage1;
        }
    DB::table('tbl_Video')->where('IntId', $lastinsertid)->update(['VchTitle'=> $vchvideotitle,'VchFolderPath'=>$path1,'VchVideoName'=>$newfilename,'VchVideothumbnail'=>$newfilename,'VchResizeimage'=>$resizeimage,'EnumType'=>'I','vchorginalfile'=>$vchorginalfile,'stock_category' =>$stock]);
    }
    } else {
     }
    if(!empty($_FILES["thumbfile"])){
            //get provided file information
          $fileName= $_FILES["thumbfile"]['name'];
         $fileExtArr  = explode('.',$fileName);//make array of file.name.ext as    array(file,name,ext)
            $fileExt     = strtolower(end($fileExtArr));//get last item of array of user file input
            $fileSize    = $_FILES["thumbfile"]['size'];
            $fileTmp     = $_FILES["thumbfile"]['tmp_name'];

            $path1 = 'upload/'.'videosearch/'.$lastinsertid.'/'.$fileName;
            if(empty($errors)){
                 move_uploaded_file($fileTmp, $path1);

             DB::table('tbl_Video')->where('IntId', $lastinsertid)->update(['Vchcustomthumbnail'=> $fileName]);
            }


    }


     }

    foreach($msite as $siteId) {
        try {
            $this->createImages($siteId, true);
        } catch (Exception $e) {
        }
    }

    echo $returnarray = json_encode(array('videoid'=>$lastinsertid));

}
//---------------
	public function stringReplace($string){

		$oldstring = ["  ","(", ")", "?"," "];
		$newstring   = ["","", "", "","-"];
		return str_replace($oldstring, $newstring, $string);
	}

public function ManageSearchCategory(Request $request){
	 echo $this->checklogin();
$search  = $request->search;
$getvideosearch = DB::table('tbl_Searchcategory')->select('tbl_Searchcategory.IntId','tbl_Searchcategory.VchCategoryTitle','tbl_Searchcategory.IntParent',DB::raw('parentcategory.VchCategoryTitle as parent'))->leftjoin('tbl_Searchcategory as parentcategory', 'tbl_Searchcategory.IntParent', '=', 'parentcategory.IntId')->where('tbl_Searchcategory.IntParent','=','0');
if(!empty($search)){
	$getvideosearch->where('tbl_Searchcategory.VchCategoryTitle','like', "%$search%");
}
$getvideosearch = $getvideosearch->paginate(10)->appends('search',"$search");

$parentcategory = DB::table('tbl_Searchcategory')->select('*')->where('IntParent','0')->get();
return view('admin.admin-ManageSearchCategory',compact('parentcategory','search'))->with('getvideosearch', $getvideosearch);
}

    public function ManageGroups(Request $request){
        echo $this->checklogin();
        $search = $request->search;
        $groups = DB::table('tbl_group')
            ->when($request->search, function ($query, $search) {
                return $query->where('groupname', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->appends('search',"$search");

        return view('admin.admin-ManageGroups', ['groups' => $groups, 'search' => $search]);
    }

    public function createGroup(Request $request)
    {
        DB::table('tbl_group')->insert(['groupname' => $request->groupname]);
    }
    public function updateGroup(Request $request, $id)
    {
        DB::table('tbl_group')->where('intgroupid', $id)->update(['groupname' => $request->groupname]);
    }

    public function deleteGroup(Request $request, $id)
    {
        DB::table('tbl_group')->where('intgroupid', $id)->delete();
    }

public function ManageSearchSubCategory(Request $request){
	 echo $this->checklogin();

		 $search  = $request->search;
		 $searchcategory  = $request->searchcategory;
		 $getvideosearch= DB::table(DB::raw("(Select tblparent.IntId,tblparent.VchCategoryTitle,group_concat(tbl_Searchcategory.VchCategoryTitle) as CategoryTitle,`tbl_Searchcategory`.`IntParent` from tbl_Searchcategory INNER join tbl_Searchcategory as tblparent on tbl_Searchcategory.IntParent = tblparent.IntId where tbl_Searchcategory.IntParent != 0 group by tbl_Searchcategory.IntParent ) as tmp" ));

		if(!empty($search)){
		$getvideosearch->Where(function ($q) use ($search){

				$q->where(DB::raw('CONCAT_WS(" ",CategoryTitle,VchCategoryTitle)'),'like',  "%$search%");
			});
		}
		/* if(!empty($search)){
		$getvideosearch->Where(function ($q) use ($search){
				$q->where('CategoryTitle','like',  "%$search%")->orWhere('VchCategoryTitle','like',  "%$search%");
			});
		} */

		if(!empty($searchcategory)){
			$getvideosearch->Where('IntId', "$searchcategory");
		}
		/* $getvideosearch = $getvideosearch->toSql();
		echo $getvideosearch;
		exit; */
		$getvideosearch = $getvideosearch->paginate(10)->appends('search',"$search",'searchcategory',"$searchcategory");


//->paginate(15);
$parentcategory = DB::table('tbl_Searchcategory')->select('*')->where('IntParent','0')->get();
return view('admin.admin-ManageSearchsubCategory',compact('parentcategory','search','searchcategory'))->with('getvideosearch', $getvideosearch);
}
	public function addeditsearchcategory(){
		echo $this->checklogin();
		$categorytitle = $_POST['categorytitle'];
		 if(!empty($_POST['grouptag'])){
		 $grouptag = $_POST['grouptag'];
		 }

		$myvideo = array();
		 if($categorytitle != "" && $categorytitle != "Place tags"){
			$category = $_POST['category'];
			$parentcat = $_POST['parentcat'];
			if(empty($category)){

				$categoryall = explode(',',$_POST['categorytitle']);
				for($i=0;$i<count($categoryall);$i++){
				$catcheck=DB::table('tbl_Searchcategory')->where('VchCategoryTitle',$categoryall[$i])->first();
				if(empty($catcheck)){
					$lastinsertid = DB::table('tbl_Searchcategory')->insertGetId(['VchCategoryTitle'=>$categoryall[$i],'IntParent' =>$parentcat]);
					$myvideo[] = array('lastinsertid'=>$lastinsertid,'vchtitle'=>$categoryall[$i]);
					}
				}
			}else {
				$updateres = DB::table('tbl_Searchcategory')->where('IntId', $category)->update(['VchCategoryTitle'=>$categorytitle,'IntParent' =>$parentcat]);
				$myvideo[] = array('lastinsertid'=>$category,'vchtitle'=>$categorytitle);

			}
			return response()->json($myvideo, 200);
		 }else if($grouptag != "" && $grouptag != "Place group"){

				if($grouptag != ""){
					$checkgroup = DB::table('tbl_group')->where('groupname',$grouptag)->first();
					if(empty($checkgroup)){
						//DB::table('tbl_group')->insert(['groupname'=>$groupname]);
						$lastinsertid = DB::table('tbl_group')->insertGetId(['groupname'=>$grouptag]);
						$myvideo[] = array('lastinsertid'=>$lastinsertid,'vchtitle'=>$grouptag);
					}
				}
				return response()->json($myvideo, 200);
		 }

	}
public function addeditsearchsubcategory(){
	 echo $this->checklogin();
$categorytitle = $_POST['categorytitle'];
$category = $_POST['category'];
$parentcat = $_POST['parentcat'];
$myvideo = array();
$categoryall = explode(',',$_POST['categorytitle']);
if(empty($category)){


for($i=0;$i<count($categoryall);$i++){
$lastinsertid = DB::table('tbl_Searchcategory')->insertGetId(['VchCategoryTitle'=>$categoryall[$i],'IntParent' =>$parentcat]);
$myvideo[] = array('lastinsertid'=>$lastinsertid,'vchtitle'=>$categoryall[$i]);
}
}else {
	$getallsubtags =  DB::table('tbl_Searchcategory')->where('IntParent',$parentcat)->get();
	$oldtag = array();
	foreach($getallsubtags as $getallsubtag){
		$oldtag[] = $getallsubtag->VchCategoryTitle;
	}


	$a1=$categoryall;
	$a2=$oldtag;
	$newtags=array_diff($a1,$a2);
	$olddeletetags=array_diff($a2,$a1);

	$newtags = array_values($newtags);
	$olddeletetags = array_values($olddeletetags);

	for($i=0;$i<count($newtags);$i++){

		$lastinsertid = DB::table('tbl_Searchcategory')->insertGetId(['VchCategoryTitle'=>$newtags[$i],'IntParent' =>$parentcat]);
		$myvideo[] = array('lastinsertid'=>$lastinsertid,'vchtitle'=>$newtags[$i]);
	}

	for($k=0;$k<count($olddeletetags);$k++){
		DB::table('tbl_Searchcategory')->where('VchCategoryTitle', $olddeletetags[$k])->where('IntParent', $parentcat)->delete();
	}

}
return response()->json($myvideo, 200);

}



public function addeditmastertags(){
	 echo $this->checklogin();
$categorytitle = $_POST['categorytitle'];
$category = $_POST['category'];

if(empty($category)){
DB::table('tbl_MasterTag')->insert(['VchTitle'=>$categorytitle]);
}else {
echo $updateres = DB::table('tbl_MasterTag')->where('IntId', $category)->update(['VchTitle'=>$categorytitle]);

}

}

public function editvideo(){
   echo $this->checklogin();
  $videoid = $_GET['editvideo'];


  $searchtags = DB::table('tbl_Searchcategory')->select('*')->get();
	$allvideo = DB::table('tbl_Video')->select('*')->orderBy('IntId', 'DESC');
	$allvideorelation = array();
	$allsearchvideorelation = array();
	if(isset($_GET['editvideo'])){
	$videoid = $_GET['editvideo'];
	$allvideo = $allvideo->where('IntId',$videoid);
	$allvideorelation = DB::table('tbl_Videotagrelations')->where('VchVideoId',$videoid)->select('*')->first();
	$allsearchvideorelation = DB::table('tbl_SearchcategoryVideoRelationship')->where('IntVideoID',$videoid)->select('*')->get();
	}else {
		$allvideo =  $allvideo->where('Enumuploadstatus','Y');

	}
	$allvideo =$allvideo->limit(12)->get();
	$managesites = DB::table('tbl_managesite')->select('*')->get();
	$stocktype = DB::table('tblstocktype')->get();
	$alltags = DB::table('tbl_MasterTag')->select('tbl_MasterTag.IntId','tbl_MasterTag.VchColumnType','tbl_MasterTag.VchTitle',DB::raw('group_concat(tbl_Tagtype.vchTitle) as tagTitle,group_concat(tbl_Tagtype.IntId) as tagid'))->join('tbl_Tagtype', 'tbl_Tagtype.VchTypeID', '=', 'tbl_MasterTag.IntId')->groupBy('tbl_Tagtype.VchTypeID')->get();

  $videotags = DB::table('tbl_Video')->select('*')->where('IntId',$videoid)->first();


  $alldata = array("searchtags"=>$searchtags,"allvideo"=>$allvideo,'alltags'=>$alltags,'allvideorelation'=>$allvideorelation,'allsearchvideorelation'=>$allsearchvideorelation,'managesites'=>$managesites,'stocktype'=>$stocktype);





  $allvideo = $alldata;


  return view('admin.admin-editvideo',compact('allvideo'))->with('videotags', $videotags);

}
public function managesubcategorytagstags(){
	 echo $this->checklogin();
	 $access = $this->accessPoint(5);
$getvideosearch = DB::table('tbl_Tagtype')->select('tbl_Tagtype.Intid','tbl_Tagtype.vchTitle','tbl_Tagtype.Intid',DB::raw('tbl_MasterTag.VchTitle as parenttitle'),DB::raw('tbl_MasterTag.IntId as parentid'))->leftjoin('tbl_MasterTag', 'tbl_MasterTag.IntId', '=', 'tbl_Tagtype.VchTypeID')->paginate(15);
$parentcategory = DB::table('tbl_MasterTag')->select('*')->get();

return view('admin.admin-managesubcategorytagstags',compact('parentcategory','access'))->with('getvideosearch', $getvideosearch);
}
public function addeditaddsearchtags(){
	 echo $this->checklogin();
$categorytitle = $_POST['categorytitle'];
$category = $_POST['category'];
$parentcat = $_POST['parentcat'];
if(empty($category)){
DB::table('tbl_Tagtype')->insert(['vchTitle'=>$categorytitle,'VchTypeID' =>$parentcat]);
}else {
 $updateres = DB::table('tbl_Tagtype')->where('Intid', $category)->update(['vchTitle'=>$categorytitle,'VchTypeID' =>$parentcat]);

}
}
public function deleteTagtype(Request $request){
	 echo $this->checklogin();
DB::table('tbl_Tagtype')->where('Intid', $request->id)->delete();

}
public static function getsearchingtags($videoid){

$searchingtags = DB::table('tbl_SearchcategoryVideoRelationship')->select('*')->where('IntVideoID',$videoid)->get();
return $searchingtags;
}

public function replace($id){
$replaceid = $id;
$allvideo = DB::table('tbl_Video')->select('*')->where('IntId',$replaceid)->first();
return view('admin/admin-video-replace',compact('allvideo'));
}
public function replacemedia(Request $request){
if(isset($_POST['uploadtype'])){
 $videoid = $_POST['videoid'];
 $googlelink = $_POST['googlelink'];
 $getcontent =  file_get_contents($_POST['googlelink']);
 preg_match_all('~<\s*meta\s+property="(og:image+)"\s+content="([^"]*)~i', $getcontent, $matches1);
preg_match_all('~<\s*meta\s+property="(og:title+)"\s+content="([^"]*)~i', $getcontent, $matches2);
$myvchvideotitle = $matches2[2][0];
$videoext =explode('.',$myvchvideotitle);
if(isset($matches1[2][0])){
$imagelink = $matches1[2][0];
}else {
	$imagelink = '';
}
if(isset($_POST['videoid'])){
$videoIntId = $_POST['videoid'];

$filenamechange = str_replace(" ","",$matches2[2][0]);

putenv('GOOGLE_APPLICATION_CREDENTIALS='.public_path().'/Video Search-2ecb22ecfe7d.json');
		$destinationPath = '/var/www/vhosts/fox-ae.com/dev.fox-ae.com/public/upload/videosearch/'.$videoIntId.'/';
		File::makeDirectory($destinationPath, $mode = 0777, true, true);

		$client = new Google_Client();
		$client->addScope(Google_Service_Drive::DRIVE);
		$client->useApplicationDefaultCredentials();
		$service = new Google_Service_Drive($client);
		$videoexplode =  explode('/',$googlelink);
		$fileId = $videoexplode[5];
		$response = $service->files->get($fileId, array('alt' => 'media'));
		$content = $response->getBody()->getContents();
		//Storage::disk('public')->put($destinationPath.$matches2[2][0]);
		//Storage::put($destinationPath.$matches2[2][0]);
		file_put_contents($destinationPath.$filenamechange,$content);

				if (file_exists(public_path().'/upload/videosearch/'.$videoIntId.'/watermark.mp4')){
				$file_to_delete = public_path().'/upload/videosearch/'.$videoIntId.'/watermark.mp4';
				unlink($file_to_delete);
				}



$vchvideotitle= $videoext[0];
$dataupdate = [
	'VchTitle'=>$vchvideotitle,
	'VchVideoName'=>$filenamechange,
	'vchorginalfile'=>$filenamechange,
	'VchFolderPath' => 'upload/videosearch/'.$videoIntId,
	'vchgoogledrivelink' => '',
	"EnumUploadType"=>'G',
	"EnumType"=>'V',
	'VchVideothumbnail'=>$imagelink
];

DB::table('tbl_Video')->where('IntId', $videoIntId)->update($dataupdate);

//upload/videosearch/'.$videoIntId
$servername = $_SERVER['SERVER_NAME'];
$selectserver = DB::table('tbl_managesite')->where('txtsiteurl',$servername)->first();
$Watermark = DB::table('tblwatermarklogo')->where('vchtype','V')->where('enumstatus','A')->where('vchsiteid',$selectserver->intmanagesiteid)->first();
$watermarklogo = public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname;

shell_exec('ffmpeg -i upload/videosearch/'.$videoIntId.'/'.$filenamechange.' -i '.$watermarklogo.' -filter_complex  "overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2" -codec:a copy upload/videosearch/'.$videoIntId.'/'.$selectserver->intmanagesiteid.'/watermark.mp4');
exit;

}
echo $returnarray = json_encode(array('videoid'=>$_POST['videoid']));
}else {
if(!empty($_FILES["file1"])){
$fileName = $_FILES["file1"]["name"]; // The file name
$fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
$fileType = $_FILES["file1"]["type"]; // The type of file it is
$fileSize = $_FILES["file1"]["size"]; // File size in bytes
$fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true
$videoid = $_POST['videoid'];
$allvideo = DB::table('tbl_Video')->select('*')->where('IntId',$videoid)->first();
 $path = $allvideo->VchFolderPath;
$VchVideoName = $allvideo->VchVideoName;
$VchVideothumbnail = $allvideo->VchVideothumbnail;
if(file_exists($path.'/'.$VchVideoName)){
unlink($path.'/'.$VchVideoName);
unlink($path.'/'.$VchVideothumbnail);
}
$lastinsertid=$videoid;
$path1 = 'upload/'.'videosearch/'.$lastinsertid;
File::makeDirectory($path, $mode = 0777, true, true);
 $temp = explode(".", $fileName);
$newfilename = 'org'.round(microtime(true)) . '.' . end($temp);
$resizeimage ='';
 $destinationPath = $path.'/resize';
 File::makeDirectory($path.'/resize', $mode = 0777, true, true);
         $allowedMimeTypes = ['tif','jpg','gif','jpeg','gif','png','bmp','svg+xml'];
        $image = $request->file('file1');
		//$watermarklogo = DB::table('tbl_setting')->where('vchcolumnname','watermark')->first();
      if(in_array(strtolower($image->getClientOriginalExtension()), $allowedMimeTypes)){

		/* if(!empty($watermarklogo)){
        $watermark =  Image::make('upload/watermark/'.$watermarklogo->Vchvalues);
        $img = Image::make($image->getRealPath());
       $resizeimage = time().'.'.$image->getClientOriginalExtension();
        $watermarkSize = $img->width() - 20;

     $watermarkSize = $img->width() / 2;

      $resizePercentage = 70;
     $watermarkSize = round($img->width() * ((100 - $resizePercentage) / 100), 2);

     $watermark->resize($watermarkSize, null, function ($constraint) {
    $constraint->aspectRatio();
});
      $img->insert($watermark, 'bottom-right', 10, 10);
      $img->resize(300, 300, function ($constraint) {$constraint->aspectRatio();})->save($destinationPath.'/'.$resizeimage);




	 $watermark1 =  Image::make('upload/watermark/'.$watermarklogo->Vchvalues);
        $img = Image::make($image->getRealPath());
       $resizeimage1 = "watermark".time().'.'.$image->getClientOriginalExtension();
        $watermarkSize = $img->width() - 20;

     $watermarkSize = $img->width() / 2;

      $resizePercentage = 70;
     $watermarkSize = round($img->width() * ((100 - $resizePercentage) / 100), 2);

     $watermark1->resize($watermarkSize, null, function ($constraint) {
      $constraint->aspectRatio();});
      $img->insert($watermark1, 'bottom-right', 10, 10);
      $img->save($path.'/'.$resizeimage1);

		}else {
			 */
		$img = Image::make($image->getRealPath());
        $resizeimage = time().'.'.$image->getClientOriginalExtension();
        $img->resize(300, 300, function ($constraint) {$constraint->aspectRatio();})->save($destinationPath.'/'.$resizeimage);
		//}


}

$temp = explode(".", $fileName);
$newfilename = round(microtime(true)) . '.' . end($temp);
if(move_uploaded_file($fileTmpLoc, "$path/$newfilename")){
  $ext = pathinfo($fileName, PATHINFO_EXTENSION);
if($ext=='webm'||$ext=='wmv'||$ext=='mkv'||$ext=='m4v'||$ext=='flv' ||$ext=='vob'||$ext=='mp4'){
 $video = public_path().'/upload/'.'videosearch/'.$lastinsertid.'/'.$newfilename;
 $thumbnailimage = round(microtime(true)).'thumbnail.jpg';
$thumbnail = public_path().'/upload/'.'videosearch/'.$lastinsertid.'/'.$thumbnailimage;
shell_exec("ffmpeg -i $video -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $thumbnail 2>&1");
 DB::table('tbl_Video')->where('IntId', $lastinsertid)->update(['VchFolderPath'=>$path,'VchVideoName'=>$newfilename,'VchVideothumbnail'=>$thumbnailimage,'EnumType'=>'V']);
}else {

	$vchorginalfile = $newfilename;
	if(!empty($resizeimage1)){
	$newfilename = $resizeimage1;
	}


DB::table('tbl_Video')->where('IntId', $lastinsertid)->update(['VchFolderPath'=>$path1,'VchVideoName'=>$newfilename,'VchVideothumbnail'=>$newfilename,'VchResizeimage'=>$resizeimage,'EnumType'=>'I','vchorginalfile'=>$vchorginalfile]);


}
}
echo $returnarray = json_encode(array('videoid'=>$_POST['videoid']));
}
}
}
public function sendemail($to,$subject,$message){
 echo $this->checklogin();
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
// More headers
$headers .= 'From: admin@dev.fox-ae.com' . "\r\n";
//$headers .= 'Cc: myboss@example.com' . "\r\n";
mail($to,$subject,$message,$headers);
}
public function watermarkupdate(Request $request){

  $file = $request->file('fileToUpload');
	if(!empty($request->file('fileToUpload'))){
   $destinationPath = 'upload/watermark';
   $filenames = round(microtime(true)).$file->getClientOriginalName();
   $file->move($destinationPath,$filenames);

   $transparentlogo = $request->transparentlogo;
   $settransparency = $request->settransparency;
	$vchsiteid= $request->multisite;
   $random = rand(100000000,1000000000);
   $default = $request->default;
   if(!empty($default)){
	   $status = 'A';
   }else{
	   $status = 'D';
   }

    DB::table('tblwatermarklogo')->insert(['vchwatermarklogoname' =>$filenames,'vchtype' => $settransparency,'vchtransparency'=>$transparentlogo,'vchsiteid'=>$vchsiteid,'enumstatus'=>$status,'randomnumber'=>$random]);
	return redirect('/admin/websitemanagement');
}
}
public function deletewatermark(){
  $deleteid = $_POST['deleteid'];
   $mydelete = DB::table('tblwatermarklogo')->where('Intwatermarklogoid',$deleteid)->delete();

} public function deletebackground(){
  $deleteid = $_POST['deleteid'];
   $mydelete = DB::table('tbl_backgrounds')->where('bg_id',$deleteid)->delete();

}
public function watermarkupdateedit(){
	$watermark='';
	$backgrounds='';
	if(!empty($_REQUEST['id'])){
		$id = $_REQUEST['id'];
	$watermark = DB::table('tblwatermarklogo')->where('Intwatermarklogoid',$id)->first();
	}
	if(!empty($_REQUEST['bid'])){
		$id = $_REQUEST['bid'];
		$backgrounds = DB::table('tbl_backgrounds')->where('bg_id',$id)->first();
	}
	$managesites = DB::table('tbl_managesite')->get();
return view('admin/admin-websitemanagementedit',compact('backgrounds','watermark','managesites'));
}
public function refreshwatermark(){

 $allcontent = DB::table('tbl_Video')->where('EnumUploadType','W')->count();

return view('admin/admin-refreshwatermark',compact('allcontent'));
}
public function savewatermarkupdateedit(Request $request){
	$file = $request->file('fileToUpload');
	if(!empty($request->file('fileToUpload'))){
   $destinationPath = 'upload/watermark';
   $filenames = round(microtime(true)).$file->getClientOriginalName();
   $file->move($destinationPath,$filenames);

   }else{
	   $filenames = $request->oldimageupload;
   }
   $vchsiteid= $request->multisite;
   $vchtransparency = $request->transparency;
   $random = rand(100000000,1000000000);
   	$imagetype = $_POST['imagetype'];
	$checkboxid = $_POST['checkboxid'];

   DB::table('tblwatermarklogo')->where('Intwatermarklogoid', $request->logoid)->update(['vchwatermarklogoname'=>$filenames,'vchtransparency'=>$vchtransparency,'vchsiteid'=>$vchsiteid,'randomnumber'=>$random]);
   if($imagetype=='L' || $imagetype=='S'){
	DB::table('tblwatermarklogo')->where('Intwatermarklogoid', '=', $checkboxid)->where('vchsiteid','=', $vchsiteid)->update(['enumstatus' =>'A']);

	}

   $getresponse = DB::table('tblwatermarklogo')->where('Intwatermarklogoid', '=', $request->logoid)->first();
   if($getresponse->enumstatus == 'A'){
		if($imagetype == 'L'){
		define('DIR_CACHE', './image_cache/'.$vchsiteid);
		$files = glob(DIR_CACHE."*");
			foreach($files as $file){ // iterate files
				if(is_file($file))
				unlink($file); // delete file
			}

			$getvideodata = DB::table('tbl_Video')->get();
			foreach($getvideodata as $videodata){


					$dataarray = array('intsetdefault'=>$random);
					DB::table('tbl_Video')->where('IntId', $videodata->IntId)->update($dataarray);

			}

		}else if($imagetype == 'V'){
			$getvideodata = DB::table('tbl_Video')->where('EnumType','V')->get();
			foreach($getvideodata as $videodata){
				if (file_exists(public_path().'/'.$videodata->VchFolderPath.'/watermark.mp4')){
				$file_to_delete = public_path().'/'.$videodata->VchFolderPath.'/watermark.mp4';
				unlink($file_to_delete);
				}
			}
		}else if($imagetype == 'S'){
			define('DIR_CACHESMALL', './image_cache6/'.$vchsiteid.'/');
			$files = glob(DIR_CACHESMALL."*");
			foreach($files as $file){
				if(is_file($file))
				unlink($file); // delete file
			}
			$getvideodata = DB::table('tbl_Video')->get();
			foreach($getvideodata as $videodata){
				if($videodata->vchcacheimages != ""){
					$dataarray = array('vchcacheimages'=>'','intsetdefault'=>$random);
					DB::table('tbl_Video')->where('IntId', $videodata->IntId)->update($dataarray);
				}
			}
		}
   }

  return redirect('/admin/websitemanagement');
}
public function resizeimages(){
$allvideos = DB::table('tbl_Video')->where('EnumType','I')->where('VchResizeimage','=','')->get();
foreach($allvideos as $videos){
	$videopath = $videos->VchFolderPath;
	$VchVideoName = $videos->VchVideoName;
	$path = $videopath.'/'.$VchVideoName;
	$newvideoli = $videopath.'/'.'resize';
   $filename = basename($path);
 File::makeDirectory($newvideoli, $mode = 0777, true, true);
Image::make($path)->resize(300, 300, function ($constraint) {$constraint->aspectRatio();})->save(public_path($newvideoli.'/'.$filename));
DB::table('tbl_Video')->where('IntId', $videos->IntId)->update(['VchResizeimage'=>$filename]);
echo $videos->IntId;

}

}
public function waterimages(){
	$watermark =  Image::make('upload/watermark/logo11.png');
	$watermark->opacity(10);
$img = Image::make('images/1553257254.JPG');
//#1
$watermarkSize = $img->width() - 20; //size of the image minus 20 margins
//#2
//$watermarkSize = $img->width() / 2; //half of the image size
//#3
//$resizePercentage = 0;//70% less then an actual image (play with this value)
//$watermarkSize = round($img->width() * ((100 - $resizePercentage) / 100), 2); //watermark will be $resizePercentage less then the actual width of the image

// resize watermark width keep height auto
$watermark->resize($watermarkSize, null, function ($constraint) {
    $constraint->aspectRatio();
});
//insert resized watermark to image center aligned
$img->insert($watermark, 'center');
//save new image
$img->save('images/watermark-test123.jpg');
}
 public function themeoption($id){

 $allthemeoptions = DB::table('tbl_themesetting')->where('Intsiteid',$id)->first();

 return view('admin/themesetting',compact('allthemeoptions'));
}
public function themesetting($id){

 $allthemeoptions = DB::table('tbl_themesetting')->where('Intsiteid',$id)->first();

}
public function savethemeoption(Request $request){
	$Intsiteid = $_POST['Intsiteid'];
	 $homesetting = DB::table('tbl_themesetting')->where('Intsiteid',$Intsiteid)->first();
	 // print_r($_POST);
	 // exit;

	 if(isset($_POST['themereset'])){
			$Intsiteid = $_POST['Intsiteid'];
			$IntthemeId = $_POST['IntthemeId'];
			$primarycolor = 'FF8F09';
			$secondarycolor = 'FFECB4';
			$bgtext_iconcolor = '5B5C5C';
			$sectext_iconcolor = '5B5C5C';
			$error_required = 'F80707';
			$inactive_text = 'BDBDBD';
			$hyperlink = '0044CC';
			$background_color = 'F7F7F7';
			$premium_color = '0076CC';
			$standard_plan_color = 'FF8F09';
			$deluxe_plan_color = 'DBB900';
			$primarytext_iconcolor = 'F7F7F7';
			$surface = 'ECECEC';
			$surfacetext_iconcolor = '5B5C5C';
			$basic_plan_color = '5B5C5C';
			$plantext_iconcolor = 'F7F7F7';
			$standard_tier_color = 'FF8F09';
			$premium_tier_color = '0076CC';
			$deluxe_tier_color = 'DBB900';
			$primary_shadow_color = '5B5C5C';
			$secondary_shadow_color = '5B5C5C';
			$background_shadow_color = '5B5C5C';
			$surface_shadow_color = '5B5C5C';
			 $height = $homesetting->height;
	   $width = $homesetting->width;

		$themedata= array(

					"primary_color"=>$primarycolor,
					"secondary_color"=>$secondarycolor,
					"bgtext_iconcolor"=>$bgtext_iconcolor,
					"sectext_iconcolor"=>$sectext_iconcolor,
					"error_required"=>$error_required,
					"inactive_text"=>$inactive_text,
					"hyperlink"=>$hyperlink,
					"background_color"=>$background_color,
					"premium_color"=>$premium_color,
					"standard_plan_color"=>$standard_plan_color,
					"deluxe_plan_color"=>$deluxe_plan_color,
					"primarytext_iconcolor"=>$primarytext_iconcolor,
					"surface"=>$surface,
					"surfacetext_iconcolor"=>$surfacetext_iconcolor,
					"basic_plan_color"=>$basic_plan_color,
					"plantext_iconcolor"=>$plantext_iconcolor,
					"standard_tier_color"=>$standard_tier_color,
					"premium_tier_color"=>$premium_tier_color,
					"deluxe_tier_color"=>$deluxe_tier_color,
					"primary_shadow_color"=>$primary_shadow_color,
					"secondary_shadow_color"=>$secondary_shadow_color,
					"background_shadow_color"=>$background_shadow_color,
					"surface_shadow_color"=>$surface_shadow_color,

				);
				$this->AdminModel->Updatedomaintheme($themedata,$IntthemeId);
}

	 if(isset($_POST['themesave'])){
			$Intsiteid = $_POST['Intsiteid'];
			$IntthemeId = $_POST['IntthemeId'];
			$primarycolor = $_POST['primary_color'];
			$secondarycolor = $_POST['secondary_color'];
			$bgtext_iconcolor = $_POST['bgtext_iconcolor'];
			$sectext_iconcolor = $_POST['sectext_iconcolor'];
			$error_required = $_POST['error_required'];
			$inactive_text = $_POST['inactive_text'];
			$hyperlink = $_POST['hyperlink'];
			$background_color = $_POST['background_color'];
			$premium_color = $_POST['premium_color'];
			$standard_plan_color = $_POST['standard_plan_color'];
			$deluxe_plan_color =  $_POST['deluxe_plan_color'];
			$primarytext_iconcolor = $_POST['primarytext_iconcolor'];
			$surface = $_POST['surface'];
			$surfacetext_iconcolor = $_POST['surfacetext_iconcolor'];
			$basic_plan_color = $_POST['basic_plan_color'];
			$plantext_iconcolor = $_POST['plantext_iconcolor'];
			$standard_tier_color = $_POST['standard_tier_color'];
			$premium_tier_color =  $_POST['premium_tier_color'];
			$deluxe_tier_color =  $_POST['deluxe_tier_color'];
			$primary_shadow_color = $_POST['primary_shadow_color'];
			$secondary_shadow_color = $_POST['secondary_shadow_color'];
			$background_shadow_color = $_POST['background_shadow_color'];
			$surface_shadow_color = $_POST['surface_shadow_color'];
		 $height = $homesetting->height;
		   $width = $homesetting->width;


		$themedata= array(

					"primary_color"=>$primarycolor,
					"secondary_color"=>$secondarycolor,
					"bgtext_iconcolor"=>$bgtext_iconcolor,
					"sectext_iconcolor"=>$sectext_iconcolor,
					"error_required"=>$error_required,
					"inactive_text"=>$inactive_text,
					"hyperlink"=>$hyperlink,
					"background_color"=>$background_color,
					"premium_color"=>$premium_color,
					"standard_plan_color"=>$standard_plan_color,
					"deluxe_plan_color"=>$deluxe_plan_color,
					"primarytext_iconcolor"=>$primarytext_iconcolor,
					"surface"=>$surface,
					"surfacetext_iconcolor"=>$surfacetext_iconcolor,
					"basic_plan_color"=>$basic_plan_color,
					"plantext_iconcolor"=>$plantext_iconcolor,
					"standard_tier_color"=>$standard_tier_color,
					"premium_tier_color"=>$premium_tier_color,
					"deluxe_tier_color"=>$deluxe_tier_color,
					"primary_shadow_color"=>$primary_shadow_color,
					"secondary_shadow_color"=>$secondary_shadow_color,
					"background_shadow_color"=>$background_shadow_color,
					"surface_shadow_color"=>$surface_shadow_color,
				);
				$this->AdminModel->Updatedomaintheme($themedata,$IntthemeId);

		}

     if(isset($_POST['resettodefault'])){
			$Intsiteid = $_POST['Intsiteid'];
			$IntthemeId = $_POST['IntthemeId'];
			$backgroundcolor = '000000';
			$checkboxcolor = 'E46C3D';
			$checkmakcolor = 'E46C3D';
			$anchorcolor = 'E46C3D';
			$searchbuttonicon = 'E46C3D';
			$pagnationanchorcolor = '000000';
			$searchbutton = 'B5B5B5';
			$searchbox = 'B5B5B5';
			$popupcolor = '000000';
			$textcolor = 'E46C3D';
			$labelcolor = 'E46C3D';
			$titlecolor = 'E46C3D';
			$boxshadow = 'B5B5B5';
			$buttoncolor = 'EF880B';
			$menucolor = 'C1C1C1';
			$searchbartextcolor = 'E46C3D';
			$cartnumbercolor = 'FFFFFF';
			$cartnumberbgcolor = 'FF8F09';
			$height = '70';
			$width = '200';
			$popupboxcolor='B5B5B5';
			$popuptextcolor='FFFFF';



		$themedata= array(
					"Vchthemelogo"=>'logo.jpg',
					"vchwebsitebackgroundcolor"=>$backgroundcolor,
					"vchcheckboxcolor"=>$checkboxcolor,
					"checkmakcolor"=>$checkmakcolor,
					"vchanchorcolor"=>$anchorcolor,
					"vchpopupbackgroundcolor"=>$popupcolor,
					"vchtextcolor"=>$textcolor,
					"vchlabelcolor"=>$labelcolor,
					"vchvideoicon"=>'1578379683.png',
					"vchtitlecolor"=>$titlecolor,
					"height"=>$height,
					"width"=>$width,
					"searchbox"=>$searchbox,
					"pagnationanchorcolor"=>$pagnationanchorcolor,
					"searchbuttonicon"=>$searchbuttonicon,
					"buttoncolor"=>$buttoncolor,
					"menucolor"=>$menucolor,
					"searchbartextcolor"=>$searchbartextcolor,
					"searchbutton"=>$searchbutton,
					"boxshadow"=>$boxshadow,
					"carticon"=>'cart.png',
					"cartnumbercolor"=> $cartnumbercolor,
					"cartnumberbgcolor"=> $cartnumberbgcolor,
					"popuptextcolor"=> $popuptextcolor,
					"popupboxcolor"=> $popupboxcolor,
					"enumstatus"=>'A'
				);
		$this->AdminModel->Updatedomaintheme($themedata,$IntthemeId);
  $this->validate($request, [
        'uploadcarticon' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
    ]);
		}

     if(isset($_POST['homesave'])){

     $fileName = $_POST['vchlogo'];
     if ($request->hasFile('uploadlogo')) {
					$image      = $request->file('uploadlogo');
					$fileName   = time() . '.' . $image->getClientOriginalExtension();
					$img = Image::make($image->getRealPath());
					$destinationPath = 'images/';
					$image->move($destinationPath,$fileName);
		 }
		$videoiconName = $_POST['vchicon'];
		if ($request->hasFile('uploadicon')) {
					$logo  = $request->file('uploadicon');
					$videoiconName   = time() . '.' . $logo->getClientOriginalExtension();
					$img = Image::make($logo->getRealPath());
				   $destination = 'images/';
					$logo->move($destination,$videoiconName);
		 }
		 $proiconName = $_POST['proicon'];
		if ($request->hasFile('profileicon')) {
					$prologo  = $request->file('profileicon');
					$proiconName   = time() . '.' . $prologo->getClientOriginalExtension();
					$img = Image::make($prologo->getRealPath());
				   $profiledestination = 'images/';
					$prologo->move($profiledestination,$proiconName);
		 }

 		 $carticonName = $_POST['gicon'];
		if ($request->hasFile('gificon')) {
					$cartlogo  = $request->file('gificon');
					$carticonName   = time() . '.' . $cartlogo->getClientOriginalExtension();
					$destinationPath ='images/';
					$cartlogo->move($destinationPath, $carticonName);
				//	$this->save();
			//		 Image::make($cartlogo->getRealPath())->resize(50, 40, function ($constraint) {$constraint->aspectRatio();})->save(public_path('images/'.$carticonName));
					//$cartlogo->move($cartdestination,$carticonName);

		 }


			$Intsiteid = $_POST['Intsiteid'];
			$IntthemeId = $_POST['IntthemeId'];
			$height = $request->height;
			$width = $request->width;

			$tagcolor_standard = $homesetting->tagcolor_standard;
			$tagcolor_premium = $homesetting->tagcolor_premium;
			$tagcolor_ultrapremium = $homesetting->tagcolor_ultrapremium;
			$backgroundcolorpricing = $homesetting->pricingbackgroundcolor;
			$fontcolor = $homesetting->fontcolorpricing;
			$pricingpopupbgcolor = $homesetting->pricingpopupbgcolor;
			$pricingpopupcolor = $homesetting->pricingpopupcolor;
			$backgroundcolormember = $homesetting->backgroundcolormember;
			$fontcolormember = $homesetting->fontcolormember;
			$bordercolor = $homesetting->bordercolor;
			$sidebarcolor = $homesetting->sidebarcolor;
			$sidebarbackgroundcolor = $homesetting->sidebarbackgroundcolor;
			$memberactivecolor = $homesetting->activemember;
			$imagemouseover_color = $homesetting->imagemouseover_color;
			$cartbutton_color = $homesetting->cartbutton_color;
			$primarycolor = $homesetting->primary_color;
			$secondarycolor = $homesetting->secondary_color;
			$bgtext_iconcolor = $homesetting->bgtext_iconcolor;
			$sectext_iconcolor = $homesetting->sectext_iconcolor;
			$error_required = $homesetting->error_required;
			$inactive_text = $homesetting->inactive_text;
			$hyperlink = $homesetting->hyperlink;
			$background_color = $homesetting->background_color;
			$premium_color = $homesetting->premium_color;
			$standard_plan_color = $homesetting->standard_plan_color;
			$deluxe_plan_color =  $homesetting->deluxe_plan_color;
			$primarytext_iconcolor = $homesetting->primarytext_iconcolor;
			$surface = $homesetting->surface;
			$surfacetext_iconcolor =  $homesetting->surfacetext_iconcolor;
			$basic_plan_color = $homesetting->basic_plan_color;
			$plantext_iconcolor = $homesetting->plantext_iconcolor;
			$standard_tier_color = $homesetting->standard_tier_color;
			$premium_tier_color =  $homesetting->premium_tier_color;
			$deluxe_tier_color =  $homesetting->deluxe_tier_color;
			$primary_shadow_color = $homesetting->primary_shadow_color;
			$secondary_shadow_color = $homesetting->secondary_shadow_color;
			$background_shadow_color = $homesetting->background_shadow_color;
			$surface_shadow_color = $homesetting->surface_shadow_color;
			$themedata= array(

					"height"=>$height,
					"width"=>$width,

				);

				if($fileName != ""){
					$themedata['Vchthemelogo'] =  $fileName;
				}
				if($videoiconName != ""){
					$themedata['vchvideoicon'] =  $videoiconName;
				}if($proiconName != ""){
					$themedata['vchprofileicon'] =  $proiconName;
				}
				if($carticonName != ""){
					$themedata['gificon'] =  $carticonName;
				}
			$this->AdminModel->Updatedomaintheme($themedata,$IntthemeId);
		}

     if(isset($_POST['pricingsave'])){
			$Intsiteid = $_POST['Intsiteid'];
			$IntthemeId = $_POST['IntthemeId'];
			$backgroundcolor = $homesetting->vchwebsitebackgroundcolor;
			$checkboxcolor = $homesetting->vchcheckboxcolor;
			$vchcheckboxcolor = $homesetting->vchcheckboxcolor;
			$anchorcolor = $homesetting->vchanchorcolor;
			$popupcolor = $homesetting->vchpopupbackgroundcolor;
			$textcolor = $homesetting->vchtextcolor;
			$labelcolor = $homesetting->vchlabelcolor;
			$titlecolor = $homesetting->vchtitlecolor;
			$checkmakcolor = $homesetting->checkmakcolor;
			$height = $homesetting->height;
			$width = $homesetting->width;
			$boxshadow = $homesetting->boxshadow;
			$pagnationanchorcolor = $homesetting->pagnationanchorcolor;
			$searchbox = $homesetting->searchbox;
			$searchbuttonicon = $homesetting->searchbuttonicon;
			$searchbutton = $homesetting->searchbutton;
			$buttoncolor = $homesetting->buttoncolor;
			$menucolor = $homesetting->menucolor;
			$tagcolor_standard = $homesetting->tagcolor_standard;
			$tagcolor_premium = $homesetting->tagcolor_premium;
			$tagcolor_ultrapremium = $homesetting->tagcolor_ultrapremium;
			$backgroundcolormember = $homesetting->backgroundcolormember;
			$fontcolormember = $homesetting->fontcolormember;
			$bordercolor = $homesetting->bordercolor;
			$sidebarcolor = $homesetting->sidebarcolor;
			$sidebarbackgroundcolor = $homesetting->sidebarbackgroundcolor;
			$memberactivecolor = $homesetting->activemember;
			$backgroundcolorpricing = $_POST['pricingbackgroundcolor'];
			$fontcolor = $_POST['pricingcolor'];
			$pricingpopupbgcolor = $_POST['pricingpopupbgcolor'];
			$pricingpopupcolor = $_POST['pricingpopupcolor'];
			$searchbartextcolor = $homesetting->searchbartextcolor;
			$cartnumbercolor = $homesetting->cartnumbercolor;
			$cartnumberbgcolor = $homesetting->cartnumberbgcolor;
			 $cartbutton_color = $homesetting->cartbutton_color;
			 $imagemouseover_color = $homesetting->imagemouseover_color;
			 $popuptextcolor = $homesetting->popuptextcolor;
			$popupboxcolor = $homesetting->popupboxcolor;
			$primarycolor = $homesetting->primary_color;
			$secondarycolor = $homesetting->secondary_color;
			$bgtext_iconcolor = $homesetting->bgtext_iconcolor;
			$sectext_iconcolor = $homesetting->sectext_iconcolor;
			$error_required = $homesetting->error_required;
			$inactive_text = $homesetting->inactive_text;
			$hyperlink = $homesetting->hyperlink;
			$background_color = $homesetting->background_color;
			$premium_color = $homesetting->premium_color;
			$standard_plan_color = $homesetting->standard_plan_color;
			$deluxe_plan_color =  $homesetting->deluxe_plan_color;
			$primarytext_iconcolor = $homesetting->primarytext_iconcolor;
			$surface = $homesetting->surface;
			$surfacetext_iconcolor =  $homesetting->surfacetext_iconcolor;
			$basic_plan_color = $homesetting->basic_plan_color;
			$plantext_iconcolor = $homesetting->plantext_iconcolor;
			$standard_tier_color = $homesetting->standard_tier_color;
			$premium_tier_color =  $homesetting->premium_tier_color;
			$deluxe_tier_color =  $homesetting->deluxe_tier_color;
			$primary_shadow_color = $homesetting->primary_shadow_color;
			$secondary_shadow_color = $homesetting->secondary_shadow_color;
			$background_shadow_color = $homesetting->background_shadow_color;
			$surface_shadow_color = $homesetting->surface_shadow_color;
		    $themedata= array(
					"pricingbackgroundcolor"=>$backgroundcolorpricing,
					"fontcolorpricing"=>$fontcolor,
					"pricingpopupbgcolor"=>$pricingpopupbgcolor,
					"pricingpopupcolor"=>$pricingpopupcolor,

				);


			$this->AdminModel->Updatedomaintheme($themedata,$IntthemeId);

	}

     if(isset($_POST['pricingreset'])){
		$backgroundcolor = $homesetting->vchwebsitebackgroundcolor;
	   $checkboxcolor = $homesetting->vchcheckboxcolor;
	   $vchcheckboxcolor = $homesetting->vchcheckboxcolor;
	   $anchorcolor = $homesetting->vchanchorcolor;
	   $popupcolor = $homesetting->vchpopupbackgroundcolor;
	   $textcolor = $homesetting->vchtextcolor;
	   $labelcolor = $homesetting->vchlabelcolor;
	   $titlecolor = $homesetting->vchtitlecolor;
	   $checkmakcolor = $homesetting->checkmakcolor;
	   $height = $homesetting->height;
	   $width = $homesetting->width;
	   $boxshadow = $homesetting->boxshadow;
	   $pagnationanchorcolor = $homesetting->pagnationanchorcolor;
	   $searchbox = $homesetting->searchbox;
	   $searchbuttonicon = $homesetting->searchbuttonicon;
	   $searchbutton = $homesetting->searchbutton;
	   $buttoncolor = $homesetting->buttoncolor;
	   $menucolor = $homesetting->menucolor;
	    $tagcolor_standard = $homesetting->tagcolor_standard;
		$tagcolor_premium = $homesetting->tagcolor_premium;
		$tagcolor_ultrapremium = $homesetting->tagcolor_ultrapremium;
	   	$searchbartextcolor = $homesetting->searchbartextcolor;
			$cartnumbercolor = $homesetting->cartnumbercolor;
			$cartnumberbgcolor = $homesetting->cartnumberbgcolor;
			 $imagemouseover_color = $homesetting->imagemouseover_color;
			  $popuptextcolor = $homesetting->popuptextcolor;
			$popupboxcolor = $homesetting->popupboxcolor;
			 $cartbutton_color = $homesetting->cartbutton_color;
			 $primarycolor = $homesetting->primary_color;
			$secondarycolor = $homesetting->secondary_color;
			$bgtext_iconcolor = $homesetting->bgtext_iconcolor;
			$sectext_iconcolor = $homesetting->sectext_iconcolor;
			$error_required = $homesetting->error_required;
			$inactive_text = $homesetting->inactive_text;
			$hyperlink = $homesetting->hyperlink;
			$background_color = $homesetting->background_color;
			$premium_color = $homesetting->premium_color;
			$standard_plan_color = $homesetting->standard_plan_color;
			$deluxe_plan_color =  $homesetting->deluxe_plan_color;
			$primarytext_iconcolor = $homesetting->primarytext_iconcolor;
			$surface = $homesetting->surface;
			$surfacetext_iconcolor =  $homesetting->surfacetext_iconcolor;
			$basic_plan_color = $homesetting->basic_plan_color;
			$plantext_iconcolor = $homesetting->plantext_iconcolor;
			$standard_tier_color = $homesetting->standard_tier_color;
			$premium_tier_color =  $homesetting->premium_tier_color;
			$deluxe_tier_color =  $homesetting->deluxe_tier_color;
			$primary_shadow_color = $homesetting->primary_shadow_color;
			$secondary_shadow_color = $homesetting->secondary_shadow_color;
			$background_shadow_color = $homesetting->background_shadow_color;
			$surface_shadow_color = $homesetting->surface_shadow_color;
		 $backgroundcolorpricing = 'fffff';
		 $fontcolor ='00000';
		 $IntthemeId = $_POST['IntthemeId'];
		 $themedata= array(
					"pricingbackgroundcolor"=>$backgroundcolorpricing,
					"fontcolorpricing"=>$fontcolor,

				);
			$this->AdminModel->Updatedomaintheme($themedata,$IntthemeId);

	}

     if(isset($_POST['membersave'])){
		   $backgroundcolor = $homesetting->vchwebsitebackgroundcolor;
		   $checkboxcolor = $homesetting->vchcheckboxcolor;
		   $vchcheckboxcolor = $homesetting->vchcheckboxcolor;
		   $anchorcolor = $homesetting->vchanchorcolor;
		   $popupcolor = $homesetting->vchpopupbackgroundcolor;
		   $textcolor = $homesetting->vchtextcolor;
		   $labelcolor = $homesetting->vchlabelcolor;
		   $titlecolor = $homesetting->vchtitlecolor;
		   $checkmakcolor = $homesetting->checkmakcolor;
		   $height = $homesetting->height;
		   $width = $homesetting->width;
		   $boxshadow = $homesetting->boxshadow;
		   $pagnationanchorcolor = $homesetting->pagnationanchorcolor;
		   $searchbox = $homesetting->searchbox;
		   $searchbuttonicon = $homesetting->searchbuttonicon;
		   $searchbutton = $homesetting->searchbutton;
		   $buttoncolor = $homesetting->buttoncolor;
		   $menucolor = $homesetting->menucolor;
		   $tagcolor_standard = $homesetting->tagcolor_standard;
			$tagcolor_premium = $homesetting->tagcolor_premium;
			$tagcolor_ultrapremium = $homesetting->tagcolor_ultrapremium;
		   $backgroundcolorpricing = $homesetting->pricingbackgroundcolor;
		   $fontcolor = $homesetting->fontcolorpricing;
		   $pricingpopupbgcolor = $homesetting->pricingpopupbgcolor;
		   $pricingpopupcolor = $homesetting->pricingpopupcolor;
		   	$searchbartextcolor = $homesetting->searchbartextcolor;
			$cartnumbercolor = $homesetting->cartnumbercolor;
			$cartnumberbgcolor = $homesetting->cartnumberbgcolor;
			 $imagemouseover_color = $homesetting->imagemouseover_color;
			  $popuptextcolor = $homesetting->popuptextcolor;
			$popupboxcolor = $homesetting->popupboxcolor;
			 $cartbutton_color = $homesetting->cartbutton_color;
			 			$primarycolor = $homesetting->primary_color;
			$secondarycolor = $homesetting->secondary_color;
			$bgtext_iconcolor = $homesetting->bgtext_iconcolor;
			$sectext_iconcolor = $homesetting->sectext_iconcolor;
			$error_required = $homesetting->error_required;
			$inactive_text = $homesetting->inactive_text;
			$hyperlink = $homesetting->hyperlink;
			$background_color = $homesetting->background_color;
			$premium_color = $homesetting->premium_color;
			$standard_plan_color = $homesetting->standard_plan_color;
			$deluxe_plan_color =  $homesetting->deluxe_plan_color;
			$primarytext_iconcolor = $homesetting->primarytext_iconcolor;
			$surface = $homesetting->surface;
			$surfacetext_iconcolor =  $homesetting->surfacetext_iconcolor;
			$basic_plan_color = $homesetting->basic_plan_color;
			$plantext_iconcolor = $homesetting->plantext_iconcolor;
			$standard_tier_color = $homesetting->standard_tier_color;
			$premium_tier_color =  $homesetting->premium_tier_color;
			$deluxe_tier_color =  $homesetting->deluxe_tier_color;
			$primary_shadow_color = $homesetting->primary_shadow_color;
			$secondary_shadow_color = $homesetting->secondary_shadow_color;
			$background_shadow_color = $homesetting->background_shadow_color;
			$surface_shadow_color = $homesetting->surface_shadow_color;
		   $backgroundcolormember = $request->memberbackgroundcolor;
		   $fontcolormember = $request->membercolor;
		   $bordercolor = $request->bordercolor;
		   $sidebarcolor = $request->sidebarcolor;
		   $sidebarbackgroundcolor = $request->sidebarbackgroundcolor;
		   $memberactivecolor = $request->activecolor;
		   	$IntthemeId = $request->IntthemeId;
		    $themedata= array(
					"backgroundcolormember"=>$backgroundcolormember,
					"fontcolormember"=>$fontcolormember,
					"bordercolor"=>$bordercolor,
					"activemember"=>$memberactivecolor,
					"sidebarcolor"=>$sidebarcolor,
					"sidebarbackgroundcolor"=>$sidebarbackgroundcolor,

				);
			$this->AdminModel->Updatedomaintheme($themedata,$IntthemeId);

	}

     if(isset($_POST['resetmember'])){
		$backgroundcolor = $homesetting->vchwebsitebackgroundcolor;
	   $checkboxcolor = $homesetting->vchcheckboxcolor;
	   $vchcheckboxcolor = $homesetting->vchcheckboxcolor;
	   $anchorcolor = $homesetting->vchanchorcolor;
	   $popupcolor = $homesetting->vchpopupbackgroundcolor;
	   $textcolor = $homesetting->vchtextcolor;
	   $labelcolor = $homesetting->vchlabelcolor;
	   $titlecolor = $homesetting->vchtitlecolor;
	   $checkmakcolor = $homesetting->checkmakcolor;
	   $height = $homesetting->height;
	   $width = $homesetting->width;
	   $boxshadow = $homesetting->boxshadow;
	   $pagnationanchorcolor = $homesetting->pagnationanchorcolor;
	   $searchbox = $homesetting->searchbox;
	   $searchbuttonicon = $homesetting->searchbuttonicon;
	   $searchbutton = $homesetting->searchbutton;
	   $buttoncolor = $homesetting->buttoncolor;
	   $menucolor = $homesetting->menucolor;
	   $tagcolor_standard = $homesetting->tagcolor_standard;
		$tagcolor_premium = $homesetting->tagcolor_premium;
		$tagcolor_ultrapremium = $homesetting->tagcolor_ultrapremium;
	    $backgroundcolorpricing = $homesetting->pricingbackgroundcolor;
		   $fontcolor = $homesetting->fontcolorpricing;
		   $pricingpopupbgcolor = $homesetting->pricingpopupbgcolor;
		   $pricingpopupcolor = $homesetting->pricingpopupcolor;
		   	$searchbartextcolor = $homesetting->searchbartextcolor;
			$cartnumbercolor = $homesetting->cartnumbercolor;
			$cartnumberbgcolor = $homesetting->cartnumberbgcolor;
			 $imagemouseover_color = $homesetting->imagemouseover_color;
			  $cartbutton_color = $homesetting->cartbutton_color;
			  $popuptextcolor = $homesetting->popuptextcolor;
			$popupboxcolor = $homesetting->popupboxcolor;
						$primarycolor = $homesetting->primary_color;
			$secondarycolor = $homesetting->secondary_color;
			$bgtext_iconcolor = $homesetting->bgtext_iconcolor;
			$sectext_iconcolor = $homesetting->sectext_iconcolor;
			$error_required = $homesetting->error_required;
			$inactive_text = $homesetting->inactive_text;
			$hyperlink = $homesetting->hyperlink;
			$background_color = $homesetting->background_color;
			$premium_color = $homesetting->premium_color;
			$standard_plan_color = $homesetting->standard_plan_color;
			$deluxe_plan_color =  $homesetting->deluxe_plan_color;
			$primarytext_iconcolor = $homesetting->primarytext_iconcolor;
			$surface = $homesetting->surface;
			$surfacetext_iconcolor =  $homesetting->surfacetext_iconcolor;
			$basic_plan_color = $homesetting->basic_plan_color;
			$plantext_iconcolor = $homesetting->plantext_iconcolor;
			$standard_tier_color = $homesetting->standard_tier_color;
			$premium_tier_color =  $homesetting->premium_tier_color;
			$deluxe_tier_color =  $homesetting->deluxe_tier_color;
			$primary_shadow_color = $homesetting->primary_shadow_color;
			$secondary_shadow_color = $homesetting->secondary_shadow_color;
			$background_shadow_color = $homesetting->background_shadow_color;
			$surface_shadow_color = $homesetting->surface_shadow_color;
		  $backgroundcolormember = 'ffff';
		   $fontcolormember = '00000';
		   $bordercolor = 'e27d06';
		   $sidebarcolor = '00000';
		   //$sidebarbackgroundcolor = $request->sidebarbackgroundcolor;
		   $memberactivecolor = 'e27d06';
		   	$IntthemeId = $request->IntthemeId;

	}

     if(isset($_POST['tagcolorsave'])){
		   $backgroundcolor = $homesetting->vchwebsitebackgroundcolor;
		   $checkboxcolor = $homesetting->vchcheckboxcolor;
		   $vchcheckboxcolor = $homesetting->vchcheckboxcolor;
		   $anchorcolor = $homesetting->vchanchorcolor;
		   $popupcolor = $homesetting->vchpopupbackgroundcolor;
		   $textcolor = $homesetting->vchtextcolor;
		   $labelcolor = $homesetting->vchlabelcolor;
		   $titlecolor = $homesetting->vchtitlecolor;
		   $checkmakcolor = $homesetting->checkmakcolor;
		   $height = $homesetting->height;
		   $width = $homesetting->width;
		   $boxshadow = $homesetting->boxshadow;
		   $pagnationanchorcolor = $homesetting->pagnationanchorcolor;
		   $searchbox = $homesetting->searchbox;
		   $searchbuttonicon = $homesetting->searchbuttonicon;
		   $searchbutton = $homesetting->searchbutton;
		   $buttoncolor = $homesetting->buttoncolor;
		   $menucolor = $homesetting->menucolor;
			$backgroundcolorpricing = $homesetting->pricingbackgroundcolor;
		   $fontcolor = $homesetting->fontcolorpricing;
		   $pricingpopupbgcolor = $homesetting->pricingpopupbgcolor;
		   $pricingpopupcolor = $homesetting->pricingpopupcolor;
		   $backgroundcolormember = $homesetting->backgroundcolormember;
		   $fontcolormember = $homesetting->fontcolormember;
		   $bordercolor = $homesetting->bordercolor;
		   $sidebarcolor = $homesetting->sidebarcolor;
		   $sidebarbackgroundcolor = $homesetting->sidebarbackgroundcolor;
		   $memberactivecolor = $homesetting->activemember;
		   	$searchbartextcolor = $homesetting->searchbartextcolor;
			$cartnumbercolor = $homesetting->cartnumbercolor;
			$cartnumberbgcolor = $homesetting->cartnumberbgcolor;
			 $popuptextcolor = $homesetting->popuptextcolor;
			$popupboxcolor = $homesetting->popupboxcolor;
			$primarycolor = $homesetting->primary_color;
			$secondarycolor = $homesetting->secondary_color;
			$bgtext_iconcolor = $homesetting->bgtext_iconcolor;
			$sectext_iconcolor = $homesetting->sectext_iconcolor;
			$error_required = $homesetting->error_required;
			$inactive_text = $homesetting->inactive_text;
			$hyperlink = $homesetting->hyperlink;
			$background_color = $homesetting->background_color;
			$premium_color = $homesetting->premium_color;
			$standard_plan_color = $homesetting->standard_plan_color;
			$deluxe_plan_color =  $homesetting->deluxe_plan_color;
			$primarytext_iconcolor = $homesetting->primarytext_iconcolor;
			$surface = $homesetting->surface;
			$surfacetext_iconcolor =  $homesetting->surfacetext_iconcolor;
			$basic_plan_color = $homesetting->basic_plan_color;
			$plantext_iconcolor = $homesetting->plantext_iconcolor;
			$standard_tier_color = $homesetting->standard_tier_color;
			$premium_tier_color =  $homesetting->premium_tier_color;
			$deluxe_tier_color =  $homesetting->deluxe_tier_color;
			$primary_shadow_color = $homesetting->primary_shadow_color;
			$secondary_shadow_color = $homesetting->secondary_shadow_color;
			$background_shadow_color = $homesetting->background_shadow_color;
			$surface_shadow_color = $homesetting->surface_shadow_color;
			$tagcolor_standard = $request->hovertagcolor_standard;
		   $tagcolor_premium = $request->hovertagcolor_premium;
		   $tagcolor_ultrapremium = $request->hovertagcolor_ultrapremium;
		   $imagemouseover_color = $request->imagemouseover_color;
		   $cartbutton_color = $request->button_color;
		   	$IntthemeId = $request->IntthemeId;
		    $themedata= array(
					"tagcolor_standard"=>$tagcolor_standard,
					"tagcolor_premium"=>$tagcolor_premium,
					"tagcolor_ultrapremium"=>$tagcolor_ultrapremium,
					"imagemouseover_color"=>$imagemouseover_color,
					"cartbutton_color"=>$cartbutton_color,

				);


			$this->AdminModel->Updatedomaintheme($themedata,$IntthemeId);

	}

     if(isset($_POST['resettagcolor'])){
            $backgroundcolor = $homesetting->vchwebsitebackgroundcolor;
           $checkboxcolor = $homesetting->vchcheckboxcolor;
           $vchcheckboxcolor = $homesetting->vchcheckboxcolor;
           $anchorcolor = $homesetting->vchanchorcolor;
           $popupcolor = $homesetting->vchpopupbackgroundcolor;
           $textcolor = $homesetting->vchtextcolor;
           $labelcolor = $homesetting->vchlabelcolor;
           $titlecolor = $homesetting->vchtitlecolor;
           $checkmakcolor = $homesetting->checkmakcolor;
           $height = $homesetting->height;
           $width = $homesetting->width;
           $boxshadow = $homesetting->boxshadow;
           $pagnationanchorcolor = $homesetting->pagnationanchorcolor;
           $searchbox = $homesetting->searchbox;
           $searchbuttonicon = $homesetting->searchbuttonicon;
           $searchbutton = $homesetting->searchbutton;
           $buttoncolor = $homesetting->buttoncolor;
           $menucolor = $homesetting->menucolor;
            $popuptextcolor = $homesetting->popuptextcolor;
                $popupboxcolor = $homesetting->popupboxcolor;
           $tagcolor_standard = $homesetting->tagcolor_standard;
            $tagcolor_premium = $homesetting->tagcolor_premium;
            $tagcolor_ultrapremium = $homesetting->tagcolor_ultrapremium;
                $backgroundcolorpricing = $homesetting->pricingbackgroundcolor;
               $fontcolor = $homesetting->fontcolorpricing;
               $pricingpopupbgcolor = $homesetting->pricingpopupbgcolor;
               $pricingpopupcolor = $homesetting->pricingpopupcolor;
                $backgroundcolormember = $homesetting->backgroundcolormember;
               $fontcolormember = $homesetting->fontcolormember;
               $bordercolor = $homesetting->bordercolor;
               $sidebarcolor = $homesetting->sidebarcolor;
               $sidebarbackgroundcolor = $homesetting->sidebarbackgroundcolor;
               $memberactivecolor = $homesetting->activemember;
                $searchbartextcolor = $homesetting->searchbartextcolor;
                $cartnumbercolor = $homesetting->cartnumbercolor;
                $cartnumberbgcolor = $homesetting->cartnumberbgcolor;
                            $primarycolor = $homesetting->primary_color;
                $secondarycolor = $homesetting->secondary_color;
                $bgtext_iconcolor = $homesetting->bgtext_iconcolor;
                $sectext_iconcolor = $homesetting->sectext_iconcolor;
                $error_required = $homesetting->error_required;
                $inactive_text = $homesetting->inactive_text;
                $hyperlink = $homesetting->hyperlink;
                $background_color = $homesetting->background_color;
                $premium_color = $homesetting->premium_color;
                $standard_plan_color = $homesetting->standard_plan_color;
                $deluxe_plan_color =  $homesetting->deluxe_plan_color;
                $primarytext_iconcolor = $homesetting->primarytext_iconcolor;
                $surface = $homesetting->surface;
                $surfacetext_iconcolor =  $homesetting->surfacetext_iconcolor;
                $basic_plan_color = $homesetting->basic_plan_color;
                $plantext_iconcolor = $homesetting->plantext_iconcolor;
                $standard_tier_color = $homesetting->standard_tier_color;
                $premium_tier_color =  $homesetting->premium_tier_color;
                $deluxe_tier_color =  $homesetting->deluxe_tier_color;
                $primary_shadow_color = $homesetting->primary_shadow_color;
                $secondary_shadow_color = $homesetting->secondary_shadow_color;
                $background_shadow_color = $homesetting->background_shadow_color;
                $surface_shadow_color = $homesetting->surface_shadow_color;
                $tagcolor_standard = 'ff8f08';
               $tagcolor_premium = 'ff8f08';
               $tagcolor_ultrapremium = 'ff8f08';
               $imagemouseover_color = 'FFC000';
               $cartbutton_color = 'FFFF';
                $IntthemeId = $request->IntthemeId;

                $themedata= array(
                        "tagcolor_standard"=>$tagcolor_standard,
                        "tagcolor_premium"=>$tagcolor_premium,
                        "tagcolor_ultrapremium"=>$tagcolor_ultrapremium,
                        "imagemouseover_color"=>$imagemouseover_color,
                        "cartbutton_color"=>$cartbutton_color,

                    );
            $this->AdminModel->Updatedomaintheme($themedata,$IntthemeId);
        }

     if(isset($_POST['custompage'])){

	  	$fileName = $_POST['custmvideo'];
			if(!empty($_FILES["customvideo"])){
					$fileName= $_FILES["customvideo"]['name'];
					$fileExtArr  = explode('.',$fileName);//make array of file.name.ext as    array(file,name,ext)
					$fileExt     = strtolower(end($fileExtArr));//get last item of array of user file input
					$fileSize    = $_FILES["customvideo"]['size'];
					$fileTmp     = $_FILES["customvideo"]['tmp_name'];
					$path1 = 'images/'.$fileName;
        if(empty($errors)){
             move_uploaded_file($fileTmp, $path1);
		}
		 }
	     $backgroundcolor = $homesetting->vchwebsitebackgroundcolor;
		   $checkboxcolor = $homesetting->vchcheckboxcolor;
		   $vchcheckboxcolor = $homesetting->vchcheckboxcolor;
		   $anchorcolor = $homesetting->vchanchorcolor;
		   $popupcolor = $homesetting->vchpopupbackgroundcolor;
		   $textcolor = $homesetting->vchtextcolor;
		   $labelcolor = $homesetting->vchlabelcolor;
		   $titlecolor = $homesetting->vchtitlecolor;
		   $checkmakcolor = $homesetting->checkmakcolor;
		   $height = $homesetting->height;
		   $width = $homesetting->width;
		   $boxshadow = $homesetting->boxshadow;
		   $pagnationanchorcolor = $homesetting->pagnationanchorcolor;
		   $searchbox = $homesetting->searchbox;
		   $searchbuttonicon = $homesetting->searchbuttonicon;
		   $searchbutton = $homesetting->searchbutton;
		   $buttoncolor = $homesetting->buttoncolor;
		   $menucolor = $homesetting->menucolor;
			$backgroundcolorpricing = $homesetting->pricingbackgroundcolor;
		   $fontcolor = $homesetting->fontcolorpricing;
		   $pricingpopupbgcolor = $homesetting->pricingpopupbgcolor;
		   $pricingpopupcolor = $homesetting->pricingpopupcolor;
		   $backgroundcolormember = $homesetting->backgroundcolormember;
		   $fontcolormember = $homesetting->fontcolormember;
		   $bordercolor = $homesetting->bordercolor;
		   $sidebarcolor = $homesetting->sidebarcolor;
		   $sidebarbackgroundcolor = $homesetting->sidebarbackgroundcolor;
		   $memberactivecolor = $homesetting->activemember;
		   	$searchbartextcolor = $homesetting->searchbartextcolor;
			$cartnumbercolor = $homesetting->cartnumbercolor;
			$cartnumberbgcolor = $homesetting->cartnumberbgcolor;
			 $popuptextcolor = $homesetting->popuptextcolor;
			$popupboxcolor = $homesetting->popupboxcolor;
			$tagcolor_standard = $homesetting->tagcolor_standard;
		   $tagcolor_premium = $homesetting->tagcolor_premium;
		   $tagcolor_ultrapremium = $homesetting->tagcolor_ultrapremium;
		   $imagemouseover_color = $homesetting->imagemouseover_color;
		   $cartbutton_color = $homesetting->cartbutton_color;
		   	$IntthemeId = $homesetting->IntthemeId;
			$primarycolor = $homesetting->primary_color;
			$secondarycolor = $homesetting->secondary_color;
			$bgtext_iconcolor = $homesetting->bgtext_iconcolor;
			$sectext_iconcolor = $homesetting->sectext_iconcolor;
			$error_required = $homesetting->error_required;
			$inactive_text = $homesetting->inactive_text;
			$hyperlink = $homesetting->hyperlink;
			$background_color = $homesetting->background_color;
			$premium_color = $homesetting->premium_color;
			$standard_plan_color = $homesetting->standard_plan_color;
			$deluxe_plan_color =  $homesetting->deluxe_plan_color;
			$primarytext_iconcolor = $homesetting->primarytext_iconcolor;
			$surface = $homesetting->surface;
			$surfacetext_iconcolor =  $homesetting->surfacetext_iconcolor;
			$basic_plan_color = $homesetting->basic_plan_color;
			$plantext_iconcolor = $homesetting->plantext_iconcolor;
			$standard_tier_color = $homesetting->standard_tier_color;
			$premium_tier_color =  $homesetting->premium_tier_color;
			$deluxe_tier_color =  $homesetting->deluxe_tier_color;
			$primary_shadow_color = $homesetting->primary_shadow_color;
			$secondary_shadow_color = $homesetting->secondary_shadow_color;
			$background_shadow_color = $homesetting->background_shadow_color;
			$surface_shadow_color = $homesetting->surface_shadow_color;
		    $themedata= array(
					"cutomvideo"=>$fileName,
					);
			$this->AdminModel->Updatedomaintheme($themedata,$IntthemeId);



  }

     $RGB_pcolor = $this->hex2rgb($primary_shadow_color);
     $Final_Rgb_pcolor = implode(",", $RGB_pcolor);

     $RGB_seccolor = $this->hex2rgb($secondary_shadow_color);
     $Final_Rgb_seccolor = implode(",", $RGB_seccolor);

     $RGB_sec2color = $this->hex2rgb($secondary_shadow_color);
     $Final_Rgb_sec2color = implode(" ", $RGB_sec2color);

     $RGB_bcolor = $this->hex2rgb($background_shadow_color);
     $Final_Rgb_bcolor = implode(", ", $RGB_bcolor);

     $RGB_b2color = $this->hex2rgb($background_shadow_color);
     $Final_Rgb_b2color = implode(" ", $RGB_b2color);

     $RGB_scolor = $this->hex2rgb($surface_shadow_color);
     $Final_Rgb_scolor = implode(",", $RGB_scolor);



	 $p=' .homepage .top-view a.navbar-brand img{ height:'.$height.'px !important; width:'.$width.'px !important;}
			.nav-link.active { color: #'.$primarycolor.' !important;}

		li.nav-item.cart a span { background: #'.$primarycolor.' !important; color: #'.$primarytext_iconcolor.' !important;}
		.btn.btn-outline { background-color: #'.$primarycolor.' !important;}
		.btn-download, .btn-setting, .submit-btn { background-color: #'.$primarycolor.' !important;border: #'.$primarycolor.' !important;}
		li.ng-scope.active a.ng-binding {background: #'.$primarycolor.' !important; color: #'.$primarytext_iconcolor.' !important;}
		.pagination .ng-binding, .ng-scope.disabled a { background: #'.$surface.' !important;}
		.pagination .ng-binding { color: #'.$surfacetext_iconcolor.' !important;}
		.form-control.gray {background-color: #'.$surface.' !important;}
		.nav-link {color: #'.$bgtext_iconcolor.' !important;}

		input#searchkeyword::placeholder, input#searchkeyword, h5.ng-binding { color: #'.$surfacetext_iconcolor.' !important;}
		.form-control.gray { background-color: #'.$surface.' !important;}
		.check-box-container, .ng-binding, .dropdownlabel { color: #'.$bgtext_iconcolor.'!important;}
		.standard { background-color: #'.$standard_tier_color.' !important;}
		.cnrflash-label { color: #'.$plantext_iconcolor.'!important;}
		.standard:after, .standard:before { border: 8px solid #'.$standard_plan_color.' !important;}
		.premium {background-color: #'.$premium_tier_color.' !important;}
		.premium:after, .premium:before {border: 8px solid #'.$premium_color.' !important;}
		.ultra_premium:after, .ultra_premium:before {border: 8px solid #'.$deluxe_plan_color.' !important;}
		.ultra_premium { background-color: #'.$deluxe_tier_color.' !important;}
		ul.video-parts li .cart-btn {color: #'.$primarytext_iconcolor.' !important;}
		.crat-popup {background: #'.$secondarycolor.' !important;}
		button.btn {background: #'.$primarycolor.' !important;}
		.crat-popup button.btn.btn-default {color: #'.$primarytext_iconcolor.' !important;}
		div#footer { border-top: 2px solid #'.$surface.' !important; border-bottom: 2px solid #'.$surface.' !important; background-color: #'.$surface.' !important;}
		div#footer ul li a svg path { fill: #'.$surfacetext_iconcolor.' !important;}
		div#footer ul li a { color: #'.$surfacetext_iconcolor.' !important;}
		.f-left h3, .f-right h3 { color: #'.$surfacetext_iconcolor.' !important;}
		div#footer h3:after { border-bottom: 0.25px solid #'.$surfacetext_iconcolor.' !important; }
		.homepage .main-container.top-view ul.navbar-nav a.nav-link.svg-icon svg path:last-child {fill: #'.$bgtext_iconcolor.' !important;}
		.homepage { background-color: #'.$background_color.'!important; }
		input[type="checkbox"]:checked + span {border: 2px solid #'.$primarycolor.'!important; background-color: #'.$primarycolor.' !important;}
		input[type="checkbox"] + span {border: 2px solid #'.$bgtext_iconcolor.'!important;}
		i.fa.fa-search { color: #'.$primarytext_iconcolor.' !important; }
		.bgpopup-color { background-color: #'.$background_color.' !important; }
		.big-image h3 { color: #'.$bgtext_iconcolor.'!important; text-shadow: 1px 1px 4px rgb('.$Final_Rgb_b2color.' / 25%) !important;}
		.img-btm p {color: #'.$bgtext_iconcolor.'!important;}
		.img-btm a { color: #'.$hyperlink.' !important;}
		.rlt-key a { color: #'.$hyperlink.' !important;}
		.img-btm a { color: #'.$hyperlink.' !important;}
		.img-btm p { color: #'.$bgtext_iconcolor.' !important; }
		.footer-bottom ul:before { border-bottom: 0.25px solid #'.$surfacetext_iconcolor.' !important;}
		.customform_inner .custom_forms, .customform_inner .customform_contect { background: #'.$secondarycolor.' !important;}
		.customform_inner h4 {color: #'.$sectext_iconcolor.' !important;}
		.main_customform .custom_forms form { border-top: 0.25px solid #'.$sectext_iconcolor.' !important; }
		.btn-download, .btn-setting, .submit-btn { background-color: #'.$primarycolor.' !important; border: #'.$primarycolor.' !important;}
		.submit-btn { color: #'.$primarytext_iconcolor.';}

		.customform_contect div { color: #'.$sectext_iconcolor.' !important;}
		.customform_service ul li a {background: #'.$secondarycolor.' !important; color: #'.$sectext_iconcolor.' !important;}
		.customf_video h4 {color: #'.$bgtext_iconcolor.' !important; border-bottom: 0.25px solid #'.$bgtext_iconcolor.'!important;}
		#pricing {color: #'.$bgtext_iconcolor.' !important; }
		.pricing_main_section { border-bottom: 0.25px solid #'.$bgtext_iconcolor.' !important;}
		.pricing_main { border-bottom: 1px solid #'.$bgtext_iconcolor.';}
		.check-box-container .checkmark:after { border: solid #'.$primarytext_iconcolor.';}
		.pricing_total_section p span {color: #'.$bgtext_iconcolor.' !important;}
		#collapse1, #collapse_two, #collapse_three {color: #'.$hyperlink.' !important;}
		.support-link a { color: #'.$hyperlink.' !important;}
		.customform_inner .custom_forms, .customform_inner .customform_contect { background: #'.$secondarycolor.' !important;}
		.main_customform .custom_forms form {border-top: 0.25px solid #'.$sectext_iconcolor.' !important;}
		.customform_inner .form-group input, .customform_inner .form-group textarea { background: #'.$background_color.' !important; color: #'.$bgtext_iconcolor.' !important;}
		.btn-download, .btn-setting, .submit-btn { background-color: #'.$primarycolor.' !important;  border: #'.$primarycolor.' !important;}
		.modal-dialog.login-modal .modal-content { background-color: #'.$secondarycolor.' !important;}
		.crat-popup { background: #'.$secondarycolor.' !important; }
		.crat-price { border-bottom: 1px #'.$sectext_iconcolor.' solid !important;}
		.crat-popup span { color: #'.$sectext_iconcolor.' !important; }

		a.tablinks.inactive { color: #'.$inactive_text.' !important;}
		a.tablinks.clicked {color: #'.$primarycolor.' !important;}
		.bg_section .pricing_titles:before { background: #'.$basic_plan_color.' !important;}
		.pricing_main.bg_section .payment_sections p {color: #'.$bgtext_iconcolor.' !important;}

		.standard .pricing_titles:before { background: #'.$standard_plan_color.' !important; }
		.bg_section .pricing_titles p { color: #'.$plantext_iconcolor.' !important; }
		.premium .pricing_titles:before { background: #'.$premium_color.' !important; }
		.deluxe .pricing_titles:before { background: #'.$deluxe_plan_color.' !important; }
		.download_section p { color: #'.$bgtext_iconcolor.' !important; }
		.pricing_button a { background: #'.$primarycolor.' !important;  color: #'.$plantext_iconcolor.' !important; }
		.bg-color .profile-container { background-color: #'.$surface.' !important; color: #'.$surfacetext_iconcolor.' !important; }
		td.cart-text-center h5 { color: #'.$bgtext_iconcolor.' !important;}
		span.tag { color: #'.$plantext_iconcolor.' !important;}
		.standard { background-color: #'.$standard_plan_color.' !important; }
		.ultra_premium { background-color: #'.$deluxe_plan_color.' !important; }
		.premium { background-color: #'.$premium_color.' !important;}
		.profile-container.cart_table td.cart-text-center { color: #'.$bgtext_iconcolor.' !important;}
		span.clr-grey { color: #'.$bgtext_iconcolor.' !important;}
		.text-style { color: #'.$hyperlink.' !important; }
		input:checked ~ .checkmarked { background-color: #'.$primarycolor.' !important; border: 2px solid #'.$primarycolor.' !important;}
		.crat-popup p { color: #'.$hyperlink.' !important;}
		h4.main-heading { border-bottom: 1px solid #'.$bgtext_iconcolor.' !important; color: #'.$bgtext_iconcolor.' !important;}
		.signup-modal button.btn.btn-primary.trans { background: #'.$primarycolor.' !important; color: #'.$background_color.' !important; }
		.searchplaceholder { -webkit-animation: color-change 2s infinite alternate; }
		@-webkit-keyframes color-change {25% { color: #'.$inactive_text.'; } 75% { color: #'.$primarycolor.'; }}
		@-moz-keyframes color-change {
			 25% { color: #'.$inactive_text.'; }
		   100% { color: #'.$primarycolor.'; }
		}
		@-ms-keyframes color-change {
			 25% { color: #'.$inactive_text.'; }
		   100% { color: #'.$primarycolor.'; }
		}
		@-o-keyframes color-change {
			 25% { color: #'.$inactive_text.' ; }
		   100% { color: #'.$primarycolor.' ; }
		}
		@keyframes color-change {
			25% { color: #'.$inactive_text.'; }
		   100% { color: #'.$primarycolor.' ; }
		}

		.homepage .main-container.top-view ul.navbar-nav a.nav-link.active svg path:last-child {
			fill: #'.$primarycolor.' !important;
		}
		.row-fuild-maintain { background: #'.$background_color.' !important; border: 1px solid  #'.$background_color.' !important; }
		.profile-container { background-color: #'.$background_color.' !important; color: #'.$bgtext_iconcolor.' !important }
		.row-fuild-maintain h1:after { border-bottom: 2px solid #'.$primarycolor.' !important; }
		.row-fuild-maintain a.list-group-item.list-group-item-action { background: #'.$surface.'!important; color: #'.$surfacetext_iconcolor.' !important ;}
		.row-fuild-maintain a.list-group-item.list-group-item-action.active {color: #'.$bgtext_iconcolor.'!important;}
		.row-fuild-maintain a.list-group-item.list-group-item-action.active { background-color: #'.$primarycolor.' !important; color: #'.$primarytext_iconcolor.' !important }
		.table thead th { border-bottom: 2px solid #'.$surface.'; }
		table.table.fav-table a { color: #'.$hyperlink.' !important;}
		table.table.download-table a { color: #'.$hyperlink.' !important; }
		#apply_coupon_price { color: #'.$surfacetext_iconcolor.'; }
		#apply_coupon { color: #'.$surfacetext_iconcolor.'; }
		.page-link { color: #'.$surfacetext_iconcolor.' !important; background-color: #'.$surface.'!important;  border: 1px solid #'.$surface.'!important;}
		.page-item.active .page-link {color: #'.$hyperlink.'!important; background-color: #'.$surface.'!important; border-color: #'.$surface.'!important;}
		form-control {color: #'.$bgtext_iconcolor.' !important; background-color: #'.$background_color.'!important; border: 1px solid #'.$surface.'!important; }
		.leftsidenav { background-color: #'.$background_color.'!important;}
		.download-message { background: #'.$secondarycolor.'!important; }
		#subscribe_success { color: #'.$sectext_iconcolor.' !important;}
		#download_success { color: #'.$sectext_iconcolor.' !important;}
		div#download_success p { color: #'.$sectext_iconcolor.' !important; }
		div#subscribe_success p { color: #'.$sectext_iconcolor.' !important; }
		div#incartcredit strong, div#availablecredit strong, div#errorMessage strong{ background: #'.$secondarycolor.'!important; }
		#errorMessage { color: #'.$sectext_iconcolor.' !important; }
		.signup-modal button.btn.btn-primary.trans { background: #'.$primarycolor.' !important; }
		.billing-form .buy_button.detail button { background: #'.$primarycolor.' !important; }
		.video-parts .inner-parts{  box-shadow: -1px -2px 10px 3px rgb('.$Final_Rgb_b2color.' / 45%) !important; }
		.inner-parts.ng-scope:hover {box-shadow: 1px 4px 10px 5px rgb('.$Final_Rgb_b2color.' / 90%) !important;}
		.pagination .ng-binding {box-shadow: 0px 4px 4px rgb('.$Final_Rgb_b2color.' / 25%) !important;}
		input#searchkeyword { box-shadow: 0px 4px 4px rgb('.$Final_Rgb_b2color.' / 25%) !important;}
		.btn.btn-outline { box-shadow: 0px 4px 4px rgb('.$Final_Rgb_b2color.' / 25%) !important;}
		.customform_service ul li a { box-shadow: 0px 4px 4px rgb('.$Final_Rgb_b2color.' / 25%) !important;}
		.customform_inner .custom_forms, .customform_inner .customform_contect { box-shadow: 0px 4px 4px rgb('.$Final_Rgb_b2color.' / 25%) !important}
		.crat-popup {box-shadow: 0px 4px 4px rgb('.$Final_Rgb_b2color.' / 25%) !important;}
		.customform_inner .form-group input, .customform_inner .form-group textarea { box-shadow: 0px 4px 4px rgba('.$Final_Rgb_seccolor.', 0.25) !important;}
		.main_customform .bt_sec button.submit-btn { box-shadow: 0px 4px 4px rgb('.$Final_Rgb_sec2color.' / 25%) !important;}
		td.cart-img img {box-shadow: -1px -2px 10px 3px rgb('.$Final_Rgb_b2color.' / 45%) !important;}
		.verfiy-email {background: #'.$primarycolor.' !important; color: #'.$primarytext_iconcolor.' !important;}
		input:checked ~ .checkmarked-button { background-color: #'.$primarycolor.' !important;}
		ul.navbar-nav:after { border-bottom: 0.25px solid #'.$bgtext_iconcolor.' !important;}
		.iconsdsf select { border: 0.5px solid #'.$bgtext_iconcolor.' !important; color: #'.$bgtext_iconcolor.' !important; }
		p.p-heading { color: #'.$bgtext_iconcolor.' !important; }
		.big-image .rlt-key span { color: #'.$bgtext_iconcolor.' !important;}
		.main-icon ul li p { color: #'.$bgtext_iconcolor.' !important;}
		.bgpopup-color svg:hover path {fill: #'.$primarycolor.' !important;}
		.bgpopup-color svg path {fill: #'.$bgtext_iconcolor.' !important;}
		.big-image span.close_icon { color: #'.$bgtext_iconcolor.' !important; filter: drop-shadow(1px 1px 4px rgba('.$Final_Rgb_bcolor.', 0.25)) !important;}
		.profile-container.cart_table .heading-table tr{border-bottom: 1px solid #'.$bgtext_iconcolor.';}
		.modal-header .modal-title {color: #'.$sectext_iconcolor.' !important;}
		.changebg-modal .modal-header .modal-title {color: #'.$bgtext_iconcolor.' !important;}
		button.btn.btn-default {color: #'.$primarytext_iconcolor.' !important; }
		.checkmarked:after { border: solid #'.$primarytext_iconcolor.' !important; }
		.changebg-modal select {  border: 1px solid #'.$bgtext_iconcolor.' !important;  color: #'.$bgtext_iconcolor.' !important; }
		span.text-setting { color: #'.$bgtext_iconcolor.' !important; }
		.close { color: #'.$bgtext_iconcolor.' !important;  }
		.botm p { color: #'.$sectext_iconcolor.' !important;}
		.svg-setting svg path { fill: #'.$sectext_iconcolor.' !important;}
		div#the-count span { color: #'.$sectext_iconcolor.' !important;}
		.billing-form h6 { color: #'.$sectext_iconcolor.' !important;}
		.billing-form .inputBox .inputText { color: #'.$sectext_iconcolor.' !important;}
		.billing-form .input, .billing-form input { color: #'.$sectext_iconcolor.' !important;}

		form.method_sec label { color: #'.$sectext_iconcolor.' !important;}
		.order-summary h6 { border-bottom: 1px solid #'.$sectext_iconcolor.' !important;}
		p#plan-name, p#plan-price { color: #'.$sectext_iconcolor.' !important;}
		.billing-form .check_com { border-top: 1px solid #'.$sectext_iconcolor.' !important;}
		.billing-form .check_com p { color: #'.$sectext_iconcolor.' !important;}
		.loginshowXpassword svg path:last-child{ fill: #'.$sectext_iconcolor.' !important;}
		.showXpassword svg path:last-child{ fill: #'.$sectext_iconcolor.' !important;}
		form .loginForm3 .form-control { color: #'.$sectext_iconcolor.' !important;}
		input#cart-loginemail, input#cart-loginpassword, input#signupname, input#signupemail, input#cartsignuppassword, input#cartsignupconfirmpassword  { color: #'.$sectext_iconcolor.' !important;}
		.form-group h6 { color: #'.$sectext_iconcolor.' !important; border-bottom: 1px solid #'.$sectext_iconcolor.' !important;}
		p.head-recap {color: #'.$sectext_iconcolor.'!important; }
		.conpasswordplaceholder span, .passwordplaceholder span, .nameplaceholder span, .emailplaceholder span, .formBox.border span, .method.border span { color: #'.$error_required.' !important;}
		.conpasswordplaceholder, .passwordplaceholder, .nameplaceholder, .emailplaceholder{ color: #'.$inactive_text.' !important; }
		input#cart-loginemail::placeholder, input#cart-loginpassword::placeholder, input#forgotemail::placeholder{color: #'.$inactive_text.' !important;}
		a.hyperlink-setting { color: #'.$hyperlink.' !important;}
		a#collapse_one { color: #'.$hyperlink.' !important;}
		.bg_section .pricing_titles:after { border-right: 17px solid #'.$background_color.'!important;}
		span.tag:after {  border-right: 10px solid  #'.$background_color.'!important;}
		.container-fuild-maintain { background: #'.$background_color.'!important;}
		.form-container .btn {color: #'.$primarytext_iconcolor.' !important;}
		.signup-modal button.btn.btn-primary.trans{ color: #'.$primarytext_iconcolor.' !important; }
		.download-message hr{border-top: 0.25px solid #'.$sectext_iconcolor.' !important;}
		.buy_button button{color: #'.$primarytext_iconcolor.' !important;}
		.feat-ryt div#login-user ul {border-top: 1px solid #'.$bgtext_iconcolor.' !important; border-bottom: 1px solid #'.$bgtext_iconcolor.' !important;}
		.feat-ryt div#login-user ul li{ color: #'.$bgtext_iconcolor.' !important; }
		.feat-top .rlt-key span { color: #'.$bgtext_iconcolor.' !important; }
		.feat-ryt .main-icon { border-top: 1px solid #'.$bgtext_iconcolor.' !important; } input#email::placeholder,input#Phone::placeholder,textarea#exampleFormControlTextarea1::placeholder, input#cvv::placeholder,input#cardnumber::placeholder,input#expirationdate::placeholder,input#expirationYeardate::placeholder{color: #'.$inactive_text.' !important;}
		.verfiy-email a.resend { color: #'.$hyperlink.' !important;}
		body { color: #'.$bgtext_iconcolor.' !important;}
		.thank-container.acc-verify p {color: #'.$bgtext_iconcolor.'!important;}
		.thank-container.acc-verify p a { color: #'.$hyperlink.' !important; }
		.success-img svg path {  fill: #'.$primarycolor.' !important; }
		.dropdown-menu { color: #'.$bgtext_iconcolor.' !important;  background-color: #'.$background_color.'!important;     border: 1px solid #'.$background_color.' !important;}
		.dropdown-menu.show a { border-bottom: 1px solid #'.$bgtext_iconcolor.' !important;}
	 .dropdown-item { color: #'.$bgtext_iconcolor.' !important;}
	 .dropdown-item:focus, .dropdown-item:hover { color: #'.$surfacetext_iconcolor.'!important; background-color: #'.$surface.'!important; }
	 .homepage .billing-form .modal-body form.ng-pristine.ng-valid input { border-bottom: 1px solid #'.$sectext_iconcolor.' !important;}
	 .billing-form .inputBox .input { border-bottom: 1px solid #'.$sectext_iconcolor.' !important; }
	 form.form-container.ng-pristine.ng-valid input.form-control { border-bottom: 1px solid #'.$sectext_iconcolor.' !important; }
	 .confirm-modal .modal-content{background-color: #'.$secondarycolor.' !important;}
	 .table thead th { border-bottom: 2px solid #'.$bgtext_iconcolor.' !important; }
	 .form-control { color: #'.$bgtext_iconcolor.' !important; background-color: #'.$background_color.'!important; }
	 .changebg-modal .modal-content{ background-color: #'.$background_color.'!important; }
	 .dropdown select option{ background:  #'.$background_color.'!important; }
	 .iconsdsf select { background:#'.$background_color.'!important; }
	 .searchresult { background: #'.$background_color.'!important; }
	 li.fox-list.ng-binding.ng-scope:hover { background-color: #'.$surface.'!important; color: #'.$surfacetext_iconcolor.'!important; }
	 .modal-dialog.modal-lg.login-modal.signup-modal.checkoutModal.billing-form .close {color: #'.$sectext_iconcolor.' !important; }
	 form#password-changed .btn-primary { color: #'.$primarytext_iconcolor.' !important; }
	 div#custom-modal p { color: #'.$sectext_iconcolor.' !important;}
	 .keyword { color: #'.$error_required.' !important; }
	 .keyword li { color: #'.$hyperlink.' !important; }
	 .img-show span{color: #'.$inactive_text.' !important;}
		p.bigimagename-p{color: #'.$inactive_text.' !important;}
	 .imgs-setup button:hover, .imgs-setup button { background-color: #'.$surface.'!important; }
	 .imgs-setup button svg path, .imgs-setup button svg:hover path { fill: #'.$surfacetext_iconcolor.' !important; }
	 .confirm-modal .modal-content { background-color: #'.$secondarycolor.' !important; }
	 div#changeplan-modal .modal-body:after { background: #'.$sectext_iconcolor.' !important;}
	 .custom{ background-color: #'.$primarycolor.' !important; }
	.custom:after, .custom:before {  border: 8px solid #'.$primarycolor.' !important;  }
	.custom .cnrflash-label { color: #'.$primarytext_iconcolor.'!important; }
	td.subscripition a{ color: #'.$primarytext_iconcolor.' !important;}
	a.btn.btn-primary.btn-setting { color: #'.$primarytext_iconcolor.' !important ;}

	 @media only screen and (max-width: 767px){
		.after_click +.pricing_main, .pricing_main.tap_anywhere.onetime-div {
			background-color: #'.$secondarycolor.' !important;
		}
		form#price-plan-from .pricing_button {
			background-color: #'.$secondarycolor.' !important;

		}

	 }


		';


		// echo public_path()."/css/theme".$Intsiteid.".css";
		// exit;
		$a = fopen(public_path()."/css/theme".$Intsiteid.".css", 'w');
		fwrite($a, $p);
		fclose($a);
		chmod(public_path()."/css/theme".$Intsiteid.".css", 0644);

    return redirect('admin/themeoption/'.$Intsiteid);

}

public function myshowimage(){

ini_set("display_errors",1);
$img=$_GET['img'];
$w=$_GET['w'];
$h=$_GET['h'];

if(!defined('DIR_CACHE'))
	define('DIR_CACHE', './image_cache/');

if (!Is_Dir(DIR_CACHE))
	mkdir(DIR_CACHE, 0777);

$addl_path ="";
//if(ZW_IN == 'ADMIN')
//	$addl_path ="../";
// IMAGE RESIZE AND SAVE TO FIT IN $new_width x $new_height

if (file_exists($img))
{
	$thumb=strtolower(preg_replace('/\W/is', "_", "$img $w $h"));
	$changed=0;

	//if(!is_file($img))


	if (file_exists($img) && file_exists(DIR_CACHE.$thumb))
	{
		$mtime1=filemtime(DIR_CACHE.$thumb);
   		$mtime2=filemtime($img);
   		if ($mtime2>$mtime1)
   			$changed=1;
  	}
  	elseif (!file_exists(DIR_CACHE.$thumb))
   		$changed=1;

 	if ($changed)
 	{
		$filename=$img;
 		$new_width=(int)@$w;
 		$new_height=(int)@$h;
 		$lst=GetImageSize($filename);

 		$image_width=$lst[0];
		$image_height=$lst[1];
		$image_format=$lst[2]; //print_R($lst);

		if ($image_format==1)
		{
   			//header("Content-Type:image/gif");
   			//readfile($filename);
   			//exit;
			$old_image=imagecreatefromgif($filename);
 		}
 		elseif ($image_format==2)
  			$old_image=imagecreatefromjpeg($filename);
 		elseif ($image_format==3) {
  			$old_image=imagecreatefrompng($filename);
		}


 		if (($new_width!=0) && ($new_width<$image_width))
 		{
  			$image_height=(int)($image_height*($new_width/$image_width));
  			$image_width=$new_width;
			//  $image_height=$new_height;
 		}

		if (($new_height!=0) && ($new_height<$image_height))
		{
  			$image_width=(int)($image_width*($new_height/$image_height));
  			$image_height=$new_height;
			//  $image_width=$new_width;
		}

		 $new_image=ImageCreateTrueColor($image_width, $image_height);
		// $white = ImageCopyResampled($new_image, $old_image, 0, 0, 0, 0, $image_width, $image_height, imageSX($old_image), imageSY($old_image));
		// ImageFill($new_image, 0, 0, $white);
		//if ($image_format==3)
			//setTransparency($new_image,$old_image);
		 $white = ImageCopyResampled($new_image, $old_image, 0, 0, 0, 0, $image_width, $image_height, imageSX($old_image), imageSY($old_image));
		 addWatermark($new_image);
		 $image = $new_image;

	$stamp = imagecreatefrompng('upload/watermark/yandex_PNG20.png');

	$im = $image;
	// Set the margins for the stamp and get the height/width of the stamp image
	$marge_right = 10;
	$marge_bottom = 10;
	$sx = imagesx($stamp);
	$sy = imagesy($stamp);

	$imageWidth=imagesx($image);
   $imageHeight=imagesy($image);

  $logoWidth=imagesx($stamp);
  $logoHeight=imagesy($stamp);
	$logoImage = $stamp;
	$image = $image;
	// Copy the stamp image onto our photo using the margin offsets and the photo
	// width to calculate positioning of the stamp.
	/* imagecopy($im, $stamp, imagesx($im) - $sx + $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp)); */


	imagecopy($image, $logoImage, ($imageWidth-$logoWidth)/2,  ($imageHeight-$logoHeight)/2,0, 0,$logoWidth,$logoHeight);
	imageJpeg($new_image, DIR_CACHE.$thumb);
 	}
	//echo DIR_CACHE.$thumb;
	header("Content-type:image/jpeg");
	header('Content-Disposition: attachment; filename="'.$img.'"');
	readfile(DIR_CACHE.$thumb);
}

}
public function resizeshowimage($id,$siteid,$imgs){
	$img="upload/videosearch/".$id.'/resize/'.''.$imgs;
	$w=0;
	$h=0;
	if(!defined('DIR_CACHE'))
		define('DIR_CACHE', './image_cache6/'.$siteid.'/');

	if (!Is_Dir(DIR_CACHE))
		mkdir(DIR_CACHE, 0777);

	$addl_path ="";
	if (file_exists($img))
	{
		$thumb=strtolower(preg_replace('/\W/is', "_", "$img $w $h"));
		$changed=0;
		if (file_exists($img) && file_exists(DIR_CACHE.$thumb))
		{
			$mtime1=filemtime(DIR_CACHE.$thumb);
			$mtime2=filemtime($img);
			if ($mtime2>$mtime1)
				$changed=1;
			//exit;
		}
		elseif (!file_exists(DIR_CACHE.$thumb))
			$changed=1;

		if ($changed)
		{
			$filename=$img;
			$new_width=(int)@$w;
			$new_height=(int)@$h;
			$lst=GetImageSize($filename);

			$image_width=$lst[0];
			$image_height=$lst[1];
			$image_format=$lst[2]; //print_R($lst);

			if ($image_format==1)
			{
				$old_image=imagecreatefromgif($filename);
			}
			elseif ($image_format==2)
				$old_image=imagecreatefromjpeg($filename);
			elseif ($image_format==3) {
				$old_image=imagecreatefrompng($filename);
			}
			if (($new_width!=0) && ($new_width<$image_width))
			{
				$image_height=(int)($image_height*($new_width/$image_width));
				$image_width=$new_width;
			}

			if (($new_height!=0) && ($new_height<$image_height))
			{
				$image_width=(int)($image_width*($new_height/$image_height));
				$image_height=$new_height;
				//  $image_width=$new_width;
			}
			$new_image=ImageCreateTrueColor($image_width, $image_height);
			$white = ImageCopyResampled($new_image, $old_image, 0, 0, 0, 0, $image_width, $image_height, imageSX($old_image), imageSY($old_image));
			global $h;

			$Watermark = DB::table('tblwatermarklogo')->where('vchtype','S')->where('vchsiteid',$siteid)->where('enumstatus','A')->first();

			$stamp = imagecreatefrompng(public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname);

			$vchtransparency = $Watermark->vchtransparency * 10;

			$image = $new_image;
			$im = $image;
		// Set the margins for the stamp and get the height/width of the stamp image
			$marge_right = 10;
			$marge_bottom = 10;
			//$stamp = imagecolorallocate($stamp, 255, 255, 0, 75);
			$sx = imagesx($stamp);
			$sy = imagesy($stamp);

			$imageWidth=imagesx($image);
			$imageHeight=imagesy($image);

			$logoWidth=imagesx($stamp);
			$logoHeight=imagesy($stamp);
			$logoImage = $stamp;
			$image = $image;

			imagecopymerge($image, $logoImage, ($imageWidth-$logoWidth)/2,  ($imageHeight-$logoHeight)/2,0, 0,$logoWidth,$logoHeight,$vchtransparency);

			imageJpeg($new_image, DIR_CACHE.$thumb);


		}
		header("Content-type:image/jpeg");
			header('Content-Disposition: attachment; filename="'.$img.'"');
		/* echo DIR_CACHE.$thumb;
		exit; */

		readfile(DIR_CACHE.$thumb);
	}

}
public function resizeshowimage2($id,$siteid,$imgs){
 $img="upload/videosearch/".$id.'/'.''.$imgs;

	// if(empty($img)){
	// $img="upload/videosearch/".$id.'/resize/'.''.$imgs;
	// }

	$w=0;
	$h=0;
	if(!defined('DIR_CACHEs'))
		define('DIR_CACHEs', './image_cache7/'.$siteid.'/');

	if (!Is_Dir(DIR_CACHEs))
		mkdir(DIR_CACHEs, 0777);

	$addl_path ="";
	if(file_exists($img))
	{
		$thumb=strtolower(preg_replace('/\W/is', "_", "$img $w $h"));
		$changed=0;
		if (file_exists($img) && file_exists(DIR_CACHEs.$thumb))
		{
			//echo 'hoooo'; exit;
			$mtime1=filemtime(DIR_CACHEs.$thumb);
			$mtime2=filemtime($img);
			if ($mtime2>$mtime1)
				$changed=1;
			//exit;
		}
		elseif (!file_exists(DIR_CACHEs.$thumb))
			$changed=1;

		if ($changed)
		{
			$filename=$img;
			$new_width=(int)@$w;
			$new_height=(int)@$h;
			$lst=GetImageSize($filename);

			$image_width=$lst[0];
			$image_height=$lst[1];
			$image_format=$lst[2]; //print_R($lst);

			if ($image_format==1)
			{
				$old_image=imagecreatefromgif($filename);
			}
			elseif ($image_format==2)
				$old_image=imagecreatefromjpeg($filename);
			elseif ($image_format==3) {
				$old_image=imagecreatefrompng($filename);
			}
			if (($new_width!=0) && ($new_width<$image_width))
			{
				$image_height=(int)($image_height*($new_width/$image_width));
				$image_width=$new_width;
			}

			if (($new_height!=0) && ($new_height<$image_height))
			{
				$image_width=(int)($image_width*($new_height/$image_height));
				$image_height=$new_height;
				//  $image_width=$new_width;
			}
			$new_image=ImageCreateTrueColor($image_width, $image_height);
			$white = ImageCopyResampled($new_image, $old_image, 0, 0, 0, 0, $image_width, $image_height, imageSX($old_image), imageSY($old_image));
			global $h;

			$Watermark = DB::table('tblwatermarklogo')->where('vchtype','V')->where('vchsiteid',$siteid)->where('enumstatus','A')->first();

			$stamp = imagecreatefrompng(public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname);

			$vchtransparency = $Watermark->vchtransparency * 10;

			$image = $new_image;
			$im = $image;
		// Set the margins for the stamp and get the height/width of the stamp image
			$marge_right = 10;
			$marge_bottom = 10;
			//$stamp = imagecolorallocate($stamp, 255, 255, 0, 75);
			$sx = imagesx($stamp);
			$sy = imagesy($stamp);

			$imageWidth=imagesx($image);
			$imageHeight=imagesy($image);

			$logoWidth=imagesx($stamp);
			$logoHeight=imagesy($stamp);
			$logoImage = $stamp;
			$image = $image;

			imagecopymerge($image, $logoImage, ($imageWidth-$logoWidth)/2,  ($imageHeight-$logoHeight)/2,0, 0,$logoWidth,$logoHeight,$vchtransparency);

			imageJpeg($new_image, DIR_CACHEs.$thumb);


		}
		header("Content-type:image/jpeg");
			header('Content-Disposition: attachment; filename="'.$img.'"');
		/* echo DIR_CACHE.$thumb;
		exit; */

		readfile(DIR_CACHEs.$thumb);
	}

}
public function resizeshowimage1($id,$imgs){
	$img="upload/videosearch/".$id.'/resize/'.''.$imgs;
	$w=0;
	$h=0;
	if(!defined('DIR_CACHE'))
		define('DIR_CACHE', './image_cache6/');

	if (!Is_Dir(DIR_CACHE))
		mkdir(DIR_CACHE, 0777);

	$addl_path ="";
	if (file_exists($img))
	{
		$thumb=strtolower(preg_replace('/\W/is', "_", "$img $w $h"));
		$changed=0;
		if (file_exists($img) && file_exists(DIR_CACHE.$thumb))
		{
			$mtime1=filemtime(DIR_CACHE.$thumb);
			$mtime2=filemtime($img);
			if ($mtime2>$mtime1)
				$changed=1;
			//exit;
		}
		elseif (!file_exists(DIR_CACHE.$thumb))
			$changed=1;

		if ($changed)
		{
			$filename=$img;
			$new_width=(int)@$w;
			$new_height=(int)@$h;
			$lst=GetImageSize($filename);

			$image_width=$lst[0];
			$image_height=$lst[1];
			$image_format=$lst[2]; //print_R($lst);

			if ($image_format==1)
			{
				$old_image=imagecreatefromgif($filename);
			}
			elseif ($image_format==2)
				$old_image=imagecreatefromjpeg($filename);
			elseif ($image_format==3) {
				$old_image=imagecreatefrompng($filename);
			}
			if (($new_width!=0) && ($new_width<$image_width))
			{
				$image_height=(int)($image_height*($new_width/$image_width));
				$image_width=$new_width;
			}

			if (($new_height!=0) && ($new_height<$image_height))
			{
				$image_width=(int)($image_width*($new_height/$image_height));
				$image_height=$new_height;
				//  $image_width=$new_width;
			}
			$new_image=ImageCreateTrueColor($image_width, $image_height);
			$white = ImageCopyResampled($new_image, $old_image, 0, 0, 0, 0, $image_width, $image_height, imageSX($old_image), imageSY($old_image));
			global $h;

			$Watermark = DB::table('tblwatermarklogo')->where('vchtype','S')->where('enumstatus','A')->first();

			$stamp = imagecreatefrompng(public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname);

			$vchtransparency = $Watermark->vchtransparency * 10;

			$image = $new_image;
			$im = $image;
		// Set the margins for the stamp and get the height/width of the stamp image
			$marge_right = 10;
			$marge_bottom = 10;
			//$stamp = imagecolorallocate($stamp, 255, 255, 0, 75);
			$sx = imagesx($stamp);
			$sy = imagesy($stamp);

			$imageWidth=imagesx($image);
			$imageHeight=imagesy($image);

			$logoWidth=imagesx($stamp);
			$logoHeight=imagesy($stamp);
			$logoImage = $stamp;
			$image = $image;

			imagecopymerge($image, $logoImage, ($imageWidth-$logoWidth)/2,  ($imageHeight-$logoHeight)/2,0, 0,$logoWidth,$logoHeight,$vchtransparency);

			imageJpeg($new_image, DIR_CACHE.$thumb);


		}
		header("Content-type:image/jpeg");
			header('Content-Disposition: attachment; filename="'.$img.'"');
		/* echo DIR_CACHE.$thumb;
		exit; */

		readfile(DIR_CACHE.$thumb);
	}

}
public function showimagedemo($id,$siteid,$imgs){
	$img="upload/videosearch/".$id.'/'.''.$imgs;
	$w=0;
	$h=0;
	if(!defined('DIR_CACHE'))
		define('DIR_CACHE', './image_cache/'.$siteid.'/');

	if (!Is_Dir(DIR_CACHE))
		mkdir(DIR_CACHE, 0777);

	$addl_path ="";
	if (file_exists($img))
	{
		$thumb=strtolower(preg_replace('/\W/is', "_", "$img $w $h"));
		$changed=0;
		if (file_exists($img) && file_exists(DIR_CACHE.$thumb))
		{
			$mtime1=filemtime(DIR_CACHE.$thumb);
			$mtime2=filemtime($img);
			if ($mtime2>$mtime1)
				$changed=1;
		}
		elseif (!file_exists(DIR_CACHE.$thumb))
			$changed=1;

		if ($changed)
		{
			$filename=$img;
			$new_width=(int)@$w;
			$new_height=(int)@$h;
			$lst=GetImageSize($filename);

			$image_width=$lst[0];
			$image_height=$lst[1];
			$image_format=$lst[2]; //print_R($lst);

			if ($image_format==1)
			{
				$old_image=imagecreatefromgif($filename);
			}
			elseif ($image_format==2)
				$old_image=imagecreatefromjpeg($filename);
			elseif ($image_format==3) {
				$old_image=imagecreatefrompng($filename);
			}
			if (($new_width!=0) && ($new_width<$image_width))
			{
				$image_height=(int)($image_height*($new_width/$image_width));
				$image_width=$new_width;
			}

			if (($new_height!=0) && ($new_height<$image_height))
			{
				$image_width=(int)($image_width*($new_height/$image_height));
				$image_height=$new_height;
				//  $image_width=$new_width;
			}
			$new_image=ImageCreateTrueColor($image_width, $image_height);
			$white = ImageCopyResampled($new_image, $old_image, 0, 0, 0, 0, $image_width, $image_height, imageSX($old_image), imageSY($old_image));
			global $h;

			$Watermark = DB::table('tblwatermarklogo')->where('vchtype','L')->where('vchsiteid',$siteid)->where('enumstatus','A')->first();

			$stamp = imagecreatefrompng(public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname);

			$image = $new_image;
			$im = $image;
			$vchtransparency = $Watermark->vchtransparency * 10;
				if($image_format==1 || $image_format==2){


		// Set the margins for the stamp and get the height/width of the stamp image
			$marge_right = 10;
			$marge_bottom = 10;
			//$stamp = imagecolorallocate($stamp, 255, 255, 0, 75);
			$sx = imagesx($stamp);
			$sy = imagesy($stamp);

			$imageWidth=imagesx($image);
			$imageHeight=imagesy($image);

			$logoWidth=imagesx($stamp);
			$logoHeight=imagesy($stamp);
			$logoImage = $stamp;
			$image = $image;

			//imagecopy($image, $logoImage, ($imageWidth-$logoWidth)/2,  ($imageHeight-$logoHeight)/2,0, 0,$logoWidth,$logoHeight);

			imagecopymerge($image, $logoImage, ($imageWidth-$logoWidth)/2,  ($imageHeight-$logoHeight)/2,0, 0,$logoWidth,$logoHeight,$vchtransparency);
			imagepng($new_image, DIR_CACHE.$thumb);
			header("Content-type:image/png");

			}elseif ($image_format==3) {


				$imgx = imagesx($im);
				$imgy = imagesy($im);
				//$vchtransparency = 6 * 10;
				//$stampResized =imagescale($stamp , ($imgx*50)/100, ($imgy*50)/100);

				$width = imagesx($stamp);
				$height = imagesy($stamp);
				// $dst_width=($imgx*50)/100;
				// $dst_height=($imgy*50)/100;
				// $newImg = imagecreatetruecolor($dst_width, $dst_height);

				// imagealphablending($newImg, true);
				// imagesavealpha($newImg, true);
				// $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
				// imagefilledrectangle($newImg, 0, 0, $width, $height, $transparent);
				// imagecopyresampled($newImg, $stampResized, 0, 0, 0, 0, $dst_width, $dst_height, $width, $height);
				$marge_right = 10;
				$marge_bottom = 10;
				$sx = imagesx($stamp);
				$sy = imagesy($stamp);


				$centerX=($imgx-$sx)/2;
				$centerY=($imgy-$sy)/2;

				// Copy the stamp image onto our photo using the margin offsets and the photo
				// width to calculate positioning of the stamp.

				imagecopy($im, $stamp, $centerX, $centerY, 0, 0, imagesx($stamp), imagesy($stamp));
			//imagecopymerge($im, $stamp, $centerX, $centerY, 0, 0, imagesx($stamp), imagesy($stamp),$vchtransparency);

				imagepng($new_image, DIR_CACHE.$thumb);
				header("Content-type:image/png");

			}


			//imagejpeg($new_image, "/imagick/".$thumb );
		}

		header('Content-Disposition: attachment; filename="'.$img.'"');
		readfile(DIR_CACHE.$thumb);
	}
}

public function showimage1($id,$imgs){
	$img="upload/videosearch/".$id.'/'.''.$imgs;
	$w=0;
	$h=0;
	if(!defined('DIR_CACHE'))
		define('DIR_CACHE', './image_cache/');

	if (!Is_Dir(DIR_CACHE))
		mkdir(DIR_CACHE, 0777);

	$addl_path ="";
	if (file_exists($img))
	{
		$thumb=strtolower(preg_replace('/\W/is', "_", "$img $w $h"));
		$changed=0;
		if (file_exists($img) && file_exists(DIR_CACHE.$thumb))
		{
			$mtime1=filemtime(DIR_CACHE.$thumb);
			$mtime2=filemtime($img);
			if ($mtime2>$mtime1)
				$changed=1;
		}
		elseif (!file_exists(DIR_CACHE.$thumb))
			$changed=1;

		if ($changed)
		{
			$filename=$img;
			$new_width=(int)@$w;
			$new_height=(int)@$h;
			$lst=GetImageSize($filename);

			$image_width=$lst[0];
			$image_height=$lst[1];
			$image_format=$lst[2]; //print_R($lst);

			if ($image_format==1)
			{
				$old_image=imagecreatefromgif($filename);
			}
			elseif ($image_format==2){
				$old_image=imagecreatefromjpeg($filename);
			}elseif ($image_format==3) {
				$old_image=imagecreatefrompng($filename);
			}
			if (($new_width!=0) && ($new_width<$image_width))
			{
				$image_height=(int)($image_height*($new_width/$image_width));
				$image_width=$new_width;
			}

			if (($new_height!=0) && ($new_height<$image_height))
			{
				$image_width=(int)($image_width*($new_height/$image_height));
				$image_height=$new_height;
				//  $image_width=$new_width;
			}
			$new_image=ImageCreateTrueColor($image_width, $image_height);
			$white = ImageCopyResampled($new_image, $old_image, 0, 0, 0, 0, $image_width, $image_height, imageSX($old_image), imageSY($old_image));
			global $h;

			$Watermark = DB::table('tblwatermarklogo')->where('vchtype','L')->where('enumstatus','A')->first();

			$stamp = imagecreatefrompng(public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname);

			$vchtransparency = $Watermark->vchtransparency * 10;

			$image = $new_image;
			$im = $image;
		// Set the margins for the stamp and get the height/width of the stamp image
			$marge_right = 10;
			$marge_bottom = 10;
			//$stamp = imagecolorallocate($stamp, 255, 255, 0, 75);
			$sx = imagesx($stamp);
			$sy = imagesy($stamp);

			$imageWidth=imagesx($image);
			$imageHeight=imagesy($image);

			$logoWidth=imagesx($stamp);
			$logoHeight=imagesy($stamp);
			$logoImage = $stamp;
			$image = $image;

			imagecopymerge($image, $logoImage, ($imageWidth-$logoWidth)/2,  ($imageHeight-$logoHeight)/2,0, 0,$logoWidth,$logoHeight,$vchtransparency);

			 	if ($image_format==1)
			{
				imageJpeg($new_image, DIR_CACHE.$thumb);
			}
			elseif ($image_format==2){
				imageJpeg($new_image, DIR_CACHE.$thumb);
			}elseif ($image_format==3) {
				imagepng($new_image, DIR_CACHE.$thumb);
			}

		}

			if ($image_format==1)
			{
				header("Content-type:image/jpeg");
				header('Content-Disposition: attachment; filename="'.$img.'"');
				readfile(DIR_CACHE.$thumb);
			}
			elseif ($image_format==2){
				header("Content-type:image/jpeg");
				header('Content-Disposition: attachment; filename="'.$img.'"');
				readfile(DIR_CACHE.$thumb);
			}
			elseif ($image_format==3) {
				header("Content-type:image/png");
				header('Content-Disposition: attachment; filename="'.$img.'"');
				readfile(DIR_CACHE.$thumb);
			}

	}
}

public function showimage($id,$siteid,$imgs){
	$img="upload/videosearch/".$id.'/'.''.$imgs;
	//$info = pathinfo( $imgs );
	$reurnimg = $imgs;
	$returnimg="upload/videosearch/".$id.'/'.''.$imgs;
	$w=0;
	$h=0;

	if(!defined('DIR_CACHE'))
		define('DIR_CACHE', 'image_cache/'.$siteid.'/');

	if (!Is_Dir(DIR_CACHE))
		mkdir(DIR_CACHE, 0777);

	$addl_path ="";

		$thumb=strtolower(preg_replace('/\W/is', "_", "$img $w $h"));
        $returnthumb=strtolower(preg_replace('/\W/is', "_", "$img $w $h"));
		$changed=0;
		if (file_exists($img) && file_exists(DIR_CACHE.$thumb))
		{
			$mtime1=filemtime(DIR_CACHE.$thumb);
			$mtime2=filemtime($img);

			if ($mtime2>$mtime1)
				$changed=1;
		}
		elseif (!file_exists(DIR_CACHE.$thumb))
			$changed=1;


			$filename=$img;
			$new_width=(int)@$w;
			$new_height=(int)@$h;
			$lst=GetImageSize($filename);

			$image_width=$lst[0];
			$image_height=$lst[1];
			$image_format=$lst[2];
			if($image_width > 1920){
	            $withoutExt = substr($imgs, 0, -3);
				$newimg = $withoutExt.'jpg';
			    $returnthumb=strtolower(preg_replace('/\W/is', "_", "$newimg $w $h"));
				$returnimg="upload/videosearch/".$id.'/'.''.$newimg;
            }
		if ($image_format==1)
		{
				$old_image=imagecreatefromgif($filename);
					if (($new_width!=0) && ($new_width<$image_width))
			{
				$image_height=(int)($image_height*($new_width/$image_width));
				$image_width=$new_width;
			}

			if (($new_height!=0) && ($new_height<$image_height))
			{
				$image_width=(int)($image_width*($new_height/$image_height));
				$image_height=$new_height;
				//  $image_width=$new_width;
			}
			$new_image=ImageCreateTrueColor($image_width, $image_height);
			$white = ImageCopyResampled($new_image, $old_image, 0, 0, 0, 0, $image_width, $image_height, imageSX($old_image), imageSY($old_image));
			$image = $new_image;
			$reimg=$img;
		}elseif ($image_format==2){
				$old_image=imagecreatefromjpeg($filename);

			if (($new_width!=0) && ($new_width<$image_width))
			{
				$image_height=(int)($image_height*($new_width/$image_width));
				$image_width=$new_width;
			}

			if (($new_height!=0) && ($new_height<$image_height))
			{
				$image_width=(int)($image_width*($new_height/$image_height));
				$image_height=$new_height;
				//  $image_width=$new_width;
			}
			$new_image=ImageCreateTrueColor($image_width, $image_height);
			$white = ImageCopyResampled($new_image, $old_image, 0, 0, 0, 0, $image_width, $image_height, imageSX($old_image), imageSY($old_image));
			$reimg="upload/videosearch/".$id.'/'.''.$imgs;
			$image = $new_image;
		}elseif ($image_format==3) {
				$bk_stamp=imagecreatefrompng($filename);

				$bgresponse = DB::table('tbl_backgrounds')->where('background_title','Transparent')->first();
				//$bk_stamp = imagecreatefrompng($new_image);

				$im2 = imagecreatefrompng('images/'.$bgresponse->background_img);

				$img2=Image::make($im2);
				$size = getimagesize($img);
				$diemension=$size[0].'x'.$size[1];
				$img2->resize($size[0],$size[1])->save('background/'.$bgresponse->background_img);
				$marge_right = 0;
				$marge_bottom = 0;
				$sx = imagesx($bk_stamp);
				$sy = imagesy($bk_stamp);

				//$im = imagecreatefrompng('imagick/black_with_effect.png');
				$new_image = imagecreatefrompng('background/'.$bgresponse->background_img);
				// Copy the stamp image onto our photo using the margin offsets and the photo
				// width to calculate positioning of the stamp.
				imagecopy($new_image, $bk_stamp, imagesx($new_image) - $sx - $marge_right, imagesy($bk_stamp) - $sy - $marge_bottom, 0, 0, imagesx($bk_stamp), imagesy($bk_stamp));

				// Output and free memory
				header('Content-type: image/png');
				 $reimg="upload/videosearch/".$id.'/transparent_'.''.$imgs;

				imagepng($new_image,$reimg);
				//imagepng($new_image);

					$image = $new_image;


			}

			global $h;

			$Watermark = DB::table('tblwatermarklogo')->where('vchtype','L')->where('vchsiteid',$siteid)->where('enumstatus','A')->first();

			$stamp = imagecreatefrompng(public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname);
			$stamp2 = public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname;

			$vchtransparency = $Watermark->vchtransparency * 10;
		// Set the margins for the stamp and get the height/width of the stamp image
			$marge_right = 10;
			$marge_bottom = 10;
			//$stamp = imagecolorallocate($stamp, 255, 255, 0, 75);
			$sx = imagesx($stamp);
			$sy = imagesy($stamp);

			$imageWidth=imagesx($image);
			$imageHeight=imagesy($image);

			$logoWidth=imagesx($stamp);
			$logoHeight=imagesy($stamp);
			$logoImage = $stamp;
			//$image = $new_image;


			 if($imageWidth > 1024){
				 	 $marge_right = 0;
					$marge_bottom = 0;
				 $dst_width=$imageWidth;
				$dst_height=$imageHeight;
			 }else{
				 $marge_right = 50;
				$marge_bottom = 50;
				$dst_width=($imageWidth*50)/100;
				$dst_height=($imageHeight*50)/100;
			 }

			$imgpath=public_path().'/image_cache/'.$siteid.'/'.$thumb;
			$imgfullpath=public_path().'/'.$reimg;
		  //  $reurnimg = str_replace(' ', '_', $reurnimg);
			$reurnimgpath=public_path().'/image_cache/'.$siteid.'/'.$returnthumb;
			$tempreurnimgpath=public_path().'/image_cache/'.$siteid.'/testing.jpg';
			// $lst=GetImageSize($imgfullpath);
		    // $image_width=$lst[0];


				// exec("composite -dissolve ".$vchtransparency."% -gravity center -geometry '".$dst_width."'x'".$dst_height."'+".$marge_right."+".$marge_bottom." '".$stamp2."' '".$imgfullpath."' '".$reurnimgpath."'");
				// exec("convert $reurnimgpath -resize 50% $imgpath");
			   // unlink($reurnimgpath);
               // if (!file_exists(DIR_CACHE.$thumb)){
				   	// exec("composite -dissolve ".$vchtransparency."% -gravity center -geometry '".$dst_width."'x'".$dst_height."'+".$marge_right."+".$marge_bottom." '".$stamp2."' '".$imgfullpath."' '".$imgpath."'");
			   // }

			// }else{
				// exec("composite -dissolve ".$vchtransparency."% -gravity center -geometry '".$dst_width."'x'".$dst_height."'+".$marge_right."+".$marge_bottom." '".$stamp2."' '".$imgfullpath."' '".$imgpath."'");
			// }
			//echo $imageWidth; exit;
			 if($imageWidth > 1920){
			     exec("composite -dissolve ".$vchtransparency."% -gravity center -geometry '".$dst_width."'x'".$dst_height."'+".$marge_right."+".$marge_bottom." '".$stamp2."' '".$imgfullpath."' '".$tempreurnimgpath."'");
				 exec("convert $tempreurnimgpath -resize 50% $tempreurnimgpath");
				  exec("convert -strip -interlace Plane -gaussian-blur 0.05 -quality 85% $tempreurnimgpath $reurnimgpath");
				 if (file_exists($tempreurnimgpath))
		           {
			          unlink($tempreurnimgpath);
				   }
			 }else{
				 exec("composite -dissolve ".$vchtransparency."% -gravity center -geometry '".$dst_width."'x'".$dst_height."'+".$marge_right."+".$marge_bottom." '".$stamp2."' '".$imgfullpath."' '".$tempreurnimgpath."'");

				  exec("convert -strip -interlace Plane -gaussian-blur 0.05 -quality 85% $tempreurnimgpath $reurnimgpath");
				 if (file_exists($tempreurnimgpath))
		           {
			          unlink($tempreurnimgpath);
				   }
			 }

			// exec("convert $reurnimgpath  -quality 75  $imgpath");
            // unlink($reurnimgpath);


			/* if (!file_exists(DIR_CACHE.$thumb)){
			imagecopymerge($image, $logoImage, ($imageWidth-$logoWidth)/2,  ($imageHeight-$logoHeight)/2,0, 0,$logoWidth,$logoHeight,$vchtransparency);
			 imageJpeg($new_image, DIR_CACHE.$thumb);
			//imagejpeg($new_image, "/imagick/".$thumb );
			} */

		header("Content-type:image/jpeg");
		header('Content-Disposition: attachment; filename="'.$returnimg.'"');
		readfile(DIR_CACHE.$returnthumb);

}
public function myadminshowimage($id,$imgs){
	$img="upload/videosearch/".$id.'/'.''.$imgs;
	$w=0;
	$h=0;
	if(!defined('DIR_CACHE'))
		define('DIR_CACHE', './image_cache/');

	if (!Is_Dir(DIR_CACHE))
		mkdir(DIR_CACHE, 0777);

	$addl_path ="";
	if (file_exists($img))
	{
		$thumb=strtolower(preg_replace('/\W/is', "_", "$img $w $h"));
		$changed=0;
		if (file_exists($img) && file_exists(DIR_CACHE.$thumb))
		{
			$mtime1=filemtime(DIR_CACHE.$thumb);
			$mtime2=filemtime($img);
			if ($mtime2>$mtime1)
				$changed=1;
		}
		elseif (!file_exists(DIR_CACHE.$thumb))
			$changed=1;

		if ($changed)
		{
			$filename=$img;
			$new_width=(int)@$w;
			$new_height=(int)@$h;
			$lst=GetImageSize($filename);

			$image_width=$lst[0];
			$image_height=$lst[1];
			$image_format=$lst[2]; //print_R($lst);

			if ($image_format==1)
			{
				$old_image=imagecreatefromgif($filename);
			}
			elseif ($image_format==2)
				$old_image=imagecreatefromjpeg($filename);
			elseif ($image_format==3) {
				$old_image=imagecreatefrompng($filename);
			}
			if (($new_width!=0) && ($new_width<$image_width))
			{
				$image_height=(int)($image_height*($new_width/$image_width));
				$image_width=$new_width;
			}

			if (($new_height!=0) && ($new_height<$image_height))
			{
				$image_width=(int)($image_width*($new_height/$image_height));
				$image_height=$new_height;
				//  $image_width=$new_width;
			}
			$new_image=ImageCreateTrueColor($image_width, $image_height);
			$white = ImageCopyResampled($new_image, $old_image, 0, 0, 0, 0, $image_width, $image_height, imageSX($old_image), imageSY($old_image));
			global $h;

			$Watermark = DB::table('tblwatermarklogo')->where('vchtype','L')->where('enumstatus','A')->first();

			$stamp = imagecreatefrompng(public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname);

			$vchtransparency = $Watermark->vchtransparency * 10;

			$image = $new_image;
			$im = $image;
		// Set the margins for the stamp and get the height/width of the stamp image
			$marge_right = 10;
			$marge_bottom = 10;
			//$stamp = imagecolorallocate($stamp, 255, 255, 0, 75);
			$sx = imagesx($stamp);
			$sy = imagesy($stamp);

			$imageWidth=imagesx($image);
			$imageHeight=imagesy($image);

			$logoWidth=imagesx($stamp);
			$logoHeight=imagesy($stamp);
			$logoImage = $stamp;
			$image = $image;

			imagecopymerge($image, $logoImage, ($imageWidth-$logoWidth)/2,  ($imageHeight-$logoHeight)/2,0, 0,$logoWidth,$logoHeight,$vchtransparency);

			imageJpeg($new_image, DIR_CACHE.$thumb);
		}
		header("Content-type:image/jpeg");
		header('Content-Disposition: attachment; filename="'.$img.'"');
		readfile(DIR_CACHE.$thumb);
	}
}
public function adddomains($id=""){
echo  $this->checklogin();
	$response = "";
	  if($id != ""){

		 $response = $this->AdminModel->editdomains($id);
		}

return view('admin.admin-adddomains',compact('response'));
}
 public function insdomains(Request $request)
    {
		echo  $this->checklogin();

		if($_POST){
			$data = array(
				'vchsitename'=>$request->site_name,
				'txtsiteurl'=>$request->site_url,
				'vchmetatitle'=>$request->meta_title,
				'vchdescription'=>$request->description,
				'vchkeywords'=>$request->keywords,
				'vchemailfrom'=>$request->emailfrom,
				'vchemailto'=>$request->emailto,
				'status'=>$request->status,

				);
			if($request->intmanagesiteid == ""){
				$lastid = $this->AdminModel->Insertdomain($data);

				$themedata= array(
					"Intsiteid"=>$lastid,
					"Vchthemelogo"=>'logo.jpg',
					"vchwebsitebackgroundcolor"=>'000000',
					"vchcheckboxcolor"=>'E46C3D',
					"vchanchorcolor"=>'E46C3D',
					"vchpopupbackgroundcolor"=>'000000',
					"vchtextcolor"=>'E46C3D',
					"vchlabelcolor"=>'ffff',
					"vchvideoicon"=>'',
					"vchtitlecolor"=>'E46C3D',
					"enumstatus"=>'A'
				);
				$this->AdminModel->Insertdomaintheme($themedata);


echo   $p= '.homepage{background-color: #000000 !important;}
		   .pagination .ng-binding {color: #E46C3D !important;}
		   .colorwhite.ng-binding {color: #E46C3D !important;}
		   .navigation-bar {color: #E46C3D !important;}
		   #cboxLoadedContent { background-color: #000000 !important;}
		   input[type="checkbox"] + span{ border: 2px solid #E46C3D !important;}
		   .check-box-container, .ng-binding, .dropdownlabel { color: #ffff !important;}
		';
		$a = fopen(public_path()."/css/theme".$lastid.".css", 'w');
		fwrite($a, $p);
		fclose($a);
		chmod(public_path()."/css/theme".$lastid.".css", 0644);

		$smalllogo = $request->file('smalllogo');
		$largelogo = $request->file('largelogo');
		$videologo = $request->file('videologo');
		$random = rand(100000000,1000000000);
		$destinationPath = 'upload/watermark';
		if(!empty($request->file('smalllogo'))){
		   $smallimage = "Small_".round(microtime(true)).$smalllogo->getClientOriginalName();
		   $smalllogo->move($destinationPath,$smallimage);
		}else{
			$smallimage = "smalllogo.png";
		}
		DB::table('tblwatermarklogo')->insert(['vchwatermarklogoname' =>$smallimage,'vchtype' => 'S','vchtransparency'=>'3','vchsiteid'=>$lastid,'enumstatus'=>'A','randomnumber'=>$random."1"]);


		if(!empty($request->file('largelogo'))){
		   $largeimage = "Large_".round(microtime(true)).$largelogo->getClientOriginalName();
		   $largelogo->move($destinationPath,$largeimage);
		}else{
			$largeimage = "largelogo.png";
		}
		DB::table('tblwatermarklogo')->insert(['vchwatermarklogoname' =>$largeimage,'vchtype' => 'L','vchtransparency'=>'3','vchsiteid'=>$lastid,'enumstatus'=>'A','randomnumber'=>$random."2"]);


		if(!empty($request->file('videologo'))){
		   $videoimage = "Large_".round(microtime(true)).$videologo->getClientOriginalName();
		   $videologo->move($destinationPath,$videoimage);
		}else{
			$videoimage = "videologo.png";
		}
		DB::table('tblwatermarklogo')->insert(['vchwatermarklogoname' =>$videoimage,'vchtype' => 'V','vchtransparency'=>'3','vchsiteid'=>$lastid,'enumstatus'=>'A','randomnumber'=>$random."3"]);

				return redirect('/admin/managedomains');
			 }
			  else{
				 $this->AdminModel->updatedomains($data,$request->intmanagesiteid);
				 return redirect('/admin/managedomains');

			 }

		}

    }
	public function addgroup(Request $request){
		$groupname = $request->groupname;
		if($groupname != ""){
			$checkgroup = DB::table('tbl_group')->where('groupname',$groupname)->first();
			if(empty($checkgroup)){
				DB::table('tbl_group')->insert(['groupname'=>$groupname]);
			}
		}
	}
	public function managedomains(Request $request){
		echo  $this->checklogin();
		$access = $this->accessPoint(7);
		  $responce=$this->AdminModel->domainslist();
		return view('admin.admin-managedomains',compact('responce','access'));
	}
		public function changedomainsstatus(Request $request){
		echo  $this->checklogin();
		$status = $request->status;
		$id = $request->id;
		if($status == 'Active'){
			$status = 'L';
		}else{
			$status = 'D';
		}
		$data = array(
			"status"=>$status
		);
		//echo $status;
		$this->AdminModel->updatedomains($data,$id);
	}
	  public function deletedomains(Request $request){
	   echo  $this->checklogin();
			$id=$_POST['id'];
	     $responce=$this->AdminModel->DeleteDomains($id);
	    }

	public function managefeature(Request $request){
	  echo $this->checklogin();
	 //$multisite =  $request->multisite;
		$access = $this->accessPoint(8);
	 $allvideo = DB::table('tbl_Video')->select('tbl_Video.IntId','tbl_Video.sortingorder','tbl_Video.vchsiteid','tbl_Video.feature','tbl_Video.intsetdefault','tbl_Video.VchResizeimage','tbl_Video.VchTitle','tbl_Video.VchVideothumbnail','tbl_Video.VchVideoName','tbl_Video.vchgoogledrivelink','tbl_Video.EnumType','tbl_Video.EnumUploadType','tbl_Video.VchFolderPath','releationtable.VchGenderTagid',DB::raw('(select tbl_Tagtype.vchTitle from tbl_Tagtype where tbl_Tagtype.Intid = releationtable.VchGenderTagid) as Gendercategory'),DB::raw('(select tbl_Tagtype.vchTitle from tbl_Tagtype where tbl_Tagtype.Intid = releationtable.VchRaceTagID) as Racecategory'),DB::raw('(select tbl_Tagtype.vchTitle from tbl_Tagtype where tbl_Tagtype.Intid = releationtable.VchCategoryTagID) as category'),DB::raw('group_concat(tbl_SearchcategoryVideoRelationship.VchSearchcategorytitle) as VchSearchcategorytitle'),DB::raw('(select  GROUP_CONCAT(tbl_SearchcategoryVideoRelationship.VchSearchcategorytitle) as `ColumnName` from tbl_SearchcategoryVideoRelationship where  tbl_SearchcategoryVideoRelationship.IntVideoID = tbl_Video.IntId) as group_category'))->leftjoin('tbl_Videotagrelations as releationtable', 'releationtable.VchVideoId', '=', 'tbl_Video.IntId')->leftjoin('tbl_SearchcategoryVideoRelationship', 'tbl_SearchcategoryVideoRelationship.IntVideoID', '=', 'tbl_Video.IntId')->leftJoin('tbl_Searchcategory', 'tbl_SearchcategoryVideoRelationship.IntCategorid', '=', 'tbl_Searchcategory.IntParent')->where('tbl_Video.feature','1');
	 	$search= $request->search;
	 if(!empty($_GET['search'])){

		 $allvideo = $allvideo->whereRaw('FIND_IN_SET('.$search.',tbl_Video.vchsiteid)');
		}

      $allvideo = $allvideo->orderBy('tbl_Video.IntId', 'desc')->groupBy('tbl_Video.IntId')->get();

	  foreach($allvideo as $all){
		//
		if(!empty($all->vchsiteid)){

			$siteid = explode(",",$all->vchsiteid);
			$res = DB::table('tbl_managesite')->select(DB::raw("GROUP_CONCAT(tbl_managesite.txtsiteurl SEPARATOR ', ') as sitename"))->whereIn('intmanagesiteid',$siteid)->first();

			$all->sitename = $res->sitename;
		}else{
			$all->sitename = "";
		}

	}

	$searchtags = DB::table('tbl_Searchcategory')->select('*')->get();
	$managesites = DB::table('tbl_managesite')->select('*')->get();
	 $alltags = DB::table('tbl_MasterTag')->select('tbl_MasterTag.IntId','tbl_MasterTag.VchColumnType','tbl_MasterTag.VchTitle',DB::raw('group_concat(tbl_Tagtype.vchTitle) as tagTitle,group_concat(tbl_Tagtype.IntId) as tagid'))->join('tbl_Tagtype', 'tbl_Tagtype.VchTypeID', '=', 'tbl_MasterTag.IntId')->groupBy('tbl_Tagtype.VchTypeID')->get();

	 $allvideorelation = array();
	$allsearchvideorelation = array();
	$getmanagevideodomains =DB::table('tbl_managesite')->select('*')->get();
	// print_r($getmanagevideodomains);
	// exit();
	$alldata = array("search"=>$search,"managesites"=>$managesites,"searchtags"=>$searchtags,"allvideo"=>$allvideo,'alltags'=>$alltags,'allvideorelation'=>$allvideorelation,'allsearchvideorelation'=>$allsearchvideorelation,'getmanagevideodomains'=>$getmanagevideodomains,'access'=>$access);

			return view('admin/admin-featuredtags')->with('allvideo', $alldata);
	    }

	public function changeorder(Request $request){
		$arr=json_decode($request->get('position'));
		$arrayno = array();
		foreach($arr as $arrs){
		 $val=$arrs->value;
		 $arraynot[] =  $arrs->id;
		$updatearr = array('sortingorder'=>$val);
		$updqry=DB::table('tbl_Video')->where('IntId', $arrs->id)->update($updatearr);
		}


		DB::table('tbl_Video')->whereNotIn('IntId', $arraynot)->update(array("sortingorder"=>0));

	}
	public function removefeature(Request $request){
		$updatearr = array('feature'=>'0',"sortingorder"=>0);
		$updqry=DB::table('tbl_Video')->where('IntId', $request->id)->update($updatearr);
	}
	public function changewatermark(){
		$getvideodata = DB::table('tbl_Video')->where('EnumType','V')->orderby('IntId','DESC')->get();
			foreach($getvideodata as $videodata){
				$siteid=explode(',',$videodata->vchsiteid);
				for($i=0;$i < count($siteid);$i++){
					$Watermark = DB::table('tblwatermarklogo')->where('vchtype','V')->where('vchsiteid',$siteid[$i])->where('enumstatus','A')->first();
					if(!empty($Watermark)){
						if (!Is_Dir(public_path().'/'.$videodata->VchFolderPath.'/'.$Watermark->vchsiteid)){
							mkdir(public_path().'/'.$videodata->VchFolderPath.'/'.$Watermark->vchsiteid, 0777);
						}
						if(file_exists(public_path().'/'.$videodata->VchFolderPath.'/'.$Watermark->vchsiteid.'/watermark.mp4')){
							$file_to_delete = public_path().'/'.$videodata->VchFolderPath.'/'.$Watermark->vchsiteid.'/watermark.mp4';
							\File::delete($file_to_delete);
						}
							$watermarklogo = public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname;
							shell_exec('ffmpeg -i upload/'.'videosearch/'.$videodata->IntId.'/'.$videodata->VchVideoName.' -i '.$watermarklogo.' -filter_complex  "overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2" -codec:a copy upload/videosearch/'.$videodata->IntId.'/'.$siteid[$i].'/watermark.mp4');
							echo "Cron is working fine for".$videodata->IntId;
					}

				}

			}
	}

	public function  Manageuser(Request $request){
		 echo $this->checklogin();
		 $access = $this->accessPoint(9);
		 $search = $request->search;
		 $domain = $request->domain;
		 $response =  DB::table('tbluser')->leftjoin('tbl_buypackage','tbluser.intuserid','tbl_buypackage.package_userid')->join('tbl_managesite','tbluser.vchsiteid','tbl_managesite.intmanagesiteid')
		 //->where('tbl_buypackage.status','A')
		//->whereDate('tbl_buypackage.package_expiredate','>',date('Y-m-d'))
			->orderBy('tbluser.intuserid', 'DESC')->groupBy('tbluser.intuserid');
		 if(!empty($search)){
			 $response->where(DB::raw('CONCAT_WS(" ",vchfirst_name,vchlast_name,vchemail,txtsiteurl)'),'like',  "%$search%");
		 }
		 if(!empty($domain)){
			 $response->where('vchsiteid',$domain);
		 }

		$response =  $response->paginate(20)->appends('search',$search)->appends('domain',$domain);
		$availablecount = 0;
		foreach($response as $res){
			$packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',$res->intuserid)->whereDate('package_expiredate','>',date('Y-m-d'))->get();
				if(!$packageavailable->isEmpty()){
					$packageid ="";
					$buyid ="";
					$availablecount = 0;
					$total_credits = 0;
					$used_credits = 0;
					foreach($packageavailable as $pack){
						if($pack->package_download < $pack->package_count){
								 $total_credits += $pack->package_count;
								 $used_credits += $pack->package_download;
							if(empty($packageid)){
								$packageid = $pack->package_id;
								$buyid = $pack->buy_id;



							}
						}
					}

							 $availablecount = $total_credits - $used_credits;

				}else{

					$availablecount = 0;
				}

				$res->availablecount=$availablecount;

		}
	//	exit;

		 $domains = DB::table('tbl_managesite')->get();
		 return view('admin/user/admin-manageuser',compact('response','search','domains','domain','access'));
	}

	public function removeuser(Request $request){
		 echo $this->checklogin();
		 $id = $request->id;
		 $deleteuser = DB::table('tbluser')->where('intuserid', $id)->delete();
		 $value = array('response'=>1);
		echo json_encode($value);


	}

	public function removecustom(Request $request){
		 echo $this->checklogin();
		 $id = $request->id;
		 $deleteuser = DB::table('tblcustom')->where('intid', $id)->delete();
		 $value = array('response'=>1);
		echo json_encode($value);


	}
	public function changestatus(Request $request){
		 echo $this->checklogin();
		 $id = $request->id;
		 $status = $request->status;
		 if($status=='Active'){
			$sta='D';
		 }else{
			$sta='A';
		 }

		DB::table('tbluser')->where('intuserid', $id)->update(['enumstatus'=>$sta]);
		 $value = array('response'=>1);
		echo json_encode($value);


	}

	public function managecustom(Request $request){
		 echo $this->checklogin();
		  $access = $this->accessPoint(10);
		 $search = $request->search;
		 $domain = $request->domain;
		 $response =  DB::table('tblcustom')->leftjoin('tbl_managesite','tblcustom.vchsiteid','tbl_managesite.intmanagesiteid');
		 if(!empty($search)){
			 $response->where(DB::raw('CONCAT_WS(" ",email,description,txtsiteurl)'),'like',  "%$search%");
		 }
		 if(!empty($domain)){
			 $response->where('vchsiteid',$domain);
		 }

		$response =  $response->paginate(20)->appends('search',$search)->appends('domain',$domain);

		 $domains = DB::table('tbl_managesite')->get();
		 return view('admin/admin-custom',compact('response','search','domains','domain','access'));

	}
	public function managedownload($id,Request $request){
		echo $this->checklogin();
		 $search = $request->search;
		 $domain = $request->domain;
		 $imgtype = $request->type;
		 $response =  DB::table('tbl_download')->leftjoin('tbl_Video','tbl_download.video_id','tbl_Video.IntId')->leftjoin('tbl_managesite','tbl_download.site_id','tbl_managesite.intmanagesiteid')->where('tbl_download.user_id',$id);
		  if(!empty($search)){
			 $response->where(DB::raw('CONCAT_WS(" ",VchTitle,txtsiteurl)'),'like',  "%$search%");
		 }
		  if(!empty($domain)){
			 $response->where('vchsiteid',$domain);
		 }
		 if(!empty($imgtype)){
			 $response->where('tbl_Video.EnumType',$imgtype);
		 }
		$response =  $response->paginate(20)->appends('search',$search)->appends('domain',$domain)->appends('type',$imgtype);
		 $domains = DB::table('tbl_managesite')->get();
		 return view('admin/admin-downloads',compact('response','search','domains','domain','imgtype'));
	}
	public function managebuypack($id,Request $request){
		echo $this->checklogin();
		 $search = $request->search;
		 $response =  DB::table('tbl_payment')->join('tbl_buypackage','tbl_payment.payment_id','tbl_buypackage.payment_id')->leftjoin('tbl_plan','tbl_payment.plan_id','tbl_plan.plan_id')->where('tbl_payment.user_id',$id);
		  if(!empty($search)){
			 $response->where(DB::raw('CONCAT_WS(" ",package_name)'),'like',  "%$search%");
		 }

		$response =  $response->paginate(20)->appends('search',$search);
		// $domains = DB::table('tbl_managesite')->get();
		 return view('admin/admin-buypackage',compact('response','search'));
	}
	public function managepayment($id,Request $request){
		echo $this->checklogin();

		 $search = $request->search;
		 $response =  DB::table('tbl_payment')->leftjoin('tbl_buypackage','tbl_payment.payment_id','tbl_buypackage.payment_id')->leftjoin('tbl_plan','tbl_payment.plan_id','tbl_plan.plan_id')->where('tbl_payment.user_id',$id);
		  if(!empty($search)){
			 $response->where(DB::raw('CONCAT_WS(" ",strip_transactionid,strip_status)'),'like',  "%$search%");
		 }

		$response =  $response->paginate(20)->appends('search',$search);
		// $domains = DB::table('tbl_managesite')->get();
		 return view('admin/admin-payment',compact('response','search'));
	}
	public function siteplans(Request $request, $id){
		echo $this->checklogin();
			if($_POST){
			$plan_id = $request->plan_id;
			$plan_type = $request->plan_type;
			$plan_download = $request->plan_download;
			$plan_price = $request->plan_price;
			$siteid = $request->siteid;
			$plan_description = $request->plan_description;
			$conversion_rate = $request->conversion_rate;
			$p_name = $request->plan_name;
			$yearly_discount = $request->yearly_discount;

		// print_r($_POST);
		// exit;

			for($i=0; $i<count($plan_id); $i++){
				$plan_name='';
			if($plan_type[$i]=='M'){
				$plan_name=$p_name[$i];
			}

				$data = [
					"plan_title"=>$plan_name,
					"plan_name"=>$plan_download[$i]." Credits",
					"plan_download"=>$plan_download[$i],
					"plan_type"=>$plan_type[$i],
					"plan_purchase"=>$plan_type[$i],
					"plan_description"=>$plan_description[$i],
					"plan_time"=>'1',
					"plan_status"=>'A',
					"plan_price"=>$plan_price[$i],
					"plan_siteid"=>$siteid,
					"conversion_rate"=>$conversion_rate,
					"yearly_discount"=>$yearly_discount,
					"plan_createdate"=>date('Y-m-d H:i:s')

				];





				if(empty($plan_id[$i])){
					DB::table('tbl_plan')->insert($data);
					$newplanid  = DB::getPdo()->lastInsertId();
				}else{
					/* echo $plan_id[$i];
					exit; */
					DB::table('tbl_plan')->where('plan_id', $plan_id[$i])->update($data);
					$newplanid  = $plan_id[$i];
				}

				/* STOCK INFORMATION INSERT AND UPDATE  */
				for($k=0;$k<count($request->stock);$k++){

					$stocktypeid=$request->stocktypeid[$k];
					$contentcatid=$request->contentcatid[$k];
					$stock=$request->stock[$k];
					$getstockinfo = DB::table('tblstock')->where('plan_id',$newplanid)->where('stocktype_id',$stocktypeid)->where('contentcat_id',$contentcatid)->first();


						$stockdata = [
								"stocktype_id"=>$stocktypeid,
								"contentcat_id"=>$contentcatid,
								"stock"=>$stock
						];

					if(!empty($getstockinfo)){
						DB::table('tblstock')->where('plan_id',$newplanid)->where('stocktype_id',$stocktypeid)->where('contentcat_id',$contentcatid)->update($stockdata);
					}else{
						$stockdata['created_date'] = date('Y-m-d H:i:s');
						$stockdata['plan_id'] = $newplanid;
						DB::table('tblstock')->insert($stockdata);
					}
				}
				/* STOCK INFORMATION INSERT AND UPDATE  */

			}

		}


		$plans = DB::table('tbl_plan')->where('plan_siteid',$id)->get();
		$stockrescolwise='';
		$conversion_rate='';
		if(!empty($plans[0]->conversion_rate)){
			$conversion_rate=$plans[0]->conversion_rate;

		}
		if(!empty($plans[0]->plan_id)){
		$stockresponse = DB::table('tblstock')->leftJoin('tblstocktype','tblstock.stocktype_id','tblstocktype.stock_type_id')->where('plan_id',$plans[0]->plan_id)->where('tblstocktype.status','A')->groupBy('stocktype_id')->get();
			if(count($stockresponse)!=0){
				$stockrescolwise=[];
				foreach($stockresponse as $stockresponses){
					for($i=1;$i<4;$i++){
						$stockrescolwise[]= DB::table('tblstock')->where('plan_id',$plans[0]->plan_id)->where('stocktype_id',$stockresponses->stocktype_id)->where('contentcat_id',$i)->get();
					}
				}
			}else{
				$stockresponse = DB::table('tblstocktype')->get();
			}
		}else{
			$stockresponse = DB::table('tblstocktype')->get();
		}
		return view('admin/admin-plans',compact('plans','conversion_rate','id','stockresponse','stockrescolwise'));
	}
	public function addplan($id=""){
		$response='';
		//$stockresponse='';
		$stockrescolwise='';
		$mexplode = explode("_",$id);
		$id = $mexplode[1];
		$so = $mexplode[0];
		if($so == 'p'){
			$response = DB::table('tbl_plan')->where('plan_id',$id)->first();
			$stockresponse = DB::table('tblstock')->leftJoin('tblstocktype','tblstock.stocktype_id','tblstocktype.stock_type_id')->where('plan_id',$id)->groupBy('stocktype_id')->get();
			if(count($stockresponse)!=0){
				$stockrescolwise=[];
				foreach($stockresponse as $stockresponses){
					for($i=1;$i<4;$i++){
						$stockrescolwise[]= DB::table('tblstock')->where('plan_id',$id)->where('stocktype_id',$stockresponses->stocktype_id)->where('contentcat_id',$i)->get();
					}
				}
			}else{
				$stockresponse = DB::table('tblstocktype')->get();

			}
			}else{
			$stockresponse = DB::table('tblstocktype')->get();
		}

		return view('admin/admin-addplans',compact('id','so','response','stockresponse','stockrescolwise'));
	}

	public function createplan(Request $request){
		$siteid = $request->site_id;
		$data = [
			"plan_name"=>$request->planname,
			"plan_description"=>$request->plandescription,
			"plan_time"=>1,
			"plan_price"=>$request->planprice,
			"plan_download"=>$request->plandownload,
			"plan_type"=>$request->plansubscription,
			"plan_purchase"=>$request->plansubscription,
			"plan_status"=>$request->planstatus
		];

		if($request->type == 's'){
			$data['plan_siteid'] = $request->siteid;
			$data['plan_createdate'] = date('Y-m-d H:i:s');
			$planid=DB::table('tbl_plan')->insertGetId($data);
			for($i=0;$i<count($request->stock);$i++){
				$stocktypeid=$request->stocktypeid[$i];
				$contentcatid=$request->contentcatid[$i];
				$stock=$request->stock[$i];
				$date=date('Y-m-d H:i:s');
				$stockdata = [
								"plan_id"=>$planid,
								"stocktype_id"=>$stocktypeid,
								"contentcat_id"=>$contentcatid,
								"stock"=>$stock,
								"created_date"=>$date
						];
					DB::table('tblstock')->insert($stockdata);

			}

		}else{
			DB::table('tbl_plan')->where('plan_id', $request->planid)->update($data);
			for($i=0;$i<count($request->stock);$i++){
				 $stocktypeid=$request->stocktypeid[$i];
				 $contentcatid=$request->contentcatid[$i];
				 $stock=$request->stock[$i];
				 $stockid=$request->stockid[$i];
					$stockdata = [
								"plan_id"=>$request->planid,
								"stocktype_id"=>$stocktypeid,
								"contentcat_id"=>$contentcatid,
								"stock"=>$stock,

						];
				if($stockid!=''){
					DB::table('tblstock')->where('intid', $stockid)->update($stockdata);
				}else{
					$stockdata['created_date'] = date('Y-m-d H:i:s');
					DB::table('tblstock')->insert($stockdata);
				}
			}
		}
		return redirect('/admin/siteplans/'.$siteid);
	}
	public function removeplan(Request $request){
		$plan = $request->plan;
		DB::table('tbl_plan')->where('plan_id',$plan)->delete();
		DB::table('tblstock')->where('plan_id',$plan)->delete();
	}
	public function deleteplan($id,$sid){
		DB::table('tbl_plan')->where('plan_id',$id)->delete();
		return redirect('/admin/siteplans/'.$sid);
	}

public function change_background(Request $request){
		header('Content-Type: image/png');
		//copy('https://dev.fox-ae.com/showimage/3701/1/org1588022082.png?=673515041', 'flower.jpg');

		$content = file_get_contents('https://dev.fox-ae.com'.$request->src);
file_put_contents('/imagick/flower.jpg', $content);
exit;
		if (file_exists('colorImage.png')){
			unlink('colorImage.png');
		}
		//	header('Content-Type: image/png');
			$colors= array("0","0","0");

			$cutter=imagecreatefromjpeg($request->src);
			$remove = imagecolorallocate($cutter, $colors[0], $colors[1], $colors[2]);
			imagecolortransparent($cutter, $remove);
			imagepng($cutter, 'colorImage.png');

			$color = $request->color;
			$imgs22 = 'cb.png';
			$cmd = '-fuzz 42% -fill none -draw "matte 0,0 floodfill" -background red -flatten';
			exec("convert colorImage.png $cmd $imgs22 ");





		}

		public function managecredits(Request $request){
			$current_packagecount = $request->current_packagecount;
			//$packageid = $request->package_id;

			$exp_days = $request->exp_days;
			/* $response = DB::table('tbl_buypackage')->where('package_id',$packageid)->where('status','A')->whereDate('package_expiredate','>',date('Y-m-d'))->first();

			if(empty($response)){ */

				$response = DB::table('tbl_buypackage')->where('status','A')->where('site_id',$request->site_id)->where('package_userid',$request->user_id)->whereDate('package_expiredate','>',date('Y-m-d'))->first();
				$packageid = $response->package_id;
			//}


			if(!empty($response)){
			$credit = $request->credit;


			if($request->sign=='+'){
				$act='Add';
			 $credit2 = $request->sign.$request->credit;

			$change_credit=$response->package_count + $credit;
			}else if($request->sign=='-'){
				$act='Remove';
				if($response->package_count>0 && $response->package_count > $credit ){
					if($response->extra_credit>0){
						$credit2 = $request->sign.$request->credit;
					}else{
						$credit2 = $request->sign.$request->credit;

					}
				$change_credit=$response->package_count - $credit;
				}else{
					return redirect()->back()->with('error','Dont have enough credit to remove');

				}

			}
			$packagedata = [
								"package_count"=>$change_credit,
								"extra_credit"=>$credit2,
						];
					 if(!empty($exp_days)){

						 $packagedata['package_expiredate'] = date('Y-m-d H:i:s',strtotime($response->package_expiredate.'+'.$exp_days.'days'));
					 }


				DB::table('tbl_buypackage')->where('package_id', $packageid)->update($packagedata);

			}else{
				return back()->with('error','User dont have plan to add or remove credits');

			}

			return redirect('/admin/manageuser/')->with('Success',"Add or remove credits successfully");

		}

		public function managelegalpages($siteid){
			$response = DB::table('tblfaq')->where('siteid',$siteid)->get();
			$contactresponse = DB::table('tblcontact')->join('tbluser', 'tbluser.intuserid', '=', 'tblcontact.userid')->where('tblcontact.issue_archived','N')->where('tblcontact.siteid',$siteid)->get();

			$archivedresponse = DB::table('tblcontact')->join('tbluser', 'tbluser.intuserid', '=', 'tblcontact.userid')->where('tblcontact.issue_archived','Y')->where('tblcontact.siteid',$siteid)->get();
			$legaldocsresponse = DB::table('tbl_legaldocuments')->where('siteid',$siteid)->first();


		return view('/admin/managepages',compact('siteid','response','contactresponse','legaldocsresponse','archivedresponse'));

		}

		public function managefaq(Request $request){

			$siteid=$request->siteid;
				for($k=0;$k<count($request->question);$k++){

					$question=$request->question[$k];
					$answer=$request->answer[$k];
					if(!empty($request->faqid[$k])){
					$faqid=$request->faqid[$k];
					}else{
						$faqid='';

					}
					//$getstockinfo = DB::table('tblstock')->where('plan_id',$newplanid)->where('stocktype_id',$stocktypeid)->where('contentcat_id',$contentcatid)->first();


						$faqdata = [
								"question"=>$question,
								"answer"=>$answer,
								"siteid"=>$siteid
						];

					if(!empty($faqid)){
						DB::table('tblfaq')->where('id',$faqid)->update($faqdata);
					}else{
						$faqdata['create_date'] = date('Y-m-d H:i:s');
						DB::table('tblfaq')->insert($faqdata);
					}
				}

			return redirect('/admin/managepages/'.$siteid)->with('Success',"Question and answer inserted or updated successfully");

		}

		public function Deleteqa(Request $request){

		DB::table('tblfaq')->where('id',$request->id)->delete();
		return 1;
	}
	public function issue_archived(Request $request){
		$siteid=$request->siteid;
		$archiveddata = [
				"issue_archived"=>$request->issue_archived,

			];
		DB::table('tblcontact')->where('id',$request->issueid)->update($archiveddata);
		return redirect('/admin/managepages/'.$siteid)->with('Success',"Issue archived successfuly");
	}

	public function contactrespond_email(Request $request){
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		$siteid=$request->siteid;
			$responddata = [
				"respond_date"=>$request->respond_date,
				"respond_status"=>$request->respond_status,
				"respond_person"=>$request->respond_person,
			];
			DB::table('tblcontact')->where('id',$request->issueid)->update($responddata);
			return redirect('/admin/managepages/'.$siteid);
	}

	public function managedocuments(Request $request){
		$termscondition=$request->termscondition;
		$privacypolicy=$request->privacypolicy;
		$userlicence=$request->userlicence;
		$about=$request->about;
		$siteid=$request->siteid;
		$id=$request->id;

				$faqdata = [
								"termscondition"=>$termscondition,
								"privacypolicy"=>$privacypolicy,
								"userlicence"=>$userlicence,
								"about"=>$about,
								"siteid"=>$siteid
						];
			if(!empty($id)){
				DB::table('tbl_legaldocuments')->where('id',$id)->update($faqdata);

				return redirect('/admin/managepages/'.$siteid)->with('Success',"Legal documents updated successfully");
			}else{
				DB::table('tbl_legaldocuments')->insert($faqdata);
				return redirect('/admin/managepages/'.$siteid)->with('Success',"Legal documents inserted successfully");
			}


	}

		public function exportuserlist(Request $request){
			$startdate=$request->startdate;
			$enddate=$request->enddate;
			$domains=$request->domain;
		$filename = "Userlist".date("Y_M_D").".csv";
		$fp = fopen('php://output', 'w');
		$users = DB::table('tbluser')->leftjoin('tbl_payment','tbl_payment.user_id','tbluser.intuserid')->leftjoin('tbl_managesite','tbl_managesite.intmanagesiteid','tbluser.vchsiteid')->leftjoin('tbl_plan','tbl_payment.plan_id','tbl_plan.plan_id')->whereIn('tbluser.vchsiteid', $domains)->whereBetween('created_date', [$startdate, $enddate])->get();

		$columns = array('User Name','User Email','Website Name','Transaction ID','Dollar Amount','Package Name','Credit Amount','Date');

		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$file = fopen('php://output', 'w');
        fputcsv($file, $columns);
        foreach($users as $user) {
			$payment_date='';
			if(!empty($user->create_at)){
				$payment_date=date('Y-m-d', strtotime($user->create_at));
			}else{
				$payment_date='';
			}
			if($user->plan_type=='M'){



			}
            fputcsv($file, array($user->vchfirst_name,$user->vchemail,$user->txtsiteurl,$user->strip_transactionid,$user->strip_amount,$user->strip_packagename,$user->plan_name,$payment_date));
        }
        fclose($file);
		exit;
	}

	public function savebackground(Request $request){
			if ($request->hasFile('bg_upload')) {
					$bg_img  = $request->file('bg_upload');
					$bg_imgName   = time() . '.' . $bg_img->getClientOriginalExtension();
					Image::make($bg_img->getRealPath())->save(public_path('images/'.$bg_imgName));
			}else{
			$bg_imgName = $request->bg_image;
			 }

			if(!empty($request->multisite)){

				 $multisite= implode(",",$request->multisite);
			 }

		$bgdata = [
								"background_title"=>$request->background_title,
								"background_img"=>$bg_imgName,
								"siteid"=>$multisite
						];
		if(empty($request->bg_id)){
			DB::table('tbl_backgrounds')->insert($bgdata);
		}else{
			DB::table('tbl_backgrounds')->where('bg_id',$request->bg_id)->update($bgdata);
		}
		return redirect('/admin/websitemanagement');

	}

	 function hex2rgb($hex) {
		//$hex = str_replace("#", "", $hex);

			if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
			} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
			}
			$rgb = array($r, $g, $b);

			return $rgb; // returns an array with the rgb values
	}
	public function demo(){
		 $RGB_color = $this->hex2rgb('#5B5C5C');
		echo $Final_Rgb_color = implode(",", $RGB_color);
	}
	public function manageAdminUser(Request $request){
		$domains = DB::table('tbl_managesite')->get();
		$search = $request->search;
		$response = DB::table('tblAdminMaster')->select('tblAdminMaster.*','tbl_roles.name','tbl_roles.id as role_id')
			->leftjoin('tbl_roles','tbl_roles.id','tblAdminMaster.vchRole');
			if(!empty($search)){
				$response->where('tbl_roles.id',$search);
			}

			$response = $response->paginate(20)->appends('search',$search);

		$domain = $request->domain;
		$access = $this->accessPoint(11);
		return view('admin/manageadminuser',compact('domains','search','domain','response','access'));
	}
	public function manageapi(Request $request){
		$api = DB::table('tblapidetail')->where('id',1)->first();
		return view('admin/manageapi',compact('api'));
	}
	public function updateapi(Request $request){
		$data = [
			'stripe_key'=>$request->stripe_key,
			'stripe_secret'=>$request->stripe_secret
		];

		if(!empty($request->apiid)){
			DB::table('tblapidetail')->where('id', $request->apiid)->update($data);
			return redirect()->to('/admin/manage_api')->with('success', 'API update successfully');
		}else{
			DB::table('tblapidetail')->insert($data);
			return redirect()->to('/admin/manage_api')->with('success', 'API Insert successfully');
		}

	}
	public function addadminuser($id=""){
		$roles = DB::table('tbl_roles')->get();
		if(!empty($id)){
			$respone = DB::table('tblAdminMaster')->where('intAdminID',$id)->first();
		}else{
			$respone = [];
		}
		return view('admin/addadminuser',compact('respone','roles'));
	}
	public function admincreate(Request $request){
		$data = [
			'vchName'=>$request->name,
			'vchUserName'=>$request->name,
			'vchEmail'=>$request->email,
			'vchPassword'=>md5($request->password),
			'showpassword'=>$request->password,
			'vchRole'=>$request->role,
			'enumStatus'=>$request->status
		];
		if(empty($request->userid)){
			$response = DB::table('tblAdminMaster')->insert($data);
			$lastinsertid = DB::getPdo()->lastInsertId();
			return redirect()->to('/admin/manageadminuser')->with('success', 'Admin user add successfully');
		}else{
			$response = DB::table('tblAdminMaster')->where('intAdminID', $request->userid)->update($data);
			return redirect()->to('/admin/manageadminuser')->with('success', 'Admin user update successfully');
		}
	}
	public function adminroles(Request $request){
		$access = $this->accessPoint(11);
		$reponse = DB::table('tbl_roles')->paginate(20);
		return view('admin/admin-roles',compact('reponse','access'));
	}
	public function addroles($id=""){



		$permissions = DB::table('tbl_module')->get();
		if(!empty($id)){
			$respone = DB::table('tbl_roles')->where('id',$id)->first();
			$permissione = DB::table('tbl_permission')->where('user_id',$id)->get();
			$permissione = json_decode(json_encode($permissione), true);
		}else{
			$respone = [];
			$permissione =  [];
		}


		return view('admin/add-roles',compact('permissions','respone','permissione'));
	}
	public function createroles(Request $request){
		$data = [
			'name'=>$request->name
		];
		if(empty($request->roleid)){
			$data['created_at'] =  date('Y-m-d H:i:s');
			$response = DB::table('tbl_roles')->insert($data);
			$lastinsertid = DB::getPdo()->lastInsertId();
			$module = $request->module;
			$type = $request->type;
			for($i=0; $i<count($module); $i++){
				$role = "";
				if(!empty($type[$i])){
					$role = implode(",",$type[$i]);
				}
				$datapre = [
					'user_id'=>$lastinsertid,
					'permission'=>$module[$i],
					'role'=>$role,
					'created_at'=>date('Y-m-d H:i:s')
				];
				DB::table('tbl_permission')->insert($datapre);
			}
			return redirect()->to('/admin/roles')->with('success', 'Role add successfully');
		}else{
			$data['updated_at'] =  date('Y-m-d H:i:s');
			DB::table('tbl_roles')->where('id', $request->roleid)->update($data);
			$preid = $request->preid;
			$module = $request->module;
			$type = $request->type;
			for($i=0; $i<count($preid); $i++){
				$role = "";
				if(!empty($type[$i])){
					$role = implode(",",$type[$i]);
				}

				$datapre = [
					'user_id'=>$request->roleid,
					'permission'=>$module[$i],
					'role'=>$role
				];
				if(empty($preid[$i])){
					$datapre['created_at'] =  date('Y-m-d H:i:s');
					DB::table('tbl_permission')->insert($datapre);
				}else{
					$datapre['updated_at'] =  date('Y-m-d H:i:s');
					DB::table('tbl_permission')->where('id', $preid[$i])->update($datapre);
				}
			}
			return redirect()->to('/admin/roles')->with('success', 'Role update successfully');
		}
	}
	public function deleterole($id){
		DB::table('tbl_roles')->where('id', $id)->delete();
		DB::table('tbl_permission')->where('user_id', $id)->delete();
		return redirect()->to('/admin/roles')->with('success', 'Role deleted successfully');
	}
	public function deleteadminuser($id){
		DB::table('tblAdminMaster')->where('intAdminID',$id)->delete();
		return redirect()->to('/admin/manageadminuser')->with('success', 'User deleted successfully');
	}
	public function permissionUser(){
		$response = DB::table('tbl_roles')
		->leftjoin('tbl_permission','tbl_roles.id','tbl_permission.user_id')
		->leftjoin('tbl_module','tbl_module.id','tbl_permission.permission')
		->where('tbl_roles.id', Session::get('vchRole'))
		->where('tbl_permission.role','!=','')
		->get();
		return json_decode(json_encode($response), true);
	}
	public function accessPoint($id){
		$response = $this->permissionUser();
		foreach($response as $res){
			if($res['permission'] == $id){
				return  $res['role'];
			}
		}
	}
	public function RedirectNoPermission($id){
		$response = DB::table('tbl_permission')->where('user_id', Session::get('vchRole'))->where('permission', $id)->where('tbl_permission.role','!=','')->first();

		if(empty($response)){
			$notfind = $this->permissionUser();
			foreach($notfind as $find){
				if (strpos($find['role'], '2') !== false) {
					// header("Location: ".$find['url']);
					// exit;
				}
			}
		}


		if(!empty($response)){
			if (strpos($response->role, '2') !== false){

			}else{
				$notfind = $this->permissionUser();
				foreach($notfind as $find){
					if (strpos($find['role'], '2') !== false) {
						// header("Location: ".$find['url']);
						// exit;
					}
				}
			}
		}
	}

	public static function getmenuaccess(){
        return DB::table('tbl_roles')
        ->leftjoin('tbl_permission','tbl_roles.id','tbl_permission.user_id')
        ->leftjoin('tbl_module','tbl_module.id','tbl_permission.permission')
        ->where('tbl_roles.id', Session::get('vchRole'))
        ->where('tbl_permission.role','!=','')
        ->get();
	}
	public function gettingroleinfo(Request $request){
		$id = $request->id;
		$respone = DB::table('tbl_roles')->where('id',$id)->first();
		$permissione = DB::table('tbl_permission')->where('user_id',$id)->get();
		$permissione = json_decode(json_encode($permissione), true);
		$permissions = DB::table('tbl_module')->get();

		$htmlcontect = '<div class="form-group" style="height: 500px;overflow: auto;">
												<input type="hidden" name="permissionid" >';
													 $i = 0;
													foreach($permissions as $permission){

														$currentarray = "";
														$preid = "";
														if(!empty($permissione)){
															if(!empty($permissione[$i])){
																$currentarray = explode(",",$permissione[$i]['role']);
																$preid = $permissione[$i]['id'];
															}
														}

													$htmlcontect .= '<div class="roles-permission">
													<div class="checkbox">
													  <label>'.$permission->name.'</label>
													</div>
														<div class="roles-permission-sub">
															<div class="checkbox">
													<label>';

													if(!empty($currentarray)){
														if (in_array(1, $currentarray)){
															$htmlcontect .= '<i class="fa fa-check-square-o" aria-hidden="true"></i>';
														}else{
															$htmlcontect .= '<i class="fa fa-times" aria-hidden="true"></i>';
														}
													}else{
														$htmlcontect .= '<i class="fa fa-times" aria-hidden="true"></i>';
													}

													$htmlcontect .= '<span  class="m-left">Add / Edit / Delete</span></label>
															</div>
															<div class="checkbox">
																<label>';
																if(!empty($currentarray)){
														if (in_array(2, $currentarray)){
															$htmlcontect .= '<i class="fa fa-check-square-o" aria-hidden="true"></i>';
														}else{
															$htmlcontect .= '<i class="fa fa-times" aria-hidden="true"></i>';
														}
													}else{
														$htmlcontect .= '<i class="fa fa-times" aria-hidden="true"></i>';
													}
																$htmlcontect .= '<span class="m-left"> View </span></label>
															</div>
														</div>
													</div>';
														$i++;
													}
												$htmlcontect .= '</div>';
												return $htmlcontect;

	}


	public function discountlist(){
		$response = DB::table('tbl_discount')->select('tbl_discount.*','tbl_managesite.vchsitename')->leftjoin('tbl_managesite','tbl_discount.domain_id','tbl_managesite.intmanagesiteid')->where('delete_status','N')
		->orderBy('id', 'DESC')->paginate(20);
		return view('admin/discount/discount-list',compact('response'));
	}
	public function creatediscount(){
		$sitelists = DB::table('tbl_managesite')->get();
		return view('admin/discount/discount-create',compact('sitelists'));
	}

	public function discountadd(Request $request){
		$get = $_POST;
		unset($get['_token']);
		$get['place'] = '';
		if(!empty($_POST['place'])) {
			$get['place'] = implode(',',$_POST['place']);
		}

		$get['tier'] = '';
		if(!empty($_POST['tier'])) {
			$get['tier'] = implode(',',$_POST['tier']);
		}

		$get['content'] = '';
		if(!empty($_POST['content'])) {
			$get['content'] = implode(',',$_POST['content']);
		}


		$get['created_at'] = date('Y-m-d H:i:s');
		DB::table('tbl_discount')->insert($get);
		$id = DB::getPdo()->lastInsertId();


		return redirect()->to('/admin/discountlist')->with('success', 'Coupon Create successfully');
	}

	public function discountedit($id){
		$sitelists = DB::table('tbl_managesite')->get();
		$response = DB::table('tbl_discount')->select('tbl_discount.*')->where('tbl_discount.id',$id)->first();
		return view('admin/discount/discount-edit',compact('sitelists','response'));
	}

	public function discountupdate($id) {
		$get = $_POST;
		unset($get['_token']);
		$get['place'] = '';
		if(!empty($_POST['place'])) {
			$get['place'] = implode(',',$_POST['place']);
		}

		$get['tier'] = '';
		if(!empty($_POST['tier'])) {
			$get['tier'] = implode(',',$_POST['tier']);
		}

		$get['content'] = '';
		if(!empty($_POST['content'])) {
			$get['content'] = implode(',',$_POST['content']);
		}

		DB::table('tbl_discount')->where('id', $id)->update($get);


		return redirect()->to('/admin/discountlist')->with('success', 'Coupon update successfully');
	}

	public function deleteCoupon($id) {
		$get = [
			'delete_status'=>'Y'
		];
		DB::table('tbl_discount')->where('id', $id)->update($get);
		//DB::table('tbl_discount')->where('id', $id)->delete();
		return redirect()->to('/admin/discountlist')->with('success', 'Coupon delete successfully');
	}


	public function sendEmailUser(Request $request){

		$to_email = $request->to_email;
		$to_subject = $request->to_subject;
		$message = $request->message;
		$users_id = explode(",",$request->users_id);


		if($to_email == 'ALL'){
			$response = DB::table('tbluser')->join('tbl_managesite','tbluser.vchsiteid','tbl_managesite.intmanagesiteid')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->get();
		} else {
			$response = DB::table('tbluser')->join('tbl_managesite','tbluser.vchsiteid','tbl_managesite.intmanagesiteid')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->whereIn('tbluser.intuserid',$users_id)->get();
		}


		foreach($response as $res){

			$data = array(
				'vchsite'=>$res->vchsitename,
				'message'=>$message,
			);
			$data2 = array(
				'email'	=> $res->vchemail,
				'emailfrom'	=> $res->vchemailfrom,
				'vchsitename'	=> $res->vchsitename,
				'subject'=> $to_subject,
			);


			$data['vchsitename'] = $res->vchsitename;
			$data['vchfirst_name'] = $res->vchfirst_name .' '.$res->vchlast_name;
			$data['siteurl'] = "https://".$res->txtsiteurl;
			$data['vlogo'] =  "https://".$res->txtsiteurl."/images/".$res->Vchthemelogo;
			$data['surface']=$res->surface;
			$data['surfacetext_iconcolor']=$res->surfacetext_iconcolor;
			$data['primary_color']=$res->primary_color;
			$data['primarytext_iconcolor']=$res->primarytext_iconcolor;
			$data['hyperlink']=$res->hyperlink;
			$data['bgtext_iconcolor']=$res->bgtext_iconcolor;

			Mail::send('email.sendemail',['data' => $data], function ($message) use ($data2, $data) {
				$message->from($data2['emailfrom'],$data2['vchsitename']);
				$message->to($data2['email']);
                $message->subject($data2['subject'] ?? 'Hey '.$data['vchfirst_name']);
            });

		}
		return redirect()->to('/admin/manageuser')->with('success', 'Email sent successfully');
	}

    public function scheduleImageCaching(Request $request)
    {
        if($job = DB::table('jobs')->where('payload', 'like','%UpdateDomainPreviewImagesJob%')->first()) {
            if(data_get($job, 'attempts', 0) > 0) {
                DB::table('jobs')->where('id', $job->id)->delete();
            } else {
                $in = Carbon::parse($job->available_at);
                return response()->json(['status' => 0, 'message' => $this->getMessage($in)]);
            }
        }

        $date = Carbon::parse($request->date);

        if($date > now()) {
            UpdateDomainPreviewImagesJob::dispatch($request->domainId)->delay($date);
        } else {

            UpdateDomainPreviewImagesJob::dispatch($request->domainId);
        }

        return response()->json(['status' => 1]);
    }

    private function getDbImage($lastinsertid)
    {
        return DB::table('tbl_Video')->where('IntId', $lastinsertid)->first();
    }

    private function getWatermarkImage($domainId): \Intervention\Image\Image
    {
        $watermark = DB::table('tblwatermarklogo')->where('vchtype','L')->where('vchsiteid', $domainId)->where('enumstatus','A')->first();

        return Image::make(public_path('/upload/watermark/'.$watermark->vchwatermarklogoname));
    }

    private function getBackgrounds($domainId): \Illuminate\Support\Collection
    {
        return DB::table('tbl_backgrounds')->where('siteid', 'like', '%'.$domainId.'%')->get(); // get backgrounds.
    }

    /**
     * @param Carbon $in
     * @return string
     */
    private function getMessage(Carbon $in): string
    {
        if($in > now()) {
            return "Caching has already been scheduled and starts {$in->diffForHumans()}. Please try again after it finishes.";
        }

        return "Caching process is currently in progress and has started {$in->diffForHumans()}. Please try again after it finishes.";
    }

    private function createImages($domainId)
    {

        $images = DB::table('tbl_Video')
            ->orderByDesc('IntId')
            ->limit(1)
            ->get(['IntId', 'VchFolderPath', 'VchVideoName', 'VchResizeimage', 'vchcacheimages', 'vchorginalfile', 'transparent']);


        $backgrounds = DB::table('tbl_backgrounds')->where('siteid', 'like', '%'.$domainId.'%')->get(); // get backgrounds.

        $watermark = DB::table('tblwatermarklogo')->where('vchtype','L')->where('vchsiteid', $domainId)->where('enumstatus','A')->first();

        $watermarkImage = Image::make(public_path('/upload/watermark/'.$watermark->vchwatermarklogoname));

        $watermarkImage->resize(852, 480);

        $watermarkImage->opacity(40);

        $this->assureDirectoryExists('watermarkedImages/'.$domainId);


        $images->each(function ($dbImage) use ($watermarkImage, $backgrounds, $domainId) {

            try {
                $this->addWatermark($dbImage, $watermarkImage, $backgrounds, $domainId);
            } catch (Exception $e) {
            }
        });
    }

    private function assureDirectoryExists($path)
    {
        \Illuminate\Support\Facades\File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
    }

    private function addWatermark($dbImage, $watermarkImage, $backgrounds, $domainId)
    {
        $imagePath = public_path($dbImage->VchFolderPath . '/' . $dbImage->VchVideoName);

        try {
            $image = Image::make($imagePath);
        } catch (Exception $e) {

            return 1;
        }

        $image->resize(852, 480);

        if ($dbImage->transparent === 'N') {
            try {
                $image->insert($watermarkImage, 'bottom-left');

                $this->saveImageWithoutBackground($image, $dbImage, $domainId);

                return 1;
            } catch (Exception $e) {

                return 1;
            }

        }

        foreach ($backgrounds as $background) {
            try {
                $destinationPath = $this->getDestinationPath($background, $dbImage, $domainId);

                $backgroundImage = $this->getBackgroundImage($background);

                if (!$backgroundImage) {
                }

                $backgroundImage->resize(852, 480);

                $image->save($destinationPath);

                $backgroundImage->insert($destinationPath, 'bottom-left');

                $backgroundImage->save($destinationPath);

                $backgroundImage->insert($watermarkImage, 'bottom-left');

                $backgroundImage->save();
            } catch (Exception $e) {

                continue;
            }
        }
    }

    private function getBackgroundImage($background)
    {
        $imagePath = public_path('background/'.$background->background_img);

        return Image::make($imagePath);
    }

    /**
     * @param $background
     * @param $dbImage
     * @return string
     */
    private function getDestinationPath($background, $dbImage, $domainId): string
    {
        $destinationPath = 'watermarkedImages/' . $domainId . '/' . $dbImage->IntId . '/' . $background->bg_id;

        $this->assureDirectoryExists($destinationPath);

        return public_path($destinationPath . '/' . $dbImage->VchVideoName);
    }

    private function saveImageWithoutBackground($image, $dbImage, $domainId)
    {
        $path = public_path("watermarkedImages/{$domainId}/{$dbImage->IntId}");

        $this->assureDirectoryExists($path);

        $image->save($path.'/'.$dbImage->VchVideoName);
    }
}
