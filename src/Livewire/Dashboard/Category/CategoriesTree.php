<?php


namespace Eshop\Livewire\Dashboard\Category;


use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class CategoriesTree extends Component
{
    public function render(): Renderable
    {
        return view('eshop::dashboard.category.wire.categories-tree');
    }
}