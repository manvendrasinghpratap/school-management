/*
|--------------------------------------------------------------------------
| Global AJAX Helper
|--------------------------------------------------------------------------
*/

window.ajaxRequest = function (options) {

    const defaults = {
        method: 'POST',
        url: '',
        data: {},
        success: function () { },
        error: function () { },
        complete: function () { }
    };

    const settings = {
        ...defaults,
        ...options
    };

    $.ajax({
        url: settings.url,
        type: settings.method,

        data: settings.data,

        headers: {
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content'),

            'X-Requested-With': 'XMLHttpRequest',

            'Accept': 'application/json'
        },

        beforeSend: function () {

            // Optional global loading
            $('button[type="submit"]').prop('disabled', true);

        },

        success: function (response) {

            settings.success(response);

        },

        error: function (xhr) {

            settings.error(xhr);

        },

        complete: function () {

            $('button[type="submit"]').prop('disabled', false);

            settings.complete();

        }
    });
};
