<?php 
include_once '../models/enrollmentModel.php'; // Include the Enrollment model
include_once '../models/courseModel.php'; // Include the Course model

class EnrollmentController {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function enrollStudent(EnrollmentModel $enrollment) {
        $studentId = $enrollment->getStudentId();
        $courseId = $enrollment->getCourseId();

        $stmt = $this->connection->prepare("call enroll_student(?, ?)");
        $stmt->bindParam(1, $studentId);
        $stmt->bindParam(2, $courseId);

        return $stmt->execute();
    }
    public function getAllEnrollments() {
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
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // public function getEnrollmentsByStudent($studentId) {
    //     $stmt = $this->connection->prepare("SELECT * FROM enrollments WHERE student_id = ?");
    //     $stmt->bindParam(1, $studentId);
    //     $stmt->execute();
        
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
    public function getEnrollmentsByStudent($studentId) {
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
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteEnrollment($enrollmentId) {
        $stmt = $this->connection->prepare("CALL delete_enrollment_by_id(?)");
        $stmt->bindParam(1, $enrollmentId);
        
        return $stmt->execute();
    }
}

?>