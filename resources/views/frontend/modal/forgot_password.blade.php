<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h4 class="modal-title fw-bold text-dark" id="forgotPasswordModalLabel">Forgot Password?</h4>
                    <p class="text-muted small mb-0">Enter your email address and we'll send you a password reset link.</p>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-4">
                <form id="forgotPasswordForm" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="forgot_username" class="form-label fw-semibold small text-secondary">Username or Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-light fa-envelope"></i></span>
                            <input type="text" class="form-control bg-light border-start-0 py-2 shadow-none" id="forgot_username" name="username" placeholder="Enter username or email" required>
                        </div>
                        <div class="invalid-feedback" id="forgot_username_error"></div>
                    </div>
                    <button type="submit" id="forgotPasswordSubmitBtn" class="btn btn-danger w-100 py-2 fw-semibold shadow-sm">
                        <span class="forgot-btn-text">Send Reset Link</span>
                        <span class="forgot-btn-loader d-none">
                            <span class="spinner-border spinner-border-sm me-2"></span>Sending...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>