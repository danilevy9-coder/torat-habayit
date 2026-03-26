<?php
// ============================================
// TORAT HABAYIT — Contact Form (SMTP via PHPMailer)
// SMTP Host : mail.torathabayit.com | Port 465 SSL
// ============================================

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer (files are in phpmailer/src/)
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

header('Content-Type: application/json');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// ── Collect & sanitize fields ──
$firstName = trim(htmlspecialchars($_POST['firstName'] ?? ''));
$lastName  = trim(htmlspecialchars($_POST['lastName']  ?? ''));
$email     = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
$phone     = trim(htmlspecialchars($_POST['phone']   ?? ''));
$reason    = trim(htmlspecialchars($_POST['reason']  ?? ''));
$message   = trim(htmlspecialchars($_POST['message'] ?? ''));
$fullName  = "$firstName $lastName";

// ── Validate required fields ──
if (!$firstName || !$lastName || !$email || !$reason || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

// ── SMTP Credentials ──
$smtpHost     = 'localhost';
$smtpUser     = 'contact@torathabayit.com';
$smtpPassword = ''; // Password not needed for localhost
$smtpPort     = 25;
$fromName     = 'Torat Habayit Institute';
$adminEmail   = 'danilevy9@gmail.com'; // ← Where inquiries arrive

// ── Send admin notification ──
$adminMail = new PHPMailer(true);
try {
    $adminMail->isSMTP();
    $adminMail->Host       = $smtpHost;
    $adminMail->SMTPAuth   = false;
    $adminMail->Username   = $smtpUser;
    $adminMail->Password   = $smtpPassword;
    $adminMail->SMTPSecure = false;
    $adminMail->Port       = $smtpPort;
    $adminMail->CharSet    = 'UTF-8';

    $adminMail->setFrom($smtpUser, $fromName);
    $adminMail->addAddress($adminEmail);
    $adminMail->addReplyTo($email, $fullName); // Reply goes straight to the sender

    $adminMail->isHTML(true);
    $adminMail->Subject = "New Inquiry: $reason — $fullName";
    $adminMail->Body = "
    <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;'>
      <div style='background:#0A192F;padding:24px;border-radius:8px 8px 0 0;'>
        <h2 style='color:#C5A059;margin:0;font-size:20px;'>Torat Habayit Institute</h2>
        <p style='color:rgba(255,255,255,0.6);margin:4px 0 0;font-size:13px;'>New Contact Form Inquiry</p>
      </div>
      <div style='background:#f9fafb;padding:28px;border:1px solid #e5e7eb;border-top:none;border-radius:0 0 8px 8px;'>
        <table style='width:100%;border-collapse:collapse;'>
          <tr><td style='padding:8px 0;color:#6b7280;font-size:14px;width:140px;'>Name</td>
              <td style='padding:8px 0;color:#111827;font-weight:600;font-size:14px;'>$fullName</td></tr>
          <tr><td style='padding:8px 0;color:#6b7280;font-size:14px;'>Email</td>
              <td style='padding:8px 0;color:#111827;font-size:14px;'><a href='mailto:$email' style='color:#C5A059;'>$email</a></td></tr>
          <tr><td style='padding:8px 0;color:#6b7280;font-size:14px;'>Phone</td>
              <td style='padding:8px 0;color:#111827;font-size:14px;'>" . ($phone ?: 'Not provided') . "</td></tr>
          <tr><td style='padding:8px 0;color:#6b7280;font-size:14px;'>Reason</td>
              <td style='padding:8px 0;color:#111827;font-size:14px;'><strong>$reason</strong></td></tr>
        </table>
        <div style='margin-top:20px;padding:16px;background:#fff;border-radius:6px;border:1px solid #e5e7eb;'>
          <p style='color:#6b7280;font-size:12px;margin:0 0 8px;text-transform:uppercase;letter-spacing:0.05em;'>Message</p>
          <p style='color:#111827;font-size:14px;line-height:1.7;margin:0;'>$message</p>
        </div>
        <div style='margin-top:20px;'>
          <a href='mailto:$email' style='display:inline-block;background:#0A192F;color:#fff;padding:12px 24px;border-radius:50px;text-decoration:none;font-size:14px;font-weight:600;'>Reply to $firstName →</a>
        </div>
      </div>
    </div>";

    $adminMail->AltBody = "New inquiry from $fullName\nEmail: $email\nPhone: $phone\nReason: $reason\n\nMessage:\n$message";
    $adminMail->send();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Sorry, there was an error sending your message. Please try again or call us directly.']);
    exit;
}

// ── Send confirmation to user ──
$userMail = new PHPMailer(true);
try {
    $userMail->isSMTP();
    $userMail->Host       = $smtpHost;
    $userMail->SMTPAuth   = false;
    $userMail->Username   = $smtpUser;
    $userMail->Password   = $smtpPassword;
    $userMail->SMTPSecure = false;
    $userMail->Port       = $smtpPort;
    $userMail->CharSet    = 'UTF-8';

    $userMail->setFrom($smtpUser, $fromName);
    $userMail->addAddress($email, $fullName);

    $userMail->isHTML(true);
    $userMail->Subject = "We received your inquiry — Torat Habayit Institute";
    $userMail->Body = "
    <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;'>
      <div style='background:#0A192F;padding:24px;border-radius:8px 8px 0 0;'>
        <h2 style='color:#C5A059;margin:0;font-size:20px;'>Torat Habayit Institute</h2>
        <p style='color:rgba(255,255,255,0.6);margin:4px 0 0;font-size:13px;'>Guiding the intersection of Torah law and modern medicine</p>
      </div>
      <div style='background:#f9fafb;padding:28px;border:1px solid #e5e7eb;border-top:none;border-radius:0 0 8px 8px;'>
        <p style='color:#111827;font-size:15px;'>Shalom <strong>$firstName</strong>,</p>
        <p style='color:#4b5563;font-size:14px;line-height:1.7;'>Thank you for reaching out to the Torat Habayit Institute. We have received your inquiry and will respond within <strong>1–2 business days</strong>.</p>
        <div style='background:#fff;border:1px solid #e5e7eb;border-radius:6px;padding:16px;margin:20px 0;'>
          <p style='color:#6b7280;font-size:12px;margin:0 0 6px;text-transform:uppercase;letter-spacing:0.05em;'>Your Inquiry</p>
          <p style='color:#111827;font-size:14px;margin:0 0 4px;'><strong>$reason</strong></p>
          <p style='color:#6b7280;font-size:13px;margin:0;line-height:1.6;'>$message</p>
        </div>
        <p style='color:#4b5563;font-size:14px;'>For urgent matters, please contact us directly:</p>
        <p style='color:#4b5563;font-size:14px;margin:4px 0;'>📞 Israel: <a href='tel:026504650' style='color:#C5A059;'>02-650-4-650</a></p>
        <p style='color:#4b5563;font-size:14px;margin:4px 0;'>📞 USA: <a href='tel:+17187040985' style='color:#C5A059;'>+1-718-704-0985</a></p>
        <p style='color:#4b5563;font-size:14px;margin:4px 0;'>✉️ <a href='mailto:info@torathabayit.com' style='color:#C5A059;'>info@torathabayit.com</a></p>
        <hr style='border:none;border-top:1px solid #e5e7eb;margin:24px 0;'>
        <p style='color:#9ca3af;font-size:12px;line-height:1.6;'>Torat Habayit Institute<br>Rechov Haran 3/3, Jerusalem 9246603, Israel<br>All medical-halachic inquiries are handled with strict confidentiality.</p>
      </div>
    </div>";

    $userMail->AltBody = "Shalom $firstName,\n\nThank you for contacting the Torat Habayit Institute. We received your inquiry and will respond within 1-2 business days.\n\nFor urgent matters:\nIsrael: 02-650-4-650\nUSA: +1-718-704-0985\n\nTorat Habayit Institute";
    $userMail->send();

} catch (Exception $e) {
    // Admin email already sent — don't fail the whole request
}

// ── Success ──
echo json_encode([
    'success' => true,
    'message' => 'Thank you, ' . $firstName . '! Your message has been sent. We\'ll be in touch within 1–2 business days.'
]);
?>
