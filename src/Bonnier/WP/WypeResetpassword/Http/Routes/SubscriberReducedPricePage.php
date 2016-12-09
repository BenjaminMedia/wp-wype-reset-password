<?php

namespace Bonnier\WP\WypeResetpassword\Http\Routes;

use Bonnier\WP\WypeResetpassword\Http\Client;
use Bonnier\WP\WypeResetpassword\Plugin;
use Bonnier\WP\WypeResetpassword\Services\BmdEmailFromSubscriptionService;
use Bonnier\WP\WypeResetpassword\Services\BmdValidateLoginService;
use Bonnier\WP\WypeResetpassword\Settings\SettingsPage;

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
            $postal_code = $_POST['postal_code'];
            $emailOrSubNumber = $_POST['email_or_subscription_number'];
            $subscriptionNumber = $emailOrSubNumber;

            $validationService = new BmdValidateLoginService();
            $dataService = new BmdEmailFromSubscriptionService();

            // If it's a email, get the subscription number instead
            if(filter_var($emailOrSubNumber, FILTER_VALIDATE_EMAIL))
            {
                // Grap the response from the request and find the SubscriptionNr
                $subscriptionNumber = $dataService->subscriptionIdFromEmail($emailOrSubNumber);
            }

            // Let's validate!
            $isValid = $validationService->validateSubscription($subscriptionNumber);

            if(!$isValid)
            {
                return false;
            }


            // redirect
            //wp_redirect();

            die(var_dump(Plugin::instance()->settings->get_setting_value('subscriber_valid_redirect_url')));

        }

        return false;
    }

    /***
     * Gives you the current locale language
     *
     * @return string
     */
    private function getLocale()
    {
        return substr($_SERVER['HTTP_HOST'],strripos($_SERVER['HTTP_HOST'], '.') + 1);
    }
}