<?php

namespace Bonnier\WP\WypeResetpassword\Settings;

use PLL_Language;

class SettingsPage
{
    const SETTINGS_KEY = 'bp_wype_resetpassword_settings';
    const SETTINGS_GROUP = 'bp_wype_resetpassword_settings_group';
    const SETTINGS_SECTION = 'bp_wype_resetpassword_settings_section';
    const SUBSCRIBER_REDUCED_PRICE_SETTINGS_SECTION = 'bp_wype_subscriber_reduced_price_settings_section';
    const SETTINGS_PAGE = 'bp_wype_resetpassword_settings_page';
    const API_ENDPOINT_FALLBACK = '';
    const NOTICE_PREFIX = 'Bonnier Wype Resetpassword:';

    private $settingsFields = [
        'email_service_key' => [
            'type' => 'text',
            'name' => 'Api Key',
            'section' => self::SETTINGS_SECTION
        ],
        'bp_language' => [
            'type' => 'text',
            'name' => 'BP Language (la)',
            'section' => self::SETTINGS_SECTION
        ],
        'wype_reset_url' => [
            'type' => 'text',
            'name' => 'Full Wype Reset Url',
            'section' => self::SETTINGS_SECTION
        ],
        'wype_support_mail' => [
            'type' => 'text',
            'name' => 'Wype Mail',
            'section' => self::SETTINGS_SECTION
        ],
        'wype_reset_mail_subject' => [
            'type' => 'text',
            'name' => 'Mail Subject',
            'section' => self::SETTINGS_SECTION
        ],
        'wype_reset_mail_body' => [
            'type' => 'text',
            'name' => 'Mail Body',
            'section' => self::SETTINGS_SECTION
        ],
        'wype_text_request_reset_link' => [
            'type' => 'text',
            'name' => 'Request reset headline text',
            'section' => self::SETTINGS_SECTION
        ],
        'wype_text_request_reset_link_button' => [
            'type' => 'text',
            'name' => 'Request link button text',
            'section' => self::SETTINGS_SECTION
        ],
        'text_if_in_system_mail_sent' => [
            'type' => 'text',
            'name' => 'Mail sent if user in system text',
            'section' => self::SETTINGS_SECTION
        ],
        'text_new_password' => [
            'type' => 'text',
            'name' => 'New password page title',
            'section' => self::SETTINGS_SECTION
        ],
        'text_submit_new_password_button' => [
            'type' => 'text',
            'name' => 'New password page button text',
            'section' => self::SETTINGS_SECTION
        ],
        'text_password_resat' => [
            'type' => 'text',
            'name' => 'Password was resat text',
            'section' => self::SETTINGS_SECTION
        ],
        'text_email_address_placeholder' => [
            'type' => 'text',
            'name' => 'Email Address Input Placeholder',
            'section' => self::SETTINGS_SECTION
        ],
        'text_password_placeholder' => [
            'type' => 'text',
            'name' => 'Password Input Placeholder',
            'section' => self::SETTINGS_SECTION
        ],
        'wype_page_title' => [
            'type' => 'text',
            'name' => 'Page Title',
            'section' => self::SETTINGS_SECTION
        ],
        'wype_subscriber_reduced_price_page_title' => [
            'type' => 'text',
            'name' => 'Page Title',
            'section' => self::SUBSCRIBER_REDUCED_PRICE_SETTINGS_SECTION
        ],
        'subscriber_text_form_title' => [
            'type' => 'text',
            'name' => 'Form Title',
            'section' => self::SUBSCRIBER_REDUCED_PRICE_SETTINGS_SECTION
        ],
        'subscriber_text_form_user_input_placeholder' => [
            'type' => 'text',
            'name' => 'User input placeholder',
            'section' => self::SUBSCRIBER_REDUCED_PRICE_SETTINGS_SECTION
        ],
        'subscriber_text_form_postal_input_placeholder' => [
            'type' => 'text',
            'name' => 'Postal code input placeholder',
            'section' => self::SUBSCRIBER_REDUCED_PRICE_SETTINGS_SECTION
        ],
        'subscriber_text_form_submit_placeholder' => [
            'type' => 'text',
            'name' => 'Submit btn placeholder',
            'section' => self::SUBSCRIBER_REDUCED_PRICE_SETTINGS_SECTION
        ],
        'subscriber_text_invalid_form_input_error' => [
            'type' => 'text',
            'name' => 'Submit input error',
            'section' => self::SUBSCRIBER_REDUCED_PRICE_SETTINGS_SECTION
        ],
        'subscriber_valid_redirect_url' => [
            'type' => 'text',
            'name' => 'Redirect url for valid subscribers',
            'section' => self::SUBSCRIBER_REDUCED_PRICE_SETTINGS_SECTION
        ],
    ];

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $settingsValues;

