<?php
include_once '../models/enrollmentModel.php'; // Include the Enrollment model
include_once '../models/courseModel.php'; // Include the Course model

class EnrollmentController
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function enrollStudent(EnrollmentModel $enrollment)
    {

        $studentId = $enrollment->getStudentId();
        $courseId = $enrollment->getCourseId();

        $stmt = $this->connection->prepare("INSERT INTO enrollments (student_id, course_id)
                        VALUES (?, ?)");
        $stmt->bindParam(1, $studentId, PDO::PARAM_INT);
        $stmt->bindParam(2, $courseId, PDO::PARAM_INT);

        try {

            $stmt->execute();
            return true;
        } catch (PDOException $e) {

            if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
                echo "Enrollment failed: The student or course does not exist.";
            } else {
                echo "Enrollment failed: " . htmlspecialchars($e->getMessage());
            }
            return false;
        }
    }

    public function getAllEnrollments()
    {
        $query = "
            SELECT 
                e.EN_id AS enrollment_id,
                s.S_id AS student_id,
                u.name AS student_name,
                c.C_id AS course_id,
                c.name AS course_name
            FROM enrollments e
            INNER JOIN students s ON e.student_id = s.S_id
            INNER JOIN users u ON s.user_id = u.U_id
            INNER JOIN courses c ON e.course_id = c.C_id";

        $stmt = $this->connection->prepare($query);
        try {

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // You can log $e->getMessage() for debugging
            if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
                echo "Failed to retrieve enrollments: The student or course does not exist.";
            } else {
                echo "Failed: " . htmlspecialchars($e->getMessage());
            }
            return false;
        }
    }


    // public function getEnrollmentsByStudent($studentId) {
    //     $stmt = $this->connection->prepare("SELECT * FROM enrollments WHERE student_id = ?");
    //     $stmt->bindParam(1, $studentId);
    //     $stmt->execute();

    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
    public function getEnrollmentsByStudent($studentId)
    {
        $query = "
            SELECT 
                e.EN_id AS enrollment_id,
                s.S_id AS student_id,
                u.name AS student_name,
                c.C_id AS course_id,
                c.name AS course_name
            FROM enrollments e
            INNER JOIN students s ON e.student_id = s.S_id
            INNER JOIN users u ON s.user_id = u.U_id
            INNER JOIN courses c ON e.course_id = c.C_id
            WHERE s.S_id = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $studentId);
        //$stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
        try {

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // You can log $e->getMessage() for debugging
            if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
                echo "Failed to retrieve enrollments: The student or course does not exist.";
            } else {
                echo "Failed: " . htmlspecialchars($e->getMessage());
            }
            return false;
        }
    }
    public function getAttendanceStatusbyID($studentId)
    {
        try {
            $query = "SELECT status from attendance where student_id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(1, $studentId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
                echo "Failed to retrieve enrollments: The student or course does not exist.";
            } else {
                echo "Failed: " . htmlspecialchars($e->getMessage());
            }
            return [];
        }
    }


    public function getEnrollmentbyCourseID($courseId): array
    {
        try {

            $query = "SELECT 
                s.S_id AS student_id,
                u.name AS student_name,
                c.C_id AS course_id,
                c.name AS course_name
            FROM enrollments e
            INNER JOIN students s ON e.student_id = s.S_id
            INNER JOIN users u ON s.user_id = u.U_id
            INNER JOIN courses c ON e.course_id = c.C_id
            WHERE c.C_id = ? ";


            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(1, $courseId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            echo "failed: " . htmlspecialchars($e->getMessage());
            return [];
        }
    }
    public function deleteEnrollment($enrollmentId)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM enrollments WHERE EN_id = ?");
            $stmt->bindParam(1, $enrollmentId, PDO::PARAM_INT);
            $stmt->execute();

            // Check how many rows were affected
            if ($stmt->rowCount() > 0) {
                echo "Enrollment deleted successfully!";
                return;
            } else {
                echo "Error: Enrollment ID does not exist.";
                return;
            }
        } catch (PDOException $e) {
            error_log("Delete Error: " . $e->getMessage());
            echo "Deletion failed: " . htmlspecialchars($e->getMessage());
            return false;
        }
    }
}
