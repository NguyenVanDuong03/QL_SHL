@push('styles')
<style>
    .breadcrumb-item + .breadcrumb-item::before {
        color: white;
    }
</style>
@endpush

<nav class="p-3 rounded">
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
