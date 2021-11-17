<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as Image;

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
        Log::info("UpdateDomainPreviewImagesJob for domain ID {$this->domainId}");

        $images = DB::table('tbl_Video')->orderByDesc('IntId')->get(['IntId', 'VchFolderPath', 'VchVideoName', 'VchResizeimage', 'vchcacheimages', 'vchorginalfile', 'transparent']);

        $backgrounds = DB::table('tbl_backgrounds')->where('siteid', 'like', '%'.$this->domainId.'%')->get(); // get backgrounds.

        $watermark = DB::table('tblwatermarklogo')->where('vchtype','L')->where('vchsiteid', $this->domainId)->where('enumstatus','A')->first();

        $watermarkImage = Image::make(public_path('/upload/watermark/'.$watermark->vchwatermarklogoname));

        $watermarkImage->resize(852, 480);

        $watermarkImage->opacity(40);

        $this->assureDirectoryExists('watermarkedImages/'.$this->domainId);

        Log::info("Number of images: {$images->count()}");

        $images->each(function ($dbImage) use ($watermarkImage, $backgrounds) {
            Log::info("Image ID {$dbImage->IntId}");

            $this->addWatermark($dbImage, $watermarkImage, $backgrounds);
        });
    }

    private function addWatermark($dbImage, $watermarkImage, $backgrounds)
    {
        $imagePath = public_path($dbImage->VchFolderPath.'/'.$dbImage->VchVideoName);

        $image = Image::make($imagePath);

        $image->resize(852, 480);

        if($dbImage->transparent === 'N') {
            // add watermark
            // save to folder without background (/watermarkedImage/id)
            $image->insert($watermarkImage, 'bottom-left');

            $this->saveImageWithoutBackground($image, $dbImage);

            return 1;
        }

        foreach ($backgrounds as $background) {
            $destinationPath = $this->getDestinationPath($background, $dbImage);

            $backgroundImage = $this->getBackgroundImage($background);

            $backgroundImage->resize(852, 480);

            $image->save($destinationPath);

            $backgroundImage->insert($destinationPath, 'bottom-left');

            $backgroundImage->save($destinationPath);

            $backgroundImage->insert($watermarkImage, 'bottom-left');

            $backgroundImage->save();

//            $backgroundImage->insert($watermarkImage, 'bottom-left');
//
//            $this->assureDirectoryExists('watermarkedImages/'.$this->domainId.'/'.$background->bg_id);
//
//            $img->save($destinationPath);
        }
    }

    private function assureDirectoryExists($path)
    {
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
    }

    private function getBackgroundImage($background)
    {
        $imagePath = public_path('background/'.$background->background_img);

        return Image::make($imagePath);
    }

    /**
     * @param $background
     * @param $dbImage
     * @return string
     */
    private function getDestinationPath($background, $dbImage): string
    {
        $destinationPath = 'watermarkedImages/' . $this->domainId . '/' . $dbImage->IntId . '/' . $background->bg_id;

        $this->assureDirectoryExists($destinationPath);

        return public_path($destinationPath . '/' . $dbImage->VchVideoName);
    }

    private function saveImageWithoutBackground($image, $dbImage)
    {
        $path = public_path("watermarkedImages/{$this->domainId}/{$dbImage->IntId}");

        $this->assureDirectoryExists($path);

        $image->save($path.'/'.$dbImage->VchVideoName);
    }
}
