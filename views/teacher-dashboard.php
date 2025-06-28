<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'teacher') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="brand">Teacher Portal</div>
    <ul>
        <li><a href="teacherCourseView.php">My Courses</a></li>
        <li><a href="attendance.php">Attendance</a></li>
        <li class="account-menu">
            <a href="#">Account ▾</a>
            <div class="account-dropdown">
                <a href="#">Welcome, <?= htmlspecialchars($_SESSION["name"]) ?></a>
                <a href="ChangePassword-teacher.php">Change Password</a>
                <a href="logout.php">Logout</a>
            </div>
        </li>
    </ul>
</nav>

<!-- MAIN CONTENT -->
<div class="main">
    <h2>Welcome, <?= htmlspecialchars($_SESSION["name"]) ?>!</h2>
    <p>This is your teacher dashboard.</p>
    <!-- ...existing dashboard content... -->
</div>

</body>
</html>
