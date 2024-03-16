<?php
session_start();

// Include the connection.php file
include 'connection.php';

// Check if the user is logged in
if(isset($_SESSION['username'])) {
    // Get the username from the session
    $username = $_SESSION['username'];

    // Retrieve the LoginID for the logged-in user
    $stmt = $conn->prepare("SELECT LoginID FROM Login WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists in the login table
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $loginID = $row['LoginID']; // Retrieve the LoginID
    } else {
        // Handle the case where user doesn't exist in the login table
        exit("User not found in the login table.");
    }

    // Retrieve patient details from the form
    $firstName = $_POST['inputFirstName'];
    $lastName = $_POST['inputLastName'];
    $dateOfBirth = $_POST['inputDOB'];
    $gender = $_POST['inputGender'];
    $smokingHistory = $_POST['inputSmokingHistory'];
    $occupation = $_POST['inputOccupation'];
    $contactNumber = $_POST['inputContactNumber'];
    $address = $_POST['inputAddress'] . ", " . $_POST['inputAddress2'] . ", " . $_POST['inputCity'] . ", " . $_POST['inputState'] . ", " . $_POST['inputZip'];
    $insuranceDetails = $_POST['inputinsurance'];
    $aadhar = $_POST['inputaadhar'];
    $panCard = $_POST['inputpan'];

    // Insert data into the Patients table
    $stmtPatients = $conn->prepare("INSERT INTO Patients (FirstName, LastName, DateOfBirth, Gender, SmokingHistory, Occupation, ContactNumber, Address, LoginID) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtPatients->bind_param("ssssssiss", $firstName, $lastName, $dateOfBirth, $gender, $smokingHistory, $occupation, $contactNumber, $address, $loginID);
    $stmtPatients->execute();

    // Get the last inserted PatientID
    $patientID = $stmtPatients->insert_id;

    // Insert data into the Documents table
    $stmtDocuments = $conn->prepare("INSERT INTO Documents (Aadhar, InsuranceDetails, PanCard, LoginID) 
                                     VALUES (?, ?, ?, ?)");
    $stmtDocuments->bind_param("sssi", $aadhar, $insuranceDetails, $panCard, $loginID);
    $stmtDocuments->execute();

    // Redirect to bloodhealth.html
    header("Location: bloodhealth.html");
    exit;
} else {
    // Handle the case where session username is not set
    exit("Session username not set.");
}
?>
