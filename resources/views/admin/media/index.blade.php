@extends('layouts.admin')

@section('title', 'Médiathèque')

@section('content')
    <div class="feed-card p-4 mb-6">
        <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="flex flex-wrap gap-3 items-end">
            @csrf
            <div>
                <label class="ig-label">Téléverser des fichiers</label>
                <input type="file" name="files[]" multiple class="mt-1 text-sm">
            </div>
            <button type="submit" class="btn-ig">Téléverser</button>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse ($media as $file)
            <div class="feed-card p-2">
                @if ($file->isImage())
                    <img src="{{ $file->url() }}" alt="" class="w-full h-24 object-cover rounded-lg mb-2">
                @else
                    <div class="w-full h-24 bg-ig-hover rounded-lg mb-2 flex items-center justify-center text-xs text-ig-muted p-2 text-center">
                        {{ $file->mime_type }}
                    </div>
                @endif
                <p class="text-xs truncate" title="{{ $file->original_name }}">{{ $file->original_name }}</p>
                <p class="text-xs text-ig-muted">{{ number_format($file->size / 1024, 1) }} Ko</p>
                <input type="text" readonly value="{{ $file->url() }}" class="ig-input text-xs mt-2 py-1" onclick="this.select()">
                <form method="POST" action="{{ route('admin.media.destroy', $file) }}" class="mt-2" onsubmit="return confirm('Supprimer ce fichier ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-500 hover:underline">Supprimer</button>
                </form>
            </div>
        @empty
            <p class="text-ig-muted col-span-full text-center py-8">Aucun fichier dans la médiathèque.</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $media->onEachSide(1)->links() }}</div>
@endsection
