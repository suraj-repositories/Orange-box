@extends('user.layout.layout')


@section('title', 'Licence ')

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Licence</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ authRoute('user.documentation.index') }}">Template</a></li>

                            <li class="breadcrumb-item active">Licence</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header d-flex gap-3 flex-wrap justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Licence Agreement
                                </h5>

                                <button class="btn btn-no-style" data-printable="#printableZone">
                                    <i class='bx bx-printer fs-4' ></i>
                                </button>
                            </div>

                            <div class="card-body" id="printableZone">

                                <h3 class="mb-4">Documentation Template License Agreement</h3>

                                <p class="text-muted">
                                    Last Updated: {{ now()->format('F d, Y') }}
                                </p>

                                <hr>

                                <h5>1. Grant of License</h5>
                                <p>
                                    Upon purchase, you are granted a non-exclusive, worldwide, perpetual license to use the
                                    purchased documentation template for personal and commercial purposes, subject to the
                                    terms
                                    of this agreement.
                                </p>

                                <h5>2. Permitted Uses</h5>
                                <ul>
                                    <li>Create, edit, and customize documentation.</li>
                                    <li>Use templates for personal, business, and commercial projects.</li>
                                    <li>Publish documentation for websites, applications, products, and services.</li>
                                    <li>Distribute documentation created using the template to customers and users.</li>
                                    <li>Modify template content, layout, styling, and structure as needed.</li>
                                    <li>Use the template in unlimited projects owned by you or your organization.</li>
                                </ul>

                                <h5>3. Restrictions</h5>
                                <ul>
                                    <li>You may not resell the template as a standalone product.</li>
                                    <li>You may not redistribute, sublicense, or share template source files.</li>
                                    <li>You may not upload the template to marketplaces or template repositories.</li>
                                    <li>You may not claim ownership of the original template design.</li>
                                </ul>

                                <h5>4. Commercial Use</h5>
                                <p>
                                    Documentation created using this template may be sold, published, distributed,
                                    or otherwise made commercially available as part of your products, software,
                                    services, or business operations.
                                </p>

                                <h5>5. Intellectual Property</h5>
                                <p>
                                    Ownership of the original template remains with the template creator. This license
                                    grants usage rights only and does not transfer intellectual property ownership.
                                </p>

                                <h5>6. No Warranty</h5>
                                <p>
                                    The template is provided <strong>"AS IS"</strong> without warranties of any kind,
                                    express or implied, including merchantability, fitness for a particular purpose,
                                    or non-infringement.
                                </p>

                                <h5>7. Limitation of Liability</h5>
                                <p>
                                    The template creator shall not be liable for any direct, indirect, incidental,
                                    consequential, or special damages arising from the use of the template.
                                </p>

                                <h5>8. Updates</h5>
                                <p>
                                    Future template updates may be provided at the creator's discretion. Purchase of
                                    the template does not guarantee future updates unless explicitly stated.
                                </p>

                                <h5>9. Termination</h5>
                                <p>
                                    Violation of this license agreement may result in immediate termination of the
                                    granted license rights.
                                </p>

                                <h5>10. Acceptance</h5>
                                <p>
                                    By purchasing, downloading, or using this template, you acknowledge that you have
                                    read, understood, and agreed to be bound by this License Agreement.
                                </p>

                                <div class="alert alert-info mt-4">
                                    <strong>Summary:</strong> You may use this template to create and commercially
                                    distribute documentation, but you may not resell or redistribute the original
                                    template files.
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

@endsection
