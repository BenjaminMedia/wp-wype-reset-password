<?php

namespace Bonnier\WP\WypeResetpassword\Http\Routes;

use Bonnier\WP\WypeResetpassword\Http\Client;
use Bonnier\WP\WypeResetpassword\Plugin;
use Bonnier\WP\WypeResetpassword\Services\BmdValidateLoginService;

class SubscriberReducedPricePage extends BasePageRoute
{
    const PAGE_URI = '/subscriber-reduced-price';
    const PAGE_FILE = 'subscriber-reduced-price.php';

    public static $inputError = false;

    public static function isAuthenticated() {
        if(isset($_POST['email_or_subscription_number'], $_POST['postal_code'])
            && !empty($_POST['email_or_subscription_number'])
            && !empty($_POST['postal_code']))
        {
            $validationService = new BmdValidateLoginService();
            
        }
        return false;
    }
}