<?php

namespace App\Http\Requests;

use Eshop\Models\Product\CategoryChoice;
use Eshop\Models\Product\Manufacturer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class CategoryRequest extends FormRequest
{
    private $category;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $this->category = $this->route('category');

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
        ];
    }

    public function validated(): Collection
    {
        $filters = collect(['m' => collect(), 'c' => collect()]);
        if ($this->segment(3) === 'm') {
            $filters['m'] = $this->getManufacturersFromQuery();

            if ($this->segment(5) !== null) {
                $filters['c'] = $this->getChoicesFromQuery(5);
            }

        } else if ($this->segment(3) === 'f') {
            $filters['c'] = $this->getChoicesFromQuery(4);
        }

        $filters['min_price'] = $this->input('min_price');
        $filters['max_price'] = $this->input('max_price');

        return $filters;
    }

    public function manufacturers(): ?string
    {
        if ($this->isManufacturerFilteringDisabled()) {
            return '';
        }

        return $this->segment(3) === 'm' ? $this->segment(4) : null;
    }

    public function choices(): ?string
    {
        return $this->segment(3) === 'f' ? $this->segment(4) : $this->segment(5);
    }

    public function isManufacturerFilteringDisabled(): bool
    {
        return !$this->isManufacturerFilteringEnabled();
    }

    public function isManufacturerFilteringEnabled(): bool
    {
        return eshop('filter.manufacturers');
    }

    private function getManufacturersFromQuery(): Collection
    {
        if ($this->isManufacturerFilteringDisabled()) {
            return collect();
        }

        $slugs = array_filter(explode('-', $this->segment(4)));

        if (empty($slugs)) {
            return collect();
        }

        return Manufacturer
            ::whereIn('slug', $slugs)
            ->whereHas('categories', fn($q) => $q->where('categories.id', $this->category->id))
            ->orderBy('name')
            ->get();
    }

    private function getChoicesFromQuery(int $segment): Collection
    {
        return CategoryChoice
            ::whereIn('slug', explode('-', $this->segment($segment)))
            ->with('property', 'translation')
            ->get()
            ->reject(fn($choice) => $choice->property->category_id !== $this->category->id)
            ->sortBy(['property.position', 'position']);
    }
}
