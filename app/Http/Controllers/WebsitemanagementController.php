<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\AdminModel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Hash;
use File;
use Session;
use App\Admin;
class WebsitemanagementController extends Controller
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
		$this->checklogin();
		$access = $this->accessPoint(6);
		$search = "";
		if(empty($_GET['search'])){
		  $watermark = DB::table('tblwatermarklogo')->leftjoin('tbl_managesite', 'tblwatermarklogo.vchsiteid', '=', 'tbl_managesite.intmanagesiteid')->where('vchtype','L')->get();
		  $smallwatermark = DB::table('tblwatermarklogo')->leftjoin('tbl_managesite', 'tblwatermarklogo.vchsiteid', '=', 'tbl_managesite.intmanagesiteid')->where('vchtype','S')->get();
		  $videowatermark = DB::table('tblwatermarklogo')->leftjoin('tbl_managesite', 'tblwatermarklogo.vchsiteid', '=', 'tbl_managesite.intmanagesiteid')->where('vchtype','V')->get();

			$background_list = DB::table('tbl_backgrounds')->leftjoin('tbl_managesite', 'tbl_backgrounds.siteid', '=', 'tbl_managesite.intmanagesiteid')->get();
		}else{
			$search=$_GET['search'];
		  $watermark = DB::table('tblwatermarklogo')->leftjoin('tbl_managesite', 'tblwatermarklogo.vchsiteid', '=', 'tbl_managesite.intmanagesiteid')->where('tblwatermarklogo.vchsiteid',$search)->where('vchtype','L')->get();
		  $smallwatermark = DB::table('tblwatermarklogo')->leftjoin('tbl_managesite', 'tblwatermarklogo.vchsiteid', '=', 'tbl_managesite.intmanagesiteid')->where('tblwatermarklogo.vchsiteid',$search)->where('vchtype','S')->get();
		  $videowatermark = DB::table('tblwatermarklogo')->leftjoin('tbl_managesite', 'tblwatermarklogo.vchsiteid', '=', 'tbl_managesite.intmanagesiteid')->where('tblwatermarklogo.vchsiteid',$search)->where('vchtype','V')->get();
			$background_list = DB::table('tbl_backgrounds')->leftjoin('tbl_managesite', 'tbl_backgrounds.siteid', '=', 'tbl_managesite.intmanagesiteid')->where('tbl_backgrounds.siteid',$search)->get();


		}
		foreach($background_list as $all){
			if(!empty($all->siteid)){
				$siteid = explode(",",$all->siteid);
				$res = DB::table('tbl_managesite')->select(DB::raw("GROUP_CONCAT(tbl_managesite.txtsiteurl SEPARATOR ', ') as sitename"))->whereIn('intmanagesiteid',$siteid)->first();

				$all->sitename = $res->sitename;
			}else{
				$all->sitename = "";
			}
		}
		$managesites = DB::table('tbl_managesite')->get();
		return view('admin.websitemanagement',compact('watermark','smallwatermark','videowatermark','managesites','search','background_list','access'));
 }
  public function create(){
	echo $this->checklogin();
 $managesites = DB::table('tbl_managesite')->get();
  return view('admin.addwatermark',compact('managesites'));
}
 	public function logout()
    {
		Session::flush();
         return redirect('/admin');
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

}
