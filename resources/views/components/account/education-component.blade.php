<div class="row education-component">

    <div class="col-12">
        <div class="d-flex align-items-center mb-3">
            <h5 class="fs-16 text-dark fw-semibold  text-capitalize">My Education</h5>
            <div class="ms-auto fw-semibold d-flex gap-2 align-items-center">

                <button id="create-education" class="btn btn-light btn-sm border center-content gap-1">
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
                                <input type="checkbox" class="form-check-input m-0" id="education-enable-editing"
                                    checked="false">
                                <span class="cursor-pointer">Enable Editing</span>
                            </label>
                        </li>
                        <li>
                            <label class="dropdown-item d-flex align-items-center gap-2">
                                <input type="checkbox" class="form-check-input m-0" id="education-enable-deletion"
                                    checked="false">
                                <span class="cursor-pointer">Enable Deletion</span>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="row row-cols-1 row-cols-md-2 profile-section actionable-content px-4 g-2" id="education_view">
        @forelse ($educations as $education)
            <div class="col">
                <div class="profile-item">
                    <div class="avatar-sm profile-icon p-1">
                        <div class="avatar-title rounded-2 bg-light" style="height: 40px; width: 40px;">
                            <img src="{{ $education->logo_url }}"
                                onerror="this.onerror=null;this.src='{{ asset('assets/images/defaults/education-50.svg') }}';"
                                class="max-square-40" alt="company-img">
                        </div>

                          <div class="d-flex flex-column gap-2 p-2 mt-1">
                            <button class="btn-no-style edit-btn edit-education"
                                data-education-id="{{ $education->id }}"
                                data-institution="{{ $education->institution }}"
                                data-institution-logo="{{ $education->logo_url }}"
                                data-degree="{{ $education->degree }}"
                                data-field-of-study="{{$education->field_of_study}}"
                                data-start-date="{{ $education->start_date?->format("Y-m-d") ?? "" }}"
                                data-end-date="{{ $education->end_date?->format("Y-m-d") ?? "" }}"
                                data-grade="{{ $education->grade }}"
                                data-description="{{ $education->description }}"
                            >
                                <i class="bx bx-edit fs-5 text-success"></i>
                            </button>
                             <button class="btn-no-style delete-btn delete-education"
                                data-delete-url="{{ authRoute('user.education.delete', ['education' => $education]) }}"
                             ><i class="bx bx-trash fs-5 text-danger"></i></button>
                         </div>
                    </div>
                    <div class="exper-item-list">
                        <h5 class="fs-18 text-dark">{{ $education->institution }}</h5>
                        <p class="mb-2 fw-semibold text-dark">{{ $education->degree }} In
                            {{ $education->field_of_study }}</p>
                        <div class="list-inline list-inline-dots mb-2 fs-14">
                            <div class="list-inline-item"> {{ $education->start_date->format('M Y') }}</div>
                            <div class="list-inline-item list-inline-item-second">
                                {{ $education->end_date->format('M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <x-no-data />
        @endforelse
    </div>

    <div class="modal fade education" id="education-form-modal" tabindex="-1" aria-labelledby="education-form-title"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="education-form" action="#" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="education-form-title">Add Education</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-6 ">
                                <div class="row g-3">
                                    <!-- Institution -->
                                    <div class="col-12">
                                        <label for="institution-input" class="form-label">Institution</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i
                                                    class='bx bx-briefcase-alt-2 fs-5'></i></span>
                                            <input type="text" class="form-control" id="institution-input"
                                                name="institution" placeholder="e.g. Abc institute">
                                        </div>
                                    </div>

                                    <!-- Degree -->
                                    <div class="col-12">
                                        <label for="degree-input" class="form-label">Degree</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class='bx bx-map fs-5'></i></span>
                                            <input type="text" class="form-control" id="degree-input" name="degree"
                                                placeholder="e.g. Diploma, Btech, Ph.D">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Institution Logo -->
                            <div class="col-md-6 logo-input-area">
                                <label for="institution-logo-input" class="form-label">Institution Logo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-image fs-5'></i></span>
                                    <input type="file" class="form-control" accept="image/*"
                                        id="institution-logo-input" name="institution_logo"
                                        onchange="enableImagePick(this)">
                                </div>
                                <div class="mt-2 d-flex gap-3 " id="institution-logo-preivew">
                                </div>
                            </div>

                            <!-- Field Of Study -->
                            <div class="col-md-6">
                                <label for="field-of-study-type" class="form-label">Field Of Study</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-time-five fs-5'></i></span>
                                    <input type="text" class="form-control" id="field-of-study-input"
                                        name="field_of_study" placeholder="e.g. Computer Science">
                                </div>
                            </div>

                            <!-- GPA -->
                            <div class="col-md-6">
                                <label for="grade-type" class="form-label">GPA  </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-time-five fs-5'></i></span>
                                    <input type="number" class="form-control" id="grade-input"
                                        name="grade" placeholder="e.g. 7.85">
                                </div>
                            </div>

                            <!-- Start Date -->
                            <div class="col-lg-6">
                                <label for="education-start-input" class="form-label">Start Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-calendar fs-5'></i></span>
                                    <input type="date" class="form-control" id="education-start-input"
                                        max="{{ date('Y-m-d') }}" name="start_date">
                                </div>
                            </div>

                            <!-- End Date -->
                            <div class="col-lg-6">
                                <label for="education-end-input" class="form-label">End Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-calendar-minus fs-5'></i></span>
                                    <input type="date" class="form-control" id="education-end-input"
                                        name="end_date">
                                </div>
                            </div>
                            <!-- Description -->
                            <div class="col-12">
                                <label for="editor" class="form-label">Description</label>
                                <textarea class="form-control ckeditor ckeditor-minimal" id="editor" name="description" rows="3"
                                    placeholder="Describe your role, responsibilities, and achievements..."></textarea>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="education-save-btn">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
