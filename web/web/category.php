<?php 
include_once './includes/header.php';
$type;
$film = [];
if(isset($_GET['s'])) {
   $search = $_GET['s'];
   $type = "Kết quả tìm kiếm";
   $res1 = $movie->searchMovies($search);
   $res2 = $series->searchSeries($search);
   if($res1) {
      while($row = $res1->fetch_assoc())
         $film[] = $row;
   }
   if($res2) {
      while($row = $res2->fetch_assoc())
         $film[] = $row;
   }
} else if(isset($_GET['genre'])) {
   $id = $_GET['genre'];
   $res = $genre->getGenreById($id);
   if($res) {
      $row = $res->fetch_assoc();
      $type = $row['title'];
   }
   $res1 = $genre->getMovieByGenreId($id);
   $res2 = $genre->getSeriesByGenreId($id);
   if($res1) {
      while($row = $res1->fetch_assoc())
         $film[] = $row;
   }
   if($res2) {
      while($row = $res2->fetch_assoc())
         $film[] = $row;
   }
} else if(isset($_GET['name'])) {
   $name = $_GET['name'];
   if($name == 'newfilm') {
      $year = date("Y");
      $type = "Phim ".$year;
      $res1 = $movie->getMovieByYear($year);
      $res2 = $series->getSeriesByYear($year);
      if($res1) {
         while($row = $res1->fetch_assoc())
            $film[] = $row;
      }
      if($res2) {
         while($row = $res2->fetch_assoc())
            $film[] = $row;
      }
   } else if($name == 'movie') {
      $type = "Movie";
      $res = $movie->getAllMovies();
      if($res) {
         while($row = $res->fetch_assoc())
            $film[] = $row;
      }
   } else if($name == 'series') {
      $type = "Phim Bộ";
      $res = $series->getAllSeries();
      if($res) {
         while($row = $res->fetch_assoc())
            $film[] = $row;
      }
   } else {
      header('Location: index.php');
      exit();
   }
}
?>
<div class="row container" id="wrapper">
   <main id="main-contents" class="col-xs-12 col-sm-12 col-md-8">
      <section>
         <?php 
            if(empty($film)) {
               echo "<div class='alert alert-danger' style='margin-top: 40px;'>Không có phim nào</div>";
            } else {
         ?>
         <div class="section-bar clearfix">
            <h1 class="section-title"><span><?php echo $type; ?></span></h1>
         </div>
         <div class="halim_box">
            <?php 
               // PHÂN TRANG BLOCK
               $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
               $items_per_page = 12;
               $total_pages = ceil(count($film) / $items_per_page);
               $start_index = ($page - 1) * $items_per_page;
               $film_page = array_slice($film, $start_index, $items_per_page);
               foreach($film_page as $row) {
            ?>
            <article class="col-md-3 col-sm-3 col-xs-6 thumb grid-item post-27021">
               <div class="halim-item">
                  <a class="halim-thumb" 
                     href="detail.php?<?php echo ($row['isCheck'] == 0) ? 'movieId='.$row['id'] : 'seriesId='.$row['id'];  ?>" 
                     title="<?php echo $row['title']; ?>">
                     <figure>
                        <img class="lazy img-responsive"
                           src="<?php echo $row['photo']?>"
                           alt="<?php echo $row['title']; ?>" 
                           title="<?php echo $row['title']; ?>">
                     </figure>
                     <span class="status"><?php echo $row['quality']; ?></span>
                     <span class="episode">
                        <i class="fa fa-play" aria-hidden="true"></i>Thuyết Minh
                     </span>
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
            ?>
         </div>
         <?php if ($total_pages > 1): ?>
         <div class="text-center">
            <ul class="pagination justify-content-center">
                <?php
                // Build query string for pagination links
                $base_url = '?';
                foreach ($_GET as $k => $v) {
                    if ($k != 'page') $base_url .= urlencode($k) . '=' . urlencode($v) . '&';
                }
                $max_visible = 5;
                $current_block = ceil($page / $max_visible);
                $start = ($current_block - 1) * $max_visible + 1;
                $end = min($start + $max_visible - 1, $total_pages);

                // Previous page
                $prev_page = max(1, $page - 1);
                echo '<li class="page-item '.($page == 1 ? 'disabled' : '').'">'
                    .'<a class="page-link" href="'.$base_url.'page='.$prev_page.'" aria-label="Previous">'
                    .'<span aria-hidden="true">&laquo;</span>'
                    .'</a>'
                    .'</li>';

                // Page numbers in block
                for ($i = $start; $i <= $end; $i++) {
                    echo '<li class="page-item '.($i == $page ? 'active' : '').'">'
                        .'<a class="page-link" href="'.$base_url.'page='.$i.'">'.$i.'</a>'
                        .'</li>';
                }

                // Next page
                $next_page = min($total_pages, $page + 1);
                echo '<li class="page-item '.($page == $total_pages ? 'disabled' : '').'">'
                    .'<a class="page-link" href="'.$base_url.'page='.$next_page.'" aria-label="Next">'
                    .'<span aria-hidden="true">&raquo;</span>'
                    .'</a>'
                    .'</li>';
                ?>
            </ul>
         </div>
         <?php endif; ?>
         <?php
            }
         ?>
         <div class="clearfix"></div>
      </section>
   </main>
<?php include_once './includes/topview.php'; ?>
   </div>
<?php include_once './includes/footer.php';?>