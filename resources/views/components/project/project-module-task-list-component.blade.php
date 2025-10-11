 <div class="card overflow-hidden">
     <div class="card-header">
         <div class="d-flex align-items-center">
             <div class="rounded-2 me-2 widget-icons-sections">
                 <i data-feather="crosshair" class="widgets-icons"></i>
             </div>
             <h5 class="card-title mb-0">Recent Tasks</h5>

             <div class="ms-auto fw-semibold d-flex gap-1">
                 <button id="create-module-task" class="btn btn-light btn-sm border center-content gap-1">
                     <i class="bx bx-plus fs-5"></i>
                     <div>New</div>
                 </button>

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
                         <th>Number of Task</th>
                         <th>Deadline</th>
                         <th>Project</th>
                         <th>Assignee</th>
                     </tr>
                 </thead>
                 @forelse ($tasks as $task)
                     <tr>
                         <td>
                             <input type="checkbox" class="form-check-input m-0 align-middle" aria-label="Select task"
                                 checked readonly>
                         </td>
                         <td>
                             <a href="#" class="text-reset">{{ $task->title }} </a>
                         </td>
                         <td class="text-nowrap text-reset">
                             <i data-feather="calendar" style="height: 18px; width: 18px;" class="me-1"></i>
                             May 15, 2023
                         </td>
                         <td class="text-nowrap">
                             <a href="#" class="text-reset">
                                 <i data-feather="check" style="height: 18px; width: 18px;" class="me-1"></i>
                                 5/8
                             </a>
                         </td>
                         <td class="text-nowrap text-reset">
                             <i data-feather="calendar" style="height: 18px; width: 18px;" class="me-1"></i>
                             December 08, 2023
                         </td>
                         <td class="text-nowrap">
                             <a href="#" class="text-reset">
                                 <i data-feather="folder" style="height: 18px; width: 18px;" class="me-1"></i>
                                 6
                             </a>
                         </td>
                         <td>
                             <img src="/assets/images/users/user-10.jpg" class="avatar avatar-sm img-fluid rounded-2" />
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

 <div class="modal fade module-task" id="module-task-form-modal" tabindex="-1" aria-labelledby="module-task-form-title"
     aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-lg">
         <div class="modal-content">
             <form id="module-task-form" action="#" method="post">
                 @csrf
                 <div class="modal-header">
                     <h1 class="modal-title fs-5" id="module-task-form-title">Create Task</h1>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <div class="row">
                         <!-- Title -->
                         <div class="col-md-12 mb-3">
                             <label for="title-input" class="form-label">Task Title</label>
                             <div class="input-group">
                                 <span class="input-group-text"><i class="bi bi-folder-plus"></i></span>
                                 <input type="text" class="form-control" id="title-input" name="title"
                                     placeholder="Enter task title">
                             </div>
                         </div>

                         <!-- Description -->
                         <div class="col-md-12 mb-3">
                             <label for="description-input" class="form-label">Description</label>
                             <textarea class="form-control ckeditor" name="description"  id="editor" cols="30" rows="3"> </textarea>
                         </div>

                         <!-- Assign To -->
                         <div class="col-md-6 mb-3">
                             <label for="assign-to" class="form-label">Assign To</label>
                             <div class="input-group">
                                 <span class="input-group-text"><i class="bi bi-person"></i></span>
                                 <select class="form-select" id="assign-to" name="assign_to">
                                     <option value="">Select user</option>
                                     <option value="1">John Doe</option>
                                     <option value="2">Jane Smith</option>
                                     <option value="3">Alex Johnson</option>
                                 </select>
                             </div>
                         </div>

                         <!-- Priority -->
                         <div class="col-md-3 mb-3">
                             <label for="priority" class="form-label">Priority</label>
                             <div class="input-group">
                                 <span class="input-group-text"><i class="bi bi-flag"></i></span>
                                 <select class="form-select" id="priority" name="priority">
                                     <option value="">Select priority</option>
                                     <option value="low">Low</option>
                                     <option value="medium">Medium</option>
                                     <option value="high">High</option>
                                 </select>
                             </div>
                         </div>

                         <!-- Deadline -->
                         <div class="col-md-3 mb-3">
                             <label for="deadline" class="form-label">Deadline</label>
                             <div class="input-group">
                                 <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                 <input type="date" class="form-control" id="deadline" name="deadline">
                             </div>
                         </div>
                     </div>

                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                     <button type="submit" class="btn btn-primary" id="save-btn">Create</button>
                 </div>
             </form>
         </div>
     </div>
 </div>
