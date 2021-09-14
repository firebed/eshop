<?php

namespace Eshop\Models\Slide;

use Eshop\Models\Media\Traits\HasImages;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasImages;

    protected        $fillable = ['link'];
    protected string $disk     = 'slides';

    protected function registerImageConversions(): void
    {
        $this->addImageConversion('md', function ($image) {
            $image->resize(640, 360, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        });

        $this->addImageConversion('sm', function ($image) {
            $image->resize(320, 180, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        });
    }
}