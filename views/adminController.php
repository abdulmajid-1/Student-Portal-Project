<?php
session_start();
include_once '../config/db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'admin') {
    header("Location: login.php");
    exit;
}

$objDatabaseConnection = new DatabaseConnectivity();
$connection = $objDatabaseConnection->getConnection();

class AdminController {
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
            return "❌ User not found.";
        }

        if (!password_verify($oldPassword, $user['password'])) {
            return "❌ Current password is incorrect.";
        }

        $query = "UPDATE Users SET password = :password WHERE U_id = :user_id";
        $stmt = $this->connection->prepare($query);
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "✅ Password changed successfully.";
        } else {
            return "❌ Failed to change password.";
        }
    }
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION["user_id"];
    $oldPassword = $_POST["oldPassword"];
    $newPassword = $_POST["newPassword"];

    if (empty($oldPassword) || empty($newPassword)) {
        $message = "⚠️ Please fill in all fields.";
    } else {
        $adminController = new AdminController($connection);
        $message = $adminController->changePassword($userId, $oldPassword, $newPassword);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Change Password</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .header-bar {
            background-color: #007BFF;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-bar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }
        .header-bar a:hover {
            text-decoration: underline;
        }
        .main {
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }
        .message {
            margin: 15px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header-bar">
    <div><strong>Admin Control Panel</strong></div>
    <div>
        <a href="admin-dashboard.php">⬅ Back to Dashboard</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
</div>

<!-- Main Content -->
<div class="main">
    <h2>Change Password</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="oldPassword">Current Password:</label><br>
        <input type="password" id="oldPassword" name="oldPassword" required><br><br>

        <label for="newPassword">New Password:</label><br>
        <input type="password" id="newPassword" name="newPassword" required><br><br>

        <button type="submit">Change Password</button>
    </form>
</div>

</body>
</html>
