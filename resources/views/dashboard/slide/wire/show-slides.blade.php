<div class="col-12 col-xxl-8 mx-auto p-4 d-grid gap-3">
    <div class="hstack gap-3 justify-content-between">
        <h1 class="fs-3 mb-0">Διαφάνειες</h1>

        <button wire:click.prevent="$toggle('showEditingModal')" class="btn btn-primary rounded-circle">
            <em class="fas fa-plus"></em>
        </button>
    </div>

    <div class="table-responsive">
        <x-bs::table>
            <thead>
            <tr>
                <th class="w-10r">Διαφάνεια</th>
                <th>Διαστάσεις</th>
                <th>Σύνδεσμος</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            @foreach($slides as $slide)
                <tr wire:key="slide-{{ $slide }}">
                    <td>
                        <div class="ratio ratio-16x9">
                            <img src="{{ $slide->image->url('sm') }}" alt="" class="rounded">
                        </div>
                    </td>

                    <td>{{ $slide->image->width }}x{{ $slide->image->height }}</td>

                    <td>{{ $slide->link }}</td>

                    <td>
                        <div class="hstack gap-1 justify-content-end">
                            <button wire:click="edit({{ $slide->id }})" class="btn btn-sm btn-secondary rounded-circle">
                                <em class="fas fa-pen"></em>
                            </button>

                            <button wire:click.prevent="confirmDelete({{ $slide->id }})" class="btn btn-sm btn-secondary rounded-circle">
                                <em class="far fa-trash-alt"></em>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>

            <caption>
                <x-eshop::pagination :paginator="$slides"/>
            </caption>
        </x-bs::table>
    </div>

    @include('eshop::dashboard.slide.partials.slide-modal')
    @include('eshop::dashboard.slide.partials.slide-delete-modal')
</div>