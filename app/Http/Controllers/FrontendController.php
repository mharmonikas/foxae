<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Session;
class FrontendController extends BaseController {
	public function index() {
        $userdetail = '';
	   	$package = '';
		if($userid = Session::get('userid')){
			$userdetail = DB::table('tbluser')->where('intuserid', $userid)->first();
			$packageavailable = DB::table('tbl_buypackage')->where('package_userid',$userid)->whereDate('package_expiredate','>', date('Y-m-d'))->get();

			foreach($packageavailable as $packageavailables) {
				if ($packageavailables->package_download < $packageavailables->package_count) {
				    $package=$packageavailables;
				}
			}
		}

		$alltags = DB::table('tbl_MasterTag')->select('tbl_MasterTag.IntId','tbl_MasterTag.VchColumnType','tbl_MasterTag.VchTitle', DB::raw('group_concat(tbl_Tagtype.vchTitle ORDER BY sorting_order Asc) as tagTitle,group_concat(tbl_Tagtype.IntId ORDER BY sorting_order Asc) as tagid'))->join('tbl_Tagtype', 'tbl_Tagtype.VchTypeID', '=', 'tbl_MasterTag.IntId')->groupBy('tbl_Tagtype.VchTypeID')->get();

		$Plans = DB::table('tbl_plan')->where('plan_status','A')->get();
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl', self::getServerName())->first();
        $intmanagesiteid = !app()->isLocal() ? $managesite->intmanagesiteid : 1;

		$tblthemesetting = DB::table('tbl_themesetting')->select('*')->where('Intsiteid', $intmanagesiteid)->first();

		return view('homepage',compact('tblthemesetting','managesite','userdetail','Plans','package'))->with('alltags', $alltags);
	}

	public function theme(){
		$tblthemesetting = DB::table('tbl_themesetting')->select('*')->first();
		return view('/css/theme.php?v=5',compact('tblthemesetting'));
	}

	public function updatetitle() {
		$allvideo = DB::table('tbl_Video')->select('*')->get();

		foreach($allvideo as $videoid){
			$myvideoids = $videoid->IntId;
			$allvideor = DB::table('tbl_SearchcategoryVideoRelationship')->where("IntVideoID",$myvideoids)->select('*')->get();
			$title = '';

			foreach($allvideor as $myresults){
				$title .= $myresults->VchSearchcategorytitle." ";
			}

			echo $title = trim($title);

			if(!empty($title)){
				DB::table('tbl_Video')->where('IntId', $myvideoids)->update(['VchTitle' => $title]);
			}
		}
	}

    public static function getServerName()
    {
        return app()->isLocal() ? 'dev.fox-ae.com' : $_SERVER['SERVER_NAME'];
    }
}
