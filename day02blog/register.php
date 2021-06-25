<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    require_once 'db.php';

    function displayForm($name = "", $email = "") {
        $form = <<< END
    <form method="post">
        Name: <input name="name" type="text" value="$name"><br>
        Email: <input name="email" type="email" value="$email"><br>
        Password: <input name="pass1" type="password"><br>
        Password (repeated): <input name="pass2" type="password"><br>
        <input type="submit" value="Register">
    </form>
END;
        echo $form;
    }

    if (isset($_POST['name'])) { // we're receving a submission
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass1 = $_POST['pass1'];
        $pass2 = $_POST['pass2'];
        // verify inputs
        $errorList = array();
        if (preg_match('/^[A-Za-z0-9 \.\'-]{4,20}$/', $name) != 1) {
            $errorList[] = "Name must be 4-20 characters long made up of upper-case lower-case characters and numbers";
            $name = "";
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
            $errorList[] = "Email does not look valid";
            $email = "";
        } else { // but is this email already in use?
            $result = mysqli_query($link, sprintf("SELECT * FROM users WHERE email='%s'",
                mysqli_real_escape_string($link, $email)));
            if (!$result) {
                die("SQL Query failed: " . mysqli_error($link));
            }
            $userRecord = mysqli_fetch_assoc($result);
            if ($userRecord) {
                $errorList[] ="This email is already registered";
                $email = "";
            }
        }
        if ($pass1 != $pass2) {
            $errorList[] = "Passwords do not match";
        } else {
            // maybe? preg_match("/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])[a-zA-Z0-9]{6,100}$/"
            if (strlen($pass1) < 6 || strlen($pass1) > 100
                || (preg_match("/[A-Z]/", $pass1) == FALSE )
                || (preg_match("/[a-z]/", $pass1) == FALSE )
                || (preg_match("/[0-9]/", $pass1) == FALSE )) {
                    $errorList[] = "Password must be 6-100 characters long, "
                        . "with at least one uppercase, one lowercase, and one digit in it";
            }
        }
        //
        if ($errorList) { // STATE 2: submission with errors (failed)
            echo '<ul class="errorMessage">';
            foreach ($errorList as $error) {
                echo "<li>$error</li>\n";
            }
            echo '</ul>';
            displayForm($name, $email);
        } else { // STATE 3: submission successful
            $sql = sprintf("INSERT INTO users VALUES (NULL, '%s', '%s', '%s')",
                    mysqli_real_escape_string($link, $name),
                    mysqli_real_escape_string($link, $email),
                    mysqli_real_escape_string($link, $pass1)
                );
            if (!mysqli_query($link, $sql)) {
                die("Fatal error: failed to execute SQL query: " . mysqli_error($link));
            }
            echo "<p>Registration successful</p>";
            echo '<p><a href="login.php">Click here to login</a></p>';
        }
    } else { // STATE 1: first show
        displayForm();
    }
    ?>
</body>
</html>