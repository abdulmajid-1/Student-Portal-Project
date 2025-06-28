<?php
session_start();
require_once '../config/db.php';
include_once '../controllers/userController.php';

$objdatabaseconnection = new DatabaseConnectivity();
$objdatabaseconnection = $objdatabaseconnection->getConnection();

$objUserController = new userController($objdatabaseconnection);

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $objUserModel = new User();
    $objUserModel->setName($_POST["name"]);
    $objUserModel->setEmail($_POST["email"]);
    $objUserModel->setPassword(password_hash($_POST["password"], PASSWORD_DEFAULT));
    $objUserModel->setRole($_POST["role"]);

    $isSuccess = $objUserController->InsertUser($objUserModel);

    if ($isSuccess) {
        $message = "<div class='success'>Registration successful! <a href='login.php'>Login now</a></div>";
    } else {
        $message = "<div class='error'>Error: Something went wrong during registration.</div>";
    }

    $objdatabaseconnection = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Student Portal</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-box {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 380px;
            text-align: center;
        }
        .register-box h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .register-box input,
        .register-box select {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }
        .register-box button {
            width: 100%;
            padding: 12px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        .register-box button:hover {
            background: #0056b3;
        }
        .register-box p {
            margin-top: 15px;
            font-size: 14px;
        }
        .register-box a {
            color: #007BFF;
            text-decoration: none;
        }
        .error, .success {
            background-color: #ffe0e0;
            color: #cc0000;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
            font-size: 14px;
        }
        .success {
            background-color: #e0ffe5;
            color: #007F00;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Register</h2>
        <?php if (!empty($message)) { echo $message; } ?>
        <form method="post">
            <input type="text" name="name" required placeholder="Full Name">
            <input type="email" name="email" required placeholder="Email">
            <input type="password" name="password" required placeholder="Password">
            <select name="role" required>
                <option value="User">User</option>
                <option value="teacher">Teacher</option>
            </select>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
