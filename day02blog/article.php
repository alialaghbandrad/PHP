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
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            http_response_code(400);
            die("Error: missing article ID in the URL or is not a number");
        }
        $id = $_GET['id'];
        $sql = sprintf("SELECT a.id, a.createdTS, a.title, a.body, u.name "
            . "FROM articles as a, users as u "
            . "WHERE a.authorId = u.id AND a.id = '%s' ORDER BY Id DESC",
                mysqli_real_escape_string($link, $id));
        $result = mysqli_query($link, $sql);
        if (!$result) {
            http_response_code(500);
            die("SQL Query failed: " . mysqli_error($link));
        }
        $article = mysqli_fetch_assoc($result);
        if ($article) {
            echo '<div class="articleBox">';
            echo '<h2>' . htmlentities($article['title']) . '</h2>';
            $postedDate = date('M d, Y \a\t H:i:s', strtotime($article['createdTS']));
            echo '<i> Posted by '. $article['name'] .' on ' . $postedDate . "</i>\n";
            echo '<div class="articleBody">' . $article['body'] . "</div>\n";
            echo '</div>';
            echo '<script> document.title="' . htmlentities($article['title']) . '"; </script>\n';
        } else {
            http_response_code(404);
            echo '<h2>Article with this ID was not found</h2>';
        }
    ?>
</body>
</html>