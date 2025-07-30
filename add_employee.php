<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Employee - Sandra HRM</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <style>
      * {
        box-sizing: border-box;
      }

      body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f4f8fb;
        padding: 20px;
      }

      .form-container {
        background-color: #ffffff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: 0 auto;
      }

      label {
        display: block;
        margin-top: 15px;
        color: #2a2a2a;
        font-weight: bold;
      }

      input,
      select,
      textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
      }

      button {
        margin-top: 20px;
        width: 100%;
        padding: 12px;
        background-color: #2a4d69;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
      }

      button:hover {
        background-color: #1c3551;
        cursor: pointer;
      }
    </style>
  </head>
  <body>
    <h1 style="text-align: center">Registration Form</h1>
    <div class="form-container">
      <form method="POST" action="../backend/add_employee.php">
        <label>Name</label>
        <input type="text" name="name" placeholder="Enter your name" />
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" />
        <label>Gender</label>
        <select name="gender" id="gender" required>
          <option value="Select Gender">Select</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          <option value="Other">Other</option>
        </select>
        <label for="address">Address</label>
        <textarea
          id="address"
          name="address"
          rows="2"
          placeholder="Enter your address"
        ></textarea>
        <label>Department</label>
        <select name="department" id="department" required>
          <option value="Select Department">Select</option>
          <option value="HR">HR</option>
          <option value="IT">IT</option>
          <option value="Sales">Sales</option>
        </select>
        <label>Designation</label>
        <select name="designation" id="designation" required>
          <option value="Select Designation">Select</option>
          <option value="Manager">Manager</option>
          <option value="Web Developer">Web Developer</option>
          <option value="Sales Executive">Sales Executive</option>
        </select>
        <label>Salary</label>
        <input
          type="text"
          name="salary"
          placeholder="Enter your salary"
          required
        />
        <label>Phone</label>
        <input type="text" name="phone" placeholder="Enter your phone number" />
        <label>Joining Date</label>
        <input type="date" name="joining date" required />
        <button type="submit">Register</button>
      </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>