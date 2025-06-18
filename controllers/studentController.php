<?php
//require_once '../config/db.php'; // Include your database connection file
include_once '../models/studentModel.php'; // Include the Student model

class StudentController {

    // Database connection
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function InsertStudent(Student $objStudent): int {
        $S_id = $objStudent->getS_id();
        $user_id = $objStudent->getUser_id();
        $roll_no = $objStudent->getRoll_no();
        $department = $objStudent->getDepartment();
        $year = $objStudent->getYear();

        // Prepare the SQL statement to insert a student
        $objstatment = $this->connection->prepare("Insert into Students (S_id, user_id, roll_no, department, year)
            VALUES (?, ?, ?, ?, ?)");
        $objstatment->bindParam(1, $S_id);
        $objstatment->bindParam(2, $user_id);
        $objstatment->bindParam(3, $roll_no);
        $objstatment->bindParam(4, $department);
        $objstatment->bindParam(5, $year);
        return $objstatment->execute();


    }
    public function GetAllStudents(): array {
        $query = "SELECT s.*, u.name AS Student_name FROM 
                    students s 
            INNER JOIN users u ON s.user_id = u.U_id;";

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

    public function GetStudentById($id): array {
        $query = "call getStudentByStudentId(?)";
        $objStatement = $this->connection->prepare($query);
        $objStatement->bindParam(1, $id, PDO::PARAM_INT);
        $objStatement->execute();
        $result = $objStatement->fetch(PDO::FETCH_ASSOC);
        if ($result) {

            return $result;
        } 
        else {

            return [];
        }
    }
    public function DeleteStudent($id): bool {
        $query = "DELETE FROM STUDENTS WHERE S_id = ?";
        $objStatement = $this->connection->prepare($query);
        $objStatement->bindParam(1, $id, PDO::PARAM_INT);
        $isSuccess = $objStatement->execute();
        return $isSuccess;
    }

    public function changePassword($userId, $newPassword) {
        // Fetch user to confirm they exist
        $query = "SELECT * FROM Users WHERE U_id = :user_id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo "User not found.";
            return false;
        }

        // Update password
        $query = "UPDATE Users SET password = :password WHERE U_id = :user_id";
        $stmt = $this->connection->prepare($query);
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

