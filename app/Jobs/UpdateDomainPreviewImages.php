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
        $this->domainId = (int)$domainId;
    }

    /**
     * Execute the job.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function handle()
    {
        $watermark = DB::table('tblwatermarklogo')->where('vchtype','L')->where('vchsiteid', $this->domainId)->where('enumstatus','A')->first();

        $watermarkPath = public_path('/upload/watermark/'.$watermark->vchwatermarklogoname);
        $watermarkImage = Image::make($watermarkPath);

        $images = DB::table('tbl_Video')->orderByDesc('IntId')->get(['IntId', 'VchFolderPath', 'VchVideoName', 'VchResizeimage', 'vchcacheimages', 'vchorginalfile']);

        // todo: This should be in the each() loop.
        $image = $images[0];

        $imagePath = public_path($image->VchFolderPath.'/'.$image->VchVideoName);
//        $imagePath = public_path('showimage/'.$image->IntId.'/1/'.$image->vchorginalfile);
//        $imagePath = public_path('upload/videosearch/'.$image->IntId.'/resize/'.$image->VchResizeimage);

        $img = Image::make($imagePath);

        $watermarkImage->resize($img->width(), $img->height());

        $img->insert($watermarkImage, 'bottom-left');

        File::isDirectory('watermarkedImages/'.$this->domainId) or File::makeDirectory('watermarkedImages/'.$this->domainId, 0777, true, true);

        $destinationPath = 'watermarkedImages/'.$this->domainId.'/'.$image->IntId;

        File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);

        $destinationPath = public_path($destinationPath.'/'.$image->VchVideoName);

        $img->save($destinationPath);
    }

    private function addWatermark($imagePath)
    {

    }
}
