@extends('layout/auth_layout')
@section('title', Route::is('register') ? 'Sign Up' : '🟢🟢🟢')

@section('content')
    <div class="account-page">
        <div class="container-fluid p-0">
            <div class="row align-items-center g-0 bg-light">
                <div class="col-xl-5">
                    <div class="row">
                        <div class="col-md-7 mx-auto">
                            <div class="mb-0 border-0 p-md-5 p-lg-0 p-4">
                                <div class="mb-4 p-0 mt-3">
                                    <a class='auth-logo' href='/'>
                                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-dark"
                                            class="mx-auto" />
                                    </a>
                                </div>

                                <div class="pt-0">
                                    <form method="post" action="{{ route('register') }}" class="my-4">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input class="form-control" name="username" type="text" id="username"
                                                placeholder="Enter your Username">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="emailaddress" class="form-label">Email address</label>
                                            <input class="form-control" type="email" id="emailaddress" name="email"
                                                placeholder="Enter your email">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input class="form-control" type="password" name="password" id="password"
                                                placeholder="Enter your password">
                                        </div>

                                        <div class="form-group d-flex mb-3">
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                                    <label class="form-check-label" for="checkbox-signin">I agree to the <a
                                                            href="#" class="text-primary fw-medium"
                                                            data-bs-toggle="modal" data-bs-target="#termsModal">
                                                            Terms and Conditions
                                                        </a>
                                                </div>
                                            </div><!--end col-->
                                        </div>

                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button class="btn btn-primary" type="submit"> Register</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="saprator my-4"><span>or sign in with</span></div>

                                    <div class="text-center text-muted mb-4">
                                        <p class="mb-0">Already have an account ?<a class='text-primary ms-2 fw-medium'
                                                href='{{ route('login') }}'>Login here</a></p>
                                    </div>

                                    @include('auth.partials.social-media-login-buttons')

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7">
                    <div class="account-page-bg p-md-5 p-4">
                        <div class="text-center">
                            <h3 class="text-dark mb-3 pera-title">
                                Join us today and unlock all the features.
                            </h3>
                            <div class="auth-image">
                                <img src="assets/images/authentication.svg" class="mx-auto img-fluid" alt="images">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Terms & Conditions Modal -->
    <div class="modal" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">
                        <i class="bi bi-file-text me-2"></i>
                        Terms & Conditions
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">
                        Welcome to <strong>Orange Box</strong>. By creating an account or using our Content Management
                        System (CMS) and Document Management System (DMS), you agree to the following Terms & Conditions.
                    </p>

                    <h6>1. Acceptance of Terms</h6>
                    <p>
                        By registering for an Orange Box account, you acknowledge that you have read, understood, and agree
                        to be bound by these Terms & Conditions and any applicable policies published by Orange Box.
                    </p>

                    <h6>2. Account Responsibilities</h6>
                    <ul>
                        <li>Provide accurate, complete, and up-to-date registration information.</li>
                        <li>Maintain the confidentiality of your account credentials.</li>
                        <li>You are responsible for all activities performed under your account.</li>
                        <li>Notify us immediately if you suspect unauthorized access to your account.</li>
                    </ul>

                    <h6>3. Content & Document Management</h6>
                    <p>
                        Orange Box allows you to create, upload, organize, publish, and manage digital content and
                        documents. You retain ownership of your content, but you are solely responsible for ensuring that it
                        complies with applicable laws and does not infringe on the rights of others.
                    </p>

                    <h6>4. Acceptable Use</h6>
                    <ul>
                        <li>Do not upload malicious software, harmful code, or illegal content.</li>
                        <li>Do not attempt to gain unauthorized access to other users' data or system resources.</li>
                        <li>Do not use the platform to distribute spam, fraudulent content, or copyrighted material without
                            permission.</li>
                        <li>Use the platform in compliance with all applicable laws and regulations.</li>
                    </ul>

                    <h6>5. Data Security & Privacy</h6>
                    <p>
                        We implement reasonable security measures to protect your data. While we strive to maintain a secure
                        platform, no online service can guarantee absolute security. Please use strong passwords and protect
                        your account credentials.
                    </p>

                    <h6>6. Subscription & Payments</h6>
                    <p>
                        Some Orange Box features may require a paid subscription. Subscription fees, billing cycles,
                        upgrades, cancellations, and refunds are governed by our pricing and billing policies.
                    </p>

                    <h6>7. Intellectual Property</h6>
                    <p>
                        Orange Box, including its software, branding, design, and documentation, is protected by
                        intellectual property laws. You may not copy, modify, distribute, or reverse engineer any part of
                        the platform without prior written permission.
                    </p>

                    <h6>8. Service Availability</h6>
                    <p>
                        We continuously improve Orange Box and may perform maintenance, updates, or feature enhancements.
                        Temporary interruptions or downtime may occur during these activities.
                    </p>

                    <h6>9. Account Suspension & Termination</h6>
                    <p>
                        We reserve the right to suspend or terminate accounts that violate these Terms & Conditions, misuse
                        the platform, or engage in activities that compromise the security or integrity of Orange Box.
                    </p>

                    <h6>10. Limitation of Liability</h6>
                    <p>
                        To the fullest extent permitted by law, Orange Box shall not be liable for any indirect, incidental,
                        special, or consequential damages resulting from your use of the platform or inability to access the
                        service.
                    </p>

                    <h6>11. Changes to These Terms</h6>
                    <p>
                        We may update these Terms & Conditions from time to time. Continued use of Orange Box after changes
                        become effective constitutes your acceptance of the revised terms.
                    </p>

                    <h6>12. Contact Us</h6>
                    <p class="mb-0">
                        If you have any questions regarding these Terms & Conditions, please contact the Orange Box support
                        team. By clicking <strong>"Register"</strong>, you confirm that you have read and agree to these
                        Terms & Conditions.
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        I Understand
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
