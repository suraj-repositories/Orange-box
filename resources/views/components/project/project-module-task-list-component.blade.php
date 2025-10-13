 <div class="card overflow-hidden">
     <div class="card-header">
         <div class="d-flex align-items-center">
             <div class="rounded-2 me-2 widget-icons-sections">
                 <i data-feather="crosshair" class="widgets-icons"></i>
             </div>
             <h5 class="card-title mb-0">Recent Tasks</h5>

             <div class="ms-auto fw-semibold d-flex gap-1">
                 <a href="{{ isset($projectBoard) && isset($projectModule) && $projectBoard->exists && $projectModule->exists
                     ? authRoute('user.project-board.modules.tasks.createNested', [
                         'slug' => $projectBoard->slug,
                         'module' => $projectModule->slug,
                     ])
                     : authRoute('user.tasks.create') }}"
                     class="btn btn-light btn-sm border center-content gap-1">
                     <i class="bx bx-plus fs-5"></i>
                     <div>New</div>
                 </a>


                 @if (!Route::is('user.project-board.modules.index'))
                     <a href="#" class="btn btn-light btn-sm border center-content gap-1">
                         <i class='bx bx-list-ul fs-5'></i>
                         <div> Show All</div>
                     </a>
                 @endif
             </div>
         </div>
     </div>

     <div class="card-body p-0">
         <div class="table-responsive">
             <table class="table table-traffic mb-0">

                 <thead>
                     <tr>
                         <th>No</th>
                         <th>Task Name</th>
                         <th>Created Date</th>
                         <th>Deadline</th>
                         <th>Priority</th>
                         <th>Status</th>
                         <th>Assignee</th>
                         <th>Action</th>
                     </tr>
                 </thead>
                 @forelse ($tasks as $task)
                     <tr>
                         <td>
                             <input type="checkbox" class="form-check-input m-0 align-middle" aria-label="Select task"
                                 checked readonly>
                         </td>
                         <td>
                             <a href="#" class="text-reset truncate-1">{{ $task->title }} </a>
                         </td>
                         <td class="text-nowrap text-reset">
                             <i data-feather="calendar" style="height: 18px; width: 18px;" class="me-1"></i>
                             {{ date('F d, Y', strtotime($task->created_at)) }}
                         </td>
                         <td class="text-nowrap text-reset">
                             <i data-feather="calendar" style="height: 18px; width: 18px;" class="me-1"></i>
                             {{ date('F d, Y', strtotime($task->due_date)) }}
                         </td>
                         <td class="text-nowrap">
                             <div class="d-flex align-items-center">
                                 <i class='bx bxs-circle me-1 fs-4 priority-{{ $task->priority }}'></i>
                                 {{ ucfirst($task->priority) }}
                             </div>
                         </td>
                         <td class="text-nowrap">
                             {{ ucfirst($task->status) }}
                         </td>
                         <td>
                             @php $assignee = $task->assignedUser; @endphp
                             @if (!empty($assignee))
                                 <img src="{{ $assignee->profilePicture() }}"
                                     class="avatar avatar-sm img-fluid rounded-2" title="{{ $assignee->username }}" />
                             @endif
                         </td>
                         <td>
                             <div class="action-container m-0 gap-1">
                                 <a href="#" class="info ms-0 ">
                                     <i class='bx bx-info-circle fs-4'></i>
                                 </a>

                                 <a href="{{ authRoute('user.project-board.modules.tasks.editNested', ['slug' => $projectBoard->slug, 'module' => $task->module->slug, 'task' => $task->uuid]) }}"
                                     class="edit">
                                     <i class='bx bx-edit fs-4'></i>
                                 </a>
                                 <form action="{{ authRoute('user.tasks.delete', ['task' => $task]) }}" method="post">
                                     @method('DELETE')
                                     @csrf
                                     <button type="submit" class="delete btn-no-style">
                                         <i class='bx bx-trash fs-4'></i>
                                     </button>
                                 </form>

                                 {{-- <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                                     data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Toggle right
                                     offcanvas</button> --}}

                                 <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                                     aria-labelledby="offcanvasRightLabel">
                                     <div class="offcanvas-header">
                                         <h5 class="offcanvas-title" id="offcanvasRightLabel">Offcanvas right</h5>
                                         <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                             aria-label="Close"></button>
                                     </div>
                                     <div class="offcanvas-body">
                                         ...
                                     </div>
                                 </div>
                             </div>
                         </td>
                     </tr>

                 @empty
                     <tr>
                         <td colspan="7">
                             <x-no-data />
                         </td>
                     </tr>
                 @endforelse

             </table>
         </div>
     </div>
 </div>
