<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'teacher') {
    header("Location: login.php");
    exit;
}
include_once '../controllers/teacherController.php';
include_once '../config/db.php';
$objdatabaseconnection = new DatabaseConnectivity();
$objdatabaseconnection = $objdatabaseconnection->getConnection();
$objTeacherController = new TeacherController($objdatabaseconnection);

$teacherCourses = $objTeacherController->GetTeacherCourses($_SESSION["user_id"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Courses (Teacher)</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="main">
    <?php
    if (empty($teacherCourses)) {
        echo "<p>No courses found for this teacher.</p>";
    } 
    else {
        echo '<h3>My Courses</h3>';
        echo '<table border="1" cellpadding="5">
                <tr>
                    <th>Course ID</th>
                    <th>Course Name</th>
                    <th>Course Code</th>
                    <th>Department</th>
                    <th>Teacher Name</th>
                </tr>';
        foreach ($teacherCourses as $course) {
            echo '<tr>
                    <td>' . htmlspecialchars($course['C_id']) . '</td>
                    <td>' . htmlspecialchars($course['course_name']) . '</td>
                    <td>' . htmlspecialchars($course['course_code']) . '</td>
                    <td>' . htmlspecialchars($course['course_department']) . '</td>
                    <td>' . htmlspecialchars($course['teacher_name']) . '</td>
                  </tr>';
        }
        echo '</table>';
    }
    ?>
</div>
</body>
</html>
