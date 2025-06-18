<?php
session_start(); // Start the session to use session variables
require_once '../config/db.php'; // Include your database connection file
include_once '../controllers/userController.php'; // Include the controller

$objdatabaseconnection = new DatabaseConnectivity();
$objdatabaseconnection = $objdatabaseconnection->getConnection(); // Get the PDO connection

$objUserController = new userController($objdatabaseconnection); // Create an instance of the controller

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $objUserModel = new User(); // Create an instance of the User model
    $objUserModel->setName($_POST["name"]);
    $objUserModel->setEmail($_POST["email"]);   
    $objUserModel->setPassword(password_hash($_POST["password"], PASSWORD_DEFAULT)); // Hash the passwor
    $objUserModel->setRole($_POST["role"]); // Default role; change logic if needed

    // Call the InsertUser method to register the user
    $isSucsses = $objUserController->InsertUser($objUserModel);


    if ($isSucsses) {
        // Registration successful
        echo "Registration successful! <a href='login.php'>Login now</a>";
    } 
    else {
        echo "Error: " . $objdatabaseconnection->errorInfo()[2]; // Display error message
    }
    // after execute
    $objdatabaseconnection = null;

}
?>
<!-- HTML Form -->
<form method="post">
    <input type="text" name="name" required placeholder="Full Name"><br>
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="password" required placeholder="Password"><br>
    <label for="choose role"></label>
    <select name="role" required>
        <option value="User">User</option>
        <!-- <option value="teacher">Teacher</option> -->
        <option value="admin">Admin</option>
    </select><br>
    <button type="submit">Register</button>
</form>
