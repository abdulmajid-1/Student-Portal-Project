<?php
session_start();
include_once '../config/db.php'; // Include your database connection file
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
$objDatabaseConnection = new DatabaseConnectivity();
$objDatabaseConnection = $objDatabaseConnection->getConnection(); // Get the PDO connection

class StudentPasswordController {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function changePassword($userId, $oldPassword, $newPassword) {
        // Fetch current hashed password
        $query = "SELECT password FROM Users WHERE U_id = :user_id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo "User not found.";
            return false;
        }

        // Verify current password
        if (!password_verify($oldPassword, $user['password'])) {
            echo "Current password is incorrect.";
            return false;
        }

        // Update to new password
        $query = "UPDATE Users SET password = :password WHERE U_id = :user_id";
        $stmt = $this->connection->prepare($query);
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION["user_id"])) {
        echo "You are not logged in.";
        exit;
    }

    $userId = $_SESSION["user_id"];
    $oldPassword = $_POST["oldPassword"];
    $newPassword = $_POST["newPassword"];

    if (empty($oldPassword) || empty($newPassword)) {
        echo "Please fill in all fields.";
    } else {
        $passwordController = new StudentPasswordController($objDatabaseConnection);
        $success = $passwordController->changePassword($userId, $oldPassword, $newPassword);
        echo $success ? "Password changed successfully." : "Failed to change password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
<<<<<<< HEAD
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="main center">
    <h2>Change Password</h2>
    <form method="post">
        <input type="password" name="oldPassword" required placeholder="Current Password">
        <input type="password" name="newPassword" required placeholder="New Password">
        <button type="submit">Change Password</button>
    </form>
</div>
=======
</head>
<body>
    <h2>Change Password</h2>
    <form method="POST" action="">
        <label for="oldPassword">Current Password:</label>
        <input type="password" id="oldPassword" name="oldPassword" required>
        <br><br>

        <label for="newPassword">New Password:</label>
        <input type="password" id="newPassword" name="newPassword" required>
        <br><br>

        <button type="submit">Change Password</button>
    </form>
>>>>>>> 75f05e234758a5677829d8a78701678a06e21c6c
</body>
</html>
