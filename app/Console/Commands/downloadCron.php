<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Home\HomeModel;
use File;
use Mail;
use Stripe; 
class downloadCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Images list ';

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
	$response = DB::table('tbl_download')->select('tbl_download.*','tbl_Video.VchResizeimage','tbl_Video.VchVideothumbnail','tbl_Video.EnumType','tbl_Video.VchTitle','tbl_Video.intsetdefault')->leftjoin("tbl_Video","tbl_download.video_id","tbl_Video.IntId")->where('tbl_download.download','Today')->get();
		//->where('tbl_download.user_id','24')
		$userid = [];
		$getdownloadinfo = [];
		foreach($response as $res){
			
			if(!in_array($res->user_id, $userid))
			{
			  $userid[] = $res->user_id;
			}
			$data = [
				"download"=>'Yesterday'
			];
			$getdownloadinfo[$res->user_id][] = array($res->site_id,$res->VchResizeimage,$res->VchVideothumbnail,$res->VchTitle,$res->EnumType,$res->download_id,$res->intsetdefault,$res->video_id);
			DB::table('tbl_download')->where('download_id', $res->download_id)->update($data);
		}
		//print_r($getdownloadinfo);
		 foreach($userid as $key=>$value){
		
			$getuserinfo = DB::table('tbluser')->where('intuserid',$value)->first();
			$managesite = DB::table('tbl_managesite')->leftjoin('tbl_themesetting','tbl_managesite.intmanagesiteid','tbl_themesetting.Intsiteid')->where('intmanagesiteid',$getuserinfo->vchsiteid)->first();

				$data2 = array('email'=>$getuserinfo->vchemail,'emailfrom'	=> $managesite->vchemailfrom,);
				$renewdata = [
					'username'=>$getuserinfo->vchfirst_name,
					'siteurl'=> "https://".$managesite->txtsiteurl,
					'vlogo' =>  "https://".$managesite->txtsiteurl."/images/".$managesite->Vchthemelogo,
					'downloadlist'=>$getdownloadinfo[$value]
				];
				/* print_r($renewdata);
				exit; */
				 Mail::send('email.today',['data'=>$renewdata], function ($message) use ($data2) {
					$message->from($data2['emailfrom'],'noreply');
					$message->to($data2['email']);
					$message->subject('Today Download List');
                });
		 }		
				
		\Log::info("Download Images list");
        
    }
}
