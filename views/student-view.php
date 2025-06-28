<?php
session_start();
include_once '../config/db.php';
include_once '../controllers/studentController.php';
include_once '../controllers/userController.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'admin') {
    header("Location: login.php");
    exit;
}

$objdatabaseconnection = new DatabaseConnectivity();
$connection = $objdatabaseconnection->getConnection();

$objStudentController = new StudentController($connection);
$objUserController = new UserController($connection); // For fetching user names

$studentData = [];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'insert':
            $student = new Student();
            $student->setS_id($_POST["S_id"]);
            $student->setUser_id($_POST["user_id"]);
            $student->setRoll_no($_POST["roll_no"]);
            $student->setDepartment($_POST["department"]);
            $student->setYear($_POST["year"]);
            $objStudentController->InsertStudent($student);
            $message = "Student inserted successfully.";
            break;

        case 'delete':
            $studentId = $_POST["delete_id"];
            $objStudentController->DeleteStudent($studentId);
            $message = "Student deleted.";
            break;

        case 'get_by_id':
            $studentId = $_POST["view_id"];
            $studentData = $objStudentController->GetStudentById($studentId);
            break;

        case 'get_all':
            $studentData = $objStudentController->GetAllStudents();
            break;
    }
}

// Fetch all users for the dropdown
$userList = $objUserController->getAllUsersNames(); // Must return id and name
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Management</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="main">
    <h2>Student Management</h2>

    <?php if (!empty($message)) echo "<p><strong>$message</strong></p>"; ?>

    <!-- Operation Selector -->
    <form method="post">
        <label>Select Operation:</label>
        <select name="action" onchange="toggleForms(this.value)" required>
            <option value="">--Choose Action--</option>
            <option value="insert">Insert Student</option>
            <option value="delete">Delete Student</option>
            <option value="get_by_id">View Student By ID</option>
            <option value="get_all">View All Students</option>
        </select><br><br>

        <!-- Insert Student Form -->
        <div id="insert_form" style="display:none;">
            <label>Student ID:</label>
            <input type="number" name="S_id"><br><br>

            <label>Select User:</label>
            <select name="user_id">
                <?php foreach ($userList as $user): ?>
                    <option value="<?= $user['U_id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Roll No:</label>
            <input type="text" name="roll_no"><br><br>

            <label>Department:</label>
            <input type="text" name="department"><br><br>

            <label>Year:</label>
            <input type="text" name="year"><br><br>
        </div>

        <!-- Delete Form -->
        <div id="delete_form" style="display:none;">
            <label>Enter Student ID to Delete:</label>
            <input type="number" name="delete_id"><br><br>
        </div>

        <!-- View by ID Form -->
        <div id="get_by_id_form" style="display:none;">
            <label>Enter Student ID to View:</label>
            <input type="number" name="view_id"><br><br>
        </div>

        <!-- Submit -->
        <button type="submit">Submit</button>
    </form>

    <!-- Display Result -->
    <?php if (!empty($studentData)): ?>
        <hr>
        <h3>Student Record(s)</h3>
        <table border="1" cellpadding="5">
            <tr>
                <th>S_ID</th>
                <th>User_ID</th>
                <th>Student Name</th>
                <th>Roll No</th>
                <th>Department</th>
                <th>Year</th>
            </tr>
            <?php if (isset($studentData['S_id'])): ?>
                <tr>
                    <td><?= htmlspecialchars($studentData['S_id']) ?></td>
                    <td><?= htmlspecialchars($studentData['user_id']) ?></td>
                    <td><?= htmlspecialchars($studentData['Student_name']) ?></td>
                    <td><?= htmlspecialchars($studentData['roll_no']) ?></td>
                    <td><?= htmlspecialchars($studentData['department']) ?></td>
                    <td><?= htmlspecialchars($studentData['year']) ?></td>
                </tr>
            <?php else: foreach ($studentData as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['S_id']) ?></td>
                    <td><?= htmlspecialchars($student['user_id']) ?></td>
                    <td><?= htmlspecialchars($student['Student_name']) ?></td>
                    <td><?= htmlspecialchars($student['roll_no']) ?></td>
                    <td><?= htmlspecialchars($student['department']) ?></td>
                    <td><?= htmlspecialchars($student['year']) ?></td>
                </tr>
            <?php endforeach; endif; ?>
        </table>
    <?php endif; ?>
</div>

<script>
    function toggleForms(action) {
        document.getElementById('insert_form').style.display = action === 'insert' ? 'block' : 'none';
        document.getElementById('delete_form').style.display = action === 'delete' ? 'block' : 'none';
        document.getElementById('get_by_id_form').style.display = action === 'get_by_id' ? 'block' : 'none';
    }
</script>
</body>
</html>
