<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html"); // Redirect to login if not logged in
    exit;
}

// Fetch user details from session
$mail = $_SESSION['email'];

// Database connection (adjust as needed)
$conn = mysqli_connect("localhost", "root", "", "hms"); // Make sure your DB connection is correct
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch bookings for the logged-in user
$sql = "SELECT bookingid, roomname, checkin, checkout FROM bookedrooms WHERE email='$mail'";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Vacation Rental</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,500,500i,600,600i,700,700i&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
 
    <link rel="stylesheet" href="../css/animate.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="../css/magnific-popup.css">

    <link rel="stylesheet" href="../css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="../css/jquery.timepicker.css">

    <link rel="stylesheet" href="../css/flaticon.css">
    <link rel="stylesheet" href="../css/style.css">

    <style>
                /* Center the "no bookings" message */
        .no-bookings {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;  /* Increased font size */
            color: #666;
            text-align: center;
        }
        .booking-card {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }
        .booking-card h4 {
            font-size: 18px;
            color: #333;
        }
        .booking-card p {
            font-size: 14px;
            color: #666;
        }
        .booking-card p strong {
            color: #333;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #ddd;
            font-size: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
  </head>
  <body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="../index.php"><i class="bi bi-geo-fill me-1 "></i>Vacation<span> Rental</span></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars"></span> Menu
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="../index.php" class="nav-link">Home</a></li>
                    <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) : ?>
                        <li class="nav-item"><a href="user/login.html" class="nav-link">Login</a></li>
                    <?php else : ?>
                        <li class="nav-item active"><a href="profile.php" class="nav-link">Bookings</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a href="../about.php" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="../services.php" class="nav-link">Services</a></li>
                    <li class="nav-item"><a href="../rooms.php" class="nav-link">Rooms</a></li>
                    <li class="nav-item"><a href="../contact.php" class="nav-link">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- END nav -->
        <!-- Profile Section -->
        <div class="container mt-5">
        <h2 class="text-center">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        <h3 class="text-center text-muted" style="font-size: 20px;">
        <?php echo htmlspecialchars($_SESSION['email']); ?>
        </h3>
        <br>

        <!-- User's Bookings -->
         <br>
        <div class="row">
            <div class="col-md-12">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='booking-card'>";
                        echo "<div class='card-header'>Booking ID: " . $row['bookingid'] . "</div>";
                        echo "<li class='card-body' style='color: black'><strong>Hotel Name:</strong> " . $row['roomname'] . "</li>";
                        echo "<li class='card-body' style='color: black'><strong>Check-in:</strong> " . $row['checkin'] . "</li>";
                        echo "<li class='card-body' style='color: black'><strong>Check-out:</strong> " . $row['checkout'] . "</li>";
                        echo "</div><hr>";
                    }
                } else {
                    echo "<br><br><br><br>";
                    echo "<div class='no-bookings'><p><strong>:(</strong></p></div>";
                    echo "<div class='no-bookings'><p><strong>You have no bookings yet</strong></p></div>";
                }
                ?>
            </div>
        </div>
    </div>

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


  <script src="../js/jquery.min.js"></script>
  <script src="../js/jquery-migrate-3.0.1.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/jquery.easing.1.3.js"></script>
  <script src="../js/jquery.waypoints.min.js"></script>
  <script src="../js/jquery.stellar.min.js"></script>
  <script src="../js/jquery.animateNumber.min.js"></script>
  <script src="../js/bootstrap-datepicker.js"></script>
  <script src="../js/jquery.timepicker.min.js"></script>
  <script src="../js/owl.carousel.min.js"></script>
  <script src="../js/jquery.magnific-popup.min.js"></script>
  <script src="../js/scrollax.min.js"></script>
  <script src="../js/main.js"></script>    
  </body>
</html>