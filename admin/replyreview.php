<?php
include '../database/connect.php';
include './includes/header.php';

// Lấy ID review
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin review + sản phẩm + user
$sql = "SELECT r.*, p.name AS product_name, u.name AS user_name
        FROM reviews r
        JOIN products p ON r.product_id = p.id
        LEFT JOIN users u ON r.user_id = u.id
        WHERE r.id = $id";

$rv = mysqli_fetch_assoc(mysqli_query($conn, $sql));

if (!$rv) {
    echo "<h4>Không tìm thấy đánh giá!</h4>";
    exit;
}

// Lưu phản hồi
if (isset($_POST['save_reply'])) {
    $reply = mysqli_real_escape_string($conn, $_POST['admin_reply']);

    $sql_update = "
        UPDATE reviews 
        SET admin_reply = '$reply',
            admin_reply_at = NOW(),
            updated_at = NOW()
        WHERE id = $id
    ";

    mysqli_query($conn, $sql_update);

    echo "<script>alert('Đã lưu phản hồi!'); location='listreviews.php';</script>";
    exit;
}
?>

<div class="container mt-4">
    <h3>Phản hồi đánh giá</h3>

    <div class="mb-3">
        <b>Sản phẩm:</b> <?= htmlspecialchars($rv['product_name']) ?><br>
        <b>Khách hàng:</b> <?= htmlspecialchars($rv['user_name'] ?? "User #".$rv['user_id']) ?><br>
        <b>Số sao:</b> <?= $rv['rating'] ?>/5<br>
        <b>Đánh giá:</b>
        <p style="white-space: pre-line;"><?= htmlspecialchars($rv['comment']) ?></p>
    </div>

    <form method="POST">
        <label><b>Phản hồi của Admin:</b></label>
        <textarea name="admin_reply" class="form-control mb-2" rows="4" required><?= htmlspecialchars($rv['admin_reply'] ?? "") ?></textarea>

        <button class="btn btn-success" name="save_reply">Lưu phản hồi</button>
        <a href="listreviews.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

<?php include './includes/footer.php'; ?>
