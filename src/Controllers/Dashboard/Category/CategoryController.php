<?php

namespace Eshop\Controllers\Dashboard\Category;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Controllers\Dashboard\Product\Traits\WithCategoryBreadcrumbs;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Product\Category;
use Eshop\Requests\Dashboard\Category\CategoryMoveRequest;
use Eshop\Requests\Dashboard\Category\CategoryRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class CategoryController extends Controller
{
    use WithCategoryBreadcrumbs, WithNotifications;

    public function __construct()
    {
        $this->middleware('can:Manage categories');
    }

    public function index(): View
    {
        $categories = Category::root()
            ->with('image', 'translation')
            ->withCount('translations')
            ->orderByDesc('type')
            ->get();

        return $this->view('category.index', compact('categories'));
    }

    public function create(Request $request): RedirectResponse|Renderable
    {
        if ($request->filled('parentId')) {
            $parent = Category::findOrFail($request->parentId);
            $categories = $parent->children();
        } else {
            $categories = Category::root();
        }

        $categories = $categories->with('image', 'translation')
            ->withCount('translations')
            ->orderByDesc('type')
            ->get();

        return $this->view('category.create', [
            'parent'      => $parent ?? null,
            'categories'  => $categories,
            'breadcrumbs' => isset($parent) ? $this->getCategoryBreadcrumbs($parent) : []
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        try {
            $category = new Category();
            $category->fill($request->only($category->getFillable()));

            DB::transaction(function () use ($request, $category) {
                $category->save();

                $category->seo()->create($request->input('seo'));

                if ($request->hasFile('image')) {
                    $this->replaceImage($category, $request->file('image'));
                }
            });

            $this->showSuccessNotification(trans('eshop::notifications.saved'));
            return redirect()->route('categories.edit', $category);
        } catch (Throwable $e) {
            $request->flash();
            $this->showErrorNotification(trans('eshop::notifications.error'), $e->getMessage());
            return back();
        }
    }

    public function edit(Category $category): Renderable
    {
        if ($category->isFolder()) {
            $categories = $category->children()
                ->with('image', 'translation')
                ->withCount('translations')
                ->orderByDesc('type')
                ->get();
        } else {
            $properties = $category->properties()
                ->with('translation')
                ->withCount('translations')
                ->orderBy('position')
                ->get();
        }

        return $this->view('category.edit', [
            'category'    => $category,
            'categories'  => $categories ?? [],
            'properties'  => $properties ?? [],
            'breadcrumbs' => $this->getCategoryBreadcrumbs($category)
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request, $category) {
                $category->update($request->only($category->getFillable()));

                $category->seo()->updateOrCreate([], $request->input('seo'));

                if ($request->hasFile('image')) {
                    $this->replaceImage($category, $request->file('image'));
                }
            });

            $this->showSuccessNotification(trans('eshop::notifications.saved'));
        } catch (Throwable $e) {
            $this->showErrorNotification(trans('eshop::notifications.error'), $e->getMessage());
        }

        return back();
    }

    public function destroy(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'delete_name' => ['required', 'string', 'confirmed', 'in:' . $category->name],
        ]);

        try {
            DB::transaction(static fn() => $category->delete());

            $this->showSuccessNotification(trans('eshop::notifications.deleted'));
            return $category->parent_id
                ? redirect()->route('categories.edit', $category->parent_id)
                : redirect()->route('categories.index');
        } catch (Throwable) {
            $this->showErrorNotification(trans('eshop::category.unable_to_delete'));
            return back();
        }
    }

    public function destroyMany(Request $request): RedirectResponse
    {
        $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['required', 'integer', 'exists:categories,id'],
        ]);

        $categories = Category::whereKey($request->input('ids'))->get();
        try {
            DB::transaction(static fn() => $categories->each->delete());

            $count = $categories->count();
            $this->showSuccessNotification(trans_choice('eshop::category.notifications.deleted_many', $count, ['number' => $count]));
        } catch (Throwable) {
            $this->showErrorNotification(trans('eshop::category.unable_to_delete'));
        }

        return back();
    }

    public function move(CategoryMoveRequest $request): RedirectResponse
    {
        $sources = $request->input('source_ids');
        $targetId = $request->input('target_id');
        try {
            DB::transaction(static fn() => Category::whereKey($sources)->update(['parent_id' => $targetId]));
            $this->showSuccessNotification(trans('eshop::category.notifications.moved'));
        } catch (Throwable $e) {
            $request->flashOnly('source_ids');
            $this->showErrorNotification(trans('eshop::notifications.error'), $e->getMessage());
        }

        return back();
    }

    public function expand(?int $id = null): JsonResponse
    {
        $query = $id === null
            ? Category::root()
            : Category::where('parent_id', $id);

        return response()->json($query->with('translation')->get()->pluck('name', 'id'));
//        return response()->json($query->folders()->with('translation')->get()->pluck('name', 'id'));
    }

    private function replaceImage($imageable, UploadedFile $image): void
    {
        $oldImage = $imageable->image;
        $oldImage?->delete();

        $imageable->saveImage($image);
    }
}
