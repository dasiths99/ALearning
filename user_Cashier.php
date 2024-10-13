<?php
session_start();
include 'db.php'; // Ensure you have a database connection here

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header('location:login.php');
    exit();
}

// Define quiz questions and correct answers
$questions = [
    "What is the primary responsibility of a cashier in terms of data security?" => [
        "options" => ["Ensuring customer information is kept confidential", "Sharing customer data with team members", "Storing customer data openly"],
        "correct" => "Ensuring customer information is kept confidential"
    ],
    "How should sensitive customer data be handled during a transaction?" => [
        "options" => ["Left visible on the screen", "Secured and not shared", "Stored on personal devices"],
        "correct" => "Secured and not shared"
    ],
    "What should you do if you notice unusual transaction activity?" => [
        "options" => ["Complete the transaction", "Report it immediately", "Ignore it"],
        "correct" => "Report it immediately"
    ],
    "Who should have access to transaction records?" => [
        "options" => ["Everyone in the team", "Only authorized personnel", "External vendors"],
        "correct" => "Only authorized personnel"
    ],
    "How often should passwords for transaction systems be updated?" => [
        "options" => ["Once a year", "When management requests", "Regularly as per company policy"],
        "correct" => "Regularly as per company policy"
    ],
    "What is the first action you should take in case of a suspected breach?" => [
        "options" => ["Log off and leave", "Report it to IT immediately", "Wait to see if it happens again"],
        "correct" => "Report it to IT immediately"
    ],
    "How should physical receipts with sensitive information be stored?" => [
        "options" => ["In locked, secure storage", "Left at the cashier desk", "Disposed of immediately"],
        "correct" => "In locked, secure storage"
    ],
    "What is the importance of logging out from the system after each shift?" => [
        "options" => ["Saves electricity", "Prevents unauthorized access to customer data", "It’s a habit"],
        "correct" => "Prevents unauthorized access to customer data"
    ],
    "What is a safe practice when handling customer card details?" => [
        "options" => ["Writing them down for future reference", "Using secure payment systems", "Sharing them with your colleagues"],
        "correct" => "Using secure payment systems"
    ],
    "What should you do if a customer asks for sensitive transaction data to be shared?" => [
        "options" => ["Provide it upon request", "Refer them to the customer service team", "Share it if the transaction was recent"],
        "correct" => "Refer them to the customer service team"
    ]
];

// Process form submission and calculate score
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $score = 0;
    foreach ($questions as $question => $details) {
        if (isset($_POST[md5($question)]) && $_POST[md5($question)] == $details['correct']) {
            $score += 10; // Each correct answer is worth 10 points
        }
    }

    // Debugging: Check the calculated score
    echo "Calculated Score: $score<br>";

    // Update the score in the database
    if (isset($_SESSION['userId'])) {
        $userId = $_SESSION['userId'];

        // Prepare SQL query
        $sql = "UPDATE user SET course1_mark = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        // Check if the prepare was successful
        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }

        // Bind the parameters
        $stmt->bind_param("ii", $score, $userId);

        // Execute the query
        if ($stmt->execute()) {
            echo "Your score is $score/100. Your course1 mark has been updated.";
        } else {
            // Debugging: Check if there's an issue executing the query
            echo "Error updating your marks: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: User ID not found in the session.";
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"> <!-- Bootstrap for styling -->
    <link href="css/style.css" rel="stylesheet"> <!-- Your custom CSS -->
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Cashier Security Quiz</h1>
        <form method="POST" action="quiz.php">
            <?php foreach ($questions as $question => $details): ?>
                <div class="mb-4">
                    <h4><?php echo $question; ?></h4>
                    <?php foreach ($details['options'] as $option): ?>
                        <div>
                            <input type="radio" name="<?php echo md5($question); ?>" value="<?php echo $option; ?>" required>
                            <label><?php echo $option; ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <input type="submit" class="btn btn-primary" value="Submit Quiz">
        </form>
    </div>
</body>
</html>
