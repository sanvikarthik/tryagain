<?php
session_start();

// Include the connection.php file
include 'connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Query to insert username and password into Login table
    $sql = "INSERT INTO Login (Username, Password) VALUES ('$username', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        // Signup successful, redirect to login page
        header("Location: login.html");
        exit();
    } else {
        // Error occurred, display error message
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
