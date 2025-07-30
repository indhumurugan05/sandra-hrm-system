<?php
session_start();
if (!isset($_SESSION['admin'])) {
    $_SESSION['error'] = "Session expired. Please login again.";
    header("Location: login.php");
    exit();
}

include("../backend/connect.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Attendance</title>
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 95%;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 16px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        .back:hover {
            background-color: #2c80b4;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            font-size: 15px;
        }

        th {
            background-color: #f8f8f8;
            color: #444;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .no-data {
            text-align: center;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a class="back" href="dashboard.php">← Back to Dashboard</a>
        <h2>Employee Attendance Records</h2>

        <div class="table-wrapper">
        <?php
        $sql = "SELECT emp_id, name, date, in_time, out_time, working_hours, status FROM attendance ORDER BY date DESC, in_time DESC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Emp ID</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Working Hours</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['emp_id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= date('d-m-Y', strtotime($row['date'])) ?></td>
                        <td><?= $row['in_time'] ?: '-' ?></td>
                        <td><?= $row['out_time'] ?: '-' ?></td>
                        <td><?= $row['working_hours'] ?: '-' ?></td>
                        <td><?= $row['status'] ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No attendance records found.</p>
        <?php endif; ?>
        </div>
    </div>
</body>
</html>
