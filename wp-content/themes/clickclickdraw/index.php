<?php
get_header();
$image = wp_get_attachment_image_src(get_post_thumbnail_id(get_option('page_for_posts')),'full');
$image = $image[0];
?>
<div id="wrapper" class="blog">
  <div class="hero"><img src="<?php echo $image; ?>" alt="" /></div>
  <div class="scaler">
    <header>
      <h1 class="title blog"><span>Blog</h1>
    </header>
  <?php
  $args = array(
    'post_type' => 'post'
  );
  $myPosts = get_posts($args);
  foreach($myPosts as $post):
    echo $post->post_content;
    echo '<a href="'.get_the_permalink($post->ID).'">Link</a>';
  endforeach; ?>
  </div>
<?php include 'inc/contact.php'; ?>
</div>
<?php get_footer();
?>
