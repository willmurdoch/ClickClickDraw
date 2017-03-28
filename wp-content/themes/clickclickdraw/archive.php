<?php get_header(); ?>
<div class="news feed">
  <?php include get_template_directory().'/inc/schedule-slider.php'; ?>
  <div class="article-thumbnail-container">
    <?php
    $args = array(
      'posts_per_page' => 4,
      'post__in' =>  get_option('sticky_posts'),
      'orderby' => 'date'
    );
    $myStickyPosts = get_posts($args);
    foreach($myStickyPosts as $story):
      include get_template_directory().'/inc/tiles/news-tile.php';
    endforeach;
    ?>
  </div>
  <div class="header-text-container">
    <div class="header-text">
      <h1><?php single_cat_title(); ?></h1>
    </div>
  </div>
  <?php include get_template_directory().'/inc/filters/news-filters.php'; ?>
  <div class="news-item-container">
    <?php
    if(have_posts()):
      while (have_posts()): the_post();
        include get_template_directory().'/inc/tiles/news-block.php';
      endwhile;
    endif; ?>
  </div>
</div>
<?php get_footer(); ?>
