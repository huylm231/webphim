<aside id="sidebar" class="col-xs-12 col-sm-12 col-md-4">
    <div id="halim_tab_popular_videos-widget-7" class="widget halim_tab_popular_videos-widget">
        <div class="section-bar clearfix">
        <div class="section-title">
            <span>Xem nhiều nhất</span>
        </div>
        </div>
        <section class="tab-content">
        <div role="tabpanel" class="tab-pane active halim-ajax-popular-post">
            <div class="halim-ajax-popular-post-loading hidden"></div>
            <div id="halim-ajax-popular-post" class="popular-post">
                <?php
                    $result = $movie->getMovieTopViews();
                    if($result) {
                        while($row = $result->fetch_assoc()) {
                ?>
                <div class="item post-37176">
                    <a href="detail.php?movieId=<?php echo $row['id']?>" title="<?php echo $row['title'] ?>">
                    <div class="item-link">
                        <img
                            src="<?php echo $row['photo']; ?>"
                            class="lazy post-thumb" alt="<?php echo $row['title'] ?>"
                            title="<?php echo $row['title'] ?>" />
                        <span class="is_trailer">Trailer</span>
                    </div>
                    <p class="title"><?php echo $row['title'] ?></p>
                    </a>
                    <div class="viewsCount" style="color: #9d9d9d;"><?php echo $fm->formatNumber($row['view']) ?> lượt xem</div>
                    <div style="float: left;">
                    <span class="user-rate-image post-large-rate stars-large-vang"
                        style="display: block;/* width: 100%; */">
                        <span style="width: 0%"></span>
                    </span>
                    </div>
                </div>
                <?php
                        }
                    }
                ?>
            </div>
        </div>
        </section>
        <div class="clearfix"></div>
    </div>
</aside>