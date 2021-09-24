@props([
    'company' => 'Control Panel'
])

<div {{ $attributes->class('col-auto w-100 w-xl-17r px-0 sticky-top') }} x-data="{ show: false }">
    <div class="d-flex justify-content-between align-items-center sticky-top px-3" style="height: 3.5rem; background-color: rgb(49, 58, 70);">
        <div class="fs-5 fw-500 text-light d-flex justify-content-between">
            <div class="bg-pink-500 rounded w-2r text-center me-2">
                {{ Str::of($company)->ucfirst()->substr(0, 1) }}
            </div>
            <div>{{ $company }}</div>
        </div>

        <x-bs::button.warning class="d-xl-none" x-on:click="show = !show">
            <em class="fa fa-bars"></em>
        </x-bs::button.warning>
    </div>

    <div :class="{ 'show': show }" class="sidebar sticky-xl-top w-xl-17r" data-bs-backdrop="false" style="--top: 3.5rem">
        <div class="h-100" data-simplebar>
            <div class="d-grid">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
