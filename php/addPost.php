<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "snippetmanager";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) 
    die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['title']) && isset($_POST['code']) && isset($_POST['description'])) {
        $title = $_POST['title'];
        $code = $_POST['code'];
        $description = $_POST['description'];
        $userid = $_SESSION['id'];

        $sql = "INSERT INTO snippets (snippet_title, snippet_code, description, author_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $code, $description, $userid);
        if ($stmt->execute()) {
            echo "Post added successfully";
        } else {
            echo "Error: $sql <br> $conn->error";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add post</title>

    <link rel="stylesheet" href="../css/stylesheet.css">
    <link rel="stylesheet" href="../resources//highlightjs/styles/atom-one-dark.css">
    <link rel="stylesheet" href="../css/addpost.css">

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
            <form action="addPost.php" method="post">
                <input type="text" name="title" id="title" placeholder="Title">
                <textarea name="code" id="code" placeholder="Code"></textarea>
                <textarea name="description" id="description" placeholder="Description"></textarea>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>
    <script src="./resources/highlightjs/highlight.js"></script>
    <script>hljs.highlightAll();</script>
</body>
</html>
