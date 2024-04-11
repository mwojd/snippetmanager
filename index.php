<?php
    session_start();
    function displayPosts() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "snippetmanager";
        $conn = new mysqli($servername, $username, $password, $dbname);
        $stmt = $conn->prepare("SELECT snippets.*, users.username, likes.user_id, likes.like_value 
                                FROM snippets 
                                JOIN users ON snippets.author_id=users.user_id 
                                LEFT JOIN likes ON snippets.snippet_id=likes.snippet_id AND likes.user_id = ? 
                                ORDER BY snippet_score DESC");
        $stmt->bind_param("i", $_SESSION['id']);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $id = $row["snippet_id"];
            echo "<div class='snippet-post' data-id='$id' style='width: 400px;'>";
            echo "<div class='snippet-header'>";
            echo "<span class='snippet-title'>" . $row['snippet_title'] . "</span>&nbsp;&nbsp;&nbsp;by&nbsp;&nbsp;&nbsp;";
            echo "<span class='snippet-author'>" . $row['username'] . "</span>";
            echo "</div>";
            echo "<div class='snippet-body'>";
            echo "<div class='snippet-code'><pre><code>" . htmlspecialchars($row['snippet_code']) . "</code></pre></div>";
            echo "</div>";
            echo "<div class='snippet-footer'>";
            if ($row['like_value'] == 1) {
                echo "<button class='up-vote active'>arrow up</button>";
            } else {
                echo "<button class='up-vote'>arrow up</button>";
            }
            echo "<span class='snippet-score'>" . $row['snippet_score'] . "</span>";
            if ($row['like_value'] == -1) {
                echo "<button class='down-vote active'>arrow down</button>";
            } else {
                echo "<button class='down-vote'>arrow down</button>";
            }
            echo "</div>";
            echo "</div>";
        }
        $stmt->close();
        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>snippet manager</title>
    <link rel="stylesheet" href="./css/stylesheet.css">
    <link rel="stylesheet" href="./css/index.css">
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
                <div class="profile">
                <a class="add-snippet" href="/snippetmanager/php/addPost.php">+</a>
                <?php 
                    if (!isset($_SESSION['id'])) { 
                        echo "<a class='login' href='/snippetmanager/php/login.php'>login</a>";
                    } else {
                        echo "<button class='login'>" . $_SESSION['username'] . "</button>";
                    }
                ?>
                    <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "snippetmanager";
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        $stmt = $conn->prepare('SELECT userkarma FROM users WHERE user_id=?');
                        $stmt->bind_param("i", $_SESSION['id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        if (isset($_SESSION['id'])) {
                            echo "<span class='karma'>" . $row['userkarma'] . " karma</span>";
                        }
                    ?>
                </div>
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
    <script src="./js/likeSystem.js"></script>
    <script src="./js/redirectToPost.js"></script>
</body>
</html>