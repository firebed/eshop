<?php

namespace Eshop\Models\Media\Traits;

use Eshop\Models\Media\Image;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

/**
 * Trait HasImages
 * @package App\Models\Media\Traits
 *
 * @property Image image
 */
trait HasImages
{
    protected bool $userIdAsPrefix = true;
    private array  $conversions    = [];

    public static function bootHasImages(): void
    {
        static::deleting(static function ($model) {
            $isSoftDelete = in_array(SoftDeletes::class, class_uses($model), false);
            if (!$isSoftDelete || $model->isForceDeleting()) {
                $model->images()->delete();
                Storage::disk($model->getMediaDisk())->deleteDirectory($model->getPathPrefix());
            }
        });
    }

    public function image(?string $collection = null): MorphOne
    {
        if (is_null($collection)) {
            return $this->morphOne(Image::class, 'imageable')->whereNull('collection');
        }

        return $this->morphOne(Image::class, 'imageable')->where('collection', $collection);
    }

    public function images(?string $collection = null): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->when($collection, fn($q) => $q->where('collection', $collection));
    }

    public function saveImage($file, ?string $collection = null): Image
    {
        $manager = new ImageManager();
        $image = $manager->make($file);

        if ($file instanceof UploadedFile) {
            $hashName = $file->hashName();
        } else {
            $mime = $image->mime();
            $hashName = Str::random(40) . '.' . substr($mime, strrpos($mime, '/') + 1);
        }

        $path = $this->path($hashName);
        $this->resizeBaseImage($image);
        $this->saveToDisk($path, $image, 80);

        if (method_exists($this, 'registerImageConversions')) {
            $this->registerImageConversions();
        }

        $media = new Image([
            'disk'        => $this->getMediaDisk(),
            'collection'  => $collection,
            'src'         => $path,
            'conversions' => $this->prepareConversions($hashName, $manager, $file)
        ]);
        $this->images()->save($media);
        return $media;
    }

    public function addWatermark(): void
    {
        $baseImage = $this->image;

        if ($baseImage === null || blank(eshop('watermark'))) {
            return;
        }

        $manager = new ImageManager();
        $watermark = $manager->make(public_path(eshop('watermark')));
        
        $image = $manager->make($baseImage->path());
        
        $wmarkWidth = $watermark->width();
        $wmarkHeight = $watermark->height();

        $imgWidth = $image->width();
        $imgHeight = $image->height();

        $x = 0;
        $y = 0;
        while ($y <= $imgHeight) {
            $image->insert($watermark, 'top-left', $x, $y);
            $x += $wmarkWidth;
            if ($x >= $imgWidth) {
                $x = 0;
                $y += $wmarkHeight;
            }
        }

        Storage::disk($this->getMediaDisk())->delete($baseImage->src);
        
        $mime = $image->mime();
        $hashName = Str::random(40) . '.' . substr($mime, strrpos($mime, '/') + 1);
        $path = $this->path($hashName);
        $this->saveToDisk($path, $image, 90);
        $baseImage->update(['src' => $path]);
    }

    public function getMediaDisk(): string
    {
        return $this->disk;
    }

    public function addImageConversion($name, callable $func): void
    {
        $this->conversions[$name] = $func;
    }

    public function isCollectionAttribute($key): bool
    {
        return in_array($key, $this->collections ?? []);
    }

    protected function resizeBaseImage($image): void
    {
        $image->resize(3000, 3000, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
    }

    protected function getPathPrefix(): string
    {
        return ($this->userIdAsPrefix ? $this->id . '/' : '');
    }

    protected function path($filename): string
    {
        return $this->getPathPrefix() . $filename;
    }

    private function saveToDisk($path, $image, $quality): void
    {
        Storage::disk($this->getMediaDisk())->put($path, $image->encode(null, $quality));
    }

    private function prepareConversions($hashName, $manager, $file): array
    {
        if (method_exists($this, 'registerImageConversions')) {
            $this->registerImageConversions();
        }

        $conversions = $media->conversions ?? [];
        foreach ($this->conversions as $name => $callback) {
            $image = $manager->make($file);
            $callback($image);

            $path = $this->path($name . '-' . $hashName);
            $this->saveToDisk($path, $image, 80);
            $conversions[$name] = [
                'src' => $path,
            ];
        }
        return $conversions;
    }
}
