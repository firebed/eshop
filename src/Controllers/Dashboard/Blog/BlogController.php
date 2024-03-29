<?php

namespace Eshop\Controllers\Dashboard\Blog;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Mail\BlogMail;
use Eshop\Models\Blog\Blog;
use Eshop\Models\Cart\Cart;
use Eshop\Requests\Dashboard\Blog\BlogRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\PendingMail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Swift_Message;

class BlogController extends Controller
{
    use WithNotifications;

    public function __construct()
    {
        $this->middleware('can:Manage blogs');
    }

    public function index(): Renderable
    {
        $blogs = Blog::latest()->paginate();

        return $this->view('blog.index', compact('blogs'));
    }

    public function create(): Renderable
    {
        return $this->view('blog.create');
    }

    public function store(BlogRequest $request): RedirectResponse
    {
        $blog = Blog::create($request->validated());
        if ($request->hasFile('image')) {
            $blog->saveImage($request->file('image'));
        }

        $this->showSuccessNotification('Δημιουργία επιτυχής');
        return redirect()->route('blogs.edit', $blog);
    }

    public function edit(Blog $blog): Renderable
    {
        $mailCount = Cart::whereNotNull('email')->distinct()->count('email');

        return $this->view('blog.edit', compact('blog', 'mailCount'));
    }

    public function update(BlogRequest $request, Blog $blog): RedirectResponse
    {
        $blog->update($request->validated());
        if ($request->hasFile('image')) {
            $blog->image?->delete();
            $blog->saveImage($request->file('image'));
        }

        $this->showSuccessNotification('Ενημέρωση επιτυχής');
        return redirect()->route('blogs.edit', $blog);
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        $blog->delete();

        Storage::disk('blogs')->deleteDirectory($blog->id);

        $this->showSuccessNotification('Διαγραφή επιτυχής');
        return redirect()->route('blogs.index');
    }

    public function upload(Request $request, Blog $blog): JsonResponse
    {
        $request->validate(['image' => ['required', 'image']]);


        $filename = $request->file('image')->store($blog->id, 'blogs');
        $path = Storage::disk('blogs')->url($filename);

        return response()->json(['location' => asset($path)]);
    }

    public function deleteUploadedImage(Request $request, Blog $blog): JsonResponse
    {
        $filename = basename($request->input('src'));

        Storage::disk('blogs')->delete($blog->id . '/' . $filename);

        return response()->json();
    }

    public function publish(Blog $blog)
    {
        //$emails = Cart::whereNotNull('email')->distinct()->pluck('email');
        //$emails = collect(['okan.giritli@gmail.com', 'plexoudes@gmail.com', 'gizemonbasi@gmail.com', 'joseph.nteli@gmail.com', 'ebocivil@gmail.com']);
        $emails = collect(['okan.giritli@gmail.com']);

        $message = new BlogMail($blog);
        $returnPath = config('mail.return_path');
        if ($returnPath) {
            $message->withSwiftMessage(function (Swift_Message $message) use ($returnPath) {
                $message->getHeaders()->addTextHeader('Return-Path', $returnPath);
            });
        }

        $bccPerEmail = 500;
        $emails->chunk($bccPerEmail)->each(function (Collection $chunk) use ($message) {
            Mail::to($chunk->shift())
                ->when($chunk->isNotEmpty(), fn(PendingMail $mail) => $mail->bcc($chunk->all()))
                ->send($message);
        });

        $blog->increment('sent', $emails->count());

        return "ok";
    }
}
