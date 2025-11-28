<?php
include 'includes/header.php';
$page_title = "Quản lý Phim";
if(isset($_GET['delser'])) {
    $msg = $series->deleteSeries($_GET['delser']);
} else if(isset($_GET['delmov'])) {
    $msg = $movie->deleteMovie($_GET['delmov']);
}

$type = isset($_GET['type']) ? ($_GET['type']) : null;
$search = isset($_GET['search']) ? ($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$films = [];
$all_movies = $movie->getAllMovies();
$all_series = $series->getAllSeries();

if(!empty($search)) {
    $mov = $movie->searchMovies($search);
    $ser = $series->searchSeries($search);
    if($type == 'movie') {
        if($mov) {
            foreach ($mov as $m) $films[] = $m;
        }
    } elseif($type == 'series') {
        if($ser) {
            foreach ($ser as $s) $films[] = $s;
        }
    } else {
        if($mov) {
            foreach ($mov as $m) $films[] = $m;
        }
        if($ser) {
            foreach ($ser as $s) $films[] = $s;
        }
    }
} else {
    if($type == 'movie') {
        foreach ($all_movies as $m) $films[] = $m;
    } elseif($type == 'series') {
        foreach ($all_series as $s) $films[] = $s;
    } else {
        foreach ($all_movies as $m) $films[] = $m;
        foreach ($all_series as $s) $films[] = $s;
    }
}
$items_per_page = 4;
$total_pages = ceil(count($films) / $items_per_page);
$offset = ($page - 1) * $items_per_page;
$movies = array_slice($films, $offset, $items_per_page);
?>

<style>
    .mt-4, .my-4 {margin-top: 1rem !important;}
    .table-responsive {min-height: 428.8px;}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?php echo $page_title; ?></h1>
    <div>
        <a href="add-movie.php?type=movie" class="btn btn-success mr-2">
            <i class="fas fa-film mr-1"></i> Thêm Movie
        </a>
        <a href="add-movie.php?type=series" class="btn btn-info">
            <i class="fas fa-tv mr-1"></i> Thêm Phim Bộ
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <form action="" method="GET" class="form-inline">
                    <?php if ($type): ?>
                        <input type="hidden" name="type" value="<?php echo $type; ?>">
                    <?php endif; ?>
                    <div class="input-group w-100">
                        <input type="text" class="form-control" name="search" value="<?php echo $search; ?>" placeholder="Search movies...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <div class="btn-group float-right" role="group">
                    <a href="movies.php" class="btn <?php echo !$type ? 'btn-primary' : 'btn-outline-primary'; ?>">Tất cả</a>
                    <a href="movies.php?type=movie" class="btn <?php echo $type == 'movie' ? 'btn-primary' : 'btn-outline-primary'; ?>">Phim</a>
                    <a href="movies.php?type=series" class="btn <?php echo $type == 'series' ? 'btn-primary' : 'btn-outline-primary'; ?>">Bộ phim</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isset($msg)) echo $msg;?>

<?php if (empty($movies)): ?>
<div class="alert alert-info">
    <i class="fas fa-info-circle mr-1"></i> Không tìm thấy phim hoặc bộ phim.
