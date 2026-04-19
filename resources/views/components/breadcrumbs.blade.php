@props([
    'items' => [] 
])
<nav aria-label="Breadcrumb" class="text-sm">
    <ol class="flex items-center flex-wrap">
        @foreach ($items as $item)
            <li class="flex items-center">
                @if (!$loop->first)
                    <i class="fas fa-chevron-right text-xs mx-2 text-unik-muted"></i>
                @endif

                @if (!empty($item['url']))
                    <a href="{{ $item['url'] }}"
                       class="flex items-center gap-1 text-unik-primary hover:text-unik-secondary">
                        @if(!empty($item['icon']))
                            <i class="fas {{ $item['icon'] }}"></i>
                        @endif
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="flex items-center gap-1 text-unik-muted font-medium">
                        @if(!empty($item['icon']))
                            <i class="fas {{ $item['icon'] }}"></i>
                        @endif
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

