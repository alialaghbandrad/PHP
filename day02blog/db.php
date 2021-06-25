<?php

    session_start();

    $dbUser = 'day02blog';
    $dbPass = 'kd3B1c4oZCq3vUOV';
    $dbName = 'day02blog';
    $dbHost = 'localhost:3333';

    $link = @mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
    // check if connection failed
    if (mysqli_connect_errno()) {
        http_response_code(500);
        die ("Fatal error: failed to connect to MySQL - " . mysqli_connect_error());
    }
