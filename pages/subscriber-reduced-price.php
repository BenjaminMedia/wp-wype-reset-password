<?php

use Bonnier\WP\WypeResetpassword\Plugin;
use Bonnier\WP\WypeResetpassword\Http\Routes\SubscriberReducedPricePage;

include __DIR__ . '/partials/head.php';

if(!Plugin::instance()->settings->get_setting_value('subscriber_valid_redirect_url_bt')
    || !Plugin::instance()->settings->get_setting_value('subscriber_valid_redirect_url_bp'))
{
    wp_redirect(home_url());
    ob_end_flush();
    exit;
}

if(!SubscriberReducedPricePage::isAuthenticated()) {
    include __DIR__ . '/partials/subscripber-reduced-price/authenticateSubscriber.php';
}