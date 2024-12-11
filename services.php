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
	        	<li class="nav-item active"><a href="services.php" class="nav-link">Services</a></li>
	        	<li class="nav-item"><a href="rooms.php" class="nav-link">Rooms</a></li>
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
          	<p class="breadcrumbs mb-2"><span class="mr-2"><a href="index.php">Home <i class="fa fa-chevron-right"></i></a></span> <span>Services <i class="fa fa-chevron-right"></i></span></p>
            <h1 class="mb-0 bread">Services</h1>
          </div>
        </div>
      </div>
    </section>
   
    <section class="ftco-section bg-light">
      <div class="container">
        <div class="row">
          <div class="col px-4 ftco-animate">
            <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
              <div class="d-flex align-items-center mb-2">            
                <i class="bi bi-geo-alt-fill" style="font-size: 50px;"></i>
                <h5 class="m-0 ms-3">&nbsp;&nbsp;Map Direction</h5>
              </div>
              <p>Our intuitive map direction feature ensures that guests have seamless 
                navigation from booking to check-in. Integrated with real-time updates, 
                it offers precise directions to your accommodation and highlights key 
                landmarks along the way, helping guests feel confident and prepared as 
                they travel.</p>
            </div>
          </div>
          <div class="col px-4 ftco-animate">
            <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
              <div class="d-flex align-items-center mb-2">
              <i class="bi bi-building-fill" style="font-size: 50px;"></i>
                  <h5 class="m-0 ms-3">&nbsp;&nbsp;Accomodation Services</h5>
              </div>
              <p>We pride ourselves on providing exceptional accommodation services 
                tailored to meet our guests’ unique needs. From comfortable rooms to 
                personalized amenities, our team is dedicated to making every stay 
                memorable, relaxing, and customized to your preferences, setting a high 
                standard for hospitality.</p>
            </div>
          </div>
          <div class="col px-4 ftco-animate">
            <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
              <div class="d-flex align-items-center mb-2">
              <i class="bi bi-trophy" style="font-size: 50px;"></i>
                  <h5 class="mt-2 ms-3">&nbsp;&nbsp;Great Experience</h5>
              </div>
              <p>Our commitment to creating a great experience goes beyond just providing 
                a place to stay. With us, guests enjoy a thoughtfully curated environment, 
                responsive service, and activities designed to make each moment enjoyable 
                and fulfilling. We aim to exceed expectations.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section bg-light ftco-no-pt">
			<div class="container">
				<div class="row no-gutters justify-content-center pb-5 mb-3">
          <div class="col-md-7 heading-section text-center ftco-animate">
            <h2>Amenities</h2>
          </div>
        </div>
				<div class="row">
          <div class="services-2 col-md-3 d-flex w-100 ftco-animate">
            <div class="icon d-flex justify-content-center align-items-center">
          		<span class="flaticon-diet"></span>
            </div>
            <div class="media-body pl-3">
              <h3 class="heading">Tea Coffee</h3>
              <p>A small river named Duden flows by their place and supplies it with the necessary</p>
            </div>
          </div> 
          <div class="services-2 col-md-3 d-flex w-100 ftco-animate">
            <div class="icon d-flex justify-content-center align-items-center">
          		<span class="flaticon-workout"></span>
            </div>
            <div class="media-body pl-3">
              <h3 class="heading">Hot Showers</h3>
              <p>A small river named Duden flows by their place and supplies it with the necessary</p>
            </div>
          </div>
          <div class="services-2 col-md-3 d-flex w-100 ftco-animate">
            <div class="icon d-flex justify-content-center align-items-center">
          		<span class="flaticon-diet-1"></span>
            </div>
            <div class="media-body pl-3">
              <h3 class="heading">Laundry</h3>
              <p>A small river named Duden flows by their place and supplies it with the necessary</p>
            </div>
          </div>      
          <div class="services-2 col-md-3 d-flex w-100 ftco-animate">
            <div class="icon d-flex justify-content-center align-items-center">
          		<span class="flaticon-first"></span>
            </div>
            <div class="media-body pl-3">
              <h3 class="heading">Air Conditioning</h3>
              <p>A small river named Duden flows by their place and supplies it with the necessary</p>
            </div>
          </div>
          <div class="services-2 col-md-3 d-flex w-100 ftco-animate">
            <div class="icon d-flex justify-content-center align-items-center">
          		<span class="flaticon-first"></span>
            </div>
            <div class="media-body pl-3">
              <h3 class="heading">Free Wifi</h3>
              <p>A small river named Duden flows by their place and supplies it with the necessary</p>
            </div>
          </div> 
          <div class="services-2 col-md-3 d-flex w-100 ftco-animate">
            <div class="icon d-flex justify-content-center align-items-center">
          		<span class="flaticon-first"></span>
            </div>
            <div class="media-body pl-3">
              <h3 class="heading">Kitchen</h3>
              <p>A small river named Duden flows by their place and supplies it with the necessary</p>
            </div>
          </div> 
          <div class="services-2 col-md-3 d-flex w-100 ftco-animate">
            <div class="icon d-flex justify-content-center align-items-center">
          		<span class="flaticon-first"></span>
            </div>
            <div class="media-body pl-3">
              <h3 class="heading">Ironing</h3>
              <p>A small river named Duden flows by their place and supplies it with the necessary</p>
            </div>
          </div> 
          <div class="services-2 col-md-3 d-flex w-100 ftco-animate">
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