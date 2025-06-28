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
$teacherId = $objTeacherController->GetTeacherID($userId);
$teacherCourses = $objTeacherController->GetTeacherCourses($userId);

$operation = $_POST['operation'] ?? null;
$selectedCourseId = $_POST['filter_course_id'] ?? null;
$filteredAttendance = [];
$Enrolled_students = [];

// Handle Attendance Marking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_attendance']) && isset($_POST['attendance'])) {
    $courseId = $_POST['course_id'];
    foreach ($_POST['attendance'] as $studentId => $status) {
        $objTeacherController->markAttendance($studentId, $courseId, $teacherId, $date, $status);
    }
    echo "<p>✅ Attendance marked successfully.</p>";
}

// Handle Operation Selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operation_submit'])) {
    if ($operation === 'show_attendance' && $selectedCourseId) {
        $filteredAttendance = $objTeacherController->GetAttendanceByCourse($teacherId, $selectedCourseId);
        $Enrolled_students = $objenrollmentController->getEnrollmentbyCourseID($selectedCourseId);
    } elseif ($operation === 'mark_attendance' && $selectedCourseId) {
        $Enrolled_students = $objenrollmentController->getEnrollmentbyCourseID($selectedCourseId);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Attendance Management</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

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
            max-width: 1000px;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th,
        td {
            border: 1px solid #aaa;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #efefef;
        }

        .mark-button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            margin-top: 15px;
            border: none;
            cursor: pointer;
        }

        .mark-button:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>

    <div class="header-bar">
        <div><strong>Attendance Management</strong></div>
        <div>
            <a href="teacher-dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="main">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></h2>

        <!-- Operation and Course Selection Form -->
        <form method="POST">
            <label for="operation">Select Operation:</label>
            <select name="operation" id="operation" required>
                <option value="">-- Choose Operation --</option>
                <option value="show_attendance" <?= ($operation === 'show_attendance') ? 'selected' : '' ?>>Show Attendance</option>
                <option value="mark_attendance" <?= ($operation === 'mark_attendance') ? 'selected' : '' ?>>Mark Attendance</option>
            </select>

            <label for="filter_course_id" style="margin-left: 20px;">Select Course:</label>
            <select name="filter_course_id" id="filter_course_id" required>
                <option value="">-- Choose a Course --</option>
                <?php foreach ($teacherCourses as $course): ?>
                    <option value="<?= htmlspecialchars($course['C_id']) ?>" <?= ($selectedCourseId == $course['C_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($course['course_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="operation_submit">Proceed</button>
        </form>

        <!-- Show Attendance Records -->
        <?php if ($operation === 'show_attendance' && !empty($filteredAttendance)): ?>
            <h3>Attendance Records</h3>
            <?php
            // Extract unique dates
            $dates = [];
            foreach ($filteredAttendance as $record) {
                $dates[$record['date']] = true;
            }
            ksort($dates); // sort by date

            // Map student_id => [date => status]
            $attendanceMap = [];
            foreach ($filteredAttendance as $entry) {
                $sid = $entry['student_id'];
                $d = $entry['date'];
                $attendanceMap[$sid][$d] = $entry['status'];
            }
            ?>
            <table>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <?php foreach (array_keys($dates) as $d): ?>
                        <th><?= htmlspecialchars($d) ?></th>
                    <?php endforeach; ?>
                </tr>
                <?php foreach ($Enrolled_students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['student_id']) ?></td>
                        <td><?= htmlspecialchars($student['student_name']) ?></td>
                        <?php foreach (array_keys($dates) as $d): ?>
                            <td><?= htmlspecialchars($attendanceMap[$student['student_id']][$d] ?? '-') ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <!-- Mark Attendance Form -->
        <?php if ($operation === 'mark_attendance' && !empty($Enrolled_students)): ?>
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
        <?php endif; ?>
    </div>

</body>

</html>