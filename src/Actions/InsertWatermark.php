<?php

namespace Eshop\Actions;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;

class InsertWatermark
{
    public function handle($file): ?Image
    {
        if ($file === null || blank(eshop('watermark'))) {
            return null;
        }

        $manager = new ImageManager();
        $image = $manager->make($file);
        
        $watermark = $manager->make(public_path(eshop('watermark')));
        
        $watermarkWidth = $watermark->width();
        $watermarkHeight = $watermark->height();

        $imgWidth = $image->width();
        $imgHeight = $image->height();

        $x = 0;
        $y = 0;
        while ($y <= $imgHeight) {
            $image->insert($watermark, 'top-left', $x, $y);
            $x += $watermarkWidth;
            if ($x >= $imgWidth) {
                $x = 0;
                $y += $watermarkHeight;
            }
        }

        return $image;
    }
}