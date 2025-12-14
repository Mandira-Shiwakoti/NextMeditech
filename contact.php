<!doctype html>
<html class="no-js" lang="zxx">
  <?php include 'head.php' ?>
    <body>
        <!-- header start -->
     <?php include 'header.php' ?>
		<!-- header end -->
        <!-- Breadcrumb Area Start -->
        <div class="breadcrumb-area bg-image-3 ptb-150" style="background-image: url('assets/img/product/aboutus.jpg');">
            <div class="container">
                
            </div>
        </div>
		<!-- Breadcrumb Area End -->
		<!-- Contact Area Start -->
        <div class="contact-us ptb-95">
            <div class="container">
                <h3 style="text-align:center;padding-bottom: 50px;">CONTACT US</h3>
                <div class="row">
					<!-- Contact Form Area Start -->
					<div class="col-lg-8">
						<div class="small-title mb-30">
						
							<p>Please submit the query form. </p>
						</div>
						<form id="contact-form" action="send-email.php" method="post">
    <div class="row">
                <!-- Title -->
        <div class="col-lg-2">
            <div class="contact-form-style mb-20">
                <select name="title" required>
                    <option value="">Title</option>
                    <option value="Mr">Mr</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>

        <!-- First Name -->
        <div class="col-lg-5">
            <div class="contact-form-style mb-20">
                <input name="first_name" placeholder="First Name *" type="text" required>
            </div>
        </div>

        <!-- Last Name -->
        <div class="col-lg-5">
            <div class="contact-form-style mb-20">
                <input name="last_name" placeholder="Last Name *" type="text" required>
            </div>
        </div>

        <!-- Company / Organization -->
        <div class="col-lg-6">
            <div class="contact-form-style mb-20">
                <input name="company" placeholder="Company / Organization *" type="text" required>
            </div>
        </div>

        <!-- Email -->
        <div class="col-lg-6">
            <div class="contact-form-style mb-20">
                <input name="email" placeholder="Email *" type="email" required>
            </div>
        </div>

        <!-- Phone -->
        <div class="col-lg-6">
            <div class="contact-form-style mb-20">
                <input name="phone" placeholder="Phone Number *" type="text" required>
            </div>
        </div>

        <!-- Street -->
        <div class="col-lg-6">
            <div class="contact-form-style mb-20">
                <input name="street" placeholder="Street *" type="text" required>
            </div>
        </div>

        <!-- Zip -->
        <div class="col-lg-4">
            <div class="contact-form-style mb-20">
                <input name="zip" placeholder="Zip Code *" type="text" required>
            </div>
        </div>

        <!-- City -->
        <div class="col-lg-4">
            <div class="contact-form-style mb-20">
                <input name="city" placeholder="City *" type="text" required>
            </div>
        </div>

        <!-- Region -->
        <div class="col-lg-4">
            <div class="contact-form-style mb-20">
                <input name="region" placeholder="Region *" type="text" required>
            </div>
        </div>

        <!-- Subject -->
        <div class="col-lg-6">
            <div class="contact-form-style mb-20">
                <input name="subject" placeholder="Subject *" type="text" required>
            </div>
        </div>

        <!-- Country -->
        <div class="col-lg-6">
            <div class="contact-form-style mb-20">
                <input name="country" placeholder="Country *" type="text" required>
            </div>
        </div>

        <!-- Message -->
        <div class="col-lg-12">
            <div class="contact-form-style mb-20">
                <textarea name="message" placeholder="Message *" required></textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="col-lg-12">
            <div class="contact-form-style">
                <button class="submit" type="submit">SEND MESSAGE</button>
            </div>
        </div>

    </div>
</form>

						<p class="form-messege"></p>
					</div>
					<!-- Contact Form Area End -->
					<!-- Contact Address Strat -->
					<div class="col-lg-4">
						<div class="small-title mb-30">
							
							<p>You can contact us @</p>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="contact-information mb-30">
									<h4>Our Location</h4>
									<h5><strong>Next Meditech Pvt. Ltd.</strong><h5>
									<p><strong>Address:</strong> Baluwatar-4, Kathmandu Metropolitan City, Bagmati Province, Nepal</p>
								</div>
							</div>
						<!--	<div class="col-lg-12 col-md-12">
								<div class="contact-information contact-mrg mb-30">
									<h4>Phone Number</h4>
									<p>
										<a href="tel:01234567">01234567 </a>
										
									</p>
								</div>
							</div>-->
							
							<div class="col-lg-12 col-md-12">
								<div class="contact-information contact-mrg mb-30">
									<h4>Email Contacts</h4>
									<p><strong>General Inquiries : </strong> <a href="mailto:info@nextmeditech.com">info@nextmeditech.com</a>	</p>
									  <p><strong>Domestic Sales:</strong> <a href="mailto:sales@nextmeditech.com">sales@nextmeditech.com</a></p>
									  <p><strong>International B2B Partnerships : </strong><a href="mailto:business@nextmeditech.com">business@nextmeditech.com</a></p>
									<p><strong>Web Address : </strong><a href="https://www.nextmeditech.com">www.nextmeditech.com</a></p>
									
									 
									<p><strong>Reach out to the right team directly for faster response</strong></p>
								</div>
							</div>
						</div>
                    </div>
					<!-- Contact Address Strat -->
					<!-- Google Map Start -->
					<!--<div class="col-md-12">
						<div id="store-location">
							<div class="contact-map pt-80">
								<div id="map"></div>
							</div>
						</div>
					</div> -->
					<!-- Google Map Start -->
                </div>
            </div>
             <?php	require_once 'our-product.php'; ?>
        </div>
		<!-- Contact Area Start -->
        <!-- Footer style Start -->
     <?php require_once 'footer.php';  ?>
       <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmGmeot5jcjdaJTvfCmQPfzeoG_pABeWo"></script>
		<script>
            function init() {
                var mapOptions = {
                    zoom: 11,
                    scrollwheel: false,
                    center: new google.maps.LatLng(40.709896, -73.995481),
                    styles: 
                    [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"visibility":"on"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#f53651"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"},{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"},{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45},{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"road.highway.controlled_access","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#f1ced3"},{"visibility":"on"}]}]
                };
                var mapElement = document.getElementById('map');
                var map = new google.maps.Map(mapElement, mapOptions);
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(40.709896, -73.995481),
                    map: map,
                    icon: 'assets/img/icon-img/map.png',
                    animation:google.maps.Animation.BOUNCE,
                    title: 'Snazzy!'
                });
            }
            google.maps.event.addDomListener(window, 'load', init);
		</script>  -->
        <script src="assets/js/main.js"></script>
    </body>

</html>
