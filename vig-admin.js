(function($) {
    function initializeCaptcha() {
        // Attach the event listener
        $('#custom_captcha_response').on('input', function() {
            var captchaResponse = $('#custom_captcha_response').val();
            var captchaMessage = $('#captcha_verification_message');
            
            // Check if input is not empty before making the AJAX request
            if (captchaResponse !== '') {
                // Log the value being sent for verification
                console.log('Verifying CAPTCHA with value:', captchaResponse);

                // AJAX request to verify the CAPTCHA
                $.ajax({
                    url: wc_checkout_params.ajax_url, // WooCommerce AJAX URL
                    type: 'POST',
                    data: {
                        action: 'verify_custom_captcha',
                        captcha_response: captchaResponse
                    },
                    success: function(response) {
                        // Log the server response
                        console.log('Server response:', response);

                        if (response.success) {
                            captchaMessage.text('CAPTCHA verified successfully!').css('color', 'green');
                            $('#custom_captcha_response').prop('disabled', true);
                            $('#custom_captcha_verified').val('1'); // Set hidden field to indicate success
                        } else {
                            captchaMessage.text('Incorrect CAPTCHA, please try again.').css('color', 'red');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error:', error);
                        captchaMessage.text('An error occurred during verification. Please try again.').css('color', 'red');
                    }
                });
            } else {
                captchaMessage.text(''); // Clear the message if input is empty
            }
        });
    }

    $(document).ready(function() {
        initializeCaptcha();

        // Reinitialize on updated_checkout event in WooCommerce
        $(document.body).on('updated_checkout', function() {
            initializeCaptcha();
        });
    });
})(jQuery);
