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
            $name = $objStudent->getName();
            $email = $objStudent->getEmail();
            $password = $objStudent->getPassword();
            $role = $objStudent->getRole();

            $objstatment = $this->connection->prepare("CALL insert_user(?, ?, ?, ?)");
            $objstatment->bindParam(1, $name);
            $objstatment->bindParam(2, $email);
            $objstatment->bindParam(3, $password);
            $objstatment->bindParam(4, $role);

            return $objstatment->execute();
    }
}
?>
