<?php

namespace App\Http\Controllers;

use App\Models\newpost_details;
use App\Services\ImageProcessingService;
use App\Services\OpenAiBlogGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Admin extends Controller
{
    public function __construct(
        private ImageProcessingService $imgService,
        private OpenAiBlogGeneratorService $openAiGenerator
    )
    {
        $this->middleware('auth');
        $this->middleware('web');
    }

    public function index(Request $request)
    {
        $posts = newpost_details::query()->orderByDesc('created_at')->get();

        $totalViews = Schema::hasTable('post_views')
            ? DB::table('post_views')->count()
            : (Schema::hasColumn('newpost_details', 'views_count') ? (int) DB::table('newpost_details')->sum('views_count') : 0);

        $totalComments = Schema::hasTable('comments')
            ? DB::table('comments')->count()
            : (Schema::hasColumn('newpost_details', 'comments_count') ? (int) DB::table('newpost_details')->sum('comments_count') : 0);

        $totalShares = Schema::hasColumn('newpost_details', 'shares_count')
            ? (int) DB::table('newpost_details')->sum('shares_count')
            : 0;

        $draftCount = newpost_details::query()->draft()->count();
        $publishedCount = newpost_details::query()->published()->count();

        $stats = [
            'posts' => (int) $posts->count(),
            'published' => (int) $publishedCount,
            'drafts' => (int) $draftCount,
            'views' => (int) $totalViews,
            'comments' => (int) $totalComments,
            'shares' => (int) $totalShares,
        ];

        $message = '';
        if ($request->session()->get('status') !== 'active') {
            $request->session()->flash('message', 'Hello Admin Welcome here');
            $request->session()->flash('status', 'active');
            $request->session()->put('message_shown', false);
        }

        if ($request->session()->get('status') === 'active' && !$request->session()->get('message_shown')) {
            if ($request->session()->has('message')) {
                $message = $request->session()->get('message');
                $request->session()->forget('message');
                $request->session()->put('message_shown', true);
            }
        }

        $request->session()->put('name', 'Suraj');

        return view('admin/adminpanel', [
            'details_arr' => $posts,
            'stats' => $stats,
            'message' => $message,
        ]);
    }

    public function drafts(Request $request)
    {
        $drafts = newpost_details::query()
            ->draft()
            ->orderByDesc('updated_at')
            ->paginate(12);

        return view('admin.drafts', compact('drafts'));
    }

    public function previewDraft(int $id)
    {
        $post = newpost_details::query()->findOrFail($id);
        $wordCount = str_word_count(strip_tags($post->description ?? ''));
        $readingTime = max(1, (int) ceil($wordCount / 200));

        return view('admin.draft-preview', compact('post', 'wordCount', 'readingTime'));
    }

    public function publishDraft(int $id)
    {
        $post = newpost_details::query()->findOrFail($id);

        $optimized = $this->openAiGenerator->optimizeForPublishing(
            (string) $post->title,
            (string) $post->description,
            (string) ($post->category ?? 'technology')
        );

        $updateData = [
            'status' => 'published',
            'is_published' => 1,
            'active' => 1,
            'created_date' => $post->created_date ?? now(),
        ];

        if ($optimized !== null) {
            $updateData['title'] = $optimized['title'];
            $updateData['description'] = $optimized['content'];
            $updateData['meta_title'] = Str::limit($optimized['title'], 255, '');
            $updateData['meta_description'] = $optimized['meta_description'];
            $updateData['word_count'] = str_word_count(strip_tags($optimized['content']));
            $updateData['reading_time'] = (string) max(1, (int) ceil(((int) $updateData['word_count']) / 200));
            $updateData['table_of_contents'] = $this->generateTOC($optimized['content']);
            $updateData['name'] = 'Suraj';
        }

        $post->update($updateData);

        return redirect()
            ->route('admin.drafts')
            ->with(
                $optimized !== null ? 'success' : 'warning',
                $optimized !== null
                    ? 'Draft optimized and published successfully.'
                    : 'Draft published, but final AI optimization was skipped.'
            );
    }

    public function addpost()
    {
        return view('admin/addPost');
    }

    public function save_post(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:255',
                'desc' => 'required',
                'file' => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/webp|max:5120',
                'category' => 'nullable|string',
                'source_url' => 'nullable|url|max:2000',
                'source_type' => 'nullable|in:rss,reddit,trends,manual',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
                'featured' => 'boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'slug' => 'nullable|string|max:255|unique:newpost_details,slug',
                'table_of_contents' => 'nullable|json',
                'reading_time' => 'nullable|integer',
                'word_count' => 'nullable|integer',
                'status' => 'nullable|in:draft,published',
            ]);

            if (empty($validated['table_of_contents'])) {
                $validated['table_of_contents'] = $this->generateTOC($validated['desc']);
            }

            if (empty($validated['reading_time']) || empty($validated['word_count'])) {
                $wordCount = str_word_count(strip_tags($validated['desc']));
                $validated['word_count'] = $wordCount;
                $validated['reading_time'] = ceil($wordCount / 200);
            }

            $status = $request->boolean('draft') || (($validated['status'] ?? 'published') === 'draft')
                ? 'draft'
                : 'published';

            $data = [
                'title' => $validated['title'],
                'description' => $validated['desc'],
                'created_date' => now(),
                'category' => $validated['category'] ?? null,
                'source_url' => $validated['source_url'] ?? null,
                'source_type' => $validated['source_type'] ?? 'manual',
                'slug' => $validated['slug'] ?? Str::slug($validated['title']) . '-' . Str::random(5),
                'meta_title' => $validated['meta_title'] ?? null,
                'tags' => !empty($validated['tags']) ? json_encode($validated['tags']) : null,
                'meta_description' => $validated['meta_description'] ?? null,
                'table_of_contents' => $validated['table_of_contents'],
                'reading_time' => (string) $validated['reading_time'],
                'word_count' => $validated['word_count'],
                'active' => 1,
                'is_featured' => $validated['featured'] ?? 0,
                'is_published' => $status === 'published' ? 1 : 0,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (!Schema::hasColumn('newpost_details', 'source_type')) {
                unset($data['source_type']);
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');

                if (!$file->isValid()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Uploaded file is corrupted or invalid.',
                    ], 422);
                }

                $processed = $this->imgService->processImage($file->getPathname(), [
                    'quality' => 80,
                    'formats' => ['jpeg', 'webp'],
                    'sizes' => ['thumb', 'medium', 'large', 'original'],
                ]);

                $storagePaths = [];
                foreach ($processed as $size => $formats) {
                    foreach ($formats as $format => $path) {
                        $storagePaths[$size][$format] = $this->imgService->saveToStorage($path, 'uploads');
                    }
                }

                $data['file_path'] = json_encode($storagePaths);
            } else {
                $data['file_path'] = null;
            }

            $inserted = DB::table('newpost_details')->insert($data);

            if (!$inserted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Database error: Failed to save post.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => $status === 'draft' ? 'Draft saved successfully!' : 'Post created successfully!',
                'reset_form' => true,
                'redirect' => '/admin',
                'drafts' => newpost_details::query()->draft()->count(),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error occurred.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Admin save_post exception', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected server error occurred. Please try again.',
            ], 500);
        }
    }

    public function view_editPage($id)
    {
        $post = DB::table('newpost_details')->where('id', $id)->firstOrFail();
        // echo "<pre>";
        // print_r($post);
        // echo "</pre>";
        // return;
        return view('admin.editpage', compact('post'));
    }

    public function update_data(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:255',
                'desc' => 'required',
                'file' => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/webp|max:5120',
                'category' => 'nullable|string',
                'source_url' => 'nullable|url|max:2000',
                'source_type' => 'nullable|in:rss,reddit,trends,manual',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
                'featured' => 'boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'slug' => 'nullable|string|max:255',
                'table_of_contents' => 'nullable|json',
                'reading_time' => 'nullable|integer|min:1',
                'word_count' => 'nullable|integer|min:0',
            ]);

            $isSlugExists = !empty($validated['slug'])
                ? newpost_details::checkSlugExists($validated['slug'], $id)
                : false;

            if ($isSlugExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'The slug has already been taken.',
                ], 422);
            }

            $post = DB::table('newpost_details')
                ->select('file_path', 'source_url', 'source_type')
                ->where('id', $id)
                ->first();

            $wordCount = isset($validated['word_count'])
                ? (int) $validated['word_count']
                : str_word_count(strip_tags($validated['desc']));
            $readingTime = isset($validated['reading_time'])
                ? (int) $validated['reading_time']
                : (int) ceil($wordCount / 200);

            $data = [
                'title' => $validated['title'],
                'description' => $validated['desc'],
                'category' => $validated['category'] ?? null,
                'source_url' => $validated['source_url'] ?? ($post->source_url ?? null),
                'source_type' => $validated['source_type'] ?? ($post->source_type ?? 'manual'),
                'slug' => $validated['slug'] ?? null,
                'meta_title' => $validated['meta_title'] ?? null,
                'tags' => !empty($validated['tags']) ? json_encode($validated['tags']) : null,
                'meta_description' => $validated['meta_description'] ?? null,
                'table_of_contents' => $validated['table_of_contents'] ?? null,
                'reading_time' => (string) $readingTime,
                'word_count' => $wordCount,
                'is_featured' => $validated['featured'] ?? 0,
                'name' => 'Suraj',
                'updated_at' => now(),
            ];

            if (!Schema::hasColumn('newpost_details', 'source_type')) {
                unset($data['source_type']);
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');

                if (!$file->isValid()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Uploaded file is corrupted or invalid.',
                    ], 422);
                }

                $processed = $this->imgService->processImage($file->getPathname(), [
                    'quality' => 80,
                    'formats' => ['jpeg', 'webp'],
                    'sizes' => ['thumb', 'medium', 'large', 'original'],
                ]);

                if (empty($processed)) {
                    // Fallback: keep update reliable even if image processing pipeline returns empty.
                    $storedPath = Storage::disk('public')->putFile('uploads', $file);
                    $data['file_path'] = json_encode([
                        'original' => [
                            'jpeg' => $storedPath,
                        ],
                    ]);
                } else {
                    $storagePaths = [];
                    foreach ($processed as $size => $formats) {
                        foreach ($formats as $format => $path) {
                            $storagePaths[$size][$format] = $this->imgService->saveToStorage($path, 'uploads');
                        }
                    }

                    $data['file_path'] = json_encode($storagePaths);
                }
            } else {
                $data['file_path'] = $post->file_path ?? null;
            }

            $affected = DB::table('newpost_details')
                ->where('id', $id)
                ->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully!',
                'reset_form' => true,
                'redirect' => '/admin',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error occurred.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Admin update_data exception', [
                'post_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected server error occurred. Please try again.',
            ], 500);
        }
    }

    public function delete_data($id)
    {
        $deleted = DB::table('newpost_details')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json(['success' => 'Record deleted successfully']);
        }

        return response()->json(['error' => 'Error while deleting the record'], 500);
    }

    private function generateTOC(string $content): string
    {
        if (Str::contains(Str::lower($content), '<h2') || Str::contains(Str::lower($content), '<h3') || Str::contains(Str::lower($content), '<h4')) {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $content);
            $xpath = new \DOMXPath($dom);
            $nodes = $xpath->query('//h2 | //h3 | //h4');

            $tocFromHtml = [];
            $index = 1;

            if ($nodes !== false) {
                foreach ($nodes as $node) {
                    $title = trim((string) $node->textContent);
                    if ($title === '') {
                        continue;
                    }

                    $tocFromHtml[] = [
                        'id' => 'section-' . $index,
                        'title' => $title,
                        'level' => (int) str_replace('h', '', strtolower($node->nodeName)),
                        'slug' => Str::slug($title),
                        'order' => $index,
                    ];

                    $index++;
                }
            }

            if (!empty($tocFromHtml)) {
                return json_encode($tocFromHtml);
            }
        }

        $headings = [];
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            $line = trim($line);

            if (preg_match('/^(#{2,4})\s+(.+)$/', $line, $matches)) {
                $title = trim($matches[2]);
                $headings[] = [
                    'id' => 'section-' . (count($headings) + 1),
                    'title' => $title,
                    'level' => strlen($matches[1]),
                    'slug' => Str::slug($title),
                    'order' => count($headings) + 1,
                ];
                continue;
            }

            if (preg_match('/<h([2-4])[^>]*>(.*?)<\/h\1>/i', $line, $matches)) {
                $title = trim(strip_tags($matches[2]));
                $headings[] = [
                    'id' => 'section-' . (count($headings) + 1),
                    'title' => $title,
                    'level' => (int) $matches[1],
                    'slug' => Str::slug($title),
                    'order' => count($headings) + 1,
                ];
            }
        }

        return json_encode($headings);
    }
}
