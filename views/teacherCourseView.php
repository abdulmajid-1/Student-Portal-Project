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
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
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
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        h3 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
            padding: 10px;
        }

        td {
            padding: 8px;
            text-align: center;
        }

        p {
            text-align: center;
            font-size: 16px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<!-- Header Bar -->
<div class="header-bar">
    <div><strong>My Courses</strong></div>
    <div>
        <a href="teacher-dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- Main Content -->
<div class="main">
    <?php
    if (empty($teacherCourses)) {
        echo "<p>No courses found for this teacher.</p>";
    } else {
        echo '<h3>Courses Assigned to You</h3>';
        echo '<table>
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
