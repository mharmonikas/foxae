<?php
namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



class ChatModel extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	protected $table = 'tblAdminMaster';
	
	
	
	function ChatitemuserList($chatid){
		$list = DB::table('tblPartRequestMessage')
            ->select('*')
            ->join('tblBuyerSeller', 'tblBuyerSeller.intBuyerSellerID', '=', 'tblPartRequestMessage.sender')
			->where('tblPartRequestMessage.intPartRequestID',$chatid)		
			->where('tblBuyerSeller.vchRole','Seller')		
            ->get();
		
		return $list;
	}
	function ChatuserLists($senderid,$receiverid){
		
		
		$list = DB::table('tblPartRequestMessageDetails')
            ->select('*')
            ->join('tblBuyerSeller', 'tblBuyerSeller.intBuyerSellerID', '=', 'tblPartRequestMessageDetails.sender')
			->Where('sender',  $senderid)
			->Where('receiver',  $receiverid)  
			->orWhere('sender',  $receiverid)
			->Where('receiver',  $senderid)  
			
		
			
			->get();
		
		return $list;
	}
	
	function ChatSellers($sellerid){
		$list = DB::table('tblPartRequestMessage')
            ->select('*')
            ->join('tblBuyerSeller', 'tblBuyerSeller.intBuyerSellerID', '=', 'tblPartRequestMessage.sender')
            ->join('tblPartRequest', 'tblPartRequest.intPartRequestID', '=', 'tblPartRequestMessage.intPartRequestID')
			->where('tblPartRequestMessage.sender',$sellerid)		
				
            ->get();
		
		return $list;
	}
	function employeeChatSellers($sellerid){
		$list = DB::table('tblPartRequestMessage')
            ->select('*')
            ->join('tblBuyerSeller', 'tblBuyerSeller.intBuyerSellerID', '=', 'tblPartRequestMessage.sender')
            ->join('tblPartRequest', 'tblPartRequest.intPartRequestID', '=', 'tblPartRequestMessage.intPartRequestID')
			->where('tblPartRequestMessage.intPartReqMsgID',$sellerid)		
				
            ->get();
		
		return $list;
	}
	
	function ChatsellerLists($senderid,$receiverid){
		$list = DB::table('tblPartRequestMessageDetails')
            ->select('*')
            ->join('tblBuyerSeller', 'tblBuyerSeller.intBuyerSellerID', '=', 'tblPartRequestMessageDetails.sender')
			->Where('tblPartRequestMessageDetails.intPartReqMsgID',  $senderid)
			->Where('tblPartRequestMessageDetails.intPartRequestID',  $receiverid) 
			->get();
		
		return $list;
	}
	
}