<?php
session_start(); // Start or resume the session

include 'db.php'; // Ensure this file contains the necessary database connection setup

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']); // Retrieve and escape the email
    $password = $_POST['password']; // Retrieve the password directly

    // SQL to check the existence of the user with the given email
    $sql = "SELECT id, password FROM user WHERE email = ?"; // Assuming the table name is 'user'
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

                // Redirect to a welcome page or dashboard
                header("location: index.html");
                exit;
            } else {
                // Password is incorrect
                echo "Invalid password provided.";
            }
        } else {
            // No user found with the email
            echo "No account found with that email address.";
        }
        $stmt->close(); // Close the statement
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
    $conn->close(); // Close the database connection
}
?>
