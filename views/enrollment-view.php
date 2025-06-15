<?php
include_once '../models/enrollmentModel.php';
include_once '../controllers/enrollmentController.php'; 
include_once '../config/db.php'; // Include your database connection file
session_start();

$objDatabaseConnection = new DatabaseConnectivity();
$objEnrollmentController = new EnrollmentController($objDatabaseConnection->getConnection());

$enrollmentList = $objEnrollmentController->getAllEnrollments();
if (isset($_POST["action"])) {
    switch ($_POST["action"]) {
        case 'enroll':
            $objEnrollmentModel = new EnrollmentModel();
            $objEnrollmentModel->setStudentId($_POST["student_id"]);
            $objEnrollmentModel->setCourseId($_POST["course_id"]);

            $isSuccess = $objEnrollmentController->enrollStudent($objEnrollmentModel);
            if ($isSuccess) {
                echo "Student enrolled successfully!";
            } else {
                echo "Error enrolling student.";
            }
            break;

        case 'delete':
            $enrollmentId = $_POST["enrollment_id"];
            $isSuccess = $objEnrollmentController->deleteEnrollment($enrollmentId);
            if ($isSuccess) {
                echo "Enrollment deleted successfully!";
            } else {
                echo "Error deleting enrollment.";
            }
            break;

        case 'view':
            $studentId = $_POST["student_id"];
            $enrollmentList = $objEnrollmentController->getEnrollmentsByStudent($studentId);
            if (empty($enrollmentList)) {
                echo "<p>No enrollments found for this student.</p>";
            } 
            else {
                echo '<table border="1" cellpadding="5">
                <tr>
                    <th>Enrollment ID</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Course ID</th>
                    <th>Course Name</th>
                </tr>';
                foreach ($enrollmentList as $enrollment) {
                echo "<tr>
                <td>" . htmlspecialchars($enrollment['enrollment_id']) . "</td>
                <td>" . htmlspecialchars($enrollment['student_id']) . "</td>
                <td>" . htmlspecialchars($enrollment['student_name']) . "</td>
                <td>" . htmlspecialchars($enrollment['course_id']) . "</td>
                <td>" . htmlspecialchars($enrollment['course_name']) . "</td>
                </tr>";
                }
                echo '</table>';
            }

        
            break;

            case 'view_all':
            $enrollmentList = $objEnrollmentController->getAllEnrollments();   
            if (empty($enrollmentList)) {
                echo "<p>No enrollments found.</p>";
            } 
            else {
                echo '<table border="1" cellpadding="5">
                        <tr>
                            <th>Enrollment ID</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Course ID</th>
                            <th>Course Name</th>
                        </tr>';
                foreach ($enrollmentList as $enrollment) {
                  echo "<tr>
                        <td>" . htmlspecialchars($enrollment['enrollment_id']) . "</td>
                        <td>" . htmlspecialchars($enrollment['student_id']) . "</td>
                        <td>" . htmlspecialchars($enrollment['student_name']) . "</td>
                        <td>" . htmlspecialchars($enrollment['course_id']) . "</td>
                        <td>" . htmlspecialchars($enrollment['course_name']) . "</td>
                    </tr>";

                }
                echo '</table>';
                
            }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Enrollments</title>
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
    <h1>Enrollment Management</h1>

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
        <form method="post" action="">
            <input type="hidden" name="action" value="enroll">
            <label for="student_id">Student ID:</label>
            <input type="number" name="student_id" required><br><br>

            <label for="course_id">Course ID:</label>
            <input type="number" name="course_id" required><br><br>

            <input type="submit" value="Enroll Student">
        </form>
    </div>

    <!-- Delete Enrollment Form -->
    <div id="deleteForm" style="display:none;">
        <h2>Delete an Enrollment</h2>
        <form method="post" action="">
            <input type="hidden" name="action" value="delete">
            <label for="enrollment_id">Enrollment ID:</label>
            <input type="number" name="enrollment_id" required><br><br>

            <input type="submit" value="Delete Enrollment">
        </form>
    </div>

    <!-- View Student Enrollments -->
    <div id="viewForm" style="display:none;">
        <h2>View Enrollments by Student ID</h2>
        <form method="post" action="">
            <input type="hidden" name="action" value="view">
            <label for="student_id">Student ID:</label>
            <input type="number" name="student_id" required><br><br>

            <input type="submit" value="View Enrollments">
        </form>
    </div>

    <!-- View All Enrollments -->
    <div id="viewAllForm" style="display:none;">
        <h2>View All Enrollments</h2>
        <form method="post" action="">
            <input type="hidden" name="action" value="view_all">
            <input type="submit" value="View All Enrollments">
        </form>
    </div>
</body>
</html>
