@extends('backend.layout.login')
@section('content')

<div class="account-pages d-flex align-items-center min-vh-100 py-3">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden shadow-sm mb-0">
                    {{-- Header --}}
                    <div class="bg-primary-subtle">
                        <div class="row align-items-center">
                            <div class="col-7">
                                <div class="text-primary p-4">
                                    <h5 class="text-primary mb-2">Reset Password</h5>
                                    <p class="mb-0">Enter your email to receive a password reset link.</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="{{ asset('backend/assets/images/profile-img.png') }}" alt="Profile" class="img-fluid">
                            </div>  
                        </div>
                    </div>
                    {{-- Card Body --}}
                    <div class="card-body pt-0">
                        {{-- Logo --}}
                        <div class="auth-logo">
                            {{-- Light Logo --}}
                            <a href="{{ url('/') }}" class="auth-logo-light">
                                <div class="avatar-md profile-user-wid mb-3">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="{{ asset('backend/assets/images/logo-light.svg') }}" alt="Logo" class="rounded-circle" height="34">
                                    </span>
                                </div>
                            </a>
                            {{-- Dark Logo --}}
                            <a href="{{ url('/') }}" class="auth-logo-dark">
                                <div class="avatar-md profile-user-wid mb-3">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="{{ asset('backend/assets/images/logo.svg') }}" alt="Logo" class="rounded-circle" height="34">
                                    </span>
                                </div>
                            </a>
                        </div>
                        {{-- Forgot Password Form --}}
                        <div class="px-2 pb-2">
                            <form class="form-horizontal" method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm" novalidate>
                                @csrf
                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username / Email</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="Enter username or email" value="{{ old('username') }}" required>
                                   
                                </div>
                                {{-- Submit Button --}}
                                <div class="mt-3 d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit" id="submitButton">
                                        <span id="submitButtonText">Send Reset Link</span>
                                        <span id="submitButtonLoader" class="spinner-border spinner-border-sm d-none ms-1" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                                {{-- Back to Login --}}
                                <div class="mt-3 text-center">
                                    <a href="{{ route('login') }}" class="fw-medium text-primary"><i class="mdi mdi-arrow-left me-1"></i> Back to Log In</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Footer --}}
                <div class="mt-3 text-center">
                    <p class="text-muted mb-0">© {{ date('Y') }} School Management System.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        /*
        |--------------------------------------------------------------------------
        | ELEMENTS
        |--------------------------------------------------------------------------
        */
        const forgotForm = document.getElementById('forgotPasswordForm');
        const usernameInput = document.getElementById('username');
        const submitButton = document.getElementById('submitButton');
        const submitButtonText = document.getElementById('submitButtonText');
        const submitButtonLoader = document.getElementById('submitButtonLoader');

        if (!forgotForm) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | REMOVE ERROR WHEN USER STARTS TYPING
        |--------------------------------------------------------------------------
        */
        if (usernameInput) {
            usernameInput.addEventListener('input', function () {
                usernameInput.classList.remove('is-invalid');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FORM SUBMIT
        |--------------------------------------------------------------------------
        */
        forgotForm.addEventListener('submit', function (event) {
            event.preventDefault();
            usernameInput.classList.remove('is-invalid');

            /*
            |--------------------------------------------------------------------------
            | CLIENT-SIDE VALIDATION
            |--------------------------------------------------------------------------
            */
            const username = usernameInput ? usernameInput.value.trim() : '';

            if (!username) {
                usernameInput.classList.add('is-invalid');
                usernameInput.focus();
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | LOADING STATE
            |--------------------------------------------------------------------------
            */
            if (submitButton) {
                submitButton.disabled = true;
            }

            if (submitButtonText) {
                submitButtonText.textContent = 'Sending Link...';
            }

            if (submitButtonLoader) {
                submitButtonLoader.classList.remove('d-none');
            }

            /*
            |--------------------------------------------------------------------------
            | CSRF TOKEN
            |--------------------------------------------------------------------------
            */
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');

            if (!csrfMeta) {
                if (typeof showError === 'function') {
                    showError('Session expired. Please refresh the page.', 'Request Failed');
                } else {
                    usernameInput.classList.add('is-invalid');
                }
                resetButton();
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | AJAX REQUEST
            |--------------------------------------------------------------------------
            */
            fetch(forgotForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfMeta.getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(forgotForm)
            })
            .then(async function (response) {
                let data = {};

                try {
                    data = await response.json();
                } catch (error) {
                    throw new Error('Invalid server response.');
                }

                /*
                |--------------------------------------------------------------------------
                | SUCCESS (SweetAlert)
                |--------------------------------------------------------------------------
                */
                if (response.ok && (data.status || data.message)) {
                    if (typeof showSuccess === 'function') {
                        showSuccess(data.message || 'Password reset link sent successfully.', 'Message').then(function () {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            }
                        });
                    } else if (data.redirect) {
                        window.location.href = data.redirect;
                    }

                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | ERROR HANDLING (SweetAlert Priority)
                |--------------------------------------------------------------------------
                */
                let errorMessage = 'Unable to process request. Please try again.';

                if (data.message) {
                    errorMessage = data.message;
                } else if (data.errors) {
                    const firstField = Object.keys(data.errors)[0];
                    if (firstField && data.errors[firstField] && data.errors[firstField][0]) {
                        errorMessage = data.errors[firstField][0];
                    }
                }

                if (typeof showError === 'function') {
                    showError(errorMessage, 'Request Failed');
                } else {
                    usernameInput.classList.add('is-invalid');
                }
            })
            .catch(function (error) {
                console.error('Password Reset Error:', error);

                if (typeof showError === 'function') {
                    showError(error.message || 'Something went wrong. Please try again.', 'Request Failed');
                } else {
                    usernameInput.classList.add('is-invalid');
                }
            })
            .finally(function () {
                resetButton();
            });
        });

        /*
        |--------------------------------------------------------------------------
        | RESET BUTTON
        |--------------------------------------------------------------------------
        */
        function resetButton() {
            if (submitButton) {
                submitButton.disabled = false;
            }

            if (submitButtonText) {
                submitButtonText.textContent = 'Send Reset Link';
            }

            if (submitButtonLoader) {
                submitButtonLoader.classList.add('d-none');
            }
        }
    });
</script>
@endpush