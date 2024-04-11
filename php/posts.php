<?php
session_start();

$query = isset($_GET['query']) ? $_GET['query'] : null;
if (!$query) {
    echo '{}';
    exit();
}

$serverName = "localhost";
$username = "root";
$password = "";
$dbname = "snippetmanager";
$conn = new mysqli($serverName, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare('SELECT snippets.*, users.username
FROM snippets
JOIN users ON snippets.author_id = users.user_id
WHERE LOWER(snippets.snippet_code) LIKE ?
    OR LOWER(users.username) LIKE ?
    OR LOWER(snippets.snippet_title) LIKE ?
    OR LOWER(snippets.description) LIKE ?
ORDER BY snippet_score DESC');
$queryParam = "%" . $query . "%";
$stmt->bind_param("ssss", $queryParam, $queryParam, $queryParam, $queryParam);
$stmt->execute();
$result = $stmt->get_result();
$snippets = array();
while ($row = $result->fetch_assoc()) {
    $snippets[] = $row;
}
$stmt->close();
$conn->close();

echo json_encode($snippets, JSON_UNESCAPED_UNICODE);
exit();
    
