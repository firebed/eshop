<div class="table-responsive bg-white rounded-3 border">
    <x-bs::table hover class="small">
        <tbody>
        @foreach($notifications as $notification)
            <tr wire:key="not-{{ $notification->id }}" @if($notification->viewed_at === null) class="fw-bold" @endif style="background-color: #f2f6fc">
                <td>
                    <a wire:click.prevent="show({{ $notification->id }})" href="#" class="d-block text-decoration-none text-dark">{!! $notification->text !!}</a>
                </td>

                <td class="text-end text-secondary" style="width: 8rem">{{ $notification->created_at->format('d/m/y H:i') }}</td>
            </tr>
        @endforeach
        </tbody>

        <caption>
            <x-eshop::wire-pagination :paginator="$notifications"/>
        </caption>
    </x-bs::table>

    <x-bs::modal wire:model.defer="showModal" size="lg">
        @isset($activeNotification)
            <x-bs::modal.header>{!! $this->activeNotification->text !!}</x-bs::modal.header>

            @if($payouts->isNotEmpty())
                <x-bs::modal.body class="bg-gray-100">
                    @include('eshop::dashboard.notification.partials.payout-template')

                    @if($attachment = $activeNotification->metadata['attachment'] ?? null)
                        <div class="p-3">
                            <a href="{{ route('notifications.download', $activeNotification) }}"><em class="fas fa-download me-2"></em>Κατέβασμα αρχείου</a>
                        </div>
                    @endif
                </x-bs::modal.body>
            @endif
        @endisset
    </x-bs::modal>
</div>
