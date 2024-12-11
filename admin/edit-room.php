<?php
require('inc/essentials.php');
adminLogin();

$hname = 'localhost';
$uname = 'root';
$pass = '';
$db = 'hms';
$maxFileSize = 16 * 1024 * 1024;

$conn = mysqli_connect($hname, $uname, $pass, $db);
if (!$conn) {
    die("Cannot connect to database: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $serial = mysqli_real_escape_string($conn, $_POST['room_serial']);
    
    if ($serial == '') {
        echo "<script>alert('Serial number is required!');</script>";
        redirect('rooms.php');
        $conn->close();
        die();
    }

    // Check if the serial number exists in the database
    $checkSerialQuery = "SELECT COUNT(*) FROM rooms WHERE serials = ?";
    $checkSerialStmt = $conn->prepare($checkSerialQuery);
    $checkSerialStmt->bind_param('s', $serial);
    $checkSerialStmt->execute();
    $checkSerialStmt->bind_result($serialCount);
    $checkSerialStmt->fetch();
    $checkSerialStmt->close();

    if ($serialCount == 0) {
        echo "<script>alert('Invalid serial number, Room not found!');</script>";
        redirect('rooms.php');
        $conn->close();
        die();
    }

    // Prepare the update fields array
    $updateFields = [];
    $updateParams = [];
    $paramTypes = '';

    // Dynamically add each field to be updated only if it has a value
    if (!empty($_POST['room_name'])) {
        $updateFields[] = "roomname = ?";
        $updateParams[] = $_POST['room_name'];
        $paramTypes .= 's';
    }
    if (!empty($_POST['room_price'])) {
        $updateFields[] = "price = ?";
        $updateParams[] = $_POST['room_price'];
        $paramTypes .= 'i';
    }
    if (!empty($_POST['room_capacity'])) {
        $updateFields[] = "maxperson = ?";
        $updateParams[] = $_POST['room_capacity'];
        $paramTypes .= 'i';
    }
    if (!empty($_POST['room_size'])) {
        $updateFields[] = "roomsize = ?";
        $updateParams[] = $_POST['room_size'];
        $paramTypes .= 'i';
    }
    if (!empty($_POST['room_view'])) {
        $updateFields[] = "roomview = ?";
        $updateParams[] = $_POST['room_view'];
        $paramTypes .= 's';
    }
    if (!empty($_POST['bed_capacity'])) {
        $updateFields[] = "bed = ?";
        $updateParams[] = $_POST['bed_capacity'];
        $paramTypes .= 'i';
    }
    if (!empty($_POST['location'])) {
        $updateFields[] = "location = ?";
        $updateParams[] = $_POST['location'];
        $paramTypes .= 's';
    }
    if (!empty($_POST['availability'])) {
        $updateFields[] = "available = ?";
        $updateParams[] = $_POST['availability'];
        $paramTypes .= 's';
    }

    // Handle optional image upload
    if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] == 0) {
        if ($_FILES['room_image']['size'] > $maxFileSize) {
            echo "<script>alert('Image exceeds the maximum allowed size of 16 MB.');</script>";
            redirect('rooms.php');
            $conn->close();
            die();
        }
        $imageTmpName = $_FILES['room_image']['tmp_name'];
        $imageName = $_FILES['room_image']['name'];
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $allowedExtensions = ['png'];

        if (in_array(strtolower($imageExtension), $allowedExtensions)) {
            $roomimages = file_get_contents($imageTmpName);
            $updateFields[] = "roomimages = ?";
            $updateParams[] = $roomimages;
            $paramTypes .= 's';
        } else {
            echo "<script>alert('Invalid image format. Only PNG is allowed.');</script>";
            redirect('rooms.php');
            $conn->close();
            die();
        }
    }

    // Check if there are fields to update, ensuring at least one changeable field is provided
    if (count($updateFields) > 0) {
        // Prepare the SQL statement
        $updateQuery = "UPDATE rooms SET " . implode(", ", $updateFields) . " WHERE serials = ?";
        $updateParams[] = $serial;
        $paramTypes .= 's';

        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param($paramTypes, ...$updateParams);
        
        if ($updateStmt->execute()) {
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
                    title: 'Room Modified Successfully',
                    showConfirmButton: false,
                    timer: 1800,
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
            echo "<script>alert('Failed to update room details.');</script>";
            redirect('rooms.php');
        }
        $updateStmt->close();
    } else {
        // No fields were provided to update
        echo "<script>alert('Please provide at least one field to update.');</script>";
        redirect('rooms.php');
    }

    // Close the connection
    mysqli_commit($conn);
    $conn->close();
}
