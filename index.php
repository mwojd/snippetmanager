<?php
    session_start();

    function displayPosts() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "snippetmanager";
        $conn = new mysqli($servername, $username, $password, $dbname);
        foreach ($conn->query('SELECT snippets.*, users.username FROM snippets JOIN users ON snippets.author_id=users.user_id ORDER BY snippet_score DESC') as $row) {
            $id = $row["snippet_id"];
            echo "<div class='snippet-post' data-id='$id' style='width: 400px;'>";
            echo "<div class='snippet-header'>";
            echo "<span class='snippet-title'>" . $row['snippet_title'] . "</span>";
            echo "<span class='snippet-author'>" . $row['username'] . "</span>";
            echo "</div>";
            echo "<div class='snippet-body'>";
            echo "<div class='snippet-code'><pre><code>" . $row['snippet_code'] . "</code></pre></div>";
            echo "</div>";
            echo "<div class='snippet-footer'>";
            echo "<button class='up-vote'>arrow up</button>";
            echo "<button class='comments'>comm</button>";
            echo "<button class='down-vote'>arrow down</button>";
            echo "</div>";
            echo "</div>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>snippet manager</title>
    <link rel="stylesheet" href="./css/stylesheet.css">

    <!-- HighLighJs package used for highlighting code -->
    <!-- https://highlightjs.org/ -->
    <link rel="stylesheet" href="./resources/highlightjs/styles/atom-one-dark.css">
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <div class="search">
                <input type="text" name="search" id="search-bar" placeholder="Search...">
            </div>
            <!-- profile -->
            <div class="profile-bar">
                <button class="add-snippet"><a href="/snippetmanager/php/addPost.php">+</a></button>
                <button class="login"><a href="/snippetmanager/php/login.php">login</a></button>
                <button class="user-settings">settings</button>
            </div>
            <div class="profile">
                    <button class="profile-button">Profile</button>
                    <span class="karma">karma</span>
            </div>
        </div>
        <div class="main">
            <div class="main-child"> <!-- this is where the snippets will be displayed -->
                <?php displayPosts(); ?>    
            </div>
        </div>
    </div>
    <script src="./resources/highlightjs/highlight.js"></script>
    <script>hljs.highlightAll();</script>
    <script src="./js/search.js"></script>
</body>
</html>