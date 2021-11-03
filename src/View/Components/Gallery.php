<?php

namespace Eshop\View\Components;

use Eshop\Models\Slide\Slide;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Gallery extends Component
{
    public Collection $slides;

    public function __construct()
    {
        $this->slides = Slide::with('image')->get();
    }

    public function render(): Renderable|string
    {
        return view('eshop::customer.components.gallery');
    }
}
