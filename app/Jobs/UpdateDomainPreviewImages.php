<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateDomainPreviewImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $domainId;

    public function __construct($domainId)
    {
        $this->domainId = $domainId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $watermark = DB::table('tblwatermarklogo')->where('vchtype','L')->where('vchsiteid', $this->domainId)->where('enumstatus','A')->first();

        $watermarkPath = public_path().'/upload/watermark/'.$watermark->vchwatermarklogoname;
        $watermarkImage = imagecreatefrompng($watermarkPath);
        $watermarkWidth = imagesx($watermarkImage);
        $watermarkHeight = imagesy($watermarkImage);

        $images = DB::table('Video')->get(['IntId', 'VchFolderPath', 'VchVideoName', 'VchResizeImage', 'vchcacheimages']);
        $images->each( function($image) use ($watermarkImage,$watermarkWidth, $watermarkHeight) {
            $imagePath = public_path().$image->vchcacheimages;
            $image = imagecreatefrompng($imagePath);
            $imageWidth = imagesx($image);
            $imageHeight = imagesy($image);

            imagecopy($image, $watermarkImage,  $watermarkWidth, $watermarkHeight, 0, 0, $imageWidth, $imageHeight);
        });
    }
}
