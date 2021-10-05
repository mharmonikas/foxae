<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Session;

class MyajaxController extends Controller {
    public function index(){
        $servername = $_SERVER['SERVER_NAME'];
        $selectserver = DB::table('tbl_managesite')->where('txtsiteurl',$servername)->first();

        $mygetallvideo = DB::table('tbl_Video')->select(DB::raw('COUNT(DISTINCT(tbl_Video.Intid)) AS totalvideo'),'parentserachingcategory.VchSearchcategorytitle','tbl_SearchgroupVideoRelationship.VchSearchgrouptitle','tbl_Video.VchTitle','p.VchCategoryTitle')->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')->leftJoin('tbl_SearchcategoryVideoRelationship as parentserachingcategory', 'tbl_Video.IntId', '=', 'parentserachingcategory.IntVideoID')->leftJoin('tbl_SearchgroupVideoRelationship', 'tbl_Video.IntId', '=', 'tbl_SearchgroupVideoRelationship.IntVideoID')->leftJoin('tbl_Searchcategory as p', 'parentserachingcategory.IntCategorid', '=', 'p.IntParent')->leftJoin('tbl_group', 'tbl_SearchgroupVideoRelationship.Intgroupid', '=', 'tbl_group.intgroupid');

        $getallvideo = DB::table('tbl_Video')->select('tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId ORDER BY RAND() LIMIT 4) as videotags "))->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId');

//        if(request()->get('racetag')){
//            if($_GET['racetag'] != "" && $_GET['racetag'] != 0 ){
//            }
//        }

        if($searchtext = request()->get('searchtext')){
            $mysearchtext = explode(' ',$searchtext);
            $searchtagsinfo = DB::table('tbl_Searchcategory')->where('VchCategoryTitle', $searchtext)->first();

            if($searchtagsinfo){
                $subcategory[] = $searchtagsinfo->VchCategoryTitle;

                if($searchtagsinfo->IntParent==0) {
                    $searchtagsinfos = DB::table('tbl_Searchcategory')->where('IntParent','=',$searchtagsinfo->IntId)->get();
                    foreach($searchtagsinfos as $searchtagsinfos2) {
                        $subcategory[] = $searchtagsinfos2->VchCategoryTitle;
                    }
                }
            }

            $getallvideo = $getallvideo->leftJoin('tbl_SearchcategoryVideoRelationship as parentserachingcategory', 'tbl_Video.IntId', '=', 'parentserachingcategory.IntVideoID');

            $getallvideo = $getallvideo->leftJoin('tbl_SearchgroupVideoRelationship', 'tbl_Video.IntId', '=', 'tbl_SearchgroupVideoRelationship.IntVideoID');

            $getallvideo = $getallvideo->leftJoin('tbl_Searchcategory', 'parentserachingcategory.IntCategorid', '=', 'tbl_Searchcategory.IntParent');
        }

        if(($vchRaceTagID = request()->get('VchRaceTagID')) && $vchRaceTagID !== 0) {
            $getallvideo = $getallvideo->where("tbl_Videotagrelations.VchRaceTagID", $vchRaceTagID);
        }

        if($searchtext = request()->get('searchtext')) {
             $msearch ="";
                if(!empty($subcategory)){

                    foreach($subcategory as $skey=>$svalue){
                        $msearch .= " (parentserachingcategory.VchSearchcategorytitle like '%$svalue%') or ";
                    }

                }
                //(parentserachingcategory.VchSearchcategorytitle like '%$searchtext%') or
            $getallvideo = $getallvideo->whereRaw("
            ($msearch (tbl_Video.VchTitle like '%$searchtext%') or (tbl_Searchcategory.VchCategoryTitle like '%$searchtext%') or (tbl_SearchgroupVideoRelationship.VchSearchgrouptitle like '%$searchtext%'))");

            $mygetallvideo = $mygetallvideo->where(DB::raw('CONCAT_WS(" ",parentserachingcategory.VchSearchcategorytitle, tbl_SearchgroupVideoRelationship.VchSearchgrouptitle,tbl_Video.VchTitle,p.VchCategoryTitle)'),'like',  "%$searchtext%");
        }

        $startlimit = request()->get('startlimit') ?? 0;

         if($type = request()->get('type')) {
              $getallvideo = $getallvideo->where('tbl_Video.EnumType','=',$type);
              $mygetallvideo = $mygetallvideo->where('tbl_Video.EnumType','=',$type);
         }

         if(request()->get('category')) {
             $tagid = request()->get('Tagid');
             $myjsonreuslt = json_decode($tagid, true);

             foreach($myjsonreuslt as $mysearchresult){
                 $tagid = $mysearchresult['tagtype'];
                 $category = $mysearchresult['category'];
                 $columnname = 'tbl_Videotagrelations.'.$category;
                 $getallvideo = $getallvideo->whereRaw("(($columnname = '$tagid')or($columnname = '$tagid'))");
                 $mygetallvideo = $mygetallvideo->whereRaw("(($columnname = '$tagid')or($columnname = '$tagid'))");
             }
         }

        $endlimit = request()->get('showitemperpage') ?? 48;

        $useragent=$_SERVER['HTTP_USER_AGENT'];

        $pregMatch = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));

