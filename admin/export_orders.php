<?php
// 1. Kết nối CSDL
include '../database/connect.php';

// 2. Tên tệp sẽ được tải về
$filename = "danh-sach-don-hang-" . date('Y-m-d') . ".xls";

// 3. Thiết lập Headers để trình duyệt hiểu là file Excel (.xls)
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache"); 
header("Expires: 0");

// 4. Định nghĩa mảng dịch trạng thái
$statusText = [
    'Processing' => 'Xử lý',
    'Confirmed'  => 'Đã xác nhận',
    'Shipping'   => 'Vận chuyển',
    'Delivered'  => 'Đã giao hàng',
    'Cancelled'  => 'Đã hủy'
];

// 5. Bắt đầu xuất nội dung HTML (Thêm <meta> để Excel đọc UTF-8)
echo '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>';
echo '<table border="1">';

// 6. Ghi dòng tiêu đề (Header)
echo '<thead>';
echo '<tr>';
echo '<th style="background: #ccc; font-weight: bold;">STT</th>';
echo '<th style="background: #ccc; font-weight: bold;">Mã đơn hàng</th>';
echo '<th style="background: #ccc; font-weight: bold;">Tên người mua</th>';
echo '<th style="background: #ccc; font-weight: bold;">Ngày đặt</th>';
// MỚI: Thêm tiêu đề
echo '<th style="background: #ccc; font-weight: bold;">Sản phẩm</th>';
echo '<th style="background: #ccc; font-weight: bold;">Thành tiền</th>';
//
echo '<th style="background: #ccc; font-weight: bold;">Trạng thái</th>';
echo '<th style="background: #ccc; font-weight: bold;">Địa chỉ</th>';
echo '<th style="background: #ccc; font-weight: bold;">Số điện thoại</th>';
echo '<th style="background: #ccc; font-weight: bold;">Email</th>';
echo '</tr>';
echo '</thead>';

// 7. Truy vấn và lặp dữ liệu (Câu lệnh SQL đã cập nhật)
$sql_str = "
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
    GROUP BY 
        orders.id
    ORDER BY 
        orders.created_at DESC
";
$result = mysqli_query($conn, $sql_str);
$stt = 0;

echo '<tbody>';
while ($row = mysqli_fetch_assoc($result)) {
    $stt++;
    $trang_thai = $statusText[$row['status']] ?? $row['status'];

    echo '<tr>';
    echo '<td>' . $stt . '</td>';
    echo '<td>' . $row['id'] . '</td>';
    echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
    echo '<td>' . $row['created_at'] . '</td>';
    // MỚI: Thêm dữ liệu
    echo '<td>' . ($row['product_names'] ?? '') . '</td>';
    echo '<td>' . ($row['grand_total'] ?? 0) . '</td>'; // Xuất ra dạng số
    //
    echo '<td>' . $trang_thai . '</td>';
    echo '<td>' . $row['address'] . '</td>';
    // Ép SĐT thành chuỗi để Excel không hiểu nhầm
    echo '<td style="mso-number-format:\'@\';">' . $row['phone'] . '</td>';
    echo '<td>' . $row['email'] . '</td>';
    echo '</tr>';
}
echo '</tbody>';

// 8. Đóng bảng và kết thúc file
echo '</table>';
echo '</body></html>';
exit();
?>