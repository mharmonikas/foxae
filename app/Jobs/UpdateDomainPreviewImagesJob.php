<?php

namespace App\Jobs;

use Exception;
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

    public $onlyLastImage;

    public function __construct($domainId, $onlyLastImage = false)
    {
        $this->domainId = (int)$domainId;
        $this->onlyLastImage = $onlyLastImage;
    }

    /**
     * Execute the job.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function handle()
    {
        Log::info("UpdateDomainPreviewImagesJob for domain ID {$this->domainId}");

        $images = DB::table('tbl_Video')
            ->orderByDesc('IntId')
            ->when($this->onlyLastImage, function ($query) {
                $query->limit(1);
            })->get(['IntId', 'VchFolderPath', 'VchVideoName', 'VchResizeimage', 'vchcacheimages', 'vchorginalfile', 'transparent']);

        Log::info('Number of images: '.$images->count());

        $backgrounds = DB::table('tbl_backgrounds')->where('siteid', 'like', '%'.$this->domainId.'%')->get(); // get backgrounds.

        $watermark = DB::table('tblwatermarklogo')->where('vchtype','L')->where('vchsiteid', $this->domainId)->where('enumstatus','A')->first();

        $watermarkImage = Image::make(public_path('/upload/watermark/'.$watermark->vchwatermarklogoname));

        $watermarkImage->resize(852, 480);

        $watermarkImage->opacity(40);

        $this->assureDirectoryExists('watermarkedImages/'.$this->domainId);

        Log::info("Number of images: {$images->count()}");

        $images->each(function ($dbImage) use ($watermarkImage, $backgrounds) {
            Log::info("Image ID {$dbImage->IntId}");

            try {
                $this->addWatermark($dbImage, $watermarkImage, $backgrounds);
            } catch (Exception $e) {
                Log::critical($e);
            }
        });
    }

    private function addWatermark($dbImage, $watermarkImage, $backgrounds)
    {
        $imagePath = public_path($dbImage->VchFolderPath.'/'.$dbImage->VchVideoName);

        try {
            $image = Image::make($imagePath);
        } catch (Exception $e) {
            Log::critical('Image path: ' . $imagePath);
            Log::critical('Can not make main image');
            Log::critical($e);

            return 1;
        }

        $image->resize(852, 480);

        if($dbImage->transparent === 'N') {
            try {
                $image->insert($watermarkImage, 'bottom-left');

                $this->saveImageWithoutBackground($image, $dbImage);

                return 1;
            } catch (Exception $e) {
                Log::critical('Can not add watermark');
                Log::critical($e);

                return 1;
            }

        }

        foreach ($backgrounds as $background) {
            Log::info("Background ID {$background->bg_id}");
            try {
                $destinationPath = $this->getDestinationPath($background, $dbImage);

                $backgroundImage = $this->getBackgroundImage($background);

                if(!$backgroundImage) {
                    Log::info("Background image with ID {$background->id} is missing");
                }

                $backgroundImage->resize(852, 480);

                $image->save($destinationPath);

                $backgroundImage->insert($destinationPath, 'bottom-left');

                $backgroundImage->save($destinationPath);

                $backgroundImage->insert($watermarkImage, 'bottom-left');

                $backgroundImage->save();
            } catch (Exception $e) {
                Log::info("Background image with ID {$background->id} is missing");
                Log::critical($e);

                continue;
            }
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
