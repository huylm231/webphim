<?php
$page_title = "Sửa Thể Loại";
include 'includes/header.php';
$msg = '';
if(isset($_GET['id'])) {
    $id = $_GET['id'];
}
$category = $genre->getGenreById($id)->fetch_assoc();
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $msg = $genre->edit($name, $id);
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
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $category['title']; ?>" required>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Cập nhật thể loại
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>