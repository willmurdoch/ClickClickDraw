<?php
include 'inc/pointstreak/globals.php';
$seasonCurrentYear = $season;
$selectedTeam = get_the_title();
$selectedTeamId = get_field('pointstreak_id');

//Retrieve Team info
$thisTeamStanding = file_get_contents(get_template_directory_uri().'/inc/pointstreak/json/standings/standings'.$seasonCurrentYear.'.json');
$tts_json = json_decode($thisTeamStanding,true);
$tts_div = $tts_json['teams'];
$tts_team = null;
$x = 0;
foreach($tts_div as $division){
  $leagueDivision = $division;
  foreach($division as $team){
    if($team['teamid'] == $selectedTeamId){
      $myDivision = $leagueDivision;
      $tts_team = $team;
      break;
    }
  }
  $x++;
}
if(isset($myDivision)){
  $rank = 1;
  foreach($myDivision as $team){
    if($tts_team['rank'] > $team['rank']){
      $rank++;
    }
  }
}
else{
  $myDivision = 'Inactive';
  $tts_team['division']['divisionname'] = '';
  $rank = '-';
  $tts_team['teamname'] = '-';
  $tts_team['goalsfor'] = '-';
  $tts_team['goalsagainst'] = '-';
  $tts_team['mascot'] = '-';
  $tts_team['wins'] = '0';
  $tts_team['losses'] = '0';
}


?>
<?php get_header();

$staffMembers = 0;
if(have_rows('staff_members')):
  while(have_rows('staff_members')): the_row();
    $staffMembers++;
  endwhile;
