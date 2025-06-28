<?php
//require_once '../config/db.php'; // Include your database connection file
include_once '../models/UserModel.php'; // Include the User model

class UserController {

    // Database connection
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function InsertUser(User $objUser): int {
            $name = $objUser->getName();
            $email = $objUser->getEmail();
            $password = $objUser->getPassword();
            $role = $objUser->getRole();

            $objstatment = $this->connection->prepare("INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $objstatment->bindParam(1, $name);
            $objstatment->bindParam(2, $email);
            $objstatment->bindParam(3, $password);
            $objstatment->bindParam(4, $role);
            try{
                $objstatment->execute();
                return true; // Return the last inserted ID
            } 
            catch (PDOException $e) {
                error_log("InsertUser Error: " . $e->getMessage()); // logs error
                if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                    echo "Error: Invalid email or duplicate entry.";
                } 
                else {
                    echo "Failed to insert user: " . htmlspecialchars($e->getMessage());
                }
                return 0; // Return 0 on failure
            }


    }
    public function getAllUsersNames() {
        $stmt = $this->connection->prepare("SELECT U_id, name FROM users where role = 'user'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // public function GetAllUsers(): array {
    //     $query = "SELECT s.*, u.name AS User_name FROM 
    //                 Users s 
    //         INNER JOIN users u ON s.user_id = u.U_id;";

    //     $objStatement = $this->connection->prepare($query);
    //     $objStatement->execute();
    //     $result = $objStatement->fetchAll(PDO::FETCH_ASSOC);
    //     return $result;
    // }

    // public function GetUserById($id): array {
    //     $query = "call getUserByUserId(?)";
    //     $objStatement = $this->connection->prepare($query);
    //     $objStatement->bindParam(1, $id, PDO::PARAM_INT);
    //     $objStatement->execute();
    //     $result = $objStatement->fetch(PDO::FETCH_ASSOC);
    //     if ($result) {

    //         return $result;
    //     } 
    //     else {

    //         return [];
    //     }
    // }
    // public function DeleteUser($id): bool {
    //     $query = "DELETE FROM Users WHERE S_id = ?";
    //     $objStatement = $this->connection->prepare($query);
    //     $objStatement->bindParam(1, $id, PDO::PARAM_INT);
    //     $isSuccess = $objStatement->execute();
    //     return $isSuccess;
    // }

}
?>
