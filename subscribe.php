<?php
// ============================================
// TORAT HABAYIT — Newsletter Subscription
// ============================================

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

// ── SMTP credentials live in api/secrets.php (gitignored) ──
$config       = require __DIR__ . '/api/config.php';
$smtpHost     = $config['smtp_host'];
$smtpUser     = $config['smtp_user'];
$smtpPassword = $config['smtp_password'];
$smtpPort     = $config['smtp_port'];
$fromEmail    = $config['from_email'];
$fromName     = $config['from_name'];
$adminEmail   = $config['admin_email'];

$adminMail = new PHPMailer(true);
try {
    $adminMail->isSMTP();
    $adminMail->Host       = $smtpHost;
    $adminMail->SMTPAuth   = true;
    $adminMail->Username   = $smtpUser;
    $adminMail->Password   = $smtpPassword;
    $adminMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $adminMail->Port       = $smtpPort;
    $adminMail->CharSet    = 'UTF-8';

    $adminMail->setFrom($fromEmail, $fromName);
    $adminMail->addAddress($adminEmail);
    $adminMail->addReplyTo($email); 

    $adminMail->isHTML(true);
    $adminMail->Subject = "New Newsletter Subscriber: $email";
    $adminMail->Body = "New subscriber email: <strong>$email</strong>";

    $adminMail->send();

} catch (Exception $e) {
    error_log('Newsletter SMTP error: ' . $adminMail->ErrorInfo);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Sorry, something went wrong. Please try again later.']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Thank you for subscribing!']);
?>
