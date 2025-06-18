<?php
session_start();
include_once '../config/db.php';
include_once '../controllers/studentController.php';

$objdatabaseconnection = new DatabaseConnectivity();
$objdatabaseconnection = $objdatabaseconnection->getConnection();
$objStudentController = new StudentController($objdatabaseconnection);

// Handle form submission
if (isset($_POST["action"])) {
    switch ($_POST["action"]) {
        case 'delete':
            $studentId = $_POST["S_id"];
            $isSuccess = $objStudentController->DeleteStudent($studentId);
            echo $isSuccess ? "Student deleted successfully!<br><br>" : "Error deleting student.<br><br>";
            break;

        case 'insert':

            $objStudentModel = new Student();
            $objStudentModel->setS_id($_POST["S_id"]);
            $objStudentModel->setUser_id($_POST["user_id"]);
            $objStudentModel->setRoll_no($_POST["roll_no"]);
            $objStudentModel->setDepartment($_POST["department"]);
            $objStudentModel->setYear($_POST["year"]);


            $isSuccess = $objStudentController->InsertStudent($objStudentModel);
            echo $isSuccess ? "Student added successfully!<br><br>" : "Error adding student.<br><br>";
            break;

        case 'getStudentById':
            $studentId = $_POST["id"];
            $studentData = $objStudentController->GetStudentById($studentId);
            if (empty($studentData)) {
                echo "No student found with ID: $studentId<br><br>";
            } else {
                echo "<h3>Student Details</h3>";
                echo "<p>ID: " . htmlspecialchars($studentData['S_id']) . "</p>";
                echo "<p>User ID: " . htmlspecialchars($studentData['user_id']) . "</p>";
                echo "<p>Name: " . htmlspecialchars($studentData['Student_name']) . "</p>";
                echo "<p>Roll No: " . htmlspecialchars($studentData['roll_no']) . "</p>";
                echo "<p>Department: " . htmlspecialchars($studentData['department']) . "</p>";
                echo "<p>Year: " . htmlspecialchars($studentData['year']) . "</p><br><br>";
            }
            break;

        case 'getAllStudents':
            echo 'Hello';
            $studentList = $objStudentController->GetAllStudents();
            if (empty($studentList)) {
                echo "<p>No students found.</p><br><br>";
            } 
            else {
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
            break;
    }
}
?>

<!-- HTML Below -->
<!DOCTYPE html>
<html>
<head>
    <title>Student Management</title>
    <script>
        function showFormFields() {
            const action = document.getElementById("action").value;
            document.getElementById("insertFields").style.display = action === "insert" ? "block" : "none";
            document.getElementById("deleteFields").style.display = action === "delete" ? "block" : "none";
            document.getElementById("getStudentByIdFields").style.display = action === "getStudentById" ? "block" : "none";
        }
    </script>
</head>
<body>

<h2>Student Management Portal</h2>

<!-- Unified Form -->
<form method="post">
    <label>Select Action:</label>
    <select name="action" id="action" required>
        <!-- <option value="">-- Select an action --</option> -->
        <option value="insert">Insert Student</option>
        <option value="delete">Delete Student</option>
        <option value="getStudentById">Get Student By ID</option>
        <option value="getAllStudents">Get All Students</option>
    </select>
    <button type="submit" name="submit">Submit</button>
    
    <br><br>

    <!-- Insert Fields -->
    <div id="insertFields" style="display: none;">
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
   
    </div>

    <!-- Delete Fields -->
    <div id="deleteFields" style="display: none;">
        <label>Enter Student ID to Delete:</label>
        <input type="number" name="S_id"><br><br>
    </div>

    <!-- Get Student by ID Fields -->
    <div id="getStudentByIdFields" style="display: none;">
        <label>Enter Student ID to View:</label>
        <input type="number" name="S_id"><br><br>
    </div>

    <!-- <button type="submit">Submit</button> -->
</form>

</body>
</html>
