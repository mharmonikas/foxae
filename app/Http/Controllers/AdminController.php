<?php 
namespace App\Http\Controllers; 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\Admin\AdminModel;
use Illuminate\Support\Facades\DB;
use Hash;
use File;
use Intervention\Image\ImageManagerStatic as Image;
use Session;
use App\Admin; 
class AdminController extends Controller
{
   public function __construct(AdminModel $AdminModel) {
        $this->AdminModel = $AdminModel;
		
    }
	public function checklogin(){
		$intAdminID = Session::get('intAdminID');	
		 if(empty($intAdminID)){		  
		
			 return $this->logout();
		 }
	}
	public function index(){         
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
				//end			
			
			
			Session::put('intAdminID',$data->intAdminID);
			Session::put('name',$data->vchName);
			Session::put('intAdminID',$data->intAdminID);
			Session::put('vchRole',$data->vchRole);
		
			return redirect('/admin/dashboard/');	 
			
		}else{
			
			$msg="Invalid login credentials.";		 
            return view('admin/admin-login',compact('msg'));			
			
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
	$updatearr = array('vchPassword'=>$vchPassword);
	DB::table('tblAdminMaster')->where('intAdminID', $changepasswordid)->update($updatearr);
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
		
	}
	
public function edit($id='')
    { 
    
	 echo $this->checklogin();
	 
	 $data = DB::table('tbl_MasterTag')->where('IntId',$id)->first();	
	 /* print_r($data); */
	 /* exit; */
		
	return view('admin.edit',compact('data'));
    }
	
