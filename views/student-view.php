<?php
session_start();
include_once '../config/db.php';
include_once '../controllers/studentController.php';

$objdatabaseconnection = new DatabaseConnectivity();
$objdatabaseconnection = $objdatabaseconnection->getConnection();
$objStudentController = new StudentController($objdatabaseconnection);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['insert'])) {
        $objStudentModel = new Student();
        $objStudentModel->setS_id($_POST["S_id"]);
        $objStudentModel->setUser_id($_POST["user_id"]);
        $objStudentModel->setRoll_no($_POST["roll_no"]);
        $objStudentModel->setDepartment($_POST["department"]);
        $objStudentModel->setYear($_POST["year"]);

        $isSuccess = $objStudentController->InsertStudent($objStudentModel);
        echo $isSuccess ? "Student added successfully!<br><br>" : "Error adding student.<br><br>";
    }

    if (isset($_POST['delete'])) {
        $studentId = $_POST["delete_id"];
        $isSuccess = $objStudentController->DeleteStudent($studentId);
        echo $isSuccess ? "Student deleted successfully!<br><br>" : "Error deleting student.<br><br>";
    }

    if (isset($_POST['get_by_id'])) {
        $studentId = $_POST["view_id"];
        $studentData = $objStudentController->GetStudentById($studentId);
        if (empty($studentData)) {
            echo " No student found with ID: $studentId<br><br>";
        } else {
            echo "<h3>Student Details</h3>";
            echo "<p>ID: " . htmlspecialchars($studentData['S_id']) . "</p>";
            echo "<p>User ID: " . htmlspecialchars($studentData['user_id']) . "</p>";
            echo "<p>Name: " . htmlspecialchars($studentData['Student_name']) . "</p>";
            echo "<p>Roll No: " . htmlspecialchars($studentData['roll_no']) . "</p>";
            echo "<p>Department: " . htmlspecialchars($studentData['department']) . "</p>";
            echo "<p>Year: " . htmlspecialchars($studentData['year']) . "</p><br><br>";
        }
    }

    if (isset($_POST['get_all'])) {
        $studentList = $objStudentController->GetAllStudents();
        if (empty($studentList)) {
            echo "<p>No students found.</p><br><br>";
        } 
        else {
            echo '<h3>All Students</h3>';
            echo '<table border="1" cellpadding="5">
                    <tr>
                        <th>S_ID</th>
                        <th>User_ID</th>
                        <th>Student Name</th>
                        <th>Roll No</th>
                        <th>Department</th>
                        <th>Year</th>
                    </tr>';
            foreach ($studentList as $student) {
                echo "<tr>
                        <td>" . htmlspecialchars($student['S_id']) . "</td>
                        <td>" . htmlspecialchars($student['user_id']) . "</td>
                        <td>" . htmlspecialchars($student['Student_name']) . "</td>
                        <td>" . htmlspecialchars($student['roll_no']) . "</td>
                        <td>" . htmlspecialchars($student['department']) . "</td>
                        <td>" . htmlspecialchars($student['year']) . "</td>
                      </tr>";
            }
            echo '</table><br><br>';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management Portal</title>
</head>
<body>

<h2>Student Management Portal</h2>

<!-- Insert Student Form -->
<h3>Insert Student</h3>
<form method="post">
    <label>Student ID:</label>
    <input type="number" name="S_id" required><br><br>

    <label>User ID:</label>
    <input type="number" name="user_id" required><br><br>

    <label>Roll No:</label>
    <input type="text" name="roll_no" required><br><br>

    <label>Department:</label>
    <input type="text" name="department" required><br><br>

    <label>Year:</label>
    <input type="text" name="year" required><br><br>

    <button type="submit" name="insert">Insert Student</button>
</form>
<hr>

<!-- Delete Student Form -->
<h3>Delete Student</h3>
<form method="post">
    <label>Enter Student ID:</label>
    <input type="number" name="delete_id" required><br><br>
    <button type="submit" name="delete">Delete Student</button>
</form>
<hr>

<!-- View Student By ID Form -->
<h3>View Student By ID</h3>
<form method="post">
    <label>Enter Student ID:</label>
    <input type="number" name="view_id" required><br><br>
    <button type="submit" name="get_by_id">Get Student</button>
</form>
<hr>

<!-- Get All Students -->
<h3>View All Students</h3>
<form method="post">
    <button type="submit" name="get_all">Get All Students</button>
</form>

</body>
</html>
