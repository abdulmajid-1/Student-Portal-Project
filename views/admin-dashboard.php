<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* NAVBAR GRID STYLING */
        .navbar {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            background-color: #006699;
            color: white;
            padding: 10px 20px;
        }

        .navbar .brand {
            font-size: 1.5rem;
        }

        .navbar ul {
            display: grid;
            grid-auto-flow: column;
            gap: 20px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .account-menu {
            position: relative;
        }

        .account-dropdown {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            color: black;
            border: 1px solid #ccc;
            padding: 10px;
            min-width: 150px;
            z-index: 1;
        }

        .account-dropdown a {
            display: block;
            color: black;
            padding: 5px 0;
            text-decoration: none;
        }

        .account-dropdown a:hover {
            background-color: #f0f0f0;
        }

        .account-menu:hover .account-dropdown {
            display: block;
        }

        .main {
            padding: 20px;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="brand">Admin Portal</div>
    <ul>
        <li><a href="student-view.php">Students</a></li>
        <li><a href="course-view.php">Courses</a></li>
        <li><a href="enrollment-view.php">Enrollments</a></li>
        <li><a href="teacher-view.php">Teachers</a></li>
        <li class="account-menu">
            <a href="#">Account ▾</a>
            <div class="account-dropdown">
                <a href="#">Welcome, <?= htmlspecialchars($_SESSION["name"]) ?></a>
                <a href="adminController.php">Change Password</a>
                <a href="logout.php">Logout</a>
            </div>
        </li>
    </ul>
</nav>
<div class="main">
    <h2>Welcome, <?= htmlspecialchars($_SESSION["name"]) ?>!</h2>
    <p>This is your admin dashboard.</p>
    <!-- ...existing dashboard content... -->
</div>


</body>
</html>
