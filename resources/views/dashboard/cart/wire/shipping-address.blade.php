<div>
    <x-bs::navbar expand="xxl" class="card shadow-sm flex-xxl-wrap">
        <x-bs::navbar.brand class="d-xxl-flex justify-content-xxl-between w-xxl-100 py-0">{{ __('Shipping Address') }}</x-bs::navbar.brand>

        <x-bs::navbar.toggler target="shipping-address"/>

        <x-bs::navbar.collapse id="shipping-address">
            <div class="d-grid flex-grow-1 gap-1 mt-3">
                <a href="#" class="text-decoration-none" wire:click.prevent="edit">{{ __("Edit") }}</a>

                <x-bs::group label="{{ __('To') }}" inline>
                    @isset($user_id)
                        <a href="{{ route('users.show', $user_id) }}">{{ $shippingAddress?->to }}</a>
                    @else
                        {{ $shippingAddress?->to }}
                    @endisset
                </x-bs::group>
                <x-bs::group label="Email" inline>{{ $email }}</x-bs::group>
                <x-bs::group label="{{ __('Phone') }}" inline>
                    @if($shippingAddress->phone)<a href="tel:{{ $shippingAddress?->phone}}">{{ telephone($shippingAddress?->phone) }}</a>@endif
                </x-bs::group>
                <x-bs::group label="{{ __('Street') }}" inline>{{ $shippingAddress?->street }} {{ $shippingAddress?->street_no }}</x-bs::group>
                <x-bs::group label="{{ __('City') }}" inline>{{ $shippingAddress?->city }}</x-bs::group>
                <x-bs::group label="{{ __('Postcode') }}" inline>{{ $shippingAddress?->postcode }}</x-bs::group>
                <x-bs::group label="{{ __('Province') }}" inline>{{ $shippingAddress?->province ?? '' }}</x-bs::group>
                <x-bs::group label="{{ __('Country') }}" inline>{{ $country?->name }}</x-bs::group>
                <x-bs::group label="{{ __('IP') }}" inline>
                    {{ $ip }} @if($location) ({{ $location->cityName }} / {{ $location->regionName }}) @endif
                </x-bs::group>
            </div>
        </x-bs::navbar.collapse>
    </x-bs::navbar>

    @include('eshop::dashboard.cart.partials.show.shipping-address-modal')
</div>