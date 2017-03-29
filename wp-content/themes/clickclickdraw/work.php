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
     print_r($work);
     echo $work->post_title;
     echo '<a href="'.get_the_permalink($work->ID).'">Link</a>';
   endforeach;

   include 'inc/contact.php'; ?>
 </div>
 <?php get_footer(); ?>
