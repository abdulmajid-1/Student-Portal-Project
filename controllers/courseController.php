<?php
include_once '../config/db.php'; // Include your database connection file
include_once '../models/courseModel.php'; // Include the Course model

class CourseController {

    // Database connection
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Method to insert a new course
    public function InsertCourse(Course $objCourse): int {

        $courseCode = $objCourse->getCourseCode();
        $courseId = $objCourse->getCourseId();
        $courseDep = $objCourse->getCourseDepartment();
        $courseName = $objCourse->getCourseName();

        $objStatement = $this->connection->prepare("CALL add_course(?, ?, ?, ?)");
        $objStatement->bindParam(1, $courseCode);
        $objStatement->bindParam(2, $courseId);
        $objStatement->bindParam(3, $courseDep);
        $objStatement->bindParam(4, $courseName);

        return $objStatement->execute();
    }

    // Method to update an existing course

    // public function UpdateCourse(Course $objCourse) {
    //     $courseCode = $objCourse->getCourseCode();
    //     $courseId = $objCourse->getCourseId();
    //     $courseDep = $objCourse->getCourseDepartment();
    //     $courseName = $objCourse->getCourseName();

    //     $objStatement = $this->connection->prepare("CALL update_course(?, ?, ?, ?)");
    //     $objStatement->bindParam(1, $courseCode);
    //     $objStatement->bindParam(2, $courseId);
    //     $objStatement->bindParam(3, $courseDep);
    //     $objStatement->bindParam(4, $courseName);

    //     return $objStatement->execute();
    // }

    public function DeleteCourse($courseId) {
        $objStatement = $this->connection->prepare("CALL delete_course(?)");
        $objStatement->bindParam(1, $courseId);
        $objStatement->execute();
        return $objStatement->rowCount() > 0; // Return true if a row was deleted
    }

    // Method to get all courses
    public function GetAllCourses() {
        $objStatement = $this->connection->prepare("CALL show_all_courses()");
        $objStatement->execute();
        $result = $objStatement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}


?>