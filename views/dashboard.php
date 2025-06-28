<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
include_once '../config/db.php';
include_once '../controllers/studentController.php';

$objdatabaseconnection = new DatabaseConnectivity();
$connection = $objdatabaseconnection->getConnection();
$objStudentController = new StudentController($connection);

// In a real application, you would fetch this data from your database
// $student_info = [
//     'roll_no' => 'ST2023001',
//     'department' => 'Computer Science',
//     'year' => '2023',
//     'email' => $_SESSION["email"] ?? 'student@university.edu',
//     'courses_enrolled' => 5,
//     'attendance_percentage' => 92.5,
//     'cgpa' => 3.7
// ];

$student_info = $objStudentController->GetStudentDataDashboard($_SESSION["user_id"]);
if(!$student_info){
     $student_info = [
        'roll_no' => 'N/A',
        'department' => 'N/A',
        'year' => 'N/A'
     ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* NAVBAR GRID STYLING */
        .navbar {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            background-color: #004080;
            color: white;
            padding: 10px 20px;
        }

        .navbar .brand {
            font-size: 1.5rem;
        }

        .navbar ul {
            display: grid;
            grid-auto-flow: column;
            gap: 20px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .account-menu {
            position: relative;
        }

        .account-dropdown {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            color: black;
            border: 1px solid #ccc;
            padding: 10px;
            min-width: 150px;
            z-index: 1;
        }

        .account-dropdown a {
            display: block;
            color: black;
            padding: 5px 0;
            text-decoration: none;
        }

        .account-dropdown a:hover {
            background-color: #f0f0f0;
        }

        .account-menu:hover .account-dropdown {
            display: block;
        }

        /* MAIN BODY */
        .main {
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .dashboard-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .student-info-form {
            grid-column: span 2;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .action-btn {
            background-color: #004080;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .action-btn:hover {
            background-color: #003366;
        }

        .stats-card {
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #004080;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="brand">Student Portal</div>
    <ul>
        <li><a href="studentCourseView.php">My Courses</a></li>
        <li><a href="Student-attendance-view.php">Attendance</a></li>
        <li><a href="academic-Calendar.html">Academic Calendar</a></li>
        <li class="account-menu">
            <a href="#">Account ▾</a>
            <div class="account-dropdown">
                <a href="#">Welcome, <?= htmlspecialchars($_SESSION["name"]) ?></a>
                <a href="ChangePassword-Student.php">Change Password</a>
                <a href="logout.php">Logout</a>
            </div>
        </li>
    </ul>
</nav>

<!-- MAIN CONTENT -->
<div class="main">
    <div class="dashboard-card student-info-form">
        <h2>Student Information</h2>
        <form class="info-grid">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" value="<?= htmlspecialchars($_SESSION["name"]) ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="rollno">Roll Number</label>
                <input type="text" id="rollno" value="<?= htmlspecialchars($student_info['roll_no']) ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" id="department" value="<?= htmlspecialchars($student_info['department']) ?>" readonly>
            </div>
            
        </form>
    </div>


</div>

</body>
</html>