<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add article</title>
</head>
<body>
<?php

    if (!isset($_SESSION['blogUser'])) {
        die("Error: only authenticated users may post an article");
    }

 function displayForm($title = "", $body = "") {
    // heredoc example
    $form = <<< END
    <form method="POST">
        Title: <input name="title" type="text" value="$title"><br>
        <textarea name="body" cols="60" row="10">$body</textarea><br>
        <input type="submit" value="Post article">
    </form>
END;
        echo $form;
    }

    if (isset($_POST['title'])) { // we're receving a submission
        $title = $_POST['title'];
        $body = $_POST['body'];
        // FIXME: sanitize body - 1) only allow certain HTML tags, 2) make sure it is valid html
        // WARNING: If you forget to sanitize the body bad things may happen such as JavaScript injection
        $body = strip_tags($body, "<p><ul><li><em><strong><i><b><ol><h3><h4><h5><span>");
        $title = htmlentities($title);
        // verify inputs
        $errorList = array();
        if (strlen($title) < 2 || strlen($title) > 100) {
            $errorList[] = "Title must be 2-100 characters long";
            // $title = ""; // keep even if invalid
        }
        if (strlen($body) < 2 || strlen($body) > 4000) {
            $errorList[] = "Body must be 2-4000 characters long";
            // $title = ""; // keep even if invalid
        }
        //
        if ($errorList) { // STATE 2: submission with errors (failed)
            echo '<ul class="errorMessage">';
            foreach ($errorList as $error) {
                echo "<li>$error</li>\n";
            }
            echo '</ul>';
            displayForm($title, $body);
        } else { // STATE 3: submission successful
            $userId = $_SESSION['blogUser']['id'];
            $sql = sprintf(
                "INSERT INTO articles VALUES (NULL, '%s', NULL, '%s', '%s')",
                mysqli_real_escape_string($link, $userId),
                mysqli_real_escape_string($link, $title),
                mysqli_real_escape_string($link, $body)
            );
            if (!mysqli_query($link, $sql)) {
                die("Fatal error: failed to execute SQL query: " . mysqli_error($link));
            }
            $articleId = mysqli_insert_id($link);
            echo "<h3>Article added</h3>";
            echo '<p><a href="article.php?id='.$articleId.'">Click here to view it</a></p>';
        }
    } else { // STATE 1: first show
        displayForm();
    }

?>
</body>
</html>