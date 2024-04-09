<?php
    session_start();

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
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check for database connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepared statement to check if the like exists
    $doesExistSMT = $conn->prepare('SELECT * FROM likes WHERE snippet_id=? AND user_id=?');
    $doesExistSMT->bind_param("ii", $postID, $_SESSION['id']);
    $doesExistSMT->execute();
    $doesExistResult = $doesExistSMT->get_result();
    $doesExist = $doesExistResult->num_rows > 0;
    
    if ($actionType == "like") {
        $likeValue = 1;
    } elseif ($actionType == "dislike") {
        $likeValue = -1;
    }
    
    if ($doesExist) {
        $stmt = $conn->prepare('UPDATE likes SET like_value=? WHERE snippet_id=? AND user_id=?');
        $stmt->bind_param("iii", $likeValue, $postID, $_SESSION['id']);
    } else {
        $stmt = $conn->prepare('INSERT INTO likes (user_id, like_value, snippet_id) VALUES (?, ?, ?)');
        $stmt->bind_param("iii", $_SESSION['id'], $likeValue, $postID);
    }
    
    $stmt->execute();
    $stmt->close();
    
    $updateScoreStmt = $conn->prepare("UPDATE snippets SET snippet_score = (SELECT SUM(like_value) FROM likes WHERE snippet_id=?) WHERE snippet_id=?");
    $updateScoreStmt->bind_param("ii", $postID, $postID);
    $updateScoreStmt->execute();
    $updateScoreStmt->close();

    $userScoreStmt = $conn->prepare("UPDATE users SET userkarma = (SELECT SUM(snippet_score) FROM snippets WHERE author_id=?) WHERE user_id=?");
    $userScoreStmt->bind_param("ii", $_SESSION['id'], $_SESSION['id']);
    $userScoreStmt->execute();
    $userScoreStmt->close();
    
    $conn->close();
    ?>