<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendConfirmation($id){
    $hname = 'localhost';
$uname = 'root';
$pass = '';
$db = 'hms';
$conn = mysqli_connect($hname, $uname, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    $sql = "SELECT email FROM bookedrooms where bookingid = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0){

        while ($row = $result->fetch_assoc()){
            $umail = $row['email'];
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
            $mail->addAddress($umail);

            $mail->isHTML(true);
            $mail->Subject = 'Booking Confirmation';
            $mail->Body = '
                    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
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
            color: #2ecc71;
        }
        .header p {
            font-size: 18px;
            color: #555;
        }
        .details {
            margin: 20px 0;
        }
        .details h3 {
            color: #2c3e50;
            margin-bottom: 10px;
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
        .btn {
            display: inline-block;
            background-color: #3498db;
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }
        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Confirmed</h1>
            <p>Your payment has been successfully received, and your hotel room is now booked.
            Thanks for starting your vacation journey with us ❤️</p>
        </div>

        <div class="footer">
            <p>If you have any questions or need assistance, feel free to <a href="mailto:system.vacation.rental@gmail.com" style="color: #3498db;">contact us</a>.</p>
            <p>&copy; 2024 Vacation Rental, All Rights Reserved</p>
        </div>
    </div>
</body>
</html>
            ';

            $mail->send();
        } catch (Exception $e) {
            echo "<script>alert('ERROR: {$mail->ErrorInfo}'); window.location.href = 'booking.php';</script>";
        }

    } else {
        echo "<script>alert('Booking ID not found!'); window.location.href = 'booking.php';</script>";
    }
}

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
if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    $admin_told_status = 'YES';

    // Prepare the UPDATE statement
    $stmt = $conn->prepare("UPDATE bookedrooms SET status = ? WHERE bookingid = ?");
    $stmt->bind_param("ss", $admin_told_status, $booking_id);    

    // Execute the statement
    if ($stmt->execute()) {
        sendConfirmation($booking_id);
        header("Location: booking.php");
    } else {
        echo "Error updating status: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No booking ID specified for deletion.";
}
mysqli_commit($conn);
$conn->close();
