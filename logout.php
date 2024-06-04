<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user'])) {
    // Destroy the session
    session_destroy();

    // Clear the login cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600);
    }
}

// Redirect to the login page after logout
header("Location: bestiu-login.php");
exit();
?>