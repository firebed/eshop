<?php

namespace Eshop\Models\Attachments\Traits;

use Eshop\Models\Attachments\Attachment;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @property Attachment $attachment
 */
trait HasAttachments
{
    public static function bootHasAttachments(): void
    {
        static::deleting(static function ($model) {
            $isSoftDelete = in_array(SoftDeletes::class, class_uses($model), false);
            if (!$isSoftDelete || $model->isForceDeleting()) {
                $model->attachment()->delete();
                Storage::disk($model->getAttachmentDisk())->deleteDirectory($model->getPathPrefix());
            }
        });
    }

    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }

    public function saveAttachment(UploadedFile $file, ?string $title = null): Attachment
    {
        $path = $this->getPathPrefix();
        $file->storeAs($this->id, $file->hashName(), $this->disk);
        
        $attachment = new Attachment([
            'disk' => $this->disk,
            'title' => $title,
            'src' => $path . '/' . $file->hashName(),
        ]);
        
        $this->attachment()->save($attachment);
        return $attachment;
    }
}
