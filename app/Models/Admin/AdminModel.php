<?php
namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



class AdminModel extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	//protected $table = 'tblAdminMaster';
	
	function userCount(){
		$results= DB::table('tblBuyerSeller')
			->select(DB::raw('count(*) as total'),'vchRole')
			->where('intParentID','=','0')
			->groupBy('vchRole')
			->get();
			
		return $results;	
	}
	function subuserCount(){
		$results= DB::table('tblBuyerSeller')
			->select(DB::raw('count(*) as total'),'vchRole')
			->where('intParentID','!=','0')
			->groupBy('vchRole')
			->get();
			
		return $results;	
	}
	function call_direction(){
		$list = DB::table('tblOrders')
			->Select(DB::raw('SUM(callcount_buyer) as callcount'),DB::raw('SUM(directioncount_buyer) as direction'))
			->get();
		return $list;	
	}
	
	//protected $fillable = ['vchUserName','vchPassword','vchEmail','vchName','intRoleID'];
	function getuserprofile($uid){ 
		$profiledata = DB::table('tblAdminMaster')
            ->select('*')
			->where('intAdminID',$uid)		
            ->get();
		return $profiledata;
	}
	function updateUserprofile($data,$id){
		
		DB::table('tblAdminMaster')
			->where('intAdminID', $id)
			->update($data); 
	}
	function managebuyerlist($search){	
	
		if($search){
			$results= DB::table('tblBuyerSeller')
			->where('vchRole', 'Buyer')
			
			->Where(function ($q) use ($search){
				$q->where('vchFirstName','like',  "%$search%")
				->orWhere('vchLastName','like',  "%$search%")
				->orWhere('vchEmail','like',  "%$search%")
				->orWhere('vchMobileNumber','like',  "%$search%");
			})
			->orderBy('intBuyerSellerID','DESC')
			->paginate(15)
			->appends('search',"$search");
			return $results;
			
		}else{
			$results= DB::table('tblBuyerSeller')->where('vchRole', 'Buyer')->orderBy('intBuyerSellerID','DESC')->paginate(15);
			return $results;	
		}
		
	}
	function SellerUser(){
		$results= DB::table('tblBuyerSeller')
			->select(DB::raw('COUNT(intParentID) as parcount'),'intParentID as parentid')
			->where('vchRole', 'Seller')
			->where('intParentID','!=', 0)
			->groupBy('intParentID')
			->get();
			
		return $results;	
	}
	
	function manageSellers($search){	
	
		if($search){
			$results= DB::table('tblBuyerSeller')
			->where('vchRole', 'Seller')
			->where('intParentID', 0)
			
			->Where(function ($q) use ($search){
				$q->where('vchFirstName','like',  "%$search%")
				->orWhere('vchLastName','like',  "%$search%")
				->orWhere('vchEmail','like',  "%$search%")
				->orWhere('vchMobileNumber','like',  "%$search%");
			})
			->orderBy('intBuyerSellerID','DESC')
			->paginate(15)
			->appends('search',"$search"); 
			return $results;
			
		}else{
			$results= DB::table('tblBuyerSeller')->where('vchRole', 'Seller')->where('intParentID', 0)->orderBy('intBuyerSellerID','DESC')->paginate(15);
			return $results;	
		}
		
	}
	
	function getbuyerdetil($bid){ 
	
	if($bid == "add"){
		return $list = array();
	}else{
		$list = DB::table('tblBuyerSeller')
            ->select('*')
            ->leftJoin('tblCountry', 'tblBuyerSeller.intCountryID', '=', 'tblCountry.id')             
			->where('tblBuyerSeller.intBuyerSellerID',$bid)		
            ->get();
		// ->leftJoin('tblCityMaster', 'tblBuyerSeller.intCityID', '=', 'tblCityMaster.intCityID')  
		return $list;
	}	
	}	
	
	function getVendorList(){
		$list = DB::table('tblBuyerSeller')
            ->select('*')
            ->where('vchRole','Seller')		
            ->where('intParentID',0)		
            ->get();
			return $list;
	}
	
	function updatebuyerprofile($data,$id){
		
		DB::table('tblBuyerSeller')
			->where('intBuyerSellerID', $id)
			->update($data); 
	}
	
	function InsertBuyerProfile($data){
		$results = DB::table('tblBuyerSeller')->insert($data);
		$lastid	=  DB::getPdo()->lastInsertId();
		return $lastid;
	}
	
	function manageitem($id,$status,$search){		
		$results= DB::table('tblPartRequest')
				->select('*')
				->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
				->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
				->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
				->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID');
				
				if($search !=""){
					$results->Where(function ($q) use ($search){
						$q->where('tblPartRequest.vchTitle','like',  "%$search%")
						->orWhere('tblBrandMaster.vchBrandName','like',  "%$search%")
						->orWhere('tblModels.vchModelNumber','like',  "%$search%");
						
					}); 
				}	
				if($status != 'all'){
					$results->where('tblPartRequest.intBuyerSellerID', $id);
				}		
			
		return $results = $results->paginate(15)->appends('search',"$search");		
	}
	function ManageItemDashboard($id){		
		$results= DB::table('tblPartRequest')
				->select('*')
				->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
				->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
				->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
				->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID')   
				->leftJoin('tblBuyerSeller', 'tblPartRequest.intBuyerSellerID', '=', 'tblBuyerSeller.intBuyerSellerID')
				->orderBy('intPartRequestID', 'desc')
				->limit($id);
					
		return $results = $results->get();		
	}
	function ScraplistDashboard($sid){		
		
			$results = DB::table('tblScrapRequest')
					 ->select('*')
					->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')
					->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID')  
					->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
					->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')	
					->join('tblBuyerSeller', 'tblScrapRequest.intBuyerSellerID', '=', 'tblBuyerSeller.intBuyerSellerID')    
					->orderBy('intScrapRequestID', 'desc')
					->limit($sid);
			return $results = $results->get();		
	}
	
	function scraplist($sid,$status,$search){		
		
			$results = DB::table('tblScrapRequest')
					 ->select('*')
					->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')
					->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID')  
					->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
					->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')	
					->join('tblBuyerSeller', 'tblScrapRequest.intBuyerSellerID', '=', 'tblBuyerSeller.intBuyerSellerID');    
					//->where('tblScrapRequest.intBuyerSellerID', $sid);
			//return $results = $results->get();	

				if($search !=""){
					$results->Where(function ($q) use ($search){
						$q->where('tblScrapRequest.vchTitle','like',  "%$search%")
						->orWhere('tblBrandMaster.vchBrandName','like',  "%$search%")
						->orWhere('tblModels.vchModelNumber','like',  "%$search%");
						
					}); 
				}	
				if($status != 'all'){
					$results->where('tblScrapRequest.intBuyerSellerID', $sid);
				}		
			
		return $results = $results->paginate(15)->appends('search',"$search");	
	}
	/***********BRAND SECTION*******/
	function ManageBrandList(){	
	
		$results= DB::table('tblBrandMaster')
					->paginate(15);
		return $results;
	}
	function BrandInsert($data){
		$results = DB::table('tblBrandMaster')->insert($data);
		return $results;
	}
	
	function brandedit($id){
		$results = DB::table('tblBrandMaster')
		->where('intBrandID', $id)->get();
		return $results;
	}
	function updatebrand($data,$id){
		
		DB::table('tblBrandMaster')
			->where('intBrandID', $id)
			->update($data); 
	}
	/*********************************/
	/***********BRAND MODEL SECTION*******/
	function ManageBrandModelList($brandsearch,$modelsearch){	
	
		if($brandsearch !="" || $modelsearch !=""){
			$results= DB::table('tblModels')
					->select('tblModels.*','tblBrandMaster.vchBrandName')
					->join('tblBrandMaster', 'tblModels.intBrandID', '=', 'tblBrandMaster.intBrandID')
					->where('tblModels.intBrandID','like',  "%$brandsearch%")
					->where('tblModels.vchModelNumber','like',  "%$modelsearch%")
					->paginate(15)
					->appends('brandsearch',"$brandsearch")
					->appends('modelsearch',"$modelsearch");
			
		}else{
			$results= DB::table('tblModels')
					->select('tblModels.*','tblBrandMaster.vchBrandName')
					->join('tblBrandMaster', 'tblModels.intBrandID', '=', 'tblBrandMaster.intBrandID')
					->paginate(15);
			
		}
		return $results;
	}
	function ManageBrandLists(){	
	
		$results= DB::table('tblBrandMaster')
					->get();
		return $results;
	}
	function ModelInsert($data){
		$results = DB::table('tblModels')->insert($data);
		return $results;
	}function updateModel($data,$id){
		
		DB::table('tblModels')
			->where('intModelID', $id)
			->update($data); 
	}
	
	function MBlist(){
		$results= DB::table('tblModels')
					->select('tblModels.*','tblBrandMaster.vchBrandName')
					->join('tblBrandMaster', 'tblModels.intBrandID', '=', 'tblBrandMaster.intBrandID')
					->get();
		return $results;
	}
	function manageEmploye($id,$search){		
		
		if($search){
			$results= DB::table('tblBuyerSeller')
			->where('vchRole', 'Seller');
			if(is_numeric($id)){
				$results->where('intParentID', $id);
			}else{
				$results->where('intParentID','!=','0');
			}
			$results->Where(function ($q) use ($search){
				$q->where('vchFirstName','like',  "%$search%")
				->orWhere('vchLastName','like',  "%$search%")
				->orWhere('vchEmail','like',  "%$search%")
				->orWhere('vchMobileNumber','like',  "%$search%");
			});
			
			
			return $results->paginate(15)->appends('search',"$search");
			
		}else{
			$results= DB::table('tblBuyerSeller')
			->where('vchRole', 'Seller');
			if(is_numeric($id)){
				$results->where('intParentID', $id);
			}else{
				$results->where('intParentID','!=','0');
			}
			return $results->paginate(15);	
		}
	}
	function getstatuscount($id){
		$results= DB::table('tblPartRequestMessage')
				->select(DB::raw('COUNT(*) numcount'),'sender as userid')
				->where('sender', $id)
				->groupBy('sender')
				->get();
		return $results;	
	}
	function getTypeuser($id){
		$results= DB::table('tblPartRequestMessage')
				->where('sender', $id)
				->get();
		return $results;	
	}
	function getstatus($id){
		$results= DB::table('tblPartRequestMessage')
				->where('sender', $id)
				->get();
		return $results;	
	}
	function getScarpstatus($id){
		$results= DB::table('tblScrapRequestMessage')
				->where('sender', $id)
				->get();
		return $results;	
	}
	
	function checkpartrequert($id,$status,$search){
		
		$results= DB::table('tblPartRequestMessage')
				
				->select('tblPartRequestMessage.*','tblPartRequest.vchTitle','tblPartRequest.vchImage','tblPartRequest.vchYear','tblPartRequest.vchPrice','tblPartRequest.vchDescription','tblCountry.nicename','tblCityMaster.vchCity','tblBrandMaster.vchBrandName','tblModels.vchModelNumber')
				->join('tblPartRequest', 'tblPartRequestMessage.intPartRequestID', '=', 'tblPartRequest.intPartRequestID')
				->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
				->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
				->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
				->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID') ;
			if($status == "accept"){
				$results->where('enumMsgStatus', 'ACP');
			}elseif($status == "decline"){
				$results->where('enumMsgStatus', 'DCL');
			}else{
					
			}
			
			if($search !=""){
				$results->Where(function ($q) use ($search){
					$q->where('tblPartRequest.vchTitle','like',  "%$search%")
					->orWhere('tblBrandMaster.vchBrandName','like',  "%$search%")
					->orWhere('tblModels.vchModelNumber','like',  "%$search%");
					
				});
			}
			$results->where('sender', $id);
		return $results->paginate(15)->appends('search',"$search");
		
	}
	
	function checkscraprequert($id,$status,$search){
		
		$results= DB::table('tblScrapRequestMessage')
				
				->select('tblScrapRequestMessage.*','tblScrapRequest.vchTitle','tblScrapRequest.vchImage','tblScrapRequest.vchYear','tblScrapRequest.vchPrice','tblScrapRequest.vchDescription','tblCountry.nicename','tblCityMaster.vchCity','tblBrandMaster.vchBrandName','tblModels.vchModelNumber')
				->join('tblScrapRequest', 'tblScrapRequestMessage.intScrapRequestID', '=', 'tblScrapRequest.intScrapRequestID')
				->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
				->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')    
				->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
				->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID') ;
			if($status == "accept"){
				$results->where('enumMsgStatus', 'ACP');
			}elseif($status == "decline"){
				$results->where('enumMsgStatus', 'DCL');
			}else{
					
			}
			
			if($search !=""){
				$results->Where(function ($q) use ($search){
					$q->where('tblScrapRequest.vchTitle','like',  "%$search%")
					->orWhere('tblBrandMaster.vchBrandName','like',  "%$search%")
					->orWhere('tblModels.vchModelNumber','like',  "%$search%");
					
				}); 
			}
			$results->where('sender', $id);
		return $results->paginate(15)->appends('search',"$search");
		
	}
	function PartDate(){
		$results= DB::table('tblPartRequest')
			->select(DB::raw('COUNT(*) partcount'),DB::raw('DATE_FORMAT(created_at,"%Y-%m") as partdate'))
			->Where(DB::raw('DATE_FORMAT(created_at,"%Y")') ,'=',date('Y'))
			->groupBy('partdate')
			->get();
		return $results;	
	}
	function GetItemRequest(){
		$results= DB::table('tblPartRequestMessage')
			->select(DB::raw('COUNT(*) partcount'),DB::raw('DATE_FORMAT(created_at,"%Y-%m") as partdate'))
			->Where(DB::raw('DATE_FORMAT(created_at,"%Y")') ,'=',date('Y'))
			->Where('enumMsgStatus','ACP')
			->groupBy('partdate')
			->get();
		return $results;	
	}
	function GetUserTotal(){
		$results= DB::table('tblBuyerSeller')
			->select(DB::raw('COUNT(*) partcount'),DB::raw('DATE_FORMAT(created_at,"%Y-%m") as partdate'))
			->Where(DB::raw('DATE_FORMAT(created_at,"%Y")') ,'=',date('Y'))
			->groupBy('partdate')
			->get();
		return $results;	
	}
	function ScrapDate(){
		$results= DB::table('tblScrapRequest')
			->select(DB::raw('COUNT(*) scrapcount'),DB::raw('DATE_FORMAT(created_at,"%Y-%m") as scrapdate'))
			->Where(DB::raw('DATE_FORMAT(created_at,"%Y")') ,'=',date('Y'))
			->groupBy('scrapdate')
			->get();
		return $results;	
	}
	function GetScrapRequest(){
		$results= DB::table('tblScrapRequestMessage')
			->select(DB::raw('COUNT(*) scrapcount'),DB::raw('DATE_FORMAT(created_at,"%Y-%m") as scrapdate'))
			->Where(DB::raw('DATE_FORMAT(created_at,"%Y")') ,'=',date('Y'))
			->Where('enumMsgStatus','ACP')
			->groupBy('scrapdate')
			->get();
		return $results;	
	}
	function GetDirectionCall(){
		$results= DB::table("tblOrders")
			->select(DB::raw("sum((callcount_buyer + callcount_subvendor)) as calls"),DB::raw("sum((directioncount_subvendor + directioncount_buyer)) as directions"),DB::raw("DATE_FORMAT(created_at,'%Y-%m') as scrapdate"))
			->Where(DB::raw("DATE_FORMAT(created_at,'%Y')") ,"=",date("Y"))
			->groupBy("scrapdate")
			//->toSql();
			->get();
		return $results;	
	}
	function PageInsert($data){
		$results = DB::table('tblPages')->insert($data);
		return $results;
	}
	function ManageCmsPages($cid){
		$results = DB::table('tblPages')
			->select('*');
			if($cid !=""){
				if($cid != 'add'){
				$results->Where('intPagesid',$cid);	
				}
			}
		return $results->get();	
	}
	function ManageSingleData($cid){
		
		$results  = array();
		if($cid !=""){
			if($cid != 'add'){
			$results = DB::table('tblPages')
				->select('*')
				->Where('intPagesid',$cid)
				->get();
			}
		}
		return $results;	
	}
	function updateCmsPages($data,$id){
		DB::table('tblPages')
			->where('intPagesid', $id)
			->update($data); 
	}
	
	
	function CustomerDate(){
		$results= DB::table('tblBuyerSeller')
			->select(DB::raw('COUNT(*) custcount'),DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d") as cusdate'))
			->Where('vchRole','Buyer')
			->groupBy('cusdate')
			->get();
		return $results;	
	}
	function CountofmonthVendor(){
		$results= DB::table('tblBuyerSeller')
			->select(DB::raw('COUNT(*) custcount'),DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d") as cusdate'))
			->Where('vchRole','Seller')
			->groupBy('cusdate')
			->get();
		return $results;	
	}
	function CountofmonthsubVendor(){
		$results= DB::table('tblBuyerSeller')
			->select(DB::raw('COUNT(*) custcount'),DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d") as cusdate'))
			->Where('intParentID','!=','0')
			->groupBy('cusdate')
			->get();
		return $results;	
	}
	
	function AcceptPartDate(){
		$results= DB::table('tblPartRequest')
			->select(DB::raw('COUNT(*) partcount'),DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d") as partdate'))
			->Where('enumStatus','ACP')
			->groupBy('partdate')
			->get();
		return $results;	
	}
	function AcceptScrapDate(){
		$results= DB::table('tblScrapRequest')
			->select(DB::raw('COUNT(*) scrapcount'),DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d") as scrapdate'))
			->Where('enumStatus','ACP')
			->groupBy('scrapdate')
			->get();
		return $results;	
	}
	
	function RevenuePartDate(){
		$results= DB::table('tblPartRequestMessage')
			->select(DB::raw('SUM(vchOfferPrice) as PartPrice'),DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d") as partdate'))
			->Where('enumMsgStatus','ACP')
			->groupBy('partdate')
			->orderBY('partdate','ASC')
			->get();
		return $results;	
	}
	function RevenueScrapDate(){
		$results= DB::table('tblScrapRequestMessage')
			->select(DB::raw('SUM(vchOfferPrice) as ScrapPrice'),DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d") as  scrapdate'))
			->Where('enumMsgStatus','ACP')
		    ->groupBy('scrapdate')
			->orderBY('scrapdate','ASC')
			->get();
		return $results;	
	}
	
	function ExportData($option,$item,$userid,$orderoption,$start,$end,$vendorid,$subvendor,$poption,$pstatus){
		
		if($option == 'buyer'){
			
			
			if($item == 'item'){
				if($userid !=""){
					$results= DB::table('tblPartRequest')
					->select('vchTitle as Item_name','vchBrandName as Brand_name','vchModelNumber as Model_Name','vchYear as Year','nicename as Country','vchCity as City','tblPartRequest.vchImage as images','tblPartRequest.created_at as Date')
					->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
					->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
					->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
					->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID')
					->Where('tblPartRequest.intBuyerSellerID',$userid)
					->whereBetween('tblPartRequest.created_at', [$start, $end])->get();
				}else{
					$results= DB::table('tblPartRequest')
					->select('vchTitle as Item_name','vchBrandName as Brand_name','vchModelNumber as Model_Name','vchYear as Year','nicename as Country','vchCity as City','tblPartRequest.vchImage as images','tblPartRequest.created_at as Date')
					->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
					->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
					->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
					->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID')
					->whereBetween('tblPartRequest.created_at', [$start, $end])->get();
				}
			}elseif($item == 'scrap'){
				if($userid !=""){
					$results = DB::table('tblScrapRequest')
					->select('vchTitle as Scrap_name','vchBrandName as Brand_name','vchModelNumber as Model_Name','vchYear as Year','nicename as Country','vchCity as City','tblScrapRequest.created_at as Date')
					->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')
					->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID')  
					->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
					->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')
					->Where('tblScrapRequest.intBuyerSellerID',$userid)
					->whereBetween('tblScrapRequest.created_at', [$start, $end])->get();
				}else{
					$results = DB::table('tblScrapRequest')
					->select('vchTitle as Scrap_name','vchBrandName as Brand_name','vchModelNumber as Model_Name','vchYear as Year','nicename as Country','vchCity as City','tblScrapRequest.created_at as Date')
					->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')
					->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID')  
					->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
					->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')	
					->whereBetween('tblScrapRequest.created_at', [$start, $end])->get();
				}
			}elseif($item == 'order'){
				if($orderoption == 'item'){
					if($userid !=""){
						$results = DB::table('tblOrders')
						->select('tblPartRequest.vchTitle as Item_name','tblBrandMaster.vchBrandName as Brand_name','tblModels.vchModelNumber as Model_Name','tblPartRequest.vchYear as Year','tblCountry.nicename as Country','tblCityMaster.vchCity as City','tblPartRequest.vchImage as images','tblOrders.created_at as Date','tblOrders.vchReview as Review')
						->join('tblPartRequest','tblPartRequest.intPartRequestID','tblOrders.intPartRequestID')
						->join('tblPartRequestMessage','tblPartRequestMessage.intPartReqMsgID','tblOrders.intPartReqMsgID')
						->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
						->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
						->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
						->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID')
						->Where('tblOrders.intBuyerSellerID_Buyer',$userid)
						->Where('tblOrders.enumType','P')
						->whereBetween('tblOrders.created_at', [$start, $end])->get();
					}else{
						$results = DB::table('tblOrders')
						->select('tblPartRequest.vchTitle as Item_name','tblBrandMaster.vchBrandName as Brand_name','tblModels.vchModelNumber as Model_Name','tblPartRequest.vchYear as Year','tblCountry.nicename as Country','tblCityMaster.vchCity as City','tblPartRequest.vchImage as images','tblOrders.created_at as Date','tblOrders.vchReview as Review',DB::raw('CONCAT(tblBuyerSeller.vchFirstName, " ", tblBuyerSeller.vchLastName) as Name'))
						->join('tblBuyerSeller','tblBuyerSeller.intBuyerSellerID','tblOrders.intBuyerSellerID_Buyer')
						->join('tblPartRequestMessage','tblPartRequestMessage.intPartReqMsgID','tblOrders.intPartReqMsgID')
						->join('tblPartRequest','tblPartRequest.intPartRequestID','tblOrders.intPartRequestID')
						->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
						->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
						->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
						->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID')
						->Where('tblOrders.enumType','P')
						->whereBetween('tblOrders.created_at', [$start, $end])->get();
					}
					
					
				}elseif($orderoption == 'scrap'){
					
					if($userid !=""){
						$results = DB::table('tblOrders')
						->select('tblScrapRequest.vchTitle as Item_name','tblBrandMaster.vchBrandName as Brand_name','tblModels.vchModelNumber as Model_Name','tblScrapRequest.vchYear as Year','tblCountry.nicename as Country','tblCityMaster.vchCity as City','tblScrapRequest.vchImage as images','tblOrders.created_at as Date','tblOrders.vchReview as Review')
						->join('tblScrapRequest','tblScrapRequest.intScrapRequestID','tblOrders.intScrapRequestID')
						->join('tblScrapRequestMessage','tblScrapRequestMessage.intScrapReqMsgID','tblOrders.intScrapReqMsgID')
						->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
						->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')    
						->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
						->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID')
						->Where('tblOrders.intBuyerSellerID_Buyer',$userid)
						->Where('tblOrders.enumType','S')
						->whereBetween('tblOrders.created_at', [$start, $end])->get();
					}else{
						$results = DB::table('tblOrders')
						->select('tblScrapRequest.vchTitle as Item_name','tblBrandMaster.vchBrandName as Brand_name','tblModels.vchModelNumber as Model_Name','tblScrapRequest.vchYear as Year','tblCountry.nicename as Country','tblCityMaster.vchCity as City','tblScrapRequest.vchImage as images','tblOrders.created_at as Date','tblOrders.vchReview as Review',DB::raw('CONCAT(tblBuyerSeller.vchFirstName, " ", tblBuyerSeller.vchLastName) as Name'))
						->join('tblBuyerSeller','tblBuyerSeller.intBuyerSellerID','tblOrders.intBuyerSellerID_Buyer')
						->join('tblScrapRequestMessage','tblScrapRequestMessage.intScrapReqMsgID','tblOrders.intScrapReqMsgID')
						->join('tblScrapRequest','tblScrapRequest.intScrapRequestID','tblOrders.intScrapRequestID')
						->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
						->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')    
						->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
						->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID')
						->Where('tblOrders.enumType','S')
						->whereBetween('tblOrders.created_at', [$start, $end])->get();
					}
					
				}else{
					
				}
				
			}else{
				$results= DB::table('tblBuyerSeller')->select('intBuyerSellerID as Buyer_id','vchFirstName as First_Name','vchLastName as Last_Name','vchEmail as Email','vchMobileNumber as Mobile_No','created_at as Date')->Where('vchRole','Buyer')->whereBetween('created_at', [$start, $end])->get();
			}
			
			
		}elseif($option == 'vendor'){
			
			if($item == 'vendor'){
				//->where('intParentID', 0)
				$results= DB::table('tblBuyerSeller')->select('intBuyerSellerID as Buyer_id','vchFirstName as First_Name','vchLastName as Last_Name','vchEmail as Email','vchMobileNumber as Mobile_No','created_at as Date')->Where('vchRole','Seller')->WhereBetween('created_at', [$start, $end])->get();
			}elseif($item == 'subvendor'){
				$results= DB::table('tblBuyerSeller')->select('intBuyerSellerID as Buyer_id','vchFirstName as First_Name','vchLastName as Last_Name','vchEmail as Email','vchMobileNumber as Mobile_No','created_at as Date')->Where('vchRole','Seller')->where('intParentID',$vendorid)->get();
			}elseif($item == 'product'){
			
				if($poption == "sold"){
					if($subvendor ==""){
						$results = DB::table('tblOrders')
							->select('tblPartRequest.vchTitle as Item_name','tblBrandMaster.vchBrandName as Brand_name','tblModels.vchModelNumber as Model_Name','tblPartRequest.vchYear as Year','tblCountry.nicename as Country','tblCityMaster.vchCity as City','tblPartRequest.vchImage as images','tblOrders.created_at as Date','tblOrders.vchReview as Review')
							->join('tblPartRequest','tblPartRequest.intPartRequestID','tblOrders.intPartRequestID')
							->join('tblPartRequestMessage','tblPartRequestMessage.intPartReqMsgID','tblOrders.intPartReqMsgID')
							->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
							->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
							->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
							->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID')
							->Where('tblOrders.intBuyerSellerID_Vendor',$vendorid)
							->Where('tblOrders.enumType','P')
							->whereBetween('tblOrders.created_at', [$start, $end])->get();
					}else{
						$results = DB::table('tblOrders')
							->select('tblPartRequest.vchTitle as Item_name','tblBrandMaster.vchBrandName as Brand_name','tblModels.vchModelNumber as Model_Name','tblPartRequest.vchYear as Year','tblCountry.nicename as Country','tblCityMaster.vchCity as City','tblPartRequest.vchImage as images','tblOrders.created_at as Date','tblOrders.vchReview as Review')
							->join('tblPartRequest','tblPartRequest.intPartRequestID','tblOrders.intPartRequestID')
							->join('tblPartRequestMessage','tblPartRequestMessage.intPartReqMsgID','tblOrders.intPartReqMsgID')
							->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
							->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
							->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
							->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID')
							->Where('tblOrders.intBuyerSellerID_Vendor',$subvendor)
							->Where('tblOrders.enumType','P')
							->whereBetween('tblOrders.created_at', [$start, $end])->get();
					}
				}elseif($poption == "purchase"){
					if($subvendor =="" ){
						$results = DB::table('tblOrders')
						->select('tblScrapRequest.vchTitle as Scrap_name','tblBrandMaster.vchBrandName as Brand_name','tblModels.vchModelNumber as Model_Name','tblScrapRequest.vchYear as Year','tblCountry.nicename as Country','tblCityMaster.vchCity as City','tblScrapRequest.vchImage as images','tblOrders.created_at as Date','tblOrders.vchReview as Review')
						->join('tblScrapRequest','tblScrapRequest.intScrapRequestID','tblOrders.intScrapRequestID')
						->join('tblScrapRequestMessage','tblScrapRequestMessage.intScrapReqMsgID','tblOrders.intScrapReqMsgID')
						->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
						->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')    
						->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
						->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID')
						->Where('tblOrders.intBuyerSellerID_Vendor',$vendorid)
						->Where('tblOrders.enumType','S')
						->whereBetween('tblOrders.created_at', [$start, $end])->get();
					}else{
						$results = DB::table('tblOrders')
						->select('tblScrapRequest.vchTitle as Scrap_name','tblBrandMaster.vchBrandName as Brand_name','tblModels.vchModelNumber as Model_Name','tblScrapRequest.vchYear as Year','tblCountry.nicename as Country','tblCityMaster.vchCity as City','tblScrapRequest.vchImage as images','tblOrders.created_at as Date','tblOrders.vchReview as Review')
						->join('tblScrapRequest','tblScrapRequest.intScrapRequestID','tblOrders.intScrapRequestID')
						->join('tblScrapRequestMessage','tblScrapRequestMessage.intScrapReqMsgID','tblOrders.intScrapReqMsgID')
						->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
						->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')    
						->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
						->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID')
						->Where('tblOrders.intBuyerSellerID_Vendor',$subvendor)
						->Where('tblOrders.enumType','S')
						->whereBetween('tblOrders.created_at', [$start, $end])->get();
					}
				}
			}elseif($item == 'statusproduct'){
				if($poption == 'item'){
				if($subvendor =="" ){
					if($pstatus == "ac"){
					$results= DB::table('tblPartRequestMessage')
					->select('tblPartRequest.vchTitle as Item_name','tblBrandMaster.vchBrandName as Brand_name','tblModels.vchModelNumber as Model_Name','tblPartRequest.vchYear as Year','tblCountry.nicename as Country','tblCityMaster.vchCity as City','tblPartRequest.vchImage as images','tblPartRequestMessage.created_at as Date')
					->join('tblPartRequest', 'tblPartRequestMessage.intPartRequestID', '=', 'tblPartRequest.intPartRequestID')
					->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
					->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
					->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
					->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID')
					->whereBetween('tblPartRequestMessage.created_at', [$start, $end])
					->where('tblPartRequestMessage.enumMsgStatus', 'ACP')
					->Where('tblPartRequestMessage.sender',$vendorid)->get();
						
					}elseif($pstatus == "dc"){
						$results= DB::table('tblPartRequestMessage')
					->select('tblPartRequest.vchTitle as Item_name','tblBrandMaster.vchBrandName as Brand_name','tblModels.vchModelNumber as Model_Name','tblPartRequest.vchYear as Year','tblCountry.nicename as Country','tblCityMaster.vchCity as City','tblPartRequest.vchImage as images','tblPartRequestMessage.created_at as Date')
					->join('tblPartRequest', 'tblPartRequestMessage.intPartRequestID', '=', 'tblPartRequest.intPartRequestID')
					->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
					->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
					->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
					->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID')
					->whereBetween('tblPartRequestMessage.created_at', [$start, $end])
					->where('tblPartRequestMessage.enumMsgStatus', 'DCL')
					->Where('tblPartRequestMessage.sender',$vendorid)->get();
					
					}else{
						$results= DB::table('tblPartRequestMessage')
					->select('tblPartRequest.vchTitle as Item_name','tblBrandMaster.vchBrandName as Brand_name','tblModels.vchModelNumber as Model_Name','tblPartRequest.vchYear as Year','tblCountry.nicename as Country','tblCityMaster.vchCity as City','tblPartRequest.vchImage as images','tblPartRequestMessage.created_at as Date')
					->join('tblPartRequest', 'tblPartRequestMessage.intPartRequestID', '=', 'tblPartRequest.intPartRequestID')
					->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
					->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
					->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
					->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID')
					->whereBetween('tblPartRequestMessage.created_at', [$start, $end])
					->Where('tblPartRequestMessage.sender',$vendorid)->get();
								
					}
					
				}else{
					if($pstatus == "ac"){
					$results= DB::table('tblPartRequestMessage')
					->select('tblPartRequest.vchTitle as Item_name','tblBrandMaster.vchBrandName as Brand_name','tblModels.vchModelNumber as Model_Name','tblPartRequest.vchYear as Year','tblCountry.nicename as Country','tblCityMaster.vchCity as City','tblPartRequest.vchImage as images','tblPartRequestMessage.created_at as Date')
					->join('tblPartRequest', 'tblPartRequestMessage.intPartRequestID', '=', 'tblPartRequest.intPartRequestID')
					->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
					->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
					->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
					->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID')
					->whereBetween('tblPartRequestMessage.created_at', [$start, $end])
					->where('tblPartRequestMessage.enumMsgStatus', 'ACP')
					->Where('tblPartRequestMessage.sender',$subvendor)->get();
						
					}elseif($pstatus == "dc"){
						$results= DB::table('tblPartRequestMessage')
					->select('tblPartRequest.vchTitle as Item_name','tblBrandMaster.vchBrandName as Brand_name','tblModels.vchModelNumber as Model_Name','tblPartRequest.vchYear as Year','tblCountry.nicename as Country','tblCityMaster.vchCity as City','tblPartRequest.vchImage as images','tblPartRequestMessage.created_at as Date')
					->join('tblPartRequest', 'tblPartRequestMessage.intPartRequestID', '=', 'tblPartRequest.intPartRequestID')
					->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
					->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
					->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
					->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID')
					->whereBetween('tblPartRequestMessage.created_at', [$start, $end])
					->where('tblPartRequestMessage.enumMsgStatus', 'DCL')
					->Where('tblPartRequestMessage.sender',$subvendor)->get();
					
					}else{
						$results= DB::table('tblPartRequestMessage')
					->select('tblPartRequest.vchTitle as Item_name','tblBrandMaster.vchBrandName as Brand_name','tblModels.vchModelNumber as Model_Name','tblPartRequest.vchYear as Year','tblCountry.nicename as Country','tblCityMaster.vchCity as City','tblPartRequest.vchImage as images','tblPartRequestMessage.created_at as Date')
					->join('tblPartRequest', 'tblPartRequestMessage.intPartRequestID', '=', 'tblPartRequest.intPartRequestID')
					->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
					->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
					->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
					->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID')
					->whereBetween('tblPartRequestMessage.created_at', [$start, $end])
					->Where('tblPartRequestMessage.sender',$subvendor)->get();
								
					}
				}
				}else{
					if($subvendor =="" ){
							if($pstatus == "ac"){
								$results= DB::table('tblScrapRequestMessage')
									->select('*')
									->join('tblScrapRequest', 'tblScrapRequestMessage.intScrapRequestID', '=', 'tblScrapRequest.intScrapRequestID')
									->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
									->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')    
									->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')
									->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID') 
									->whereBetween('tblScrapRequestMessage.created_at', [$start, $end])
									->where('tblScrapRequestMessage.enumMsgStatus', 'ACP')
									->Where('tblScrapRequestMessage.sender',$vendorid)->get();
							}elseif($pstatus == "dc"){
								$results= DB::table('tblScrapRequestMessage')
									->select('*')
									->join('tblScrapRequest', 'tblScrapRequestMessage.intScrapRequestID', '=', 'tblScrapRequest.intScrapRequestID')
									->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
									->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')    
									->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')
									->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID') 
									->whereBetween('tblScrapRequestMessage.created_at', [$start, $end])
									->where('tblScrapRequestMessage.enumMsgStatus', 'DCL')
									->Where('tblScrapRequestMessage.sender',$vendorid)->get();
							}else{
								$results= DB::table('tblScrapRequestMessage')
									->select('*')
									->join('tblScrapRequest', 'tblScrapRequestMessage.intScrapRequestID', '=', 'tblScrapRequest.intScrapRequestID')
									->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
									->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')    
									->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')
									->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID') 
									->whereBetween('tblScrapRequestMessage.created_at', [$start, $end])
									->Where('tblScrapRequestMessage.sender',$vendorid)->get();
								
							}
					}else{
						if($pstatus == "ac"){
								$results= DB::table('tblScrapRequestMessage')
									->select('*')
									->join('tblScrapRequest', 'tblScrapRequestMessage.intScrapRequestID', '=', 'tblScrapRequest.intScrapRequestID')
									->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
									->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')    
									->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')
									->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID') 
									->whereBetween('tblScrapRequestMessage.created_at', [$start, $end])
									->where('tblScrapRequestMessage.enumMsgStatus', 'ACP')
									->Where('tblScrapRequestMessage.sender',$subvendor)->get();
							}elseif($pstatus == "dc"){
								$results= DB::table('tblScrapRequestMessage')
									->select('*')
									->join('tblScrapRequest', 'tblScrapRequestMessage.intScrapRequestID', '=', 'tblScrapRequest.intScrapRequestID')
									->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
									->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')    
									->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')
									->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID') 
									->whereBetween('tblScrapRequestMessage.created_at', [$start, $end])
									->where('tblScrapRequestMessage.enumMsgStatus', 'DCL')
									->Where('tblScrapRequestMessage.sender',$subvendor)->get();
							}else{
								$results= DB::table('tblScrapRequestMessage')
									->select('*')
									->join('tblScrapRequest', 'tblScrapRequestMessage.intScrapRequestID', '=', 'tblScrapRequest.intScrapRequestID')
									->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
									->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')    
									->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')
									->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID') 
									->whereBetween('tblScrapRequestMessage.created_at', [$start, $end])
									->Where('tblScrapRequestMessage.sender',$subvendor)->get();
								
							}
					}
				}
			}
			
		}elseif($option == 'subvendor'){
			$results= DB::table('tblBuyerSeller')->select('intBuyerSellerID as Buyer_id','vchFirstName as First_Name','vchLastName as Last_Name','created_at as Date')->Where('vchRole','Seller')->where('intParentID','!=', 0)->whereBetween('created_at', [$start, $end])->get();
		}elseif($option == 'item'){
			
			$results= DB::table('tblPartRequest')
				->select('vchTitle as Item_name','vchBrandName as Brand_name','vchModelNumber as Model_Name','vchYear as Year','nicename as Country','vchCity as City','tblPartRequest.created_at as Date')
				->leftJoin('tblCountry', 'tblPartRequest.intCountryID', '=', 'tblCountry.id')
				->leftJoin('tblCityMaster', 'tblPartRequest.intCityID', '=', 'tblCityMaster.intCityID')    
				->join('tblBrandMaster', 'tblPartRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')    
				->join('tblModels', 'tblPartRequest.intModelID', '=', 'tblModels.intModelID')
				->whereBetween('tblPartRequest.created_at', [$start, $end])->get();
			
		}elseif($option == 'scrap'){
			$results = DB::table('tblScrapRequest')
					->select('vchTitle as Item_name','vchBrandName as Brand_name','vchModelNumber as Model_Name','vchYear as Year','nicename as Country','vchCity as City','tblScrapRequest.created_at as Date')
					->join('tblBrandMaster', 'tblScrapRequest.intBrandID', '=', 'tblBrandMaster.intBrandID')
					->join('tblModels', 'tblScrapRequest.intModelID', '=', 'tblModels.intModelID')  
					->leftJoin('tblCountry', 'tblScrapRequest.intCountryID', '=', 'tblCountry.id')
					->leftJoin('tblCityMaster', 'tblScrapRequest.intCityID', '=', 'tblCityMaster.intCityID')	
					->whereBetween('tblScrapRequest.created_at', [$start, $end])->get();
		}elseif($option == 'itemrevenue'){
			
			$results= DB::table('tblPartRequestMessage')
			->select(DB::raw('SUM(vchOfferPrice) as Price'),'created_at as Date')
			->Where('enumMsgStatus','ACP')
			->groupBy('Date')
			->whereBetween('tblPartRequestMessage.created_at', [$start, $end])->get();
			
		}elseif($option == 'Scraprevenue'){
			
			$results= DB::table('tblScrapRequestMessage')
			->select(DB::raw('SUM(vchOfferPrice) as Price'),'created_at as Date')
			->Where('enumMsgStatus','ACP')
			->groupBy('Date')
			->whereBetween('tblScrapRequestMessage.created_at', [$start, $end])->get();
			
		}
		
		return $results;
	}
	public function DeleteData($id){
		$results = DB::table('tblBuyerSeller')->where('intBuyerSellerID', $id)->delete();
		return $results;
	}
	public function otheroptiondelete($id,$status){
		if($status == "brand"){
			$table = 'tblBrandMaster';
			$tableid = 'intBrandID';
		}elseif($status == "model"){
			$table = 'tblModels';
			$tableid = 'intModelID';
		}elseif($status == "pages"){
			$table = 'tblPages';
			$tableid = 'intPagesid';
		}
		$results = DB::table($table)->where($tableid, $id)->delete();
		return $results;
	}
	
	function chatData(){
		$results= DB::table('tblAdminSetting')
			->select('*')
			->Where('intSettingID',1)
			->get();
		return $results;	
	}
	function UpdateChatSetting($data,$id){
		
		DB::table('tblAdminSetting')
			->where('intSettingID', $id)
			->update($data); 
	}
	
	function getlistofbuyer(){
		$list = DB::table('tblBuyerSeller')
            ->select('*')
            ->where('vchRole','Buyer')				
            ->get();
			return $list;
	}function getlistofvendor(){
		$list = DB::table('tblBuyerSeller')
            ->select('*')
            ->where('vchRole','Seller')				
            ->get();
			return $list;
	}
	
	function adminSetting($id){
		$results= DB::table('tblAdminMaster')
			->select('*')
			->Where('intAdminID',$id)
			->get();
		return $results;	
	}
	function sendnotification($user,$device){
		if($user == 'buyer'){
			$results= DB::table('tblBuyerSeller')->select('*')->Where('vchRole','Buyer')->get();
		}elseif($user == 'vendor'){
			$results= DB::table('tblBuyerSeller')->select('*')->Where('vchRole','Seller')->where('intParentID',0)->get();
		}else{
			$results= DB::table('tblBuyerSeller')->select('*')->Where('vchRole','Seller')->where('intParentID','!=',0)->get();
		}
		
		return $results;	
	}
	
	function AddcountrY($data){
		$results = DB::table('tblCountry')->insert($data);
		return $results;
	}
	function UpdatecountrY($data,$id){
		DB::table('tblCountry')
			->where('id', $id)
			->update($data); 
	}
	/**************ADD Domaen******************/
	function Insertdomain($data){
		$results = DB::table('tbl_managesite')->insert($data);
		return DB::getPdo()->lastInsertId();
	}
	function Insertdomaintheme($data){
		$results = DB::table('tbl_themesetting')->insert($data);
		return DB::getPdo()->lastInsertId();
	}
	function Updatedomaintheme($data,$id){
		DB::table('tbl_themesetting')
			->where('IntthemeId', $id)
			->update($data); 
	}
	function domainslist(){
	  $results = DB::table('tbl_managesite')->paginate(20);
	  
		return $results;
		
	}
	function editdomains($id){
		
		$results = DB::table('tbl_managesite')->where("intmanagesiteid",$id)->first();
		return $results;
		
	}
		function updatedomains($data,$id){
		DB::table('tbl_managesite')->where('intmanagesiteid', $id)->update($data); 
	}
	function updatestatus($data,$id){
		DB::table('tbl_managesite')->where('intmanagesiteid', $id)->update($data); 
	}
	public function DeleteDomains($id){
		$results = DB::table('tbl_managesite')->where('intmanagesiteid', $id)->delete();
		return $results;
	}
	/**************End Domaen******************/
	function AddCity($data){
		$results = DB::table('tblCityMaster')->insert($data);
		return $results;
	}
	function UpdateCity($data,$id){
		DB::table('tblCityMaster')
			->where('intCityID', $id)
			->update($data); 
	}
	function ItemgetcallDir(){
		$list = DB::table('tblOrders')
            ->select(DB::raw('SUM(callcount_buyer) as callcount'),DB::raw('SUM(directioncount_buyer) as directioncount'),'intBuyerSellerID_Vendor as vendorid')
			->where('enumType','P')
            ->groupBy('intBuyerSellerID_Vendor')				
            ->get();
			return $list;
	}
	function ScrapgetcallDir(){
		$list = DB::table('tblOrders')
            ->select(DB::raw('SUM(callcount_buyer) as callcount'),DB::raw('SUM(directioncount_buyer) as directioncount'),'intBuyerSellerID_Vendor as vendorid')
			->where('enumType','S')
            ->groupBy('intBuyerSellerID_Vendor')				
            ->get();
			return $list;
	}
	function GetCity($id){
		$list = DB::table('tblCityMaster')
		 ->where('intCountryID',$id)
		 ->where('enumStatus','A')
		 ->get();
		return $list;
	}
	function getallcount($id,$item,$call){ 
		if($item == "Item" && $call == "Call"){
			$list = DB::table('tblOrders')
			->Select('tblPartRequest.vchTitle as title',DB::raw('CONCAT(tblBuyerSeller.vchFirstName, " ", tblBuyerSeller.vchLastName) as Name'),'tblBuyerSeller.vchEmail as email','tblBuyerSeller.vchMobileNumber as Phone','tblOrders.intPartRequestID as orderid','tblOrders.callcount_buyer as Count')
			->leftJoin('tblBuyerSeller','tblBuyerSeller.intBuyerSellerID','tblOrders.intBuyerSellerID_Buyer')
			->leftJoin('tblPartRequest','tblPartRequest.intPartRequestID','tblOrders.intPartRequestID')
			->where('tblOrders.enumType','P')
			->where('callcount_buyer','!=','')
			
			->where('tblOrders.intBuyerSellerID_Vendor',$id)->get();
			
		}else if($item == "Item" && $call == "Direction"){
			$list = DB::table('tblOrders')
			->Select('tblPartRequest.vchTitle as title',DB::raw('CONCAT(tblBuyerSeller.vchFirstName, " ", tblBuyerSeller.vchLastName) as Name'),'tblBuyerSeller.vchEmail as email','tblBuyerSeller.vchMobileNumber as Phone','tblOrders.intPartRequestID as orderid','tblOrders.directioncount_buyer as Count')
			->leftJoin('tblBuyerSeller','tblBuyerSeller.intBuyerSellerID','tblOrders.intBuyerSellerID_Buyer')
			->leftJoin('tblPartRequest','tblPartRequest.intPartRequestID','tblOrders.intPartRequestID')
			->where('tblOrders.enumType','P')
			
			->where('directioncount_buyer','!=','')
			->where('tblOrders.intBuyerSellerID_Vendor',$id)->get();
		}else if($item == "Scrap" && $call == "Call"){
			$list = DB::table('tblOrders')
			->Select('tblScrapRequest.vchTitle as title',DB::raw('CONCAT(tblBuyerSeller.vchFirstName, " ", tblBuyerSeller.vchLastName) as Name'),'tblBuyerSeller.vchEmail as email','tblBuyerSeller.vchMobileNumber as Phone','tblOrders.intScrapRequestID as orderid','tblOrders.callcount_buyer as Count')
			->leftJoin('tblBuyerSeller','tblBuyerSeller.intBuyerSellerID','tblOrders.intBuyerSellerID_Buyer')
			->leftJoin('tblScrapRequest','tblScrapRequest.intScrapRequestID','tblOrders.intScrapRequestID')
			->where('tblOrders.enumType','S')
			->where('callcount_buyer','!=','')
			
			->where('tblOrders.intBuyerSellerID_Vendor',$id)->get();
			
		}else if($item == "Scrap" && $call == "Direction"){
			$list = DB::table('tblOrders')
			->Select('tblScrapRequest.vchTitle as title',DB::raw('CONCAT(tblBuyerSeller.vchFirstName, " ", tblBuyerSeller.vchLastName) as Name'),'tblBuyerSeller.vchEmail as email','tblBuyerSeller.vchMobileNumber as Phone','tblOrders.intScrapRequestID as orderid','tblOrders.directioncount_buyer as Count')
			->leftJoin('tblBuyerSeller','tblBuyerSeller.intBuyerSellerID','tblOrders.intBuyerSellerID_Buyer')
			->leftJoin('tblScrapRequest','tblScrapRequest.intScrapRequestID','tblOrders.intScrapRequestID')
			->where('tblOrders.enumType','S')
			
			->where('directioncount_buyer','!=','')
			->where('tblOrders.intBuyerSellerID_Vendor',$id)->get();
		}
		
		return $list;
	}
}