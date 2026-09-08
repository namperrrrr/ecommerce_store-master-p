<?php
include '../database/connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Xóa chi tiết đơn hàng trước (do có khóa ngoại)
    $sql_del_details = "DELETE FROM order_details WHERE order_id = $id";
    mysqli_query($conn, $sql_del_details);

    // Sau đó xóa đơn hàng
    $sql_del_order = "DELETE FROM orders WHERE id = $id";
    mysqli_query($conn, $sql_del_order);

    // Quay lại danh sách
    header("Location: ./listorders.php");
    exit();
} else {
    echo "Thiếu ID đơn hàng!";
}
?>
