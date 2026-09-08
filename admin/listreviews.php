<?php
$is_homePage = false;
include '../database/connect.php';
include './includes/header.php';

// ====== TÌM KIẾM ======
$keyword = $_GET['keyword'] ?? "";

// ====== LỌC PHẢN HỒI ======
// all | replied | unreplied
$reply_status = $_GET['reply_status'] ?? "all";

// ====== SQL ======
$sql = "SELECT r.*, p.name AS product_name, u.name AS user_name
        FROM reviews r
        JOIN products p ON r.product_id = p.id
        LEFT JOIN users u ON r.user_id = u.id
        WHERE 1";

// tìm kiếm theo keyword
if ($keyword !== "") {
    $k = mysqli_real_escape_string($conn, $keyword);
    $sql .= " AND (
                p.name LIKE '%$k%' 
                OR u.name LIKE '%$k%'
                OR r.comment LIKE '%$k%'
             )";
}

// lọc theo trạng thái phản hồi
if ($reply_status == "replied") {
    $sql .= " AND r.admin_reply IS NOT NULL AND r.admin_reply <> ''";
} elseif ($reply_status == "unreplied") {
    $sql .= " AND (r.admin_reply IS NULL OR r.admin_reply = '')";
}

$sql .= " ORDER BY r.created_at DESC";
$rs = mysqli_query($conn, $sql);

// link export giữ filter
$export_link = "export_reviews.php?keyword=" . urlencode($keyword) . "&reply_status=" . urlencode($reply_status);
?>

<div class="container mt-4">
    <h3>Danh sách đánh giá sản phẩm</h3>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- FORM TÌM KIẾM + LỌC -->
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <div class="input-group" style="max-width: 350px;">
                <input type="text" name="keyword" class="form-control"
                       placeholder="Tìm theo sản phẩm / khách / nội dung..."
                       value="<?= htmlspecialchars($keyword) ?>">
                <button class="btn btn-primary">Tìm</button>
            </div>

            <select name="reply_status" class="form-control" style="max-width: 200px;">
                <option value="all" <?= $reply_status=="all"?"selected":"" ?>>Tất cả</option>
                <option value="replied" <?= $reply_status=="replied"?"selected":"" ?>>Đã phản hồi</option>
                <option value="unreplied" <?= $reply_status=="unreplied"?"selected":"" ?>>Chưa phản hồi</option>
            </select>

            <button class="btn btn-secondary">Lọc</button>
        </form>

        <!-- XUẤT EXCEL -->
        <a href="<?= $export_link ?>" class="btn btn-success">
            Xuất Excel
        </a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>Khách hàng</th>
                <th>Số sao</th>
                <th>Nội dung</th>
                <th>Ngày tạo</th>
                <th>Phản hồi Admin</th>
                <th>Hành động</th>
            </tr>
        </thead>

        <tbody>
        <?php if (mysqli_num_rows($rs) == 0) { ?>
            <tr>
                <td colspan="8" class="text-center text-muted">Không có đánh giá phù hợp.</td>
            </tr>
        <?php } ?>

        <?php while ($r = mysqli_fetch_assoc($rs)) {
            $rate = (int)$r['rating'];
        ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= htmlspecialchars($r['product_name']) ?></td>
                <td><?= htmlspecialchars($r['user_name'] ?? ("User #".$r['user_id'])) ?></td>
                <td><?= str_repeat("★", $rate) . str_repeat("☆", 5 - $rate) ?></td>

                <td style="max-width:300px">
                    <?= nl2br(htmlspecialchars($r['comment'])) ?>
                </td>

                <td><?= $r['created_at'] ?></td>

                <td style="max-width:300px">
                    <?php if (!empty($r['admin_reply'])) { ?>
                        <div class="p-2 bg-light border rounded">
                            <?= nl2br(htmlspecialchars($r['admin_reply'])) ?>
                            <div class="text-muted small mt-1">
                                <?= $r['admin_reply_at'] ?>
                            </div>
                        </div>
                    <?php } else { ?>
                        <span class="text-muted">Chưa phản hồi</span>
                    <?php } ?>
                </td>

                <td>
                    <a href="replyreview.php?id=<?= $r['id'] ?>" class="btn btn-primary btn-sm">
                        Phản hồi
                    </a>
                    <!-- không có xóa -->
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php include './includes/footer.php'; ?>
