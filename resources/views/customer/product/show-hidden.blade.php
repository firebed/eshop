@php($title = $product->seo->title ?? $product->trademark ?? "")
@php($description = $product->seo->description ?? null)

@extends('eshop::customer.layouts.master', ['title' =>  $title])

{{--@include('eshop::customer.product.partials.product-meta')--}}

@section('main')
    <x-eshop-category-breadcrumb :category="$category" :product="$product"/>

    <main class="container-fluid bg-white">
        <div class="container-xxl py-4">
            <div class="col-12 col-xl-8 col-xxl-7 mx-auto">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <div class="d-flex justify-content-center p-3">
                            @if($src = $product->image?->url('sm'))
                                <img src="{{ $src }}" alt="" class="img-fluid rounded">
                            @endif
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card-body">
                                <h1 class="fs-5">{{ $product->trademark }}</h1>

                                <p>Το προϊόν είναι κρυφό προς το κοινό και οι πελάτες μεταφέρονται
                                    στη σελίδα "<span class="fw-500">404: Η σελίδα που ψάχνετε δε βρέθηκε!</span>"</p>

                                <p>Πατήστε το παρακάτω κουμπί για να κάνετε το προϊόν ορατό και πάλι.</p>

                                <form x-data="{ submitting: false }" x-on:submit="submitting = true" action="{{ route('products.toggle-visibility', $product) }}" method="post" class="mb-3">
                                    @csrf

                                    <button x-bind:disabled="submitting" type="submit" class="btn btn-primary">
                                        <div x-cloak x-show="submitting" class="spinner-border spinner-border-sm"></div>

                                        Αλλαγή σε ορατό
                                    </button>
                                </form>

                                <p>
                                    <a href="{{ route('products.edit', $product) }}" class="text-decoration-none">
                                        Επεξεργασία στη σελίδα διαχείρισης
                                    </a>
                                </p>

                                <p class="text-muted small mb-0">
                                    <em class="fas fa-exclamation-circle"></em>
                                    Βλέπετε αυτό το μήνυμα επειδή είστε διαχειριστής του συστήματος.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
