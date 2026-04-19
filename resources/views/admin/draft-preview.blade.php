@php
    $content = (string) ($post->description ?? '');
    $isHtml = str_contains($content, '<h2') || str_contains($content, '<p') || str_contains($content, '<ul');
@endphp

@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Draft Preview</h1>
            <p class="text-muted mb-0">Review before publishing to live blog.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.drafts') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Drafts
            </a>
            <form method="POST" action="{{ route('admin.drafts.publish', $post->id) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-upload me-1"></i>Publish Now
                </button>
            </form>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="mb-2 text-uppercase small text-muted">{{ $post->category ?: 'technology' }}</div>
            <h2 class="h4">{{ $post->title }}</h2>
            <p class="text-muted mb-1"><strong>Source type:</strong> {{ $post->source_type ?: 'manual' }}</p>
            <p class="text-muted mb-1"><strong>Meta description:</strong> {{ $post->meta_description }}</p>
            <p class="text-muted mb-0"><strong>Reading time:</strong> {{ $readingTime }} min | <strong>Word count:</strong> {{ number_format($wordCount) }}</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            @if ($isHtml)
                {!! $content !!}
            @else
                {!! (new Parsedown())->text($content) !!}
            @endif
        </div>
    </div>
</div>
