<?php
require('includes/header.php');

function anhdaidien($arrstr, $height)// $arrstr là chuỗi chứa nhiều link ảnh, ví dụ: "img1.jpg;img2.jpg;img3.jpg"
{
    //$arrstr la mang cac anh co dang anh1;anh2;anh3
    //tach chuoi nay thanh mang - tach voi ;
    // $arr = $arrstr.split(';');
    $arr = explode(';', $arrstr); // explode(';', $arrstr) sẽ tách chuỗi thành mảng theo dấu ;
    return "<img src='$arr[0]' height='$height' />";
}

?>

<div>
    <div class="card shadow mb-4">
        
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary fs-3 ">Danh sách sản phẩm</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form method="GET" class="d-none d-sm-inline-block form-inline ml-md-3 navbar-search w-50">
                    <div class="input-group">
                        <input type="text" name="timKiem" value="<?= $_GET['timKiem'] ?? '' ?>" 
                            class="form-control bg-light border-0 small "
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
                            <th>STT</th>
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Ảnh đại diện</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require('../database/connect.php');
                        
                        // Xử lý tìm kiếm
                        $timKiem = "";
                        if (isset($_GET["timKiem"])) {
                            $keyword = mysqli_real_escape_string($conn, $_GET["timKiem"]);
                            $timKiem = " AND products.name LIKE '%$keyword%' ";
                        }

                        $sql_str = "SELECT 
                                    products.id AS pid, /*id sản phẩm*/
                                    products.name AS pname,images,/*tên sp */
                                    categories.name AS cname,/*tên danh mục */
                                    brands.name AS bname,/* tên thương hiệu*/
                                    products.status AS pstatus /**trạng thái sản phẩm */
                                    FROM products, categories, brands 
                                    WHERE products.category_id=categories.id /*Kết nối bảng products với categories, Mỗi sản phẩm có category_id =>tương ứng với id của bảng categories */                                   
                                    AND products.brand_id = brands.id  /*Kết nối bảng products với brands,Mỗi sản phẩm có brand_id =>tương ứng với id của bảng brands */
                                    $timKiem
                                    ORDER BY products.name";//Sắp xếp danh sách theo tên sản phẩm từ A → Z.
                        $result = mysqli_query($conn, $sql_str);//Gửi câu truy vấn đến MySQL
                        $stt = 1;
                        while ($row = mysqli_fetch_assoc($result)) {//mysqli_fetch_assoc() lấy từng dòng từ database và trả về dạng mảng (key là tên cột)
                        ?>
                            <tr>
                                <!-- Các câu lệnh in dữ liệu ra HTML -->
                                 <!-- vertical-align: middle -->
                                <td><?= $stt++ ?></td>
                                <td><?= $row['pid'] ?></td>
                                <td ><?= $row['pname'] ?></td> 
                                <td><?= anhdaidien($row['images'], "120px") ?></td>
                                <td><?= $row['cname'] ?></td>
                                <td><?= $row['bname'] ?></td>
                                <td><?= $row['pstatus'] ?></td>
                                <td>
                                    <a class="btn btn-warning" href="editproducts.php?id=<?= $row['pid'] ?>">Sửa</a> 
                                    <!-- Gửi pid qua URL để chỉnh sửa -->
                                    <br>
                                    <br>
                                    <a class="btn btn-danger" href="deleteproducts.php?id=<?= $row['pid'] ?>" onclick="return confirm('Bạn chắc chắn xóa sản phẩm này?');">Xóa</a>
                                    <!-- Gửi id để xóa sản phẩm ,confirm() bật popup xác nhận -->
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
require('includes/footer.php');
?>