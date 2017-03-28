<?php
if(!isset($_GET['ajax'])){
  get_header();
  $myTeam = $_GET['team'];
  $mySeason = $_GET['season'];
  $myType = $_GET['category'];
}
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
      <h1><span>Results:</span> <?php echo get_search_query(); ?></h1>
    </div>
  </div>
  <?php include get_template_directory().'/inc/filters/news-filters.php'; ?>
  <div class="news-item-container">
    <?php
    if(have_posts()):
      while (have_posts()): the_post();
        include get_template_directory().'/inc/tiles/news-block.php';
      endwhile;
    else: ?>
    <div class="no-results">
      <h3>No results found.</h3>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php
if(!isset($_GET['ajax'])){
  get_footer();
}
?>
