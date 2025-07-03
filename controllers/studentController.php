<?php
//require_once '../config/db.php'; // Include your database connection file
include_once '../models/studentModel.php'; // Include the Student model

class StudentController
{

    // Database connection
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function InsertStudent(Student $objStudent)
    {
        // $S_id = $objStudent->getS_id();
        $user_id = $objStudent->getUser_id();
        $roll_no = $objStudent->getRoll_no();
        $department = $objStudent->getDepartment();
        $year = $objStudent->getYear();

        $query = "SELECT role FROM users WHERE U_id = :user_id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        try {
            $stmt->execute();
            $role = $stmt->fetchColumn(); // Fetch the user ID based on the user ID
            if ($role !== 'user') {
                echo "Error: The user ID provided does not belong to a student.";
                return;
            }
        } catch (PDOException $e) {
            error_log("InsertStudent Error: " . $e->getMessage());
            echo "Error: " . htmlspecialchars($e->getMessage());
            return;
        }
        // Prepare the SQL statement to insert a student
        try {
            $stmt = $this->connection->prepare("
<<<<<<< HEAD
            INSERT INTO students (user_id, roll_no, department, year)
=======
            INSERT INTO Students (user_id, roll_no, department, year)
>>>>>>> 244e2f4eefbebb5f44c6822d91d693c6f5f92baf
            VALUES (?, ?, ?, ?)");

            // $stmt->bindParam(1, $S_id, PDO::PARAM_INT);
            $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $roll_no);
            $stmt->bindParam(3, $department);
            $stmt->bindParam(4, $year);

            $stmt->execute();
            echo "Student inserted successfully!";
            return;
        } catch (PDOException $e) {
            error_log("InsertStudent Error: " . $e->getMessage()); // logs error
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                echo "Error: Invalid Student ID or Invalid user ID.";
            } else {
                echo "Failed to insert student: " . htmlspecialchars($e->getMessage());
            }
            return;
        }
    }
    public function GetAllStudents(): array
    {
        $query = "SELECT s.*, u.name AS Student_name FROM 
                    students s 
            INNER JOIN users u ON s.user_id = u.U_id;";

        $objStatement = $this->connection->prepare($query);
        $objStatement->execute();
        $result = $objStatement->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {

            return $result;
        } else {

            return [];
        }
    }

    public function GetStudentById($id): array
    {
        $query = "SELECT s.*, u.name AS Student_name
                    FROM 
                students s
                    INNER JOIN 
                users u ON s.user_id = u.U_id
                     WHERE 
                s.S_id = ?";
        $objStatement = $this->connection->prepare($query);
        $objStatement->bindParam(1, $id, PDO::PARAM_INT);
        $objStatement->execute();
        $result = $objStatement->fetch(PDO::FETCH_ASSOC);
        if ($result) {

            return $result;
        } else {

            return [];
        }
    }
    public function DeleteStudent($id)
    {
<<<<<<< HEAD
        $query = "DELETE FROM students WHERE S_id = ?";
=======
        $query = "DELETE FROM STUDENTS WHERE S_id = ?";
>>>>>>> 244e2f4eefbebb5f44c6822d91d693c6f5f92baf
        $objStatement = $this->connection->prepare($query);
        $objStatement->bindParam(1, $id, PDO::PARAM_INT);
        $objStatement->execute();
        $isSuccess = $objStatement->rowCount() > 0; // Check if any rows were affected
        if ($isSuccess) {
            echo "Student deleted successfully!";
        } else {
            echo "No student found with ID: $id";
        }
        return;
    }
    public function GetStudentDataDashboard($userId): array
    {

        try {
<<<<<<< HEAD
            $query = "SELECT roll_no, department, year FROM students WHERE user_id = :user_id";
=======
            $query = "SELECT roll_no, department, year FROM Students WHERE user_id = :user_id";
>>>>>>> 244e2f4eefbebb5f44c6822d91d693c6f5f92baf
            $objStatement = $this->connection->prepare($query);
            $objStatement->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $objStatement->execute();
            $studentData = $objStatement->fetch(PDO::FETCH_ASSOC);
            if ($studentData) {
                return $studentData;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            error_log("GetStudentDataDashboard Error: " . $e->getMessage()); // logs error
            echo "Error: " . htmlspecialchars($e->getMessage());
            return [];
        }
    }


    public function viewMyCourses($userId): array
    {
        if (!isset($_SESSION["user_id"])) {
            echo "You are not logged in.";
            return [];
        }

        // $userId = $_SESSION["user_id"];
<<<<<<< HEAD
        $query = "SELECT S_id FROM students WHERE user_id = :user_id";
=======
        $query = "SELECT S_id FROM Students WHERE user_id = :user_id";
>>>>>>> 244e2f4eefbebb5f44c6822d91d693c6f5f92baf
        $objStatement = $this->connection->prepare($query);
        $objStatement->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $objStatement->execute();
        $student_id = $objStatement->fetchColumn(); // Fetch the student ID based on the user ID


        if (!$student_id) {
            echo "No student found with this user ID. ";
            return [];
        }


        $query = "SELECT c.* 
<<<<<<< HEAD
                    FROM courses c 
                    INNER JOIN enrollments e ON c.C_id = e.course_id 
=======
                    FROM Courses c 
                    INNER JOIN Enrollments e ON c.C_id = e.course_id 
>>>>>>> 244e2f4eefbebb5f44c6822d91d693c6f5f92baf
                    WHERE e.student_id = :student_id";
        $objStatement = $this->connection->prepare($query);
        $objStatement->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $objStatement->execute();
        $courses = $objStatement->fetchAll(PDO::FETCH_ASSOC);

        return $courses;
    }

    public function GetAttendanceforStudent($user_id, $course_id): array
    {

        try {
<<<<<<< HEAD
            $query = "SELECT S_id FROM students where user_id = ?";
=======
            $query = "SELECT S_id FROM Students where user_id = ?";
>>>>>>> 244e2f4eefbebb5f44c6822d91d693c6f5f92baf
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $student_id = $stmt->fetchColumn();
        } catch (PDOException $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
            return [];
        }


        try {
            $query = "SELECT a.date, a.status, u.name AS teacher_name
                        FROM attendance a
                    INNER JOIN courses c ON a.course_id = c.C_id
                    INNER JOIN teachers t ON c.teacher_id = t.T_id
                    INNER JOIN users u ON t.user_id = u.U_id
                        WHERE a.student_id = ? AND a.course_id = ?
                    ORDER BY a.date";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(1, $student_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $course_id, PDO::PARAM_INT);
            $stmt->execute();
            $attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $attendance;
        } catch (PDOException $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
            return [];
        }
    }

    public function GetCoursesbyStudentID($user_id): array
    {


        try {
            $query = "SELECT 
                c.C_id AS course_id, 
                c.name AS course_name, 
                u.name AS teacher_name
                    FROM enrollments e
              JOIN students s ON e.student_id = s.S_id
              JOIN courses c ON e.course_id = c.C_id
              JOIN teachers t ON c.teacher_id = t.T_id
              JOIN users u ON t.user_id = u.U_id
                    WHERE s.user_id = ?";

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $courses;
        } catch (PDOException $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
            return [];
        }
    }
    public function GetAllStudentsName(): array
    {
        try {
<<<<<<< HEAD
            $query = "SELECT S.S_id , U.name AS student_name FROM students S INNER JOIN users U ON S.user_id = U.U_id";
=======
            $query = "SELECT S.S_id , U.name AS student_name FROM Students S INNER JOIN Users U ON S.user_id = U.U_id";
>>>>>>> 244e2f4eefbebb5f44c6822d91d693c6f5f92baf
            $objStatement = $this->connection->prepare($query);
            $objStatement->execute();
            $result = $objStatement->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            error_log("GetAllStudentsName Error: " . $e->getMessage());
            return [];
        }
    }
}
