<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hms";

session_start();
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $name = $_POST['name'];

        $email_length = strlen((string)$email);
        $pass_length = strlen((string)$password);
        $name_length = strlen((string)$name);

        if ($email_length > 30 || $name_length > 30 || $pass_length > 28 || $pass_length < 8) {
            header("Location: registration-fail.html");
            $conn->close();
            die();
        }

        if ($email_length == 0 || $name_length == 0 || $pass_length == 0) {
            echo '<script>
            alert("Field/s can\'t be blank!");
            window.location.href = "register.html";
            </script>';
            $conn->close();
            die();
        }

        $email_check_query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $result = $conn->query($email_check_query);

        if ($result->num_rows > 0) {
            echo '<script>
            alert("This email is already registered!");
            window.location.href = "register.html";
            </script>';
            session_unset();
            session_destroy();
            die();
        } else {
            $otp = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_time'] = time();

            // Store user details in session
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            $_SESSION['name'] = $name;

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
                $mail->Subject = 'Verify your email address';
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
                            <div class="header">Email Verification</div>
                            <div class="content">
                                <p>Dear User,</p>
                                <p>We have received a request to create your account. To complete the account creation process, please use the following code:</p>
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
        }
    } else if (isset($_POST['otp'])) {
        $enteredOtp = $_POST['otp'];
        $enteredOtp_length = strlen($enteredOtp);

        if ($enteredOtp_length != 6) {
            echo '<script>
            alert("Invalid OTP length!");
            window.location.href = "register.html";
            </script>';
            session_unset();
            session_destroy();
            die();
        }

        if (time() - $_SESSION['otp_time'] > 360) {
            echo '<script>
                alert("OTP has expired. Please request a new one.");
                window.location.href = "register.html";
            </script>';
            session_unset();
            session_destroy();
            die();
        }

        if ($enteredOtp == $_SESSION['otp']) {
            // Insert user data into the database after OTP verification
            $sql = "INSERT INTO users (email, password, name) VALUES ('{$_SESSION['email']}', '{$_SESSION['password']}', '{$_SESSION['name']}')";

            if ($conn->query($sql) === TRUE) {
                header("Location: registration-success.html");
                session_unset();
                session_destroy();
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo '<script>
                alert("Invalid OTP. Please try again!");
                window.location.href = "register.html";
            </script>';
            session_unset();
            session_destroy();
            die();
        }
    } else {
        echo '<script>
        alert("Something went wrong!");
        window.location.href = "register.html";
        </script>';
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
        <form action="register.php" method="POST">
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
