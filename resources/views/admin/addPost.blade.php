@vite(['resources/css/app.css', 'resources/js/app.js'])
@vite('resources/js/admin.js')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@include('admin.common.header')

<div class="admin-container">
    @include('admin.common.sidebar')

    <div class="main-content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 addPost">Add New Post</h1>
                <p class="text-muted mb-0">Create and publish new content for your blog</p>
            </div>
            <div class="btn-group">
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-save me-2"></i>Save Draft
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-eye me-2"></i>Preview
                </button>
            </div>
        </div>

        <div class="admin-grid mb-4">
            <div class="admin-grid-item">
                <div class="stats-card">
                    <div class="stats-number">24</div>
                    <div class="stats-label">Total Posts</div>
                </div>
            </div>
            <div class="admin-grid-item">
                <div class="stats-card">
                    <div class="stats-number">5</div>
                    <div class="stats-label">Drafts</div>
                </div>
            </div>
            <div class="admin-grid-item">
                <div class="stats-card">
                    <div class="stats-number">1,234</div>
                    <div class="stats-label">Monthly Views</div>
                </div>
            </div>
            <div class="admin-grid-item">
                <div class="stats-card">
                    <div class="stats-number">89%</div>
                    <div class="stats-label">Engagement Rate</div>
                </div>
            </div>
        </div>

        <div class="form-container">
            <h4 class="add_head mb-4">
                <i class="fas fa-plus-circle me-2"></i>Create New Post
            </h4>

            <form id="addPost" method="POST" enctype="multipart/form-data" action="/saveData">
                @csrf

                <div class="form-section">
                    <div class="d-flex align-items-center mb-3">
                        <div style="width: 150px; flex-shrink: 0;">
                            <label class="form-label">
                                <i class="fas fa-heading me-2"></i>Title
                            </label>
                        </div>
                        <div style="flex: 1; max-width: 600px;">
                            <input class="form-control" name="title" type="text" maxlength="200"
                                placeholder="Enter a compelling title for your post..." aria-label="Post title"
                                id="postTitle">
                            <div class="character-count">
                                <span id="titleCount">0</span>/200 characters
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="d-flex align-items-center mb-3">
                        <div style="width: 150px; flex-shrink: 0;">
                            <label class="form-label">
                                <i class="fas fa-link me-2"></i>URL Slug
                            </label>
                        </div>
                        <div style="flex: 1; max-width: 600px;">
                            <div class="slug-field">
                                <span class="slug-prefix">/</span>
                                <input class="form-control slug-input" name="slug" type="text" maxlength="100"
                                    placeholder="url-friendly-slug" aria-label="Post slug" id="postSlug">
                                <button type="button" class="slug-generate" id="generateSlugBtn">
                                    Generate
                                </button>
                            </div>
                            <div class="character-count">
                                <span id="slugCount">0</span>/100 characters
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="d-flex mb-3">
                        <div style="width: 150px; flex-shrink: 0;">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-2"></i>Description
                            </label>
                        </div>
                        <div style="flex: 1; max-width: 600px;">
                            <textarea class="form-control" name="desc" id="description" rows="6"
                                placeholder="Write your post content here..."></textarea>
                            <div class="character-count">
                                <span id="descCount">0</span> characters
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="d-flex mb-3">
                        <div style="width: 150px; flex-shrink: 0;">
                            <label class="form-label">
                                <i class="fas fa-list-ol me-2"></i>Table of Contents
                            </label>
                        </div>
                        <div style="flex: 1; max-width: 600px;">
                            <div class="mb-4">
                                <button type="button" class="btn btn-outline-primary btn-sm mb-2" id="generateTOCBtn">
                                    <i class="fas fa-magic me-1"></i> Auto-generate from Headings
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm mb-2" id="resetTOCBtn">
                                    <i class="fas fa-redo me-1"></i> Reset
                                </button>
                                <p class="small text-muted mb-0">Will auto-generate from markdown (##, ###, ####) or HTML headings (&lt;h2&gt;-&lt;h4&gt;) in your
                                    content</p>
                            </div>

                            <!-- TOC Preview -->
                            <div class="card mb-3" id="tocPreviewCard" style="display: none;">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0">
                                        <i class="fas fa-list me-1"></i> TOC Preview
                                        <span id="tocSectionCount" class="badge bg-primary ms-2">0 sections</span>
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <ul id="tocPreviewList" class="list-unstyled mb-0">
                                        <!-- TOC items will appear here -->
                                    </ul>
                                </div>
                            </div>

                            <!-- Hidden fields to store data -->
                            <input type="hidden" name="table_of_contents" id="tableOfContents">
                            <input type="hidden" name="reading_time" id="readingTime">
                            <input type="hidden" name="word_count" id="wordCount">

                            <!-- Stats -->
                            <div class="row g-2 mt-3" id="tocStats" style="display: none;">
                                <div class="col-4">
                                    <div class="border rounded p-2 text-center">
                                        <div class="small text-muted">Sections</div>
                                        <div class="h5 mb-0" id="statSections">0</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border rounded p-2 text-center">
                                        <div class="small text-muted">Words</div>
                                        <div class="h5 mb-0" id="statWords">0</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border rounded p-2 text-center">
                                        <div class="small text-muted">Read Time</div>
                                        <div class="h5 mb-0" id="statReadTime">0 min</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="d-flex mb-3">
                        <div style="width: 150px; flex-shrink: 0;">
                            <label class="form-label">
                                <i class="fas fa-image me-2"></i>Featured Image
                            </label>
                        </div>
                        <div style="flex: 1; max-width: 400px;">
                            <div class="upload-area" onclick="document.getElementById('upload').click()">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-uploadalt"></i>
                                </div>
                                <p class="mb-2">Drag & drop your image here or click to browse</p>
                                <p class="small text-muted mb-3">Supports JPG, PNG, GIF, webp - Max 5MB</p>
                                <input class="form-control form-control-sm d-none" name="file" accept="image/*"
                                    id="upload" type="file">
                                <button type="button" class="btn btn-outline-primary btn-sm">
                                    Choose File
                                </button>
                            </div>
                            <img id="imagePreview" class="preview-image" alt="Image preview">
                        </div>
                    </div>
                </div>

                @php
                    $categories = [
                        'travel' => 'Travel',
                        'life-style' => 'Lifestyle',
                        'digital-trends' => 'Digital Trends',
                        'technology' => 'Technology',
                        'productivity' => 'Productivity',
                        'news-updates' => 'News & Updates',
                        'stories-experiences' => 'Stories & Experiences',
                        'creativity-inspiration' => 'Creativity & Inspiration',
                    ];

                    $commonTags = [
                        'AI',
                        'Machine Learning',
                        'Web Development',
                        'Travel',
                        'Lifestyle',
                        'Productivity',
                        'Digital Marketing',
                        'Programming',
                        'Design',
                        'Photography',
                        'Business',
                        'Health',
                        'Finance',
                        'Education',
                        'Technology',
                        'Innovation',
                        'Startup',
                        'Mobile',
                        'Cloud',
                        'Blockchain',
                        'Cryptocurrency',
                        'Web3',
                        'IoT',
                        'Cybersecurity',
                        'Data Science',
                        'Analytics',
                        'UX/UI',
                        'Frontend',
                        'Backend',
                        'Laravel',
                        'PHP',
                        'JavaScript',
                        'Python',
                        'React',
                        'Vue',
                        'Node.js',
                        'API',
                        'DevOps',
                        'Agile',
                        'Remote Work',
                        'Digital Nomad',
                        'Mindfulness',
                        'Meditation',
                        'Wellness',
                        'Fitness',
                        'Nutrition',
                        'Mental Health',
                        'Self Improvement',
                        'Career Growth',
                        'Leadership',
                        'Management',
                        'Entrepreneurship',
                    ];
                @endphp

                <div class="form-section">
                    <div class="d-flex mb-3">
                        <div style="width: 150px; flex-shrink: 0;">
                            <label class="form-label">
                                <i class="fas fa-cog me-2"></i>Options
                            </label>
                        </div>
                        <div style="flex: 1; max-width: 600px;">
                            <div class="admin-form-row">
                                <div class="admin-form-col">
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-control select2" name="category" id="categorySelect">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="admin-form-col">
                                    <div class="mb-3">
                                        <label class="form-label">Tags</label>
                                        <select class="form-control select2" name="tags[]" id="tagsSelect"
                                            multiple="multiple">
                                            @foreach ($commonTags as $tag)
                                                <option value="{{ $tag }}">{{ $tag }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Select multiple tags</small>
                                    </div>
                                </div>
                            </div>

                            <div class="admin-form-row">
                                <div class="admin-form-col">
                                    <div class="mb-3">
                                        <label class="form-label">Meta Title</label>
                                        <input type="text" class="form-control" name="meta_title"
                                            placeholder="SEO meta title" maxlength="60">
                                        <div class="character-count">
                                            <span id="metaTitleCount">0</span>/60 characters
                                        </div>
                                    </div>
                                </div>
                                <div class="admin-form-col">
                                    <div class="mb-3">
                                        <label class="form-label">Meta Description</label>
                                        <textarea class="form-control" name="meta_description" rows="2" placeholder="SEO meta description"
                                            maxlength="160"></textarea>
                                        <div class="character-count">
                                            <span id="metaDescCount">0</span>/160 characters
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="featuredPost" name="featured"
                                    value="1">
                                <label class="form-check-label" for="featuredPost">
                                    Mark as featured post
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex mt-4">
                    <div style="width: 150px; flex-shrink: 0;"></div>
                    <div style="flex: 1; max-width: 600px;">
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Publish Post
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="cancelBtn">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.common.footer')
