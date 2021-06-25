<?php

require_once 'vendor/autoload.php';

require_once 'init.php';

use Slim\Http\Request;
use Slim\Http\Response;



// LOGIN / LOGOUT USING FLASH MESSAGES TO CONFIRM THE ACTION

function setFlashMessage($message) {
    $_SESSION['flashMessage'] = $message;
}

// returns empty string if no message, otherwise returns string with message and clears is
function getAndClearFlashMessage() {
    if (isset($_SESSION['flashMessage'])) {
        $message = $_SESSION['flashMessage'];
        unset($_SESSION['flashMessage']);
        return $message;
    }
    return "";
}
