<?php
include_once '../controllers/studentController.php';
include_once '../config/db.php';
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'user') {
    header("Location: login.php");
    exit;
}

$objDatabaseConnection = new DatabaseConnectivity();
$connection = $objDatabaseConnection->getConnection();
$objStudentController = new StudentController($connection);

$courses = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['view'])) {
    $courses = $objStudentController->viewMyCourses($_SESSION["user_id"]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View My Courses</title>
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
            max-width: 900px;
            margin: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #666;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header-bar">
        <div><strong>Student Dashboard</strong></div>
        <div>
            <a href="dashboard.php"> Back to Dashboard</a>
            <a href="logout.php"> Logout</a>
        </div>
    </div>

    <div class="main">
        <h2>Welcome to Student Dashboard</h2>

        <form method="POST" action="">
            <button type="submit" name="view">View My Courses</button>
        </form>

        <div id="course-table" style="margin-top: 25px;">
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['view'])): ?>
                <?php if (empty($courses)): ?>
                    <p>No courses found.</p>
                <?php else: ?>
                    <h3>📋 My Courses</h3>
                    <table>
                        <tr>
                            <th>Course ID</th>
                            <th>Course Name</th>
                            <th>Course Code</th>
                            <th>Department</th>
                        </tr>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?= htmlspecialchars($course['C_id']) ?></td>
                                <td><?= htmlspecialchars($course['name']) ?></td>
                                <td><?= htmlspecialchars($course['code']) ?></td>
                                <td><?= htmlspecialchars($course['department']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>