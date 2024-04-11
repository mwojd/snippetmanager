<?php
//login and register page
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "snippetmanager";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

        if ($action === 'register') {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

            // Validate username
            $pattern = "/^[a-zA-Z0-9_\-\.]+$/";
            if (!preg_match($pattern, $username)) {
                echo "Invalid username format. Please use alphanumeric characters, underscore, hyphen, and period. Maximum length is 15 characters.";
                return;
            }

            // Username must be less than 15 characters long
            if (strlen($username) > 15) {
                echo "Username must be less than 15 characters long.";
                return;
            }

            // Validate password
            $pattern = "/^[a-zA-Z0-9_!@#\$%&\*\^\(\)]+$/";
            if (!preg_match($pattern, $password)) {
                echo "Invalid password format. Please use alphanumeric characters, !, @, #, $, %, &, *, ^";
                return;
            }

            // Check if username already exists
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "Username already exists. Please choose a different one.";
                exit();
            }

            // Encrypt password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into database
            $sql = "INSERT INTO users (username, passwordHash) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $passwordHash);

            // If user was successfully inserted into database
            if ($stmt->execute()) {
                echo "New record created successfully";
                header("Location: ../index.php");
                $conn->close();
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        } elseif ($action === 'login') {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

            // Check if username exists
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows === 1) {
                    $row = $result->fetch_assoc();
                    if (password_verify($password, $row["passwordHash"])) {
                        session_start();
                        $_SESSION["username"] = $username;
                        $_SESSION["id"] = $row["user_id"];
                        header("Location: ../index.php");
                        $conn->close();
                        exit();
                    } else {
                        echo "Invalid login credentials";
                    }
                } else {
                    echo "Invalid login credentials";
                }
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
}

$conn->close();

if (isset($_SESSION['id'])) {
    header("Location: localhost/snippetmanager/index.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="../css/stylesheet.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="container">
        <h1><a href="https://github.com/mwojd/snippetmanager" target="_blank">Snippet Manager</a></h1>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="username" required><br>
            <input type="password" name="password" placeholder="password" required><br>

            <input type="submit" name="action" value="register">
            <input type="submit" name="action" value="login">
        </form>
    </div>
    
</body>
</html>