</div>
<?php else: ?>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th width="80">Thứ tự</th>
                        <th width="100">Ảnh bìa</th>
                        <th width="400">Tên</th>
                        <th width="100">Loại</th>
                        <th width="100">Năm</th>
                        <th width="150">Ngày thêm</th>
                        <th width="200">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; foreach ($movies as $film): $i++ ?>
                        <tr>
                            <td class="align-middle"><?php echo $i; ?></td>
                            <td class="align-middle">
                                <?php if ($film['photo']): ?>
                                    <img src="<?php echo $film['photo']; ?>" alt="<?php echo $film['title']; ?>" style="width: 50px; height: 70px;">
                                <?php else: ?>
                                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="width: 50px; height: 70px;">
                                        <i class="fas fa-<?php echo $film['type'] == 'movie' ? 'film' : 'tv'; ?> text-white"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle">
                                <strong><?php echo $film['title']; ?></strong>
                                <?php if ($film['isCheck'] == 1): ?>
                                    <?php 
                                    // Get episode count
                                    $episode_count = $episode->getNumberOfEpisodes($film['id'])->fetch_assoc()['total'];
                                    ?>
                                    <div><small><?php echo $episode_count; ?> tập phim</small></div>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle"><?php echo $film['isCheck'] ? 'Bộ phim' : 'Phim'; ?></td>
                            <td class="align-middle"><?php echo $film['releaseYear']; ?></td>
                            <td class="align-middle"><?php echo date('Y-m-d', strtotime($film['date_created'])); ?></td>
                            <td class="align-middle">
                                <a href="edit-movie.php?id=<?php echo $film['id']; ?>&type=<?php echo $film['isCheck'] == 1 ? 'series' : 'movie'; ?>" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <?php if ($film['isCheck'] == 1): ?>
                                    <a href="manage-series.php?id=<?php echo $film['id']; ?>" class="btn btn-sm btn-info" title="Quản lý bộ phim">
                                        <i class="fas fa-list-ol"></i>
                                    </a>
                                <?php endif; ?>

                                <div class="custom-control custom-switch d-inline-block ml-2">
                                    <input type="checkbox" class="custom-control-input popular-toggle" 
                                           id="popularSwitch<?php echo $film['id']; ?>" 
                                           data-id="<?php echo $film['id']; ?>"
                                           data-type="<?php echo $film['isCheck'] == 1 ? 'series' : 'movie'; ?>"
                                           <?php echo $film['popular'] == 1 ? 'checked' : ''; ?> >
                                    <label class="custom-control-label" for="popularSwitch<?php echo $film['id']; ?>" title="Độ phổ biến" style="padding-right: 0.5rem;">
                                        <i class="fas <?php echo $film['popular'] == 1 ? 'fa-fire text-danger' : 'fa-fire text-muted'; ?>"></i>
                                    </label>
                                </div>

                                <a href="?<?php echo ($film['isCheck'] == '1') ? 'delser=' . $film['id'] : 'delmov=' . $film['id'] ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this movie/series?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($total_pages > 1): ?>
<nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">
        <?php
        $query_params = [];
        if ($type) $query_params['type'] = $type;
        if ($search) $query_params['search'] = $search;
        
        $query_string = http_build_query($query_params);
        $query_prefix = empty($query_string) ? '?' : '?' . $query_string . '&';

        $max_visible = 5; // số trang tối đa hiển thị 1 block
        $current_block = ceil($page / $max_visible);
        $start = ($current_block - 1) * $max_visible + 1;
        $end = min($start + $max_visible - 1, $total_pages);

        // Previous page
        $prev_page = max(1, $page - 1);
        echo '<li class="page-item '.($page == 1 ? 'disabled' : '').'">
            <a class="page-link" href="'.$query_prefix.'page='.$prev_page.'" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>';

        // Hiển thị các số trang trong block hiện tại
        for ($i = $start; $i <= $end; $i++) {
            echo '<li class="page-item '.($i == $page ? 'active' : '').'">
                <a class="page-link" href="'.$query_prefix.'page='.$i.'">'.$i.'</a>
            </li>';
        }

        // Next page
        $next_page = min($total_pages, $page + 1);
        echo '<li class="page-item '.($page == $total_pages ? 'disabled' : '').'">
            <a class="page-link" href="'.$query_prefix.'page='.$next_page.'" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>';
        ?>
    </ul>
</nav>
<?php endif; ?>
<?php endif; ?>

<!-- Add this before the closing </body> tag -->
<script>
$(document).ready(function() {
    $('.popular-toggle').change(function() {
        const id = $(this).data('id');
        const type = $(this).data('type');
        const isPopular = $(this).prop('checked') ? 1 : 0;
        const icon = $(this).next('label').find('i');
        
        $.ajax({
            url: 'ajax/toggle-popular.php',
            type: 'POST',
            data: {
                id: id,
                type: type,
                popular: isPopular
            },
            success: function(response) {
                if(response.success) {
                    // Update icon color
                    if(isPopular) {
                        icon.removeClass('text-muted').addClass('text-danger');
                    } else {
                        icon.removeClass('text-danger').addClass('text-muted');
                    }
                    
                    // Show success message
                    toastr.success('Cập nhật độ phổ biến thành công!');
                } else {
                    // Revert toggle if failed
                    $(this).prop('checked', !isPopular);
                    toastr.error('Có lỗi xảy ra khi cập nhật!');
                }
            },
            error: function() {
                // Revert toggle if error
                $(this).prop('checked', !isPopular);
                toastr.error('Có lỗi xảy ra khi cập nhật!');
            }
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>