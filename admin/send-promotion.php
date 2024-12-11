<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('inc/essentials.php');
adminLogin();

if (isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['body'])) {
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['body'];

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'system.vacation.rental@gmail.com'; // Your Gmail address
        $mail->Password = 'kylzdtyyggvrfmdt'; // Your app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('system.vacation.rental@gmail.com', 'Vacation Rental');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = nl2br($message);

        $mail->send();
        echo "<script>alert('Promotional email sent successfully!'); window.location.href = 'subscribers.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Failed to send promotion email. Error: {$mail->ErrorInfo}'); window.location.href = 'subscribers.php';</script>";
    }
} else {
    echo "<script>alert('Please fill all fields!'); window.location.href = 'subscribers.php';</script>";
}
