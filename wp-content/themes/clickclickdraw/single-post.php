<?php get_header(); ?>

<div class="single-article">
  <?php
  if(have_posts()):
  while(have_posts()): the_post();
    $thumb_id = get_post_thumbnail_id($post->ID);
    $thumb_array = wp_get_attachment_image_src($thumb_id, 'full', true);
    $postImage = $thumb_array[0];
    $categories = get_the_category(); ?>
    <div class="story-content-container">
      <div class="header-image<?php if(get_field('video_url')) echo ' video'?>" style="background-image:url(<?php if(getimagesize($postImage)[0] > 1100) echo $postImage; ?>)">
        <?php
        if(get_field('video_url')):
          echo '<iframe src="'.get_field('video_url').'" frameborder="0" allowfullscreen></iframe>';
        endif; ?>
        <?php if(get_field('featured_image_credit')):
          echo '<div class="photo-credit">'.get_field('featured_image_credit').'</div>';
        endif; ?>
      </div>
      <div class="story-editorial-copy-container">
        <!--Article Header-->
        <div class="title-container">
          <div class="tag-container">
            <div class="story-tag">
              <a href="<?php echo get_category_link($categories[0]->term_id); ?>"><?php echo $categories[0]->name; ?></a>
            </div>
          </div>
          <h2><?php the_title(); ?></h2>
          <?php if(get_field('sponsored') == 1): ?>
          <div class="sponsoredPost">
            <p>Presented by </p><a href="<?php echo get_field('sponsor_link'); ?>" target="_blank"><img src="<?php echo get_field('sponsor_logo'); ?>" alt="<?php echo get_field('sponsor'); ?>" /></a>
          </div>
          <?php endif; ?>
        </div>
        <div class="author-date-sharing">
          <span class="author">
            <?php if(get_field('authors')) the_field('authors'); ?>
          </span>
          <span class="published-date"><?php echo get_the_date('m.d.Y'); ?></span>
          <div class="social-icons">
            <a class="facebook-share" target="_blank" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title(); ?>"></a>
            <a class="twitter-share" target="_blank" href="http://twitter.com/home/?status=<?php the_title(); ?> - <?php the_permalink(); ?>"></a>
            <a class="reddit-share" target="_blank" href="http://www.reddit.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>"></a>
            <a class="email-share" href="mailto:?subject=<?php the_title(); ?>&body=<?php the_permalink(); ?>"></a>
            <a class="print" onclick="print();"></a>
          </div>
        </div>
        <div class="story-subhead">
          <?php if(get_field('subheader')): ?>
            <p><?php the_field('subheader'); ?></p>
          <?php endif; ?>
        </div>
        <!--Article body-->
        <div class="content-wrap">
          <div class="nll-bar sidebar-left w70">
            <div class="article-content">
              <div class="story-description list-component-body<?php if(get_field('big_first_letter') == true) echo ' bigFirst'; ?>">
                <?php
                $content = apply_filters('the_content', $post->post_content);
                echo $content;
                if( have_rows('transactions') ):
                  while ( have_rows('transactions') ) : the_row(); ?>
                  <div class="transaction">
                  <h3 class="transaction-date"><?php echo the_sub_field('transaction_date'); ?></h3>
                  <?php echo the_sub_field('transactions'); ?>
                  </div>
                  <?php
                  endwhile;
                endif;
                ?>
              </div>
              <!--Story footer-->
            </div>
          </div>
          <div class="nll-bar sidebar-right w30">
            <?php include get_template_directory().'/inc/master-sidebar.php'; ?>
          </div>
          <div class="article-footer">

            <!--Social stuff-->
            <div class="article-footer-left">
              <span class="label">Share</span>
              <div class="social-icons">
                <a class="facebook-share" target="_blank" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title(); ?>"></a>
                <a class="twitter-share" target="_blank" href="http://twitter.com/home/?status=<?php the_title(); ?> - <?php the_permalink(); ?>"></a>
                <a class="reddit-share" target="_blank" href="http://www.reddit.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>"></a>
                <a class="email-share" href="mailto:?subject=<?php the_title(); ?>&body=<?php the_permalink(); ?>"></a>
                <a class="print" onclick="print();"></a>
              </div>
            </div>

            <!--Tags-->
            <div class="article-footer-right">
              <div class="tag-container">
                <?php
                //Get all teams & tags assigned to post
                $myTeams = wp_get_post_terms($post->ID, 'teams');
                $myTags = wp_get_post_tags($post->ID);

                //Empty variables for term links
                $myTerms = array();
                $tagSpot = 0;

                //Get names and slugs of tags & custom taxonomies for setting up tag links
                if(isset($myTeams[0])){
                  foreach($myTeams as $val){
                    $myTerms[$tagSpot] = $val;
                    $myTerms[$tagSpot]->slug = 'team/'.$myTerms[$tagSpot]->slug;
                    $myTerms[$tagSpot]->name = $myTerms[$tagSpot]->description;
                    $tagSpot++;
                  }
                }
                if(isset($myTags[0])){
                  foreach($myTags as $val){
                    $myTerms[$tagSpot] = $val;
                    $myTerms[$tagSpot]->slug = 'tag/'.$myTerms[$tagSpot]->slug;
                    $tagSpot++;
                  }
                }
                if($tagSpot > 0) echo '<span class="label">Tags</span>';

                //Output tag linkage
                for($i = 0; $i < $tagSpot; $i++){
                  echo '<a href="'.get_site_url().'/news/'.$myTerms[$i]->slug.'">'.$myTerms[$i]->name.'</a>';
                  if($i < $tagSpot - 1) echo ', ';
                }

                ?>
              </div>
            </div>
          </div>

          <!--Related-->
          <?php
          $args = array(
            'category__in' => wp_get_post_categories($post->ID),
            'post__not_in' => array('sticky', $post->ID),
            'posts_per_page' => 4,
            'orderby' => 'date'
          );
          $myStickyPosts = get_posts($args);
          if($myStickyPosts): ?>
          <div class="post-thumbnail-container">
            <div class="more-like-text">Related Articles:</div>
            <div class="more-like">
              <?php
              foreach($myStickyPosts as $story):
                include get_template_directory().'/inc/tiles/news-tile.php';
              endforeach;
              ?>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php if(!$myStickyPosts):
     echo '<div class="noRelatedContent"></div>';
    endif; ?>
  <?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>
