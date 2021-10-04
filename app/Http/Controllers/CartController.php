<?php
namespace App\Http\Controllers;
use http\Params;
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
use Intervention\Image\ImageManagerStatic as Image;
class CartController extends Controller {

	public function __construct(HomeModel $HomeModel) {
        $this->HomeModel = $HomeModel;

    }

	public function checklogin(){
		if (!Session::get('userid')) {
			return redirect('/');
        }
	}

	public function cartlogin(Request $request){
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		$siteid=$managesite->intmanagesiteid;
		$email = $request->email;
		$password = md5($request->password);
		$userLogin = $this->HomeModel->loginData($email,$password,$siteid);

		if(!empty($userLogin->intuserid)){
			if ($userLogin->enumstatus == 'A') {
				$date = date('Y-m-d H:i:s');
				$data = ['lastlogin' => $date];
				$this->HomeModel->updateuserdetails($userLogin->intuserid,$data);
				Session::put('userid',$userLogin->intuserid);
				$cartcount = DB::table('tbl_wishlist')->where('userid',$userLogin->intuserid)->where('status','cart')->where('siteid',$siteid)->count();
				$packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',$userLogin->intuserid)->whereDate('package_expiredate','>',date('Y-m-d'))->get();
				$package = null;

				foreach($packageavailable as $packageavailables){
					if($packageavailables->package_download < $packageavailables->package_count){
					    $package=$packageavailables->package_count-$packageavailables->package_download;
					}
				}

                $pvalue = $package ? 'yes' : 'no';

                $package = null;

                if (Session::get('userid')) {
                    $userid=Session::get('userid');
                    $userdetail = DB::table('tbluser')->where('intuserid',$userid)->first();
                    $packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',$userid)->whereDate('package_expiredate','>',date('Y-m-d'))->get();

                    foreach($packageavailable as $packageavailables){
                        if($packageavailables->package_download < $packageavailables->package_count){
                            $package=$packageavailables;
                            $packageid = $packageavailables->package_id;
                            $buyid = $packageavailables->buy_id;
                        }
                    }
                }

                $availablecredit = 0;

                if($package){
                    $availablecredit = $package->package_count-$package->package_download;
                }

                $cartimages=DB::table('tbl_wishlist')->where('userid',$userid)->whereNotNull('status')->where('siteid',$managesite->intmanagesiteid)->get();

                $cartvalue=0;

                if (!empty($cartimages)) {
                    foreach($cartimages as $cartimage){
                        $videodetail=DB::table('tbl_Video')->where('IntId',$cartimage->videoid)->first();
                        $stock=$videodetail->stock_category;
                        $content=$videodetail->content_category;
                        if(!empty($packageid)){
                            $stockinfo = DB::table('tbl_buypackagestock')->where('buypackage_id',$packageid)->where('plan_id',$buyid)->where('stocktype_id',$stock)->where('contentcat_id',$content)->first();

                            if(!empty($stockinfo)){
                                $cartvalue += $stockinfo->stock;
                            }
                        }
                    }
                }

                $value = ["id"=> $userLogin->intuserid,'response' => 1,'name' => $userLogin->vchfirst_name, 'count' => $cartcount,'pack' => $package,'val' => $pvalue,'logo' => $managesite->vchprofileicon, 'carticon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 3c0 .55.45 1 1 1h1l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h11c.55 0 1-.45 1-1s-.45-1-1-1H7l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.67-1.43c-.16-.35-.52-.57-.9-.57H2c-.55 0-1 .45-1 1zm16 15c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path></svg>', 'verifystatus' => $userLogin->verifystatus, 'availablecredit' => $availablecredit, 'cartvalue' => $cartvalue];
            } else {
				$value = ['response' => 0];
            }
        } else {
			$value = ['response' => 2];
        }

        return response()->json($value);
    }

