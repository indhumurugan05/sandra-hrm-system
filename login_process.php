<?php
session_start();
include 'connect.php';  // connect.php is in the same backend folder

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['admin'] = $username;
            header("Location: /Sandra_HRM/frontend/dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password'); window.location.href='../frontend/login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Admin not found'); window.location.href='../frontend/login.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid request method'); window.location.href='../frontend/login.php';</script>";
    exit();
}
?>
