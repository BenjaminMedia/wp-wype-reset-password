<?php

use Bonnier\WP\WypeResetpassword\Http\Routes\ResetPasswordPage;

include __DIR__ . '/partials/head.php';

if(ResetPasswordPage::hasChangedPassword()) {
    include __DIR__ . '/partials/changedPasswordPartial.php';
} elseif (ResetPasswordPage::hasValidToken()) {
    include __DIR__ . '/partials/newPasswordPartial.php';
} elseif (ResetPasswordPage::hasRequestedReset()) {
    include __DIR__ . '/partials/emailSentPartial.php';
} else {
    include __DIR__ . '/partials/requestResetPartial.php';
}

