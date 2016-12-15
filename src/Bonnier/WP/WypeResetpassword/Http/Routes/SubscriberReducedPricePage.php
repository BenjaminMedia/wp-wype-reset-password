<?php

namespace Bonnier\WP\WypeResetpassword\Http\Routes;

use Bonnier\WP\WypeResetpassword\Plugin;
use Bonnier\WP\WypeResetpassword\Services\BmdEmailToSubscriptionService;
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
            $postalCode = $_POST['postal_code'];
            $emailOrSubNumber = $_POST['email_or_subscription_number'];
            $subscriptionNumber = $emailOrSubNumber;

            $dataService = new BmdEmailToSubscriptionService();
            $validationService = new BmdValidateLoginService();

            // If it's a email, get the subscription number instead
            if(filter_var($emailOrSubNumber, FILTER_VALIDATE_EMAIL))
            {
                // Grap the response from the request and find the SubscriptionNr
                $subscriptionNumber = $dataService->subscriptionIdFromEmail($emailOrSubNumber);
            }

            // Let's validate!
            $returnData = $validationService->validateSubscription($subscriptionNumber, SubscriberReducedPricePage::getLocale());

            if(!$returnData['IsValid'])
            {
                return false;
            }

            // Checks if the URL is set from settings
            if(!Plugin::instance()->settings->get_setting_value('subscriber_valid_redirect_url_bt')
                && !Plugin::instance()->settings->get_setting_value('subscriber_valid_redirect_url_bp'))
            {
                return false;
            }

            switch ($returnData['Prefix'])
            {
                case 'BT':
                    wp_redirect(add_query_arg([
                        'subscription_number' => $subscriptionNumber,
                        'zipcode' => $postalCode,
                    ], Plugin::instance()->settings->get_setting_value('subscriber_valid_redirect_url_bt')));
                    ob_end_flush();
                    exit;
                case 'BP':
                    wp_redirect(add_query_arg([
                        'subscription_number' => $subscriptionNumber,
                        'zipcode' => $postalCode,
                    ], Plugin::instance()->settings->get_setting_value('subscriber_valid_redirect_url_bp')));
                    ob_end_flush();
                    exit;
            }
        }

        return false;
    }

    /***
     * Gives you the current locale domain
     *
     * @return string
     */
    private static function getLocale()
    {
        return substr($_SERVER['HTTP_HOST'],strripos($_SERVER['HTTP_HOST'], '.') + 1);
    }
}