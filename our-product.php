<div class="blog-area bg-image-1 pt-90 pb-70">
            <div class="container">
                <div class="product-top-bar section-border mb-55">
                    <div class="section-title-wrap text-center">
                        <h3 class="section-title">Our Products</h3>
                    </div>
                </div>
			</div>
			<div class="container">
				<div class="row">
	<?php			    
$stmt = $pdo->query("SELECT * FROM product_categories ORDER BY id ASC");

while ($row = $stmt->fetch()) {
    $cid = $row['id'];
    $name = $row['title'];
    $image = $row['image'];
    $intro = $row['intro']; 
    $slug=$row['slug'];
    ?>
    <div class="col-lg-4 col-md-4">
        <div class="blog-single mb-30">
            <div class="blog-thumb">
                <a href="products/<?php echo urlencode($slug); ?>">
                    <img src="uploads/<?php echo $image; ?>?v=<?php echo date('ymdhis') ?>" alt="<?php echo htmlspecialchars($name); ?>" />
                </a>
            </div>
            <div class="blog-content pt-25">
                <span class="blog-date"><?php echo htmlspecialchars($name); ?></span>
            </div>
        </div>
    </div>
    <?php
}
?>




				</div>
			</div>
		</div>