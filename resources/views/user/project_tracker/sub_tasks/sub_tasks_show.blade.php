 <div class="card sub_tasks">
     <div class="card-header">
         <div class="d-flex align-items-center">
             <div class="rounded-2 me-2 widget-icons-sections">
                 <i data-feather="crosshair" class="widgets-icons"></i>
             </div>
             <h5 class="card-title mb-0">Sub Tasks</h5>

             <div class="ms-auto fw-semibold d-flex gap-1">
                 <a class="btn btn-light btn-sm border center-content gap-1" id="new-sub-task-btn" data-submit-url="{{ authRoute('user.tasks.sub_task.store', ['task' => $task]) }}">
                     <i class="bx bx-plus fs-5"></i>
                     <div> Add</div>
                 </a>
             </div>

             <div class="modal fade" id="sub_task_form_modal" tabindex="-1" aria-labelledby="sub_task_form_title"
                 aria-hidden="true">
                 <div class="modal-dialog modal-lg modal-dialog-scrollable">
                     <form action="#" method="POST"
                         enctype="multipart/form-data">
                         @csrf
                         <div class="modal-content">
                             <div class="modal-header">
                                 <h1 class="modal-title fs-5" id="sub_task_form_title">Create Sub Task</h1>
                                 <button type="button" class="btn-close" data-bs-dismiss="modal"
                                     aria-label="Close"></button>
                             </div>
                             <div class="modal-body mh-70-vh">
                                 <div class="row">
                                     <div class="col col-12 col-md-12">
                                         <label for="title-input" class="form-label">Enter Description</label>

                                         <textarea class="form-control ckeditor ckeditor-minimal" name="description" id="editor" cols="30" rows="3"
                                             data-markdown="{{ !empty($dailyDigest) ? $dailyDigest->description : '' }}">
                                                {{ !empty($dailyDigest) ? trim($dailyDigest->description) : '' }}
                                            </textarea>

                                         @error('description')
                                             <small class="text-danger">{{ $message }}</small>
                                         @enderror
                                     </div>

                                     <div class="col col-md-12 mt-2">

                                         <input type="file" class="hide" name="media_files[]" id="media-input"
                                             multiple>

                                         <div class="d-flex align-items-center gap-2  mb-2">

                                             <label class="circle-40 cursor-pointer" for="media-input">
                                                 <img src="{{ asset('assets/images/svg/files-icon-24.svg') }}"
                                                     alt="upload" title="File Upload">
                                             </label>

                                             <div class="status-radios d-flex flex-wrap gap-2 ms-auto ">

                                                 <!-- Pending -->
                                                 <input type="radio" class="btn-check" name="status" value="pending"
                                                     id="status-pending" autocomplete="off" >
                                                 <label
                                                     class="status-btn status-pending rounded-circle circle-40 d-flex align-items-center justify-content-center p-2"
                                                     for="status-pending" title="Pending">
                                                     <i class='bx bx-time fs-4'></i>
                                                 </label>

                                                 <!-- Hold -->
                                                 <input type="radio" class="btn-check" name="status" id="status-hold"
                                                     value="on_hold" autocomplete="off">
                                                 <label
                                                     class="status-btn status-hold rounded-circle circle-40 d-flex align-items-center justify-content-center p-2"
                                                     for="status-hold" title="Hold">
                                                     <i class='bx bx-pause-circle fs-4'></i>
                                                 </label>

                                                 <!-- In Progress -->
                                                 <input type="radio" class="btn-check" name="status"
                                                     value="in_progress" id="status-progress" autocomplete="off">
                                                 <label
                                                     class="status-btn status-progress rounded-circle circle-40 d-flex align-items-center justify-content-center p-2"
                                                     for="status-progress" title="In Progress">
                                                     <i class='bx bx-loader bx-spin fs-4'></i>
                                                 </label>

                                                 <!-- Completed -->
                                                 <input type="radio" class="btn-check" name="status"
                                                     value="completed" id="status-completed" autocomplete="off">
                                                 <label
                                                     class="status-btn status-completed rounded-circle circle-40 d-flex align-items-center justify-content-center p-2"
                                                     for="status-completed" title="Completed">
                                                     <i class='bx bx-check-circle fs-4'></i>
                                                 </label>

                                             </div>

                                             <div class="btn-group-horizontal grid-list hide-lt-730 hide"
                                                 id="card-list-tab-toggler" role="group">
                                                 <input type="radio" class="btn-check" name="hbtn-radio"
                                                     id="card-radio" autocomplete="off" checked>
                                                 <label class="btn btn-outline-primary" for="card-radio"><i
                                                         class='bx bx-grid-alt'></i></label>
                                                 <input type="radio" class="btn-check" name="hbtn-radio"
                                                     id="list-radio" autocomplete="off">
                                                 <label class="btn btn-outline-primary" for="list-radio"><i
                                                         class='bx bx-list-ul'></i></label>
                                             </div>

                                         </div>
                                         @error('media_files')
                                             <small class="text-danger">{{ $message }}</small>
                                         @enderror

                                         <div id="list-view-container" class="media-upload-preview mt-3 list-view-container">
                                          </div>


                                     </div>
                                 </div>
                             </div>
                             <div class="modal-footer">
                                 <button type="button" class="btn btn-light border"
                                     data-bs-dismiss="modal">Close</button>
                                 <button type="submit" class="btn btn-primary">Save</button>
                             </div>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
     <div class="card-body">
         <div class="timeline-1">
             @forelse ($task->subTasks as $subTask)
                 <div class="timeline-data">
                     <div class="number-circle">{{ $loop->iteration }}</div>
                     <div class="card">
                         <div class="card-header">
                             <div class="d-flex align-items-center">
                                 <img src="{{ $subTask->user->avatar }}" alt="_"
                                     class="rounded-circle avatar avatar-sm me-1">
                                 <span class="d-flex w-100">
                                     <div>{{ $subTask->user->name() }}</div>
                                     @if ($subTask->created_at != $subTask->updated_at)
                                         <small class="ms-auto px-2">(Edited)</small>
                                     @endif
                                 </span>
                             </div>
                         </div>
                         <div class="card-body">
                             {!! Str::markdown($subTask->description ?? '') !!}

                             @php
                                 $images = $subTask->images();
                                 $otherFiles = $subTask->otherFiles();
                             @endphp

                             <div class="row">
                                 @if ($images && $images->isNotEmpty())
                                     <div class="col-12 col-sm-6 col-md-3">
                                         <div class="image-3-gallery" data-image-preview="true"
                                             data-image-downloadable="true">
                                             @foreach ($images as $file)
                                                 <div class="img-wrapper">
                                                     <img src="{{ $file->getFileUrl() }}" alt="">
                                                 </div>
                                             @endforeach
                                         </div>
                                     </div>
                                 @endif

                                 @if ($otherFiles && $otherFiles->isNotEmpty())
                                     <div class="col-12 col-sm-6 col-md 6">
                                         <div class="file-list row g-2">

                                             @foreach ($otherFiles as $file)
                                                 <div class="col-12 col-lg-6">
                                                     <div
                                                         class="file-item d-flex align-items-center gap-2 p-2 py-1 border rounded text-reset w-100">
                                                         <i class="{{ $file->extensionIcon() }} fs-3"></i>
                                                         <span
                                                             class="file-name flex-grow-1 truncate-2 fs-8">{{ $file->file_name }}</span>
                                                         <small
                                                             class="file-size text-muted ms-auto w-fit-content fs-8">({{ $file->size() }})</small>
                                                         <div class="file-actions">
                                                             <a href="{{ $file->getFileUrl() }}" target="_blank"><i
                                                                     class='bx bx-link-external'></i></a>
                                                             <a href="{{ $file->getFileUrl() }}" download><i
                                                                     class='bx bxs-download'></i></a>
                                                         </div>
                                                     </div>
                                                 </div>
                                             @endforeach

                                         </div>

                                     </div>
                                 @endif
                             </div>

                         </div>
                         <div class="card-footer">
                             <div class="d-flex align-items-center text-reset">
                                 <i class='bx bx-calendar-week fs-5 me-1'></i>
                                 <small>{{ $subTask->created_at->diffForHumans() }}</small>
                             </div>
                             <div class="action-container m-0 gap-1">

                                 <a href="javascript:void(0);"
                                        class="edit edit-sub-task-btn"
                                        data-submit-url="{{ authRoute('user.sub_task.update', [ 'subTask' => $subTask]) }}"
                                        data-subtask-description="{{ $subTask->description ?? "" }}"
                                        data-subtask-status="{{ $subTask->status ?? "" }}"
                                        data-subtask-files="{{ $subTask->files->select('formatted_file_size', 'extension', 'file_url', 'file_name', 'extension_icon', 'id') }}"
                                        >
                                     <i class='bx bx-edit'></i>
                                 </a>
                                 <form action="{{ authRoute('user.sub_task.delete', ['subTask' => $subTask]) }}" method="post">
                                     @method('DELETE')
                                     @csrf
                                     <button type="submit" class="delete btn-no-style">
                                         <i class='bx bx-trash'></i>
                                     </button>
                                 </form>

                             </div>
                         </div>
                     </div>
                 </div>
             @empty
                 <x-no-data />
             @endforelse
         </div>
     </div>
 </div>
