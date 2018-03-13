<?php
/**
 * Plugin Name: Bonnier Wype Reset Password
 * Version: 1.1.10
 * Plugin URI: https://github.com/BenjaminMedia/wp-wype-reset-password
 * Description: This plugin allows users on to reset password on a Wype site.
 * Author: Bonnier - Nicklas Frank
 * License: GPL v3
 */

namespace Bonnier\WP\WypeResetpassword;

use Bonnier\WP\WypeResetpassword\Assets\Scripts;
use Bonnier\WP\WypeResetpassword\Db\DbSetup;
use Bonnier\WP\WypeResetpassword\Http\Routes\OauthLoginRoute;
use Bonnier\WP\WypeResetpassword\Http\Routes\ResetPasswordPage;
use Bonnier\WP\WypeResetpassword\Http\Routes\SubscriberReducedPricePage;
use Bonnier\WP\WypeResetpassword\Http\Routes\UserUpdateCallbackRoute;
use Bonnier\WP\WypeResetpassword\Settings\SettingsPage;

// Do not access this file directly
if (!defined('ABSPATH')) {
    exit;
}

// Handle autoload so we can use namespaces
spl_autoload_register(function ($className) {
    if (strpos($className, __NAMESPACE__) !== false) {
        $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
        require_once(__DIR__ . DIRECTORY_SEPARATOR . Plugin::CLASS_DIR . DIRECTORY_SEPARATOR . $className . '.php');
    }
});

class Plugin
{
    /**
     * Text domain for translators
     */
    const TEXT_DOMAIN = 'bp-wype-resetpassword';

    const CLASS_DIR = 'src';

    /**
     * @var object Instance of this class.
     */
    private static $instance;

    /**
     * @var SettingsPage Instance of settings.
     */
    public $settings;

    private $resetPasswordPage;
    private $subscriberReducedPicePage;

    /**
     * @var string Filename of this class.
     */
    public $file;

    /**
     * @var string Basename of this class.
     */
    public $basename;

    /**
     * @var string Plugins directory for this plugin.
     */
    public $plugin_dir;

    /**
     * @var string Plugins url for this plugin.
     */
    public $plugin_url;

    /**
     * @var string Page dir for this plugin.
     */
    public $page_dir;


    /**
     * Do not load this more than once.
     */
    private function __construct()
    {
        // Set plugin file variables
        $this->file = __FILE__;
        $this->basename = plugin_basename($this->file);
        $this->plugin_dir = plugin_dir_path($this->file);
        $this->plugin_url = plugin_dir_url($this->file);
        $this->page_dir = $this->plugin_dir . 'pages' . DIRECTORY_SEPARATOR;

        // Load textdomain
        load_plugin_textdomain(self::TEXT_DOMAIN, false, dirname($this->basename) . '/languages');

        $this->settings = new SettingsPage();
        $this->resetPasswordPage = new ResetPasswordPage($this);
        $this->resetPasswordPage = new SubscriberReducedPricePage($this);
    }

    private function boostrap() {
        Scripts::bootstrap();
    }

    /**
     * Returns the instance of this class.
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
            global $bp_wype_resetpassword;
            $bp_wype_resetpassword = self::$instance;
            self::$instance->boostrap();

            /**
             * Run after the plugin has been loaded.
             */
            do_action('bp_wype_resetpassword_loaded');
        }

        return self::$instance;
    }

}

/**
 * @return Plugin $instance returns an instance of the plugin
 */
function instance()
{
    return Plugin::instance();
}

add_action('plugins_loaded', __NAMESPACE__ . '\instance', 0);

register_activation_hook( __FILE__, function() {
    DbSetup::db_install();
});
