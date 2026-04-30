 <div class="tag-scroll-wrapper mb-1">

     <button class="scroll-btn left" type="button" onclick="scrollTags(-200)">
         <i class='bx bx-chevron-left'></i>
     </button>

     <div class="tag-scroll-container" id="tagScrollContainer">
         @foreach ($quickLinks as $quickLink)
             @php
                 $url = $quickLink->route_name
                     ? authRoute($quickLink->route_name, json_decode($quickLink->route_params ?? '[]', true))
                     : $quickLink->external_url;
             @endphp

             <a href="{{ $url }}" target="{{ $quickLink->target ?? '_self' }}"
                 class="tag-chip tag-chip-{{ $quickLink->color ?? 'primary' }}">

                 @if ($quickLink->icon)
                     <i class="{{ $quickLink->icon }}"></i>
                 @endif

                 <span class="text-dark">{{ $quickLink->title }}</span>
             </a>
         @endforeach
     </div>

     <button class="scroll-btn right" type="button" onclick="scrollTags(200)">
         <i class='bx bx-chevron-right'></i>
     </button>

 </div>
