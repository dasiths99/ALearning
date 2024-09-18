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
