<?php get_header(); ?>
<div id="wrapper" class="single-work">
  <?php
  if(have_posts()):
    while(have_posts()): the_post();
      $featuredImage = wp_get_attachment_url(get_post_thumbnail_id()); ?>
      <div class="hero"><img src="<?php echo $featuredImage; ?>" alt="" /></div>
      <div class="scaler">
      <header>
          <h1 class="title work"><span><?php the_title(); ?></span></h1>
          <p class="contributors">
            <?php
            if(have_rows('contributors')):
              while(have_rows('contributors')): the_row();
                echo '<span>'.get_sub_field('role').': <strong>'.get_sub_field('name').'</strong></span>';
              endwhile;
            endif;
            ?>
          </p>
          <?php include 'inc/social.php'; ?>
          <div class="content">
            <?php the_content(); ?>
          </div>
        </header>

        <?php
        if(have_rows('gallery')):
          while(have_rows('gallery')): the_row();
            echo '<img class="gallery-image" src="'.get_sub_field('image').'" alt="" />';
          endwhile;
        endif; ?>
        <?php if(get_field('link') != ''):
          echo '<a class="btn ext" href="'.get_field('link').'" target="_blank">Launch Site</a>';
        endif;
        ?>
        <a class="btn" href="<?php echo get_site_url(); ?>">Back to All Work</a>
      </div>
  <?php endwhile;
endif; ?>
<?php include 'inc/contact.php'; ?>
</div>
<?php get_footer(); ?>
