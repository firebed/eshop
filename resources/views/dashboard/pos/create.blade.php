@extends('eshop::dashboard.layouts.master')

@section('main')
    <div class="col-12 g-0">
        <div class="row row-cols-2 g-0">
            <div class="col-7 p-4 border-end overflow-auto" style="height: calc(100vh - 3.5rem)">
                <div class="row row-cols-5 g-3"
                     x-data="{
                        load(parent = null) {
                            axios.get('/dashboard/pos/categories', {params:{parent}})
                            .then(r => $el.innerHTML = r.data)
                        }
                    }">
                    @include('eshop::dashboard.pos.partials.pos-categories')
                </div>
            </div>

            <div class="col-5 bg-white p-4 overflow-auto" style="height: calc(100vh - 3.5rem);">
            </div>
        </div>
    </div>
@endsection
