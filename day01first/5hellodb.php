<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
</head>
<body>
<?php

    $dbUser = 'day01people';
    $dbPass = 'FckKd5ckePjj9YRW';
    $dbName = 'day01people';
    $dbHost = 'localhost:3333';

    $link = @mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
    // check if connection failed
    if (mysqli_connect_errno()) {
        die ("Fatal error: failed to connect to MySQL - " . mysqli_connect_error());
    }

    function displayForm($nameLLL = "", $ageLLL = "") {
        // heredoc example
        $formLLL = <<< END
    <form method="post">
        Name: <input name="name" type="text" value="$nameLLL"><br>
        Age: <input name="age" type="number" value="$ageLLL"><br>
        <input type="submit" value="Say hello">
    </form>
END;
        echo $formLLL;
    }

    if (isset($_POST['name'])) { // we're receving a submission
        $name = $_POST['name'];
        $age = $_POST['age'];
        // verify inputs
        $errorList = array();
        if (strlen($name) < 2 || strlen($name) > 50) {
            $errorList[] = "Name must be 2-50 characters long";
            $name = "";
        }
        if (filter_var($age, FILTER_VALIDATE_INT) === false || $age < 0 || $age > 150) {
            $errorList[] = "Age must be a number between 0 and 150";
            $age = "";
        }
        //
        if ($errorList) { // STATE 2: submission with errors (failed)
            echo '<ul class="errorMessage">';
            foreach ($errorList as $error) {
                echo "<li>$error</li>\n";
            }
            echo '</ul>';
            displayForm($name, $age);
        } else { // STATE 3: submission successful
            $sql = sprintf("INSERT INTO people VALUES (NULL, '%s', '%s')",
                    mysqli_real_escape_string($link, $name),
                    mysqli_real_escape_string($link, $age)
                );
            if (!mysqli_query($link, $sql)) {
                    die("Fatal error: failed to execute SQL query: " . mysqli_error($link));
            }
            echo "Hello $name, you are $age y/o.";
        }
    } else { // STATE 1: first show
        displayForm();
    }
    ?>
</body>
</html>