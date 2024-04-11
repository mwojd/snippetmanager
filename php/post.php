<?php
    session_start();
    $postID = isset($_REQUEST['postID']) ? intval($_REQUEST['postID']) : null;
    // cSpell:words servername snippetmanager
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "snippetmanager";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $stmt = $conn->prepare('SELECT snippets.*, users.username, likes.like_value 
                            FROM snippets 
                            JOIN users ON snippets.author_id=users.user_id 
                            LEFT JOIN likes ON snippets.snippet_id=likes.snippet_id AND likes.user_id = ? 
                            WHERE snippets.snippet_id=? 
                            ORDER BY snippet_score DESC');
    $stmt->bind_param("ii", $_SESSION['id'], $postID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $conn->close();
    $title = $row['snippet_title'];
    $author = $row['username'];
    $description = $row['description'];
    $code = $row['snippet_code'];
    $score = $row['snippet_score'];
    $likeType = $row['like_value'];
    $postID = $row['snippet_id'];
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="../css/postpage.css">
    <link rel="stylesheet" href="../resources/highlightjs/styles/atom-one-dark.css">
</head>
<body>
    <?php 
        echo "<div class='container' data-id='$postID'>";
    ?>
        <div class="header">
                <a class="back" href="../index.php">Back</a>
            <span class="title"><?php echo $title;?></span>
            <span class="author"><?php echo $author;?></span>
        </div>
        <div class="description"><?php echo $description;?></div>
        <div class="code">
            <pre><code><?php echo htmlspecialchars($code);?></code></pre>
        </div>
        <div class="snippet-footer">
            <?php
                if ($likeType == 1) {
                    echo "<button class='up-vote active'>arrow up</button>";
                } else {
                    echo "<button class='up-vote'>arrow up</button>";
                }
            ?>
            <span class="snippet-score"><?php echo $score;?></span>
            <?php
                if ($likeType == -1) {
                    echo "<button class='down-vote active'>arrow down</button>";
                } else {
                    echo "<button class='down-vote'>arrow down</button>";
                }
            ?>
        </div>
    </div>
    <script src="../resources/highlightjs/highlight.js"></script>
    <script>hljs.highlightAll();</script>
    <script src="../js/likeSystem.js"></script>
</body>
</html>