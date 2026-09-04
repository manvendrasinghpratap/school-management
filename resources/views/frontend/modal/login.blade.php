<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-login-modal border-0 shadow-lg">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h4 class="modal-title fw-bold text-dark" id="loginModalLabel">Welcome Back!</h4>
                    <p class="text-muted small mb-0">Please enter your details to sign in.</p>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-4">
                <form method="POST" action="{{-- route('login') --}}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold small text-secondary">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-light fa-envelope"></i></span>
                            <input type="email" class="form-control bg-light border-start-0 py-2 shadow-none" id="email" name="email" placeholder="name@example.com" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password" class="form-label fw-semibold small text-secondary">Password</label>
                            <a href="#" class="small text-decoration-none text-danger">Forgot?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-light fa-lock"></i></span>
                            <input type="password" class="form-control bg-light border-start-0 py-2 shadow-none" id="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input shadow-none" id="remember">
                        <label class="form-check-label small text-muted" for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 py-2 fw-semibold shadow-sm">Sign In</button>
                </form>
            </div>
        </div>
    </div>
</div>