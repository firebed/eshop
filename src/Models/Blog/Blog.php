<?php

namespace Eshop\Models\Blog;

use Eshop\Models\Media\Traits\HasImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @propert integer $id
 * @propert integer $user_id
 * @propert string  $title
 * @propert string  $slug
 * @propert string  $content
 * @propert integer $sent
 * @propert integer $opened
 * @propert integer $clicked
 */
class Blog extends Model
{
    use HasFactory, HasImages;

    protected $fillable = ['user_id', 'title', 'slug', 'description', 'content'];

    protected $casts = [
        'sent'    => 'integer',
        'opened'  => 'integer',
        'clicked' => 'integer'
    ];

    protected string $disk = 'blogs';

    protected function registerImageConversions(): void
    {
        $this->addImageConversion('md', function ($image) {
            $image->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        });
    }
}