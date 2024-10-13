<?php
session_start();
include 'db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, redirect to the login page
    header('location:login.html');
    exit();
}

// Get userId from session
$userId = $_SESSION['userId'];

// Delete the user from the database
$sql = "DELETE FROM user WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // Successfully deleted the profile
        // Destroy the session to log the user out
        session_unset();
        session_destroy();

        // Start a new session to set the deletion confirmation message
        session_start();
        $_SESSION['deleted'] = true; // Indicate account deletion

        // Redirect to the homepage (index.php) after deletion
        header('Location: index.php');
        exit();
    } else {
        echo "Error deleting profile. Please try again.";
    }
    $stmt->close();
} else {
    echo "Error preparing statement. Please try again.";
}

$conn->close(); // Close the database connection
?>
