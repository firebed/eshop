<div class="card shadow-sm"
     x-data="{ isUploading: false, progress: 0 }"
     x-on:livewire-upload-start="isUploading = true"
     x-on:livewire-upload-finish="isUploading = false"
     x-on:livewire-upload-error="isUploading = false"
     x-on:livewire-upload-progress="progress = $event.detail.progress"
>
    <div class="card-body">
        <div class="ratio ratio-16x9 mb-3">
            @if($image)
                <img class="img-middle" src="{{ $image->temporaryUrl() }}" alt="{{ $name }}">
            @elseif($media)
                <img class="img-middle" src="{{ $media->url('sm') }}" alt="{{ $name }}">
            @endif
            <div wire:loading wire:target="saveImage" style="background-color: rgba(255, 255, 255, .4)">
                <i class="fa fa-spin fa-spinner position-absolute top-50 start-50 translate-middle text-primary"></i>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <input hidden name="icon" type="file" wire:model="image" x-ref="input">
            <button x-bind:disabled="isUploading" id="upload-button" type="button" class="btn btn-sm btn-secondary" @click="$refs.input.click()">
                <i wire:target="saveImage" class="fa fa-image"></i>
                <span>{{ __("Upload") }}</span>
            </button>
            <div x-cloak x-show="isUploading">
                <progress max="100" x-bind:value="progress"></progress>
            </div>
        </div>
    </div>
</div>
