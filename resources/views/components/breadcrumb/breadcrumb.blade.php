@push('styles')
    <style>
        .breadcrumb-item + .breadcrumb-item::before {
            color: white;
        }

        .breadcrumb-check {
            font-size: 0.875rem;
        }
    </style>
@endpush

<nav class="p-3 rounded breadcrumb-check d-none d-md-block">
    <ul class="breadcrumb mb-0">
        @foreach ($links as $link)
            @if (!$loop->last)
                <li class="breadcrumb-item">
                    <a href="{{ isset($link['params']) ? route($link['url'], $link['params']) : route($link['url']) }}" class="text-white text-decoration-none">
                        {{ $link['label'] }}
                    </a>
                </li>
            @else
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">
                    {{ $link['label'] }}
                </li>
            @endif
        @endforeach
    </ul>
</nav>
