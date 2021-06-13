<?php

namespace Eshop\Livewire\Dashboard\Category;

use Eshop\Livewire\Dashboard\Category\Traits\HasBreadcrumbs;
use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Product\Category;
use Eshop\Services\SlugGenerator;
use Firebed\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Livewire\Traits\Datatable\WithSelections;
use Firebed\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Class ShowCategories
 * @package Eshop\Livewire\Dashboard\Category
 *
 * @property \Illuminate\Pagination\LengthAwarePaginator categories
 */
class ShowCategories extends Component
{
    use SendsNotifications;
    use TrimStrings;
    use WithFileUploads;
    use WithSelections;
    use HasBreadcrumbs;
    use DeletesRows;

    public           $image;
    public string    $name        = "";
    public string    $description = "";
    public ?Category $category    = NULL;
    public Category  $editing;

    public bool $showCategoryModal = FALSE;

    protected function rules(): array
    {
        return [
            'name'              => ['required', 'string'],
            'description'       => ['nullable', 'string'],
            'editing.parent_id' => ['nullable', 'int', 'exists:categories,id'],
            'editing.type'      => ['required', 'string', Rule::in([Category::FOLDER, Category::FILE])],
            'editing.slug'      => ['required', 'string', Rule::unique('categories', 'slug')->ignore($this->editing)],
            'editing.visible'   => ['required', 'boolean'],
            'editing.promote'   => ['required', 'boolean'],
        ];
    }

    public function mount(): void
    {
        $this->editing = $this->makeCategory(Category::FILE);
    }

    public function updatedName($value): void
    {
        if (!$this->editing->getKey()) {
            $this->editing->slug = SlugGenerator::getSlug($value);
            $this->skipRender();
        }
    }

    private function makeCategory(string $type): Category
    {
        return new Category([
            'parent_id' => $this->category->id ?? NULL,
            'type'      => $type,
            'visible'   => TRUE,
            'promote'   => FALSE
        ]);
    }

    public function create(): void
    {
        $this->reset('image', 'name', 'description');
        $this->editing = $this->makeCategory(Category::FILE);

        $this->skipRender();
        $this->showCategoryModal = TRUE;
    }

    public function createGroup(): void
    {
        $this->editing = $this->makeCategory(Category::FOLDER);

        $this->skipRender();
        $this->showCategoryModal = TRUE;
    }

    public function edit(Category $category): void
    {
        $this->reset('image');

        $this->editing = $category;
        $this->name = $this->editing->name ?? '';
        $this->description = $this->editing->description ?? '';

        $this->skipRender();
        $this->showCategoryModal = TRUE;
    }

    public function getCategoriesProperty(): LengthAwarePaginator
    {
        $query = isset($this->category)
            ? $this->category->children()
            : Category::root();

        return $query
            ->with('image', 'translation')
            ->withCount('translations')
            ->withCount(['products as products_count' => fn($q) => $q->exceptVariants()])
            ->withCount(['products as variants_count' => fn($q) => $q->onlyVariants()])
            ->orderBy('type')
            ->paginate();
    }

    public function save(): void
    {
        $this->validate();

        $this->editing->name = $this->name;
        $this->editing->description = $this->trim($this->description);

        DB::transaction(function () {
            $this->editing->save();

            if (!empty($this->image)) {
                optional($this->editing->image)->delete();
                $this->editing->saveImage($this->image);
            }
        });

        $this->showSuccessToast('Category saved!');
        $this->showCategoryModal = FALSE;
    }

    protected function deleteRows(): ?int
    {
        if (!$this->canDeleteCategories()) {
            return NULL;
        }

        return DB::transaction(function () {
            Category::findMany($this->selected())->each->delete();
            return $this->countSelected();
        });
    }

    private function canDeleteCategories(): bool
    {
        $undeletable = Category::whereKey($this->selected)->whereHas('products')->withCount('products')->with('translation')->get();
        if ($undeletable->isNotEmpty()) {
            $details = $undeletable->map(fn($c) => "$c->name ($c->products_count)")->join('<br>');

            $this->hideConfirmDelete();
            $count = $this->countSelected();
            $this->showErrorToast("Delete failed!", "<p>$count categories cannot be deleted because they are used by products.</p><p>$details</p>", FALSE);
            $this->skipRender();
            return FALSE;
        }

        return TRUE;
    }

    protected function getModels(): Collection
    {
        return $this->categories->getCollection();
    }

    public function render(): View
    {
        if (!$this->showCategoryModal && $this->editing->getKey()) {
            $this->editing = $this->makeCategory(Category::FILE);
        }

        return view('eshop::dashboard.category.livewire.show-categories', [
            'categories' => $this->categories
        ]);
    }
}
