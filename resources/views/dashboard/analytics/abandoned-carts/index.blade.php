@extends('eshop::dashboard.layouts.master')

@section('header')
    <h1 class="fs-5 mb-0">Εγκατάλειψη καλαθιών</h1>
@endsection

@push('header_scripts')
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net/">
@endpush

@push('footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.2/dist/chart.min.js"></script>
@endpush

@section('main')
    <div class="col-12 p-4 d-grid gap-3">
        @include('eshop::dashboard.analytics.partials.navbar')

        <div class="row row-cols-1 g-4">
            <div class="col">
                <x-bs::card class="h-100 flex-grow-1">
                    <x-bs::table>
                        <thead>
                        <tr>
                            <th>Email</th>
                            <th>Στάλθηκαν</th>
                            <th>Ανοίχτηκαν</th>
                            <th>Ποσοστό ανοίγματος</th>
                            <th>Ολοκληρώθηκαν</th>
                            <th>Ποσοστό ολοκλήρωσης</th>
                            <th>Έσοδα</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1η ειδοποίηση</td>
                            <td>{{ $sent_1 }}</td>
                            <td>{{ $resumed_1 }}</td>
                            @if($sent_1 > 0)
                                <td>{{ format_percent($resumed_1/($sent_1 ?: 1)) }}</td>
                            @else
                                <td>{{ format_percent(0) }}</td>
                            @endif
                            <td>{{ $submitted_1->count() }}</td>
                            @if($submitted_1->count() > 0)
                                <td>{{ format_percent($resumed_1/$submitted_1->count()) }}</td>
                            @else
                                <td>{{ format_percent(0) }}</td>
                            @endif
                            <td>{{ format_currency($submitted_1->sum()) }}</td>
                        </tr>
                        <tr>
                            <td>2η ειδοποίηση</td>
                            <td>{{ $sent_2 }}</td>
                            <td>{{ $resumed_2 }}</td>
                            @if($sent_2 > 0)
                                <td>{{ format_percent($resumed_2/($sent_2 ?: 1)) }}</td>
                            @else
                                <td>{{ format_percent(0) }}</td>
                            @endif
                            <td>{{ $submitted_2->count() }}</td>
                            @if($submitted_2->count() > 0)
                                <td>{{ format_percent($resumed_2/$submitted_2->count()) }}</td>
                            @else
                                <td>{{ format_percent(0) }}</td>
                            @endif
                            <td>{{ format_currency($submitted_2->sum()) }}</td>
                        </tr>
                        <tr>
                            <td>3η ειδοποίηση</td>
                            <td>{{ $sent_3 }}</td>
                            <td>{{ $resumed_3 }}</td>
                            @if($sent_3 > 0)
                                <td>{{ format_percent($resumed_3/($sent_3 ?: 1)) }}</td>
                            @else
                                <td>{{ format_percent(0) }}</td>
                            @endif
                            <td>{{ $submitted_3->count() }}</td>
                            @if($submitted_3->count() > 0)
                                <td>{{ format_percent($resumed_3/$submitted_3->count()) }}</td>
                            @else
                                <td>{{ format_percent(0) }}</td>
                            @endif
                            <td>{{ format_currency($submitted_3->sum()) }}</td>
                        </tr>
                        </tbody>
                    </x-bs::table>
                </x-bs::card>
            </div>
        </div>
    </div>
@endsection