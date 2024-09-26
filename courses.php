<?php
session_start(); 

include 'db.php'; 




                // Redirect user based on role
                switch ($row['job']) {
                    case 'Admin':
                        header("location: index.php");
                       // $_SESSION['name'] = "User"; 
                        exit;
                    case 'Intern':
                        header("location: index.php");
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
                
        $stmt->close(); // Close the statement

?>