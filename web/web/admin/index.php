<?php
include_once 'includes/header.php';
$page_title = "Trang chủ Admin";
$movie_count = $movie->getCountMovies()->fetch_assoc()['Total'];
$series_count = $series->getCountSeries()->fetch_assoc()['Total'];
$category_count = $genre->getCountGenres()->fetch_assoc()['Total'];
$admin_count = $admin->show()->num_rows;
$films = [];
$recent_movies = $movie->getRecentMovies()->fetch_all(MYSQLI_ASSOC);
$recent_series = $series->getRecentSeries()->fetch_all(MYSQLI_ASSOC);
foreach ($recent_movies as $movie) {
    $films[] = $movie;
}
foreach ($recent_series as $series) {
    $films[] = $series;
}
$recent_admin = $admin->recentAdmin()->fetch_all(MYSQLI_ASSOC);
?>
<style>
    .text-white .card-body {
        padding: 0.75rem;
    }
</style>
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-white">Movies</h5>
                        <h2 class="text-white"><?php echo $movie_count; ?></h2>
                    </div>
                    <i class="fas fa-film fa-3x"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="movies.php?type=movie">Xem chi tiết</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-white">Phim Bộ</h5>
                        <h2 class="text-white"><?php echo $series_count; ?></h2>
                    </div>
                    <i class="fas fa-tv fa-3x"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="movies.php?type=series">Xem chi tiết</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-danger text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-white">Thể loại</h5>
                        <h2 class="text-white"><?php echo $category_count; ?></h2>
                    </div>
                    <i class="fas fa-list fa-3x"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="categories.php">Xem chi tiết</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-white">Quản lý Admin</h5>
                        <h2 class="text-white"><?php echo $admin_count; ?></h2>
                    </div>
                    <i class="fas fa-users fa-3x"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="admin.php">Xem chi tiết</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-film mr-1"></i>
                Movie & Phim Bộ gần đây
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tên</th>
                                <th>Loại</th>
                                <th>Năm</th>
                                <th>Thêm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($films as $film): ?>
                                <tr>
                                    <td>
                                        <a href="edit-movie.php?id=<?php echo $film['id']; ?>&type=<?php echo $film['isCheck'] ? 'series' : 'movie'; ?>">
                                            <?php echo $film['title']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $film['isCheck'] ? 'Series' : 'Movie'; ?></td>
                                    <td><?php echo $film['releaseYear']; ?></td>
                                    <td><?php echo date('M j, Y', strtotime($film['date_created'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($films)): ?>
                                <tr>
                                    <td colspan="4" class="text-center">Không tìm thấy phim hoặc bộ phim</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    <a href="movies.php" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-users mr-1"></i>
                Tài khoản gần đây
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tên tài khoản</th>
                                <th>Email</th>
                                <th>Tham gia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_admin as $user): ?>
                                <tr>
                                    <td>
                                        <a href="edit-user.php?id=<?php echo $user['id']; ?>">
                                            <?php echo $user['name']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo date('M j, Y', strtotime($user['date_created'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($recent_admin)): ?>
                                <tr>
                                    <td colspan="4" class="text-center">Không tìm thấy tài khoản</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    <a href="users.php" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>