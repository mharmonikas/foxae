<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

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

        $images = DB::table('tbl_Video')->get(['IntId', 'VchFolderPath', 'VchVideoName', 'VchResizeImage', 'vchcacheimages']);

        $image = $images[0];

        // todo: This should be in the each() loop.

//        $imagePath = public_path().'/'.$image->VchFolderPath.'/'.$image->VchVideoName;
        $imagePath = public_path('testing/image.jpg');

        $img = Image::make($imagePath);

        /* insert watermark at bottom-right corner with 10px offset */
        $img->insert($watermarkPath, 'bottom-left');
        $img->insert($watermarkPath, 'bottom-right');
        $img->insert($watermarkPath); // top-left is default.
        $img->insert($watermarkPath, 'top-right');

        $img->save(public_path('/testing/imageresult.jpg'));

        dd('saved image successfully.');


//        dump($imagePath);
//        $image = imagecreatefromjpeg($imagePath);
//        $imageWidth = imagesx($image);
//        $imageHeight = imagesy($image);
//
//        imagecopymerge($image, $watermarkImage,  $watermarkWidth, $watermarkHeight, 0, 0, $imageWidth, $imageHeight, 100);
//
//        file_put_contents(public_path().'/image_cache6/testimage.png', file_get_contents($imagePath));

//        $images->each( function($image) use ($watermarkImage,$watermarkWidth, $watermarkHeight) {
//
//        });
    }

    private function addWatermark($imagePath)
    {

    }
}
