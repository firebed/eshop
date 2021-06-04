<?php


namespace Ecommerce\Livewire\Dashboard\Category\Traits;


use Ecommerce\Models\Product\CategoryChoice;
use Ecommerce\Models\Product\CategoryProperty;
use Illuminate\Support\Facades\DB;

trait ManagesChoices
{
    public bool $showChoicesModal = FALSE;

    public int   $propertyId;
    public array $choices = [];

    public function editChoices(CategoryProperty $property): void
    {
        $this->propertyId = $property->id;
        $this->choices = $property->choices->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->all();
        $this->showChoicesModal = TRUE;

        $this->addChoice();
    }

    public function addChoice(): void
    {
        $this->choices[] = ['id' => '', 'name' => ''];
    }

    public function deleteChoice(int $index): void
    {
        unset($this->choices[$index]);
        $this->choices = array_values($this->choices);
    }

    public function saveChoices(): void
    {
        $property = CategoryProperty::find($this->propertyId);
        $currentChoices = $property->choices->keyBy('id');

        $position = 1;
        $choices = collect($this->choices)
            ->map(function ($choice) {
                $choice['name'] = trim($choice['name']);
                return $choice;
            })
            ->filter(fn($choice) => filled($choice['name']))
            ->transform(function ($choice) use ($property, $currentChoices, &$position) {
                $model = filled($choice['id']) ? $currentChoices->find($choice['id']) : new CategoryChoice();
                $model->fill([
                    'category_property_id' => $property->id,
                    'slug'                 => slugify($choice['name']),
                    'position'             => $position++
                ]);

                $model->name = $choice['name'];
                return $model;
            });

        $delete = $currentChoices->diffKeys($choices->keyBy('id'));

        DB::transaction(function () use ($delete, $choices) {
            CategoryChoice::whereKey($delete->pluck('id'))->delete();

            foreach ($choices as $choice) {
                $choice->save();
            }
        });

        $this->showChoicesModal = FALSE;
        $this->showSuccessToast(__("Choices saved successfully"));
    }

    public function moveChoiceUp(int $index): void
    {
        if ($index === 0) {
            return;
        }

        $tmp = $this->choices[$index];
        $this->choices[$index] = $this->choices[$index - 1];
        $this->choices[$index - 1] = $tmp;
    }

    public function moveChoiceDown(int $index): void
    {
        if ($index >= count($this->choices) - 1) {
            return;
        }

        $tmp = $this->choices[$index];
        $this->choices[$index] = $this->choices[$index + 1];
        $this->choices[$index + 1] = $tmp;
    }
}
