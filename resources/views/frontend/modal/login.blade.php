<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-login-modal border-0 shadow-lg">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h4 class="modal-title fw-bold text-dark" id="loginModalLabel"> Welcome Back!</h4>
                    <p class="text-muted small mb-0">Please enter your details to sign in.</p>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-4">
                <form id="ajaxLoginForm" method="POST" action="{{ route('model.login') }}">
                    @csrf
                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="login_email" class="form-label fw-semibold small text-secondary">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"> <i class="fa-light fa-envelope"></i></span>
                            <input type="text" class="form-control bg-light border-start-0 py-2 shadow-none" id="login_email" name="login" placeholder="name@example.com or username" required>
                        </div>
                        <div class="invalid-feedback" id="login_email_error"></div>
                    </div>
                    {{-- Password --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="login_password" class="form-label fw-semibold small text-secondary">Password</label>
                            <a href="javascript:void(0);" id="forgotPasswordLink" class="small text-decoration-none text-danger">Forgot Password?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-light fa-lock"></i></span>
                            <input type="password" class="form-control bg-light border-start-0 py-2 shadow-none" id="login_password" name="password" placeholder="••••••••" required>
                            <button type="button" class="btn bg-light border-start-0 shadow-none border-outline" id="toggleLoginPassword" tabindex="-1"><i class="fa-light fa-eye"></i></button>
                        </div>
                        <div class="invalid-feedback" id="login_password_error"></div>
                    </div>
                    {{-- Remember Me --}}
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input shadow-none" id="remember" name="remember" value="1">
                        <label class="form-check-label small text-muted" for="remember">Remember me</label>
                    </div>
                    {{-- Login Button --}}
                    <button type="submit" id="loginSubmitBtn" class="btn btn-danger w-100 py-2 fw-semibold shadow-sm">
                        <span class="login-btn-text">Sign In</span>
                        <span class="login-btn-loader d-none"><span class="spinner-border spinner-border-sm me-2"></span>Signing in...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
