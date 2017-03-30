<?php
/**
 * Template Name: Work
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
     <div class="hero"><img src="<?php echo $featuredImage; ?>" alt="" /></div>
     <div class="scaler">
       <header>
         <h1 class="title work"><span>Work</span></h1>
       </header>
       <div class="work-grid">
         <?php $args = array(
           'post_type' => 'work',
           'posts_per_page' => -1
         );
         $myWork = get_posts($args);
         foreach($myWork as $work):
           $category = get_the_category($work->ID);
           $i = 0;
           if(get_field('tile_image', $work->ID)):
             $featuredImage = get_field('tile_image', $work->ID);
           elseif(have_rows('gallery', $work->ID)):
             while(have_rows('gallery', $work->ID)): the_row();
              if($i == 0){
                $featuredImage = get_sub_field('image');
                $i++;
              }
              else break;
             endwhile;
           endif;
           echo '<a class="work-tile" href="'.get_the_permalink($work->ID).'">'; ?>
            <img src="<?php echo $featuredImage; ?>" alt="" />
            <span class="text-overlay">
              <span class="text-center">
                <span class="icon <?php echo slugify($category[0]->name); ?>"></span>
                <span class="text">
                  <p class="header"><?php echo $work->post_title; ?></p>
                  <p class="category"><?php echo $category[0]->name; ?></p>
                </span>
              </span>
            </span>
           <?php echo '</a>';
         endforeach; ?>
       </div>
     </div>
  <?php endwhile;
  endif;

  include 'inc/contact.php'; ?>
 </div>
 <?php get_footer(); ?>
