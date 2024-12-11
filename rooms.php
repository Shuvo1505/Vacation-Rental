<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Vacation Rental </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,500,500i,600,600i,700,700i&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
 
    <link rel="stylesheet" href="css/animate.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">

    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
		
		<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	    	<a class="navbar-brand" href="index.php"><i class="bi bi-geo-fill me-1 "></i>Vacation<span> Rental</span></a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="fa fa-bars"></span> Menu
	      </button>
	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	        	<li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
				<?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) : ?>
                        <li class="nav-item"><a href="user/login.html" class="nav-link">Login</a></li>
                        
				<?php else : ?>
                        <!-- Profile dropdown menu -->
                        <li class="nav-item dropdown ms-3">
    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    	Profile
    </a>
	<div class="dropdown-menu" aria-labelledby="profileDropdown">
    <a href="user/profile.php">
        <span class="dropdown-item-text" style="display: flex; align-items: center; padding: 10px 15px; font-size: 16px;">
            <i class="bi bi-calendar-check me-2" style="font-size: 18px;"></i>
            &nbsp;&nbsp;My Bookings
        </span>
    </a>
    <div class="dropdown-divider" style="margin: 0;"></div>
    <a href="user/logout.php">
        <span class="dropdown-item-text" style="display: flex; align-items: center; padding: 10px 15px; font-size: 16px;">
            <i class="bi bi-box-arrow-right me-2" style="font-size: 18px;"></i>
            &nbsp;&nbsp;Logout
        </span>
    </a>
</div>


</li>
                    <?php endif; ?>
	        	<li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
	        	<li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
	        	<li class="nav-item active"><a href="rooms.php" class="nav-link">Rooms</a></li>
	          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->

    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/image_2.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
          	<p class="breadcrumbs mb-2"><span class="mr-2"><a href="index.php">Home <i class="fa fa-chevron-right"></i></a></span> <span>Rooms <i class="fa fa-chevron-right"></i></span></p>
            <h1 class="mb-0 bread">Hotel Rooms</h1>
          </div>
        </div>
      </div>
    </section>

	<section class="ftco-section bg-light ftco-no-pt ftco-no-pb">
    <div class="container-fluid px-md-0">
        <div class="row no-gutters">
            <?php
            // Database connection
			$servername = "localhost";
			$username = "root";
			$password = "";
			$dbname = "hms";
            $conn = mysqli_connect($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch rooms data from database
            $sql = "SELECT serials, roomname, price, available, maxperson, roomsize, roomview, bed, roomimages, location from rooms";
            $result = $conn->query($sql);

            // Check if there are results
            if ($result->num_rows > 0) {
                // Loop through each room and display it
                while ($room = $result->fetch_assoc()) {
					$imageData = base64_encode($room['roomimages']);
          if ($room['available'] == 'YES'){
            $av_status = 'Available';
          } else {
            $av_status = 'Not Available';
          }
          echo '
          <div class="col-lg-6">
              <div class="room-wrap d-md-flex ftco-animate">
                  <a class="img" style="background-image: url(data:image/png;base64,' . $imageData . ');"></a>
                  <div class="half left-arrow d-flex align-items-center">
                      <div class="text p-4 p-xl-5 text-center">
                          <p class="star mb-0"><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span></p>
                          <p class="mb-0"><span class="price mr-1">₹' . $room['price'] . '</span> <span class="price mr-1">/Night</span></p>
                          <h3 class="mb-3"><a>' . $room['roomname'] . '</a></h3>
                          <ul class="list-accomodation">
                              <li><strong>' . $av_status . '</strong></li>
                              <li><span>Room Capacity:</span> ' . $room['maxperson'] . ' Persons</li>
                              <li><span>Room Size:</span> ' . $room['roomsize'] . ' Square meter/s</li>
                              <li><span>Hotel Location:</span> ' . $room['location'] . '</li>
                              <li><span>Hotel View:</span> ' . $room['roomview'] . '</li>
                              <li><span>Bed Capacity:</span> ' . $room['bed'] . '</li>
                          </ul>';
          
                          // Conditionally display "I'm Interested" button if available
                          if ($room['available'] == 'YES') {
                            echo '
                            <p class="pt-1">
                                <a href="room-single.php?roomname=' . urlencode($room['roomname']) . '&rid=' . urlencode($room['serials']) . '" class="btn-custom px-3 py-2">
                                    I\'m Interested<span class="icon-long-arrow-right"></span>
                                </a>
                            </p>';                            
                          }
          
          echo '       </div>
                  </div>
              </div>
          </div>';

                }
            }
            // Close the connection
            $conn->close();
            ?>
        </div>
    </div>
</section>

		
<footer class="footer">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-lg-3 mb-md-0 mb-4">
						<h2 class="footer-heading"><a href="#" class="logo">Vacation Rental</a></h2>
						<p style="color: gray;">© 2024 All Rights Reserved</p>
					</div>
					<div class="col-md-6 col-lg-3 mb-md-0 mb-4">
						<h2 class="footer-heading">Services</h2>
						<ul class="list-unstyled">
              <li><a class="py-1 d-block">Map Direction</a></li>
              <li><a class="py-1 d-block">Accomodation Services</a></li>
              <li><a class="py-1 d-block">Great Experience</a></li>
              <li><a class="py-1 d-block">Perfect central location</a></li>
            </ul>
					</div>
					<div class="col-md-6 col-lg-3 mb-md-0 mb-4">
						<h2 class="footer-heading">Tag cloud</h2>
						<div class="tagcloud">
	            <a class="tag-cloud-link">apartment</a>
	            <a class="tag-cloud-link">home</a>
	            <a class="tag-cloud-link">vacation</a>
	            <a class="tag-cloud-link">rental</a>
	            <a class="tag-cloud-link">rent</a>
	            <a class="tag-cloud-link">house</a>
	            <a class="tag-cloud-link">place</a>
	            <a class="tag-cloud-link">drinks</a>
	          </div>
					</div>
					<div class="col-md-6 col-lg-3 mb-md-0 mb-4">
						<h2 class="footer-heading">Subcribe</h2>
						<form action="user/subscribers.php" class="subscribe-form" method="POST">
              <div class="form-group d-flex">
                <input type="text" class="form-control rounded-left" placeholder="Enter email address" name="email" required>
                <button type="submit" class="form-control submit rounded-right"><span class="sr-only">Submit</span><i class="fa fa-paper-plane"></i></button>
              </div>
            </form>
            <h2 class="footer-heading mt-5">Follow us</h2>
            <ul class="ftco-footer-social p-0">
              <li class="ftco-animate"><a data-toggle="tooltip" data-placement="top" title="Twitter"><span class="fa fa-twitter"></span></a></li>
              <li class="ftco-animate"><a data-toggle="tooltip" data-placement="top" title="Facebook"><span class="fa fa-facebook"></span></a></li>
              <li class="ftco-animate"><a data-toggle="tooltip" data-placement="top" title="Instagram"><span class="fa fa-instagram"></span></a></li>
            </ul>
					</div>
				</div>
			</div>
			<div class="w-100 mt-5 border-top py-5">
				<div class="container">
					<div class="row">
	          
	        </div>
				</div>
			</div>
		</footer>
    
  

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="js/main.js"></script>   
  </body>
</html>