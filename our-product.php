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
$stmt = $pdo->query("SELECT * FROM product ORDER BY sort_order ASC");

while ($row = $stmt->fetch()) {
    $cid = $row['id'];
    $name = $row['cat_name'];
    $image = $row['image']; // path to image
    ?>
    <div class="col-lg-4 col-md-4">
        <div class="blog-single mb-30">
            <div class="blog-thumb">
                <a href="category.php?cid=<?php echo $cid; ?>">
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