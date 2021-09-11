<x-bs::card>
    <x-bs::card.body class="position-relative">
        <a href="{{ route('countries.edit', $country) }}" class="position-absolute btn btn-primary rounded-circle shadow-sm" style="right: 1rem">
            <em class="fas fa-pen small"></em>
        </a>

        <div class="row mb-2">
            <div class="col-4 small text-secondary">{{ __("Country") }}</div>
            <div class="col">{{ $country->name }}</div>
        </div>

        <div class="row mb-2">
            <div class="col-4 small text-secondary">{{ __("Κωδικός") }}</div>
            <div class="col">{{ $country->code }}</div>
        </div>

        <div class="row mb-2">
            <div class="col-4 small text-secondary">{{ __("Ζώνη ώρας") }}</div>
            <div class="col">{{ $country->timezone }}</div>
        </div>

        <div class="row mb-2">
            <div class="col-4 small text-secondary">{{ __("Ορατό") }}</div>
            <div class="col">{{ $country->visible ? "Ναι" : "Όχι" }}</div>
        </div>

        <div class="row mb-2">
            <div class="col-4 small text-secondary">{{ __("Created at") }}</div>
            <div class="col">{{ $country->created_at?->isoFormat('LlL') }}</div>
        </div>

        <div class="row">
            <div class="col-4 small text-secondary">{{ __("Updated at") }}</div>
            <div class="col">{{ $country->updated_at?->isoFormat('LlL') }}</div>
        </div>
    </x-bs::card.body>
</x-bs::card>