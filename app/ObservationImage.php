<?php

namespace App;

use Imagick;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ObservationImage
{
    private $crops = [
        'water_tank' => ['x' => 25, 'y' => 200, 'width' => 275, 'height' => 250],
        'coffee_mug' => ['x' => 350, 'y' => 650, 'width' => 275, 'height' => 250],
        'power_button' => ['x' => 22, 'y' => 960, 'width' => 120, 'height' => 55],
        'heating_plate_button' => ['x' => 161, 'y' => 962, 'width' => 117, 'height' => 50],
    ];

    public function __construct(string $path, string $mimeType, string $uuid = null)
    {
        $this->path = $path;
        $this->mimeType = $mimeType;
        $this->uuid = $uuid;
    }

    public function crop()
    {
        $paths = [];

        foreach ($this->crops as $name => $cropRect) {
            $croppedPath = pathinfo($this->path, PATHINFO_DIRNAME) . '/' . $name . ".jpg";
            $src = \imagecreatefromjpeg($this->path);
            $cropped = \imagecrop($src, $cropRect);

            if ($cropped !== FALSE) {
                \imagejpeg($cropped, $croppedPath);
                \imagedestroy($cropped);
            }

            \imagedestroy($src);

            $paths[$name] = [
                'path' => $croppedPath,
                'url' => config('app.url') . Storage::url('observations/' . $this->uuid . '/' . basename($croppedPath)),
            ];
        }

        return $paths;
    }

    public function rotate()
    {
        $imageResource = \imagecreatefromstring(file_get_contents($this->path));
        $rotated = \imagerotate($imageResource, 90, 0);

        ob_start();
        $imageMime = $this->mimeType;
        if ($imageMime === 'image/png') {
            \imagepng($rotated);
        } else {
            \imagejpeg($rotated);
        }
        $imageData = ob_get_clean();

        $storagePath = request()->file('image')->store('observations', 'public');
        $stored = \Storage::disk('public')->put($storagePath, $imageData);
        $this->storedAt = \Storage::disk('public')->path($storagePath);

        \imagedestroy($imageResource);
        \imagedestroy($rotated);
    }
}
