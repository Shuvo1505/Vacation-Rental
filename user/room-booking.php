<?php
require 'send-booking.php';
session_start();
// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hms";

$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $roomname = urldecode($_GET['roomname']);
    $roomid = urldecode($_GET['rid']);

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
        echo "
        <html>
        <head>
        <link href='https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css' rel='stylesheet'>
        </head>
        <body>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js'></script>
        <script>
            Swal.fire({
                title: 'You need to login first',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Dismiss',
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
    } else {
        $account_mail = $_SESSION['email'];
        $name_length = strlen((string)$name);
        $phone_length = strlen((string)$phone);
        $checkin_length = strlen((string)$checkin);
        $checkout_length = strlen((string)$checkout);
        $email_length = strlen((string)$account_mail);
    
        if ($email_length > 30 || $name_length > 30 || $phone_length > 10 ||
        $checkin_length > 14 || $checkout_length > 14) {
            header("Location: booking-fail.html");
            $conn->close();
            die();
        }
    
        if ($email_length == 0 || $name_length == 0 || $phone_length == 0 ||
        $checkin_length == 0 || $checkout_length == 0){
            echo "<script>alert('Field/s cannot be blank!');</script>";
            $conn->close();
            die();
        }
        // Generate booking ID
        $booking_id = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
        
        // SQL to insert data into your table
        $sql = "INSERT INTO bookedrooms (bookingid, email, name, phone, checkin, checkout, roomname) VALUES 
            ('$booking_id', '$account_mail', '$name', '$phone', '$checkin', '$checkout', '$roomname')";
        
        $accsql = "SELECT price, maxperson, roomsize, roomview, bed FROM rooms WHERE serials='$roomid'";
        $result = $conn->query($accsql);
        $acco = $result->fetch_assoc();
    
        if ($conn->query($sql) === TRUE) {
            // Close connection and proceed to display modal on success
            mysqli_commit($conn);
            sendBookingDetails($booking_id);
            $conn->close();
            ?>
            
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <title>Confirmation</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                
                <!-- Required CSS -->
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap">
            </head>
            <body>
    
<!-- Booking Confirmation Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingModalLabel">Appointment Confirmation</h5>
            </div>
            <div class="modal-body">
                <!-- Room Details Section -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">Room Details</h5>
                        <p><strong>Name:</strong> <?php echo $roomname; ?></p>
                        <p><strong>Price:</strong> <?php echo $acco['price'].' /Night'; ?></p>
                        <p><strong>Capacity:</strong> <?php echo $acco['maxperson'].' Person/s'; ?></p>
                        <p><strong>Room Size:</strong> <?php echo $acco['roomsize'].' Square meter/s'; ?></p>
                        <p><strong>Total Bed:</strong> <?php echo $acco['bed']; ?></p>
                        <p><strong>Site View:</strong> <?php echo $acco['roomview']; ?></p>
                    </div>
                </div>
                
                <hr> <!-- Divider between sections -->

                <!-- Appointment Details Section -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">Appointment Details</h5>
                        <p><strong>Booking ID:</strong> <?php echo $booking_id; ?></p>
                        <p><strong>Email:</strong> <?php echo $account_mail; ?></p>
                        <p><strong>Name:</strong> <?php echo $name; ?></p>
                        <p><strong>Phone:</strong> <?php echo $phone; ?></p>
                        <p><strong>Check-In Date:</strong> <?php echo $checkin; ?></p>
                        <p><strong>Check-Out Date:</strong> <?php echo $checkout; ?></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="window.location.href='../index.php'">Go to Home</button>
            </div>
        </div>
    </div>
</div>
    
            <!-- Optional JavaScript and jQuery -->
            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
            <script>
                // Show the booking modal immediately upon page load
                $(document).ready(function() {
                    $('#bookingModal').modal('show');
                });
            </script>
    
            </body>
            </html>
    
            <?php
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
mysqli_commit($conn);
$conn->close();
?>