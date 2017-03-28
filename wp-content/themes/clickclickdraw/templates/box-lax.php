<?php
/**
 * Template Name: Box Lax 101
 *
 * @package WordPress
 * @subpackage National Lacrosse League

 * @since National Lacrosse League 0.0.1
 */
get_header();

//Get featured image
$thumb_id = get_post_thumbnail_id($post->ID);
$thumb_array = wp_get_attachment_image_src($thumb_id, 'large', true);
$postImage = $thumb_array[0];

//Convert names to URL-safe strings for direct term links
function format_uri( $string, $separator = '-' ){
    $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
    $special_cases = array( '&' => 'and', "'" => '');
    $string = mb_strtolower( trim( $string ), 'UTF-8' );
    $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
    $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
    $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
    $string = preg_replace("/[$separator]+/u", "$separator", $string);
    return $string;
}
?>
<div class="single-article about-page">
  <div class="story-content-container">
     <!--Article Featured Image-->
      <div class="header-image boxlax">
        <img src="<?php echo $postImage; ?>" />
      </div>
      <div class="story-editorial-copy-container">
        <!--Article Header-->
        <div class="title-container">
          <h2><?php the_title(); ?></h2>
        </div>
        <div class="story-subhead">
          <p><?php the_field('subheader'); ?></p>
        </div>

        <div class="about-content">
          <!--Article Content-->
          <div class="about-sidebar w30">
            <div class="about-nav-bar">
              <form class="filterSearch">
                <input type="text" placeholder="Search the rules...">
                <input type="submit" value="">
              </form>
              <div class="about-nav">
                <div class="about-nav-section">
                  <div class="chapter">1</div>
                  <div class="title">
                    <a href="#overview">
                      Overview
                      <p><?php the_field('overview_blurb'); ?></p>
                    </a>
                  </div>
                </div>
                <?php
                $i = 1;
                if(have_rows('section')):
                  while(have_rows('section')): the_row();
                    $i++;
                    echo '<div class="about-nav-section">'; ?>
                      <div class="chapter"><?php echo $i; ?></div>
                      <div class="title">
                        <a href="#<?php echo format_uri(get_sub_field('section_title')); ?>">
                          <?php the_sub_field('section_title'); ?>
                          <p><?php the_sub_field('section_blurb'); ?></p>
                        </a>
                      </div>
                      <?php
                      if(count(get_sub_field('subsection')) > 0):
                        if(have_rows('subsection')):
                          echo '<ul>';
                          while(have_rows('subsection')): the_row();
                            if(have_rows('term')):
                              while(have_rows('term')): the_row();
                                echo '<li><a href="#'.format_uri(get_sub_field('term_title')).'">'.get_sub_field('term_title').'</a></li>';
                              endwhile;
                            endif;
                          endwhile;
                          echo '</ul>';
                        endif;
                      endif;
                      ?>
                    <?php echo '</div>';
                  endwhile;
                endif; ?>
              </div>
            </div>
          </div>
          <div class="about-text w70">
            <div id="overview" class="about-section">
              <?php
              while(have_posts()): the_post();
                the_content();
              endwhile; ?>
            </div>
            <?php
            if(have_rows('section')):
              while(have_rows('section')): the_row(); ?>
                <div id="<?php echo format_uri(get_sub_field('section_title')); ?>" class="about-section">
                  <?php
                  if(get_sub_field('section_title')) echo '<h2>'.get_sub_field('section_title').'</h2>';
                  if(have_rows('subsection')):
                    while(have_rows('subsection')): the_row();
                      if(have_rows('videos')):
                        echo '<div class="about-related-videos">';
                        while(have_rows('videos')): the_row(); ?>
                        <div class="about-video w25" data-video="<?php the_sub_field('video_source'); ?>">
                          <div class="textWrap">
                            <span>Box Lax 101</span>
                            <p><?php the_sub_field('video_title'); ?></p>
                          </div>
                        </div>
                        <?php endwhile;
                        echo '</div>';
                      endif;
                      if(have_rows('term')):
                        while(have_rows('term')): the_row();
                          echo '<p id="'.format_uri(get_sub_field('term_title')).'"><strong>'.get_sub_field('term_title').':</strong> '.get_sub_field('term_definition').'</p>';
                        endwhile;
                      endif;
                    endwhile;
                  endif; ?>
                </div>
              <?php endwhile;
            endif; ?>
          </div>
        </div>
      </div>
  </div>
</div>
<?php get_footer(); ?>
