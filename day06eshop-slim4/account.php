<?php

use Slim\Factory\AppFactory;
use Slim\Views\TwigMiddleware;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

require_once "setup.php";


$app->get('/passreset_request', function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'password_reset.html.twig');
});

$app->post('/passreset_request', function (Request $request, Response $response) {
    global $log;
    $view = Twig::fromRequest($request);
    $post = $request->getParsedBody();
    $email = filter_var($post['email'], FILTER_VALIDATE_EMAIL); // 'FALSE' will never be found anyway
    $user = DB::queryFirstRow("SELECT * FROM users WHERE email=%s", $email);
    if ($user) { // send email
        $secret = generateRandomString(60);
        $dateTime = gmdate("Y-m-d H:i:s"); // GMT time zone
        DB::insertUpdate('passwordresets', [
                'userId' => $user['id'],
                'secret' => $secret,
                'creationDateTime' => $dateTime
            ], [
                'secret' => $secret,
                'creationDateTime' => $dateTime
            ]);
        //
        // primitive template with string replacement
        $emailBody = file_get_contents('templates/password_reset_email.html.strsub');
        $emailBody = str_replace('EMAIL', $email, $emailBody);
        $emailBody = str_replace('SECRET', $secret, $emailBody);
        /* // OPTION 1: PURE PHP EMAIL SENDING - most likely will end up in Spam / Junk folder
        $to = $email;
        $subject = "Password reset";
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // More headers
        $headers .= 'From: No Reply <noreply@teacher.ip20.com>' . "\r\n";
        // finally send the email
        $result = mail($to, $subject, $emailBody, $headers);
        if ($result) {
            $log->debug(sprintf("Password reset sent to %s", $email));
        } else {
            $log->error(sprintf("Error sending password reset email to %s\n:%s", $email));
        } 
        // end of option 1 code */

        // OPTION 2: USING EXTERNAL SERVICE - should not land in Spam / Junk folder 
        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key',
            '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');
        $apiInstance = new SendinBlue\Client\Api\SMTPApi(new GuzzleHttp\Client(), $config);
        // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();
        $sendSmtpEmail->setSubject("Password reset for teacher.ipd20.com");
        $sendSmtpEmail->setSender(new \SendinBlue\Client\Model\SendSmtpEmailSender(
            ['name' => 'No-Reply', 'email' => 'noreply@teacher.ip20.com']) );
        $sendSmtpEmail->setTo([ new \SendinBlue\Client\Model\SendSmtpEmailTo(
            ['name' => $user['name'], 'email' => $email])  ]);
        $sendSmtpEmail->setHtmlContent($emailBody);
        //
        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            $log->debug(sprintf("Password reset sent to %s", $email));
            return $view->render($response, 'password_reset_sent.html.twig');
        } catch (Exception $e) {
            $log->error(sprintf("Error sending password reset email to %s\n:%s", $email, $e->getMessage()));
            return $response->withHeader("Location", "/error_internal",403);            
        }
        // end of option 2 code
    }
    //
    return $view->render($response, 'password_reset_sent.html.twig');
});

$app->map(['GET', 'POST'], '/passresetaction/{secret}', function (Request $request, Response $response, array $args) {
    global $log;
    $view = Twig::fromRequest($request);
    // this needs to be done both for get and post
    $secret = $args['secret'];
    $resetRecord = DB::queryFirstRow("SELECT * FROM passwordresets WHERE secret=%s", $secret);
    if (!$resetRecord) {
        $log->debug(sprintf('password reset token not found, token=%s', $secret));
        return $view->render($response, 'password_reset_action_notfound.html.twig');
    }
    // check if password reset has not expired
    $creationDT = strtotime($resetRecord['creationDateTime']); // convert to seconds since Jan 1, 1970 (UNIX time)
    $nowDT = strtotime(gmdate("Y-m-d H:i:s")); // current time GMT
    if ($nowDT - $creationDT > 60*60) { // expired
        DB::delete('passwordresets', 'secret=%s', $secret);
        $log->debug(sprintf('password reset token expired userid=%s, token=%s', $resetRecord['userId'], $secret));
        return $view->render($response, 'password_reset_action_notfound.html.twig');
    }
    // 
    if ($request->getMethod() == 'POST') {
        $post = $request->getParsedBody();
        $pass1 = $post['pass1'];
        $pass2 = $post['pass2'];
        $errorList = array();
        if ($pass1 != $pass2) {
            array_push($errorList, "Passwords don't match");
        } else {
            $passQuality = verifyPasswordQuality($pass1);
            if ($passQuality !== TRUE) {
                array_push($errorList, $passQuality);
            }
        }
        //
        if ($errorList) {
            return $view->render($response, 'password_reset_action.html.twig', ['errorList' => $errorList]);
        } else {
            DB::update('users', ['password' => $pass1], "id=%d", $resetRecord['userId']);
            DB::delete('passwordresets', 'secret=%s', $secret); // cleanup the record
            return $view->render($response, 'password_reset_action_success.html.twig');
        }
    } else {
        return $view->render($response, 'password_reset_action.html.twig');
    }
});



