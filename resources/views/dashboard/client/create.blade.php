@extends('eshop::dashboard.layouts.master')

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ __("Νέος πελάτης") }}</div>
@endsection

@section('main')
    <div class="col-12 col-xxl-8 p-4">
        <form method="post" action="{{ route('clients.store') }}" class="d-grid card shadow-sm gap-3 p-3">
            @csrf
            
            @if(request()->filled('cart_id'))
                <input type="hidden" name="redirect_to" value="{{ route('carts.invoice', request()->query('cart_id')) }}">
            @endif
            
            <div>
                <label for="name" class="form-label">Επωνυμία <span class="text-danger">*</span></label>
                <x-bs::input.text name="name" value="{{ old('name', $client->name ?? '') }}" id="name" error="name"/>
            </div>

            <div class="row">
                <div class="col">
                    <label for="vat-number" class="form-label">ΑΦΜ <span class="text-danger">*</span></label>
                    <x-bs::input.text name="vat_number" value="{{ old('vat_number', $client->vat_number ?? '') }}" id="vat-number" error="vat_number"/>
                </div>

                <div class="col">
                    <label for="tax-authority" class="form-label">ΔΟΥ</label>
                    <x-bs::input.text name="tax_authority" value="{{ old('tax_authority', $client->tax_authority ?? '') }}" id="tax-authority" error="tax_authority"/>
                </div>
            </div>

            <div>
                <label for="job" class="form-label">Επάγγελμα <span class="text-danger">*</span></label>
                <x-bs::input.text name="job" value="{{ old('job', $client->job ?? '') }}" id="job" error="job"/>
            </div>

            <div class="row">
                <div class="col-4">
                    <label for="country" class="form-label">Χώρα <span class="text-danger">*</span></label>
                    <x-bs::input.select name="country" id="country" error="country">
                        <option value="GR" @if(old('country', $client->country ?? '') === 'GR') selected @endif>Ελλάδα</option>
                        <option value="CY" @if(old('country', $client->country ?? '') === 'CY') selected @endif>Κύπρο</option>
                    </x-bs::input.select>
                </div>

                <div class="col">
                    <label for="city" class="form-label">Πόλη <span class="text-danger">*</span></label>
                    <x-bs::input.text name="city" value="{{ old('city', $client->city ?? '') }}" id="city" error="city"/>
                </div>
            </div>

            <div class="row">
                <div class="col-8">
                    <label for="street" class="form-label">Οδός <span class="text-danger">*</span></label>
                    <x-bs::input.text name="street" value="{{ old('street', $client->street ?? '') }}" id="street" error="street"/>
                </div>

                <div class="col">
                    <label for="street_no" class="form-label">Αριθμός οδού</label>
                    <x-bs::input.text name="street_number" value="{{ old('street_number', $client->street_number ?? '') }}" id="street_no" error="street_number"/>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label for="postcode" class="form-label">Ταχυδρομικό κώδικας <span class="text-danger">*</span></label>
                    <x-bs::input.text name="postcode" value="{{ old('postcode', $client->postcode ?? '') }}" id="postcode" error="postcode"/>
                </div>

                <div class="col">
                    <label for="phone-number" class="form-label">Τηλέφωνο <span class="text-danger">*</span></label>
                    <x-bs::input.text name="phone_number" value="{{ old('phone_number', $client->phone_number ?? '') }}" id="phone-number" error="phone_number"/>
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-alt">Αποθήκευση</button>
            </div>
        </form>
    </div>
@endsection
