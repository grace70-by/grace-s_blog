@props(['publication'])

@auth
    <button type="button"
            class="publication-like-btn feed-action-btn {{ $publication->user_has_liked ? 'feed-action-btn-active' : '' }}"
            data-url="{{ route('publications.reactions.toggle', $publication) }}">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
        <span class="like-count">{{ $publication->reactions_count ?? 0 }}</span>
    </button>
@else
    <span class="feed-action-btn pointer-events-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        {{ $publication->reactions_count ?? 0 }}
    </span>
@endauth

@once
    @push('scripts')
    <script>
        document.querySelectorAll('.publication-like-btn').forEach(btn => {
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
