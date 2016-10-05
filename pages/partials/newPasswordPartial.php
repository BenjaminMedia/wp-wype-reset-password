<?php

use Bonnier\WP\WypeResetpassword\Plugin;

echo "
    <body>
        <div class=\"jumbotron vertical-center\">
            <div class=\"container text-center\" style=\"max-width: 520px;\">
                <form class=\"form-signin\" action=\"\" method=\"post\">
                    <h2 class=\"form-signin-heading\">" . Plugin::instance()->settings->get_text_new_password() . "</h2>
                    
                    <input type=\"password\" name=\"password\" id=\"password\" class=\"form-control\" placeholder=\"Password\">
                    
                    </br>
                    
                    <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\" value=\"Submit\">" . Plugin::instance()->settings->get_text_submit_new_password_button() . "</button>
                </form>
            </div>
        </div>
    </body>
    ";