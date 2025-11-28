<?php
$page_title = "Thêm Admin";
include 'includes/header.php';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $msg = $admin->add($_POST);
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?php echo $page_title; ?></h1>
    <a href="admin.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Quay lại
    </a>
</div>

<?php if(isset($msg)) echo $msg; ?>

<div class="card">
    <div class="card-body">
        <form action="" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username">Tên tài khoản <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($username) ? $username : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
            </div>
            
            <div class="text-right mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus mr-1"></i> Thêm tài khoản
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>