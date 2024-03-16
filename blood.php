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
        $userID = $row['LoginID']; // Retrieve the LoginID
    } else {
        // Handle the case where user doesn't exist in the login table
        exit("User not found in the login table.");
    }

    // Retrieve health data from the form
    $hypertension = isset($_POST['inputHypertension']) ? $_POST['inputHypertension'] : null;
    $glucoseLevel = isset($_POST['inputglucose']) ? $_POST['inputglucose'] : null;
    $hbA1CLevel = isset($_POST['inputHbA1C']) ? $_POST['inputHbA1C'] : null;
    $heartDisease = isset($_POST['inputHeartDisease']) ? $_POST['inputHeartDisease'] : null;
    $height = isset($_POST['inputheight']) ? $_POST['inputheight'] : null;
    $weight = isset($_POST['inputweight']) ? $_POST['inputweight'] : null;
    $bmiValue = isset($_POST['inputbmi']) ? $_POST['inputbmi'] : null;
    $age = isset($_POST['inputAge']) ? $_POST['inputAge'] : null;
    $gender = isset($_POST['inputGender']) ? $_POST['inputGender'] : null;
    $smokingHistory = isset($_POST['inputSmokingHistory']) ? $_POST['inputSmokingHistory'] : null;

    // Check if all required attributes are provided
    if ($hypertension === null || $glucoseLevel === null || $hbA1CLevel === null || $heartDisease === null || $height === null || $weight === null || $bmiValue === null || $age === null || $gender === null || $smokingHistory === null) {
        exit("Please provide all required attributes.");
    }

    // Insert data into the blood_health table
    $stmtBloodHealth = $conn->prepare("INSERT INTO blood_health (UserID, Hypertension, GlucoseLevel, HbA1C_Level, HeartDisease) VALUES (?, ?, ?, ?, ?)");
    $stmtBloodHealth->bind_param("iiidd", $userID, $hypertension, $glucoseLevel, $hbA1CLevel, $heartDisease);
    $stmtBloodHealth->execute();

    // Insert data into the bmi table
    $stmtBMI = $conn->prepare("INSERT INTO bmi (UserID, Height, Weight, BMI_Value) VALUES (?, ?, ?, ?)");
    $stmtBMI->bind_param("iddd", $userID, $height, $weight, $bmiValue);
    $stmtBMI->execute();

    // Insert data into the diagnosis table
    $stmtDiagnosis = $conn->prepare("INSERT INTO diagnosis (UserID, HbA1C_Level, BloodGlucose, BMI, HeartDisease, Hypertension, Age, Gender, SmokingHistory) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtDiagnosis->bind_param("iddddiiss", $userID, $hbA1CLevel, $glucoseLevel, $bmiValue, $heartDisease, $hypertension, $age, $gender, $smokingHistory);
    $stmtDiagnosis->execute();
    $pythonScript = "sanvi.ipynb";
    $pythonParams = escapeshellarg($age) . ' ' . escapeshellarg($hypertension) . ' ' . escapeshellarg($bmiValue) . ' ' . escapeshellarg($hbA1CLevel) . ' ' . escapeshellarg($glucoseLevel);
    $prediction = shell_exec("python $pythonScript $pythonParams");
    // Redirect to result.html
    header("Location: result.html");
    exit;
} else {
    // Handle the case where session username is not set
    exit("Session username not set.");
}
?>
