<?php

namespace Eshop\Models\Attachments;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $attachable_type
 * @property int $attachable_id
 * @property string $title
 * @property string $disk
 * @property string $src
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @mixin Builder
 */
class Attachment extends Model
{
    protected $fillable = ['title', 'disk', 'src'];

    public function url(): string|null
    {
        $src = $this->src;

        $disk = Storage::disk($this->disk);
        return $disk->url($src);
    }

    public function path(): string
    {
        return Storage::disk($this->disk)->path($this->src);
    }
}