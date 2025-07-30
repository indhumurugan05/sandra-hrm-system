<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include '../backend/connect.php';

$query = "
    SELECT s.*, e.name 
    FROM salaries s 
    JOIN employees e ON s.emp_id = e.emp_id 
    ORDER BY s.year DESC, s.month DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Monthly Salary Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table-container {
            margin: 30px auto;
            max-width: 95%;
        }
        table {
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
<div class="container table-container">
    <h2 class="text-center my-4">🧾 Monthly Salary Report</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark text-center">
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Month</th>
                <th>Year</th>
                <th>Total Working Days</th>
                <th>Present Days</th>
                <th>Final Salary (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr class="text-center">
                    <td><?= $row['emp_id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= date('F', mktime(0, 0, 0, $row['month'], 10)) ?></td>
                    <td><?= $row['year'] ?></td>
                    <td><?= $row['total_days'] ?></td>
                    <td><?= $row['present_days'] ?></td>
                    <td>₹<?= number_format($row['final_salary'], 2) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
