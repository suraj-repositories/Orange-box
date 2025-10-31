 <div class="card border mb-0">

     <div class="card-header">
         <div class="row align-items-center">
             <div class="col">
                 <h4 class="card-title mb-0">Social Media Links</h4>
             </div>
         </div>
     </div>

     <div class="card-body">
         <form action="{{ authRoute('user.profile.social_media_links.save') }}" method="post">
             @csrf
             @foreach ($socialMediaPlatforms as $platform)
                 <div class="form-group mb-3 row">
                     <label class="form-label">{{ $platform->name }}</label>
                     <div class="col-lg-12 col-xl-12">
                         <div class="input-group">
                             <span class="input-group-text"><i class="{{ $platform->icon }} fs-5"></i></span>
                             <input type="hidden" name="platform_id[]" value="{{ $platform->id }}">
                             <input type="text" class="form-control" value="{{ $platform->user_link ?? "" }}" name="social_media_link[]"
                                 placeholder="{{ $platform->url }}" >
                         </div>
                     </div>
                 </div>
             @endforeach


             <button class="btn btn-primary mb-3" type="submit">Submit</button>
         </form>

     </div>
 </div>
