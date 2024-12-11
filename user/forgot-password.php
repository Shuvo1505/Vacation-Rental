<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function redirectTo($url){
    echo "<script>window.location.href='$url';</script>";
}

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hms";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Start form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Reset Password: Check for email and passwords
    if (isset($_POST['email'], $_POST['new_password'])) {
        $email = $_POST['email'];
        $newPassword = $_POST['new_password'];
        $_SESSION['newpass'] = $newPassword;
        $_SESSION['pemail'] = $email;

        // Validate password length
        if (strlen($newPassword) < 8 || strlen($newPassword) > 28) {
            echo "<script>alert('Password should be between 8 and 28 characters.');</script>";
            redirectTo('login.html');
            $conn->close();
            die();
        }

        // Check for blank fields
        if (empty($email) || empty($newPassword)) {
            echo "<script>alert('Fields cannot be blank.');</script>";
            redirectTo('login.html');
            $conn->close();
            die();
        }

        // Verify email exists
        $query = "SELECT password FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($currentPassword);
            $stmt->fetch();

            $_SESSION['oldpass'] = $currentPassword;

            // Generate OTP, store in session with timestamp
            $otp = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_time'] = time();

            // Send OTP email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'system.vacation.rental@gmail.com';
                $mail->Password = 'kylzdtyyggvrfmdt'; // Replace with app-specific password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('system.vacation.rental@gmail.com', 'Vacation Rental');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Verification Code';
                $mail->Body = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { font-size: 24px; font-weight: bold; color: #1a73e8; margin-bottom: 20px; }
            .content { font-size: 16px; line-height: 1.5; }
            .otp { font-size: 20px; font-weight: bold; color: #ff6f61; }
            .footer { font-size: 14px; color: #888; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">Password Reset Verification</div>
            <div class="content">
                <p>Dear User,</p>
                <p>We have received a request to reset your password. To complete the reset process, please use the following code:</p>
                <p class="otp">' . $otp . '</p>
                <p>This code is valid for 6 minutes. Please do not share it with anyone.</p>
                <p>If you did not request this, please ignore this email or contact support immediately.</p>
            </div>
            <div class="footer">
                <p>Best regards,</p>
                <p><strong>Vacation Rental Team</strong></p>
                <p>If you have any questions, feel free to <a href="mailto:system.vacation.rental@gmail.com">contact support</a>.</p>
            </div>
        </div>
    </body>
    </html>
                ';

                $mail->send();
            } catch (Exception $e) {
                error_log("Mailer Error: " . $mail->ErrorInfo);
                echo "<script>alert('Failed to send OTP. Check email server settings.');</script>";
                session_destroy();
                session_unset();
                redirectTo('login.html');
                die();
            }

        } else {
            echo "<script>alert('Email address not found!');</script>";
            redirectTo('login.html');
            session_unset();
            session_destroy();
            die();
        }
    }

    // OTP Verification
    else if (isset($_POST['otp'])) {
        $enteredOtp = $_POST['otp'];

        if (strlen($enteredOtp) != 6) {
            echo "<script>alert('Invalid OTP length!');</script>";
            redirectTo('login.html');
            session_unset();
            session_destroy();
            die();
        }

        // OTP expiration: 6 minutes
        if (time() - $_SESSION['otp_time'] > 360) {
            echo "<script>alert('OTP has expired. Please request a new one.');</script>";
            redirectTo('login.html');
            session_unset();
            session_destroy();
            die();
        }

        if ($enteredOtp == $_SESSION['otp']) {

            $newPass = $_SESSION['newpass'];
            $opass = $_SESSION['oldpass'];
            $nemail = $_SESSION['pemail'];

            if ($newPass == $opass){
                echo "<script>alert('Your old and new password should be different!');</script>";
                session_unset();
                session_destroy();
                redirectTo('login.html');
                die();
            }

            // Update the password in the database
            $updateQuery = "UPDATE users SET password = ? WHERE email = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ss", $newPass, $nemail);

            if ($updateStmt->execute()) {
                echo "<script>alert('Password updated successfully!');</script>";
                mysqli_commit($conn);
                session_unset();
                session_destroy();
                redirectTo('login.html');
                die();
            } else {
                error_log("Password update failed for email: " . $email);
                echo "<script>alert('Error updating password.');</script>";
                redirectTo('login.html');
                session_unset();
                session_destroy();
            }

        } else {
            echo "<script>alert('Invalid OTP. Please try again!');</script>";
            redirectTo('login.html');
            session_unset();
            session_destroy();
            die();
        }
    }

    // Catch-all for unexpected cases
    else {
        echo "<script>alert('Invalid submission data!');</script>";
        redirectTo('login.html');
        session_unset();
        session_destroy();
        die();
    }
} else {
    echo "<script>alert('Invalid request method!');</script>";
    redirectTo('login.html');
    session_unset();
    session_destroy();
    die();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <head>
    <title>OTP Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap">
    </head>
    <body>
<!-- OTP Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="otpModalLabel">Verification</h5>
      </div>
      <div class="modal-body">
        <form action="forgot-password.php" method="POST">
          <div class="form-group">
            <input type="text" class="form-control" id="otp" name="otp" maxlength="6" required
            placeholder="Enter Code">
            <br>
            <strong style="color: green;">Code has been sent to your mail.</strong>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Verify</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        $("#otpModal").modal("show");
    });
</script>;
</body>
</html>