<?php

namespace Bonnier\WP\WypeResetpassword\Services;

use Bonnier\WP\WypeResetpassword\Http\Client;

class BmdValidateLoginService extends Client
{
    const BMD_ENDPOINT = 'http://api2.bm-data.com/services/bm400/1.3/SubscriptionAPI.asmx/';
    const LOGIN_PATH = 'GetLoginResponse2';

    public function __construct()
    {
        parent::__construct(['base_uri' => self::BMD_ENDPOINT]);
    }

    public function validateSubscription($emailOrSubscriptionNumber, $postalCode) {
        return $this->get(self::LOGIN_PATH, [
            'body'=> [
                'db' => 'DK',
                'subscriptionNr' => $emailOrSubscriptionNumber,
                'postalNr' => $postalCode,
                'serviceID' => 'BP_ALL'
            ]
        ]);
    }
}