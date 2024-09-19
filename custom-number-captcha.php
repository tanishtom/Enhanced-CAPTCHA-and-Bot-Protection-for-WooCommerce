<?php
/*
Plugin Name: Enhanced CAPTCHA and Bot Protection for WooCommerce
Description: Adds a custom alphanumeric CAPTCHA with enhanced bot protection to the WooCommerce checkout page.
Version: 1.5
Author: Tanish Mukri
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue the custom JavaScript
add_action('wp_enqueue_scripts', 'enhanced_captcha_scripts');

function enhanced_captcha_scripts() {
    if (is_checkout()) {
        // Enqueue jQuery if not already loaded
        wp_enqueue_script('jquery');

        // Enqueue the custom JavaScript file
        wp_enqueue_script(
            'enhanced-captcha-js', // Handle
            plugins_url('vig-admin.js', __FILE__), // Path to the JS file
            array('jquery'), // Dependencies
            null, // Version
            true // Load in the footer
        );
    }
}

// Function to generate a random alphanumeric string
function generate_alphanumeric_string($length = 6) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Add the CAPTCHA and honeypot fields before the "Place Order" button
add_action('woocommerce_review_order_before_submit', 'enhanced_captcha_display');

function enhanced_captcha_display() {
    $captcha_string = generate_alphanumeric_string();
    
    // Store the CAPTCHA string in session for verification
    WC()->session->set('custom_captcha_string', $captcha_string);

    // Store the timestamp for time-based check
    WC()->session->set('captcha_start_time', time());

    echo '<div id="enhanced_captcha" style="border-top: 1px solid #ddd;">
            <label for="Captcha" class="" style="padding-top: 10px;">Captcha&nbsp;<abbr class="required" title="required">*</abbr></label>
            <p  class="captchabg">' . $captcha_string . '</p>
			<p class="lablecaptch" style="margin-bottom: 0px;">Enter Captcha</p>
		    <input type="text" name="custom_captcha_response" class="input-text" id="custom_captcha_response" style="font-family: sans-serif;" />
            <p id="captcha_verification_message"></p>
            <input type="hidden" name="custom_captcha_verified" id="custom_captcha_verified" value="0" />
            <!-- Honeypot field (hidden) -->
            <input type="text" name="honey_pot" id="honey_pot" style="display:none;" value="" />
        </div>';
}

// Validate CAPTCHA and detect bots on form submission
add_action('woocommerce_checkout_process', 'enhanced_captcha_validate');

function enhanced_captcha_validate() {
    $captcha_verified = isset($_POST['custom_captcha_verified']) ? intval($_POST['custom_captcha_verified']) : 0;

    // Honeypot check
    if (!empty($_POST['honey_pot'])) {
        wc_add_notice(__('Bot detected. Please try again.', 'woocommerce'), 'error');
        return;
    }

    // Time-based check (less than 2 seconds)
    $captcha_start_time = WC()->session->get('captcha_start_time');
    if (time() - $captcha_start_time < 2) {
        wc_add_notice(__('Form submitted too quickly. Please try again.', 'woocommerce'), 'error');
        return;
    }

    // CAPTCHA validation
    if ($captcha_verified !== 1) {
        $captcha_response = isset($_POST['custom_captcha_response']) ? sanitize_text_field($_POST['custom_captcha_response']) : '';
        $captcha_string = WC()->session->get('custom_captcha_string');

        if ($captcha_response !== $captcha_string) {
            wc_add_notice(__('Incorrect CAPTCHA response. Please try again.', 'woocommerce'), 'error');
        }
    }
}

// AJAX handler to verify the CAPTCHA
add_action('wp_ajax_verify_custom_captcha', 'verify_custom_alphanumeric_captcha');
add_action('wp_ajax_nopriv_verify_custom_captcha', 'verify_custom_alphanumeric_captcha');

function verify_custom_alphanumeric_captcha() {
    // Verify the CAPTCHA string
    $captcha_response = isset($_POST['captcha_response']) ? sanitize_text_field($_POST['captcha_response']) : '';
    $captcha_string = WC()->session->get('custom_captcha_string');

    if ($captcha_response === $captcha_string) {
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }

    wp_die();
}

// Clear CAPTCHA session data after order is processed
add_action('woocommerce_thankyou', 'enhanced_captcha_clear_session');

function enhanced_captcha_clear_session($order_id) {
    WC()->session->__unset('custom_captcha_string');
    WC()->session->__unset('captcha_start_time');
}
