<?php
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
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $name = $_POST['name'];

    $email_length = strlen((string)$email);
    $phone_length = strlen((string)$phone);
    $name_length = strlen((string)$name);

    if ($email_length > 30 || $name_length > 30 || $phone_length > 10){
        echo "<script>alert('Something went wrong!');
        window.location.href = 'contact.php';</script>";
        $conn->close();
        die();
    }

    if ($email_length == 0 || $name_length == 0 || $phone_length == 0){
        echo "<script>alert('Field/s cannot be blank!');
        window.location.href = 'contact.php';</script>";
        $conn->close();
        die();
    }

    $tickets = substr(str_shuffle("0123456789"),0,6);

    // SQL to insert data into your table
    $sql = "INSERT INTO calls (ticketid, name, email, phone) VALUES ('$tickets', '$name', '$email', '$phone')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Your call request has been registered successfully!');
        window.location.href = 'contact.php';</script>";
        mysqli_commit($conn);
        $conn->close();
        die();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
mysqli_commit($conn);
$conn->close();
