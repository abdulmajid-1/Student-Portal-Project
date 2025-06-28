<?php
session_start();
include_once '../config/db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$objDatabaseConnection = new DatabaseConnectivity();
$objDatabaseConnection = $objDatabaseConnection->getConnection();

class TeacherPasswordController {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function changePassword($userId, $oldPassword, $newPassword) {
        $query = "SELECT password FROM Users WHERE U_id = :user_id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return "User not found.";
        }

        if (!password_verify($oldPassword, $user['password'])) {
            return "Current password is incorrect.";
        }

        $query = "UPDATE Users SET password = :password WHERE U_id = :user_id";
        $stmt = $this->connection->prepare($query);
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        return $stmt->execute() ? "Password changed successfully." : "Failed to change password.";
    }
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST["oldPassword"];
    $newPassword = $_POST["newPassword"];

    if (empty($oldPassword) || empty($newPassword)) {
        $message = "Please fill in all fields.";
    } 
    else {
        $controller = new StudentPasswordController($objDatabaseConnection);
        $message = $controller->changePassword($_SESSION["user_id"], $oldPassword, $newPassword);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
        }

        .header-bar {
            background-color: #007BFF;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-bar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 14px;
        }

        .header-bar a:hover {
            text-decoration: underline;
        }

        .main {
            max-width: 500px;
            margin: 60px auto;
            padding: 30px 40px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="password"] {
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        button {
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 15px;
            font-weight: bold;
            text-align: center;
            color: #d9534f;
        }
    </style>
</head>
<body>

<!-- Header Bar -->
<div class="header-bar">
    <div><strong>Change Password</strong></div>
    <div>
        <a href="teacher-dashboard.php">⬅ Dashboard</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
</div>

<!-- Main Section -->
<div class="main">
    <form method="POST">
        <input type="password" name="oldPassword" required placeholder="Current Password">
        <input type="password" name="newPassword" required placeholder="New Password">
        <button type="submit">Change Password</button>
        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
    </form>
</div>

</body>
</html>
