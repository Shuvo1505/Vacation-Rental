<?php
require('inc/essentials.php');
adminLogin();

$hname = 'localhost';
$uname = 'root';
$pass = '';
$db = 'hms';
$maxFileSize = 16 * 1024 * 1024;

// Establish a database connection
$conn = mysqli_connect($hname, $uname, $pass, $db);
if (!$conn) {
    die("Cannot connect to database: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data and sanitize
    $roomname = mysqli_real_escape_string($conn, $_POST['room_name']);
    $price = mysqli_real_escape_string($conn, $_POST['room_price']);
    $maxperson = mysqli_real_escape_string($conn, $_POST['room_capacity']);
    $roomsize = mysqli_real_escape_string($conn, $_POST['room_size']);
    $roomview = mysqli_real_escape_string($conn, $_POST['room_view']);
    $bed = mysqli_real_escape_string($conn, $_POST['bed_capacity']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $available = mysqli_real_escape_string($conn,$_POST['availability']);

    if ($roomname == '' || $price == '' || $maxperson == '' || $roomsize == '' ||
    $roomview == '' || $bed == '' || $location == '' || $available == ''){
        echo "<script>alert('Field/s cannot be blank!');</script>";
        redirect('rooms.php');
        $conn->close();
        die();
    }

    // Handle image upload and check for valid image
    $roomimages = null;
    if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] == 0) {
        if ($_FILES['room_image']['size'] > $maxFileSize) {
            echo "<script>alert('Image exceeds the maximum allowed size of 16 MB.');</script>";
            redirect('rooms.php');
            mysqli_close($conn);
            die();
        }
        $imageTmpName = $_FILES['room_image']['tmp_name'];
        $imageName = $_FILES['room_image']['name'];
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $allowedExtensions = ['png'];

        if (in_array(strtolower($imageExtension), $allowedExtensions)) {
            // Read the image file as binary data
            $roomimages = file_get_contents($imageTmpName);
        } else {
            echo "<script>alert('Invalid image format. Only PNG is allowed.');</script>";
            redirect('rooms.php');
            mysqli_close($conn);
            die();
        }
    }

    // SQL query to insert data into the database
    $sql = "INSERT INTO rooms (roomname, price, location, maxperson, roomsize, roomview, bed, roomimages, available) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare the statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        // Use 's' instead of 'b' for roomimages, as binary data is best handled as a string here
        mysqli_stmt_bind_param($stmt, 'sisississ', $roomname, $price, $location, $maxperson, $roomsize, $roomview, $bed, $roomimages, $available);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
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
                    title: 'Room Saved Successfully',
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
            echo "Error executing statement: " . mysqli_stmt_error($stmt);
        }
        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        die("Error preparing the SQL statement: " . mysqli_error($conn));
    }
    // Close and commit the database connection
    mysqli_commit($conn);
    mysqli_close($conn);
}
