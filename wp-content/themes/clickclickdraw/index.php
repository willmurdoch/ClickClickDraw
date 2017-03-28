<?php
if(!isset($_GET['ajax']))
  get_header();

//Filters (if applicable)
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
      <h1>News</h1>
    </div>
  </div>
  <?php include get_template_directory().'/inc/filters/news-filters.php'; ?>
  <div class="news-item-container">
    <?php
    if(isset($_GET['offset'])){
      $offset = $_GET['offset'];
    }
    else $offset = 0;

    $args = array(
      'orderby' => 'date',
      'ignore_sticky_posts' => 1,
      'offset' => $offset
    );
    if(isset($_GET['season']) && $_GET['season'] != ''){
      $seasonQuery = array(
        array(
          'year' => $_GET['season']
        )
      );
      $args['date_query'] = $seasonQuery;
    }
    if(isset($_GET['team']) && $_GET['team'] != ''){
      $teamQuery = array(
        array(
          'taxonomy' => 'teams',
          'field' => 'slug',
          'terms' => $_GET['team']
        )
      );
      $args['tax_query'] = $teamQuery;
    }
    if(isset($_GET['category']) && $_GET['category'] != ''){
      $args['category_name'] = $_GET['category'];
    }

    $newsQuery = new WP_Query($args);
    if($newsQuery->have_posts()):
      while ($newsQuery->have_posts()) : $newsQuery->the_post();
        include get_template_directory().'/inc/tiles/news-block.php';
      endwhile;
    else:
      echo '<div class="no-results"><h3>No results found.</h3></div>';
    endif; ?>
  </div>
</div>
<?php
if(!isset($_GET['ajax']))
  get_footer();
?>
