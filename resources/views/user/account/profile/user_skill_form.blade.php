 <div class="card border mb-0">

     <div class="card-header">
         <div class="row align-items-center">
             <div class="col">
                 <h4 class="card-title mb-0">User Skills</h4>
             </div>
         </div>
     </div>

     <div class="card-body">
         <form action="" method="post" id="add_skill_form">
            @csrf
             <div class="form-group mb-3 row">
                 <label class="form-label">Skill Name</label>
                 <div class="col-lg-12 col-xl-12">
                     <div class="input-group">
                         <span class="input-group-text"><i class="bx bx-cube-alt fs-5"></i></span>

                         <input type="text" class="form-control" value="" name="skill"
                             placeholder="eg. Java, Python etc.">
                     </div>
                 </div>
             </div>
             <div class="form-group mb-3 row">
                 <label class="form-label">Skill Level</label>
                 <div class="col-lg-12 col-xl-12">
                     <input type="range" class="form-range"  min="0" max="100" value="50" name="level">
                 </div>
             </div>

             <button class="btn btn-primary mb-3" type="submit">Add</button>
         </form>

         <div class="mt-3 d-flex flex-wrap gap-2" id="user_skill_viewer" data-user-skills="{{ json_encode($user_skills ?? []) }}">

         </div>



     </div>
 </div>
