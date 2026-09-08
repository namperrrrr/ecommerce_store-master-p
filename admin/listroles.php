<?php
include './includes/header.php';

if ($_SESSION['user']['type'] != 'Admin') {
    echo "<h2>Quyền của bạn không đủ để truy cập nội dung này</h2>";
    die();
}
?>

<div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary fs-3">Danh sách tài khoản</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- Tìm kiếm  -->
                <form method="GET" class="d-none d-sm-inline-block form-inline ml-md-3 navbar-search w-50">
                    <div class="input-group">
                        <input type="text" name="timKiem" value="<?= $_GET['timKiem'] ?? '' ?>"
                            class="form-control bg-light border-0 small" 
                            placeholder="Tìm kiếm...">
                        <!-- Nút tìm kiếm -->
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <br>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <br>
                        <tr>
                            <th style="width:70px;">STT</th>
                            <th>ID</th>
                            <th>Tên người dùng</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include '../database/connect.php';
                       // Xử lý tìm kiếm
                        $timKiem = "";
                        if (isset($_GET['timKiem']) && $_GET['timKiem'] != "") {
                            $keyword = mysqli_real_escape_string($conn, $_GET['timKiem']);
                            $timKiem = " WHERE name LIKE '%$keyword%' 
                                        OR email LIKE '%$keyword%' ";
                        }

                        $sql_str = "SELECT * FROM admins $timKiem ORDER BY created_at";
                        $result = mysqli_query($conn, $sql_str);
                        $stt = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $stt++;
                        ?>
                            <tr>
                                <td><?=$stt?></td>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['type']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td>
                                    <a class="btn btn-warning" href="editroles.php?id=<?= $row['id'] ?>">Sửa</a>
                                    <a class="btn btn-danger" href="deleteroles.php?id=<?= $row['id'] ?>" onclick="return confirm('Bạn chắc chắn xóa mục này?');">Xóa</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include './includes/footer.php';
?>