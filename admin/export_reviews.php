<?php
include '../database/connect.php';

// Nếu có output buffering thì tắt để tránh lỗi file
if (ob_get_length()) ob_end_clean();

// Header bắt trình duyệt tải file Excel .xls
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=danh_sach_danh_gia.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Lấy dữ liệu review
$sql = "SELECT r.*, p.name AS product_name, u.name AS user_name
        FROM reviews r
        JOIN products p ON r.product_id = p.id
        LEFT JOIN users u ON r.user_id = u.id
        ORDER BY r.created_at DESC";
$rs = mysqli_query($conn, $sql);

// Xuất dạng bảng HTML (Excel đọc như file thật)
echo "<table border='1'>";
echo "<tr style='font-weight:bold; background:#f2f2f2;'>
        <th>ID</th>
        <th>Sản phẩm</th>
        <th>Khách hàng</th>
        <th>Số sao</th>
        <th>Nội dung đánh giá</th>
        <th>Ngày tạo</th>
        <th>Phản hồi Admin</th>
        <th>Ngày phản hồi</th>
      </tr>";

while ($r = mysqli_fetch_assoc($rs)) {
    $userName = $r['user_name'] ?? ("User #".$r['user_id']);

    echo "<tr>";
    echo "<td>".$r['id']."</td>";
    echo "<td>".htmlspecialchars($r['product_name'])."</td>";
    echo "<td>".htmlspecialchars($userName)."</td>";
    echo "<td>".$r['rating']."</td>";
    echo "<td>".htmlspecialchars($r['comment'])."</td>";
    echo "<td>".$r['created_at']."</td>";
    echo "<td>".htmlspecialchars($r['admin_reply'])."</td>";
    echo "<td>".$r['admin_reply_at']."</td>";
    echo "</tr>";
}

echo "</table>";
exit;
