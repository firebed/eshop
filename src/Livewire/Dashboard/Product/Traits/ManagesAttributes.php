<?php


namespace Eshop\Livewire\Dashboard\Product\Traits;


use Eshop\Models\Product\CategoryProperty;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait ManagesAttributes
{
    public array $choices = [];
    public array $values  = [];

    public function mountManagesAttributes(): void
    {
        $this->loadAttributes();
    }

    protected function loadAttributes(): void
    {
        $this->choices = [];
        $this->values = [];
        if ($this->category) {
            $product_properties = $this->product->properties()->get();
            foreach ($this->category->properties as $property) {
                if ($property->isValueRestricted()) {
                    $property->isValueRestrictionMultiple()
                        ? $this->mapMultipleChoices($property, $product_properties)
                        : $this->mapSingleChoice($property, $product_properties);
                } else {
                    $this->mapPropertyValue($property, $product_properties);
                }
            }
        }
    }

    private function mapMultipleChoices(CategoryProperty $property, Collection $product_properties): void
    {
        if ($product_properties->contains($property->id)) {
            $product_properties
                ->where('id', $property->id)
                ->pluck('pivot.category_choice_id')
                ->each(fn($i) => $this->choices[$property->id][] = (string)$i);
        } else {
            $this->choices[$property->id] = [];
        }
    }

    private function mapSingleChoice(CategoryProperty $property, Collection $product_properties): void
    {
        $prop = $product_properties->find($property->id);

        $this->choices[$property->id] = $prop
            ? (string)$prop->pivot->category_choice_id
            : '';
    }

    private function mapPropertyValue(CategoryProperty $property, Collection $product_properties): void
    {
        $prop = $product_properties->find($property->id);

        $this->values[$property->id] = $prop
            ? $prop->pivot->value
            : '';
    }

    public function saveAttributes(): void
    {
        $data = [];
        $filtered = collect($this->choices)->filter(fn($i) => filled($i));
        foreach ($filtered as $property => $choices) {
            $choices = Arr::wrap($choices);
            foreach ($choices as $choice) {
                $data[] = $this->mapProperty($property, $choice);
            }
        }

        $filtered = collect($this->values)->filter(fn($i) => filled($i));
        foreach ($filtered as $property => $value) {
            $data[] = $this->mapProperty($property, NULL, $value);
        }

        DB::table('product_properties')->where('product_id', $this->product->id)->delete();
        DB::table('product_properties')->insert($data);
    }

    private function mapProperty($property_id, $choice_id, $value = NULL): array
    {
        return [
            'product_id'           => $this->product->id,
            'category_property_id' => $property_id,
            'category_choice_id'   => $choice_id,
            'value'                => $value
        ];
    }
}
