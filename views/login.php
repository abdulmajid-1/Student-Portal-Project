<?php
session_start();
require_once '../config/db.php'; // This must return a PDO connection

$db = new DatabaseConnectivity();
$conn = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {
        // Prepare the stored procedure call
        $stmt = $conn->prepare("CALL GetUserByEmail(:email)");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Fetch the result
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a user was found
        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION["user_id"] = $user["U_id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["role"] = $user["role"];

                if($user["role"] == "admin") {
                    header("Location: admin-dashboard.php");
                    exit;
                } 
                else {
                    header("Location: dashboard.php");
                    exit;
                }
                
              
            } 
            else {
                echo "Incorrect password.";
            }
        } 
        else {
            echo "User not found.";
        }

        $stmt->closeCursor();

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>

<form method="post">
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="password" required placeholder="Password"><br>
    <button type="submit">Login</button>
</form>