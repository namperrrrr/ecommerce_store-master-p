<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" >

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fab fa-shopware"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Ecommerce Store</div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0"  />

  <!-- Nav Item - Dashboard -->
  <li class="nav-item active">
    <a class="nav-link" href="index.php">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider" />

  <!-- Heading -->
  <div class="sidebar-heading">Chức năng chính:</div>

  <!-- Brand -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
      aria-controls="collapseOne">
      <i class="fas fa-calendar-day"></i>
      <span>Thương hiệu - Brand</span>
    </a>
    <div id="collapseOne" class="collapse" aria-labelledby="collapseOne" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Các chức năng:</h6>
        <a class="collapse-item" href="./listbrands.php">Thương hiệu</a>
        <a class="collapse-item" href="./themthuonghieu.php">Thêm thương hiệu</a>
      </div>
    </div>
  </li>

  <!-- Danh mục sản phẩm -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
      aria-controls="collapseTwo">
      <i class="fas fa-calendar-day"></i>
      <span>Danh mục sản phẩm</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Các chức năng:</h6>
        <a class="collapse-item" href="./listcategories.php">Danh mục sản phẩm</a>
        <a class="collapse-item" href="./themdanhmucsp.php">Thêm danh mục</a>
      </div>
    </div>
  </li>

  <!-- Sản phẩm -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true"
      aria-controls="collapseThree">
      <i class="fab fa-product-hunt"></i>
      <span>Sản phẩm</span>
    </a>
    <div id="collapseThree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Các chức năng:</h6>
        <a class="collapse-item" href="./listproducts.php">Sản phẩm</a>
        <a class="collapse-item" href="./themsanpham.php">Thêm sản phẩm</a>
      </div>
    </div>
  </li>

  <!-- Danh mục tin tức -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDMTT" aria-expanded="true"
      aria-controls="collapseDMTT">
      <i class="fas fa-calendar-day"></i>
      <span>Danh mục tin tức</span>
    </a>
    <div id="collapseDMTT" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Các chức năng:</h6>
        <a class="collapse-item" href="./listnewscategories.php">Danh mục tin tức</a>
        <a class="collapse-item" href="./themdanhmuctintuc.php">Thêm danh mục tin tức</a>
      </div>
    </div>
  </li>

  <!-- Tin tức -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTT" aria-expanded="true"
      aria-controls="collapseTT">
      <i class="fab fa-product-hunt"></i>
      <span>Tin tức</span>
    </a>
    <div id="collapseTT" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Các chức năng:</h6>
        <a class="collapse-item" href="./listnews.php">Tin tức</a>
        <a class="collapse-item" href="./themtintuc.php">Thêm tin tức</a>
      </div>
    </div>
  </li>

  <!-- Đơn hàng -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true"
      aria-controls="collapseFour">
      <i class="fas fa-wallet"></i>
      <span>Đơn hàng</span>
    </a>
    <div id="collapseFour" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Các chức năng:</h6>
        <a class="collapse-item" href="./listorders.php">Danh sách đơn hàng</a>
      </div>
    </div>
  </li>

  <!-- ⭐ THÊM MỤC REVIEWS NGAY SAU ĐƠN HÀNG ⭐ -->
  <li class="nav-item">
    <a class="nav-link" href="./listreviews.php">
      <i class="fas fa-comments"></i>
      <span>Đánh giá sản phẩm</span>
    </a>
  </li>

  <!-- Người dùng -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser" aria-expanded="true"
      aria-controls="collapseUser">
      <i class="fas fa-users"></i>
      <span>Người dùng</span>
    </a>
    <div id="collapseUser" class="collapse" aria-labelledby="headingUser" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Các chức năng:</h6>
        <a class="collapse-item" href="./listusers.php">Danh sách người dùng</a>
      </div>
    </div>
  </li>

  <!-- Phân quyền -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRoles" aria-expanded="true"
      aria-controls="collapseRoles">
      <i class="fas fa-user-shield"></i>
      <span>Phân quyền</span>
    </a>
    <div id="collapseRoles" class="collapse" aria-labelledby="headingRoles" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Các chức năng:</h6>
        <a class="collapse-item" href="./listroles.php">Danh sách tài khoản</a>
        <a class="collapse-item" href="./themvaitro.php">Thêm tài khoản</a>
      </div>
    </div>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block" />

  <!-- Sidebar Toggler -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
<!-- End of Sidebar -->
