<?php
session_start(); // Start or resume the session

include 'db.php'; // Ensure this file contains the necessary database connection setup

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']); // Retrieve and escape the email
    $password = $_POST['password']; // Retrieve the password directly
    $job = $_POST['job']; // Assuming the job is posted from the form, which might not be the case

    // SQL to check the existence of the user with the given email, and fetch the job
    $sql = "SELECT id, password, job FROM user WHERE email = ?"; // Include job in your SQL query
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email); // Bind the email variable to the query
        $stmt->execute(); // Execute the query
        $result = $stmt->get_result(); // Get the result of the query

        // Check if there is exactly one user with this email
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc(); // Fetch the result row as an associative array
            if ($password === $row['password']) { // Direct comparison
                // Password is correct, set up session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['userId'] = $row['id'];
                $_SESSION['userEmail'] = $email;
                $_SESSION['userJob'] = $row['job'];  // Store user role in session

                // Redirect user based on role
                switch ($row['job']) {
                    case 'Admin':
                        header("location: profile.php");
                        exit;
                    case 'Intern':
                        header("location: index.html");
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
