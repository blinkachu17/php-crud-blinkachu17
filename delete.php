<?php
session_start();

// Include the database connection file
require_once 'config/db.php';

// Initialize variables
$id = $fullname = '';
$confirmMessage = '';

// --- Handle GET Request (Confirmation Form) ---
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = filter_var(trim($_GET["id"]), FILTER_SANITIZE_NUMBER_INT);

    // Prepare a select statement
    $sql = "SELECT id, fullname FROM students WHERE id = :id";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                // Fetch result row as an associative array
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $fullname = htmlspecialchars($row["fullname"]); // Sanitize output
            } else {
                // URL doesn't contain valid id. Redirect to error page or read page
                header("location: read.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        // Close statement
        unset($stmt);
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    // --- Handle POST Request (Deletion) ---
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    // Prepare a delete statement
    $sql = "DELETE FROM students WHERE id = :id";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Set success message and redirect to the read page
            $_SESSION['success_message'] = "Record deleted successfully.";
            header("location: read.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        // Close statement
        unset($stmt);
    }
    // Close connection
    unset($pdo);
} else {
    // URL doesn't contain id parameter. Redirect to error page or read page
    header("location: read.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Student Record</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        .warning { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .warning p { margin: 0; }
        .btn { background-color: #dc3545; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background-color: #c82333; }
        .cancel-btn { background-color: #6c757d; }
        .cancel-btn:hover { background-color: #5a6268; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Delete Student Record</h2>
        <div class="warning">
            <p><strong>WARNING:</strong> You are about to delete the record for <strong><?php echo $fullname; ?></strong>. This action cannot be undone.</p>
        </div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="submit" value="Confirm Delete" class="btn">
            <a href="read.php" class="btn cancel-btn">Cancel</a>
        </form>
    </div>
</body>
</html>