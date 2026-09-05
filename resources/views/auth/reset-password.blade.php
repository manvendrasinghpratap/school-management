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
                                    <h5 class="text-primary mb-2">Create New Password</h5>
                                    <p class="mb-0">Enter your new password below.</p>
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
                        {{-- Reset Password Form --}}
                        <div class="px-2 pb-2">
                            <form class="form-horizontal" method="POST" action="{{ route('password.store') }}" id="resetPasswordForm" novalidate>
                                @csrf
                                {{-- Password Reset Token --}}
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username / Email</label>
                                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $request->username) }}" required autofocus autocomplete="username">
                                </div>

                                {{-- Password --}}
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <div class="input-group auth-pass-inputgroup">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required autocomplete="new-password">
                                        <button class="btn btn-light" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                    </div>
                                </div>

                                {{-- Confirm Password --}}
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <div class="input-group auth-pass-inputgroup">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" required autocomplete="new-password">
                                        <button class="btn btn-light" type="button" id="password-confirm-addon"><i class="mdi mdi-eye-outline"></i></button>
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <div class="mt-3 d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit" id="submitButton">
                                        <span id="submitButtonText">Reset Password</span>
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
        const resetForm = document.getElementById('resetPasswordForm');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const passwordAddon = document.getElementById('password-addon');
        const passwordConfirmAddon = document.getElementById('password-confirm-addon');
        const submitButton = document.getElementById('submitButton');
        const submitButtonText = document.getElementById('submitButtonText');
        const submitButtonLoader = document.getElementById('submitButtonLoader');

        if (!resetForm) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | PASSWORD SHOW / HIDE HELPER
        |--------------------------------------------------------------------------
        */
        function setupPasswordToggle(inputField, addonButton) {
            if (inputField && addonButton) {
                addonButton.addEventListener('click', function () {
                    const icon = this.querySelector('i');

                    if (inputField.type === 'password') {
                        inputField.type = 'text';

                        if (icon) {
                            icon.classList.remove('mdi-eye-outline');
                            icon.classList.add('mdi-eye-off-outline');
                        }

                        this.setAttribute('aria-label', 'Hide password');
                    } else {
                        inputField.type = 'password';

                        if (icon) {
                            icon.classList.remove('mdi-eye-off-outline');
                            icon.classList.add('mdi-eye-outline');
                        }

                        this.setAttribute('aria-label', 'Show password');
                    }
                });
            }
        }

        setupPasswordToggle(passwordInput, passwordAddon);
        setupPasswordToggle(passwordConfirmInput, passwordConfirmAddon);

        /*
        |--------------------------------------------------------------------------
        | CLEAR ERRORS ON TYPING
        |--------------------------------------------------------------------------
        */
        [usernameInput, passwordInput, passwordConfirmInput].forEach(function (input) {
            if (input) {
                input.addEventListener('input', function () {
                    this.classList.remove('is-invalid');
                });
            }
        });

        /*
        |--------------------------------------------------------------------------
        | FORM SUBMIT
        |--------------------------------------------------------------------------
        */
        resetForm.addEventListener('submit', function (event) {
            event.preventDefault();

            [usernameInput, passwordInput, passwordConfirmInput].forEach(function (input) {
                if (input) input.classList.remove('is-invalid');
            });

            /*
            |--------------------------------------------------------------------------
            | CLIENT-SIDE VALIDATION
            |--------------------------------------------------------------------------
            */
            const username = usernameInput ? usernameInput.value.trim() : '';
            const password = passwordInput ? passwordInput.value : '';
            const passwordConfirmation = passwordConfirmInput ? passwordConfirmInput.value : '';

            let hasError = false;

            if (!username) {
                if (usernameInput) usernameInput.classList.add('is-invalid');
                hasError = true;
            }

            if (!password) {
                if (passwordInput) passwordInput.classList.add('is-invalid');
                hasError = true;
            }

            if (!passwordConfirmation) {
                if (passwordConfirmInput) passwordConfirmInput.classList.add('is-invalid');
                hasError = true;
            }

            if (password && passwordConfirmation && password !== passwordConfirmation) {
                if (passwordConfirmInput) passwordConfirmInput.classList.add('is-invalid');
                if (typeof showError === 'function') {
                    showError('Passwords do not match.', 'Validation Error');
                } else {
                    alert('Passwords do not match.');
                }
                hasError = true;
            }

            if (hasError) {
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
                submitButtonText.textContent = 'Resetting...';
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
                }
                resetButton();
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | AJAX REQUEST
            |--------------------------------------------------------------------------
            */
            fetch(resetForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfMeta.getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(resetForm)
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
                        showSuccess(data.message || 'Password has been reset successfully.', 'Success').then(function () {
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
                let errorMessage = 'Unable to reset password. Please try again.';

                if (data.message) {
                    errorMessage = data.message;
                } else if (data.errors) {
                    const firstField = Object.keys(data.errors)[0];
                    if (firstField && data.errors[firstField] && data.errors[firstField][0]) {
                        errorMessage = data.errors[firstField][0];
                    }
                }

                if (typeof showError === 'function') {
                    showError(errorMessage, 'Reset Failed');
                } else {
                    alert(errorMessage);
                }
            })
            .catch(function (error) {
                console.error('Password Reset Error:', error);

                if (typeof showError === 'function') {
                    showError(error.message || 'Something went wrong. Please try again.', 'Request Failed');
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
                submitButtonText.textContent = 'Reset Password';
            }

            if (submitButtonLoader) {
                submitButtonLoader.classList.add('d-none');
            }
        }
    });
    
</script>
@endpush