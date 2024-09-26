<?php
session_start();

include 'db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.html"); // Ensure the user is redirected to login if not logged in
    exit;
}


$user_name = isset($_SESSION['userJob']) ? $_SESSION['userJob'] : 'fuck';

echo "<p>Welcome, " . htmlspecialchars($user_name) . "!</p>";


session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Optionally include a CSS file to style your page -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Logout Button -->
    <form action="logout.php" method="post">
        <button type="submit" name="logout">Logout</button>
    </form>

    <!-- Optionally add more content here -->
</body>
</html>
