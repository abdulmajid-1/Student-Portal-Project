<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'teacher') {
    header("Location: login.php");
    exit;
}

include_once '../controllers/teacherController.php';
include_once '../controllers/enrollmentController.php';
include_once '../config/db.php';

$objdatabaseconnection = new DatabaseConnectivity();
$connection = $objdatabaseconnection->getConnection();
$objTeacherController = new TeacherController($connection);
$objenrollmentController = new enrollmentController($connection);

$userId = $_SESSION["user_id"];
$date = date('Y-m-d');


$filteredAttendance = [];

if (isset($_POST['show_attendance']) && !empty($_POST['filter_course_id'])) {
    $CourseID = $_POST['filter_course_id'];
    $teacherId = $objTeacherController->GetTeacherID($userId);

    $filteredAttendance = $objTeacherController->GetAttendanceByCourse($teacherId, $CourseID);
}

$attendanceData = $objTeacherController->GetAttendanceRecords($userId);
$teacherCourses = $objTeacherController->GetTeacherCourses($userId);

$selectedCourseId = null;
$Enrolled_students = [];

if (isset($_POST['submit_attendance']) && isset($_POST['attendance'])) {
    $courseId = $_POST['course_id'];
    $teacherId = $objTeacherController->GetTeacherID($userId);
  //  $studentId = 

    foreach ($_POST['attendance'] as $studentId => $status) {
        $objTeacherController->markAttendance($studentId, $courseId, $teacherId, $date, $status);
    }

    echo "<p>Attendance marked successfully.</p>";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["View-Enrolled-Students"])) {
    if (!empty($_POST["course_id"])) {
        $selectedCourseId = $_POST["course_id"];
        $Enrolled_students = $objenrollmentController->getEnrollmentbyCourseID($selectedCourseId);
    } 
    else {
        echo "<p>Please select a course.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Attendance Management</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="main">
    <h2>Attendance Management</h2>


    <title>Teacher Attendance Dashboard</title>

    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { width: 100%; border-collapse: collapse; margin-top: 25px; }
        th, td { border: 1px solid #aaa; padding: 10px; text-align: left; }
        th { background-color: #efefef; }
        .mark-button { background-color: #28a745; color: white; padding: 10px 20px; margin-top: 15px; border: none; cursor: pointer; }
        .mark-button:hover { background-color: #218838; }
    </style>


    <div>
</head>
<body>
    <h2>Welcome,  <?= htmlspecialchars($_SESSION['name']) ?></h2>
<div>

  <form method="POST">

    <label for="filter_course_id">Filter by Course:</label>
    <select name="filter_course_id" id="filter_course_id" required>
        <option value="">-- Select a Course --</option>
        <?php foreach ($teacherCourses as $course): ?>
            <option value="<?= htmlspecialchars($course['C_id']) ?>">
                <?= htmlspecialchars($course['course_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="show_attendance">Show Attendance</button>
</form>
</div>

    <?php if (isset($_POST['show_attendance'])): ?>
        <h3>Attendance Records</h3>
        <?php if (empty($attendanceData)) : ?>
            <p>No attendance records found for this teacher.</p>
        <?php else : ?>

    <?php
        //<?php
        // Step 1: Unique Dates
        $dates = [];
        foreach ($filteredAttendance as $date) {
            $dates[$date['date']] = true;
        }
        ksort($dates); // Sort chronologically

        // Step 2: Group by student & date
        $attendanceMap = [];
        foreach ($filteredAttendance as $singleAtten) {
            $sid = $singleAtten['student_id'];
            $date = $singleAtten['date'];
            $status = $singleAtten['status'];
            $attendanceMap[$sid][$date] = $status;
        }
    ?>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            <?php foreach (array_keys($dates) as $date): ?>
                <th><?= htmlspecialchars($date) ?></th>
            <?php endforeach; ?>
        </tr>
    <?php

        $CourseID = $_POST['filter_course_id'];
        $Enrolled_students = $objenrollmentController->getEnrollmentbyCourseID($CourseID);


        foreach ($Enrolled_students as $student): ?>
            <tr>
                <td><?= htmlspecialchars($student['student_id']) ?></td>
                <td><?= htmlspecialchars($student['student_name']) ?></td>
                <?php foreach (array_keys($dates) as $date): ?>
                    <td>
                        <?php
                            if (isset($attendanceMap[$student['student_id']][$date])) {
                                echo htmlspecialchars($attendanceMap[$student['student_id']][$date]);
                            } 
                            else {
                                echo '-';
                            }
                        ?>
                    </td>

                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>

        <?php endif; ?>
    <?php endif; ?>

    <form method="POST">
        <label for="course_id">Select a Course:</label>
        <select name="course_id" id="course_id" required>
            <option value="">-- Choose a Course --</option>
            <?php foreach ($teacherCourses as $course): ?>
                <option value="<?= htmlspecialchars($course['C_id']) ?>"
                    <?= ($selectedCourseId == $course['C_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($course['course_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <button name="View-Enrolled-Students" type="submit">Mark Attendance</button>
    </form>

    <?php if (!empty($Enrolled_students)) : ?>
        <h3>Mark Attendance for Course: <?= htmlspecialchars($selectedCourseId) ?> (<?= $date ?>)</h3>
        <form method="POST">
            <input type="hidden" name="course_id" value="<?= htmlspecialchars($selectedCourseId) ?>">
            <input type="hidden" name="date" value="<?= $date ?>">
            <table>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($Enrolled_students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['student_id']) ?></td>
                        <td><?= htmlspecialchars($student['student_name']) ?></td>
                        <td>
                            <select name="attendance[<?= $student['student_id'] ?>]">
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <button type="submit" name="submit_attendance" class="mark-button">Submit Attendance</button>
        </form>
    <?php elseif (isset($selectedCourseId)) : ?>
        <p>No students enrolled in the selected course.</p>
    <?php endif; ?>

</div>
</body>
</html>
