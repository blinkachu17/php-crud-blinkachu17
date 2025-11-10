<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Branch Directory System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9f2f9; /* Light blue background */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        .container {
            background-color: #ffffff;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        h1 {
            color: #007bff; /* Primary blue for title */
            margin-bottom: 30px;
            font-size: 2.2em;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }
        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .nav-menu li {
            margin-bottom: 15px;
        }
        .nav-menu a {
            display: block;
            padding: 12px 20px;
            background-color: #007bff; /* Button background */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            font-size: 1.1em;
        }
        .nav-menu a:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transform: translateY(-2px); /* Slight lift effect */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Branch Directory System</h1>
        <ul class="nav-menu">
            <li><a href="create.php">Add Student</a></li>
            <li><a href="read.php">View Students</a></li>
            <li><a href="read.php">Update Student</a></li> <!-- Update is initiated from the read page -->
            <li><a href="read.php">Delete Student</a></li> <!-- Delete is initiated from the read page -->
        </ul>
    </div>
</body>
</html>