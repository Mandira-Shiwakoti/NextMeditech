<!doctype html>
<html class="no-js" lang="zxx">
    
<?php
 include 'head.php';
if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
    $pid = (int) $_GET['pid'];

    // Prepare and execute query to fetch subcategory details
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE id = :pid");
    $stmt->execute(['pid' => $pid]);
    $subcat = $stmt->fetch();

    if ($subcat) {
        // Display subcategory details
       
        
    } else {
        echo "❌ Subcategory not found.";
    }
} else {
    echo "❌ Invalid or missing product ID.";
}
?>
    <body>
        <!-- header start -->
          <?php include 'header.php' ?>
		<!-- header end -->
		<!-- Breadcrumb Area Start -->
        <div class="breadcrumb-area bg-image-3 ptb-150">
            <div class="container">
                <div class="breadcrumb-content text-center">
					<h3>ABOUT US</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li class="active"><?php echo htmlspecialchars($subcat['title']) ?> </li>
                    </ul>
                </div>
            </div>
        </div>
		<!-- Breadcrumb Area End -->
		<!-- About Us Area Start -->
        <div class="about-us-area pt-100 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-12 d-flex align-items-center">
                        <div class="overview-content-2">
							<h4>Next Meditech</h4>
                            <h2><?php echo htmlspecialchars($subcat['title']) ?></h2>
                            <p class="peragraph-blog">
                                <?php echo $subcat['detail'] ?>
                            </p>
                           
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="overview-img text-center">
                            <a href="#">
                                <img src="assets/img/product/aboutus.jpg" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
             <?php	require_once 'our-product.php'; ?>
        </div>
		<!-- About Us Area End -->
		<!-- Testimonial Area Start -->
      <div class="testimonials-area gray-bg pt-100 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="testimonial-active owl-carousel">
                            <div class="single-testimonial text-center">
                                <div class="testimonial-img">
                                    <img alt="" src="assets/img/icon-img/testi.png">
                                </div>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisici elit, sed do eiusmod tempor incididunt ut labore</p>
                                <h4>Gregory Perkins</h4>
								<h5>Customer</h5>
                            </div>
                            <div class="single-testimonial text-center">
                                <div class="testimonial-img">
                                    <img alt="" src="assets/img/icon-img/testi.png">
                                </div>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisici elit, sed do eiusmod tempor incididunt ut labore</p>
                                <h4>Khabuli Teop</h4>
								<h5>Marketing</h5>
                            </div>
                            <div class="single-testimonial text-center">
                                <div class="testimonial-img">
                                    <img alt="" src="assets/img/icon-img/testi.png">
                                </div>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisici elit, sed do eiusmod tempor incididunt ut labore </p>
                                <h4>Lotan Jopon</h4>
								<h5>Admin</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<!-- Testimonial Area End -->>
		<!-- Team Area Start -->
		<div class="team-area pt-95 pb-70">
            <div class="container">
                <div class="product-top-bar section-border mb-50">
                    <div class="section-title-wrap style-two text-center">
                        <h3 class="section-title">Team Members</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="team-wrapper mb-30">
                            <div class="team-img">
                                <a href="#">
                                    <img src="assets/img/team/team-1.jpg" alt="">
                                </a>
                                <div class="team-action">
                                    <a class="action-plus facebook" href="#">
                                        <i class="ion-social-facebook"></i>
                                    </a>
                                    <a class="action-heart twitter" title="Wishlist" href="#">
                                        <i class="ion-social-twitter"></i>
                                    </a>
                                    <a class="action-cart instagram" href="#">
                                        <i class="ion-social-instagram"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="team-content text-center">
                                <h4>Mr.Mike Banding</h4>
                                <span>Manager </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="team-wrapper mb-30">
                            <div class="product-img">
                                <a href="product-details.html">
                                    <img src="assets/img/team/team-2.jpg" alt="">
                                </a>
                                <div class="team-action">
                                    <a class="action-plus facebook" href="#">
                                        <i class="ion-social-facebook"></i>
                                    </a>
                                    <a class="action-heart twitter" title="Wishlist" href="#">
                                        <i class="ion-social-twitter"></i>
                                    </a>
                                    <a class="action-cart instagram" href="#">
                                        <i class="ion-social-instagram"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="team-content text-center">
                                <h4>Mr.Peter Pan</h4>
                                <span>Developer </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="team-wrapper mb-30">
                            <div class="team-img">
                                <a href="#">
                                    <img src="assets/img/team/team-3.jpg" alt="">
                                </a>
                                <div class="team-action">
                                    <a class="action-plus facebook" href="#">
                                        <i class="ion-social-facebook"></i>
                                    </a>
                                    <a class="action-heart twitter" title="Wishlist" href="#">
                                        <i class="ion-social-twitter"></i>
                                    </a>
                                    <a class="action-cart instagram" href="#">
                                        <i class="ion-social-instagram"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="team-content text-center">
                                <h4>Ms.Sophia</h4>
                                <span>Designer </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<!-- Team Area End -->
		<!-- Project Area Start -->
        
		<!-- Project Area End -->
       
        <!-- Footer style Start -->
          <?php require_once 'footer.php';  ?>

    </body>

</html>
