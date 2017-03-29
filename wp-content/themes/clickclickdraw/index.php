<?php
get_header();

$args = array(
  'post_type' => 'post'
);
$myPosts = get_posts($args);
foreach($myPosts as $post):
  echo $post->post_content;
  echo '<a href="'.get_the_permalink($post->ID).'">Link</a>';
endforeach;

include 'inc/contact.php';
get_footer();
?>
