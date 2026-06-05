{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('search') }}</loc>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('archives.index') }}</loc>
        <changefreq>weekly</changefreq>
    </url>
    @foreach ($pages as $page)
        <url>
            <loc>{{ route('pages.show', $page) }}</loc>
            <lastmod>{{ $page->updated_at->toAtomString() }}</lastmod>
        </url>
    @endforeach
    @foreach ($publications as $publication)
        <url>
            <loc>{{ route('publications.show', $publication) }}</loc>
            <lastmod>{{ $publication->updated_at->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
        </url>
    @endforeach
</urlset>
