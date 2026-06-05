@extends('layouts.feed', ['narrow' => true])

@section('title', $page->title.' — '.config('app.name'))

@section('content')
    <article class="feed-card p-6 prose prose-sm max-w-none">
        <h1 class="text-2xl font-bold text-ig-dark mb-6">{{ $page->title }}</h1>
        <div class="text-ig-dark leading-relaxed whitespace-pre-line">{{ $page->body }}</div>
    </article>
@endsection
