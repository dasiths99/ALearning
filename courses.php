<?php
session_start();
include 'db.php'; // Ensure this includes your database connection logic

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
}

// Check if userId is set in session
if (!isset($_SESSION['userId'])) {
    echo "User ID not found in session.";
    exit;
}

// Fetch user job role from session
$userJob = $_SESSION['userJob']; // Make sure this is set during login

// Redirect user to the course based on their job role
switch ($userJob) {
    case 'Admin':
        header("Location: test.php");
        break;
    case 'Intern':
        header("Location: intern_courses.php");
        break;
    case 'HR':
        header("Location: hr_courses.php");
        break;
    case 'Manager':
        header("Location: manager_courses.php");
        break;
    default:
        echo "No courses available for your job role.";
        break;
}

exit; // Ensure the script stops executing after the redirect
?>
