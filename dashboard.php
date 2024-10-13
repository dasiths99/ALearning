<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php'; // Ensure you have a database connection here

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet"> <!-- Custom CSS for dashboard -->
</head>
<body>
    <!-- Sidebar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-0">
        <a href="dashboard.php" class="navbar-brand d-flex align-items-center px-4">
            <img src="img/logo.png" alt="Logo" class="rounded-circle" style="width: 50px;">
            <span class="ms-2">Your Profile Dashboard</span>
        </a>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav ms-auto p-4">
                <li class="nav-item">
                    <a href="profile.php" class="nav-link active">Profile</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-primary">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

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
