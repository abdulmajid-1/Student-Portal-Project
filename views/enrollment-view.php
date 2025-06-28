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
$selectedAction = $_POST["action"] ?? '';

if ($selectedAction) {
    switch ($selectedAction) {
        case 'enroll':
            $objEnrollmentModel = new EnrollmentModel();
            $objEnrollmentModel->setStudentId($_POST["student_id"]);
            $objEnrollmentModel->setCourseId($_POST["course_id"]);

            $isSuccess = $objEnrollmentController->enrollStudent($objEnrollmentModel);
            echo $isSuccess ? "<p>✅ Student enrolled successfully!</p>" : "<p>❌ Error enrolling student.</p>";
            break;

        case 'delete':
            $enrollmentId = $_POST["enrollment_id"];
            $objEnrollmentController->deleteEnrollment($enrollmentId);
            echo "<p>🗑️ Enrollment deleted (if existed).</p>";
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
    <style>
        .header-bar {
            background-color: #007BFF;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-bar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }
        .header-bar a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function showForm() {
            const selected = document.getElementById("actionSelector").value;
            document.getElementById("enrollForm").style.display = selected === "enroll" ? "block" : "none";
            document.getElementById("deleteForm").style.display = selected === "delete" ? "block" : "none";
            document.getElementById("viewForm").style.display = selected === "view" ? "block" : "none";
            document.getElementById("viewAllForm").style.display = selected === "view_all" ? "block" : "none";
        }

        window.onload = function () {
            const selectedAction = "<?= $selectedAction ?>";
            if (selectedAction !== "") {
                document.getElementById("actionSelector").value = selectedAction;
                showForm();
            }
        };
    </script>
</head>
<body>

<!-- Header -->
<div class="header-bar">
    <div><strong>Enrollment Management Panel</strong></div>
    <div>
        <a href="admin-dashboard.php">⬅ Back to Dashboard</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
</div>

<!-- Main Content -->
<div class="main" style="padding: 20px; max-width: 900px; margin: auto;">
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
        <h3>Enroll a Student</h3>
        <form method="post">
            <input type="hidden" name="action" value="enroll">

            <label>Select Student:</label><br>
            <select name="student_id" required>
                <option value="">-- Select Student --</option>
                <?php foreach ($studentList as $student): ?>
                    <option value="<?= $student['S_id'] ?>">
                        <?= htmlspecialchars($student['student_name']) ?> (ID: <?= $student['S_id'] ?>)
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Select Course:</label><br>
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
        <h3>Delete an Enrollment</h3>
        <form method="post">
            <input type="hidden" name="action" value="delete">
            <label>Enrollment ID:</label>
            <input type="number" name="enrollment_id" required><br><br>
            <input type="submit" value="Delete Enrollment">
        </form>
    </div>

    <!-- View Enrollments by Student -->
    <div id="viewForm" style="display:none;">
        <h3>View Enrollments by Student</h3>
        <form method="post">
            <input type="hidden" name="action" value="view">
            <label>Select Student:</label><br>
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
        <h3>View All Enrollments</h3>
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
