<?php 
$filepath = realpath(dirname(__FILE__));
include_once($filepath . "/../lib/session.php");
include_once($filepath . "/../lib/database.php");
include_once($filepath . "/../helpers/format.php");
spl_autoload_register(function($className){
    $file = "classes/".$className.".php";
    if (file_exists($file)) {
        include_once $file;
    }
});
$db = new Database();
$fm = new Format();
$series = new Series();
$episode = new Episode();
$genre = new Genre();
$movie = new Movie();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="shortcut icon" href="images.png" type="image/x-icon" />
    <title>Phim Hoạt Hình</title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel='dns-prefetch' href='//s.w.org' />
    <link rel='stylesheet' id='bootstrap-css' href='assets/css/bootstrap.min.css?ver=5.7.2' media='all' />
    <link rel='stylesheet' id='style-css' href='assets/css/style.css?ver=5.7.2' media='all' />
    <link rel='stylesheet' id='wp-block-library-css' href='assets/css/style.min.css?ver=5.7.2' media='all' />
    <script type='text/javascript' src='assets/js/jquery.min.js?ver=5.7.2' id='halim-jquery-js'></script>
    <style type="text/css" id="wp-custom-css">
        .textwidget p a img {
            width: 100%;
        }
        #header .site-title {
            background: url(includes/images.png) no-repeat top left;
            background-size: contain;
            text-indent: -9999px;
        }
    </style>
</head>
<body class="home blog halimthemes halimmovies" data-masonry="">
    <header id="header">
        <div class="container">
            <div class="row" id="headwrap">
                <div class="col-md-3 col-sm-6 slogan">
                    <p class="site-title"><a class="logo" href="" title="phim hay ">Phim Hay</p>
                    </a>
                </div>
                <div class="col-md-5 col-sm-6 halim-search-form hidden-xs">
                    <div class="header-nav">
                        <div class="col-xs-12">
                            <form  action="category.php" method="GET">
                                <div class="form-group">
                                    <div class="input-group col-xs-12">
                                        <input id="search" type="text" name="s" class="form-control" placeholder="Tìm kiếm..." autocomplete="off" required>
                                        <i class="animate-spin hl-spin4 hidden"></i>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="navbar-container">
        <div class="container">
            <nav class="navbar halim-navbar main-navigation" role="navigation" data-dropdown-hover="1">
                <div class="collapse navbar-collapse" id="halim">
                    <div class="menu-menu_1-container">
                        <ul id="menu-menu_1" class="nav navbar-nav navbar-left">
                            <?php
                            $current_page = basename($_SERVER['PHP_SELF']);
                            $current_query = $_SERVER['QUERY_STRING'];
                            ?>
                            <li class="<?php echo ($current_page == 'index.php') ? 'current-menu-item active' : ''; ?>">
                                <a title="Trang Chủ" href="index.php">Trang Chủ</a>
                            </li>
                            <li class="<?php echo ($current_page == 'category.php' && $current_query == 'name=newfilm') ? 'current-menu-item active' : ''; ?>">
                                <a title="Phim Mới" href="category.php?name=newfilm">Phim Mới</a>
                            </li>
                            <li class="mega dropdown <?php echo ($current_page == 'category.php' && strpos($current_query, 'genre=') !== false) ? 'current-menu-item active' : ''; ?>">
                                <a title="Thể Loại" href="#" data-toggle="dropdown" class="dropdown-toggle" aria-haspopup="true">
                                    Thể Loại 
                                    <span class="caret"></span>
                                </a>
                                <ul role="menu" class="dropdown-menu">
                                    <?php
                                        $result = $genre->getAllGenres();
                                        if ($result) {
                                            while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <li class="<?php echo ($current_page == 'category.php' && $current_query == 'genre='.$row['id']) ? 'active' : ''; ?>">
                                        <a title="<?php echo $row['title'] ?>" href="category.php?genre=<?php echo $row['id'] ?>">
                                            <?php echo $row['title'] ?>
                                        </a>
                                    </li>
                                    <?php
                                            }
                                        }
                                    ?>
                                </ul>
                            </li>
                            <li class="<?php echo ($current_page == 'category.php' && $current_query == 'name=movie') ? 'current-menu-item active' : ''; ?>">
                                <a title="Phim Lẻ" href="category.php?name=movie">Movie</a>
                            </li>
                            <li class="<?php echo ($current_page == 'category.php' && $current_query == 'name=series') ? 'current-menu-item active' : ''; ?>">
                                <a title="Phim Bộ" href="category.php?name=series">Phim Bộ</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    </div>
    <div class="container">