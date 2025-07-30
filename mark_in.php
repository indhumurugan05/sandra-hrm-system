<?php
include 'connect.php';

date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d");
$time = date("H:i:s");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    // Check employee credentials
    $sql = "SELECT * FROM employee WHERE user_id=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user_id, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Insert into attendance
        $emp_id = $row['emp_id'];
        $name = $row['name'];
        $department = $row['department'];
        $designation = $row['designation'];
        $work_location = $row['work_location'];

        // Check if already checked-in
        $check_sql = "SELECT * FROM attendance WHERE emp_id=? AND date=? AND status='Present'";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $emp_id, $date);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo "<script>alert('Already checked in today');window.history.back();</script>";
        } else {
            $insert = "INSERT INTO attendance (emp_id, name, department, designation, work_location, date, in_time, status)
                       VALUES (?, ?, ?, ?, ?, ?, ?, 'Present')";
            $stmt = $conn->prepare($insert);
            $stmt->bind_param("sssssss", $emp_id, $name, $department, $designation, $work_location, $date, $time);
            if ($stmt->execute()) {
                echo "<script>alert('Check-in successful!');window.location.href='../frontend/check_in.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    } else {
        echo "<script>alert('Invalid User ID or Password');window.history.back();</script>";
    }
}
?>
