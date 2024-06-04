<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user'])) {
    header("Location: bestiu-home.php");
    exit();
}

// Database connection configuration
$servername = "edubestonline.com";
$username = "u963402001_ASF454AG";
$password = "dfI06^V0:";
$dbname = "u963402001_ASF454AG";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user input
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);

    // Query the database to check if the provided email and mobile number exist in the table
    $query = "SELECT * FROM bestiu_students WHERE email = '$email' AND mobile = '$mobile'";
    $result = $conn->query($query);

    if ($result && $result->num_rows === 1) {
        // Successful login
        $user = $result->fetch_assoc();

        // Store user data in the session
        $_SESSION['user'] = $user;

        // Redirect to the home page
        header("Location: bestiu-home.php");
        exit();
    } else {
        // Invalid credentials
        $error_message = "Invalid email or mobile number";
    }
}

// Close the database connection
$conn->close();
?>