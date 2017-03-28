<?php
/**
 * Template Name: Grid Page
 *
 * @package WordPress
 * @subpackage National Lacrosse League

 * @since National Lacrosse League 0.0.1
 */
get_header();
?>

<div class="single-article page grid-page">
  <?php include get_template_directory().'/inc/schedule-slider.php'; ?>
  <?php
  if(have_posts()):
  while(have_posts()): the_post(); ?>
    <div class="story-content-container">
      <div class="story-editorial-copy-container">
        <!--Article body-->
        <div class="content-wrap">
          <div class="nll-bar sidebar-left w100">
            <div class="title-container large">
              <h2><?php the_title(); ?></h2>
            </div>
            <div class="story-subhead"><?php the_content(); ?></div>
            <div class="article-content">
              <div class="grids">
              <?php
              if(have_rows('grid_maker')):
                while(have_rows('grid_maker')): the_row();
                  echo '<h3>'.get_sub_field('grid_title').'</h3>';
                  if(have_rows('grid_items')):
                    while(have_rows('grid_items')): the_row(); ?>
                      <div class="grid-item">
                        <div class="grid-item-image">
                          <a href="<?php the_sub_field('link'); ?>" target="_blank">
                            <img src="<?php the_sub_field('logo'); ?>" alt="" />
                          </a>
                        </div>
                        <?php if(get_sub_field('title')): ?>
                          <p><?php the_sub_field('title'); ?></p>
                          <a href="<?php the_sub_field('link'); ?>"<?php if(strpos(get_sub_field('link_text'), 'nll.') === false) echo ' target="_blank"'; ?>><?php
                          if(get_sub_field('link_text') != '') echo get_sub_field('link_text');
                          else echo explode('//', get_sub_field('link'))[1];
                          ?></a>
                        <?php
                        endif; ?>
                      </div>
                    <?php endwhile;
                  endif;
                endwhile;
              endif;
              ?>
              </div>
              <!--Story footer-->
            </div>
          </div>


        </div>
      </div>
    </div>
  <?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>
