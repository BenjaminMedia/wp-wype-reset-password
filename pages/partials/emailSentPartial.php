<?php

echo "
    <body>
        <div class=\"jumbotron vertical-center\">
            <div class=\"container text-center\" style=\"max-width: 520px;\">
                <div class=\"alert alert-info\" role=\"alert\">
                    " . Plugin::instance()->settings->get_text_if_in_system_mail_sent() . "
                </div>
            </div>
        </div>
    </body>
    ";