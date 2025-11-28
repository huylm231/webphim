<?php
$page_title = "Quản lý Admin";
include 'includes/header.php';
$search = isset($_GET['search']) ? ($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$profiles = [];
if(!empty($search)) {
    $admins = $admin->searchAdmins($search);
    if($admins) {
        foreach ($admins as $a) $profiles[] = $a;
    }
} else {
    $getAllAdmins = $admin->show();
    if(!empty($getAllAdmins)) {
        foreach ($getAllAdmins as $a) $profiles[] = $a;
    }
}

$items_per_page = 7;
$total_pages = ceil(count($profiles) / $items_per_page);
$offset = ($page - 1) * $items_per_page;
$users = array_slice($profiles, $offset, $items_per_page);

?>

<style>
    .mt-4, .my-4 {margin-top: 0.5rem !important;}
    .table-responsive {min-height: 437.4px;}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?php echo $page_title; ?></h1>
    <a href="add-admin.php" class="btn btn-success">
        <i class="fas fa-user-plus mr-1"></i> Thêm quản trị viên
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <form action="" method="GET" class="form-inline">
                    <div class="input-group w-100">
                        <input type="text" class="form-control" name="search" value="<?php echo $search; ?>" placeholder="Tìm kiếm tài khoản...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (isset($msg)) echo $msg; ?>

<?php if (empty($users)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle mr-1"></i> Không tìm thấy tài khoản.
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th width="80">Thứ tự</th>
                            <th>Tên tài khoản</th>
                            <th>Email</th>
                            <th width="150">Ngày đăng ký</th>
                            <th width="150">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; foreach ($users as $row): $i++; ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><strong><?php echo $row['name']; ?></strong></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo date('Y-m-d', strtotime($row['date_created'])); ?></td>
                                <td>
                                    <a href="profile.php?admin_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-user"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php
            $query_params = [];
            if ($search) $query_params['search'] = $search;
            
            $query_string = http_build_query($query_params);
            $query_prefix = empty($query_string) ? '?' : '?' . $query_string . '&';
            
            $max_visible = 5; // Số lượng số trang tối đa muốn hiển thị trong 1 block
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

<?php include 'includes/footer.php'; ?>