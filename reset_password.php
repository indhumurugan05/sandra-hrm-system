<?php
include("../backend/connect.php"); // ✅ Corrected path if this file is in /backend or another folder

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = sha1($_POST['new_password']); // or use md5() if that's how it's stored in DB

    // Update password for admin user
    $sql = "UPDATE admin SET password='$new_password' WHERE username='admin'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('Password has been reset successfully!');
            window.location.href = '../frontend/login.html'; // ✅ redirect back to login
        </script>";
    } else {
        echo "❌ Error updating password: " . $conn->error;
    }

    $conn->close();
}
?>
