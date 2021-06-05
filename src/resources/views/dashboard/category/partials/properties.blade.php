@isset($property)
    @include('eshop::dashboard.category.livewire.modals.property-modal')
@endisset

<div class="card shadow-sm">
    <div class="card-body">
        <h6 class="d-flex justify-content-between mb-2">
            {{ __("Properties") }}
            <a href="#" class="text-decoration-none" wire:click.prevent="createProperty">
                <i wire:loading wire:target="createProperty" class="fa fa-spinner fa-spin"></i>
                <span wire:loading.remove wire:target="createProperty">{{ __("Add property") }}</span>
            </a>
        </h6>
        <div class="table-responsive position-relative">
            <div wire:loading wire:target="moveUp, moveDown" class="position-absolute w-100 h-100 top-0 start-0" style="background-color: rgba(255, 255, 255, .4)">
                <i class="fa fa-spinner fa-spin position-absolute top-50 start-50 translate-middle"></i>
            </div>
            <table class="table table-sm table-hover mb-0">
                @foreach($properties as $property)
                    <tr>
                        <td class="align-middle">
                            <div>{{ $property->name }}</div>
                            @if ($property->choices->isNotEmpty())
                                <div class="small text-secondary">{{ $property->choices->take(5)->pluck('name')->join(', ') }}</div>
                            @endif
                        </td>
                        <td class="text-end align-middle">
                            <button type="button" class="btn btn-sm btn-light" wire:click.prevent="editProperty({{ $property->id }})" wire:loading.attr="disabled" wire:target="editProperty({{ $property->id}})">
                                <i wire:loading wire:target="editProperty({{ $property->id}})" class="fa fa-spinner fa-spin"></i>
                                <i wire:loading.remove wire:target="editProperty({{ $property->id}})" class="far fa-edit"></i>
                            </button>
                            <a href="#" class="btn btn-sm btn-light" wire:click.prevent="moveUp({{ $property->id }})"><i class="fa fa-chevron-up"></i></a>
                            <a href="#" class="btn btn-sm btn-light" wire:click.prevent="moveDown({{ $property->id }})"><i class="fa fa-chevron-down"></i></a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