        if($pregMatch) {
            $endlimit = 10;
        }

        $mygetallvideo = $mygetallvideo->whereRaw('FIND_IN_SET('.$selectserver->intmanagesiteid.',vchsiteid)')->first();

        $totalvideo = $mygetallvideo->totalvideo;
        $mysql = $getallvideo->groupBy('tbl_Video.IntId')->offset($startlimit)->limit($endlimit)->toSql();
        $paramter = $getallvideo->groupBy('tbl_Video.IntId')->offset($startlimit)->limit($endlimit)->getBindings();
        $getallvideo = $getallvideo->whereRaw('FIND_IN_SET('.$selectserver->intmanagesiteid.',vchsiteid)')->orderByRaw('sortingorder = 0, sortingorder')->orderby('tbl_Video.IntId','desc')->groupBy('tbl_Video.IntId')->offset($startlimit)->limit($endlimit)->get();

        foreach($getallvideo as $tumbvideo){
            $tumbvideo->contentid = 'content_'.$tumbvideo->IntId;
            $tumbvideo->productid = Crypt::encryptString($tumbvideo->IntId);

            $userid = Session::get('userid') ?? Session::getId();

            $incartlist =  DB::table('tbl_wishlist')->where('tbl_wishlist.videoid',$tumbvideo->IntId)->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.siteid',$selectserver->intmanagesiteid)->whereNotNull('status')->first();

            if ($userid) {
                if (!empty($incartlist)) {
                    $tumbvideo->cartstatus = 'out-cart';
                    $tumbvideo->carthtml = 'Remove';
                    $tumbvideo->userid = $userid;
                    $tumbvideo->imgname = $incartlist->img_name;

                    if(!empty($incartlist->applied_bg)){
                        $tumbvideo->applied_bg = $incartlist->applied_bg;
                    } else {
                        $tumbvideo->applied_bg = '';
                    }
                } else {
                    $tumbvideo->cartstatus = 'in-cart';
                    $tumbvideo->carthtml = 'Add to Cart';
                    $tumbvideo->userid = $userid;
                    $tumbvideo->imgname = '';
                    $tumbvideo->applied_bg = '';
                }
            } else {
                $tumbvideo->cartstatus = 'in-cart';
                $tumbvideo->carthtml = 'Add to Cart';
                $tumbvideo->userid = '';
                $tumbvideo->imgname = '';
                $tumbvideo->applied_bg = '';
            }

            if(!empty($userid)) {
                $indownloadlist =  DB::table('tbl_download')->where('tbl_download.video_id',$tumbvideo->IntId)->where('tbl_download.user_id',$userid)->where('tbl_download.site_id',$selectserver->intmanagesiteid)->first();

                $infavoriteslist =  DB::table('tbl_favorites')->where('tbl_favorites.fav_videoid',$tumbvideo->IntId)->where('tbl_favorites.fav_userid',$userid)->where('tbl_favorites.fav_siteid',$selectserver->intmanagesiteid)->first();

                if (!empty($infavoriteslist)) {
                    $tumbvideo->favoritesstatus = 'in-favorites';
                    $tumbvideo->favoriteshtml = 'fa fa-heart';
                }else{
                    $tumbvideo->favoritesstatus = 'out-favorites';
                    $tumbvideo->favoriteshtml = 'fa fa-heart-o';
                }

                if (!empty($indownloadlist)) {
                    $tumbvideo->downloadstatus = 'in-download';
                } else {
                    $tumbvideo->downloadstatus = 'out-download';
                }
            } else {
                $tumbvideo->downloadstatus = 'out-download';
                $tumbvideo->favoritesstatus = 'in-favorites';
                $tumbvideo->favoriteshtml = 'fa fa-heart-o';
            }

            $watermarklogo = '';
            $Watermark = '';

            if($tumbvideo->EnumType == 'V') {
               if(!file_exists($tumbvideo->VchFolderPath.'/'.$selectserver->intmanagesiteid.'/watermark.mp4')){
                    $Watermark = DB::table('tblwatermarklogo')->where('vchtype','V')->where('vchsiteid',$selectserver->intmanagesiteid)->where('enumstatus','A')->first();
                    $watermarklogo = public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname;
                    //shell_exec('ffmpeg -i '.$tumbvideo->VchFolderPath.'/'.$tumbvideo->VchVideoName.' -i '.$watermarklogo.' -filter_complex "overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2" -codec:a copy '.$tumbvideo->VchFolderPath.'/'.$selectserver->intmanagesiteid.'/watermark.mp4');
                }
            }

            if($tumbvideo->vchcacheimages == '' && $tumbvideo->EnumType != 'V') {
                $img="upload/videosearch/".$tumbvideo->VchVideoId.'/resize/'.$tumbvideo->VchResizeimage;
                $w=0;
                $h=0;

                if(!defined('DIR_CACHE')) {
                    define('DIR_CACHE', './image_cache6/'.$selectserver->intmanagesiteid.'/');
                }

                if (!Is_Dir(DIR_CACHE)){
                    mkdir(DIR_CACHE, 0777);
                }

                $thumb = strtolower(preg_replace('/\W/is', "_", "$img $w $h"));

                DB::table('tbl_Video')->where('IntId', $tumbvideo->IntId)->update(['vchcacheimages' => DIR_CACHE.$thumb]);
            }
        }

