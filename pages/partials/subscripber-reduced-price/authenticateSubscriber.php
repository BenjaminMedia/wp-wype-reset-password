<?php

use Bonnier\WP\WypeResetpassword\Plugin;
use Bonnier\WP\WypeResetpassword\Http\Routes\SubscriberReducedPricePage;

$errorText = SubscriberReducedPricePage::$inputError ? 
    Plugin::instance()->settings->get_setting_value('subscriber_text_invalid_form_input_error')
    : '' ;

echo '
<body>
    <div class="jumbotron vertical-center">
        <div class="container text-center" style="max-width: 520px;">
        <form class="form-signin" action="" method="post">
            <h2 class="form-signin-heading">' . Plugin::instance()->settings->get_setting_value('subscriber_text_form_title') . '</h2>
        
            <p class="bg-danger">'.$errorText.'</p>
        
            <input type="text" name="email_or_subscription_number"  class="form-control" placeholder="' . Plugin::instance()->settings->get_setting_value('subscriber_text_form_user_input_placeholder') . '">
        
            </br>
        
            <input type="text" name="postal_code" class="form-control" placeholder="' . Plugin::instance()->settings->get_setting_value('subscriber_text_form_postal_input_placeholder') . '">
        
            </br>
        
            <button class="btn btn-lg btn-primary btn-block" type="submit" value="Submit">' . Plugin::instance()->settings->get_setting_value('subscriber_text_form_submit_placeholder') . '</button>
        </form>
    </div>
</div>
</body>
';