 <div class="card overflow-hidden">

     <div class="card-header">
         <div class="d-flex align-items-center">
             <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                 <i data-feather="tablet" class="widgets-icons"></i>
             </div>
             <h5 class="card-title mb-0">Memory Monitor</h5>
         </div>
     </div>

     <div class="card-body p-0">
         <div class="memory-tracker-chart-parent">
             <div id="memory-tracker" class="apex-charts memory-tracker-chart"
                 data-memory-used="{{ $usedMemoryPercent }}"></div>
         </div>
         <div class="row text-center">
             <div class="col-6">
                 <p class="text-muted mb-2">Total Used</p>
                 <h4 class="text-dark mb-3 fw-bold">{{ $usedMemory }}</h4>
             </div>
             <div class="col-6">
                 <p class="text-muted mb-2">Total Available</p>
                 <h4 class="text-dark mb-3 fw-bold">{{ $availableMemory }}</h4>
             </div>
         </div>
     </div>

 </div>
