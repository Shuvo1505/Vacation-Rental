<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$hname = 'localhost';
$uname = 'root';
$pass = '';
$db = 'hms';
$conn = mysqli_connect($hname, $uname, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if email parameter is provided
if (isset($_GET['id'])) {
    $serial = $_GET['id'];

    // Fetch the email of the subscriber using the serial
    $stmt = $conn->prepare("SELECT email FROM subscribers WHERE serials = ?");
    $stmt->bind_param("s", $serial);
    $stmt->execute();
    
    // Store result to clear up the buffer for the next query
    $stmt->store_result();
    
    // Bind result variable
    $stmt->bind_result($email);
    
    if ($stmt->fetch()) {
        // Prepare the DELETE statement after fetching the email
        $stmt_delete = $conn->prepare("DELETE FROM subscribers WHERE serials = ?");
        $stmt_delete->bind_param("s", $serial);

        // Execute the DELETE statement
        if ($stmt_delete->execute()) {
            // Send unsubscribe confirmation email
            sendUnsubscribeConfirmation($email);

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
                    title: 'Subscription Revoked Successfully',
                    showConfirmButton: false,
                    timer: 1700,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                        window.location.href = 'subscribers.php';
                });
            </script>
            </body>
            </html>
            ";
        } else {
            echo "Error deleting user: " . $stmt_delete->error;
        }

        $stmt_delete->close();
    } else {
        echo "No subscriber found with the specified ID.";
    }

    $stmt->close();
} else {
    echo "No email specified for cancellation.";
}

function sendUnsubscribeConfirmation($email) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'system.vacation.rental@gmail.com'; // Your Gmail address
        $mail->Password = ''; // Your app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('system.vacation.rental@gmail.com', 'Vacation Rental');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Subscription Cancelled';
        $mail->Body = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribed Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #4CAF50;
            padding: 20px;
            color: #ffffff;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
        }
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #f1f1f1;
            font-size: 14px;
            color: #777;
        }
        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Unsubscribed Confirmation</h1>
        </div>
        <div class="content">
            <p>Dear User,</p>
            <p>We wanted to let you know that you have successfully unsubscribed from our promotional mailing list. You will no longer receive promotional emails from us.</p>
            <p>If you have any questions or if this was done in error, feel free to <a href="mailto:system.vacation.rental@gmail.com">contact our support team</a>.</p>
            <p>Thank you for your time and we hope to serve you in the future.</p>
        </div>
        <div class="footer">
            <p>© 2024 Vacation Rental. All rights reserved.</p>
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
