 <div class="card border mb-0">

     <div class="card-header">
         <div class="row align-items-center">
             <div class="col">
                 <h4 class="card-title mb-0">Personal Information</h4>
             </div>
         </div>
     </div>

     <div class="card-body">
         <form action="{{ authRoute('user.profile.personal_information.update') }}" method="post">
             @csrf
             @method('PUT')
             <div class="form-group mb-3 row">
                 <label class="form-label">First Name</label>
                 <div class="col-lg-12 col-xl-12">
                     <input class="form-control" type="text" name="first_name" value="{{ $personalInfo?->first_name ?? "" }}">
                 </div>
             </div>

             <div class="form-group mb-3 row">
                 <label class="form-label">Last Name</label>
                 <div class="col-lg-12 col-xl-12">
                     <input class="form-control" type="text" name="last_name" value="{{ $personalInfo?->last_name ?? "" }}">
                 </div>
             </div>
             <div class="form-group mb-3 row">
                 <label class="form-label">Gender</label>
                 <div class="col-lg-12 col-xl-12 ">
                     <div class="d-flex gap-3 flex-wrap">
                         <div class="form-check">
                             <input class="form-check-input" type="radio" name="gender" value="male"
                                 id="radio1" {{ (!empty($personalInfo) && $personalInfo->gender == 'male') ? 'checked' : '' }}>
                             <label class="form-check-label" for="radio1">
                                 Male
                             </label>
                         </div>
                         <div class="form-check">
                             <input class="form-check-input" type="radio" name="gender" value="female"
                                 id="radio2" {{ (!empty($personalInfo) && $personalInfo->gender == 'female') ? 'checked' : '' }}>
                             <label class="form-check-label" for="radio2">
                                 Female
                             </label>
                         </div>
                         <div class="form-check">
                             <input class="form-check-input" type="radio" name="gender" value="other"
                                 id="radio3" {{ (!empty($personalInfo) && $personalInfo->gender == 'other') ? 'checked' : '' }}>
                             <label class="form-check-label" for="radio3">
                                 Other
                             </label>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="form-group mb-3 row">
                 <label class="form-label">Contact Phone</label>
                 <div class="col-lg-12 col-xl-12">
                     <div class="input-group">
                         <span class="input-group-text"><i class="mdi mdi-phone-outline"></i></span>
                         <input class="form-control" type="text" placeholder="Phone" name="contact"
                             value="{{ $personalInfo?->contact ?? "" }}">
                     </div>
                 </div>
             </div>

             <div class="form-group mb-3 row">
                 <label class="form-label">Public Email Address</label>
                 <div class="col-lg-12 col-xl-12">
                     <div class="input-group">
                         <span class="input-group-text"><i class="mdi mdi-email"></i></span>
                         <input type="text" class="form-control" value="{{ $personalInfo?->public_email ?? "" }}" placeholder="Email"
                             name="public_email">
                     </div>
                 </div>
             </div>

             <div class="form-group mb-3 row">
                 <label class="form-label">About</label>
                 <div class="col-lg-12 col-xl-12">
                     <textarea name="bio" class="form-control" resizeable="true" row="3" cols="10">{{ $personalInfo?->bio ?? "" }}</textarea>
                 </div>
             </div>

             <button class="btn btn-primary mb-3" type="submit">Submit</button>
         </form>

     </div>
 </div>
