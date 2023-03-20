<?php

namespace Eshop\Controllers\Dashboard\Category;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Controllers\Dashboard\Product\Traits\WithCategoryBreadcrumbs;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Product\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CategoryTranslationController extends Controller
{
    use WithCategoryBreadcrumbs, WithNotifications;

    public function __construct()
    {
        $this->middleware('can:Manage categories');
    }

    public function index(): View
    {
        $categories = Category::query()
            ->with('image', 'translations')
            ->get();

        return $this->view('category.translations', compact('categories'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'translations'               => ['required', 'array'],
            'translations.*.id'          => ['required', 'integer', 'exists:categories,id'],
            'translations.*.locale'      => ['required', 'string'],
            'translations.*.translation' => ['nullable', 'string', 'max:255'],
        ]);

        $translations = $request->collect('translations');
        
        DB::beginTransaction();
        try {
            $categories = Category::whereKey($translations->pluck('id'))
                ->with('translation')
                ->get()
                ->keyBy('id');
            
            foreach ($translations as $translation) {
                $category = $categories[$translation['id']];
                
                $category->setTranslation('name', $translation['translation'], $translation['locale']);
                $category->save();
            }

            $this->showSuccessNotification(trans('eshop::notifications.saved'));
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            $this->showErrorNotification(trans('eshop::notifications.error'), $e->getMessage());
        }

        return back();
    }
}
