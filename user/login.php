<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hms";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Verify user credentials
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            
            $row = $result->fetch_assoc();
            // User found, generate OTP
            $otp = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6); // Generate 6 digit OTP
            $_SESSION['otp'] = $otp;
            $_SESSION['loggedin'] = true;
            $_SESSION['otp_time'] = time();
            $_SESSION['username'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            
            // Send OTP to email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; 
                $mail->SMTPAuth = true;
                $mail->Username = 'system.vacation.rental@gmail.com'; 
                $mail->Password = ''; //app-specific password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('system.vacation.rental@gmail.com', 'Vacation Rental');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Code for Login';
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
            <div class="header">Login Verification</div>
            <div class="content">
                <p>Dear User,</p>
                <p>We have received a request to log in to your account. To complete the login process, please use the following code:</p>
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
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            header("Location: login-fail.html");
            die();
        }
    } else if (isset($_POST['otp'])) {
        $enteredOtp = $_POST['otp'];
        $enteredOtp_length = strlen($enteredOtp);

        if ($enteredOtp_length != 6){
            echo "
            <html>
            <head>
            <link href='https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css' rel='stylesheet'>
            </head>
            <body>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js'></script>
            <script>
                Swal.fire({
                    title: 'Invalid OTP Length',
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Try Again!',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'login.html';
                    }
                });
            </script>
            </body>
            </html>
            ";
            session_unset();
            session_destroy();
            die();
        }        

            // Check if the OTP has expired (1 minute = 60 seconds)
        if (time() - $_SESSION['otp_time'] > 360) {
            echo "
            <html>
            <head>
            <link href='https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css' rel='stylesheet'>
            </head>
            <body>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js'></script>
            <script>
                Swal.fire({
                    title: 'OTP Expired',
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Try Again!',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'login.html';
                    }
                });
            </script>
            </body>
            </html>
            ";
            session_unset();
            session_destroy();
            die();
        }
        if ($enteredOtp == $_SESSION['otp']) {
            echo "
            <html>
            <head>
            <link href='https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css' rel='stylesheet'>
            </head>
            <body>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js'></script>
            <script>
                Swal.fire({
                    title: 'Wait a moment',
                    timer: 2000,
                    timerProgressBar: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            window.location.href = '../index.php';
                        }
                });
            </script>
            </body>
            </html>
            ";              
            sendLoginAlert();
            exit();
        } else {
            echo "
            <html>
            <head>
            <link href='https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css' rel='stylesheet'>
            </head>
            <body>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js'></script>
            <script>
                Swal.fire({
                    title: 'Invalid OTP',
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Try Again!',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'login.html';
                    }
                });
            </script>
            </body>
            </html>
            ";
            session_unset();
            session_destroy();
            die();
        }
    } else {
        echo "
        <html>
        <head>
        <link href='https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css' rel='stylesheet'>
        </head>
        <body>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js'></script>
        <script>
            Swal.fire({
                title: 'Something went wrong',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Try Again!',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'login.html';
                }
            });
        </script>
        </body>
        </html>
        ";
        session_unset();
        session_destroy();
        die();
    }
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
        <form action="login.php" method="POST">
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

<?php
    function sendLoginAlert(){
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'system.vacation.rental@gmail.com'; 
            $mail->Password = ''; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('system.vacation.rental@gmail.com', 'Vacation Rental');
            $mail->addAddress($_SESSION['email']);

            $mail->isHTML(true);
            $mail->Subject = 'Login Alert';
            $mail->Body = '
                        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 10px;
            background-color: #ff6b6b;
            color: #ffffff;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            line-height: 1.6;
        }
        .content h2 {
            font-size: 20px;
            color: #ff6b6b;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #ff6b6b;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Login Activity Detected</h1>
    </div>
    <div class="content">
        <h2>Hello '.$_SESSION['username'].',</h2>
        <p>We noticed a login request to your account on Vacation Rental.</p>
        <p>If this was you, no further action is required.</p>
        <p><strong>If you did not initiate this login, please secure your account immediately by changing your password.</strong></p>
    </div>
    <div class="footer">
        <p>For further assistance, contact us at <a href="mailto:system.vacation.rental@gmail.com">our support</a>.</p>
        <p>&copy; 2024 Vacation Rental. All rights reserved.</p>
    </div>
</div>

</body>
</html>
            ';

            $mail->send();

        } catch (Exception $e) {
            echo "
            <html>
            <head>
            <link href='https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css' rel='stylesheet'>
            </head>
            <body>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js'></script>
            <script>
                Swal.fire({
                    title: 'Something went wrong',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Try Again!',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'login.html';
                    }
                });
            </script>
            </body>
            </html>
            ";
            session_unset();
            session_destroy();
            die();
        }
    }
?>