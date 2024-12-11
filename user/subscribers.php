<?php
    function redirecth($url){
        echo "
            <script>
                window.location.href='$url';
            </script>";
    }

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

        $email_length = strlen((string)$email);

        if ($email_length > 30){
            echo "<script>alert('Email address should be less than 30 characters!');</script>";
            redirecth('../index.php');
        } else if ($email_length == 0){
            echo "<script>alert('Email address should not be blank!');</script>";
            redirecth('../index.php');
        }

        // Check if email already exists in the database
        $check_email_sql = "SELECT * FROM subscribers WHERE email = '$email'";
        $result = $conn->query($check_email_sql);

        if ($result->num_rows > 0) {
            // Email already exists
            echo "<script>alert('This email is already subscribed!');</script>";
            redirecth('../index.php');
        } else {
            // SQL to insert data into your table
            $sql = "INSERT INTO subscribers (email) VALUES ('$email')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Subscription granted successfully!');</script>";
                redirecth('../index.php');
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    mysqli_commit($conn);
    $conn->close();
    die();
?>
