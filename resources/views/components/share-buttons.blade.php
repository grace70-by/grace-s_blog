@props(['url', 'title'])

<div class="flex flex-wrap gap-2">
    <a href="https://twitter.com/intent/tweet?url={{ urlencode($url) }}&text={{ urlencode($title) }}"
       target="_blank" rel="noopener" class="feed-action-btn !flex-none !px-3 text-xs">
        X / Twitter
    </a>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}"
       target="_blank" rel="noopener" class="feed-action-btn !flex-none !px-3 text-xs">
        Facebook
    </a>
    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($url) }}"
       target="_blank" rel="noopener" class="feed-action-btn !flex-none !px-3 text-xs">
        LinkedIn
    </a>
    <button type="button" onclick="navigator.clipboard.writeText('{{ $url }}'); this.textContent='Copié !';"
            class="feed-action-btn !flex-none !px-3 text-xs">
        Copier le lien
    </button>
</div>
