@extends('user.layout.layout')

@section('title', Route::is('user.settings.index') ? 'Settings' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Settings</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Account</a></li>
                            <li class="breadcrumb-item active">Settings</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Settings</h5>
                            </div>

                            <div class="card-body p-0">

                                <div class="accordion accordion-flush plain-accordion"
                                    id="notificationsList">
                                    @forelse (["Notifcation", "Passwords", 'App Theme', 'Account Settings'] as $topic)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-{{ $loop->iteration }}">
                                                <div class="accordion-button fw-medium collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse-{{ $loop->iteration }}" aria-expanded="false"
                                                    aria-controls="collapse-{{ $loop->iteration }}">

                                                    <div
                                                        class="file-toggle d-flex align-items-center overflow-hidden w-sm-100 min-w-200  me-sm-4">
                                                        <div class="icon me-2 position-relative">

                                                            <img class="img-badge-40 rounded-circle"
                                                                src="https://placehold.co/400" alt="">


                                                        </div>
                                                        <div class="name me-2 w-100 ">
                                                            <div class="w-100 d-flex">
                                                               <div>
                                                                 <div class="text-truncate">
                                                                    Notfication
                                                                </div>
                                                               <small class="text-muted ms-1">
                                                                Simple <b class="text-dark">•</b> Normal <b class="text-dark">•</b> Impossible
                                                               </small>

                                                               </div>
                                                            </div>
                                                            <small class="sm-message truncate-1 text-muted">Lorem ipsum
                                                                dolor sit amet consectetur adipisicing elit. Neque,
                                                                minus.</small>
                                                        </div>
                                                    </div>


                                                    <small class="date me-2 ms-auto w-fit-content min-w-100 text-center">
                                                       view</small>
                                                </div>
                                            </h2>
                                            <div id="collapse-{{ $loop->iteration }}" class="accordion-collapse collapse"
                                                aria-labelledby="heading-{{ $loop->iteration }}"
                                                data-bs-parent="#notificationsList">
                                                <div class="accordion-body">

                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum similique
                                                    dicta temporibus ratione quibusdam, accusamus tempora ea omnis
                                                    voluptatum obcaecati adipisci soluta cumque cupiditate ipsum! Mollitia
                                                    accusamus inventore assumenda nam.
                                                </div>
                                            </div>

                                        </div>
                                    @empty
                                        <x-no-data />
                                    @endforelse
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        @include('layout.components.copyright')
    </div>


@endsection
