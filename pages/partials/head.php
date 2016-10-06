<?php

use Bonnier\WP\WypeResetpassword\Plugin;

echo "<head>
        <link rel=\"stylesheet\" type=\"text/css\" href=\" " . get_template_directory_uri() . "/dist/styles/main.css \">
       <script>
           document.title ='". Plugin::instance()->settings->get_page_title() ."';
       </script>
    </head>";

