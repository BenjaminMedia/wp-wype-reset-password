<?php

namespace Bonnier\WP\WypeResetpassword\Http\Routes;

use Bonnier\WP\WypeResetpassword\Http\Client;
use Bonnier\WP\WypeResetpassword\Plugin;

class ResetPasswordPage extends BasePageRoute
{
    const PAGE_URI = '/wype-reset-password';
    const PAGE_FILE = 'resetpassword.php';

    protected static $providers = [
        'plenti' => 'BP_PLENTI',
        'tdc' => 'BP_TDC',
        'telmore' => 'BP_TELMOR',
    ];

    public static function hasChangedPassword() {
        if(isset($_POST['password']) && !empty($_POST['password'])) {
            if($entry = self::hasValidToken()) {
                return self::changePassword($entry, $_POST['password']);
            }
        }
    }

    public static function hasValidToken() {

        if(isset($_GET['token']) && !empty($_GET['token'])) {
            global $wpdb;

            if (preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $_GET['token'])) {
                $results = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "wype_password_reset_tokens WHERE token = '" . $_GET['token'] . "'", OBJECT );

                if(count($results) > 0) {
                    $now = new \DateTime('now');
                    $tokenBirth = new \DateTime($results[0]->time);
                    $minutes = $now->diff($tokenBirth)->i;

                    if($minutes < 60) {
                        return $results[0];
                    }
                }
            }
        }
        return false;
    }

    public static function hasRequestedReset() {

        self::cleanDatabase();

        if(
            isset($_POST['email']) && !empty($_POST['email'])
            && isset($_POST['provider']) && !empty($_POST['provider'])
            && array_key_exists($_POST['provider'], self::$providers)
        ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'wype_password_reset_tokens';

            $email = $_POST['email'];
            $provider = self::$providers[$_POST['provider']];
            $guid = self::GUID();

            if(self::isBMDUser($email, $provider)) {
                if(self::sendEmail($email, $guid)) {
                    $wpdb->insert(
                        $table_name,
                        array(
                            'time' => current_time('mysql'),
                            'token' => $guid,
                            'email' => $email,
                            'provider' => $provider,
                        )
                    );
                };
            }
            return true;
        }
        return false;
    }

    // Reset password with BP api
    private static function changePassword($entry, $password) {

        $client = new Client(['base_uri' => 'https://order.bm-data.com/']);

        $args = array(
            'body' => array(
                'type' => 'reset',
                'format' => 'JSON',
                'sid' => $entry->provider,
                'cnr' => $entry->email,
                'la'=> Plugin::instance()->settings->get_bp_language(),
                'pc' => $password,
            )
        );

        $response = $client->get('/', $args);

        return true;
    }

    // Send mail with mailgun API
    private static function sendEmail($email, $guid) {

        $client = new Client(['base_uri' => 'https://api.mailgun.net']);

        $args = array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode( 'api:' . Plugin::instance()->settings->get_email_service_key() )
            ),
            'body' => array('from' => 'Wype <' . Plugin::instance()->settings->get_wype_support_mail() . '>',
                'to' => "You <$email>",
                'subject' => Plugin::instance()->settings->get_wype_reset_mail_subject(),
                'text' => Plugin::instance()->settings->get_wype_reset_mail_body() . ' ' . Plugin::instance()->settings->get_wype_reset_url() . '?token=' . $guid
            )
        );

        $response = $client->post('v3/mg.wype.dk/messages', $args);

        return true;
    }

    private static function isBMDUser($email, $provider) {

        $client = new Client(['base_uri' => 'https://order.bm-data.com/']);

        $args = array(
            'body' => array(
                'type' => 'getusr',
                'format' => 'JSON',
                'sid' => $provider,
                'cnr' => $email,
                'la'=> Plugin::instance()->settings->get_bp_language(),
            )
        );

        $response = $client->get('/', $args);

        $parsedResponse = json_decode($response->getBody());
        if(isset($parsedResponse->IsValid) && $parsedResponse->IsValid) {
            return true;
        }

        return false;
    }

    private static function cleanDatabase() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wype_password_reset_tokens';

        $wpdb->query(
            "DELETE  FROM $table_name
               WHERE `time` < (NOW() - INTERVAL 60 MINUTE)"
        );

        return true;
    }

    static function GUID()
    {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

}