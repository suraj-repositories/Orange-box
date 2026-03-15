@foreach ($socialLinks as $socialLink)
    <a class="dropdown-item p-2 pe-1 text-center" href="{{ $socialLink->url }}" target="_blank">
        @if (empty($socialLink->icon) && !empty($socialLink->platform))
            <i class='{{ $socialLink?->platform?->icon }} fs-4'></i>
        @elseif (empty($socialLink->icon))
            <i class='bx bx-link fs-4'></i>
        @else
            <img src="{{ Storage::url($socialLink->icon) }}" alt="" width="20px" height="20px" style="object-fit: contain">
        @endif
    </a>
@endforeach
