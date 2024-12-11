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
if (isset($_GET['bookingid'])) {
    $booking_id = $_GET['bookingid'];
    $booking_id_length = strlen($booking_id);

    if ($booking_id_length > 8 || $booking_id == '') {
        echo "<script>alert('Invalid Booking ID!');</script>";
        $conn->close();
        die();
    }

    // Fetch booking details before deletion
    $fetch_stmt = $conn->prepare("SELECT email, name, bookingid, roomname FROM bookedrooms WHERE bookingid = ?");
    $fetch_stmt->bind_param("s", $booking_id);
    $fetch_stmt->execute();
    $result = $fetch_stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Prepare the DELETE statement
        $delete_stmt = $conn->prepare("DELETE FROM bookedrooms WHERE bookingid = ?");
        $delete_stmt->bind_param("s", $booking_id);

        if ($delete_stmt->execute()) {
            // Send cancellation email using retrieved data
            sendSuspensionConfirmation($row);
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
                    title: 'Booking was Cancelled Successfully',
                    showConfirmButton: false,
                    timer: 1700,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                        window.location.href = 'booking.php';
                });
            </script>
            </body>
            </html>
            ";
        } else {
            echo "Error deleting booking: " . $delete_stmt->error;
        }
        $delete_stmt->close();
    } else {
        echo "<script>alert('Booking ID not found!'); window.location.href = 'booking.php';</script>";
    }
    $fetch_stmt->close();
} else {
    echo "No booking ID specified for cancellation.";
}

function sendSuspensionConfirmation($row) {
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
        $mail->addAddress($row['email']);

        $mail->isHTML(true);
        $mail->Subject = 'Booking Cancellation';
        $mail->Body = '
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Cancellation Confirmation</title>
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
        <h1>Booking Cancelled!</h1>
        <p>We regret to inform you that your booking has been cancelled from our side. Please find the details below.</p>
    </div>

    <div class="details">
        <h3>Cancellation Details</h3>
        <p><strong>Booking ID: </strong> '.$row['bookingid'].'</p>
        <p><strong>Booking Name: </strong> '.$row['name'].'</p>
        <p><strong>Hotel Name: </strong> '.$row['roomname'].'</p>
        <p>We’re sorry for any inconvenience caused. If you have any questions or need further assistance, please feel free to contact us.</p>
    </div>

    <div class="footer">
        <p>If you have any concerns, Contact us at <a href="mailto:system.vacation.rental@gmail.com">support</a>.</p>
        <p>&copy; 2024 Vacation Rental, All Rights Reserved.</p>
    </div>
</div>
</body>
</html>';
        $mail->send();
    } catch (Exception $e) {
        echo "<script>alert('ERROR: {$mail->ErrorInfo}'); window.location.href = 'booking.php';</script>";
    }
}
mysqli_commit($conn);
$conn->close();
