@if ($comments->isEmpty())
    <p class="text-ig-muted text-sm text-center py-6">Soyez le premier à commenter.</p>
@else
    <ul class="space-y-3 {{ $depth > 0 ? 'comment-thread-line' : '' }}">
        @foreach ($comments as $comment)
            <li class="rounded-xl border border-ig-border bg-ig-surface/30 p-3" x-data="{ showReply: false, showReport: false, showEdit: false }">
                <div class="flex gap-3">
                    <x-avatar :user="$comment->user" size="sm" />
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-2">
                            <div>
                                <span class="font-semibold text-ig-dark text-sm">{{ $comment->user->name }}</span>
                                <span class="text-ig-muted text-xs">@{{ $comment->user->username }}</span>
                                <span class="text-ig-muted text-xs">· {{ $comment->created_at->diffForHumans() }}</span>
                                @if ($comment->edited_at)
                                    <span class="text-ig-muted text-xs">(modifié)</span>
                                @endif
                            </div>
                            <div class="flex gap-2 shrink-0">
                                @can('update', $comment)
                                    <button type="button" @click="showEdit = !showEdit" class="text-xs text-ig-pink hover:underline">Modifier</button>
                                @endcan
                                @can('delete', $comment)
                                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Supprimer ce commentaire ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:underline">Supprimer</button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                        <div x-show="!showEdit" class="mt-1.5 text-sm text-ig-dark leading-relaxed">
                            {!! $commentService->formatBodyWithMentions($comment) !!}
                        </div>

                        @can('update', $comment)
                            <div x-show="showEdit" x-cloak class="mt-2">
                                <form method="POST" action="{{ route('comments.update', $comment) }}">
                                    @csrf
                                    @method('PATCH')
                                    <textarea name="body" rows="3" required maxlength="5000" class="ig-input text-sm resize-none">{{ $comment->body }}</textarea>
                                    <div class="mt-2 flex gap-2">
                                        <button type="submit" class="btn-ig text-xs py-2 px-4">Enregistrer</button>
                                        <button type="button" @click="showEdit = false" class="text-xs text-ig-muted hover:underline">Annuler</button>
                                    </div>
                                </form>
                            </div>
                        @endcan

                        <div class="mt-2 flex flex-wrap items-center gap-1">
                            @auth
                                <button type="button"
                                        class="comment-like-btn feed-action-btn !flex-none !px-3 {{ $comment->reactions->where('user_id', auth()->id())->count() ? 'feed-action-btn-active' : '' }}"
                                        data-url="{{ route('comments.reactions.toggle', $comment) }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                                    <span class="like-count">{{ $comment->reactions_count ?? $comment->reactions->count() }}</span>
                                </button>

                                @if ($depth < 2)
                                    <button type="button" @click="showReply = !showReply" class="feed-action-btn !flex-none !px-3">
                                        Répondre
                                    </button>
                                @endif

                                <button type="button" @click="showReport = !showReport" class="feed-action-btn !flex-none !px-3 hover:!text-red-500">
                                    Signaler
                                </button>
                            @else
                                <span class="text-xs text-ig-muted px-2">{{ $comment->reactions->count() }} j'aime</span>
                            @endauth
                        </div>

                        @auth
                            <div x-show="showReply" x-cloak class="mt-3 pl-1">
                                <form method="POST" action="{{ route('comments.reply', $comment) }}">
                                    @csrf
                                    <textarea name="body" rows="2" required maxlength="5000"
                                              class="ig-input text-sm resize-none"
                                              placeholder="Votre réponse… @username"></textarea>
                                    <button type="submit" class="mt-2 btn-ig text-xs py-2 px-4">Répondre</button>
                                </form>
                            </div>

                            <div x-show="showReport" x-cloak class="mt-3">
                                <form method="POST" action="{{ route('comments.reports.store', $comment) }}">
                                    @csrf
                                    <textarea name="reason" rows="2" required minlength="10" maxlength="1000"
                                              class="ig-input text-sm resize-none"
                                              placeholder="Motif du signalement (min. 10 caractères)"></textarea>
                                    <button type="submit" class="mt-2 text-xs font-semibold text-red-500 hover:underline">Envoyer le signalement</button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>

                @if ($depth < 2 && $comment->relationLoaded('children') && $comment->children->isNotEmpty())
                    @include('comments._thread', [
                        'comments' => $comment->children->whereNull('hidden_at'),
                        'depth' => $depth + 1,
                        'commentService' => $commentService,
                    ])
                @endif
            </li>
        @endforeach
    </ul>
@endif

@once
    @push('scripts')
    <script>
        document.querySelectorAll('.comment-like-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const res = await fetch(btn.dataset.url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                if (res.ok) {
                    const data = await res.json();
                    btn.querySelector('.like-count').textContent = data.count;
                    btn.classList.toggle('feed-action-btn-active', data.liked);
                }
            });
        });
    </script>
    @endpush
@endonce
