@extends('user.layout.layout')

@section('title', 'Contact Us')

@section('content')
    <div class="content-page">
        <div class="content">

            <div class="container-xxl">

                <!-- Page Header -->
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Contact Us</h4>
                        <p class="text-muted mb-0">
                            Have a question, suggestion, or need help? We'd love to hear from you.
                        </p>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item">
                                <a href="{{ authRoute('user.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Contact Us</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row">

                    <!-- Contact Form -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-envelope-paper me-2"></i>
                                    Send us a Message
                                </h5>
                            </div>

                            <div class="card-body">

                                <form action="{{ authRoute('user.contact-us.store') }}" method="POST"
                                    enctype="multipart/form-data">

                                    @csrf

                                    <div class="row">

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Full Name</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-person"></i>
                                                </span>
                                                <input type="text" class="form-control" name="name"
                                                    placeholder="Enter your name" value="{{ old('name') }}">
                                            </div>

                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email Address</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-envelope"></i>
                                                </span>
                                                <input type="email" class="form-control" name="email"
                                                    placeholder="Enter your email" value="{{ old('email') }}">
                                            </div>

                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Subject</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-chat-left-text"></i>
                                                </span>
                                                <input type="text" class="form-control" name="subject"
                                                    placeholder="How can we help?" value="{{ old('subject') }}">
                                            </div>

                                            @error('subject')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Category</label>

                                            <select class="form-select" name="category">
                                                <option value="">Select Category</option>
                                                <option value="general">General Inquiry</option>
                                                <option value="template">Template Inquiry</option>
                                                <option value="support">Technical Support</option>
                                                <option value="billing">Billing</option>
                                                <option value="feedback">Feedback</option>
                                                <option value="bug">Bug Report</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Message</label>

                                            <textarea class="form-control" rows="6" name="message" placeholder="Describe your issue or inquiry...">{{ old('message') }}</textarea>

                                            @error('message')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Attachment (Optional)</label>

                                            <input type="file" class="form-control" name="attachment">
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-send me-1"></i>
                                                Send Message
                                            </button>
                                        </div>

                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="col-lg-4">

                        <div class="card">
                            <div class="card-body">

                                <div class="text-center mb-4">
                                    <div class="avatar-lg mx-auto mb-3">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-24">
                                            <i class="bi bi-headset"></i>
                                        </div>
                                    </div>

                                    <h5>We're Here to Help</h5>
                                    <p class="text-muted mb-0">
                                        Our team typically responds within 24 hours.
                                    </p>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <h6>
                                        <i class="bi bi-envelope me-2"></i>
                                        Email
                                    </h6>
                                    <p class="text-muted mb-0">
                                        support@example.com
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <h6>
                                        <i class="bi bi-clock me-2"></i>
                                        Support Hours
                                    </h6>
                                    <p class="text-muted mb-0">
                                        Monday - Friday<br>
                                        9:00 AM - 6:00 PM
                                    </p>
                                </div>

                                <div>
                                    <h6>
                                        <i class="bi bi-lightning-charge me-2"></i>
                                        Quick Help
                                    </h6>
                                    <p class="text-muted mb-0">
                                        For faster assistance, include screenshots and detailed information about your
                                        issue.
                                    </p>
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
