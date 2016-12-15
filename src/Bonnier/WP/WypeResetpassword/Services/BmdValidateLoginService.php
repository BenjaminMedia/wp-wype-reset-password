<?php

namespace Bonnier\WP\WypeResetpassword\Services;

use Bonnier\WP\WypeResetpassword\Http\Client;

class BmdValidateLoginService extends Client
{
    const BMD_ENDPOINT = 'https://order.bm-data.com';
    const LOGIN_PATH = '/';

    public function __construct()
    {
        parent::__construct(['base_uri' => self::BMD_ENDPOINT]);
    }

    public function validateSubscription($subscriptionNumber, $local)
    {
        $response = $this->get(self::LOGIN_PATH, [
            'body'=> [
                'type' => 'status',
                'format' => 'JSON',
                'cnr' => $subscriptionNumber, // subscription number
                'sid' => 'BP_CCI',
                'la' => $local,
            ],
        ]);

        return json_decode($response->getBody());
    }
}
