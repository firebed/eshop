@extends('eshop::customer.layouts.master', ['title' =>  __('My companies')])

@section('main')
    <div class="container-fluid bg-pink-500">
        <div class="container pt-4">
            <div class="row py-4">
                <div class="col fs-3 text-light">{{ user()->fullName }}</div>
            </div>
        </div>
    </div>

    @include('eshop::customer.account.partials.account-navbar')

    <div class="container-fluid py-3" @if(session('success')) x-data x-init="$dispatch('toast-notification', {type: 'success', title: '{{ session('success') }}', content: '', autohide: true})" @endif>
        <div class="container">
            <h1 class="fs-4 fw-normal mb-4">{{ __("My companies") }}</h1>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4 mb-4">
                <div class="col">
                    <x-bs::card class="h-100 bg-transparent shadow-none border-2" style="border-style: dashed">
                        <x-bs::card.body class="d-grid align-items-center">
                            <div class="d-grid gap-4">
                                <div class="text-secondary">{{ __("You can add a new company and use it in all your next purchases.") }}</div>
                                <a href="{{ route('account.companies.create', app()->getLocale()) }}" class="btn btn-primary">{{ __("Add new company") }}</a>
                            </div>
                        </x-bs::card.body>
                    </x-bs::card>
                </div>

                @foreach($companies as $company)
                    <div class="col">
                        <x-bs::card class="h-100 shadow-none position-relative">
                            <div class="position-absolute end-0 p-2">
                                <div class="dropdown">
                                    <button class="btn btn-link text-secondary shadow-none" type="button" id="company-{{ $loop->index }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <em class="fas fa-ellipsis-v"></em>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="company-{{ $loop->index }}">
                                        <li>
                                            <div class="dropdown-item">
                                                <form action="{{ route('account.companies.destroy', [app()->getLocale(), $company]) }}" method="POST" class="w-100" onsubmit="return confirm('{{ __("Delete company?") }}');">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="px-3 py-2 shadow-none bg-transparent text-start border-0 w-100" style="color: inherit">{{ __("Delete") }}</button>
                                                </form>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <x-bs::card.body class="d-grid">
                                <div class="mb-3 fs-5 fw-500">{{ $company->name }}</div>

                                <div class="d-grid mb-3 text-secondary">
                                    <div class="fw-500">{{ $company->vat_number }}</div>
                                    <div>{{ $company->address->full_name }}</div>
                                    <div>{{ $company->address->full_street }}</div>
                                    <div>{{ $company->address->city }}, {{ $company->address->postcode }}</div>
                                    <div>{{ $company->address->country->name ?? '' }}</div>
                                    <div>{{ __("Phone") }}: {{ $company->address->phone }}</div>
                                </div>

                                <div class="d-grid mt-auto">
                                    <a href="{{ route('account.companies.edit', [app()->getLocale(), $company]) }}" class="btn btn-secondary">{{ __("Edit") }}</a>
                                </div>
                            </x-bs::card.body>
                        </x-bs::card>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
