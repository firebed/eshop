<?php


namespace App\Http\Livewire\Dashboard\Category;


use App\Http\Livewire\Dashboard\Category\Traits\HasBreadcrumbs;
use App\Http\Livewire\Dashboard\Category\Traits\ManagesChoices;
use App\Models\Product\Category;
use App\Models\Product\CategoryProperty;
use App\Services\SlugGenerator;
use Firebed\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Livewire\Traits\Datatable\UpdatesPositioning;
use Firebed\Livewire\Traits\Datatable\WithSelections;
use Firebed\Livewire\Traits\SendsNotifications;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class ShowCategoryProperties extends Component
{
    use HasBreadcrumbs;
    use SendsNotifications;
    use WithSelections;
    use DeletesRows;
    use UpdatesPositioning;
    use ManagesChoices;

    public Category $category;

    public CategoryProperty $property;
    public string           $name      = "";
    public bool             $showModal = false;

    protected function rules(): array
    {
        return [
            'name'                       => ['required', 'string'],
            'property.slug'              => ['required', 'string', Rule::unique('category_properties', 'slug')->where(fn($q) => $q->where('category_id', $this->property->category_id))->ignore($this->property)],
            'property.category_id'       => ['required', 'integer'],
            'property.value_restriction' => ['required_if:property_restricted,true', Rule::in(['None', 'Simple', 'Multiple'])],
            'property.index'             => ['required_if:property_indexed,true', Rule::in(['None', 'Simple', 'Multiple'])],
            'property.visible'           => ['required', 'boolean'],
            'property.promote'           => ['required', 'boolean'],
            'property.show_empty_value'  => ['required', 'boolean'],
            'property.show_caption'      => ['required', 'boolean'],
        ];
    }

    public function mount(): void
    {
        $this->property = $this->makeProperty();
    }

    public function updatedName($value): void
    {
        if (!$this->property->getKey()) {
            $this->property->slug = SlugGenerator::getSlug($value);
            $this->skipRender();
        }
    }

    private function makeProperty(): CategoryProperty
    {
        return new CategoryProperty([
            'category_id'       => $this->category->id,
            'index'             => 'None',
            'value_restriction' => 'None',
            'visible'           => true,
            'promote'           => false,
            'show_empty_value'  => false,
            'show_caption'      => false
        ]);
    }

    public function save(): void
    {
        $this->validate();

        if (!$this->property->getKey()) {
            $this->property->position = $this->getMaxPosition() + 1;
        }

        $this->property->name = trim($this->name);
        $this->property->save();

        $this->showModal = false;
        $this->showSuccessToast('Property saved!');
    }

    private function getMaxPosition(): int
    {
        return $this->category->properties()->max('position') ?: 0;
    }

    public function create(): void
    {
        $this->property = $this->makeProperty();
        $this->reset('name');

        $this->skipRender();
        $this->showModal = true;
    }

    public function edit(CategoryProperty $property): void
    {
        $this->property = $property;
        $this->name = $property->name;

        $this->skipRender();
        $this->showModal = true;
    }

    protected function deleteRows(): ?int
    {
        return DB::transaction(fn() => CategoryProperty::whereKey($this->selected())->delete());
    }

    protected function getModelAt(int $index): Model|CategoryProperty
    {
        return CategoryProperty::where('category_id', $this->category->id)->firstWhere('position', $index);
    }

    public function getPropertiesProperty(): Collection
    {
        return CategoryProperty::query()
            ->where('category_id', $this->category->id)
            ->with('translation')
            ->withCount('translations')
            ->orderBy('position')
            ->get();
    }

    protected function getModels(): Collection
    {
        return $this->properties;
    }

    protected function findModel(int $id): CategoryProperty
    {
        return CategoryProperty::find($id);
    }

    public function render(): View
    {
        if (!$this->showModal && $this->property->getKey()) {
            $this->property = $this->makeProperty();
        }

        return view('dashboard.category.livewire.show-category-properties', [
            'properties' => $this->properties
        ]);
    }
}
