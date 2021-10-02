<?php
namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



class ScrapModel extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	protected $table = 'tblAdminMaster';
	
	
	
	function scrapitemuserList($scraplist){
		$list = DB::table('tblScrapRequestMessage')
            ->select('*')
            ->join('tblBuyerSeller', 'tblBuyerSeller.intBuyerSellerID', '=', 'tblScrapRequestMessage.sender')
			->where('tblScrapRequestMessage.intScrapRequestID',$scraplist)		
			->where('tblBuyerSeller.vchRole','Seller')		
            ->get();
		
		return $list;
	}
	function ScrapuserLists($senderid,$receiverid){
		
		
		$list = DB::table('tblScrapRequestMessageDetails')
            ->select('*')
            ->join('tblBuyerSeller', 'tblBuyerSeller.intBuyerSellerID', '=', 'tblScrapRequestMessageDetails.sender')
			 ->Where('sender',  $senderid)
			->Where('receiver',  $receiverid)  
			->orWhere('sender',  $receiverid)
			->Where('receiver',  $senderid)  
			
		
			
			->get();
		
		return $list;
	}
	
	function ScrapSellers($sellerid){
		$list = DB::table('tblScrapRequestMessage')
            ->select('*')
            ->join('tblBuyerSeller', 'tblBuyerSeller.intBuyerSellerID', '=', 'tblScrapRequestMessage.sender')
            ->join('tblScrapRequest', 'tblScrapRequest.intScrapRequestID', '=', 'tblScrapRequestMessage.intScrapRequestID')
			->where('tblScrapRequestMessage.sender',$sellerid)		
				
            ->get();
		
		return $list;
	}
	function employeeScrapSellers($sellerid){
		$list = DB::table('tblScrapRequestMessage')
            ->select('*')
            ->join('tblBuyerSeller', 'tblBuyerSeller.intBuyerSellerID', '=', 'tblScrapRequestMessage.sender')
            ->join('tblScrapRequest', 'tblScrapRequest.intScrapRequestID', '=', 'tblScrapRequestMessage.intScrapRequestID')
			->where('tblScrapRequestMessage.intScrapReqMsgID',$sellerid)		
			->get();
		
		return $list;
	}
	
	function ScrapsellerLists($senderid,$receiverid){
		
		
		$list = DB::table('tblScrapRequestMessageDetails')
            ->select('*')
            ->join('tblBuyerSeller', 'tblBuyerSeller.intBuyerSellerID', '=', 'tblScrapRequestMessageDetails.sender')
			->Where('tblScrapRequestMessageDetails.intScrapReqMsgID',  $senderid)
			->Where('tblScrapRequestMessageDetails.intScrapRequestID',  $receiverid) 
			->get();
		
		return $list;
	}
	
}