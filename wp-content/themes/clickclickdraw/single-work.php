<?php get_header(); ?>
<div id="wrapper" class="single-work">
  <?php
  if(have_posts()):
    while(have_posts()): the_post();
      $featuredImage = wp_get_attachment_url(get_post_thumbnail_id()); ?>
      <div class="hero"><img src="<?php echo $featuredImage; ?>" alt="" /></div>
      <div class="scaler">
      <header>
          <img src="" alt="" />
          <h1 class="title work"><span><?php the_title(); ?></span></h1>
          <?php include 'inc/social.php'; ?>
          <?php the_content(); ?>
        </header>

        <?php
        if(have_rows('gallery')):
          while(have_rows('gallery')): the_row();
            echo '<img class="gallery-image" src="'.get_sub_field('image').'" alt="" />';
          endwhile;
        endif; ?>
        <a class="btn" href="<?php echo get_site_url(); ?>">Back to All Work</a>
      </div>
  <?php endwhile;
endif; ?>
<?php include 'inc/contact.php'; ?>
</div>
<?php get_footer(); ?>