	public function cartregister(Request $request){
		 $this->validate($request, [
           'captcha' => 'required|captcha'
        ],
        ['captcha.captcha'=>'invalid captcha code.']);


		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();

		$checkemail=$this->HomeModel->checkmail($request->email,$managesite->intmanagesiteid);
		if(!empty($checkemail)){
		$value = array('response'=>3);
		echo json_encode($value);
		}else{
		$date = date('Y-m-d H:i:s');
			$data = array(
				'vchfirst_name'	=> $request->first_name,
				'vchemail'		=> $request->email,
				'vchsiteid'		=> $managesite->intmanagesiteid,
				'vchpassword'	=> md5($request->password),
				'lastlogin'		=> $date,
				'enumstatus'	=> 'A',
				'created_date'	=> $date,
				'updated_date'	=> $date,

			);
			$lastinsetid=$this->HomeModel->submitData($data);
			//$username=$request->email;
			$data2 = array(
				'email'	=> $request->email,
				'emailfrom'	=> $managesite->vchemailfrom,
			);

			$data['vchsitename'] = $managesite->vchsitename;
			$data['siteurl'] =  "https://".$managesite->txtsiteurl;
			$data['vlogo'] =  "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo;
			$data['vchfirst_name'] = $request->first_name;
			$data['userid'] = Crypt::encryptString($lastinsetid);
			/*
			  Mail::send('email.registration',['data'=>$data], function ($message) use ($data2) {
				$message->from($data2['emailfrom'],'noreply');
				$message->to($data2['email']);
                $message->subject('New Registration');
                });
				*/
				Mail::send('email.emailverify',['data'=>$data], function ($message) use ($data2) {
				$message->from($data2['emailfrom'],'noreply');
				$message->to($data2['email']);
                $message->subject('Verify Email Account');
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
				//setcookie('userid', $Userdetail->intuserid, time() - 3600);
				Session::put('userid',$Userdetail->intuserid);
				$value = array( "id"=> $Userdetail->intuserid,'response'=>1);
				echo json_encode($value);

			}else{
				$value = array('response'=>0);
				echo json_encode($value);


			}
		}else{
			$value = array('response'=>2);
				echo json_encode($value);



		}
	}

	}

	public function allcart_background(Request $request){

		if(empty(Session::get('userid'))){
				$session_id = Session::getId();
			}else{
				$session_id = Session::get('userid');
			}
			$path = public_path().'/change_background/'.$session_id;
				//File::isDirectory($path) or
				if (!Is_Dir($path)){
					mkdir($path, 0777);
					//mkdir($path.'/new', 0777);
			}
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		$response = DB::table('tbl_wishlist')->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId')->where('tbl_wishlist.userid',$session_id)->where('tbl_wishlist.siteid',$managesite->intmanagesiteid)->where('tbl_wishlist.status','cart')->where('tbl_Video.transparent','Y')->get();
		 foreach($response as $res){
			 $bg=$request->img;
			 	$bgresponse = DB::table('tbl_backgrounds')->where('bg_id',$bg)->first();
			$mainimg="upload/videosearch/".$res->IntId."/".$res->VchVideoName;
			$imagename=$bg.'_'.$res->VchVideoName;
			header('Content-Type: image/png');


				$imgs2 = $path.'/'.$imagename;
				//$cmd = '-background "'.$bg.'" -flatten';
				$stamp = imagecreatefrompng($mainimg);
				$im2 = imagecreatefrompng('images/'.$bgresponse->background_img);
				$img=Image::make($im2);
				$size = getimagesize($mainimg);
				$diemension=$size[0].'x'.$size[1];
				$img->resize($size[0],$size[1])->save('background/'.$bgresponse->background_img);
				$marge_right = 0;
				$marge_bottom = 0;
				$sx = imagesx($stamp);
				$sy = imagesy($stamp);
				//$im = imagecreatefrompng('imagick/black_with_effect.png');
				$im = imagecreatefrompng('background/'.$bgresponse->background_img);
				// Copy the stamp image onto our photo using the margin offsets and the photo
				// width to calculate positioning of the stamp.
				imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($stamp) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

				// Output and free memory
				header('Content-type: image/png');
				//imagepng($im,'img.png');
				imagepng($im,$imgs2);
			if(!empty($res->id)){
					$dataarr = array(
						"applied_bg"=> $bgresponse->background_title,
						"img_url"=> '/change_background/'.$session_id,
						"img_name"=> $imagename,

				);
					DB::table('tbl_wishlist')->where('id', $res->id)->update($dataarr);



				}
			$arrayresponse[] = array($res->IntId=>'showimg/'.$session_id.'/'.$imagename.'?v='.time());
			//return $session_id.'/new/'.$imagename.'?v='.time();
		 }
		echo json_encode(array("response"=>$arrayresponse));


	}

