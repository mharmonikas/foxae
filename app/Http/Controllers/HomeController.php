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
use Illuminate\Support\Facades\Hash;
use Mail;
use Response;
use Stripe;
use Validator;
use File;
use ZipArchive;
class HomeController extends Controller {

	public function __construct(HomeModel $HomeModel) {
        $this->HomeModel = $HomeModel;

    }

    /**
     * @return mixed|string
     */
    public static function getServerName()
    {
        return app()->isLocal() ? 'dev.fox-ae.com' : $_SERVER['SERVER_NAME'];
    }

    public function checklogin(){
		if(!Session::get('userid')){
			return redirect('/');
		 }
	}

	public function login(Request $request) {
        $type='';
		$getplan='';
		$price=0;
		$monthly_price=0;
		$plantype='';
		$cartval='';
		$wcount='';
		$onetime_price='';
		$pricing_flow='';
		$country='';
		$availablecredit='';
		$cartvalue='';
		$billing_address='';
		$card_details='';
		$buyid='';
		$current_packageid	='';
		$package_type = '';
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',self::getServerName())->first();
		$siteid=$managesite->intmanagesiteid;
		$email = $request->email;
		$password = md5($request->password);
		$userLogin = $this->HomeModel->loginData($email,$password,$siteid);

		if (data_get($userLogin, 'intuserid')) {
            $userid=Session::getId();
            $guest_wishlist=DB::table('tbl_wishlist')->where('userid',$userid)->where('siteid',$managesite->intmanagesiteid)->where('status','cart')->get();

            if(!$guest_wishlist->isEmpty()){
                $guestexist_wishlist=DB::table('tbl_wishlist')->where('status','cart')->where('userid',$userLogin->intuserid)->where('siteid',$managesite->intmanagesiteid)->get();

                if(!empty($guestexist_wishlist)){
                    foreach($guestexist_wishlist as $existwishlist){
                        DB::table('tbl_wishlist')->where('id', $existwishlist->id)->delete();
                    }
                }

                foreach($guest_wishlist as $wishlist){
                    $useridupdate = ["userid" => $userLogin->intuserid];
                    DB::table('tbl_wishlist')->where('id', $wishlist->id)->update($useridupdate);
                }
            }

			if($userLogin->enumstatus == 'A'){
				$date = date('Y-m-d H:i:s');
				$data=array('lastlogin'=>$date);
				$this->HomeModel->updateuserdetails($userLogin->intuserid,$data);
				Session::put('userid',$userLogin->intuserid);
				$cartcount=DB::table('tbl_wishlist')->where('userid',$userLogin->intuserid)->where('status','cart')->where('siteid',$siteid)->count();
				$packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',$userLogin->intuserid)->whereDate('package_expiredate','>',date('Y-m-d'))->get();
				$package = null;

				foreach($packageavailable as $packageavailables){
					if($packageavailables->package_download < $packageavailables->package_count){
					    $package = $packageavailables->package_count-$packageavailables->package_download;
					}
				}

                $pvalue = $package ? "yes" : "no";

                $package = null;

                if($userid = Session::get('userid')) {
                    $userdetail = DB::table('tbluser')->where('intuserid',$userid)->first();
                    $packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',$userid)->whereDate('package_expiredate','>',date('Y-m-d'))->get();
                    $total_credits = 0;

                    foreach($packageavailable as $packageavailables){
                        if($packageavailables->package_type!='O'){
                            if(empty($current_packageid)){
                                $package_type=$packageavailables->package_type;
                                $current_packageid = $packageavailables->buy_id;
                            }
                        }

                        $total_credits += $packageavailables->package_count;

                        if($packageavailables->package_download < $packageavailables->package_count){
                            $package=$packageavailables;
                            $packageid = $packageavailables->package_id;
                            $buyid = $packageavailables->buy_id;
                        }
                    }
                }

                $availablecredit = 0;

                if ($package) {
                    $availablecredit = $total_credits - $package->package_download;
                }

                $cartvalue=0;
                $cartimages = DB::table('tbl_wishlist')->where('userid',$userid)->where('siteid',$managesite->intmanagesiteid)->get();

                if(!empty($cartimages)) {
                    foreach($cartimages as $cartimage) {
                        $videodetail = DB::table('tbl_Video')->where('IntId',$cartimage->videoid)->first();

                        if(!empty($videodetail)){
                            $stock=$videodetail->stock_category;
                            $content=$videodetail->content_category;
                        } else {
                            $stock='';
                            $content='';
                        }

                        if(!empty($packageid)){
                             $stockinfo = DB::table('tbl_buypackagestock')->where('buypackage_id',$packageid)->where('plan_id',$buyid)->where('stocktype_id',$stock)->where('contentcat_id',$content)->first();

                             if(!empty($stockinfo)){
                                $cartvalue = $stockinfo->stock;
                             }
                        }
                    }
                }

                if(!empty($request->login_flow)) {
                    $userid = Session::get('userid');
                    $type='';
                    $getplan='';
                    $price=0;
                    $monthly_price=0;
                    $plantype='';
                    $cartval='';
                    $wcount='';
                    $onetime_price='';
                    $country='';
                    $pricing_flow=$request->login_flow;
                    $type=$request->package_type;
                    Session::put('packageid',$request->package_id);
                    Session::put('packagetype',$request->package_type);
                    $packageid = Session::get('packageid');
                    $getplan = DB::table('tbl_plan')->where('plan_id',$packageid)->where('plan_status','A')->first();

                    if(empty($packageid) || empty($getplan)){
                        return redirect('/');
                        exit;
                    }

                    if($getplan->plan_type=='O'){
                        $onetime_price=number_format($getplan->plan_price, 2);
                    }

                    if($getplan->plan_type=='M'){
                        $price1= ($getplan->plan_price * 12);
                        $price2= ($getplan->plan_price * 12 * ($getplan->yearly_discount / 100));
                        $price=$price1-$price2;
                        $monthly_price=number_format($getplan->plan_price, 2);
                    }

                    $country = DB::table('tblcountry')->select('name')->get();
                    $billing_address = DB::table('tbl_billinguser')->where('user_id',$userid)->orderBy('billing_id', 'DESC')->first();
                    $card_details = DB::table('tbl_paymentdetails')->where('c_userid',$userid)->orderBy('id', 'DESC')->first();
                }

				$value = array( "id"=> $userLogin->intuserid,'response'=>1,'name'=>$userLogin->vchfirst_name,'count'=>$cartcount,'pack'=>$package,'val'=>$pvalue,'logo'=>$managesite->vchprofileicon,'carticon'=>'<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 3c0 .55.45 1 1 1h1l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h11c.55 0 1-.45 1-1s-.45-1-1-1H7l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.67-1.43c-.16-.35-.52-.57-.9-.57H2c-.55 0-1 .45-1 1zm16 15c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path></svg>','verifystatus'=>$userLogin->verifystatus,'availablecredit'=>$availablecredit,'cartvalue'=>$cartvalue,'getplan'=>$getplan,'country'=>$country,'type'=>$type,'annually_price'=>number_format($price, 2),'monthly_price'=>$monthly_price,'plantype'=>$plantype,'cartval'=>$cartval,'wcount'=>$wcount,'billing_address'=>$billing_address,'card_details'=>$card_details,'onetime_price'=>$onetime_price,'pricing_flow'=>$pricing_flow,'buyid'=>$buyid,'current_packageid'=>$current_packageid,'package_type'=>$package_type);

				echo json_encode($value);
			} else {
				$value = array('response'=>0);
				echo json_encode($value);
			}
		} else {
			$value = array('response'=>2);
            echo json_encode($value);
		}
	}

