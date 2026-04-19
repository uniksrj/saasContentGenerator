<?php

namespace App\Providers;

use App\Models\newpost_details;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class BreadcrumbServiceProvider extends ServiceProvider
{

    private $categoryMap = [
        'technology' => ['label' => 'Technology', 'icon' => 'fa-microchip'],
        'travel' => ['label' => 'Travel', 'icon' => 'fa-plane'],
        'life-style' => ['label' => 'Lifestyle', 'icon' => 'fa-leaf'],
        'digital-trends' => ['label' => 'Digital Trends', 'icon' => 'fa-chart-line'],
        'productivity' => ['label' => 'Productivity', 'icon' => 'fa-bolt'],
        'tutorials' => ['label' => 'Tutorials', 'icon' => 'fa-book-open'],
        'news-updates' => ['label' => 'News & Updates', 'icon' => 'fa-newspaper'],
        'stories-experiences' => ['label' => 'Stories & Experiences', 'icon' => 'fa-feather'],
        'creativity-inspiration' => ['label' => 'Creativity & Inspiration', 'icon' => 'fa-lightbulb'],
    ];

    private function resolveCategoryMeta(?string $category): array
    {
        if (!empty($category) && isset($this->categoryMap[$category])) {
            return $this->categoryMap[$category];
        }

        if (!empty($category)) {
            return [
                'label' => ucwords(str_replace('-', ' ', $category)),
                'icon' => 'fa-folder',
            ];
        }

        return [
            'label' => 'Category',
            'icon' => 'fa-folder',
        ];
    }
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $route = Route::currentRouteName();
            $breadcrumbs = [];

            switch ($route) {
                case 'blog.index':
                    $breadcrumbs = [
                        ['label' => 'Home', 'url' => url('/'), 'icon' => 'fa-home'],
                        ['label' => 'Blog', 'icon' => 'fa-blog'],
                    ];
                    break;

                case 'categories.index':
                    $breadcrumbs = [
                        ['label' => 'Home', 'url' => url('/'), 'icon' => 'fa-home'],
                        ['label' => 'Blog', 'url' => url('/blog'), 'icon' => 'fa-blog'],
                        ['label' => 'Categories','icon' => 'fa-folder-open'],
                    ];
                    break;

                case 'category.show':
                    if ($category = request()->route('categorySlug')) {
                        $categoryMeta = $this->resolveCategoryMeta($category);
                        $breadcrumbs = [
                            ['label' => 'Home', 'url' => url('/'), 'icon' => 'fa-home'],
                            ['label' => 'Blog', 'url' => url('/blog'), 'icon' => 'fa-blog'],
                            ['label' => 'Categories', 'url' => url('/categories'), 'icon' => 'fa-folder-open'],
                            ['label' => $categoryMeta['label'], 'icon' => $categoryMeta['icon']]
                        ];
                    }
                    break;

                case 'post.show':
                    $slugOrId = request()->route('slugOrId');
                    if (!empty($slugOrId)) {
                        $post = is_numeric($slugOrId)
                            ? newpost_details::select('title', 'category', 'slug', 'id')->find($slugOrId)
                            : newpost_details::select('title', 'category', 'slug', 'id')->where('slug', $slugOrId)->first();

                        if ($post) {
                            $categoryMeta = $this->resolveCategoryMeta($post->category);
                            $breadcrumbs = [
                                ['label' => 'Home', 'url' => url('/'), 'icon' => 'fa-home'],
                                ['label' => 'Blog', 'url' => url('/blog'), 'icon' => 'fa-blog'],
                                ['label' => 'Categories', 'url' => url('/categories'), 'icon' => 'fa-folder-open'],
                                [
                                    'label' => $categoryMeta['label'],
                                    'url' => url('/category/' . $post->category),
                                    'icon' => $categoryMeta['icon']
                                ],
                                ['label' => $post->title, 'icon' => 'fa-file-alt'],
                            ];
                        }
                    }
                    break;

                case 'about':
                    $breadcrumbs = [
                        ['label' => 'Home', 'url' => url('/'), 'icon' => 'fa-home'],
                        ['label' => 'About', 'icon' => 'fa-circle-info'],
                    ];
                    break;

                case 'contact':
                    $breadcrumbs = [
                        ['label' => 'Home', 'url' => url('/'), 'icon' => 'fa-home'],
                        ['label' => 'Contact', 'icon' => 'fa-envelope'],
                    ];
                    break;

                case 'privacy.policy':
                    $breadcrumbs = [
                        ['label' => 'Home', 'url' => url('/'), 'icon' => 'fa-home'],
                        ['label' => 'Privacy Policy', 'icon' => 'fa-shield-halved'],
                    ];
                    break;

                case 'terms.conditions':
                    $breadcrumbs = [
                        ['label' => 'Home', 'url' => url('/'), 'icon' => 'fa-home'],
                        ['label' => 'Terms & Conditions', 'icon' => 'fa-file-contract'],
                    ];
                    break;

                case 'disclaimer':
                    $breadcrumbs = [
                        ['label' => 'Home', 'url' => url('/'), 'icon' => 'fa-home'],
                        ['label' => 'Disclaimer', 'icon' => 'fa-triangle-exclamation'],
                    ];
                    break;
            }

            $view->with('breadcrumbs', $breadcrumbs);
        });
    }
}
