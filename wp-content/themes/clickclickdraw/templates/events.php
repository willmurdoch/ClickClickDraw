<?php
/**
 * Template Name: Events
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
          <div class="nll-bar sidebar-left w70">
            <div class="title-container large">
              <h2><?php the_title(); ?></h2>
            </div>
            <div class="article-content event-listing">
              <div class="event-blurb">
                <?php the_content(); ?>
              </div>
              <div class="event-grid">
                <?php
                $args = array(
                  'posts_per_page' => -1,
                  'post_type' => 'event',
                  'orderby' => 'meta_value',
                  'meta_key' => 'event_date',
                  'order' => 'ASC'
                );
                $events = get_posts($args);
                foreach($events as $event){
                  $eventDate = get_field('event_date', $event->ID);
                  $eventMonth = explode(' ', $eventDate)[0];
                  $eventDay = str_replace(',', '', explode(' ', $eventDate)[1]);
                  echo '<a class="event-tile" href="'.get_the_permalink($event->ID).'">';
                  echo '<span class="month">'.$eventMonth.'</span>';
                  echo '<span class="day">'.$eventDay.'</span>';
                  echo '<h3>'.$event->post_title.'</h3>';
                  echo '<p>'.get_field('event_location', $event->ID).'</p>';
                  echo '</a>';
                }

                ?>
              </div>
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
