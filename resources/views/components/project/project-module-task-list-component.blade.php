 <div class="card overflow-hidden">
     <div class="card-header">
         <div class="d-flex align-items-center">
             <div class="rounded-2 me-2 widget-icons-sections">
                 <i data-feather="crosshair" class="widgets-icons"></i>
             </div>
             <h5 class="card-title mb-0">Recent Tasks</h5>

             <div class="ms-auto fw-semibold d-flex gap-1">
                 @if (!request()->attributes->get('is_collaboration'))
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
                 @endif

                 @if (!Route::is('user.tasks.index'))
                     <a href="{{  authRoute(request()->attributes->get('is_collaboration') ? 'user.collab.tasks.index':'user.tasks.index', ['project' => $projectBoard?->slug ?? '', 'module' => $projectModule?->slug ?? '']) }}"
                         class="btn btn-light btn-sm border center-content gap-1">
                         <i class='bx bx-list-ul fs-5'></i>
                         <div> Show All</div>
                     </a>
                 @endif
             </div>
         </div>
     </div>

     <div class="card-body p-0">
         <div class="d-flex gap-2 ps-2">
             @if (request()->filled('project'))
                 <div class="alert alert-secondary my-2 w-fit p-2">
                     <strong>Project : </strong>{{ request()->get('project') }}
                 </div>
             @endif
             @if (request()->filled('module'))
                 <div class="alert alert-success my-2 w-fit p-2">
                     <strong>Module : </strong>{{ request()->get('module') }}
                 </div>
             @endif
         </div>


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
                             {{-- <input type="checkbox" class="form-check-input m-0 align-middle" aria-label="Select task"
                                 checked readonly> --}}
                             {{ $tasks->firstItem() + $loop->iteration - 1 }}
                         </td>
                         <td>
                             <a href="{{ request()->attributes->get('is_collaboration') ? authRoute('user.collab.tasks.show', ['task' => $task]) : authRoute('user.tasks.show', ['task' => $task]) }}"
                                 class="text-dark truncate-2">{{ $task->title }} </a>
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
                             {{ ucwords(str_replace('_', ' ', $task->status ?? '')) }}
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
                                 <a href="{{ request()->attributes->get('is_collaboration') ? authRoute('user.collab.tasks.show', ['task' => $task]) : authRoute('user.tasks.show', ['task' => $task]) }}" class="info ms-0 ">
                                     <i class='bx bx-info-circle fs-4'></i>
                                 </a>
                                 @if (!request()->attributes->get('is_collaboration'))
                                     @php
                                         $editRoute =
                                             !empty($projectBoard) && $projectBoard->exists
                                                 ? authRoute('user.project-board.modules.tasks.editNested', [
                                                     'slug' => $projectBoard->slug,
                                                     'module' => $task->module->slug,
                                                     'task' => $task->uuid,
                                                 ])
                                                 : authRoute('user.tasks.edit', ['task' => $task]);
                                     @endphp
                                     <a href="{{ $editRoute }}" class="edit">
                                         <i class='bx bx-edit fs-4'></i>
                                     </a>

                                     <form action="{{ authRoute('user.tasks.delete', ['task' => $task]) }}"
                                         method="post">
                                         @method('DELETE')
                                         @csrf
                                         <button type="submit" class="delete btn-no-style">
                                             <i class='bx bx-trash fs-4'></i>
                                         </button>
                                     </form>
                                 @endif

                             </div>
                         </td>
                     </tr>

                 @empty
                     <tr>
                         <td colspan="8">
                             <x-no-data />
                         </td>
                     </tr>
                 @endforelse

             </table>

             <div class="m-3 mb-0">
                 {{ $tasks->withQueryString()->links() }}
             </div>
         </div>
     </div>
 </div>
