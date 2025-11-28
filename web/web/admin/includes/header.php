<?php
$current_page = basename($_SERVER['PHP_SELF']);
$filepath = realpath(dirname(__FILE__));
include_once($filepath . "/../../lib/session.php");
include_once($filepath . "/../../lib/database.php");
include_once($filepath . "/../helpers/format.php");
Session::init();
if (!Session::get('admin')) {
    header("Location: login.php");
    exit();
}
if (isset($_GET["action"]) && $_GET["action"] == "logout") {
    Session::destroy();
    header("Location: login.php");
    exit();
}
spl_autoload_register(function($className){
    $file = "../classes/".$className.".php";
    if (file_exists($file)) {
        include_once $file;
    }
});
$db = new Database();
$fm = new Format();
$admin = new Admin();
$series = new Series();
$episode = new Episode();
$genre = new Genre();
$movie = new Movie();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang quản lý phim</title>
    <link rel="shortcut icon" href="https://static.vecteezy.com/system/resources/thumbnails/019/194/935/small/global-admin-icon-color-outline-vector.jpg" type="image/x-icon" />
    <!-- Font Awesome (biểu tượng icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- jQuery (thư viện JavaScript phổ biến) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css?ver=4.5.2">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-film mr-1"></i> Trang quản lý phim
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php" target="_blank">
                        <i class="fas fa-external-link-alt mr-1"></i> Xem trang
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-circle mr-1"></i> <?php echo Session::get("name"); ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="profile.php">
                            <i class="fas fa-user-cog mr-1"></i> Hồ sơ
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="?action=logout">
                            <i class="fas fa-sign-out-alt mr-1"></i> Đăng xuất
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 px-0 sidebar">
                <div class="list-group list-group-flush">
                    <a href="index.php" class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i> Bảng điều khiển
                    </a>
                    <a href="movies.php" class="nav-link <?php echo $current_page == 'movies.php' ? 'active' : ''; ?>">
                        <i class="fas fa-film"></i> Danh sách phim
                    </a>
                    <a href="add-movie.php?type=movie" class="nav-link <?php echo $current_page == 'add-movie.php' && isset($_GET['type']) && $_GET['type'] == 'movie' ? 'active' : ''; ?>">
                        <i class="fas fa-plus-circle"></i> Thêm Movie
                    </a>
                    <a href="add-movie.php?type=series" class="nav-link <?php echo $current_page == 'add-movie.php' && isset($_GET['type']) && $_GET['type'] == 'series' ? 'active' : ''; ?>">
                        <i class="fas fa-tv"></i> Thêm Phim Bộ
                    </a>
                    <a href="categories.php" class="nav-link <?php echo $current_page == 'categories.php' || $current_page == 'add-category.php' || $current_page == 'edit-category.php' ? 'active' : ''; ?>">
                        <i class="fas fa-tags"></i> Thể loại
                    </a>
                    <a href="admin.php" class="nav-link <?php echo $current_page == 'admin.php' || $current_page == 'add-admin.php' || $current_page == 'profile.php' ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i> Quản lý Admin
                    </a>
                </div>
            </div>
            <div class="col-md-10 content">