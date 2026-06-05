@extends('layouts.admin')

@section('title', 'Publications')

@section('content')
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <p class="text-ig-muted font-medium">{{ $publications->total() }} publication(s)</p>
        <a href="{{ route('admin.publications.create') }}" class="btn-ig">Nouvelle publication</a>
    </div>

    <div class="feed-card overflow-hidden">
        <table class="min-w-full divide-y divide-ig-border">
            <thead class="bg-ig-surface">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-ig-muted uppercase tracking-wide">Titre</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-ig-muted uppercase tracking-wide">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-ig-muted uppercase tracking-wide">Date</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ig-border bg-ig-card">
                @foreach ($publications as $publication)
                    <tr class="hover:bg-ig-hover/50 transition">
                        <td class="px-6 py-4">
                            <a href="{{ route('publications.show', $publication) }}" class="text-ig-pink font-medium hover:underline" target="_blank">
                                {{ $publication->title }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span @class([
                                'px-2.5 py-1 text-xs font-semibold rounded-full',
                                'bg-green-100 text-green-800' => $publication->status === 'published',
                                'bg-amber-100 text-amber-800' => $publication->status !== 'published',
                            ])>
                                {{ $publication->status === 'published' ? 'Publié' : 'Brouillon' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-ig-muted">{{ $publication->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('admin.publications.edit', $publication) }}" class="text-sm font-medium text-ig-pink hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.publications.destroy', $publication) }}" class="inline" onsubmit="return confirm('Supprimer ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-red-500 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $publications->links() }}</div>
@endsection
