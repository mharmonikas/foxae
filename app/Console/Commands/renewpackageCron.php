<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Home\HomeModel;
use File;
use Illuminate\Support\Facades\Log;
use Mail;
use Stripe;
class renewpackageCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'renewpackage:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew package update deatils';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(HomeModel $HomeModel)
    {
		$this->HomeModel = $HomeModel;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	//\Log::info("Cron is working fine2!");
		$t=time();
		$getresponse = $this->HomeModel->getautorenewpackage($t);
		$getapidetail = DB::table('tblapidetail')->where('id','1')->first();

		foreach($getresponse as $res){
            if($res->package_subscription=='Y'){
                Stripe\Stripe::setApiKey($getapidetail->stripe_secret);
                try {
                    $response = \Stripe\Subscription::update($res->package_renewid);
                }
                catch (Exception $e) {
                    Log::critical('Cron renew subscription error:');
                    Log::critical($e->getMessage());
                    $this->deactivatePackage($res);

                    continue;
                }

                $response = $response->jsonSerialize();
                $invoice_number=$response['latest_invoice'];
                Stripe\Stripe::setApiKey($getapidetail->stripe_secret);
                $invoice = Stripe\Invoice::retrieve($response['latest_invoice']);
                $invoiceresponse = $invoice->jsonSerialize();

                if($this->paymentSuccessful($response, $res)) {
                    //$available = $res->package_count - $res->package_download;
                    $available = 0;
                    $data = [
                        "package_count" => $available + $res->package_count,
                        "package_download" => '0',
                        "package_expiredate" => date('Y-m-d H:i:s', strtotime('+30 days')),
                        "package_start_time" => data_get($response, 'current_period_end'),
                    ];

                    $this->createPackageFromOld($res, $response, $invoiceresponse);

                    $this->deactivatePackage($res);

//                    $paymentdata = [
//                        "strip_paymentid" => $response['id'],
//                        "strip_packagename" => $res->plan_title,
//                        "strip_transactionid" => $response['plan']['id'],
//                        "strip_amount" =>($response['plan']['amount'] / 100),
//                        "strip_created" => $response['plan']['created'],
//                        "strip_currency" => $response['plan']['currency'],
//                        "strip_receipt_url" => $invoiceresponse['hosted_invoice_url'],
//                        "strip_status" => $response['status'],
//                        "plan_id" => $res->buy_id,
//                        "user_id" => $res->package_userid,
//                        "strip_payment_type" => 'Renew Subscription',
//                        "strip_package_type" => $res->package_type,
//                        "create_at" => date('Y-m-d H:i:s')
//                    ];
//                    $paymentlastid = $this->HomeModel->paymentinfo_insert($paymentdata);

                    $managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('intmanagesiteid',$res->site_id)->first();
                    $userinfo = $this->HomeModel->UserData($res->package_userid);
//                    $myUser = DB::table('tbluser')->where('intuserid', $res->package_userid)->first();

                    if(!$userinfo) {
                        Log::critical('No user info. Package user id:' . $res->package_userid);
                        Log::critical('Package id:' . $res->buy_id);

                        continue;
                    }

                    $data2 = array(
                        'email'	=> $managesite->vchemailfrom,
                        'emailfrom'	=> $userinfo->vchemail,
                        'vchsitename'	=> $managesite->vchsitename,
                    );

                    if($res->package_type == 'Y'){
                        $data['package_title']=strip_tags($res->plan_title);
                        $data['purchase_type']= 'Anually';
                        $data['strip_amount'] =  number_format($response['plan']['amount'] / 100, 2);
                        $expiry_date = date('M d, Y', strtotime("+".$res->plan_time." years"));
                    }elseif($res->package_type == 'M'){
                        $data['package_title']=strip_tags($res->plan_title);
                        $data['strip_amount'] =  number_format($response['plan']['amount'] / 100, 2);
                        $data['purchase_type']= 'Monthly';
                        $expiry_date = date('M d, Y', strtotime("+".$res->plan_time." month"));
                    }
                    $data['vchfirst_name'] = $userinfo->vchfirst_name;
                    $data['vchsitename'] = $managesite->vchsitename;
                    $data['vlogo'] =  "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo;
                    $data['siteurl'] =  "https://".$managesite->txtsiteurl;
                    $data['package_name'] =  strip_tags($res->plan_name);
                    $data['payment_time']= date('M d, Y');
                    $data['package_startdate'] = date('M d');
                    $data['expiry_date'] = $expiry_date;

                    $data['receipt_url'] = $invoiceresponse['hosted_invoice_url'];
                    $data['surface']=$managesite->surface;
                    $data['surfacetext_iconcolor']=$managesite->surfacetext_iconcolor;
                    $data['primary_color']=$managesite->primary_color;
                    $data['primarytext_iconcolor']=$managesite->primarytext_iconcolor;
                    $data['hyperlink']=$managesite->hyperlink;
                    $data['bgtext_iconcolor']=$managesite->bgtext_iconcolor;
                    $data['background_color']=$managesite->background_color;

                    $data['contactlink'] = "https://".$managesite->txtsiteurl.'/custom';
                    Mail::send('email.purchase',['data'=> $data], function ($message) use ($data2) {
                        $message->from($data2['email'],$data2['vchsitename']);
                        $message->to($data2['emailfrom']);
                        $message->subject('Your receipt from '.$data2['vchsitename']);
                    });
                } else {
                    $buypack = DB::table('tbl_buypackage')->leftjoin('tbluser','tbl_buypackage.package_userid','tbluser.intuserid')->where('package_id',$res->package_id)->first();
                    $managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('intmanagesiteid',$res->site_id)->first();
                    $dataarr = array(
                        "package_subscription" => 'C',
                        "status" => 'D',
                    );

                    $this->HomeModel->UpdateBuyPackage($res->package_id,$dataarr);

                    Stripe\Stripe::setApiKey($getapidetail->stripe_secret);
                    $subscription = \Stripe\Subscription::retrieve($buypack->package_renewid);
                    $subscription->cancel();
                    $response = $subscription->jsonSerialize();

                    $userinfo=$this->HomeModel->UserData($res->package_userid);
                    $data2 = array('email'=> $userinfo->vchemail,'emailfrom'	=> $managesite->vchemailfrom,'siteinfo'=> $managesite->vchsitename);
                    $renewdata = [
                        "package" => $res->package_name,
                        "siteinfo" => $managesite->vchsitename,
                        "vlogo" => "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo,
                        "siteurl" => "https://".$managesite->txtsiteurl,
                        "vchfirst_name" => $userinfo->vchfirst_name,
                        "plan_title" => $res->plan_title
                    ];
                    $renewdata['surface']=$managesite->surface;
                    $renewdata['surfacetext_iconcolor']=$managesite->surfacetext_iconcolor;
                    $renewdata['primary_color']=$managesite->primary_color;
                    $renewdata['primarytext_iconcolor']=$managesite->primarytext_iconcolor;
                    $renewdata['hyperlink']=$managesite->hyperlink;
                    $renewdata['bgtext_iconcolor']=$managesite->bgtext_iconcolor;

                    Mail::send('email.unpaid',['data'=> $renewdata], function ($message) use ($data2) {
                        $message->from($data2['emailfrom'],$data2['siteinfo']);
                        $message->to($data2['email']);
                        $message->subject('Your payment for '.$data2['siteinfo'].' could not be processed');
                    });
                }
            }

            elseif ($res->package_subscription=='C'){
                $this->deactivatePackage($res);
            }

//		    \Log::info("Package Renew Cron");
        }

        return 220;
    }

    /**
     * @param $res
     */
    private function deactivatePackage($res): void
    {
        $this->HomeModel->UpdateBuyPackage($res->package_id, ['status' => 'D']);
    }

    /**
     * @param array $response
     * @param $res
     * @return bool
     */
    private function paymentSuccessful(array $response, $res): bool
    {
        return (data_get($response, 'status') == 'active' || data_get($response, 'status') == 'succeeded') && data_get($response, 'current_period_end') > $res->package_start_time;
    }

    private function createPackageFromOld($oldPackage, $stripePayment, $invoiceResponse)
    {
        try {
            $payment = [
                'strip_paymentid' => $stripePayment['id'],
                'strip_packagename' => $oldPackage->package_name,
                'strip_transactionid' => $stripePayment['plan']['id'],
                'strip_amount' => $stripePayment['plan']['amount'],
                'strip_currency' => $stripePayment['plan']['currency'],
                'strip_created' => $stripePayment['plan']['created'],
                'strip_receipt_url' => $invoiceResponse['hosted_invoice_url'],
                'strip_status' => $stripePayment['status'],
                'strip_payment_type' => 'Renew Subscription',
                'plan_id' => $oldPackage->buy_id,
                'user_id' => $oldPackage->package_userid,
                'create_at' => now(),
                'strip_package_type' => $oldPackage->package_type,
            ];

            $newPaymentId = $this->HomeModel->paymentinfo_insert($payment);

            $data = [
                'buy_id' => $oldPackage->buy_id,
                'package_name' => $oldPackage->package_name,
                'package_credit' => $oldPackage->package_credit,
                'extra_credit' => $oldPackage->extra_credit,
                'package_count' => $oldPackage->package_count,
                'package_startdate' => Carbon::parse($stripePayment['current_period_start']),
                'package_expiredate' => Carbon::parse($stripePayment['current_period_end']),
                'package_userid' => $oldPackage->package_userid,
                'package_download' => 0,
                'payment_id' => $newPaymentId,
                'package_renewid' => $oldPackage->package_renewid,
                'package_subscription' => $oldPackage->package_subscription,
                'package_type' => $oldPackage->package_type,
                'site_id' => $oldPackage->site_id,
                'package_start_time' => $oldPackage->package_start_time,
                'status' => $oldPackage->status,
                'create_at' => $oldPackage->create_at,
                'plan_id' => $oldPackage->plan_id,
                'plan_name' => $oldPackage->plan_name,
                'plan_title' => $oldPackage->plan_title,
                'plan_description' => $oldPackage->plan_description,
                'plan_time' => $oldPackage->plan_time,
                'plan_price' => $oldPackage->plan_price,
                'plan_download' => $oldPackage->plan_download,
                'plan_type' => $oldPackage->plan_type,
                'plan_purchase' => $oldPackage->plan_purchase,
                'plan_status' => $oldPackage->plan_status,
                'plan_siteid' => $oldPackage->plan_siteid,
                'yearly_discount' => $oldPackage->yearly_discount,
                'conversion_rate' => $oldPackage->conversion_rate,
                'plan_createdate' => now(),
            ];

            $this->HomeModel->buypackage_insert($data);
        } catch (Exception $e) {
            Log::critical($e->getMessage());
        }
    }
}
