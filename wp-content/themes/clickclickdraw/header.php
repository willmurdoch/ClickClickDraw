<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width" />
  <?php wp_head(); ?>
  <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>" />
  <!--[if lte IE 9]>
  <link rel="stylesheet" type="text/css" href="<?php echo get_site_url().'/wp-content/themes/nll/styles/ie.css'; ?>" />
  <![endif]-->
  <link rel="shortcut icon" href="<?php echo get_site_url(); ?>/favicon.ico" type="image/x-icon" />
  <link rel="icon" href="<?php echo get_site_url(); ?>/favicon.ico" type="image/x-icon" />
  <?php
  if(is_single()){
    $post_id = get_post_thumbnail_id($post->ID);
    $image_array = wp_get_attachment_image_src($post_id, 'thumbnail', true);
    $postImage = $image_array[0];
    if(strpos($postImage, 'media/default.png') !== false){
      $postImage = get_site_url().'/wp-content/uploads/453814_raw.jpg';
    }
  }
  if(isset($postImage) && $postImage != '' && is_single()): ?>
    <meta property="og:image" content="<?php echo $postImage; ?>" />
    <link rel="image_src" href="<?php echo $postImage; ?>" />
  <?php endif; ?>
  <script src="<?php echo get_template_directory_uri(); ?>/js/typekit_cache.js"></script>
  <script src="https://use.typekit.net/bws7uny.js"></script>
  <script>try{Typekit.load({ async: true });}catch(e){}</script>
