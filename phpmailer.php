use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.yourhosting.com'; // e.g., mail.nextmeditech.com
$mail->SMTPAuth = true;
$mail->Username = 'noreply@nextmeditech.com'; 
$mail->Password = 'YOUR_PASSWORD';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('noreply@nextmeditech.com', 'Next Meditech');
$mail->addAddress('swettam17@gmail.com');
$mail->addReplyTo($email, $first_name . ' ' . $last_name);

$mail->isHTML(true);
$mail->Subject = "New Contact Form Message from $first_name $last_name";
$mail->Body    = $email_body;

if ($mail->send()) {
    echo "✅ Your message has been sent successfully!";
} else {
    echo "❌ Mailer Error: " . $mail->ErrorInfo;
}
