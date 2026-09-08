<?php
$is_homePage = false;
include '../database/connect.php';
include './includes/header.php';

/* =========================================================
   1) THÊM SẢN PHẨM VÀO GIỎ HÀNG
========================================================= */
if (isset($_POST['addtocartbtn'])) {
    $id  = (int)$_POST['pid'];
    $qty = (int)$_POST['qty'];

    $cart = [];
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    }

    $isFound = false;
    for ($i = 0; $i < count($cart); $i++) {
        if ($cart[$i]['id'] == $id) {
            $cart[$i]['qty'] += $qty;
            $isFound = true;
            break;
        }
    }

    if (!$isFound) {
        $sql_str = "SELECT * FROM products WHERE id = $id";
        $result  = mysqli_query($conn, $sql_str);
        $product = mysqli_fetch_assoc($result);

        if ($product) {
            $product['qty'] = $qty;
            $cart[] = $product;
        }
    }

    $_SESSION['cart'] = $cart;
}

/* =========================================================
   2) GỬI ĐÁNH GIÁ (THEO BẢNG reviews)
   columns: id, user_id, product_id, comment, rating,
            created_at, updated_at, admin_reply, admin_reply_at
========================================================= */
if (isset($_POST['submit_review'])) {

    // yêu cầu đăng nhập
    if (!isset($_SESSION['user'])) {
        echo "<script>alert('Bạn cần đăng nhập để gửi đánh giá!');</script>";
    } else {

        $user_id    = (int)$_SESSION['user']['id'];
        $product_id = (int)$_GET['id'];
        $rating     = (int)$_POST['rating'];
        $comment    = mysqli_real_escape_string($conn, $_POST['comment']);

        if ($rating < 1) $rating = 1;
        if ($rating > 5) $rating = 5;

        $sql_rv = "INSERT INTO reviews(user_id, product_id, comment, rating, created_at, updated_at)
                   VALUES ($user_id, $product_id, '$comment', $rating, NOW(), NOW())";

        mysqli_query($conn, $sql_rv);

        echo "<script>alert('Cảm ơn bạn đã phản hồi!');</script>";
    }
}

/* =========================================================
   3) LẤY THÔNG TIN SẢN PHẨM
========================================================= */
$idsp = (int)$_GET['id'];
$sql_str = "SELECT * FROM products WHERE id=$idsp";
$result = mysqli_query($conn, $sql_str);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "<h3>Sản phẩm không tồn tại.</h3>";
    exit;
}

