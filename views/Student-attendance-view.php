<?php
    // session_start();
    // include_once '../config/db.php';
    // include_once '../studentController.php';

    // $objdatabaseconnection = new DatabaseConnectivity();
    // $connection = $objdatabaseconnection->getConnection();
    // $objStudentController = new studentController($connection);

    // $courses =  $objStudentController->GetCoursesbyStudentID($_SESSION['user_id']);
    // $attendance = $objStudentController->GetAttendanceforStudent($_SESSION['user_id'], $course_id);
    
    

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
include_once '../config/db.php';
include_once '../controllers/studentController.php';

$objdatabaseconnection = new DatabaseConnectivity();
$connection = $objdatabaseconnection->getConnection();
$objStudentController = new studentController($connection);

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
<html>
<head>
    <title>Student Attendance Viewer</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="main">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></h2>

    <!-- Course Selection Form -->
    <form method="POST">
        <label for="course_id">Select a Course to View Attendance:</label>
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
         <pre><?php print_r($attendance); ?></pre>

    <?php endif; ?>

</div>
</body>
</html>


