<?php
require('inc/essentials.php');
adminLogin();
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
if (isset($_POST['serial_number_remove'])) {
    $srno = $_POST['serial_number_remove'];

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM rooms WHERE serials = ?");
    $stmt->bind_param("s", $srno);

    // Execute the statement
    if ($stmt->execute()) {
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
                title: 'Room Removed Successfully',
                showConfirmButton: false,
                timer: 1700,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                    window.location.href = 'rooms.php';
            });
        </script>
        </body>
        </html>
        ";
    } else {
        echo "Error deleting room: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No serial number specified for deletion.";
}
mysqli_commit($conn);
$conn->close();
