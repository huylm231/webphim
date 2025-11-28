<?php
$page_title = "Quản lý Phim Bộ";
include 'includes/header.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$series = $series->getSeriesById($id)->fetch_assoc();
$seasons = [];
$result = $episode->getSeasion($id);
if($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc())
        $seasons[] = $row['season_number'];
}
$total_episodes = $episode->getNumberOfEpisodes($id)->fetch_assoc()['total'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?php echo $page_title; ?></h1>
    <div>
        <a href="edit-movie.php?id=<?php echo $id; ?>" class="btn btn-primary mr-2">
            <i class="fas fa-edit mr-1"></i> Chỉnh sửa chi tiết bộ phim
        </a>
        <a href="movies.php?type=series" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại bộ phim
        </a>
    </div>
</div>

<?php if(isset($msg)) echo $msg;?>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin bộ phim</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <?php if ($series['photo']): ?>
                        <img src="<?php echo $series['photo']; ?>" alt="<?php echo $series['title']; ?>" style="max-width: 100%; max-height: 300px;">
                    <?php else: ?>
                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="width: 200px; height: 300px; margin: 0 auto;">
                            <i class="fas fa-tv fa-3x text-white"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <h4><?php echo $series['title']; ?></h4>
                <p class="text-muted"><?php echo $series['releaseYear']; ?></p>
                
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Mùa:</strong>
                        <span class="badge badge-primary"><?php echo count($seasons); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Tập phim:</strong>
                        <span class="badge badge-primary"><?php echo $total_episodes; ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <strong>Thời lượng trung bình:</strong>
                        <span><?php echo $series['runtime']; ?> phút</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thêm mùa mới</h5>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="season_number">Số mùa <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="season_number" name="season_number" value="<?php echo empty($seasons) ? 1 : max($seasons) + 1; ?>" readonly>
                    </div>
                    <div class="text-right">
                        <a href="add-episode.php?id=<?php echo $id; ?>&season=<?php echo  empty($seasons) ? 1 : max($seasons) + 1; ?>" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle mr-1"></i> Thêm mùa
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tập phim</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($seasons)): ?>
                    <div class="alert alert-info m-3">
                        <i class="fas fa-info-circle mr-1"></i> Không có tập phim đã được thêm. Thêm một mùa để bắt đầu.
                    </div>
                <?php else: ?>
                    <div class="accordion" id="seasonsAccordion">
                        <?php foreach ($seasons as $season): ?>
                            <div class="card rounded-0 border-0">
                                <div class="card-header bg-light" id="heading<?php echo $season; ?>">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#collapse<?php echo $season; ?>" aria-expanded="<?php echo $season == $seasons[0] ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $season; ?>">
                                            <span>Mùa <?php echo $season; ?></span>
                                            <?php
                                            $result = $episode->getEpisodeBySeason($id, $season);
                                            $episode_count = $result->num_rows;
                                            ?>
                                            <span class="badge badge-primary"><?php echo isset($episode_count) ? $episode_count : '0'; ?> tập phim</span>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse<?php echo $season; ?>" class="collapse <?php echo $season == $seasons[0] ? 'show' : ''; ?>" aria-labelledby="heading<?php echo $season; ?>" data-parent="#seasonsAccordion">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0">
                                                <thead>
                                                    <tr>
                                                        <th width="60">Tập phim</th>
                                                        <th>Tên</th>
                                                        <th>Đường dẫn video</th>
                                                        <th width="80">Thời lượng</th>
                                                        <th width="120">Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    
                                                    if ($result && $result->num_rows > 0):
                                                        while ($row = $result->fetch_assoc()):
                                                    ?>
                                                        <tr>
                                                            <td>S<?php echo $season; ?>E<?php echo $row['ep']; ?></td>
                                                            <td><?php echo $row['title']; ?></td>
                                                            <td>
                                                                <div class="text-truncate" style="max-width: 200px;" title="<?php echo $row['linkVideo']; ?>">
                                                                    <a href="<?php echo $row['linkVideo']; ?>" target="_blank">
                                                                        <?php echo $row['linkVideo']; ?>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <td><?php echo $row['runtime']; ?> min</td>
                                                            <td>
                                                                <a href="edit-episode.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="delete-episode.php?id=<?php echo $row['id']; ?>&series_id=<?php echo $series_id; ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this episode?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                        endwhile;
                                                    else:
                                                    ?>
                                                        <tr>
                                                            <td colspan="5" class="text-center">Không tìm thấy tập phim cho mùa này.</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <a href="add-episode.php?id=<?php echo $id; ?>&season=<?php echo $season; ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-plus-circle mr-1"></i> Thêm tập phim vào mùa <?php echo $season; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>