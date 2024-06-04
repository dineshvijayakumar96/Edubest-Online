<?php
require 'vendor/autoload.php'; // Load PHPMailer library
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sanitizeInput($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function redirect($location, $success = false)
{
    if ($success) {
        $location .= '?success=true';
    }
    header("Location: $location");
    exit;
}

// Load .env variables into $_ENV
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

// Database Setup
$host = 'edubestonline.com';
$dbname = 'u963402001_ASF454AG';
$username = 'u963402001_ASF454AG';
$password = 'dfI06^V0:';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database Connection Error: " . $e->getMessage();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate input data
    $firstname = sanitizeInput($_POST["firstname"]);
    $lastname = sanitizeInput($_POST["lastname"]);
    $email = validateEmail($_POST["email"]);
    $programs = isset($_POST["programs"]) ? $_POST["programs"] : [];
    $comment = sanitizeInput($_POST["comment"]);

    $errors = [];

    if (empty($firstname)) {
        $errors["firstname"] = "First Name is required";
    }

    if (empty($lastname)) {
        $errors["lastname"] = "Last Name is required";
    }

    if ($email === false) {
        $errors["email"] = "Invalid email format";
    }

    // Convert the programs array to a comma-separated string
    $programsString = is_array($programs) ? implode(", ", $programs) : '';

    if (empty($errors)) {
        // Database insertion with prepared statements
        $sql = "INSERT INTO form_submissions (first_name, last_name, email, programs, comment) VALUES (:first_name, :last_name, :email, :programs, :comment)";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':first_name', $firstname, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $lastname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':programs', $programsString, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

        try {
            $stmt->execute();

            // Email sending code to notify the user
            $userMailer = new PHPMailer(true);

            $userMailer->SMTPDebug = SMTP::DEBUG_OFF;
            $userMailer->isSMTP();
            $userMailer->Host = $_ENV['smtp_host'];
            $userMailer->SMTPAuth = true;
            $userMailer->Username = $_ENV['smtp_username'];
            $userMailer->Password = $_ENV['smtp_password'];
            $userMailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $userMailer->Port = $_ENV['smtp_port'];
            $userMailer->isHTML(true);

            $userMailer->setFrom($_ENV['smtp_username'], 'infoedubestonline');
            $userMailer->addAddress($email, $firstname); // Set the user's email address and name

            $userMailer->isHTML(true);
            $userMailer->Subject = 'Thank you for your submission';

            // HTML Email Template for user reply
            $userEmailBody = "
                <html>
                <head>
                    <title>Thank You for Your Submission</title>
                </head>
                <body>
                    <h1>Thank You for Your Submission</h1>
                    <p>Dear <strong>$firstname</strong>,</p>
                    <p>Thank you for submitting your inquiry. Our team will get back to you shortly.</p>
                </body>
                </html>
            ";
            $userMailer->Body = $userEmailBody;

            $userMailer->send();

            // Email sending code for the notification to you
            $mailer = new PHPMailer(true);

            $mailer->SMTPDebug = SMTP::DEBUG_OFF;
            $mailer->isSMTP();
            $mailer->Host = $_ENV['smtp_host'];
            $mailer->SMTPAuth = true;
            $mailer->Username = $_ENV['smtp_username'];
            $mailer->Password = $_ENV['smtp_password'];
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mailer->Port = $_ENV['smtp_port'];
            $mailer->isHTML(true);

            $mailer->setFrom($_ENV['smtp_username'], 'infoedubestonline');
            $mailer->addAddress('edubestonline@bestiu.edu.in', 'edubestonline'); // Change this to your email address

            $mailer->isHTML(true);
            $mailer->Subject = 'Enquiry Form Submission';

            // HTML Email Template for notification
            $emailBody = "
                <html>
                <head>
                    <title>Enquiry Form Submission</title>
                </head>
                <body>
                    <h1>Enquiry Form Submission</h1>
                    <p><strong>First Name:</strong> $firstname</p>
                    <p><strong>Last Name:</strong> $lastname</p>
                    <p><strong>Email:</strong> $email</p>
                    <p><strong>Selected Programs:</strong> $programsString</p>
                    <p><strong>Comment:</strong> $comment</p>
                </body>
                </html>
            ";
            $mailer->Body = $emailBody;

            $mailer->send();

            redirect("index.php", true); // Change this to your thank you page
        } catch (PDOException $e) {
            echo "Database Insertion Error: " . $e->getMessage();
            exit;
        } catch (Exception $e) {
            echo "Email Sending Error: " . $e->getMessage();
            exit;
        }
    }
}
?>