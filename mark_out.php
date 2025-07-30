<?php
include 'connect.php';

date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d");
$out_time = date("H:i:s");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM employee WHERE user_id=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user_id, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $emp_id = $row['emp_id'];

        $sql = "SELECT * FROM attendance WHERE emp_id=? AND date=? AND status='Present'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $emp_id, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($att = $result->fetch_assoc()) {
            $in_time = $att['in_time'];
            $start = strtotime($in_time);
            $end = strtotime($out_time);
            $hours_worked = round(($end - $start) / 3600, 2);

            $update = "UPDATE attendance SET out_time=?, hours_worked=? WHERE emp_id=? AND date=?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("sdss", $out_time, $hours_worked, $emp_id, $date);
            if ($stmt->execute()) {
                echo "<script>alert('Checked out successfully!');window.location.href='../frontend/check_out.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "<script>alert('No check-in record found!');window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Invalid User ID or Password');window.history.back();</script>";
    }
}
?>
