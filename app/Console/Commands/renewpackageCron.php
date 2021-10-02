<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Home\HomeModel;
use File;
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
                $response = \Stripe\Subscription::update(
                  $res->package_renewid
                );
                $response = $response->jsonSerialize();
                $invoice_number=$response['latest_invoice'];
                 Stripe\Stripe::setApiKey($getapidetail->stripe_secret);
                $invoice =	Stripe\Invoice::retrieve($response['latest_invoice']);
                $invoiceresponse = $invoice->jsonSerialize();
                if($response['status'] == 'active' || $response['status'] == 'succeeded'){
                    if($response['current_period_end'] > $res->package_start_time){
                        //$available = $res->package_count - $res->package_download;
                        $available = 0;
                        $data = [
                            "package_count"=>$available+$res->package_count,
                            "package_download"=>'0',
                            "package_expiredate"=>date('Y-m-d H:i:s',strtotime('+30 days')),
                            "package_start_time"=>$response['current_period_end']

                        ];
                        $planinfo = $this->HomeModel->UpdateBuyPackage($res->package_id,$data);

                        $paymentdata = [
                                "strip_paymentid"=>$response['id'],
                                "strip_packagename"=>$res->plan_title,
                                "strip_transactionid"=>$response['plan']['id'],
                                "strip_amount"=>($response['plan']['amount'] / 100),
                                "strip_created"=>$response['plan']['created'],
                                "strip_currency"=>$response['plan']['currency'],
                                "strip_receipt_url"=>$invoiceresponse['hosted_invoice_url'],
                                "strip_status"=>$response['status'],
                                "plan_id"=>$res->buy_id,
                                "user_id"=>$res->package_userid,
                                "strip_payment_type"=>'Renew Subscription',
                                "strip_package_type"=>$res->package_type,
                                "create_at"=>date('Y-m-d H:i:s')
                            ];
                        $paymentlastid = $this->HomeModel->paymentinfo_insert($paymentdata);

                        $managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('intmanagesiteid',$res->site_id)->first();
                        $userinfo=$this->HomeModel->UserData($res->package_userid);

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
                                Mail::send('email.purchase',['data'=>$data], function ($message) use ($data2) {

                                $message->from($data2['email'],$data2['vchsitename']);
                                $message->to($data2['emailfrom']);
                                $message->subject('Your receipt from '.$data2['vchsitename']);
                                });
                    }
                } else {
                    $buypack = DB::table('tbl_buypackage')->leftjoin('tbluser','tbl_buypackage.package_userid','tbluser.intuserid')->where('package_id',$res->package_id)->first();
                    $managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('intmanagesiteid',$res->site_id)->first();
                    $dataarr = array(
                        "package_subscription"=> 'C',
                        "status"=> 'D',
                    );

                    $this->HomeModel->UpdateBuyPackage($res->package_id,$dataarr);

                    Stripe\Stripe::setApiKey($getapidetail->stripe_secret);
                    $subscription = \Stripe\Subscription::retrieve($buypack->package_renewid);
                    $subscription->cancel();
                    $response = $subscription->jsonSerialize();

                    $userinfo=$this->HomeModel->UserData($res->package_userid);
                    $data2 = array('email'=>$userinfo->vchemail,'emailfrom'	=> $managesite->vchemailfrom,'siteinfo'=>$managesite->vchsitename);
                    $renewdata = [
                        "package"=>$res->package_name,
                        "siteinfo"=>$managesite->vchsitename,
                        "vlogo"=> "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo,
                        "siteurl"=> "https://".$managesite->txtsiteurl,
                        "vchfirst_name" => $userinfo->vchfirst_name,
                        "plan_title" => $res->plan_title
                    ];
                    $renewdata['surface']=$managesite->surface;
                    $renewdata['surfacetext_iconcolor']=$managesite->surfacetext_iconcolor;
                    $renewdata['primary_color']=$managesite->primary_color;
                    $renewdata['primarytext_iconcolor']=$managesite->primarytext_iconcolor;
                    $renewdata['hyperlink']=$managesite->hyperlink;
                    $renewdata['bgtext_iconcolor']=$managesite->bgtext_iconcolor;

                     Mail::send('email.unpaid',['data'=>$renewdata], function ($message) use ($data2) {
                        $message->from($data2['emailfrom'],$data2['siteinfo']);
                        $message->to($data2['email']);
                        $message->subject('Your payment for '.$data2['siteinfo'].' could not be processed');
                    });
                }
		    }

            elseif ($res->package_subscription=='C'){
                $dataarr = array(
                    "status"=> 'D',

                );
                    $this->HomeModel->UpdateBuyPackage($res->package_id,$dataarr);
                }

//		    \Log::info("Package Renew Cron");
    }
}
}
