<?php
$page_title = "Sửa Tập Phim";
include 'includes/header.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$result = $episode->getFilmById($id)->fetch_assoc();
$series_id = $result['seriesId'];
$series = $series->getSeriesById($series_id)->fetch_assoc();
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $msg = $episode->edit($id, $_POST);
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?php echo $page_title; ?></h1>
    <div>
        <a href="manage-series.php?id=<?php echo $series_id; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>
</div>

<?php if(isset($msg)) echo $msg; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin bộ phim</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <?php if ($series['photo']): ?>
                        <img src="<?php echo $series['photo']; ?>" alt="<?php echo $series['title']; ?>" style="max-width: 100%; max-height: 200px;">
                    <?php else: ?>
                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="width: 150px; height: 200px; margin: 0 auto;">
                            <i class="fas fa-tv fa-3x text-white"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <h5><?php echo $series['title']; ?></h5>
                <p class="text-muted">Mùa <?php echo $result['season_number']; ?>, Tập <?php echo $result['ep']; ?></p>
                
                <?php if ($result['linkVideo']): ?>
                    <div class="mt-3">
                        <h6>Xem trước video</h6>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe id="videoPreview" class="embed-responsive-item" src="<?php echo $result['linkVideo']; ?>" allowfullscreen></iframe>
                        </div>
                        <small class="text-muted d-block mt-1">Xem trước có thể không hoạt động với tất cả các nguồn video</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thông tin tập phim</h5>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="episode_title">Tên tập phim <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="episode_title" name="title" value="<?php echo $result['title']; ?>" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="season_number">Mùa <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="season_number" name="season_number" min="1" value="<?php echo $result['season_number']; ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="episode_number">Tập <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="episode_number" name="episode_number" min="1" value="<?php echo $result['ep']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="episode_duration">Thời lượng (phút) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="episode_duration" name="episode_duration" min="1" value="<?php echo $result['runtime']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="video_url">Đường dẫn video <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="video_url" name="video_url" value="<?php echo $result['linkVideo']; ?>" required>
                        <small class="form-text text-muted">Sử dụng định dạng URL iframe</small>
                    </div>
                    
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>