endif;
?>
<div class="players feed">
  <div class="schedule">
    <?php include get_template_directory().'/inc/schedule-slider.php'; ?>
  </div>

  <div class="scaler">
    <div class="content-wrap">
      <div class="nll-bar sidebar-left w70">
        <!--Team-->
        <div class="team-wrap">
          <div class="team-details" style="border-color:<?php echo get_field('color'); ?>">

            <div class="team-logo">
              <?php
              $myLogo = get_field('logo');
              $imageSize = 'thumbnail';
              $logoSrc = wp_get_attachment_image_src($myLogo);
              $logoSrc = $logoSrc[0];
              ?>
              <img src="<?php echo $logoSrc; ?>" alt="<?php echo get_the_title(); ?>" />
            </div>

            <div class="team-info">
              <div class="team-header">
                <h3><?php the_field('city'); ?></h3>
                <h2><?php the_field('mascot'); ?></h2>
                <?php if(strpos($tts_team['division']['divisionname'], 'Playoff') !== false): ?>
                <p>Playoffs</p>
                <?php else: ?>
                <p><?php echo $tts_team['division']['divisionname']; ?></p>
                <?php endif; ?>
                <p><?php echo $tts_team['wins']."-".$tts_team['losses']; ?><span>|</span><a href="<?php echo site_url(); ?>/standings" class="tdi-infolinks">Full Standings &gt;</a></p>
              </div>

              <div class="team-links">
                <?php if(get_field('website') != ''): ?>
                <a href="<?php the_field('website'); ?>" target="_blank">Website</a>
                <?php endif;if(get_field('tickets') != '' && $tts_team['division']['divisionname'] != ''): ?>
                <a href="<?php the_field('tickets'); ?>" target="_blank">Buy Tickets</a>
                <?php endif;if(get_field('shop') != ''): ?>
                <a href="<?php the_field('shop'); ?>" target="_blank">Shop</a>
                <?php endif; ?>
              </div>

              <div class="team-social">
                <?php if(get_field('instagram') != ''): ?>
                  <a class="instagram" href="<?php echo get_field('instagram'); ?>" target="_blank"><span class="logo"></span> </a>
                <?php endif; ?>
                <?php if(get_field('twitter') != ''): ?>
                  <a class="twitter" href="<?php echo get_field('twitter'); ?>" target="_blank"><span class="logo"></span> </a>
                <?php endif; ?>
                <?php if(get_field('facebook') != ''): ?>
                  <a class="facebook" href="<?php echo get_field('facebook'); ?>" target="_blank"><span class="logo"></span> </a>
                <?php endif; ?>
                <?php if(get_field('youtube') != ''): ?>
                  <a class="youtube" href="<?php echo get_field('youtube'); ?>" target="_blank"><span class="logo"></span> </a>
                <?php endif; ?>
                <?php if(get_field('snapchat') != ''): ?>
                  <a class="snapchat" href="<?php echo get_field('snapchat'); ?>" target="_blank"><span class="logo"></span> </a>
                <?php endif; ?>
              </div>
            </div>

            <div class="team-stats">
              <div class="stat-block">
                <p class="stat-label" title="Goals For">GF</p>
                <p class="stat-value"><?php echo $tts_team['goalsfor']; ?></p>
              </div>
              <div class="stat-block">
                <p class="stat-label" title="Goals Against">GA</p>
                <p class="stat-value"><?php echo $tts_team['goalsagainst']; ?></p>
              </div>
              <div class="stat-block">
                <?php
                if(strpos($tts_team['division']['divisionname'], 'Division') !== false):
                    echo '<p class="stat-label" title="Division Rank">DIV RK</p>';
                else:
                  echo '<p class="stat-label">RANK</p>';
                endif;
                ?>
                <p class="stat-value"><?php echo $rank; ?></p>
              </div>
            </div>

          </div>

          <div class="team-feed-content">
            <div class="team-feed-filter">
              <ul>
                <li class="current" data-tab="news"><strong>News</strong></li>
                <?php if(get_field('pointstreak_id') != '' && $tts_team['division']['divisionname'] != ''): ?>
                <li data-tab="roster"><strong>Roster</strong></li>
                <li data-tab="schedule"><strong>Schedule</strong></li>
                <?php endif; ?>
                <?php if($staffMembers > 0): ?>
                <li data-tab="staff"><strong>Staff</strong></li>
                <?php endif; ?>
              </ul>
            </div>

            <div class="team-feed current" data-section="news">
              <div class="news-item-container">
                <?php
                $args = array(
                  'tax_query' => array(
                    array(
                      'taxonomy' => 'teams',
                      'field' => 'slug',
                      'terms' => $selectedTeam
                    )
                  )
                );
                $newsQuery = new WP_Query($args);
                if($newsQuery->have_posts()):
                  while ($newsQuery->have_posts()) : $newsQuery->the_post();
                    include get_template_directory().'/inc/tiles/news-block.php';
                  endwhile;
                else:
                  echo '<div class="no-results"><h3>No results found.</h3></div>';
                endif;
                wp_reset_postdata();
                ?>
              </div>
              <a class="btn gold" href="<?php echo get_site_url().'/news/team/'.$post->post_name;; ?>">See More News</a>
            </div>

            <?php if(get_field('pointstreak_id') != ''): ?>
            <div class="team-feed" data-section="roster">
              <div class="player-listing team-roster" data-team="<?php echo get_post_field('post_name', get_post()); ?>"></div>
            </div>

            <div class="team-feed" data-section="schedule">
              <div class="schedule-listing team-schedule" data-team="<?php echo $selectedTeamId; ?>"></div>
            </div>
            <?php endif; ?>

            <?php if($staffMembers > 0): ?>
            <div class="team-feed" data-section="staff">
              <div class="staff-listing" data-team="<?php echo $selectedTeamId; ?>">
                <?php if(have_rows('staff_members')):
                  while(have_rows('staff_members')): the_row();
                    echo '<div class="employee">';
                    echo '<div class="headshot" style="background-image:url('.get_sub_field('photo').');"></div>';
                    echo '<div class="staff-text"><p class="staff-name">'.get_sub_field('name').'</p><p>'.get_sub_field('position').'</p>';
                    echo '<p>'.get_the_title().'</p>';
                    echo '</div></div>';
                  endwhile;
                endif; ?>
              </div>
            </div>
            <?php endif ?>

          </div>
        </div>
      </div>
      <div class="nll-bar sidebar-right w30">
        <?php include get_template_directory().'/inc/master-sidebar.php'; ?>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>
