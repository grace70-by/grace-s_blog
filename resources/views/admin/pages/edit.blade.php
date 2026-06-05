@extends('layouts.admin')

@section('title', 'Modifier — '.$page->title)

@section('content')
    <form method="POST" action="{{ route('admin.pages.update', $page) }}" class="feed-card p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="ig-label">Titre</label>
            <input type="text" name="title" value="{{ old('title', $page->title) }}" required class="ig-input mt-1">
        </div>

        <div>
            <label class="ig-label">Contenu</label>
            <textarea name="body" rows="12" required class="ig-input mt-1">{{ old('body', $page->body) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-ig">Enregistrer</button>
            <a href="{{ route('admin.pages.index') }}" class="btn-ig-outline">Annuler</a>
        </div>
    </form>
@endsection
