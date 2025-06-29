<?php
include_once '../config/db.php';
include_once '../controllers/courseController.php';
include_once '../controllers/teacherController.php';
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'admin') {
    header("Location: login.php");
    exit;
}

$objdatabaseconnection = new DatabaseConnectivity();
$connection = $objdatabaseconnection->getConnection();
$objCourseController = new CourseController($connection);
$objteacherController = new TeacherController($connection);

$courseList = $objCourseController->GetAllCourses();
$teacherList = $objteacherController->GetAllTeachersNames();
$viewCourseList = [];

if (isset($_POST["action"])) {
    switch ($_POST["action"]) {
        case 'add':
            $objCourseModel = new Course();
            $objCourseModel->setCourseCode($_POST["code"]);
            $objCourseModel->setCourseDepartment($_POST["department"]);
            $objCourseModel->setCourseName($_POST["name"]);
            $objCourseModel->setTeacherId($_POST["teacher_id"]);

            $isSuccess = $objCourseController->InsertCourse($objCourseModel);
            echo $isSuccess ? "Course added successfully!" : "Error adding course.";
            break;

        case 'delete':
            $courseId = $_POST["id"];
            $objCourseController->DeleteCourse($courseId);
            echo "Course deleted (if existed).";
            break;

        case 'view':
            $viewCourseList = $objCourseController->GetAllCourses();
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Course Management</title>
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
    </style>
    <script>
        function toggleFields() {
            const action = document.getElementById("action").value;
            document.getElementById("addFields").style.display = (action === "add") ? "block" : "none";
            document.getElementById("deleteField").style.display = (action === "delete") ? "block" : "none";
        }
    </script>
</head>

<body>

    <!-- Header -->
    <div class="header-bar">
        <div><strong>Course Management Panel</strong></div>
        <div>
            <a href="admin-dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="main">
        <h2>Course Management</h2>

        <form method="post">
            <label>Choose Action:</label><br>
            <select name="action" id="action" onchange="toggleFields()" required>
                <option value="">--Select--</option>
                <option value="add">Add Course</option>
                <option value="delete">Delete Course</option>
                <option value="view">View All Courses</option>
            </select><br><br>

            <!-- Add Course Fields -->
            <div id="addFields" style="display:none;">
                <label>Code:</label><br>
                <input type="text" name="code"><br>

                <label>Department:</label><br>
                <input type="text" name="department"><br>

                <label>Name:</label><br>
                <input type="text" name="name"><br>

                <label>Select Teacher:</label><br>
                <select name="teacher_id">
                    <?php foreach ($teacherList as $teacher): ?>
                        <option value="<?= $teacher['T_id'] ?>">
                            <?= htmlspecialchars($teacher['name']) ?> (ID: <?= $teacher['T_id'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select><br><br>
            </div>

            <!-- Delete Course Fields -->
            <div id="deleteField" style="display:none;">
                <label>Course ID to Delete:</label><br>
                <input type="number" name="id"><br><br>
            </div>

            <input type="submit" value="Submit">
        </form>

        <!-- View Courses Table -->
        <?php if (!empty($viewCourseList)): ?>
            <hr>
            <h3>All Courses</h3>
            <table border="1" cellpadding="5">
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Department</th>
                    <th>Name</th>
                    <th>Teacher Name</th>
                </tr>
                <?php foreach ($viewCourseList as $course): ?>
                    <tr>
                        <td><?= htmlspecialchars($course['C_id']) ?></td>
                        <td><?= htmlspecialchars($course['code']) ?></td>
                        <td><?= htmlspecialchars($course['department']) ?></td>
                        <td><?= htmlspecialchars($course['name']) ?></td>
                        <td><?= htmlspecialchars($course['teacher_name']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>