	public function submitregistrationdata(Request $request){
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',self::getServerName())->first();

        if ($captcha = data_get($_POST, 'g-recaptcha-response')) {
             $secretKey = $this->getSecretKey($managesite);

             $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);

			 $response = file_get_contents($url);

            $responseKeys = json_decode($response,true);

            if(data_get($responseKeys, 'success')) {
                $checkemail = $this->HomeModel->checkmail($request->email,$managesite->intmanagesiteid);

                if (!empty($checkemail)) {
                    $value = ['response' => 3];
                    echo json_encode($value);
                    exit;
                } else {
                    $date = date('Y-m-d H:i:s');
                    $data = [
                        'vchfirst_name'	=> $request->first_name,
                        'vchemail'		=> $request->email,
                        'vchsiteid'		=> $managesite->intmanagesiteid,
                        'vchpassword'	=> md5($request->password),
                        'lastlogin'		=> $date,
                        'enumstatus'	=> 'A',
                        'created_date'	=> $date,
                        'updated_date'	=> $date,
                    ];
                    $lastinsetid=$this->HomeModel->submitData($data);
                    $data2 = [
                        'vchsitename'	=> $managesite->vchsitename,
                        'email'	=> $request->email,
                        'emailfrom'	=> $managesite->vchemailfrom,
                    ];

                    $data['vchsitename'] = $managesite->vchsitename;
                    $data['siteurl'] =  "https://".$managesite->txtsiteurl;
                    $data['vlogo'] =  "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo;
                    $data['vchfirst_name'] = $request->first_name;
                    $data['userid'] = Crypt::encryptString($lastinsetid);
                    $data['surface']=$managesite->surface;
                    $data['surfacetext_iconcolor']=$managesite->surfacetext_iconcolor;
                    $data['primary_color']=$managesite->primary_color;
                    $data['primarytext_iconcolor']=$managesite->primarytext_iconcolor;
                    $data['hyperlink']=$managesite->hyperlink;
                    $data['bgtext_iconcolor']=$managesite->bgtext_iconcolor;

                    Mail::send('email.emailverify',['data' => $data], function ($message) use ($data2) {
                        $message->from($data2['emailfrom'],$data2['vchsitename']);
                        $message->to($data2['email']);
                        $message->subject('Welcome to '.$data2['vchsitename'].' - Please verify your account');
                    });

                    if(!empty($lastinsetid)){
                        $userid=Session::getId();
                        $guest_wishlist=DB::table('tbl_wishlist')->where('userid',$userid)->where('siteid',$managesite->intmanagesiteid)->get();
                        if(!empty($guest_wishlist)){
                        foreach($guest_wishlist as $wishlist){
                            $useridupdate = ["userid" => $lastinsetid];
                            DB::table('tbl_wishlist')->where('id', $wishlist->id)->update($useridupdate);
                            }
                        }

                        $Userdetail=$this->HomeModel->UserData($lastinsetid);

                        if($Userdetail->enumstatus == 'A'){
                            Session::put('userid',$Userdetail->intuserid);
                            $value = ['id' => $Userdetail->intuserid,'response' => 1];
                            echo json_encode($value);
                        } else {
                            $value = ['response' => 0];
                            echo json_encode($value);
                            exit;
                        }
                    } else {
                        $value = ['response' => 2];
                        echo json_encode($value);
                        exit;
                    }

                }
           } else {
                $value = ['response' => 4];
                echo json_encode($value);
                exit;
            }
         } else {
            $value = ['response' => 4];
            echo json_encode($value);
            exit;
        }
	}

	public function forgot_password(Request $request){
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',self::getServerName())->first();
		$email=$request->email;
		$checkemail=$this->HomeModel->checkmail($email,$managesite->intmanagesiteid);
		if(!empty($checkemail)){
			 $userid = $this->mycrypt($checkemail->intuserid,'e');

			$data = array(
					'userid'=>$userid,
					'vchsite'=>self::getServerName(),
					);
				//$username=$request->email;
				$data2 = array(
						'email'	=> $request->email,
						'emailfrom'	=> $managesite->vchemailfrom,
						'vchsitename'	=> $managesite->vchsitename,
				);


				$data['vchsitename'] = $managesite->vchsitename;
				$data['vchfirst_name'] = $checkemail->vchfirst_name;
				$data['siteurl'] = "https://".$managesite->txtsiteurl;
				$data['vlogo'] =  "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo;
				$data['surface']=$managesite->surface;
				$data['surfacetext_iconcolor']=$managesite->surfacetext_iconcolor;
				$data['primary_color']=$managesite->primary_color;
				$data['primarytext_iconcolor']=$managesite->primarytext_iconcolor;
				$data['hyperlink']=$managesite->hyperlink;
				$data['bgtext_iconcolor']=$managesite->bgtext_iconcolor;

				Mail::send('email.forgotpass',['data'=>$data], function ($message) use ($data2) {
				$message->from($data2['emailfrom'],$data2['vchsitename']);
				$message->to($data2['email']);
                $message->subject('Password Reset Request');
                });
			$id = $checkemail->intuserid;
			$date = date('Y-m-d H:i:s');
			$dataarr = array(
				"forgotstatus"=> '1',
				'updated_date'	=> $date
			);
			$this->HomeModel->updateuserdetails($id,$dataarr);
			$value = array('response'=>1);
				echo json_encode($value);
		}else{
			$value = array('response'=>0);
				echo json_encode($value);
		}

	}

	public function resetpassword($id=''){
		$userid = $this->mycrypt($id,'d');
		$userdata = $this->HomeModel->UserData($userid);
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
		$tblthemesetting = DB::table('tbl_themesetting')->select('*')->where('Intsiteid',$managesite->intmanagesiteid)->first();
		if($userdata->forgotstatus==1){
			return view('reset-password',compact('managesite','tblthemesetting','id'));
		}else{
			return redirect('/');

		}
	}

	public function check_oldpassword(Request $request){
		  $pass=$request->oldpassword;
		  $password=md5($pass);
		  $userid=Session::get('userid');
		$check = DB::table('tbluser')->where('vchpassword',$password)->where('intuserid',$userid)->first();
		if(empty($check)){
			echo "false";
			exit;

		}else{
			echo "true";
			exit;
		}



	}

	public function aboutus(){
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
		$manageabout = DB::table('tbl_legaldocuments')->where('siteid',$managesite->intmanagesiteid)->first();

		return view('aboutus',compact('manageabout'));
	}

	public function termscondition(){
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
		$manageabout = DB::table('tbl_legaldocuments')->where('siteid',$managesite->intmanagesiteid)->first();

		return view('termscondition',compact('manageabout'));
	}

	public function privacypolicy(){
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
		$manageabout = DB::table('tbl_legaldocuments')->where('siteid',$managesite->intmanagesiteid)->first();

		return view('privacypolicy',compact('manageabout'));
	}

	public function userlicence(){
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
		$manageabout = DB::table('tbl_legaldocuments')->where('siteid',$managesite->intmanagesiteid)->first();

		return view('userlicence',compact('manageabout'));
	}

	public function support(){
		$userid='';
		$siteid='';
		$user='';
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
		$tblthemesetting = DB::table('tbl_themesetting')->select('*')->where('Intsiteid',$managesite->intmanagesiteid)->first();
		$faqs = DB::table('tblfaq')->select('*')->where('siteid',$managesite->intmanagesiteid)->get();
		if(!empty(Session::get('userid'))){
			$userid=Session::get('userid');
			$user = DB::table('tbluser')->select('*')->where('intuserid',$userid)->first();
		}

		$siteid=$managesite->intmanagesiteid;
		return view('support',compact('faqs','userid','siteid','user','managesite'));
	}

	public function contactus(Request $request){
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',self::getServerName())->first();
			if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
			 if($managesite->intmanagesiteid=='1'){
			  $secretKey = "6LflkxcaAAAAAMlSzq_xPbwMzy7zwypu602wScoi";
			 }elseif($managesite->intmanagesiteid=='17'){
				 $secretKey = "6Lc5WyUaAAAAAJNPbMjbAL1ehCbYb2oUTu0oL0RT";
			 }elseif($managesite->intmanagesiteid=='22'){
				 $secretKey = "6Le8WyUaAAAAAFlwhC61NiT21QKo8s-QbANDk4Jg";

			 }
			  $captcha=$_POST['g-recaptcha-response'];
				$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
			$response = file_get_contents($url);
        $responseKeys = json_decode($response,true);
		 if($responseKeys["success"]) {

		$contact = DB::table('tblcontact')->orderBy('id', 'desc')->first();
		$start_num=$contact->id + 1;
		$num_str = sprintf("%06d", 000000 + $start_num);
		$date = date('Y-m-d H:i:s');
		$dataarr = array(
				"userid"=> $request->userid,
				"siteid"=>$request->siteid,
				"query"=>$request->contactquery,
				"ticket_number"=>$num_str,
				'create_date'	=> $date
			);

		DB::table('tblcontact')->insert($dataarr);

			$data2['email']=$request->useremail;
			$data2['emailfrom']=$managesite->vchemailfrom;
			$data2['vchsitename']=$managesite->vchsitename;
			$data2['ticket_number']=$num_str;


			$data['vchsitename'] = $managesite->vchsitename;
			$data['siteurl'] =  "https://".$managesite->txtsiteurl;
			$data['vlogo'] =  "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo;
			$data['vchfirst_name'] = $request->username;
			$data['query']=$request->contactquery;
			$data['ticket_number']=$num_str;
			$data['surface']=$managesite->surface;
			$data['surfacetext_iconcolor']=$managesite->surfacetext_iconcolor;
			$data['primary_color']=$managesite->primary_color;
			$data['primarytext_iconcolor']=$managesite->primarytext_iconcolor;
			$data['hyperlink']=$managesite->hyperlink;
			$data['bgtext_iconcolor']=$managesite->bgtext_iconcolor;

			Mail::send('email.response_email',['data'=>$data], function ($message) use ($data2) {
				$message->from($data2['emailfrom'],$data2['vchsitename']);
				$message->to($data2['email']);
                $message->subject('Ticket Number: '.$data2['ticket_number']);
            });
			Session::flash('msg', 'Invalid Captcha');
		return redirect('/support');

		 }else{

			Session::flash('errormsg', 'Invalid Captcha');
				 return redirect('/support');

		 }
				 }else{

					 Session::flash('errormsg', 'Invalid Captcha');
				 return redirect('/support');

				 }
	}

	public function submitResetPassword(Request $request){
		$date = date('Y-m-d H:i:s');
		$userid = $this->mycrypt($request->userid,'d');
		$dataarr = array(
				"forgotstatus"=> '0',
				"vchpassword"=>md5($request->password),
				'updated_date'	=> $date
			);
			$this->HomeModel->updateuserdetails($userid,$dataarr);
			Session::flash('changepassword', 'password');
			return redirect('/');


	}

	public function submitnewpassword(Request $request){

		$date = date('Y-m-d H:i:s');
		$userid = $this->mycrypt($request->userid,'d');
		$dataarr = array(
				"forgotstatus"=> '0',
				"vchpassword"=>md5($request->password),
				'updated_date'	=> $date
			);
			$this->HomeModel->updateuserdetails($userid,$dataarr);
			Session::flash('msg1', 'You have successfully changed your password');
			return redirect('/change-password');


	}

	public function custom(){
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
		$tblthemesetting = DB::table('tbl_themesetting')->select('*')->where('Intsiteid',$managesite->intmanagesiteid)->first();

			return view('custom',compact('managesite','tblthemesetting'));

	}

	public function submitcustom(Request $request){
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',self::getServerName())->first();

			 if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
			 if($managesite->intmanagesiteid=='1'){
			  $secretKey = "6LflkxcaAAAAAMlSzq_xPbwMzy7zwypu602wScoi";
			 }elseif($managesite->intmanagesiteid=='17'){
				 $secretKey = "6Lc5WyUaAAAAAJNPbMjbAL1ehCbYb2oUTu0oL0RT";
			 }elseif($managesite->intmanagesiteid=='22'){
				 $secretKey = "6Le8WyUaAAAAAFlwhC61NiT21QKo8s-QbANDk4Jg";

			 }
			  $captcha=$_POST['g-recaptcha-response'];
				$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
			$response = file_get_contents($url);
        $responseKeys = json_decode($response,true);
		 if($responseKeys["success"]) {
		$date = date('Y-m-d H:i:s');
			$data = array(
				'email'	=> $request->email,
				'phone'	=> $request->phone,
				'description'=> $request->description,
				'vchsiteid'=> $request->vchsiteid,
				'created_date'	=> $date,


			);
			$lastinsetid=$this->HomeModel->submitcustom($data);
			//$username=$request->email;
			$data2 = array(
				'email'	=> $request->vchfrom,
				'emailfrom'	=> $request->vchfrom,
			);

				$data['vchsitename'] = $managesite->vchsitename;
				$data['vlogo'] =  "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo;
			  Mail::send('email.contact',['data'=>$data], function ($message) use ($data2) {

				$message->from($data2['email'],'noreply');
				$message->to($data2['emailfrom']);
                $message->subject('New Quote Request');
                });
				Session::flash('msg', 'Thank you! You Have Successfully send quote request.');
			  return redirect('/custom');
		 }else{

			 Session::flash('errormsg', 'Invalid Captcha');
			 return redirect('/custom');
		 }
			 }else{

				 Session::flash('errormsg', 'Invalid Captcha');
				 return redirect('/custom');
			 }
	}

    public static function mycrypt($string,$action){
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

	public function pricing(){
		$msg = request()->get('page');
		$monthly_response = '';
		$onetime_response = '';
		$package_type='';
		$buyid = "";
		$current_packageid='';
		$package_subscription='';
		$package='';
		$package_status='';
        $yarly_dis = '';
        $coupon = data_get(Session::get('pricing-coupon'), 'coupon');
        $websiteWideCoupons = $this->getPricingWebsiteWideCoupons();
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();

		if(!empty(Session::get('userid'))){
            $packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid', Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();

            if(!$packageavailable->isEmpty()){
                $packageid ="";
                $current_packageid ="";
                $buyid ="";
                $availablecount = 0;
                $total_credits = 0;
                $package_type='';
                $package='';
                $package_subscription='';
                foreach($packageavailable as $pack){
                    if($pack->package_type!='O' && $pack->package_subscription!='C' && $pack->status=='A' && empty($current_packageid)){
                        $package_subscription = $pack->package_subscription;
                        $package_status = $pack->status;
                        $package_type=$pack->package_type;
                        $current_packageid = $pack->buy_id;
                    }

                    $total_credits += $pack->package_count;

                    if($pack->package_download < $pack->package_count && empty($packageid)){
                        $package=$pack;
                        $packageid = $pack->package_id;
                        $buyid = $pack->buy_id;
                    }

                    $availablecount = $total_credits - $pack->package_download;

                    if($availablecount == 0){
                        $packageid = "";
                    }
                }
            }
        } else {
            $monthly_response = DB::table('tbl_plan')->where('plan_type','M')->where('plan_status','A')->where('plan_siteid',$managesite->intmanagesiteid)->orderBy('plan_id', 'DESC')->get();

            foreach($monthly_response as $res){
                $yarly_dis = $res->yearly_discount;
            }
        }

		if(!empty($packageid)){
			$onetime_response = DB::table('tbl_plan')->where('plan_type','O')->where('plan_status','A')->where('plan_siteid',$managesite->intmanagesiteid)->orderBy('plan_id', 'DESC')->get();
        }

        $monthly_response = DB::table('tbl_plan')->where('plan_type','M')->where('plan_status','A')->where('plan_siteid',$managesite->intmanagesiteid)->orderBy('plan_id', 'DESC')->get();

        foreach($monthly_response as &$res){
            $res->discountText = '';

            if(count($websiteWideCoupons)) {
                [$res->plan_price, $res->discountText] = $this->calculateWebsiteWideDiscounts($res->plan_price, $websiteWideCoupons);
            }

            if($coupon) {
                if($res->discountText !== '') {
                    $res->discountText .="<br>";
                }

                $res->plan_price = $this->calculateDiscount($res->plan_price, $coupon);
                $res->discountText .= $coupon->discount_type == 'P' ? "Coupon ".$coupon->amount."% off" : sprintf("Coupon $%s off", $coupon->amount);
            }

            $yarly_dis = $res->yearly_discount;
        }

		return view('pricing', compact('monthly_response','onetime_response','managesite','msg','yarly_dis','package_type','buyid','current_packageid','package_subscription','package_status'));
	}

	public function pricing1(){
		$managesite  = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
		$response = DB::table('tbl_plan')->where('plan_status','A')->where('plan_siteid',$managesite->intmanagesiteid)->orderBy('plan_id', 'DESC')->get();

		//$onetime_response = DB::table('tbl_plan')->where('plan_type','O')->where('plan_status','A')->where('plan_siteid',$managesite->intmanagesiteid)->orderBy('plan_id', 'DESC')->get();


		return view('pricing1',compact('response','managesite'));
	}

	public function logout()
    {
		Session::flush();
         return redirect('/');
    }

	public static function managesite(){
        return DB::table('tbl_managesite')->where('txtsiteurl', self::getServerName())->first();
	}

	public static function tblthemesetting(){
        $response = DB::table('tbl_managesite')->where('txtsiteurl', self::getServerName())->first();

		$tblthemesetting = DB::table('tbl_themesetting')->select('*')->where('Intsiteid',$response->intmanagesiteid)->first();

		//echo file_get_contents('images/'.$tblthemesetting->carticon);
		return $tblthemesetting;
	}

	public static function userinfo(){
		$userdetail='';
		if(!empty(Session::get('userid'))){
			$userid=Session::get('userid');
			$userdetail = DB::table('tbluser')->where('intuserid',$userid)->first();
		}
		return $userdetail;
	}

	public static function cartcount(){
		$cartcount='0';
		$response = DB::table('tbl_managesite')->where('txtsiteurl', self::getServerName())->first();
		if(!empty(Session::get('userid'))){
			$userid=Session::get('userid');
		}else{
			 $userid=Session::getId();
		}
			$cartcount=DB::table('tbl_wishlist')->where('userid',$userid)->where('status','cart')->where('siteid',$response->intmanagesiteid)->count();
			// echo "<pre>";
			// print_r($cartcount);
			// exit;

		return $cartcount;
	}

    public static function availablecreditcount(){
        $availablecount = '';

        if(Session::get('userid')) {
            $packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();

            if(!$packageavailable->isEmpty()){
                $total_credits=0;
                $usedcredit=0;

                foreach($packageavailable as $pack){
                    if($pack->package_download < $pack->package_count){
                        $total_credits += $pack->package_count;
                        $usedcredit += $pack->package_download;
                    }
                }

                $availablecount = $total_credits - $usedcredit ;
            }
        }

		return $availablecount;
}

	public static function incartcredit(){
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl', self::getServerName())->first();
        $coupon = data_get(Session::get('cart-coupon'), 'coupon');

        if(Session::get('userid')){
			$userid=Session::get('userid');
            $packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();

            if (!$packageavailable->isEmpty()) {
                $packageid = "";
                $buyid = "";
                $availablecount = 0;
                $total_credits = 0;
                $used_credits = 0;

                foreach($packageavailable as $pack){
                    $total_credits += $pack->package_count;
                    $used_credits += $pack->package_download;
                    if($pack->package_download < $pack->package_count) {
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

            $cartcredit=0;
            $cartvalue=0;

            if(!empty($packageid)){
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
                ->where('tbl_wishlist.userid',$userid)
                ->where('tbl_wishlist.status','cart')
                ->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)
                ->where('tbl_buypackage.status','A')
                ->whereDate('tbl_buypackage.package_expiredate','>',date('Y-m-d'))
                ->orderBy('tbl_wishlist.created_date','DESC')
                ->groupBy('tbl_Video.IntId')
                ->get();

                if(!$response->isEmpty()){
                    $totalitems = count($response);

                    foreach($response as $res){
                        $cartcredit += $res->stock;
                    }

                    $cartvalue = $cartcredit;
                }
            } else {
                $response = DB::table('tbl_wishlist')->select('tblstock.*','tbl_plan.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
                    ->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
                    ->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
                        ->leftjoin("tblstock",function($join){
                             $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
                             ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
                        })
                    ->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
                    ->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.status','cart')->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->orderBy('tbl_wishlist.created_date','DESC')->groupBy('tbl_Video.IntId')
                    ->get();

                if(!$response->isEmpty()) {
                    $totalitems=count($response);

                    foreach($response as $res){
                        $stock = $res->stock;

                        $tiers = $coupon ? explode(',', $coupon->tier) : [];
                        if($coupon && in_array($res->content_category, $tiers)) {
                            $stock = $coupon->discount_type == 'P' ? $stock - $stock * $coupon->amount / 100 : $stock - $coupon->amount;
                        }

                        $cartcredit += $stock;
                    }

                    if(empty($res->conversion_rate)){
                        $cartvalue =$cartcredit;

                    } else {
                        $cartvalue =$cartcredit/$res->conversion_rate;
                    }
                }
            }
        } else {
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

            if(!$response->isEmpty()){
                $totalitems = count($response);

                if($totalitems>0){
                    foreach($response as $res){
                        $cartcredit += $res->stock;
                    }

                    if(empty($res->conversion_rate)){
                        $cartvalue =$cartcredit;

                    } else {
                        $cartvalue =$cartcredit/$res->conversion_rate;
                    }
                } else {
                    $cartvalue=0;
                }
            }
        }

        return $cartvalue;
    }

	public function download(){
			$filePath='';
			//$filePath = $_GET['path'];
			$filePath = 'upload/videosearch/3675/org1580387417.mp4';
			$fileName = basename($filePath);
			if (empty($filePath)) {
				echo "'path' cannot be empty";
				exit;
			}

			if (!file_exists($filePath)) {
				echo "'$filePath' does not exist";
				exit;
			}

			header("Content-disposition: attachment; filename=" . $fileName);
			header("Content-type: " . mime_content_type($filePath));
			readfile($filePath);

	}

	public function myprofile(){
        $this->checklogin();

        $availablecount = 0;
        $userid = Session::get('userid');
        $profiledetail = $this->HomeModel->UserData($userid);
        $packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();

        if(!$packageavailable->isEmpty()){
            $total_credits = 0;
            $used_credits = 0;
            foreach($packageavailable as $pack){
                if($pack->package_download < $pack->package_count){
                    $total_credits += $pack->package_count;
                    $used_credits += $pack->package_download;
                }
            }

            $availablecount = $total_credits - $used_credits;
        }
        return view('/myProfile',compact('profiledetail','availablecount'));
    }

	public function downloadData(Request $request){
		$videoid = $request->id;
		$id = Crypt::decryptString($request->id);
		$response = DB::table('tbl_Video')->where('IntId',$id)->first();
		$res = [];

		if(Session::get('userid')) {
            if($response) {
                $getdownloadresponse = DB::table('tbl_download')->where('user_id', Session::get('userid'))->where('video_id',$id)->first();

                $packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid', Session::get('userid'))->whereDate('package_expiredate', '>', date('Y-m-d'))->get();

                if(!$packageavailable->isEmpty()) {
                    $packageid = '';

                    foreach($packageavailable as $pack) {
                        if($pack->package_download < $pack->package_count){
                            if(empty($packageid)){
                                $packageid = $pack->package_id;
                            }
                        }
                    }

                    if(!empty($packageid)){
                        $stockav = DB::table('tbl_buypackagestock')->where('buypackage_id',$packageid)->where("stocktype_id",$response->stock_category)->where("contentcat_id",$response->content_category)->first();
                        $needstock = 1;

                        if(!empty($stockav)){
                            $needstock = $stockav->stock;
                        }

                        $fileName = $response->VchVideoName;
                        $filePath = $response->VchFolderPath;
                        $managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',self::getServerName())->first();
                        $userinfo = DB::table('tbluser')->where('intuserid',Session::get('userid'))->first();

                        $data2 = [
                            'email'	=> $managesite->vchemailfrom,
                            'emailfrom'	=> $userinfo->vchemail,
                        ];

                        $data['vchfirst_name'] = $userinfo->vchfirst_name;
                        $data['vchsitename'] = $managesite->vchsitename;
                        $data['downloadlink'] = "https://".$managesite->txtsiteurl.'/member-download/';
                        $data['vlogo'] =  "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo;

                        $package = "";
                        $utlra = "";

                        if(!empty($getdownloadresponse)) {
                            foreach($packageavailable as $packageavailables){
                                $packagedownload=$packageavailables->package_download;

                                if($packageavailables->package_download < $packageavailables->package_count){
                                 $package=$packageavailables->package_count-$packagedownload;
                                }
                            }
                        } else {
                            foreach($packageavailable as $packageavailables){
                                $packagedownload=$packageavailables->package_download+$needstock;

                                if($packageavailables->package_download < $packageavailables->package_count){
                                    $package=$packageavailables->package_count-$packagedownload;

                                    if($package == 0){
                                        $utlra = "last";
                                    }
                                }
                            }
                        }

                        if(!empty($package) && $package > 0) {
                            $pvalue="yes";
                        } else {
                            if(!empty($utlra)){
                                $pvalue="yes";
                            }else{
                                $pvalue="no";
                            }
                        }

                        if(!empty($getdownloadresponse)){
                            $res = array('response'=>'done','image'=>$videoid,'pack'=>1,'val'=>'yes','id'=>$id,'credit'=>0,"download"=>'old');
                        } else {
                            $res = array('response'=>'done','image'=>$videoid,'pack'=>(($package > 0)?$package:0),'val'=>$pvalue,'id'=>$id,'credit'=>1,"download"=>'new');
                        }
                    } else {
                        if(!empty($getdownloadresponse)){
                            $res = array('response'=>'done','image'=>$videoid,'credit'=>0,"download"=>'old','pack'=>1,'val'=>'yes');
                        }else{
                            $res = array('response'=>'expire',"download"=>'old','pack'=>1,'val'=>'yes');
                        }
                    }
                } else {
                    if(!empty($getdownloadresponse)) {
                        $res = ['response' => 'done', 'image' => $videoid, 'download' => 'old'];
                    } else {
                        $res = ['response' => 'expire', 'download' => 'old'];
                    }
                }
            } else {
                $res = ['response' => 'expire'];
            }
		} else {
			$res = ['response' => 'login'];
		}
		echo json_encode($res);
	}

	public function changepassword(){
		$this->checklogin();
		$userid=Session::get('userid');
		$id=$this->mycrypt($userid,'e');
		return view('change-password',compact('id'));
	}

	public function fileTodownload($id) {
		$this->checklogin();
		$id = Crypt::decryptString($id);

		$getdownloadresponse = DB::table('tbl_download')->where('user_id',Session::get('userid'))->where('video_id',$id)->first();

		if(!empty($getdownloadresponse)){
			$this->DownloadFileServer2($id);
		} else {
			$packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();
			if(!$packageavailable->isEmpty()){
                $packageid = '';
                $packageDownloadcount = '';
                $buyid = '';

                foreach($packageavailable as $pack) {
                    if($pack->package_download < $pack->package_count){
                        if(empty($packageid)){
                            $packageid = $pack->package_id;
                            $buyid = $pack->buy_id;
                            $packageDownloadcount = $pack->package_download;
                        }
                    }
                }

                if(!empty($packageid)){
                    $managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl', self::getServerName())->first();

                    $videoinfo = DB::table('tbl_Video')->select('content_category','stock_category')->where('IntId',$id)->first();

                    $stockinfo = DB::table('tbl_buypackagestock')->leftjoin('tbl_plan','tbl_plan.plan_id','tbl_buypackagestock.plan_id')->where('buypackage_id',$packageid)->where('plan_id',$buyid)->where('stocktype_id',$videoinfo->stock_category)->where('contentcat_id',$videoinfo->content_category)->first();

                    if(!empty($stockinfo)){
                        if($stockinfo->stock > 0){
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

                            $this->DownloadFileServer2($id);
                        } else {
                            echo 'No Stock available';
                        }
                    } else {
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
        }
	}

	public function RemoveFromWishlist($id){
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl', self::getServerName())->first();
		$this->HomeModel->DeleteFromWishlist($id,$managesite->intmanagesiteid,Session::get('userid'));
	}

	public function buynow2(Request $request){
		Session::put('packageid',$request->packageid);
		$intUserID = Session::get('userid');
		if(empty($intUserID)){
			return redirect('/');
		}else{
			return redirect('/checkout');
		}
	}

	public function buynow(Request $request){
		$userid = Session::get('userid');
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl', self::getServerName())->first();
		$type = '';
		$getplan = '';
		$price = 0;
		$monthly_price = 0;
		$plantype = '';
		$cartval = '';
		$wcount = '';
		$onetime_price = '';
        $coupon = data_get(Session::get('pricing-coupon'), 'coupon');

		if($request->id){
			$type=$request->type;
			Session::put('packageid',$request->id);
			Session::put('packagetype',$request->type);
			Session::put('old_pack_type',$request->old_pack_type);
			Session::put('old_pack_id',$request->old_pack_id);
			$packageid = Session::get('packageid');
            $getplan = DB::table('tbl_plan')->where('plan_id',$packageid)->where('plan_status','A')->first();

            if(empty($packageid) || empty($getplan)){
                return redirect('/');
                exit;
            }

            if($getplan->plan_type=='O'){
                $onetime_price=number_format($getplan->plan_price, 2);
            }

            if($getplan->plan_type=='M'){
                $price1= ($getplan->plan_price * 12);
                $price2= ($getplan->plan_price * 12 * ($getplan->yearly_discount / 100));
                $price=$price1-$price2;
                $monthly_price=number_format($getplan->plan_price, 2);
            }
		} else {
			$plantype='direct';
			$cartval=$request->cartval;
			Session::put('packagetype','direct');
			Session::put('price',$request->cartval);

			$wishlist=DB::table('tbl_wishlist')->where('userid', $userid)->where('status', 'cart')->where('siteid', $managesite->intmanagesiteid)->get();
			$wcount=count($wishlist);
		}

		$country = DB::table('tblcountry')->select('name')->get();
		$billing_address = DB::table('tbl_billinguser')->where('user_id', $userid)->orderBy('billing_id', 'DESC')->first();
		$card_details = DB::table('tbl_paymentdetails')->where('c_userid', $userid)->orderBy('id', 'DESC')->first();

		$discount = '';
		if($coupon){
			if($getplan->plan_type == 'M') {
                $price = $this->calculateDiscount($price, $coupon);
//				$discount = array('discount_type'=>$couponinfo->discount_type,'original_price'=>number_format($price, 2),'discount_amount'=>$couponinfo->amount);
//				if($couponinfo->discount_type == 'P') {
//					$customprice = $price * $couponinfo->amount / 100;
//					$price = $price - $customprice;
//					if($price > 0){
//						$price = $price;
//					}else{
//						$price = 0;
//					}
//				}elseif($couponinfo->discount_type == 'A') {
//					$price = $price - $couponinfo->amount;
//					if($price > 0){
//						$price = $price;
//					}else{
//						$price = 0;
//					}
//				}
			}

            else if($getplan->plan_type=='O') {
                $onetime_price = $this->calculateDiscount($price, $coupon);

//				$discount = array('discount_type'=>$couponinfo->discount_type,'original_price'=>number_format($onetime_price, 2),'discount_amount'=>$couponinfo->amount);
//				if($couponinfo->discount_type == 'P') {
//					$customprice = $onetime_price * $couponinfo->amount / 100;
//					$onetime_price = $onetime_price - $customprice;
//					if($onetime_price > 0){
//						$onetime_price = $onetime_price;
//					}else{
//						$onetime_price = 0;
//					}
//				}elseif($couponinfo->discount_type == 'A') {
//					$onetime_price = $onetime_price - $couponinfo->amount;
//					if($onetime_price > 0){
//						$onetime_price = $onetime_price;
//					}else{
//						$onetime_price = 0;
//					}
//				}
			}
		}

		$value = [
            'response' => 1,
            'getplan' => $getplan,
            'country' => $country,
            'type' => $type,
            'annually_price' => number_format($price, 2),
            'monthly_price' => $monthly_price,
            'plantype' => $plantype,
            'cartval' => $cartval,
            'wcount' => $wcount,
            'billing_address' => $billing_address,
            'card_details' => $card_details,
            'onetime_price' => $onetime_price,
            'discount' => $discount
        ];

		echo json_encode($value);
	}

	public function checkout(Request $request) {
		$this->checklogin();
		$packageid = Session::get('packageid');
		$getplan = DB::table('tbl_plan')->where('plan_id',$packageid)->where('plan_status','A')->first();

		if(empty($packageid) || empty($getplan)) {
			return redirect('/');
			exit;
		}

		$country = DB::table('tblcountry')->get();

		return view('/checkout',compact('country','getplan'));
	}

	public function downloadlist(Request $request){
		$this->checklogin();
		$search = $request->search;
		$imgtype = $request->type;
		$userid = Session::get('userid');
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
		$response =  DB::table('tbl_download')->leftjoin('tbl_Video','tbl_download.video_id','tbl_Video.IntId')->where('tbl_download.user_id',$userid)->where('tbl_download.site_id',$managesite->intmanagesiteid);

        if(!empty($search)){
			 $response->where(DB::raw('CONCAT_WS(" ",VchTitle,txtsiteurl)'),'like',  "%$search%");
        }

        if(!empty($imgtype)){
			 $response->where('tbl_Video.EnumType',$imgtype);
		}

		$siteid=$managesite->intmanagesiteid;
		$response = $response->orderBy('create_at','DESC')->paginate(10)->appends('search',$search)->appends('imgtype',$imgtype);
		return view('/user-downloads',compact('response','search','imgtype','siteid'));
	}

	public function getreceipt(Request $request){
		 Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

         $invoice =	Stripe\Receipt::retrieve('2484-8571');
         $invoiceresponse = $invoice->jsonSerialize();
         echo"<pre>";
         print_r($invoice);
         exit;
	}

	public function payment(Request $request) {
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',self::getServerName())->first();
		$userinfo = $this->HomeModel->UserData(Session::get('userid'));
		$getapidetail = DB::table('tblapidetail')->where('id','1')->first();
        $pricingCoupon = Session::get('pricing-coupon');
        $cartCoupon = Session::get('cart-coupon');
        $cartWebsiteWideCoupons = $this->getCartWebsiteWideCoupons();
        $pricingWebsiteWideCoupons = $this->getPricingWebsiteWideCoupons();

        Stripe\Stripe::setApiKey($getapidetail->stripe_secret);

        if(!empty($userinfo) && $userinfo->verifystatus == '1') {
            // Pricing payment.
            if($packageid = Session::get('packageid')){
                $getplan = DB::table('tbl_plan')->where('plan_id',$packageid)->where('plan_status','A')->first();
                $price1 = ($getplan->plan_price * 12);
                $price2 = ($getplan->plan_price * 12 * ($getplan->yearly_discount / 100));
                $yearlyprice = $price1-$price2;
                $yearlyprice = $this->calculateDiscount($yearlyprice, data_get($pricingCoupon, 'coupon'));
                $monthlyprice = $yearlyprice/12;
                $plan_title=strip_tags($getplan->plan_title);
                $plan_name=strip_tags($getplan->plan_name);

                if($getplan->plan_purchase == 'O'){
                    $paymentarray = [
                            "amount" => $getplan->plan_price * 100,
                            "currency" => "usd",
                            "source" => $request->stripeToken,
                            "description" => 'One Time Credit Purchase -'.$plan_name
                    ];

                    $response = Stripe\Charge::create($paymentarray);
                    $response = $response->jsonSerialize();
                    $card_name = $response['payment_method_details']['card']['network'];
                    $lastdigit = $response['payment_method_details']['card']['last4'];
                    $plan_title = 'One Time Credit Purchase';
                }

                // Pricing payment
                if($getplan->plan_purchase == 'M'){
                    if(Session::get('packagetype') == 'monthly') {
                         $customer = \Stripe\Customer::create([
                            'email' => $userinfo->vchemail,
                            'source'  => $request->stripeToken
                        ]);

                        // Create a plan
                        try {
                            $plan = \Stripe\Plan::create([
                                "product" => [
                                    "name" => $plan_title.' - Subscription - Monthly - '.$plan_name
                                ],
                                "amount" => $getplan->plan_price * 100,
                                "currency" => 'usd',
                                "interval" => 'month',
                                "interval_count" => 1,
                            ]);

                            // Create a coupon
                            if($pricingCoupon) {
                                $this->createCoupon(data_get($pricingCoupon, 'coupon'));
                            }

                            if(count($pricingWebsiteWideCoupons)) {
                                $this->createWebsiteWideCoupons($pricingWebsiteWideCoupons);
                            }
                        } catch (Exception $e) {
                            $api_error = $e->getMessage();
                        }

                        if(empty($api_error) && $plan){
                            // Creates a new subscription
                            try {
                                $subscriptionData = [
                                    'customer' => $customer->id,
                                    'items' => [
                                        [
                                            'plan' => $plan->id

                                        ],
                                    ],
                                ];

                                if($pricingCoupon) {
                                    $subscriptionData['coupon'] = $pricingCoupon['coupon']->coupon;
                                } else if(count($pricingWebsiteWideCoupons)) {
                                    $subscriptionData['coupon'] = $pricingWebsiteWideCoupons[0]->coupon;
                                }

                                $subscription = \Stripe\Subscription::create($subscriptionData);
                            } catch (Exception $e) {
                                $api_error = $e->getMessage();
                            }
                        }

                        $response = $subscription->jsonSerialize();
                        $card_name='';
                        $lastdigit='';

                        $invoice_number=$response['latest_invoice'];
                        $invoice =	Stripe\Invoice::retrieve($response['latest_invoice']);
                        $invoiceresponse = $invoice->jsonSerialize();

                    }

                    else if(Session::get('packagetype') == 'annual'){
                         $customer = \Stripe\Customer::create([
                            'email' => $userinfo->vchemail,
                            'source'  => $request->stripeToken,
                        ]);

                        // Create a plan
                        try {
                            $plan = \Stripe\Plan::create([
                                "product" => [
                                    "name" => $plan_title.' - Subscription - Yearly - '.$plan_name
                                ],
                                "amount" => $yearlyprice * 100,
                                "currency" => 'usd',
                                "interval" => 'year',
                                "interval_count" => 1,
                            ]);

                            // Create a coupon
                            if($pricingCoupon) {
                                $this->createCoupon(data_get($pricingCoupon, 'coupon'));
                            }

                            if(count($pricingWebsiteWideCoupons)) {
                                $this->createWebsiteWideCoupons($pricingWebsiteWideCoupons);
                            }
                        } catch (Exception $e) {
                            $api_error = $e->getMessage();
                        }

                        if(empty($api_error) && $plan) {
                            // Creates a new subscription
                            try {
                                $subscriptionData = [
                                    'customer' => $customer->id,
                                    'items' => [
                                        [
                                            'plan' => $plan->id
                                        ],
                                    ],
                                ];

                                if($pricingCoupon) {
                                    $subscriptionData['coupon'] = $pricingCoupon['coupon']->coupon;
                                } else if (count($pricingWebsiteWideCoupons)) {
                                    $subscriptionData['coupon'] = $pricingWebsiteWideCoupons[0]->coupon;
                                }
                                $subscription = \Stripe\Subscription::create($subscriptionData);
                            } catch(Exception $e) {
                                $api_error = $e->getMessage();
                            }
                        }

                        $response = $subscription->jsonSerialize();
                        $card_name = '';
                        $lastdigit = '';
                        $invoice_number = $response['latest_invoice'];
                        $invoice = Stripe\Invoice::retrieve($response['latest_invoice']);
                        $invoiceresponse = $invoice->jsonSerialize();
                    }
                }

                if($response['status'] == 'active' || $response['status'] == 'succeeded'){
                    if($pricingCoupon) {
                        DB::table('users_coupons')->insert([
                            'user_id' => $pricingCoupon['user_id'],
                            'coupon_id' => $pricingCoupon['coupon_id'],
                        ]);

                        Session::forget('pricing-coupon');
                    }

                    $renewid = '';

                    if($getplan->plan_purchase == 'M'){
                        if(Session::get('packagetype') == 'monthly'){
                            $buy_type='M';
                            $packagestart = $response['current_period_end'];
                            $renewid = $response['id'];
                            $receipt_url = $invoiceresponse['hosted_invoice_url'];
                            $paymentdata = [
                                "strip_paymentid"=>$response['id'],
                                "strip_packagename"=>$getplan->plan_title,
                                "strip_transactionid"=>$response['plan']['id'],
                                "strip_amount"=>($response['plan']['amount'] / 100),
                                "strip_created"=>$response['plan']['created'],
                                "strip_currency"=>$response['plan']['currency'],
                                "strip_receipt_url"=>$invoiceresponse['hosted_invoice_url'],
                                "strip_status"=>$response['status'],
                                "plan_id"=>$packageid,
                                "strip_package_type"=>$buy_type,
                                "user_id"=>Session::get('userid'),
                                "create_at"=>date('Y-m-d H:i:s')
                            ];
                        }
                        else if(Session::get('packagetype') == 'annual'){
                            $buy_type = 'Y';
                            $packagestart = $response['current_period_end'];
                            $renewid = $response['id'];
                            $receipt_url = $invoiceresponse['hosted_invoice_url'];
                            $paymentdata = [
                                "strip_paymentid" => $response['id'],
                                "strip_packagename" => $getplan->plan_title,
                                "strip_transactionid" => $response['plan']['id'],
                                "strip_amount" => ($response['plan']['amount'] / 100),
                                "strip_created" => $response['plan']['created'],
                                "strip_currency" => $response['plan']['currency'],
                                "strip_receipt_url" => $invoiceresponse['hosted_invoice_url'],
                                "strip_status" => $response['status'],
                                "plan_id" => $packageid,
                                "strip_package_type" => $buy_type,
                                "user_id" => Session::get('userid'),
                                "create_at" => date('Y-m-d H:i:s'),
                            ];
                        }
                    }
                    else if($getplan->plan_purchase == 'O'){
                        $receipt_url = $response['receipt_url'];
                        $buy_type = 'O';
                        $paymentdata = [
                            "strip_paymentid"=>$response['id'],
                            "strip_packagename"=>'One Time Purchase',
                            "strip_transactionid"=>$response['balance_transaction'],
                            "strip_amount"=>($response['amount'] / 100),
                            "strip_created"=>$response['created'],
                            "strip_currency"=>$response['currency'],
                            "strip_receipt_url"=>$response['receipt_url'],
                            "strip_status"=>$response['status'],
                            "plan_id"=>$packageid,
                            "strip_package_type"=>$buy_type,
                            "user_id"=>Session::get('userid'),
                            "create_at"=>date('Y-m-d H:i:s')
                        ];

                        $packagedata = [
                        'buy_id'=>$packageid,
                        'package_name'=>$getplan->plan_name,
                        'package_count'=>$getplan->plan_download,
                        'package_credit'=>$getplan->plan_download,
                        'package_userid'=>Session::get('userid'),
                        'package_download'=>0,
                        'site_id'=>$managesite->intmanagesiteid,
                        "package_type"=>$buy_type,
                        'create_at'=>date('Y-m-d H:i:s')
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

                    $packagedata['payment_id'] = $paymentlastid;

                    if($buy_type == 'M' || $buy_type == 'Y'){
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
                    }
                    if($buy_type == 'Y' || $buy_type == 'M'){
                        if(!empty($renewid)) {
                            $packagedata['package_renewid'] = $renewid;
                            $packagedata['package_subscription'] = 'Y';
                        }

                        if(!empty($request->old_packageid)){

                            $buypackageinfo = DB::table('tbl_buypackage')->where('package_userid',Session::get('userid'))
                            ->where('package_subscription','Y')->where('status','A')->get();
                            if(!empty($buypackageinfo)){
                                foreach($buypackageinfo as $buyinfos){
                                    $id=$buyinfos->package_id;

                                        $packagearray = [
                                        "status" => 'A',
                                        "package_subscription" => 'C'

                                    ];
                                    $this->HomeModel->UpdateBuyPackage($id,$packagearray);

                                    $subscription = \Stripe\Subscription::retrieve($buyinfos->package_renewid);
                                    $subscription->cancel();
                                    $response = $subscription->jsonSerialize();
                                }
                            }
                        }

                        $packagedata['package_startdate'] = date('Y-m-d H:i:s');
                        $packagedata['package_start_time'] = $packagestart;

                        if($buy_type=='M'){
                            $packagedata['package_expiredate'] = date('Y-m-d H:i:s', strtotime("+".$getplan->plan_time." month"));
                            $expiry_date = date('M d, Y', strtotime("+".$getplan->plan_time." month"));
                        } elseif ($buy_type=='Y') {
                            $packagedata['package_expiredate'] = date('Y-m-d H:i:s', strtotime("+".$getplan->plan_time." years"));
                            $expiry_date = date('M d, Y', strtotime("+".$getplan->plan_time." years"));
                        }
                    } else if($getplan->plan_type == 'O'){
                        $packagedata['package_startdate'] = date('Y-m-d H:i:s');
                        $packagedata['package_expiredate'] = date('Y-m-d H:i:s', strtotime("+30 days"));
                        $expiry_date = date('M d, Y', strtotime("+30 days"));
                    }

                    if($getplan->plan_type == 'O'){
                        $buyid = $this->HomeModel->buypackage_insert($packagedata);
                    }else{
                        $buyid = $this->HomeModel->buypackage_insert($packagedata);
                    }

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

                    $data2 = [
                        'email'	=> $managesite->vchemailfrom,
                        'emailfrom'	=> $userinfo->vchemail,
                        'vchsitename'	=> $managesite->vchsitename,
                    ];

                    $data['vchfirst_name'] = $userinfo->vchfirst_name;
                    $data['vchsitename'] = $managesite->vchsitename;
                    $data['vlogo'] =  "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo;
                    $data['siteurl'] =  "https://".$managesite->txtsiteurl;
                    $data['package_name'] =  strip_tags($getplan->plan_name);
                    $data['payment_time']= date('M d, Y');
                    $data['package_startdate'] = date('M d');
                    $data['expiry_date'] = $expiry_date;
                    $data['card_name'] = $card_name;
                    $data['lastdigit'] = $lastdigit;
                    $data['receipt_url'] = $receipt_url;
                    $data['surface']=$managesite->surface;
                    $data['surfacetext_iconcolor']=$managesite->surfacetext_iconcolor;
                    $data['primary_color']=$managesite->primary_color;
                    $data['primarytext_iconcolor']=$managesite->primarytext_iconcolor;
                    $data['hyperlink']=$managesite->hyperlink;
                    $data['bgtext_iconcolor']=$managesite->bgtext_iconcolor;
                    $data['background_color']=$managesite->background_color;

                    if($buy_type == 'Y'){
                        $data['package_title']=strip_tags($getplan->plan_title);
                        $data['purchase_type']= 'Anually';
                        $data['strip_amount'] =  number_format($response['plan']['amount'] / 100, 2);
                    }elseif($buy_type == 'M'){
                        $data['package_title']=strip_tags($getplan->plan_title);
                        $data['strip_amount'] =  number_format($response['plan']['amount'] / 100, 2);
                        $data['purchase_type']= 'Monthly';
                    }elseif($getplan->plan_type == 'O'){
                        $data['package_title']='One Time Credit Purchase';
                        $data['purchase_type']= 'One time purchase';
                        $data['strip_amount'] =  number_format($response['amount'] / 100, 2);

                    }
                    $data['contactlink'] = "https://".$managesite->txtsiteurl.'/custom';
                    Mail::send('email.purchase',['data'=>$data], function ($message) use ($data2) {

                    $message->from($data2['email'],$data2['vchsitename']);
                    $message->to($data2['emailfrom']);
                    $message->subject('Your receipt from '.$data2['vchsitename']);
                    });


                    Session::put('packageid','');
                    Session::put('packagetype','');
                    Session::put('price','');
                    $arrayresponse = array('response' => 'done', 'transaction' => '', 'code' => 200);
                }

                else{
                    $arrayresponse = array('response'=>'failed','code'=>504);
                }
            }

            // Cart payment.
            else if(Session::get('packagetype') == 'direct') {
                $puchasetype=Session::get('packagetype');

                $price = $this->calculateWebsiteWideDiscounts(Session::get('price'), $cartWebsiteWideCoupons, false);
                $puchaseprice = $this->calculateDiscount($price, data_get($cartCoupon, 'coupon'));

                $paymentarray = [
                    "amount" => $puchaseprice * 100,
                    "currency" => "usd",
                    "source" => $request->stripeToken,
                    "description" => 'Shopping Cart - Direct Purchase',
                ];

                $response = Stripe\Charge::create($paymentarray);
                $response = $response->jsonSerialize();

                $paymentdata = [
                    "strip_paymentid" => $response['id'],
                    "strip_packagename" => 'No Sub - Direct Payment',
                    "strip_transactionid" => $response['balance_transaction'],
                    "strip_amount" => ($response['amount'] / 100),
                    "strip_created" => $response['created'],
                    "strip_currency" => $response['currency'],
                    "strip_receipt_url" => $response['receipt_url'],
                    "strip_status" => $response['status'],
                    "plan_id" => 0,
                    "strip_package_type" => 'D',
                    "user_id" => Session::get('userid'),
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

                Session::forget('packagetype');
                Session::forget('price');

                $arrayresponse = array('response'=>'done','transaction'=>'','type'=>'direct','code'=>200);

                if($cartCoupon) {
                    DB::table('users_coupons')->insert([
                        'user_id' => $cartCoupon['user_id'],
                        'coupon_id' => $cartCoupon['coupon_id'],
                    ]);

                    Session::forget('cart-coupon');
                }
            } else {
                $arrayresponse = ['response' => 'failed', 'code' => 404];
            }
		} else{
			$arrayresponse = ['response' => 'failed','code' => 301];
		}

		echo json_encode($arrayresponse);
	}

	public function memberplans(){
		$this->checklogin();
		$userid = Session::get('userid');
		$response = $this->HomeModel->buy_planslist2($userid);
		$packageid ="";
		$package='';
        $packageDownloadcount ="";
        $buyid =" ";

        foreach($response as $pack){
            if($pack->package_download < $pack->package_count){
                if(empty($packageid)){
                    $packageid = $pack->package_id;
                    $package = $pack;

                }
            }
        }

		return view('/member-plans',compact('response','package'));
	}

	public function purchasehistory(){
			$this->checklogin();
		$userid = Session::get('userid');
		$response = $this->HomeModel->purchasehistory($userid);
		// echo "<pre>";
		// print_r($response);
		// exit;
		return view('/purchase_history',compact('response'));
	}

	public function checkmail(Request $request){
		$email = $request->email;
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
		$checkemail = $this->HomeModel->checkmail($email,$managesite->intmanagesiteid);
		if(!empty($checkemail)){
			$value = array('response'=>3);
				echo json_encode($value);
		}else{
			$value = array('response'=>0);
				echo json_encode($value);
		}
	}

	public function wishlistData(Request $request){
		//$this->checklogin();
		if(!empty(Session::get('userid'))){
			$userid=Session::get('userid');
			}else{
			$userid=Session::getId();
		}
		//echo $userid;
		//exit;
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',self::getServerName())->first();
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
				'status'=> 'cart',
				'created_date'	=> $date,
			);
			$lastinsetid=$this->HomeModel->wishlistdata($data);
			}else{
					$dataarr = array(
						"status"=> 'cart',
					);
			DB::table('tbl_wishlist')->where('id', $checklist->id)->update($dataarr);


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

						if($pack->package_download < $pack->package_count){
							$total_credit += $pack->package_count;
						$used_credit += $pack->package_download;
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
			$this->checklogin();
		$userid = Session::get('userid');
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
		$response =  DB::table('tbl_wishlist')->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId')->whereNotNull('status')->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->orderBy('created_date','DESC')->paginate(10);
		$siteid=$managesite->intmanagesiteid;

		return view('/user-wishlist',compact('response','siteid'));


	}

	public function deletewishlist(Request $request){
		//$this->checklogin();
		$resname =  DB::table('tbl_wishlist')->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId')->where('tbl_wishlist.id',$request->id)->first();

		$results = DB::table('tbl_wishlist')->where('id', $request->id)->delete();

		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('tbl_managesite.txtsiteurl',self::getServerName())->first();
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
						if(!empty($res->conversion_rate)){
							 $cartvalue +=$res->stock/$res->conversion_rate;
							}else{
							$cartvalue +=$res->stock;
						}
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
							$cartvalue =$res->stock/$res->conversion_rate;
						}
				}else{
					$cartvalue=0;

				}
				$carticon= '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 3c0 .55.45 1 1 1h1l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h11c.55 0 1-.45 1-1s-.45-1-1-1H7l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.67-1.43c-.16-.35-.52-.57-.9-.57H2c-.55 0-1 .45-1 1zm16 15c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path></svg>';
						$value2 = array('cartvalue'=>'$'.number_format($cartvalue, 2),'totalitems'=>$totalitems,'carticon'=>$carticon);
		}

		$value = array('value2'=>$value2,'response'=>1,'message'=>'<b>'.$resname->VchTitle.'</b> was removed from shopping cart. <a class="hyperlink-setting" id="undo">Undo</a>');

		echo json_encode($value);
		}

	public static function plans(){
		$managesite  = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
		$response = DB::table('tbl_plan')->where('plan_status','A')->where('plan_siteid',$managesite->intmanagesiteid)->get();
		return $response;
	}

	public static function background(){
		$managesite  = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
		$response = DB::table('tbl_backgrounds')->whereRaw('FIND_IN_SET('.$managesite->intmanagesiteid.',siteid)')->get();
		return $response;
	}

	public function cart() {
        $managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',self::getServerName())->first();
		$siteid = $managesite->intmanagesiteid;
		$package = '';
		$stockinfo = '';
		$totalitems = '';
		$checkloginuser = '';
		$packageid = '';
		$availablecount ='';

        $coupon = data_get(Session::get('cart-coupon'), 'coupon');
        $cartWebsiteWideCoupons = $this->getCartWebsiteWideCoupons();

        $cartcredit = 0;
        $cartvalue = 0;
        if(Session::get('userid')) {
            $userid = Session::get('userid');
			$packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid', Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();

            if(!$packageavailable->isEmpty()){
                $packageid = '';
                $buyid = '';
                $availablecount = 0;
                $total_credit = 0;
                $used_credit = 0;

                foreach($packageavailable as $pack){
                    if($pack->package_download < $pack->package_count){
                        $total_credit += $pack->package_count;

                        $used_credit += $pack->package_download;

                        if(empty($packageid)){
                            $packageid = $pack->package_id;
                            $buyid = $pack->buy_id;
                            $pck=$pack->package_count;
                        }
                    }
                }

                $availablecount = $total_credit - $used_credit;

                if($availablecount == 0){
                    $packageid = '';
                }
            }

            $checkloginuser = Session::get('userid');

            if (!empty($packageid)) {
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
                ->where('tbl_wishlist.userid',$userid)
                ->where('tbl_wishlist.status','cart')
                ->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)
                ->where('tbl_buypackage.status','A')
                ->where('tbl_buypackage.package_id',$packageid)
                ->whereDate('tbl_buypackage.package_expiredate','>',date('Y-m-d'))
                ->orderBy('tbl_wishlist.created_date','DESC')
                ->groupBy('tbl_Video.IntId')
                ->get();

                if (!$response->isEmpty()) {
                    $totalitems = count($response);
                    $cartvalue = $this->incartcredit();

                    $cartcredit = 0;

                    foreach($response as $res){
                        $cartcredit += $res->stock;

                        $infavoriteslist =  DB::table('tbl_favorites')->where('tbl_favorites.fav_videoid',$res->IntId)->where('tbl_favorites.fav_userid',$userid)->where('tbl_favorites.fav_siteid',$managesite->intmanagesiteid)->first();

                        $res->favoritesstatus = $infavoriteslist ? 'in-favorites' : 'out-favorites';
                    }

                    if(!empty($res->conversion_rate) && $packageavailable->isEmpty()){
                        $cartvalue = $cartcredit/$res->conversion_rate;
                    } else {
                        $cartvalue = $cartcredit;
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
            } else {
				$cartcredit = 0;
				$cartvalue = 0;
				$response = DB::table('tbl_wishlist')->select('tblstock.*','tbl_plan.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
                    ->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
                    ->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
                    ->leftjoin("tblstock",function($join){
                         $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
                         ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
                    })
                    ->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
                    ->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.status','cart')->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->orderBy('tbl_wishlist.created_date','DESC')->groupBy('tbl_Video.IntId')
                    ->get();

				$later_response = DB::table('tbl_wishlist')
                    ->select('tblstock.*','tbl_plan.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
                    ->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
                    ->leftJoin('tbl_Videotagrelations', 'tbl_Video.IntId', '=', 'tbl_Videotagrelations.VchVideoId')
                    ->leftjoin("tblstock",function($join){
                         $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
                         ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
                    })
                    ->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
                    ->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.status','later')->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->orderBy('tbl_wishlist.created_date','DESC')->groupBy('tbl_Video.IntId')->get();

                if (!$response->isEmpty()) {
                    $totalitems = count($response);

                    foreach($response as &$res){
                        [$stock, $res->discountText] = $this->calculateWebsiteWideDiscounts($res->stock, $cartWebsiteWideCoupons);

                        $tiers = $coupon ? explode(',', $coupon->tier) : [];
                        if($coupon && in_array($res->content_category, $tiers)) {
                            if($res->discountText !== '') {
                                $res->discountText .="<br>";
                            }

                            $stock = $this->calculateDiscount($stock, $coupon);
                            $res->discountText .= $coupon->discount_type == 'P' ? "Coupon ".$coupon->amount."% off" : sprintf("Coupon $%s off", $coupon->amount);
                        }

                        $res->stock = $stock;

                        $cartcredit += $stock;

                        $infavoriteslist =  DB::table('tbl_favorites')->where('tbl_favorites.fav_videoid',$res->IntId)->where('tbl_favorites.fav_userid',$userid)->where('tbl_favorites.fav_siteid',$managesite->intmanagesiteid)->first();

                        $res->favoritesstatus = $infavoriteslist ? 'in-favorites' : 'out-favorites';
                    }

                    if (!empty($res->conversion_rate)) {
                        $cartvalue =$cartcredit/$res->conversion_rate;
                    } else {
                        $cartvalue =$cartcredit;
                    }
				}
            }
        } else {
            $userid = Session::getId();

            $response = DB::table('tbl_wishlist')->select('tblstock.*','tbl_plan.*','tbl_wishlist.*','tbl_Video.*','tbl_Videotagrelations.VchVideoId',DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))
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
                ->leftjoin("tblstock",function ($join) {
                     $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
                     ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
                })
                ->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
                ->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.status','later')->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->orderBy('tbl_wishlist.created_date','DESC')->groupBy('tbl_Video.IntId')->get();

            if(!$response->isEmpty()){
                $totalitems = count($response);

                foreach($response as &$res) {
                    $res->discountText = '';
                    [$stock, $res->discountText] = $this->calculateWebsiteWideDiscounts($res->stock, $cartWebsiteWideCoupons);

//                    if($cartWebsiteWideCoupons) {
//                        $res->text =
//                    }

                    $tiers = $coupon ? explode(',', $coupon->tier) : [];
                    if($coupon && in_array($res->content_category, $tiers)) {
                        $stock = $this->calculateDiscount($stock, $coupon);
                        $res->discountText = $coupon->discount_type == 'P' ? 'Coupon '.$coupon->amount.'% off' : sprintf('Coupon $%s off', $coupon->amount);
                    }
                    $res->stock = $stock;

                    $cartcredit += $stock;

                    $infavoriteslist =  DB::table('tbl_favorites')->where('tbl_favorites.fav_videoid',$res->IntId)->where('tbl_favorites.fav_userid',$userid)->where('tbl_favorites.fav_siteid',$managesite->intmanagesiteid)->first();

                    $res->favoritesstatus = $infavoriteslist ? 'in-favorites' : 'out-favorites';
                }

                if (data_get($res, 'conversion_rate')) {
                    $cartvalue =$cartcredit/$res->conversion_rate;
                } else {
                    $cartvalue =$cartcredit;
                }
            }
		}

        $expensive_plan = DB::table('tbl_plan')->where('plan_siteid', $managesite->intmanagesiteid)->where('plan_type','M')->orderBy('plan_price', 'desc')->first();
        $pricepercredit = 1;

        if($expensive_plan) {
            $yearly_discount = $expensive_plan->plan_price * $expensive_plan->yearly_discount / 100;
            $calculatedDis = $expensive_plan->plan_price - $yearly_discount;
            $pricepercredit = $expensive_plan->plan_download / $calculatedDis;
        }

        $cartcreditvalue = $cartcredit / $pricepercredit;
        $saveuptoamount = abs($cartvalue -  $cartcreditvalue);
        $myPlan = DB::table('tbl_plan')->where('plan_id',$packageid)->where('plan_status','A')->first();
        $backgroundslist = DB::table('tbl_backgrounds')->whereRaw('FIND_IN_SET('.$managesite->intmanagesiteid.',siteid)')->get();

        foreach($response as &$res) {
            $background = DB::table('tbl_backgrounds')->where('background_title', $res->applied_bg)->first();
            $res->background_id = data_get($background, 'bg_id');
        }

        return view('/cart', compact('response','siteid','packageid','later_response','stockinfo','totalitems','cartvalue','checkloginuser','saveuptoamount','managesite','availablecount','backgroundslist'));
	}

	public function savetolater(Request $request){
        DB::table('tbl_wishlist')->where('id', $request->id)->update(['status' => $request->status]);

		return response()->json(['response' => 1]);
	}

    public function refreshCaptcha()
    {
		return response()->json(['captcha'=> captcha_img()]);
    }

	public function autorenew(){
	}

	public function downloadcart(Request $request){
		$arr = explode(',',$request->check_id);
		$cartcount='';
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',self::getServerName())->first();
		$cartvalue=$request->cartvalue;

		if(!empty($arr)){
			$response  = DB::table('tbl_wishlist')->whereIn('id', $arr)->orderBy('id', 'DESC')->get();
		}else{
            $response  = DB::table('tbl_wishlist')->where('siteid',$managesite->intmanagesiteid)->where('status','cart')->where('userid',Session::get('userid'))->orderBy('id', 'DESC')->get();
		}

		$crtcount=count($response);
		$arrayresponse = [];
		$y = 1;
		$x = 0;
		$n = 0;
        $packageIds = [];
        $buyIds = [];

        if($request->type=='direct'){
            $totalCountImage = 0;
			$cartcount = 0;
			foreach($response as $res){
                $getdownloadres = DB::table('tbl_download')->where('user_id',Session::get('userid'))->where('video_id',$res->videoid)->where('site_id',$managesite->intmanagesiteid)->first();
				$videoinfo = DB::table('tbl_Video')->select('content_category','stock_category','VchFolderPath','VchVideoName')->where('IntId',$res->videoid)->first();
				$cartcount= $crtcount - $y;
				$arrayresponse[] = array('cartid'=>$res->id,'downloadid'=>Crypt::encryptString($res->videoid));
				$y++;
			}
		}
        else {
            $packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();

            $packageDownloadcount = 0;
            $availablecount = 0;
            $total_credit = 0;
            $used_credit = 0;
            $packageid ="";
            $buyid ="";
            $onepackcreditcount=0;

			if(!$packageavailable->isEmpty()){
                $availablecount = $packageavailable->sum('package_count') - $packageavailable->sum('package_download');

                $cartCreditsLeft = $cartvalue;

                foreach($packageavailable as $package) {
                    $creditsInCurrentPackage = $package->package_download < $package->package_count;

                    $packageIds[] = $package->package_id;
                    $buyIds[] = $package->buy_id;

                    if($creditsInCurrentPackage >= $cartCreditsLeft){
                        $cartCreditsLeft = 0;
                        break;
                    }

                    $cartCreditsLeft = $cartCreditsLeft - $creditsInCurrentPackage;
                }

                if ($cartCreditsLeft !== 0) {
                    return 1;
                }
			}

            $packageIds = array_unique($packageIds);
            $buyIds = array_unique($buyIds);

            $totalCountImage = $availablecount;

            if($availablecount > 0){
                foreach($response as $res){
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
                        }
                        else{
                            $stockinfos = DB::table('tbl_buypackagestock')
                                ->whereIn('buypackage_id', $packageIds)
                                ->whereIn('plan_id', $buyIds)
                                ->where('stocktype_id',$videoinfo->stock_category)
                                ->where('contentcat_id',$videoinfo->content_category)
                                ->get();

                            if($stockinfos->count()){
                                foreach($stockinfos as $stockinfo) {
                                    $real_stockvalue = $stockinfo->stock;

                                    if($real_stockvalue <=  $totalCountImage){
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
                    }


                    $y++;
                }
            }

            $availablecount = $availablecount - $n;
        }

        $carticon = '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 3c0 .55.45 1 1 1h1l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h11c.55 0 1-.45 1-1s-.45-1-1-1H7l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.67-1.43c-.16-.35-.52-.57-.9-.57H2c-.55 0-1 .45-1 1zm16 15c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path></svg>';

        echo json_encode(array("response"=>$arrayresponse,"available"=>$totalCountImage,'cartcount'=>$cartcount,'carticon'=>$carticon));
	}

	public function resend_email(Request $request){
		$this->checklogin();
		if(!empty(Session::get('userid'))){
			$userid=Session::get('userid');
			$userdetail = DB::table('tbluser')->where('intuserid',$userid)->first();
			$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',self::getServerName())->first();

			$data['vchsitename'] = $managesite->vchsitename;
			$data['siteurl'] =  "https://".$managesite->txtsiteurl;
			$data['vlogo'] =  "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo;
			$data['vchfirst_name'] = $userdetail->vchfirst_name;
			$data['userid'] = Crypt::encryptString($userdetail->intuserid);
			$data['surface']=$managesite->surface;
			$data['surfacetext_iconcolor']=$managesite->surfacetext_iconcolor;
			$data['primary_color']=$managesite->primary_color;
			$data['primarytext_iconcolor']=$managesite->primarytext_iconcolor;
			$data['hyperlink']=$managesite->hyperlink;
			$data['bgtext_iconcolor']=$managesite->bgtext_iconcolor;

			$data2 = [
				'vchsitename' => $managesite->vchsitename,
				'email'	=> $userdetail->vchemail,
				'emailfrom'	=> $managesite->vchemailfrom,
			];

			Mail::send('email.emailverify',['data'=>$data], function ($message) use ($data2) {
				$message->from($data2['emailfrom'],$data2['vchsitename']);
				$message->to($data2['email']);
                $message->subject('Welcome to '.$data2['vchsitename'].' - Please verify your account');
            });
		}
	}

	public function pack_unsubscribe(Request $request){
		$this->checklogin();
		$getapidetail = DB::table('tblapidetail')->where('id','1')->first();

		$packid=$request->packid;
		$buypack = DB::table('tbl_buypackage')->leftjoin('tbluser','tbl_buypackage.package_userid','tbluser.intuserid')->leftjoin('tbl_plan','tbl_plan.plan_id','tbl_buypackage.buy_id')->where('package_id',$packid)->first();
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',self::getServerName())->first();
		$dataarr = array(
				"package_subscription"=> 'C',
				"status"=> 'A',
			);

		Stripe\Stripe::setApiKey($getapidetail->stripe_secret);
		$subscription = \Stripe\Subscription::retrieve($buypack->package_renewid);
		//$cancelNow = array('at_period_end' => true); // false will cancel now
		$subscription->cancel();
		$response = $subscription->jsonSerialize();

		$cancel_data = [
			'user_id'=>$buypack->package_userid,
			'subscription_id'=>$buypack->package_renewid,
			'subscription_response'=>serialize($response),
			'package_id'=>$buypack->package_id,
			'created_at'=>date('Y-m-d H:i:s')
		];

		DB::table('tbl_subscription_cancel')->insert($cancel_data);

		DB::table('tbl_buypackage')->where('package_id', $packid)->update($dataarr);


		$value = array('response'=>1,'packid'=>$packid);

		echo json_encode($value);

		$data2 = array(
				'email'	=> $buypack->vchemail,
				'emailfrom'	=> $managesite->vchemailfrom,
				'vchsitename'	=> $managesite->vchsitename,
			);

			$data['siteurl'] = "https://".$managesite->txtsiteurl;
			$data['vchsitename'] = $managesite->vchsitename;
			$data['vlogo'] =  "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo;
			$data['vchfirst_name'] = $buypack->vchfirst_name;
			$data['exp_date'] = date('M d, Y',strtotime($buypack->package_expiredate));
			$data['productname'] = $buypack->plan_title;
			$data['surface']=$managesite->surface;
			$data['surfacetext_iconcolor']=$managesite->surfacetext_iconcolor;
			$data['primary_color']=$managesite->primary_color;
			$data['primarytext_iconcolor']=$managesite->primarytext_iconcolor;
			$data['hyperlink']=$managesite->hyperlink;
			$data['bgtext_iconcolor']=$managesite->bgtext_iconcolor;

			  Mail::send('email.unsubscribe',['data'=>$data], function ($message) use ($data2) {
				$message->from($data2['emailfrom'],$data2['vchsitename']);
				$message->to($data2['email']);
                $message->subject('Subscription Canceled');
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
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',self::getServerName())->first();

		if(!empty(Session::get('userid')))
        {
			$packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();
			if(!$packageavailable->isEmpty()){
					$packageid ="";
					$buyid ="";
					$availablecount = 0;
					$total_credits = 0;
					$used_credits = 0;
					foreach($packageavailable as $pack){
						$total_credits += $pack->package_count;
						$used_credits += $pack->package_download;
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
							echo json_encode(["response"=>'Done',"stock"=>((!empty($stockinfo->stock))?$stockinfo->stock:0),'instock'=>'alreadydownload',"available_stock"=>$availablecount]);
						} else {
							echo json_encode(["response"=>'Done',"stock"=>((!empty($stockinfo->stock))?$stockinfo->stock:0),'instock'=>'yes',"available_stock"=>$availablecount]);
						}
					} else {
						echo json_encode(["response"=>'Done',"stock"=>0,'instock'=>'no',"available_stock"=>$availablecount]);
					}
				} else {
					$getdownloadres = DB::table('tbl_download')->where('user_id',Session::get('userid'))->where('video_id',$id)->where('site_id',$managesite->intmanagesiteid)->first();

                    if(!empty($getdownloadres)){
                        echo json_encode(["response"=>'Done',"stock"=>0,'instock'=>'alreadydownload',"available_stock"=>0]);
                    }else{
                        echo json_encode(["response"=>'Done',"stock"=>0,'instock'=>'no',"available_stock"=>0]);
                    }
				}
			} else {
				echo json_encode(["response"=>'Done',"stock"=>0,'instock'=>'no',"available_stock"=>0]);
			}
        } else {
			echo json_encode(["response"=>'No']);
        }
        exit;
    }

	public function imageAnimation($seo = ''){
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


		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',self::getServerName())->first();
		$response = DB::table('tbl_Video')->select("tbl_Video.*",DB::raw("(Select GROUP_CONCAT(np.VchSearchcategorytitle SEPARATOR ', ') from tbl_SearchcategoryVideoRelationship as np where np.IntVideoID = tbl_Video.IntId) as videotags "))->where("IntId",$seo)->first();

		$productid = Crypt::encryptString($response->IntId);
        $gender='';
        $skintone='';
        $category='';
        $id = $response->IntId;

        $video_detail = DB::table('tbl_Video')->leftjoin('tbl_Videotagrelations','tbl_Videotagrelations.VchVideoId','tbl_Video.IntId')->where('tbl_Video.IntId',$id)->first();
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

        if($response->EnumType=='I') {
            $data_type = "Image";
        } else {
            $data_type = "Video";
        }
        $size = getimagesize(public_path().'/'.$response->VchFolderPath.'/'.$response->VchVideoName);
        $diemension='';

        if(!empty($size)){
            $diemension=$size[0].'x'.$size[1];
        }

        $incartlist =  DB::table('tbl_wishlist')->where('tbl_wishlist.videoid',$id)->where('tbl_wishlist.userid',$userid)->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->whereNotNull('status')->first();

        if (!empty($incartlist)) {
            $cartstatus = 'out-cart';
            $imgname = $incartlist->img_name;
            if(!empty($incartlist->applied_bg)){
                $applied_bg = $incartlist->applied_bg;
            }else{
                $applied_bg = '';
            }
        } else {
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
			$data = ["seo_url" => $this->stringReplace($res->VchTitle)."-".$res->IntId];

			DB::table('tbl_Video')->where('IntId', $res->IntId)->update($data);
		}

		$oldstring = ["  ","(", ")", "?"," "];
		$newstring   = ["","", "", "","-"];

		return str_replace($oldstring, $newstring, $string);
	}

    public function favoritesData(Request $request){
            if(Session::get('userid')) {
                $managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
                $videoid = $request->id;
                $id = Crypt::decryptString($request->id);
                $userid = Session::get('userid');
                $date = date('Y-m-d H:i:s');
                $cartstatus = $request->cartstatus;

                if($cartstatus == 'Add'){
                    $checklist = DB::table('tbl_favorites')->where('fav_userid',$userid)->where('fav_siteid',$managesite->intmanagesiteid)->where('fav_videoid',$id)->first();

                    if(empty($checklist)){
                        $data = [
                            'fav_videoid'	=> $id,
                            'fav_siteid'	=> $managesite->intmanagesiteid,
                            'fav_userid' => $userid,
                            'fav_created_date'	=> $date,
                        ];

                        $lastinsetid = $this->HomeModel->favoritesdata($data);
                    }
                } elseif ($cartstatus == 'Remove') {
                    $this->HomeModel->DeleteFromfavorites($id,$managesite->intmanagesiteid,$userid);
                }

                $value = ['response' => 1];
            } else {
                $value = ['response'=>2];
    		}

			echo json_encode($value);
	}

	public function favorites(){
		$this->checklogin();
		$userid = Session::get('userid');
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
		$response =  DB::table('tbl_favorites')->leftjoin('tbl_Video','tbl_favorites.fav_videoid','tbl_Video.IntId')->where('tbl_favorites.fav_userid',$userid)->where('tbl_favorites.fav_siteid',$managesite->intmanagesiteid)->orderBy('fav_created_date','DESC')->paginate(10);
		$siteid=$managesite->intmanagesiteid;

		return view('/user-favorites',compact('response','siteid'));


	}

	public function deletefavorites(Request $request){
		$this->checklogin();
		$results = DB::table('tbl_favorites')->where('fav_id', $request->id)->delete();
		$value = array('response'=>1);
		echo json_encode($value);
		}

    public function change_background(Request $request){
			$managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
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

    public function datadetail(Request $request) {
			$gender='';
			$skintone='';
			$category='';
			$id = Crypt::decryptString($request->productid);
			$managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
			$video_detail = DB::table('tbl_Video')->leftjoin('tbl_Videotagrelations','tbl_Videotagrelations.VchVideoId','tbl_Video.IntId')->where('tbl_Video.IntId',$id)->first();
			$gender=DB::table('tbl_Tagtype')->where('Intid',$video_detail->VchGenderTagid)->first();
			$skintone=DB::table('tbl_Tagtype')->where('Intid',$video_detail->VchRaceTagID)->first();
			$category=DB::table('tbl_Tagtype')->where('Intid',$video_detail->VchCategoryTagID)->first();
			$backgrounds=DB::table('tbl_backgrounds')->select('bg_id','background_title')->get();

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
			$diemension='';
			if(!empty($video_detail)){
		    if(file_exists(public_path().'/'.$video_detail->VchFolderPath.'/'.$video_detail->VchVideoName)){
			$size = getimagesize(public_path().'/'.$video_detail->VchFolderPath.'/'.$video_detail->VchVideoName);


			$diemension=$size[0].'x'.$size[1];
			}
			}
			// print_r($skintone);
			// print_r($category);



			echo json_encode(array("gender"=>$gender,"skintone"=>$skintone,'tranparent'=>$tranparent,'category'=>$category,"type"=>$video_detail->EnumType,"size"=>$diemension,"backgrounds"=>$backgrounds));


		}

    public function showimage1($id, $imgs){
	$managesite = DB::table('tbl_managesite')->where('txtsiteurl',self::getServerName())->first();
	$img="change_background/".$id.'/'.''.$imgs;
	$info = pathinfo( $imgs );
    $returnimg="change_background/".$id.'/'.''.$imgs;
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
		$returnthumb=strtolower(preg_replace('/\W/is', "_", "$img $w $h"));
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
            $filename=$img;
			$new_width=(int)@$w;
			$new_height=(int)@$h;
			$lst=GetImageSize($filename);

			$image_width=$lst[0];
			$image_height=$lst[1];
			$image_format=$lst[2]; //print_R($lst);
			 if($image_width > 1920){
	           $withoutExt = substr($imgs, 0, -3);
			    $newimg = $withoutExt.'jpg';

			    $returnthumb=strtolower(preg_replace('/\W/is', "_", "$newimg $w $h"));
				$returnimg="change_background/".$id.'/'.''.$newimg;
            }
			//echo 'h'; exit;
			if(!file_exists(DIR_CACHE.$returnthumb)){

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

			//$stamp = imagecolorallocate($stamp, 255, 255, 0, 75);
			$sx = imagesx($stamp);
			$sy = imagesy($stamp);

			$imageWidth=imagesx($image);
			$imageHeight=imagesy($image);

			$logoWidth=imagesx($stamp);
			$logoHeight=imagesy($stamp);
			$logoImage = $stamp;
			$image = $image;

             if($imageWidth > 1024){
                 $marge_right = 0;
			     $marge_bottom = 0;
                 $dst_width=$imageWidth;
				 $dst_height=$imageHeight;
			 } else {
				 $marge_right = 50;
			     $marge_bottom = 50;
				 $dst_width=($imageWidth*50)/100;
				 $dst_height=($imageHeight*50)/100;
			 }

			$imgpath=public_path().'/image_cache/'.$thumb;
		    $reurnimgpath=public_path().'/image_cache/'.$returnthumb;
			$imgfullpath=public_path().'/'.$img;
			$tempreurnimgpath=public_path().'/image_cache/testing.jpg';
            if($imageWidth > 1920) {
                 exec("composite -dissolve ".$vchtransparency."% -gravity center -geometry '".$dst_width."'x'".$dst_height."'+".$marge_right."+".$marge_bottom." '".$stamp2."' '".$imgfullpath."' '".$tempreurnimgpath."'");
                 exec("convert $tempreurnimgpath -resize 50% $tempreurnimgpath");
                 exec("convert -strip -interlace Plane -gaussian-blur 0.05 -quality 85% $tempreurnimgpath $reurnimgpath");
                 if (file_exists($tempreurnimgpath))
                   {
                      unlink($tempreurnimgpath);
                   }
             } else {
                exec("composite -dissolve ".$vchtransparency."% -gravity center -geometry '".$dst_width."'x'".$dst_height."'+".$marge_right."+".$marge_bottom." '".$stamp2."' '".$imgfullpath."' '".$reurnimgpath."'");
			 }
		}
		}

		header("Content-type:image/jpeg");
		header('Content-Disposition: attachment; filename="'.$returnimg.'"');
		readfile(DIR_CACHE.$returnthumb);
}


}

    public static function getapidetail(){
	$response = DB::table('tblapidetail')->where('id','1')->first();
		return $response;

}

    public static function managesite2(){
	$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',self::getServerName())->first();
		return $managesite;

}

    public function testfunction(){
		$getresponse = $this->HomeModel->getautorenewpackage();
		$getapidetail = DB::table('tblapidetail')->where('id','1')->first();

		foreach($getresponse as $res){
			if($res->package_subscription=='Y' || $res->package_id!='0'){
			Stripe\Stripe::setApiKey($getapidetail->stripe_secret);
			$response = \Stripe\Subscription::update(
			  $res->package_renewid
			);
			$response = $response->jsonSerialize();

			$invoice_number=$response['latest_invoice'];
			 Stripe\Stripe::setApiKey($getapidetail->stripe_secret);
			$invoice =	Stripe\Invoice::retrieve($response['latest_invoice']);
			$invoiceresponse = $invoice->jsonSerialize();
			if ($response['status'] == 'active' || $response['status'] == 'succeeded') {
			    if ($response['current_period_end'] > $res->package_start_time) {
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
                        "strip_payment_type"=>'Renew Payment',
                        "strip_package_type"=>$res->package_type,
                        "create_at"=>date('Y-m-d H:i:s'),
                    ];

                    $paymentlastid = $this->HomeModel->paymentinfo_insert($paymentdata);

                    $managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('intmanagesiteid',$res->site_id)->first();
                    $userinfo=$this->HomeModel->UserData($res->package_userid);
                    $data2 = [
                        'email'	=> $managesite->vchemailfrom,
                        'emailfrom'	=> $userinfo->vchemail,
                        'vchsitename'	=> $managesite->vchsitename,
                    ];

                    if($res->package_type == 'Y') {
                        $data['package_title']=strip_tags($res->plan_title);
                        $data['purchase_type']= 'Anually';
                        $data['strip_amount'] =  number_format($response['plan']['amount'] / 100, 2);
                        $expiry_date = date('M d, Y', strtotime("+".$res->plan_time." years"));
                    } elseif ($res->package_type == 'M') {
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
				$dataarr = [
                    "package_subscription"=> 'C',
                    "status"=> 'D',
			    ];

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
		} elseif ($res->package_subscription=='C') {
			$dataarr = ['status' => 'D'];
            $this->HomeModel->UpdateBuyPackage($res->package_id,$dataarr);
		}

		\Log::info("Package Renew Cron");
    }
}

    private function calculateDiscount($itemPrice, $coupon)
    {
        if(!$coupon) {
            return $itemPrice;
        }

        if($coupon->discount_type == 'P') {
            $discount = $itemPrice - $itemPrice * $coupon->amount / 100;
        } else {
            $discount = $itemPrice - $coupon->amount;
        }

        return $discount < 0 ? 0 : $discount;
    }

    private function calculateWebsiteWideDiscounts($itemPrice, $websiteWideCoupons, $returnAsArray = true)
    {
        $discount = $itemPrice;
        $discountText = '';

        foreach($websiteWideCoupons as $websiteWideCoupon) {
            if($discountText !== '') {
                $discountText .= '<br>';
            }

            if($websiteWideCoupon->discount_type == 'P') {
                $discount =  $discount - $itemPrice * $websiteWideCoupon->amount / 100;
                $discountText .= "Coupon ".$websiteWideCoupon->amount.'% off';
            } else {
                $discount -= $websiteWideCoupon->amount;
                $discountText .= "Coupon $".$websiteWideCoupon->amount.' off';
            }
        }

        $discount =  $discount < 0 ? 0 : $discount;

        if(!$returnAsArray) {
            return $discount;
        }

        return [$discount, $discountText];
    }

    private function createCoupon($coupon)
    {
        $couponData = [
            'duration' => 'once',
            'id' => $coupon->coupon,
        ];

        if($coupon->discount_type === 'P') {
            $couponData['percent_off'] = $coupon->amount;
        } else {
            $couponData['amount_off'] = $coupon->amount;
            $couponData['currency'] = 'usd';
        }

        try {
            \Stripe\Coupon::retrieve($coupon->coupon);
        } catch(\Exception $e) {
            \Stripe\Coupon::create($couponData);
        }
    }

    private function createWebsiteWideCoupons($coupons)
    {
        foreach($coupons as $coupon) {
            $this->createCoupon($coupon);
        }
    }

    /**
     * @param $managesite
     * @return string
     */
    protected function getSecretKey($managesite): string
    {
        if ($managesite->intmanagesiteid == '1') {
            return "6LflkxcaAAAAAMlSzq_xPbwMzy7zwypu602wScoi";
        } elseif ($managesite->intmanagesiteid == '17') {
            return "6Lc5WyUaAAAAAJNPbMjbAL1ehCbYb2oUTu0oL0RT";
        } elseif ($managesite->intmanagesiteid == '22') {
            return "6Le8WyUaAAAAAFlwhC61NiT21QKo8s-QbANDk4Jg";
        }

        return '';
    }

    /**
     * @return mixed
     */
    protected function getDomainId()
    {
        $url = app()->isLocal() ? 'dev.fox-ae.com' : str_replace('https://', '', url('/'));

        $siteInfo = DB::table('tbl_managesite')
            ->where('txtsiteurl', $url)
            ->first();

        return $siteInfo->intmanagesiteid;
    }

    protected function getCartWebsiteWideCoupons()
    {
        return $this->websiteWideCoupons(1);
    }

    protected function getPricingWebsiteWideCoupons()
    {
        return $this->websiteWideCoupons(2);
    }

    protected function websiteWideCoupons($place)
    {
        return DB::table('tbl_discount')
            ->where('type', 'W')
            ->where(function ($query) {
                $query->where('domain_id', $this->getDomainId())->orWhere('domain_id', 'A');
            })
            ->where('status', 'A')
            ->whereDate('end_date', '>=', now())
            ->where('place', 'like', '%'.$place.'%')
            ->get();
    }
}
