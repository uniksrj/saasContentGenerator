@vite('resources/css/app.css')
@vite('resources/js/app.js')
@vite('resources/js/admin.js')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<style>
    /* Reset Bootstrap row conflicts */
    .admin-edit-container {
        display: flex;
        min-height: 100vh;
        background: #f8f9fa;
    }

    .main-content {
        flex: 1;
        margin-left: 250px;
        padding: 20px;
        width: calc(100% - 250px);
        transition: all 0.3s ease;
    }

    /* Override Bootstrap row styles */
    .admin-edit-content {
        display: block;
        margin: 0;
        padding: 0;
    }

    .admin-edit-content>* {
        max-width: none;
        padding-right: 0;
        padding-left: 0;
        margin-top: 0;
    }

    .addPost {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #3498db;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .add_head {
        color: #34495e;
        font-weight: 600;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Form Container */
    .form-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .form-label i {
        margin-right: 8px;
        color: #3498db;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, #3498db, #2980b9);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
    }

    /* Image Upload Section */
    .upload-section {
        border: 2px dashed #bdc3c7;
        border-radius: 8px;
        padding: 1.5rem;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .upload-section:hover {
        border-color: #3498db;
        background: #ecf0f1;
    }

    .upload-icon {
        font-size: 2rem;
        color: #7f8c8d;
        margin-bottom: 1rem;
        text-align: center;
    }

    .current-images {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e9ecef;
    }

    .image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1rem;
    }

    .image-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .image-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .image-item img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        display: block;
    }

    .image-remove {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(231, 76, 60, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .image-remove:hover {
        background: #c0392b;
    }

    /* Character Count */
    .character-count {
        font-size: 0.8rem;
        color: #7f8c8d;
        text-align: right;
        margin-top: 0.25rem;
    }

    /* Form Sections */
    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #ecf0f1;
    }

    /* Custom flex layouts */
    .form-row {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        gap: 1rem;
    }

    .form-label-col {
        flex: 0 0 120px;
        padding-top: 0.5rem;
    }

    .form-input-col {
        flex: 1;
        max-width: 600px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 15px;
            width: 100%;
        }

        .form-row {
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label-col {
            flex: none;
            width: 100%;
        }

        .form-input-col {
            flex: none;
            width: 100%;
            max-width: none;
        }

        .image-preview {
            justify-content: center;
        }

        .image-item img {
            width: 120px;
            height: 120px;
        }
    }

    @media (max-width: 480px) {
        .main-content {
            padding: 10px;
        }

        .form-container {
            padding: 1rem;
        }

        .image-item img {
            width: 100px;
            height: 100px;
        }
    }

    /* Loading State */
    .btn-loading {
        position: relative;
        color: transparent;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-top: 2px solid #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: translate(-50%, -50%) rotate(0deg);
        }

        100% {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }
</style>

@include('admin.common.header')

<div class="admin-edit-container">
    @include('admin.common.sidebar')

    <div class="main-content">
        <!-- Page Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 class="h2 mb-1 addPost">Edit Post</h1>
                <p class="text-muted mb-0">Update your blog post details and images</p>
            </div>
            <a href="/admin" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>

        <!-- Edit Form -->
        <div class="form-container">
            <h4 class="add_head mb-4">
                <i class="fas fa-edit me-2"></i>Update Post Details
            </h4>

            <form data-id="{{ $post->id }}" enctype="multipart/form-data" id="editForm">
                @csrf
                @method('PUT')

                <!-- Title Section -->
                <div class="form-section">
                    <div class="d-flex align-items-center mb-3">
                        <div style="width: 150px; flex-shrink: 0;">
                            <label class="form-label">
                                <i class="fas fa-heading me-2"></i>Title
                            </label>
                        </div>
                        <div style="flex: 1; max-width: 600px;">
                            <input class="form-control" name="title" type="text" maxlength="50"
                                placeholder="Enter a compelling title for your post..." value="{{ $post->title }}"
                                aria-label="Post title" id="postTitle">
                            <div class="character-count">
                                <span id="titleCount">{{ strlen($post->title) }}</span>/50 characters
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slug Section -->
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
                                <input class="form-control slug-input" name="slug" value="{{ $post->slug }}"
                                    type="text" maxlength="100" placeholder="url-friendly-slug"
                                    aria-label="Post slug" id="postSlug">
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

                <!-- Description Section -->
                <div class="form-section">
                    <div class="d-flex mb-3">
                        <div style="width: 150px; flex-shrink: 0;">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-2"></i>Description
                            </label>
                        </div>
                        <div style="flex: 1; max-width: 600px;">
                            <textarea class="form-control" name="desc" id="description" rows="6"
                                placeholder="Write your post content here...">{{ $post->description }}</textarea>
                            <div class="character-count">
                                <span id="descCount">{{ strlen($post->description) }}</span> characters
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
                                    <i class="fas fa-magic me-1"></i> Regenerate TOC
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm mb-2" id="resetTOCBtn">
                                    <i class="fas fa-redo me-1"></i> Clear TOC
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm mb-2" id="viewTOCBtn">
                                    <i class="fas fa-eye me-1"></i> View Current TOC
                                </button>
                                <p class="small text-muted mb-0">Auto-generates from markdown (##, ###, ####) or HTML headings (&lt;h2&gt;-&lt;h4&gt;) in your content
                                </p>
                            </div>

                            <!-- Hidden fields to store TOC data -->
                            @php
                                $tocData = [];

                                if (is_string($post->table_of_contents) && trim($post->table_of_contents) !== '') {
                                    // Decode as objects so existing Blade access ($item->title, $item->level) keeps working.
                                    $decoded = json_decode($post->table_of_contents);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                                        $tocData = $decoded;
                                    }
                                }

                                $readingTime = $post->reading_time ?? ceil(str_word_count(strip_tags($post->description)) / 200);
                                $wordCount = $post->word_count ?? str_word_count(strip_tags($post->description));
                            @endphp
                            <input type="hidden" name="table_of_contents" id="tableOfContents"
                                value="{{ !empty($tocData) ? json_encode($tocData) : '' }}">
                            <input type="hidden" name="reading_time" id="readingTime" value="{{ $readingTime }}">
                            <input type="hidden" name="word_count" id="wordCount" value="{{ $wordCount }}">
                            
                            <!-- TOC Preview -->
                            <div class="card mb-3" id="tocPreviewCard"
                                style="{{ !empty($tocData) ? '' : 'display: none;' }}">
                                <div
                                    class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-list me-1"></i> Table of Contents
                                        <span id="tocSectionCount" class="badge bg-primary ms-2">
                                            {{ !empty($tocData) ? count($tocData) : '0' }} sections
                                        </span>
                                    </h6>
                                    <div class="small text-muted">
                                        {{ $readingTime }} min read • {{ number_format($wordCount) }} words
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    @if (!empty($tocData))
                                        <ul id="tocPreviewList" class="list-unstyled mb-0">
                                            @foreach ($tocData as $index => $item)
                                                <li class="mb-2"
                                                    style="padding-left: {{ ($item->level - 2) * 20 }}px">
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-primary me-2" style="min-width: 24px;">
                                                            {{ $index + 1 }}
                                                        </span>
                                                        <span class="small">{{ $item->title }}</span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div id="tocPreviewList" class="text-muted small">
                                            No table of contents generated yet. Click "Regenerate TOC" to create one.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="row g-2 mt-3" id="tocStats"
                                style="{{ !empty($tocData) ? '' : 'display: none;' }}">
                                <div class="col-4">
                                    <div class="border rounded p-2 text-center">
                                        <div class="small text-muted">Sections</div>
                                        <div class="h5 mb-0" id="statSections">
                                            {{-- {{ !empty($tocData) ? count($tocData) : '0' }} --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border rounded p-2 text-center">
                                        <div class="small text-muted">Words</div>
                                        <div class="h5 mb-0" id="statWords">{{ number_format($wordCount) }}</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border rounded p-2 text-center">
                                        <div class="small text-muted">Read Time</div>
                                        <div class="h5 mb-0" id="statReadTime">{{ $readingTime }} min</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Upload Section -->
                <div class="form-section">
                    <div class="form-row">
                        <div class="form-label-col">
                            <label class="form-label">
                                <i class="fas fa-image"></i>Images
                            </label>
                        </div>
                        <div class="form-input-col">
                            <div class="upload-section">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <p class="text-center mb-2">Drag & drop new images here or click to browse</p>
                                <p class="text-center small text-muted mb-3">Supports JPG, PNG, GIF - Max 5MB each</p>
                                <div class="text-center">
                                    <input class="form-control form-control-sm d-none" name="file" id="upload"
                                        accept="image/*" type="file">
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="document.getElementById('upload').click()">
                                        <i class="fas fa-folder-open me-2"></i>Choose Files
                                    </button>
                                </div>
                            </div>

                            <!-- Current Images -->
                            <div class="current-images">
                                <h6 class="mb-3">
                                    <i class="fas fa-images me-2"></i>Current Images
                                    <small class="text-muted">(Click × to remove)</small>
                                </h6>
                                <div class="image-preview" id="currentImages">
                                    @php
                                        $imagepaths = json_decode($post->file_path);
                                    @endphp

                                    @if (!empty($imagepaths))
                                        @if (is_array($imagepaths))
                                            @foreach ($imagepaths as $index => $imagepath)
                                                @php
                                                    $previewPath = $imagepath->thumb->webp ?? $imagepath->thumb->jpeg ?? $imagepath->medium->webp ?? $imagepath->medium->jpeg ?? $imagepath->large->webp ?? $imagepath->large->jpeg ?? $imagepath->original->webp ?? $imagepath->original->jpeg ?? null;
                                                @endphp
                                                <div class="image-item">
                                                    <img src="{{ $previewPath ? asset('storage/' . $previewPath) : asset('images/default-thumbnail.jpg') }}"
                                                        alt="Current Image {{ $index + 1 }}">
                                                    <button type="button" class="image-remove"
                                                        onclick="removeImage(this, '{{ $previewPath ?? '' }}')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @else
                                            @php
                                                $previewPath = $imagepaths->thumb->webp ?? $imagepaths->thumb->jpeg ?? $imagepaths->medium->webp ?? $imagepaths->medium->jpeg ?? $imagepaths->large->webp ?? $imagepaths->large->jpeg ?? $imagepaths->original->webp ?? $imagepaths->original->jpeg ?? null;
                                            @endphp
                                            <div class="image-item">
                                                <img src="{{ $previewPath ? asset('storage/' . $previewPath) : asset('images/default-thumbnail.jpg') }}"
                                                    alt="Current Image">
                                                <button type="button" class="image-remove"
                                                    onclick="removeImage(this, '{{ $previewPath ?? '' }}')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <input type="hidden" name="removed_images" id="removedImages" value="">
                            </div>

                            <!-- New Images Preview -->
                            <div class="current-images" id="newImagesPreview" style="display: none;">
                                <h6 class="mb-3">
                                    <i class="fas fa-plus-circle me-2"></i>New Images to Upload
                                </h6>
                                <div class="image-preview" id="newImagesContainer"></div>
                            </div>
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
                                                <option value="{{ $key }}"
                                                    {{ $post->category == $key ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="admin-form-col">
                                    <div class="mb-3">
                                        <label class="form-label">Tags</label>
                                        @php
                                            $postTags = json_decode($post->tags ?? '[]', true) ?? [];
                                            $allTags = array_unique(array_merge($commonTags, $postTags));
                                        @endphp

                                        <select class="form-control select2" name="tags[]" id="tagsSelect"
                                            multiple="multiple">
                                            @foreach ($allTags as $tag)
                                                @php
                                                    $isSelected = in_array($tag, $postTags);
                                                @endphp
                                                <option value="{{ $tag }}"
                                                    {{ $isSelected ? 'selected' : '' }}>
                                                    {{ $tag }}
                                                </option>
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
                                            placeholder="SEO meta title" maxlength="60"
                                            value="{{ $post->meta_title }}">
                                        <div class="character-count">
                                            <span id="metaTitleCount">0</span>/60 characters
                                        </div>
                                    </div>
                                </div>
                                <div class="admin-form-col">
                                    <div class="mb-3">
                                        <label class="form-label">Meta Description</label>
                                        <textarea class="form-control" name="meta_description" rows="2" placeholder="SEO meta description"
                                            maxlength="160">{{ $post->meta_description }}</textarea>
                                        <div class="character-count">
                                            <span id="metaDescCount">0</span>/160 characters
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="featuredPost"
                                    {{ $post->is_featured ? 'checked' : '' }} name="featured" value="1">
                                <label class="form-check-label" for="featuredPost">
                                    Mark as featured post
                                </label>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Submit Section -->
                <div class="form-row">
                    <div class="form-label-col"></div>
                    <div class="form-input-col">
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <button type="submit" id="submit_data" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Post
                            </button>
                            <a href="/admin" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('postTitle');
        const descTextarea = document.getElementById('description');
        const titleCount = document.getElementById('titleCount');
        const descCount = document.getElementById('descCount');

        titleInput.addEventListener('input', function() {
            titleCount.textContent = this.value.length;
        });

        descTextarea.addEventListener('input', function() {
            descCount.textContent = this.value.length;
        });

        // File upload preview
        const fileInput = document.getElementById('upload');
        const newImagesPreview = document.getElementById('newImagesPreview');
        const newImagesContainer = document.getElementById('newImagesContainer');

        fileInput.addEventListener('change', function(e) {
            const files = e.target.files;
            newImagesContainer.innerHTML = '';

            if (files.length > 0) {
                newImagesPreview.style.display = 'block';

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const imageItem = document.createElement('div');
                        imageItem.className = 'image-item';
                        imageItem.innerHTML = `
                            <img src="${e.target.result}" alt="New Image ${i + 1}">
                            <button type="button" class="image-remove" onclick="removeNewImage(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        newImagesContainer.appendChild(imageItem);
                    };

                    reader.readAsDataURL(file);
                }
            } else {
                newImagesPreview.style.display = 'none';
            }
        });

        const uploadSection = document.querySelector('.upload-section');
        uploadSection.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.borderColor = '#3498db';
            this.style.background = '#ecf0f1';
        });

        uploadSection.addEventListener('dragleave', function() {
            this.style.borderColor = '#bdc3c7';
            this.style.background = '#f8f9fa';
        });

        uploadSection.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.borderColor = '#bdc3c7';
            this.style.background = '#f8f9fa';

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;

                // Trigger change event to show preview
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        });
    });
</script>

@include('admin.common.footer')
