<?php
include 'db.php';  // Include database connection file


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and filter input data
    $employee_id = $conn->real_escape_string($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $password = $conn->real_escape_string($_POST['password']);
    $job = $conn->real_escape_string($_POST['job']);

    $sql = "INSERT INTO user (id, name, email, mobile, password, job) VALUES ('$id', '$name', '$email', '$mobile', '$password', '$job')";

    if ($conn->query($sql) === TRUE) {
        echo "Registered successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    

// Close connection
$conn->close();

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Page</title>
    <link rel="stylesheet" type="text/css" href="css/form_styles.css">
</head>
<body>
    <button class="home-button" onclick="location.href='index.php'"><img src="img/home.png"></button>  
    <div class="main">
        <div class="signup">
            <img src="img/logo.png" alt="Company Logo" class="logo">
            <form action="register.php" method="post">
                <label for="chk" aria-hidden="true">Register</label>
                <input type="text" name="id" placeholder="Employee ID" required>
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="mobile" placeholder="Mobile" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="job" required>
                    <option value="">Select Account Type</option>
                    <option value="Admin">Admin</option>
                    <option value="Intern">Intern</option>
                    <option value="HR">HR</option>
                    <option value="Manager">Manager</option>
                </select>
                <button type="submit">Register</button>
            </form>
        </div>
    </div>
</body>

</html>

