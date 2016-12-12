<?php

use Bonnier\WP\WypeResetpassword\Http\Routes\SubscriberReducedPricePage;

include __DIR__ . '/partials/head.php';

if(!SubscriberReducedPricePage::isAuthenticated()) {
    include __DIR__ . '/partials/subscripber-reduced-price/authenticateSubscriber.php';
}

