# Enhanced CAPTCHA and Bot Protection for WooCommerce

A lightweight WordPress plugin that adds an alphanumeric CAPTCHA to the WooCommerce checkout page to prevent spam and bot submissions. This plugin also includes additional bot protection measures such as a honeypot field and a time-based check, ensuring that only human users can complete the checkout process.

## Features

- **Alphanumeric CAPTCHA**: Adds a randomized alphanumeric CAPTCHA to the WooCommerce checkout process.
- **Honeypot Field**: Includes an invisible honeypot field to detect and block bots that attempt to auto-fill all form fields.
- **Time-Based Check**: Prevents rapid form submissions typically associated with bots.
- **AJAX Verification**: Uses AJAX to verify the CAPTCHA input, providing instant feedback to users without reloading the page.
  
## Installation

1. Download the plugin ZIP file or clone the repository.
2. Upload the plugin files to the `/wp-content/plugins/enhanced-captcha-bot-protection` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress.
4. The CAPTCHA will automatically appear on the WooCommerce checkout page.

## Usage

Once the plugin is activated, it will automatically add an alphanumeric CAPTCHA to the WooCommerce checkout page. The CAPTCHA is validated via AJAX as the user enters their response, providing immediate feedback. The plugin also incorporates additional bot protection techniques like honeypot fields and time-based checks.

## How it Works

1. **CAPTCHA Generation**: The plugin generates a random alphanumeric string that the user must enter correctly to proceed with the checkout.
2. **Honeypot Field**: An invisible field is added to the form. If this field is filled, the plugin detects a bot submission and blocks it.
3. **Time-Based Check**: The plugin checks the time between the CAPTCHA being shown and the form submission. If the form is submitted too quickly, it is flagged as a potential bot.
4. **AJAX Verification**: The CAPTCHA response is verified using AJAX, providing instant feedback to the user on whether they entered the correct value.

## Contributing

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes.
4. Commit your changes (`git commit -am 'Add new feature'`).
5. Push to the branch (`git push origin feature-branch`).
6. Open a pull request.

## Changelog

### 1.5
- Added honeypot field for bot detection.
- Implemented time-based check for rapid form submissions.
- Improved CAPTCHA validation logic.

### 1.4
- Introduced alphanumeric CAPTCHA.

### 1.3
- Initial release with basic number CAPTCHA.

## License

This plugin is licensed under the [MIT License](LICENSE).
