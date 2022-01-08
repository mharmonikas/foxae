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
        ->where('place', 'like',"%{$place}%")
		->first();

        $code = '201';

        if($coupon) {
            $userCoupons = DB::table('users_coupons')->where('coupon_id', $coupon->id)->where('user_id', Session::get('userid'))->get();

            if ($coupon->type == 'O' && $userCoupons->count() > 1) {
                $code = '201';
            }

            else if (!$coupon->number_of_uses || $userCoupons->count() < $coupon->number_of_uses) {
                Session::put('cart-coupon', [
                    'user_id' => Session::get('userid'),
                    'coupon_id' => $coupon->id,
                    'coupon' => $coupon,
                ]);

                $code = '200';
            }
        }

		return response()->json(['status' => $code,'data' => $coupon]);
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
            ->where('place', 'like',"%{$place}%")
            ->where(function ($query) use ($siteInfo) {
                $query->where('domain_id', 'like',"%{$siteInfo->intmanagesiteid}%")->orWhere('domain_id', 'A');
            })
            ->first();

        $code = '201';

        if($coupon) {
            $userCoupons = DB::table('users_coupons')->where('coupon_id',$coupon->id)->where('user_id', Session::get('userid'))->get();

            if ($coupon->type == 'O' && $userCoupons->count() > 1) {
                $code = '201';
            }

            else if (!$coupon->number_of_uses || $userCoupons->count() < $coupon->number_of_uses) {
                Session::put('pricing-coupon', [
                    'user_id' => Session::get('userid'),
                    'coupon_id' => $coupon->id,
                    'coupon' => $coupon,
                ]);

                $code = '200';
            }
        }

		return response()->json(['status' => $code,'data' => $coupon]);
	}
}
