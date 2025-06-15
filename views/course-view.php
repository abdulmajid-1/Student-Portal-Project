<?php
include_once '../config/db.php';
include_once '../controllers/courseController.php';
session_start();

$objdatabaseconnection = new DatabaseConnectivity();
$objCourseController = new CourseController($objdatabaseconnection->getConnection());

$courseList = $objCourseController->GetAllCourses();

if (isset($_POST["action"])) {
    switch ($_POST["action"]) {
        case 'add':
            $objCourseModel = new Course();
            $objCourseModel->setCourseCode($_POST["code"]);
            $objCourseModel->setCourseId($_POST["id"]);
            $objCourseModel->setCourseDepartment($_POST["department"]);
            $objCourseModel->setCourseName($_POST["name"]);

            $isSuccess = $objCourseController->InsertCourse($objCourseModel);
            if ($isSuccess) {
                echo "Course added successfully!";
            } else {
                echo "Error adding course.";
            }
            break;

        case 'delete':
            $courseId = $_POST["id"];
            $isSuccess = $objCourseController->DeleteCourse($courseId);
            if ($isSuccess) {
                echo "Course deleted successfully!";

            } else {
                echo "Error deleting course.";
            }
            break;
    }
}


function displayCourses($controller) {
    $courseList = $controller->GetAllCourses();
    if (empty($courseList)) {
        echo "<p>No courses found.</p>";
        return;
    }

    echo '<table border="1" cellpadding="5">
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Department</th>
                <th>Name</th>
            </tr>';

    foreach ($courseList as $course) {
        echo "<tr>
                <td>" . htmlspecialchars($course['C_id']) . "</td>
                <td>" . htmlspecialchars($course['code']) . "</td>
                <td>" . htmlspecialchars($course['department']) . "</td>
                <td>" . htmlspecialchars($course['name']) . "</td>
              </tr>";
    }
    echo "</table>";
}
?>

<!-- HTML Starts Here -->
<!DOCTYPE html>
<html>
<head>
    <title>Course Management</title>
    <script>
        function toggleFields() {
            const action = document.getElementById("action").value;
            document.getElementById("addFields").style.display = action === "add" ? "block" : "none";
            document.getElementById("deleteField").style.display = action === "delete" ? "block" : "none";
        }
    </script>
</head>
<body>

    <h2>Manage Course</h2>
    <form method="post">
        <label>Choose Action:</label><br>
        <select name="action" id="action" onchange="toggleFields()" required>
            <option value="">--Select--</option>
            <option value="add">Add Course</option>
            <option value="delete">Delete Course</option>
        </select><br><br>

        <div id="addFields" style="display:none;">
            <label>Course ID:</label><br>
            <input type="number" name="id"><br>

            <label>Code:</label><br>
            <input type="text" name="code"><br>

            <label>Department:</label><br>
            <input type="text" name="department"><br>

            <label>Name:</label><br>
            <input type="text" name="name"><br><br>
        </div>

        <div id="deleteField" style="display:none;">
            <label>Course ID to Delete:</label><br>
            <input type="number" name="id"><br><br>
        </div>

        <input type="submit" value="Submit" id="submitButton">
    </form>

    <hr>

    
    <?php displayCourses($objCourseController); ?>

</body>
</html>
