@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ $title ?? '' }}</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item">
                                <a href="{{ authRoute('user.documentation.index') }}">Documentations</a>
                            </li>
                                 <li class="breadcrumb-item">
                                <a
                                    href="{{ authRoute('user.documentation.document.pages.index', ['documentation' => $documentation]) }}">
                                    Page Editor
                                </a>
                            </li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row">
                    <div class="col-md-12">
                        <div class="card overflow-hidden">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-2 me-2 widget-icons-sections">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26"
                                            viewBox="0 0 26 26">
                                            <path fill="currentColor"
                                                d="M13 0c-1.7 0-3 1.3-3 3v6c0 1.7 1.3 3 3 3h6l4 4v-4c1.7 0 3-1.3 3-3V3c0-1.7-1.3-3-3-3zm4.188 3h1.718l1.688 6h-1.5l-.407-1.5h-1.5L16.813 9H15.5zM18 4c-.1.4-.212.888-.313 1.188l-.28 1.312h1.187l-.282-1.313C18.113 4.888 18 4.4 18 4M3 10c-1.7 0-3 1.3-3 3v6c0 1.7 1.3 3 3 3v4l4-4h6c1.7 0 3-1.3 3-3v-6h-3c-1.9 0-3.406-1.3-3.906-3zm4.594 2.906c1.7 0 2.5 1.4 2.5 3c0 1.4-.481 2.288-1.281 2.688c.4.2.874.306 1.374.406l-.374 1c-.7-.2-1.426-.512-2.126-.813c-.1-.1-.275-.093-.375-.093C6.112 18.994 5 18 5 16c0-1.7.994-3.094 2.594-3.094m0 1.094c-.8 0-1.188.9-1.188 2c0 1.2.388 2 1.188 2s1.218-.9 1.218-2s-.418-2-1.218-2" />
                                        </svg>
                                    </div>
                                    <h5 class="card-title mb-0"> Frequently Asked Questions </h5>

                                    <div class="ms-auto fw-semibold d-flex gap-1">

                                        <a href="javascript:void(0)" id="create-faq-button"
                                            class="btn btn-light btn-sm border center-content gap-1">
                                            <i class="bx bx-plus fs-5"></i>
                                            <div>New</div>
                                        </a>

                                        <div class="modal fade faq-creation" id="faq-creation-form-modal" tabindex="-1"
                                            aria-labelledby="faq-creation-form-title" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form id="faq-creation-form" data-submit-type="ajax"
                                                        action="{{ authRoute('user.documentation.faqs.save', ['document' => $document]) }}"
                                                        method="post">
                                                        @csrf

                                                        <input type="hidden" name="faq_id" id="faq-id">

                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="faq-creation-form-title">Create
                                                                FAQ</h1>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row g-3">
                                                                <!-- question -->
                                                                <div class="col-md-12">
                                                                    <label for="question-input"
                                                                        class="form-label">Question</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text"><i
                                                                                class='bx bx-question-mark fs-5'></i></span>
                                                                        <input type="text" class="form-control"
                                                                            id="question-input" name="question"
                                                                            placeholder="Question">
                                                                    </div>
                                                                </div>

                                                                <!-- Answer -->
                                                                <div class="col-12">
                                                                    <label for="answer-editor"
                                                                        class="form-label">Answer</label>
                                                                    <textarea class="form-control ckeditor ckeditor-minimal" id="answer-editor" name="answer" rows="2"
                                                                        placeholder="Add an answer..."></textarea>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary"
                                                                id="save-btn">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="card-body p-0">

                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">

                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Question</th>
                                                <th>Answer</th>
                                                <th width="140">Last Updated</th>
                                                <th width="120">Status</th>
                                                <th width="120">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse ($faqs as $faq)
                                                <tr>
                                                    <th> {{ $faqs->firstItem() + $loop->iteration - 1 }}</th>
                                                    <td style="max-width:400px;" class="text-truncate">
                                                        {{ $faq->question }}
                                                    </td>

                                                    <td style="max-width:400px;" class="text-truncate">
                                                        {!! $faq->answer !!}
                                                    </td>

                                                    <td>
                                                        <small class="text-muted">
                                                            {{ $faq->updated_at->diffForHumans() }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input faqStatusToggleSwitch"
                                                                type="checkbox" role="switch"
                                                                data-documentation-faq-id="{{ $faq->id }}"
                                                                {{ $faq->is_active ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="action-container m-0 gap-1">


                                                            <a href="javascript:void(0)" class="info ms-0 show-faq"
                                                                data-question="{{ $faq->question }}"
                                                                data-answer="{{ Str::markdown($faq->answer) }}">
                                                                <i class='bx bx-info-circle fs-5'></i>
                                                            </a>

                                                            <a href="javascript:void(0)" class="edit edit-faq"
                                                                data-id="{{ $faq->id }}"
                                                                data-question="{{ $faq->question }}"
                                                                data-answer="{{ $faq->answer }}">
                                                                <i class='bx bx-edit fs-5'></i>
                                                            </a>

                                                            <form
                                                                action="{{ authRoute('user.documentation.faqs.delete', ['faq' => $faq]) }}"
                                                                method="POST" data-confirm-delete>
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" disabled
                                                                    class="delete btn-no-style delete-password-locker-button">
                                                                    <i class='bx bx-trash fs-5'></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5">
                                                        <x-no-data />
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>

                                    </table>

                                    @if ($faqs->lastPage() > 1)
                                        <div class="m-3 mb-0">
                                            {{ $faqs->withQueryString()->links() }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!-- End content -->

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>

    <div class="modal fade" id="faq-view-modal" tabindex="-1" aria-labelledby="faq-view-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="faq-view-title">FAQ Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <h6 class="fw-bold mb-2">Question</h6>
                    <p id="faq-view-question"></p>

                    <h6 class="fw-bold mt-3 mb-2">Answer</h6>
                    <div id="faq-view-answer"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('js')
    @include('layout.extras.ckeditor5')
    <script src="{{ asset('assets/js/pages/documentation-faq-list.js') }}"></script>
@endsection
