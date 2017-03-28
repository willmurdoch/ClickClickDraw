<?php
/**
 * Template Name: Home
 *
 * @package WordPress
 * @subpackage National Lacrosse League

 * @since National Lacrosse League 0.0.1
 */
get_header(); ?>
<div class="home-slides">
  <div class="slide-wrap">
  <?php
  if(have_rows('slides')):
    $i = 0;
    while(have_rows('slides')): the_row();
      $i++;
      //CTA properties
      $isButton = get_sub_field('enable_button');
      $buttonType = get_sub_field('button_type');
      $buttonText = get_sub_field('button_text');
      $buttonLink = get_sub_field('button_link');

      //Common between all formats
      $sponsor = get_sub_field('sponsor');
      $sponsorLink = get_sub_field('sponsor_link');
      $gameDate = get_sub_field('game_date');
      $gameLocation = get_sub_field('game_location');
      $homeTeam = get_sub_field('home_team');
      $awayTeam = get_sub_field('away_team');

      //Rivalry header
      if(get_sub_field('slide_type') == 'Rivalry'):
        $homeImage = get_sub_field('home_background');
        $awayImage = get_sub_field('away_background');
        //Pass everything off to template file
        include get_template_directory().'/inc/heroes/rivalry.php';
      elseif(get_sub_field('slide_type') == 'Today'):
        $background = get_sub_field('background_today');
        //Pass everything off to template file
        include get_template_directory().'/inc/heroes/today.php';
      else:
        $background = get_sub_field('background_today');
        include get_template_directory().'/inc/heroes/custom.php';
      endif;
    endwhile;
  endif;
  ?>
  </div>
  <div class="slide-nav">
    <?php
    if(have_rows('slides')):
      if($i > 1):
        $x = 0;
        while(have_rows('slides')): the_row();
          $x++;
          if($x == 1) $active = ' class="current"';
          else $active = '';
          echo '<div'.$active.'></div>';
        endwhile;
      endif;
    endif;
    ?>
  </div>
  <?php
  $leagueOpts = get_option('league_options');
  if($leagueOpts['enable_standings'] == 1): ?>
  <div class="standings-drawer">
    <div class="collapsed-drawer">
      <div class="expand-icon"></div>
      <p>Team Standings</p>
    </div>
    <?php include get_template_directory().'/inc/standings.php'; ?>
  </div>
  <?php endif; ?>
</div>

<div class="nllWrap">
  <?php include get_template_directory().'/inc/schedule-slider.php'; ?>
  <div class="story-and-feed-container">
    <?php
    $newsArgs = array(
      'numberposts' => 12
    );
    $myStories = get_posts($newsArgs);
    $promoArgs = array(
      'post_type' => 'promotion'
    );
    $promoData = get_posts($promoArgs);
    $adArgs = array(
      'post_type' => 'advertisement'
    );
    $adData = get_posts($adArgs);
    ?>
    <div class="news-item-container">
      <?php
      $tileCount = 0; $myPromos = []; $promoCount = 0;
      foreach($promoData as $promo){
        $promoPages = get_field('pages', $promo->ID);
        if(is_array($promoPages)){
          if(implode(',', $promoPages) == get_permalink()){
            $promoHere = true;
          }
        }
        else $promoHere = false;
        if($promoHere == true){
          $myPromos[$promoCount]['pos'] = get_field('order', $promo->ID);
          $myPromos[$promoCount]['width'] = get_field('width', $promo->ID);
          $myPromos[$promoCount]['type'] = get_field('type', $promo->ID);
          $myPromos[$promoCount]['id'] = $promo->ID;
          if(get_field('url', $promo->ID) != '') $myPromos[$promoCount]['link'] = get_field('url', $promo->ID);
          $myPromos[$promoCount]['image'] = get_field('image', $promo->ID);
          $myPromos[$promoCount]['color'] = get_field('background_fill', $promo->ID);
          $promoCount++;
        }
      }
      $myAds = []; $adCount = 0; $adHere = false;
      foreach($adData as $ad){
        $adPages = get_field('pages', $ad->ID);
        if(is_array($adPages)){
          foreach($adPages as $page){
            if($page == get_permalink()){
              $adHere = true;
            }
          }
        }
        else if($adPages == get_permalink()){
          $adHere = true;
        }
        if($adHere == true){
          $myAds[$adCount]['pos'] = get_field('order', $ad->ID);
          $myAds[$adCount]['width'] = get_field('ad_width', $ad->ID);
          $myAds[$adCount]['height'] = get_field('ad_height', $ad->ID);
          if(get_field('ad_url', $ad->ID) != ''){
            $myAds[$adCount]['src'] = get_field('ad_url', $ad->ID);
          }
          else $myAds[$adCount]['code'] = get_field('ad_code', $ad->ID);
          $adCount++;
        }
      }
      $adCount = 0; $promoCount = 0;
      foreach($myStories as $story){
        foreach($myPromos as $promo){
          if($promo['pos'] == $tileCount - $adCount || $promo['pos'] == $tileCount){
            echo '<div class="promo" style="background-color:'.$promo['color'].'" data-width="'.$promo['width'].'">';
            if(isset($promo['link'])) echo '<a href="'.$promo['link'].'"></a>';
            echo '<img src="'.$promo['image'].'" alt="" />';
            echo '</div>';
            $promoCount++;
            if($promo['width'] == '25%') $tileCount++;
            elseif($promo['width'] == '50%') $tileCount += 2;
          }
          else break;
        }
        foreach($myAds as $ad){
          if($ad['pos'] == $tileCount - $promoCount || $ad['pos'] == $tileCount){
            if(isset($ad['src'])){
              echo '<div class="thumbnail-outer-container ad"><iframe class="adFrame" src="'.$ad['src'].'" width="'.$ad['width'].'" height="'.$ad['height'].'" scrolling="no" frameborder="0"></iframe></div>';
            }
            else{
              echo '<div class="thumbnail-outer-container ad">'.$ad['code'].'</div>';
            }
            $adCount++; $tileCount++;
          }
          else break;
        }
        if($tileCount < 12){
          include get_template_directory().'/inc/tiles/news-tile.php';
          $tileCount++;
        }
        else break;
      }
      ?>
      <br><a class="loadMore" href="news">More News <span>❯</span></a>
    </div>
  </div>
</div>

<?php get_footer(); ?>
