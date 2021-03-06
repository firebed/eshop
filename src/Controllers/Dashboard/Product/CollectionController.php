<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Product\Collection;
use Eshop\Models\Product\Product;
use Eshop\Requests\Dashboard\Product\CollectionDeleteRequest;
use Eshop\Requests\Dashboard\Product\CollectionRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    use WithNotifications;

    public function __construct()
    {
        $this->middleware('can:Manage collections');
    }

    public function index(): Renderable
    {
        $collections = Collection::withCount('products')->get();

        return $this->view('collection.index', compact('collections'));
    }

    public function create(): Renderable
    {
        return $this->view('collection.create');
    }

    public function store(CollectionRequest $request): RedirectResponse
    {
        $collection = Collection::create(array_merge($request->validated(), ['slug' => slugify($request->input('name'))]));

        $this->showSuccessNotification(trans('eshop::collection.notifications.saved'));
        return redirect()->route('collections.edit', $collection);
    }

    public function edit(Collection $collection): Renderable
    {
        $products = $collection->products()
            ->with('translation', 'category.translation', 'parent.translation', 'image')
            ->orderBy('category_id')
            ->get();

        return $this->view('collection.edit', compact('collection', 'products'));
    }

    public function update(CollectionRequest $request, Collection $collection): RedirectResponse
    {
        $collection->update(array_merge($request->validated(), ['slug' => slugify($request->input('name'))]));

        $this->showSuccessNotification(trans('eshop::collection.notifications.saved'));
        return back();
    }

    public function destroy(Request $request, Collection $collection): RedirectResponse
    {
        $request->validate([
            'delete_name' => ['required', 'string', 'confirmed'],
        ]);
        $collection->delete();

        $this->showSuccessNotification(trans('eshop::collection.notifications.deleted'));
        return redirect()->route('collections.index');
    }

    public function destroyMany(CollectionDeleteRequest $request): RedirectResponse
    {
        $count = Collection::whereKey($request->validated())->delete();

        $this->showSuccessNotification(trans_choice('eshop::collection.notifications.deleted_many', $count, ['number' => $count]));
        return redirect()->route('collections.index');
    }

    public function detachProduct(Request $request, Collection $collection, Product $product): RedirectResponse|JsonResponse
    {
        $collection->products()->detach($product);

        if ($request->expectsJson()) {
            return response()->json();
        }

        $this->showSuccessNotification(trans('eshop::collection.notifications.product_detached'));
        return redirect()->route('collections.edit', $collection);
    }
}
