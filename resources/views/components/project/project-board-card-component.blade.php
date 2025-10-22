 <div class="col">
     <div class="card d-flex flex-column h-100">

         @if (!empty($projectBoard->colorTag))
             <span class="ribbon-3 top-right ribbon-{{ strtolower($projectBoard->colorTag->name) }}">
                 <span><i class='bx bxs-circle'></i></span>
             </span>
         @endif
         <img class="card-img-top rounded-top" src="{{ $projectBoard->thumbnail_url }}"
             onerror="this.onerror=null;this.src='{{ config('constants.DEFAULT_PROJECT_THUMBNAIL') }}';" alt="">

         <div class="card-body flex-grow-1 pb-0">
             <a
                 href="{{ request()->attributes->get('is_collaboration') ? authRoute('user.collab.project-board.show', ['owner' => $projectBoard->user->username, 'slug' => $projectBoard->slug]) : authRoute('user.project-board.show', ['slug' => $projectBoard->slug]) }}">
                 <h6 class="fw-bold truncate-3 text-dark">
                     {{ $projectBoard->title }}
                 </h6>
             </a>
             <p class="card-text text-muted mb-0 truncate-3">
                 {{ $projectBoard->preview_text }}
             </p>
         </div>

         <div class="card-footer mt-auto pt-0">
             <div class="action-container d-flex justify-content-between align-items-center">
                 <div class="ago-string">
                     <strong>Created: </strong> {{ $projectBoard->created_at->diffForHumans() }}
                 </div>
                 <div class="action-buttons d-flex gap-1">
                     @if (auth()->user()->id == $projectBoard->user_id)
                         <a href="{{ request()->attributes->get('is_collaboration') ? authRoute('user.collab.project-board.show', ['owner' => $projectBoard->user->username, 'slug' => $projectBoard->slug]) : authRoute('user.project-board.show', ['slug' => $projectBoard->slug]) }}"
                             class="info"><i class='bx bx-info-circle'></i></a>

                         <a href="{{ authRoute('user.project-board.edit', ['slug' => $projectBoard->slug]) }}"
                             class="edit"><i class='bx bx-edit'></i></a>
                         <form action="{{ authRoute('user.project-board.delete', ['slug' => $projectBoard->slug]) }}"
                             method="post">
                             @method('DELETE')
                             @csrf
                             <button type="submit" class="delete btn-no-style">
                                 <i class='bx bx-trash'></i>
                             </button>
                         </form>
                     @endif
                 </div>
             </div>
         </div>
     </div>
 </div>
