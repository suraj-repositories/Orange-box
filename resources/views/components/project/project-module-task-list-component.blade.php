 <div class="card overflow-hidden">
     <div class="card-header">
         <div class="d-flex align-items-center">
             <div class="rounded-2 me-2 widget-icons-sections">
                 <i data-feather="crosshair" class="widgets-icons"></i>
             </div>
             <h5 class="card-title mb-0">Resent Tasks</h5>
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
