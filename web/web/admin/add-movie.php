<?php
include 'includes/header.php';
$page_title = "Thêm Movie/Phim Bộ";
if(isset($_GET['type'])){
    $type = $_GET['type'];
}
$page_title = "Thêm " . ($type == 'movie' ? 'Phim' : 'Phim Bộ');
$msg = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($type == 'movie' ) {
        $msg = $movie->add($_POST);
    } else if($type == 'series') {
        $msg = $series->add($_POST);
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?php echo $page_title; ?></h1>
    <a href="movies.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Quay lại
    </a>
</div>

<?php if ($msg) echo $msg?>

<div class="card">
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="title">Tên phim</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($title) ? $title : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="5"><?php echo isset($description) ? $description : ''; ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="release_year">Năm phát hành</label>
                            <input type="number" class="form-control" id="release_year" name="releaseYear" min="1900" max="<?php echo date('Y'); ?>" value="<?php echo isset($release_year) ? $release_year : date('Y'); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="duration">
                                <?php echo $type == 'movie' ? 'Thời lượng (phút)' : 'Thời lượng tập phim (phút)'; ?>
                            </label>
                            <input type="number" class="form-control" id="duration" name="duration" min="1" value="<?php echo isset($duration) ? $duration : ($type == 'movie' ? '90' : '45'); ?>">
                            <?php if ($type == 'series'): ?>
                                <small class="form-text text-muted">Đây là thời lượng mặc định cho tập phim</small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="quality">Chất lượng video</label>
                            <select class="form-control" id="quality" name="quality">
                                <option value="HD">HD</option>
                                <option value="Full HD">Full HD</option>
                                <option value="4K">4K</option>
                                <option value="2K">2K</option>
                                <option value="1080p">1080p</option>
                                <option value="720p">720p</option>
                                <option value="480p">480p</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="popular">Loại phổ biến</label>
                            <select class="form-control" id="popular" name="popular">
                                <option value="1">Phổ biến</option>
                                <option value="0">Không phổ biến</option>
                            </select>
                        </div>
                    </div>
                    
                    <?php if ($type == 'movie'): ?>
                    <div class="form-group">
                        <label for="video_url">Đường dẫn video</label>
                        <input type="url" class="form-control" id="video_url" name="video_url" value="<?php echo isset($video_url) ? $video_url : ''; ?>" required>
                        <small class="form-text text-muted">Sử dụng định dạng URL iframe</small>
                    </div>
                    <?php else: ?>
                    <input type="hidden" name="video_url" value="">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Thể loại</label>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            <?php 
                            $filtered_categories = $genre->getAllGenres()->fetch_all(MYSQLI_ASSOC);
                            foreach ($filtered_categories as $category):
                            ?>
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="category_<?php echo $category['id']; ?>" 
                                           name="categories[]" 
                                           value="<?php echo $category['id']; ?>"
                                           <?php echo isset($selected_categories) && in_array($category['id'], $selected_categories) ? 'checked' : ''; ?>>
                                    <label class="custom-control-label" for="category_<?php echo $category['id']; ?>">
                                        <?php echo $category['title']; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Loại nội dung</label>
                        <input type="text" class="form-control" value="<?php echo $type == 'movie' ? 'Phim' : 'Bộ phim'; ?>" readonly>
                        <input type="hidden" name="type" value="<?php echo $type; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="poster">Ảnh bìa</label>
                        <input type="url" class="form-control" id="poster" name="poster" value="<?php echo isset($poster) ? $poster : ''; ?>">
                        <div class="mt-2">
                            <img id="posterPreview" src="<?php echo isset($poster) ? $poster : ''; ?>" alt="Poster Preview" style="width: 50%; display: none;">
                        </div>
                    </div>
                    
                    <?php if ($type == 'movie'): ?>
                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Xem trước video</h6>
                            </div>
                            <div class="card-body p-0">
                                <div id="videoPreviewContainer" class="embed-responsive embed-responsive-16by9" style="display: none;">
                                    <iframe id="videoPreview" class="embed-responsive-item" src="/placeholder.svg" allowfullscreen></iframe>
                                </div>
                                <div id="noVideoMessage" class="text-center p-3">
                                    <i class="fas fa-film fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Nhập đường dẫn video để xem trước</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($type == 'series'): ?>
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle mr-1"></i> Sau khi thêm bộ phim, bạn sẽ được chuyển hướng đến trang quản lý bộ phim để thêm mùa và tập phim.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="text-right mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Lưu
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Preview poster image before upload
document.getElementById('poster').addEventListener('change', function(e) {
    const link = e.target.value;
    if (link) {
        const img = document.getElementById('posterPreview');
        img.src = link;
        img.style.display = 'block';
    }
});

<?php if ($type == 'movie'): ?>
// Preview video embed
document.getElementById('video_url').addEventListener('input', function() {
    const videoUrl = this.value.trim();
    const previewContainer = document.getElementById('videoPreviewContainer');
    const noVideoMessage = document.getElementById('noVideoMessage');
    const videoPreview = document.getElementById('videoPreview');
    
    if (videoUrl) {
        videoPreview.src = videoUrl;
        previewContainer.style.display = 'block';
        noVideoMessage.style.display = 'none';
    } else {
        previewContainer.style.display = 'none';
        noVideoMessage.style.display = 'block';
    }
});
<?php endif; ?>
</script>
<?php include 'includes/footer.php'; ?>