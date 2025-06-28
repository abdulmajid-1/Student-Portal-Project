<?php
include_once '../models/teacherModel.php'; // Include the Teacher model
class TeacherController {

    // Database connection
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Method to insert a new teacher
    public function InsertTeacher(Teacher $objTeacher) {
        // $id = $objTeacher->getTeacherID();
        $userId = $objTeacher->getUserID();
        // $name = $objTeacher->getName();
        // $email = $objTeacher->getEmail();
        $phoneNo = $objTeacher->getPhoneNo();
        $designation = $objTeacher->getDesignation();
        $hireDate = $objTeacher->getHireDate();
        $salary = $objTeacher->getSalary();

        $query = "SELECT role FROM Users WHERE U_id = ?";
        $objStatement = $this->connection->prepare($query);
        $objStatement->bindParam(1, $userId, PDO::PARAM_INT);
        try{
            $objStatement->execute();
            $userRole = $objStatement->fetchColumn();
        }
        catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0; // Return 0 on failure
        }

        if ($userRole !== 'teacher') {
            echo "Error: User with ID $userId is not a teacher.";
            return 0; // Return 0 on failure
        }
    

        $objStatement = $this->connection->prepare("INSERT INTO Teachers (user_id, phone, designation, hire_date, salary) VALUES (?, ?, ?, ?, ?)");
        $objStatement->bindParam(1, $userId, PDO::PARAM_INT);
        $objStatement->bindParam(2, $phoneNo);
        $objStatement->bindParam(3, $designation);
        $objStatement->bindParam(4, $hireDate);
        $objStatement->bindParam(5, $salary);


