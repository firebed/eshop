<?php

namespace Eshop\Controllers\Customer\Blog;

use Eshop\Controllers\Customer\Controller;
use Eshop\Models\Blog\Blog;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlogController extends Controller
{
    public function show(string $locale, Blog $blog): Renderable
    {        
        return $this->view('blog.show', compact('locale', 'blog'));
    }
    
    public function click(Request $request, string $locale, Blog $blog): RedirectResponse
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('home');
        }

        $blog->increment('clicked');

        return redirect()->route('blogs.show', [$locale, $blog->slug]);
    }

    public function track(Request $request, string $locale, Blog $blog): Response
    {
        $image = base64_decode("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII");
        $response = response($image)->header('Content-Type', 'image/png');

        if (!$request->hasValidSignature()) {
            return $response;
        }

        $blog->increment('opened');

        return $response;
    }
}