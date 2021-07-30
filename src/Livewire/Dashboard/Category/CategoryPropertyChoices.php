<?php


namespace Eshop\Livewire\Dashboard\Category;


use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class CategoryPropertyChoices extends Component
{
    public array $choices = [];

    public function add(): void
    {
        $this->choices[] = ['id' => '', 'name' => ''];
    }

    public function remove(int $index): void
    {
        unset($this->choices[$index]);
        $this->choices = array_values($this->choices);
    }

    public function moveUp(int $index): void
    {
        if ($index <= 0) {
            return;
        }

        $temp = $this->choices[$index - 1];
        $this->choices[$index - 1] = $this->choices[$index];
        $this->choices[$index] = $temp;
    }

    public function moveDown(int $index): void
    {
        if ($index >= count($this->choices)) {
            return;
        }

        $temp = $this->choices[$index + 1];
        $this->choices[$index + 1] = $this->choices[$index];
        $this->choices[$index] = $temp;
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.category-property.wire.property-choices');
    }
}