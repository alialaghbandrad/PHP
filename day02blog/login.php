<?php require_once 'db.php'; ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
</head>
<body>
    <div id="centeredContent">
    <?php
    function displayForm() {
        $form = <<< END
    <form method="post">
        Email: <input name="email" type="text"><br>
        Password: <input name="password" type="password"><br>
        <input type="submit" value="Login">
    </form>
END;
        echo $form;
    }

    if (isset($_POST['email'])) { // we're receving a submission
        $email = $_POST['email'];
        $password = $_POST['password'];
        // verify inputs
        $result = mysqli_query($link, sprintf("SELECT * FROM users WHERE email='%s'",
            mysqli_real_escape_string($link, $email)));
        if (!$result) {
            die("SQL Query failed: " . mysqli_error($link));
        }
        $userRecord = mysqli_fetch_assoc($result);
        $loginSuccessful = false;
        if ($userRecord) {
            if ($userRecord['password'] == $password) {
                    $loginSuccessful = true;
            }
        }
        //
        if (!$loginSuccessful) { // STATE 2: submission with errors (failed)
            echo '<p class="errorMessage">Invalid username or password</p>';
            displayForm();
        } else { // STATE 3: submission successful
            unset($userRecord['password']); // for safety reasons remove the password
            $_SESSION['blogUser'] = $userRecord;
            echo "<p>login successful</p>";
            echo '<p><a href="index.php">Click here to continue</a></p>';
        }
    } else { // STATE 1: first show
        displayForm();
    }
    ?>

    </div>
</body>
</html>