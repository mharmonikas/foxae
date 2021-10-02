<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Home\HomeModel;
use Session;
use App\Admin; 
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
//use App\File;
use Illuminate\Support\Facades\Hash;
use Mail;
use Response;
use Stripe; 
use Validator;
use File;
use ZipArchive;
class TestController extends Controller {
	
	public function __construct(HomeModel $HomeModel) {
        $this->HomeModel = $HomeModel;
		
    }
	public function checklogin(){
	 $intUserID = Session::get('userid');	
		if(empty($intUserID)){		 
			return redirect('/'); 
			 //exit;
		 }
		
	}


		public static function mycrypt($string,$action = 'e'){
            // you may change these values to your own
            $secret_key = 'my_simple_secret_key';
            $secret_iv = 'my_simple_secret_iv';

            $output = false;
            $encrypt_method = "AES-256-CBC";
            $key = hash( 'sha256', $secret_key );
            $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

            if( $action == 'e' ) {
                $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
            }
            else if( $action == 'd' ){
                $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
            }

            return $output;
    }



	public function downloadData(Request $request){
		//echo  $this->checklogin();
		$videoid = $request->id;
		$id = Crypt::decryptString($request->id);
		$response = DB::table('tbl_Video')->where('IntId',$id)->first();
		$res =[];
		if(!empty(Session::get('userid'))){
		if(!empty($response)){
				
				$getdownloadresponse = DB::table('tbl_download')->where('user_id',Session::get('userid'))->where('video_id',$id)->first();
				
				$packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();

				if(!$packageavailable->isEmpty()){	
				
					$packageid ="";
					foreach($packageavailable as $pack){
						if($pack->package_download < $pack->package_count){
							if(empty($packageid)){
								$packageid = $pack->package_id;
							}
						}
					}
					
					
					
					
					//echo $needstock; exit;
					
					if(!empty($packageid)){
						
						$stockav = DB::table('tbl_buypackagestock')->where('buypackage_id',$packageid)->where("stocktype_id",$response->stock_category)->where("contentcat_id",$response->content_category)->first();
					$needstock = 1;
					if(!empty($stockav)){
						$needstock = $stockav->stock;
					}
					
						$fileName = $response->VchVideoName;
						$filePath = $response->VchFolderPath;
						$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
						$userinfo = DB::table('tbluser')->where('intuserid',Session::get('userid'))->first();
					
					
						$data2 = array(
							'email'	=> $managesite->vchemailfrom,
							'emailfrom'	=> $userinfo->vchemail,
						);
					
						$data['vchfirst_name'] = $userinfo->vchfirst_name;
						$data['vchsitename'] = $managesite->vchsitename;
						$data['downloadlink'] = "https://".$managesite->txtsiteurl.'/member-download/';
						$data['vlogo'] =  "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo;
						// Mail::send('email.download',['data'=>$data], function ($message) use ($data2) {
						  
						// $message->from($data2['email'],'noreply');
						// $message->to($data2['emailfrom']);
						// $message->subject('Download Content');
						// });
						
				//$packageavailable = DB::table('tbl_buypackage')->where('package_userid',Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();
				$package ="";
				$utlra = "";
				if(!empty($getdownloadresponse)){
				
					foreach($packageavailable as $packageavailables){
					//$packcount=$packageavailables->package_count;
						$packagedownload=$packageavailables->package_download;
							if($packageavailables->package_download < $packageavailables->package_count){
							 $package=$packageavailables->package_count-$packagedownload;
							// echo $package;
								//exit;
							}
					}
				}else{
					
					foreach($packageavailable as $packageavailables){
					//$packcount=$packageavailables->package_count;
						$packagedownload=$packageavailables->package_download+$needstock;
						// echo $packagedownload;
						// exit;
						if($packageavailables->package_download < $packageavailables->package_count){
						 $package=$packageavailables->package_count-$packagedownload;
						 
							if($package == 0){
								$utlra = "last";
							}
						}
					}
				}
				if(!empty($package) && $package > 0){
					$pvalue="yes";
				}else{
					if(!empty($utlra)){
						$pvalue="yes";
					}else{
						$pvalue="no";
					}
					
				}
				if(!empty($getdownloadresponse)){
				$res = array('response'=>'done','image'=>$videoid,'pack'=>1,'val'=>'yes','id'=>$id,'credit'=>0,"download"=>'old');
				}else{
					$res = array('response'=>'done','image'=>$videoid,'pack'=>(($package > 0)?$package:0),'val'=>$pvalue,'id'=>$id,'credit'=>1,"download"=>'new');
				}
					}else{
						if(!empty($getdownloadresponse)){
							$res = array('response'=>'done','image'=>$videoid,'credit'=>0,"download"=>'old','pack'=>1,'val'=>'yes');
						}else{
							$res = array('response'=>'expire',"download"=>'old','pack'=>1,'val'=>'yes');
						}
					}
			}else{
				if(!empty($getdownloadresponse)){
					$res = array('response'=>'done','image'=>$videoid,"download"=>'old');
				}else{
					$res = array('response'=>'expire',"download"=>'old');
				}
			}
		}else{
			$res = array('response'=>'expire');
		}
		}else{
			$res = array('response'=>'login');
		}
		echo json_encode($res);
	}
		
	
	public function fileTodownload($id){
		echo  $this->checklogin();
		$id = Crypt::decryptString($id);
	
		$getdownloadresponse = DB::table('tbl_download')->where('user_id',Session::get('userid'))->where('video_id',$id)->first();
		
		if(!empty($getdownloadresponse)){
			$this->DownloadFileServer2($id);
			//$this->RemoveFromWishlist($id);
		}else{
			$packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();
			if(!$packageavailable->isEmpty()){	
					$packageid ="";
					$packageDownloadcount ="";
					$buyid ="";
					foreach($packageavailable as $pack){
						if($pack->package_download < $pack->package_count){
							if(empty($packageid)){
								$packageid = $pack->package_id;
								$buyid = $pack->buy_id;
								$packageDownloadcount = $pack->package_download;
							}
						}
					}
					if(!empty($packageid)){
						$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
						
						$videoinfo = DB::table('tbl_Video')->select('content_category','stock_category')->where('IntId',$id)->first();
						
						$stockinfo = DB::table('tbl_buypackagestock')->leftjoin('tbl_plan','tbl_plan.plan_id','tbl_buypackagestock.plan_id')->where('buypackage_id',$packageid)->where('plan_id',$buyid)->where('stocktype_id',$videoinfo->stock_category)->where('contentcat_id',$videoinfo->content_category)->first(); 
						if(!empty($stockinfo)){
							
								if($stockinfo->stock > 0){
									/* echo '<pre>';
									print_r($videoinfo);
									echo $videoinfo->stock_category."<br>";
									echo $videoinfo->content_category."<br>";
									//echo $videoinfo->stock."<br>";
									echo $stockinfo->stock;
									echo 'Yes';
									exit;
									 */
									$data = [
										"video_id"=>$id,
										"user_id"=>Session::get('userid'),
										"site_id"=>$managesite->intmanagesiteid,
										"create_at"=>date("Y-m-d H:i:s")
									];
									$this->HomeModel->DownloadData($data);
								/* One Check pending Start date or end date  */
								
									$datapackage = [
										"package_download" => $packageDownloadcount + $stockinfo->stock / $stockinfo->conversion_rate
									];
									DB::table('tbl_buypackage')->where('package_id', $packageid)->update($datapackage);
									
									// $stockupdate = [
										// "stock" =>  ($stockinfo->stock-1)
									// ];
									// DB::table('tbl_buypackagestock')->where('intid', $stockinfo->intid)->update($stockupdate);
									
									$this->DownloadFileServer2($id);
								}else{
									echo 'No Stock available';
								}
						}else{
							$data = [
								"video_id"=>$id,
								"user_id"=>Session::get('userid'),
								"site_id"=>$managesite->intmanagesiteid,
								"create_at"=>date("Y-m-d H:i:s")
							];
							$this->HomeModel->DownloadData($data);
							/* One Check pending Start date or end date  */
							$datapackage = [
								"package_download" => $packageDownloadcount+1
							];
							DB::table('tbl_buypackage')->where('package_id', $packageid)->update($datapackage);
							$this->DownloadFileServer2($id);
						}
						//$this->RemoveFromWishlist($id);
					}
			
			}
		}

	}
	public function DownloadFileServer2($id){
		$response = DB::table('tbl_Video')->where('IntId',$id)->first();
		$fileName = $response->VchVideoName;
		$filePath = $response->VchFolderPath."/".$fileName;
		$filenew = explode(".",basename($filePath));
        if(file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$response->VchTitle.'.'.end($filenew).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            flush(); // Flush system output buffer
            readfile($filePath);
			$this->RemoveFromWishlist($id);
            die();
        } else {
           die("Invalid file name!");
	        die();
        }
	}


