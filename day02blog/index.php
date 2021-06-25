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
        //
        if (isset($_SESSION['blogUser'])) { // logged in
            echo "<p>You are logged in as " . $_SESSION['blogUser']['name'] . ". ";
            echo 'You can <a href="logout.php">logout</a> or <a href="articleadd.php">post an article</a></p>'. "\n";
        } else { // not logged in
            echo '<p><a href="login.php">login</a> or <a href="register.php">register</a> to post articles and comments.</p>'. "\n";
        }
        //
        $sql = "SELECT a.id, a.createdTS, a.title, a.body, u.name FROM articles as a, users as u WHERE a.authorId = u.id ORDER BY Id DESC";
        $result = mysqli_query($link, $sql);
        if (!$result) {
            die("SQL Query failed: " . mysqli_error($link));
        }
        echo "<pre>\n";
        while ($article = mysqli_fetch_assoc($result)) {
            // print_r($article);
            echo '<div class="articlePreviewBox">';
            echo '<h2><a href="article.php?id=' . $article['id'] . '">' . $article['title'] . '</a></h2>';
            $postedDate = date('M d, Y \a\t H:i:s', strtotime($article['createdTS']));
            echo "<i>Posted by ". htmlentities($article['name']) . " on " . $postedDate . "</i>\n";
            $fullBodyNoTags = strip_tags($article['body']);
            $bodyPreview = substr($fullBodyNoTags, 0, 100); // FIXME
            $bodyPreview .= (strlen($fullBodyNoTags) > strlen($bodyPreview)) ? "..." : "";
            echo "<p>$bodyPreview</p>";
            echo '</div>' . "\n\n";
        }
        echo "</pre>\n";

    ?>
</body>
</html>