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

class UpdateDomainPreviewImagesJob implements ShouldQueue
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

        $watermarkImage = Image::make(public_path('/upload/watermark/'.$watermark->vchwatermarklogoname));

        $images = DB::table('tbl_Video')->orderByDesc('IntId')->get(['IntId', 'VchFolderPath', 'VchVideoName', 'VchResizeimage', 'vchcacheimages', 'vchorginalfile']);

        $this->assureDirectoryExists('watermarkedImages/'.$this->domainId);

        $images->each(function ($image) use ($watermarkImage) {
            $this->addWatermark($image, $watermarkImage);
        });
    }

    private function addWatermark($image, $watermarkImage)
    {
        $imagePath = public_path('upload/videosearch/'.$image->IntId.'/resize/'.$image->VchResizeimage);

        $img = Image::make($imagePath);

        $watermarkImage->resize($img->width(), $img->height());

        $img->insert($watermarkImage, 'bottom-left');

        $destinationPath = 'watermarkedImages/'.$this->domainId.'/'.$image->IntId;

        $this->assureDirectoryExists($destinationPath);

        $destinationPath = public_path($destinationPath.'/'.$image->VchVideoName);

        $img->save($destinationPath);
    }

    private function assureDirectoryExists($path)
    {
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
    }
}
