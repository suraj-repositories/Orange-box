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

                                <div class="accordion accordion-flush plain-accordion" id="notificationsList">
                                    @forelse ($appSettings as $appSetting)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-{{ $loop->iteration }}">
                                                <div class="accordion-button fw-medium collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse-{{ $loop->iteration }}" aria-expanded="false"
                                                    aria-controls="collapse-{{ $loop->iteration }}">

                                                    <div
                                                        class="file-toggle d-flex align-items-center overflow-hidden w-sm-100 min-w-200  me-sm-4">
                                                        <div class="icon me-2 position-relative">

                                                            <img class="img-badge-40 rounded-circle avatar p-1"
                                                                src="{{ asset($appSetting->icon_url) }}" alt="">

                                                        </div>
                                                        <div class="name me-2 w-100 ">
                                                            <div class="w-100 d-flex">
                                                                <div>
                                                                    <div class="text-truncate">
                                                                        {{ $appSetting->name ?? '#' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <small
                                                                class="sm-message d-inline truncate-1 ms-1  fs-8 text-muted">
                                                                {{ !empty($appSetting->description) ? $appSetting->description : $appSetting->settings->take(3)->pluck('title')->implode(' • ') }}
                                                            </small>
                                                        </div>
                                                    </div>

                                                </div>
                                            </h2>
                                            <div id="collapse-{{ $loop->iteration }}" class="accordion-collapse collapse"
                                                aria-labelledby="heading-{{ $loop->iteration }}">
                                                <div class="accordion-body">

                                                    @if (str_contains(strtolower($appSetting->name ?? ''), 'security'))
                                                        @include('user.account.settings.password_settings')
                                                    @elseif (str_contains(strtolower($appSetting->name ?? ''), 'theme'))
                                                        @include('user.account.settings.theme_settings')
                                                    @elseif (str_contains(strtolower($appSetting->name ?? ''), 'account'))
                                                        @include('user.account.settings.account_settings')
                                                    @else
                                                        @foreach ($appSetting->settings as $setting)
                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="d-flex align-items-center">
                                                                    <img class="circle-30 me-2"
                                                                        src="https://placehold.co/400" alt="alter">
                                                                    <div>
                                                                        <h2 class="fs-7 m-0">{{ $setting->title }} </h2>
                                                                        <p class="fs-8 m-0 text-muted">
                                                                            {{ $setting->description }}</p>
                                                                    </div>
                                                                </div>

                                                                <div class="buttonArea px-2 ps-3 border-start ms-auto">
                                                                    <label class="switch">
                                                                        <input type="checkbox" {{ ($userSettings[$setting->key] ?? '0') == '1' ? 'checked' : ''  }} id="setting_{{ $setting->key }}" data-setting-key="{{ $setting->key }}">
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
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

@section('js')
<script src="{{ asset('assets/js/pages/settings.js') }}"></script>
@endsection
