 <div class="tag-scroll-wrapper mb-1">

     <button class="scroll-btn left" type="button" onclick="scrollTags(-200)">
         <i class='bx bx-chevron-left'></i>
     </button>

     <div class="tag-scroll-container" id="tagScrollContainer">
         @foreach ($tags as $tag)
             <button class="tag-chip tag-chip-primary">
                 <i class="bx bx-file"></i> {{ $tag }}
             </button>
         @endforeach
     </div>

     <button class="scroll-btn right" type="button" onclick="scrollTags(200)">
         <i class='bx bx-chevron-right'></i>
     </button>

 </div>
