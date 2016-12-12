<?php

namespace Bonnier\WP\WypeResetpassword\Http\Routes;

use Bonnier\WP\WypeResetpassword\Plugin;
use Bonnier\WP\WypeResetpassword\Settings\SettingsPage;

class BasePageRoute
{

    const PAGE_URI = null;
    const PAGE_FILE = null;

    /**
     * @var Plugin Instance of settings.
     */
    protected $plugin;

    public function __construct(Plugin $plugin)
    {
        ob_start();
        $this->plugin = $plugin;
        add_action('init', [$this, 'register_page'] );
    }

    public function register_page() {

        add_action( 'template_redirect', function() {

            if ( strtok($_SERVER['REQUEST_URI'], '?') === static::PAGE_URI ) {

                // Prevent WordPress returning 404 when loading the page
                header("HTTP/1.1 200 OK");

                add_filter( 'template_include', function() {
                    return $this->plugin->page_dir . static::PAGE_FILE;
                });
            }

        });
    }

}