document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Global SweetAlert Helpers
    |--------------------------------------------------------------------------
    */

    window.showSuccess = function (message, title = 'Success') {
        return Swal.fire({
            icon: 'success',
            title: title,
            text: message,
            confirmButtonText: 'OK'
        });
    };


    window.showError = function (message, title = 'Error') {
        return Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            confirmButtonText: 'OK'
        });
    };


    window.showWarning = function (message, title = 'Warning') {
        return Swal.fire({
            icon: 'warning',
            title: title,
            text: message,
            confirmButtonText: 'OK'
        });
    };


    window.showInfo = function (message, title = 'Information') {
        return Swal.fire({
            icon: 'info',
            title: title,
            text: message,
            confirmButtonText: 'OK'
        });
    };


    /*
    |--------------------------------------------------------------------------
    | Success Toast
    |--------------------------------------------------------------------------
    */

    window.successToast = function (message) {
        return Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
    };


    /*
    |--------------------------------------------------------------------------
    | Error Toast
    |--------------------------------------------------------------------------
    */

    window.errorToast = function (message) {
        return Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    };


    /*
    |--------------------------------------------------------------------------
    | Confirmation Dialog
    |--------------------------------------------------------------------------
    */

    window.confirmAction = function (
        message,
        title = 'Are you sure?',
        confirmText = 'Yes, continue'
    ) {
        return Swal.fire({
            icon: 'warning',
            title: title,
            text: message,
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: 'Cancel',
            reverseButtons: true
        });
    };

});