<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the connection.php file
include 'connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Query to check if username and password match
    $sql = "SELECT * FROM Login WHERE Username = '$username' AND Password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION['username'] = $username;
            $row = $result->fetch_assoc();
            $loginID = $row['LoginID']; // Assuming 'LoginID' is the name of the column in the Login table
        
            $_SESSION['username'] = $username;
            $_SESSION['LoginID'] = $loginID;
            header("Location: diabetes_form.html"); // Assuming $loginID is the ID of the logged-in user
            exit();
    } else {
        // Invalid username or password, display error message
        echo "<script>alert('Invalid username or password. Please try again.');</script>";
        echo "<script>window.location.href = 'signup.html';</script>";
    }
}

// Close connection
$conn->close();
?>
