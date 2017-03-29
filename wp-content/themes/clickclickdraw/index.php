<?php
get_header();

$args = array(
  'post_type' => 'post'
);
$myWork = get_posts($args);
foreach($myWork as $work):
  print_r($work);
  echo $work->post_title;
  echo '<a href="'.get_the_permalink($work->ID).'">Link</a>';
endforeach;

include 'inc/contact.php';
get_footer();
?>