	public function cart_background2(Request $request){
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		if(empty(Session::get('userid'))){
			$session_id = Session::getId();
		}else{
			$session_id = Session::get('userid');
		}
		$path = public_path().'/change_background/'.$session_id;
			//File::isDirectory($path) or
			if (!Is_Dir($path)){
				mkdir($path, 0777);
				//mkdir($path.'/new', 0777);
		}
		$bg=$request->img;

		if(!empty($request->src)){
		$src=$request->src;
		$srcarr=explode('?',$request->src);
		$srcarr1=$srcarr[0];
		$srcarr2=explode('/',$srcarr1);

		$img="upload/videosearch/".$srcarr2[2]."/".$srcarr2[4];
		$imagename=$bg.'_'.$srcarr2[4];
		}else{
			$response = DB::table('tbl_Video')->where('IntId',$request->id)->first();
			$img=$response->VchFolderPath.'/'.$response->VchVideoName;
			$imagename=$bg.'_'.$response->VchVideoName;
		}
				header('Content-Type: image/png');

				//$color = '#ff0000';


				$imgs2 = $path.'/'.$imagename;
				//$cmd = '-background "'.$bg.'" -flatten';
				$cmd = '-background '.$bg.' -flatten';
				exec("convert $img $cmd $imgs2");
				if(!empty($request->wishlistid)){
					$dataarr = array(
						"applied_bg"=> $bg,
						"img_url"=> '/change_background/'.$session_id,
						"img_name"=> $imagename,

				);
					DB::table('tbl_wishlist')->where('id', $request->wishlistid)->update($dataarr);



				}

			return 'showimg/'.$session_id.'/'.$imagename.'?v='.time();
		}

	public function cart_background(Request $request){
		$full_path =$_SERVER['DOCUMENT_ROOT'].'/public/image_cache/';
		$managesite = DB::table('tbl_managesite')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		if(empty(Session::get('userid'))){
			$session_id = Session::getId();
		}else{
			$session_id = Session::get('userid');
		}
		$path = public_path().'/change_background/'.$session_id;
			//File::isDirectory($path) or
			if (!Is_Dir($path)){
				mkdir($path, 0777);
				//mkdir($path.'/new', 0777);
		}
		//Storage::put('flower.jpg', file_get_contents('https://dev.fox-ae.com/showimage/3701/1/org1588022082.png'));
		$bg=$request->img;
		$bgresponse = DB::table('tbl_backgrounds')->where('bg_id',$bg)->first();
		 if(!empty($request->src)){
		$src=$request->src;
		$srcarr=explode('?',$request->src);
		$srcarr1=$srcarr[0];
		$srcarr2=explode('/',$srcarr1);

		$mainimg="upload/videosearch/".$srcarr2[2]."/".$srcarr2[4];
		$imagename=$bg.'_'.$srcarr2[4];
		}else{
			$response = DB::table('tbl_Video')->where('IntId',$request->id)->first();
			$mainimg=$response->VchFolderPath.'/'.$response->VchVideoName;
			$imagename=$bg.'_'.$response->VchVideoName;

		}
		$w=0;
	    $h=0;
			header('Content-Type: image/png');
			if(!file_exists($path.'/'.$imagename)){
				$color = '#ff0000';

				$imgs2 = $path.'/'.$imagename;
				$stamp = imagecreatefrompng($mainimg);
				$im2 = imagecreatefrompng('images/'.$bgresponse->background_img);
				$img=Image::make($im2);
				$size = getimagesize($mainimg);
				$diemension=$size[0].'x'.$size[1];
				$img->resize($size[0],$size[1])->save('background/'.$bgresponse->background_img);
				$marge_right = 0;
				$marge_bottom = 0;
				$sx = imagesx($stamp);
				$sy = imagesy($stamp);
				$im = imagecreatefrompng('background/'.$bgresponse->background_img);
				imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($stamp) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
				header('Content-type: image/png');
				imagepng($im,$imgs2);
				}
				$date = date('Y-m-d H:i:s');
				if(!empty($request->id)){
					$response_wishlist = DB::table('tbl_wishlist')->where('videoid', $request->id)->where('siteid', $managesite->intmanagesiteid)->where('userid', $session_id)->where('status', 'cart')->first();
					if(!empty($response_wishlist)){
					$dataarr = array(
						"applied_bg"=> $bgresponse->background_title,
						"img_url"=> '/change_background/'.$session_id,
						"img_name"=> $imagename,

				);
					DB::table('tbl_wishlist')->where('videoid', $request->id)->where('siteid', $managesite->intmanagesiteid)->where('userid', $session_id)->where('status', 'cart')->update($dataarr);



				}else{
				$data = array(
				'videoid'	=> $request->id,
				'siteid'	=> $managesite->intmanagesiteid,
				'userid'=> $session_id,
				"applied_bg"=> $bgresponse->background_title,
				"img_url"=> '/change_background/'.$session_id,
				"img_name"=> $imagename,
				'created_date'	=> $date,
			);
			$lastinsetid=$this->HomeModel->wishlistdata($data);


				}

			$res = array('url'=>'showimg/'.$session_id.'/'.$imagename.'?v='.time(),"apllied_bg"=>$bgresponse->background_title);
			echo json_encode($res);
			//return 'showimg/'.$session_id.'/'.$imagename.'?v='.time();
			}
		}

