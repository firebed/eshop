<?php

namespace Eshop\Models\Page;

use Eshop\Models\Lang\Traits\HasTranslations;
use Eshop\Models\Seo\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasTranslations,
        HasSeo;
    
    public array $translatable = ['content'];
    
    protected $fillable = ['name'];
}