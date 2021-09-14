<?php

namespace Eshop\Livewire\Dashboard\Slide;

use Eshop\Models\Slide\Slide;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ShowSlides extends Component
{
    use WithFileUploads;

    public ?int    $slide_id = null;
    public         $image;
    public ?string $link      = null;

    public bool $showEditingModal       = false;
    public bool $showConfirmDeleteModal = false;

    public function edit(Slide $slide): void
    {
        $this->slide_id = $slide->id;
        $this->link = $slide->link;
        $this->showEditingModal = true;
    }

    public function confirmDelete(int $slide_id): void
    {
        $this->slide_id = $slide_id;
        $this->showConfirmDeleteModal = true;
    }

    public function save(): void
    {
        $rules = ['link'   => ['nullable', 'string']];
        if (!empty($this->slide_id)) {
            $rules = array_merge($rules, ['image' => ['nullable', 'image', 'dimensions:ratio=16/9,min_width:960']]);
        } else {
            $rules = array_merge($rules, ['image' => ['required', 'image', 'dimensions:ratio=16/9,min_width:960']]);
        }
        $this->validate($rules);

        $link = blank($this->link) ? null : trim($this->link);

        DB::transaction(function () use ($link) {
            if (!empty($this->slide_id)) {
                $slide = Slide::find($this->slide_id);
                $slide->link = $link;
                if ($this->image) {
                    $slide->image->delete();
                    $slide->saveImage($this->image);
                }
                $slide->save();
            } else {
                $slide = Slide::create(['link' => $link]);
                $slide->saveImage($this->image);
            }
        });

        $this->reset();
    }

    public function delete(): void
    {
        $slide = Slide::findOrFail($this->slide_id);
        $slide->delete();

        $this->reset();
    }

    public function render(): Renderable
    {
        $slides = Slide::with('image')->get();

        return view('eshop::dashboard.slide.wire.show-slides', compact('slides'));
    }
}