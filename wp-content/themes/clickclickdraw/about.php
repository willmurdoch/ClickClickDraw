<?php
/**
 * Template Name: About
 *
 * @package WordPress
 * @subpackage Click Click Draw

 * @since Click Click Draw 2.0.0
 */
get_header(); ?>
<div id="wrapper" class="single-work">
  <?php
  if(have_posts()):
    while(have_posts()): the_post();
      $featuredImage = wp_get_attachment_url(get_post_thumbnail_id()); ?>
      <div class="hero" style="background-image:url();"><img src="<?php echo $featuredImage; ?>" alt="" /></div>
      <div class="scaler">
      <header>
        <img src="" alt="" />
        <h1 class="title about"><span><?php the_title(); ?></span></h1>
      </header>
      <div class="content">
        <?php the_content(); ?>
      </div>
  <?php endwhile;
endif; ?>
</div>
<?php
include 'inc/tweet.php';
include 'inc/contact.php'; ?>
</div>
<?php get_footer(); ?>
