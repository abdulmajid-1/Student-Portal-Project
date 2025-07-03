<?php
session_start();
include_once '../config/db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'admin') {
    header("Location: login.php");
    exit;
}

$objDatabaseConnection = new DatabaseConnectivity();
$connection = $objDatabaseConnection->getConnection();

class AdminController
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function changePassword($userId, $oldPassword, $newPassword)
    {
<<<<<<< HEAD
        $query = "SELECT password FROM users WHERE U_id = :user_id";
=======
        $query = "SELECT password FROM Users WHERE U_id = :user_id";
>>>>>>> 244e2f4eefbebb5f44c6822d91d693c6f5f92baf
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

<<<<<<< HEAD
        $query = "UPDATE users SET password = :password WHERE U_id = :user_id";
=======
        $query = "UPDATE Users SET password = :password WHERE U_id = :user_id";
>>>>>>> 244e2f4eefbebb5f44c6822d91d693c6f5f92baf
        $stmt = $this->connection->prepare($query);
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "Password changed successfully.";
        } else {
            return "Failed to change password.";
        }
    }
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION["user_id"];
    $oldPassword = $_POST["oldPassword"];
    $newPassword = $_POST["newPassword"];

    if (empty($oldPassword) || empty($newPassword)) {
        $message = "Please fill in all fields.";
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
            background: linear-gradient(90deg, #004080 60%, #0074d9 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: Arial, sans-serif;
            font-size: 18px;
            font-weight: 500;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            letter-spacing: 0.5px;
            margin: 0;
        }

        .header-bar .brand {
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-bar a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            margin-left: 25px;
            padding: 6px 12px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .header-bar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            text-decoration: none;
        }

        .main {
            padding: 20px;
            max-width: 550px;
            margin: auto;
        }

        .message {
            margin: 15px 0;
            font-weight: bold;
        }

        .OldPass,
        .NewPass {
            width: 100%;
            padding: 10px;
            margin-bottom: 1px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header-bar">
        <div><strong>Admin Control Panel</strong></div>
        <div>
            <a href="admin-dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
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
            <input class="OldPass" type="password" id="oldPassword" name="oldPassword" required><br><br>

            <label for="newPassword">New Password:</label><br>
            <input class="NewPass" type="password" id="newPassword" name="newPassword" required><br><br>

            <button type="submit">Change Password</button>
        </form>
    </div>

</body>

</html>