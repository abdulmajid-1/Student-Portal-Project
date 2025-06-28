<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View My Courses</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="main">
    <h2>Welcome to Student Dashboard</h2>

    <form method="POST" action="">
        <button type="submit" name="view">View My Courses</button>
    </form>

    <div id="course-table">
        <?php

        include_once '../controllers/studentController.php';
        include_once '../config/db.php';
        session_start();
        if (!isset($_SESSION["user_id"])) {
            header("Location: login.php");
            exit;
        }

        $objdatabaseconnection = new DatabaseConnectivity();
        $objdatabaseconnection = $objdatabaseconnection->getConnection();
        $objStudentController = new StudentController($objdatabaseconnection);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['view'])) {
                if (!isset($_SESSION["user_id"])) {
                    echo "<p style='color:red;'>You are not logged in.</p>";
                } else {
                    $courses = $objStudentController->viewMyCourses($_SESSION["user_id"]);
                    if (empty($courses)) {
                        echo "<p>No courses found.</p>";
                    } else {
                        echo '<h3>My Courses</h3>';
                        echo '<table border="1" cellpadding="5">
                                <tr>
                                    <th>Course ID</th>
                                    <th>Course Name</th>
                                    <th>course code</th>
                                    <th>department</th>
                                </tr>';
                        foreach ($courses as $course) {
                            echo '<tr>
                                    <td>' . htmlspecialchars($course['C_id']) . '</td>
                                    <td>' . htmlspecialchars($course['name']) . '</td>
                                    <td>' . htmlspecialchars($course['code']) . '</td>
                                    <td>' . htmlspecialchars($course['department']) . '</td>
                                  </tr>';
                        }
                        echo '</table>';
                    }
                }
            }
        }
        ?>
    </div>
</div>
</body>
</html>











