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
        Log::info('Start update');

        $watermark = DB::table('tblwatermarklogo')->where('vchtype','L')->where('vchsiteid', $this->domainId)->where('enumstatus','A')->first();

        $watermarkImage = Image::make(public_path('/upload/watermark/'.$watermark->vchwatermarklogoname));

        $images = DB::table('tbl_Video')->orderByDesc('IntId')->get(['IntId', 'VchFolderPath', 'VchVideoName', 'VchResizeimage', 'vchcacheimages', 'vchorginalfile']);

        $this->assureDirectoryExists('watermarkedImages/'.$this->domainId);

        Log::info('Before foreach');

        Log::info('count');
        Log::info($images->count());

//        $image = $images[0];

        $images->each(function ($image) use ($watermarkImage) {
            Log::info('In foreach');
            Log::info($image->IntId);

            $this->addWatermark($image, $watermarkImage);
        });
    }

    private function addWatermark($image, $watermarkImage)
    {
        $imagePath = public_path($image->VchFolderPath.'/'.$image->VchVideoName);

        $img = Image::make($imagePath);

        $backgrounds = DB::table('tbl_backgrounds')->where('siteid', 'like', '%'.$this->domainId.'%')->get(); // get backgrounds.

        $backgrounds->each(function ($background) use ($img, $image, $imagePath, $watermarkImage) {
            $destinationPath = $this->getDestinationPath($background, $image);

            $backgroundImage = $this->getBackgroundImage($background);

            $backgroundImage->resize(852, 480);

            $img->resize(852, 480);

            $img->save($destinationPath);

            $backgroundImage->insert($destinationPath, 'bottom-left');

            $backgroundImage->save($destinationPath);

            $watermarkImage->resize(852, 480);

            $watermarkImage->opacity(50);

            $backgroundImage->insert($watermarkImage, 'bottom-left');

            $backgroundImage->save();

//            $backgroundImage->insert($watermarkImage, 'bottom-left');
//
//            $this->assureDirectoryExists('watermarkedImages/'.$this->domainId.'/'.$background->bg_id);
//
//            $img->save($destinationPath);
        });
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
     * @param $image
     * @return string
     */
    private function getDestinationPath($background, $image): string
    {
        $destinationPath = 'watermarkedImages/' . $this->domainId . '/' . $image->IntId . '/' . $background->bg_id;

        $this->assureDirectoryExists($destinationPath);

        return public_path($destinationPath . '/' . $image->VchVideoName);
    }
}
