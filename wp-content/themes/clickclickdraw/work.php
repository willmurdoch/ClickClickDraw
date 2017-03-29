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
   <?php $args = array(
     'post_type' => 'work'
   );
   $myWork = get_posts($args);
   foreach($myWork as $work):
     $category = get_the_category($work->ID);
     $featuredImage = wp_get_attachment_url(get_post_thumbnail_id($work->ID));
     echo '<a href="'.get_the_permalink($work->ID).'" style="background-image:url('.$featuredImage.');">'; ?>
      <span class="text-overlay">
        <p class="header"><?php echo $work->post_title; ?></p>
        <p class="category"><?php echo $category[0]->name; ?></p>
      </span>
     <?php echo '</a>';
   endforeach;

   include 'inc/contact.php'; ?>
 </div>
 <?php get_footer(); ?>
