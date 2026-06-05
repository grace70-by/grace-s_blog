@extends('layouts.feed')

@section('title', 'Archives — '.config('app.name'))

@section('content')
    <div class="space-y-4">
        <div class="feed-card p-4">
            <h1 class="text-lg font-bold text-ig-dark">Archives</h1>
            <p class="text-sm text-ig-muted mt-1">Parcourez les publications par date.</p>
        </div>

        @if ($archives->isEmpty())
            <div class="feed-card p-8 text-center text-ig-muted">Aucune archive disponible.</div>
        @else
            <div class="feed-card divide-y divide-ig-border">
                @foreach ($archives as $archive)
                    <a href="{{ route('archives.show', ['year' => $archive->year, 'month' => $archive->month]) }}"
                       class="flex justify-between items-center px-4 py-3 hover:bg-ig-hover transition">
                        <span class="font-medium text-ig-dark">
                            {{ \Carbon\Carbon::create($archive->year, $archive->month, 1)->translatedFormat('F Y') }}
                        </span>
                        <span class="text-sm text-ig-muted">{{ $archive->total }} article(s)</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
