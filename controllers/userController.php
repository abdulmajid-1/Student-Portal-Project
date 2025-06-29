<?php
//require_once '../config/db.php'; // Include your database connection file
include_once '../models/userModel.php'; // Include the User model

class UserController
{

    // Database connection
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function InsertUser(User $objUser): int
    {
        $name = $objUser->getName();
        $email = $objUser->getEmail();
        $password = $objUser->getPassword();
        $role = $objUser->getRole();

        $objstatment = $this->connection->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $objstatment->bindParam(1, $name);
        $objstatment->bindParam(2, $email);
        $objstatment->bindParam(3, $password);
        $objstatment->bindParam(4, $role);
        try {
            $objstatment->execute();
            return true;
        } catch (PDOException $e) {
            error_log("InsertUser Error: " . $e->getMessage());
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                echo "Error: Invalid email or duplicate entry.";
            } else {
                echo "Failed to insert user: " . htmlspecialchars($e->getMessage());
            }
            return 0;
        }
    }
    public function getAllUsersNames()
    {
        $stmt = $this->connection->prepare("SELECT u.U_id, u.name 
                        FROM users u
                    LEFT JOIN students s ON u.U_id = s.user_id
                        WHERE u.role = 'user' AND s.S_id IS NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
