<?php

/*
|--------------------------------------------------------------------------
| Auto Upload, Delete and Resizing of Images
|--------------------------------------------------------------------------
|
|
*/

namespace App\Traits;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

trait Imaging
{
    /**
     * Get storage path for images, photos or pictures.
     *
     * @return string
     */
    public static function storagePath()
    {
        return 'images';
    }

    /**
     * Get compression rate.
     *
     * @return int
     */
    public static function compressionRate()
    {
        return 90;
    }

    /**
     * default 16:9 ratio resolutions.
     *
     * @return array
     */
    public static function resolutions()
    {
        return [
            '768' => [
                'width'  => 1280,
                'height' => 768
            ],
            '900' => [
                'width'  => 1600,
                'height' => 900
            ],
            '1080' => [
                'width'  => 1920,
                'height' => 1080
            ],
            '1440' => [
                'width'  => 2560,
                'height' => 1440
            ],
            '2160' => [
                'width'  => 3840,
                'height' => 2160
            ]
        ];
    }

    /**
     * If directory doesn't exist create directory.
     *
     * @return void
     */
    public static function createDirectory()
    {
        Storage::makeDirectory('public/' . self::storagePath(), 0775, true);
    }

    /**
     * Check if the required field exists.
     *
     * @return boolean
     */
    public static function checkIfFieldExists()
    {
        if (request()->hasFile('image') || request()->hasFile('images') ||
            request()->hasFile('picture') || request()->hasFile('pictures') ||
            request()->hasFile('photo') || request()->hasFile('photos')
        ) {
            return true;
        }

        return false;
    }

    /**
     * Create sizes and store image, photo or picture automatically.
     *
     * @param  Illuminate\Database\Eloquent\Model $model
     * @param  array $sizes
     * @return void
     */
    public static function storeImage($model, $sizes = null)
    {
        if (self::checkIfFieldExists()) {
            self::createDirectory();

            $storagePath       = self::storagePath();
            $resolutions       = self::resolutions();
            $compressionRate   = self::compressionRate();

            // Todo: consider image parameters with different name
            $image        = $model->image;
            $imageName    = uniqid() . '.jpg';
            $model->image = $imageName;

            if ($sizes != null && is_array($sizes)) {
                foreach ($sizes as $size) {
                    foreach ($resolutions as $key => $value) {
                        Image::make($image)->resize($value['width'], $value['height'])
                                        ->encode('jpg', $compressionRate)
                                        ->save(
                                            storage_path('app/public/' . $storagePath . '/' . $value['width'] . '-' . $value['height'] . '-' . $imageName),
                                            $compressionRate
                                        );
                    }
                }
            }

            Image::make($image)->encode('jpg', $compressionRate)->save(
                storage_path('app/public/' . $storagePath . '/' . $imageName),
                $compressionRate
            );
        }
    }

    /**
     * Update image including sizes.
     *
     * @param  Illuminate\Database\Eloquent\Model $model
     * @param  array $sizes
     * @return void
     */
    public static function updateImage($model, $sizes = null)
    {
        if (self::checkIfFieldExists()) {
            self::deleteImage($model->findorFail($model->id));
            self::storeImage($model, $sizes);
        }
    }

    /**
     * Delete image including sizes.
     *
     * @param  Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public static function deleteImage($model)
    {
        $storagePath = self::storagePath();
        $resolutions = self::resolutions();

        foreach ($resolutions as $key => $value) {
            $image = $value['width'] . '-' . $value['height'] . '-' . $model->image;
            Storage::delete('public/' . $storagePath . '/' . $image);
        }

        Storage::delete('public/' . $storagePath . '/' . $model->image);
    }
}
