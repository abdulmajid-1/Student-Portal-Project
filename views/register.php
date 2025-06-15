<?php
session_start(); // Start the session to use session variables
require_once '../config/db.php'; // Include your database connection file
include_once '../controllers/studentController.php'; // Include the controller

$objdatabaseconnection = new DatabaseConnectivity();
$objdatabaseconnection = $objdatabaseconnection->getConnection(); // Get the PDO connection

$objStudentController = new StudentController($objdatabaseconnection); // Create an instance of the controller

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $objStudentModel = new Student(); // Create an instance of the Student model
    $objStudentModel->setName($_POST["name"]);
    $objStudentModel->setEmail($_POST["email"]);   
    $objStudentModel->setPassword(password_hash($_POST["password"], PASSWORD_DEFAULT)); // Hash the passwor
    $objStudentModel->setRole("student"); // Default role; change logic if needed

    // Call the InsertStudent method to register the user
    $isSucsses = $objStudentController->InsertStudent($objStudentModel);


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
    <button type="submit">Register</button>
</form>
