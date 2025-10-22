 <div class="card overflow-hidden">
     <div class="card-header">
         <div class="d-flex align-items-center">
             <div class="rounded-2 me-2 widget-icons-sections">
                 <i data-feather="crosshair" class="widgets-icons"></i>
             </div>
             <h5 class="card-title mb-0">Project Modules</h5>

             <div class="ms-auto fw-semibold d-flex gap-1">

                 @if (!request()->attributes->get('is_collaboration'))
                     <a href="{{ authRoute($projectBoard?->slug ? 'user.project-board.modules.create' : 'user.modules.create', [
                         'slug' => $projectBoard?->slug,
                     ]) }}"
                         class="btn btn-light btn-sm border center-content gap-1">
                         <i class="bx bx-plus fs-5"></i>
                         <div> New</div>
                     </a>
                 @endif

                 @if (!Route::is('user.project-board.modules.index') && !Route::is('user.modules.index') && !empty($projectBoard->slug))
                     <a href="{{ request()->attributes->get('is_collaboration') ?
                     authRoute('user.collab.modules.index', ['owner' => $projectBoard->user->username, 'project' => $projectBoard->id]) :
                     authRoute('user.project-board.modules.index', ['slug' => $projectBoard->slug]) }}"
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

             @if (request()->filled('project') && !empty($filter['project']))
                 <div class="alert alert-secondary my-2 w-fit p-2">
                     <strong>Project : </strong>{{ $filter['project'] }}
                 </div>
             @endif
         </div>
         <div class="table-responsive">
             <table class="table table-traffic mb-0">

                 <thead>
                     <tr>
                         <th>No</th>
                         <th>Module Name</th>
                         @if (Route::is('user.modules.index') || Route::is('user.collab.modules.index'))
                             <th>Project Name</th>
                         @endif
                         <th>Created Date</th>
                         <th>Number of Task</th>
                         <th>Deadline</th>

                         <th>Assignee</th>
                     </tr>
                 </thead>

                 @forelse($modules as $module)
                     <tr>
                         <td>
                             {{-- <input type="checkbox" class="form-check-input m-0 align-middle" aria-label="Select task"
                                 checked> --}}
                             {{ $modules->firstItem() + $loop->iteration - 1 }}
                         </td>
                         <td>
                             <a href="{{ request()->attributes->get('is_collaboration') ? authRoute('user.collab.modules.show', ['owner' => $module->user->username, 'slug' => $module->projectBoard->slug, 'module' => $module->slug]) : authRoute('user.project-board.modules.show', ['slug' => $module->projectBoard->slug, 'module' => $module->slug]) }}"
                                 class="text-reset"> {{ $module->name }} </a>
                         </td>
                         @if (Route::is('user.modules.index') || Route::is('user.collab.modules.index'))
                             <td>
                                 <a class="text-reset"
                                     href="{{ request()->attributes->get('is_collaboration') ? authRoute('user.collab.project-board.show', ['owner' => $module->user->username, 'slug' => $module->projectBoard->slug]) : authRoute('user.project-board.show', ['slug' => $module->projectBoard->slug]) }}">{{ $module->projectBoard->title }}</a>
                             </td>
                         @endif
                         <td class="text-nowrap text-reset">
                             <i data-feather="calendar" style="height: 18px; width: 18px;" class="me-1"></i>
                             {{ date('F d, Y', strtotime($module->created_at)) }}
                         </td>
                         <td>
                             <a href="#" class="text-reset">
                                 <i data-feather="check" style="height: 18px; width: 18px;" class="me-1"></i>
                                 {{ $module->completed_task_count }}/{{ $module->task_count }}
                             </a>
                         </td>

                         <td class="text-nowrap text-reset">
                             @if (!empty($module->end_date))
                                 <i data-feather="calendar" style="height: 18px; width: 18px;" class="me-1"></i>
                                 <small> {{ date('F d, Y', strtotime($module->end_date)) }}</small>
                             @else
                                 <p class="mx-auto">-</p>
                             @endif
                         </td>

                         <td>
                             <div class="avatar-list-stacked">
                                 @php
                                     $projectModuleUsers = $module->limitedUsers;
                                 @endphp

                                 @foreach ($projectModuleUsers as $projectModuleUser)
                                     <span class="avatar avatar-rounded">
                                         <img src="{{ $projectModuleUser->user->profilePicture() }}" alt="img"
                                             title="{{ $projectModuleUser->user->username }}">
                                     </span>
                                 @endforeach

                                 @if ($module->project_module_users_count > 3)
                                     <a class="avatar bg-dark avatar-rounded text-white" href="javascript:void(0);">
                                         +{{ $module->project_module_users_count - 3 }}
                                     </a>
                                 @endif
                             </div>

                         </td>
                     </tr>
                 @empty
                     <tr>
                         <td
                             colspan="{{ Route::is('user.modules.index') || Route::is('user.collab.modules.index') ? '8' : '7' }}">
                             <x-no-data />
                         </td>
                     </tr>
                 @endforelse

             </table>
         </div>
         <div class="mx-3 my-3">{{ $modules->withQueryString()->links() }}</div>
     </div>
 </div>
