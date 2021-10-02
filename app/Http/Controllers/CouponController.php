<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Session;
use Mail;
class CouponController extends Controller {

	public function __construct() {

    }

	public function index(Request $request){
		$couponcode = $request->couponcode;
		$place = $request->place;
		$ctype = $request->ctype;

		$coupon = DB::table('tbl_discount')
		->where('coupon', $couponcode)
		->where('delete_status', 'N')
		->where('end_date', '>=', \DB::raw('NOW()'))
		->whereIn('place', [$place])
		->first();

		if(!empty($coupon)) {
			if($coupon->number_of_uses){
                $userCoupons = DB::table('users_coupons')->where('coupon_id', $coupon->id)->where('user_id', Session::get('userid'))->get();

                if($coupon->type == 'O' && $coupon->number_of_uses > 1) {
                    $code = '201';
                }

				else if($userCoupons->count() >= $coupon->number_of_uses){
					$code = '201';
				} else {
                    Session::put('cart-coupon', [
                        'user_id' => Session::get('userid'),
                        'coupon_id' => $coupon->id,
                        'coupon' => $coupon,
                    ]);

					$code = '200';
				}

			} else {
                Session::put('cart-coupon', [
                    'user_id' => Session::get('userid'),
                    'coupon_id' => $coupon->id,
                    'coupon' => $coupon,
                ]);

				$code = '200';
			}

		}else {
			$code = '201';
		}

		$result = array('status' => $code,'data' => $coupon);
		return response()->json($result);
	}


	public function couponPrice(Request $request){
		$couponcode = $request->couponcode;
		$place = $request->place;
		$ctype = $request->surl;
		$surl = str_replace("https://","", $request->surl);
		$user_id = Session::get('userid');

		$siteInfo = DB::table('tbl_managesite')
            ->where('txtsiteurl',$surl)
            ->first();

		$coupon = DB::table('tbl_discount')
            ->where('coupon',$couponcode)
            ->where('delete_status','N')
            ->where('end_date', '>=', \DB::raw('NOW()'))
            ->whereIn('place', [$place])
            ->whereIn('domain_id', [$siteInfo->intmanagesiteid])
            ->first();

		if(!empty($coupon)) {
			if(!empty($coupon->number_of_uses)){
				$count_of_use = DB::table('tbl_discount_use')->where('id',$coupon->id)->where('user_id',$user_id)->count();
                $userCoupons = DB::table('users_coupons')->where('coupon_id',$coupon->id)->where('user_id', Session::get('userid'))->get();

                if($coupon->type == 'O' && $coupon->number_of_uses > 1) {
                    $code = '201';
                }

				else if($userCoupons->count() > (int)$coupon->number_of_uses){
					$code = '201';
				} else {
                    Session::put('pricing-coupon', [
                        'user_id' => Session::get('userid'),
                        'coupon_id' => $coupon->id,
                        'coupon' => $coupon,
                    ]);

					$code = '200';
				}
			} else {
                Session::put('pricing-coupon', [
                    'user_id' => Session::get('userid'),
                    'coupon_id' => $coupon->id,
                    'coupon' => $coupon,
                ]);

                $code = '200';
			}
		} else {
			$code = '201';
		}

		return response()->json(['status' => $code,'data' => $coupon]);
	}
}
