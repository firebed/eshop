<?php

namespace Eshop\Controllers\Dashboard\Page;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Page\Page;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(): Renderable
    {
        $pages = Page::orderBy('name')->get();

        return $this->view('page.index', compact('pages'));
    }

    public function edit(string $slug): Renderable
    {
        $name = (string) Str::of($slug)->replace('-', ' ')->ucfirst();
        
        $page = Page::whereName($slug)->firstOrNew(['name' => $name]);
        return $this->view('page.edit', compact('page', 'slug'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);

        Page::updateOrCreate($data);
    }
}
