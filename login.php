<?php
session_start(); 

include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']); 
    $password = $_POST['password']; 
     

   
    $sql = "SELECT id, name, password, job FROM user WHERE email = ?"; 
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email); 
        $stmt->execute(); 
        $result = $stmt->get_result(); 

        
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc(); 
            if ($password === $row['password']) { 
                $_SESSION['loggedin'] = true;
                $_SESSION['userId'] = $row['id'];
                $_SESSION['userEmail'] = $email;
                $_SESSION['userJob'] = $row['job']; 
                $_SESSION['name'] = $row['name'];

                // Redirect user based on role
                switch ($row['job']) {
                    case 'Admin':
                        header("location: index.php");
                       // $_SESSION['name'] = "User"; 
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
                        header("location: user_dashboard.php");  // Default redirection
                        exit;
                }
            } else {
                echo "Invalid password provided.";
            }
        } else {
            echo "No account found with that email address.";
        }
        $stmt->close(); // Close the statement
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
    $conn->close(); // Close the database connection
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="css/form_styles.css">
</head>
<body>
    <button class="home-button" onclick="location.href='index.php'"><img src="img/home.png"></button>  
    <div class="main">
        <div class="login">
            <img src="img/logo.png" alt="Company Logo" class="logo">
            <form action="login.php" method="POST">
                <label for="chk" aria-hidden="true">Sign In</label>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Sign In</button>
            </form>
        </div>
    </div>
</body>

</html>