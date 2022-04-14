@extends('eshop::dashboard.layouts.master')

@section('header')
    <h1 class="fs-5 mb-0">Ειδοποιήσεις</h1>
@endsection

@section('main')
    <div class="col-12 col-xxl-8 mx-auto p-4 d-grid gap-3">
        <div class="table-responsive bg-white rounded-3 border">
            <table class="table table-hover small">
                @foreach($notifications as $notification)
                    <tr>
                        <td>{!! $notification->text !!}</td>
                        <td class="text-end text-secondary">{{ $notification->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        
        <caption>
            <x-eshop::pagination :paginator="$notifications"/>
        </caption>
    </div>
@endsection
