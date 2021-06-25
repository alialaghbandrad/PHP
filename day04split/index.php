<?php

// for development we want to see all the errors, some php.ini versions disable those (e.g. MAMP)
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once 'vendor/autoload.php';

require_once 'init.php'; // initializa of $app, $log, DB::

//

require_once 'utils.php'; // functions that may be used by other code

require_once 'hello.php';

require_once 'person.php';

// Run app
$app->run();
