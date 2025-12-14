<!doctype html>
<html class="no-js" lang="zxx">
    

 <?php 
 include 'head.php';
 
if (isset($_GET['cid']) && is_numeric($_GET['cid'])) {
    $pid = (int) $_GET['cid'];

    // Fetch the category/page
    $stmtP = $pdo->prepare("SELECT * FROM equipmentcat WHERE id = :id LIMIT 1");
    $stmtP->execute([':id' => $pid]);
    $cats = $stmtP->fetch(PDO::FETCH_ASSOC);
    $catname =  $cats['cat_name'];
    if ($cats) {
        // Fetch subcategories for this category
        $stmtSub = $pdo->prepare("SELECT *  FROM tbl_esubcat WHERE cat_id = :cat_id");
        $stmtSub->execute([':cat_id' => $pid]);
        $subcategories = $stmtSub->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo '❌ Category not found.';
        exit;
    }
} else {
    echo '❌ Invalid category ID.';
    exit;
}


  ?>
    <body>
      <?php include 'header.php' ?>
		<!-- header end -->
        <!-- Breadcrumb Area Start -->
         <div class="breadcrumb-area ptb-150" style="background-image: url('uploads/<?php echo $cats['image']; ?>?v=<?php echo date('ymdhis')?>');height:400px;"> 
            
            
        </div>
		<!-- Breadcrumb Area End -->
		<!-- Shop Page Area Start -->
        <div class="shop-page-area ptb-100">
            <div class="container">
                <div class="row flex-row-reverse">
                    <div class="col-lg-12">
                       <div class="container">
                <div class="breadcrumb-content text-center">
					<h3 style="color: #000;margin-top: -47px;" ><?php echo $catname ?></h3>
                    
                </div>
            </div>
                        <div class="grid-list-product-wrapper">
                            <div class="product-list product-view pb-20">
                                <div class="row">
                                    
                                    <div class="product-width col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 mb-30">
                                        
                                           
                                                <p><?php echo $cats['detail'] ?></p>
                                                   <ul class="category-grid">
                                                        <?php /* foreach ($subcategories as $subcat): ?>
                                                        <li>
                                                            <a href="products.php?pid=<?= urlencode($subcat['id']) ?>">
                                                            <!--<div class="cat-image">
                                                                <img src="uploads/category/<?= htmlspecialchars($subcat['image'] ?? 'placeholder.png') ?>" alt="<?= htmlspecialchars($category['cat_name']) ?>">
                                                            </div> -->
                                                            <span class="cat-title"><?= htmlspecialchars($subcat['subcat_name']) ?></span>
                                                            </a>
                                                        </li>
                                                        <?php endforeach; */ ?>
                                                        </ul>
                                       
                                    </div>
                                    
                                  
                                </div>
                            </div>
                            
                        </div>
                    </div>
                   
                </div>
            </div>
            
             <?php	require_once 'our-product.php'; ?>
        </div>
        
		<!-- Shop Page Area Start -->
        <!-- Footer style Start -->
         <?php require_once 'footer.php';  ?>
		<!-- Footer style End -->
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <!-- Thumbnail Large Image start -->
                                <div class="tab-content">
                                    <div id="pro-1" class="tab-pane fade show active">
                                        <img src="assets/img/product-details/product-detalis-l1.jpg" alt="">
                                    </div>
                                    <div id="pro-2" class="tab-pane fade">
                                        <img src="assets/img/product-details/product-detalis-l2.jpg" alt="">
                                    </div>
                                    <div id="pro-3" class="tab-pane fade">
                                        <img src="assets/img/product-details/product-detalis-l3.jpg" alt="">
                                    </div>
                                    <div id="pro-4" class="tab-pane fade">
                                        <img src="assets/img/product-details/product-detalis-l4.jpg" alt="">
                                    </div>
                                </div>
                                <!-- Thumbnail Large Image End -->
                                <!-- Thumbnail Image End -->
                                <div class="product-thumbnail">
                                    <div class="thumb-menu owl-carousel nav nav-style" role="tablist">
                                        <a class="active" data-toggle="tab" href="#pro-1"><img src="assets/img/product-details/product-detalis-s1.jpg" alt=""></a>
                                        <a data-toggle="tab" href="#pro-2"><img src="assets/img/product-details/product-detalis-s2.jpg" alt=""></a>
                                        <a data-toggle="tab" href="#pro-3"><img src="assets/img/product-details/product-detalis-s3.jpg" alt=""></a>
                                        <a data-toggle="tab" href="#pro-4"><img src="assets/img/product-details/product-detalis-s4.jpg" alt=""></a>
                                    </div>
                                </div>
                                <!-- Thumbnail image end -->
                            </div>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <div class="modal-pro-content">
                                    <h3>Dutchman's Breeches </h3>
                                    <div class="product-price-wrapper">
                                        <span class="product-price-old">£162.00 </span>
                                        <span>£120.00</span>
                                    </div>
                                    <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet.</p>	
                                    <div class="quick-view-select">
                                        <div class="select-option-part">
                                            <label>Size*</label>
                                            <select class="select">
                                                <option value="">S</option>
                                                <option value="">M</option>
                                                <option value="">L</option>
                                            </select>
                                        </div>
                                        <div class="quickview-color-wrap">
                                            <label>Color*</label>
                                            <div class="quickview-color">
                                                <ul>
                                                    <li class="blue">b</li>
                                                    <li class="red">r</li>
                                                    <li class="pink">p</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-quantity">
                                        <div class="cart-plus-minus">
                                            <input class="cart-plus-minus-box" type="text" name="qtybutton" value="02">
                                        </div>
                                        <button>Add to cart</button>
                                    </div>
                                    <span><i class="fa fa-check"></i> In stock</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal end -->
        
		<!-- all js here -->
        <script src="assets/js/vendor/jquery-1.12.0.min.js"></script>
        <script src="assets/js/popper.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/imagesloaded.pkgd.min.js"></script>
        <script src="assets/js/isotope.pkgd.min.js"></script>
        <script src="assets/js/ajax-mail.js"></script>
        <script src="assets/js/owl.carousel.min.js"></script>
        <script src="assets/js/plugins.js"></script>
        <script src="assets/js/main.js"></script>
    </body>

</html>