	public function chck_uncheckcart(Request $request){
		$arr=explode(',',$request->id);
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		$siteid=$managesite->intmanagesiteid;
		$package='';
		$stockinfo='';
		$totalitems='';
		$cartvalue=0;
		$credit=0;
		$checkloginuser='';

		if(!empty(Session::get('userid'))){
            $packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid',$userid)->whereDate('package_expiredate','>',date('Y-m-d'))->get();
            $userid = Session::get('userid');
            $package = null;

            foreach($packageavailable as $packageavailables){
                if($packageavailables->package_download < $packageavailables->package_count){
                    $package=$packageavailables;
                }
            }

            if($package) {
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
                ->whereIn('tbl_wishlist.id', $arr)
                ->where('tbl_buypackage.status','A')

                ->whereDate('tbl_buypackage.package_expiredate','>',date('Y-m-d'))
                ->groupBy('tbl_Video.IntId')
                ->get();

                        $totalitems=count($response);

                    foreach($response as $res){
                        $credit += $res->stock;
                    }
                $cartvalue = $credit;
                $carticon= '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 3c0 .55.45 1 1 1h1l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h11c.55 0 1-.45 1-1s-.45-1-1-1H7l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.67-1.43c-.16-.35-.52-.57-.9-.57H2c-.55 0-1 .45-1 1zm16 15c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path></svg>';
                $value = array('cartvalue'=>$cartvalue.' Credits','cartcreditvalue'=>$cartvalue,'totalitems'=>$totalitems,'carticon'=>$carticon);
            } else{
                $cartvalue = 0;
                $coupon = Session::get('cart-coupon') ? Session::get('cart-coupon')['coupon'] : null;
                $userid = Session::getId();

                $response =  DB::table('tbl_wishlist')->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
                    ->join("tblstock",function($join){
                         $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
                         ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
                    })
                ->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
                ->whereIn('tbl_wishlist.id', $arr)->groupBy('tbl_Video.IntId')
                ->get();

                $totalitems = count($response);

                if($totalitems > 0){
                    foreach($response as $res){
                        $stock = $res->stock;
//                        $tiers = $coupon ? explode(',', $coupon->tier) : [];
//                        if($coupon && in_array($res->content_category, $tiers)) {
//                            $stock = $this->calculateDiscount($stock, $coupon);
//                        }

                        $credit += $stock;
                    }

                    $cartvalue = $credit/$res->conversion_rate;
                }else{
                    $cartvalue=0;
                }
                $carticon= '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 3c0 .55.45 1 1 1h1l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h11c.55 0 1-.45 1-1s-.45-1-1-1H7l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.67-1.43c-.16-.35-.52-.57-.9-.57H2c-.55 0-1 .45-1 1zm16 15c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path></svg>';
                $value = array('cartvalue'=>'$'.number_format($cartvalue, 2),'totalitems'=>$totalitems,'carticon'=>$carticon);
                }
			}
        else{
				$credit=0;
				$cartvalue=0;

			$userid=Session::getId();
				$response =  DB::table('tbl_wishlist')->leftjoin('tbl_Video','tbl_wishlist.videoid','tbl_Video.IntId','tbl_Video.content_category')
					->join("tblstock",function($join){
						 $join->on("tblstock.stocktype_id","=","tbl_Video.stock_category")
						 ->on("tblstock.contentcat_id","=","tbl_Video.content_category");
					})
				->leftjoin('tbl_plan','tbl_plan.plan_id','tblstock.plan_id')
				->whereIn('tbl_wishlist.id', $arr)->groupBy('tbl_Video.IntId')
				->get();

							$totalitems=count($response);

				if($totalitems>0){
						foreach($response as $res){
								$credit +=$res->stock;
						}

                    $cartvalue = $res->conversion_rate;
				}else{
					$cartvalue=0;

				}
				$carticon= '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 3c0 .55.45 1 1 1h1l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h11c.55 0 1-.45 1-1s-.45-1-1-1H7l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.67-1.43c-.16-.35-.52-.57-.9-.57H2c-.55 0-1 .45-1 1zm16 15c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path></svg>';
                $value = array('cartvalue'=>'$'.number_format($cartvalue, 2),'totalitems'=>$totalitems,'carticon'=>$carticon);
		    }

        echo json_encode($value);
    }

