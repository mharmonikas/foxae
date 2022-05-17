<?php
namespace App\Models\Home;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



class HomeModel extends Model{

	public function submitData($data){
		$insertid = DB::table('tbluser')->insertGetId($data);
		return $insertid;
     }

	 public function loginData($useremail,$password,$siteid){
		 $data = DB::table('tbluser')->where('vchemail', $useremail)->where('vchpassword',$password)->where('vchsiteid',$siteid)->first();
		 return $data;
     }
	 public function UserData($userid){
		 $data = DB::table('tbluser')->where('intuserid', $userid)->first();
		 return $data;
     }
	 public function checkmail($email,$siteid){

		 $data = DB::table('tbluser')->where('vchemail', $email)->where('vchsiteid', $siteid)->first();

		 return $data;
     }
	 public function updateuserdetails($id,$data){
		 $data = DB::table('tbluser')->where('intuserid', $id)->update($data);
		 return $data;
     }
	public function submitcustom($data){
		$insertid = DB::table('tblcustom')->insert($data);

     }

	public function DownloadData($data){
		$insertid = DB::table('tbl_download')->insertGetId($data);
		return $insertid;
    }

	public function billinginfo($data){
		$insertid = DB::table('tbl_billinguser')->insertGetId($data);
		return $insertid;
    }
	public function billinginfo_insert($data){
		$insertid = DB::table('tbl_billinguser')->insertGetId($data);
		return DB::getPdo()->lastInsertId();
    }
	public function paymentinfo_insert($data){
		$insertid = DB::table('tbl_payment')->insertGetId($data);
		return $insertid;
    }
	public function buypackage_insert($data){
		$insertid = DB::table('tbl_buypackage')->insertGetId($data);
		return $insertid;
    }
	public function buypackagestock_insert($data){
		$insertid = DB::table('tbl_buypackagestock')->insertGetId($data);
		return $insertid;
    }
	public function packageStock($id){
		 $data = DB::table('tblstock')->where('plan_id', $id)->get();
		 return $data;
     }
	 public function buy_planslist($userid){
		$data =  DB::table('tbl_payment')->select('tbl_payment.payment_id','tbl_payment.strip_paymentid','tbl_payment.strip_packagename','tbl_payment.strip_transactionid','tbl_payment.strip_amount','tbl_payment.strip_currency','tbl_payment.strip_created','tbl_payment.strip_receipt_url','tbl_payment.strip_status','tbl_payment.strip_payment_type','tbl_payment.plan_id','tbl_payment.user_id','tbl_payment.create_at as create_date', 'tbl_buypackage.*', 'tbl_plan.*')->leftjoin('tbl_buypackage','tbl_payment.payment_id','tbl_buypackage.payment_id')->leftjoin('tbl_plan','tbl_payment.plan_id','tbl_plan.plan_id')->where('tbl_buypackage.package_userid',$userid)->where('tbl_buypackage.status','A')->orderBy('tbl_buypackage.package_id', 'DESC')->paginate(20);

		 //$data = DB::table('tbl_buypackage')->where('package_userid',$userid)->orderBy('package_id', 'DESC')->paginate(20);
		 return $data;
     }
	 public function buy_planslist2($userid){
		$data =  DB::table('tbl_payment')->select('tbl_payment.payment_id','tbl_payment.strip_paymentid','tbl_payment.strip_packagename','tbl_payment.strip_transactionid','tbl_payment.strip_amount','tbl_payment.strip_package_type','tbl_payment.strip_currency','tbl_payment.strip_created','tbl_payment.strip_receipt_url','tbl_payment.strip_status','tbl_payment.strip_payment_type','tbl_payment.plan_id','tbl_payment.user_id','tbl_payment.create_at as create_date', 'tbl_buypackage.*', 'tbl_plan.*')->leftjoin('tbl_buypackage','tbl_payment.payment_id','tbl_buypackage.payment_id')->leftjoin('tbl_plan','tbl_payment.plan_id','tbl_plan.plan_id')->where('tbl_buypackage.package_userid',$userid)->where('tbl_payment.strip_package_type','!=','O')->where('tbl_payment.strip_package_type','!=','D')->whereDate('tbl_buypackage.package_expiredate','>',date('Y-m-d'))->where('tbl_buypackage.status','A')->get();
		//->orderBy('tbl_buypackage.package_id', 'DESC')
		//->paginate(20);

		 //$data = DB::table('tbl_buypackage')->where('package_userid',$userid)->orderBy('package_id', 'DESC')->paginate(20);
		 return $data;
     }
	 public function purchasehistory($userid){
		 $data = DB::table('tbl_payment')->select('tbl_payment.strip_package_type','tbl_payment.payment_id','tbl_payment.strip_paymentid','tbl_payment.strip_packagename','tbl_payment.strip_transactionid','tbl_payment.strip_amount','tbl_payment.strip_currency','tbl_payment.strip_created','tbl_payment.strip_receipt_url','tbl_payment.strip_status','tbl_payment.strip_payment_type','tbl_payment.plan_id','tbl_payment.user_id','tbl_payment.create_at as create_date', 'tbl_buypackage.*', 'tbl_plan.*')->leftjoin('tbl_buypackage','tbl_payment.payment_id','tbl_buypackage.payment_id')->leftjoin('tbl_plan','tbl_payment.plan_id','tbl_plan.plan_id')->where('tbl_payment.user_id',$userid)->orderBy('tbl_payment.payment_id', 'DESC')->paginate(20);
		 return $data;
     }
	 public function wishlistdata($data){
			$insertid = DB::table('tbl_wishlist')->insertGetId($data);
		 return $data;
     }
	 public function favoritesdata($data){
			$insertid = DB::table('tbl_favorites')->insertGetId($data);
		 return $data;
     }
	public function DeleteFromWishlist($videoid,$siteid,$userid){
		 DB::table('tbl_wishlist')->where('videoid', $videoid)->where('siteid', $siteid)->where('userid', $userid)->delete();
	}
	public function DeleteFromfavorites($videoid,$siteid,$userid){
		 DB::table('tbl_favorites')->where('fav_videoid', $videoid)->where('fav_siteid', $siteid)->where('fav_userid', $userid)->delete();
	}
	public function getautorenewpackage(){
        return DB::table('tbl_buypackage')
            ->where('package_start_time','<', time())
            ->where('package_expiredate', '<', now())
            ->whereNotNull('package_start_time')
            ->leftjoin('tbl_plan','tbl_buypackage.buy_id','tbl_plan.plan_id')
            ->where('tbl_buypackage.package_subscription','!=','N')
            ->where('tbl_buypackage.status','A')
            ->where('package_subscription', 'Y')
            ->get();
	}
	/* public function getautorenewpackage($timestamp){
		$result = DB::table('tbl_buypackage')->where('package_start_time','<',$timestamp)->whereNotNull('package_start_time')->where('package_subscription','Y')->orwhere('package_subscription','C')->where('status','A')->get();
		return $result;
	} */
	public function UpdateBuyPackage($id,$data){
		 $data = DB::table('tbl_buypackage')->where('package_id', $id)->update($data);
		 return $data;
    }
}


?>
