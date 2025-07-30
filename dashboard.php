<?php
session_start();
include '../backend/connect.php';  // adjust path if needed

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$total_employees = $conn->query("SELECT COUNT(*) as count FROM employee")->fetch_assoc()['count'];
$today = date('Y-m-d');
$checked_in_today = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE date='$today' AND status='Present'")->fetch_assoc()['count'];
$checked_out_today = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE date='$today' AND out_time IS NOT NULL")->fetch_assoc()['count'];
$todays_attendance = $conn->query("SELECT COUNT(DISTINCT emp_id) as count FROM attendance WHERE date='$today'")->fetch_assoc()['count'];
$salary_records = $conn->query("SELECT COUNT(*) as count FROM salaries")->fetch_assoc()['count'];

// Which table to show? From URL param "show"
$show_section = $_GET['show'] ?? '';
$valid_sections = ['employees', 'checkin', 'checkout', 'attendance', 'salary'];
if (!in_array($show_section, $valid_sections)) {
    $show_section = ''; // fallback to no table shown
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Admin Dashboard - Sandra HRM</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<style>
    body {
        background-color: #f8f9fa;
        margin: 0;
        font-family: Arial, sans-serif;
    }

    /* Sidebar styles */
    .sidebar {
        height: 100vh;
        width: 0;
        position: fixed;
        z-index: 1050;
        top: 0;
        left: 0;
        background-color: #343a40;
        overflow-x: hidden;
        transition: 0.3s;
        padding-top: 60px;
    }

    .sidebar.open {
        width: 280px;
    }

    .sidebar a.card {
        margin: 10px 15px;
        padding: 15px;
        border-radius: 12px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-decoration: none;
        transition: background-color 0.2s ease;
        position: relative;
    }
    /* Use different background colors for cards inside sidebar */
    .sidebar a.bg-primary {background-color: #0d6efd;}
    .sidebar a.bg-success {background-color: #198754;}
    .sidebar a.bg-danger {background-color: #dc3545;}
    .sidebar a.bg-warning {background-color: #ffc107; color: #212529;}
    .sidebar a.bg-secondary {background-color: #6c757d;}
    
    .sidebar a.card:hover {
        background-color: #495057;
        color: white;
    }

    .sidebar a.card .icon {
        font-size: 2rem;
        opacity: 0.3;
    }

    /* Hamburger button */
    .hamburger {
        font-size: 2rem;
        cursor: pointer;
        color: #0d6efd;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1100;
        background: white;
        border-radius: 4px;
        padding: 5px 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    /* Content wrapper with margin-left when sidebar open */
    .content-wrapper {
        margin-left: 0;
        transition: margin-left 0.3s ease;
        padding: 40px 20px 20px 20px;
    }

    .content-wrapper.shifted {
        margin-left: 280px;
    }

    /* Table styles */
    h2, h4 {
        color: #0d6efd;
    }
    .thead-dark th {
        background-color: #0d6efd;
        color: white;
    }
    .table-bordered {
        background: white;
    }
    .back-btn {
        margin: 20px 0;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .content-wrapper.shifted {
            margin-left: 0;
        }
        .sidebar.open {
            width: 100%;
        }
    }
</style>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content-wrapper');
        sidebar.classList.toggle('open');
        content.classList.toggle('shifted');
    }
</script>
</head>
<body>

<!-- Hamburger button -->
<div class="hamburger" onclick="toggleSidebar()" aria-label="Toggle menu" role="button" tabindex="0">
    <i class="fa fa-bars"></i>
</div>

<!-- Sidebar navigation with cards -->
<div id="sidebar" class="sidebar" aria-label="Sidebar navigation">
    <a href="?show=employees" class="card bg-primary" role="link" tabindex="0">
        <span>Total Employees</span>
        <span class="badge bg-light text-primary fs-5"><?= $total_employees ?></span>
        <i class="fa-solid fa-users icon"></i>
    </a>
    <a href="?show=checkin" class="card bg-success" role="link" tabindex="0">
        <span>Checked-In Today</span>
        <span class="badge bg-light text-success fs-5"><?= $checked_in_today ?></span>
        <i class="fa-solid fa-sign-in icon"></i>
    </a>
    <a href="?show=checkout" class="card bg-danger" role="link" tabindex="0">
        <span>Checked-Out Today</span>
        <span class="badge bg-light text-danger fs-5"><?= $checked_out_today ?></span>
        <i class="fa-solid fa-sign-out icon"></i>
    </a>
    <a href="?show=attendance" class="card bg-warning" role="link" tabindex="0" style="color:#212529;">
        <span>Today's Attendance</span>
        <span class="badge bg-light text-warning fs-5"><?= $todays_attendance ?></span>
        <i class="fa-solid fa-calendar-check icon"></i>
    </a>
    <a href="?show=salary" class="card bg-secondary" role="link" tabindex="0">
        <span>Salary Records</span>
        <span class="badge bg-light text-secondary fs-5"><?= $salary_records ?></span>
        <i class="fa-solid fa-dollar-sign icon"></i>
    </a>
</div>
<div>
<?php if (isset($_SESSION['admin'])): ?>
    <div style="position: fixed; bottom: 20px; width: 100%; text-align: center;">
        <a href="logout.php" class="btn btn-danger"><strong>LOGOUT</strong></a>
<?php endif; ?>
</div>


    <div class="container mt-4">
    <h2 class="text-center text-primary mb-4">Admin Dashboard</h2>
    <!-- Welcome Box -->
    <div class="welcome-box text-center p-4 mb-5 rounded shadow-sm bg-light" style="border-left: 8px solid #4CAF50;">
        <h4 class="mb-3" style="color:#2e7d32;"><strong>Welcome, Admin!</strong></h4>
        <p style="font-size: 16px; color:#444;">
            You're now at the heart of the <strong>Sandra HRM</strong> system. 
            Seamlessly access employee records, monitor daily attendance, and manage payroll—all in one place. 
            Your mission to maintain a smarter and smoother workforce begins here. 🚀
        </p>
    </div>
</div>

    <!-- Show Table Based On Click -->
    <?php if ($show_section === 'employees'): ?>
        <h4>Total Employees</h4>
        <table class="table table-bordered table-hover table-sm">
            <thead class="thead-dark">
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Department</th></tr>
            </thead>
            <tbody>
            <?php
            $res = $conn->query("SELECT * FROM employee");
            while ($row = $res->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['emp_id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['department']}</td>
                </tr>";
            }
            ?>
            </tbody>
        </table>
    <?php elseif ($show_section === 'checkin'): ?>
        <h4>Checked-In Today</h4>
        <table class="table table-bordered table-hover table-sm">
            <thead class="thead-dark">
                <tr><th>Employee ID</th><th>Name</th><th>Check-In Time</th></tr>
            </thead>
            <tbody>
            <?php
            $res = $conn->query("SELECT a.emp_id, e.name, a.in_time FROM attendance a 
                JOIN employee e ON a.emp_id = e.emp_id 
                WHERE a.date='$today' AND a.status='Present'");
            while ($row = $res->fetch_assoc()) {
                echo "<tr><td>{$row['emp_id']}</td><td>{$row['name']}</td><td>{$row['in_time']}</td></tr>";
            }
            ?>
            </tbody>
        </table>
    <?php elseif ($show_section === 'checkout'): ?>
        <h4>Checked-Out Today</h4>
        <table class="table table-bordered table-hover table-sm">
            <thead class="thead-dark">
                <tr><th>Employee ID</th><th>Name</th><th>Check-Out Time</th></tr>
            </thead>
            <tbody>
            <?php
            $res = $conn->query("SELECT a.emp_id, e.name, a.out_time FROM attendance a 
                JOIN employee e ON a.emp_id = e.emp_id 
                WHERE a.date='$today' AND a.out_time IS NOT NULL");
            while ($row = $res->fetch_assoc()) {
                echo "<tr><td>{$row['emp_id']}</td><td>{$row['name']}</td><td>{$row['out_time']}</td></tr>";
            }
            ?>
            </tbody>
        </table>
    <?php elseif ($show_section === 'attendance'): ?>
        <h4>Today's Attendance (IN & OUT)</h4>
        <table class="table table-bordered table-hover table-sm">
            <thead class="thead-dark">
                <tr><th>Emp ID</th><th>Name</th><th>IN Time</th><th>OUT Time</th><th>Status</th></tr>
            </thead>
            <tbody>
            <?php
            $res = $conn->query("SELECT a.*, e.name FROM attendance a JOIN employee e ON a.emp_id = e.emp_id WHERE a.date='$today'");
            while ($row = $res->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['emp_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['in_time']}</td>
                        <td>{$row['out_time']}</td>
                        <td>{$row['status']}</td>
                    </tr>";
            }
            ?>
            </tbody>
        </table>
    <?php elseif ($show_section === 'salary'): ?>
        <h4>Salary Records</h4>
        <table class="table table-bordered table-hover table-sm">
            <thead class="thead-dark">
                <tr><th>Emp ID</th><th>Month</th><th>Year</th><th>Total Days</th><th>Present Days</th><th>Final Salary</th></tr>
            </thead>
            <tbody>
            <?php
            $res = $conn->query("SELECT * FROM salaries ORDER BY year DESC, month DESC");
            while ($row = $res->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['emp_id']}</td>
                        <td>{$row['month']}</td>
                        <td>{$row['year']}</td>
                        <td>{$row['total_days']}</td>
                        <td>{$row['present_days']}</td>
                        <td>{$row['final_salary']}</td>
                    </tr>";
            }
            ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>

