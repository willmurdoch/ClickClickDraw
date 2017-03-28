<?php
if(have_posts()):
  while (have_posts()): the_post();
    $myUrl = site_url().'/players/player?id='.get_field('player_id');
    wp_redirect($myUrl);
  endwhile;
endif;
?>
