<?php

namespace Eshop\Models\Media;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

/**
 * @property string type
 * @property string disk
 * @property string collection
 * @property string src
 * @property array  conversions
 *
 * @mixin Builder
 */
class Image extends Model
{
    use HasFactory;

    public const TYPE_PATH = 'Path';
    public const TYPE_URL  = 'URL';

    protected $guarded = [];

    protected $casts = [
        'conversions' => 'array'
    ];

    protected static function booted(): void
    {
        static::deleted(static function (Image $image) {
            if (!method_exists($image, 'isForceDeleting') || $image->isForceDeleting()) {
                if ($image->isTypeURL()) {
                    return;
                }

                Storage::disk($image->disk)->delete($image->src);

                if ($image->conversions) {
                    foreach ($image->conversions as $conversion) {
                        Storage::disk($image->disk)->delete($conversion['src']);
                    }
                }
            }
        });
    }

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function addConversion($conversion, $file): void
    {
        $manager = new ImageManager();
        $image = $manager->make($file);

        $mime = $image->mime();
        $hashName = Str::random(40) . '.' . substr($mime, strrpos($mime, '/') + 1);
        $path = $this->imageable_id . '/' . $hashName;

        Storage::disk($this->disk)->put($path, $file->encode(null, 80));
        $conversions = $this->conversions ?? [];
        $conversions[$conversion]['src'] = $path;
        $this->update(['conversions' => $conversions]);
    }

    public function deleteConversion($conversion): void
    {
        $conversions = $this->conversions ?? [];
        
        if (array_key_exists($conversion, $conversions)) {
            Storage::disk($this->disk)->delete($conversions[$conversion]['src']);

            unset($conversions[$conversion]);
            $this->update(['conversions' => $conversions]);
        }
    }

    public function url($conversion = null): string|null
    {
        $src = $this->src;

        if ($this->isTypeURL()) {
            return $src;
        }

        if ($conversion !== null && $this->hasConversion($conversion)) {
            $src = $this->conversion($conversion)['src'];
        }
        
        $disk = Storage::disk($this->disk);
//        return $disk->exists($src) ? $disk->url($src) : NULL;
        return $disk->url($src);
    }

    public function path(): string
    {
        return Storage::disk($this->disk)->path($this->src);
    }

    public function isTypeURL(): bool
    {
        return $this->type === self::TYPE_URL;
    }

    public function isTypePath(): bool
    {
        return $this->type === self::TYPE_PATH;
    }

    public function conversion($name)
    {
        return $this->conversions[$name];
    }

    public function hasConversion($key): bool
    {
        return $this->conversions !== null && array_key_exists($key, $this->conversions);
    }

    public function getFileSize($src): int
    {
        $disk = Storage::disk($this->disk);
        return $disk->exists($src) ? $disk->size($src) : 0;
    }

    public function fileExists(): bool
    {
        return Storage::disk($this->disk)->exists($this->src);
    }

    public function getConversionsTotalSizeAttribute(): string
    {
        $initial = $this->getFileSize($this->src);
        return array_reduce($this->conversions, fn($carry, $item) => $carry + $this->getFileSize($item['src']), $initial);
    }

    public function getDimensionsAttribute(): string
    {
        $size = $this->size;
        return $size[0] . 'x' . $size[1];
    }

    public function getSizeAttribute(): array
    {
        $disk = Storage::disk($this->disk);
        return $disk->exists($this->src) ? getimagesize($this->url()) : [0, 0];
    }
}
