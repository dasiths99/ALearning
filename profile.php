<?php
session_start(); // Start or resume the session

include 'db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, redirect to the login page
    header('location:login.php');
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

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_mobile = $_POST['mobile'];

    // Update user details in the database
    $update_sql = "UPDATE user SET name = ?, email = ?, mobile = ? WHERE id = ?";
    if ($stmt = $conn->prepare($update_sql)) {
        $stmt->bind_param("sssi", $new_name, $new_email, $new_mobile, $userId);
        if ($stmt->execute()) {
            echo "Profile updated successfully.";
            // Optionally refresh the session variables
            $_SESSION['userEmail'] = $new_email;
            header("Location: profile.php");
            exit();
        } else {
            echo "Error updating profile.";
        }
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Arpico | User Profile</title>
    <!-- Favicon -->

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/profile.css" rel="stylesheet">

    <script>
        function enableEdit() {
            document.getElementById('viewProfile').style.display = 'none'; // Hide profile view
            document.getElementById('editProfile').style.display = 'block'; // Show edit form
        }

        function disableEdit() {
            document.getElementById('viewProfile').style.display = 'block'; // Show profile view
            document.getElementById('editProfile').style.display = 'none'; // Hide edit form
        }
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <img src="img/logo.png">
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="index.html" class="nav-item nav-link active">Home</a>
            <a href="about.html" class="nav-item nav-link">About</a>
            <a href="courses.php" class="nav-item nav-link">Courses</a>

            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                <a href="logout.php" class="btn btn-primary py-4 px-lg-5 d-none d-lg-block">Logout<i class="fa fa-arrow-right ms-3"></i></a>
            <?php endif; ?>
    </nav>
    <!-- Navbar End -->

    <div class="container">
        <div class="content">
            <h3>Hi, <span><?php echo htmlspecialchars($email); ?></span></h3>
            <h1>Welcome to Your Profile</h1>

            <!-- Profile Viewing Mode -->
            <div id="viewProfile">
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
                <a href="#" class="btn" onclick="enableEdit()">Edit Profile</a>
                <a href="deleteprofile.php" onclick="return confirm('Are you sure you want to delete your account?');" class="btn">Delete Profile</a>

            </div>

            <!-- Profile Editing Mode (Hidden by Default) -->
            <div id="editProfile" style="display: none;">
                <form method="POST" action="profile.php">
                    <table>
                        <tr>
                            <th>Full Name:</th>
                            <td><input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required></td>
                        </tr>
                        <tr>
                            <th>Phone Number:</th>
                            <td><input type="text" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>" required></td>
                        </tr>
                    </table>
                    <input type="submit" class="btn btn-primary" value="Upload">
                    <a href="#" class="btn btn-secondary" onclick="disableEdit()">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