        try{
            $objStatement->execute();
            echo "Teacher inserted successfully!";
            return;
        } 
        catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0; // Return 0 on failure
        }
    }

    // Method to delete a teacher by ID
    public function DeleteTeacher($teacherId) {
        try {
            $stmt = $this->connection->prepare("DELETE FROM Teachers WHERE T_id = ?");
            $stmt->bindParam(1, $teacherId, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                echo "Teacher deleted successfully!";
                return;
            } 
            else {
                echo "No teacher found with . ID: $teacherId";
                return;
            }
        } 
        catch (PDOException $e) {
            echo "Teacher deletion error (ID $teacherId): " . $e->getMessage();
            return;
        }
    }


    // Method to get all teachers
    public function GetAllTeachers(): array {
        $query = "SELECT 
        	teachers.T_id,
            teachers.user_id,
            users.name,
            users.email,
            teachers.phone,
            teachers.designation,
            teachers.hire_date,
            teachers.salary
        FROM 
            teachers
        INNER JOIN 
            users ON teachers.user_id = users.U_id";

        $objStatement = $this->connection->prepare($query);
        $objStatement->execute();
        $result = $objStatement->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : [];
    }

    // Method to get a teacher by ID
    public function GetTeacherById($teacherId): array {
        $query =  "SELECT 
            teachers.T_id,
            teachers.user_id,
            users.name,
            users.email,
            teachers.phone,
            teachers.designation,
            teachers.hire_date,
            teachers.salary
        FROM 
            teachers
        INNER JOIN 
            users ON teachers.user_id = users.U_id
        WHERE 
            teachers.T_id = ?";


        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(1, $teacherId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result: []; // Return empty array if no result found

        }
        catch (PDOException $e) {
            error_log("GetTeacherById Error: " . $e->getMessage());
            echo "Failed to retrieve teacher information.";
            return [];
        }
    }
    public function GetTeacherCourses($UserId): array {

        try{
            $query = "SELECT T_id FROM teachers WHERE user_id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(1, $UserId, PDO::PARAM_INT);
            $stmt->execute();
            $teacherId = $stmt->fetchColumn();

        } 
        catch (PDOException $e) {
            error_log("GetTeacherCourses Error: " . $e->getMessage());
            echo "Failed to retrieve teacher ID.";
            return [];
        }





        $query = "SELECT 
            c.C_id, 
            c.name AS course_name, 
            c.code AS course_code, 
            c.department AS course_department, 
            u.name AS teacher_name
        FROM 
            courses c
        INNER JOIN 
            teachers t ON c.teacher_id = t.T_id
        INNER JOIN 
            users u ON t.user_id = u.U_id
        WHERE 
            t.T_id = ?";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(1, $teacherId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ? $result : []; // Return empty array if no result found

        } 
        catch (PDOException $e) {
            error_log("GetTeacherCourses Error: " . $e->getMessage());
            echo "Failed to retrieve teacher courses.";
            return [];
        }
    }

    public function GetAttendanceRecords($userId): array {


        $query = "SELECT T_id FROM teachers WHERE user_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->execute();
        $teacherId = $stmt->fetchColumn();
        if (!$teacherId) {
            echo "No teacher found with user ID: $userId";
            return [];
        }

        $query = "SELECT 
            a.A_id, 
            a.date, 
            a.status, 
            u.name AS student_name, 
            c.name AS course_name
                FROM 
            attendance a
                INNER JOIN 
            students s ON a.student_id = s.S_id
                INNER JOIN 
            users u ON s.user_id = u.U_id
                INNER JOIN 
            courses c ON a.course_id = c.C_id
                INNER JOIN 
            teachers t ON c.teacher_id = t.T_id
                WHERE 
            t.T_id = ?";


        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(1, $teacherId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ? $result : []; // Return empty array if no result found

        } 
        catch (PDOException $e) {
            error_log("GetAttendanceRecords Error: " . $e->getMessage());
            echo "Failed to retrieve attendance records.";
            return [];
        }
        
    }

    public function GetAttendace($userID, $course_id): array{

        $query = "SELECT T_id FROM teachers WHERE user_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->execute();
        $teacherId = $stmt->fetchColumn();
        if (!$teacherId) {
            echo "No teacher found with user ID: $userId";
            return [];
        }

        $query = "SELECT 
            u.name AS student_name,
            u.email,
            s.S_id AS student_id,
            e.enrollment_id,
            c.C_id AS course_id,
            c.name AS course_name
                FROM 
            enrollment e
                INNER JOIN students s ON e.student_id = s.S_id
                INNER JOIN users u ON s.user_id = u.U_id
                INNER JOIN courses c ON e.course_id = c.C_id
                WHERE 
            c.C_id = ?";
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(1, $course_id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ? $result : []; // Return empty array if no result found

        }
        catch (PDOException $e) {
            error_log("GetAttendace Error: " . $e->getMessage());
            echo "Failed to retrieve attendance records.";
            return [];
        }   

    }

    public function markAttendance($studentId, $courseId, $teacherId, $date, $status)
    {
        $query = "INSERT INTO attendance (student_id, course_id, teacher_id, date, status)
              VALUES (?, ?, ?, ?, ?)";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(1, $studentId);
            $stmt->bindParam(2, $courseId);
            $stmt->bindParam(3, $teacherId);
            $stmt->bindParam(4, $date);
            $stmt->bindParam(5, $status);
            $stmt->execute();
        } 
        catch (PDOException $e) {
            // Log this instead of echo in production
            echo "Database Error: " . $e->getMessage();
        }
    }

    
    public function GetTeacherID($userID) : int {
        $query = "SELECT T_id from teachers where user_id = ?";
        try{
            $stmt = $this -> connection -> prepare($query);
            $stmt -> bindParam(1, $userID, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt -> fetchColumn();
        }
        catch (PDOException $e)
        {
            echo "Error : " . htmlspecialchars($e->getMessage());
            return 0;
        }
    }

    public function GetAttendanceByCourse($teacherId, $courseId) : array {

        // echo "course id is ". $courseId . "teacehr id : ". $teacherId;
        // return [];
        try{
            $query = "SELECT 
                a.A_id,
                a.date,
                a.status,
                s.S_id AS student_id,
                u.name AS student_name,
                c.name AS course_name
              FROM attendance a
              INNER JOIN students s ON a.student_id = s.S_id
              INNER JOIN users u ON s.user_id = u.U_id
              INNER JOIN courses c ON a.course_id = c.C_id
              WHERE a.teacher_id = ? AND c.C_id = ?";
    
            $stmt = $this->connection->prepare($query);
            $stmt -> bindParam(1, $teacherId, PDO::PARAM_INT);
            $stmt -> bindParam(2, $courseId, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            echo "Error: " . htmlspecialchars($e->getMessage());
            return [];
        }

    }
    public function GetAllTeachersNames(): array {
        $query = "SELECT T.T_id, U.name
                  FROM teachers T
                  INNER JOIN users U ON T.user_id = U.U_id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($teachers)
        {
            return $teachers;
        }
        else
        {
            return [];
        }
    }
    public function GetAllTeachersIDAndNames() : array {
        $query = "SELECT U.U_id, U.name
                  FROM users U
                  WHERE U.role = 'teacher'";
        $objStatement = $this->connection->prepare($query);
        $objStatement->execute();
        $result = $objStatement->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            return $result;
        } 
        else {
            return [];
        }
    }
}
?>