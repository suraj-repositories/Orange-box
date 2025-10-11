 <div class="modal fade pick-user-model" id="pick-user-modal" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">

             <div class="modal-header">
                 <h5 class="modal-title fw-semibold">{{ $title }}</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>

             <div class="modal-body">
                 <div class="search-wrapper mb-4">
                     <div class="input-group border rounded shadow-sm">
                         <span class="input-group-text bg-transparent border-0 ps-3 pe-0">
                             <i class="bi bi-search text-muted fs-5"></i>
                         </span>
                         <input type="search" class="form-control border-0 shadow-none rounded-pill pe-4"
                             placeholder="Search users..." aria-label="Search users">
                     </div>
                 </div>

                 <div id="user-pick-results">
                 </div>
             </div>

             <div class="modal-footer border-0">
                 <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
             </div>

         </div>
     </div>
 </div>
