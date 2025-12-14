
 <header class="header-area gray-bg clearfix">
            <div class="header-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-6">
                            <div class="logo">
                                <a href="index.php">
                                    <img alt="" src="assets/img/logo/logo.png">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8 col-6">
                            <div class="header-bottom-right">
                                <div class="main-menu">
                                    <nav>
                                        <ul>
                                            <li><a href="index.php">Home</a></li>
                                            <li class="top-hover"><a href="pages.php?pid=1">About Us</a>
                                                <ul class="submenu">
                                                   <?php $stmtP = $pdo->prepare("SELECT id, title FROM pages ORDER BY title ASC");
                                                            $stmtP->execute();
                                                            $resPs = $stmtP->fetchAll(PDO::FETCH_ASSOC);
                                                            foreach ($resPs as $resP) {
                                                            ?>
                                                    <li><a href="pages.php?pid=<?php echo $resP['id'] ?>"><?php echo $resP['title'] ?></a></li>
                                                 <?php }  ?>
                                                </ul>
                                            </li>
                                            
                                            <li class="mega-menu-position top-hover"><a href="products.php">Products</a>
                                                <ul class="mega-menu">
                                                   <li>
                                                       <?php
                                                        try {
                                                            $stmt = $pdo->prepare("SELECT id, cat_name FROM equipmentcat ORDER BY sort_order ASC");
                                                            $stmt->execute();
                                                            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                            $count = 1;
                                                            foreach ($categories as $category) {
                                                                echo "<li>"; // start outer li for category
                                                                echo "<ul>"; // inner ul for category + subcategories

                                                                // Category title
                                                                echo "<li class=\"mega-menu-title\"><a href=\"category.php?cid=" . htmlspecialchars($category['id']) . "\">" . htmlspecialchars($category['cat_name']) . "  </a></li>";

                                                                // Fetch subcategories for this category
                                                                

                                                                echo "</ul>"; // close inner ul
                                                                echo "</li>"; // close outer li

                                                                       if ($count % 4 == 0) {
                                                                             echo '<br/><br/>';
                                                                        }

                                                                         $count++;
                                                            }

                                                        } catch (PDOException $e) {
                                                            echo "❌ Database error: " . $e->getMessage();
                                                        }
                                                        ?>


                                                    </li>
                                                           
                                                   
                                                </ul>
                                            </li>
                                           
                                             <li><a href="our-services.php">Our Services</a></li>
                                            <li><a href="contact.php">contact Us</a></li>
                                        </ul>
                                    </nav>
                                </div>
							
                            </div>
                        </div>
                    </div>
                    <div class="mobile-menu-area">
                        <div class="mobile-menu">
                            <nav id="mobile-menu-active">
                                <ul class="menu-overflow">
                           
                                            <li><a href="index.php">Home</a></li>
                                            <li class="top-hover"><a href="pages.php?pid=1">About Us</a>
                                                <ul class="submenu">
                                                   <?php $stmtPM = $pdo->prepare("SELECT id, title FROM pages ORDER BY title ASC");
                                                            $stmtPM->execute();
                                                            $resPsM = $stmtPM->fetchAll(PDO::FETCH_ASSOC);
                                                            foreach ($resPsM as $resPM) {
                                                            ?>
                                                    <li><a href="pages.php?pid=<?php echo $resPM['id'] ?>"><?php echo $resPM['title'] ?></a></li>
                                                 <?php }  ?>
                                                </ul>
                                            </li>
                                            
                                            <li class="mega-menu-position top-hover"><a href="products.php">Products</a>
                                                <ul class="mega-menu">
                                                   <li>
                                                       <?php
                                                        try {
                                                            $stmtM = $pdo->prepare("SELECT id, cat_name FROM equipmentcat ORDER BY sort_order ASC");
                                                            $stmtM->execute();
                                                            $categoriesM = $stmtM->fetchAll(PDO::FETCH_ASSOC);
                                                        
                                                            foreach ($categoriesM as $categoryM) {
                                                                echo '<li><a href="category.php?cid=' . htmlspecialchars($categoryM['id']) . '">' 
                                                                     . htmlspecialchars($categoryM['cat_name']) . '</a></li>';
                                                            }
                                                        } catch (PDOException $e) {
                                                            echo "❌ Database error: " . $e->getMessage();
                                                        }
                                                        ?>


                                                    </li>
                                                           
                                                   
                                                </ul>
                                            </li>
                                           
                                             <li><a href="our-services.php">Our Services</a></li>
                                            <li><a href="contact.php">contact Us</a></li>

                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </header>