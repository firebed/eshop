<?php


namespace Eshop\Rules;


use Eshop\Models\Product\Category;
use Illuminate\Contracts\Validation\Rule;

class CategoryIsNotChildOf implements Rule
{
    private array $sources;
    private string $name = "";

    public function __construct(array $sources = null)
    {
        $this->sources = $sources ?? [];
    }

    public function passes($attribute, $value): bool
    {
        if (in_array($value, $this->sources)) {
            $this->name = Category::find($value)->name;
            return false;
        }

        $target = Category::find($value);
        $parent = $target->parent;

        while ($parent) {
            // If the target is child of any of the sources
            if (($key = array_search($parent->id, $this->sources)) !== FALSE) {
                $this->name = Category::find($this->sources[$key])->name;
                return false;
            }

            $parent = $parent->parent;
        }

        return true;
    }

    public function message(): string
    {
        return trans('eshop::category.notifications.unable_to_move', ['name' => $this->name]);
    }
}