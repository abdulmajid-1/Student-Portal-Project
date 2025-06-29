<?php
include_once '../config/db.php'; // Include your database connection file
include_once '../models/courseModel.php'; // Include the Course model

class CourseController
{

    // Database connection
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    // Method to insert a new course
    public function InsertCourse(Course $objCourse): int
    {

        $courseCode = $objCourse->getCourseCode();
        $courseId = $objCourse->getCourseId();
        $courseDep = $objCourse->getCourseDepartment();
        $courseName = $objCourse->getCourseName();
        $teacherId = $objCourse->getTeacherId();

        $courseId = 0;

        $objStatement = $this->connection->prepare("INSERT INTO courses (code, C_id, department, name, teacher_id)
                        VALUES (?, ?, ?, ?, ?)");
        $objStatement->bindParam(1, $courseCode);
        $objStatement->bindParam(2, $courseId);
        $objStatement->bindParam(3, $courseDep);
        $objStatement->bindParam(4, $courseName);
        $objStatement->bindParam(5, $teacherId, PDO::PARAM_INT);

        return $objStatement->execute();
    }


    public function DeleteCourse($courseId)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM courses WHERE C_id = ?");
            $stmt->bindParam(1, $courseId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo "Course deleted successfully!";
                return;
            } else {
                echo "Error: No course found with ID $courseId.";
                return;
            }
        } catch (PDOException $e) {
            error_log("Course Delete Error: " . $e->getMessage());
            echo "Deletion failed: " . htmlspecialchars($e->getMessage());
            return;
        }
    }

    // Method to get all courses
    public function GetAllCourses()
    {
        $objStatement = $this->connection->prepare("SELECT c.*, u.name AS teacher_name
                        FROM 
                    courses c
                        INNER JOIN 
                    teachers t ON c.teacher_id = t.T_id
                        INNER JOIN 
                    users u ON t.user_id = u.U_id");
        $objStatement->execute();
        $result = $objStatement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function GetAllCoursesName(): array
    {
        try {
            $objStatement = $this->connection->prepare("SELECT C_id, name FROM courses");
            $objStatement->execute();
            $result = $objStatement->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            error_log("Error fetching course names: " . $e->getMessage());
            return [];
        }
    }
}
