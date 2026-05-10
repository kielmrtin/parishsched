(function ($) {
    'use strict';

    $(document).ready(function () {
        const form = $('#contact-form');
        const success = $('#contact-success');
        const error = $('#contact-error');

        if (!form.length) return;

        const submitButton = form.find('button[type="submit"]');
        const originalButtonHtml = submitButton.html();

        form.on('submit', function (event) {
            event.preventDefault();

            const formElement = this;

            if (!formElement.checkValidity()) {
                formElement.reportValidity();
                return;
            }

            success.addClass('d-none');
            error.addClass('d-none');

            submitButton
                .prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .done(function (response) {
                if (response && response.success) {
                    formElement.reset();
                    success.text(response.message).removeClass('d-none').focus();
                } else {
                    error.text(response.message || 'An unexpected error occurred. Please try again later.')
                        .removeClass('d-none')
                        .focus();
                }
            })
            .fail(function (jqXHR) {
                let message = 'We could not send your message. Please try again later.';

                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    message = jqXHR.responseJSON.message;
                }

                error.text(message).removeClass('d-none').focus();
            })
            .always(function () {
                submitButton.prop('disabled', false).html(originalButtonHtml);
            });
        });
    });
})(jQuery);