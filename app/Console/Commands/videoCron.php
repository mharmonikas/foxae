<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use File;
class videoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
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
		 $currnettime = date('Y-m-d H:i');
		 $runcron = DB::table('scheduling')->where('schedulingtime','like','%'.$currnettime.'%')->first();
		 if(!empty($runcron)){
		  if($runcron->status != 'Run'){ 
			  
			 $data = [
				"status"=>'Run'
			 ];
			 DB::table('scheduling')->where('id', 1)->update($data);
			$getvideodata = DB::table('tbl_Video')->where('EnumType','V')->orderby('IntId','DESC')->get();	
			foreach($getvideodata as $videodata){
				$siteid=explode(',',$videodata->vchsiteid);
				for($i=0;$i < count($siteid);$i++){
				$Watermark = DB::table('tblwatermarklogo')->where('vchtype','V')->where('vchsiteid',$siteid[$i])->where('enumstatus','A')->first();
				if(!empty($Watermark)){
					if (!Is_Dir(public_path().'/'.$videodata->VchFolderPath.'/'.$Watermark->vchsiteid)){
						mkdir(public_path().'/'.$videodata->VchFolderPath.'/'.$Watermark->vchsiteid, 0777);
					}
					if(file_exists(public_path().'/'.$videodata->VchFolderPath.'/'.$Watermark->vchsiteid.'/watermark.mp4')){
						$file_to_delete = public_path().'/'.$videodata->VchFolderPath.'/'.$Watermark->vchsiteid.'/watermark.mp4';
							//unlink($file_to_delete);
						\File::delete($file_to_delete);
					}
					$watermarklogo = public_path().'/upload/watermark/'.$Watermark->vchwatermarklogoname;
					//shell_exec('ffmpeg -i '.public_path().'/upload/videosearch/'.$videodata->IntId.'/'.$videodata->VchVideoName.' -i '.$watermarklogo.' -filter_complex"overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2" -codec:a copy  '.public_path().'/'.$videodata->VchFolderPath.'/'.$Watermark->vchsiteid.'/watermark.mp4');
					shell_exec('ffmpeg -i upload/'.'videosearch/'.$videodata->IntId.'/'.$videodata->VchVideoName.' -i '.$watermarklogo.' -filter_complex  "overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2" -codec:a copy upload/videosearch/'.$videodata->IntId.'/'.$siteid[$i].'/watermark.mp4');
					
					\Log::info("Cron is working fine for".$videodata->IntId);
					}
					
				}
					
				}
		  }
		 } 
        
    }
}