    public function fileTodownload1(Request $request){
        $this->checklogin();

        $managesite = DB::table('tbl_managesite')
            ->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')
            ->where('txtsiteurl',$_SERVER['SERVER_NAME'])
            ->first();

        $path = public_path().'/zip/'.Session::get('userid');
        $fileName = 'zip/cartArchive'.Session::get('userid').'.zip';

        File::deleteDirectory($path);
        File::delete(public_path().'/'.$fileName);

        $arr=explode(',', $request->downloadid);

        $packageavailable = DB::table('tbl_buypackage')
            ->where('status','A')
            ->where('package_userid', Session::get('userid'))
            ->whereDate('package_expiredate', '>', date('Y-m-d'))
            ->get();

        $cartvalue = (int) $request->cartvalue;

        $cartCreditsLeft = $cartvalue;

        foreach($packageavailable as &$package) {
            $creditsInCurrentPackage = $package->package_count - $package->package_download;

            if($creditsInCurrentPackage >= $cartCreditsLeft){
                DB::table('tbl_buypackage')
                    ->where('package_id', $package->package_id)
                    ->update(['package_download' => $package->package_download + $cartCreditsLeft]);

                $package->package_download = $package->package_download + $cartCreditsLeft;

                $cartCreditsLeft = 0;

                break;
            } else {
                DB::table('tbl_buypackage')
                    ->where('package_id', $package->package_id)
                    ->update(['package_download' => $package->package_count]);

                $package->package_download = $package->package_count;

                $cartCreditsLeft = $cartCreditsLeft - $creditsInCurrentPackage;
            }
        }

        if($cartCreditsLeft !== 0) {
            return 1;
        }

        if(!empty($arr)){
            for($i=0; $i < count($arr); $i++){
                if(!data_get($arr, $i)) {
                    continue;
                }

                $id = Crypt::decryptString($arr[$i]);

                $getdownloadresponse = DB::table('tbl_download')->where('user_id',Session::get('userid'))->where('video_id',$id)->first();

                if($request->type == 'direct') {
                    $data = [
                        "video_id"=>$id,
                        "user_id"=>Session::get('userid'),
                        "site_id"=>$managesite->intmanagesiteid,
                        "create_at"=>date("Y-m-d H:i:s")
                    ];

                    $this->HomeModel->DownloadData($data);

                    $this->DownloadFileServer($id);
                } else {
                    $packageavailable = DB::table('tbl_buypackage')->where('status','A')->where('package_userid', Session::get('userid'))->whereDate('package_expiredate','>',date('Y-m-d'))->get();

                    $videoinfo = DB::table('tbl_Video')->select('content_category','stock_category')->where('IntId',$id)->first();

                    if($packageavailable->isEmpty()){
                        return 1;
                    }

                    $packageid = "";
                    $packageDownloadcount ="";
                    $buyid ="";
                    $pack_count ="";
                    $availablecount=0;
                    $onepackcreditcount=0;
                    $remainingcount=0;

//                    foreach($packageavailable as $pack){
//                        $onepackcreditcount = $pack->package_count - $pack->package_download;
//
//                        if($pack->package_download < $pack->package_count && $cartvalue <= $onepackcreditcount){
//                            $availablecount += $pack->package_count;
//                            $remainingcount += $pack->package_count - $pack->package_download;
//
//                            if(empty($packageid)){
//                                $packageid = $pack->package_id;
//                                $buyid = $pack->buy_id;
//                                $packageDownloadcount = $pack->package_download;
//                                $pack_count=$pack->package_count;
//                            }
//                        }
//                    }

//                    if(!empty($packageid)){
                        $stockinfo = DB::table('tbl_buypackagestock')->leftjoin('tbl_plan','tbl_plan.plan_id','tbl_buypackagestock.plan_id')->where('tbl_buypackagestock.buypackage_id', $packageid)->where('tbl_buypackagestock.plan_id', $buyid)->where('tbl_buypackagestock.stocktype_id', $videoinfo->stock_category)->where('tbl_buypackagestock.contentcat_id',$videoinfo->content_category)->first();

//                        if(!empty($stockinfo)){
                            $data = [
                                "video_id" => $id,
                                "user_id" => Session::get('userid'),
                                "site_id" => $managesite->intmanagesiteid,
                                "create_at" => date("Y-m-d H:i:s")
                            ];
                            $this->HomeModel->DownloadData($data);
                            /* One Check pending Start date or end date  */

                            $this->DownloadFileServer($id);
//                        }
//                    }
                }
            }
        }
    }

