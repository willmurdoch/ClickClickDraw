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
    'post_type' => 'post',
    'posts_per_page' => -1
  );
  $myPosts = get_posts($args);
  foreach($myPosts as $post):
    $featuredImage = wp_get_attachment_url(get_post_thumbnail_id());
    echo '<div class="blog-tile">';
    echo '<h3><a href="'.get_the_permalink($post->ID).'">'.$post->post_title.'</a></h3>';
    echo '<p class="date">'.get_the_date('dS F, Y', $post->ID).'</p>';
    echo '<a href="'.get_the_permalink($post->ID).'"><img src="'.$featuredImage.'" alt="" /></a><div class="post-blurb">';
    echo '<p>'.wp_trim_words(strip_tags($post->post_content), 35);
    echo '<a class="readMore" href="'.get_the_permalink($post->ID).'">Read more...</a></p></div></div>';
  endforeach; ?>
  </div>
<?php include 'inc/contact.php'; ?>
</div>
<?php get_footer();
?>
