<?php
session_start();

// Sanitize user input
$postID = isset($_REQUEST['postID']) ? intval($_REQUEST['postID']) : null;
$actionType = isset($_REQUEST['actionType']) ? $_REQUEST['actionType'] : null;

if (!isset($_SESSION['id'])) {
    echo '{}';
    echo "not logged in";
    echo $_SESSION['id'];
    exit();
}

if (!$postID || !in_array($actionType, ['like', 'dislike'])) {
    echo '{}';
    exit();
}

$servername = "localhost";
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "snippetmanager";

// Create a PDO instance
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Prepared statement to check if the like exists
$doesExistStmt = $conn->prepare('SELECT * FROM likes WHERE snippet_id=? AND user_id=?');
$doesExistStmt->execute([$postID, $_SESSION['id']]);
$doesExist = $doesExistStmt->rowCount() > 0;

if ($actionType == "like") {
    $likeValue = 1;
} elseif ($actionType == "dislike") {
    $likeValue = -1;
}

if ($doesExist) {
    $stmt = $conn->prepare('UPDATE likes SET like_value=? WHERE snippet_id=? AND user_id=?');
    $stmt->execute([$likeValue, $postID, $_SESSION['id']]);
} else {
    $stmt = $conn->prepare('INSERT INTO likes (user_id, like_value, snippet_id) VALUES (?, ?, ?)');
    $stmt->execute([$_SESSION['id'], $likeValue, $postID]);
}

$stmt->closeCursor();

$updateScoreStmt = $conn->prepare("UPDATE snippets SET snippet_score = (SELECT SUM(like_value) FROM likes WHERE snippet_id=?) WHERE snippet_id=?");
$updateScoreStmt->execute([$postID, $postID]);
$updateScoreStmt->closeCursor();

$userScoreStmt = $conn->prepare("UPDATE users SET userkarma = (SELECT SUM(snippet_score) FROM snippets WHERE author_id=?) WHERE user_id=?");
$userScoreStmt->execute([$_SESSION['id'], $_SESSION['id']]);
$userScoreStmt->closeCursor();

$stmt = $conn->prepare('SELECT snippet_score FROM snippets WHERE snippet_id=?');
$stmt->execute([$postID]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);

$conn = null;