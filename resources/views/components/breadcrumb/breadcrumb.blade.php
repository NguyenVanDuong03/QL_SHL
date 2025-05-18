@push('styles')
<style>
    .breadcrumb-item + .breadcrumb-item::before {
        color: white;
    }

    @media screen and (max-width: 768px) {
        .breadcrumb-check {
           display: none;
        }

    }
</style>
@endpush

<nav class="p-3 rounded breadcrumb-check">
    <ul class="breadcrumb mb-0">
        @foreach ($links as $link)
            @if (!$loop->last)
                <li class="breadcrumb-item">
                    <a href="{{ route($link['url']) }}" class="text-white text-decoration-none">
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
