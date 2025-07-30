-- Create Database
CREATE DATABASE IF NOT EXISTS sandra_hrm;
USE sandra_hrm;

-- Admin Table
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Employee Table
CREATE TABLE IF NOT EXISTS employee (
    emp_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    department VARCHAR(50),
    designation VARCHAR(50),
    join_date DATE,
    gender ENUM('Male', 'Female', 'Other'),
    password VARCHAR(255) NOT NULL,
    salary DECIMAL(10,2),
    address TEXT,
    work_location VARCHAR(100)
);

-- Attendance Table
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id INT NOT NULL,
    name VARCHAR(100),
    department VARCHAR(50),
    designation VARCHAR(50),
    work_location VARCHAR(100),
    date DATE,
    in_time TIME,
    out_time TIME,
    hours_worked DECIMAL(5,2),
    status ENUM('Present', 'Absent', 'Leave'),
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id) ON DELETE CASCADE
);

-- Salaries Table
CREATE TABLE IF NOT EXISTS salaries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id INT NOT NULL,
    month VARCHAR(20),
    year INT,
    total_days INT,
    present_days INT,
    final_salary DECIMAL(10,2),
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id) ON DELETE CASCADE
);
