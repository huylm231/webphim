<?php 
include_once './includes/header.php';
?>
<div class="row container" id="wrapper">
   <div class="halim-panel-filter">
      <div id="ajax-filter" class="panel-collapse collapse" aria-expanded="true" role="menu">
         <div class="ajax"></div>
      </div>
   </div>
   <div class="col-xs-12 carausel-sliderWidget">
      <section id="halim-advanced-widget-4">
         <div class="section-heading">
            <a href="category.php" title="Phim Chiếu Rạp">
               <span class="h-text">Movie Nổi Bật</span>
            </a>
            <ul class="heading-nav pull-right hidden-xs">
               <li class="section-btn halim_ajax_get_post" data-catid="4" data-showpost="12"
                  data-widgetid="halim-advanced-widget-4" data-layout="6col"><span data-text="Chiếu Rạp"></span>
               </li>
            </ul>
         </div>
         <div id="halim-advanced-widget-4-ajax-box" class="halim_box">
            <?php 
               $result = $movie->getPopularMovies();
               if($result) {
                  while($row = $result->fetch_assoc()) {
            ?>
            <article class="col-md-2 col-sm-4 col-xs-6 thumb grid-item post-38424">
               <div class="halim-item">
                  <a class="halim-thumb" href="detail.php?movieId=<?php echo $row['id']?>" title="<?php echo $row['title']; ?>">
                     <figure><img class="lazy img-responsive"
                           src="<?php echo $row['photo']; ?>"
                           alt="<?php echo $row['title']; ?>" title="<?php echo $row['title']; ?>"></figure>
                     <span class="status"><?php echo $row['quality']; ?></span><span class="episode"><i class="fa fa-play"
                           aria-hidden="true"></i>Thuyết Minh</span>
                     <div class="icon_overlay"></div>
                     <div class="halim-post-title-box">
                        <div class="halim-post-title ">
                           <p class="entry-title"><?php echo $row['title']; ?></p>
                        </div>
                     </div>
                  </a>
               </div>
            </article>
            <?php 
               }
            }
            ?>
         </div>
      </section>
      <div class="clearfix"></div>
   </div>
   <main id="main-contents" class="col-xs-12 col-sm-12 col-md-8">
      <section id="halim-advanced-widget-2">
         <div class="section-heading">
            <a href="category.php" title="Phim Bộ">
               <span class="h-text">Phim Bộ</span>
            </a>
         </div>
         <div id="halim-advanced-widget-2-ajax-box" class="halim_box">
            <?php 
               $result = $series->getPopularSeries();
               if($result) {
                  while($row = $result->fetch_assoc()) {
            ?>
            <article class="col-md-3 col-sm-3 col-xs-6 thumb grid-item post-37606">
               <div class="halim-item">
                  <a class="halim-thumb" href="detail.php?seriesId=<?php echo $row['id']?>">
                     <figure><img class="lazy img-responsive"
                           src="<?php echo $row['photo']; ?>"
                           alt="<?php echo $row['title']?>" title="<?php echo $row['title']?>">
                     </figure>
                     <span class="status">TẬP <?php echo $episode->getNumberOfEpisodes($row['id'])->fetch_assoc()['total']?></span><span class="episode"><i class="fa fa-play"
                           aria-hidden="true"></i>Thuyết Minh</span>
                     <div class="icon_overlay"></div>
                     <div class="halim-post-title-box">
                        <div class="halim-post-title ">
                           <p class="entry-title"><?php echo $row['title']?></p>
                        </div>
                     </div>
                  </a>
               </div>
            </article>
            <?php 
                  }
               }
            ?>
         </div>
      </section>
      <div class="clearfix"></div>
      <section id="halim-advanced-widget-2">
         <div class="section-heading">
            <a href="category.php" title="Phim Lẻ">
               <span class="h-text">Movie</span>
            </a>
         </div>
         <div id="halim-advanced-widget-2-ajax-box" class="halim_box">
            <?php 
               $result = $movie->getNewMovies();
               if($result) {
                  while($row = $result->fetch_assoc()) {
            ?>
            <article class="col-md-3 col-sm-3 col-xs-6 thumb grid-item post-37606">
               <div class="halim-item">
                  <a class="halim-thumb" href="detail.php?movieId=<?php echo $row['id']?>">
                     <figure><img class="lazy img-responsive"
                           src="<?php echo $row['photo']; ?>"
                           alt="<?php echo $row['title']?>" title="<?php echo $row['title']?>">
                     </figure>
                     <span class="status">TẬP 15</span><span class="episode"><i class="fa fa-play"
                           aria-hidden="true"></i>Thuyết Minh</span>
                     <div class="icon_overlay"></div>
                     <div class="halim-post-title-box">
                        <div class="halim-post-title ">
                           <p class="entry-title"><?php echo $row['title']?></p>
                        </div>
                     </div>
                  </a>
               </div>
            </article>
            <?php 
                  }
               }
            ?>
         </div>
      </section>
      <div class="clearfix"></div>
   </main>
<?php include_once './includes/topview.php'; ?>
   </div>
<?php include_once './includes/footer.php'; ?>