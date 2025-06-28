<?php
include_once '../config/db.php';
include_once '../controllers/enrollmentController.php'; 
include_once '../controllers/courseController.php';
include_once '../controllers/studentController.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'admin') {
    header("Location: login.php");
    exit;
}

$objDatabaseConnection = new DatabaseConnectivity();
$connection = $objDatabaseConnection->getConnection();

$objEnrollmentController = new EnrollmentController($connection);
$objCourseController = new CourseController($connection);
$objStudentController = new StudentController($connection);

$courseList = $objCourseController->GetAllCoursesName(); // must return C_id and name
$studentList = $objStudentController->GetAllStudentsName(); // must return S_id and name

$enrollmentList = [];

if (isset($_POST["action"])) {
    switch ($_POST["action"]) {
        case 'enroll':
            $objEnrollmentModel = new EnrollmentModel();
            $objEnrollmentModel->setStudentId($_POST["student_id"]);
            $objEnrollmentModel->setCourseId($_POST["course_id"]);

            $isSuccess = $objEnrollmentController->enrollStudent($objEnrollmentModel);
            echo $isSuccess ? "✅ Student enrolled successfully!" : "❌ Error enrolling student.";
            break;

        case 'delete':
            $enrollmentId = $_POST["enrollment_id"];
            $objEnrollmentController->deleteEnrollment($enrollmentId);
            echo "🗑️ Enrollment deleted (if existed).";
            break;

        case 'view':
            $studentId = $_POST["student_id"];
            $enrollmentList = $objEnrollmentController->getEnrollmentsByStudent($studentId);
            break;

        case 'view_all':
            $enrollmentList = $objEnrollmentController->getAllEnrollments();
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enrollment Management</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script>
        function showForm() {
            const selected = document.getElementById("actionSelector").value;
            document.getElementById("enrollForm").style.display = selected === "enroll" ? "block" : "none";
            document.getElementById("deleteForm").style.display = selected === "delete" ? "block" : "none";
            document.getElementById("viewForm").style.display = selected === "view" ? "block" : "none";
            document.getElementById("viewAllForm").style.display = selected === "view_all" ? "block" : "none";
        }
    </script>
</head>
<body>
<div class="main">
    <h2>Enrollment Management</h2>

    <!-- Dropdown Menu -->
    <label for="actionSelector"><strong>Select an action:</strong></label>
    <select id="actionSelector" onchange="showForm()">
        <option value="">-- Select Action --</option>
        <option value="enroll">Enroll Student</option>
        <option value="delete">Delete Enrollment</option>
        <option value="view">View Enrollments by Student</option>
        <option value="view_all">View All Enrollments</option>
    </select>

    <hr>

    <!-- Enroll Student Form -->
    <div id="enrollForm" style="display:none;">
        <h2>Enroll a Student</h2>
        <form method="post">
            <input type="hidden" name="action" value="enroll">

            <label for="student_id">Select Student:</label><br>
            <select name="student_id" required>
                <option value="">-- Select Student --</option>
                <?php foreach ($studentList as $student): ?>
                    <option value="<?= $student['S_id'] ?>">
                        <?= htmlspecialchars($student['student_name']) ?> (ID: <?= $student['S_id'] ?>)
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="course_id">Select Course:</label><br>
            <select name="course_id" required>
                <option value="">-- Select Course --</option>
                <?php foreach ($courseList as $course): ?>
                    <option value="<?= $course['C_id'] ?>">
                        <?= htmlspecialchars($course['name']) ?> (ID: <?= $course['C_id'] ?>)
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <input type="submit" value="Enroll Student">
        </form>
    </div>

    <!-- Delete Enrollment Form -->
    <div id="deleteForm" style="display:none;">
        <h2>Delete an Enrollment</h2>
        <form method="post">
            <input type="hidden" name="action" value="delete">
            <label for="enrollment_id">Enrollment ID:</label>
            <input type="number" name="enrollment_id" required><br><br>
            <input type="submit" value="Delete Enrollment">
        </form>
    </div>

    <!-- View Enrollments by Student -->
    <div id="viewForm" style="display:none;">
        <h2>View Enrollments by Student</h2>
        <form method="post">
            <input type="hidden" name="action" value="view">
            <label for="student_id">Select Student:</label><br>
            <select name="student_id" required>
                <option value="">-- Select Student --</option>
                <?php foreach ($studentList as $student): ?>
                    <option value="<?= $student['S_id'] ?>">
                        <?= htmlspecialchars($student['student_name']) ?> (ID: <?= $student['S_id'] ?>)
                    </option>
                <?php endforeach; ?>
            </select><br><br>
            <input type="submit" value="View Enrollments">
        </form>
    </div>

    <!-- View All Enrollments -->
    <div id="viewAllForm" style="display:none;">
        <h2>View All Enrollments</h2>
        <form method="post">
            <input type="hidden" name="action" value="view_all">
            <input type="submit" value="View All Enrollments">
        </form>
    </div>

    <hr>

    <!-- Display Enrollments Table -->
    <?php if (!empty($enrollmentList)): ?>
        <h3>Enrollment Records</h3>
        <table border="1" cellpadding="5">
            <tr>
                <th>Enrollment ID</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Course ID</th>
                <th>Course Name</th>
            </tr>
            <?php foreach ($enrollmentList as $enrollment): ?>
                <tr>
                    <td><?= htmlspecialchars($enrollment['enrollment_id']) ?></td>
                    <td><?= htmlspecialchars($enrollment['student_id']) ?></td>
                    <td><?= htmlspecialchars($enrollment['student_name']) ?></td>
                    <td><?= htmlspecialchars($enrollment['course_id']) ?></td>
                    <td><?= htmlspecialchars($enrollment['course_name']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</div>
</body>
</html>
