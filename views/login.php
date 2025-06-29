<?php
session_start();
require_once '../config/db.php';
include_once '../controllers/userController.php';

$errorMsg = '';
$email = '';

try {
    $db = new DatabaseConnectivity();
    $conn = $db->getConnection();
} catch (Exception $e) {
    $conn = null;
    $errorMsg = 'Database connection failed.';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST["email"]) ? $_POST["email"] : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';

    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION["user_id"] = $user["U_id"];
                    $_SESSION["name"] = $user["name"];
                    $_SESSION["role"] = $user["role"];

                    if (isset($stmt)) {
                        $stmt->closeCursor();
                    }
                    if ($user["role"] == "admin") {
                        header("Location: admin-dashboard.php");
                    } elseif ($user["role"] == "teacher") {
                        header("Location: teacher-dashboard.php");
                    } else {
                        header("Location: dashboard.php");
                    }
                    exit;
                } else {
                    $errorMsg = "Incorrect password.";
                }
            } else {
                $errorMsg = "User not found.";
            }
            if (isset($stmt)) {
                $stmt->closeCursor();
            }
        } catch (PDOException $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            $errorMsg = "Unexpected error: " . $e->getMessage();
        }
    } else {
        $errorMsg = 'Database connection failed.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | Student Portal</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .login-box input,
        .login-box select {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .login-box button:hover {
            background: #0056b3;
        }

        .login-box p {
            margin-top: 15px;
            font-size: 14px;
        }

        .login-box a {
            color: #007BFF;
            text-decoration: none;
        }

        .error {
            background-color: #ffe0e0;
            color: #cc0000;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
            font-size: 14px;
        }

        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-wrapper input {
            width: 100%;
            padding-right: 40px;
            box-sizing: border-box;
        }

        .toggle-eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 16px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h2>Login</h2>
        <?php if (!empty($errorMsg)) {
            echo "<div class='error'>" . htmlspecialchars($errorMsg) . "</div>";
        } ?>
        <form method="post">
            <input type="email" name="email" required placeholder="Email" value="<?php echo htmlspecialchars($email); ?>">

            <div class="password-wrapper">
                <input type="password" name="password" id="password" required placeholder="Password">
                <span class="toggle-eye" onclick="togglePassword()">👁️</span>
            </div>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>

    <script>
        function togglePassword() {
            const field = document.getElementById("password");
            field.type = field.type === "password" ? "text" : "password";
        }
    </script>
</body>

</html>