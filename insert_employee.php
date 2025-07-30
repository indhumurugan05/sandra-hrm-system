<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Step 1: Generate new emp_id
    $sql_get_last = "SELECT emp_id FROM employee ORDER BY emp_id DESC LIMIT 1";
    $result = $conn->query($sql_get_last);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = intval(substr($row['emp_id'], 3)); // remove 'EMP'
        $new_id_number = $last_id + 1;
    } else {
        $new_id_number = 1;
    }

    $emp_id = "EMP" . str_pad($new_id_number, 3, "0", STR_PAD_LEFT);
    $user_id = $emp_id; // user_id same as emp_id

    // Step 2: Collect form values
    $name = $_POST["name"] ?? '';
    $email = $_POST["email"] ?? '';
    $phone = $_POST["phone"] ?? '';
    $department = $_POST["department"] ?? '';
    $designation = $_POST["designation"] ?? '';
    $join_date = $_POST["join_date"] ?? '';
    $gender = $_POST["gender"] ?? '';
    $address = $_POST["address"] ?? '';
    $work_location = $_POST["work_location"] ?? '';

    // Step 3: Generate password based on emp_id (e.g., EMP001123)
    $password_plain = $emp_id . "123";  // e.g., EMP001123
    $password_hashed = $password_plain; // You can use password_hash() here in real apps

    // Step 4: Insert data into the database
    $sql = "INSERT INTO employee 
        (emp_id, user_id, name, email, phone, department, designation, join_date, gender, address, work_location, password)
        VALUES 
        ('$emp_id', '$user_id', '$name', '$email', '$phone', '$department', '$designation', '$join_date', '$gender', '$address', '$work_location', '$password_hashed')";
    if ($conn->query($sql) === TRUE) {
        // ✅ Step 5: Success HTML Output
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Employee Added</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f0f8ff;
                    text-align: center;
                    padding: 50px;
                }
                .success-box {
                    background-color: #ffffff;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    display: inline-block;
                }
                .success-box h2 {
                    color: green;
                }
                .details {
                    margin-top: 20px;
                    font-size: 18px;
                }
            </style>
        </head>
        <body>
            <div class='success-box'>
                <h2>✅ Employee Registered Successfully!</h2>
                <div class='details'>
                    <p><strong>Employee ID:</strong> $emp_id</p>
                    <p><strong>Password:</strong> $password_plain</p>
                </div>
                <br><a href='../frontend/index.html'>Continue</a>
            </div>
        </body>
        </html>";
    } else {
        echo "❌ Error: " . $conn->error;
    }

    $conn->close();
}
?>