    /**
     * Start up
     */
    public function __construct()
    {
        $this->settingsValues = get_option(self::SETTINGS_KEY);
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    function print_error($error)
    {
        $out = "<div class='error settings-error notice is-dismissible'>";
        $out .= "<strong>" . self::NOTICE_PREFIX . "</strong><p>$error</p>";
        $out .= "</div>";
        print $out;
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin',
            'Bonnier Wype Resetpassword',
            'manage_options',
            self::SETTINGS_PAGE,
            array($this, 'create_admin_page')
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property

        ?>
        <div class="wrap">
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields(self::SETTINGS_GROUP);
                do_settings_sections(self::SETTINGS_PAGE);
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function register_settings()
    {
        if ($this->languages_is_enabled()) {
            $this->enable_language_fields();
        }

        register_setting(
            self::SETTINGS_GROUP, // Option group
            self::SETTINGS_KEY, // Option name
            array($this, 'sanitize') // Sanitize
        );

        add_settings_section(
            self::SETTINGS_SECTION, // ID
            'Bonnier Wype Reset Password Settings', // Title
            array($this, 'print_section_info'), // Callback
            self::SETTINGS_PAGE // Page
        );

        add_settings_section(
            self::SUBSCRIBER_REDUCED_PRICE_SETTINGS_SECTION, // ID
            'Bonnier Wype Subscriber Reduced Price Settings', // Title
            array($this, 'print_section_info'), // Callback
            self::SETTINGS_PAGE // Page
        );

        foreach ($this->settingsFields as $settingsKey => $settingField) {
            add_settings_field(
                $settingsKey, // ID
                $settingField['name'], // Title
                array($this, $settingsKey), // Callback
                self::SETTINGS_PAGE, // Page
                $settingField['section'] // Section
            );
        }
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     * @return array
     */
    public function sanitize($input)
    {
        $sanitizedInput = [];

        foreach ($this->settingsFields as $fieldKey => $settingsField) {
            if (isset($input[$fieldKey])) {
                if ($settingsField['type'] === 'checkbox') {
                    $sanitizedInput[$fieldKey] = absint($input[$fieldKey]);
                }
                if ($settingsField['type'] === 'text' || $settingsField['type'] === 'select') {
                    $sanitizedInput[$fieldKey] = sanitize_text_field($input[$fieldKey]);
                }
            }
        }

        return $sanitizedInput;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /**
     * Catch callbacks for creating setting fields
     * @param string $function
     * @param array $arguments
     * @return bool
     */
    public function __call($function, $arguments)
    {
        if (!isset($this->settingsFields[$function])) {
            return false;
        }

        $field = $this->settingsFields[$function];
        $this->create_settings_field($field, $function);

    }

    public function get_setting_value($settingKey, $locale = null)
    {
        if(!$this->settingsValues) {
            $this->settingsValues = get_option(self::SETTINGS_KEY);
        }

        if ($locale) {
            $settingKey = $locale . '_' . $settingKey;
        }

        if (isset($this->settingsValues[$settingKey]) && !empty($this->settingsValues[$settingKey])) {
            return $this->settingsValues[$settingKey];
        }
        return false;
    }

    public function get_bp_language($locale = null)
    {
        return $this->get_setting_value('bp_language', $locale) ?: 'Something went wrong.';
    }

    public function get_email_service_key($locale = null)
    {
        return $this->get_setting_value('email_service_key', $locale) ?: '';
    }

    public function get_wype_support_mail($locale = null)
    {
        return $this->get_setting_value('wype_support_mail', $locale) ?: 'support@wype.dk';
    }

    public function get_wype_reset_mail_subject($locale = null)
    {
        return $this->get_setting_value('wype_reset_mail_subject', $locale) ?: 'Wype';
    }

    public function get_wype_reset_mail_body($locale = null)
    {
        return $this->get_setting_value('wype_reset_mail_body', $locale) ?: 'Something went wrong.';
    }

    public function get_wype_reset_url($locale = null)
    {
        return $this->get_setting_value('wype_reset_url', $locale) ?: 'support@wype.dk';
    }

    public function get_text_request_reset_link($locale = null)
    {
        return $this->get_setting_value('wype_text_request_reset_link', $locale) ?: 'Request reset link';
    }

    public function get_text_request_reset_link_button($locale = null)
    {
        return $this->get_setting_value('wype_text_request_reset_link_button', $locale) ?: 'Request link';
    }

    public function get_text_new_password($locale = null)
    {
        return $this->get_setting_value('new_text_new_password', $locale) ?: 'Reset Password';
    }

    public function get_text_submit_new_password_button($locale = null)
    {
        return $this->get_setting_value('submit_new_password_button', $locale) ?: 'Submit';
    }

    public function get_text_if_in_system_mail_sent($locale = null) {
        return $this->get_setting_value('text_if_in_system_mail_sent', $locale) ?: 'If the email is in our system it will shortly receive instructions by email';
    }

    public function get_text_password_resat($locale = null)
    {
        return $this->get_setting_value('text_password_resat', $locale) ?: 'Password was resat.';
    }

    public function get_text_email_address_placeholder($locale = null)
    {
        return $this->get_setting_value('text_email_address_placeholder', $locale) ?: 'Email Address';
    }

    public function get_text_password_placeholder($locale = null)
    {
        return $this->get_setting_value('text_password_placeholder', $locale) ?: 'Password';
    }

    public function get_page_title($locale = null)
    {
        return $this->get_setting_value('wype_page_title', $locale) ?: 'Password Reset';
    }


    private function enable_language_fields()
    {
        $languageEnabledFields = [];

        foreach ($this->get_languages() as $language) {
            foreach ($this->settingsFields as $fieldKey => $settingsField) {

                $localeFieldKey = $language->locale . '_' . $fieldKey;
                $languageEnabledFields[$localeFieldKey] = $settingsField;
                $languageEnabledFields[$localeFieldKey]['name'] .= ' ' . $language->locale;
                $languageEnabledFields[$localeFieldKey]['locale'] = $language->locale;

            }
        }

        $this->settingsFields = $languageEnabledFields;

    }

    public function languages_is_enabled()
    {
        return function_exists('Pll') && PLL()->model->get_languages_list();
    }

    public function get_languages()
    {
        if ($this->languages_is_enabled()) {
            return PLL()->model->get_languages_list();
        }
        return false;
    }

    /**
     * Get the current language by looking at the current HTTP_HOST
     *
     * @return null|PLL_Language
     */
    public function get_current_language()
    {
        if ($this->languages_is_enabled()) {
            return PLL()->model->get_language(pll_current_language());
        }
        return null;
    }

    public function get_current_locale() {
        $currentLang = $this->get_current_language();
        return $currentLang ? $currentLang->locale : null;
    }

    private function get_select_field_options($field)
    {
        if (isset($field['options_callback'])) {
            $options = $this->{$field['options_callback']}($field['locale']);
            if ($options) {
                return $options;
            }
        }

        return [];
    }

    private function create_settings_field($field, $fieldKey)
    {
        $fieldName = self::SETTINGS_KEY . "[$fieldKey]";
        $fieldOutput = false;

        if ($field['type'] === 'text') {
            $fieldValue = isset($this->settingsValues[$fieldKey]) ? esc_attr($this->settingsValues[$fieldKey]) : '';
            $fieldOutput = "<input type='text' name='$fieldName' value='$fieldValue' class='regular-text' />";
        }
        if ($field['type'] === 'checkbox') {
            $checked = isset($this->settingsValues[$fieldKey]) && $this->settingsValues[$fieldKey] ? 'checked' : '';
            $fieldOutput = "<input type='hidden' value='0' name='$fieldName'>";
            $fieldOutput .= "<input type='checkbox' value='1' name='$fieldName' $checked />";
        }
        if ($field['type'] === 'select') {
            $fieldValue = isset($this->settingsValues[$fieldKey]) ? $this->settingsValues[$fieldKey] : '';
            $fieldOutput = "<select name='$fieldName'>";
            $options = $this->get_select_field_options($field);
            foreach ($options as $option) {
                $selected = ($option['system_key'] === $fieldValue) ? 'selected' : '';
                $fieldOutput .= "<option value='" . $option['system_key'] . "' $selected >" . $option['system_key'] . "</option>";
            }
            $fieldOutput .= "</select>";
        }

        if ($fieldOutput) {
            print $fieldOutput;
        }
    }
}