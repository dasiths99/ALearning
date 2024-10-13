<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php'; // Ensure you have a database connection here
$user_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header('location:login.php');
    exit();
}

// Get logged-in user's ID (assuming it's stored in the session)
$userId = $_SESSION['userId'];

// Query to get course marks from the database for the logged-in user
$sql = "SELECT course1_mark, course2_mark, course3_mark FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die('Execute failed: ' . htmlspecialchars($stmt->error));
}

$row = $result->fetch_assoc();

if ($row === null) {
    die('No data found for user.');
}

$course1 = $row['course1_mark'];
$course2 = $row['course2_mark'];
$course3 = $row['course3_mark'];
$overall = ($course1 + $course2 + $course3) / 3; // Calculate overall average

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>eLEARNING - eLearning HTML Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

 <!-- Navbar Start -->
 <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <img src="img/logo.png">
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link active">Home</a>
                <a href="about.php" class="nav-item nav-link">About</a>
                <a href="courses.php" class="nav-item nav-link">Courses</a>

                <div class="nav-item dropdown">
                    <a href="#" class="btn btn-primary py-4 px-lg-5 d-none d-lg-block" data-bs-toggle="dropdown"><?php echo htmlspecialchars($user_name); ?></a>
                    <div class="dropdown-menu fade-down m-0">
                        <a href="profile.php" class="dropdown-item active">Profile</a>
                        <a href="dashboard.php" class="dropdown-item">Dashboard</a>
                        <a href="404.html" class="dropdown-item"><?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                    <a href="logout.php" class="dropdown-item">Logout<i class="fa fa-arrow-right ms-3"></i></a>
                <?php else: ?>
                    <a href="login.php" class="dropdown-item">Login<i class="fa fa-arrow-right ms-3"></i></a>
                <?php endif; ?> </a>
                    </div>
    </nav>
    <!-- Navbar End -->

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Course 1 Marks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Course 2 Marks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Course 3 Marks
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Content Area -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Course Performance</h1>
            </div>

            <!-- Course Marks Table -->
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Course 1</td>
                            <td><?php echo $course1; ?></td>
                        </tr>
                        <tr>
                            <td>Course 2</td>
                            <td><?php echo $course2; ?></td>
                        </tr>
                        <tr>
                            <td>Course 3</td>
                            <td><?php echo $course3; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Overall Marks</strong></td>
                            <td><strong><?php echo number_format($overall, 2); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
