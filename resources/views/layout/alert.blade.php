@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show m-o"  role="alert">
        <strong>Success ! </strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif(session()->has('warning'))
    <div class="alert alert-warning alert-dismissible fade show m-0" role="alert">
        <strong>Warning ! </strong> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
        <strong>Error ! </strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
