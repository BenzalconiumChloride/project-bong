<?php
// config.php
$host = 'localhost';
$username = 'root'; //
$password = ''; // 
$database = 'student_bmi_tracker';

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8");

// Test query to check if table exists
$test_query = "SHOW TABLES LIKE 'students'";
$test_result = mysqli_query($conn, $test_query);

if (mysqli_num_rows($test_result) == 0) {
    // Table doesn't exist, create it
    $create_table = "CREATE TABLE IF NOT EXISTS students (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        school VARCHAR(100) NOT NULL,
        grade VARCHAR(10) NOT NULL,
        section VARCHAR(10) NOT NULL,
        age INT NOT NULL,
        gender VARCHAR(20) NOT NULL,
        height DECIMAL(5,2),
        weight DECIMAL(5,2),
        bmi DECIMAL(5,2) NOT NULL,
        bmi_category VARCHAR(20) NOT NULL,
        measurement_date DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (!mysqli_query($conn, $create_table)) {
        die("Error creating table: " . mysqli_error($conn));
    }
}
?>