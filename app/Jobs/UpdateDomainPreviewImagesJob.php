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

        Log::info('images');
        Log::info($images->pluck('IntId'));

        $backgrounds = DB::table('tbl_backgrounds')->where('siteid', 'like', '%'.$this->domainId.'%')->get(); // get backgrounds.

        $this->assureDirectoryExists('watermarkedImages/'.$this->domainId);

        Log::info('Before foreach');

        Log::info('count');
        Log::info($images->count());

//        $image = $images[0];

        foreach ($images as $image) {
            Log::info('In foreach');
            Log::info($image->IntId);

            $this->addWatermark($image, $watermarkImage, $backgrounds);
        }
    }

    private function addWatermark($image, $watermarkImage, $backgrounds)
    {
        $imagePath = public_path($image->VchFolderPath.'/'.$image->VchVideoName);

        $img = Image::make($imagePath);

        foreach ($backgrounds as $background) {
            $destinationPath = $this->getDestinationPath($background, $image);

            $backgroundImage = $this->getBackgroundImage($background);

            $backgroundImage->resize(852, 480);

            $img->resize(852, 480);

            $img->save($destinationPath);

            $backgroundImage->insert($destinationPath, 'bottom-left');

            $backgroundImage->save($destinationPath);

            $watermarkImage->resize(852, 480);

            $watermarkImage->opacity(40);

            $reloadedImage = Image::make($destinationPath);

            $reloadedImage->insert($watermarkImage, 'bottom-left');

            $reloadedImage->save();

//            $backgroundImage->insert($watermarkImage, 'bottom-left');
//
//            $this->assureDirectoryExists('watermarkedImages/'.$this->domainId.'/'.$background->bg_id);
//
//            $img->save($destinationPath);
        }

        return 1;
    }

    private function assureDirectoryExists($path)
    {
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
    }

    private function getBackgroundImage($background)
    {
        $imagePath = public_path('background/'.$background->background_img);

        Log::info('Background path: ');
        Log::info($imagePath);

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