         $myvideo = ['totalvideo' => $totalvideo,'allvideo' => $getallvideo,'sql' => $mysql,'parameter' => $paramter];
         return response()->json($myvideo);
    }

    function getkeywordsvideo(){

     //if(count($searchtextbox)==1){
    $searchtext = $_REQUEST['term'];
    $allkeyword = array();
    $mysearchingkeywordVideo = DB::table('tbl_Video')->select(DB::raw('tbl_Video.VchTitle as VchCategoryTitle'))->whereRaw("tbl_Video.VchTitle like '%$searchtext%'")->offset(0)->limit(10);

     $mysearchingkeyword = DB::table('tbl_Searchcategory as parentcategory')->select('parentcategory.VchCategoryTitle')->leftJoin('tbl_Searchcategory as childcategory', 'childcategory.IntParent', '=', 'parentcategory.IntId')->groupBy('parentcategory.IntId')->whereRaw("parentcategory.VchCategoryTitle like '%$searchtext%'");
     $mysearchinggroup = DB::table('tbl_group')->select('tbl_group.groupname as VchCategoryTitle')->whereRaw("groupname like '%$searchtext%'");
     $mysearchingkeyword=$mysearchingkeyword->offset(0)->limit(10)->union($mysearchingkeywordVideo)->union($mysearchinggroup)->get();
     foreach($mysearchingkeyword as $keywords){
        array_push($allkeyword, $keywords->VchCategoryTitle);
     }
    return response()->json($allkeyword, 200);

    /* }else {
        $searchtextbox = explode(' ',$_REQUEST['searchtext']);
        $searchtext = $searchtextbox[0];
        $subcategory = $searchtextbox[1];

    $mysearchingkeyword = DB::table('tbl_Searchcategory as parentcategory')->select('parentcategory.VchCategoryTitle as VchCategoryTitle','childcategory.VchCategoryTitle as childcategory')->leftJoin('tbl_Searchcategory as childcategory', 'childcategory.IntParent', '=', 'parentcategory.IntId')->whereRaw("parentcategory.VchCategoryTitle = '$searchtext'")->where('childcategory.VchCategoryTitle', 'LIKE', "%$subcategory%");

        $mysearchingkeyword=$mysearchingkeyword->offset(0)->limit(10)->get();

       return response()->json($mysearchingkeyword, 200);


     } */


    }//
     function getkeywords(){
     $searchtextbox = explode(' ',$_REQUEST['searchtext']);
     //if(count($searchtextbox)==1){
    $searchtext = $_REQUEST['searchtext'];
    $mysearchingkeywordVideo = DB::table('tbl_Video')->select(DB::raw("replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(tbl_Video.VchTitle, '3', ''), '1', ''), '2', ''), '(R)', ''), '(L)', ''), '4', ''), '5', ''), '6', ''), '7', ''), '0', ''), '8', ''), '9', '') as VchCategoryTitle"))->whereRaw("tbl_Video.VchTitle like '%$searchtext%'")->offset(0)->limit(10);

     $mysearchinggroup = DB::table('tbl_group')->select('tbl_group.groupname as VchCategoryTitle')->whereRaw("groupname like '%$searchtext%'");

      //$mysearchinggroup=$mysearchinggroup->union($mysearchingkeywordVideo);

     $mysearchingkeyword = DB::table('tbl_Searchcategory as parentcategory')->select('parentcategory.VchCategoryTitle')->leftJoin('tbl_Searchcategory as childcategory', 'childcategory.IntParent', '=', 'parentcategory.IntId')->groupBy('parentcategory.IntId')
     //->where('parentcategory.IntParent',0)
     ->whereRaw("parentcategory.VchCategoryTitle like '%$searchtext%'");



     $mysearchingkeyword=$mysearchingkeyword->offset(0)->limit(10)->union($mysearchinggroup)->union($mysearchingkeywordVideo)->get();
     ///$mysearchingkeyword=$mysearchingkeyword->offset(0)->limit(10)->union($mysearchingkeywordVideo)->get();

    return response()->json($mysearchingkeyword, 200);


    }
    function getallkeywords(){
        $keyword=$_REQUEST['keyword'];
        $pspell_link = pspell_new("en");

    if (!pspell_check($pspell_link,$keyword)) {
        $suggestions = pspell_suggest($pspell_link,$keyword);

        $count = 1;
        $whereconditions = '';
        $selectconditions = '';
       foreach($suggestions as $mykeyword){
          $mykeyword = str_replace("'", '', $mykeyword);
           if($count==1){
              $selectconditions .= "select CASE when tbl_Video.VchTitle like '%$mykeyword%' then '".$mykeyword."' end  as  title from tbl_Video where VchTitle like '%$mykeyword%' ";

           }else {
               $selectconditions .= " UNION select CASE when VchTitle like '%$mykeyword%' then '".$mykeyword."' end  as title from tbl_Video where VchTitle like '%$mykeyword%' ";

           }
           $count++;
       }

       $myresultss = DB::select(DB::raw($selectconditions));
        return response()->json($myresultss);
    }

}


}
