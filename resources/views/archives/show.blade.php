@extends('layouts.feed')

@php
    $label = $month
        ? \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y')
        : $year;
@endphp

@section('title', 'Archives '.$label.' — '.config('app.name'))

@section('content')
    <div class="space-y-4">
        <div class="feed-card p-4 flex items-center justify-between">
            <div>
                <a href="{{ route('archives.index') }}" class="text-sm text-ig-pink hover:underline">← Archives</a>
                <h1 class="text-lg font-bold text-ig-dark mt-1">{{ $label }}</h1>
            </div>
            <span class="text-xs text-ig-muted">{{ $publications->total() }} article(s)</span>
        </div>

        @foreach ($publications as $publication)
            @include('publications._card', ['publication' => $publication])
        @endforeach

        <div>{{ $publications->links() }}</div>
    </div>
@endsection