	public function delete($id='')
    { 
    
	 echo $this->checklogin();
	 
DB::table('tbl_MasterTag')->where('IntId', $id)->delete();	
		
	return redirect('/admin/mastertag');
    }

	
	public function forgotpasswordsubmit(Request $request){
		
		$vchEmail = $request->vchEmail;
		$data = DB::table('tblAdminMaster')->where('vchEmail', $vchEmail)->first();	
		if(!empty($data->intAdminID)){			
			//return redirect('/admin/2');		
          $to=$request->vchEmail;
		  $subject = "Forgot Password";
		  $message = "Please check below Link";
		  $useridss = $data->intAdminID;
		  $userid = urlencode(base64_encode($data->intAdminID)); 
		 //$decoded_id = base64_decode(urldecode($userid));
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
	
	
	public function userprofile(Request $request)
    {   
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
	
	public function changepassword(Request $request)
    {    echo  $this->checklogin();
		
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
		/* DB::table('tblBuyerSeller')->where('intBuyerSellerID', $intBuyerSellerID)->update($updatearr); */
		//return redirect('/dashboard/');	
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
	public function UpdateCmsStatus(Request $request)
	{
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
	/* Export CSV/Excel For Search Tags  */
	
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
     $allvideo = $allvideo->whereRaw("((parentserachingcategory.VchSearchcategorytitle like '%$searchtitle%')or(tbl_Video.VchTitle like '%$searchtitle%'))");
	 if(isset($_GET['filteringcategory'])){
	 $filteringcategory = $_GET['filteringcategory'];
	 if(isset($filteringcategory['VchCategoryTagID'])){
	  $VchCategoryTagID = $filteringcategory['VchCategoryTagID'];
	  if($VchCategoryTagID!=0){
     $allvideo = $allvideo->where('tbl_Videotagrelations.VchCategoryTagID','=',$VchCategoryTagID);
	  }
	 }
	 if(isset($filteringcategory['VchRaceTagID'])){
	  $VchCategoryTagID = $filteringcategory['VchRaceTagID'];
	  if($filteringcategory['VchRaceTagID']!=0){
      $allvideo = $allvideo->where('tbl_Videotagrelations.VchRaceTagID','=',$VchCategoryTagID);
	  }
	 }
	 if(isset($filteringcategory['VchGenderTagid'])){
	  $VchCategoryTagID = $filteringcategory['VchGenderTagid'];
      $allvideo = $allvideo->where('tbl_Videotagrelations.VchGenderTagid','=',$VchCategoryTagID);
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
		//$allvideo =  $allvideo->where('Enumuploadstatus','Y');
		
	}
	$allvideo =$allvideo->groupBy('tbl_Video.IntId')->paginate(15);
	$alltags = DB::table('tbl_MasterTag')->select('tbl_MasterTag.IntId','tbl_MasterTag.VchColumnType','tbl_MasterTag.VchTitle',DB::raw('group_concat(tbl_Tagtype.vchTitle) as tagTitle,group_concat(tbl_Tagtype.IntId) as tagid'))->join('tbl_Tagtype', 'tbl_Tagtype.VchTypeID', '=', 'tbl_MasterTag.IntId')->groupBy('tbl_Tagtype.VchTypeID')->get();
	$alldata = array("searchtags"=>$searchtags,"allvideo"=>$allvideo,'alltags'=>$alltags,'allvideorelation'=>$allvideorelation,'allsearchvideorelation'=>$allsearchvideorelation);
	
    if ($request->ajax()) {
       return view('admin.admin-videotagslist')->with('allvideo', $alldata);;
     }	 
	 return view('admin.admin-videotags1')->with('allvideo', $alldata);	 
	
	}	 
		 
	public function taggedvideo(){
		 echo $this->checklogin();
	$searchtags = DB::table('tbl_Searchcategory')->select('*')->get();
	$allvideo = DB::table('tbl_Video')->select('*',DB::raw('tbl_Video.IntId as videoid'))->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId');
	$allvideorelation = array();
	$allsearchvideorelation = array();
	if(isset($_GET['searchtitle'])){
	 $searchtitle = $_GET['searchtitle'];	
	  $allvideo = $allvideo->leftJoin('tbl_SearchcategoryVideoRelationship as parentserachingcategory', 'tbl_Video.IntId', '=', 'parentserachingcategory.IntVideoID');
     $allvideo = $allvideo->whereRaw("((parentserachingcategory.VchSearchcategorytitle like '%$searchtitle%')or(tbl_Video.VchTitle like '%$searchtitle%'))");
	 if(isset($_GET['filteringcategory'])){
	 $filteringcategory = $_GET['filteringcategory'];
	 if(isset($filteringcategory['VchCategoryTagID'])){
	  $VchCategoryTagID = $filteringcategory['VchCategoryTagID'];
	  if($VchCategoryTagID!=0){
     $allvideo = $allvideo->where('tbl_Videotagrelations.VchCategoryTagID','=',$VchCategoryTagID);
	  }
	 }
	 if(isset($filteringcategory['VchRaceTagID'])){
	  $VchCategoryTagID = $filteringcategory['VchRaceTagID'];
	  if($filteringcategory['VchRaceTagID']!=0){
      $allvideo = $allvideo->where('tbl_Videotagrelations.VchRaceTagID','=',$VchCategoryTagID);
	  }
	 }
	 if(isset($filteringcategory['VchGenderTagid'])){
	  $VchCategoryTagID = $filteringcategory['VchGenderTagid'];
      $allvideo = $allvideo->where('tbl_Videotagrelations.VchGenderTagid','=',$VchCategoryTagID);
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
		//$allvideo =  $allvideo->where('Enumuploadstatus','Y');
		
	}
	$allvideo =$allvideo->groupBy('tbl_Video.IntId')->paginate(15);
	$alltags = DB::table('tbl_MasterTag')->select('tbl_MasterTag.IntId','tbl_MasterTag.VchColumnType','tbl_MasterTag.VchTitle',DB::raw('group_concat(tbl_Tagtype.vchTitle) as tagTitle,group_concat(tbl_Tagtype.IntId) as tagid'))->join('tbl_Tagtype', 'tbl_Tagtype.VchTypeID', '=', 'tbl_MasterTag.IntId')->groupBy('tbl_Tagtype.VchTypeID')->get();
	$alldata = array("searchtags"=>$searchtags,"allvideo"=>$allvideo,'alltags'=>$alltags,'allvideorelation'=>$allvideorelation,'allsearchvideorelation'=>$allsearchvideorelation);
	
	return view('admin.admin-videotags')->with('allvideo', $alldata);		
	
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
public function posttaggedvideo(Request $request){
	 echo $this->checklogin();
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
	if(isset($_POST['tags'])){
	$tagid= $_POST['tags'];	
    for($j=0;$j<count($tagid);$j++){
	$mytagid = $tagid[$j];
	$searchcategory = DB::table('tbl_Searchcategory')->select('VchCategoryTitle')->where("IntId",$mytagid)->first(); 
	$VchCategoryTitle = $searchcategory->VchCategoryTitle; 
	/* $videotitle .= $searchcategory->VchCategoryTitle."_"; */
	DB::table('tbl_SearchcategoryVideoRelationship')->insert(['IntCategorid'=>$mytagid,'VchSearchcategorytitle' =>$VchCategoryTitle,'IntVideoID'=>$videoid]);
	
	}
	}
  //$videotitle = rtrim($videotitle,',');
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
	$videotitle .= $result;		
	}	
   if($key=='VchCategoryTagID'){
	$videotitle .= $result;		
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
	$videotitle .= $result;		
	}	
   if($key=='VchCategoryTagID'){
	$videotitle .= $result;		
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
	//$videotitle
	DB::table('tbl_Video')->where('IntId', $videoid)->update(['Enumuploadstatus' => 'N','VchVideoName'=>$videoname,'VchVideothumbnail'=>$VchVideothumbnail1]); 
	rename(public_path().'/'.$VchFolderPath.'/'.$VchVideoName, public_path().'/'.$VchFolderPath.'/'.$videoname);
	if($videotype=='V'){
	rename(public_path().'/'.$VchFolderPath.'/'.$VchVideothumbnail, public_path().'/'.$VchFolderPath.'/'.$VchVideothumbnail1);
	}
	
	}
  
}
	  
	   return redirect('/admin/taggedvideo?msg=1');
}
public function managevideosection(Request $request){
	 echo $this->checklogin();
	if(isset($_GET['deletevideoid'])){
		if(isset($_GET['multiple'])){
		
        $myvideoid = json_decode($_GET['deletevideoid']);
		foreach($myvideoid as $deleteid){
		 $myvideoall = DB::table('tbl_Video')->where('IntId', $deleteid)->first();
		$myvideoallname = $myvideoall->VchVideoName;
		DB::table('tbl_Video')->where('IntId',$deleteid)->delete();
		DB::table('tbl_Videotagrelations')->where('VchVideoId', $deleteid)->delete();
		DB::table('tbl_SearchcategoryVideoRelationship')->where('IntVideoID', $_GET['deletevideoid'])->delete();
		/* $path = public_path().'/upload/'.'videosearch/'.$deleteid."/".$myvideoallname;
		unlink($path);
		rmdir(public_path().'/upload/'.'videosearch/'.$deleteid);  */

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






		
			
		}else {
		
		$videoid = $_GET['deletevideoid'];
		$myvideoall = DB::table('tbl_Video')->where('IntId', $_GET['deletevideoid'])->first();
		$myvideoallname = $myvideoall->VchVideoName;
		DB::table('tbl_Video')->where('IntId', $_GET['deletevideoid'])->delete();
		DB::table('tbl_Videotagrelations')->where('VchVideoId', $_GET['deletevideoid'])->delete();
		DB::table('tbl_SearchcategoryVideoRelationship')->where('IntVideoID', $_GET['deletevideoid'])->delete();
	/* 	$path = public_path().'/upload/'.'videosearch/'.$videoid."/".$myvideoallname;
		unlink($path);
		rmdir(public_path().'/upload/'.'videosearch/'.$videoid); */
		
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
		rmdir($folder);
		}
	}
	 $allvideo = DB::table('tbl_Video')->select('tbl_Video.IntId','tbl_Video.VchTitle','tbl_Video.VchVideothumbnail','tbl_Video.VchVideoName','tbl_Video.vchgoogledrivelink','tbl_Video.EnumType','tbl_Video.EnumUploadType','tbl_Video.VchFolderPath','releationtable.VchGenderTagid',DB::raw('(select tbl_Tagtype.vchTitle from tbl_Tagtype where tbl_Tagtype.Intid = releationtable.VchGenderTagid) as Gendercategory'),DB::raw('(select tbl_Tagtype.vchTitle from tbl_Tagtype where tbl_Tagtype.Intid = releationtable.VchRaceTagID) as Racecategory'),DB::raw('(select tbl_Tagtype.vchTitle from tbl_Tagtype where tbl_Tagtype.Intid = releationtable.VchCategoryTagID) as category'),DB::raw('group_concat(tbl_SearchcategoryVideoRelationship.VchSearchcategorytitle) as VchSearchcategorytitle'))->leftjoin('tbl_Videotagrelations as releationtable', 'releationtable.VchVideoId', '=', 'tbl_Video.IntId')->leftjoin('tbl_SearchcategoryVideoRelationship', 'tbl_SearchcategoryVideoRelationship.IntVideoID', '=', 'tbl_Video.IntId');
      
	 
	 if(isset($_REQUEST['searchtitle'])){
		$searchtitle = $_REQUEST['searchtitle'];  
		 $allvideo = $allvideo->whereRaw("((tbl_SearchcategoryVideoRelationship.VchSearchcategorytitle like '%$searchtitle%')or(tbl_Video.VchTitle like '%$searchtitle%'))"); 
		  
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
	
	 
	 $allvideo = $allvideo->orderBy('tbl_Video.IntId', 'desc')->groupBy('tbl_Video.IntId')->paginate(16);  
	
	
	 $searchtags = DB::table('tbl_Searchcategory')->select('*')->get();
	 $alltags = DB::table('tbl_MasterTag')->select('tbl_MasterTag.IntId','tbl_MasterTag.VchColumnType','tbl_MasterTag.VchTitle',DB::raw('group_concat(tbl_Tagtype.vchTitle) as tagTitle,group_concat(tbl_Tagtype.IntId) as tagid'))->join('tbl_Tagtype', 'tbl_Tagtype.VchTypeID', '=', 'tbl_MasterTag.IntId')->groupBy('tbl_Tagtype.VchTypeID')->get();
	
	$allvideorelation = array();
	$allsearchvideorelation = array();
	
	
	
	
	$alldata = array("searchtags"=>$searchtags,"allvideo"=>$allvideo,'alltags'=>$alltags,'allvideorelation'=>$allvideorelation,'allsearchvideorelation'=>$allsearchvideorelation);
	 if ($request->ajax()) {
       return view('admin.admin-managevideo')->with('allvideo', $alldata);
     }	
	return view('admin.admin-managevideosection')->with('allvideo', $alldata);		
       }

public function uploadvideo(){
	 echo $this->checklogin();
 $searchtags = DB::table('tbl_Searchcategory')->select('*')->get();
	$allvideo = DB::table('tbl_Video')->select('*')->orderBy('IntId', 'DESC');
	$allvideorelation = array();
	$allsearchvideorelation = array();
	$alltags = DB::table('tbl_MasterTag')->select('tbl_MasterTag.IntId','tbl_MasterTag.VchColumnType','tbl_MasterTag.VchTitle',DB::raw('group_concat(tbl_Tagtype.vchTitle) as tagTitle,group_concat(tbl_Tagtype.IntId) as tagid'))->join('tbl_Tagtype', 'tbl_Tagtype.VchTypeID', '=', 'tbl_MasterTag.IntId')->groupBy('tbl_Tagtype.VchTypeID')->get();
	$alldata = array("searchtags"=>$searchtags,"allvideo"=>$allvideo,'alltags'=>$alltags,'allvideorelation'=>$allvideorelation,'allsearchvideorelation'=>$allsearchvideorelation);	
	//$alldata
	return view('admin.admin-uploadvideo')->with('allvideo', $alldata);		
}
public function managetags(){
	 echo $this->checklogin();
$allmastertags = DB::table('tbl_MasterTag')->get();
return view('admin.admin-managetags')->with('allmastertags', $allmastertags);		
}
public function saveuploadvideo(){
	 echo $this->checklogin();
//$vchvideotitle = $_POST['vchvideotitle'];
if(isset($_POST['uploadtype'])){
	
if($_POST['uploadtype']=='G'){
	
	$googlelink = $_POST['googlelink'];
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
      DB::table('tbl_Video')->where('IntId', $videoIntId)->update(['VchTitle'=>$vchvideotitle,'vchgoogledrivelink' => $googlelink,"EnumUploadType"=>'G','VchVideothumbnail'=>$imagelink]);	
		 $lastinsertid =$_POST['videoid'];
		
		
	}else {
  $vchvideotitle= $videoext[0];	
	$lastinsertid = DB::table('tbl_Video')->insertGetId(['VchTitle'=>$vchvideotitle,'vchgoogledrivelink' => $googlelink,"EnumUploadType"=>'G','VchVideothumbnail'=>$imagelink]);
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
	$searchcategory = DB::table('tbl_Searchcategory')->select('VchCategoryTitle')->where("IntId",$mytagid)->first(); 
	$VchCategoryTitle = $searchcategory->VchCategoryTitle; 
	DB::table('tbl_SearchcategoryVideoRelationship')->insert(['IntCategorid'=>$mytagid,'VchSearchcategorytitle' =>$VchCategoryTitle,'IntVideoID'=>$videoid]);
	
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
	
}else {
 
  if(isset($_POST['videoida'])){
    $videoid = $_POST['videoida'];
	$lastinsertid = $_POST['videoida']; 
	$filteringcategory = $_POST['filteringcategory'];
	
	
	//$vchvideotitle= $_POST['vchvideotitle'];
      //DB::table('tbl_Video')->where('IntId', $videoid)->update(['VchTitle'=>$vchvideotitle]);
	
	$allvideodata = DB::table('tbl_Video')->where("IntId",$videoid)->first();
	$videotitle = $allvideodata->VchTitle;
	$VchVideoName = $allvideodata->VchVideoName;
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
	$searchcategory = DB::table('tbl_Searchcategory')->select('VchCategoryTitle')->where("IntId",$mytagid)->first(); 
	$VchCategoryTitle = $searchcategory->VchCategoryTitle; 
	/* $videotitle .= $searchcategory->VchCategoryTitle."_"; */
	DB::table('tbl_SearchcategoryVideoRelationship')->insert(['IntCategorid'=>$mytagid,'VchSearchcategorytitle' =>$VchCategoryTitle,'IntVideoID'=>$videoid]);
	
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
}else {

if(isset($_POST['videoida'])){
	
	
	$videoid = $_POST['videoida'];
	
	$allvideodata = DB::table('tbl_Video')->where("IntId",$videoid)->first();
	$videotitle = $allvideodata->VchTitle;
	$VchVideoName = $allvideodata->VchVideoName;
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
	$searchcategory = DB::table('tbl_Searchcategory')->select('VchCategoryTitle')->where("IntId",$mytagid)->first(); 
	$VchCategoryTitle = $searchcategory->VchCategoryTitle; 
	/* $videotitle .= $searchcategory->VchCategoryTitle."_"; */
	DB::table('tbl_SearchcategoryVideoRelationship')->insert(['IntCategorid'=>$mytagid,'VchSearchcategorytitle' =>$VchCategoryTitle,'IntVideoID'=>$videoid]);
	
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
	DB::table('tbl_Video')->where('IntId', $videoid)->update(['Enumuploadstatus' => 'N','VchVideoName'=>$videoname,'VchVideothumbnail'=>$VchVideothumbnail1]); 
	rename(public_path().'/'.$VchFolderPath.'/'.$VchVideoName, public_path().'/'.$VchFolderPath.'/'.$videoname);
	if($videotype=='V'){
	rename(public_path().'/'.$VchFolderPath.'/'.$VchVideothumbnail, public_path().'/'.$VchFolderPath.'/'.$VchVideothumbnail1);
	}

}	

$filename = pathinfo($_FILES["file1"]["name"]);

$vchvideotitle = $filename['filename'];
if(isset($_POST['videoid'])){
	$myvideoid = DB::table('tbl_Video')->where('IntId', $_POST['videoid'])->update(['VchTitle' => $vchvideotitle]);
	$lastinsertid = $_POST['videoid'];
}else {
	$lastinsertid = DB::table('tbl_Video')->insertGetId(['VchTitle' => $vchvideotitle]);
	
}
$structure = '/upload/video/'.$lastinsertid.'/';	
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
$newfilename = round(microtime(true)) . '.' . end($temp);
if(move_uploaded_file($fileTmpLoc, "$path/$newfilename")){
  $ext = pathinfo($fileName, PATHINFO_EXTENSION); 
if($ext=='webm'||$ext=='wmv'||$ext=='mkv'||$ext=='m4v'||$ext=='flv' ||$ext=='vob'||$ext=='mp4'){
 $video = public_path().'/upload/'.'videosearch/'.$lastinsertid.'/'.$newfilename;
 $thumbnailimage = round(microtime(true)).'thumbnail.jpg';
$thumbnail = public_path().'/upload/'.'videosearch/'.$lastinsertid.'/'.$thumbnailimage;
shell_exec("ffmpeg -i $video -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $thumbnail 2>&1");
 DB::table('tbl_Video')->where('IntId', $lastinsertid)->update(['VchFolderPath'=>$path1,'VchVideoName'=>$newfilename,'VchVideothumbnail'=>$thumbnailimage,'EnumType'=>'V']);	
}else {
DB::table('tbl_Video')->where('IntId', $lastinsertid)->update(['VchFolderPath'=>$path1,'VchVideoName'=>$newfilename,'VchVideothumbnail'=>$newfilename,'EnumType'=>'I']);	
}  
} else {
 }

 
 }


} 
echo $returnarray = json_encode(array('videoid'=>$lastinsertid));	
}
public function ManageSearchCategory(){
	 echo $this->checklogin();
$getvideosearch = DB::table('tbl_Searchcategory')->select('tbl_Searchcategory.IntId','tbl_Searchcategory.VchCategoryTitle','tbl_Searchcategory.IntParent',DB::raw('parentcategory.VchCategoryTitle as parent'))->leftjoin('tbl_Searchcategory as parentcategory', 'tbl_Searchcategory.IntParent', '=', 'parentcategory.IntId')->paginate(15);	
$parentcategory = DB::table('tbl_Searchcategory')->select('*')->where('IntParent','0')->get();
return view('admin.admin-ManageSearchCategory',compact('parentcategory'))->with('getvideosearch', $getvideosearch);					
}
public function addeditsearchcategory(){
	 echo $this->checklogin();
$categorytitle = $_POST['categorytitle'];
$category = $_POST['category'];		
$parentcat = $_POST['parentcat'];
$myvideo = array();
if(empty($category)){
$categoryall = explode(',',$_POST['categorytitle']);
for($i=0;$i<count($categoryall);$i++){	
$lastinsertid = DB::table('tbl_Searchcategory')->insertGetId(['VchCategoryTitle'=>$categoryall[$i],'IntParent' =>$parentcat]);
$myvideo[] = array('lastinsertid'=>$lastinsertid,'vchtitle'=>$categoryall[$i]);
}
}else {
echo $updateres = DB::table('tbl_Searchcategory')->where('IntId', $category)->update(['VchCategoryTitle'=>$categorytitle,'IntParent' =>$parentcat]);	
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
	
	$alltags = DB::table('tbl_MasterTag')->select('tbl_MasterTag.IntId','tbl_MasterTag.VchColumnType','tbl_MasterTag.VchTitle',DB::raw('group_concat(tbl_Tagtype.vchTitle) as tagTitle,group_concat(tbl_Tagtype.IntId) as tagid'))->join('tbl_Tagtype', 'tbl_Tagtype.VchTypeID', '=', 'tbl_MasterTag.IntId')->groupBy('tbl_Tagtype.VchTypeID')->get();
	
  $videotags = DB::table('tbl_Video')->select('*')->where('IntId',$videoid)->first();
  
  $alldata = array("searchtags"=>$searchtags,"allvideo"=>$allvideo,'alltags'=>$alltags,'allvideorelation'=>$allvideorelation,'allsearchvideorelation'=>$allsearchvideorelation);
  
  
  
  
  
  $allvideo = $alldata;
  
  
  return view('admin.admin-editvideo',compact('allvideo'))->with('videotags', $videotags);	

}
public function managesubcategorytagstags(){
	 echo $this->checklogin();
$getvideosearch = DB::table('tbl_Tagtype')->select('tbl_Tagtype.Intid','tbl_Tagtype.vchTitle','tbl_Tagtype.Intid',DB::raw('tbl_MasterTag.VchTitle as parenttitle'),DB::raw('tbl_MasterTag.IntId as parentid'))->leftjoin('tbl_MasterTag', 'tbl_MasterTag.IntId', '=', 'tbl_Tagtype.VchTypeID')->paginate(15);	
$parentcategory = DB::table('tbl_MasterTag')->select('*')->get();	

return view('admin.admin-managesubcategorytagstags',compact('parentcategory'))->with('getvideosearch', $getvideosearch);				
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
public function replacemedia(){
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
$vchvideotitle= $videoext[0];
DB::table('tbl_Video')->where('IntId', $videoIntId)->update(['VchTitle'=>$vchvideotitle,'vchgoogledrivelink' => $googlelink,"EnumUploadType"=>'G','VchVideothumbnail'=>$imagelink]);	
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
DB::table('tbl_Video')->where('IntId', $lastinsertid)->update(['VchFolderPath'=>$path,'VchVideoName'=>$newfilename,'VchVideothumbnail'=>$newfilename,'EnumType'=>'I']);	
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
	
	
	      echo "shelll";
		  exit;
}
public function deletewatermark(){
   DB::table('tbl_setting')->where('vchcolumnname','watermark')->delete();   
	
}
public function watermarkupdateedit(){
	
 $watermark = DB::table('tbl_setting')->where('vchcolumnname','watermark')->first();  
	
return view('admin/admin-websitemanagementedit',compact('watermark'));	
}

public function savewatermarkupdateedit(Request $request){
	$file = $request->file('fileToUpload');
	if(!empty($request->file('fileToUpload'))){
   $destinationPath = 'upload/watermark';
   $file->move($destinationPath,$file->getClientOriginalName());	
   DB::table('tbl_setting')->where('vchcolumnname','watermark')->delete();   
   DB::table('tbl_setting')->insert(['vchcolumnname' => 'watermark', 'Vchvalues' => $file->getClientOriginalName()]);
  }
  return redirect('/admin/websitemanagement');	
}
}
