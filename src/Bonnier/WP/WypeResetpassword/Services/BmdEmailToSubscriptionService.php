<?php

namespace Bonnier\WP\WypeResetpassword\Services;

use Bonnier\WP\WypeResetpassword\Http\Client;

class BmdEmailToSubscriptionService extends Client
{
    const BMD_ENDPOINT = 'http://api2.bm-data.com';
    const SUB_LIST = '/services/bm400/1.3/ContactAPI.asmx/GetSubscriptionListFromEmail';

    public function __construct()
    {
        parent::__construct(['base_uri' => self::BMD_ENDPOINT]);
    }

    /**
     * Get subscription id from the email
     * @param $email
     * @return array
     */
    public function subscriptionIdFromEmail($email)
    {
        $response = $this->get(self::SUB_LIST, [
            'body'=> [
                'db' => 'x',
                'email' => $email,
                'serviceID' => 'BP_CCI',
            ],
        ]);

        $xml_response = simplexml_load_string($response->getBody());

        return (string)$xml_response->Subscription[0]->SubscriptionNr;
    }
}