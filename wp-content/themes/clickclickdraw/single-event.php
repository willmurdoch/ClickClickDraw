<?php
function slugify($text){
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  $text = preg_replace('~[^-\w]+~', '', $text);
  $text = trim($text, '-');
  $text = preg_replace('~-+~', '-', $text);
  $text = strtolower($text);
  if (empty($text)) {
    return 'n-a';
  }
  return $text;
}
get_header(); ?>
<div class="single-article page event-page">
  <?php include get_template_directory().'/inc/schedule-slider.php'; ?>
  <?php
  if(have_posts()):
  while(have_posts()): the_post();
    $thumb_id = get_post_thumbnail_id($post->ID);
    $thumb_array = wp_get_attachment_image_src($thumb_id, 'full', true);
    if(isset($thumb_array[3])) $postImage = $thumb_array[0];
    else unset($postImage);
    ?>
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
                <div class="event-dets">
                  <?php if(get_field('event_address')): ?>
                    <a class="location" href="https://www.google.com/maps/dir//<?php echo slugify(get_field('event_address')); ?>" target="_blank"><?php the_field('event_location'); ?></a><span>|</span>
                  <?php else: ?>
                    <p class="location"><?php the_field('event_location'); ?></p><span>|</span>
                  <?php endif; ?>
                  <h3 class="date"><?php the_field('event_date'); ?></h3>
                </div>
                <?php if(isset($postImage)) echo '<img class="eventImage" src="'.$postImage.'" alt="" />'; ?>
                <?php the_content(); ?>
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