	public function DownloadFileServer($id){
		$managesite = DB::table('tbl_managesite')
			->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')
			->where('txtsiteurl',$_SERVER['SERVER_NAME'])
			->first();

		$response = DB::table('tbl_Video')
			->where('IntId',$id)
			->first();

		$path = public_path().'/zip/'.Session::get('userid');

		if (!Is_Dir(public_path().'/zip')){
			mkdir(public_path().'/zip', 0777);
		}

		if (!Is_Dir($path)){
			mkdir($path, 0777);
		}

		$wishlist_response = DB::table('tbl_wishlist')
			->where('videoid',$id)
			->where('userid',Session::get('userid'))
			->where('siteid',$managesite->intmanagesiteid)
			->first();

		if(!empty($wishlist_response)){
			if(!empty($wishlist_response->applied_bg)){
				if($wishlist_response->applied_bg!='Transparent'){
					copy(public_path().'/'.$wishlist_response->img_url.'/'.$wishlist_response->img_name, $path.'/'.$wishlist_response->img_name);
				}else{
					copy(public_path().'/'.$response->VchFolderPath.'/'.$response->VchVideoName, $path.'/'.$response->VchVideoName);

				}
			}else{
				copy(public_path().'/'.$response->VchFolderPath.'/'.$response->VchVideoName, $path.'/'.$response->VchVideoName);
			}
		}
		$this->RemoveFromWishlist($id);

	}
	public function RemoveFromWishlist($id){
		$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('txtsiteurl',$_SERVER['SERVER_NAME'])->first();
		$this->HomeModel->DeleteFromWishlist($id,$managesite->intmanagesiteid,Session::get('userid'));
	}



