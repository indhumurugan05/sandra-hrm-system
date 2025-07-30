<?php
session_start();
include '../backend/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed);

        if ($stmt->execute()) {
            echo "<script>alert('Admin added successfully!'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error adding admin: {$stmt->error}');</script>";
        }
    } else {
        echo "<script>alert('All fields are required');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 500px;">
        <h2 class="text-center mb-4">➕ Add New Admin</h2>
        <form method="POST" action="add_admin.php">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required class="form-control" placeholder="New admin username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required class="form-control" placeholder="New admin password">
            </div>
            <button type="submit" class="btn btn-success btn-block">Create Admin</button>
            <a href="login.php" class="btn btn-secondary btn-block">Back to Login</a>
        </form>
    </div>
</body>
</html>