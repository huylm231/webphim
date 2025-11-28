<?php
$page_title = "Thêm Tập Phim";
include 'includes/header.php';
$id = (int)$_GET['id'];
$season = (int)$_GET['season'];
$series = $series->getSeriesById($id)->fetch_assoc();
$next_episode = $episode->getNumberOfEpisodesBySeason($id, $season)->fetch_assoc()['total'] + 1;
$msg = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $msg = $episode->add($_POST, $id);
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?php echo $page_title; ?></h1>
    <div>
        <a href="manage-series.php?id=<?php echo $id; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>
</div>

<?php if(isset($msg)) echo $msg;?>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin phim</h5>
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
                <p class="text-muted">Mùa <?php echo $season; ?></p>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle mr-1"></i> Bạn đang thêm vào mùa <?php echo $season; ?>.
                </div>
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
                        <input type="text" class="form-control" id="episode_title" name="title" value="<?php echo isset($episode_title) ? $episode_title : ''; ?>" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="season_number">Mùa <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="season_number" name="season_number" value="<?php echo $season; ?>" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="episode_number">Tập <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="episode_number" name="episode_number" min="<?php echo $next_episode; ?>" value="<?php echo $next_episode; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="episode_duration">Thời lượng (phút) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="episode_duration" name="episode_duration" min="1" value="<?php echo $series['runtime']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="video_url">Đường dẫn video <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="video_url" name="video_url" value="<?php echo isset($video_url) ? $video_url : ''; ?>" required>
                    </div>
                    
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Thêm tập phim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>