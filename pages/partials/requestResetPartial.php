<?php

use Bonnier\WP\WypeResetpassword\Plugin;

echo "
    <body>
        <div class=\"jumbotron vertical-center\">
            <div class=\"container text-center\" style=\"max-width: 520px;\">
                <form class=\"form-signin\" action=\"\" method=\"post\">
                    <h2 class=\"form-signin-heading\">" . Plugin::instance()->settings->get_text_request_reset_link() . "</h2>
                    
                    <input type=\"email\" name=\"email\" id=\"email\" class=\"form-control\" placeholder=\"".Plugin::instance()->settings->get_text_email_address_placeholder()."\"> 
                    
                    </br>
                    
                    <label class=\"checkbox-inline\"><input name=\"provider\" type=\"radio\" value=\"plenti\"> Plenti</label>
                    <label class=\"checkbox-inline\"><input name=\"provider\" type=\"radio\" value=\"tre\"> Tre</label>
                    
                    </br></br>
                    
                    <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\" value=\"Submit\">" . Plugin::instance()->settings->get_text_request_reset_link_button() . "</button>
                </form>
            </div>
        </div>
    </body>
    ";