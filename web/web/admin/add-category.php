<?php
$page_title = "Thêm Thể Loại";
include 'includes/header.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $msg = $genre->add($name);
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?php echo $page_title; ?></h1>
    <a href="categories.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Quay lại
    </a>
</div>

<?php if ($msg) echo $msg; ?>

<div class="card">
    <div class="card-body">
        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Tên thể loại <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Lưu thể loại
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>