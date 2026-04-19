<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\newpost_details;
use App\Models\PostView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Blogmain extends Controller
{

    private $defaultCategories = [
        'technology' => 'Technology',
        'travel' => 'Travel',
        'life-style' => 'Lifestyle',
        'digital-trends' => 'Digital Trends',
        'productivity' => 'Productivity',
        'news-updates' => 'News & Updates',
        'stories-experiences' => 'Stories & Experiences',
        'creativity-inspiration' => 'Creativity & Inspiration'
    ];
    public function __construct() {}

    public function index()
    {
        $getcategory = request()->get('category');
        if (empty($getcategory) || !array_key_exists($getcategory, $this->defaultCategories)) {
            $getcategory = null;
        }

        $featuredPosts = newpost_details::withCount('views')
            ->published()
            ->featured()
            ->byCategory($getcategory)
            ->recent()
            ->limit(4)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => $post->excerpt,
                    'content' => $post->description,
                    'category' => $post->category,
                    'thumbnail' => json_decode($post->file_path, true),
                    'is_featured' => (bool)$post->is_featured,
                    'views' => $post->views_count,
                    'estimated_reading_time' => $post->estimated_reading_time,
                    'tags' => $post->tags,
                    'published_at' => $post->created_date ? $post->created_date->toDateTimeString() : null,
                    'created_at' => $post->created_at ? $post->created_at->toDateTimeString() : null,
                    'updated_at' => $post->updated_at ? $post->updated_at->toDateTimeString() : null
                ];
            });

        $posts = newpost_details::withCount('views')
            ->published()
            ->byCategory($getcategory)
            ->recent()
            ->paginate(4)
            ->through(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => $post->excerpt,
                    'content' => $post->description,
                    'category' => $post->category,
                    'thumbnail' => json_decode($post->file_path, true),
                    'is_featured' => (bool)$post->is_featured,
                    'views' => $post->views_count,
                    'estimated_reading_time' => $post->estimated_reading_time,
                    'tags' => $post->tags,
                    'published_at' => $post->created_date ? $post->created_date->toDateTimeString() : null,
                    'created_at' => $post->created_at ? $post->created_at->toDateTimeString() : null,
                    'updated_at' => $post->updated_at ? $post->updated_at->toDateTimeString() : null
                ];
            });

        $popularPosts = newpost_details::withCount('views')
            ->published()
            ->byCategory($getcategory)
            ->popular()
            ->limit(4)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'category' => $post->category,
                    'thumbnail' => json_decode($post->file_path, true),
                    'views' => $post->views_count,
                    'estimated_reading_time' => $post->estimated_reading_time,
                    'published_at' => $post->created_date ? $post->created_date->toDateTimeString() : null
                ];
            });

        $categories = newpost_details::published()
            ->select('category', DB::raw('COUNT(*) as count'))
            ->whereNotNull('category')
            // ->byCategory($getcategory)
            ->groupBy('category')
            ->get()
            ->pluck('count', 'category')
            ->toArray();

        $CategoriesCount = [
            'Technology' => 0,
            'Travel' => 0,
            'Lifestyle' => 0,
            'Digital Trends' => 0,
            'Productivity' => 0,
            'News & Updates' => 0,
            'Stories & Experiences' => 0,
            'Creativity & Inspiration' => 0
        ];

        $categoriesList = [];

        foreach ($CategoriesCount as $label => $defaultCount) {
            $lowerKey = strtolower($label);
            $lowerKey = str_replace('&', '-', $lowerKey);
            $lowerKey = trim($lowerKey);
            $lowerKey = str_replace(' ', '-', $lowerKey);
            $categoriesList[$label] = $categories[$lowerKey] ?? 0;
        }

        return view('pages.blogPage', [
            'featuredPosts' => $featuredPosts,
            'posts' => $posts,
            'popularPosts' => $popularPosts,
            'categories' => $categories,
            'categoriesList' => $categoriesList,
            'defaultCategories' => $this->defaultCategories,
            'category' => $getcategory
        ]);
    }

    public function main(Request $request)
    {
        $query = $this->decodeSearchQuery($request->input('q'));
        $postsQuery = $this->getData($query);

        $featuredPost = (clone $postsQuery)->featured()->first();
        if (!$featuredPost) {
            $featuredPost = (clone $postsQuery)->first();
        }

        $topStories = (clone $postsQuery)
            ->when($featuredPost, function ($builder) use ($featuredPost) {
                return $builder->where('id', '!=', $featuredPost->id);
            })
            ->take(3)
            ->get();

        $latestPosts = (clone $postsQuery)->take(8)->get();

        $popularPosts = newpost_details::published()
            ->orderByDesc('views_count')
            ->orderByDesc('created_date')
            ->take(5)
            ->get();

        $categoryStats = newpost_details::published()
            ->select('category', DB::raw('COUNT(*) as total'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        return view('main.home', compact(
            'query',
            'featuredPost',
            'topStories',
            'latestPosts',
            'popularPosts',
            'categoryStats'
        ));
    }

    public function getData($query = null)
    {
        return newpost_details::query()
            ->published()
            ->recent()
            ->when(!empty($query), function ($posts) use ($query) {
                $posts->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%")
                        ->orWhere('category', 'LIKE', "%{$query}%");
                });
            });
    }

    private function decodeSearchQuery(?string $encoded): ?string
    {
        if (empty($encoded)) {
            return null;
        }

        $decoded = base64_decode($encoded, true);
        if ($decoded === false) {
            $decoded = urldecode($encoded);
        }

        $decoded = trim((string) $decoded);
        return $decoded === '' ? null : $decoded;
    }

    public function show($id)
    {
        try {
            if (is_numeric($id)) {
                $post = newpost_details::published()->findOrFail($id);

                // Keep old ID URLs working, but redirect to canonical slug URL.
                if (!empty($post->slug)) {
                    return redirect()->route('post.show', ['slugOrId' => $post->slug], 301);
                }
            } else {
                $post = newpost_details::published()->where('slug', $id)->firstOrFail();
            }

            try {
                $this->trackView($post);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("View tracking failed: " . $e->getMessage());
            }

            $images = $post->images;

            $relatedPosts = newpost_details::published()
                ->where('id', '!=', $post->id)
                ->where('category', $post->category)
                ->recent()
                ->limit(3)
                ->get();

            $popularPosts = newpost_details::published()
                ->where('id', '!=', $post->id)
                ->recent()
                ->limit(5)
                ->get();

            $comments = Comment::where('post_id', $post->id)
                ->approved()
                ->orderBy('created_at', 'desc')
                ->get();

            $viewStats = [
                'today' => PostView::todayViews($post->id) ?? 0,
                'total' => PostView::totalViews($post->id) ?? 0,
                'unique' => PostView::uniqueViews($post->id) ?? 0,
            ];

            if (is_array($post->table_of_contents)) {
                $tocData = $post->table_of_contents;
            } elseif (is_string($post->table_of_contents) && !empty($post->table_of_contents)) {
                $tocData = json_decode($post->table_of_contents, true) ?? [];
            } else {
                $tocData = [];
            }
            $wordCount = str_word_count(strip_tags($post->description));
            $readingTime = (int) preg_replace('/[^0-9]/', '', $post->reading_time ?? ceil($wordCount / 200));
            
            return view('main.readmore', compact(
                'post',
                'images',
                'relatedPosts',
                'popularPosts',
                'comments',
                'viewStats',
                'tocData',
                'readingTime',
                'wordCount'
            ));
        } catch (\Exception $e) {
            abort(404, 'Post not found');
        }
    }

    public function sitemap()
    {
        $posts = newpost_details::published()
            ->select('id', 'slug', 'updated_at', 'created_at')
            ->orderByDesc('updated_at')
            ->get();

        $staticPages = [
            ['loc' => url('/'), 'lastmod' => now()->toDateString(), 'priority' => '1.0'],
            ['loc' => url('/blog'), 'lastmod' => now()->toDateString(), 'priority' => '0.9'],
            ['loc' => url('/categories'), 'lastmod' => now()->toDateString(), 'priority' => '0.8'],
            ['loc' => url('/about'), 'lastmod' => now()->toDateString(), 'priority' => '0.6'],
            ['loc' => url('/contact'), 'lastmod' => now()->toDateString(), 'priority' => '0.6'],
            ['loc' => url('/privacy-policy'), 'lastmod' => now()->toDateString(), 'priority' => '0.3'],
            ['loc' => url('/terms-conditions'), 'lastmod' => now()->toDateString(), 'priority' => '0.3'],
            ['loc' => url('/disclaimer'), 'lastmod' => now()->toDateString(), 'priority' => '0.3'],
        ];

        $categories = array_keys($this->defaultCategories);
        $xml = view('sitemap.xml', compact('posts', 'staticPages', 'categories'));

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Track post view
     */
    private function trackView($post)
    {
        $ipAddress = request()->ip();
        $sessionId = session()->getId();
        if (!PostView::hasViewedToday($post->id, $ipAddress)) {
            PostView::create([
                'post_id' => $post->id,
                'ip_address' => $ipAddress,
                'session_id' => $sessionId,
                'user_agent' => request()->header('User-Agent')
            ]);
        }
    }

    /**
     * Submit a comment
     */
    public function storeComment(Request $request, $postId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string|min:5|max:1000',
        ]);

        $comment = Comment::create([
            'post_id' => $postId,
            'name' => $request->name,
            'email' => $request->email,
            'content' => $request->content,
            'user_ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);

        // If you add comments_count column, uncomment:
        // Post::find($postId)->increment('comments_count');

        return back()->with('success', 'Comment submitted successfully! It will appear after approval.');
    }

    /**
     * Show posts by category
     */
    public function byCategory($category)
    {
        if (!isset($this->defaultCategories[$category])) {
            abort(404);
        }

        $displayName = $this->defaultCategories[$category];
        // $category = $defaultCategories[$category];
        $posts = newpost_details::published()
            ->where('category', $category)
            ->recent()
            ->paginate(10);

        return view('categories.showPage', [
            'posts' => $posts ?? [],
            'categoryName' => $displayName,
            'categorySlug' => $category
        ]);
    }

    public function categories()
    {
        $categories = [
            [
                'name' => 'Technology',
                'post_count' => newpost_details::where('category', 'technology')->count(),
                'description' => 'Latest tech news, gadgets, programming, and digital innovations',
                'reading_time' => '7',
                'slug' => 'technology',
                'icon' => 'laptop-code', // Font Awesome icon class
                'icon_bg' => 'bg-blue-100',
                'icon_color' => 'text-blue-600'
            ],
            [
                'name' => 'Travel',
                'post_count' => newpost_details::where('category', 'travel')->count(),
                'description' => 'Travel guides, destination tips, and adventure stories',
                'reading_time' => '8',
                'slug' => 'travel',
                'icon' => 'plane', // Font Awesome icon class
                'icon_bg' => 'bg-green-100',
                'icon_color' => 'text-green-600'
            ],
            [
                'name' => 'Lifestyle',
                'post_count' => newpost_details::where('category', 'life-style')->count(),
                'description' => 'Daily life, wellness, habits, and personal development',
                'reading_time' => '6',
                'slug' => 'life-style',
                'icon' => 'heart', // Font Awesome icon class
                'icon_bg' => 'bg-purple-100',
                'icon_color' => 'text-purple-600'
            ],
            [
                'name' => 'Digital Trends',
                'post_count' => newpost_details::where('category', 'digital-trends')->count(),
                'description' => 'Latest digital marketing, social media, and online trends',
                'reading_time' => '5',
                'slug' => 'digital-trends',
                'icon' => 'chart-line', // Font Awesome icon class
                'icon_bg' => 'bg-indigo-100',
                'icon_color' => 'text-indigo-600'
            ],
            [
                'name' => 'Productivity',
                'post_count' => newpost_details::where('category', 'productivity')->count(),
                'description' => 'Time management, efficiency tips, and work optimization',
                'reading_time' => '6',
                'slug' => 'productivity',
                'icon' => 'check-double',
                'icon_bg' => 'bg-amber-100',
                'icon_color' => 'text-amber-600'
            ],
            [
                'name' => 'News & Updates',
                'post_count' => newpost_details::where('category', 'news-updates')->count(),
                'description' => 'Latest news, announcements, and updates',
                'reading_time' => '4',
                'slug' => 'news-updates',
                'icon' => 'newspaper',
                'icon_bg' => 'bg-red-100',
                'icon_color' => 'text-red-600'
            ],
            [
                'name' => 'Stories & Experiences',
                'post_count' => newpost_details::where('category', 'stories-experiences')->count(),
                'description' => 'Personal stories, experiences, and narratives',
                'reading_time' => '9',
                'slug' => 'stories-experiences',
                'icon' => 'book-open',
                'icon_bg' => 'bg-pink-100',
                'icon_color' => 'text-pink-600'
            ],
            [
                'name' => 'Creativity & Inspiration',
                'post_count' => newpost_details::where('category', 'creativity-inspiration')->count(),
                'description' => 'Creative ideas, inspiration, and artistic content',
                'reading_time' => '7',
                'slug' => 'creativity-inspiration',
                'icon' => 'lightbulb',
                'icon_bg' => 'bg-cyan-100',
                'icon_color' => 'text-cyan-600'
            ],
        ];

        $featuredCategories = array_slice($categories, 0, 4);
        $trendingCategories = array_slice($categories, 0, 2);
        $latestPostsByCategory = [];

        foreach ($trendingCategories as $category) {
            $latestPostsByCategory[$category['slug']] = newpost_details::published()
                ->where('category', $category['slug'])
                ->latest()
                ->take(3)
                ->get();
        }

        return view('categories.categoryPage', [
            'categories' => $categories,
            'featuredCategories' => $featuredCategories,
            'trendingCategories' => $trendingCategories,
            'latestPostsByCategory' => $latestPostsByCategory,
            'totalPosts' => newpost_details::count(),
            'totalAuthors' => 1,
            'totalViews' =>  PostView::count(),
            'totalViewsByMonth' =>  PostView::totalViewsByMonth( date('m'), date('Y')),
        ]);
    }
}
