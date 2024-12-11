<?php
session_start();
// Check if roomname parameter is provided
if (isset($_GET['roomname'])) {
    $room_name = $_GET['roomname'];
	$roomid = $_GET['rid'];
} else {
    echo "No room name specified for booking.";
}
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

    <div class="hero-wrap js-fullheight" style="background-image: url('images/room-1.png');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate">
          	<h2 class="subheading">Welcome to Vacation Rental</h2>
          	<h1 class="mb-4"><?php echo ($room_name); ?></h1>
            <!-- <p><a href="#" class="btn btn-primary">Learn more</a> <a href="#" class="btn btn-white">Contact us</a></p> -->
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section ftco-book ftco-no-pt ftco-no-pb">
    	<div class="container">
	    	<div class="row justify-content-end">
	    		<div class="col-lg-4">
					<form action="user/room-booking.php?roomname=<?php echo urlencode($room_name) ?>&rid=<?php echo urlencode($roomid) ?>" class="appointment-form" style="margin-top: -568px;"
					method="POST">
						<h3 class="mb-3">Book this room</h3>
						<div class="row">
						   
							<div class="col-md-12">
								<div class="form-group">
									<input type="text" class="form-control" placeholder="Full Name"
									name="name" required maxlength="30">
								</div>
							</div>
							<div class="col-md-12">
    <div class="form-group">
        <input type="number" class="form-control" placeholder="Phone Number"
        name="phone" required id="phone" oninput="limitPhoneNumber(this)" maxlength="10">
        <small class="form-text text-muted">This is essential for future communication</small>
    </div>
</div>

<script>
function limitPhoneNumber(input) {
    if (input.value.length > 10) {
        input.value = input.value.slice(0, 10); // Limit to 10 digits
    }
}
</script>

							<div class="col-md-6">
								<div class="form-group">
								<div class="input-wrap">
									<div class="icon"><span class="ion-md-calendar"></span></div>
										<input type="text" class="form-control appointment_date-check-in" placeholder="Check-In"
										name="checkin" required maxlength="14">
									</div>
								</div>
							</div>
						
							<div class="col-md-6">
									<div class="form-group">
										<div class="icon"><span class="ion-md-calendar"></span></div>
										<input type="text" class="form-control appointment_date-check-out" placeholder="Check-Out"
										name="checkout" required maxlength="14">
									</div>
							</div>
							
						
						
							<div class="col-md-12">
								<div class="form-group">
									<input type="submit" value="Book an Appointment" class="btn btn-primary py-3 px-4">
								</div>
							</div>
						</div>
				</form>
	    		</div>
	    	</div>
	    </div>
    </section>
   

    <section class="ftco-section bg-light">
			<div class="container">
				<div class="row no-gutters">
					<div class="col-md-6 wrap-about">
						<div class="img img-2 mb-4" style="background-image: url(images/image_2.jpg);">
						</div>
						<h2>The most recommended vacation rental</h2>
						<p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth. Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
					</div>
					<div class="col-md-6 wrap-about ftco-animate">
	          <div class="heading-section">
	          	<div class="pl-md-5">
		            <h2 class="mb-2">What we offer</h2>
	            </div>
	          </div>
	          <div class="pl-md-5">
							<p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>
							<div class="row">
		            <div class="services-2 col-lg-6 d-flex w-100">
		              <div class="icon d-flex justify-content-center align-items-center">
		            		<span class="flaticon-diet"></span>
		              </div>
		              <div class="media-body pl-3">
		                <h3 class="heading">Tea Coffee</h3>
		                <p>A small river named Duden flows by their place and supplies it with the necessary</p>
		              </div>
		            </div> 
		            <div class="services-2 col-lg-6 d-flex w-100">
		              <div class="icon d-flex justify-content-center align-items-center">
		            		<span class="flaticon-workout"></span>
		              </div>
		              <div class="media-body pl-3">
		                <h3 class="heading">Hot Showers</h3>
		                <p>A small river named Duden flows by their place and supplies it with the necessary</p>
		              </div>
		            </div>
		            <div class="services-2 col-lg-6 d-flex w-100">
		              <div class="icon d-flex justify-content-center align-items-center">
		            		<span class="flaticon-diet-1"></span>
		              </div>
		              <div class="media-body pl-3">
		                <h3 class="heading">Laundry</h3>
		                <p>A small river named Duden flows by their place and supplies it with the necessary</p>
		              </div>
		            </div>      
		            <div class="services-2 col-lg-6 d-flex w-100">
		              <div class="icon d-flex justify-content-center align-items-center">
		            		<span class="flaticon-first"></span>
		              </div>
		              <div class="media-body pl-3">
		                <h3 class="heading">Air Conditioning</h3>
		                <p>A small river named Duden flows by their place and supplies it with the necessary</p>
		              </div>
		            </div>
		            <div class="services-2 col-lg-6 d-flex w-100">
		              <div class="icon d-flex justify-content-center align-items-center">
		            		<span class="flaticon-first"></span>
		              </div>
		              <div class="media-body pl-3">
		                <h3 class="heading">Free Wifi</h3>
		                <p>A small river named Duden flows by their place and supplies it with the necessary</p>
		              </div>
		            </div> 
		            <div class="services-2 col-lg-6 d-flex w-100">
		              <div class="icon d-flex justify-content-center align-items-center">
		            		<span class="flaticon-first"></span>
		              </div>
		              <div class="media-body pl-3">
		                <h3 class="heading">Kitchen</h3>
		                <p>A small river named Duden flows by their place and supplies it with the necessary</p>
		              </div>
		            </div> 
		            <div class="services-2 col-lg-6 d-flex w-100">
		              <div class="icon d-flex justify-content-center align-items-center">
		            		<span class="flaticon-first"></span>
		              </div>
		              <div class="media-body pl-3">
		                <h3 class="heading">Ironing</h3>
		                <p>A small river named Duden flows by their place and supplies it with the necessary</p>
		              </div>
		            </div> 
		            <div class="services-2 col-lg-6 d-flex w-100">
		              <div class="icon d-flex justify-content-center align-items-center">
		            		<span class="flaticon-first"></span>
		              </div>
		              <div class="media-body pl-3">
		                <h3 class="heading">Lovkers</h3>
		                <p>A small river named Duden flows by their place and supplies it with the necessary</p>
		              </div>
		            </div>
		          </div>  
						</div>
					</div>
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