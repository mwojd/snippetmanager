<?php
    session_start();

    $query = $_GET['query'] ?? null;
    if (!$query) {
        echo '{}';
        exit();
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "snippetmanager";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $stmt = $conn->prepare('SELECT snippets.*, users.username FROM snippets JOIN users ON snippets.author_id=users.user_id WHERE snippets.snippet_code LIKE CONCAT("%",?,"%") ORDER BY snippet_score DESC');
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $snippets = array();
    while ($row = $result->fetch_assoc()) {
        $snippets[] = $row;
    }
    echo json_encode($snippets);

    