$anh_arr = explode(';', $row['images']);
?>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2><?= $row['name'] ?></h2>
                    <div class="breadcrumb__option">
                        <a href="./index.html">Trang chủ</a>
                        <a href="./index.html">Sản phẩm</a>
                        <span><?= $row['name'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Product Details Section Begin -->
<section class="product-details spad">
    <div class="container">
        <div class="row">

            <div class="col-lg-6 col-md-6">
                <div class="product__details__pic">
                    <div class="product__details__pic__item">
                        <img class="product__details__pic__item--large"
                             src="<?= "../admin/" . $anh_arr[0] ?>"
                             alt="<?= $row['name'] ?>">
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="product__details__text">
                    <h3><?= $row['name'] ?></h3>

                    <div class="product__details__rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star-half-o"></i>
                        <span>(reviews)</span>
                    </div>

                    <div class="product__details__price">
                        <div class="prices mt-3">
                            <span style="text-decoration: line-through"
                                  class="text-muted small d-block">
                                <?= number_format($row['price'], 0, '', '.') . " VNĐ" ?>
                            </span>
                            <span class="fs-5 fw-bold text-danger">
                                <?= number_format($row['disscounted_price'], 0, '', '.') . " VNĐ" ?>
                            </span>
                        </div>
                    </div>

                    <p class="text-justify"><?= $row['sumary'] ?></p>

                    <!-- FORM THÊM GIỎ HÀNG -->
                    <form method="POST">
                        <div class="product__details__quantity">
                            <div class="quantity">
                                <div class="pro-qty">
                                    <!-- để number để nhận đúng số lượng -->
                                    <input type="number" name="qty" value="1" min="1">
                                </div>
                                <input type="hidden" value="<?= $idsp ?>" name="pid">
                            </div>
                        </div>

                        <button style="outline:none;border:none;"
                                class="primary-btn"
                                name="addtocartbtn">
                            Thêm vào giỏ hàng
                        </button>
                    </form>

                    <a href="#" class="heart-icon">
                        <span class="icon_heart_alt"></span>
                    </a>

                    <ul>
                        <li><b>Availability</b> <span>In Stock</span></li>
                        <li><b>Shipping</b> <span>01 day shipping. <samp>Free pickup today</samp></span></li>
                        <li><b>Weight</b> <span>0.5 kg</span></li>
                        <li><b>Share on</b>
                            <div class="share">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-pinterest"></i></a>
                            </div>
                        </li>
                    </ul>

                </div>
            </div>

            <div class="col-lg-12">
                <div class="product__details__tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active"
                               data-toggle="tab"
                               href="#tabs-1"
                               role="tab"
                               aria-selected="true">Mô tả</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                               data-toggle="tab"
                               href="#tabs-3"
                               role="tab"
                               aria-selected="false">Đánh giá</a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        <!-- TAB MÔ TẢ -->
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <div class="product__details__tab__desc text-justify">
                                <h6>Thông tin sản phẩm</h6>
                                <?= $row['description'] ?>
                            </div>
                        </div>

                        <!-- TAB ĐÁNH GIÁ -->
                        <div class="tab-pane" id="tabs-3" role="tabpanel">
                            <div class="product__details__tab__desc">

                                <h6>Đánh giá sản phẩm</h6>

                                <!-- FORM GỬI REVIEW -->
                                <form method="POST" class="mb-4">
                                    <select name="rating" class="form-control mb-2" required>
                                        <option value="">Chọn số sao</option>
                                        <option value="5">★★★★★ (5 sao)</option>
                                        <option value="4">★★★★☆ (4 sao)</option>
                                        <option value="3">★★★☆☆ (3 sao)</option>
                                        <option value="2">★★☆☆☆ (2 sao)</option>
                                        <option value="1">★☆☆☆☆ (1 sao)</option>
                                    </select>

                                    <textarea name="comment"
                                              required
                                              class="form-control mb-2"
                                              placeholder="Nhập nội dung đánh giá..."
                                              rows="4"></textarea>

                                    <button class="btn btn-primary" name="submit_review">
                                        Gửi đánh giá
                                    </button>
                                </form>

                                <hr>

                                <!-- DANH SÁCH REVIEW + PHẢN HỒI ADMIN -->
                                <h6 class="mt-3">Phản hồi từ khách hàng</h6>

                                <?php
                                $rv_sql = "SELECT r.*, u.name AS user_name
                                           FROM reviews r
                                           LEFT JOIN users u ON r.user_id = u.id
                                           WHERE r.product_id = $idsp
                                           ORDER BY r.created_at DESC";

                                $rv_rs = mysqli_query($conn, $rv_sql);

                                if (mysqli_num_rows($rv_rs) == 0) {
                                    echo "<p class='text-muted'>Chưa có đánh giá nào.</p>";
                                }

                                while ($rv = mysqli_fetch_assoc($rv_rs)) {
                                    $safe_name = htmlspecialchars($rv['user_name'] ?? "Người dùng");
                                    $safe_cmt  = nl2br(htmlspecialchars($rv['comment']));
                                    $rate = (int)$rv['rating'];
                                ?>
                                    <div class="border p-2 mb-3 rounded">
                                        <b><?= $safe_name ?></b>
                                        <span class="text-muted small">
                                            (<?= date('d/m/Y H:i', strtotime($rv['created_at'])) ?>)
                                        </span>

                                        <div>
                                            <?= str_repeat("★", $rate) . str_repeat("☆", 5 - $rate) ?>
                                        </div>

                                        <p class="mb-1"><?= $safe_cmt ?></p>

                                        <!-- ✅ HIỂN THỊ PHẢN HỒI ADMIN NẾU CÓ -->
                                        <?php if (!empty($rv['admin_reply'])) { ?>
                                            <div class="mt-2 p-2 bg-light border rounded">
                                                <b>Admin phản hồi:</b>
                                                <p class="mb-1">
                                                    <?= nl2br(htmlspecialchars($rv['admin_reply'])) ?>
                                                </p>
                                                <small class="text-muted">
                                                    <?= $rv['admin_reply_at'] ?>
                                                </small>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                        <!-- END TAB ĐÁNH GIÁ -->

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- Product Details Section End -->

<!-- Related Product Section Begin -->
<section class="related-product">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title related__product__title">
                    <h2>Các sản phẩm liên quan</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <?php
            $ctid = (int)$row['category_id'];
            $sql2 = "SELECT * FROM products WHERE category_id=$ctid AND id <> $idsp";
            $result2 = mysqli_query($conn, $sql2);

            while ($row2 = mysqli_fetch_assoc($result2)) {
                $arrs = explode(";", $row2["images"]);
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product__item">
                    <div class="product__item__pic set-bg"
                         data-setbg="<?= "../admin/" . $arrs[0] ?>">
                        <ul class="product__item__pic__hover">
                            <li><a href="#"><i class="fa fa-heart"></i></a></li>
                            <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                            <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                        </ul>
                    </div>

                    <div class="product__item__text">
                        <h6>
                            <a href="shop-detail.php?id=<?= $row2['id'] ?>">
                                <?= $row2['name'] ?>
                            </a>
                        </h6>

                        <div class="product__details__price">
                            <div class="prices">
                                <span class="fs-5 fw-bold text-danger">
                                    <?= number_format($row2['disscounted_price'], 0, '', '.') . " VNĐ" ?>
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<?php include './includes/footer.php'; ?>
