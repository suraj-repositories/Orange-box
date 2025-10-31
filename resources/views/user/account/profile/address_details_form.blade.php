 <div class="card border mb-0">
     <div class="card-header">
         <div class="row align-items-center">
             <div class="col">
                 <h4 class="card-title mb-0">Address Details</h4>
             </div>
         </div>
     </div>

     <div class="card-body">

         <form action="{{ authRoute('user.profile.address.save') }}" method="post">
             @csrf
             <div class="form-group mb-3 row">
                 <label class="form-label">Line 1</label>
                 <div class="col-lg-12 col-xl-12">
                     <input class="form-control" type="text" name="line1" value="{{ $address?->line1 ?? '' }}">
                 </div>
             </div>

             <div class="form-group mb-3 row">
                 <label class="form-label">Line 2</label>
                 <div class="col-lg-12 col-xl-12">
                     <input class="form-control" type="text" name="line2" value="{{ $address?->line2 ?? '' }}">
                 </div>
             </div>

             <div class="row  mb-3">
                 <div class="col-md-6">
                     <div class="form-group  row">
                         <label class="form-label">City</label>
                         <div class="col-lg-12 col-xl-12">
                             <input class="form-control" type="text" name="city"
                                 value="{{ $address?->city ?? '' }}">
                         </div>
                     </div>
                 </div>
                 <div class="col-md-6">
                     <div class="form-group row">
                         <label class="form-label">Zip Code</label>
                         <div class="col-lg-12 col-xl-12">
                             <input class="form-control" type="number" name="postal_code"
                                 value="{{ $address?->postal_code ?? '' }}">
                         </div>
                     </div>
                 </div>
             </div>

             <div class="row mb-3">
                 <div class="col-md-6">
                     <div class="form-group row">
                         <label class="form-label">State</label>
                         <div class="col-lg-12 col-xl-12">
                             <input class="form-control" type="text" name="state"
                                 value="{{ $address?->state ?? '' }}">
                         </div>
                     </div>

                 </div>
                 <div class="col-md-6">
                     <div class="form-group row">
                         <label class="form-label">Country</label>
                         <div class="col-lg-12 col-xl-12">
                             <input class="form-control" type="text" name="country"
                                 value="{{ $address?->country ?? '' }}">
                         </div>
                     </div>
                 </div>
             </div>
             <button class="btn btn-primary mb-3" type="submit">Submit</button>
         </form>

     </div>
 </div>
