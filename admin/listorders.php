<?php
include './includes/header.php';
include '../database/connect.php';

// --- PHẦN MỚI: XỬ LÝ LỌC TRẠNG THÁI ---

// 1. Lấy trạng thái cần lọc từ URL
$status_filter = $_GET['status'] ?? ''; // Dùng ?? để tránh lỗi nếu 'status' không tồn tại

// 2. Chuẩn bị link cho nút "Xuất Excel"
$export_link = "export_orders.php";
if (!empty($status_filter)) {
    // Nếu có lọc, thêm trạng thái vào link xuất Excel
    $export_link .= "?status=" . urlencode($status_filter);
}

// 3. Xây dựng câu lệnh SQL
$sql_base = "
    SELECT 
        orders.*, 
        SUM(order_details.qty * order_details.price) AS grand_total,
        GROUP_CONCAT(products.name SEPARATOR ', ') AS product_names
    FROM 
        orders
    LEFT JOIN 
        order_details ON orders.id = order_details.order_id
    LEFT JOIN 
        products ON order_details.product_id = products.id
";

$where_sql = ""; // Mệnh đề WHERE, rỗng nếu không lọc
if (!empty($status_filter)) {
    // Bảo vệ chống SQL Injection (dù $status_filter là từ dropdown của mình)
    $safe_status = mysqli_real_escape_string($conn, $status_filter);
    
    // Chỉ lọc theo cột 'status'
    $where_sql = " WHERE orders.status = '$safe_status' ";
}

$group_by_sql = " GROUP BY orders.id ";
$order_by_sql = " ORDER BY orders.created_at DESC ";

// 4. Ghép các phần lại thành câu lệnh SQL cuối cùng
$sql_str = $sql_base . $where_sql . $group_by_sql . $order_by_sql;

// --- KẾT THÚC PHẦN MỚI ---

?>

<style>
    /* ... (Phần style CSS của bạn giữ nguyên) ... */
    .Processing { ... }
    .Shipping { ... }
    .Confirmed { ... }
    .Cancelled { ... }
    .Delivered { ... }
    .products-cell { ... }
</style>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col-md-4">
                <h6 class="m-0 font-weight-bold text-primary">Đơn hàng</h6>
            </div>
            
            <div class="col-md-5">
                <form method="GET" action="listorders.php" class="form-inline">
                    <div class="input-group">
                        <select name="status" class="form-control">
                            <option value="">-- Lọc theo trạng thái --</option>
                            <option value="Processing" <?= $status_filter == 'Processing' ? 'selected' : '' ?>>Xử lý</option>
                            <option value="Confirmed" <?= $status_filter == 'Confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                            <option value="Shipping" <?= $status_filter == 'Shipping' ? 'selected' : '' ?>>Vận chuyển</option>
                            <option value="Delivered" <?= $status_filter == 'Delivered' ? 'selected' : '' ?>>Đã giao hàng</option>
                            <option value="Cancelled" <?= $status_filter == 'Cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Lọc</button>
                            <a href="listorders.php" class="btn btn-secondary">Tất cả</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-3 text-right">
                <a href="<?= $export_link ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="dataTable" width="100%" cellspacing="0">
                <thead class="table">
                    <tr>
                        <th>STT</th>
                        <th>Mã đơn hàng</th>
                        <th>Tên người mua</th>
                        <th>Ngày đặt</th>
                        <th>Sản phẩm</th>
                        <th>Thành tiền</th>
                        <th>Trạng thái</th>
                        <th>Xem</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $result = mysqli_query($conn, $sql_str);
                    $stt = 0;
                    
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $stt++;
                    ?>
                        <tr>
                            <td><?= $stt ?></td>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['firstname'] . ' ' . $row['lastname'] ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td class="products-cell" title="<?= htmlspecialchars($row['product_names'] ?? 'Chưa có sản phẩm') ?>">
                                <?= htmlspecialchars($row['product_names'] ?? 'Chưa có sản phẩm') ?>
                            </td>
                            <td>
                                <?= number_format($row['grand_total'] ?? 0, 0, '', '.') . " VNĐ" ?>
                            </td>
                            <td>
                                <?php
                                $statusText = [
                                    'Processing' => 'Xử lý',
                                    'Confirmed'  => 'Đã xác nhận',
                                    'Shipping'   => 'Vận chuyển',
                                    'Delivered'  => 'Đã giao hàng',
                                    'Cancelled'  => 'Đã hủy'
                                ];
                                ?>
                                <span class='<?= $row['status'] ?>'><?= $statusText[$row['status']] ?? $row['status'] ?></span>
                            </td>
                            <td>
                                <a class="btn btn-warning btn-sm" href="vieworders.php?id=<?= $row['id'] ?>">Xem</a>
                                <a class="btn btn-danger btn-sm" href="xoa_order.php?id=<?= $row['id'] ?>" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng #<?= $row['id'] ?> không?');">Xóa</a>
                            </td>
                        </tr>
                    <?php
                        } // Kết thúc while
                    } else {
                    ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                Không tìm thấy đơn hàng nào
                                <?php if (!empty($status_filter)): ?>
                                    với trạng thái "<?= htmlspecialchars($statusText[$status_filter] ?? $status_filter) ?>"
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                    } // Kết thúc if
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include './includes/footer.php';
?>