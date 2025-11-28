<?php
$page_title = "Hồ sơ của tôi";
include 'includes/header.php';
$id = isset($_GET['admin_id']) ? $_GET['admin_id'] : (isset($_GET['user_id']) ? $_GET['user_id'] : Session::get('id'));
$type = isset($_GET['user_id']) ? 'user' : 'admin';

if($type == 'admin') {
    $data = $admin->getAdminByID($id)->fetch_assoc();
    if(isset($_POST['update_profile'])) {
        $msg = $admin->update($_POST);
    }

    if(isset($_POST['change_password'])) {
        $msg = $admin->changePassword($id, md5($_POST['current_password']), md5($_POST['new_password']));
    }
} else {
    $data = $user->getUserByID($id)->fetch_assoc();
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?php echo $page_title; ?></h1>
    <a href="index.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Quay lại bảng điều khiển
    </a>
</div>

<?php if(isset($msg)) echo $msg;?>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin hồ sơ</h5>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="username">Tên tài khoản <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $data['name']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $data['email']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Loại tài khoản</label>
                        <input type="text" class="form-control" value="<?php echo $type == 'admin' ? 'Quản trị viên' : 'Người dùng thường'; ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Ngày đăng ký</label>
                        <input type="text" class="form-control" value="<?php echo date('F j, Y', strtotime($data['date_created'])); ?>" readonly>
                    </div>
                    <?php if($type == 'admin'): ?>
                    <div class="text-right">
                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Cập nhật hồ sơ
                        </button>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <?php if($type == 'admin'): ?>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Đổi mật khẩu</h5>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="current_password">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">Mật khẩu mới <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                        <small class="form-text text-muted">Mật khẩu phải có ít nhất 6 ký tự</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <div class="text-right">
                        <button type="submit" name="change_password" class="btn btn-primary">
                            <i class="fas fa-key mr-1"></i> Đổi mật khẩu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>