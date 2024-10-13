<?php
session_start(); 

include 'db.php'; 

$error_message = ""; // Initialize an empty general error message
$email_err = $password_err = ""; // Initialize variables for validation errors

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = "Please enter your email.";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = $conn->real_escape_string($_POST['email']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = "Please enter your password.";
    } else {
        $password = $_POST['password'];
    }

    // Proceed only if there are no validation errors
    if (empty($email_err) && empty($password_err)) {
        // SQL query to check user details
        $sql = "SELECT id, name, password, job FROM user WHERE email = ?"; 
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email); 
            $stmt->execute(); 
            $result = $stmt->get_result(); 

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc(); 
                if ($password === $row['password']) { 
                    // Set session variables and redirect
                    $_SESSION['loggedin'] = true;
                    $_SESSION['userId'] = $row['id'];
                    $_SESSION['userEmail'] = $email;
                    $_SESSION['userJob'] = $row['job']; 
                    $_SESSION['name'] = $row['name'];

                    // Redirect user based on role
                    switch ($row['job']) {
                        case 'Admin':
                            header("location: index.php");
                            exit;
                        case 'Intern':
                            header("location: index.php");
                            exit;
                        case 'HR':
                            header("location: hr_dashboard.php");
                            exit;
                        case 'Manager':
                            header("location: manager_dashboard.php");
                            exit;
                        default:
                            header("location: user_dashboard.php");
                            exit;
                    }
                } else {
                    $error_message = "Invalid password provided."; // Set error message for invalid password
                }
            } else {
                $error_message = "No account found with that email address."; // Set error message for email not found
            }
            $stmt->close(); 
        } else {
            $error_message = "Oops! Something went wrong. Please try again later."; // Set error message for SQL error
        }
    }
    $conn->close(); 
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="css/form_styles.css">
    <style>
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <button class="home-button" onclick="location.href='index.php'"><img src="img/home.png"></button>  
    <div class="main">
        <div class="login">
            <img src="img/logo.png" alt="Company Logo" class="logo">
            <form action="login.php" method="POST" novalidate>
                <label for="chk" aria-hidden="true">Sign In</label>

                <!-- Display general error message (e.g., wrong password, email not found) -->
                <?php if (!empty($error_message)) : ?>
                    <div class="error"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <!-- Email input field with validation and error display -->
                <input type="email" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                <?php if (!empty($email_err)) : ?>
                    <div class="error"><?php echo $email_err; ?></div>
                <?php endif; ?>

                <!-- Password input field with validation and error display -->
                <input type="password" name="password" placeholder="Password" required>
                <?php if (!empty($password_err)) : ?>
                    <div class="error"><?php echo $password_err; ?></div>
                <?php endif; ?>

                <button type="submit">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>
