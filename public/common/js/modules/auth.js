/*
|--------------------------------------------------------------------------
| Authentication AJAX
|--------------------------------------------------------------------------
*/

$(document).ready(function () {
    $('#ajaxLoginForm').on('submit', function (e) {
        e.preventDefault();
        const form = $(this);
        const button = $('#loginSubmitBtn');
        /*
        |--------------------------------------------------------------------------
        | Clear Previous Errors
        |--------------------------------------------------------------------------
        */

        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('').hide();
        /*
        |--------------------------------------------------------------------------
        | Disable Login Button
        |--------------------------------------------------------------------------
        */

        button.prop('disabled', true);
        $('.login-btn-text').addClass('d-none');
        $('.login-btn-loader').removeClass('d-none');
        /*
        |--------------------------------------------------------------------------
        | AJAX Request
        |--------------------------------------------------------------------------
        */
        ajaxRequest({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */
            success: function (response) {
                if (response.status) {
                    /*
                    |--------------------------------------------------------------
                    | Show Success Alert
                    |--------------------------------------------------------------
                    */
                    showSuccess(response.message || 'Login successful.', 'Login Successful').then(function () {
                        /*
                        |----------------------------------------------------------
                        | Close Login Modal
                        |----------------------------------------------------------
                        */
                        const modalElement = document.getElementById('loginModal');
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        }
                        /*
                        |----------------------------------------------------------
                        | Redirect
                        |----------------------------------------------------------
                        */
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    });
                } else {
                    showError(response.message || 'Login failed.', 'Login Failed');
                }
            },
            /*
            |--------------------------------------------------------------------------
            | Error
            |--------------------------------------------------------------------------
            */

            error: function (xhr) {
                const response = xhr.responseJSON || {};
                /*
                |--------------------------------------------------------------------------
                | Validation Error / Authentication Error
                |--------------------------------------------------------------------------
                */

                if (xhr.status === 422) {
                    const errors = response.errors || {};
                    /*
                    |--------------------------------------------------------------
                    | Field Validation Errors
                    |--------------------------------------------------------------
                    */

                    $.each(errors, function (field, messages) {
                        const input = $('[name="' + field + '"]');
                        input.addClass('is-invalid');
                        $('#' + field + '_error').text(messages[0]).show();
                    });
                    /*
                    |--------------------------------------------------------------
                    | Authentication Error
                    |
                    | Example:
                    | Invalid username/email or password.
                    |--------------------------------------------------------------
                    */
                    if (response.message) {
                        showError(response.message, 'Login Failed');
                    }
                    return;
                }
                /*
                |--------------------------------------------------------------------------
                | Unauthorized
                |--------------------------------------------------------------------------
                */
                if (xhr.status === 401) {
                    showError(response.message || 'Invalid username/email or password.', 'Login Failed');
                    return;
                }
                /*
                |--------------------------------------------------------------------------
                | Other Errors
                |--------------------------------------------------------------------------
                */
                showError(response.message || 'Something went wrong. Please try again.', 'Error');
            },
            /*
            |--------------------------------------------------------------------------
            | Complete
            |--------------------------------------------------------------------------
            */
            complete: function () {
                button.prop('disabled', false);
                $('.login-btn-text').removeClass('d-none');
                $('.login-btn-loader').addClass('d-none');
            }
        });

    });

    /*
    |--------------------------------------------------------------------------
    | Toggle Login Password
    |--------------------------------------------------------------------------
    */

    $('#toggleLoginPassword').on('click', function () {
        const passwordInput = $('#login_password');
        const icon = $(this).find('i');
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });


    /*
|--------------------------------------------------------------------------
| Forgot Password
|--------------------------------------------------------------------------
*/

    $('#forgotPasswordLink').on('click', function (e) {
        e.preventDefault();
        const loginModalElement = document.getElementById('loginModal');
        const forgotModalElement = document.getElementById('forgotPasswordModal');
        const forgotModal = new bootstrap.Modal(forgotModalElement);
        if (loginModalElement) {
            const loginModal = bootstrap.Modal.getInstance(loginModalElement) || new bootstrap.Modal(loginModalElement);
            loginModalElement.addEventListener('hidden.bs.modal', function () {
                forgotModal.show();
            }, { once: true });
            loginModal.hide();
        } else {
            forgotModal.show();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Forgot Password Form
    |--------------------------------------------------------------------------
    */

    $('#forgotPasswordForm').on('submit', function (e) {
        e.preventDefault();
        const form = $(this);
        const button = $('#forgotPasswordSubmitBtn');
        $('#forgot_email').removeClass('is-invalid');
        $('#forgot_email_error').text('');
        button.prop('disabled', true);
        $('.forgot-btn-text').addClass('d-none');
        $('.forgot-btn-loader').removeClass('d-none');
        ajaxRequest({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function (response) {
                if (response.status) {
                    const forgotModalElement = document.getElementById('forgotPasswordModal');
                    const forgotModal = bootstrap.Modal.getInstance(forgotModalElement);
                    if (forgotModal) {
                        forgotModal.hide();
                    }
                    showSuccess(response.message || 'Password reset link has been sent to your email.');
                    form[0].reset();
                } else {
                    showError(response.message || 'Unable to send password reset link.');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON?.errors || {};
                    if (errors.username) {
                        $('#forgot_username').addClass('is-invalid');
                        $('#forgot_username_error').text(errors.username[0]);
                    }
                    return;
                }
                showError(xhr.responseJSON?.message || 'Unable to send password reset link. Please try again.');
            },
            complete: function () {
                button.prop('disabled', false);
                $('.forgot-btn-text').removeClass('d-none');
                $('.forgot-btn-loader').addClass('d-none');
            }
        });
    });
});

