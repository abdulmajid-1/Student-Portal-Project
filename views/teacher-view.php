<?php
include_once '../config/db.php';
include_once '../controllers/teacherController.php';
session_start();

// Redirect to login if not logged in or not admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Initialize controller
$db = new DatabaseConnectivity();
$connection = $db->getConnection();
$objTeacherController = new TeacherController($connection);

// Get dropdown data
$teacherUserNamesList = $objTeacherController->GetAllTeachersIDAndNames(); // user_id, name
$teacherNamesList = $objTeacherController->GetAllTeachersNames(); // T_id, name

// Handle actions via switch
$action = $_POST['action'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Management</title>
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
        function toggleForms() {
            const action = document.getElementById("actionSelector").value;
            document.getElementById("insertForm").style.display = action === "insert" ? "block" : "none";
            document.getElementById("deleteForm").style.display = action === "delete" ? "block" : "none";
            document.getElementById("viewForm").style.display = action === "get_by_id" ? "block" : "none";
            document.getElementById("viewAllForm").style.display = action === "get_all" ? "block" : "none";
        }

        window.onload = function () {
            const selectedAction = "<?= $action ?>";
            if (selectedAction !== "") {
                document.getElementById("actionSelector").value = selectedAction;
                toggleForms();
            }
        };
    </script>
</head>
<body>

<!-- Header -->
<div class="header-bar">
    <div><strong>Teacher Management Panel</strong></div>
    <div>
        <a href="admin-dashboard.php">⬅ Back to Dashboard</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
</div>

<!-- Main Content -->
<div class="main" style="padding: 20px; max-width: 900px; margin: auto;">
    <h2>Teacher Management</h2>

    <!-- Action Selector -->
    <label for="actionSelector">Select Action:</label>
    <select id="actionSelector" onchange="toggleForms()">
        <option value="">-- Select Action --</option>
        <option value="insert">Insert Teacher</option>
        <option value="delete">Delete Teacher</option>
        <option value="get_by_id">View Teacher By ID</option>
        <option value="get_all">View All Teachers</option>
    </select>

    <hr>

    <!-- PHP Action Handlers -->
    <?php
    switch ($action) {
        case 'insert':
            $objTeacherModel = new Teacher();
            $objTeacherModel->setUserID($_POST["user_id"]);
            $objTeacherModel->setPhoneNo($_POST["phone_no"]);
            $objTeacherModel->setDesignation($_POST["designation"]);
            $objTeacherModel->setHireDate($_POST["hire_date"]);
            $objTeacherModel->setSalary($_POST["salary"]);
            $objTeacherController->InsertTeacher($objTeacherModel);
            echo "<p>✅ Teacher inserted.</p>";
            break;

        case 'delete':
            $teacherId = $_POST["delete_id"];
            $objTeacherController->DeleteTeacher($teacherId);
            echo "<p>🗑️ Teacher deleted (if exists).</p>";
            break;

        case 'get_by_id':
            $teacherId = $_POST["view_id"];
            $teacherData = $objTeacherController->GetTeacherById($teacherId);
            echo "<h3>Teacher Info</h3>";
            if (empty($teacherData)) {
                echo "<p>No teacher found with ID $teacherId.</p>";
            } else {
                echo "<table border='1' cellpadding='5'>
                        <tr>
                            <th>T_ID</th><th>User_ID</th><th>Name</th><th>Email</th><th>Phone</th>
                            <th>Designation</th><th>Hire Date</th><th>Salary</th>
                        </tr>
                        <tr>
                            <td>" . htmlspecialchars($teacherData['T_id']) . "</td>
                            <td>" . htmlspecialchars($teacherData['user_id']) . "</td>
                            <td>" . htmlspecialchars($teacherData['name']) . "</td>
                            <td>" . htmlspecialchars($teacherData['email']) . "</td>
                            <td>" . htmlspecialchars($teacherData['phone']) . "</td>
                            <td>" . htmlspecialchars($teacherData['designation']) . "</td>
                            <td>" . htmlspecialchars($teacherData['hire_date']) . "</td>
                            <td>" . htmlspecialchars($teacherData['salary']) . "</td>
                        </tr>
                    </table>";
            }
            break;

        case 'get_all':
            $teacherList = $objTeacherController->GetAllTeachers();
            echo "<h3>All Teachers</h3>";
            if (empty($teacherList)) {
                echo "<p>No teachers found.</p>";
            } else {
                echo "<table border='1' cellpadding='5'>
                        <tr>
                            <th>T_ID</th><th>User_ID</th><th>Name</th><th>Email</th><th>Phone</th>
                            <th>Designation</th><th>Hire Date</th><th>Salary</th>
                        </tr>";
                foreach ($teacherList as $teacher) {
                    echo "<tr>
                            <td>" . htmlspecialchars($teacher['T_id']) . "</td>
                            <td>" . htmlspecialchars($teacher['user_id']) . "</td>
                            <td>" . htmlspecialchars($teacher['name']) . "</td>
                            <td>" . htmlspecialchars($teacher['email']) . "</td>
                            <td>" . htmlspecialchars($teacher['phone']) . "</td>
                            <td>" . htmlspecialchars($teacher['designation']) . "</td>
                            <td>" . htmlspecialchars($teacher['hire_date']) . "</td>
                            <td>" . htmlspecialchars($teacher['salary']) . "</td>
                        </tr>";
                }
                echo "</table>";
            }
            break;
    }
    ?>

    <!-- Insert Teacher Form -->
    <div id="insertForm" style="display:none;">
        <form method="post">
            <input type="hidden" name="action" value="insert">
            <h3>Insert Teacher</h3>
            <label>User (Name - ID):</label>
            <select name="user_id" required>
                <option value="">-- Select User --</option>
                <?php foreach ($teacherUserNamesList as $user): ?>
                    <option value="<?= $user['U_id'] ?>">
                        <?= htmlspecialchars($user['name']) ?> (ID: <?= $user['U_id'] ?>)
                    </option>
                <?php endforeach; ?>
            </select><br><br>
            Phone No: <input type="text" name="phone_no"><br>
            Designation: <input type="text" name="designation"><br>
            Hire Date: <input type="date" name="hire_date"><br>
            Salary: <input type="number" name="salary" step="0.01"><br>
            <button type="submit">Insert</button>
        </form>
    </div>

    <!-- Delete Teacher Form -->
    <div id="deleteForm" style="display:none;">
        <form method="post">
            <input type="hidden" name="action" value="delete">
            <h3>Delete Teacher</h3>
            <label>Select Teacher:</label>
            <select name="delete_id" required>
                <option value="">-- Select Teacher --</option>
                <?php foreach ($teacherNamesList as $teacher): ?>
                    <option value="<?= $teacher['T_id'] ?>">
                        <?= htmlspecialchars($teacher['name']) ?> (ID: <?= $teacher['T_id'] ?>)
                    </option>
                <?php endforeach; ?>
            </select><br><br>
            <button type="submit">Delete</button>
        </form>
    </div>

    <!-- View Teacher by ID -->
    <div id="viewForm" style="display:none;">
        <form method="post">
            <input type="hidden" name="action" value="get_by_id">
            <h3>View Teacher By ID</h3>
            <label>Select Teacher:</label>
            <select name="view_id" required>
                <option value="">-- Select Teacher --</option>
                <?php foreach ($teacherNamesList as $teacher): ?>
                    <option value="<?= $teacher['T_id'] ?>">
                        <?= htmlspecialchars($teacher['name']) ?> (ID: <?= $teacher['T_id'] ?>)
                    </option>
                <?php endforeach; ?>
            </select><br><br>
            <button type="submit">View</button>
        </form>
    </div>

    <!-- View All Teachers -->
    <div id="viewAllForm" style="display:none;">
        <form method="post">
            <input type="hidden" name="action" value="get_all">
            <button type="submit">View All Teachers</button>
        </form>
    </div>
</div>

</body>
</html>
