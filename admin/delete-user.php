<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$hname='localhost';
$uname='root';
$pass='';
$db='hms';
$conn = mysqli_connect($hname, $uname, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if email parameter is provided
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the statement
    if ($stmt->execute()) {
        sendDeletionConfirmation($email);
        echo "
        <html>
        <head>
        <link href='https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css' rel='stylesheet'>
        </head>
        <body>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js'></script>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'User Removed Successfully',
                showConfirmButton: false,
                timer: 1600,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                    window.location.href = 'users.php';
            });
        </script>
        </body>
        </html>
        ";
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No email specified for deletion.";
}
function sendDeletionConfirmation($email) {
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
        $mail->Subject = 'Account Actions';
        $mail->Body = '
        <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Account Removal Notification</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        color: #333;
        margin: 0;
        padding: 0;
    }
    .container {
        width: 80%;
        margin: 0 auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
    }
    .header h1 {
        color: #e74c3c;
        font-size: 28px;
    }
    .header p {
        font-size: 18px;
        color: #555;
    }
    .details {
        margin: 20px 0;
        line-height: 1.6;
    }
    .details h3 {
        color: #2c3e50;
        margin-bottom: 10px;
        font-size: 20px;
    }
    .details p {
        font-size: 16px;
        color: #555;
    }
    .footer {
        text-align: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #ddd;
    }
    .footer p {
        font-size: 14px;
        color: #888;
    }
    a {
        color: #e74c3c;
        text-decoration: none;
    }
    .btn {
        display: inline-block;
        background-color: #e74c3c;
        color: #fff;
        padding: 12px 24px;
        text-decoration: none;
        font-size: 16px;
        border-radius: 5px;
        margin-top: 20px;
        text-align: center;
    }
    .btn:hover {
        background-color: #c0392b;
    }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Account Removed!</h1>
        <p>We regret to inform you that your account has been removed from our system due to some legal issues.</p>
    </div>

    <div class="details">
        <p>We understand that this may cause inconvenience, and we sincerely apologize for any disruption this may have caused. If you have any questions or require further assistance, please do not hesitate to contact us.</p>
    </div>

    <div class="footer">
        <p>If you have any concerns, please contact us at <a href="mailto:system.vacation.rental@gmail.com">support</a>.</p>
        <p>&copy; 2024 Vacation Rental, All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
        ';
        $mail->send();
    } catch (Exception $e) {
        echo "<script>alert('ERROR: {$mail->ErrorInfo}'); window.location.href = 'booking.php';</script>";
    }
}
mysqli_commit($conn);
$conn->close();
