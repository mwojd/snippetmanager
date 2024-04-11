<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "snippetmanager";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['title'], $_POST['code'], $_POST['description'])) {
        $title = $_POST['title'];
        $code = $_POST['code'];
        $description = $_POST['description'];

        // Check if user is logged in
        if (!isset($_SESSION['id'])) {
            echo "<script>alert('Please log in before adding a post'); window.location.href = '../index.php';</script>";
            die();
        }
        $userid = $_SESSION['id'];

        // Prepare and bind the SQL statement
        $sql = "INSERT INTO snippets (snippet_title, snippet_code, description, author_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $code, $description, $userid);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Post added successfully";
            header("Location: ../index.php");
            die();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Missing required fields";
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
        <div class="main">
            <form action="addPost.php" method="post">
                <input type="text" name="title" id="title" placeholder="Title">
                <textarea name="code" id="code" placeholder="Code"></textarea>
                <textarea name="description" id="description" placeholder="Description"></textarea>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>
    <script src="../resources/highlightjs/highlight.js"></script>
    <script>hljs.highlightAll();</script>
</body>
</html>
