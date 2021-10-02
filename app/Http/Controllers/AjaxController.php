<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
class AjaxController extends Controller {
public function index(){
$mygetallvideo = DB::table('tbl_Video')->select(DB::raw('COUNT(DISTINCT(tbl_Video.Intid)) AS totalvideo'))->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId');
$getallvideo = DB::table('tbl_Video')->select('*')->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId');
 if(isset($_GET['searchtext'])){
 if(!empty($_GET['searchtext'])){	 
  $searchtext = $_GET['searchtext'];
  $mysearchtext = explode(' ',$searchtext);
  //if(count($mysearchtext)==1){
  $getallvideo = $getallvideo->leftJoin('tbl_SearchcategoryVideoRelationship as parentserachingcategory', 'tbl_Video.IntId', '=', 'parentserachingcategory.IntVideoID');
  
  $getallvideo = $getallvideo->whereRaw("((parentserachingcategory.VchSearchcategorytitle like '%$searchtext%')or(tbl_Video.VchTitle like '%$searchtext%'))");
   
  $mygetallvideo = $mygetallvideo->leftJoin('tbl_SearchcategoryVideoRelationship as parentserachingcategory', 'tbl_Video.IntId', '=', 'parentserachingcategory.IntVideoID');
 $mygetallvideo = $mygetallvideo->where('parentserachingcategory.VchSearchcategorytitle','like',"%$searchtext%");
 $mygetallvideo = $mygetallvideo->orWhere('tbl_Video.VchTitle','like',"%$searchtext%");
  
 /*  }else {
   $getallvideo = $getallvideo->leftJoin('tbl_SearchcategoryVideoRelationship as parentserachingcategory', 'tbl_Video.IntId', '=', 'parentserachingcategory.IntVideoID')->leftJoin('tbl_SearchcategoryVideoRelationship as childcategory', 'tbl_Video.IntId', '=', 'childcategory.IntVideoID');
   $getallvideo = $getallvideo->where('parentserachingcategory.VchSearchcategorytitle',$mysearchtext[0]);
  $getallvideo = $getallvideo->where('childcategory.VchSearchcategorytitle','like',$mysearchtext[1]);  
  $getallvideo = $getallvideo->orWhere('tbl_Video.VchTitle','like',"%$searchtext%");
    $mygetallvideo = $mygetallvideo->leftJoin('tbl_SearchcategoryVideoRelationship as parentserachingcategory', 'tbl_Video.IntId', '=', 'parentserachingcategory.IntVideoID')->leftJoin('tbl_SearchcategoryVideoRelationship as childcategory', 'tbl_Video.IntId', '=', 'childcategory.IntVideoID');
   $mygetallvideo = $mygetallvideo->where('parentserachingcategory.VchSearchcategorytitle',$mysearchtext[0]); 
  $mygetallvideo = $mygetallvideo->where('childcategory.VchSearchcategorytitle','like',$mysearchtext[1]);   
    $mygetallvideo = $mygetallvideo->orWhere('tbl_Video.VchTitle','like',"%$searchtext%");
	} */
 
 }
 }
if(isset($_GET['startlimit'])){
 $startlimit = $_GET['startlimit'];
}else {
$startlimit = 0;
 }
 if(isset($_GET['type'])){
  $type = $_GET['type'];
  if(!empty($type)){
  $getallvideo = $getallvideo->where('tbl_Video.EnumType','=',$type);
  $mygetallvideo = $mygetallvideo->where('tbl_Video.EnumType','=',$type); 
  }
}
 if(isset($_REQUEST['category'])){
  $tagid = $_REQUEST['Tagid'];
 $myjsonreuslt =json_decode($tagid, true);
 foreach($myjsonreuslt as $mysearchresult){
 $tagid = $mysearchresult['tagtype'];
 $category = $mysearchresult['category'];
 $columnname = 'tbl_Videotagrelations.'.$category;
$getallvideo = $getallvideo->whereRaw("(($columnname = '$tagid')or($columnname = '$tagid'))");
$mygetallvideo = $mygetallvideo->whereRaw("(($columnname = '$tagid')or($columnname = '$tagid'))");
 /* $getallvideo = $getallvideo->where($columnname,'=',$tagid);
 $getallvideo = $getallvideo->orwhere($columnname,'=','0');
 $mygetallvideo = $mygetallvideo->where($columnname,'=',$tagid);
 $mygetallvideo = $mygetallvideo->orwhere($columnname,'=','0');  */ 
 
 
 }
 }
  if(isset($_GET['showitemperpage'])){
 $endlimit = $_GET['showitemperpage'];
 }else {
	 $endlimit = 12; 
 }
 $mygetallvideo = $mygetallvideo->first(); 
 $totalvideo = $mygetallvideo->totalvideo; 
 $mysql = $getallvideo->groupBy('tbl_Video.IntId')->offset($startlimit)->limit($endlimit)->toSql();
 $paramter = $getallvideo->groupBy('tbl_Video.IntId')->offset($startlimit)->limit($endlimit)->getBindings();

 $getallvideo = $getallvideo->orderby('tbl_Video.IntId','desc')->groupBy('tbl_Video.IntId')->offset($startlimit)->limit($endlimit)->get();
/*  $allvideo = array();
 foreach($getallvideo as $myvideo){
 $allvideo[] = array("videotitle"=>$myvideo->VchTitle,"VchFolderPath"=>$myvideo->VchFolderPath,"duration"=>"3","VchVideoName"=>$myvideo->VchVideoName,"VchVideothumbnail"=>$myvideo->VchVideothumbnail,'type'=>$myvideo->EnumType); 
} */

 $myvideo = array('totalvideo'=>$totalvideo,'allvideo'=>$getallvideo,'sql'=>$mysql,'parameter'=>$paramter);
 return response()->json($myvideo, 200);
 
 }
function getkeywordsvideo(){

 //if(count($searchtextbox)==1){
$searchtext = $_REQUEST['term'];
$allkeyword = array();
$mysearchingkeywordVideo = DB::table('tbl_Video')->select(DB::raw('tbl_Video.VchTitle as VchCategoryTitle'))->whereRaw("tbl_Video.VchTitle like '%$searchtext%'")->offset(0)->limit(10); 

 $mysearchingkeyword = DB::table('tbl_Searchcategory as parentcategory')->select('parentcategory.VchCategoryTitle')->leftJoin('tbl_Searchcategory as childcategory', 'childcategory.IntParent', '=', 'parentcategory.IntId')->groupBy('parentcategory.IntId')->whereRaw("parentcategory.VchCategoryTitle like '%$searchtext%'");

 $mysearchingkeyword=$mysearchingkeyword->offset(0)->limit(10)->union($mysearchingkeywordVideo)->get();
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
 

}
 function getkeywords(){
 $searchtextbox = explode(' ',$_REQUEST['searchtext']);
 //if(count($searchtextbox)==1){
$searchtext = $_REQUEST['searchtext'];
$mysearchingkeywordVideo = DB::table('tbl_Video')->select(DB::raw('tbl_Video.VchTitle as VchCategoryTitle'))->whereRaw("tbl_Video.VchTitle like '%$searchtext%'")->offset(0)->limit(10); 

 $mysearchingkeyword = DB::table('tbl_Searchcategory as parentcategory')->select('parentcategory.VchCategoryTitle')->leftJoin('tbl_Searchcategory as childcategory', 'childcategory.IntParent', '=', 'parentcategory.IntId')->groupBy('parentcategory.IntId')->whereRaw("parentcategory.VchCategoryTitle like '%$searchtext%'");

 $mysearchingkeyword=$mysearchingkeyword->offset(0)->limit(10)->union($mysearchingkeywordVideo)->get();
 
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
		  $selectconditions .= "select CASE when tbl_Video.VchTitle like '%$mykeyword%' then '".$mykeyword."' end  as title from tbl_Video where VchTitle like '%$mykeyword%' "; 
		
	   }else {
		   $selectconditions .= " UNION select CASE when VchTitle like '%$mykeyword%' then '".$mykeyword."' end  as title from tbl_Video where VchTitle like '%$mykeyword%' "; 
		
	   }
	   $count++;
   }
   
   $myresults = DB::select(DB::raw($selectconditions));
  return response()->json($myresults);	
}
	
}
}