</head>
<body <?php body_class(); ?>>
  <!--[if lte IE 9]>
    <div id="oldIEPopup">
      <div class="ieWrap">
        <p>Your browser is no longer supported. Please upgrade to <a href="https://www.google.com/chrome/browser/desktop/index.html">Google Chrome</a>, <a href="https://www.mozilla.org/en-US/firefox/new/?utm_medium=referral&utm_source=firefox-com">Mozilla Firefox</a>, or <a href="https://www.microsoft.com/en-us/download/internet-explorer.aspx">Internet Explorer 11</a>.</p>
      </div>
    </div>
  <![endif]-->
  <?php
  //Get team logos from team pages to populate top bar
  $args = array(
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'asc',
    'post_type' => 'team'
  );
  $teams = get_posts($args);
  $headerTeams = array();
  $x = 0;
  foreach($teams as $team){
    $myID = $team->ID;
    $headerTeams[$x]['name'] = get_the_title($myID);
    $headerTeams[$x]['logo'] = get_field('logo', $myID);
    $imageSize = 'logo-thumb';
    $headerTeams[$x]['logo'] = wp_get_attachment_image_src($headerTeams[$x]['logo'], $imageSize);
    $headerTeams[$x]['logo'] = $headerTeams[$x]['logo'][0];
    $headerTeams[$x]['url'] = get_the_permalink($myID);
    $x++;
  }
  $leftCount = ceil(count($headerTeams)/2);
  $rightCount = floor(count($headerTeams)/2);

  //Get nav buttons
  $leftNav = wp_get_nav_menu_items('Header Left Nav');
  $rightNav = wp_get_nav_menu_items('Header Right Nav');
  $mobileNav = wp_get_nav_menu_items('Mobile Nav');
  $socialNav = wp_get_nav_menu_items('Social Media');
  ?>
  <div class="navbar">
    <div class="desktop-nav">
  		<div class="top-bar">
  		  <div class="topBarWrap">
    			<div class="logoWrap left">
            <?php
            for($i = 0; $i < $leftCount; $i++): ?>
              <a href="<?php echo $headerTeams[$i]['url']; ?>" class="logo<?php if($headerTeams[$i]['url'] == get_permalink() && is_singular('team')) echo ' current'; ?>">
                <img src="<?php echo $headerTeams[$i]['logo']; ?>" alt="<?php echo $headerTeams[$i]['name']; ?>">
      			  </a>
            <?php endfor; ?>
    			</div>
    			<div class="logoWrap right">
            <?php for($i = 0; $i < $rightCount; $i++): ?>
              <a href="<?php echo $headerTeams[$i + $leftCount]['url']; ?>" class="logo<?php if($headerTeams[$i + $leftCount]['url'] == get_permalink() && is_singular('team')) echo ' current'; ?>">
                <img src="<?php echo $headerTeams[$i + $leftCount]['logo']; ?>" alt="<?php echo $headerTeams[$i + $leftCount]['name']; ?>">
      			  </a>
            <?php endfor; ?>
    			  <div class="social">
              <?php
              foreach($socialNav as $socialBtn):
                echo '<a href="'.$socialBtn->url.'" target="_blank"></a>';
              endforeach; ?>
    			  </div>
    			</div>
  		  </div>
  		</div>

      <?php
      if(0 == $post->post_parent){
        $activePage = get_the_title();
      }
      else{
        $parents = get_post_ancestors($post->ID);
        $activePage = apply_filters('the_title', get_the_title(end($parents)));
      }
      ?>
  		<div class="main-nav-bar">
        <nav>
          <div class="nav-left">
            <?php foreach($leftNav as $navItem){
              if(is_home() || is_singular('post') || is_archive() || is_search()){
                $activePage = 'News';
              }
              if($navItem->title == $activePage){
                $activeClass = ' class="active-page"';
              }
              else $activeClass = '';
              if(strpos($navItem->url, site_url()) === false || strpos($navItem->url, '.pdf') !== false){
                $target = ' target="_blank"';
              }
              else $target = '';
              if($navItem->menu_item_parent == 0){
                echo '<div class="item-wrap"><a href="'.$navItem->url.'"'.$activeClass.$target.' data-level="parent"><span>'.$navItem->title.'</span></a>';
                $args = array(
                  'menu' => 'Header Left Nav',
                  'submenu' => $navItem->title
                );
                wp_nav_menu($args);
                echo '</div>';
              }
            }?>
          </div>
          <a href="<?php echo get_site_url(); ?>" class="main-logo"><img src="<?php
          if(get_field('shield') && is_singular('team')):
            echo get_field('shield');
          else:
            echo get_template_directory_uri().'/assets/header/nll_logo.png';
          endif;
          ?>" alt="National Lacrosse League" /></a>
          <div class="nav-right">
            <?php foreach($rightNav as $navItem){
              if($navItem->title == $activePage){
                $activeClass = ' class="active-page"';
              }
              else $activeClass = '';
              if(strpos($navItem->url, site_url()) === false || strpos($navItem->url, '.pdf') !== false){
                $target = ' target="_blank"';
              }
              else $target = '';
              if($navItem->menu_item_parent == 0){
                echo '<div class="item-wrap"><a href="'.$navItem->url.'"'.$activeClass.$target.' data-level="parent"><span>'.$navItem->title.'</span></a>';
                $args = array(
                  'menu' => 'Header Right Nav',
                  'submenu' => $navItem->title
                );
                wp_nav_menu($args);
                echo '</div>';
              }
            }?>
            <div class="search">
        			<div class="navi-search">
        			  <div class="search-close"></div>
        			</div>
      		  </div>
          </div>
        </nav>
  		  <!--<div ng-include src="'assets/templates/navbar/_menu_dropdown.html'"></div>-->
  		  <div class='navbar-container' id="nav_container">
  			<div class="slide-nav-container">
  			  <div class="slide-nav">
  				<div class="slide-panel"></div>
  			  </div>
  			</div>
  		  </div>
  		</div>
    </div>

    <div class="mobile-nav">
  	<div class="mobile-burger">
  	  <div class="button">
  		<span></span>
  		<span></span>
  		<span></span>
  	  </div>
  	  <p>MENU</p>
  	</div>
  	<a href="<?php echo get_site_url(); ?>" class="mobile-logo"><img src="<?php echo get_template_directory_uri(); ?>/assets/header/nll_logo.png" alt="National Lacrosse League" /></a>
  	<div class="navi-search mobile-search">
  	  <div class="search-close"></div>
  	</div>
  	<div class="mobile-menu">
  	  <div class="mobile-menu-wrap">
  		<div class="mobile-close">
  		  <span class="close-btn">×</span>
  		  <p>CLOSE</p>
  		</div>
      <?php foreach($mobileNav as $navItem){
        if(is_home() || is_single() || is_archive() || is_search()){
          $activePage = 'News';
        }
        if($navItem->title == $activePage){
          $activeClass = ' class="active-page"';
        }
        else{
          $activeClass = '';
        }
        if($navItem->menu_item_parent == 0){
          echo '<div class="item-wrap"><a href="'.$navItem->url.'"'.$activeClass.$target.' data-level="parent"><span>'.$navItem->title.'</span></a>';
          $args = array(
            'menu' => 'Mobile Nav',
            'submenu' => $navItem->title
          );
          wp_nav_menu($args);
          echo '</div>';
        }
      }?>
  	  </div>
  	</div>
    </div>
  </div>
  <div class="topBorder"></div>
  <div class="search-bar-main-container closed">
  	<div class="search-backing">
  	  <div class="search-bar-inner-container">
        <div class="searchfield">
          <div class="search-topbar">
            <div class="search-input">
              <form role="search" method="get" id="searchform_global" class="searchform" action="<?php echo get_site_url(); ?>">
          			<input type="text" value="" name="s" id="s_global" placeholder="Search here...">
                <input type="hidden" name="post_type" value="any">
          			<input class="search-icon" type="submit" id="searchsubmit" value="">
          		</form>
            </div>
          </div>
        </div>
  	  </div>
      <div class="search-content">

      </div>
  	</div>
  </div>
  <div class="popup newsletter">
    <div class="popup-wrap">
      <div class="popup-close">&times;</div>
      <div class="popup-content"><?php echo do_shortcode('[contact-form-7 id="11113" title="Newsletter Signup"]'); ?></div>
    </div>
  </div>
  <div class="popup flexible">
    <div class="popup-wrap">
      <div class="popup-close">&times;</div>
      <div class="popup-content"></div>
    </div>
  </div>
  <div class="content">
