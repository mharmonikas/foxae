<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function handle()
    {
        $watermark = DB::table('tblwatermarklogo')->where('vchtype','L')->where('vchsiteid', $this->domainId)->where('enumstatus','A')->first();

//        $watermarkImage = imagecreatefrompng($watermarkPath);
//        $watermarkWidth = imagesx($watermarkImage);
//        $watermarkHeight = imagesy($watermarkImage);

//        $watermarkPath = public_path().'/testing/watermark.png';
        $watermarkPath = public_path('/upload/watermark/'.$watermark->vchwatermarklogoname);
        $watermarkImage = Image::make($watermarkPath);

        $images = DB::table('tbl_Video')->orderByDesc('IntId')->get(['IntId', 'VchFolderPath', 'VchVideoName', 'VchResizeimage', 'vchcacheimages', 'vchorginalfile']);

        $image = $images[0];

        // todo: This should be in the each() loop.

//        $imagePath = public_path('showimage/'.$image->IntId.'/1/'.$image->vchorginalfile);
        $imagePath = public_path($image->VchFolderPath.'/'.$image->VchVideoName);
//        $imagePath = public_path('upload/videosearch/'.$image->IntId.'/resize/'.$image->VchResizeimage);
//        $imagePath = public_path('testing/image.jpg');

        $img = Image::make($imagePath);

        $watermarkImage->resize($img->width(), $img->height());
//        $watermarkImage->opacity(60);

        $img->insert($watermarkImage, 'bottom-left');

//        $img->encode('jpg');
//
//        $path = public_path('/testing/imageresult.jpg');
        $destinationPath = 'watermarkedImages/'.$this->domainId.'/'.$image->IntId;
        File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);

        $destinationPath = public_path($destinationPath.'/'.$image->VchVideoName);

        $img->save($destinationPath);

//        return response()->download($path);

//        dd('saved image successfully.');


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
