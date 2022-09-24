@extends('eshop::dashboard.layouts.master')

@section('header')
    <h1 class="fs-5 mb-0">Blogs</h1>
@endsection

@section('main')
    <div class="col-12 col-xxl-8 mx-auto p-4 d-grid gap-3">
        <div class="d-flex justify-content-end">
            <a href="{{ route('blogs.create') }}" class="btn btn-primary">Δημιουργία</a>
        </div>
        
        <x-bs::card>
            <div class="table-responsive">
                <x-bs::table>
                    <thead>
                    <tr>
                        <th>Τίτλος</th>
                        <th class="text-end">Στάλθηκαν</th>
                        <th class="text-end">Ανοίχτηκαν</th>
                        <th class="text-end">Click</th>
                        <th class="text-end">Δημιουργήθηκε</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($blogs as $blog)
                        <tr>
                            <td><a href="{{ route('blogs.edit', $blog) }}" class="text-decoration-none">{{ $blog->title }}</a></td>
                            <td class="text-end">{{ $blog->sent }}</td>
                            <td class="text-end">{{ $blog->opened }}</td>
                            <td class="text-end">{{ $blog->clicked }}</td>
                            <td class="text-end">{{ $blog->created_at->isoFormat('lL') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 fst-italic text-secondary">{{ __("No records found") }}</td>                            
                        </tr>
                    @endforelse
                    </tbody>

                    <caption>
                        <x-eshop::pagination :paginator="$blogs"/>
                    </caption>
                </x-bs::table>
            </div>
        </x-bs::card>
    </div>
@endsection
