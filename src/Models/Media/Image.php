<?php

namespace Eshop\Models\Media;

use Eshop\Database\Factories\Media\ImageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

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
    public const TYPE_URL = 'URL';

    protected $guarded = [];

    protected $casts = [
        'conversions' => 'array'
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function url($conversion = NULL): string|null
    {
        $src = $this->src;

        if ($this->isTypeURL()) {
            return $src;
        }

        if ($conversion !== NULL && $this->hasConversion($conversion)) {
            $src = $this->conversion($conversion)['src'];
        }

        $disk = Storage::disk($this->disk);
//        return $disk->exists($src) ? $disk->url($src) : NULL;
        return $disk->url($src);
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
        return $this->conversions !== NULL && array_key_exists($key, $this->conversions);
    }

    public function getFileSize($src): int
    {
        $disk = Storage::disk($this->disk);
        return $disk->exists($src) ? $disk->size($src) : 0;
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
        return getimagesize($this->url());
    }

    protected static function booted(): void
    {
        static::deleted(function (Image $image) {
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

    protected static function newFactory(): ImageFactory
    {
        return ImageFactory::new();
    }
}
