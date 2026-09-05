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
                                    <h5 class="text-primary mb-2">Welcome Back!</h5>
                                    <p class="mb-0">Sign in to continue to the School Management System.</p>
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
                        {{-- Login Form --}}
                        <div class="px-2 pb-2">
                            <form class="form-horizontal" method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                                @csrf
                                {{-- Username --}}
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username / Email</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="Enter username or email" autocomplete="username" value="{{ old('username') }}">
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                {{-- Password --}}
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group auth-pass-inputgroup">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" aria-label="Password" required>
                                        <button class="btn btn-light" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                    </div>
                                </div>
                                {{-- Remember Me --}}
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-check" name="remember">
                                    <label class="form-check-label" for="remember-check">Remember me</label>
                                </div>
                                {{-- Login Button --}}
                                <div class="mt-3 d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit"  id="loginButton"> <span id="loginButtonText">Log In</span> <span id="loginButtonLoader" class="spinner-border spinner-border-sm d-none ms-1" role="status" aria-hidden="true"></span></button>
                                </div>
                                {{-- Forgot Password --}}
                                <div class="mt-3 text-center">
                                    <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Footer --}}
                <div class="mt-3 text-center">
                    <p class="mb-1">
                        Don't have an account? <a href="{{ route('register') }}" class="fw-medium text-primary">Signup now</a>
                    </p>
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
        const loginForm = document.getElementById('loginForm');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const passwordAddon = document.getElementById('password-addon');
        const usernameError = document.getElementById('usernameError');
        const passwordError = document.getElementById('passwordError');
        const loginButton = document.getElementById('loginButton');
        const loginButtonText = document.getElementById('loginButtonText');
        const loginButtonLoader = document.getElementById('loginButtonLoader');

        if (!loginForm) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | PASSWORD SHOW / HIDE
        |--------------------------------------------------------------------------
        */
        if (passwordInput && passwordAddon) {
            passwordAddon.addEventListener('click', function () {
                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';

                    if (icon) {
                        icon.classList.remove('mdi-eye-outline');
                        icon.classList.add('mdi-eye-off-outline');
                    }

                    this.setAttribute('aria-label', 'Hide password');
                } else {
                    passwordInput.type = 'password';

                    if (icon) {
                        icon.classList.remove('mdi-eye-off-outline');
                        icon.classList.add('mdi-eye-outline');
                    }

                    this.setAttribute('aria-label', 'Show password');
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | CLEAR FIELD ERROR
        |--------------------------------------------------------------------------
        */
        function clearFieldError(input, errorElement) {
            if (input) {
                input.classList.remove('is-invalid');
            }

            if (errorElement) {
                errorElement.textContent = '';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SHOW FIELD ERROR
        |--------------------------------------------------------------------------
        */
        function showFieldError(input, errorElement, message) {
            if (input) {
                input.classList.add('is-invalid');
            }

            if (errorElement) {
                errorElement.textContent = message;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CLEAR ALL ERRORS
        |--------------------------------------------------------------------------
        */
        function clearErrors() {
            clearFieldError(usernameInput, usernameError);
            clearFieldError(passwordInput, passwordError);
        }

        /*
        |--------------------------------------------------------------------------
        | REMOVE ERROR WHEN USER STARTS TYPING
        |--------------------------------------------------------------------------
        */
        if (usernameInput) {
            usernameInput.addEventListener('input', function () {
                clearFieldError(usernameInput, usernameError);
            });
        }

        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                clearFieldError(passwordInput, passwordError);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN SUBMIT
        |--------------------------------------------------------------------------
        */
        loginForm.addEventListener('submit', function (event) {
            event.preventDefault();
            clearErrors();

            /*
            |--------------------------------------------------------------------------
            | CLIENT-SIDE VALIDATION
            |--------------------------------------------------------------------------
            */
            let hasError = false;

            const username = usernameInput ? usernameInput.value.trim() : '';
            const password = passwordInput ? passwordInput.value : '';

            if (!username) {
                showFieldError(usernameInput, usernameError, 'Username or email is required.');
                hasError = true;
            }

            if (!password) {
                showFieldError(passwordInput, passwordError, 'Password is required.');
                hasError = true;
            }

            if (hasError) {
                if (!username) {
                    usernameInput.focus();
                } else {
                    passwordInput.focus();
                }

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | LOADING STATE
            |--------------------------------------------------------------------------
            */
            if (loginButton) {
                loginButton.disabled = true;
            }

            if (loginButtonText) {
                loginButtonText.textContent = 'Signing In...';
            }

            if (loginButtonLoader) {
                loginButtonLoader.classList.remove('d-none');
            }

            /*
            |--------------------------------------------------------------------------
            | CSRF TOKEN
            |--------------------------------------------------------------------------
            */
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');

            if (!csrfMeta) {
                if (typeof showError === 'function') {
                    showError('Session expired. Please refresh the page.', 'Login Failed');
                } else {
                    showFieldError(usernameInput, usernameError, 'Session expired. Please refresh the page.');
                }
                resetLoginButton();
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | AJAX LOGIN
            |--------------------------------------------------------------------------
            */
            fetch(loginForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfMeta.getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(loginForm)
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
                | LOGIN SUCCESS
                |--------------------------------------------------------------------------
                */
                if (response.ok && data.status === true) {
                    if (typeof showSuccess === 'function') {
                        showSuccess(data.message || 'Login successful.', 'Login Successful').then(function () {
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
                | EXTRACT ERROR MESSAGE (Handles both data.message and validation arrays)
                |--------------------------------------------------------------------------
                */
                let errorMessage = 'Username or password is incorrect.';

                if (data.message) {
                    errorMessage = data.message;
                } else if (data.errors) {
                    const firstField = Object.keys(data.errors)[0];
                    if (firstField && data.errors[firstField] && data.errors[firstField][0]) {
                        errorMessage = data.errors[firstField][0];
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | DISPLAY SWEATALERT ERROR
                |--------------------------------------------------------------------------
                */
                if (typeof showError === 'function') {
                    showError(errorMessage, 'Login Failed');
                } else {
                    showFieldError(usernameInput, usernameError, errorMessage);
                }
            })
            .catch(function (error) {
                console.error('Login Error:', error);

                if (typeof showError === 'function') {
                    showError(error.message || 'Something went wrong. Please try again.', 'Login Failed');
                } else {
                    showFieldError(usernameInput, usernameError, 'Something went wrong. Please try again.');
                }
            })
            .finally(function () {
                resetLoginButton();
            });
        });

        /*
        |--------------------------------------------------------------------------
        | RESET LOGIN BUTTON
        |--------------------------------------------------------------------------
        */
        function resetLoginButton() {
            if (loginButton) {
                loginButton.disabled = false;
            }

            if (loginButtonText) {
                loginButtonText.textContent = 'Log In';
            }

            if (loginButtonLoader) {
                loginButtonLoader.classList.add('d-none');
            }
        }

        if (!passwordInput || !passwordAddon) {
            return;
        }

        passwordAddon.addEventListener('click', function () {
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';

                if (icon) {
                    icon.classList.remove('mdi-eye-outline');
                    icon.classList.add('mdi-eye-off-outline');
                }

                this.setAttribute('aria-label', 'Hide password');
            } else {
                passwordInput.type = 'password';

                if (icon) {
                    icon.classList.remove('mdi-eye-off-outline');
                    icon.classList.add('mdi-eye-outline');
                }

                this.setAttribute('aria-label', 'Show password');
            }
        });
    });
</script>
@endpush