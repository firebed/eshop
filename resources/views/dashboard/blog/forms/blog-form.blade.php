<x-bs::input.group for="name" label="Τίτλος">
    @if(!isset($blog))
        <x-bs::input.text x-ref="title" x-on:input="updateSlug()" value="{{ old('title', $blog->title ?? '') }}" name="title" error="title" placeholder="Τίτλος" id="title" required/>
    @else
        <x-bs::input.text x-ref="title" value="{{ old('title', $blog->title ?? '') }}" name="title" error="title" placeholder="Τίτλος" id="title" required/>
    @endif
</x-bs::input.group>

<x-bs::input.group for="image" label="Εικόνα">
    <div class="d-flex gap-3 align-items-start">
        @if(isset($blog->image))
            <img src="{{ $blog->image->url('md') }}" alt="" class="img-fluid rounded" width="100">
        @endif

        <x-bs::input.file name="image" error="image"/>
    </div>
</x-bs::input.group>

<x-bs::input.group for="slug" label="Slug">
    <div class="d-flex">
        <x-bs::input.text x-ref="slug" value="{{ old('slug', $blog->slug ?? '') }}" name="slug" error="slug" placeholder="Slug" id="slug" required/>
        <x-bs::button.dark @click.prevent="updateSlug()" class="ms-2">Ανανέωση</x-bs::button.dark>
    </div>
</x-bs::input.group>

<x-bs::input.group for="description" label="Περιγραφή">
    <x-bs::input.textarea name="description" id="description" rows="3">{{ old('description', $blog->description ?? '') }}</x-bs::input.textarea>
</x-bs::input.group>

<x-bs::input.group for="content" label="Περιεχόμενο">
    <textarea name="content" id="content" rows="20">{{ old('content', $blog->content ?? '') }}</textarea>
</x-bs::input.group>