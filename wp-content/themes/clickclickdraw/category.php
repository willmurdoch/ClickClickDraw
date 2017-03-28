<?php
if(!isset($_GET['ajax']))
  get_header();
?>
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
    $offset = $_GET['offset'];
    if(!$offset) $offset = 0;
    $term = get_queried_object();
    $args = array(
      'category_name' => $term->slug,
      'offset' => $offset
    );
    $catQuery = new WP_Query($args);
    if($catQuery->have_posts()):
      while ($catQuery->have_posts()): $catQuery->the_post();
        include get_template_directory().'/inc/tiles/news-block.php';
      endwhile;
    endif; ?>
  </div>
</div>
<?php
if(!isset($_GET['ajax']))
  get_footer();
?>
