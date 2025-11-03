 <div class="row work-experience-component">

     <div class="col-12">
         <div class="d-flex align-items-center mb-3">
             <h5 class="fs-16 text-dark fw-semibold  text-capitalize">Experience</h5>
             <div class="ms-auto fw-semibold d-flex gap-2 align-items-center">

                 <button id="create-work-experience" class="btn btn-light btn-sm border center-content gap-1">
                     <i class="bx bx-plus fs-5"></i>
                     <div>Create</div>
                 </button>

                 <div class="dropdown">
                     <a class="dropdown-toggle btn btn-light btn-sm border center-content" href="#" role="button"
                         data-bs-toggle="dropdown" aria-expanded="false">
                         <i class='bx bx-menu-alt-right fs-5 me-1'></i> Options
                     </a>
                     <ul class="dropdown-menu" style="min-width: 220px;">
                         <li>
                             <label class="dropdown-item d-flex align-items-center gap-2">
                                 <input type="checkbox" class="form-check-input m-0" id="work-experience-enable-editing" checked="false">
                                 <span class="cursor-pointer">Enable Editing</span>
                             </label>
                         </li>
                         <li>
                             <label class="dropdown-item d-flex align-items-center gap-2">
                                 <input type="checkbox" class="form-check-input m-0" id="work-experience-enable-deletion" checked="false">
                                 <span class="cursor-pointer">Enable Deletion</span>
                             </label>
                         </li>
                     </ul>
                 </div>
             </div>
         </div>
     </div>

     <div class="row row-cols-1 row-cols-md-2 profile-section actionable-content px-4 g-2" id="work_experience_view">
         @forelse ($experiences as $experience)
             <div class="col">
                 <div class="profile-item  ">
                     <div class="avatar-sm profile-icon p-1">
                         <div class="avatar-title rounded-2 bg-light" style="height: 35px; width: 35px;">
                             <img src="{{ $experience->logo_url }}"
                                 onerror="this.onerror=null;this.src='{{ asset('assets/images/defaults/experience-company-50.svg') }}';"
                                 class="max-square-40" alt="company-img">


                         </div>
                         <div class="d-flex flex-column gap-2 p-2 mt-1">
                            <button class="btn-no-style edit-btn edit-work-experience"
                                data-work-experience-id="{{ $experience->id }}"
                                data-job-title="{{ $experience->job_title }}"
                                data-employment-type="{{ $experience->employment_type }}"
                                data-company="{{$experience->company}}"
                                data-company-logo="{{ $experience->logo_url }}"
                                data-currently-working="{{ $experience->currently_working ? 'yes' : '' }}"
                                data-start-date="{{ $experience->start_date?->format("Y-m-d") ?? "" }}"
                                data-end-date="{{ $experience->end_date?->format("Y-m-d") ?? "" }}"
                                data-currently-working="{{ $experience->currently_working }}"
                                data-description="{{ $experience->description }}"
                                data-location="{{ $experience->location }}"
                            >
                                <i class="bx bx-edit fs-5 text-success"></i>
                            </button>
                             <button class="btn-no-style delete-btn delete-work-experience"
                                data-delete-url="{{ authRoute('user.work_experience.delete', ['workExperience' => $experience]) }}"
                             ><i class="bx bx-trash fs-5 text-danger"></i></button>
                         </div>

                     </div>
                     <div class="exper-item-list">
                         <div>
                             <h5 class="fs-16 text-dark">{{ $experience->job_title }}</h5>
                             <div class="list-inline list-inline-dots mb-1 fs-14">
                                 <div class="list-inline-item">{{ $experience->company }}</div>
                                 <div class="list-inline-item list-inline-item-second">
                                     {{ ucfirst(str_replace('_', '-', $experience->employment_type ?? '')) }}</div>
                             </div>
                             <div class="list-inline list-inline-dots mb-2 fs-14">
                                 <div class="list-inline-item">{{ $experience->start_date->format('M Y') }}
                                     <strong>-</strong>
                                     {{ !empty($experience->currently_working) ? 'Present' : $experience->end_date?->format('M Y') }}
                                 </div>

                                 <div class="list-inline-item list-inline-item-second">{{ $experience->duration }}</div>
                             </div>
                             <p class="mb-0 text-dark">{{ $experience->description }}</p>
                         </div>
                     </div>

                 </div>
             </div>
         @empty
             <x-no-data />
         @endforelse


     </div>

     <div class="modal fade work-experience" id="work-experience-form-modal" tabindex="-1"
         aria-labelledby="work-experience-form-title" aria-hidden="true">
         <div class="modal-dialog modal-lg modal-dialog-centered">
             <div class="modal-content">
                 <form id="work-experience-form" action="#" method="post" enctype="multipart/form-data">
                     @csrf
                     <div class="modal-header">
                         <h1 class="modal-title fs-5" id="work-experience-form-title">Add Work Experience</h1>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>

                     <div class="modal-body">
                         <div class="row g-3">

                             <!-- Job Title -->
                             <div class="col-md-6">
                                 <label for="job-title-input" class="form-label">Job Title</label>
                                 <div class="input-group">
                                     <span class="input-group-text"><i class='bx bx-briefcase-alt-2 fs-5'></i></span>
                                     <input type="text" class="form-control" id="job-title-input" name="job_title"
                                         placeholder="e.g. Software Engineer">
                                 </div>
                             </div>

                             <!-- Employment Type -->
                             <div class="col-md-6">
                                 <label for="employment-type" class="form-label">Employment Type</label>
                                 <div class="input-group">
                                     <span class="input-group-text"><i class='bx bx-time-five fs-5'></i></span>
                                     <select class="form-select" id="employment-type" name="employment_type">
                                         <option value="full_time">Full Time</option>
                                         <option value="part_time">Part Time</option>
                                         <option value="internship">Internship</option>
                                         <option value="freelance">Freelance</option>
                                     </select>
                                 </div>
                             </div>

                             <div class="col-md-6 ">
                                 <div class="row g-3">
                                     <!-- Company Name -->
                                     <div class="col-12">
                                         <label for="company-input" class="form-label">Company Name</label>
                                         <div class="input-group">
                                             <span class="input-group-text"><i class='bx bx-buildings fs-5'></i></span>
                                             <input type="text" class="form-control" id="company-input"
                                                 name="company" placeholder="e.g. Google">
                                         </div>
                                     </div>

                                     <!-- Company Location -->
                                     <div class="col-12">
                                         <label for="company-location-input" class="form-label">Company
                                             Location</label>
                                         <div class="input-group">
                                             <span class="input-group-text"><i class='bx bx-map fs-5'></i></span>
                                             <input type="text" class="form-control" id="company-location-input"
                                                 name="location" placeholder="e.g. Bangalore, India">
                                         </div>
                                     </div>
                                 </div>
                             </div>

                             <!-- Company Logo -->
                             <div class="col-md-6 logo-input-area">
                                 <label for="company-logo-input" class="form-label">Company Logo</label>
                                 <div class="input-group">
                                     <span class="input-group-text"><i class='bx bx-image fs-5'></i></span>
                                     <input type="file" class="form-control" accept="image/*"
                                         id="company-logo-input" name="company_logo" onchange="enableWorkExperienceImagePick(this)">
                                 </div>

                                 <div class="mt-2 d-flex gap-3 " id="company-logo-preivew">

                                 </div>
                             </div>



                             <!-- Start Date -->
                             <div class="col-lg-4">
                                 <label for="company-start-input" class="form-label">Start Date</label>
                                 <div class="input-group">
                                     <span class="input-group-text"><i class='bx bx-calendar fs-5'></i></span>
                                     <input type="date" class="form-control" id="company-start-input" max="{{ date("Y-m-d") }}"
                                         name="start_date">
                                 </div>
                             </div>

                             <!-- End Date -->
                             <div class="col-lg-4">
                                 <label for="company-end-input" class="form-label">End Date</label>
                                 <div class="input-group">
                                     <span class="input-group-text"><i class='bx bx-calendar-minus fs-5'></i></span>
                                     <input type="date" class="form-control" id="company-end-input"
                                         name="end_date">
                                 </div>
                             </div>

                             <!-- Currently Working -->
                             <div class="col-lg-4 d-flex align-items-center pt-2">

                                 <div class="form-check ms-1 mt-lg-2">
                                     <input class="form-check-input" type="checkbox" name="currently_working"
                                         value="yes" id="currently-working-input" checked="false" onchange="enableCurrentlyWorkingCheckbox()">
                                     <label class="form-check-label" for="currently-working-input">
                                         Currently Working
                                     </label>
                                 </div>
                             </div>

                             <!-- Description -->
                             <div class="col-12">
                                 <label for="editor" class="form-label">Description</label>
                                 <textarea class="form-control ckeditor ckeditor-minimal" id="experience-editor" name="description" rows="3"
                                     placeholder="Describe your role, responsibilities, and achievements..."></textarea>
                             </div>

                         </div>
                     </div>

                     <div class="modal-footer">
                         <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                             Cancel
                         </button>
                         <button type="submit" class="btn btn-primary" id="save-work-experience">
                             Save
                         </button>
                     </div>
                 </form>
             </div>
         </div>
     </div>


 </div>
