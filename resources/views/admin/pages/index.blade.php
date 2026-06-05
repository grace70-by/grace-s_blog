@extends('layouts.admin')

@section('title', 'Pages')

@section('content')
    <div class="feed-card divide-y divide-ig-border">
        @foreach ($pages as $page)
            <div class="flex justify-between items-center px-4 py-3">
                <div>
                    <p class="font-medium text-ig-dark">{{ $page->title }}</p>
                    <p class="text-xs text-ig-muted">/pages/{{ $page->slug }}</p>
                </div>
                <a href="{{ route('admin.pages.edit', $page) }}" class="text-sm text-ig-pink font-medium hover:underline">Modifier</a>
            </div>
        @endforeach
    </div>
@endsection