	public function imageresize(){

		// if(end(explode('.', $filename)), $exts){




		// }
	$stamp = imagecreatefrompng('imagick/change.png');
	$im2 = imagecreatefrompng('imagick/unnamed.png');
	//$im2 = imagecreatefromjpeg('background/transparent.jpg');
	$img=Image::make($im2);
	$size = getimagesize('imagick/change.png');
	$diemension=$size[0].'x'.$size[1];
	$img->resize($size[0],$size[1])->save('imagick/unnamed.png');
	$marge_right = 0;
		$marge_bottom = 0;
		$sx = imagesx($stamp);
		$sy = imagesy($stamp);
		//$im = imagecreatefrompng('imagick/black_with_effect.png');
		$im = imagecreatefrompng('imagick/unnamed.png');
		// Copy the stamp image onto our photo using the margin offsets and the photo
		// width to calculate positioning of the stamp.
		imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($stamp) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

			$stamp2 = imagecreatefrompng('imagick/1553250678.png');

			$vchtransparency = 7 * 10;

			$image = $im;
			//$im = $image;
		// Set the margins for the stamp and get the height/width of the stamp image
			$marge_right = 10;
			$marge_bottom = 10;
			//$stamp = imagecolorallocate($stamp, 255, 255, 0, 75);
			$sx = imagesx($stamp2);
			$sy = imagesy($stamp2);

			$imageWidth=imagesx($image);
			$imageHeight=imagesy($image);

			$logoWidth=imagesx($stamp2);
			$logoHeight=imagesy($stamp2);
			$logoImage = $stamp2;
			$image = $image;

			imagecopymerge($image, $logoImage, ($imageWidth-$logoWidth)/2,  ($imageHeight-$logoHeight)/2,0, 0,$logoWidth,$logoHeight,$vchtransparency);
		// Output and free memory
		header('Content-type: image/png');
		//imagepng($im,'img.png');
		imagepng($im);


	}

	public function imageresize2(){
	$img='images/1608539999.png';
	$stamp = imagecreatefrompng('imagick/change.png');
	//$im2 = imagecreatefrompng('images/1608539023.png');
	//$im = imagecreatefromjpeg('imagick/download.jpg');
	//$img=Image::make($im2);
	//$newFileName = time();
	$size = getimagesize('imagick/change.png');
//	$diemension=$size[0].'x'.$size[1];
	$diemension='1080x1080';

	shell_exec("convert $img -resize $diemension\! $img");
	 //  $tmp = imageResize($im2,$size[0],$size[1]);
        //        imagepng($tmp,'images/'. $newFileName. ".png");
	//$img->resize($size[0],$size[1], function ($constraint) {$constraint->aspectRatio();})->save('images/1608539999-1.png');
        $im = imagecreatefrompng('images/1608539999.png');
	$marge_right = 0;
    $marge_bottom = 0;
    $sx = imagesx($stamp);
    $sy = imagesy($stamp);

// Copy the stamp image onto our photo using the margin offsets and the photo
// width to calculate positioning of the stamp.
imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

// Output and free memory
header('Content-type: image/png');
//imagepng($im,'img.png');
imagepng($im);


	}

	public function imageresize3(){
		$filename='images/1608539023.png';
	$image = $filename;

$image_name = rand(111111, 888999)*time() .'A.png';
$thumb_name = rand(111111, 888999)*time() .'A.png';
$destinationPath = public_path('/images');

//$image->move($destinationPath, $image_name);
$orgImgPath = $filename;
$thumbPath = $filename;
shell_exec("convert $orgImgPath -resize 1928x1080\! $thumbPath");


	}

	public function updatedata(){
		$data=DB::table('tbl_payment')->get();
		foreach($data as $datas){
		$payment_res =DB::table('tbl_payment')->where('payment_id',$datas->payment_id)->first();
			$packagedata = [
					"strip_packagename"=>$datas->package_name,
				];
			print_r($packagedata);
		DB::table('tbl_payment')->where('payment_id', $datas->payment_id)->update($packagedata);
		}

	}

    private function calculateDiscount($itemPrice, $coupon, $websiteWideCoupons)
    {
        $discount = 0;

        if($coupon->discount_type == 'P') {
            $discount = $itemPrice - $itemPrice * $coupon->amount / 100;
        } else {
            $discount = $itemPrice - $coupon->amount;
        }

        foreach($websiteWideCoupons as $websiteWideCoupon) {
            if($coupon->discount_type == 'P') {
                $discount = $discount - $discount * $websiteWideCoupon->amount / 100;
            } else {
                $discount -= $websiteWideCoupon->amount;
            }
        }

        return $discount;
    }
}

?>
