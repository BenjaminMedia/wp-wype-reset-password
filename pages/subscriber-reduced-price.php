<?php

use Bonnier\WP\WypeResetpassword\Plugin;
use Bonnier\WP\WypeResetpassword\Http\Routes\SubscriberReducedPricePage;

include __DIR__ . '/partials/head.php';

if(!Plugin::instance()->settings->get_setting_value('subscriber_valid_redirect_url'))
{
    wp_redirect( home_url() );
    exit;
}

if(!SubscriberReducedPricePage::isAuthenticated()) {
    include __DIR__ . '/partials/subscripber-reduced-price/authenticateSubscriber.php';
}

