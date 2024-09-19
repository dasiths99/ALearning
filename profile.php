<?php
session_start(); // Start or resume the session

include 'db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, redirect to the login page
    header('location:login.html');
    exit();
}

// Get user details from session
$email = $_SESSION['userEmail'];
$job = $_SESSION['userJob'];

// Fetch additional user details from the database using the session's user ID
$userId = $_SESSION['userId'];
$sql = "SELECT * FROM user WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $mobile = $row['mobile'];
    } else {
        // Handle user not found scenario
        $name = '';
        $mobile = '';
    }
    $stmt->close();
} else {
    echo "Oops! Something went wrong. Please try again later.";
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Arpico | User Profile</title>
    <link href="css/profile.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="content">
            <h3>Hi, <span><?php echo htmlspecialchars($email); ?></span></h3>
            <h1>Welcome to Your Profile</h1>
            <div class="profile-details">
                <h2>User Information</h2>
                <table>
                    <tr>
                        <th>Full Name:</th>
                        <td><?php echo htmlspecialchars($name); ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo htmlspecialchars($email); ?></td>
                    </tr>
                    <tr>
                        <th>Phone Number:</th>
                        <td><?php echo htmlspecialchars($mobile); ?></td>
                    </tr>
                    <tr>
                        <th>Job Title:</th>
                        <td><?php echo htmlspecialchars($job); ?></td>
                    </tr>
                </table>
            </div>
            <a href="editprofile.php" class="btn">Edit Profile</a>
            <a href="deleteprofile.php" onclick="return confirm('Are you sure you want to delete your account?');" class="btn">Delete Profile</a>
        </div>
    </div>
</body>
</html>
