<?php
// Start the session to handle messages
session_start();

// Include the database connection file
require_once 'config/db.php';

// Initialize variables
$student_no = $fullname = $branch = $email = $contact = '';
$errorMessage = '';
$id = 0;

// --- Handle POST Request (Form Submission) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $student_no = filter_input(INPUT_POST, 'student_no', FILTER_SANITIZE_STRING);
    $fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_STRING);
    $branch = filter_input(INPUT_POST, 'branch', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);

    // Basic validation
    if (empty($student_no) || empty($fullname) || empty($branch) || empty($email) || empty($contact)) {
        $errorMessage = "All fields are required.";
    } elseif (!$email) {
        $errorMessage = "Invalid email format.";
    } else {
        try {
            // Prepare an update statement
            $sql = "UPDATE students SET student_no = :student_no, fullname = :fullname, branch = :branch, email = :email, contact = :contact WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':student_no', $student_no);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':branch', $branch);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the prepared statement
            if ($stmt->execute()) {
                // Set success message and redirect to the read page
                $_SESSION['success_message'] = "Record updated successfully.";
                header("location: read.php");
                exit();
            } else {
                $errorMessage = "Something went wrong. Please try again later.";
            }
        } catch (PDOException $e) {
            // Check for duplicate entry error
            if ($e->getCode() == 23000) {
                $errorMessage = "Error: Student Number or Email already exists for another student.";
            } else {
                $errorMessage = "Database error: " . $e->getMessage();
            }
        }
        // Close statement
        unset($stmt);
    }
} else {
    // --- Handle GET Request (Fetching Data for the Form) ---
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        $id = filter_var(trim($_GET["id"]), FILTER_SANITIZE_NUMBER_INT);

        // Prepare a select statement
        $sql = "SELECT * FROM students WHERE id = :id";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    // Fetch result row as an associative array
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Retrieve individual field values
                    $student_no = $row["student_no"];
                    $fullname = $row["fullname"];
                    $branch = $row["branch"];
                    $email = $row["email"];
                    $contact = $row["contact"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page or read page
                    header("location: read.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        // Close statement
        unset($stmt);
    } else {
        // URL doesn't contain id parameter. Redirect to error page or read page
        header("location: read.php");
        exit();
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
    <title>Update Student Record</title>
    <!-- Reusing the same styles from create.php -->
    <link rel="stylesheet" href="styles.css"> <!-- Assuming you have a shared stylesheet -->
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; }
        input[type="text"], input[type="email"], select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background-color: #0056b3; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .back-link { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <h2>Update Student Record</h2>

    <?php if (!empty($errorMessage)): ?>
        <div class="error"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
        <div class="form-group">
            <label>Student Number</label>
            <input type="text" name="student_no" value="<?php echo htmlspecialchars($student_no); ?>" required>
        </div>
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>
        </div>
        <div class="form-group">
            <label>Branch</label>
            <select name="branch" required>
                <option value="">-- Select Branch --</option>
                <?php $branches = ["IT" => "Information Technology", "CS" => "Computer Science", "ME" => "Mechanical Engineering", "EE" => "Electrical Engineering", "CE" => "Civil Engineering"]; ?>
                <?php foreach ($branches as $key => $value): ?>
                    <option value="<?php echo $key; ?>" <?php echo ($branch == $key) ? 'selected' : ''; ?>><?php echo $value; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="form-group">
            <label>Contact</label>
            <input type="text" name="contact" value="<?php echo htmlspecialchars($contact); ?>" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Update Record" class="btn">
            <a href="read.php" class="back-link">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>