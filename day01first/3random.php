<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form>
        Min: <input name="min" type="number"><br>
        Max: <input name="max" type="number"><br>
        <input type="submit" value="Generate 10 random numbers">
    </form>
    <?php
        // echo "<pre>\n";
        // print_r($_GET);
        // echo "</pre>\n";

        $errorList = array();
        if (!isset($_GET['min'])) {
            // array_push($errorList, "Please enter values for submission");
            $errorList[] = "Please enter values for submission";
        } else { // submission received
            $min = $_GET['min'];
            $max = $_GET['max'];
            if (filter_var($min, FILTER_VALIDATE_INT) === false) {
                $errorList[] = "Minimum must be an integer value";
            }
            if (filter_var($max, FILTER_VALIDATE_INT) === false) {
                $errorList[] = "Maximum must be an integer value";
            }
            if ($max < $min) {
                $errorList[] = "Maximum must not be smaller than minimum";
            }
        }
        if ($errorList) { // there were errors - display them
            echo '<ul>';
            foreach($errorList as $error) {
                echo "<li>$error</li>";
            }
            echo '</ul>';
        } else { // submission successful - do the work
            echo "<p>";
            for ($i = 0; $i < 10; $i++) {
                $val = rand($min, $max);
                printf("%s%d", ($i == 0 ? "": ", "), $val);
            }
            echo "</p>";
        }
    ?>
</body>
</html>