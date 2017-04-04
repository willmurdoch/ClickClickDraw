<?php
/**
 * Template Name: Work
 *
 * @package WordPress
 * @subpackage Click Click Draw

 * @since Click Click Draw 2.0.0
 */
if(!isset($_GET['ajax']))
  get_header(); ?>
 <div id="wrapper" class="single-work">
 <?php
 if(have_posts()):
   while(have_posts()): the_post();
    if(!isset($_GET['ajax'])):
    $featuredImage = wp_get_attachment_url(get_post_thumbnail_id()); ?>
     <div class="hero"><img src="<?php echo $featuredImage; ?>" alt="" /></div>
     <div class="scaler">
       <header>
         <h1 class="title work"><span>Work</span></h1>
       </header>
       <div class="work-filters">
         <a <?php if(!isset($_GET['category'])): ?>class="active"<?php endif; ?> href="#" data-category="">All</a>
         <?php
         $filterParent = get_cat_id('filters');
         $args = array(
           'parent' => $filterParent
         );
         $myCats = get_categories($args);
         foreach($myCats as $cat){
           if(isset($_GET['category']) && $_GET['category'] == $cat->slug){
             echo '<a href="#" class="active" data-category="'.$cat->slug.'">'.$cat->name.'</a>';
           }
           else echo '<a href="#" data-category="'.$cat->slug.'">'.$cat->name.'</a>';
         }
         ?>
       </div>
      <?php endif; ?>
      <div class="work-wrap">
       <div class="work-grid">
         <?php
         if(isset($_GET['offset'])) $offset = $_GET['offset'];
         else $offset = 0;
         if(isset($_GET['category'])){
           $args = array(
             'post_type' => 'work',
             'posts_per_page' => 8,
             'offset' => $offset,
             'category_name' => $_GET['category']
           );
         }
         else $args = array(
           'post_type' => 'work',
           'posts_per_page' => 8,
           'offset' => $offset
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
           echo '<a class="work-tile" href="'.get_the_permalink($work->ID).'" style="background-image:url('.$featuredImage.')">'; ?>
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
    <?php if(!isset($_GET['ajax'])): ?>
      </div>
    </div>
   <?php endif;
   endwhile;
  endif;

  if(!isset($_GET['ajax'])) include 'inc/contact.php'; ?>
 </div>
 <?php
 if(!isset($_GET['ajax']))
  get_footer(); ?>
