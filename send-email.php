<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$to = ' info@nextmeditech.com'; // recipient email

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $title      = strip_tags($_POST["title"]);
    $first_name = strip_tags($_POST["first_name"]);
    $last_name  = strip_tags($_POST["last_name"]);
    $company    = strip_tags($_POST["company"]);
    $email      = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone      = strip_tags($_POST["phone"]);
    $street     = strip_tags($_POST["street"]);
    $zip        = strip_tags($_POST["zip"]);
    $city       = strip_tags($_POST["city"]);
    $region     = strip_tags($_POST["region"]);
    $country    = strip_tags($_POST["country"]);
    $subject    = strip_tags($_POST["subject"]);
    $message    = htmlspecialchars($_POST["message"]);

    // Email body
    $email_body = "
    <html>
    <head><title>Contact Form Submission</title></head>
    <body>
        <h2>New Contact Form Submission</h2>
        <p><strong>Title:</strong> $title</p>
        <p><strong>Name:</strong> $first_name $last_name</p>
        <p><strong>Company:</strong> $company</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Phone:</strong> $phone</p>
        <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
    </body>
    </html>
    ";

    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.stackmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@nextmeditech.com';
        $mail->Password   = 'F9P4?J^<oN}3'; // check no extra spaces

        // OPTION 1: TLS (recommended)
       // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
       // $mail->Port       = 587;

        // OPTION 2: SSL (uncomment if TLS fails)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom(' noreply@nextmeditech.com', 'Next Meditech Website');
        $mail->addAddress($to);
        $mail->addReplyTo($email, "$first_name $last_name");

        // Content
        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Message from $first_name $last_name";
        $mail->Body    = $email_body;

        $mail->send();
        $mail->SMTPDebug = 3; 
$mail->Debugoutput = 'html';

        echo "✅ Your message has been sent successfully!";
    } catch (Exception $e) {
        echo "❌ Mailer Error: {$mail->ErrorInfo}";
    }
}
