@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')
    <div class="feed-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-ig-surface/50 border-b border-ig-border">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold">Utilisateur</th>
                    <th class="text-left px-4 py-3 font-semibold">Rôle</th>
                    <th class="text-left px-4 py-3 font-semibold">Activité</th>
                    <th class="text-right px-4 py-3 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ig-border">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-medium text-ig-dark">{{ $user->name }}</p>
                            <p class="text-xs text-ig-muted">@{{ $user->username }} · {{ $user->email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <select name="role" onchange="this.form.submit()" class="ig-input text-xs py-1">
                                    <option value="user" @selected($user->role === 'user')>Utilisateur</option>
                                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-ig-muted">
                            {{ $user->publications_count }} pub. · {{ $user->comments_count }} com.
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if ($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:underline">Supprimer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
@endsection
