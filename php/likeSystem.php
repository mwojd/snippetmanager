<?
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
    