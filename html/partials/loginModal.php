<!-- LOGIN MODAL -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="login-form" novalidate>
                <div class="mb-3">
                    <label for="login-email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="login-email" name="login-email" placeholder="juan@gmail.com" required>
                    <div class="invalid-feedback" id="login-email-feedback"></div>
                </div>
                <div class="mb-3 position-relative">
                    <label for="login-password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="login-password" name="login-password" placeholder="••••••••" required>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#login-password">
                            <i class="bi bi-eye"></i>
                        </button>
                        <div class="invalid-feedback" id="login-password-feedback"></div>
                    </div>
                </div>
                <div id="login-invalid-feedback" class="invalid-feedback" style="display: none;">
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <small>
                No account yet?
                <a href="#" id="openSignup" class="text-decoration-none text-primary" data-bs-toggle="modal" data-bs-target="#signupModal">Sign up here</a>
                </small>
            </div>
        </div>
    </div>
</div>