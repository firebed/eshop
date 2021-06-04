<?php


namespace App\Http\Livewire\Dashboard\Category\Traits;


use Illuminate\Database\Eloquent\Collection;

trait HasBreadcrumbs
{
    public array $navbar = [];

    public function mountHasBreadcrumbs(): void
    {
        if ($this->category && $this->category->parent_id) {
            $parent = $this->category->parent()->first();

            $models = collect([]);
            while ($parent) {
                $models[] = $parent;

                $parent = $parent->parent_id
                    ? $parent->parent
                    : NULL;
            }

            $models = $models->reverse();

            if (filled($models)) {
                Collection::make($models)->load('translation');

                foreach ($models as $model) {
                    $this->navbar[$model->id] = $model->name;
                }
            }
        }
    }
}
