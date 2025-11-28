<?php
$page_title = "Sửa Movie/Phim Bộ";
include 'includes/header.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$type = isset($_GET['type']) ? strtolower(trim($_GET['type'])) : '';
$page_title = "Sửa " . ($type == 'movie' ? 'Movie' : 'Phim Bộ');
if($type == 'movie') {
    $films = $movie->getMovieById($id);
} else {
    $films = $series->getSeriesById($id);
}
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if($type == 'movie') {
        $msg = $movie->edit($_POST, $id);
    } else {
        $msg = $series->edit($_POST, $id);
    }
}

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?php echo $page_title; ?></h1>
    <div>
        <?php if ($type == 'series'): ?>
            <a href="manage-series.php?id=<?php echo $id; ?>" class="btn btn-info mr-2">
                <i class="fas fa-list-ol mr-1"></i> Quản lý tập phim
            </a>
        <?php endif; ?>
        <a href="movies.php<?php echo $type ? "?type=$type" : ''; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>
</div>

<?php if(isset($msg)) echo $msg; ?>

<div class="card">
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <?php $film = $films->fetch_assoc(); ?>
                    <div class="form-group">
                        <label for="title">Tên phim<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo $film['title']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="5"><?php echo $film['description']; ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="release_year">Năm phát hành</label>
                            <input type="number" class="form-control" id="release_year" name="releaseYear" min="1900" max="<?php echo date('Y'); ?>" value="<?php echo $film['releaseYear']; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="duration">
                                <?php echo $type == 'movie' ? 'Thời lượng (phút)' : 'Thời lượng tập phim (phút)'; ?>
                            </label>
                            <input type="number" class="form-control" id="duration" name="duration" min="1" value="<?php echo $film['runtime']; ?>">
                            <?php if ($type == 'series'): ?>
                                <small class="form-text text-muted">Đây là thời lượng mặc định cho tập phim</small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Thêm chất lượng video và loại phổ biến hay không phổ biến -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="quality">Chất lượng video</label>
                            <select class="form-control" id="quality" name="quality">
                                <option value="HD" <?php echo $film['quality'] == 'HD' ? 'selected' : ''; ?>>HD</option>
                                <option value="Full HD" <?php echo $film['quality'] == 'Full HD' ? 'selected' : ''; ?>>Full HD</option>
                                <option value="4K" <?php echo $film['quality'] == '4K' ? 'selected' : ''; ?>>4K</option>
                                <option value="2K" <?php echo $film['quality'] == '2K' ? 'selected' : ''; ?>>2K</option>
                                <option value="1080p" <?php echo $film['quality'] == '1080p' ? 'selected' : ''; ?>>1080p</option>
                                <option value="720p" <?php echo $film['quality'] == '720p' ? 'selected' : ''; ?>>720p</option>
                                <option value="480p" <?php echo $film['quality'] == '480p' ? 'selected' : ''; ?>>480p</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="popular">Loại phổ biến</label>
                            <select class="form-control" id="popular" name="popular">
                                <option value="1" <?php echo $film['popular'] == 1 ? 'selected' : ''; ?>>Phổ biến</option>
                                <option value="0" <?php echo $film['popular'] == 0 ? 'selected' : ''; ?>>Không phổ biến</option>
                            </select>
                        </div>
                    </div>

                    <!-- Always show Video URL field for debugging -->
                    <?php if($type == 'movie'): ?>
                    <div class="form-group">
                        <label for="video_url">Đường dẫn video <?php if ($type == 'movie'): ?><span class="text-danger">*</span><?php endif; ?></label>
                        <input type="url" class="form-control" id="video_url" name="video_url" value="<?php echo $film['linkVideo']; ?>" <?php echo $type == 'movie' ? 'required' : ''; ?>>
                        <small class="form-text text-muted">Sử dụng định dạng URL iframe</small>
                    </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Thể loại</label>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            <?php 
                            $filtered_categories = $genre->getAllGenres()->fetch_all(MYSQLI_ASSOC);
                            $link_genre = $genre->getLinkGenre($id, $type);
                            $selected_genres = [];
                            if ($link_genre) {
                                while ($row = $link_genre->fetch_assoc())
                                    $selected_genres[] = $row['genreId'];
                            }
                            foreach ($filtered_categories as $category):
                            ?>
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="category_<?php echo $category['id']; ?>" 
                                           name="categories[]" 
                                           value="<?php echo $category['id']; ?>"
                                           <?php echo in_array($category['id'], $selected_genres) ? 'checked' : ''; ?>>
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
                    </div>
                    
                    <div class="form-group">
                        <label for="poster">Ảnh bìa</label>
                        <input type="url" class="form-control" id="poster" name="poster" value="<?php echo $film['photo']; ?>">
                        <?php if ($film['photo']): ?>
                            <div class="mb-2">
                                <img src="<?php echo $film['photo']; ?>" alt="Current Poster" style="max-width: 100%; max-height: 300px;">
                            </div>
                        <?php endif; ?>
                        <div class="mt-2">
                            <img id="posterPreview" src="<?php echo $film['photo']; ?>" alt="Poster Preview" style="max-width: 100%; display: none;">
                        </div>
                    </div>
                    <?php if($type == 'movie'): ?>
                    <div class="card mt-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Xem trước video</h6>
                        </div>
                        <div class="card-body p-0">
                            <?php if (!empty($film['linkVideo'])): ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe id="videoPreview" class="embed-responsive-item" src="<?php echo $film['linkVideo']; ?>" allowfullscreen></iframe>
                            </div>
                            <?php else: ?>
                            <div class="text-center p-3" id="noVideoMessage">
                                <i class="fas fa-film fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Nhập đường dẫn video để xem trước</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($type == 'series'): ?>
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle mr-1"></i> Để quản lý tập phim cho bộ phim này, hãy sử dụng nút "Quản lý tập phim" ở trên.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="text-right mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Preview poster image before upload
document.getElementById('posterUpload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const img = document.getElementById('posterPreview');
            img.src = event.target.result;
            img.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

// Preview video embed
document.getElementById('video_url').addEventListener('input', function() {
    const videoUrl = this.value.trim();
    const videoPreview = document.getElementById('videoPreview');
    const noVideoMessage = document.getElementById('noVideoMessage');
    
    if (videoUrl) {
        if (!videoPreview) {
            // Create iframe if it doesn't exist
            const previewContainer = document.createElement('div');
            previewContainer.className = 'embed-responsive embed-responsive-16by9';
            
            const iframe = document.createElement('iframe');
            iframe.id = 'videoPreview';
            iframe.className = 'embed-responsive-item';
            iframe.setAttribute('allowfullscreen', '');
            
            previewContainer.appendChild(iframe);
            
            const cardBody = document.querySelector('.card-body.p-0');
            cardBody.innerHTML = '';
            cardBody.appendChild(previewContainer);
            
            videoPreview = iframe;
        }
        
        videoPreview.src = videoUrl;
        
        if (noVideoMessage) {
            noVideoMessage.style.display = 'none';
        }
    } else {
        if (videoPreview && videoPreview.parentElement) {
            videoPreview.parentElement.style.display = 'none';
        }
        
        if (noVideoMessage) {
            noVideoMessage.style.display = 'block';
        } else {
            // Create no video message if it doesn't exist
            const messageDiv = document.createElement('div');
            messageDiv.id = 'noVideoMessage';
            messageDiv.className = 'text-center p-3';
            messageDiv.innerHTML = `
                <i class="fas fa-film fa-2x text-muted mb-2"></i>
                <p class="text-muted">Nhập đường dẫn video để xem trước</p>
            `;
            
            const cardBody = document.querySelector('.card-body.p-0');
            cardBody.innerHTML = '';
            cardBody.appendChild(messageDiv);
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>