	public function payment(Request $request){
		 $managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		$userinfo = $this->HomeModel->UserData(Session::get('userid'));
		
		if(!empty($userinfo) && $userinfo->verifystatus == '1'){
		if(!empty(Session::get('packageid'))){
			$packageid = Session::get('packageid');
			$getplan = DB::table('tbl_plan')->where('plan_id',$packageid)->where('plan_status','A')->first();
			$price1= ($getplan->plan_price * 12);
				$price2= ($getplan->plan_price * 12 * ($getplan->yearly_discount / 100));
				$yearlyprice=$price1-$price2;
				$monthlyprice=$yearlyprice/12;
			if($getplan->plan_purchase == 'O'){
				Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
				$paymentarray = [
						"amount" => $getplan->plan_price * 100,
						"currency" => "usd",
						"source" => $request->stripeToken,
						"description" => strip_tags($getplan->plan_name) 
				];
				$response = Stripe\Charge::create($paymentarray);
				$response = $response->jsonSerialize();
			}
			
			if($getplan->plan_purchase == 'M'){
				if(Session::get('packagetype')=='monthly'){
				
				Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
				 $customer = \Stripe\Customer::create(array( 
					'email' => $userinfo->vchemail, 
					'source'  => $request->stripeToken 
				)); 
				
				// Create a plan 
				try { 
					$plan = \Stripe\Plan::create(array( 
						"product" => [ 
							"name" => strip_tags($getplan->plan_name) 
						], 
						"amount" => $monthlyprice * 100, 
						"currency" => 'usd', 
						"interval" => 'month', 
						"interval_count" => 1 
					)); 
				}catch(Exception $e) { 
					$api_error = $e->getMessage(); 
				} 
				 
				if(empty($api_error) && $plan){ 
					// Creates a new subscription 
					try { 
						$subscription = \Stripe\Subscription::create([
							  'customer' => $customer->id,
							  'items' => [
								[
								  'plan' => $plan->id

								],
							  ],
							]);
					}catch(Exception $e) { 
						$api_error = $e->getMessage(); 
					} 
							
		
			}
			$response = $subscription->jsonSerialize();
			}else if(Session::get('packagetype')=='annual'){
				Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
				$paymentarray = [
						"amount" => $yearlyprice * 100,
						"currency" => "usd",
						"source" => $request->stripeToken,
						"description" => strip_tags($getplan->plan_name) 
				];
				$response = Stripe\Charge::create($paymentarray);
				$response = $response->jsonSerialize();
				
			}
			}
			if($response['status'] == 'active' || $response['status'] == 'succeeded'){
				$renewid="";
				if($getplan->plan_purchase == 'M'){
						if(Session::get('packagetype')=='monthly'){
								$buy_type='M';
					$packagestart = $response['current_period_end'];
					$renewid=$response['id'];
					$paymentdata = [
						"strip_paymentid"=>$response['id'],
						"strip_packagename"=>$getplan->plan_title,
						"strip_transactionid"=>$response['plan']['id'],
						"strip_amount"=>($response['plan']['amount'] / 100),
						"strip_created"=>$response['plan']['created'],
						"strip_currency"=>$response['plan']['currency'],
						"strip_receipt_url"=>'',
						"strip_status"=>$response['status'],
						"plan_id"=>$packageid,
						"user_id"=>Session::get('userid'),
						"create_at"=>date('Y-m-d H:i:s')
					];
					}else if(Session::get('packagetype')=='annual'){
						
						$buy_type='Y';
						$paymentdata = [
						"strip_paymentid"=>$response['id'],
						"strip_packagename"=>$getplan->plan_title,
						"strip_transactionid"=>$response['balance_transaction'],
						"strip_amount"=>($response['amount'] / 100),
						"strip_created"=>$response['created'],
						"strip_currency"=>$response['currency'],
						"strip_receipt_url"=>$response['receipt_url'],
						"strip_status"=>$response['status'],
						"plan_id"=>$packageid,
						"user_id"=>Session::get('userid'),
						"create_at"=>date('Y-m-d H:i:s')
					];	
						
						
					}
				}else if($getplan->plan_purchase == 'O'){
					$buy_type='O';
					$paymentdata = [
						"strip_paymentid"=>$response['id'],
						"strip_packagename"=>$getplan->plan_name,
						"strip_transactionid"=>$response['balance_transaction'],
						"strip_amount"=>($response['amount'] / 100),
						"strip_created"=>$response['created'],
						"strip_currency"=>$response['currency'],
						"strip_receipt_url"=>$response['receipt_url'],
						"strip_status"=>$response['status'],
						"plan_id"=>$packageid,
						"user_id"=>Session::get('userid'),
						"create_at"=>date('Y-m-d H:i:s')
					];
				}
				$paymentlastid = $this->HomeModel->paymentinfo_insert($paymentdata);
				
				$userbillinginfo = [
					"user_id"=>Session::get('userid'),
					"billing_address_line1"=>$request->address_line1,
					"billing_address_line2"=>$request->address_line2,
					"billing_city"=>$request->city,
					"billing_state"=>$request->state,
					"billing_zipcode"=>$request->zip,
					"billing_country"=>$request->country,
					"payment_id"=>$paymentlastid,
					"create_at"=>date('Y-m-d H:i:s')
				];
				
				$this->HomeModel->billinginfo_insert($userbillinginfo);
				$carddata = [
					'holder_name'=>$request->cardname,
					'c_number'=>$request->cardnumber,
					'exp_month'=>$request->expirationdate,
					'exp_year'=>$request->expirationYeardate,
					'c_userid'=>Session::get('userid'),
					'create_at'=>date('Y-m-d H:i:s')
				];
				$insertid = DB::table('tbl_paymentdetails')->insert($carddata);
				$packagedata = [
					'buy_id'=>$packageid,
					'package_name'=>$getplan->plan_name,
					'package_count'=>$getplan->plan_download,
					'package_credit'=>$getplan->plan_download,
					'package_userid'=>Session::get('userid'),
					'package_download'=>0,
					'site_id'=>$managesite->intmanagesiteid,
					"payment_id"=>$paymentlastid,
					"package_type"=>$buy_type,
					'create_at'=>date('Y-m-d H:i:s')
				];
			
				if(!empty($renewid)){
					$packagedata['package_renewid'] = $renewid;
					$packagedata['package_subscription'] = 'Y';
				}
				$packagedata['package_startdate'] = date('Y-m-d H:i:s');	
				if($buy_type == 'M'){
					
					$packagedata['package_start_time'] = $packagestart;
					$packagedata['package_expiredate'] = date('Y-m-d H:i:s', strtotime("+".$getplan->plan_time." month"));
				}else if($buy_type == 'Y' || $getplan->plan_type == 'O'){
					$packagedata['package_expiredate'] = date('Y-m-d H:i:s', strtotime("+".$getplan->plan_time." years"));
				}
				$buyid = $this->HomeModel->buypackage_insert($packagedata);
				
				$packageStocks = $this->HomeModel->packageStock($packageid);
				foreach($packageStocks as $packageStock){ 
					$packageDownloadData = [
						"buypackage_id"=>$buyid,
						"plan_id"=>$packageStock->plan_id,
						"stocktype_id"=>$packageStock->stocktype_id,
						"contentcat_id"=>$packageStock->contentcat_id,
						"stock"=>$packageStock->stock,
						"created_date"=>date('Y-m-d H:i:s'),
					];				
					$this->HomeModel->buypackagestock_insert($packageDownloadData);
				}
				$userinfo = DB::table('tbluser')->where('intuserid',Session::get('userid'))->first();
				$data2 = array(
							'email'	=> $managesite->vchemailfrom,
							'emailfrom'	=> $userinfo->vchemail,
						);
					
						$data['vchfirst_name'] = $userinfo->vchfirst_name;
						$data['vchsitename'] = $managesite->vchsitename;
						$data['vlogo'] =  "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo;
						$data['package_name'] =  strip_tags($getplan->plan_name);
						if($buy_type == 'M'){
							$data['strip_amount'] =  ($response['plan']['amount'] / 100);
						}else{
							$data['strip_amount'] =  ($response['amount'] / 100);
						}
						$data['contactlink'] = "https://".$managesite->txtsiteurl.'/custom';
						Mail::send('email.purchase',['data'=>$data], function ($message) use ($data2) {
						  
						$message->from($data2['email'],'noreply');
						$message->to($data2['emailfrom']);
						$message->subject('Payment Confirmation');
						});
				
				
				Session::put('packageid','');
				Session::put('packagetype','');
				Session::put('price','');
			
				$arrayresponse = array('response'=>'done','transaction'=>'','code'=>200);
			}else{
				$arrayresponse = array('response'=>'failed','code'=>504);
			}
		}else{
			if(!empty(Session::get('packagetype')=='direct')){
				$puchasetype=Session::get('packagetype');
				$puchaseprice=Session::get('price');
				
				Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
				$paymentarray = [
						"amount" => $puchaseprice * 100,
						"currency" => "usd",
						"source" => $request->stripeToken,
						"description" => strip_tags('direct payment') 
				];
				$response = Stripe\Charge::create($paymentarray);
				$response = $response->jsonSerialize();
				
				$paymentdata = [
						"strip_paymentid"=>$response['id'],
						"strip_packagename"=>'No Sub - Direct Payment',
						"strip_transactionid"=>$response['balance_transaction'],
						"strip_amount"=>($response['amount'] / 100),
						"strip_created"=>$response['created'],
						"strip_currency"=>$response['currency'],
						"strip_receipt_url"=>$response['receipt_url'],
						"strip_status"=>$response['status'],
						"plan_id"=>0,
						"user_id"=>Session::get('userid'),
						"create_at"=>date('Y-m-d H:i:s')
					];
				
				$paymentlastid = $this->HomeModel->paymentinfo_insert($paymentdata);
				
				$userbillinginfo = [
					"user_id"=>Session::get('userid'),
					"billing_address_line1"=>$request->address_line1,
					"billing_address_line2"=>$request->address_line2,
					"billing_city"=>$request->city,
					"billing_state"=>$request->state,
					"billing_zipcode"=>$request->zip,
					"billing_country"=>$request->country,
					"payment_id"=>$paymentlastid,
					"create_at"=>date('Y-m-d H:i:s')
				];
				
				$this->HomeModel->billinginfo_insert($userbillinginfo);
				
				$packagedata = [
					'buy_id'=>0,
					'package_name'=>'',
					'package_count'=>0,
					'package_credit'=>0,
					'package_userid'=>Session::get('userid'),
					'package_download'=>0,
					'site_id'=>$managesite->intmanagesiteid,
					"payment_id"=>$paymentlastid,
					"package_type"=>'D',
					'create_at'=>date('Y-m-d H:i:s')
				];
				$buyid = $this->HomeModel->buypackage_insert($packagedata);
				
				$carddata = [
					'holder_name'=>$request->cardname,
					'c_number'=>$request->cardnumber,
					'exp_month'=>$request->expirationdate,
					'exp_year'=>$request->expirationYeardate,
					'c_userid'=>Session::get('userid'),
					'create_at'=>date('Y-m-d H:i:s')
				];
				$insertid = DB::table('tbl_paymentdetails')->insert($carddata);
				
				Session::put('packagetype','');
				Session::put('price','');
			
				$arrayresponse = array('response'=>'done','transaction'=>'','type'=>'direct','code'=>200);
				
			}else{
				$arrayresponse = array('response'=>'failed','code'=>404);
		}
		}
		}else{
			$arrayresponse = array('response'=>'failed','code'=>301);
		}
		echo json_encode($arrayresponse);
	}

	
	public function wishlistData(Request $request){
		//echo  $this->checklogin();
		if(!empty(Session::get('userid'))){
			$userid=Session::get('userid');
			}else{
			$userid=Session::getId();
		}
		//echo $userid;
		//exit;
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		if(!empty($request->id)){
		$videoid = $request->id;
		$id = Crypt::decryptString($request->id);
		}else{
			$id = $request->videoid;
		}
		$date = date('Y-m-d H:i:s');
		$cartstatus = $request->cartstatus;
		if($cartstatus == 'Add'){
			$checklist=DB::table('tbl_wishlist')->where('userid',$userid)->where('siteid',$managesite->intmanagesiteid)->where('videoid',$id)->first();
			if(empty($checklist)){
			$data = array(
				'videoid'	=> $id,
				'siteid'	=> $managesite->intmanagesiteid,
				'userid'=> $userid,
				'created_date'	=> $date,
			);
			$lastinsetid=$this->HomeModel->wishlistdata($data);
			}
		}elseif($cartstatus == 'Remove'){
			$this->HomeModel->DeleteFromWishlist($id,$managesite->intmanagesiteid,$userid);
		}
		
			$cartcount=DB::table('tbl_wishlist')->where('userid',$userid)->where('status','cart')->where('siteid',$managesite->intmanagesiteid)->count();
			$availablecount = '';
			if(!empty(Session::get('userid'))){
				$packageid ="";
				$packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',$userid)->whereDate('package_expiredate','>',date('Y-m-d'))->get();
				if(!$packageavailable->isEmpty()){	
					$packageid ="";
					$buyid ="";
					$availablecount = 0;
					$total_credit = 0;
					$used_credit = 0;
					
					foreach($packageavailable as $pack){
						$total_credit += $pack->package_count;
						$used_credit += $pack->package_download;
						if($pack->package_download < $pack->package_count){
							
							if(empty($packageid)){
								$packageid = $pack->package_id;
								$buyid = $pack->buy_id;	
							}
						}
					}
					$availablecount = $total_credit - $used_credit;
								if($availablecount == 0){
									$packageid = "";
								}
				}
				
		if(!empty($packageid)){
			$cartvalue=0;
			$response =  DB::table('tbl_wishlist')->select('tbl_plan.*','tbl_buypackagestock.*','tbl_buypackage.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
			->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
			->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
			->leftjoin('tbl_buypackage','tbl_buypackage.package_userid','tbl_wishlist.userid')
			
			->leftjoin("tbl_buypackagestock",function($join){
						$join->on("tbl_buypackagestock.buypackage_id","=","tbl_buypackage.package_id")
						->on("tbl_buypackagestock.stocktype_id","=","tbl_Video.stock_category")
						->on("tbl_buypackagestock.contentcat_id","=","tbl_Video.content_category");
					})
			->leftjoin('tbl_plan','tbl_plan.plan_id','tbl_buypackagestock.plan_id')
			//->where('tbl_buypackagestock.stocktype_id','tbl_Video.stock_category')
			//->where('tbl_buypackagestock.contentcat_id','tbl_Video.content_category')
			->where('tbl_wishlist.userid',$userid)
			->where('tbl_wishlist.status','cart')
			->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)
			->where('tbl_buypackage.status','A')
			->whereDate('tbl_buypackage.package_expiredate','>',date('Y-m-d'))
			->orderBy('tbl_wishlist.created_date','DESC')
			 ->groupBy('tbl_Video.IntId')
			->get();
			//->paginate(10);
			//print_r($response);
		
			if(!$response->isEmpty()){
			$totalitems=count($response);
			$cartvalue=$this->incartcredit();
		
			$cartcredit=0;
			
				foreach($response as $res){
					$cartcredit +=$res->stock;
							
				}
				
			if(!empty($res->conversion_rate)){
							$cartvalue =$cartcredit/$res->conversion_rate;	
						}else{
							$cartvalue =$cartcredit;
						}
				
			}
			
			 }else{
				$cartcredit=0;
				$cartvalue=0;
				$response =  DB::table('tbl_wishlist')->select('tblstock.*','tbl_plan.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
				->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
				->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
					->leftjoin("tblstock",function($join){
						 $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
						 ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
					})
				->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
				->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.status','cart')->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->orderBy('tbl_wishlist.created_date','DESC')->groupBy('tbl_Video.IntId')
				->get();
				//->toSql();
				//print_r($response);
				//exit;
				
				if(!$response->isEmpty()){
				$totalitems=count($response);
						foreach($response as $res){
							$cartcredit +=$res->stock;
							
						}
						if(!empty($res->conversion_rate)){
							$cartvalue =$cartcredit/$res->conversion_rate;	
						}else{
							$cartvalue =$cartcredit;
						}
				}
				  }
			}else{
				$packageid='';
				$cartcredit=0;
				$cartvalue=0;
				$userid=Session::getId();
				$response =  DB::table('tbl_wishlist')->select('tblstock.*','tbl_plan.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
				->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
				->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
					->leftjoin("tblstock",function($join){
						 $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
						 ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
					})
				->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
				->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.status','cart')->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->orderBy('tbl_wishlist.created_date','DESC')->groupBy('tbl_Video.IntId')
		
				->get();
					// print_r($response);
				// exit;
				if(!$response->isEmpty()){
				$totalitems=count($response);
						foreach($response as $res){
							$cartcredit +=$res->stock;
						}
						if(!empty($res->conversion_rate)){
							$cartvalue =$cartcredit/$res->conversion_rate;	
						}else{
							$cartvalue =$cartcredit;
						}
						
				//$cartvalue =$cartcredit/$res->conversion_rate;
				
				}
		}
				$carticon= '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 3c0 .55.45 1 1 1h1l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h11c.55 0 1-.45 1-1s-.45-1-1-1H7l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.67-1.43c-.16-.35-.52-.57-.9-.57H2c-.55 0-1 .45-1 1zm16 15c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path></svg>';
            $value = array('response'=>1,'count'=>$cartcount,'cart_icon'=>$carticon,'availablecount'=>$availablecount,'cartvalue'=>$cartvalue,'packageid'=>$packageid);
		
			echo json_encode($value); 
		}
	public function wishlist(){
			echo  $this->checklogin();
		$userid = Session::get('userid');
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		$response =  DB::table('tbl_wishlist')->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId')->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->orderBy('created_date','DESC')->paginate(10);
		$siteid=$managesite->intmanagesiteid;
		
		return view('/user-wishlist',compact('response','siteid'));
		
		
	}
	public function deletewishlist(Request $request){
		//echo  $this->checklogin();
		$resname =  DB::table('tbl_wishlist')->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId')->where('tbl_wishlist.id',$request->id)->first();
		
		$results = DB::table('tbl_wishlist')->where('id', $request->id)->delete();
		
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('tbl_managesite.txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		$siteid=$managesite->intmanagesiteid;
		$package='';
		$stockinfo='';
		$totalitems='';
		$cartvalue=0;
		$checkloginuser='';
		if(!empty(Session::get('userid'))){
				$userid=Session::get('userid');
				$checkloginuser=Session::get('userid');
					$response =  DB::table('tbl_wishlist')
					->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
					->leftjoin('tbl_buypackage','tbl_buypackage.package_userid','tbl_wishlist.userid')
					->leftjoin("tbl_buypackagestock",function($join){
								$join->on("tbl_buypackagestock.buypackage_id","=","tbl_buypackage.package_id")
								->on("tbl_buypackagestock.stocktype_id","=","tbl_Video.stock_category")
								->on("tbl_buypackagestock.contentcat_id","=","tbl_Video.content_category");
							})
					->leftjoin('tbl_plan','tbl_plan.plan_id','tbl_buypackagestock.plan_id')
					//->leftjoin('tbl_buypackagestock','tbl_buypackage.package_id','tbl_buypackagestock.buypackage_id')
					//->where('tbl_buypackagestock.stocktype_id','tbl_Video.stock_category')
					//->where('tbl_buypackagestock.contentcat_id','tbl_Video.content_category')
					//->whereIn('tbl_wishlist.id', $arr)
					->where('tbl_wishlist.status','cart')
					->where('tbl_wishlist.userid',$userid)
					->where('tbl_buypackage.status','A')
					->whereDate('tbl_buypackage.package_expiredate','>',date('Y-m-d'))
					->groupBy('tbl_Video.IntId')
					->get();
					
					$totalitems=count($response);
					
						foreach($response as $res){
							 $cartvalue +=$res->stock/$res->conversion_rate;
						}
						$packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',$userid)->whereDate('package_expiredate','>',date('Y-m-d'))->get();
					$package='';
					foreach($packageavailable as $packageavailables){
						if($packageavailables->package_download < $packageavailables->package_count){
							$package=$packageavailables;
					
						}
						}
		 if(!empty($package)){
			$response =  DB::table('tbl_wishlist')->select('tbl_plan.*','tbl_buypackagestock.*','tbl_buypackage.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
			->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
			->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
			->leftjoin('tbl_buypackage','tbl_buypackage.package_userid','tbl_wishlist.userid')
			
			->leftjoin("tbl_buypackagestock",function($join){
						$join->on("tbl_buypackagestock.buypackage_id","=","tbl_buypackage.package_id")
						->on("tbl_buypackagestock.stocktype_id","=","tbl_Video.stock_category")
						->on("tbl_buypackagestock.contentcat_id","=","tbl_Video.content_category");
					})
			->leftjoin('tbl_plan','tbl_plan.plan_id','tbl_buypackagestock.plan_id')
			//->leftjoin('tbl_favorites','tbl_favorites.fav_videoid','tbl_Video.IntId')
			//->where('tbl_buypackagestock.stocktype_id','tbl_Video.stock_category')
			//->where('tbl_buypackagestock.contentcat_id','tbl_Video.content_category')
			->where('tbl_wishlist.userid',$userid)
			->where('tbl_wishlist.status','cart')
			->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)
			->where('tbl_buypackage.status','A')
			->whereDate('tbl_buypackage.package_expiredate','>',date('Y-m-d'))
			->orderBy('tbl_wishlist.created_date','DESC')
			 ->groupBy('tbl_Video.IntId')
			->get();
			//->paginate(10);
		
		
			if(!$response->isEmpty()){
			$totalitems=count($response);
			$cartvalue=$this->incartcredit();
		
			$cartcredit=0;
			
				foreach($response as $res){
					$cartcredit +=$res->stock;
					
				$infavoriteslist =  DB::table('tbl_favorites')->where('tbl_favorites.fav_videoid',$res->IntId)->where('tbl_favorites.fav_userid',$userid)->where('tbl_favorites.fav_siteid',$managesite->intmanagesiteid)->first();
		
					if (!empty($infavoriteslist)) { 
						$res->favoritesstatus = 'in-favorites';
						//$res->favoriteshtml = 'fa fa-heart';
					}else{
						$res->favoritesstatus = 'out-favorites';
						//$res->favoriteshtml = 'fa fa-heart-o';
					}
							
				}
			if(!empty($res->conversion_rate)){
							$cartvalue =$cartcredit/$res->conversion_rate;	
						}else{
							$cartvalue =$cartcredit;
						}
				
			}
			$later_response =  DB::table('tbl_wishlist')->select('tbl_plan.*','tbl_buypackagestock.*','tbl_buypackage.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
			->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
			->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
			->leftjoin('tbl_buypackage','tbl_buypackage.package_userid','tbl_wishlist.userid')
			
				->leftjoin("tbl_buypackagestock",function($join){
						$join->on("tbl_buypackagestock.buypackage_id","=","tbl_buypackage.package_id")
						->on("tbl_buypackagestock.stocktype_id","=","tbl_Video.stock_category")
						->on("tbl_buypackagestock.contentcat_id","=","tbl_Video.content_category");
					})
			
			->leftjoin('tbl_plan','tbl_plan.plan_id','tbl_buypackagestock.plan_id')
			->where('tbl_wishlist.userid',$userid)
			->where('tbl_wishlist.status','later')
			->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)
			->where('tbl_buypackage.status','A')
			->whereDate('tbl_buypackage.package_expiredate','>',date('Y-m-d'))
			->orderBy('tbl_wishlist.created_date','DESC')
			 ->groupBy('tbl_Video.IntId')
			->get();
			 }else{
				$cartcredit=0;
				$cartvalue=0;
				$response =  DB::table('tbl_wishlist')->select('tblstock.*','tbl_plan.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
				->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
				->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
					->leftjoin("tblstock",function($join){
						 $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
						 ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
					})
				->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
				->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.status','cart')->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->orderBy('tbl_wishlist.created_date','DESC')->groupBy('tbl_Video.IntId')
				->get();
				//->toSql();
				//print_r($response);
				//exit;

				if(!$response->isEmpty()){
				$totalitems=count($response);
						foreach($response as $res){
							$cartcredit +=$res->stock;
							$infavoriteslist =  DB::table('tbl_favorites')->where('tbl_favorites.fav_videoid',$res->IntId)->where('tbl_favorites.fav_userid',$userid)->where('tbl_favorites.fav_siteid',$managesite->intmanagesiteid)->first();
		
					if (!empty($infavoriteslist)) { 
						$res->favoritesstatus = 'in-favorites';
						//$res->favoriteshtml = 'fa fa-heart';
					}else{
						$res->favoritesstatus = 'out-favorites';
						//$res->favoriteshtml = 'fa fa-heart-o';
					}
						}
						if(!empty($res->conversion_rate)){
							$cartvalue =$cartcredit/$res->conversion_rate;		
						}else{
							$cartvalue =$cartcredit;
						}
				}
				  }
				  $carticon= '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 3c0 .55.45 1 1 1h1l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h11c.55 0 1-.45 1-1s-.45-1-1-1H7l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.67-1.43c-.16-.35-.52-.57-.9-.57H2c-.55 0-1 .45-1 1zm16 15c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path></svg>';
			$value2 = array('cartvalue'=>$cartvalue.' Credits','cartcreditvalue'=>$cartvalue,'totalitems'=>$totalitems,'carticon'=>$carticon); 
						
			}else{
				$cartvalue=0;
				
			$userid=Session::getId();
				$response =  DB::table('tbl_wishlist')->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId')
					->leftjoin("tblstock",function($join){
						 $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
						 ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
					})
				->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
				->where('tbl_wishlist.status','cart')->where('tbl_wishlist.userid',$userid)->groupBy('tbl_Video.IntId')->get();
				
				$totalitems=count($response);
				if($totalitems>0){
						foreach($response as $res){
							$cartvalue +=$res->stock/$res->conversion_rate;
						}
				}else{
					$cartvalue=0;
					
				}
				$carticon= '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 3c0 .55.45 1 1 1h1l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h11c.55 0 1-.45 1-1s-.45-1-1-1H7l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.67-1.43c-.16-.35-.52-.57-.9-.57H2c-.55 0-1 .45-1 1zm16 15c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path></svg>';
						$value2 = array('cartvalue'=>'$'.number_format($cartvalue, 2),'totalitems'=>$totalitems,'carticon'=>$carticon); 
		}
		
		$value = array('value2'=>$value2,'response'=>1,'message'=>$resname->VchTitle.' was removed from shopping cart. <a class="hyperlink-setting" id="undo">Undo</a>'); 
		
		
		
		
		
		echo json_encode($value); 
		}
		
	
	public function cart2(){
		//echo  $this->checklogin();
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		$siteid=$managesite->intmanagesiteid;
		$package='';
		$stockinfo='';
		$totalitems='';
		$cartvalue='';
		$cartcredit='';
		$checkloginuser='';
		$packageid ="";
		$availablecount ="";
		if(!empty(Session::get('userid'))){
			$cartcredit=0;
			$cartvalue=0;
			$userid=Session::get('userid');
			$packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();
				if(!$packageavailable->isEmpty()){	
					$packageid ="";
					$buyid ="";
					$availablecount = 0;
					$total_credit = 0;
					$used_credit = 0;
					foreach($packageavailable as $pack){
						$total_credit += $pack->package_count;
						$used_credit += $pack->package_download;
						if($pack->package_download < $pack->package_count){
							if(empty($packageid)){
							 $packageid = $pack->package_id;
								$buyid = $pack->buy_id;
								$pck=$pack->package_count;
							}
						}
					}
					
								$availablecount = $total_credit - $used_credit;
								if($availablecount == 0){
									$packageid = "";
								}
				}
			 $checkloginuser=Session::get('userid');
			 if(!empty($packageid)){
				 $response =  DB::table('tbl_wishlist')->select('tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
			->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
			->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
			
			->where('tbl_wishlist.userid',$userid)
			->where('tbl_wishlist.status','cart')
			->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)
			->orderBy('tbl_wishlist.created_date','DESC')
			 ->groupBy('tbl_Video.IntId')
			->get();
			// echo "<pre>";
				// print_r($response); 
				
			
				 
				 
				 echo  $packageid;
				 
			$response2 =  DB::table('tbl_buypackage')->leftjoin("tbl_buypackagestock",function($join){
						$join->on("tbl_buypackagestock.buypackage_id","=","tbl_buypackage.package_id");
					
					})
			->leftjoin('tbl_plan','tbl_plan.plan_id','tbl_buypackagestock.plan_id')
			
			//->where('tbl_buypackage.status','A')
			->where('tbl_buypackage.package_id',$packageid)
			//->whereDate('tbl_buypackage.package_expiredate','>',date('Y-m-d'))
			
			->get();
				echo "<pre>";
				print_r($response2); 
				
				exit;
			
			//->paginate(10);
		if(!$response->isEmpty()){
			$totalitems=count($response);
			$cartvalue=$this->incartcredit();
		
			$cartcredit=0;
			
				foreach($response as $res){
					$cartcredit +=$res->stock;
					
				$infavoriteslist =  DB::table('tbl_favorites')->where('tbl_favorites.fav_videoid',$res->IntId)->where('tbl_favorites.fav_userid',$userid)->where('tbl_favorites.fav_siteid',$managesite->intmanagesiteid)->first();
		
					if (!empty($infavoriteslist)) { 
						$res->favoritesstatus = 'in-favorites';
						//$res->favoriteshtml = 'fa fa-heart';
					}else{
						$res->favoritesstatus = 'out-favorites';
						//$res->favoriteshtml = 'fa fa-heart-o';
					}
							
				}
			if(!empty($res->conversion_rate)){
							$cartvalue =$cartcredit/$res->conversion_rate;	
						}else{
							$cartvalue =$cartcredit;
						}
				
			}
			$later_response =  DB::table('tbl_wishlist')->select('tbl_plan.*','tbl_buypackagestock.*','tbl_buypackage.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
			->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
			->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
			->leftjoin('tbl_buypackage','tbl_buypackage.package_userid','tbl_wishlist.userid')
			
				->leftjoin("tbl_buypackagestock",function($join){
						$join->on("tbl_buypackagestock.buypackage_id","=","tbl_buypackage.package_id")
						->on("tbl_buypackagestock.stocktype_id","=","tbl_Video.stock_category")
						->on("tbl_buypackagestock.contentcat_id","=","tbl_Video.content_category");
					})
			
			->leftjoin('tbl_plan','tbl_plan.plan_id','tbl_buypackagestock.plan_id')
			->where('tbl_wishlist.userid',$userid)
			->where('tbl_wishlist.status','later')
			->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)
			->where('tbl_buypackage.status','A')
			->whereDate('tbl_buypackage.package_expiredate','>',date('Y-m-d'))
			->orderBy('tbl_wishlist.created_date','DESC')
			 ->groupBy('tbl_Video.IntId')
			->get();
			 }else{
				$cartcredit=0;
				$cartvalue=0;
				$response =  DB::table('tbl_wishlist')->select('tblstock.*','tbl_plan.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
				->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
				->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
					->leftjoin("tblstock",function($join){
						 $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
						 ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
					})
				->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
				->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.status','cart')->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->orderBy('tbl_wishlist.created_date','DESC')->groupBy('tbl_Video.IntId')
				->get();
				//->toSql();
				//print_r($response);
				//exit;
				$later_response =  DB::table('tbl_wishlist')
				->select('tblstock.*','tbl_plan.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
				->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
				->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
				->leftjoin("tblstock",function($join){
						 $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
						 ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
					})
				->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
				->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.status','later')->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->orderBy('tbl_wishlist.created_date','DESC')->groupBy('tbl_Video.IntId')->get();
				if(!$response->isEmpty()){
				$totalitems=count($response);
						foreach($response as $res){
							$cartcredit +=$res->stock;
							$infavoriteslist =  DB::table('tbl_favorites')->where('tbl_favorites.fav_videoid',$res->IntId)->where('tbl_favorites.fav_userid',$userid)->where('tbl_favorites.fav_siteid',$managesite->intmanagesiteid)->first();
		
					if (!empty($infavoriteslist)) { 
						$res->favoritesstatus = 'in-favorites';
						//$res->favoriteshtml = 'fa fa-heart';
					}else{
						$res->favoritesstatus = 'out-favorites';
						//$res->favoriteshtml = 'fa fa-heart-o';
					}
						}
						if(!empty($res->conversion_rate)){
							$cartvalue =$cartcredit/$res->conversion_rate;		
						}else{
							$cartvalue =$cartcredit;
						}
				}
				  }
			
			}else{
				$cartcredit=0;
				$cartvalue=0;
				$userid=Session::getId();
				$response =  DB::table('tbl_wishlist')->select('tblstock.*','tbl_plan.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
				->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
				->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
					->leftjoin("tblstock",function($join){
						 $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
						 ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
					})
				->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
				->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.status','cart')->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->orderBy('tbl_wishlist.created_date','DESC')->groupBy('tbl_Video.IntId')
		
				->get();
				
				$later_response =  DB::table('tbl_wishlist')->select('tblstock.*','tbl_plan.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
				->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
				->leftjoin("tblstock",function($join){
						 $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
						 ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
					})
				->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
				->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.status','later')->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->orderBy('tbl_wishlist.created_date','DESC')->groupBy('tbl_Video.IntId')->get();
				// print_r($response);
				// exit;
				if(!$response->isEmpty()){
				$totalitems=count($response);
						foreach($response as $res){
							$cartcredit +=$res->stock;
							
					$infavoriteslist =  DB::table('tbl_favorites')->where('tbl_favorites.fav_videoid',$res->IntId)->where('tbl_favorites.fav_userid',$userid)->where('tbl_favorites.fav_siteid',$managesite->intmanagesiteid)->first();
		
					if (!empty($infavoriteslist)) { 
						$res->favoritesstatus = 'in-favorites';
						//$res->favoriteshtml = 'fa fa-heart';
					}else{
						$res->favoritesstatus = 'out-favorites';
						//$res->favoriteshtml = 'fa fa-heart-o';
					}
						}
						
				if(!empty($res->conversion_rate)){
							$cartvalue =$cartcredit/$res->conversion_rate;		
						}else{
							$cartvalue =$cartcredit;
						}
				
				}
		}
		
	$expensive_plan = DB::table('tbl_plan')->select('*')->where('plan_siteid',$managesite->intmanagesiteid)->where('plan_type','M')->orderBy('plan_price', 'desc')->first();
	$yearly_discount=$expensive_plan->plan_price * $expensive_plan->yearly_discount / 100;
	$calculatedDis=$expensive_plan->plan_price - $yearly_discount;
	$pricepercredit=$expensive_plan->plan_download / $calculatedDis;
	$cartcreditvalue=$cartcredit / $pricepercredit;
	$saveuptoamount=abs($cartvalue -  $cartcreditvalue);
//$saveuptoamount=abs(0);
	$backgroundslist =  DB::table('tbl_backgrounds')->where('tbl_backgrounds.siteid',$managesite->intmanagesiteid)->get();
		return view('/cart',compact('response','siteid','packageid','later_response','stockinfo','totalitems','cartvalue','checkloginuser','saveuptoamount','managesite','availablecount','backgroundslist'));	
		  
	}
	public function savetolater(Request $request){
		if(!empty(Session::get('userid'))){
			$userid=Session::get('userid');
			}else{
			$userid=Session::getId();
		}
		
		$dataarr = array(
				"status"=> $request->status,
				
			);
			DB::table('tbl_wishlist')->where('id', $request->id)->update($dataarr);
		$value = array('response'=>1); 
		echo json_encode($value); 
		
		
	}
	  public function refreshCaptcha()
    {
		return response()->json(['captcha'=> captcha_img()]);
		
    }
	
	public function autorenew(){
		
		
	}
	public function downloadcart(Request $request){
		$arr=explode(',',$request->check_id);
		$cartcount='';
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		if(!empty($arr)){
			$response  = DB::table('tbl_wishlist')->whereIn('id', $arr)->orderBy('id', 'DESC')->get();
		}else{
		$response  = DB::table('tbl_wishlist')->where('siteid',$managesite->intmanagesiteid)->where('status','cart')->where('userid',Session::get('userid'))->orderBy('id', 'DESC')->get();
		}
		//print_r($response);
		$crtcount=count($response);
		$arrayresponse = [];
		$y = 1;
		$x = 0;
		$n = 0;
		//echo $request->type;
		//exit;
		if($request->type=='direct'){
			$totalCountImage=0;
			$cartcount=0;
			foreach($response as $res){
				
				$getdownloadres = DB::table('tbl_download')->where('user_id',Session::get('userid'))->where('video_id',$res->videoid)->where('site_id',$managesite->intmanagesiteid)->first();
			
					//if($x < $availablecount){
						
				$videoinfo = DB::table('tbl_Video')->select('content_category','stock_category','VchFolderPath','VchVideoName')->where('IntId',$res->videoid)->first();
				$cartcount= $crtcount - $y;
				$arrayresponse[] = array('cartid'=>$res->id,'downloadid'=>Crypt::encryptString($res->videoid));
					
				
					
					//$x++;
			
				
				$y++;
			}
		}else{
		
		$packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();
		$packageDownloadcount = 0;
		$availablecount = 0;
		$total_credit = 0;
		$used_credit = 0;
		$packageid ="";
		$buyid ="";
			if(!$packageavailable->isEmpty()){	
				foreach($packageavailable as $pack){
					$total_credit += $pack->package_count;
					$used_credit += $pack->package_download;
					if($pack->package_download < $pack->package_count){
						if(empty($packageid)){
							$packageid = $pack->package_id;
							$buyid = $pack->buy_id;
							$packageDownloadcount = $pack->package_download;
							
						}
					}
				}
				
				$availablecount = $total_credit - $used_credit;
			}
		
	
		
		$totalCountImage = $availablecount;
		if($availablecount > 0){
			foreach($response as $res){
				
				// $getdownloadres = DB::table('tbl_download')->where('user_id',Session::get('userid'))->where('video_id',$res->videoid)->where('site_id',$managesite->intmanagesiteid)->first();
				// if(!empty($getdownloadres)){
					// $cartcount= $crtcount - $y;
					// $arrayresponse[] = array('cartid'=>$res->id,'downloadid'=>Crypt::encryptString($res->videoid));
				// }else{
					//if($x < $availablecount){
						
					$videoinfo = DB::table('tbl_Video')->select('content_category','stock_category','VchFolderPath','VchVideoName')->where('IntId',$res->videoid)->first();
				if($totalCountImage > 0){	
					if($videoinfo->content_category == 0){
							$cartcount= $crtcount - $y;
								$getdownloadresponse = DB::table('tbl_download')->where('user_id',Session::get('userid'))->where('video_id',$res->videoid)->where('site_id',$managesite->intmanagesiteid)->first();
								if(empty($getdownloadresponse)){
									$n++;
								}
						$totalCountImage  = $totalCountImage - 1;
								$arrayresponse[] = array('cartid'=>$res->id,'downloadid'=>Crypt::encryptString($res->videoid));
					}else{
						$stockinfo = DB::table('tbl_buypackagestock')->where('buypackage_id',$packageid)->where('plan_id',$buyid)->where('stocktype_id',$videoinfo->stock_category)->where('contentcat_id',$videoinfo->content_category)->first();
						if(!empty($stockinfo)){
								if($stockinfo->stock <=  $totalCountImage){
									
									$totalCountImage = $totalCountImage - $stockinfo->stock;
									$cartcount= $crtcount - $y;
									$getdownloadresponse = DB::table('tbl_download')->where('user_id',Session::get('userid'))->where('video_id',$res->videoid)->where('site_id',$managesite->intmanagesiteid)->first();
									if(empty($getdownloadresponse)){
										$n++;
									}
									
									$arrayresponse[] = array('cartid'=>$res->id,'downloadid'=>Crypt::encryptString($res->videoid));
								}
						}
					}
				}
					
					//$x++;
				//}
				
				$y++;
			}
			//$this->downloadZip();
		}
		$availablecount = $availablecount - $n;
		}
		
		
		$cart= $cartcount;
		$carticon= '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 3c0 .55.45 1 1 1h1l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h11c.55 0 1-.45 1-1s-.45-1-1-1H7l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.67-1.43c-.16-.35-.52-.57-.9-.57H2c-.55 0-1 .45-1 1zm16 15c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path></svg>';
		echo json_encode(array("response"=>$arrayresponse,"available"=>$totalCountImage,'cartcount'=>$cart,'carticon'=>$carticon));
	}
	public function resend_email(Request $request){
		echo  $this->checklogin();
		if(!empty(Session::get('userid'))){
			$userid=Session::get('userid');
			$userdetail = DB::table('tbluser')->where('intuserid',$userid)->first();
			$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
			
			$data['vchsitename'] = $managesite->vchsitename;
			$data['siteurl'] =  "https://".$managesite->txtsiteurl;
			$data['vlogo'] =  "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo;
			$data['vchfirst_name'] = $userdetail->vchfirst_name;
			$data['userid'] = Crypt::encryptString($userdetail->intuserid);
			
			$data2 = array(
				'email'	=> $userdetail->vchemail,
				'emailfrom'	=> $managesite->vchemailfrom,
			);
			
			Mail::send('email.emailverify',['data'=>$data], function ($message) use ($data2) {
				$message->from($data2['emailfrom'],'noreply');
				$message->to($data2['email']);
                $message->subject('Verify Email Account');
            });
		}
	}
	public function pack_unsubscribe(Request $request){
		echo  $this->checklogin();
		$packid=$request->packid;
		$buypack = DB::table('tbl_buypackage')->leftjoin('tbluser','tbl_buypackage.package_userid','tbluser.intuserid')->where('package_id',$packid)->first();
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		$dataarr = array(
				"package_subscription"=> 'C',
				"status"=> 'D',
				
			);
			
		Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
		$subscription = \Stripe\Subscription::retrieve($buypack->package_renewid);
		$subscription->cancel();
		$response = $subscription->jsonSerialize();
		DB::table('tbl_buypackage')->where('package_id', $packid)->update($dataarr);
		$value = array('response'=>1,'packid'=>$packid); 
		echo json_encode($value);
			
		$data2 = array(
				'email'	=> $buypack->vchemail,
				'emailfrom'	=> $managesite->vchemailfrom,
			);
			
			$data['vchsitename'] = $managesite->vchsitename;
			$data['vlogo'] =  "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo;
			$data['vchfirst_name'] = $buypack->vchfirst_name;
			$data['productname'] = $buypack->package_name;
		
			  Mail::send('email.unsubscribe',['data'=>$data], function ($message) use ($data2) {
				$message->from($data2['emailfrom'],'noreply');
				$message->to($data2['email']);
                $message->subject('Cancel Subscription');
                });
		
	}
	public function verifyaccount($id){
		$id = Crypt::decryptString($id);
		//echo $id;
		$data = [
			"verifystatus"=>'1'
		];
		DB::table('tbluser')->where('intuserid', $id)->update($data);
		return view('/verifyaccount');	
	}
	public function checkstock(Request $request){
		$content = $request->content;
		$stock = $request->stock;
		$id = Crypt::decryptString($request->productid);
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		
		
		
		if(!empty(Session::get('userid'))){
			$packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();
			if(!$packageavailable->isEmpty()){	
					$packageid ="";
					$buyid ="";
					$availablecount = 0;
					$total_credits = 0;
					$used_credits = 0;
					foreach($packageavailable as $pack){
						$total_credits +=$pack->package_count;
						$used_credits +=$pack->package_download;
						if($pack->package_download < $pack->package_count){
							if(empty($packageid)){
								$packageid = $pack->package_id;
								$buyid = $pack->buy_id;
								
							}
						}
						
								$availablecount = $total_credits - $used_credits;
								if($availablecount == 0){
									$packageid = "";
								}
					}
				if(!empty($packageid)){	
					 $stockinfo = DB::table('tbl_buypackagestock')->where('buypackage_id',$packageid)->where('plan_id',$buyid)->where('stocktype_id',$stock)->where('contentcat_id',$content)->first();
					
					if($availablecount > 0){	
						$getdownloadres = DB::table('tbl_download')->where('user_id',Session::get('userid'))->where('video_id',$id)->where('site_id',$managesite->intmanagesiteid)->first();
						if(!empty($getdownloadres)){
							echo json_encode(array("response"=>'Done',"stock"=>((!empty($stockinfo->stock))?$stockinfo->stock:0),'instock'=>'alreadydownload',"available_stock"=>$availablecount));
						
						}else{
							echo json_encode(array("response"=>'Done',"stock"=>((!empty($stockinfo->stock))?$stockinfo->stock:0),'instock'=>'yes',"available_stock"=>$availablecount));
				
						}
					}else{
						echo json_encode(array("response"=>'Done',"stock"=>0,'instock'=>'no',"available_stock"=>$availablecount));
						
					}
				}else{
					$getdownloadres = DB::table('tbl_download')->where('user_id',Session::get('userid'))->where('video_id',$id)->where('site_id',$managesite->intmanagesiteid)->first();
						if(!empty($getdownloadres)){
							echo json_encode(array("response"=>'Done',"stock"=>0,'instock'=>'alreadydownload',"available_stock"=>0));
						}else{
							echo json_encode(array("response"=>'Done',"stock"=>0,'instock'=>'no',"available_stock"=>0));
						}
							
					
				}	
					
			}else{
				
				echo json_encode(array("response"=>'Done',"stock"=>0,'instock'=>'no',"available_stock"=>0));
				
			}
			exit;
		}else{
			echo json_encode(array("response"=>'No'));
			exit;
		}
	}
	public function imageAnimation($seo=""){
		
		$explode = explode("-",$seo);
		$seo = end($explode);
		
		$package= "";
		$userdetail= "";
		
		if(!empty(Session::get('userid'))){
			$userid = Session::get('userid');
			$userdetail = DB::table('tbluser')->where('intuserid',$userid)->first();	
		}else{
			$userid=Session::getId();
	
		}

		
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		$response = DB::table('tbl_Video')->select("tbl_Video.*",DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))->where("IntId",$seo)->first();
		
		$productid = Crypt::encryptString($response->IntId);
		
			$gender='';
			$skintone='';
			$category='';
			$id = $response->IntId;
			
			$video_detail = DB::table('tbl_Video')->leftjoin('tbl_Videotagrelations','tbl_Videotagrelations.VchVideoId','tbl_Video.IntId')->where('tbl_Video.IntId',$id)->first();
			// print_r($video_detail);
			// exit;
			$gender=DB::table('tbl_Tagtype')->where('Intid',$video_detail->VchGenderTagid)->first();
			$skintone=DB::table('tbl_Tagtype')->where('Intid',$video_detail->VchRaceTagID)->first();
			$category=DB::table('tbl_Tagtype')->where('Intid',$video_detail->VchCategoryTagID)->first();
			if(!empty($response)){
			$tranparent=$response->transparent;
				
			}else{
				$tranparent='';
			}
			if(!empty($gender)){
				$gender=$gender->vchTitle;
			}else{
				$gender='';
			}
			
			if(!empty($skintone)){
				$skintone=$skintone->vchTitle;
			}else{
				$skintone='';
			}
			
			if(!empty($category)){
				$category=$category->vchTitle;
			}else{
				$category='';
			}
			
			if($response->EnumType=='I'){
						$data_type="Image";
					}else{
						$data_type="Video";
					}
			$size = getimagesize(public_path().'/'.$response->VchFolderPath.'/'.$response->VchVideoName);
			$diemension='';
			if(!empty($size)){
			$diemension=$size[0].'x'.$size[1];
			}
			// print_r($skintone);
			// print_r($category);
			$incartlist =  DB::table('tbl_wishlist')->where('tbl_wishlist.videoid',$id)->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->first();
			
				if (!empty($incartlist)) { 
					$cartstatus = 'out-cart';
					$imgname = $incartlist->img_name;
					if(!empty($incartlist->applied_bg)){
					$applied_bg = $incartlist->applied_bg;
					}else{
							$applied_bg = '';
					}
			}else{ 
				$cartstatus = 'in-cart';
				$imgname = '';
				$applied_bg = '';
		}
		
		$infavoriteslist =  DB::table('tbl_favorites')->where('tbl_favorites.fav_videoid',$id)->where('tbl_favorites.fav_userid',$userid)->where('tbl_favorites.fav_siteid',$managesite->intmanagesiteid)->first();
		
		if (!empty($infavoriteslist)) { 
			$favoritesstatus = 'in-favorites';
			
		}else{
			$favoritesstatus = 'out-favorites';
			
		}
			
		return view('/image',compact('response','managesite','productid','gender','tranparent','skintone','category','diemension','data_type','id','cartstatus','imgname','applied_bg','favoritesstatus','userid','userdetail'));
	}
	public function stringReplace($string){
		
		$response = DB::table('tbl_Video')->get();
		foreach($response as $res){
			
			$data = [
				"seo_url" => $this->stringReplace($res->VchTitle)."-".$res->IntId
			];
			DB::table('tbl_Video')->where('IntId', $res->IntId)->update($data);
		}
		
		$oldstring = ["  ","(", ")", "?"," "];
		$newstring   = ["","", "", "","-"];
		return str_replace($oldstring, $newstring, $string);
	}
	
		public function favoritesData(Request $request){
		//echo  $this->checklogin();
		if(!empty(Session::get('userid'))){
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		$videoid = $request->id;
		$id = Crypt::decryptString($request->id);
		$userid=Session::get('userid');
		$date = date('Y-m-d H:i:s');
		$cartstatus = $request->cartstatus;
		
		if($cartstatus == 'Add'){
			$checklist=DB::table('tbl_favorites')->where('fav_userid',$userid)->where('fav_siteid',$managesite->intmanagesiteid)->where('fav_videoid',$id)->first();
			if(empty($checklist)){
			$data = array(
				'fav_videoid'	=> $id,
				'fav_siteid'	=> $managesite->intmanagesiteid,
				'fav_userid'=> $userid,
				'fav_created_date'	=> $date,
			);
			$lastinsetid=$this->HomeModel->favoritesdata($data);
			}
		}elseif($cartstatus == 'Remove'){
			$this->HomeModel->DeleteFromfavorites($id,$managesite->intmanagesiteid,$userid);
		}
			
				
			$value = array('response'=>1); 
		}else{
			$value = array('response'=>2); 
		}
		
			echo json_encode($value); 
		
		
		
	}
	public function favorites(){
		echo  $this->checklogin();
		$userid = Session::get('userid');
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		$response =  DB::table('tbl_favorites')->leftjoin('tbl_Video','tbl_favorites.fav_videoid','tbl_Video.IntId',)->where('tbl_favorites.fav_userid',$userid)->where('tbl_favorites.fav_siteid',$managesite->intmanagesiteid)->orderBy('fav_created_date','DESC')->paginate(10);
		$siteid=$managesite->intmanagesiteid;
		
		return view('/user-favorites',compact('response','siteid'));
		
		
	}
	public function deletefavorites(Request $request){
		echo  $this->checklogin();
		$results = DB::table('tbl_favorites')->where('fav_id', $request->id)->delete();
		$value = array('response'=>1); 
		echo json_encode($value); 
		}
		
		public function change_background(Request $request){ 
			$managesite = DB::table('tbl_managesite')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
			$session_id = Session::getId();
			$path = public_path().'/'.$session_id;
				//File::isDirectory($path) or 
				if (!Is_Dir($path)){
					mkdir($path, 0777);
			}
		//Storage::put('flower.jpg', file_get_contents('https://dev.fox-ae.com/showimage/3701/1/org1588022082.png'));
		$src=$request->src;
		$srcarr=explode('?',$request->src);
		$srcarr1=$srcarr[0];
		$srcarr2=explode('/',$srcarr1);
		$img="upload/videosearch/".$srcarr2[2]."/".$srcarr2[4];
		$w=0;
		$h=0;
		$thumb=strtolower(preg_replace('/\W/is', "_", "$img $w $h"));  
		$opts = array(
		  'http'=>array(
			'method'=>"GET",
			'header'=>"Accept-language: en\r\n" .
					  "Cookie: foo=bar\r\n"
		  )
		);

$context = stream_context_create($opts);
$fileurl=public_path().'/image_cache/'.$managesite->intmanagesiteid.'/'.$thumb;
$file = file_get_contents($fileurl, false, $context);
file_put_contents($path.'/flower.jpg', $file);
		if (file_exists($path.'/flower.jpg'))
		{
			//unlink(public_path().'/imagick/colorImage.png');
			if (file_exists($path.'/newImage.png')){
			unlink($path.'/newImage.png');
		}
			header('Content-Type: image/png');
			$colors= array("0","0","0");
			$cutter=imagecreatefromjpeg($path.'/flower.jpg');
			$remove = imagecolorallocate($cutter, $colors[0], $colors[1], $colors[2]);
			imagecolortransparent($cutter, $remove);
			imagepng($cutter, $path.'/colorImage.png');
			
			

			$color = "#".$request->color;
			$imgs22 = $path.'/newImage.png';
			$cmd = '-fuzz 42% -fill none -draw "matte 0,0 floodfill" -background "'.$color.'" -flatten'; 
			
			exec("convert ".$path."/colorImage.png $cmd $imgs22 ");
		}
			//echo ".public_path()."/imagick/colorImage.png";
			//exit;
			
			
			return $session_id.'/newImage.png?v='.time();
			//echo $session_id;
			
			
			
		}
		
		 public function downloadZip(){
			$path=public_path().'/zip/'.Session::get('userid');
			$zip = new ZipArchive;
			
			
		$fileName = 'zip/cartArchive'.Session::get('userid').'.zip';
		 
		   //$zip->open(public_path().'/'.$fileName, ZipArchive::CREATE);
			if ($zip->open(public_path().'/'.$fileName, ZipArchive::CREATE) === TRUE)
				{
					$files = File::files($path);
		   
					foreach ($files as $key => $value) {
						$relativeNameInZipFile = basename($value);
						$zip->addFile($value, $relativeNameInZipFile);
					}
					 
					$zip->close();
				}
				
				return response()->download(public_path($fileName));
				File::deleteDirectory($path);
				File::delete(public_path().'/'.$fileName);
		
			}
		
		
		public function datadetail(Request $request){
			$gender='';
			$skintone='';
			$category='';
			$id = Crypt::decryptString($request->productid);
			$managesite = DB::table('tbl_managesite')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
			$video_detail = DB::table('tbl_Video')->leftjoin('tbl_Videotagrelations','tbl_Videotagrelations.VchVideoId','tbl_Video.IntId')->where('tbl_Video.IntId',$id)->first();
			$gender=DB::table('tbl_Tagtype')->where('Intid',$video_detail->VchGenderTagid)->first();
			$skintone=DB::table('tbl_Tagtype')->where('Intid',$video_detail->VchRaceTagID)->first();
			$category=DB::table('tbl_Tagtype')->where('Intid',$video_detail->VchCategoryTagID)->first();
			if(!empty($video_detail)){
			$tranparent=$video_detail->transparent;
				
			}else{
				$tranparent='';
			}
			if(!empty($gender)){
				$gender=$gender->vchTitle;
			}else{
				$gender='';
			}
			
			if(!empty($skintone)){
				$skintone=$skintone->vchTitle;
			}else{
				$skintone='';
			}
			
			if(!empty($category)){
				$category=$category->vchTitle;
			}else{
				$category='';
			}
			
			$size = getimagesize(public_path().'/'.$video_detail->VchFolderPath.'/'.$video_detail->VchVideoName);
			$diemension=$size[0].'x'.$size[1];
			// print_r($skintone);
			// print_r($category);
			
			
			
			echo json_encode(array("gender"=>$gender,"skintone"=>$skintone,'tranparent'=>$tranparent,'category'=>$category,"type"=>$video_detail->EnumType,"size"=>$diemension));
			
			
		}

public function showimage1($id,$imgs){
	$managesite = DB::table('tbl_managesite')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
	$img="change_background/".$id.'/'.''.$imgs;
$w=0;
	$h=0;
	if(!defined('DIR_CACHE'))
		define('DIR_CACHE', './image_cache/');

	if (!Is_Dir(DIR_CACHE))
		mkdir(DIR_CACHE, 0777);

	$addl_path ="";
	if (file_exists($img))
	{
		$thumb=strtolower(preg_replace('/\W/is', "_", "$img $w $h"));  
		$changed=0;
		if (file_exists($img) && file_exists(DIR_CACHE.$thumb))
		{
			$mtime1=filemtime(DIR_CACHE.$thumb);
			$mtime2=filemtime($img);
			if ($mtime2>$mtime1)
				$changed=1;
		}
		elseif (!file_exists(DIR_CACHE.$thumb))
			$changed=1;

		if ($changed)
		{
			$filename=$img;
			$new_width=(int)@$w;
			$new_height=(int)@$h;
			$lst=GetImageSize($filename);
			
			$image_width=$lst[0];
			$image_height=$lst[1];
			$image_format=$lst[2]; //print_R($lst);

			if ($image_format==1)
			{
				$old_image=imagecreatefromgif($filename);
			}
			elseif ($image_format==2)
				$old_image=imagecreatefromjpeg($filename);
			elseif ($image_format==3) {
				$old_image=imagecreatefrompng($filename);
			}
			if (($new_width!=0) && ($new_width<$image_width))
			{
				$image_height=(int)($image_height*($new_width/$image_width));
				$image_width=$new_width;
			}

			if (($new_height!=0) && ($new_height<$image_height))
			{
				$image_width=(int)($image_width*($new_height/$image_height));
				$image_height=$new_height;
				//  $image_width=$new_width;
			}
			$new_image=ImageCreateTrueColor($image_width, $image_height);
			$white = ImageCopyResampled($new_image, $old_image, 0, 0, 0, 0, $image_width, $image_height, imageSX($old_image), imageSY($old_image));
			global $h;
			
			$Watermark = DB::table('tblwatermarklogo')->where('vchtype','L')->where('vchsiteid',$managesite->intmanagesiteid)->where('enumstatus','A')->first();	
			
			$stamp = imagecreatefrompng(public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname);
			$stamp2 = public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname;
			
			$vchtransparency = $Watermark->vchtransparency * 10;

			$image = $new_image;
			$im = $image;
		// Set the margins for the stamp and get the height/width of the stamp image
			$marge_right = 10; 
			$marge_bottom = 10;
			//$stamp = imagecolorallocate($stamp, 255, 255, 0, 75); 
			$sx = imagesx($stamp);
			$sy = imagesy($stamp);
			
			$imageWidth=imagesx($image);
			$imageHeight=imagesy($image);

			$logoWidth=imagesx($stamp);
			$logoHeight=imagesy($stamp);  
			$logoImage = $stamp;
			$image = $image;
			$dst_width=($imageWidth*50)/100;
			$dst_height=($imageHeight*50)/100;
		
			$imgpath=public_path().'/image_cache/'.$thumb;
			$imgfullpath=public_path().'/'.$img;
		
			exec("composite -dissolve ".$vchtransparency."% -gravity center -geometry '".$dst_width."'x'".$dst_height."'+50+50 '".$stamp2."' '".$imgfullpath."' '".$imgpath."'"); 
			// imagecopymerge($image, $logoImage, ($imageWidth-$logoWidth)/2,  ($imageHeight-$logoHeight)/2,0, 0,$logoWidth,$logoHeight,$vchtransparency);
			 
			// imageJpeg($new_image, DIR_CACHE.$thumb);
		}
		header("Content-type:image/jpeg");
		header('Content-Disposition: attachment; filename="'.$img.'"');  
		readfile(DIR_CACHE.$thumb);
}

		
}

	public static function incartcredit(){
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		if(!empty(Session::get('userid'))){
			$userid=Session::get('userid');
				$packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();
				if(!$packageavailable->isEmpty()){	
					$packageid ="";
					$buyid ="";
					$availablecount = 0;
					$total_credits = 0;
					$used_credits = 0;
					foreach($packageavailable as $pack){
						$total_credits +=$pack->package_count;
						$used_credits +=$pack->package_download;
						if($pack->package_download < $pack->package_count){
							if(empty($packageid)){
								$packageid = $pack->package_id;
								$buyid = $pack->buy_id;
								
							}
						}
					}
					
								$availablecount = $total_credits - $used_credits;
								if($availablecount == 0){
									$packageid = "";
								}
				}
				
		if(!empty($packageid)){
			$cartcredit=0;
				$cartvalue=0;
			$response =  DB::table('tbl_wishlist')->select('tbl_plan.*','tbl_buypackagestock.*','tbl_buypackage.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
			->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
			->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
			->leftjoin('tbl_buypackage','tbl_buypackage.package_userid','tbl_wishlist.userid')
			
			->leftjoin("tbl_buypackagestock",function($join){
						$join->on("tbl_buypackagestock.buypackage_id","=","tbl_buypackage.package_id")
						->on("tbl_buypackagestock.stocktype_id","=","tbl_Video.stock_category")
						->on("tbl_buypackagestock.contentcat_id","=","tbl_Video.content_category");
					})
			->leftjoin('tbl_plan','tbl_plan.plan_id','tbl_buypackagestock.plan_id')
			//->where('tbl_buypackagestock.stocktype_id','tbl_Video.stock_category')
			//->where('tbl_buypackagestock.contentcat_id','tbl_Video.content_category')
			->where('tbl_wishlist.userid',$userid)
			->where('tbl_wishlist.status','cart')
			->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)
			->where('tbl_buypackage.status','A')
			->whereDate('tbl_buypackage.package_expiredate','>',date('Y-m-d'))
			->orderBy('tbl_wishlist.created_date','DESC')
			 ->groupBy('tbl_Video.IntId')
			->get();
			//->paginate(10);
			//print_r($response);
		
			if(!$response->isEmpty()){
			$totalitems=count($response);
			foreach($response as $res){
					$cartcredit +=$res->stock;
							
				}
			if(!empty($res->conversion_rate)){
							$cartvalue =$cartcredit/$res->conversion_rate;	
						}else{
							$cartvalue =$cartcredit;
						}
			
			
			}
		
			 }else{
			
				$cartcredit=0;
				$cartvalue=0;
				$response =  DB::table('tbl_wishlist')->select('tblstock.*','tbl_plan.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
				->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
				->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
					->leftjoin("tblstock",function($join){
						 $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
						 ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
					})
				->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
				->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.status','cart')->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->orderBy('tbl_wishlist.created_date','DESC')->groupBy('tbl_Video.IntId')
				->get();
				
				if(!$response->isEmpty()){
				$totalitems=count($response);
			
						foreach($response as $res){
							$cartcredit +=$res->stock;
							
						}
						if(empty($res->conversion_rate)){
							$cartvalue =$cartcredit;	
							
						}else{
							$cartvalue =$cartcredit/$res->conversion_rate;	
							
							
						}
						
				}
				  }
			
		
		}else{
			$cartcredit=0;
				$cartvalue=0;
				$userid=Session::getId();
				$response =  DB::table('tbl_wishlist')->select('tblstock.*','tbl_plan.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
				->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
				->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
					->leftjoin("tblstock",function($join){
						 $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
						 ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
					})
				->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
				->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.status','cart')->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->orderBy('tbl_wishlist.created_date','DESC')->groupBy('tbl_Video.IntId')
		
				->get();
	
				// print_r($response);
				// exit;
				if(!$response->isEmpty()){
				$totalitems=count($response);
				if($totalitems>0){
						foreach($response as $res){
							$cartcredit +=$res->stock;
						}
						
						if(empty($res->conversion_rate)){
							$cartvalue =$cartcredit;	
							
						}else{
							$cartvalue =$cartcredit/$res->conversion_rate;	
							
							
						}
				//$cartvalue =$cartcredit/$res->conversion_rate;
				}else{
					$cartvalue=0;
					
				}
				}
				
			}
			//echo $cartvalue;
		
			return $cartvalue;
		}
	
	

		}


?>