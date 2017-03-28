<?php get_header(); ?>

<div class="single-article page">
  <?php include get_template_directory().'/inc/schedule-slider.php'; ?>
  <?php
  if(have_posts()):
  while(have_posts()): the_post(); ?>
    <div class="story-content-container">
      <div class="story-editorial-copy-container">
        <!--Article body-->
        <div class="content-wrap">
          <div class="nll-bar sidebar-left w70">
            <div class="title-container large">
              <h2><?php the_title(); ?></h2>
            </div>
            <div class="story-subhead"><?php if(get_field('subheader')): ?><p><?php the_field('subheader'); ?></p><?php endif; ?></div>
            <div class="article-content">
              <div class="story-description list-component-body<?php if(get_field('big_first_letter') == true) echo ' bigFirst'; ?>">
                <?php
                $content = apply_filters('the_content', $post->post_content);
                echo $content;
                ?>
              </div>
              <!--Story footer-->
            </div>
          </div>
          <div class="nll-bar sidebar-right w30">
            <?php include get_template_directory().'/inc/master-sidebar.php'; ?>
          </div>

        </div>
      </div>
    </div>
  <?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>
