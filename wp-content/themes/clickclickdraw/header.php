<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width" />
  <?php wp_head(); ?>
  <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>" />
  <link rel="shortcut icon" href="<?php echo get_site_url(); ?>/favicon.ico" type="image/x-icon" />
  <link rel="icon" href="<?php echo get_site_url(); ?>/favicon.ico" type="image/x-icon" />
  <script src="<?php echo get_template_directory_uri(); ?>/js/typekit_cache.js"></script>
  <script src="https://use.typekit.net/cpj5imj.js"></script>
  <script>try{Typekit.load({ async: true });}catch(e){}</script>
</head>
<body <?php body_class(); ?>>
  <nav id="mainNav">
    <?php wp_nav_menu(); ?>
  </nav>
