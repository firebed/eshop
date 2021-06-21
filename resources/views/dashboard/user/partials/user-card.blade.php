<x-bs::card>
    <x-bs::card.body>
        <div class="fs-5 fw-500 mb-3">{{ __('Primary details') }}</div>

        <div class="d-grid gap-2">
            <x-bs::group label="{{ __('ID') }}" inline>{{ $user->id }}</x-bs::group>
            <x-bs::group label="{{ __('First name') }}" inline>{{ $user->first_name }}</x-bs::group>
            <x-bs::group label="{{ __('Last name') }}" inline>{{ $user->last_name }}</x-bs::group>
            <x-bs::group label="{{ __('Email') }}" inline>{{ $user->email }}</x-bs::group>
            <x-bs::group label="{{ __('Email verified') }}" inline>{{ optional($user->email_verified_at)->format('d/m/Y H:i:s') }}</x-bs::group>
            <x-bs::group label="{{ __('Registered') }}" inline>{{ $user->created_at->format('d/m/Y H:i:s') }}</x-bs::group>
            <x-bs::group label="{{ __('Last login') }}" inline>{{ optional($user->last_login)->format('d/m/Y H:i:s') }}</x-bs::group>
        </div>
    </x-bs::card.body>
</x-bs::card>
