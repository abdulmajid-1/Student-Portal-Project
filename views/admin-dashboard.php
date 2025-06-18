<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($_SESSION["name"]) ?>!</h2>
<p>This is your Admin dashboard.</p>

<ul>
    <li><a href="enrollment-view.php">Enrollment Page</a></li>
    <li><a href="course-view.php">Course Page</a></li>
    <li><a href="student-view.php">Student View</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>

</body>
</html>
