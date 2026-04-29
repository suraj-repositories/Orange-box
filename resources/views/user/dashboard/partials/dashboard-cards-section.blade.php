 <div class="row g-3">
     <div class="col-12 col-md-12">
         <div class="dashboard-card-container">
             <div class="dashboard-card">
                 <div class="row g-0">

                     <!-- Content -->
                     <div class="col-12 col-md-6 d-flex">
                         <div class="card-content">
                             <div class="card-label">
                                 <span class="icon-box">
                                     <i class="bx bx-cube-alt text-dark"></i>
                                 </span>
                                 Projects
                             </div>

                             <h2 class="card-title">
                                 Welcome back
                             </h2>

                             <p class="card-description">
                                 Organize your ideas, manage tasks, and keep everything in one place.
                             </p>

                             <a href="{{ authRoute('user.project-board') }}" class="card-button">
                                 Go to Projects <i class="bi bi-arrow-right ms-2"></i>
                             </a>
                         </div>
                     </div>

                     <!-- Image -->
                     <div class="col-md-6 image-wrapper d-none d-md-flex align-items-end justify-content-end">
                         <img src="{{ asset('assets/images/landing/project-creation.png') }}" alt="Projects">
                     </div>

                 </div>
             </div>
         </div>
     </div>

     <div class="col-12 col-md-8">
         <a href="{{ authRoute('user.documentation.index') }}" class="card-link-wrapper">
             <div class="dashboard-card-container">
                 <div class="dashboard-card documentations-card">
                     <div class="row g-0">

                         <div class="col-12 col-md-6 d-flex">
                             <div class="card-content">
                                 <div class="card-label">
                                     <span class="icon-box">
                                         <i class="bx bx-news"></i>
                                     </span>
                                     Documentation
                                 </div>

                                 <h3 class="card-title">
                                     Build & Publish Docs Easily
                                 </h3>

                                 <p class="card-description">
                                     Create structured documentation, customize layouts, and deploy instantly.
                                 </p>

                                 <span class="explore-button">
                                     Explore Docs <i class="bi bi-arrow-right"></i>
                                 </span>
                             </div>
                         </div>

                         <div class="col-md-6 image-wrapper d-none d-md-flex align-items-end justify-content-end">
                             <img src="{{ asset('assets/images/landing/dashboard-card-bg-1.png') }}"
                                 alt="Documentation">
                         </div>

                     </div>
                 </div>
             </div>
         </a>
     </div>

     <div class="col-12 col-md-4">
         <a href="{{ authRoute('user.folder-factory') }}" class="card-link-wrapper">
             <div class="dashboard-card-container">
                 <div class="dashboard-card small-card file-management-card">

                     <div class="card-content">
                         <div class="card-label">
                             <span class="icon-box">
                                 <i class='bx bx-server'></i>
                             </span>
                             Storage
                         </div>

                         <h3 class="card-title">
                             File Management
                         </h3>

                         <p class="card-description">
                             Upload, organize, and access your files anytime.
                         </p>

                         <span class="explore-button">
                             Explore Files <i class="bi bi-arrow-right"></i>
                         </span>
                     </div>

                     <div class="image-wrapper-circular d-none d-md-block mt-3">
                         <img src="{{ asset('assets/images/landing/octa-blue.png') }}" alt="Storage">
                     </div>

                 </div>
             </div>
         </a>
     </div>
 </div>
