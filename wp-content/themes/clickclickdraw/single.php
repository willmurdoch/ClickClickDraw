<?php get_header(); ?>
<div id="wrapper" class="blog-post">
  <?php $uploads = wp_upload_dir(); ?>
  <div class="hero"><img src="<?php echo $uploads['url']; ?>/about_banner.jpg" alt="" /></div>
  <div class="scaler">
    <header>
      <h1 class="title blog"><span>Blog</h1>
    </header>
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
    $featuredImage = wp_get_attachment_url(get_post_thumbnail_id());
    echo '<div class="blog-tile">';
    echo '<h3>'.$post->post_title.'</h3>';
    echo '<p class="date">'.get_the_date('dS F, Y', $post->ID).'</p>';
    echo '<img src="'.$featuredImage.'" alt="" />';
    echo '</div>';
    the_content();
    endwhile; endif; ?>
    <a class="btn" href="../">Back to All Posts</a>
  </div>
  <?php include 'inc/social.php'; ?>
  <?php include 'inc/contact.php'; ?>
</div>
<?php get_footer(); ?>
