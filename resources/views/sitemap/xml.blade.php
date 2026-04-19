<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($staticPages as $page)
    <url>
        <loc>{{ $page['loc'] }}</loc>
        <lastmod>{{ $page['lastmod'] }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>{{ $page['priority'] }}</priority>
    </url>
@endforeach

@foreach ($categories as $category)
    <url>
        <loc>{{ url('/category/' . $category) }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.7</priority>
    </url>
@endforeach

@foreach ($posts as $post)
    <url>
        <loc>{{ route('post.show', ['slugOrId' => $post->slug ?: $post->id]) }}</loc>
        <lastmod>{{ optional($post->updated_at ?: $post->created_at)->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
@endforeach
</urlset>