function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


$app->get('/login', function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);

    if (isset($_SESSION['user'])) {
        //TODO: Add loging message
        return $response->withHeader('Location', '/');
    }

    return $view->render($response, 'login.html.twig');
});

$app->post('/login', function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);

    if (isset($_SESSION['user'])) {
        return $response->withHeader('Location', '/');
    }

    $loginInfo = $request->getParsedBody();

    if ($loginInfo != null) {
        if (isset($loginInfo['email']) && isset($loginInfo['password'])) {
            $user = DB::queryFirstRow("SELECT * FROM users WHERE email= %s", $loginInfo['email']);
            if ($loginInfo['password'] === $user['password']) {
                unset($user['password']);
                $_SESSION['user'] = $user;

                return $response
                    ->withHeader('Location', '/');
            }
        }
    }

    return $view->render($response, 'login.html.twig', [
        'error' => "Email doesn't match password."
    ]);
});

$app->get('/logout', function (Request $request, Response $response) {
    unset($_SESSION['user']);
    return $response->withHeader('Location', '/');
});

// returns error message or TRUE if password is okay
function verifyPasswordQuality($password) {
    if (strlen($password) < 6 || strlen($password) > 100
        || preg_match("/[a-z]/", $password) == false
        || preg_match("/[A-Z]/", $password) == false
        || preg_match("/[0-9#$%^&*()+=-\[\]';,.\/{}|:<>?~]/", $password) == false) {
        return "Password must be 6~100 characters,
                            must contain at least one uppercase letter, 
                            one lower case letter, 
                            and one number or special character.";
        }
    return TRUE;
}

$app->get('/register', function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'register.html.twig');
});

$app->post('/register', function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);

    if (isset($_SESSION['user'])) {
        return $response->withHeader('Location', '/');
    }

    $registerInfo = $request->getParsedBody();

    $email = $registerInfo['email'];
    $name = $registerInfo['name'];
    $password = $registerInfo['password'];
    $errors = [];

    if (strlen($name) < 5 || strlen($name) > 20) {
        $errors['name'] = "Name must be 5~20 chars";
        $registerInfo['name'] = '';
    } 

    if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $errors['email'] = "Invalid Email";
        $email = '';
    } elseif (isEmailTaken($email)) {
        $errors['email'] = "User is already exist.";
        $email= '';
    }


    $passQuality = verifyPasswordQuality($password);
    if ($passQuality !== TRUE) {
        $errors['password'] = $passQuality;
    } elseif ($password !== $_POST['confirm']) {
        $errors['password'] = "Passwords must be same.";
    }

    if (empty($errors)) {
        DB::insert('users', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'isAdmin' => 'false'
        ]);
        $_SESSION['user'] = DB::queryFirstRow("SELECT * FROM users WHERE email = %s",$email);

        return $response->withHeader('Location', '/');
    }

    return $view->render($response, 'register.html.twig', [
        'errors' => $errors,
        'prevInput' => [
            'name' => $name,
            'email' => $email
        ]
    ]);
});

$app->get('/register/isemailtaken/[{email}]', function (Request $request, Response $response, array $args){
    $error = '';

    if(isset($args['email'])){
        $error = isEmailTaken($args['email']) ? "It's already taken." :'';
    }

    $response->getBody()->write($error);
    return $response;
});



function isEmailTaken($email)
{
    $users = DB::queryFirstRow("SELECT COUNT(*) AS 'count' FROM users WHERE email = %s", $email);

    if ($users['count'] == 0) {
        return false;
    } elseif ($users['count'] == 1) {
        return true;
    } else {
        echo "Internal Error: duplicate username.";//FIXME : Log instead of echoing
        return true;
    }
}