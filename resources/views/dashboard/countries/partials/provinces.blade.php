<table id="provinces-table" class="table table-hover mb-0">
    @forelse($country->provinces as $province)
        <tr x-data="{hover: false}" x-on:mouseenter="hover=true" x-on:mouseleave="hover=false">
            <td class="ps-3 w-2r">
                <x-bs::input.checkbox id="province-{{ $province->id }}" value="{{ $province->id }}"/>
            </td>

            <td class="align-middle">
                <div class="hstack gap-3">
                    <em class="fas fa-circle border border-2 rounded-circle {{ $province->shippable ? 'text-green-400' : 'text-warning' }}" style="font-size: x-small"></em>
                    {{ $province->name }}
                </div>
            </td>

            <td class="pe-3 text-end">
                <a x-show="hover"
                   x-cloak
                   x-on:click.prevent="$dispatch('edit-province', {action: '{{ route('provinces.update', $province) }}', province: {name: '{{ $province->name }}', shippable: {{ $province->shippable ? 'true' : 'false' }}}})"
                   data-bs-toggle="offcanvas" href="#edit-province-form" class="text-primary text-decoration-none"
                >
                    <em class="fas fa-pen small"></em>
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td class="px-3 py-5 text-secondary fst-italic text-center">Δεν υπάρχουν καταχωρημένοι νομοί για αυτή την χώρα.</td>
        </tr>
    @endforelse
</table>