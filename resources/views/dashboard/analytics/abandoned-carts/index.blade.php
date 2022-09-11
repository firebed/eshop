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
                            <th>Clicked</th>
                            <th>Ολοκληρώθηκαν</th>
                            <th>Έσοδα</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1η ειδοποίηση</td>
                            <td>{{ $sent_1 }}</td>
                            <td>
                                {{ $opened_1 }}
                                ({{ format_percent($sent_1 === 0 ? 0 : $opened_1/$sent_1) }})
                            </td>
                            <td>
                                {{ $resumed_1 }}
                                ({{ format_percent($sent_1 === 0 ? 0 : $resumed_1/$sent_1) }})
                            </td>
                            <td>
                                {{ $cnt = $submitted_1->count() }}
                                ({{ format_percent($sent_1 === 0 ? 0 : $cnt/$sent_1) }})
                            </td>
                            <td>{{ format_currency($submitted_1->sum()) }}</td>
                        </tr>
                        <tr>
                            <td>2η ειδοποίηση</td>
                            <td>{{ $sent_2 }}</td>
                            <td>
                                {{ $opened_2 }}
                                ({{ format_percent($sent_2 === 0 ? 0 : $opened_2/$sent_2) }})
                            </td>
                            <td>
                                {{ $resumed_2 }}
                                ({{ format_percent($sent_2 === 0 ? 0 : $resumed_2/$sent_2) }})
                            </td>
                            <td>
                                {{ $cnt = $submitted_2->count() }}
                                ({{ format_percent($sent_2 === 0 ? 0 : $cnt/$sent_2) }})
                            </td>
                            <td>{{ format_currency($submitted_2->sum()) }}</td>
                        </tr>
                        <tr>
                            <td>3η ειδοποίηση</td>
                            <td>{{ $sent_3 }}</td>
                            <td>
                                {{ $opened_3 }}
                                ({{ format_percent($sent_3 === 0 ? 0 : $opened_3/$sent_3) }})
                            </td>
                            <td>
                                {{ $resumed_3 }}
                                ({{ format_percent($sent_3 === 0 ? 0 : $resumed_3/$sent_3) }})
                            </td>
                            <td>
                                {{ $cnt = $submitted_3->count() }}
                                ({{ format_percent($sent_3 === 0 ? 0 : $cnt/$sent_3) }})
                            </td>
                            <td>{{ format_currency($submitted_3->sum()) }}</td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td></td>
                            <td>{{ $sent = $sent_1 + $sent_2 + $sent_3 }}</td>
                            <td>
                                {{ $opened = $opened_1 + $opened_2 + $opened_3 }}
{{--                                ({{ format_percent($sent === 0 ? 0 : $opened/$sent) }})--}}
                            </td>
                            <td>
                                {{ $resumed = $resumed_1 + $resumed_2 + $resumed_3 }}
{{--                                ({{ format_percent($sent === 0 ? 0 : $resumed/$sent) }})--}}
                            </td>
                            <td>
                                {{ $submitted = $submitted_1->count() + $submitted_2->count() + $submitted_3->count() }}
{{--                                ({{ format_percent($sent === 0 ? 0 : $submitted/$sent) }})--}}
                            </td>
                            <td>{{ format_currency($submitted_1->sum() + $submitted_2->sum() + $submitted_3->sum()) }}</td>
                        </tr>
                        </tfoot>
                    </x-bs::table>
                </x-bs::card>
            </div>
        </div>
    </div>
@endsection