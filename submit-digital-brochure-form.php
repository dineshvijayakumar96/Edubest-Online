<?php
require 'vendor/autoload.php';
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
    $firstname = sanitizeInput($_POST["firstname"]);
    $lastname = sanitizeInput($_POST["lastname"]);
    $email = validateEmail($_POST["email"]);
    $phone = sanitizeInput($_POST["phone"]);
    $experience = sanitizeInput($_POST["experience"]);
    $country = sanitizeInput($_POST["country"]);
    $inquiryFor = isset($_POST["flexRadioInquiring"]) ? "Team/Group" : "Myself";

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

    if (empty($phone)) {
        $errors["phone"] = "Phone is required";
    }

    if (empty($experience)) {
        $errors["experience"] = "Total work Experience is required";
    }

    if (empty($country) || $country == "Country/Region") {
        $errors["country"] = "Please select a Country/Region";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO digital_brochure_form_submissions (first_name, last_name, email, phone, experience, country, inquiry_for) VALUES (:first_name, :last_name, :email, :phone, :experience, :country, :inquiry_for)";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':first_name', $firstname, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $lastname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':experience', $experience, PDO::PARAM_STR);
        $stmt->bindParam(':country', $country, PDO::PARAM_STR);
        $stmt->bindParam(':inquiry_for', $inquiryFor, PDO::PARAM_STR);

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
            $mailer->Subject = 'Digital Marketing Brochure Form Submission';

            $emailBody = "
                <html>
                <head>
                    <title>Digital Marketing Brochure Form Submission</title>
                </head>
                <body>
                    <h1>Digital Marketing Brochure Form Submission</h1>
                    <p><strong>First Name:</strong> $firstname</p>
                    <p><strong>Last Name:</strong> $lastname</p>
                    <p><strong>Email:</strong> $email</p>
                    <p><strong>Phone:</strong> $phone</p>
                    <p><strong>Total Work Experience:</strong> $experience</p>
                    <p><strong>Country/Region:</strong> $country</p>
                    <p><strong>Inquiring for:</strong> $inquiryFor</p>
                </body>
                </html>
            ";
            $mailer->Body = $emailBody;

            $mailer->send();

            // Download the brochure
            $brochureFile = 'digital-marketing-specialist-brochure.pdf'; // Replace with the actual path to your brochure file
            if (file_exists($brochureFile)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="digital-marketing-specialist-brochure.pdf"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($brochureFile));
                readfile($brochureFile);
                exit;
            }

            redirect("https://erp.eshiksa.net/DirectFeesv3/BestInnovationUniversity"; // Change this to your thank you page
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