<?php 
include_once './includes/header.php';
$result;
$fmovie = true;
$fseries = true;
if(isset($_GET['movieId'])) {
    $fmovie = false;
    $movieId = $_GET['movieId'];
    $result = $movie->getMovieById($movieId);
}
if(isset($_GET['seriesId'])) {
    $fseries = false;
    $seriesId = $_GET['seriesId'];
    $ep = isset($_GET['ep']) ? $_GET['ep'] : 1;
    $result = $episode->getFilmBySeriesId($seriesId, $ep);
}
if($fmovie && $fseries) {
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
            <br>
            <iframe class="rumble" width="100%" height="500" src="<?php echo $row['linkVideo'] ?>" frameborder="0" allowfullscreen></iframe>
            <div class="button-watch">
               <ul class="halim-social-plugin col-xs-4 hidden-xs">
                  <li class="fb-like" data-href="" data-layout="button_count" data-action="like" data-size="small" data-show-faces="true" data-share="true"></li>
               </ul>
               <ul class="col-xs-12 col-md-8">
                  <div class="luotxem">
                     <span><?php echo $fm->formatNumber($row['view']) ?></span> lượt xem
                  </div>
               </ul>
            </div>
            <div class="collapse" id="moretool">
               <ul class="nav nav-pills x-nav-justified">
                  <li class="fb-like" data-href="" data-layout="button_count" data-action="like" data-size="small"
                     data-show-faces="true" data-share="true"></li>
                  <div class="fb-save" data-uri="" data-size="small"></div>
               </ul>
            </div>
            <div class="clearfix"></div>
            <div class="clearfix"></div>
            <div class="title-block">
               <a href="javascript:;" data-toggle="tooltip" title="Add to bookmark">
                  <div id="bookmark" class="bookmark-img-animation primary_ribbon" data-id="37976">
                     <div class="halim-pulse-ring"></div>
                  </div>
               </a>
               <div class="title-wrapper-xem full">
                  <h1 class="entry-title">
                     <a href="" title="<?php echo $row['title']?>" class="tl">
                        <?php 
                           echo $row['title'];
                           if($fseries == false) {
                              echo ' - Tập '.$ep;
                           }
                        ?>
                     </a>
                  </h1>
               </div>
            </div>
            <div class="entry-content htmlwrap clearfix collapse" id="expand-post-content">
               <article id="post-37976" class="item-content post-37976"></article>
            </div>
            <div class="clearfix"></div>
            <div class="text-center">
               <div id="halim-ajax-list-server"></div>
            </div>
            <?php 
               if ($fseries == false) {
                  $res = $episode->getNumberOfEpisodes($seriesId);
                  $count = $res->fetch_assoc()['total'];
            ?>
            <div id="halim-list-server">
               <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" class="active server-1">
                     <a href="#server-0" aria-controls="server-0" role="tab" data-toggle="tab">
                        <i class="hl-server"></i> Thuyết Minh
                     </a>
                  </li>
               </ul>
               <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active server-1" id="server-0">
                     <div class="halim-server">
                        <ul class="halim-list-eps">
                           <?php
                              for($i = 1; $i <= $count; $i++) {
                           ?>
                           <li class="halim-episode">
                              <span class="halim-btn halim-btn-2 active halim-info-1-1 box-shadow">
                                 <a href="?seriesId=<?php echo $seriesId?>&ep=<?php echo $i?>"><?php echo $i?></a>
                              </span>
                           </li>
                           <?php 
                              }
                           ?>
                        </ul>
                        <div class="clearfix"></div>
                     </div>
                  </div>
               </div>
            </div>
            <?php
                  }
            ?>
            <div class="clearfix"></div>
            <div class="htmlwrap clearfix">
               <div id="lightout"></div>
            </div>
      </section>
      <?php 
         }
      ?>
   </main>
<?php include_once './includes/topview.php'; ?>
</div>
<?php include_once './includes/footer.php'; ?>