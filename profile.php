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
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        .profile-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .profile-details th, .profile-details td {
            padding: 10px;
        }
        .profile-details th {
            width: 30%;
            text-align: left;
        }
        .profile-details td {
            width: 70%;
        }
        .form-control {
            margin-bottom: 10px;
        }
    </style>
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
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <img src="img/logo.png" alt="Logo">
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link">Home</a>
                <a href="about.php" class="nav-item nav-link">About</a>
                <a href="courses.php" class="nav-item nav-link">Courses</a>
                <div class="nav-item dropdown">
                    <a href="#" class="btn btn-primary py-4 px-lg-5 d-none d-lg-block" data-bs-toggle="dropdown"><?php echo htmlspecialchars($name); ?></a>
                    <div class="dropdown-menu fade-down m-0">
                        <a href="profile.php" class="dropdown-item">Profile</a>
                        <a href="dashboard.php" class="dropdown-item">Dashboard</a>
                        <a href="logout.php" class="dropdown-item">Logout<i class="fa fa-arrow-right ms-3"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

<<<<<<< Updated upstream
    <!-- Profile Section -->
    <div class="container py-5">
        <h1 class="text-center mb-4">User Profile</h1>
        <div class="profile-container">
=======
    
    <div class="container">
        <div class="content">
            <h3>Hi, <span><?php echo htmlspecialchars($email); ?></span></h3>
            <h1>Welcome to Your Profile</h1>
>>>>>>> Stashed changes

            <!-- Profile Viewing Mode -->
            <div id="viewProfile">
                <div class="profile-details">
                    <h3 class="mb-3">Profile Information</h3>
                    <table class="table">
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
                <a href="#" class="btn btn-primary" onclick="enableEdit()">Edit Profile</a>
                <a href="deleteprofile.php" onclick="return confirm('Are you sure you want to delete your account?');" class="btn btn-danger">Delete Profile</a>
            </div>

            <!-- Profile Editing Mode (Hidden by Default) -->
            <div id="editProfile" style="display: none;">
                <form method="POST" action="profile.php">
                    <h3>Edit Profile</h3>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="mobile">Phone Number</label>
                        <input type="text" id="mobile" name="mobile" class="form-control" value="<?php echo htmlspecialchars($mobile); ?>" required>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Update Profile">
                    <a href="#" class="btn btn-secondary" onclick="disableEdit()">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Quick Link</h4>
                    <a class="btn btn-link" href="#">About Us</a>
                    <a class="btn btn-link" href="#">Contact Us</a>
                    <a class="btn btn-link" href="#">Privacy Policy</a>
                    <a class="btn btn-link" href="#">Terms & Condition</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Contact</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Arpico, Sri Lanka</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>contact@arpico.com</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Gallery</h4>
                    <div class="row g-2 pt-2">
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/course-1.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/course-2.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/course-3.jpg" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Newsletter</h4>
                    <p>Subscribe to Newsletter</p>
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <input class="form-control border-0 w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                        <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">SignUp</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">Arpico</a>, All Right Reserved.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="">Home</a>
                            <a href="">Cookies</a>
                            <a href="">Help</a>
                            <a href="">FAQs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Bootstrap JS and other required libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>

    <!-- Custom Template JavaScript -->
    <script src="js/main.js"></script>
</body>
</html>
