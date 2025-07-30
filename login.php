<?php
session_start(); 
include("connect.php"); 

$username = $_POST['username'];
$password = sha1($_POST['password']); 
$sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $_SESSION['admin'] = $username; 
    header("Location: ../frontend/dashboard.html");
    exit();
} else {
    
    echo "<script>
        alert('Invalid username or password!');
        window.location.href ='../frontend/login.html';
    </script>";
}

$conn->close();
?>

