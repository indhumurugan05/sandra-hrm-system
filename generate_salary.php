<?php 
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

include '../backend/connect.php';

$month = date('m');
$year = date('Y');

// Calculate working days (Mon–Fri)
$total_days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$working_days = 0;
for ($day = 1; $day <= $total_days_in_month; $day++) {
    $timestamp = strtotime("$year-$month-$day");
    $weekday = date('N', $timestamp);
    if ($weekday < 6) $working_days++;
}

// Fetch all employees
$employee_result = mysqli_query($conn, "SELECT * FROM employees");

while ($employee = mysqli_fetch_assoc($employee_result)) {
    $emp_id = $employee['emp_id'];
    $name = $employee['name'];
    $monthly_salary = $employee['salary']; // Full monthly salary

    // Count present days in the current month
    $attendance_query = "
        SELECT COUNT(*) as present_days 
        FROM attendance 
        WHERE emp_id = '$emp_id'
        AND MONTH(date) = '$month' 
        AND YEAR(date) = '$year' 
        AND status = 'Present'
    ";
    $attendance_result = mysqli_query($conn, $attendance_query);
    $attendance = mysqli_fetch_assoc($attendance_result);
    $present_days = (int)$attendance['present_days'];

    // Calculate salary per working day
    $daily_salary = $monthly_salary / $working_days;
    $final_salary = round($daily_salary * $present_days, 2);

    // Insert or update salary record
    $check_query = "SELECT id FROM salaries WHERE emp_id='$emp_id' AND month='$month' AND year='$year'";
    $exists = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($exists) > 0) {
        $update_query = "
            UPDATE salaries 
            SET present_days='$present_days', total_days='$working_days', final_salary='$final_salary' 
            WHERE emp_id='$emp_id' AND month='$month' AND year='$year'
        ";
        mysqli_query($conn, $update_query);
    } else {
        $insert_query = "
            INSERT INTO salaries (emp_id, month, year, total_days, present_days, final_salary)
            VALUES ('$emp_id', '$month', '$year', '$working_days', '$present_days', '$final_salary')
        ";
        mysqli_query($conn, $insert_query);
    }

    echo "💰 Salary generated for <strong>$name</strong>: ₹<strong>$final_salary</strong><br>";
}

echo "<hr>✅ Salary generation completed for <strong>$month/$year</strong>.";
?>
