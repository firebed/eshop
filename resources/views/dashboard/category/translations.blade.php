@extends('eshop::dashboard.layouts.master')

@section('header')
    <h1 class="fs-5 mb-0">{{ __("Categories") }}</h1>
@endsection

@section('main')
    <div class="col-12 col-xxl-8 p-4 mx-auto d-grid gap-3">
        <form action="{{ route('categories.translations.update') }}" method="post">
            @csrf
            @method('patch')

            <div class="mb-3">
                <button class="btn btn-primary" type="submit">{{ __("Save") }}</button>
            </div>

            <div class="overflow-auto">
                <x-bs::table>
                    @foreach($categories as $category)
                        <tr>
                            <td class="align-middle">{{ $category->translations->firstWhere('locale', 'el')->translation }}</td>
                            <td>
                                <input type="hidden" name="translations[{{ $loop->index }}][id]" value="{{ $category->id }}" class="form-control">
                                <input type="hidden" name="translations[{{ $loop->index }}][locale]" value="en" class="form-control">
                                <input type="text" name="translations[{{ $loop->index }}][translation]" value="{{ $category->translations->firstWhere('locale', 'en')?->translation }}" class="form-control">
                            </td>
                        </tr>
                    @endforeach
                </x-bs::table>
            </div>
        </form>
    </div>
@endsection
