@extends('eshop::dashboard.layouts.master')

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ __("Simplify") }}</div>
@endsection

@section('main')
    <div class="col-6 p-4">
        <form autocomplete="off" method="post" action="{{ route('user-variables.store') }}" class="d-grid gap-4">
            @csrf

            <div class="vstack gap-4 small">
                @foreach($variables as $name => $values)
                    <div class="vstack gap-1">
                        <div class="fw-bold">{{ $name }}</div>
                        @foreach($values as $key => $value)
                            <div class="row align-items-baseline">
                                <label for="simplify-sandbox-public-key" style="width: 15rem">{{ $key }}</label>
                                <div class="col">
                                    <input name="variables[{{ $key }}]" value="{{ old($key, $value ?? '') }}" class="form-control form-control-sm font-monospace" type="text" id="simplify-sandbox-public-key">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-alt">{{ __("Save") }}</button>
            </div>
        </form>
    </div>
@endsection
