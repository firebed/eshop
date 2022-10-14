<div class="table-responsive bg-white rounded-3 border">
    <table class="table table-hover small">
        @foreach($notifications as $notification)
            <tr wire:key="not-{{ $notification->id }}">
                <td>
                    <a wire:click.prevent="show({{ $notification->id }})" href="#" class="d-block text-decoration-none text-dark">{!! $notification->text !!}</a>
                </td>

                <td class="text-end text-secondary" style="width: 8rem">{{ $notification->created_at->format('d/m/y H:i') }}</td>
            </tr>
        @endforeach
    </table>

    <caption>
        <x-eshop::pagination :paginator="$notifications"/>
    </caption>

    <x-bs::modal wire:model.defer="showModal">
        <x-bs::modal.body>
            @isset($activeNotification)
                <table class="table table-hover table-striped">
                    @foreach($activeNotification->metadata as $key => $value)
                        <tr>
                            <td>{{ $key }}</td>
                            <td>{{ $value }}</td>
                        </tr>
                    @endforeach
                </table>
            @endisset
        </x-bs::modal.body>
        
        <x-bs::modal.footer>
            <x-bs::modal.close-button>Κλείσιμο</x-bs::modal.close-button>
        </x-bs::modal.footer>
    </x-bs::modal>
</div>
