<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('inc/essentials.php');
adminLogin();

$hname = 'localhost';
$uname = 'root';
$pass = '';
$db = 'hms';
$conn = mysqli_connect($hname, $uname, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    $sql = "SELECT bookingid, roomname, email, name, phone, bookingaccount, checkin, checkout FROM bookedrooms where bookingid = '$booking_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0){

        while ($row = $result->fetch_assoc()){
            $id = $row['bookingid'];
            $hotel = $row['roomname'];
            $umail = $row['email'];
            $uname = $row['name'];
            $uphone = $row['phone'];
            $chkin = $row['checkin'];
            $chkout = $row['checkout'];
            $bmail = $row['bookingaccount'];
        }

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
            $mail->addAddress($bmail);

            $mail->isHTML(true);
            $mail->Subject = 'Let\'s begin your vacation journey';

            $mail->Body = '
                <!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dddddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }
        .details {
            margin: 20px 0;
        }
        .details p {
            font-size: 16px;
            line-height: 1.5;
            color: #333333;
        }
        .details b {
            color: #007bff;
        }
        .call-to-action {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 18px;
        }
        .call-to-action p {
            margin: 0;
        }
        .footer {
            text-align: center;
            color: #666666;
            font-size: 14px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Welcome to Vacation Rental!</h2>
    </div>
    
    <p>Dear <b>'.$uname.'</b>,</p>
    
    <p>Thank you for choosing our hotel. We are delighted to confirm your booking and look forward to providing you with an exceptional experience.</p>
    
    <div class="details">
        <p><b>Booking ID:</b> '.$id.'</p>
        <p><b>Booking Email:</b> '.$umail.'</p>
        <p><b>Name:</b> '.$uname.'</p>
        <p><b>Phone Number:</b> '.$uphone.'</p>
        <p><b>Hotel Name:</b> '.$hotel.'</p>
        <p><b>Check-In Date:</b> '.$chkin.'</p>
        <p><b>Check-Out Date:</b> '.$chkout.'</p>
    </div>

    <!-- Call to Action Section -->
    <div class="call-to-action">
        <p>📞 Call us at <b>+91 987xxxx745</b> within 2 days to confirm your booking and complete the payment.</p>
    </div>

    <p>For any assistance, please contact us at <a href="mailto:system.vacation.rental@gmail.com">our support</a>.</p>
    
    <p>We look forward to welcoming you soon!</p>
    
    <div class="footer">
        <p>Best regards,<br>Vacation Rental Team</p>
    </div>
</div>

</body>
</html>
            ';
            $mail->send();
            echo "<script>alert('Invitation sent successfully!'); window.location.href = 'booking.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('ERROR: {$mail->ErrorInfo}'); window.location.href = 'booking.php';</script>";
        }

    } else {
        echo "<script>alert('Booking ID not found!'); window.location.href = 'booking.php';</script>";
    }
} else {
    echo "<script>alert('Please fill all fields!'); window.location.href = 'booking.php';</script>";
}
$conn->close();
