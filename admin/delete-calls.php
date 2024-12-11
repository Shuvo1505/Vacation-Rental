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

// Check if ticket parameter is provided
if (isset($_GET['id'])) {
    $ticket = $_GET['id'];

    // Fetch user details using ticket id
    $stmt = $conn->prepare("SELECT name, ticketid, email FROM calls WHERE ticketid = ?");
    $stmt->bind_param("s", $ticket);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($name, $ticketid, $email);

    if ($stmt->fetch()) {
        // Prepare delete statement and execute
        $stmt_delete = $conn->prepare("DELETE FROM calls WHERE ticketid = ?");
        $stmt_delete->bind_param("s", $ticket);

        if ($stmt_delete->execute()) {
            sendCallSolved($email, $name, $ticket);
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
                    title: 'Incident Resolved Successfully',
                    showConfirmButton: false,
                    timer: 1700,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                        window.location.href = 'calls.php';
                });
            </script>
            </body>
            </html>
            ";
        } else {
            echo "Error deleting call: " . $stmt_delete->error;
        }

        $stmt_delete->close();
    } else {
        echo "No incident found with the provided ticket ID.";
    }

    $stmt->close();
} else {
    echo "No ticket ID specified for resolution.";
}

// Function to send issue resolution email
function sendCallSolved($email, $name, $ticket) {
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
        $mail->Subject = 'Query Resolution';
        $mail->Body = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Resolution Confirmation</title>
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
            background-color: #2c3e50;
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
        .content .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .content .btn:hover {
            background-color: #2980b9;
        }
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #f1f1f1;
            font-size: 14px;
            color: #777;
        }
        .footer a {
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Thank You for Contacting Us</h1>
        </div>
        <div class="content">
            <p>Dear <b>'.$name.'</b>,</p>
            <p>We hope that we have successfully resolved your query against <b>Ticket ID: '.$ticket.'</b> during our recent call. Your satisfaction is very important to us, and we appreciate you taking the time to reach out for support.</p>
            <p>If you have any further questions or need additional assistance, please don\'t hesitate to get in touch. We are here to help!</p>
            <a href="mailto:system.vacation.rental@gmail.com" class="btn" style="color: white">Contact Support</a>
        </div>
        <div class="footer">
            <p>© 2024 Your Vacation Rental. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
        ';
        $mail->send();
    } catch (Exception $e) {
        echo "<script>alert('ERROR: {$mail->ErrorInfo}'); window.location.href = 'calls.php';</script>";
    }
}

mysqli_commit($conn);
$conn->close();
