<?php
// Include the database connection file
require_once 'config/db.php';

try {
    // Prepare a select statement to fetch all students
    $sql = "SELECT * FROM students ORDER BY id DESC";
    $stmt = $pdo->query($sql);
} catch (PDOException $e) {
    // If there is an error, terminate the script and display the error
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student Records</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        tr:hover { background-color: #e9ecef; }
        .actions a { color: #007bff; text-decoration: none; margin-right: 10px; }
        .actions a:hover { text-decoration: underline; }
        .actions a.delete { color: #dc3545; }
        .home-link { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; }
        .home-link:hover { text-decoration: underline; }
        .no-records { text-align: center; color: #777; padding: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Student Records</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Student No</th>
                <th>Full Name</th>
                <th>Branch</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Date Added</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($stmt->rowCount() > 0): ?>
                <?php while ($row = $stmt->fetch()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['student_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($row['branch']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_added']); ?></td>
                    <td class="actions">
                        <a href="update.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="no-records">No records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="index.php" class="home-link">&larr; Back to Homepage</a>
</div>

<?php
// Close statement and connection
unset($stmt);
unset($pdo);
?>

</body>
</html>