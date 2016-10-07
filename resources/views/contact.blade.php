@extends('layouts.app')

@section('content')
<div class="clearfix"></div>




<!-- Titlebar
================================================== -->
<div id="titlebar" class="single">
	<div class="container">

		<div class="sixteen columns">
			<h2>Contact</h2>
			<nav id="breadcrumbs">
				<ul>
					<li>You are here:</li>
					<li><a href="#">Home</a></li>
					<li>Contact</li>
				</ul>
			</nav>
		</div>

	</div>
</div>




<!-- Content
================================================== -->
<!-- Container -->
<div class="container">
	<div class="sixteen columns">

		<h3 class="margin-bottom-20">Our Office</h3>

		<!-- Google Maps -->
		<section style="background-color: #f6f6f6" class="google-map-container">

			<div id="map" style="width:100%;height:500px"></div>

			<script>
				function myMap() {
					var mapCanvas = document.getElementById("map");
					var mapOptions = {
						center: new google.maps.LatLng(51.5, -0.2),
						zoom: 10
					}
					var map = new google.maps.Map(mapCanvas, mapOptions);
				}
			</script>

			<script src="https://maps.googleapis.com/maps/api/js?callback=myMap"></script>

		</section>
		<!-- Google Maps / End -->

	</div>
</div>
<!-- Container / End -->



	<div class="container" style="background-color: #f6f6f6">

		<div class="eleven columns">

			<h3 class="margin-bottom-15">Contact Form</h3>

			<!-- Contact Form -->
			<section id="contact" class="padding-right">

				<!-- Success Message -->
				<mark id="message"></mark>

				<!-- Form -->
				<form method="post" name="contactform" id="contactform">

					<fieldset>

						<div>
							<label>Name:</label>
							<input name="name" type="text" id="name" />
						</div>

						<div>
							<label >Email: <span>*</span></label>
							<input name="email" type="email" id="email" pattern="^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$" />
						</div>

						<div>
							<label>Message: <span>*</span></label>
							<textarea name="comment" cols="40" rows="3" id="comment" spellcheck="true"></textarea>
						</div>

					</fieldset>
					<div id="result"></div>
					<input type="submit" class="submit" id="submit" value="Send Message" />
					<div class="clearfix"></div>
					<div class="margin-bottom-40"></div>
				</form>

			</section>
			<!-- Contact Form / End -->

		</div>
		<!-- Container / End -->


		<!-- Sidebar
        ================================================== -->
		<div class="five columns">

			<!-- Information -->
			<h3 class="margin-bottom-10">Information</h3>
			<div class="widget-box">
				<p>Morbi eros bibendum lorem ipsum dolor pellentesque pellentesque bibendum. </p>

				<ul class="contact-informations">
					<li>45 Park Avenue, Apt. 303</li>
					<li>New York, NY 10016 </li>
				</ul>

				<ul class="contact-informations second">
					<li><i class="fa fa-phone"></i> <p>+48 880 440 110</p></li>
					<li><i class="fa fa-envelope"></i> <p>mail@example.com</p></li>
					<li><i class="fa fa-globe"></i> <p>www.example.com</p></li>
				</ul>

			</div>

			<!-- Social -->
			<div class="widget margin-top-30">
				<h3 class="margin-bottom-5">Social Media</h3>
				<ul class="social-icons">
					<li><a class="facebook" href="#"><i class="icon-facebook"></i></a></li>
					<li><a class="twitter" href="#"><i class="icon-twitter"></i></a></li>
					<li><a class="gplus" href="#"><i class="icon-gplus"></i></a></li>
					<li><a class="linkedin" href="#"><i class="icon-linkedin"></i></a></li>
				</ul>
				<div class="clearfix"></div>
				<div class="margin-bottom-50"></div>
			</div>

		</div>
	</div>


@endsection
