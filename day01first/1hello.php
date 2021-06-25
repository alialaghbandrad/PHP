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
        echo "Hello world from PHP!<br>";
        $name = $_GET['name'];
        echo "Nice to meet you $name!<br>";
        echo 'Nice to meet you $name!<br>'; // verbatim output with single quotes
        echo 'Nice to meet you ' . $name . '!<br>'; 
        printf('Nice to meet you %s!<br>', $name);
    ?>
</body>
</html>