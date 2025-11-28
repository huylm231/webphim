<?php 
include_once './includes/header.php';
$result;
$type;
$f = true;
if(isset($_GET['movieId'])) {
    $f = false;
    $type = 'movie';
    $movieId = $_GET['movieId'];
    $result = $movie->getMovieById($movieId);
}
if(isset($_GET['seriesId'])) {
    $f = false;
    $type = 'series';
    $seriesId = $_GET['seriesId'];
    $result = $series->getSeriesById($seriesId);
}
if($f) {
    header('Location: index.php');
    exit();
}
?>
<div class="row container" id="wrapper">
   <main id="main-contents" class="col-xs-12 col-sm-12 col-md-8">
      <?php 
         if($result) {
            $row = $result->fetch_assoc();
      ?>
      <section id="content" class="test">
         <div class="clearfix wrap-content">
            <div class="halim-movie-wrapper">
               <div class="movie_info col-xs-12">
                  <div class="movie-poster col-md-3">
                     <img class="movie-thumb"
                        src="<?php echo $row['photo']?>"
                        alt="<?php echo $row['title'] ?>">
                     <div class="bwa-content">
                        <div class="loader"></div>
                        <a href="watching.php?<?php echo isset($movieId) ? 'movieId=' . $movieId : 'seriesId=' . $seriesId; ?>" class="bwac-btn">
                           <i class="fa fa-play"></i>
                        </a>
                     </div>
                  </div>
                  <div class="film-poster col-md-9">
                     <h1 class="movie-title title-1"
                        style="display:block;line-height:35px;margin-bottom: -14px;color: #ffed4d;text-transform: uppercase;font-size: 18px;">
                        <?php echo $row['title'] ?></h1>
                     <h2 class="movie-title title-2" style="font-size: 12px;"><?php echo $row['releaseYear'] ?></h2>
                     <ul class="list-info-group">
                        <li class="list-info-group-item"><span>Trạng Thái</span> : 
                           <span class="quality"><?php echo $row['quality'] ?></span>
                           <span class="episode">Thuyết Minh</span>
                        </li>
                        <li class="list-info-group-item"><span>Đánh giá</span> : <span class="imdb"><?php echo $row['rating'] ?></span>
                        </li>
                        <li class="list-info-group-item"><span>Thời lượng</span> : <?php echo $row['runtime'] ?> phút</li>
                        <?php 
                           $genres = $genre->getGenreByLinkGenre($row['id'], $type);
                           if($genres) {
                        ?>
                        <li class="list-info-group-item"><span>Thể loại :</span>
                           <?php while($res = $genres->fetch_assoc()) {?>
                           <a href="" rel="category tag"><?php echo $res['title'] ?></a>,
                           <?php }?>
                        </li>
                        <?php
                           }
                        ?>
                     </ul>
                     <div class="movie-trailer hidden"></div>
                  </div>
               </div>
            </div>
            <div class="clearfix"></div>
            <div id="halim_trailer"></div>
            <div class="clearfix"></div>
            <div class="section-bar clearfix">
               <h2 class="section-title"><span style="color:#ffed4d">Nội dung phim</span></h2>
            </div>
            <div class="entry-content htmlwrap clearfix">
               <div class="video-item halim-entry-box">
                  <article id="post-38424" class="item-content"> Phim 
                     <p><?php echo $row['description'] ?></p>
                  </article>
               </div>
            </div>
         </div>
      </section>
      <?php
         }
      ?>
   </main>
<?php include_once './includes/topview.php';?>
</div>
<?php include_once './includes/footer.php'; ?>