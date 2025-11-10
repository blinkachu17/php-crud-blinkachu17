<?php
// Initialize message variables
$successMessage = '';
$errorMessage = '';

// Include the database connection file
require_once 'config/db.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $studentNumber = filter_input(INPUT_POST, 'student_number', FILTER_SANITIZE_STRING);
    $fullName = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
    $branch = filter_input(INPUT_POST, 'branch', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);

    // Basic validation
    if (empty($studentNumber) || empty($fullName) || empty($branch) || empty($email) || empty($contact)) {
        $errorMessage = "All fields are required.";
    } elseif (!$email) {
        $errorMessage = "Invalid email format.";
    } else {
        try {
            // Prepare an insert statement
            $sql = "INSERT INTO students (student_no, fullname, branch, email, contact) VALUES (:student_no, :fullname, :branch, :email, :contact)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':student_no', $studentNumber);
            $stmt->bindParam(':fullname', $fullName);
            $stmt->bindParam(':branch', $branch);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':contact', $contact);

            // Execute the prepared statement
            if ($stmt->execute()) {
                $successMessage = "New student record created successfully.";
            } else {
                $errorMessage = "Something went wrong. Please try again later.";
            }
        } catch (PDOException $e) {
            // Check for duplicate entry
            if ($e->getCode() == 23000) {
                $errorMessage = "Error: Student Number or Email already exists.";
            } else {
                $errorMessage = "Database error: " . $e->getMessage();
            }
        }

        // Close statement
        unset($stmt);
    }
}

// Close connection
unset($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; }
        input[type="text"], input[type="email"], select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background-color: #0056b3; }
        .message { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .home-link { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; }
        .home-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Student Record</h2>

    <?php if (!empty($successMessage)): ?>
        <div class="message success"><?php echo $successMessage; ?></div>
    <?php endif; ?>
    <?php if (!empty($errorMessage)): ?>
        <div class="message error"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="student_number">Student Number</label>
            <input type="text" name="student_number" id="student_number" required>
        </div>
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" name="full_name" id="full_name" required>
        </div>
        <div class="form-group">
            <label for="branch">Branch</label>
            <select name="branch" id="branch" required>
                <option value="">-- Select Branch --</option>
                <option value="IT">Information Technology</option>
                <option value="CS">Computer Science</option>
                <option value="ME">Mechanical Engineering</option>
                <option value="EE">Electrical Engineering</option>
                <option value="CE">Civil Engineering</option>
            </select>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="contact">Contact</label>
            <input type="text" name="contact" id="contact" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Add Student" class="btn">
        </div>
    </form>

    <a href="index.php" class="home-link">&larr; Back to Homepage</a>
</div>

</body>
</html>