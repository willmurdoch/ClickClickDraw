<?php get_header(); ?>

<div id="wrapper" class="single-page">
<?php
if(have_posts()):
  while(have_posts()): the_post(); ?>
    <div class="story-content-container">
      <div class="story-editorial-copy-container">
        <!--Article body-->
        <div class="content-wrap">
          <div class="nll-bar sidebar-left w70">
            <div class="title-container large">
              <h2><span><?php the_title(); ?></span></h2>
            </div>
            <div class="article-content">
              <div class="story-description list-component-body<?php if(get_field('big_first_letter') == true) echo ' bigFirst'; ?>">
                <?php
                $content = apply_filters('the_content', $post->post_content);
                echo $content;
                ?>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  <?php
  endwhile;
endif;
?>
</div>
<?php get_footer(); ?>
