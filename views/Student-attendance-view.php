<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'user') {
    header("Location: login.php");
    exit;
}

include_once '../config/db.php';
include_once '../controllers/studentController.php';

$objdatabaseconnection = new DatabaseConnectivity();
$connection = $objdatabaseconnection->getConnection();
$objStudentController = new StudentController($connection);

// Fetch courses for dropdown
$courses = $objStudentController->GetCoursesbyStudentID($_SESSION['user_id']);

$selectedCourseId = null;
$attendance = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $selectedCourseId = $_POST['course_id'];
    $attendance = $objStudentController->GetAttendanceforStudent($_SESSION['user_id'], $selectedCourseId);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Attendance</title>
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
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #666;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header-bar">
        <div><strong>Student Attendance Viewer</strong></div>
        <div>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php"> Logout</a>
        </div>
    </div>

    <div class="main">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></h2>

        <!-- Course Selection Form -->
        <form method="POST">
            <label for="course_id"><strong>Select a Course to View Attendance:</strong></label><br>
            <select name="course_id" id="course_id" required>
                <option value="">-- Choose a Course --</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= htmlspecialchars($course['course_id']) ?>" <?= ($selectedCourseId == $course['course_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($course['course_name']) ?> (Taught by: <?= htmlspecialchars($course['teacher_name']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">View Attendance</button>
        </form>

        <!-- Attendance Display -->
        <?php if (!empty($attendance)): ?>
            <h3>Attendance for Selected Course</h3>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($attendance as $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($record['date']) ?></td>
                        <td><?= htmlspecialchars($record['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif ($selectedCourseId): ?>
            <p>No attendance records found for this course.</p>
        <?php endif; ?>
    </div>

</body>

</html>